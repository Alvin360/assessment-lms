<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "pup_lms";
$port = "3306";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $port);

if (!is_null($conn->connect_error)) {
    throw new Exception('Connection failed: ' . $conn->connect_error);
}

// Set UTF-8 encoding
$conn->set_charset("utf8mb4");

?>