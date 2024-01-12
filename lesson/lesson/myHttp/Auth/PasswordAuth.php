<?php

namespace myHttp\Auth;

use myHttp\ErrorResponse;
use myHttp\Request;
use src\Exceptions\AuthException;
use src\Exceptions\HttpException;
use src\Exceptions\UserNotFoundException;
use src\Model\User;
use src\Repositories\UserRepositoryInterface;

class PasswordAuth implements PasswordAuthInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) { }

    public function user(Request $request): User
    {
        try {
            $userData = $this->getUserDataFromRequest($request);
            $user = $this->getUserByUsername($userData['username']);

            $this->validatePassword($user, $userData['password']);

            return $user;
        } catch (UserNotFoundException $ex) {
            return new ErrorResponse($ex->getMessage());
        } catch (AuthException $ex) {
            return new ErrorResponse($ex->getMessage());
        }
    }

    private function getUserDataFromRequest(Request $request): array
    {
        return $request->body(['username', 'password']);
    }

    private function getUserByUsername(string $username): User
    {
        return $this->userRepository->getByUsername($username);
    }

    private function validatePassword(User $user, string $password): void
    {
        if (!$user->checkPassword($password)) {
            throw new AuthException('Wrong password');
        }
    }
}
