<?php
require_once '../entity/bookingHistory.php';
require_once '../entity/cleaningCategory.php';

class ViewFilteredHistoryController {

    public function getAllCategories(): array {
        $categoryEntity = new cleaningCategory();
        return $categoryEntity->viewAllCleaningCategory(); // returns categoryID and categoryName
    }

    public function getFilteredBookings(string $categoryID): array {
        $bookingEntity = new bookingHistory();
        // Use the method you've already defined
        return $bookingEntity->getFilteredBookingsByCategory($categoryID); // This method already exists in your BookingHistory entity
    }
}
