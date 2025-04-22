<?php
include_once '../inc_dbconnect.php';  // Connection details included

class UserAccount {
    private $conn;

    public function __construct() {
        // Database connection handled here, no need to pass connection to constructor
        include '../inc_dbconnect.php'; // Connection setup
        $this->conn = $conn;

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function login($username, $password) {
        // Prepare SQL query to check for matching credentials
        $sql = "SELECT * FROM userAccount WHERE username = '$username' AND password = '$password' AND status = 1";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); // Return the user data
        }
        
        return false; // No matching user found
    }

    public function getAllUsers() {
        $query = "SELECT ua.userAccountID, ua.username, ua.name, ua.status, ua.userProfileID, up.userProfileName 
                  FROM userAccount ua
                  LEFT JOIN userProfile up ON ua.userProfileID = up.userProfileID";
                  
        $result = $this->conn->query($query);
        $users = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        
        return $users;
    }

    public function search($searchTerm) {
        $searchTerm = "%{$searchTerm}%"; // wildcards for LIKE query

        $query = "SELECT ua.userAccountID, ua.username, ua.name, ua.status, ua.userProfileID, up.userProfileName 
                  FROM userAccount ua
                  LEFT JOIN userProfile up ON ua.userProfileID = up.userProfileID
                  WHERE ua.username LIKE ? OR ua.name LIKE ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        return $users;
    }
}
?>
