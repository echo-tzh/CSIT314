<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Include controller
    include_once '../controller/updateAccountController.php';
    
    // Get form data
    $userID = $_POST['userID'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $userProfileID = $_POST['userProfileID'];
    
    // Create controller and update account
    $controller = new UpdateAccountController();
    $result = $controller->updateAccount($userID, $username, $name, $userProfileID);
    
    if ($result) {
        $_SESSION["status"] = "User account updated successfully!";
        header("Location: viewAlluserAccountPage.php");
        exit();
    } else {
        $error = "Failed to update user account.";
    }
} 
// Check if userID is provided for loading user data
else if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $userID = $_POST['id'];
    
    // Include controller
    include_once '../controller/viewAccountController.php';
    
    // Get user data to populate the form
    $controller = new ViewAccountController();
    $userAccount = $controller->viewAccount($userID);
    
    if (!$userAccount) {
        $_SESSION["status"] = "User not found.";
        header("Location: viewAlluserAccountPage.php");
        exit();
    }
} else {
    $_SESSION["status"] = "Invalid user ID.";
    header("Location: viewAlluserAccountPage.php");
    exit();
}

// Get user profiles for dropdown
// Include database connection
include_once '../inc_dbconnect.php';

include_once '../entity/userProfile.php';
$userProfile = new UserProfile(); 
$userProfiles = $userProfile->getAllUserProfiles();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User Account - One Stop Cleaning Services</title>
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
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            margin-right: 10px;
        }
        .button.back {
            background-color: #555;
        }
        .error {
            color: #d32f2f;
            background-color: #fbe9e7;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update User Account</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="updateAccountPage.php" method="post">
            <input type="hidden" name="userID" value="<?php echo $userAccount['userAccountID']; ?>">
            
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userAccount['username']); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userAccount['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="userProfileID">User Profile:</label>
                <select id="userProfileID" name="userProfileID" required>
                    <?php foreach ($userProfiles as $profile): ?>
                        <option value="<?php echo $profile['userProfileID']; ?>" 
                            <?php echo ($profile['userProfileID'] == $userAccount['userProfileID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($profile['userProfileName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" name="submit" class="button">Update User</button>
            <a href="viewAlluserAccountPage.php" class="button back">Cancel</a>
        </form>
    </div>
</body>
</html>
