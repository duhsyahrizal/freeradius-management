<?php
session_start();
include('./sql/token.php');
date_default_timezone_set('Asia/Jakarta');
include('./sql/connection.php');
$url = $_SERVER['REQUEST_URI'];
$token = $_SESSION['user']['token'];

if(empty($token) || $token_id != $token){
  header("Location: ./login.php");
} else {
  header("Location:./admin.php?task=dashboard");
}
?>

