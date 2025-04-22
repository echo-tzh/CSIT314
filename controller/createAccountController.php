<?php
include '../entity/userAccount.php';
require_once '../entity/userProfile.php';



class createAccountController {

    
    public function createAccount($newUser) {
        // Create UserAccount entity
        $userAccount = new UserAccount();
    
        // Call createAccount method in the entity
        $result = $userAccount->createAccount(
            $newUser['username'],
            $newUser['password'],
            $newUser['name'],
            $newUser['userProfileID']
        );
    
        return $result === true;
    }
    
    
}
?>
