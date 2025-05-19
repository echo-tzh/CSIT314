<?php
// Include only the entity
include_once '../entity/userAccount.php';

class updateAccountController {
    
    public function updateAccount(int $userID, String $username, String $name, int $userProfileID):bool {
        
        
        // Initialize the UserAccount entity directly in this method
        $userAccountEntity = new userAccount();
        
        // Call updateAccount method in the entity
        return $userAccountEntity->updateAccount($userID, $username, $name, $userProfileID);
    }
}
?>