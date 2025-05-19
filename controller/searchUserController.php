<?php

// Include entity classes
include_once '../entity/userAccount.php';
require_once '../entity/userProfile.php';

class searchUserController {
    public function searchUserAccount(string $searchTerm):array {
        $userAccount = new userAccount();
        return $userAccount->searchUserAccount($searchTerm);
    }
}
?>
