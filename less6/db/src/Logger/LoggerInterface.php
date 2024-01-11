<?php

namespace src\Logger;

interface LoggerInterface {
    public function info(string $message, array $context = []): void;
    public function warning(string $message, array $context = []): void;
}