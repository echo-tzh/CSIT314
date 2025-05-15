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

// Connect to MySQL server
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Create database if not exists
executeSafely($conn, "CREATE DATABASE IF NOT EXISTS $dbname", "Database creation");
$conn->close();

// Reconnect to the specific database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database selection failed: " . $conn->connect_error);
}

// Check if table exists function
function tableExists($conn, $tableName) {
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    return $result->num_rows > 0;
}

// Check if column exists function
function columnExists($conn, $tableName, $columnName) {
    $result = $conn->query("SHOW COLUMNS FROM $tableName LIKE '$columnName'");
    return $result->num_rows > 0;
}

// Create tables only if they don't exist
// 1. userProfile table
if (!tableExists($conn, "userProfile")) {
    $sql = "CREATE TABLE userProfile (
    userProfileID INT AUTO_INCREMENT PRIMARY KEY,
    userProfileName VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    status INT
)";
    executeSafely($conn, $sql, "userProfile table creation");
    
    // Insert default user profiles
$sql = "INSERT INTO userProfile (userProfileID, userProfileName, status) VALUES
    (1, 'User Admin', 1),
    (2, 'Cleaner', 1),
    (3, 'Home Owner', 1),
    (4, 'Platform Management', 1)";
executeSafely($conn, $sql, "Default userProfile insertion");
} else {
    echo "Table 'userProfile' already exists.<br>";
}

// Add description column to userProfile if not exists
if (!columnExists($conn, "userProfile", "description")) {
    executeSafely($conn, "ALTER TABLE userProfile ADD COLUMN description VARCHAR(255)", 
                "Adding description column to userProfile");
}

// Add status column to userProfile if not exists
if (!columnExists($conn, "userProfile", "status")) {
    executeSafely($conn, "ALTER TABLE userProfile ADD COLUMN status INT", 
                "Adding status column to userProfile");
}

// 2. userAccount table
if (!tableExists($conn, "userAccount")) {
    $sql = "CREATE TABLE userAccount (
        userAccountID INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(255) NOT NULL,
        userProfileID INT,
        FOREIGN KEY (userProfileID) REFERENCES userProfile(userProfileID)
    )";
    executeSafely($conn, $sql, "userAccount table creation");
    
    // Insert default user accounts
    $sql = "INSERT INTO userAccount (userAccountID, username, password, name, userProfileID) VALUES
        (1, 'userAdmin', '12345678', 'Harry Tan', 1),
        (2, 'cleaner', '12345678', 'Will Ng', 2),
        (3, 'homeowner', '12345678', 'Lim Hui Yi', 3),
        (4, 'platformManagement', '12345678', 'Shamugan Kumar', 4)";
    executeSafely($conn, $sql, "Default userAccount insertion");
} else {
    echo "Table 'userAccount' already exists.<br>";
}

// Add status column to userAccount if not exists
if (!columnExists($conn, "userAccount", "status")) {
    executeSafely($conn, "ALTER TABLE userAccount ADD status BOOLEAN DEFAULT 1", 
                "Adding status column to userAccount");
}

// 3. cleaningCategory table
if (!tableExists($conn, "cleaningCategory")) {
    $sql = "CREATE TABLE cleaningCategory (
        categoryID INT AUTO_INCREMENT PRIMARY KEY,
        categoryName VARCHAR(255) NOT NULL
    )";
    executeSafely($conn, $sql, "cleaningCategory table creation");
} else {
    echo "Table 'cleaningCategory' already exists.<br>";
}

// Add description column to cleaningCategory if not exists
if (!columnExists($conn, "cleaningCategory", "description")) {
    executeSafely($conn, "ALTER TABLE cleaningCategory ADD COLUMN description VARCHAR(255)", 
                "Adding description column to cleaningCategory");
}

// 4. service table
if (!tableExists($conn, "service")) {
    $sql = "CREATE TABLE service (
        serviceID INT AUTO_INCREMENT PRIMARY KEY,
        cleanerID INT,
        serviceName VARCHAR(255) NOT NULL,
        description VARCHAR(500),
        price DECIMAL(10,2),
        serviceDate DATETIME,
        categoryID INT,
        status BOOLEAN,
        viewCount INT DEFAULT 0,
        shortlistCount INT DEFAULT 0,
        FOREIGN KEY (cleanerID) REFERENCES userAccount(userAccountID),
        FOREIGN KEY (categoryID) REFERENCES cleaningCategory(categoryID)
    )";
    executeSafely($conn, $sql, "service table creation");
    
    // Add isDeleted column
    executeSafely($conn, "ALTER TABLE service ADD COLUMN isDeleted TINYINT(1) DEFAULT 0", 
                "Adding isDeleted column to service");
} else {
    echo "Table 'service' already exists.<br>";
    
    // Make sure isDeleted column exists
    if (!columnExists($conn, "service", "isDeleted")) {
        executeSafely($conn, "ALTER TABLE service ADD COLUMN isDeleted TINYINT(1) DEFAULT 0", 
                    "Adding isDeleted column to service");
    }
}

// 5. bookingHistory table
if (!tableExists($conn, "bookingHistory")) {
    $sql = "CREATE TABLE bookingHistory (
        bookingID INT AUTO_INCREMENT PRIMARY KEY,
        homeOwnerID INT,
        serviceID INT,
        bookingDate DATETIME,
        FOREIGN KEY (homeOwnerID) REFERENCES userAccount(userAccountID),
        FOREIGN KEY (serviceID) REFERENCES service(serviceID) ON DELETE SET NULL
    )";
    executeSafely($conn, $sql, "bookingHistory table creation");
} else {
    echo "Table 'bookingHistory' already exists.<br>";
}

