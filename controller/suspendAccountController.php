<?php
// Include only the entity
include_once '../entity/userAccount.php';

class suspendAccountController {
    
    public function suspendAccount(int $userID):bool {
        // Initialize the UserAccount entity directly in this method
        $userAccountEntity = new userAccount();
        
        // Call suspendAccount method in the entity
        $success = $userAccountEntity->suspendAccount($userID);
        return $success;
    }
}
?>