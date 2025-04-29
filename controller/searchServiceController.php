<?php
// searchServiceController.php

require_once '../entity/service.php';

class searchServiceController {
    public function searchService(string $searchTerm): array {
        $service = new Service();
        return $service->searchService($searchTerm);
    }
}
?>