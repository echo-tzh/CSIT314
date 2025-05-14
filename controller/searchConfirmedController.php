<?php


require_once '../entity/bookingHistory.php';

class searchConfirmedController {
    public function searchConfirmedMatches(string $keyword, int $cleanerID) : array {

       
        
        $bh = new bookingHistory();
        return $bh->searchConfirmedMatches($keyword, $cleanerID);
    }
}
?>
