<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CSIT314";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to handle special characters correctly
$conn->set_charset("utf8mb4");

// Return connection object
return $conn;
?>