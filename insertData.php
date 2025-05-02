<?php
// This script generates deterministic sample records for the CSIT314 database
// Connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CSIT314";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(300); // Set timeout to 5 minutes

// Function to display progress
function showProgress($message) {
    echo $message . "<br>";
    // Force output buffer flush
    if (ob_get_level() > 0) {
        ob_flush();
        flush();
    }
}

// Function to verify table has rows
function tableHasRows($conn, $tableName) {
    $result = $conn->query("SELECT COUNT(*) as count FROM $tableName");
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

// Clear existing data except for the first 4 userProfile and userAccount records
showProgress("<h2>Clearing existing data...</h2>");
$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("DELETE FROM shortList");
showProgress("- Cleared shortList table");
$conn->query("DELETE FROM bookingHistory");
showProgress("- Cleared bookingHistory table");
$conn->query("DELETE FROM service");
showProgress("- Cleared service table");
$conn->query("DELETE FROM cleaningCategory");
showProgress("- Cleared cleaningCategory table");
$conn->query("DELETE FROM userAccount WHERE userAccountID > 4"); // Keep original 4
showProgress("- Kept original 4 user accounts, deleted others");
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

// Step 1: Insert data into cleaningCategory
showProgress("<h2>Generating Cleaning Categories...</h2>");
$categoryData = [
    ['General House Cleaning', 'Regular cleaning of residential properties'],
    ['Deep Cleaning', 'Thorough cleaning of all areas including hard-to-reach places'],
    ['Move-in/Move-out Cleaning', 'Complete cleaning service for moving properties'],
    ['Window Cleaning', 'Professional cleaning of all windows and glass surfaces'],
    ['Carpet Cleaning', 'Deep cleaning of carpets and rugs'],
    ['Kitchen Cleaning', 'Specialized cleaning of kitchen appliances and surfaces'],
    ['Bathroom Cleaning', 'Detailed cleaning of bathroom fixtures and surfaces'],
    ['Office Cleaning', 'Commercial office space cleaning services'],
    ['Post-Construction Cleaning', 'Cleanup after renovation or construction work'],
    ['Laundry Services', 'Washing, drying, and folding of clothes and linens']
];

foreach ($categoryData as $index => $category) {
    $categoryName = $category[0];
    $categoryDescription = $category[1];
    $sql = "INSERT INTO cleaningCategory (categoryName, description) VALUES ('$categoryName', '$categoryDescription')";
    if($conn->query($sql)) {
        showProgress("- Added category: $categoryName");
    } else {
        showProgress("- ERROR adding category: " . $conn->error);
    }
}

// Verify cleaning categories were created
if (!tableHasRows($conn, 'cleaningCategory')) {
    die("Failed to create cleaning categories. Cannot continue.");
}
showProgress("- Verified cleaning categories exist");

// Step 2: Insert more userAccounts (96 more to have 100 total)
showProgress("<h2>Generating User Accounts...</h2>");
$profiles = [1, 2, 3, 4]; // userProfileIDs
$names = [
    'Emma Wilson', 'Noah Thompson', 'Olivia Martinez', 'Liam Johnson', 'Ava Brown', 
    'William Davis', 'Sophia Miller', 'James Wilson', 'Isabella Jones', 'Benjamin Moore',
    'Mia Taylor', 'Lucas Anderson', 'Charlotte Thomas', 'Henry Jackson', 'Amelia White',
    'Alexander Harris', 'Harper Martin', 'Daniel Thompson', 'Evelyn Garcia', 'Matthew Rodriguez',
    'Abigail Martinez', 'Michael Anderson', 'Emily Wilson', 'Ethan Taylor', 'Elizabeth Moore',
    'Jacob Thomas', 'Mila Jackson', 'David White', 'Ella Harris', 'Joseph Martin',
    'Sofia Clark', 'John Lewis', 'Grace Lee', 'Samuel Walker', 'Victoria Hall',
    'Christopher Allen', 'Chloe Young', 'Andrew Hernandez', 'Lily King', 'Jack Wright',
    'Zoe Lopez', 'Nathan Hill', 'Layla Scott', 'Ryan Green', 'Hannah Adams',
    'Aaron Baker', 'Aria Nelson', 'Jason Campbell', 'Lucy Mitchell', 'Gabriel Roberts',
    'Audrey Carter', 'Isaac Phillips', 'Ellie Evans', 'Charles Stewart', 'Scarlett Sanchez',
    'Thomas Morris', 'Maya Rogers', 'Joshua Reed', 'Madelyn Cook', 'Tyler Morgan',
    'Zoey Peterson', 'Adam Cooper', 'Leah Bailey', 'Jonathan Reed', 'Nora Kelly',
    'Christian Bailey', 'Riley Cox', 'Jaxon Howard', 'Paisley Ward', 'Jose Richardson',
    'Quinn Gray', 'Luke Watson', 'Savannah Brooks', 'Brandon Price', 'Brooklyn James',
    'Justin Bennett', 'Skylar Watson', 'Wyatt Price', 'Sophie Foster', 'Jayden Brooks',
    'Eva Hughes', 'Jordan Richardson', 'Stella Butler', 'Leo Simpson', 'Stella Peterson',
    'Ian Foster', 'Claire Griffin', 'Connor West', 'Gabriella Howard', 'Colton Morrison',
    'Alice Cooper', 'Robert Collins', 'Caroline Simmons', 'Easton Ross', 'Kennedy Perry',
    'Chase Powell', 'Bella Butler', 'Calvin Ward', 'Naomi Hughes', 'Austin Henderson',
    'Ruby Russell', 'Miles Foster', 'Sarah Coleman', 'Cole Perry', 'Aubrey Barnes'
]; 

// Process in batches of 10
$batchSize = 10;
for ($i = 0; $i < 96; $i += $batchSize) {
    showProgress("- Adding users " . ($i+1) . " to " . min($i+$batchSize, 96));
    $batchCount = 0;
    
    for ($j = $i; $j < min($i+$batchSize, 96); $j++) {
        $name = $names[$j];
        $username = strtolower(str_replace(' ', '_', $name));
        $password = $username; // Same as username for consistency
        $profileID = $profiles[$j % 4]; // Cycle through the 4 profiles
        
        $sql = "INSERT INTO userAccount (username, password, name, userProfileID, status) VALUES ('$username', '$password', '$name', $profileID, 1)";
        if ($conn->query($sql)) {
            $batchCount++;
        } else {
            showProgress("- ERROR adding user: " . $conn->error);
        }
    }
    showProgress("  Added $batchCount users in this batch");
}

// Verify users were created
$result = $conn->query("SELECT COUNT(*) as count FROM userAccount");
$row = $result->fetch_assoc();
showProgress("- Verified user accounts: " . $row['count'] . " total accounts");

// Step 3: Generate services (100 services)
showProgress("<h2>Generating Services...</h2>");

// Get all cleaner IDs (profile ID 2)
$cleaners = [];
$result = $conn->query("SELECT userAccountID FROM userAccount WHERE userProfileID = 2");
while ($row = $result->fetch_assoc()) {
    $cleaners[] = $row['userAccountID'];
}
showProgress("- Found " . count($cleaners) . " cleaners");

// Check if we have enough cleaners
if (count($cleaners) == 0) {
    die("No cleaners found in database. Cannot continue.");
}

// Verify cleaning categories
$categoryCount = 0;
$result = $conn->query("SELECT categoryID FROM cleaningCategory ORDER BY categoryID");
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['categoryID'];
    $categoryCount++;
}
showProgress("- Verified cleaning categories: $categoryCount categories found");

