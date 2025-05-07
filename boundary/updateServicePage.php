<?php
session_start();
// Check if user is logged in and has the right permissions
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 2) {
    header("Location: loginPage.php");
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load controllers
require_once '../controller/viewServiceController.php';
require_once '../controller/updateServiceController.php';
require_once '../entity/cleaningCategory.php'; //  Include the Entity directly

// Get serviceID from GET
if (isset($_GET['id'])) {
    $serviceID = $_GET['id'];
} else {
    $_SESSION['status'] = "Invalid service ID."; // Changed to 'status'
    header("Location: viewAllServicePage.php");
    exit();
}

// Get service data
$viewController = new viewServiceController();
$service = $viewController->viewService($serviceID);

// Check if service exists
if (!$service) {
    $_SESSION['status'] = "Service not found."; // Changed to 'status'
    header("Location: viewAllServicePage.php");
    exit();
}

// Fetch categories
$categoryModel = new cleaningCategory(); //  Instantiate the Entity
$categories = $categoryModel->viewAllCleaningCategory(); //  Fetch categories

// Process form submission for updating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['serviceName'])) {
    // Get form data
    $newName = $_POST['serviceName'];
    $newDescription = $_POST['description'];
    $newPrice = $_POST['price'];

    // Convert the string to a DateTime object
    try {
        $newServiceDate = new DateTime($_POST['serviceDate']);
    } catch (Exception $e) {
        $_SESSION['status'] = "Invalid date format."; // Use session for error
        header("Location: updateServicePage.php?id=" . $serviceID); // Redirect back to the edit page
        exit();
    }

    $newCleanerID = $_POST['cleanerID'];
    $newCategoryID = $_POST['categoryID'];


    // If POST request with service name, update the service
    if (!isset($error)) { // Only proceed if there's no date error
        $updateController = new updateServiceController();
        $success = $updateController->updateService(
            $serviceID,
            $newName,
            $newDescription,
            $newPrice,
            $newServiceDate, // Pass the DateTime object
            $newCleanerID,
            $newCategoryID,
        );

        if ($success) {
            $_SESSION['status'] = "Service updated successfully!"; // Success message
            header("Location: viewOwnServicePage.php"); // Redirect to the list page
            exit();
        } else {
            $_SESSION['status'] = "Failed to update service."; // Error message
        }
    }
}

//Retrieve session message
if (isset($_SESSION['status'])) {
    $message = $_SESSION['status'];
    unset($_SESSION['status']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Service</title>
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
            max-width: 800px;
            margin: 0 auto;
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
            text-align: center;
        }

        .details-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .details-box p {
            font-size: 18px;
            margin: 10px 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: block;
            /* Center the button */
            margin: 20px auto;
            /* Center the button */
            width: fit-content;
            /* Fit button to text */
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .btn-secondary {
            display: block;
            width: fit-content;
            margin: 20px auto;
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Update Service</h1>

        <?php if (isset($message)): ?>
            <div class="message <?php echo (strpos($message, 'Failed') !== false) ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="details-box">
            <form method="post">
                <input type="hidden" name="serviceID" value="<?php echo $service['serviceID']; ?>">

                <div class="form-group">
                    <label for="serviceName">Service Name:</label>
                    <input type="text" id="serviceName" name="serviceName"
                        value="<?php echo htmlspecialchars($service['serviceName']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description"
                        required><?php echo htmlspecialchars($service['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price"
                        value="<?php echo htmlspecialchars($service['price']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="serviceDate">Service Date:</label>
                    <input type="datetime-local" id="serviceDate" name="serviceDate"
                        value="<?php echo date('Y-m-d\TH:i', strtotime($service['serviceDate'])); ?>" required>
                </div>

                <div class="form-group" style="display: none;">
                    <label for="cleanerID">Cleaner ID:</label>
                    <input type="hidden" id="cleanerID" name="cleanerID" value="<?php echo htmlspecialchars($service['cleanerID']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="categoryID">Category:</label>
                    <select id="categoryID" name="categoryID" required>
                        <?php
                        $categoryModel = new cleaningCategory();
                        $categories = $categoryModel->viewAllCleaningCategory();
                        foreach ($categories as $category): ?>
                            <option value="<?php echo $category['categoryID']; ?>" <?php if ($service['categoryID'] == $category['categoryID'])
                                  echo 'selected'; ?>>
                              <?php echo htmlspecialchars($category['categoryName']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-primary">Save Update</button>
            </form>
        </div>

        <a href="viewOwnServicePage.php" class="btn-secondary">Back to All Services</a>
    </div>
</body>

</html>