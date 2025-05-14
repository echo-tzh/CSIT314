<?php
require_once '../entity/service.php';

class getMonthlyReportController {
    public function getMonthlyReport(): array {
        $service = new Service();
        return $service->getMonthlyReport();
    }
}
