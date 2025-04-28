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

    public function viewCleaningCategory($categoryID):array {
        $categoryDetails = null;
    
        $stmt = $this->conn->prepare("SELECT categoryID, categoryName, description FROM cleaningCategory WHERE categoryID = ?");
        $stmt->bind_param("i", $categoryID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            $categoryDetails = $result->fetch_assoc();
        }
    
        $stmt->close();
        return $categoryDetails;
    }
    
    public function updateCategory($categoryID, $newName, $newDescription): bool {
        $stmt = $this->conn->prepare("UPDATE cleaningCategory SET categoryName = ?, description = ? WHERE categoryID = ?");
        $stmt->bind_param("ssi", $newName, $newDescription, $categoryID);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    public function deleteCleaningCategory($categoryID) {
        $conn = $this->conn; 
    
        $stmt = $conn->prepare("DELETE FROM cleaningcategory WHERE categoryID = ?");
        if ($stmt) {
            $stmt->bind_param("i", $categoryID);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            return false;
        }
    }

    public function searchCategory($searchCleaningCat) {
        $categories = [];
    
        $stmt = $this->conn->prepare("SELECT categoryID, categoryName FROM cleaningCategory WHERE categoryName LIKE ?");
        $searchTerm = "%" . $searchCleaningCat . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
    
        $stmt->close();
        return $categories;
    }
    
    





}
?>
