<?php
// Include entities
include_once '../entity/userAccount.php';
include_once '../entity/userProfile.php';
include_once '../inc_dbconnect.php';

class ViewAccountController {
    public function viewAccount($userID) {
        // Validate input
        

        // Instantiate UserAccount (connection handled inside)
        $userAccountEntity = new UserAccount();

        // Call viewAccount method in the entity
        return $userAccountEntity->viewAccount($userID);
    }
}
?>