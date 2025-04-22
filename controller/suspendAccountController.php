<?php
// Include only the entity
include_once '../entity/userAccount.php';

class SuspendAccountController {
    
    public function suspendAccount($userID) {
        // Initialize the UserAccount entity directly in this method
        $userAccountEntity = new UserAccount();
        
        // Call suspendAccount method in the entity
        $success = $userAccountEntity->suspendAccount($userID);
        return $success;
    }
}
?>