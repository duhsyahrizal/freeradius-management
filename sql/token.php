<?php
include('./sql/connection.php');
date_default_timezone_set('Asia/Jakarta');
$timestamp = date('Y-m-d H:i:s');
$username = $_SESSION["user"]['username'];

if(isset($_SESSION['user']['token'])){
  $sql_token="SELECT token from user_token where username='".$username."'";
  // Update user token 
  $result_token = $conn->query($sql_token);

  if($result_token->num_rows > 0){
    $resp = $result_token->fetch_assoc();
    $token_id = $resp['token'];
  }
}
?>