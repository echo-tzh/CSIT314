<?php
require_once '../entity/cleaningCategory.php';

class viewCleaningCategoryController {

    public function viewCleaningCategory(int $categoryID):array {
        // Create entity object
        $categoryEntity = new cleaningCategory();

        // Call entity method to get specific category
        $categoryDetails = $categoryEntity->viewCleaningCategory($categoryID);

        return $categoryDetails;
    }
}
?>
