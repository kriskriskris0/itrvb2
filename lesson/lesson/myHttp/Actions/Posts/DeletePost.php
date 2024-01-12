<?php

namespace myHttp\Actions\Posts;

use myHttp\Actions\ActionInterface;
use myHttp\ErrorResponse;
use myHttp\Request;
use myHttp\Response;
use myHttp\SuccessfullResponse;
use src\Exceptions\HttpException;
use src\Exceptions\PostNotFoundException;
use src\Repositories\PostRepository;

class DeletePost implements ActionInterface
{
    public function __construct(
        private PostRepository $postRepository
    ) { }

    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('uuid');

            if (empty($postUuid)) {
                throw new HttpException('UUID parameter is missing in the request');
            }

            $this->validatePostExists($postUuid);

            $this->postRepository->delete($postUuid);

            return new SuccessfullResponse(['message' => 'Post deleted successfully']);
        } catch (HttpException | PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
    }

    private function validatePostExists(string $postUuid): void
    {
        if (!$this->postRepository->exists($postUuid)) {
            throw new PostNotFoundException('Post not found');
        }
    }
}