// 6. shortList table
if (!tableExists($conn, "shortList")) {
    $sql = "CREATE TABLE shortList (
        shortlistID INT AUTO_INCREMENT PRIMARY KEY,
        homeOwnerID INT,
        serviceID INT,
        FOREIGN KEY (homeOwnerID) REFERENCES userAccount(userAccountID),
        FOREIGN KEY (serviceID) REFERENCES service(serviceID)
    )";
    executeSafely($conn, $sql, "shortList table creation");
} else {
    echo "Table 'shortList' already exists.<br>";
}

// Insert additional user accounts if they don't exist
$result = $conn->query("SELECT COUNT(*) as count FROM userAccount WHERE username = 'cleaner2'");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $sql = "INSERT INTO userAccount (username, password, name, userProfileID) VALUES
        ('admin2', '12345678', 'User Admin 2', 1),
        ('cleaner2', '12345678', 'Cleaner 2', 2),
        ('homeowner2', '12345678', 'Home Owner 2', 3),
        ('platform2', '12345678', 'Platform Manager 2', 4)";
    executeSafely($conn, $sql, "Additional user accounts insertion");
}

// Insert cleaning categories if they don't exist
$result = $conn->query("SELECT COUNT(*) as count FROM cleaningCategory");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $sql = "INSERT INTO cleaningCategory (categoryID, categoryName, description) VALUES
        (1, 'Home Cleaning', 'General home cleaning services'),
        (2, 'Office Cleaning', 'Professional office cleaning services'),
        (3, 'Specialized Cleaning', 'Deep cleaning and specialized services')";
    executeSafely($conn, $sql, "Cleaning categories insertion");
}

// Insert services only if they don't exist
$result = $conn->query("SELECT COUNT(*) as count FROM service WHERE serviceName = 'Home Cleaning Service'");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $sql = "INSERT INTO service (cleanerID, serviceName, description, price, serviceDate, categoryID, status) VALUES
        (2, 'Home Cleaning Service', 'Standard home cleaning service', 50.00, '2025-05-05 09:00:00', 1, 0),
        (2, 'Office Cleaning Service', 'Complete office cleaning service', 100.00, '2025-05-05 10:00:00', 2, 0),
        (2, 'Intensive Cleaning', 'Complete office intensive cleaning service', 150.00, '2025-05-06 14:00:00', 2, 0)";
    executeSafely($conn, $sql, "Initial services insertion");
}

// Insert additional services only if they don't exist
$result = $conn->query("SELECT COUNT(*) as count FROM service WHERE serviceName = 'Service 1'");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $sql = "INSERT INTO service (cleanerID, serviceName, description, price, serviceDate, categoryID, status) VALUES
        (2, 'Service 1', 'Cleaning service #1', 60.0, '2025-05-04 10:00:00', 1, 1),
        (2, 'Service 2', 'Cleaning service #2', 45.0, '2025-05-05 10:00:00', 3, 1),
        (2, 'Service 3', 'Cleaning service #3', 80.0, '2025-05-06 14:00:00', 1, 1),
        (2, 'Service 4', 'Cleaning service #4', 55.0, '2025-05-07 14:00:00', 2, 1),
        (2, 'Service 5', 'Cleaning service #5', 100.0, '2025-05-08 09:00:00', 3, 1),
        (2, 'Service 6', 'Cleaning service #6', 75.0, '2025-05-09 15:00:00', 2, 1),
        (2, 'Service 7', 'Cleaning service #7', 95.0, '2025-05-10 11:00:00', 3, 1),
        (2, 'Service 8', 'Cleaning service #8', 85.0, '2025-05-11 13:00:00', 1, 1),
        (2, 'Service 9', 'Cleaning service #9', 70.0, '2025-05-12 16:00:00', 2, 1),
        (2, 'Service 10', 'Cleaning service #10', 65.0, '2025-05-13 12:00:00', 1, 1)";
    executeSafely($conn, $sql, "Additional services insertion");
}

// Insert bookings only if they don't exist
$result = $conn->query("SELECT COUNT(*) as count FROM bookingHistory");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $sql = "INSERT INTO bookingHistory (homeOwnerID, serviceID, bookingDate) VALUES
        (3, 1, '2025-05-04 10:00:00'),
        (3, 2, '2025-05-05 10:00:00'),
        (3, 3, '2025-05-06 14:00:00'),
        (3, 4, '2025-05-07 14:00:00'),
        (3, 5, '2025-05-08 09:00:00'),
        (3, 6, '2025-05-09 15:00:00'),
        (3, 7, '2025-05-10 11:00:00'),
        (3, 8, '2025-05-11 13:00:00'),
        (3, 9, '2025-05-12 16:00:00'),
        (3, 10, '2025-05-13 12:00:00')";
    executeSafely($conn, $sql, "Bookings insertion");
}




// Remove status column from service if it exists
if (columnExists($conn, "service", "status")) {
    executeSafely($conn, "ALTER TABLE service DROP COLUMN status", 
                "Removing status column from service");
}

if (!columnExists($conn, "cleaningCategory", "isDeleted")) {
    executeSafely($conn, "ALTER TABLE cleaningCategory ADD COLUMN isDeleted TINYINT(1) DEFAULT 0", 
                "Adding isDeleted column to cleaningCategory");
}


echo "<h3>Database Setup Complete</h3>";
echo "The database has been successfully set up with all necessary tables and sample data.<br>";
echo "Important Note: All service statuses have been set to 0 so they will be visible in filtered views.<br>";

$conn->close();
?>