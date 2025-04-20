<?php
include '../Entity/UserAccount.php';
include '../inc_dbconnect.php';

class loginController {
    private $db;
    
    public function __construct() {
        // Connect to database
        global $conn;
        $this->db = $conn;
        
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }
    
    public function login($username, $password) {
        // Validate input
        if (empty($username) || empty($password)) {
            $_SESSION["message"] = "<p style='color: red;'>Username and password cannot be empty.</p>";
            return false;
        }
        
        // Create UserAccount entity
        $userAccount = new UserAccount($this->db);
        
        // Call login method in the entity
        $result = $userAccount->login($username, $password);
        
        if ($result) {
            // Successful login - get user details
            $sql = "SELECT userAccountID, name, userProfileID FROM userAccount WHERE username = '$username'";
            $query = $this->db->query($sql);
            $user = $query->fetch_assoc();
            
            // Set session variables
   
            $_SESSION['userAccountID'] = $user['userAccountID'];
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $user['name'];
            $_SESSION['userProfileID'] = $user['userProfileID'];
            
            // Get user profile name
            $profileSql = "SELECT userProfileName FROM userProfile WHERE userProfileID = " . $user['userProfileID'];
            $profileQuery = $this->db->query($profileSql);
            $profile = $profileQuery->fetch_assoc();
            $_SESSION['userProfileName'] = $profile['userProfileName'];
            
            // Set success message
            $_SESSION["message"] = "<p style='color: green;'>Login successful!</p>";
            
            // Redirect to homepage
            header("Location: homepage.php");
            exit();
        } else {
            // Failed login
            $_SESSION["message"] = "<p style='color: red;'>Invalid username or password.</p>";
            return false;
        }
    }
}
?>