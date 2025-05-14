<?php
require_once '../entity/cleaningCategory.php';

class updateCleaningCategoryController {
    public function updateCleaningCategory(int $categoryID,  String $newName, String $newDescription):bool {
        $category = new cleaningCategory();
        return $category->updateCategory($categoryID, $newName, $newDescription);
    }
}
?>
