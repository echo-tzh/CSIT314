<?php
require_once '../entity/bookingHistory.php';
require_once '../entity/cleaningCategory.php';

class ViewFilteredHistoryController {

    public function getAllCategories(): array {
        $categoryEntity = new cleaningCategory();
        return $categoryEntity->viewAllCleaningCategory(); // returns categoryID and categoryName
    }

    public function getFilteredBookingsByCategory(string $categoryID, string $homeOwnerID): array {
        $bookingEntity = new bookingHistory();
        return $bookingEntity->getFilteredBookingsByCategory($categoryID, $homeOwnerID); // Pass both params
    }
}
