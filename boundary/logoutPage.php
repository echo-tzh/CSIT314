<?php
session_start();
session_unset(); 
session_destroy(); // Destroy the session

// Redirect to login page
header("Location: loginPage.php");
exit();
?>