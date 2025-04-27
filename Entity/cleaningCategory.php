<?php
include_once '../inc_dbconnect.php';

class cleaningCategory {
    private $conn;

    public function __construct() {
        // You can either connect here directly or include a separate db class
        include '../inc_dbconnect.php'; // Sets up $conn
        $this->conn = $conn;

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function createCategory($categoryName) {
        // Check if the category already exists
        $checkStmt = $this->conn->prepare("SELECT categoryID FROM cleaningCategory WHERE categoryName = ?");
        $checkStmt->bind_param("s", $categoryName);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            $checkStmt->close();
            return false;
        }
        $checkStmt->close();
        
        // Insert new category
        $insertStmt = $this->conn->prepare("INSERT INTO cleaningCategory (categoryName) VALUES (?)");
        $insertStmt->bind_param("s", $categoryName);
        
        $success = $insertStmt->execute();
        $insertStmt->close();
        
        return true;
    }



}
?>
