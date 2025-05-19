<?php
// Include entities
include_once '../entity/userProfile.php';

class viewUserProfileController {
    public function viewUserProfile(int $userProfileID):array {
    
        $userProfileEntity = new userProfile();

        $result = $userProfileEntity->viewUserProfile($userProfileID);
        return $result;
    }

}
?>