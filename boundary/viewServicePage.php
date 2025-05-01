<?php
session_start();
// Check if user is logged in and has the right permissions
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 2) {
    header("Location: login.php");
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize the controller to get specific service details
require_once '../controller/viewServiceController.php';

// Get service ID from POST
if (isset($_GET['id'])) {
    $serviceID = (int)$_GET['id']; // Cast to integer for safety
} else {
    // Handle the case where serviceID is not provided (e.g., redirect or display an error)
    echo "Error: Service ID not provided.";
    exit;
}

$controller = new viewServiceController();
$serviceDetails = $controller->viewService($serviceID);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Service</title>
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
        max-width: 800px; /* Adjust this value as needed */
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
        text-align: center; /* Center the heading */
    }

    .details-box {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        max-width: 600px; /* Or a value that looks good within the container */
        margin: 0 auto;
    }

    .details-box p {
        font-size: 18px;
        margin: 10px 0;
    }

    .btn-secondary {
        display: block; /* Make it a block-level element */
        width: fit-content; /* Fit its content */
        margin: 20px auto; /* Center horizontally and add vertical spacing */
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
        <h1>Service Details</h1>

        <?php if ($serviceDetails) : ?>
            <div class="details-box">
                <p><strong>Service ID:</strong> <?php echo $serviceDetails['serviceID']; ?></p>
                <p><strong>Service Name:</strong> <?php echo $serviceDetails['serviceName']; ?></p>
                <p><strong>Description:</strong> <?php echo $serviceDetails['description']; ?></p>
                <p><strong>Price:</strong> <?php echo $serviceDetails['price']; ?></p>
                <p><strong>Service Date:</strong> <?php echo $serviceDetails['serviceDate']; ?></p>
                <div class="form-group" <?php if ($_SESSION['userProfileID'] == 2) echo 'style="display: none;"'; ?>>
                    <label for="cleanerID">Cleaner ID:</label>
                    <input type="text" id="cleanerID" name="cleanerID" value="<?php echo htmlspecialchars($service['cleanerID']); ?>" required readonly>
                </div>
                <p><strong>Category:</strong> <?php echo $serviceDetails['categoryName']; ?></p>
                <p><strong>Status:</strong> 
                    <?php 
                    if ($serviceDetails['status'] == 1) {
                        echo "Available";
                    } else {
                        echo "Booked"; // Or any other status you want to display for 0
                    }
                    ?>
                </p>
                <p><strong>View Count:</strong> <?php echo $serviceDetails['viewCount']; ?></p>
                <p><strong>Shortlist Count:</strong> <?php echo $serviceDetails['shortlistCount']; ?></p>  </div>
            </div>

        <?php else : ?>
            <p>Service not found.</p>
        <?php endif; ?>

        <a href="viewOwnServicePage.php" class="btn-secondary">Back to All Services</a>
    </div>
</body>

</html>