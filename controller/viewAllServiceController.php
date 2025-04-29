<?php
require_once '../entity/service.php';

class viewAllServiceController {

    public function viewAllServices() {
        $serviceEntity = new Service();
        
        // Get the user's ID from the session
        $cleanerID = $_SESSION['userAccountID'] ?? null;  // Assuming userAccountID stores the cleanerID
        
        $services = $serviceEntity->viewAllServices($cleanerID);
        
        return $services;
    }
}
?>