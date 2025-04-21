<?php
class UserAccount {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function login($username, $password) {
        // Validate input
        if (empty($username) || empty($password)) {
            return false;
        }
        
        // Check credentials directly with SQL query
        $sql = "SELECT * FROM userAccount WHERE username = '$username' AND password = '$password' AND status = 1";

        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return true; // Login successful

            


        }
        
        return false; // Login failed
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





    public function viewAccount($userID) {
        // Validate input
        if (empty($userID) || !is_numeric($userID)) {
            return false;
        }
        
        // Use prepared statement to prevent SQL injection
        $sql = "SELECT ua.userAccountID, ua.username, ua.name, ua.status, ua.userProfileID, up.userProfileName as profileName
                FROM userAccount ua
                LEFT JOIN userProfile up ON ua.userProfileID = up.userProfileID
                WHERE ua.userAccountID = ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return false;
    }



    public function createAccount($username, $password, $name, $userProfileID) {
        // Validate input
        if (empty($username) || empty($password) || empty($name) || empty($userProfileID)) {
            return false;
        }
        
        // Check if username already exists
        $checkSql = "SELECT * FROM userAccount WHERE username = '$username'";
        $checkResult = $this->conn->query($checkSql);
        
        if ($checkResult && $checkResult->num_rows > 0) {
            return false; // Username already exists
        }
        
        // Insert new account
        $sql = "INSERT INTO userAccount (username, password, name, userProfileID) 
                VALUES ('$username', '$password', '$name', $userProfileID)";
        
        $result = $this->conn->query($sql);
        
        if (!$result) {
            // Log the error for debugging
            error_log("Database error: " . $this->conn->error);
        }
        
        return $result ? true : false;
    }

    public function updateAccount($userID, $username, $name, $userProfileID) {
        // Validate input
        if (empty($userID) || !is_numeric($userID) || empty($username) || empty($name) || empty($userProfileID)) {
            return false;
        }
        
        // Check if username already exists for different users
        $checkSql = "SELECT * FROM userAccount WHERE username = ? AND userAccountID != ?";
        $checkStmt = $this->conn->prepare($checkSql);
        
        if (!$checkStmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
        
        $checkStmt->bind_param("si", $username, $userID);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult && $checkResult->num_rows > 0) {
            return false; // Username already exists for another user
        }
        
        // Update account
        $sql = "UPDATE userAccount SET username = ?, name = ?, userProfileID = ? WHERE userAccountID = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param("ssii", $username, $name, $userProfileID, $userID);
        $result = $stmt->execute();
        
        if (!$result) {
            error_log("Database error: " . $stmt->error);
        }
        
        return $result;
    }





    public function suspendAccount($userID) {
        $sql = "UPDATE userAccount SET status = 0 WHERE userAccountID = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
    
        $stmt->bind_param("i", $userID); // Only one integer param now
        $result = $stmt->execute();
    
        if (!$result) {
            error_log("Database error: " . $stmt->error);
        }
    
        return $result;
    }
    
    public function search($searchTerm) {
        $searchTerm = "%{$searchTerm}%"; // Add wildcards for LIKE query
        
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

        $stmt->close();
        return $users;
    }







}
?>