<?php
require_once '../entity/CleaningCategory.php';

class searchCleaningCategoryController {

    public function searchCleaningCategory($searchCleaningCat) {
        $cleaningCategory = new CleaningCategory();
        return $cleaningCategory->searchCategory($searchCleaningCat);
    }
}
?>
