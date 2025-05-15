<?php

class UserProfile {
    private $conn;

    public function __construct() {
        // Include and establish the database connection here
        include '../inc_dbconnect.php';  // **Critical:** Ensure this sets up `$conn` correctly
        $this->conn = $conn;

        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close(); // Close the connection when the object is destroyed
        }
    }


    public function viewUserProfile(int $userProfileID): array {  // Changed return type to array
        if (empty($userProfileID)) {
            return [];  // Return an empty array on failure
        }

        $sql = "SELECT userProfileID, userProfileName, description FROM userProfile WHERE userProfileID = ?";  // Added 'description'
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return [];  // Return an empty array on failure
        }

        $stmt->bind_param("i", $userProfileID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return [];  // Return an empty array if no data found
    }

    public function getAllUserProfiles(): array {
        $userProfiles = [];
        $query = "SELECT userProfileID, userProfileName, description, status FROM userProfile";
        $result = $this->conn->query($query);  // Consider prepared statement

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userProfiles[] = $row;
            }
        }

        return $userProfiles;
    }

   public function createUserProfile(string $userProfileName, string $description): bool {
    $userProfileName = trim($userProfileName);
    $description = trim($description);

    $sql = "INSERT INTO userProfile (userProfileName, description, status)
            SELECT ?, ?, 1
            WHERE NOT EXISTS (SELECT 1 FROM userProfile WHERE userProfileName = ?)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        error_log("Prepare failed: " . $this->conn->error);
        return false;
    }

    $stmt->bind_param("sss", $userProfileName, $description, $userProfileName);

    if ($stmt->execute()) {
        return $stmt->affected_rows > 0;
    } else {
        error_log("Database error: " . $this->conn->error);
        return false;
    }
}



    public function updateUserProfile(int $userProfileID, string $userProfileName, string $description): bool {
        // Validation is now in the controller
        $checkSql = "SELECT * FROM userProfile WHERE userProfileName = ? AND userProfileID != ?";
        $checkStmt = $this->conn->prepare($checkSql);
    
        if (!$checkStmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
    
        $checkStmt->bind_param("si", $userProfileName, $userProfileID);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
    
        if ($checkResult && $checkResult->num_rows > 0) {
            return false;
        }
    
        $sql = "UPDATE userProfile SET userProfileName = ?, description = ? WHERE userProfileID = ?";
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
    
        $stmt->bind_param("ssi", $userProfileName, $description, $userProfileID);
        $result = $stmt->execute();
    
        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return false;
        }
    
        return $result;
    }



    public function suspendUserProfile(int $userProfileID): bool {
        if (empty($userProfileID)) {
            return false;
        }

        $this->conn->begin_transaction();

        // Suspend the user profile
        $sqlProfile = "UPDATE userProfile SET status = 0 WHERE userProfileID = ?";
        $stmtProfile = $this->conn->prepare($sqlProfile);

        if (!$stmtProfile || !$stmtProfile->bind_param("i", $userProfileID) || !$stmtProfile->execute()) {
            $this->conn->rollback();
            error_log("Failed to suspend user profile: " . $this->conn->error);
            return false;
        }

        // Suspend related user accounts (within UserProfile class - BAD!)
        $sqlAccount = "UPDATE userAccount SET status = 0 WHERE userProfileID = ?";
        $stmtAccount = $this->conn->prepare($sqlAccount);

        if (!$stmtAccount || !$stmtAccount->bind_param("i", $userProfileID) || !$stmtAccount->execute()) {
            $this->conn->rollback();
            error_log("Failed to suspend user accounts: " . $this->conn->error);
            return false;
        }

        $this->conn->commit();
        return true;
    }


    public function searchUserProfile(string $searchTerm): array {
        $searchTerm = "%" . $this->conn->real_escape_string($searchTerm) . "%";  //  For security and correct SQL
    
        $sql = "SELECT userProfileID, userProfileName, description FROM userProfile WHERE userProfileName LIKE ? OR description LIKE ?";  // Modified SQL
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return [];
        }
    
        $stmt->bind_param("ss", $searchTerm, $searchTerm);  // Bind the parameter twice
        $stmt->execute();
        $result = $stmt->get_result();
    
        $userProfiles = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userProfiles[] = $row;
            }
        }
    
        return $userProfiles;
    }
}
?>