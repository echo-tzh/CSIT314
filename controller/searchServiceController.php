<?php
// searchServiceController.php

require_once __DIR__ . '/../entity/service.php';

class searchServiceController {
    public function searchService(string $searchTerm): array {
        $service = new service();
        return $service->searchService($searchTerm);
    }
}
?>