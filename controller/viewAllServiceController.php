<?php
require_once '../entity/service.php';

class viewAllServiceController {

    public function viewAllServices() {
        $serviceEntity = new Service();
        $services = $serviceEntity->viewAllServices();
        return $services;
    }
}
