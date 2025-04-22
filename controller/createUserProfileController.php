<?php
include '../Entity/userProfile.php';

class CreateUserProfileController {

    public function createUserProfile(array $newProfile): bool {
            
        $profileName = trim($newProfile['profile']);
        $description = trim($newProfile['description']);

        // Create UserProfile entity
        $userProfile = new UserProfile(); // DB conn inside

        // Call createUserProfile method in the entity
        return $userProfile->createUserProfile($profileName, $description);
    }
}
?>