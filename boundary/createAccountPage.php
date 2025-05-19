<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../controller/createAccountController.php';
include '../entity/userProfile.php';  // Include the entity
include '../inc_dbconnect.php';

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}



// Instantiate user profile entity and get all user profiles
$userProfileEntity = new userProfile();
$userProfiles = $userProfileEntity->getActiveUserProfiles();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $userProfileID = $_POST['userProfileID'];

    $controller = new createAccountController($conn);
    $result = $controller->createAccount($username, $password, $name, $userProfileID);
    
    if ($result) {
        echo "<p style='color: green;'>Account successfully created!</p>";
    } else {
        echo "<p style='color: red;'>Failed to create account.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Account</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create New Account</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="userProfileID">User Profile:</label>
                <select id="userProfileID" name="userProfileID" required>
                    <?php
                    if (!empty($userProfiles)) {
                        foreach ($userProfiles as $profile) {
                            echo "<option value='" . $profile['userProfileID'] . "'>" . htmlspecialchars($profile['userProfileName']) . "</option>";
                        }
                    } else {
                        echo "<option disabled>No profiles available</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn" value="Create Account">
            </div>

            <a href="viewAlluserAccountPage.php" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Back to User Account Management</a>
        </form>
    </div>
</body>
</html>
