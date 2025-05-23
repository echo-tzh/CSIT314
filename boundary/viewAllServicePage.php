<?php
session_start();

// Check if user is logged in and has the right permissions. Assuming userProfileID 3 is for admins.
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 3) {
    header("Location: loginPage.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the controller and entity
require_once '../controller/viewAllServiceController.php';
require_once '../controller/saveFavoriteController.php';
require_once '../entity/shortlist.php';

$serviceController = new viewAllServiceController();
$services = $serviceController->viewAllServices();

// Handle search
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($searchTerm)) {
    require_once '../controller/searchServiceController.php';
    $searchServiceController = new searchServiceController();
    $services = $searchServiceController->searchService($searchTerm);
}

// Get the homeOwnerID from the session
$homeOwnerID = $_SESSION['userAccountID']; 

// Handle Favorite Saving (if triggered)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_service_id'])) {
    $favoriteServiceId = $_POST['favorite_service_id'];

    // Validate (VERY IMPORTANT!)
    if ($homeOwnerID !== null && $favoriteServiceId !== null) {
        $saveFavoriteController = new saveFavoriteController();
        $result = $saveFavoriteController->saveFavorite($homeOwnerID, $favoriteServiceId);

        if ($result) {
            $_SESSION['status'] = 'Service shortlisted successfully!!'; // Use session for message
        } else {
            $_SESSION['status'] = 'Error: Service already shortlisted or an error occurred.';
        }
        header("Location: viewAllServicePage.php"); // Redirect to refresh and show message
        exit();
    } else {
        $_SESSION['status'] = 'Error: Invalid data for adding to favorites.';
        header("Location: viewAllServicePage.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Services</title>
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

        .search-container {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-container input {
            flex: 1;
            padding: 10px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .search-container button {
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 10px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .search-container button:hover {
            background-color: #45a049;
        }

        .clear-button {
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 10px;
            background-color: #e0e0e0;
            color: #333;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .clear-button:hover {
            background-color: #d5d5d5;
        }

        .back-button {
            margin-top: 30px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Service Management</h1>

        <?php if (isset($_SESSION["status"])): ?>
            <div class="message <?php echo strpos($_SESSION['status'], 'Error') === 0 ? 'error' : 'success'; ?>">
                <strong><?php echo strpos($_SESSION['status'], 'Error') === 0 ? 'Error!' : 'Success!'; ?></strong>
                <span><?php echo $_SESSION["status"]; ?></span>
            </div>
            <?php unset($_SESSION["status"]); ?>
        <?php endif; ?>

        <div class="search-container">
            <form action="viewAllServicePage.php" method="get" style="display: flex; width: 100%; gap: 10px;">
                <input type="text" name="search" placeholder="Search service name or description"
                    value="<?php echo htmlspecialchars($searchTerm); ?>"/>
                <button type="submit">Search</button>
                <a href="viewAllServicePage.php" class="clear-button">Clear</a>
            </form>
        </div>

        <a href="viewShortlistedPage.php" class="btn btn-primary">View and Search My Shortlisted</a>

        <table class="data-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Service Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Service Date</th>
                <th>View</th>
                <th>Shortlist</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($service['serviceID']); ?></td>
                        <td><?php echo htmlspecialchars($service['serviceName']); ?></td>
                        <td><?php echo htmlspecialchars($service['description']); ?></td>
                        <td><?php echo htmlspecialchars($service['price']); ?></td>
                        <td>
                            <?php
                            $date = new DateTime($service['serviceDate']);
                            echo htmlspecialchars($date->format('Y-m-d H:i'));
                            ?>
                        </td>
                        <td>
                            <form method="post" action="viewServiceHomeOwnerPage.php">
                                <input type="hidden" name="serviceID"
                                       value="<?php echo htmlspecialchars($service['serviceID']); ?>"/>
                                <button type="submit" class="btn btn-view">View</button>
                            </form>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="favorite_service_id"
                                       value="<?php echo htmlspecialchars($service['serviceID']); ?>"/>
                                <button type="submit" class="btn btn-primary btn-favorite">Shortlist</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No services found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <a href="homepage.php" class="btn btn-secondary back-button">Back to Home</a>
    </div>
</body>

</html>