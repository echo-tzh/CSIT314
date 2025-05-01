<?php



class Shortlist {
    private $conn;

    public function __construct() {
        // You can either connect here directly or include a separate db class
        include '../inc_dbconnect.php'; // Sets up $conn
        $this->conn = $conn;

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
        }
    
        $insertStmt->close();
        return $result;
    }
    

    public function getShortlistedServiceIds(int $homeOwnerID): array {
        $ids = [];
        $query = "SELECT serviceID FROM shortlist WHERE homeOwnerID = ?";
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
            error_log("Database prepare error: " . $this->conn->error);
            return []; // Return empty array on error
        }
    
        $stmt->bind_param("i", $homeOwnerID);
        $stmt->execute();
        $serviceId = 0; // Initialize $serviceId (or null, depending on your needs)
        $stmt->bind_result($serviceId);
    
        while ($stmt->fetch()) {
            $ids[] = $serviceId;
        }
    
        $stmt->close();
        return $ids;
    }
}
?>