<?php
require_once '../entity/service.php';

class viewShortlistedCountController {

    public function viewShortlistedCount(int $serviceID) {
        // Create entity object
        $serviceEntity = new Service();

        // Call entity method to get specific service
        $shortlistedCount = $serviceEntity->viewShortlistedCount($serviceID);

        return $shortlistedCount;
    }
}
?>