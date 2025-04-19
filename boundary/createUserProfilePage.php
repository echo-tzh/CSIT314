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
    <title>Create User Profile</title>
    <style>
        body {
            background-color: white;
            font-family: Arial, sans-serif;
            color: #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            width: 400px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 26px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        input[type="text"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f5f5f5;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #c8facc;
            border: 1px solid #3d3d3d;
            border-radius: 8px;
            color: #000;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #b7f3bb;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Create User Profile</h2>
        <form action="../controller/createUserProfileController.php" method="post">
            <label for="profile">User Profile Name:</label>
            <input type="text" id="profile" name="profile" placeholder="Enter new user profile name" required>
            <input type="submit" value="Submit">
        </form>
    </div>

</body>
</html>