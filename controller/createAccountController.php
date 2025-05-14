<?php
include '../entity/userAccount.php';
require_once '../entity/userProfile.php';



class createAccountController {

    
public function createAccount($newUser): bool {
    $userAccount = new UserAccount();
    return $userAccount->createAccount($newUser);
}

    
    
}
?>
