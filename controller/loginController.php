<?php

include '../entity/userAccount.php';
include '../inc_dbconnect.php';

class loginController {
    private $db;

    public function __construct() {
        global $conn;
        $this->db = $conn;
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    public function login($username, $password) {
        $username = $this->db->real_escape_string($username);
        $sql = "SELECT * FROM userAccount WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Create UserAccount entity
            $userAccount = new UserAccount(
                $user["username"],
                $user["password"],  // Storing plaintext from DB
                $user["name"],
                $user["userProfileID"]
            );

            // Insecure plaintext password comparison
            if ($userAccount->login($username, $password)) { 
                session_start();
                $_SESSION["userAccountID"] = $user["userAccountID"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["password"] = $user["password"]; // Storing plaintext in session
                $_SESSION["name"] = $user["name"];
                $_SESSION["userProfileID"] = $user["userProfileID"];

                header("Location: homepage.php");
                exit();
            }
        } else {
            echo "Invalid credentials.";
        }

        $stmt->close();
    }
}
?>