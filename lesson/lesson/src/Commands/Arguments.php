<?php

namespace src\Commands;

use src\Exceptions\ArgumentsException;

class Arguments {
    private array $arguments;

    public function __construct(array $arguments) {
        foreach ($arguments as $argument => $value) {
            $stringValue = trim((string)$value);

            if (!empty($stringValue)) {
                $this->arguments[(string)$argument] = $stringValue;
            }
        }
    }

    public static function fromArgv(array $argv): self {
        $arguments = [];

        foreach ($argv as $argument) {
            $parts = explode('=', $argument);

            if (count($parts) === 2) {
                $arguments[$parts[0]] = $parts[1];
            }
        }

        return new self($arguments);
    }

    public function get(string $argument): string {
        if (!array_key_exists($argument, $this->arguments)) {
            throw new ArgumentsException('Аргумент не найден');
        }

        return $this->arguments[$argument];
    }
}
