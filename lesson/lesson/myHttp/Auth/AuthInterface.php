<?php

namespace myHttp\Actions\Users;

use myHttp\Actions\ActionInterface;
use myHttp\ErrorResponse;
use myHttp\Request;
use myHttp\Response;
use myHttp\SuccessfullResponse;
use Psr\Log\LoggerInterface;
use src\Exceptions\HttpException;
use src\Model\Name;
use src\Model\User;
use src\Model\UUID;
use src\Repositories\UserRepositoryInterface;

class CreateUser implements ActionInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private LoggerInterface $logger
    ) { }

    public function handle(Request $request): Response
    {
        try {
            $user = $this->createUserFromRequest($request);
            $this->userRepository->save($user);

            $this->logger->info("User created: {$user->getUuid()}");
            
            return new SuccessfullResponse([
                'uuid' => (string)$user->getUuid()
            ]);
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }
    }

    private function createUserFromRequest(Request $request): User
    {
        $newUserUuid = UUID::random();
        $data = $request->body(['username', 'first_name', 'last_name']);

        return new User(
            $newUserUuid,
            $data['username'],
            new Name(
                $data['first_name'],
                $data['last_name']
            )
        );
    }
}
