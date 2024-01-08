<?php

namespace src\Commands;

use src\Exceptions\CommandException;
use src\Exceptions\UserNotFoundException;
use src\Model\Name;
use src\Model\User;
use src\Model\UUID;
use src\Repositories\UserRepositoryInterface;

class CreateUserCommand {
    private const USER_ALREADY_EXISTS_MESSAGE = 'User already exists: ';

    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function isUserExist(string $username): bool {
        try {
            $this->userRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }

        return true;
    }

    public function handle(Arguments $arguments): void {
        $username = $arguments->get('username');

        if ($this->isUserExist($username)) {
            throw new CommandException(self::USER_ALREADY_EXISTS_MESSAGE . $username);
        }

        $this->createUser($arguments);
    }

    private function createUser(Arguments $arguments): void {
        $username = $arguments->get('username');
        $firstName = $arguments->get('first_name');
        $lastName = $arguments->get('last_name');

        $this->userRepository->save(new User(
            UUID::random(),
            $username,
            new Name($firstName, $lastName)
        ));
    }
}
