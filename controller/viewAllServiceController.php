<?php
require_once '../entity/service.php';

class viewAllServiceController {

    public function viewAllServices() {
        $serviceEntity = new service();
        $services = $serviceEntity->viewAllServices();
        return $services;
    }
}
