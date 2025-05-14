<?php
// viewShortlistedController.php
include_once '../entity/shortlist.php';

class ViewShortlistedController {
    public function getShortlistedServices(int $homeOwnerID): array {
        $shortlist = new Shortlist();
        return $shortlist->getShortlistedServices($homeOwnerID);
    }
}
?>
