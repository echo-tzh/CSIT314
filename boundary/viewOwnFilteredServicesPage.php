<?php
session_start();
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 2) {
    header("Location: login.php");
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../controller/viewAllCleaningCategoryController.php';
$categoryController = new viewAllCleaningCategoryController();
$categories = $categoryController->viewAllCleaningCategory();

require_once '../controller/viewOwnFilteredServicesController.php';

$filteredServices = [];
$userAccountID = $_SESSION['userAccountID']; // Get userAccountID from session

if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $categoryID = intval($_GET['category']);
    $filteredController = new viewOwnFilteredServicesController();
    // Pass the userAccountID to the controller function
    
    $filteredServices = $filteredController->viewOwnFilteredServices($userAccountID, $categoryID);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Filtered Services</title>
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
            max-width: 600px;
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

        select, button {
            width: 100%;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }

        #results-container {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Filtered Services</h1>

        <form method="get" action="">
            <div class="form-group">
                <label for="category">Select Category:</label>
                <select name="category" id="category">
                    <?php if (empty($categories)): ?>
                        <option value="" disabled>No categories available</option>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['categoryID']; ?>" 
                            <?php if (isset($_GET['category']) && $_GET['category'] == $category['categoryID']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($category['categoryName']); ?>
                        </option>
                    <?php endforeach; ?>

                    <?php endif; ?>
                </select>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-primary">Filter Services</button>
            </div>
        </form>

        <div id="results-container">
    <?php if (!empty($filteredServices)): ?>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Homeowner</th>
                    <th>Service</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredServices as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['bookingID']) ?></td>
                        <td><?= htmlspecialchars($row['homeOwnerName']) ?></td>
                        <td><?= htmlspecialchars($row['serviceName']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>$<?= htmlspecialchars(number_format($row['price'], 2)) ?></td>
                        <td><?= htmlspecialchars($row['bookingDate']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
    <?php elseif (isset($_GET['category'])): ?>
        <p>No services found for this category.</p>
    <?php endif; ?>
    
</div>
<div class="button-group">
            <a href="homepage.php" class="btn-primary">Back to Home</a>
</div>
</div>
</body>
</html>
