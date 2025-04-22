<?php
// Include entities
include_once '../entity/userProfile.php';

class ViewUserProfileController {
    public function viewUserProfile($userProfileID) {
    
        $userProfileEntity = new UserProfile();

        return $userProfileEntity->getUserProfile($userProfileID); // Assuming getUserProfile exists
    }
}
?>