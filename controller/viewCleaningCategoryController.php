<?php
require_once '../entity/cleaningCategory.php';

class viewAllCleaningCategoryController {

    public function viewCleaningCategory($categoryID) {
        // Create entity object
        $categoryEntity = new cleaningCategory();

        // Call entity method to get specific category
        $categoryDetails = $categoryEntity->viewCleaningCategory($categoryID);

        return $categoryDetails;
    }
}
?>
