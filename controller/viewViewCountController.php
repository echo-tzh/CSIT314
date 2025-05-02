<?php
require_once '../entity/service.php';

class viewViewCountController {

    public function viewViewCount(int $serviceID) {
        // Create entity object
        $serviceEntity = new Service();

        // Call entity method to get specific service
        $viewCount = $serviceEntity->viewViewCount($serviceID);

        return $viewCount;
    }
}
?>