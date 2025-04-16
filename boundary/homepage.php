
<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php"); // Redirect if not logged in
    exit();
}
?>


<html>
    <h1>Welcome to home page</h1>

    <a href="logoutPage.php" class="btn logout-btn">Logout</a>
</html>
