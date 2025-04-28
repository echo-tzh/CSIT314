<?php
session_start();
// Check if user is logged in and has the right permissions
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 4) {
    header("Location: login.php");
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize the controller to get categories
require_once '../controller/viewAllCleaningCategoryController.php';
$controller = new viewAllCleaningCategoryController();
$categories = $controller->viewAllCleaningCategory();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Cleaning Categories</title>
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

        .message {
            background-color: #c8f7c5;
            color: #2e7d32;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .message.success {
            background-color: #c8f7c5;
            color: #2e7d32;
        }

        /* Common button styles */
        .btn {
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            display: inline-block;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #d5d5d5;
        }

        .btn-view {
            background-color: #28a745;
            color: white;
        }

        .btn-view:hover {
            background-color: #218838;
        }

        .btn-edit {
            background-color: #ff9800;
            color: white;
        }

        .btn-edit:hover {
            background-color: #e68900;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background-color: #e53935;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th, .data-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .data-table th {
            background-color: #f4f4f9;
            font-weight: bold;
        }

        .data-table td {
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View All Cleaning Categories</h1>
        
        <?php
        // Display message if it exists
        if (isset($_SESSION['message'])) {
            echo '<div class="message ' . $_SESSION['message']['type'] . '">' . $_SESSION['message']['text'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>
        
        <a href="createCategoryPage.php" class="btn btn-primary">Create New Category</a>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="3">No categories found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['categoryID']; ?></td>
                        <td><?php echo $category['categoryName']; ?></td>
                        <td>
                            <form action="updateCleaningCategoryPage.php" method="post" style="display:inline;">
                                <input type="hidden" name="categoryID" value="<?php echo $category['categoryID']; ?>">
                                <button type="submit" class="btn btn-edit">Update Cleaning Categories</button>
                            </form>

                            <a href="#" class="btn btn-delete">Delete</a>
                            
                            <form action="viewCleaningCategoryPage.php" method="post" style="display:inline;">
                                <input type="hidden" name="categoryID" value="<?php echo $category['categoryID']; ?>">
                                <button type="submit" class="btn btn-view">View Cleaning Category Details</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <a href="homepage.php" class="btn btn-secondary">Back to Homepage</a>
    </div>
</body>
</html>
