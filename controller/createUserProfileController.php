<?php
include '../Entity/userProfile.php';

class CreateUserProfileController {

   public function createUserProfile(array $newUserProfile): bool {
    $userProfile = new UserProfile(); 
    return $userProfile->createUserProfile($newUserProfile);
}

}
?>