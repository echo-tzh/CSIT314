<?php
include '../Entity/userProfile.php';

class CreateUserProfileController {

    public function createUserProfile(array $newUserProfile): bool {
            
        $userProfileName = trim($newUserProfile['userProfileName']);
        $description = trim($newUserProfile['description']);

        // Create UserProfile entity
        $userProfile = new UserProfile(); 

        // Call createUserProfile method in the entity
        return $userProfile->createUserProfile($userProfileName, $description);
    }
}
?>