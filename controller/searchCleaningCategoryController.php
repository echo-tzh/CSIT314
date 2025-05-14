<?php
require_once '../entity/CleaningCategory.php';

class searchCleaningCategoryController {

    public function searchCleaningCategory(string $searchCleaningCat):array {
        $cleaningCategory = new CleaningCategory();
        return $cleaningCategory->searchCleaningCategory($searchCleaningCat);
    }
}
?>
