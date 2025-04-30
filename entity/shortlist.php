<?php
// shortlist.php (in the entity directory)
include_once '../inc_dbconnect.php';

class Shortlist {
    private $conn;

    public function __construct() {
        $this->conn = $this->getConnection(); // Establish connection in constructor
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function getConnection() {
        if (!isset($this->conn)) {
            include '../inc_dbconnect.php';
            return $conn;
        }
        return $this->conn;
    }

    public function saveFavorite(int $homeOwnerID, int $serviceID): bool {
        // 1. Check if the favorite already exists
        if ($this->isFavoriteExists($homeOwnerID, $serviceID)) {
            return false;
        }

        // 2. If it doesn't exist, then insert
        $query = "INSERT INTO shortlist (homeOwnerID, serviceID) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log("Database prepare error: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("ii", $homeOwnerID, $serviceID);

        $result = $stmt->execute();

        if (!$result) {
            error_log("Database execute error: " . $this->conn->error);
        }

        $stmt->close();
        return $result;
    }

    private function isFavoriteExists(int $homeOwnerID, int $serviceID): bool {
        $query = "SELECT COUNT(*) FROM shortlist WHERE homeOwnerID = ? AND serviceID = ?";
        $stmt = $this->conn->prepare($query);

        // Initialize $count
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

        return $count > 0;
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