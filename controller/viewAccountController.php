<?php
// Include entities
include_once '../entity/userAccount.php';
include_once '../entity/userProfile.php';
include_once '../inc_dbconnect.php';

class viewAccountController {
    public function viewAccount(int $userID):array {
        // Validate input
        

        // Instantiate UserAccount (connection handled inside)
        $userAccountEntity = new userAccount();

        // Call viewAccount method in the entity
        return $userAccountEntity->viewAccount($userID);
    }
}
?>