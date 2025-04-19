<?php

class loginController {
    private $db;
    
    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "CSIT314");
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM userAccount WHERE username = '$username' AND password = '$password'";
        $result = $this->db->query($sql);

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            session_start();
            $_SESSION["userAccountID"] = $user["userAccountID"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["password"] = $user["password"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["userProfileID"] = $user["userProfileID"];

            header("Location: homepage.php"); // Redirect to dashboard
            exit();
        } else {
            echo "Invalid credentials.";
        }
    }

}




