<?php

// Include entity classes
include_once '../entity/userAccount.php';
require_once '../entity/userProfile.php';

class SearchUserController {
    public function searchUserAccount(string $searchTerm):array {
        $userAccount = new UserAccount();
        return $userAccount->searchUserAccount($searchTerm);
    }
}
?>
