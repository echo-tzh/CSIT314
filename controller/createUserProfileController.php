<?php
include '../Entity/userProfile.php';

class CreateUserProfileController {

    public function createUserProfile(array $newProfile): bool {
        // Validate input
        if (empty(trim($newProfile['profile']))) {
            return false;
        }

        if (!preg_match("/^[A-Za-z0-9_ ]{3,30}$/", trim($newProfile['profile']))) {
             return false;
        }

        $profileName = trim($newProfile['profile']);
        $profileDescription = trim($newProfile['description']);

        // Create UserProfile entity
        $userProfile = new UserProfile(); // DB conn inside

        // Call createUserProfile method in the entity
        return $userProfile->createUserProfile($profileName, $profileDescription);
    }
}
?>