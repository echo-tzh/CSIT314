<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../controller/getMonthlyReportController.php';
session_start();
// Check if user is logged in and has the right permissions
if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 4) {
    header("Location: loginPage.php");
    exit();
}
$controller = new getMonthlyReportController();
$report = $controller->getMonthlyReport();

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Monthly Booking Report</title>
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

        /* Button styles */
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

        /* Table styles */
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
    </style>
</head>
<body>
    <div class='container'>
        <h2>Monthly Booking Report</h2>";

echo "<table class='data-table'>";
echo "<thead><tr><th>Month</th><th>Total Bookings</th></tr></thead>";
echo "<tbody>";

foreach ($report as $row) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['month']) . "</td>";
    echo "<td>" . htmlspecialchars($row['totalBookings']) . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";

echo "<a href='homepage.php' class='btn btn-secondary back-button'>Back to Home</a>";

echo "</div>
</body>
</html>";
?>
