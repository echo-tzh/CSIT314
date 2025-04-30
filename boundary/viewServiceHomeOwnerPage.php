<?php
session_start();

if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 3) {
    header("Location: logiPage.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../controller/viewServiceHomeOwnerController.php';

$serviceDetails = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['serviceID'])) {
    $serviceID = $_POST['serviceID'];
    $controller = new viewServiceHomeOwnerController();
    $serviceDetails = $controller->viewServiceHomeOwner($serviceID);
} else {
    echo "Invalid access.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Details</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background-color: #f4f4f9;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .detail-row {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
        }

        .back-button {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
        }

        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Service Details</h2>

    <?php if ($serviceDetails): ?>
        <div class="detail-row"><span class="detail-label">Service Name:</span> <?php echo htmlspecialchars($serviceDetails['serviceName']); ?></div>
        <div class="detail-row"><span class="detail-label">Description:</span> <?php echo htmlspecialchars($serviceDetails['description']); ?></div>
        <div class="detail-row"><span class="detail-label">Price:</span> $<?php echo htmlspecialchars($serviceDetails['price']); ?></div>
        <div class="detail-row"><span class="detail-label">Date:</span> <?php echo htmlspecialchars($serviceDetails['serviceDate']); ?></div>
        <div class="detail-row"><span class="detail-label">Status:</span> <?php echo $serviceDetails['status'] == 1 ? "Available" : "Booked"; ?></div>
        
    <?php else: ?>
        <p>Service not found.</p>
    <?php endif; ?>

    <a href="viewAllServicePage.php" class="back-button">Back to All Services</a>
</div>
</body>
</html>
