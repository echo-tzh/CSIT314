<?php
require_once '../entity/bookingHistory.php';

class viewFilteredServicesController {


    public function viewFilteredServices(int $cleanerID, int $categoryID) {
        $confirmedServices = new bookingHistory();
        
        return $confirmedServices->viewFilteredServices($cleanerID, $categoryID);
    }
}
?>