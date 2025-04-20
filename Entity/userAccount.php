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
        $sql = "SELECT * FROM userAccount WHERE username = '$username' AND password = '$password'";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return true; // Login successful
        }
        
        return false; // Login failed
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
}
?>