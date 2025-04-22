<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Display success message if set
if (isset($_SESSION["status"])): ?>
    <div class="bg-green-200 border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline"><?php echo $_SESSION["status"]; ?></span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.15 2.759 3.152a1.2 1.2 0 0 1 0 1.697z"/></svg>
        </span>
    </div>
    <?php unset($_SESSION["status"]); ?>
<?php endif;

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "csit314";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user accounts with their profiles
$sql = "SELECT ua.userAccountID, ua.username, ua.name, ua.status, ua.userProfileID, up.userProfileName 
        FROM userAccount ua
        LEFT JOIN userProfile up ON ua.userProfileID = up.userProfileID";
$result = $conn->query($sql);

$userAccounts = [];
if ($result->num_rows > 0) {
    // Fetch all rows into an associative array
    while ($row = $result->fetch_assoc()) {
        $userAccounts[] = $row;
    }
}

// Include the controller for suspending user accounts
include_once '../controller/suspendAccountController.php';

// Handle the form submission for suspending
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $controller = new SuspendAccountController();
    $result = $controller->suspendAccount($_POST["id"]);

    $message = $result ? "User account suspended." : "Suspension failed.";
    $_SESSION['status'] = $message; // Set the status message to display
    header("Location: viewAlluserAccountPage.php"); // Redirect back to this page with correct name
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Account Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-white text-gray-800 p-8">

    <h1 class="text-2xl font-semibold text-center mb-6">User Account Management</h1>

    <div class="flex justify-center items-center mb-6">
        <div class="flex items-center bg-green-100 px-4 py-2 rounded-md shadow-sm w-1/2 max-w-md">
            <button class="text-lg mr-2">☰</button>
            <input type="text" id="searchInput" placeholder="Search by username or name" class="bg-transparent outline-none flex-1" />
            <button class="text-xl"></button>
        </div>
        <a href="./createAccountPage.php" class="bg-green-200 hover:bg-green-300 text-sm px-4 py-2 rounded-md shadow inline-block ml-4">
            Create User Account
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Username</th>
                    <th class="px-4 py-2 border">Name</th>

                    <th class="px-4 py-2 border">View</th>
                    <th class="px-4 py-2 border">Update</th>
                    <th class="px-4 py-2 border">Suspend</th>
                </tr>
            </thead>
            <tbody id="userAccountsTableBody">
                <?php if (!empty($userAccounts)): ?>
                    <?php foreach ($userAccounts as $account): ?>
                        <tr class="<?php echo ($account['userAccountID'] % 2 == 0) ? 'bg-white' : ''; ?>">
                            <td class="px-4 py-2 border"><?php echo $account['userAccountID']; ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($account['username']); ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($account['name']); ?></td>
                     
            
                            <td class="px-4 py-2 border">
                                <form action="viewAccountPage.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $account['userAccountID']; ?>">
                                    <button type="submit" class="text-blue-500 hover:underline" style="background:none;border:none;padding:0;cursor:pointer;">View</button>
                                </form>
                            </td>
                            <td class="px-4 py-2 border">
                                <form action="updateAccountPage.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $account['userAccountID']; ?>">
                                    <button type="submit" class="text-green-500 hover:underline" style="background:none;border:none;padding:0;cursor:pointer;">Update</button>
                                </form>
                            </td>
                            <td class="px-4 py-2 border">
                                <?php if ($account['status']): ?>
                                    <form action="viewAlluserAccountPage.php" method="post" onsubmit="return confirm('Are you sure you want to suspend this user account?');">
                                        <input type="hidden" name="id" value="<?php echo $account['userAccountID']; ?>">
                                        <button type="submit" class="text-red-500 hover:underline bg-transparent border-none cursor-pointer">Suspend</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-400">Already suspended</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td class="px-4 py-2 border text-center" colspan="8">No user accounts found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex justify-center mt-6">
        <a href="homePage.php" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">
            Back to Home
        </a>
    </div>

    <script>
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            var searchText = $(this).val();
            
            // Make AJAX request to search controller
            $.ajax({
                url: '../controller/searchUserController.php',
                type: 'POST',
                data: { search: searchText },
                dataType: 'json',
                success: function(response) {
                    // Clear the current table body
                    $('#userAccountsTableBody').empty();
                    
                    // If no results found
                    if (response.length === 0) {
                        $('#userAccountsTableBody').html('<tr><td class="px-4 py-2 border text-center" colspan="8">No matching accounts found.</td></tr>');
                        return;
                    }
                    
                    // Add each result to the table
                    $.each(response, function(index, account) {
                        var bgClass = (account.userAccountID % 2 === 0) ? 'bg-white' : '';
                        var statusText = account.status == 1 ? 'Active' : 'Suspended';
                        var suspendButton = account.status == 1 ? 
                            '<form action="viewAlluserAccountPage.php" method="post" onsubmit="return confirm(\'Are you sure you want to suspend this user account?\');">' +
                            '<input type="hidden" name="id" value="' + account.userAccountID + '">' +
                            '<button type="submit" class="text-red-500 hover:underline bg-transparent border-none cursor-pointer">Suspend</button>' +
                            '</form>' : 
                            '<span class="text-gray-400">Already suspended</span>';
                            
                        var row = '<tr class="' + bgClass + '">' +
                            '<td class="px-4 py-2 border">' + account.userAccountID + '</td>' +
                            '<td class="px-4 py-2 border">' + account.username + '</td>' +
                            '<td class="px-4 py-2 border">' + account.name + '</td>' +
                            
                            
                            '<td class="px-4 py-2 border">' +
                                '<form action="viewAccountPage.php" method="post" style="display:inline;">' +
                                '<input type="hidden" name="id" value="' + account.userAccountID + '">' +
                                '<button type="submit" class="text-blue-500 hover:underline" style="background:none;border:none;padding:0;cursor:pointer;">View</button>' +
                                '</form>' +
                            '</td>' +
                            '<td class="px-4 py-2 border">' +
                                '<form action="updateUserPage.php" method="post" style="display:inline;">' +
                                '<input type="hidden" name="id" value="' + account.userAccountID + '">' +
                                '<button type="submit" class="text-green-500 hover:underline" style="background:none;border:none;padding:0;cursor:pointer;">Update</button>' +
                                '</form>' +
                            '</td>' +
                            '<td class="px-4 py-2 border">' + suspendButton + '</td>' +
                            '</tr>';
                        $('#userAccountsTableBody').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Search error:', error);
                    $('#userAccountsTableBody').html('<tr><td class="px-4 py-2 border text-center text-red-500" colspan="8">Error performing search.</td></tr>');
                }
            });
        });
    });
    </script>
</body>
</html>