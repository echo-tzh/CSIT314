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

    //create category
    public function createCategory($categoryName, $description): bool {
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
        
        // Insert new category with description
        $insertStmt = $this->conn->prepare("INSERT INTO cleaningCategory (categoryName, description) VALUES (?, ?)");
        $insertStmt->bind_param("ss", $categoryName, $description);
        
        $success = $insertStmt->execute();
        $insertStmt->close();
        
        return true;
    }


    public function viewAllCleaningCategory() {
        $categories = [];
        
        $sql = "SELECT categoryID, categoryName FROM cleaningCategory ORDER BY categoryID";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        
        return $categories;
    }
    



}
?>
