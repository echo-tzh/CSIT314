<?php
require_once '../entity/cleaningCategory.php';

class viewAllCleaningCategoryController {


    public function viewAllCleaningCategory() {
        // Create entity object
        $categoryEntity = new cleaningCategory();
        
        // Call entity method to get all categories
        $categories = $categoryEntity->viewAllCleaningCategory();
        
        return $categories;
    }
}
?>