if ($categoryCount == 0) {
    die("No cleaning categories found. Cannot continue.");
}

$serviceNames = [
    "Standard Home Cleaning", "Deep Clean Package", "Kitchen Sanitization", 
    "Bathroom Scrub & Shine", "Window Washing Service", "Carpet Deep Clean",
    "Move-out Cleaning", "Spring Cleaning Special", "Office Space Cleaning",
    "Post-Renovation Cleanup"
];

$serviceDescriptions = [
    "Basic cleaning service covering dusting, vacuuming, and surface cleaning",
    "Thorough cleaning of all areas including hard-to-reach spots and appliance interiors",
    "Complete kitchen cleaning including appliances, cabinets, and countertops",
    "Detailed bathroom cleaning with disinfection of all fixtures and surfaces",
    "Professional window cleaning inside and out with streak-free guarantee",
    "Deep carpet cleaning using professional equipment and eco-friendly solutions",
    "Comprehensive cleaning service for vacating tenants or homeowners",
    "Intensive cleaning service ideal for seasonal maintenance",
    "Commercial space cleaning tailored for business environments",
    "Specialized cleaning after construction or renovation projects"
];

// Process in batches of 10
for ($i = 0; $i < 100; $i += $batchSize) {
    showProgress("- Adding services " . ($i+1) . " to " . min($i+$batchSize, 100));
    $batchCount = 0;
    
    for ($j = $i; $j < min($i+$batchSize, 100); $j++) {
        $cleanerID = $cleaners[$j % count($cleaners)];
        $nameIndex = $j % count($serviceNames);
        $serviceName = $serviceNames[$nameIndex] . " #" . ($j + 1);
        $serviceDescription = $serviceDescriptions[$nameIndex];
        
        // Deterministic price between 50 and 300
        $price = 50 + ($j % 26) * 10;
        
        // Deterministic dates in 2024-2025
        $month = ($j % 12) + 1;
        $day = ($j % 28) + 1;
        $hour = ($j % 10) + 8;
        $serviceDate = "2024-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day) . " " . $hour . ":00:00";
        
        // Make sure we use a valid category ID
        $categoryID = $categories[$j % count($categories)];
        $status = 1;
        $viewCount = ($j * 7) % 100;
        $shortlistCount = ($j * 3) % 50;
        
        $sql = "INSERT INTO service (cleanerID, serviceName, description, price, serviceDate, categoryID, status, viewCount, shortlistCount) 
                VALUES ($cleanerID, '$serviceName', '$serviceDescription', $price, '$serviceDate', $categoryID, $status, $viewCount, $shortlistCount)";
        
        if ($conn->query($sql)) {
            $batchCount++;
        } else {
            showProgress("- ERROR adding service: " . $conn->error);
        }
    }
    showProgress("  Added $batchCount services in this batch");
}

