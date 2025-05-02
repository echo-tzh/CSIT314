<?php


require_once '../entity/bookingHistory.php';

class searchConfirmedController {
    public function searchConfirmedMatches(string $keyword): array {

        $cleanerID = $_SESSION['userAccountID'];
        
        $bh = new bookingHistory();
        return $bh->searchConfirmedMatches($keyword, $cleanerID);
    }
}
?>
