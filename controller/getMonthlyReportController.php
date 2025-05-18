<?php
require_once '../entity/bookingHistory.php';

class getMonthlyReportController {
    public function getMonthlyReport(): array {
        $bookingHistory = new bookingHistory();
        return $bookingHistory->getMonthlyReport();
    }
}
