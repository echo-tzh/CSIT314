<?php
require_once '../entity/CleaningCategory.php';

class searchCleaningCategoryController {

    public function searchCleaningCategory(string $searchCleaningCat):array {
        $cleaningCategory = new cleaningCategory();
        return $cleaningCategory->searchCleaningCategory($searchCleaningCat);
    }
}
?>
