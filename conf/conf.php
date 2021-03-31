<?php
include('./lib/function.php');
require_once('./lib/routeros_api.class.php');

$date = date("d M Y");
$monthAgo = date("d-m-y", strtotime("-30 days"));
$endDate = date("d M Y", strtotime("+30 days"));
$month = date("d-m-y");
$created_at = date("H:i:s");
$option_shared_user = array("unlimited", 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
$rate_limits = array(1024,1536,2048,3072,4096,5120,8192, 10240);
$queryClient = "SELECT * FROM nas ORDER BY nasname ASC";
$nas = $conn->query($queryClient);
$queryPackage = "SELECT * FROM billing_package";
$package = $conn->query($queryPackage);

$queryGroup = "SELECT * FROM radusergroup";
$usergroup = $conn->query($queryGroup);

$queryUser = "SELECT username FROM user_billing";
$users = $conn->query($queryUser);
$total_user = $users->num_rows;

$queryAcc = "SELECT username FROM bayhost_users";
$account = $conn->query($queryAcc);
$total_account = $account->num_rows;

$queryAu = "SELECT * FROM radacct WHERE acctstoptime IS NULL GROUP BY framedipaddress ORDER BY username ASC";
$resultActiveUser = $conn->query($queryAu);
$total_active = $resultActiveUser->num_rows;

$queryUsers = "SELECT billing_package.id AS billing_id, user_billing.id AS user_billing_id, billing_package.*, user_billing.* FROM user_billing JOIN billing_package ON billing_package.id = user_billing.billing_package_id ORDER BY username ASC";
$resultUsers = $conn->query($queryUsers);

$queryUserGroups = "SELECT * FROM radusergroup JOIN user_billing ON user_billing.username = radusergroup.username WHERE radusergroup.groupname NOT LIKE 'UNL%'";
$resultUserGroups = $conn->query($queryUserGroups);
    
if(!$conn){
  die("Connection failed: " . mysqli_connect_error());
}
else {
  // get all client in database
  $total_router = $nas->num_rows;
}

if(isset($_GET['id'])){
  $id = $_GET['id'];
  $query = "SELECT * FROM nas WHERE id = ".$id;
  $queryPackage = "SELECT * FROM billing_package WHERE id = ".$id;
  $resquery = $conn->query($query);
  $resPackage = $conn->query($queryPackage);
  $getdata = $resquery->fetch_assoc();
  $packages = $resPackage->fetch_assoc();
} else if(isset($_GET['username'])){
  $username = $_GET['username'];
  $queryCheck = "SELECT radcheck.id AS check_id, radcheck.attribute AS check_attribute, radcheck.value AS check_value, radreply.id AS reply_id, radreply.attribute AS reply_attribute, radreply.value AS reply_value FROM radcheck JOIN radreply ON radreply.username = radcheck.username WHERE radcheck.username = '".$username."'";
  $resultQuery = $conn->query($queryCheck);
  $queryUserBill = "SELECT user_billing.id AS billing_id, billing_package.id AS billing_package_id, user_billing.*, billing_package.* FROM user_billing JOIN billing_package ON billing_package.id = user_billing.billing_package_id WHERE username = '".$username."'";
  $resultBill = $conn->query($queryUserBill);
  $userbilling = $resultBill->fetch_assoc();
}
if(isset($_GET['task'])){
  $task = $_GET['task'];
  switch ($task) {
    // Beranda
    case 'dashboard':
      include 'pages/dashboard.php';
      break;
    case 'router-nas':
      include 'pages/router-nas.php';
      break;
    case 'add-nas':
      include 'pages/add-nas.php';
      break;
    case 'edit-nas':
      include 'pages/edit-nas.php';
      break;
    case 'voucher-list':
      include 'pages/voucher-list.php';
      break;
    case 'add-voucher':
      include 'pages/add-voucher.php';
      break;
    case 'edit-voucher':
      include 'pages/edit-voucher.php';
      break;
    case 'package-list':
      include 'pages/package-list.php';
      break;
    case 'add-package':
      include 'pages/add-package.php';
      break;
    case 'edit-package':
      include 'pages/edit-package.php';
      break;
    case 'assign-package':
      include 'pages/package-voucher.php';
      break;
    case 'user-active':
      include 'pages/user-active.php';
      break;
    case 'report':
      include 'pages/reports.php';
      break;
    case 'report-data':
      include 'pages/report-data.php';
      break;
    case 'preference':
      include 'pages/manage-user.php';
      break;
    case 'system':
      include 'pages/active-soon.php';
      break;
  }
}
?>