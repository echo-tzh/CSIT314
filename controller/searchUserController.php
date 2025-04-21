<?php
// Include the entity class
include_once '../entity/userAccount.php';
require_once '../entity/userProfile.php';

class SearchUserController {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    public function searchUserAccount($searchTerm) {
        // Create UserAccount entity
        $userAccount = new UserAccount($this->db);
        
        if (empty($searchTerm)) {
            // Return all users if search term is empty
            return $userAccount->getAllUsers();
        } else {
            // Return filtered results
            return $userAccount->search($searchTerm);
        }
    }
}

// Process AJAX request if this file is called directly
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "csit314";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Create controller with connection
    $controller = new SearchUserController($conn);
    $searchResults = $controller->searchUserAccount($_POST['search']);
    
    // Return results as JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    
    // Close connection
    $conn->close();
    exit;
}
?>