<?php
require_once '../entity/userProfile.php';

class updateUserProfileController {
   
    public function updateUserProfile(int $userProfileID, string $userProfileName, string $description): bool {
        //  **Crucial:  Server-side validation**


        $userProfile = new userProfile(); // Instantiate within the method
        return $userProfile->updateUserProfile($userProfileID, $userProfileName, $description);
    }
}
?>