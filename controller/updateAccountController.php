<?php
// Include entity
include_once '../Entity/userAccount.php';
include_once '../inc_dbconnect.php';

class UpdateAccountController {
    private $userAccountEntity;
    
    public function __construct() {
        // Get the database connection from inc_dbconnect.php
        global $conn;
        
        // Initialize the UserAccount entity
        $this->userAccountEntity = new UserAccount($conn);
    }
    
    public function updateAccount($userID, $username, $name, $userProfileID) {
        // Validate input
        if (empty($userID) || !is_numeric($userID)) {
            return false;
        }
        
        if (empty($username) || empty($name) || empty($userProfileID)) {
            return false;
        }
        
        // Call updateAccount method in the entity
        return $this->userAccountEntity->updateAccount($userID, $username, $name, $userProfileID);
    }
}
?>