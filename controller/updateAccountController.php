<?php
// Include only the entity
include_once '../entity/userAccount.php';

class UpdateAccountController {
    
    public function updateAccount($userID, $username, $name, $userProfileID) {
        
        
        // Initialize the UserAccount entity directly in this method
        $userAccountEntity = new UserAccount();
        
        // Call updateAccount method in the entity
        return $userAccountEntity->updateAccount($userID, $username, $name, $userProfileID);
    }
}
?>