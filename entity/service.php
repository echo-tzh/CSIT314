<?php

require_once __DIR__ . '/../inc_dbconnect.php';

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
        
        $insertStmt = $this->conn->prepare("INSERT INTO service (serviceName, description, price, serviceDate, cleanerID, categoryID, viewCount) VALUES (?, ?, ?, ?, ?, ?, 0)");
        $insertStmt->bind_param("ssdssi", $serviceName, $description, $price, $serviceDate, $cleanerID, $categoryID);
        
        $success = $insertStmt->execute();
        $insertStmt->close();
        
        return true;
    }

    //cleaner to view 
    public function viewOwnServices(int $cleanerID = null) {
        $services = [];
        $sql = "SELECT serviceID, serviceName, description, price, serviceDate, cleanerID, categoryID 
                FROM service 
                WHERE isDeleted = 0";  // Added condition to check for not deleted services
        
        if ($cleanerID !== null) {
            $sql .= " AND cleanerID = " . $cleanerID;  // Filter by cleaner if provided
        }
    
        $sql .= " ORDER BY serviceID";
        $result = $this->conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
        }
    
        return $services;
    }
    
    //Homeowner to view 
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
                c.categoryName  
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
    //cleaner
    public function updateService(
        int $serviceID,
        string $newName,
        string $newDescription,
        float $newPrice,
        string $newServiceDate,
        int $newCleanerID,
        int $newCategoryID,
    ): bool {
        $stmt = $this->conn->prepare("UPDATE service SET serviceName = ?, description = ?, price = ?, serviceDate = ?, cleanerID = ?, categoryID = ? WHERE serviceID = ?");
        $stmt->bind_param("ssdssii", $newName, $newDescription, $newPrice, $newServiceDate, $newCleanerID, $newCategoryID, $serviceID);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    //cleaner
    public function deleteService(int $serviceID): bool {
        // Prepare the statement to update isDeleted to 1 (soft delete)
        $stmt = $this->conn->prepare("UPDATE service SET isDeleted = 1 WHERE serviceID = ?");
        if ($stmt) {
            $stmt->bind_param("i", $serviceID);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            return false;
        }
    }


    //this is for homeowner to search
    public function searchService(string $searchTerm): array {
        $services = [];
        $searchTerm = "%" . $searchTerm . "%"; // Add wildcards for partial matching

        $stmt = $this->conn->prepare("
            SELECT serviceID, serviceName, description, price, serviceDate, cleanerID, categoryID
            FROM service
            WHERE serviceName LIKE ? OR description LIKE ?
            ORDER BY serviceID
        ");
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
        }

        $stmt->close();
        return $services;
    }


    // this is for cleaner to search 
    public function searchOwnService(string $searchTerm, int $userAccountID): array {
        $services = [];
        $searchTerm = "%" . $searchTerm . "%"; // Add wildcards for partial matching
        $stmt = $this->conn->prepare("
            SELECT serviceID, serviceName, description, price, serviceDate, cleanerID, categoryID
            FROM service
            WHERE (serviceName LIKE ? OR description LIKE ?) AND cleanerID = ?
            ORDER BY serviceID
        ");
        $stmt->bind_param("ssi", $searchTerm, $searchTerm, $userAccountID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
        }
        $stmt->close();
        return $services;
    }



    //homeowner view 
    public function viewAllServices() {
        $services = [];
        $sql = "SELECT serviceID, serviceName, description, price, serviceDate, cleanerID, categoryID 
                FROM service 
                WHERE isDeleted = 0 
                ORDER BY serviceID";
        $result = $this->conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
        }
    
        return $services;
    }
    

    public function viewServiceHomeOwner(int $serviceID): array {
       
    
        // Check if this service has already been viewed in this session
        if (!isset($_SESSION['viewedServices'])) {
            $_SESSION['viewedServices'] = [];
        }
    
        if (!in_array($serviceID, $_SESSION['viewedServices'])) {
            // Increment view count only once per session
            $updateStmt = $this->conn->prepare("UPDATE service SET viewCount = viewCount + 1 WHERE serviceID = ?");
            $updateStmt->bind_param("i", $serviceID);
            $updateStmt->execute();
            $updateStmt->close();
    
            // Mark this service as viewed
            $_SESSION['viewedServices'][] = $serviceID;
        }
    
        // Fetch service details
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


    public function viewViewCount(int $serviceID): array {
        $viewCount = null;
    
        $stmt = $this->conn->prepare("
            SELECT 
                viewCount
            FROM service 
            WHERE serviceID = ?
        ");
        $stmt->bind_param("i", $serviceID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            $viewCount = $result->fetch_assoc();
        }
    
        $stmt->close();
        return $viewCount;
    }

    public function viewShortlistedCount(int $serviceID): array {
        $shortlistedCount = null;
    
        $stmt = $this->conn->prepare("
            SELECT 
                shortlistCount
            FROM service 
            WHERE serviceID = ?
        ");
        $stmt->bind_param("i", $serviceID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            $shortlistedCount = $result->fetch_assoc();
        }
    
        $stmt->close();
        return $shortlistedCount;
    }


    

  



}
?>