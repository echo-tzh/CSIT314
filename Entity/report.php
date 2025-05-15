<?php
include_once '../inc_dbconnect.php';

class report {
    private $conn;

    public function __construct() {
        include '../inc_dbconnect.php';
        $this->conn = $conn;

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getDailyReport(): array {
        $report = [];
        $sql = "SELECT DATE(s.serviceDate) AS serviceDate,
       COUNT(*) AS totalBookings
FROM service s
GROUP BY DATE(s.serviceDate)
ORDER BY s.serviceDate DESC;

";





        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $report[] = $row;
            }
        }

        return $report;
    }


    public function getWeeklyReport(): array {
    $report = [];

    $sql = "SELECT 
    YEAR(s.serviceDate) AS year, 
    WEEK(s.serviceDate) AS week,
    COUNT(*) AS totalServices
FROM service s
GROUP BY year, week
ORDER BY year DESC, week DESC";




    $result = $this->conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $report[] = $row;
        }
    }

    return $report;
}


    public function getMonthlyReport(): array {
    $report = [];

    $sql = "SELECT DATE_FORMAT(s.serviceDate, '%M %Y') AS month,  -- '%M' gives the full month name, '%Y' gives the year
       COUNT(*) AS totalBookings
FROM service s
GROUP BY YEAR(s.serviceDate), MONTH(s.serviceDate)  -- Group by year and month separately
ORDER BY YEAR(s.serviceDate) DESC, MONTH(s.serviceDate) DESC;";




    $result = $this->conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $report[] = $row;
        }
    }

    return $report;
}

}
