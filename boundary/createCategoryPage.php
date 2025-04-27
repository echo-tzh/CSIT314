<?php
session_start();
// Check if user is logged in and has the right permissions
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 4) {
    header("Location: login.php");
    exit();
}

// Initialize the controller if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../controller/createCategoryController.php';
    
    $categoryData = [
        'categoryName' => trim($_POST['categoryName'])
    ];
    
    $controller = new CreateCategoryController();
    $result = $controller->createCategory($categoryData);
    
    if ($result) {
        $_SESSION['message'] = [
            'text' => "Category created successfully!",
            'type' => 'success'
        ];
    } else {
        $_SESSION['message'] = [
            'text' => "Error creating category!",
            'type' => 'error'
        ];
    }
    
    // Redirect to prevent form resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Cleaning Category</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
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
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 16px;
        }

        .message.success {
            background-color: #c8f7c5;
            color: #2e7d32;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 18px;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .btn-primary, .btn-secondary {
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #d5d5d5;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create New Cleaning Category</h1>
        
        <?php
        // Display message if it exists
        if (isset($_SESSION['message'])) {
            $messageType = $_SESSION['message']['type'];  // Get the message type (success or error)
            $messageText = $_SESSION['message']['text'];  // Get the message text
            echo '<div class="message ' . $messageType . '">' . $messageText . '</div>';
            unset($_SESSION['message']); // Clear the message after it's displayed
        }
        ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="categoryName">Category Name:</label>
                <input type="text" id="categoryName" name="categoryName" required>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn-primary">Create Category</button>
                <a href="homepage.php" class="btn-secondary">Back to Homepage</a>
            </div>
        </form>
    </div>
</body>
</html>
