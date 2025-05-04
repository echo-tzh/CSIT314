<?php
require_once '../entity/bookingHistory.php';

class viewOwnFilteredServicesController {


    public function viewOwnFilteredServices(int $cleanerID, int $categoryID) {
        $confirmedServices = new bookingHistory();
        
        return $confirmedServices->viewOwnFilteredServices($cleanerID, $categoryID);
    }
}
?>