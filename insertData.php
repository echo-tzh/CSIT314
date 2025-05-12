<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CSIT314";

// Function to execute SQL safely and log results
function executeSafely($conn, $sql, $message) {
    if ($conn->query($sql) === TRUE) {
        echo "$message successful.<br>";
        return true;
    } else {
        echo "Error with $message: " . $conn->error . "<br>";
        return false;
    }
}

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if column exists function
function columnExists($conn, $tableName, $columnName) {
    $result = $conn->query("SHOW COLUMNS FROM $tableName LIKE '$columnName'");
    return $result->num_rows > 0;
}

// Generate random date in April 2025
function randomAprilDate() {
    $day = rand(1, 30);
    $hour = rand(8, 17);
    $minute = rand(0, 59);
    return "2025-04-" . sprintf("%02d", $day) . " " . sprintf("%02d", $hour) . ":" . sprintf("%02d", $minute) . ":00";
}

// Generate random price between min and max
function randomPrice($min, $max) {
    return round(($min + mt_rand() / mt_getrandmax() * ($max - $min)), 2);
}

echo "<h1>Generating Test Data</h1>";

// 1. Add 100 test user admins
echo "<h2>Adding Test User Admins</h2>";
$stmt = $conn->prepare("INSERT INTO userAccount (username, password, name, userProfileID) VALUES (?, '12345678', ?, 1)");
$stmt->bind_param("ss", $username, $name);

for ($i = 1; $i <= 100; $i++) {
    $username = "testUserAdmin" . $i;
    $name = "test User Admin " . $i;
    $stmt->execute();
    echo "Added $username<br>";
}
$stmt->close();

// 2. Add 100 test cleaners
echo "<h2>Adding Test Cleaners</h2>";
$stmt = $conn->prepare("INSERT INTO userAccount (username, password, name, userProfileID) VALUES (?, '12345678', ?, 2)");
$stmt->bind_param("ss", $username, $name);

for ($i = 1; $i <= 100; $i++) {
    $username = "testCleaner" . $i;
    $name = "test Cleaner " . $i;
    $stmt->execute();
    echo "Added $username<br>";
}
$stmt->close();

// 3. Add 100 test homeowners
echo "<h2>Adding Test Home Owners</h2>";
$stmt = $conn->prepare("INSERT INTO userAccount (username, password, name, userProfileID) VALUES (?, '12345678', ?, 3)");
$stmt->bind_param("ss", $username, $name);

for ($i = 1; $i <= 100; $i++) {
    $username = "testHomeOwner" . $i;
    $name = "test Home Owner " . $i;
    $stmt->execute();
    echo "Added $username<br>";
}
$stmt->close();

// 4. Add 100 test platform management
echo "<h2>Adding Test Platform Management</h2>";
$stmt = $conn->prepare("INSERT INTO userAccount (username, password, name, userProfileID) VALUES (?, '12345678', ?, 4)");
$stmt->bind_param("ss", $username, $name);

for ($i = 1; $i <= 100; $i++) {
    $username = "testPlatformManagement" . $i;
    $name = "test Platform Management " . $i;
    $stmt->execute();
    echo "Added $username<br>";
}
$stmt->close();

// 5. Add 100 test cleaning categories
echo "<h2>Adding Test Cleaning Categories</h2>";
$stmt = $conn->prepare("INSERT INTO cleaningCategory (categoryName, description, isDeleted) VALUES (?, ?, 0)");
$stmt->bind_param("ss", $categoryName, $description);

for ($i = 1; $i <= 100; $i++) {
    $categoryName = "test Cleaning Category " . $i;
    $description = "Test cleaning category description " . $i;
    $stmt->execute();
    echo "Added $categoryName<br>";
}
$stmt->close();

// Get the IDs of test cleaners
$result = $conn->query("SELECT userAccountID FROM userAccount WHERE username LIKE 'testCleaner%'");
$cleanerIDs = [];
while ($row = $result->fetch_assoc()) {
    $cleanerIDs[] = $row['userAccountID'];
}

