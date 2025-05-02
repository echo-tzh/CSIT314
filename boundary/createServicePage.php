<?php
session_start();
// Check if user is logged in and has the right permissions
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 2) {
    header("Location: login.php");
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize the controller if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../controller/createServiceController.php';
    
    $serviceName = trim($_POST['serviceName']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $serviceDate = trim($_POST['serviceDate']);
    $cleanerID = trim($_POST['cleanerID']); // Added cleanerID
    $categoryID = trim($_POST['categoryID']); // Added categoryID
    
    $controller = new CreateServiceController();
    $result = $controller->createService($serviceName, $description, $price, $serviceDate, $cleanerID, $categoryID); // Pass new parameters
    
    unset($_SESSION['message']);
    if ($result) {
        $_SESSION['message'] = [
            'text' => "Service created successfully!",
            'type' => 'success'
        ];
    } else {
        $_SESSION['message'] = [
            'text' => "Error creating service!",
            'type' => 'error'
        ];
    }
    // Clear previous message


    
    // Redirect to prevent form resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}


require_once '../controller/viewAllCleaningCategoryController.php'; // Assuming you have this controller
$categoryController = new viewAllCleaningCategoryController();
$categories = $categoryController->viewAllCleaningCategory(); // method to get all categories


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Service</title>
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
            max-width: 600px; /* Adjust this value as needed */
            margin: 0 auto; /* Center the container horizontally */
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

        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            resize: vertical;
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
        <h1>Create New Service</h1>
        
        <?php
        // Display message if it exists
        if (isset($_SESSION['message'])) {
            $messageType = $_SESSION['message']['type'];
            $messageText = $_SESSION['message']['text'];
            echo '<div class="message ' . $messageType . '">' . $messageText . '</div>';
            unset($_SESSION['message']);
        }
        ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="serviceName">Service Name:</label>
                <input type="text" id="serviceName" name="serviceName" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="serviceDate">Service Date:</label>
                <input type="datetime-local" id="serviceDate" name="serviceDate" required>
            </div>

            <div class="form-group">
                <label for="categoryID">Category:</label>
                <select id="categoryID" name="categoryID" required>
                    <?php if (empty($categories)): ?>
                        <option value="" disabled>No categories available</option>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['categoryID']; ?>"><?php echo $category['categoryName']; ?></option>  <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn-primary">Create Service</button>
                <a href="viewOwnServicePage.php" class="btn-secondary">Back to View All Services</a>
            </div>
            <input type="hidden" id="cleanerID" name="cleanerID" value="<?php echo $_SESSION['userAccountID']; ?>">
        </form>
    </div>
</body>
</html>