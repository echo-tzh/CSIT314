<?php


// Include entity classes
include_once '../entity/userAccount.php';
require_once '../entity/userProfile.php';

class SearchUserController {
    public function searchUserAccount(string $searchTerm) {
        // Create UserAccount entity (this handles the DB connection internally)
        $userAccount = new UserAccount();
        
        if (empty($searchTerm)) {
            // Return all users if search term is empty
            return $userAccount->getAllUsers();
        } else {
            // Return filtered results
            return $userAccount->search($searchTerm);
        }
    }
}
?>