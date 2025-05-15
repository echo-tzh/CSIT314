<?php
include '../entity/userProfile.php';

class CreateUserProfileController {

    public function createUserProfile(string $userProfileName, string $description): bool {
        $userProfile = new UserProfile(); 
        return $userProfile->createUserProfile($userProfileName, $description);
    }

}
?>

