<?php
require_once '../entity/service.php';

class viewOwnServiceController {

    public function viewOwnServices() {
        $serviceEntity = new service();
        
        // Get the user's ID from the session
        $cleanerID = $_SESSION['userAccountID'] ?? null;  // Assuming userAccountID stores the cleanerID
        
        $services = $serviceEntity->viewOwnServices($cleanerID);
        
        return $services;
    }
}
?>