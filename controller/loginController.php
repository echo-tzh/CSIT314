<?php
include '../entity/UserAccount.php';
include '../inc_dbconnect.php';

class loginController {
    public function login($username, $password) {
        $userAccount = new UserAccount(); // No need to pass the connection
        return $userAccount->login($username, $password);
    }
}
?>
