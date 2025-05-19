<?php
include '../entity/userAccount.php';
//require_once '../entity/userProfile.php';



class createAccountController {

    
    public function createAccount(string $username, string $password, string $name, int $userProfileID): bool {
        $userAccount = new userAccount();
        return $userAccount->createAccount($username, $password, $name, $userProfileID);
    }
    
    
}
?>
