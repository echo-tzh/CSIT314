<?php
//  ../controller/suspendUserProfileController.php
include_once '../entity/userProfile.php';

class SuspendUserProfileController {
    public function suspendUserProfile($userProfileID) {
        $userProfileEntity = new UserProfile();  // Instantiate UserProfile here
        return $userProfileEntity->suspendUserProfile($userProfileID);
    }
}
?>