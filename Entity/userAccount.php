<?php

class UserAccount
{
    private string $username;
    private string $password; // This should ideally be a hashed password
    private string $name;
    private int $userProfileID;

    public function __construct(string $username, string $password, string $name, int $userProfileID)
    {
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->userProfileID = $userProfileID;
    }

    // Getters
    public function getUsername(): string { return $this->username; }
    public function getPassword(): string { return $this->password; }
    public function getName(): string { return $this->name; }
    public function getUserProfileID(): int { return $this->userProfileID; }

    // Setters
    public function setUsername(string $username): void { $this->username = $username; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function setName(string $name): void { $this->name = $name; }
    public function setUserProfileID(int $userProfileID): void { $this->userProfileID = $userProfileID; }

    // Simulated login method (should check against stored hashed password in a real app)
    public function login(string $inputUsername, string $inputPassword): bool
    {
        return $this->username === $inputUsername && $this->password === $inputPassword;
    }
}
