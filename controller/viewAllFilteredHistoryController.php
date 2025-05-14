<?php
require_once '../entity/bookingHistory.php';
require_once '../entity/cleaningCategory.php';

class ViewAllFilteredHistoryController {



    public function getAllFilteredHistoryByCategory(int $categoryID, int $homeOwnerID): array {
        $bookingEntity = new bookingHistory();
        return $bookingEntity->getAllFilteredHistoryByCategory($categoryID, $homeOwnerID); // Pass both params
    }
}
