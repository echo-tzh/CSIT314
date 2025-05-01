<?php
require_once '../entity/shortlist.php';

class SearchShortlistedController {
    public function searchShortlist(string $searchTerm): array {
        $searchTerm = trim($searchTerm);
        $shortlist = new Shortlist(); // Instantiate Shortlist within the method
        return $shortlist->searchShortlist($searchTerm);
    }
}
?>