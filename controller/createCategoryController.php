<?php
include '../entity/cleaningCategory.php';

class CreateCategoryController {
    public function createCategory($categoryData) {
        // Create CleaningCategory entity
        $cleaningCategory = new CleaningCategory();
        
        // Call createCategory method in the entity
        $result = $cleaningCategory->createCategory($categoryData['categoryName']);
        
        return $result === true;
    }
}
?>