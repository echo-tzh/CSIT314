<?php
require_once '../entity/service.php';

class viewServiceHomeOwnerController {
    public function viewServiceHomeOwner($serviceID) {
        $serviceEntity = new Service();
        return $serviceEntity->viewServiceHomeOwner($serviceID);
    }
    
}
