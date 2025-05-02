<?php
require_once '../controller/searchConfirmedController.php';
session_start();

if (!isset($_SESSION['userProfileID']) || $_SESSION['userProfileID'] != 2) {
    echo "<p style='color: red; font-weight: bold;'>No access rights.</p>";
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Booking History</title>
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

        h2 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .btn {
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
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

        form {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ccc;
            flex: 1;
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

        .no-results {
            margin-top: 20px;
            color: #f44336;
            font-weight: bold;
        }

        .back-button {
            margin-top: 30px;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Search Confirmed Booking History</h2>

    <form method="post">
        <input type="text" name="keyword" placeholder="Enter keyword to search" value="<?php echo isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : ''; ?>" required>
        <button type="submit" name="search" class="btn btn-primary">Search</button>
        <a href="searchHistoryPage.php" class="btn btn-secondary">Clear</a>
    </form>

    <?php
    if (isset($_POST['search'])) {
        $keyword = $_POST['keyword'];
        $controller = new searchConfirmedController();
        $results = $controller->searchConfirmedMatches($keyword);

        if (count($results) > 0) {
            echo "<table class='data-table'>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Homeowner Name</th>
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Booking Date</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($results as $row) {
                echo "<tr>
                        <td>{$row['bookingID']}</td>
                        <td>{$row['homeOwnerName']}</td>
                        <td>{$row['serviceName']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['bookingDate']}</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='no-results'>No matching records found.</p>";
        }
    }
    ?>

    <a href="homepage.php" class="btn btn-secondary back-button">Back to Homepage</a>
</div>
</body>
</html>
