<?php
require_once '../entity/bookingHistory.php';
require_once '../entity/cleaningCategory.php';

class ViewAllFilteredHistoryController {

    public function getAllCategories(): array {
        $categoryEntity = new cleaningCategory();
        return $categoryEntity->viewAllCleaningCategory(); // returns categoryID and categoryName
    }

    public function getAllFilteredHistoryByCategory(string $categoryID, int $homeOwnerID): array {
        $bookingEntity = new bookingHistory();
        return $bookingEntity->getAllFilteredHistoryByCategory($categoryID, $homeOwnerID); // Pass both params
    }
}
