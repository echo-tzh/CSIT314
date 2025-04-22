<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

require_once '../controller/updateUserProfileController.php';
require_once '../controller/viewUserProfileController.php';

$updateController = new UpdateUserProfileController();
$viewController = new ViewUserProfileController();

// Get the userProfileID from the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userProfileID = $_GET['id'];
    $profile = $viewController->viewUserProfile($userProfileID);

    if (!$profile) {
        $_SESSION['status'] = "Could not retrieve user profile for editing.";
        header("Location: viewAlluserProfilePage.php");
        exit();
    }
} else {
    $_SESSION['status'] = "No User Profile ID provided for editing.";
    header("Location: viewAlluserProfilePage.php");
    exit();
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userProfileName = $_POST['userProfileName'];
    $description = $_POST['description']; // Get the description from the form

    $updateResult = $updateController->updateUserProfile($userProfileID, $userProfileName, $description); // Pass description

    if ($updateResult) {
        $_SESSION['status'] = "User profile updated successfully!";
        header("Location: viewAlluserProfilePage.php");
        exit();
    } else {
        $_SESSION['status'] = "Failed to update user profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 p-8">

<div class="max-w-md mx-auto bg-white shadow-md rounded-md overflow-hidden">
    <div class="bg-blue-200 py-4 px-6">
        <h1 class="text-2xl font-semibold text-center">Edit User Profile</h1>
    </div>

    <div class="p-6">
        <form action="" method="post" class="space-y-4">
            <div>
                <label for="userProfileName" class="block font-medium text-gray-700">Name:</label>
                <input type="text" id="userProfileName" name="userProfileName" value="<?php echo htmlspecialchars($profile['userProfileName']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="description" class="block font-medium text-gray-700">Description:</label>
                <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?php echo htmlspecialchars($profile['description']); ?></textarea>
            </div>
            <div class="flex justify-between">
                <a href="viewAlluserProfilePage.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md shadow inline-block">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow inline-block">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>