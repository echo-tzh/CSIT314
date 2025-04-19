<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: loginPage.php");
    exit();
}

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
$sql = "SELECT userProfileID, userProfileName FROM userprofile"; // Replace 'user_profiles' with your actual table name
$result = $conn->query($sql);

$userProfiles = [];
if ($result->num_rows > 0) {
    // Fetch all rows into an associative array
    while ($row = $result->fetch_assoc()) {
        $userProfiles[] = $row;
    }
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

    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center bg-green-100 px-4 py-2 rounded-md shadow-sm w-1/2 max-w-md">
            <button class="text-lg mr-2">☰</button>
            <input type="text" placeholder="search" class="bg-transparent outline-none flex-1" />
            <button class="text-xl"></button>
        </div>
        <button class="bg-green-200 hover:bg-green-300 text-sm px-4 py-2 rounded-md shadow">Create User Profile</button>
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
                          <td class="px-4 py-2 border"><a href="#" class="text-blue-500 hover:underline">🔍 View</a></td>
                          <td class="px-4 py-2 border"><a href="#" class="text-green-500 hover:underline">✏️ Update</a></td>
                          <td class="px-4 py-2 border"><a href="#" class="text-red-500 hover:underline">🗑️ Suspend</a></td>
                      </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td class="px-4 py-2 border text-center" colspan="5">No user profiles found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex justify-center items-center mt-6 space-x-2 text-sm">
        <button class="text-gray-500 cursor-not-allowed">← Previous</button>
        <button class="bg-black text-white px-2 py-1 rounded">1</button>
        <button class="px-2 py-1">2</button>
        <button class="px-2 py-1">3</button>
        <span class="px-2">...</span>
        <button class="px-2 py-1">67</button>
        <button class="px-2 py-1">68</button>
        <button class="text-blue-600">Next →</button>
    </div>

</body>
</html>