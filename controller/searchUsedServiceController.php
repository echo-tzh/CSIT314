<?php
require_once '../entity/bookingHistory.php';

class searchUsedServiceController {
    public function searchUsedService(string $keyword): array {

        $homeOwnerID = $_SESSION['userAccountID'];
        
        $bh = new bookingHistory();
        return $bh->searchUsedService($keyword, $homeOwnerID);
    }
}
?>
