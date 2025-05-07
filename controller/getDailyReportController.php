<?php
require_once '../entity/report.php';

class getDailyReportController {
    public function getDailyReport(): array {
        $service = new report();
        return $service->getDailyReport();
    }
}
