<?php
include '../entity/cleaningCategory.php';

class CreateCategoryController {
    public function createCategory($categoryName, $description) {
        $cleaningCategory = new CleaningCategory();
        $result = $cleaningCategory->createCategory($categoryName, $description);
        return $result === true;
    }
    
}
?>