<?php
$serverName = "localhost";
$dbUsername = "Admin";
$dbPassword = "/cdFOe*6MWIsBp[0"; // Random Generated Password, don't even try to hack me =3
$dbName     = "berlisahotel";

$conn = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>