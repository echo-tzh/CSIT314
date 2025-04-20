<?php
// Include entity
include_once '../Entity/userAccount.php';
include_once '../inc_dbconnect.php';

class SuspendAccountController {
    private $userAccountEntity;
    
    public function __construct() {
        // Get the database connection from inc_dbconnect.php
        global $conn;
        
        // Initialize the UserAccount entity
        $this->userAccountEntity = new UserAccount($conn);
    }
    
    public function suspendAccount($userID) {
        $success = $this->userAccountEntity->suspendAccount($userID);

        return $success;

        
    }
}
?>
