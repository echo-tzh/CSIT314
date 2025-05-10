<?php
require_once '../entity/cleaningCategory.php';

class deleteCleaningCategoryController {
    public function deleteCleaningCategory($categoryID):bool {
        $cleaningCategoryEntity = new cleaningCategory();
        return $cleaningCategoryEntity->deleteCleaningCategory($categoryID);
    }
}
?>
