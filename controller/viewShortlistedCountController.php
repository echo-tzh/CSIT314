<?php
require_once '../entity/service.php';

class viewShortlistedCountController {

    public function viewShortlistedCount(int $serviceID):array {
        // Create entity object
        $serviceEntity = new service();

        // Call entity method to get specific service
        $shortlistedCount = $serviceEntity->viewShortlistedCount($serviceID);

        return $shortlistedCount;
    }
}
?>