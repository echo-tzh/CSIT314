<?php
require_once '../entity/service.php';

class getWeeklyReportController {
    public function getWeeklyReport(): array {
        $service = new Service();
        return $service->getWeeklyReport();
    }
}