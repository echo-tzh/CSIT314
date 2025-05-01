<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include search controller
include_once '../controller/searchUserController.php';

// Check if search was submitted
$searchTerm = '';
$userAccounts = [];

$controller = new SearchUserController();

if (isset($_POST['search_submit'])) {
    $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
    $userAccounts = $controller->searchUserAccount($searchTerm);
} else {
    // Default: show all accounts
    $userAccounts = $controller->searchUserAccount('');
}

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

// Include the controller for suspending user accounts
include_once '../controller/suspendAccountController.php';

// Handle the form submission for suspending
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $suspendController = new SuspendAccountController();
    $result = $suspendController->suspendAccount($_POST["id"]);

    $message = $result ? "User account suspended." : "Suspension failed.";
    $_SESSION['status'] = $message; // Set the status message to display
    header("Location: viewAlluserAccountPage.php"); // Redirect back to this page with correct name
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Account Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 p-8">

    <h1 class="text-2xl font-semibold text-center mb-6">User Account Management</h1>

    <div class="flex justify-between items-center mb-6">
        <!-- Search form on the left -->
        <form method="POST" action="" class="flex items-center flex-grow max-w-lg">
    <div class="flex items-center bg-green-100 px-4 py-2 rounded-md shadow-sm w-full">
        <span class="text-lg mr-2">☰</span>
        <input type="text" name="search" id="searchInput" placeholder="Search by username or name" 
               value="<?php echo htmlspecialchars($searchTerm); ?>" 
               class="bg-transparent outline-none flex-1" />
            <button type="submit" name="search_submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 ml-2 rounded">
            Search
            </button>
            <button type="submit" name="clear_search" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 ml-2 rounded">
            Clear
            </button>
        </div>
        </form>

        <!-- Create User Account button all the way to the right -->
        <a href="./createAccountPage.php" class="bg-green-200 hover:bg-green-300 text-sm px-4 py-2 rounded-md shadow inline-block">
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
            <tbody>
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
                    <tr><td class="px-4 py-2 border text-center" colspan="6">No user accounts found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex justify-center mt-6">
        <a href="homePage.php" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">
            Back to Home
        </a>
    </div>
</body>
</html>