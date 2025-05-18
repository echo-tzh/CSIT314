<?php
require_once '../entity/bookingHistory.php';

class getDailyReportController {
    public function getDailyReport(): array {
        $bookingHistory = new bookingHistory();
        return $bookingHistory->getDailyReport();
    }
}
