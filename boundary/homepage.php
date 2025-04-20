<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Admin Homepage</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .container {
            padding: 20px;
            position: relative;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .header {
            font-size: 20px;
            color: #555;
        }

        .welcome {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 100px;
        }

        .nav-button {
            background-color: #c8f7c5;
            border: 1px solid #444;
            border-radius: 10px;
            padding: 20px 40px;
            font-size: 20px;
            cursor: pointer;
            text-decoration: none;
            color: black;
            text-align: center;
        }

        .nav-button:hover {
            background-color: #b3ecb0;
        }

        .admin-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">User Admin Homepage</div>
        <div class="welcome">Welcome <?php echo htmlspecialchars($_SESSION["name"]); ?>!</div>

        <?php if ($_SESSION["userProfileID"] == 1): ?>
            <div class="button-container">
                <a href="viewAlluserAccountPage.php" class="nav-button">User Account Management</a>
                <a href="./viewAllUserProfilePage.php" class="nav-button">User Profile Management</a>
               
            </div>  
        <?php endif; ?>

        <a href="logoutPage.php" class="logout-button">Log Out</a>

        
    </div>
</body>
</html>
