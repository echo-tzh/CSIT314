<?php
require_once '../entity/userProfile.php';

class UpdateUserProfileController {
    //  No __construct()

    public function updateUserProfile(int $userProfileID, string $userProfileName, string $description): bool {
        //  **Crucial:  Server-side validation**
        $userProfileName = trim($userProfileName);
        $description = trim($description);

        $userProfile = new UserProfile(); // Instantiate within the method
        return $userProfile->updateUserProfile($userProfileID, $userProfileName, $description);
    }
}
?>