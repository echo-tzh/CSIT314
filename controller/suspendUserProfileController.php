<?php
//  ../controller/suspendUserProfileController.php
include_once '../entity/userProfile.php';

class suspendUserProfileController {
    public function suspendUserProfile(int $userProfileID):bool {
        $userProfileEntity = new userProfile();  // Instantiate UserProfile here
        return $userProfileEntity->suspendUserProfile(userProfileID: $userProfileID);
    }
}
?>