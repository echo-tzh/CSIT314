<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../controller/viewAllFilteredHistoryController.php';
require_once '../entity/bookingHistory.php';
require_once '../entity/cleaningCategory.php';



$controller = new ViewAllFilteredHistoryController();
$categories = $controller->getAllCategories();
$filteredBookings = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryID'])) {
    $homeOwnerID = $_SESSION['userAccountID'];
    $filteredBookings = $controller->getAllFilteredHistoryByCategory($_POST['categoryID'], $homeOwnerID);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Filtered Booking History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        h2, h3 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            margin-right: 10px;
        }

        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .btn {
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 10px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f9;
            font-weight: bold;
        }

        td {
            background-color: #fff;
        }

        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .message.success {
            background-color: #c8f7c5;
            color: #2e7d32;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .back-button {
            margin-top: 30px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Filter Booking History by Category</h2>

        <form method="POST">
            <div class="form-group">
                <label for="categoryID">Select Category:</label>
                <select name="categoryID" id="categoryID" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['categoryID'] ?>" <?= isset($_POST['categoryID']) && $_POST['categoryID'] == $cat['categoryID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['categoryName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="viewFilteredHistoryPage.php" class="btn btn-secondary">Clear</a>
        </form>

        <?php if (!empty($filteredBookings)): ?>
            <h3>Filtered Booking Results</h3>
            <table>
                <tr>
                    <th>Booking ID</th>
                    <th>Homeowner ID</th>
                    <th>Service Name</th>
                    <th>Category</th>
                    <th>Booking Date</th>
                </tr>
                <?php foreach ($filteredBookings as $row): ?>
                    <tr>
                        <td><?= $row['bookingID'] ?></td>
                        <td><?= $row['homeOwnerID'] ?></td>
                        <td><?= htmlspecialchars($row['serviceName']) ?></td>
                        <td><?= htmlspecialchars($row['categoryName']) ?></td>
                        <td><?= $row['bookingDate'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="message error">No bookings found for the selected category.</div>
        <?php endif; ?>

        <a href="homepage.php" class="btn btn-secondary back-button">Back to Homepage</a>
    </div>
</body>
</html>
