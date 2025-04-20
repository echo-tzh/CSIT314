<?php
// Include entities
include_once '../entity/userAccount.php';
include_once '../entity/userProfile.php';
include_once '../inc_dbconnect.php';

class ViewAccountController {
    private $userAccountEntity;
    
    public function __construct() {
        // Get the database connection from inc_dbconnect.php
        global $conn;
        
        // Initialize the UserAccount entity
        $this->userAccountEntity = new UserAccount($conn);
    }
    
    public function viewAccount($userID) {
        // Validate input
        if (empty($userID) || !is_numeric($userID)) {
            return false;
        }
        
        // Call viewAccount method in the entity
        return $this->userAccountEntity->viewAccount($userID);
    }
}
?>