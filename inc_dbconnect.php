<?php
$servername = "localhost";
$username = "root";
$pass = "";
$DBName = "csit314";

// Create connection
try 
{
    $conn = new mysqli($servername, $username, $pass, $DBName);	
}
catch (mysqli_sql_exception $e)
{
    die("Connection failed: " . $e->getCode(). ": " . $e->getMessage());
}

?>

