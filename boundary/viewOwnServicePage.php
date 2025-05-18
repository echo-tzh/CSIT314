<?php
session_start();

if (!isset($_SESSION['userAccountID']) || $_SESSION['userProfileID'] != 2) {
    header("Location: loginPage.php"); 
    exit();
}



error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the controller to fetch services
require_once '../controller/viewOwnServiceController.php';
$serviceController = new viewOwnServiceController();
$services = $serviceController->viewOwnServices();

// Handle service deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_serviceID'])) {
    require_once '../controller/deleteServiceController.php';
    $deleteServiceController = new deleteServiceController();

    $serviceID = ($_POST['delete_serviceID']);
    $success = $deleteServiceController->deleteService($serviceID);

    if ($success) {
        $_SESSION['message'] = [
            
            'text' => 'Service deleted successfully.'
        ];
    } else {
        $_SESSION['message'] = [
            'text' => 'Failed to delete service.'
        ];
    }

    header("Location: viewOwnServicePage.php");
    exit();
}


// Handle search
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$userAccountID = $_SESSION['userAccountID'];
if (!empty($searchTerm)) {
    require_once '../controller/searchOwnServiceController.php';  
    $searchOwnServiceController = new searchOwnServiceController();    
    $services = $searchOwnServiceController->searchOwnService($searchTerm, $userAccountID); 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Of My Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 p-8">

    <h1 class="text-2xl font-semibold text-center mb-6">Service Management</h1>

    <?php if (isset($_SESSION["message"])): ?>
    <div class="bg-green-200 border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline"><?php echo $_SESSION["message"]["text"];
 ?></span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20">
                <title>Close</title>
                <path
                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.15 2.759-3.152a1.2 1.2 0 0 1 0 1.697z" />
            </svg>
        </span>
    </div>
    <?php unset($_SESSION["message"]); ?>
    <?php endif; ?>

    <div class="flex justify-center items-center mb-4">
    <form action="viewOwnServicePage.php" method="get"
        class="flex items-center bg-green-100 px-4 py-2 rounded-md shadow-sm w-1/2 max-w-md">
        <input type="text" name="search" placeholder="Search service name or description"
            value="<?php echo htmlspecialchars($searchTerm); ?>" class="bg-transparent outline-none flex-grow" />
    </form>
    <button type="submit" onclick="document.forms[0].submit();"
        class="bg-green-200 hover:bg-green-300 text-sm px-4 py-2 rounded-md shadow inline-block ml-2">
        Search
    </button>
   
    <button type="button" onclick="document.querySelector('input[name=\'search\']').value=''; document.forms[0].submit();"
        class="bg-red-200 hover:bg-red-300 text-sm px-4 py-2 rounded-md shadow inline-block ml-2">
        Clear
    </button>
</div>
    <div class="flex justify-center items-center mb-6">
        <a href="createServicePage.php"
            class="bg-green-200 hover:bg-green-300 text-sm px-4 py-2 rounded-md shadow inline-block">
            Create New Service
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Service Name</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Price</th>
                    <th class="px-4 py-2 border">Service Date</th>
                    <th class="px-4 py-2 border">View</th>
                    <th class="px-4 py-2 border">Update</th>
                    <th class="px-4 py-2 border">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                <tr class="<?php echo ($service['serviceID'] % 2 == 0) ? 'bg-white' : ''; ?>">
                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($service['serviceID']); ?></td>
                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($service['serviceName']); ?></td>
                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($service['description']); ?></td>
                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($service['price']); ?></td>
                    <td class="px-4 py-2 border">
                        <?php 
                            //  Format the date for display (adjust format as needed)
                            $date = new DateTime($service['serviceDate']);
                            echo htmlspecialchars($date->format('Y-m-d H:i')); 
                        ?>
                    </td>
                    <td class="px-4 py-2 border">
                        <a href="viewServicePage.php?id=<?php echo htmlspecialchars($service['serviceID']); ?>"
                            class="text-blue-500 hover:underline">View</a>
                    </td>
                    <td class="px-4 py-2 border">
                        <a href="updateServicePage.php?id=<?php echo htmlspecialchars($service['serviceID']); ?>"
                            class="text-green-500 hover:underline">Update</a>
                    </td>
                    <td class="px-4 py-2 border">
                        <form action="viewOwnServicePage.php" method="post"
                            onsubmit="return confirm('Are you sure you want to delete this service?');">
                            <input type="hidden" name="delete_serviceID" value="<?php echo htmlspecialchars($service['serviceID']); ?>">
                            <button type="submit"
                                class="text-red-500 hover:underline bg-transparent border-none cursor-pointer">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td class="px-4 py-2 border text-center" colspan="6">No services found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex justify-center mt-6">
        <a href="homepage.php"
            style="display: inline-block; padding: 10px 20px; background-color: #C0FFC0; color: black; text-decoration: none; border-radius: 5px;">
            Back to Home
        </a>
    </div>

</body>

</html>