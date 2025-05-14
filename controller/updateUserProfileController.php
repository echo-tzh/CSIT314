<?php
require_once '../entity/userProfile.php';

class UpdateUserProfileController {
   
    public function updateUserProfile(int $userProfileID, string $userProfileName, string $description): bool {
        //  **Crucial:  Server-side validation**


        $userProfile = new UserProfile(); // Instantiate within the method
        return $userProfile->updateUserProfile($userProfileID, $userProfileName, $description);
    }
}
?>