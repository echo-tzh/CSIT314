<?php
// Include entities
include_once '../entity/userProfile.php';

class ViewUserProfileController {
    public function viewUserProfile(int $userProfileID):array {
    
        $userProfileEntity = new UserProfile();

        $result = $userProfileEntity->viewUserProfile($userProfileID);
        return $result;
    }

}
?>