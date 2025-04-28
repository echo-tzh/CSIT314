<?php
require_once '../entity/cleaningCategory.php';

class deleteCleaningCategoryController {
    public function deleteCleaningCategory($categoryID) {
        $cleaningCategoryEntity = new cleaningCategory();
        return $cleaningCategoryEntity->deleteCleaningCategory($categoryID);
    }
}
?>
