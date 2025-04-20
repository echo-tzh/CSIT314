<?php
include '../controller/createUserProfileController.php'; // Include the controller
include '../inc_dbconnect.php'; // Include the database connection

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newProfile = [
        'profile' => $_POST['profile']
    ];

    $controller = new createUserProfileController($conn); // Instantiate the controller
    $result = $controller->createUserProfile($newProfile); // Call the create user profile method

    if ($result) {
        echo "<p style='color: green;'>User profile successfully created!</p>";
    } else {
        echo "<p style='color: red;'>Failed to create user profile.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .form-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .back-button:hover {
             background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Create User Profile</h2>
        <form action="" method="post">  <div class="form-group">
                <label for="profile">User Profile Name:</label>
                <input type="text" id="profile" name="profile" placeholder="Enter new user profile name" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
            <a href="viewAlluserProfilePage.php" class="back-button">Back to User Profile Management</a> </form>
    </div>

</body>
</html>