<?php

class UserProfile {
    private $conn; // Database connection

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserProfile($userProfileID) {
        // Validate input
        if (empty($userProfileID) || !is_numeric($userProfileID)) {
            return false;
        }

        // Use prepared statement to prevent SQL injection
        $sql = "SELECT userProfileID, userProfileName FROM userProfile WHERE userProfileID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $userProfileID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }

    public function getAllUserProfiles() {
        $userProfiles = [];
        $query = "SELECT userProfileID, userProfileName FROM userProfile";
        $result = $this->conn->query($query); // Can be improved with prepared statement for consistency

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userProfiles[] = $row;
            }
        }

        return $userProfiles;
    }

    public function createUserProfile($userProfileName) {
        // Validate input
        if (empty(trim($userProfileName))) {
            return false;
        }

        if (!preg_match("/^[A-Za-z0-9_ ]{3,30}$/", trim($userProfileName))) {
            return false;
        }

        $profileName = trim($userProfileName); // Trim again for consistency

        // Check if profile already exists (Prepared Statement)
        $checkSql = "SELECT * FROM userProfile WHERE userProfileName = ?";
        $checkStmt = $this->conn->prepare($checkSql);

        if (!$checkStmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $checkStmt->bind_param("s", $profileName);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult && $checkResult->num_rows > 0) {
            return false;
        }

        // Insert into database (Prepared Statement)
        $sql = "INSERT INTO userProfile (userProfileName) VALUES (?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("s", $profileName);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Database error: " . $this->conn->error);
            return false;
        }
    }

    public function updateUserProfile($userProfileID, $userProfileName) {
        // Validate input
        if (empty($userProfileID) || !is_numeric($userProfileID) || empty(trim($userProfileName))) {
            return false;
        }

        if (!preg_match("/^[A-Za-z0-9_ ]{3,30}$/", trim($userProfileName))) {
            return false;
        }

        $profileName = trim($userProfileName);

        // Check if profile name already exists for other profiles
        $checkSql = "SELECT * FROM userProfile WHERE userProfileName = ? AND userProfileID != ?";
        $checkStmt = $this->conn->prepare($checkSql);

        if (!$checkStmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $checkStmt->bind_param("si", $profileName, $userProfileID);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult && $checkResult->num_rows > 0) {
            return false;
        }

        // Update profile (Prepared Statement)
        $sql = "UPDATE userProfile SET userProfileName = ? WHERE userProfileID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("si", $profileName, $userProfileID);
        $result = $stmt->execute();

        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return false;
        }

        return $result;
    }

    public function deleteUserProfile($userProfileID) {
        // Validate input
        if (empty($userProfileID) || !is_numeric($userProfileID)) {
            return false;
        }

        // Delete profile (Prepared Statement)
        $sql = "DELETE FROM userProfile WHERE userProfileID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $userProfileID);
        $result = $stmt->execute();

        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return false;
        }

        return $result;
    }

    public function suspendUserProfile($userProfileID) {
        // Validate input
        if (empty($userProfileID) || !is_numeric($userProfileID)) {
            return false;
        }

        // Update status to 0 (suspended) using a prepared statement
        $sql = "UPDATE userProfile SET status = 0 WHERE userProfileID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $userProfileID);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Database error: " . $this->conn->error);
            return false;
        }
    }
}
?>