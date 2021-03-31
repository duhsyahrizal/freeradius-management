<?php
header('Access-Control-Allow-Origin: *');
include('../env.php');
include('../lib/function.php');
    
$conn = new mysqli($servername, $userdb, $passworddb, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$username = $_GET['username'];
if($username != null || $username != '') {
  $sql = "SELECT end_until FROM user_billing WHERE username = '".$username."'";
  $query = $conn->query($sql);
  $res = $query->fetch_assoc();
  
  $end_until = $res['end_until'];
  $kuota = showQuotaUser($username);
  
  $array = ([
    "kuota" => $kuota,
    "expired" => $end_until
  ]);

  $json = json_encode($array);
  
  echo $json;
}else {
  echo 'failed';
}

?>