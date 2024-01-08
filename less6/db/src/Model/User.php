<?php

namespace src\Model;

class User {
    public function __construct(
        private UUID $uuid,
        private string $username,
        private Name $name
    ) {
    }

    public function getUuid(): UUID {
        return $this->uuid;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getName(): Name {
        return $this->name;
    }

    public function withName(Name $name): self {
        $newUser = clone $this;
        $newUser->name = $name;
        return $newUser;
    }

    public function withUsername(string $username): self {
        $newUser = clone $this;
        $newUser->username = $username;
        return $newUser;
    }
}
