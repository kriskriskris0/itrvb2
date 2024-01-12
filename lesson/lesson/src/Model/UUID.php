<?php

namespace src\Model;

use Ramsey\Uuid\Uuid as RamseyUuid;
use src\Exceptions\InvalidArgumentException;

class UUID {
    private string $uuid;

    public function __construct(string $uuid) {
        $this->validateUuid($uuid);
        $this->uuid = $uuid;
    }

    public function __toString(): string {
        return $this->uuid;
    }

    public function getValue(): string {
        return $this->uuid;
    }

    public static function random(): self {
        return new self(RamseyUuid::uuid4()->toString());
    }

    private function validateUuid(string $uuid): void {
        try {
            RamseyUuid::fromString($uuid);
        } catch (\InvalidArgumentException $exception) {
            throw new InvalidArgumentException('Некорректный UUID', 0, $exception);
        }
    }
}
