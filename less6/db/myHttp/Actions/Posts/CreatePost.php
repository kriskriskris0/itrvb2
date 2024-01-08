<?php

namespace myHttp\Actions\Posts;

use myHttp\Actions\ActionInterface;
use myHttp\ErrorResponse;
use myHttp\Request;
use myHttp\Response;
use myHttp\SuccessfullResponse;
use src\Exceptions\InvalidArgumentException;
use src\Model\Post;
use src\Model\UUID;
use src\Repositories\PostRepository;

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostRepository $postRepository
    ) { }

    public function handle(Request $request): Response
    {
        try {
            $data = $request->body(['author_uuid', 'title', 'text']);
            
            $this->validateTitleAndText($data['title'], $data['text']);

            $post = $this->createPost($data);

            $this->postRepository->save($post);

            return new SuccessfullResponse(['message' => 'Post created successfully']);
        } catch (\Exception $ex) {
            return new ErrorResponse($ex->getMessage());
        }
    }

    private function validateTitleAndText(string $title, string $text): void
    {
        if (empty($title) || empty($text)) {
            throw new InvalidArgumentException('Title or text cannot be empty');
        }
    }

    private function createPost(array $data): Post
    {
        return new Post(UUID::random(), new UUID($data['author_uuid']), $data['title'], $data['text']);
    }
}
