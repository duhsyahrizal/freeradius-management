<?php
    include('env.php');
    
    $conn = new mysqli($servername, $userdb, $passworddb, $database);
    
    if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
    }
    
?>