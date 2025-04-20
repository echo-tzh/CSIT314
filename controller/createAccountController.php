<?php
include '../entity/userAccount.php';
require_once '../entity/userProfile.php';



class createAccountController {
    private $db;
    
    public function __construct($conn) {
        $this->db = $conn;
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }
    
    public function createAccount($newUser) {
        // Validate input
        if (empty($newUser['username']) || empty($newUser['password']) || 
            empty($newUser['name']) || empty($newUser['userProfileID'])) {
            return false;
        }
        
        // Create UserAccount entity
        $userAccount = new UserAccount($this->db);
        
        // Call createAccount method in the entity
        return $userAccount->createAccount(
            $newUser['username'],
            $newUser['password'],
            $newUser['name'],
            $newUser['userProfileID']
        );
    }
}
?>
