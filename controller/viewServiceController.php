<?php
require_once '../entity/service.php';

class viewServiceController {

    public function viewService(int $serviceID):array {
        // Create entity object
        $serviceEntity = new Service();

        // Call entity method to get specific service
        $serviceDetails = $serviceEntity->viewService($serviceID);

        return $serviceDetails;
    }
}
?>