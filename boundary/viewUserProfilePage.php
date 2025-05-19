<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

require_once '../controller/viewUserProfileController.php';

$controller = new viewUserProfileController();

// Get the userProfileID from the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userProfileID = $_GET['id'];
    $result = $controller->viewUserProfile($userProfileID);

    if (!$result) {  //  Simplified error check
        $_SESSION['status'] = "Could not retrieve user profile.";
        header("Location: viewAlluserProfilePage.php");
        exit();
    } else {
        $profile = $result;  //  Assuming the controller returns the profile directly
    }
} else {
    $_SESSION['status'] = "No User Profile ID provided.";
    header("Location: viewAlluserProfilePage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 p-8">

<div class="max-w-md mx-auto bg-white shadow-md rounded-md overflow-hidden">
    <div class="bg-green-200 py-4 px-6">
        <h1 class="text-2xl font-semibold text-center">User Profile Details</h1>
    </div>

    <div class="p-6">
        <?php if (isset($profile)): ?>
            <div class="mb-4">
                <strong class="block font-medium text-gray-700">ID:</strong>
                <p class="mt-1"><?php echo htmlspecialchars($profile['userProfileID']); ?></p>
            </div>
            <div class="mb-4">
                <strong class="block font-medium text-gray-700">Name:</strong>
                <p class="mt-1"><?php echo htmlspecialchars($profile['userProfileName']); ?></p>
            </div>
            <div class="mb-4">
                <strong class="block font-medium text-gray-700">Description:</strong>
                <p class="mt-1"><?php echo htmlspecialchars($profile['description']); ?></p>
            </div>
        <?php else: ?>
            <p class="text-red-500">Could not retrieve user profile details.</p>
        <?php endif; ?>
        <div class="mt-4 flex justify-center">
            <a href="viewAlluserProfilePage.php" class="bg-green-200 hover:bg-green-300 text-black px-4 py-2 rounded-md shadow inline-block">
                Back
            </a>
        </div>
    </div>
</div>

</body>
</html>