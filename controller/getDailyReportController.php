<?php
require_once '../entity/service.php';

class getDailyReportController {
    public function getDailyReport(): array {
        $service = new Service();
        return $service->getDailyReport();
    }
}
