<?php
require_once '../entity/report.php';

class getWeeklyReportController {
    public function getWeeklyReport(): array {
        $service = new report();
        return $service->getWeeklyReport();
    }
}