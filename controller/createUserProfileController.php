<?php
include '../Entity/userProfile.php';

class createUserProfileController {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    public function createUserProfile($newProfile) {
        // Validate input
        if (empty($newProfile['profile'])) {
            return false;
        }

        // Create UserProfile entity
        $userProfile = new UserProfile($this->db);

        // Call createUserProfile method in the entity (Corrected method name)
        return $userProfile->createUserProfile(
            $newProfile['profile']
        );
    }
}
?>