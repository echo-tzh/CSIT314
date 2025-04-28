<?php
include_once '../inc_dbconnect.php';

class Service {
    private $conn;

    public function __construct() {
        include '../inc_dbconnect.php';
        $this->conn = $conn;

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function createService(string $serviceName, string $description, float $price, string $serviceDate, int $cleanerID, int $categoryID): bool {
        $checkStmt = $this->conn->prepare("SELECT serviceID FROM service WHERE serviceName = ?");
        $checkStmt->bind_param("s", $serviceName);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            $checkStmt->close();
            return false;
        }
        $checkStmt->close();
        
        $insertStmt = $this->conn->prepare("INSERT INTO service (serviceName, description, price, serviceDate, cleanerID, categoryID, status, viewCount) VALUES (?, ?, ?, ?, ?, ?, 1, 0)");
        $insertStmt->bind_param("ssdssi", $serviceName, $description, $price, $serviceDate, $cleanerID, $categoryID);
        
        $success = $insertStmt->execute();
        $insertStmt->close();
        
        return true;
    }

    public function viewAllServices() {
        $services = [];
        $sql = "SELECT serviceID, serviceName, description, price, serviceDate, cleanerID, categoryID FROM service ORDER BY serviceID";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
        }
        
        return $services;
    }

    public function viewService(int $serviceID): array {
        $serviceDetails = null;

        $stmt = $this->conn->prepare("
            SELECT 
                s.serviceID, 
                s.serviceName, 
                s.description, 
                s.price, 
                s.serviceDate, 
                s.cleanerID, 
                s.categoryID,
                c.categoryName,  
                s.status, 
                s.viewCount
            FROM service s
            JOIN cleaningCategory c ON s.categoryID = c.categoryID
            WHERE s.serviceID = ?
        ");
        $stmt->bind_param("i", $serviceID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $serviceDetails = $result->fetch_assoc();
        }

        $stmt->close();
        return $serviceDetails;
    }

    public function updateService(
        int $serviceID,
        string $newName,
        string $newDescription,
        float $newPrice,
        string $newServiceDate,
        int $newCleanerID,
        int $newCategoryID,
        string $newStatus, // Include status
        int $newViewCount  // Include viewCount
    ): bool {
        $stmt = $this->conn->prepare("UPDATE service SET serviceName = ?, description = ?, price = ?, serviceDate = ?, cleanerID = ?, categoryID = ?, status = ?, viewCount = ? WHERE serviceID = ?");
        $stmt->bind_param("ssdssiisi", $newName, $newDescription, $newPrice, $newServiceDate, $newCleanerID, $newCategoryID, $newStatus, $newViewCount, $serviceID);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    public function deleteService(int $serviceID): bool {
        $stmt = $this->conn->prepare("DELETE FROM service WHERE serviceID = ?");
        if ($stmt) {
            $stmt->bind_param("i", $serviceID);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            return false;
        }
    }
}
?>