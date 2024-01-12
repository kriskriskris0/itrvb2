<?php

namespace tests\Http\Actions\Users;

use myHttp\Actions\Users\FindByUsername;
use myHttp\ErrorResponse;
use myHttp\Request;
use myHttp\SuccessfullResponse;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use src\Model\UUID;
use src\Repositories\UserRepository;

class FindByUsernameTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private UserRepository $repo;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->repo = new UserRepository($this->pdoMock);
    }

    public function testItReturnErrorIfParamUserNotFound(): void
    {
        $this->prepareMockForFetch(false);

        $request = new Request([], []);
        $action = new FindByUsername($this->repo);

        $response = $action->handle($request);

        $this->assertErrorResponse('Incorrect param for query', $response);
    }

    public function testItReturnErrorIfUserNotFound(): void
    {
        $this->prepareMockForFetch(false);

        $request = new Request(['username' => 'Ivan'], []);
        $action = new FindByUsername($this->repo);

        $response = $action->handle($request);

        $this->assertErrorResponse('Cannot get user: Ivan', $response);
    }

    public function testItReturnUserByName(): void
    {
        $uuid = UUID::random();

        $mockUserData = [
            'uuid' => $uuid,
            'username' => 'ivan123',
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
        ];

        $this->prepareMockForFetch($mockUserData);

        $request = new Request(['username' => 'Ivan'], []);
        $action = new FindByUsername($this->repo);
        $response = $action->handle($request);

        $this->assertSuccessResponse(['username' => 'ivan123', 'name' => 'Ivan Ivanov'], $response);
    }

    private function prepareMockForFetch($returnValue): void
    {
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturn($returnValue);
    }

    private function assertErrorResponse(string $reason, ErrorResponse $response): void
    {
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"succuess":false,"reason":"' . $reason . '"}');
        $response->send();
    }

    private function assertSuccessResponse(array $data, SuccessfullResponse $response): void
    {
        $this->assertInstanceOf(SuccessfullResponse::class, $response);
        $this->expectOutputString('{"succuess":true,"data":' . json_encode($data, JSON_UNESCAPED_UNICODE) . '}');
        $response->send();
    }
}
