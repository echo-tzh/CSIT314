<?php
include '../entity/cleaningCategory.php';

class CreateCategoryController {
    public function createCategory(String $categoryName, String $description):bool {
        $cleaningCategory = new CleaningCategory();
        $result = $cleaningCategory->createCategory($categoryName, $description);
        return $result;
    }
    
}
?>