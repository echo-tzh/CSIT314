<?php
require_once '../entity/bookingHistory.php';

class getWeeklyReportController {
    public function getWeeklyReport(): array {
        $bookingHistory = new bookingHistory();
        return $bookingHistory->getWeeklyReport();
    }
}