<?php
require_once '../entity/cleaningCategory.php';

class updateCleaningCategoryController {
    public function updateCleaningCategory($categoryID, $newName, $newDescription) {
        $category = new cleaningCategory();
        return $category->updateCategory($categoryID, $newName, $newDescription);
    }
}
?>
