<?php
namespace src\Logger;

class TestLogger implements LoggerInterface {
    public array $logMessages = [];

    public function info(string $message, array $context = []): void {
        $this->logMessages[] = ['level' => 'info', 'message' => $message, 'context' => $context];
    }

    public function warning(string $message, array $context = []): void {
        $this->logMessages[] = ['level' => 'warning', 'message' => $message, 'context' => $context];
    }
}