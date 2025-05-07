<?php
// Include entities
include_once '../entity/userProfile.php';

class ViewUserProfileController {
    public function viewUserProfile(int $userProfileID) {
    
        $userProfileEntity = new UserProfile();

        $result = $userProfileEntity->viewUserProfile($userProfileID);
        if ($result) {
            return $result;
        } else {
            return []; // Return an empty array on failure
        }
    }
}
?>