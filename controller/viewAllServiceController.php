<?php
require_once '../entity/service.php'; // Make sure the path to your service entity is correct

class viewAllServiceController {

    public function viewAllServices() {
        // Create entity object
        $serviceEntity = new Service(); // Corrected class name to Service
        
        // Call entity method to get all services
        $services = $serviceEntity->viewAllServices(); // Assuming this method name in your Service entity
        
        return $services;
    }
}
?>
