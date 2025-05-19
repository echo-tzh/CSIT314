<?php
require_once '../entity/service.php';

class viewServiceHomeOwnerController {
    public function viewServiceHomeOwner($serviceID):array{
        $serviceEntity = new service();
        return $serviceEntity->viewServiceHomeOwner($serviceID);
    }
    
}
