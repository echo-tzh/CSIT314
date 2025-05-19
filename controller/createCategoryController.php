<?php
include '../entity/cleaningCategory.php';

class createCategoryController {
    public function createCategory(String $categoryName, String $description):bool {
        $cleaningCategory = new cleaningCategory();
        $result = $cleaningCategory->createCategory($categoryName, $description);
        return $result;
    }
    
}
?>