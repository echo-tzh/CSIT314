<?php

class UserAccount {
    private string $username;
    private string $password; // Storing plaintext password (VERY BAD!)
    private string $name;
    private int $userProfileID;

    public function __construct(string $username, string $password, string $name, int $userProfileID) {
        $this->username = $username;
        $this->password = $password;  
        $this->name = $name;
        $this->userProfileID = $userProfileID;
    }

    // Getters
    public function getUsername(): string { return $this->username; }
    public function getPassword(): string { return $this->password; }  // DANGER!
    public function getName(): string { return $this->name; }
    public function getUserProfileID(): int { return $this->userProfileID; }

    // Setters
    public function setUsername(string $username): void { $this->username = $username; }
    public function setPassword(string $password): void { $this->password = $password; }  // DANGER!
    public function setName(string $name): void { $this->name = $name; }
    public function setUserProfileID(int $userProfileID): void { $this->userProfileID = $userProfileID; }

    // Insecure login method (DO NOT USE IN PRODUCTION)
    public function login(string $enteredUsername, string $enteredPassword): bool {
        return $this->username === $enteredUsername && $this->password === $enteredPassword;  // Plaintext comparison
    }
}
?>