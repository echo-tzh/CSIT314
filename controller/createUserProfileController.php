<?php
include '../entity/userProfile.php';

class createUserProfileController {

    public function createUserProfile(string $userProfileName, string $description): bool {
        $userProfile = new userProfile(); 
        return $userProfile->createUserProfile($userProfileName, $description);
    }

}
?>

