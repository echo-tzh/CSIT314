<?php
// Include entity and database connection
include_once '../Entity/userProfile.php'; // Assuming you have a UserProfile entity
include_once '../inc_dbconnect.php';

class SuspendUserProfileController {
    private $userProfileEntity;

    public function __construct() {
        // Get the database connection
        global $conn;

        // Initialize the UserProfile entity
        $this->userProfileEntity = new UserProfile($conn); // Assuming UserProfile entity exists
    }

    public function suspendUserProfile($userProfileID) {
        $success = $this->userProfileEntity->suspendUserProfile($userProfileID); // Assuming this method exists in the entity

        return $success; // Return true or false
    }
}
?>