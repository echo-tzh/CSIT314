<?php

class bookingHistory {
    private $conn;

    

    public function __construct() {
        // You can either connect here directly or include a separate db class
        include '../inc_dbconnect.php'; // Sets up $conn
        $this->conn = $conn;

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }


    public function searchConfirmedMatches(string $keyword, int $cleanerID): array {
        $results = [];

        $sql = "SELECT bh.bookingID, ua.name AS homeOwnerName, s.serviceName, s.description, s.price, bh.bookingDate
        FROM bookingHistory bh
        JOIN service s ON bh.serviceID = s.serviceID
        JOIN userAccount ua ON bh.homeOwnerID = ua.userAccountID
        WHERE s.cleanerID = ?
        AND (s.serviceName LIKE ? OR s.description LIKE ?)";

        
        
        $stmt = $this->conn->prepare($sql);
        $likeKeyword = '%' . $keyword . '%';
        $stmt->bind_param("iss", $cleanerID, $likeKeyword, $likeKeyword);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }

        $stmt->close();
        return $results;
    }

    //for homeowner
    public function searchUsedService(string $keyword, int $homeOwnerID): array {
        $results = [];

    $sql = "SELECT bh.bookingID,
                   ua.name AS cleanerName,  -- Alias for cleaner's name
                   s.serviceName,
                   s.description,
                   s.price,
                   bh.bookingDate
            FROM bookingHistory bh
            JOIN service s ON bh.serviceID = s.serviceID
            JOIN userAccount ua ON s.cleanerID = ua.userAccountID  -- Join to get cleaner's name
            WHERE bh.homeOwnerID = ?
              AND (s.serviceName LIKE ? OR s.description LIKE ?)
              ";

    $stmt = $this->conn->prepare($sql);
    $likeKeyword = '%' . $keyword . '%';
    $stmt->bind_param("iss", $homeOwnerID, $likeKeyword, $likeKeyword);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    $stmt->close();
    return $results;
    }

    //this is for cleaner to view filtered services.
    public function viewOwnFilteredServices(int $cleanerID, int $categoryID) : array {
        $results = [];
        $sql = "SELECT bh.bookingID, ua.name AS homeOwnerName, s.serviceName, s.description, s.price, bh.bookingDate
                FROM bookingHistory bh
                JOIN service s ON bh.serviceID = s.serviceID
                JOIN userAccount ua ON bh.homeOwnerID = ua.userAccountID
                WHERE s.cleanerID= ? 
                AND s.categoryID = ?
                ";  // Added cleanerID filter
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $cleanerID, $categoryID); //  Bind both parameters
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
    
            $stmt->close();
            return $results;
    
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    
    //Get filtered, this is for homeowner
    public function getAllFilteredHistoryByCategory(int $categoryID, int $homeOwnerID): array {
        $results = [];
        $stmt = $this->conn->prepare("
            SELECT b.bookingID, b.homeOwnerID, s.serviceName, c.categoryName, b.bookingDate
            FROM bookingHistory b
            JOIN service s ON b.serviceID = s.serviceID
            JOIN cleaningCategory c ON s.categoryID = c.categoryID
            WHERE s.categoryID = ? 
            AND b.homeOwnerID = ?
        ");
        
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        
        $stmt->bind_param("ii", $categoryID, $homeOwnerID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        
        $stmt->close();
        return $results;
    }



}
?>
