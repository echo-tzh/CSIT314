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
}
?>
