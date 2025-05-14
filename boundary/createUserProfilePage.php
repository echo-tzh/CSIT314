<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

include '../controller/createUserProfileController.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newProfile = [
        'userProfileName' => filter_input(INPUT_POST, 'userProfileName', FILTER_SANITIZE_STRING),  // Changed key to 'userProfileName'
        'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING)
    ];

    $controller = new CreateUserProfileController();
    $result = $controller->createUserProfile($newProfile);

    if ($result) {
        $message = "<p class='success-message'>User profile successfully created!</p>";
    } else {
        $message = "<p class='error-message'>Failed to create user profile. Profile exists</p>";
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
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 10px;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: center;
    }

    label {
        display: block;
        margin-bottom: 5px;
        text-align: left;
    }

    input[type="text"], textarea {
        width: 100%;
        padding: 8px 12px;  /* Add horizontal padding */
        box-sizing: border-box;
        border: 1px solid #ddd;  /* Light gray border */
        border-radius: 8px;   /* Rounded corners */
        color: #333;       /* Default text color */
    }

    input[type="text"]::placeholder, textarea::placeholder { /* Style placeholder text */
        color: #aaa;       /* Light gray placeholder */
    }

    input[type="submit"] {
        background-color: #c0ffc0;
        color: #333;
        padding: 10px 15px;
        border: 1px solid #8fbc8f;
        cursor: pointer;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
    }

    input[type="submit"]:hover {
        background-color: #a2d149;
    }

    .back-button {
        display: inline-block;
        background-color: #c0ffc0;
        color: #333;
        padding: 10px 15px;
        border: 1px solid #8fbc8f;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        margin-top: 10px;
    }

    .back-button:hover {
        background-color: #a2d149;
    }

    .success-message {
        color: green;
        text-align: center;
    }

    .error-message {
        color: red;
        text-align: center;
    }
</style>
</head>
<body>

    <div class="form-container">
        <h2>Create User Profile</h2>
        <?php if ($message) echo $message; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="userProfileName">User Profile Name:</label>
                <input type="text" id="userProfileName" name="userProfileName" placeholder="Enter new user profile name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" placeholder="Enter new user profile description" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
            <div class="form-group">
                <a href="viewAlluserProfilePage.php" class="back-button">Back to User Profile Management</a>
            </div>
        </form>
    </div>

</body>
</html>