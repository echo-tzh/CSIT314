<?php
require_once '../entity/report.php';

class getMonthlyReportController {
    public function getMonthlyReport(): array {
        $service = new report();
        return $service->getMonthlyReport();
    }
}