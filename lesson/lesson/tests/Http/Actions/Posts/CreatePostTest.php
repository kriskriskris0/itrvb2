<?php

namespace tests\Http\Actions\Posts;

use myHttp\Actions\Posts\CreatePost;
use myHttp\ErrorResponse;
use myHttp\Request;
use myHttp\SuccessfullResponse;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use src\Exceptions\PostIncorrectDataException;
use src\Model\UUID;
use src\Repositories\PostRepository;

class CreatePostTest extends TestCase
{
    private PDO $pdoMock;
    private PDOStatement $stmtMock;
    private PostRepository $postRepository;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->postRepository = new PostRepository($this->pdoMock);
    }

    public function testItSuccess(): void
    {
        $this->prepareMockForExecute(true);

        $request = new Request(
            [],
            ['author_uuid' => UUID::random(), 'title' => 'Test Title', 'text' => 'Test Text'],
            []
        );

        $createPostAction = new CreatePost($this->postRepository);
        $response = $createPostAction->handle($request);

        $this->assertInstanceOf(SuccessfullResponse::class, $response);
    }

    public function testItIncorrectUuid(): void
    {
        $this->prepareMockForExecute(true);

        $request = new Request(
            [],
            ['author_uuid' => 'incorrect_uuid', 'title' => 'Test Title', 'text' => 'Test Text'],
            []
        );

        $createPostAction = new CreatePost($this->postRepository);
        $response = $createPostAction->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
    }

    public function testItIncorrectUuidAuthor(): void
    {
        $uuid = UUID::random();
        $this->prepareMockForFetchColumn(0, true);

        $request = new Request(
            [],
            ['author_uuid' => $uuid, 'title' => 'Test Title', 'text' => 'Test Text'],
            []
        );

        $createPostAction = new CreatePost($this->postRepository);
        $response = $createPostAction->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $responseBody = $response->getBody();
        $this->assertContains('Автор с UUID ' . $uuid . ' не найден', $responseBody);
    }

    public function testItEmptyAuthorUuid(): void
    {
        $this->prepareMockForExecute(false);

        $request = new Request(
            [],
            ['title' => 'Test Title', 'text' => 'Test Text'],
            []
        );

        $createPostAction = new CreatePost($this->postRepository);
        $response = $createPostAction->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->assertResponseContainsErrorString('Incorrect param for body: author_uuid', $response);
    }

    public function testItEmptyTitle(): void
    {
        $this->prepareMockForExecute(false);

        $request = new Request(
            [],
            ['author_uuid' => UUID::random(), 'text' => 'Test Text'],
            []
        );

        $createPostAction = new CreatePost($this->postRepository);
        $response = $createPostAction->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->assertResponseContainsErrorString('Incorrect param for body: title', $response);
    }

    public function testItEmptyText(): void
    {
        $this->prepareMockForExecute(false);

        $request = new Request(
            [],
            ['author_uuid' => UUID::random(), 'title' => 'Test Text'],
            []
        );

        $createPostAction = new CreatePost($this->postRepository);
        $response = $createPostAction->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->assertResponseContainsErrorString('Incorrect param for body: text', $response);
    }

    private function prepareMockForExecute(bool $returnValue): void
    {
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn($returnValue);
    }

    private function prepareMockForFetchColumn(int $fetchColumnValue, bool $returnValue): void
    {
        $this->prepareMockForExecute(true);
        $this->stmtMock->method('fetchColumn')->willReturn($fetchColumnValue);
    }

    private function assertResponseContainsErrorString(string $errorString, ErrorResponse $response): void
    {
        $responseBody = $response->getBody();
        $this->assertContains(json_encode($errorString, JSON_UNESCAPED_UNICODE), $responseBody);
    }
}
