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
    <title>Homepage</title>
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
            opacity: 0;
            transform: translateY(20px);
            animation: slideFadeIn 0.6s ease forwards;
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
        @keyframes slideFadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
        }


    </style>
</head>
<body>
    <div class="container">


        <?php if ($_SESSION["userProfileID"] == 1): ?>
            <div class="header">User Admin Homepage</div>
            <div class="welcome">Welcome <?php echo htmlspecialchars($_SESSION["name"]); ?>!</div>
            <div class="button-container">
                <a href="viewAlluserAccountPage.php" class="nav-button">User Account Management</a>
                <a href="./viewAllUserProfilePage.php" class="nav-button">User Profile Management</a>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION["userProfileID"] == 2): ?>
            <div class="header">Cleaner Homepage</div>
            <div class="welcome">Welcome <?php echo htmlspecialchars($_SESSION["name"]); ?>!</div>
            <div class="button-container">
                <a href="viewOwnServicePage.php" class="nav-button">View And Manage All Services</a>
            </div>

            <div class="button-container">
                <a href="searchHistoryPage.php" class="nav-button">Search History</a>
            </div>

            <div class="button-container">
                <a href="viewOwnFilteredServicesPage.php" class="nav-button">View Filter by Service Type</a>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION["userProfileID"] == 3): ?>
            <div class="header">Home Owner Homepage</div>
            <div class="welcome">Welcome <?php echo htmlspecialchars($_SESSION["name"]); ?>!</div>
            <div class="button-container">
                <a href="viewAllServicePage.php" class="nav-button">View All Cleaning Services</a>
                <a href="viewAllFilteredHistoryPage.php" class="nav-button">Search Filtered History Page</a>
            </div>
            <div class="button-container">
                <a href="historyPage.php" class="nav-button">Search History</a>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION["userProfileID"] == 4): ?>
            
            <div class="header">Platform Management Homepage</div>
            <div class="welcome">Welcome <?php echo htmlspecialchars($_SESSION["name"]); ?>!</div>
            <div class="button-container">
                <a href="viewAllCleaningCategoryPage.php" class="nav-button">View And Manage All Category</a>
            </div>
        <?php endif; ?>

        <a href="logoutPage.php" class="logout-button">Log Out</a>
    </div>
</body>
</html>
