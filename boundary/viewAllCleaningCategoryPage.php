<?php
session_start();
// Check if user is logged in and has the right permissions
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 4) {
    header("Location: loginPage.php");
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize the controller to get categories


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search']) && !empty(trim($_POST['search']))) {
    require_once '../controller/searchCleaningCategoryController.php';
    $searchCleaningCat = trim($_POST['search']);
    $controller = new searchCleaningCategoryController();
    $categories = $controller->searchCleaningCategory($searchCleaningCat);
} else {
    require_once '../controller/viewAllCleaningCategoryController.php';
    $controller = new viewAllCleaningCategoryController();
    $categories = $controller->viewAllCleaningCategory();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_categoryID'])) {
    require_once '../controller/deleteCleaningCategoryController.php';
    $deleteController = new deleteCleaningCategoryController();
    
    $categoryID = ($_POST['delete_categoryID']);
    $success = $deleteController->deleteCleaningCategory($categoryID);

    if ($success) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Category deleted successfully.'
        ];
    }
    
    header("Location: viewAllCleaningCategoryPage.php");
    exit();
}

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

        .back-button {
        margin-top: 30px;
        display: inline-block;
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
        <form action="viewAllCleaningCategoryPage.php" method="post" style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <input type="text" name="search" placeholder="Search categories..." value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>" style="flex: 1; padding: 10px; font-size: 16px; border-radius: 10px; border: 1px solid #ccc;">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="viewAllCleaningCategoryPage.php" class="btn btn-secondary">Clear</a>
        </form>


        
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

                            <form action="viewCleaningCategoryPage.php" method="post" style="display:inline;">
                                <input type="hidden" name="categoryID" value="<?php echo $category['categoryID']; ?>">
                                <button type="submit" class="btn btn-view">View Cleaning Category Details</button>
                            </form>




                            <form action="updateCleaningCategoryPage.php" method="post" style="display:inline;">
                                <input type="hidden" name="categoryID" value="<?php echo $category['categoryID']; ?>">
                                <button type="submit" class="btn btn-edit">Update Cleaning Categories</button>
                            </form>

                           
                            


                            <form action="viewAllCleaningCategoryPage.php" method="post" style="display:inline;">
                                <input type="hidden" name="delete_categoryID" value="<?php echo $category['categoryID']; ?>">
                                <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this category?');">Delete</button>
                            </form>


                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <a href="homepage.php" class="btn btn-secondary back-button">Back to Homepage</a>

    </div>
    
</body>
</html>
