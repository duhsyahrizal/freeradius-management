<?php
session_start();
require_once('./lib/routeros_api.class.php');
date_default_timezone_set('Asia/Jakarta');
include('./sql/connection.php');

  $action = $_GET['action'];
  $data = !isset($_GET['data'])?'':$_GET['data'];
  $timestamp = date('Y-m-d H:i:s');

  if($action == 'login'){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember'])?$_POST['remember']:'';
    $token = openssl_random_pseudo_bytes(16);
    // connection
    // $conn = new mysqli($servername, $userdb, $passworddb, $database);
    $sql = "SELECT * FROM bayhost_users WHERE `username` = '".$username."' AND `password` = '".$password."'";
    $time = time();
    $matching = $conn->query($sql);
    $check = $matching->num_rows;
    

    //Convert the binary data into hexadecimal representation.
    $token = bin2hex($token);
    
    if($check > 0){
      if($remember == true){
        setcookie("username", $username, $time + 60*60*24*30, '/');
        setcookie("password", $password, $time + 60*60*24*30, '/');
        setcookie("remember", 1, $time + 60*60*24*30, '/');
      }else{

      }
      $_SESSION['user'] = $username;
      $_SESSION['token'] = $token;
      $sql_insert = "INSERT INTO user_token (`username`, `token`, `modified_at`) VALUES ('".$username."', '".$_SESSION['token']."', '".$timestamp."')";
      $sql_update = "UPDATE user_token set token='".$token."', modified_at='".$timestamp."' WHERE username='".$username."'";
      
      $sql_check_token="SELECT * from user_token where username='".$username."'";
      // Create connection
      $connect = new mysqli($servername, $userdb, $passworddb, $database);
      // Update user token 
      $result_token = $connect->query($sql_check_token);

      if($result_token->num_rows > 0){
        // Update token to table
        $connect->query($sql_update);
      }else{
        // Insert token to table
        $connect->query($sql_insert);
      }
      // $member = $matching->fetch_assoc();
      // $data['username'];
      // $data['password'];
      // $data['remember'];
      // // array_push($data, $member);
      // array_push($data, $_COOKIE['username']);
      // array_push($data, $_COOKIE['password']);
      // array_push($data, $_COOKIE['remember']);
      // $json = json_encode($data);
      // echo $json;
      echo 'success';
      // echo '<script language="javascript">';
			// echo 'alert("Welcome to Bayhost Radius, '.ucfirst($_SESSION['user']).'");';
			// echo 'window.location.href = "../admin.php?token='.$_SESSION['token'].'&task=dashboard";';
			// echo '</script>';
      // header("Location:../admin.php?token='.$_SESSION['token'].'&page=dashboard");
    }else {
      echo 'failed';
      // echo '<script language="javascript">';
      // echo 'alert("Please type username and password correctly.");';
      // echo 'window.location.href = "../index.php";';
      // echo '</script>';
    }
  }
  else if($action == 'logout'){
    unset($_SESSION['user']);
    unset($_SESSION['token']);
    session_destroy();
    header("Location:./index.php");
  }
  else if($data == 'user-bayhost'){
    if($action == 'edit-user'){
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
    else if($action == 'update-user'){
      $user_id = $_POST['user_id'];
      $fullname = $_POST['fullname'];
      $username = $_POST['username'];
      $password = $_POST['password'];
      $group = $_POST['group'];
      $manage_package = $_POST['manage_package'];
      $manage_user = $_POST['manage_user'];
      $sqlUser = "UPDATE bayhost_users SET `fullname` = '".$fullname."', `username` = '".$username."', `password` = '".$password."', `role` = $group WHERE bayhost_user_id = $user_id";
      // $sqlRole = "UPDATE role_group SET `username` = '".$username."', `password` = '".$password."', `role` = ".$group."";
      $result = $conn->query($sqlUser);
      if($result){
        echo 'success';
      }else{
        echo 'failed';
      }
    }
    else if($action == 'delete'){
      $user_id = $_POST['user_id'];
      $sql = "DELETE FROM bayhost_users WHERE bayhost_user_id = $user_id";
      $result = $conn->query($sql);
      if($result){
        echo 'success';
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
        $idletimeout = $_POST['idletimeout'];
        $type = $_POST['package_type'];
        $volspeed = $_POST['package_limit'];
        $limit_upload = isset($_POST['upload']) ? $_POST['upload']*1024 : null;
        $limit_download = isset($_POST['download']) ? $_POST['download']*1024 : null;
        $shared_users = !is_null($_POST['shared_users']) ? $_POST['shared_users'] : null;
        $shared_users_bill = !is_null($_POST['shared_users']) ? $_POST['shared_users'] : 'unlimited';

        $start_date = $_POST['start_date'];
        $start_from = date_create($start_date);
        $end_date = $_POST['end_date'];
        $end_until = date_create($end_date);
        $date_diff = date_diff($start_from, $end_until);
        $total_days = $date_diff->format("%a");
        $start_hour = $_POST['start_hour'];
        $expired = $end_date.' '.$start_hour;
        $start_date = $start_date.' '.$start_hour;
        // Attrib Max-All-Session
        $session_time = $total_days*24*60*60;

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
        $user_login = $_SESSION['user'];

        $queryIdle = "INSERT INTO radreply (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Idle-Timeout', ':=', '".$idletimeout."')";
        $queryAcct = "INSERT INTO radreply (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Acct-Interim-Interval', ':=', '60')";
        $queryPassword = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Cleartext-Password', ':=', '".$password."')";

        // run query radreply Idle Timeout
        $conn->query($queryIdle);
        // run query radreply Accounting
        $conn->query($queryAcct);
        // run query radcheck Password
        $conn->query($queryPassword);

        // check billing type is volume or speed limit
        if($type == 'speed'){
          $querySup = "INSERT INTO radreply (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'WISPr-Bandwidth-Max-Up', ':=', '".$volspeed."')";
          $querySdo = "INSERT INTO radreply (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'WISPr-Bandwidth-Max-Down', ':=', '".$volspeed."')";
          $querySessTime = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Expiration', ':=', '".$expired."')";

          // run query radreply speed limit upload
          $conn->query($querySup);
          // run query radreply speed limit download
          $conn->query($querySdo);
          // run query radcheck expiration time 30 days
          $conn->query($querySessTime);
          if($shared_users != null) {
            $querySimul = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Simultaneous-Use', ':=', $shared_users)";
            // run query radcheck simultan
            $conn->query($querySimul);
          }
          
        } else {
          $queryUpload = "INSERT INTO radreply (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'WISPr-Bandwidth-Max-Up', ':=', '".$limit_upload."')";
          $queryDownload = "INSERT INTO radreply (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'WISPr-Bandwidth-Max-Down', ':=', '".$limit_download."')";
          $queryVolume = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'ChilliSpot-Max-Total-Octets', ':=', '".$volspeed."')";
          $querySessTime = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Expiration', ':=', '".$expired."')";

          // run query radreply speed limit upload
          $conn->query($queryUpload);
          // run query radreply speed limit download
          $conn->query($queryDownload);
          // run query radreply limit total transfer (volume)
          $conn->query($queryVolume);
          // run query radcheck expiration time 30 days
          $conn->query($querySessTime);
          if($shared_users != null) {
            $querySimul = "INSERT INTO radcheck (`username`, `attribute`, `op`, `value`) VALUES ('".$username."', 'Simultaneous-Use', ':=', $shared_users)";
            // run query radcheck simultan
            $conn->query($querySimul);
          }
        }

        $queryUserBill = "INSERT INTO user_billing (`username`, `password`, `billing_package_id`, `shared_users`, `bill_price`, `fullname`, `birthdate`, `boarding_house_name`, `telp`, `start_from`, `end_until`) VALUES ('".$username."', '".$password."', $profile, '".$shared_users_bill."', $price, '".$fullname."', '".$date_of_birth."', '".$board_name."', '".$telephone."', '".$start_date."', '".$expired."')";

        $report = "INSERT INTO bill_report (`username`, `payment`, `billing_package_id`, `method`, `description`, `price`, `type`, `created_by`) VALUES ('".$username."', '".$payment."', '".$profile."', '".$paymentName."', '".$description."', '".$price."', 'voucher', '".$user_login."')";

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
          echo "Error: " . $sql . "<br>" . $conn->error;
        }

        header("Location:./admin.php?task=voucher-list");

      } else {
        echo "data tidak ada";
      }
    }
    else if($action == 'update'){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $shared_users = $_POST['shared_users'];

    }
    else if($action == 'delete'){
      $username = $_GET['username'];
      $queryDeleteUserBill = "DELETE FROM user_billing WHERE username = '".$username."'";
      $queryDeleteReply = "DELETE FROM radreply WHERE username = '".$username."'";
      $queryDeleteCheck = "DELETE FROM radcheck WHERE username = '".$username."'";

      $conn->query($queryDeleteUserBill);
      $conn->query($queryDeleteReply);
      if ($conn->query($queryDeleteCheck) === TRUE) {
        echo "success";
      } else {
        echo "failed";
      }
      
    }
  }
  else if($data == 'nas'){
    if($action == 'save'){
      if($_POST != ''){
        $nasname = $_POST['nasname'];
        $shortname = $_POST['shortname'];
        $secret = $_POST['secret'];
        $querySave = "INSERT INTO nas (`nasname`, `shortname`, `type`, `ports`, `secret`) VALUES ('".$nasname."', '".$shortname."', 'other', 0, '".$secret."')";
        if ($conn->query($querySave) === TRUE) {
          echo "success";
        } else {
          echo "failed";
        }
        header("Location:./admin.php?token=".$_SESSION['token']."&task=router-nas");
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

      if ($conn->query($queryDelete) === TRUE) {
        echo "success";
      } else {
        echo "failed";
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
        } else if($type == 'speed') {
          $upload = $_POST['upload']*1024;
          $download = $_POST['download']*1024;
          $queryUpload = "INSERT INTO radgroupcheck (`groupname`, `attribute`, `op`, `value`) VALUES ('".$name."', 'WISPr-Bandwidth-Max-Up', ':=', '".$upload."')";
          $queryDownload = "INSERT INTO radgroupcheck (`groupname`, `attribute`, `op`, `value`) VALUES ('".$name."', 'WISPr-Bandwidth-Max-Down', ':=', '".$download."')";
        }
        $queryAcct = "INSERT INTO radgroupreply (`groupname`, `attribute`, `op`, `value`) VALUES ('".$name."', 'Acct-Interim-Interval', ':=', '60')";
        $querySave = "INSERT INTO billing_package (`package_name`, `package_limit`, `package_type`, `price`) VALUES ('".$name."', $upload, '".$type."', $price)";
        if ($conn->query($querySave) === TRUE) {
          echo "success";
        } else {
          echo "failed";
        }
        header("Location:./admin.php?token=".$_SESSION['token']."&task=package-list");
      } else {
        echo "data tidak ada";
      }
    }
    else if($action == 'get-billing'){
      $id = $_GET['id'];
      $queryType = "SELECT `package_limit`, `package_type` FROM billing_package WHERE id = $id";
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
          $queryUpdate = "UPDATE billing_package SET `package_name` = '".$name."', `package_limit` = $limit, `package_type` = '".$type."' `price` = $price WHERE id = $id";
        } else if($type == 'speed') {
          $upload = $_POST['upload']*1024;
          $download = $_POST['download']*1024;
          $queryUpdate = "UPDATE billing_package SET `package_name` = '".$name."', `package_limit` = $upload, `package_type` = '".$type."' `price` = $price WHERE id = $id";
        }

        if($conn->query($queryUpdate) === TRUE) {
          echo "success";
        } else {
          echo "failed";
        }
        header("Location:./admin.php?token=".$_SESSION['token']."&task=package-list");
      } else {
        echo "data tidak ada";
      }
    }
    else if($action == 'delete'){
      $id = $_GET['id'];
      $queryDelete = "DELETE FROM billing_package WHERE id = $id";

      if ($conn->query($queryDelete) === TRUE) {
        echo "success";
      } else {
        echo "failed";
      }
    }
  }
  
  
  
?>