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

    public function searchShortlist(string $searchTerm): array {
        // Get homeOwnerID from session
        if (!isset($_SESSION['userAccountID'])) {
            return []; // Return empty array if not logged in
        }
        $homeOwnerID = $_SESSION['userAccountID'];
        
        // First get the shortlisted service IDs
        $shortlistedServiceIds = $this->getShortlistedServiceIds($homeOwnerID);
        
        // If no shortlisted services, return empty array
        if (empty($shortlistedServiceIds)) {
            return [];
        }
        
        // Get service controller to fetch service details
        $serviceController = new viewAllServiceController();
        $services = $serviceController->viewAllServices();
        
        // Filter services based on shortlist and search term
        $result = [];
        foreach ($services as $service) {
            if (in_array($service['serviceID'], $shortlistedServiceIds)) {
                // Check if search term appears in any relevant fields
                if (
                    stripos($service['serviceName'], $searchTerm) !== false ||
                    stripos($service['description'], $searchTerm) !== false ||
                    stripos($service['price'], $searchTerm) !== false ||
                    stripos($service['serviceDate'], $searchTerm) !== false
                ) {
                    $result[] = $service;
                }
            }
        }
        
        return $result;
    }


}
?>