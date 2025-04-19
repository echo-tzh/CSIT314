<?php

include '../Entity/userAccount.php';

class loginController {
    private $db;

    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "CSIT314");
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    public function login($username, $password) {
        $username = $this->db->real_escape_string($username);
        $sql = "SELECT * FROM userAccount WHERE username = '$username'";
        $result = $this->db->query($sql);

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Create UserAccount entity
            $userAccount = new UserAccount(
                $user["username"],
                $user["password"],
                $user["name"],
                $user["userProfileID"]
            );

            if ($userAccount->login($username, $password)) {
                session_start();
                $_SESSION["userAccountID"] = $user["userAccountID"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["password"] = $user["password"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["userProfileID"] = $user["userProfileID"];

                header("Location: homepage.php");
                exit();
            }
        }

        echo "Invalid credentials.";
    }
}
