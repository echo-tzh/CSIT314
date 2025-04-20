<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

// Display success message if set
if (isset($_SESSION["status"])): ?>
    <div class="bg-green-200 border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline"><?php echo $_SESSION["status"]; ?></span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.15 2.759-3.152a1.2 1.2 0 0 1 0 1.697z"/></svg>
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

// Fetch data from the database
$sql = "SELECT userProfileID, userProfileName FROM userprofile";
$result = $conn->query($sql);

$userProfiles = [];
if ($result->num_rows > 0) {
    // Fetch all rows into an associative array
    while ($row = $result->fetch_assoc()) {
        $userProfiles[] = $row;
    }
}

// Include the controller (assuming it's in the correct relative path)
include_once '../controller/suspendUserProfileController.php';

// Handle the form submission for suspending
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $controller = new SuspendUserProfileController();  // Assuming this is the correct class name
    $result = $controller->suspendUserProfile($_POST["id"]); // Assuming this is the correct method

    $message = $result ? "User profile suspended." : "Suspension failed.";
    $_SESSION['status'] = $message; // Set the status message to display
    header("Location: viewAlluserProfilePage.php"); // Redirect back to this page
    exit();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 p-8">

    <h1 class="text-2xl font-semibold text-center mb-6">User Profile Management</h1>

    <div class="flex justify-center items-center mb-6">
        <div class="flex items-center bg-green-100 px-4 py-2 rounded-md shadow-sm w-1/2 max-w-md">
            <button class="text-lg mr-2">☰</button>
            <input type="text" placeholder="search" class="bg-transparent outline-none flex-1" />
            <button class="text-xl"></button>
        </div>
        <a href="./createUserProfilePage.php" class="bg-green-200 hover:bg-green-300 text-sm px-4 py-2 rounded-md shadow inline-block ml-4">
            Create User Profile
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">User Profile</th>
                    <th class="px-4 py-2 border">View</th>
                    <th class="px-4 py-2 border">Update</th>
                    <th class="px-4 py-2 border">Suspend</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($userProfiles)): ?>
                    <?php foreach ($userProfiles as $profile): ?>
                        <tr class="<?php echo ($profile['userProfileID'] % 2 == 0) ? 'bg-white' : ''; ?>">
                            <td class="px-4 py-2 border"><?php echo $profile['userProfileID']; ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($profile['userProfileName']); ?></td>
                            <td class="px-4 py-2 border"><a href="#" class="text-blue-500 hover:underline">View</a></td>
                            <td class="px-4 py-2 border"><a href="#" class="text-green-500 hover:underline">Update</a></td>
                            <td class="px-4 py-2 border">
                                <form action="viewAlluserProfilePage.php" method="post" onsubmit="return confirm('Are you sure you want to suspend this user profile?');">
                                    <input type="hidden" name="id" value="<?php echo $profile['userProfileID']; ?>">
                                    <button type="submit" class="text-red-500 hover:underline bg-transparent border-none cursor-pointer">Suspend</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td class="px-4 py-2 border text-center" colspan="5">No user profiles found.</td></tr>
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