// Get the IDs of test categories
$result = $conn->query("SELECT categoryID FROM cleaningCategory WHERE categoryName LIKE 'test Cleaning Category%'");
$categoryIDs = [];
while ($row = $result->fetch_assoc()) {
    $categoryIDs[] = $row['categoryID'];
}

// 6. Add 100 test services - Check if status column exists
echo "<h2>Adding Test Services</h2>";

// Check if status column exists
$statusExists = columnExists($conn, "service", "status");

if ($statusExists) {
    $stmt = $conn->prepare("INSERT INTO service (cleanerID, serviceName, description, price, serviceDate, categoryID, status, viewCount, shortlistCount, isDeleted) VALUES (?, ?, ?, ?, ?, ?, 1, 0, 0, 0)");
} else {
    $stmt = $conn->prepare("INSERT INTO service (cleanerID, serviceName, description, price, serviceDate, categoryID, viewCount, shortlistCount, isDeleted) VALUES (?, ?, ?, ?, ?, ?, 0, 0, 0)");
}

for ($i = 1; $i <= 100; $i++) {
    $cleanerID = $cleanerIDs[array_rand($cleanerIDs)];
    $serviceName = "test Service " . $i;
    $description = "Test cleaning service description " . $i;
    $price = randomPrice(30, 200);
    $serviceDate = randomAprilDate();
    $categoryID = $categoryIDs[array_rand($categoryIDs)];
    
    if ($statusExists) {
        $stmt->bind_param("issdsii", $cleanerID, $serviceName, $description, $price, $serviceDate, $categoryID, $status);
        $status = 1;
    } else {
        $stmt->bind_param("issdsi", $cleanerID, $serviceName, $description, $price, $serviceDate, $categoryID);
    }
    
    $stmt->execute();
    echo "Added $serviceName<br>";
}
$stmt->close();

// Get the IDs of test homeowners
$result = $conn->query("SELECT userAccountID FROM userAccount WHERE username LIKE 'testHomeOwner%'");
$homeownerIDs = [];
while ($row = $result->fetch_assoc()) {
    $homeownerIDs[] = $row['userAccountID'];
}

// Get the IDs of test services
$result = $conn->query("SELECT serviceID FROM service WHERE serviceName LIKE 'test Service%'");
$serviceIDs = [];
while ($row = $result->fetch_assoc()) {
    $serviceIDs[] = $row['serviceID'];
}

// 7. Add 100 test bookings
echo "<h2>Adding Test Bookings</h2>";
$stmt = $conn->prepare("INSERT INTO bookingHistory (homeOwnerID, serviceID, bookingDate) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $homeOwnerID, $serviceID, $bookingDate);

for ($i = 1; $i <= 100; $i++) {
    $homeOwnerID = $homeownerIDs[array_rand($homeownerIDs)];
    $serviceID = $serviceIDs[array_rand($serviceIDs)];
    $bookingDate = randomAprilDate();
    $stmt->execute();
    echo "Added test booking $i<br>";
}
$stmt->close();

// 8. Add some test shortlist entries (bonus)
echo "<h2>Adding Test Shortlist Entries</h2>";
$stmt = $conn->prepare("INSERT INTO shortList (homeOwnerID, serviceID) VALUES (?, ?)");
$stmt->bind_param("ii", $homeOwnerID, $serviceID);

for ($i = 1; $i <= 50; $i++) {
    $homeOwnerID = $homeownerIDs[array_rand($homeownerIDs)];
    $serviceID = $serviceIDs[array_rand($serviceIDs)];
    $stmt->execute();
    echo "Added test shortlist entry $i<br>";
}
$stmt->close();

echo "<h2>Test Data Generation Complete</h2>";
echo "<p>Successfully added:</p>";
echo "<ul>";
echo "<li>100 test user admins</li>";
echo "<li>100 test cleaners</li>";
echo "<li>100 test homeowners</li>";
echo "<li>100 test platform management users</li>";
echo "<li>100 test cleaning categories</li>";
echo "<li>100 test services</li>";
echo "<li>100 test bookings</li>";
echo "<li>50 test shortlist entries</li>";
echo "</ul>";

$conn->close();
?>