<?php
require_once '../entity/userProfile.php';

class SearchUserProfileController {
    

    public function searchUserProfiles(string $searchTerm): array {
        $searchTerm = trim($searchTerm);

        $userProfile = new UserProfile(); // Instantiate UserProfile within the method

        return $userProfile->searchUserProfiles($searchTerm);
    }
}
?>