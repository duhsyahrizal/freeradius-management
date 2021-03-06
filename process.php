<?php
session_start();
require_once('./lib/routeros_api.class.php');
date_default_timezone_set('Asia/Jakarta');
include('./sql/connection.php');

  $action = $_GET['action'];
  $data = !isset($_GET['data'])?'':$_GET['data'];
  $timestamp = date('Y-m-d');
  if(isset($_SESSION['user'])){
    $user_login = $_SESSION['user']['username'];
  }

  if($action == 'login'){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember'])?$_POST['remember']:'';
    $token = openssl_random_pseudo_bytes(16);
    $sql = "SELECT * FROM bayhost_users INNER JOIN role_group ON role_group.role_group_id = bayhost_users.role WHERE `bayhost_users`.`username` = '".$username."' AND `bayhost_users`.`password` = '".$password."'";
    $time = time();
    $matching = $conn->query($sql);
    $useracc = $matching->fetch_assoc();
    $check = $matching->num_rows;
    $fullname = $useracc['fullname'];
    $role = $useracc['role_name'];
    $manage_user = $useracc['manage_user'];
    $manage_paket = $useracc['manage_package'];

    //Convert the binary data into hexadecimal representation.
    $token = bin2hex($token);

    if($check > 0){
      if($remember == true){
        setcookie("username", $username, $time + 60*60*24*30, '/');
        setcookie("password", $password, $time + 60*60*24*30, '/');
        setcookie("remember", 1, $time + 60*60*24*30, '/');
      }
      $user = ([
        "username" => $username,
        "fullname" => $fullname,
        "role" => $role,
        "manage_paket" => $manage_paket,
        "manage_user" => $manage_user,
        "token" => $token,
        "remember" => $remember,
      ]);
      $_SESSION['user'] = $user;

      $sql_insert = "INSERT INTO user_token (`username`, `token`, `modified_at`) VALUES ('".$username."', '".$token."', '".$timestamp."')";
      $sql_update = "UPDATE user_token SET token='".$token."', modified_at='".$timestamp."' WHERE username='".$username."'";
      
      $sql_check_token="SELECT * FROM user_token WHERE username='".$username."'";
      // Update user token 
      $result_token = $conn->query($sql_check_token);

      if($result_token->num_rows > 0){
        // Update token to table
        $conn->query($sql_update);
      }else{
        // Insert token to table
        $conn->query($sql_insert);
      }
      echo 'success';
    }else {
      echo 'failed';
    }
  }
  else if($action == 'logout'){
    unset($_SESSION['user']);
    unset($_SESSION['token']);
    session_destroy();
    header("Location:./login.php");
  }
  else if($data == 'user'){
    if($action == 'save'){
      $username = $_POST['username'];
      $fullname = $_POST['fullname'];
      $password = $_POST['password'];
      $role = $_POST['group'];

      $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Menambahkan User ".$username."', 'Akses Login', '".$user_login."', '".$timestamp."')";
      $conn->query($action_report);

      $sqlUser = "INSERT INTO bayhost_users (`fullname`, `username`, `password`, `role`) VALUES ('".$fullname."', '".$username."', '".$password."', '".$role."')";
      if($conn->query($sqlUser) === TRUE) {
        echo "success";
      } else {
        echo "failed";
      }

      header("Location:./admin.php?task=preference");
      
    }
    else if($action == 'edit'){
      $user_id = $_GET['user_id'];
      $sql = "SELECT 
      bayhost_users.bayhost_user_id,
      bayhost_users.fullname,
      bayhost_users.username,
      bayhost_users.password,
      role_group.role_name,
      role_group.manage_user,
      role_group.manage_package
      FROM bayhost_users
      INNER JOIN role_group ON role_group.role_group_id = bayhost_users.role
      WHERE bayhost_user_id = ".$user_id;
      $result = $conn->query($sql);
      $response = $result->fetch_assoc();
      echo json_encode($response);
    }
    else if($action == 'update'){
      $user_id = $_POST['user_id'];
      $fullname = $_POST['fullname'];
      $username = $_POST['username'];
      $password = $_POST['password'];
      $group = $_POST['group'];

      $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Mengupdate User ".$username."', 'Akses Login', '".$user_login."', '".$timestamp."')";
      $conn->query($action_report);

      $sqlUser = "UPDATE bayhost_users SET `fullname` = '".$fullname."', `username` = '".$username."', `password` = '".$password."', `role` = $group WHERE bayhost_user_id = $user_id";
      $result = $conn->query($sqlUser);
      if($result){
        $response = ([
          "status" => "success",
          "message" => "Berhasil merubah data user"
        ]);
        echo json_encode($response);
      }else{
        $response = ([
          "status" => "failed",
          "message" => $conn->error
        ]);
        echo json_encode($response);
      }
    }
    else if($action == 'delete'){
      $user_id = $_POST['user_id'];
      $queryuser = "SELECT username FROM bayhost_users WHERE bayhost_user_id = ".$user_id;
      $res = $conn->query($queryuser);
      $user = $res->fetch_assoc();
      $user = $user['username'];

      $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Menghapus User ".$user."', 'Akses Login', '".$user_login."', '".$timestamp."')";
      $conn->query($action_report);

      $sql = "DELETE FROM bayhost_users WHERE bayhost_user_id = $user_id";
      $result = $conn->query($sql);
      if($result){
        $response = ([
          "status" => "success",
          "message" => "Berhasil Menghapus data user"
        ]);
        echo json_encode($response);
      }else{
        echo 'failed';
      }
    }
  }
  else if($data == 'voucher'){
    if($action == 'save'){
      if($_POST != ''){
        $queryPrice = "SELECT price FROM billing_package WHERE id = ".$_POST['profile'];
        $resPrice = $conn->query($queryPrice);
        $price = $resPrice->fetch_assoc();
        $price = $price['price'];
        // set start & end time
        $time_indo = date('H:i:s');
        // get all post data
        $username = $_POST['username'];
        $password = $_POST['password'];
        $profile = $_POST['profile'];
        $groupname = $_POST['package_name'];
        $idletimeout = $_POST['idletimeout'];
        $type = $_POST['package_type'];
        $limit_upload = isset($_POST['upload']) ? $_POST['upload']*1024 : null;
        $limit_download = isset($_POST['download']) ? $_POST['download']*1024 : null;
        $shared_users = !isset($_POST['shared_users']) ? $_POST['shared_users'] : null;
        $shared_users_bill = !is_null($_POST['shared_users']) ? $_POST['shared_users'] : '2';

        $start_date = $_POST['start_date'];
        $start_from = date_create($start_date);
        $end_date = $_POST['end_date'];
        $end_until = date_create($end_date);
        $date_diff = date_diff($start_from, $end_until);
        $total_days = $date_diff->format("%a");
        $start_hour = $_POST['start_hour'];
        $expired = $end_date.' '.$start_hour;
        $start_date = $start_date.' '.$start_hour;

        $payment = $_POST['payment'];
        switch($_POST['payment']){
          case '1':
            $paymentName = 'Cash';
            $payment = 1;
            break;
          case '2':
            $paymentName = 'Gratis';
            $payment = 2;
            break;
          case '3':
            $paymentName = 'Transfer';
            $payment = 3;
            break;
          case '4':
            $paymentName = 'Hutang';
            $payment = 4;
            break;
        }
        $description = !isset($_POST['description'])?'':$_POST['description'];
        $expired_at = $end_date. ' ' . $time_indo;

        $fullname = isset($_POST['fullname'])?$_POST['fullname']:'';
        $date_of_birth = isset($_POST['date_of_birth'])?$_POST['date_of_birth']:'';
        $gender = isset($_POST['gender'])?$_POST['gender']:'';
        $board_name = isset($_POST['boarding_house_name'])?$_POST['boarding_house_name']:'';
        $telephone = !isset($_POST['telephone'])?'':$_POST['telephone'];

        $queryPassword = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Cleartext-Password', ':=', '".$password."')";
        // run query radcheck Password
        $conn->query($queryPassword);

        if(!empty($idletimeout)){
          $queryIdle = "INSERT INTO radreply (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Idle-Timeout', ':=', '".$idletimeout."')";
          // run query radreply Idle Timeout
          $conn->query($queryIdle);
        }

        // check billing type is volume or speed limit
        if($type == 'speed'){
          $queryGroup = "INSERT INTO radusergroup (`username`, `groupname`, `priority`) VALUES ('".$username."', '".$groupname."', 1)";
          $querySessTime = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Expiration', ':=', '".$expired."')";
          $querySimul = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Simultaneous-Use', ':=', '2')";
          
          // run query radcheck simultan
          $conn->query($querySimul);
          // run query radreply speed limit
          $conn->query($queryGroup);
          // run query radcheck expiration time 30 days
          $conn->query($querySessTime);
          if($shared_users != null) {
            $querySimul = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Simultaneous-Use', ':=', $shared_users)";
            // run query radcheck simultan
            $conn->query($querySimul);
          }
          
        } else {
          $queryGroup = "INSERT INTO radusergroup (`username`, `groupname`, `priority`) VALUES ('".$username."', '".$groupname."', 1)";
          $querySessTime = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Expiration', ':=', '".$expired."')";

          $queryUpload = "INSERT INTO radreply (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'WISPr-Bandwidth-Max-Up', ':=', $limit_upload)";
          $queryDownload = "INSERT INTO radreply (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'WISPr-Bandwidth-Max-Down', ':=', $limit_download)";

          $conn->query($queryUpload);
          $conn->query($queryDownload);
          // run query radreply limit total transfer (volume)
          $conn->query($queryGroup);
          // run query radcheck expiration time 30 days
          $conn->query($querySessTime);
          if($shared_users != null) {
            $querySimul = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Simultaneous-Use', ':=', $shared_users)";
            // run query radcheck simultan
            $conn->query($querySimul);
          }
        }

        $queryAcct = "INSERT INTO radreply (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Acct-Interim-Interval', ':=', '60')";
        // run query accounting user
        $conn->query($queryAcct);

        $queryUserBill = "INSERT INTO user_billing (`username`, `password`, `billing_package_id`, `shared_users`, `bill_price`, `fullname`, `birthdate`, `boarding_house_name`, `telp`, `start_from`, `end_until`) VALUES ('".$username."', '".$password."', $profile, '".$shared_users_bill."', $price, '".$fullname."', '".$date_of_birth."', '".$board_name."', '".$telephone."', '".$start_date."', '".$expired."')";

        $report = "INSERT INTO bill_report (`username`, `payment`, `billing_package_id`, `description`, `price`, `type`, `created_by`, `created_at`) VALUES ('".$username."', '".$payment."', '".$profile."', '".$description."', '".$price."', 'Voucher Baru', '".$user_login."', '".$timestamp."')";
        $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Menambahkan Voucher ".$username."', 'Voucher', '".$user_login."', '".$timestamp."')";
        $conn->query($action_report);

        // run query user billing
        if ($conn->query($queryUserBill) === TRUE) {
          echo "New record Report created successfully";
        } else {
          echo "Error: " . $queryUserBill . "<br>" . $conn->error;
        }

        // run query report voucher
        if ($conn->query($report) === TRUE) {
          echo "New record Account created successfully";
        } else {
          echo "Error: " . $report . "<br>" . $conn->error;
        }

        header("Location:./admin.php?task=voucher-list");

      } else {
        echo "data tidak ada";
      }
    }
    else if($action == 'update'){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $fullname = $_POST['fullname'];
      $date_of_birth = $_POST['date_of_birth'];
      $boarding_name = $_POST['boarding_name'];
      $telephone = $_POST['telephone'];
      $shared_users = $_POST['shared_users'];
      $before_shared = $_POST['before_shared_users'];

      if($before_shared == 'unlimited') {
        $querySharedUsers = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Simultaneous-Use', ':=', '".$shared_users."')";
      } else {
        $querySharedUsers = "UPDATE radcheck SET `value` = '".$shared_users."' WHERE attribute = 'Simultaneous-Use' AND username = '".$username."'";
      }

      if($shared_users == 'unlimited') {
        $queryRemoveShared = "DELETE FROM radcheck WHERE username = '".$username."' AND attribute = 'Simultaneous-Use'";
        $conn->query($queryRemoveShared);
      }
      $queryAccount = "UPDATE radcheck SET `value` = '".$password."' WHERE attribute = 'Cleartext-Password' AND username = '".$username."'";
      
      $conn->query($querySharedUsers);
      $conn->query($queryAccount);

      $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Mengupdate Voucher ".$username."', 'Voucher', '".$user_login."', '".$timestamp."')";
      $conn->query($action_report);
      
      $queryUserBill = "UPDATE user_billing SET `shared_users` = '".$shared_users."', `username` = '".$username."', `password` = '".$password."', `fullname` = '".$fullname."', `birthdate` = '".$date_of_birth."', `boarding_house_name` = '".$boarding_name."', `telp` = '".$telephone."' WHERE username = '".$username."'";
      if($conn->query($queryUserBill) === TRUE) {
        echo "New record Account created successfully";
      } else {
        echo "Error: " . $queryUserBill . "<br>" . $conn->error;
      }

      header("Location:./admin.php?task=voucher-list");
    }
    else if($action == 'refill') {
      $start_date = date('d M Y H:i:s');
      $refill = date('d M Y H:i:s', strtotime("+30 days"));
      $username = $_POST['username'];
      $profile = $_POST['billing_id'];
      $type = $_POST['type'];
      $price = $_POST['price'];
      $payment = $_POST['payment'];
      $description = isset($_POST['description'])?$_POST['description']:'';
      if($type == 'speed') {
        $queryRefill = "UPDATE radcheck SET `value` = '".$refill."' WHERE attribute = 'Expiration' AND username = '".$username."'";
        $queryRefillTraffic = "UPDATE radacct SET `acctinputoctets` = 0, `acctoutputoctets` = 0 WHERE username = '".$username."'";
        $conn->query($queryRefill);
        $conn->query($queryRefillTraffic);
      } else {
        $queryRefill = "UPDATE radcheck SET `value` = '".$refill."' WHERE attribute = 'Expiration' AND username = '".$username."'";
        $queryRefillTraffic = "UPDATE radacct SET `acctinputoctets` = 0, `acctoutputoctets` = 0 WHERE username = '".$username."'";
        $conn->query($queryRefill);
        $conn->query($queryRefillTraffic);
      }
      $report = "INSERT INTO bill_report (`username`, `payment`, `billing_package_id`, `description`, `price`, `type`, `created_by`, `created_at`) VALUES ('".$username."', $payment, $profile, '".$description."', $price, 'Refill Voucher', '".$user_login."', '".$timestamp."')";
      $conn->query($report);

      $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Merefill Voucher ".$username."', 'Voucher', '".$user_login."', '".$timestamp."')";
      $conn->query($action_report);

      $queryRefillBill = "UPDATE user_billing SET `start_from` = '".$start_date."', `end_until` = '".$refill."' WHERE username = '".$username."'";
      if($conn->query($queryRefillBill) === TRUE) {
        $response = ([
          "status" => "success",
          "message" => "Berhasil Merefill Voucher ".$username
        ]);
        echo json_encode($response);
      } else {
        $response = ([
          "status" => "failed",
          "message" => $conn->error
        ]);
        echo json_encode($response);
      }
    }
    else if($action == 'assign-profile') {
      $username = $_POST['username'];
      $billing = $_POST['billing'];

      $queryBonus = "INSERT INTO radusergroup (`username`, `groupname`, `priority`) VALUES ('".$username."', '".$billing."', 0)";

      $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Menambahkan Bonus Paket untuk Voucher ".$username."', 'Voucher', '".$user_login."', '".$timestamp."')";
      $conn->query($action_report);
      
      if($conn->query($queryBonus) === TRUE) {
        $response = ([
          "status" => "success",
          "message" => "Berhasil Menambahkan Bonus Paket ".$username
        ]);
        echo json_encode($response);
      } else {
        $response = ([
          "status" => "failed",
          "message" => $conn->error
        ]);
        echo json_encode($response);
      }

    }
    else if($action == 'delete'){
      $username = $_GET['username'];
      $queryDeleteUserBill = "DELETE FROM user_billing WHERE username = '".$username."'";
      $queryDeleteReply = "DELETE FROM radreply WHERE username = '".$username."'";
      $queryDeleteCheck = "DELETE FROM radcheck WHERE username = '".$username."'";
      $queryDeleteProfile = "DELETE FROM radusergroup WHERE username = '".$username."'";

      $conn->query($queryDeleteUserBill);
      $conn->query($queryDeleteReply);
      $conn->query($queryDeleteProfile);
      
      $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Menghapus Voucher ".$username."', 'Voucher', '".$user_login."', '".$timestamp."')";
      $conn->query($action_report);

      if ($conn->query($queryDeleteCheck) === TRUE) {
        $response = ([
          "status" => "success",
          "message" => "Berhasil Menghapus Voucher ".$username
        ]);
        echo json_encode($response);
      } else {
        $response = ([
          "status" => "failed",
          "message" => $conn->error
        ]);
        echo json_encode($response);
      }
      
    }
    else if($action == 'delete-bonus') {
      $username = $_GET['username'];
      $groupname = $_GET['groupname'];

      $queryBonus = "DELETE FROM radusergroup WHERE username = '".$username."' AND groupname = '".$groupname."' AND priority = 0";

      $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Menghapus Bonus Paket ".$groupname." untuk Voucher ".$username."', 'Voucher', '".$user_login."', '".$timestamp."')";
      $conn->query($action_report);
      
      if($conn->query($queryBonus) === TRUE) {
        $response = ([
          "status" => "success",
          "message" => "Berhasil Menghapus Bonus Paket ".$username
        ]);
        echo json_encode($response);
      } else {
        $response = ([
          "status" => "failed",
          "message" => $conn->error
        ]);
        echo json_encode($response);
      }

    }
  }
  else if($data == 'nas'){
    if($action == 'save'){
      if($_POST != ''){
        $nasname = $_POST['nasname'];
        $shortname = $_POST['shortname'];
        $secret = $_POST['secret'];

        $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Menambahkan Router ".$nasname."', 'Router NAS', '".$user_login."', '".$timestamp."')";
        $conn->query($action_report);

        $querySave = "INSERT INTO nas (`nasname`, `shortname`, `type`, `ports`, `secret`) VALUES ('".$nasname."', '".$shortname."', 'other', 0, '".$secret."')";
        if ($conn->query($querySave) === TRUE) {
          echo "success";
        } else {
          echo "failed";
        }

        header("Location:./admin.php?task=router-nas");
      } else {
        echo "data tidak ada";
      }
    }
    else if($action == 'update'){
      if($_POST != ''){
        $id = $_POST['id'];
        $nasname = $_POST['nasname'];
        $shortname = $_POST['shortname'];
        $secret = $_POST['secret'];

        $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Mengupdate Router ".$username."', 'Router NAS', '".$user_login."', '".$timestamp."')";
        $conn->query($action_report);

        $queryUpdate = "UPDATE nas SET `nasname` = '".$nasname."', `secret` = '".$secret."', `shortname` = '".$shortname."' WHERE id = $id";
        if($conn->query($queryUpdate) === TRUE) {
          echo "success";
        } else {
          echo "failed";
        }
        header("Location:./admin.php?token=".$_SESSION['token']."&task=router-nas");
      } else {
        echo "data tidak ada";
      }
    }
    else if($action == 'delete'){
      $id = $_GET['id'];
      $queryDelete = "DELETE FROM nas WHERE id = $id";
      $queryNas = "SELECT nasname FROM nas WHERE id = ".$id;
      $res = $conn->query($queryNas);
      $nasname = $res->fetch_assoc();
      $nasname = $nasname['nasname'];

      $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Menghapus Router ".$nasname."', 'Router NAS', '".$user_login."', '".$timestamp."')";
      $conn->query($action_report);

      if ($conn->query($queryDelete) === TRUE) {
        $response = ([
          "status" => "success",
          "message" => "Berhasil Menghapus Router NAS"
        ]);
        echo json_encode($response);
      } else {
        $response = ([
          "status" => "failed",
          "message" => $conn->error
        ]);
        echo json_encode($response);
      }
    }
  }
  else if($data == 'package'){
    if($action == 'save'){
      if($_POST != ''){
        $name = $_POST['name'];
        $type = $_POST['type'];
        $price = $_POST['price'];
        if($type == 'volume') {
          $limit = $_POST['volume']*1048576;
          $queryVolume = "INSERT INTO radgroupcheck (`groupname`, `attribute`, `op`, `value`) VALUES ('".$name."', 'ChilliSpot-Max-Total-Octets', ':=', '".$limit."')";
          $conn->query($queryVolume);
          $querySave = "INSERT INTO billing_package (`name`, `volume`, `billing_type`, `price`) VALUES ('".$name."', $limit, '".$type."', $price)";
          $conn->query($querySave);
        } else if($type == 'speed') {
          $upload = $_POST['upload']*1024;
          $download = $_POST['download']*1024;
          $queryUpload = "INSERT INTO radgroupreply (`groupname`, `attribute`, `op`, `value`) VALUES ('".$name."', 'WISPr-Bandwidth-Max-Up', ':=', '".$upload."')";
          $queryDownload = "INSERT INTO radgroupreply (`groupname`, `attribute`, `op`, `value`) VALUES ('".$name."', 'WISPr-Bandwidth-Max-Down', ':=', '".$download."')";
          $querySave = "INSERT INTO billing_package (`name`, `limit_upload`, `limit_download`, `billing_type`, `price`) VALUES ('".$name."', $upload, $download, '".$type."', $price)";
          $conn->query($querySave);
          $conn->query($queryUpload);
          $conn->query($queryDownload);
        }

        $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Menambahkan Paket Billing ".$name."', 'Paket Billing', '".$user_login."', '".$timestamp."')";
        $conn->query($action_report);
        var_dump($conn->error);
        
        header("Location:./admin.php?task=package-list");
      } else {
        echo "data tidak ada";
      }
    }
    else if($action == 'get-billing'){
      $id = $_GET['id'];
      $queryType = "SELECT `name`, `volume`, `limit_upload`, `limit_download`, `billing_type` FROM billing_package WHERE id = $id";
      $resType = $conn->query($queryType);
      $data = $resType->fetch_assoc();

      echo json_encode($data);
    }
    else if($action == 'update'){
      if($_POST != ''){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $price = $_POST['price'];
        if($type == 'volume') {
          $limit = $_POST['limit']*1048576;
          $queryUpdate = "UPDATE billing_package SET `name` = '".$name."', `volume` = $limit, `billing_type` = '".$type."', `price` = $price WHERE id = $id";
          $queryUpdateGroupDown = "UPDATE radgroupreply SET `groupname` = '".$name."', `value` = '".$download."' WHERE groupname = '".$before_name."' AND attribute = 'WISPr-Bandwidth-Max-Down'";
          $queryUpdateGroupUp = "UPDATE radgroupreply SET `groupname` = '".$name."', `value` = '".$upload."' WHERE groupname = '".$before_name."' AND attribute = 'WISPr-Bandwidth-Max-Up'";
          
          $conn->query($queryUpdateGroupDown);
          $conn->query($queryUpdateGroupUp);
        } else if($type == 'speed') {
          $upload = $_POST['upload']*1024;
          $download = $_POST['download']*1024;
          $queryUpdate = "UPDATE billing_package SET `name` = '".$name."', `limit_upload` = $upload, `limit_download` = $download, `billing_type` = '".$type."', `price` = $price WHERE id = $id";
          $queryUpdateGroupCheck = "UPDATE radgroupcheck SET `groupname` = '".$name."', `value` = '".$volume."' WHERE groupname = '".$before_name."' AND attribute = 'ChilliSpot-Max-Total-Octets'";
          
          $conn->query($queryUpdateGroupCheck);
        }

        $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Mengupdate Paket ".$name."', 'Paket Billing', '".$user_login."', '".$timestamp."')";
        $conn->query($action_report);

        if($conn->query($queryUpdate) === TRUE) {
          $response = ([
            "status" => "success",
            "message" => "Berhasil Update Paket Billing"
          ]);
          echo json_encode($response);
        } else {
          $response = ([
            "status" => "failed",
            "message" => "Gagal Update Paket Billing"
          ]);
          echo json_encode($response);
        }
        header("Location:./admin.php?task=package-list");
      } else {
        echo "data tidak ada";
      }
    }
    else if($action == 'delete'){
      $id = $_GET['id'];
      $groupname = $_GET['name'];
      $type = $_GET['type'];

      $checkRelation = "SELECT COUNT(username) AS total FROM radusergroup WHERE groupname = '".$groupname."'";
      $resRelation = $conn->query($checkRelation);
      $totalRelation = $resRelation->fetch_assoc();
      $totalRelation = $totalRelation['total'];

      if($totalRelation == 0) {
        if($type == 'volume') {
          $queryDeleteRad = "DELETE FROM radgroupcheck WHERE groupname = '".$groupname."'";
        } else {
          $queryDeleteRad = "DELETE FROM radgroupreply WHERE groupname = '".$groupname."'";
        }
  
        $queryDelete = "DELETE FROM billing_package WHERE id = $id";
  
        $conn->query($queryDeleteRad);

        $action_report = "INSERT INTO action_report (`description`, `type`, `created_by`, `created_at`) VALUES ('Menghapus Paket ".$groupname."', 'Paket Billing', '".$user_login."', '".$timestamp."')";
        $conn->query($action_report);
  
        if ($conn->query($queryDelete) === TRUE) {
          $response = ([
            "status" => "success",
            "message" => "Berhasil Menghapus Paket Billing"
          ]);
          echo json_encode($response);
        } else {
          $response = ([
            "status" => "failed",
            "message" => $conn->error
          ]);
          echo json_encode($response);
        }
      } else {
        $response = ([
          "status" => "failed",
          "message" => "Data Voucher masih memakai Billing Paket ini"
        ]);
        echo json_encode($response);
      }
    }
  }
  
  
  
?>