<?php
// saveFavoriteController.php
require_once __DIR__ . '/../entity/shortlist.php';

class saveFavoriteController {

    public function saveFavorite(int $homeOwnerID, int $serviceID): bool {
        $shortlist = new shortlist();
        return $shortlist->saveFavorite($homeOwnerID, $serviceID);
    }
}
?>