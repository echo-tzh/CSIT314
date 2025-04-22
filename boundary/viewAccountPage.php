<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user ID is provided via POST
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    $_SESSION["status"] = "Invalid user ID.";
    header("Location: viewAlluserAccountPage.php");
    exit();
}

$userID = $_POST['id'];

// Include controller
include_once '../controller/viewAccountController.php';

// Create the controller and get the user data
$controller = new ViewAccountController();
$userAccount = $controller->viewAccount($userID);

// Check if user data was retrieved successfully

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View User Account - One Stop Cleaning Services</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .user-details {
            margin-top: 20px;
        }
        .detail-row {
            margin-bottom: 15px;
            display: flex;
        }
        .detail-label {
            font-weight: bold;
            width: 150px;
        }
        .detail-value {
            flex: 1;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            margin-right: 10px;
        }
        .button.back {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Account Details</h1>
        <div class="user-details">
            <div class="detail-row">
                <div class="detail-label">User ID:</div>
                <div class="detail-value"><?php echo htmlspecialchars($userAccount['userAccountID']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Username:</div>
                <div class="detail-value"><?php echo htmlspecialchars($userAccount['username']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Name:</div>
                <div class="detail-value"><?php echo htmlspecialchars($userAccount['name']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status:</div>
                <div class="detail-value">
                <?php echo $userAccount['status'] == 1 ? 'Active' : 'Suspended'; ?>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">User Profile:</div>
                <div class="detail-value"><?php echo htmlspecialchars($userAccount['profileName'] ?? 'Not assigned'); ?></div>
            </div>
            
        </div>
        <a href="viewAlluserAccountPage.php" class="button back">Back to User Management</a>
        <form action="updateAccountPage.php" method="post" style="display:inline;">
            <input type="hidden" name="userID" value="<?php echo $userAccount['userAccountID']; ?>">
            
        </form>
    </div>
</body>
</html>