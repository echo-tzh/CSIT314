<?php
include '../entity/UserAccount.php';
//include '../inc_dbconnect.php';

class loginController {
    public function login(string $username, string $password):array|bool {
        $userAccount = new userAccount();
        return $userAccount->login($username, $password);
    }
}
?>
