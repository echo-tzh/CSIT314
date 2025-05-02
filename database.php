<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CSIT314";

// Initial connection to MySQL server
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' exists or created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}
$conn->close();

// Reconnect to the newly created database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database selection failed: " . $conn->connect_error);
}

// Create userProfile table
$dbtable = "userProfile";
$checktable = $conn->query("SHOW TABLES LIKE '$dbtable'");
if ($checktable->num_rows == 0) {
    $sql = "CREATE TABLE $dbtable (
        userProfileID INT AUTO_INCREMENT PRIMARY KEY,
        userProfileName VARCHAR(255) NOT NULL
    )";
    echo $conn->query($sql) ? "Table '$dbtable' created successfully.<br>" : "Error creating '$dbtable': " . $conn->error . "<br>";

    // Insert default user profiles
    $sql = "INSERT INTO userProfile (userProfileID, userProfileName) VALUES
        (1, 'userAdmin'),
        (2, 'cleaner'),
        (3, 'homeOwner'),
        (4, 'platformManagement')";
    echo $conn->query($sql) ? "Default records inserted into 'userProfile'.<br>" : "Error inserting into 'userProfile': " . $conn->error . "<br>";
} else {
    echo "Table '$dbtable' already exists.<br>";
}

// Create userAccount table
$dbtable = "userAccount";
$checktable = $conn->query("SHOW TABLES LIKE '$dbtable'");
if ($checktable->num_rows == 0) {
    $sql = "CREATE TABLE $dbtable (
        userAccountID INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(255) NOT NULL,
        userProfileID INT,
        FOREIGN KEY (userProfileID) REFERENCES userProfile(userProfileID)
    )";
    echo $conn->query($sql) ? "Table '$dbtable' created successfully.<br>" : "Error creating '$dbtable': " . $conn->error . "<br>";

    // Insert default user accounts
    $sql = "INSERT INTO userAccount (userAccountID, username, password, name, userProfileID) VALUES
        (1, 'harry_tan', 'password123', 'Harry Tan', 1),
        (2, 'will_ng', 'qwerty456', 'Will Ng', 2),
        (3, 'hui_yi', 'qwerty456', 'Lim Hui Yi', 3),
        (4, 's_kumar', 'qwerty456', 'Shamugan Kumar', 4)";
    echo $conn->query($sql) ? "Default records inserted into 'userAccount'.<br>" : "Error inserting into 'userAccount': " . $conn->error . "<br>";
} else {
    echo "Table '$dbtable' already exists.<br>";
}

// Create cleaningCategory table
$dbtable = "cleaningCategory";
$checktable = $conn->query("SHOW TABLES LIKE '$dbtable'");
if ($checktable->num_rows == 0) {
    $sql = "CREATE TABLE $dbtable (
        categoryID INT AUTO_INCREMENT PRIMARY KEY,
        categoryName VARCHAR(255) NOT NULL
    )";
    echo $conn->query($sql) ? "Table '$dbtable' created successfully.<br>" : "Error creating '$dbtable': " . $conn->error . "<br>";
} else {
    echo "Table '$dbtable' already exists.<br>";
}

// Create service table
$dbtable = "service";
$checktable = $conn->query("SHOW TABLES LIKE '$dbtable'");
if ($checktable->num_rows == 0) {
    $sql = "CREATE TABLE $dbtable (
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
    echo $conn->query($sql) ? "Table '$dbtable' created successfully.<br>" : "Error creating '$dbtable': " . $conn->error . "<br>";
} else {
    echo "Table '$dbtable' already exists.<br>";
}

// Create bookingHistory table
$dbtable = "bookingHistory";
$checktable = $conn->query("SHOW TABLES LIKE '$dbtable'");
if ($checktable->num_rows == 0) {
    $sql = "CREATE TABLE $dbtable (
        bookingID INT AUTO_INCREMENT PRIMARY KEY,
        homeOwnerID INT,
        serviceID INT,
        bookingDate DATETIME,
        FOREIGN KEY (homeOwnerID) REFERENCES userAccount(userAccountID),
        FOREIGN KEY (serviceID) REFERENCES service(serviceID)
    )";
    echo $conn->query($sql) ? "Table '$dbtable' created successfully.<br>" : "Error creating '$dbtable': " . $conn->error . "<br>";
} else {
    echo "Table '$dbtable' already exists.<br>";
}

// Create shortList table
$dbtable = "shortList";
$checktable = $conn->query("SHOW TABLES LIKE '$dbtable'");
if ($checktable->num_rows == 0) {
    $sql = "CREATE TABLE $dbtable (
        shortlistID INT AUTO_INCREMENT PRIMARY KEY,
        homeOwnerID INT,
        serviceID INT,
        FOREIGN KEY (homeOwnerID) REFERENCES userAccount(userAccountID),
        FOREIGN KEY (serviceID) REFERENCES service(serviceID)
    )";
    echo $conn->query($sql) ? "Table '$dbtable' created successfully.<br>" : "Error creating '$dbtable': " . $conn->error . "<br>";
} else {
    echo "Table '$dbtable' already exists.<br>";
}

// Update existing userProfile names
$sql = "UPDATE userProfile SET userProfileName = 'User Admin' WHERE userProfileName = 'userAdmin'";
$conn->query($sql);

$sql = "UPDATE userProfile SET userProfileName = 'Cleaner' WHERE userProfileName = 'cleaner'";
$conn->query($sql);

$sql = "UPDATE userProfile SET userProfileName = 'Home Owner' WHERE userProfileName = 'homeOwner'";
$conn->query($sql);

