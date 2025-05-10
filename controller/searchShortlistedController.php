<?php
require_once '../entity/shortlist.php';

class searchShortlistedController {
    public function searchShortlist(string $searchTerm): array {
        $homeOwnerID = $_SESSION['userAccountID'];
        $shortlist = new shortlist();
        return $shortlist->searchShortlist($searchTerm, $homeOwnerID);
    }
}
?>