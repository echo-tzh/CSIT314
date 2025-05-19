<?php
// deleteServiceController.php

// Include the Service class
require_once '../entity/service.php';

class deleteServiceController {
    public function deleteService(int $serviceID): bool {
        $service = new service();
        return $service->deleteService($serviceID);
    }
}
?>