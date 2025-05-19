<?php


require_once __DIR__ . '/../inc_dbconnect.php';
class shortlist {
    private $conn;
    public function __construct() {
   
        include __DIR__ . '/../inc_dbconnect.php'; // New line

        $this->conn = $conn;

        // Ensure that the connection is successful
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }


    public function saveFavorite(int $homeOwnerID, int $serviceID): bool {
        // Check if the favorite already exists
        $query = "SELECT COUNT(*) FROM shortlist WHERE homeOwnerID = ? AND serviceID = ?";
        $stmt = $this->conn->prepare($query);
        $count = 0;
    
        if (!$stmt) {
            error_log("Database prepare error: " . $this->conn->error);
            return false;
        }
    
        $stmt->bind_param("ii", $homeOwnerID, $serviceID);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    
        if ($count > 0) {
            return false; // Already exists
        }
    
        // Insert new favorite
        $insertQuery = "INSERT INTO shortlist (homeOwnerID, serviceID) VALUES (?, ?)";
        $insertStmt = $this->conn->prepare($insertQuery);
    
        if (!$insertStmt) {
            error_log("Database prepare error: " . $this->conn->error);
            return false;
        }
    
        $insertStmt->bind_param("ii", $homeOwnerID, $serviceID);
        $result = $insertStmt->execute();
    
        if (!$result) {
            error_log("Database execute error: " . $this->conn->error);
            $insertStmt->close(); // Close the insert statement even on error
            return false;
        }
        $insertStmt->close();
    
        // Increment shortlistCount in service table
        $updateQuery = "UPDATE service SET shortlistCount = shortlistCount + 1 WHERE serviceID = ?";
        $updateStmt = $this->conn->prepare($updateQuery);
    
        if (!$updateStmt) {
            error_log("Database prepare error: " . $this->conn->error);
            return false; // Or consider throwing an exception
        }
    
        $updateStmt->bind_param("i", $serviceID);
        $updateResult = $updateStmt->execute();
    
        if (!$updateResult) {
            error_log("Database execute error: " . $this->conn->error);
        }
    
        $updateStmt->close();
    
        return $result && $updateResult; // Return true only if both insert and update succeed
    }
    
public function getShortlistedServices(int $homeOwnerID): array {
    $query = "SELECT s.* 
              FROM shortlist sl
              JOIN service s ON sl.serviceID = s.serviceID
              WHERE sl.homeOwnerID = ?
              AND s.isDeleted = 0"; 
    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        error_log("Database prepare error: " . $this->conn->error);
        return []; // Return empty array on error
    }

    $stmt->bind_param("i", $homeOwnerID);
    $stmt->execute();
    $result = $stmt->get_result();

    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    $stmt->close();
    return $services;
}


   public function searchShortlist(string $searchTerm, int $homeOwnerID): array {
    $shortlistedServices = $this->getShortlistedServices($homeOwnerID);

    $result = [];
    foreach ($shortlistedServices as $service) {
        if (
            stripos($service['serviceName'], $searchTerm) !== false ||
            stripos($service['description'], $searchTerm) !== false
        ) {
            $result[] = $service;
        }
    }

    return $result;
}



}
?>