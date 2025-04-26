<?php
include '../entity/UserAccount.php';
//include '../inc_dbconnect.php';

class loginController {
    public function login(string $username, string $password) {
        $userAccount = new UserAccount();
        return $userAccount->login($username, $password);
    }
}
?>
