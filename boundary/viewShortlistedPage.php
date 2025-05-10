<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Check if user is logged in
if (!isset($_SESSION['userAccountID'])) {
    header("Location: loginPage.php");
    exit();
}

// Include the controllers
require_once '../controller/viewShortlistedController.php';
require_once '../controller/searchShortlistedController.php';

// Get the homeOwnerID from the session
$homeOwnerID = $_SESSION['userAccountID'];

// Initialize controllers

$searchController = new searchShortlistedController();

// Initialize variables
$searchTerm = '';
$shortlistedServices = [];

// Check if a search was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
    
    if (!empty($searchTerm)) {
        // Use the search controller to find matching shortlisted services
        $shortlistedServices = $searchController->searchShortlist($searchTerm, $homeOwnerID);
    }

} 
    // Initial page load - get all shortlisted services
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shortlisted Services</title>
    <style>
        /* Import styles from viewAllServicePage.php */
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
        
        .no-results {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 10px;
            color: #6c757d;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Shortlisted Services</h1>

        <?php if (isset($_SESSION['shortlist_message'])): ?>
            <div class="message <?php echo strpos($_SESSION['shortlist_message'], 'Error') === 0 ? 'error' : 'success'; ?>">
                <strong><?php echo strpos($_SESSION['shortlist_message'], 'Error') === 0 ? 'Error!' : 'Success!'; ?></strong>
                <span><?php echo $_SESSION['shortlist_message']; ?></span>
            </div>
            <?php unset($_SESSION['shortlist_message']); // Clear the message ?>
        <?php endif; ?>

        <!-- Search form using POST method -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="search-container" style="display: flex; width: 100%; gap: 10px;">
            <input type="text" name="search" placeholder="Search services..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn-primary">Search</button>
            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="clear-button">Clear</a>
        </form>

        <?php if (empty($shortlistedServices)): ?>
            <?php if (!empty($searchTerm)): ?>
                <div class="no-results">No services found matching "<?php echo htmlspecialchars($searchTerm); ?>"</div>
            <?php else: ?>
                <p>Key in services to start searching</p>
            <?php endif; ?>
        <?php else: ?>
            <?php if (!empty($searchTerm)): ?>
                <p>Showing results for: "<?php echo htmlspecialchars($searchTerm); ?>"</p>
            <?php endif; ?>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Service Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($shortlistedServices as $service): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($service['serviceID']); ?></td>
                            <td><?php echo htmlspecialchars($service['serviceName']); ?></td>
                            <td><?php echo htmlspecialchars($service['description']); ?></td>
                            <td><?php echo htmlspecialchars($service['price']); ?></td>
                            <td><?php echo htmlspecialchars($service['serviceDate']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="viewAllServicePage.php" class="btn btn-secondary back-button">Back to Services</a>
    </div>
</body>
</html>