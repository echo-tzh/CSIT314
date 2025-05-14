<?php
require_once __DIR__ . '/../entity/bookingHistory.php';


class searchUsedServiceController {
    public function searchUsedService(string $keyword ,int $homeOwnerID): array {

       
        
        $bh = new bookingHistory();
        return $bh->searchUsedService($keyword, $homeOwnerID);
    }
}
?>
