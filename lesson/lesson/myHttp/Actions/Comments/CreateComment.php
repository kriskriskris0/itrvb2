<?php

namespace myHttp\Actions\Comments;

use myHttp\Actions\ActionInterface;
use myHttp\ErrorResponse;
use myHttp\Request;
use myHttp\Response;
use myHttp\SuccessfullResponse;
use src\Model\Comment;
use src\Model\UUID;
use src\Repositories\CommentRepository;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CommentRepository $commentRepository
    ) { }

    public function handle(Request $request): Response
    {
        try {
            $data = $request->body(['author_uuid', 'post_uuid', 'text']);
            $authorUuid = new UUID($data['author_uuid']);
            $postUuid = new UUID($data['post_uuid']);
            $text = $data['text'];

            $this->validateText($text);

            $comment = $this->createComment($authorUuid, $postUuid, $text);

            $this->commentRepository->save($comment);

            return new SuccessfullResponse(['message' => 'Comment created successfully']);
        } catch (\Exception $ex) {
            return new ErrorResponse($ex->getMessage());
        }
    }

    private function validateText(string $text): void
    {
        if (empty($text)) {
            throw new \InvalidArgumentException('Text cannot be empty');
        }
    }

    private function createComment(UUID $authorUuid, UUID $postUuid, string $text): Comment
    {
        return new Comment(UUID::random(), $authorUuid, $postUuid, $text);
    }
}
