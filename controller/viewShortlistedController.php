<?php
// viewShortlistedController.php
include_once '../entity/shortlist.php';

class viewShortlistedController {
    public function getShortlistedServices(int $homeOwnerID): array {
        $shortlist = new shortlist();
        return $shortlist->getShortlistedServices($homeOwnerID);
    }
}
?>
