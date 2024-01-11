<?php

namespace src\Repositories;

use PDO;
use PDOException;
use Psr\Log\LoggerInterface;
use src\Exceptions\UserIncorrectDataException;
use src\Exceptions\UserNotFoundException;
use src\Model\Name;
use src\Model\User;
use src\Model\UUID;

class UserRepository implements UserRepositoryInterface {
    public function __construct(
        private PDO $pdo,
        private LoggerInterface $logger
    ) {
    }

    public function userExists(string $username): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([":username" => $username]);

        return $stmt->rowCount() > 0;
    }

    public function save(User $user): void {
        if ($this->userExists($user->getUsername())) {
            throw new UserIncorrectDataException("User already exists with username " . $user->getUsername());
        }

        $stmt = $this->pdo->prepare("INSERT INTO users(uuid, username, first_name, last_name)
                                    VALUES (:uuid, :username, :first_name, :last_name)");

        try {
            $stmt->execute([
                ":uuid" => $user->getUuid(),
                ":username" => $user->getUsername(),
                ":first_name" => $user->getName()->getFirstName(),
                ":last_name" => $user->getName()->getLastName()
            ]);

            $this->logger->info("User saved successfully", ['uuid' => $user->getUuid()]);
        } catch (PDOException $e) {
            $this->logger->error("Error saving user", ['uuid' => $user->getUuid(), 'exception' => $e]);
            throw new UserIncorrectDataException("Error saving user: " . $e->getMessage());
        }
    }

    public function getByUsername(string $username): User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");

        try {
            $stmt->execute([
                ":username" => $username
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                $this->logger->warning("User not found", ['username' => $username]);
                throw new UserNotFoundException("Cannot get user: $username");
            }
        } catch (PDOException $e) {
            throw new UserNotFoundException("Error for get user: " . $e->getMessage());
        }

        $this->logger->info("User get by username successfully", ['uuid' => $result['uuid']]);
        return new User(
            new UUID($result['uuid']),
            $result['username'],
            new Name(
                $result['first_name'],
                $result['last_name']
            )
        );
    }

    public function get(UUID $uuid): User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE uuid = :uuid");

        try {
            $stmt->execute([
                ":uuid" => $uuid
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->warning("User not found", ['uuid' => $uuid]);
            throw new UserIncorrectDataException("Error when user get: " . $e->getMessage());
        }

        $this->logger->info("User get successfully", ['uuid' => $uuid]);
        return new User($result['uuid'], $result['username'], new Name(
            $result['first_name'],
            $result['last_name']
        ));
    }
}

