<?php
$servername = "nachwa-admin.cizxdctjcize.eu-west-3.rds.amazonaws.com";
$username = "";
$password = "";
$database = "";

// $servername = "localhost";
// $username = "root";
// $password = "";
// $database = "testifdatawork";
// Create connection
$conn = mysqli_connect($servername, $username, $password,$database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

date_default_timezone_set('Africa/Casablanca');
