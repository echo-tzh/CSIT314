<?php
// saveFavoriteController.php
require_once __DIR__ . '/../entity/shortlist.php';

class SaveFavoriteController {

    public function saveFavorite(int $homeOwnerID, int $serviceID): bool {
        $shortlist = new Shortlist();
        return $shortlist->saveFavorite($homeOwnerID, $serviceID);
    }
}
?>