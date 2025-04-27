<?php
include_once '../inc_dbconnect.php';

class UserAccount {
    private $conn;

    public function __construct() {
        // You can either connect here directly or include a separate db class
        include '../inc_dbconnect.php'; // Sets up $conn
        $this->conn = $conn;

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function login(string $username, string $password): array|bool {
        // Prepare SQL query to check for matching credentials
        $sql = "SELECT * FROM userAccount WHERE username = '$username' AND password = '$password' AND status = 1";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            return [
                'success' => true,
                'user_data' => $userData
            ];
        } else {
            return [
                'success' => false,
                'user_data' => null  
            ];
        }
    }

    public function createAccount($username, $password, $name, $userProfileID): bool {
        // Check if username already exists
        $checkSql = "SELECT * FROM userAccount WHERE username = ?";
        $checkStmt = $this->conn->prepare($checkSql);
        if (!$checkStmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
    
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
    
        if ($checkResult && $checkResult->num_rows > 0) {
            return false; // Username already exists
        }
    
        // Insert new user
        $sql = "INSERT INTO userAccount (username, password, name, userProfileID, status) 
                VALUES (?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
    
        $stmt->bind_param("sssi", $username, $password, $name, $userProfileID);
        return $stmt->execute();
    }
    



    public function getAllUsers():array {
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

    public function viewAccount($userID): array {
        $sql = "SELECT ua.userAccountID, ua.username, ua.name, ua.status, ua.userProfileID, up.userProfileName as profileName
                FROM userAccount ua
                LEFT JOIN userProfile up ON ua.userProfileID = up.userProfileID
                WHERE ua.userAccountID = ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return []; // return empty array on error
        }
        
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); // normal case
        }
        
        return []; // if no user found, return empty array
    }
    

    public function updateAccount($userID, $username, $name, $userProfileID):bool {

 
        
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

    public function suspendAccount($userID): bool {
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
    public function search($searchTerm): array {
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

        return $users;
    }

}
?>
