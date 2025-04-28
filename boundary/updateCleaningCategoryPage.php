<?php
session_start();
// Check if user is logged in and has the right permissions
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 4) {
    header("Location: login.php");
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load controllers
require_once '../controller/viewCleaningCategoryController.php';
require_once '../controller/updateCleaningCategoryController.php';

// Get categoryID from POST first, then from session if not in POST
if (isset($_POST['categoryID'])) {
    $categoryID = $_POST['categoryID'];
} else {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Invalid category ID.'
    ];
    header("Location: viewAllCleaningCategoryPage.php");
    exit();
}

// Get category data
$viewController = new viewCleaningCategoryController();
$category = $viewController->viewCleaningCategory($categoryID);

// Check if category exists
if (!$category) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Category not found.'
    ];
    header("Location: viewAllCleaningCategoryPage.php");
    exit();
}

// Process form submission for updating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryName'])) {
    // Get form data
    $newName = $_POST['categoryName'];
    $newDescription = $_POST['categoryDescription'];
    
    // If POST request with category name, update the category
    $updateController = new updateCleaningCategoryController();
    $success = $updateController->updateCleaningCategory($categoryID, $newName, $newDescription);
    
    if ($success) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Cleaning category updated successfully.'
        ];
        // Clear the session variable
        unset($_SESSION['current_category_id']);
        header("Location: viewAllCleaningCategoryPage.php");
        exit();
    } else {
        $error = "Failed to update category.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Cleaning Category</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 70%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.5s forwards;
        }
        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            padding: 10px 20px;
            background-color: #ccc;
            border-radius: 4px;
            font-size: 16px;
            text-align: center;
        }
        a:hover {
            background-color: #999;
        }


        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Cleaning Category</h1>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['message']['type']; ?>">
                <?php echo $_SESSION['message']['text']; ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="categoryID" value="<?php echo $categoryID; ?>">
            
            <label for="categoryName">Category Name:</label><br>
            <input type="text" id="categoryName" name="categoryName" value="<?php echo htmlspecialchars($category['categoryName']); ?>" required><br><br>
            
            <label for="categoryDescription">Category Description:</label><br>
            <textarea id="categoryDescription" name="categoryDescription" required><?php echo htmlspecialchars($category['description']); ?></textarea><br><br>
            
            <button type="submit">Update Cleaning Category</button>
        </form>
        
        <a href="viewAllCleaningCategoryPage.php">Back to Categories</a>
    </div>
</body>
</html>
