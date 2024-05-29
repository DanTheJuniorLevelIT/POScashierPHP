<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: X-Requested-With');
  header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: http://localhost:4200');
  header('Access-Control-Allow-Headers: Content-Type');
  // Allow requests from the specific origin (http://localhost:4200)
  header("Access-Control-Allow-Origin: http://localhost:4200");
  // Allow the Content-Type header in the request
  header("Access-Control-Allow-Headers: Content-Type");
  header("Content-Type: application/json; charset=UTF-8");
    
  
  //connect.php
  $mysql_hostname = "localhost";
  $mysql_user = "root";
  $mysql_password = ""; 
  $mysql_database = "nlahpos";
  
  $conn = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
  if ($conn->connect_errno) {
      die("Failed to connect to MySQL: " . $conn->connect_error);
  }
?>