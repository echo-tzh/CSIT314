<?php
require_once '../entity/userProfile.php';

class SearchUserProfileController {
    

    public function searchUserProfile(string $searchTerm): array {
       

        $userProfile = new userProfile(); // Instantiate UserProfile within the method

        return $userProfile->searchUserProfile($searchTerm);
    }
}
?>