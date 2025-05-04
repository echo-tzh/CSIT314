<?php
// searchOwnServiceController.php

require_once '../entity/service.php';

class searchOwnServiceController {
    public function searchOwnService(string $searchTerm, int $userAccountID): array {
        $service = new Service();
        return $service->searchOwnService($searchTerm, $userAccountID);
    }
}
?>