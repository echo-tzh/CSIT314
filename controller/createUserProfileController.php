<?php

include '../entity/userProfile.php';

class createUserProfileController {
    private $db;

    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "CSIT314");
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    public function createUserProfile(UserProfile $userProfile) {
        // Pass the connection to the UserProfile method
        return UserProfile::createUserProfile($userProfile, $this->db);
    }
}

// Handle the POST request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["profile"])) {
    session_start();
    $controller = new createUserProfileController();
    $userProfile = new UserProfile($_POST["profile"]);
    $result = $controller->createUserProfile($userProfile); // Capture the result

    if ($result) {
        $_SESSION["status"] = "User profile created successfully.";
        header("Location: ../boundary/viewAlluserProfilePage.php");
        exit();
    } else {
        $_SESSION["error"] = "Error creating profile.";
        header("Location: createUserProfilePage.php");
        exit();
    }
}

?>