// Verify services were created
$result = $conn->query("SELECT COUNT(*) as count FROM service");
$row = $result->fetch_assoc();
showProgress("- Verified services: " . $row['count'] . " total services");

// Step 4: Generate booking history (100 bookings)
showProgress("<h2>Generating Booking History...</h2>");

// Get all homeowner IDs (profile ID 3)
$homeowners = [];
$result = $conn->query("SELECT userAccountID FROM userAccount WHERE userProfileID = 3");
while ($row = $result->fetch_assoc()) {
    $homeowners[] = $row['userAccountID'];
}
showProgress("- Found " . count($homeowners) . " homeowners");

// Check if we have enough homeowners
if (count($homeowners) == 0) {
    die("No homeowners found in database. Cannot continue.");
}

// Get all service IDs
$services = [];
$result = $conn->query("SELECT serviceID FROM service ORDER BY serviceID");
while ($row = $result->fetch_assoc()) {
    $services[] = $row['serviceID'];
}
showProgress("- Found " . count($services) . " services");

if (count($services) == 0) {
    die("No services found in database. Cannot continue.");
}

// Process in batches of 10
for ($i = 0; $i < 100; $i += $batchSize) {
    showProgress("- Adding bookings " . ($i+1) . " to " . min($i+$batchSize, 100));
    $batchCount = 0;
    
    for ($j = $i; $j < min($i+$batchSize, 100); $j++) {
        $homeOwnerID = $homeowners[$j % count($homeowners)];
        $serviceID = $services[$j % count($services)];
        
        // Booking date is 1-30 days after service date
        $month = ($j % 12) + 1;
        $day = (($j % 28) + 5) % 28 + 1; // Different than service date
        $hour = (($j % 10) + 5) % 24; // Different hour
        $bookingDate = "2024-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day) . " " . $hour . ":00:00";
        
        $sql = "INSERT INTO bookingHistory (homeOwnerID, serviceID, bookingDate) VALUES ($homeOwnerID, $serviceID, '$bookingDate')";
        
        if ($conn->query($sql)) {
            $batchCount++;
        } else {
            showProgress("- ERROR adding booking: " . $conn->error);
        }
    }
    showProgress("  Added $batchCount bookings in this batch");
}

// Step 5: Generate shortlists (100 entries)
showProgress("<h2>Generating Shortlists...</h2>");

// Process in batches of 10
for ($i = 0; $i < 100; $i += $batchSize) {
    showProgress("- Adding shortlists " . ($i+1) . " to " . min($i+$batchSize, 100));
    $batchCount = 0;
    
    for ($j = $i; $j < min($i+$batchSize, 100); $j++) {
        $homeOwnerID = $homeowners[$j % count($homeowners)];
        $serviceID = $services[((($j * 17) + 5) % count($services))];
        
        $sql = "INSERT INTO shortList (homeOwnerID, serviceID) VALUES ($homeOwnerID, $serviceID)";
        
        if ($conn->query($sql)) {
            $batchCount++;
        } else {
            showProgress("- ERROR adding shortlist: " . $conn->error);
        }
    }
    showProgress("  Added $batchCount shortlists in this batch");
}

// Summary
showProgress("<h2>Data Generation Complete</h2>");
showProgress("<p>Successfully generated:</p>");
showProgress("<ul>");
showProgress("<li>10 Cleaning Categories</li>");
showProgress("<li>100 User Accounts (including 4 original accounts)</li>");
showProgress("<li>100 Services</li>");
showProgress("<li>100 Booking History Records</li>");
showProgress("<li>100 Shortlist Records</li>");
showProgress("</ul>");

showProgress("<p>The data is deterministic and will be identical each time this script is run.</p>");

$conn->close();
?>