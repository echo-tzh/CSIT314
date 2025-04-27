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

// Create service table with viewCount column
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


$checkColumn = $conn->query("SHOW COLUMNS FROM cleaningCategory LIKE 'description'");
if ($checkColumn->num_rows == 0) {
    $sql = "ALTER TABLE cleaningCategory ADD COLUMN description VARCHAR(255)";
    echo $conn->query($sql) ? "'description' column added to 'cleaningCategory'.<br>" : "Error adding 'description' column: " . $conn->error . "<br>";
} else {
    echo "'description' column already exists in 'cleaningCategory'.<br>";
}

return $conn;

?>
