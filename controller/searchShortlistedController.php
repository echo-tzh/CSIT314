<?php
require_once '../entity/shortlist.php';

class searchShortlistedController {
    public function searchShortlist(string $searchTerm, int $homeOwnerID): array {
        
        $shortlist = new shortlist();
        return $shortlist->searchShortlist($searchTerm, $homeOwnerID);
    }
}
?>