<?php

class UserProfile {
    private $userProfileID;
    private $userProfileName;

    public function __construct($userProfileName) {
        $this->userProfileName = $userProfileName;
    }

    public function getUserProfileID() {
        return $this->userProfileID;
    }

    public function setUserProfileID($userProfileID) {
        $this->userProfileID = $userProfileID;
    }

    public function getUserProfileName() {
        return $this->userProfileName;
    }

    public function setUserProfileName($userProfileName) {
        $this->userProfileName = $userProfileName;
    }

    public static function createUserProfile(UserProfile $userProfile, mysqli $db) {
        $profileName = trim($db->real_escape_string($userProfile->getUserProfileName()));

        if (empty($profileName)) {
            return false;
        }

        if (!preg_match("/^[A-Za-z0-9_ ]{3,30}$/", $profileName)) {
            return false;
        }

        // Check if profile already exists (Prepared Statement - Recommended)
        $checkSql = "SELECT * FROM userProfile WHERE userProfileName = ?";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->bind_param("s", $profileName);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult && $checkResult->num_rows > 0) {
            $checkStmt->close();
            return false;
        }
        $checkStmt->close();

        // Insert into database (Prepared Statement - Recommended)
        $sql = "INSERT INTO userProfile (userProfileName) VALUES (?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $profileName);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    






}

?>