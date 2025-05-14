<?php
require_once '../entity/userProfile.php';

class SearchUserProfileController {
    

    public function searchUserProfile(string $searchTerm): array {
       

        $userProfile = new UserProfile(); // Instantiate UserProfile within the method

        return $userProfile->searchUserProfile($searchTerm);
    }
}
?>