<?php
class User {
    private $id;
    private $username;
    private $password;
    private $email;

    public function __construct($id, $username, $password, $email) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public function login(string $username, string $password): bool {
        return ($username === $this->username && $password === $this->password);
    }

    public function logout(): void {
        $this->id = "";
        $this->username = "";
        $this->password = "";
        $this->email = "";
    }

    public function register($id, $username, $password, $email): void {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }
}