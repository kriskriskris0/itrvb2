<?php

namespace myHttp\Auth;

use DateTimeImmutable;
use myHttp\Auth\TokenAuthInterface;
use myHttp\Request;
use src\Exceptions\AuthException;
use src\Exceptions\AuthTokenRepositoryException;
use src\Exceptions\HttpException;
use src\Model\User;
use src\Repositories\AuthTokenRepositoryInterface;
use src\Repositories\UserRepositoryInterface;

class BearerTokenAuth implements TokenAuthInterface
{
    private const HEADER_PREFIX = 'Bearer ';

    public function __construct(
        private AuthTokenRepositoryInterface $authTokenRepository,
        private UserRepositoryInterface $userRepository
    ) { }

    public function user(Request $request): User
    {
        $token = $this->extractTokenFromRequest($request);

        try {
            $authToken = $this->authTokenRepository->get($token);
            $this->validateAuthToken($authToken, $token);
        } catch (AuthTokenRepositoryException $error) {
            throw new AuthException("Bad token: [$token]");
        }

        $userUuid = $authToken->getUuid();

        return $this->userRepository->get($userUuid);
    }

    private function extractTokenFromRequest(Request $request): string
    {
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $error) {
            throw new AuthException($error->getMessage());
        }

        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new AuthException("Malformed token: [$header]");
        }

        return mb_substr($header, strlen(self::HEADER_PREFIX));
    }

    private function validateAuthToken($authToken, $token): void
    {
        if ($authToken->getExpiresOn() <= new DateTimeImmutable()) {
            throw new AuthException("Token expired: [$token]");
        }
    }
}
