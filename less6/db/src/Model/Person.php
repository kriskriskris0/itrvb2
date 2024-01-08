<?php

namespace src\Model;

class Person {
    public function __construct(
        private Name $name,
        private \DateTimeImmutable $registeredOn
    ) {
    }

    public function getName(): Name {
        return $this->name;
    }

    public function getRegisteredOn(): \DateTimeImmutable {
        return $this->registeredOn;
    }

    public function withName(Name $name): self {
        $newPerson = clone $this;
        $newPerson->name = $name;
        return $newPerson;
    }

    public function withRegisteredOn(\DateTimeImmutable $registeredOn): self {
        $newPerson = clone $this;
        $newPerson->registeredOn = $registeredOn;
        return $newPerson;
    }

    public function __toString(): string {
        return $this->name . ' (на сайте с ' . $this->registeredOn->format('Y-m-d') . ')';
    }
}
