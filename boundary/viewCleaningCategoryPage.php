<?php
session_start();
// Check if user is logged in and has the right permissions
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 4) {
    header("Location: login.php");
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize the controller to get specific category details
require_once '../controller/viewCleaningCategoryController.php';




$controller = new viewCleaningCategoryController();
$categoryDetails = $controller->viewCleaningCategory($_POST['categoryID']);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Category</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
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

        @keyframes slideFadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .details-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .details-box p {
            font-size: 18px;
            margin: 10px 0;
        }

        .btn-secondary {
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            background-color: #e0e0e0;
            color: #333;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #d5d5d5;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Category Details</h1>

        <?php if ($categoryDetails): ?>
            <div class="details-box">
                <p><strong>Category ID:</strong> <?php echo $categoryDetails['categoryID']; ?></p>
                <p><strong>Category Name:</strong> <?php echo $categoryDetails['categoryName']; ?></p>
                <p><strong>Category Description:</strong> <?php echo $categoryDetails['description']; ?></p>
            </div>
        <?php else: ?>
            <p>Category not found.</p>
        <?php endif; ?>

        <a href="viewAllCleaningCategoryPage.php" class="btn-secondary">Back to All Categories</a>
    </div>
</body>
</html>
