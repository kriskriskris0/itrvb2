<?php

namespace src\Model;

class Name {
    public function __construct(
        private string $firstName,
        private string $lastName
    ) {
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function withFirstName(string $firstName): self {
        $newName = clone $this;
        $newName->firstName = $firstName;
        return $newName;
    }

    public function withLastName(string $lastName): self {
        $newName = clone $this;
        $newName->lastName = $lastName;
        return $newName;
    }

    public function __toString(): string {
        return $this->firstName . ' ' . $this->lastName;
    }
}
