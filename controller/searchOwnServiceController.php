<?php
// searchOwnServiceController.php

require_once '../entity/service.php';

class searchOwnServiceController {
    public function searchOwnService(string $searchTerm, int $userAccountID): array {
        $service = new service();
        return $service->searchOwnService($searchTerm, $userAccountID);
    }
}
?>