$sql = "UPDATE userProfile SET userProfileName = 'Platform Management' WHERE userProfileName = 'platformManagement'";
$conn->query($sql);

echo "User profile names updated successfully if old values existed.<br>";

// Add 'description' column to userProfile table if it doesn't exist
$checkColumn = $conn->query("SHOW COLUMNS FROM userProfile LIKE 'description'");
if ($checkColumn->num_rows == 0) {
    $sql = "ALTER TABLE userProfile ADD COLUMN description VARCHAR(255)";
    echo $conn->query($sql) ? "'description' column added to 'userProfile'.<br>" : "Error adding 'description' column: " . $conn->error . "<br>";
} else {
    echo "'description' column already exists in 'userProfile'.<br>";
}

// Add 'status' column to userProfile table if it doesn't exist
$checkColumn = $conn->query("SHOW COLUMNS FROM userProfile LIKE 'status'");
if ($checkColumn->num_rows == 0) {
    $sql = "ALTER TABLE userProfile ADD COLUMN status INT";
    echo $conn->query($sql) ? "'status' column added to 'userProfile'.<br>" : "Error adding 'status' column: " . $conn->error . "<br>";
} else {
    echo "'status' column already exists in 'userProfile'.<br>";
}

// Add 'status' column to userAccount table if it doesn't exist
$checkColumn = $conn->query("SHOW COLUMNS FROM userAccount LIKE 'status'");
if ($checkColumn->num_rows == 0) {
    $sql = "ALTER TABLE userAccount ADD status BOOLEAN DEFAULT 1";
    echo $conn->query($sql) ? "'status' column added to 'userAccount'.<br>" : "Error adding 'status' column: " . $conn->error . "<br>";
} else {
    echo "'status' column already exists in 'userAccount'.<br>";
}

// Add 'description' column to cleaningCategory if it doesn't exist
$checkColumn = $conn->query("SHOW COLUMNS FROM cleaningCategory LIKE 'description'");
if ($checkColumn->num_rows == 0) {
    $sql = "ALTER TABLE cleaningCategory ADD COLUMN description VARCHAR(255)";
    echo $conn->query($sql) ? "'description' column added to 'cleaningCategory'.<br>" : "Error adding 'description' column: " . $conn->error . "<br>";
} else {
    echo "'description' column already exists in 'cleaningCategory'.<br>";
}

// Insert additional user accounts if they don't already exist
$checkUser = $conn->query("SELECT COUNT(*) as count FROM userAccount WHERE username = 'cleaner2'");
$row = $checkUser->fetch_assoc();

if ($row['count'] == 0) {
    $sql = "INSERT INTO userAccount (username, password, name, userProfileID) VALUES
        ('admin2', '12345678', 'User Admin 2', 1),
        ('cleaner2', '12345678', 'Cleaner 2', 2),
        ('homeowner2', '12345678', 'Home Owner 2', 3),
        ('platform2', '12345678', 'Platform Manager 2', 4)";
    echo $conn->query($sql) ? "Additional predefined users inserted into 'userAccount'.<br>" : "Error inserting additional users: " . $conn->error . "<br>";
}

// Insert cleaning categories if they don't exist
$checkCategories = $conn->query("SELECT COUNT(*) as count FROM cleaningCategory");
$row = $checkCategories->fetch_assoc();

if ($row['count'] == 0) {
    $sql = "INSERT INTO cleaningCategory (categoryID, categoryName, description) VALUES
        (1, 'Home Cleaning', 'General home cleaning services'),
        (2, 'Office Cleaning', 'Professional office cleaning services'),
        (3, 'Specialized Cleaning', 'Deep cleaning and specialized services')";
    
    echo $conn->query($sql) ? "Cleaning categories inserted.<br>" : "Error inserting cleaning categories: " . $conn->error . "<br>";
}

// Check if services already exist to avoid duplicates
$checkServices = $conn->query("SELECT COUNT(*) as count FROM service WHERE serviceName = 'Kitchen Deep Clean'");
$row = $checkServices->fetch_assoc();

if ($row['count'] == 0) {
    // Insert services with proper columns
    $sql = "INSERT INTO service (cleanerID, serviceName, description, price, serviceDate, categoryID, status, viewCount, shortlistCount) VALUES
        (2, 'Kitchen Deep Clean', 'Intensive cleaning of kitchen surfaces and appliances.', 120.00, '2025-05-05 09:00:00', 1, 1, 5, 2),
        (2, 'Bathroom Sanitization', 'Professional sanitization of bathroom areas.', 90.00, '2025-05-06 10:00:00', 1, 1, 8, 1),
        (2, 'Window Washing', 'Interior and exterior window washing for homes.', 70.00, '2025-05-07 11:00:00', 1, 1, 3, 0)";

    if ($conn->query($sql)) {
        echo "Sample services for cleaner inserted.<br>";
        
        // Get the IDs of inserted services
        $firstServiceID = $conn->insert_id;
        
        // Insert sample bookings for those services
        $sql = "INSERT INTO bookingHistory (homeOwnerID, serviceID, bookingDate) VALUES
            (3, $firstServiceID, '2025-05-05 12:00:00'),
            (3, " . ($firstServiceID + 1) . ", '2025-05-06 13:30:00'),
            (7, " . ($firstServiceID + 2) . ", '2025-05-07 15:45:00')";
        
        echo $conn->query($sql) ? "Sample bookings inserted into bookingHistory.<br>" : "Error inserting bookings: " . $conn->error . "<br>";
    } else {
        echo "Error inserting services: " . $conn->error . "<br>";
    }
} else {
    echo "Services already exist.<br>";
}

echo "Database setup completed successfully.<br>";
?>