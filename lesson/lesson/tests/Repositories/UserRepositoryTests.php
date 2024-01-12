<?php

namespace tests\Repositories;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use src\Exceptions\UserNotFoundException;
use src\Model\Name;
use src\Model\User;
use src\Model\UUID;
use src\Repositories\UserRepository;

class UserRepositoryTests extends TestCase
{
    private PDO $pdoMock;
    private PDOStatement $stmtMock;
    private UserRepository $repo;

   

    public function testItThrowsAnExceptionWhenUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Cannot get user: Ivan');

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturn(false);

        $this->repo->getByUsername('Ivan');
    }

    public function testItSaveUserToDatabase(): void
    {
        $uuid = UUID::random();

        $this->stmtMock->expects($this->once())->method('execute')->with([
            ':uuid' => $uuid,
            ':username' => 'ivan123',
            ':first_name' => 'ivan',
            ':last_name' => 'ivanov',
        ]);

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);

        $this->repo->save(
            new User(
                $uuid,
                'ivan123',
                new Name('ivan', 'ivanov')
            )
        );
    }

    public function testGetUser(): void
    {
        $uuid = UUID::random();

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn([
            'uuid' => $uuid,
            'username' => 'ivan123',
            'first_name' => 'ivan',
            'last_name' => 'ivanov',
        ]);

        $user = $this->repo->get($uuid);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($uuid, $user->getUuid());
    }

    public function testGetUserByName(): void
    {
        $username = "ivan123";

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn([
            'uuid' => UUID::random(),
            'username' => $username,
            'first_name' => 'ivan',
            'last_name' => 'ivanov',
        ]);

        $user = $this->repo->getByUsername($username);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($username, $user->getUsername());
    }
    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);

        // Используйте тестовый логгер
        $this->logger = new TestLogger();

        $this->repo = new UserRepository($this->pdoMock, $this->logger);
    }

    public function testSaveUser(): void {
        $logger = new TestLogger();
        $userRepository = new UserRepository($this->createMock(PDO::class), $logger);

        $user = new User(UUID::generate(), 'testuser', new Name('John', 'Doe'));

        $userRepository->save($user);

        // Check if the log message contains the correct UUID
        $this->assertEquals($user->getUuid(), $logger->logMessages[0]['context']['uuid']);
    }

    public function testGetByUsername(): void {
        $logger = new TestLogger();
        $userRepository = new UserRepository($this->createMock(PDO::class), $logger);

        $username = 'testuser';
        $this->expectException(UserNotFoundException::class);
        $userRepository->getByUsername($username);

        // Check if the log message contains the correct username
        $this->assertEquals($username, $logger->logMessages[0]['context']['username']);
    }
}
