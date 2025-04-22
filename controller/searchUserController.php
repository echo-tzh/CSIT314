<?php
// Include the entity class
include_once '../entity/userAccount.php';
require_once '../entity/userProfile.php';

class SearchUserController {

    public function searchUserAccount($searchTerm) {
        // Create UserAccount entity (this handles the DB connection internally)
        $userAccount = new UserAccount();
        
        if (empty($searchTerm)) {
            // Return all users if search term is empty
            return $userAccount->getAllUsers();
        } else {
            // Return filtered results
            return $userAccount->search($searchTerm);
        }
    }
}

// Process AJAX request if this file is called directly
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    // Create controller and get results
    $controller = new SearchUserController();
    $searchResults = $controller->searchUserAccount($_POST['search']);
    
    // Return results as JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit;
}
?>
