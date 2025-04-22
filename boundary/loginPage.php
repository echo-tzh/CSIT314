<?php
session_start();
include "../controller/loginController.php";

$message = '';
ini_set('display_errors', 1); 
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Create login controller and call the login method

}
$controller = new loginController();
$user = $controller->login($username, $password);

if ($user) {
    
    $_SESSION['userAccountID'] = $user['userAccountID'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['userProfileID'] = $user['userProfileID'];
    header("Location: homepage.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - One Stop Cleaning Services</title>

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

        .heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .heading h1 {
            margin: 0;
            font-size: 32px;
            color: #333;
        }

        .heading p {
            margin: 8px 0 0;
            font-size: 18px;
            color: #555;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }

        input[type="text"],
        input[type="password"] {
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

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="heading">
    <h1>One Stop Cleaning Services</h1>
    <p>Login to your account</p>
</div>

<form action="loginPage.php" method="post">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" placeholder="Enter your username" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required>

    <input type="submit" value="Login">
</form>

<?php
// Show the error message if it exists
if ($message) {
    echo "<p class='error-message'>$message</p>";
}
?>

</body>
</html>
