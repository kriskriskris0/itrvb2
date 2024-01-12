<?php

namespace tests\Model;

use PHPUnit\Framework\TestCase;
use src\Model\Comment;
use src\Model\UUID;

class CommentTests extends TestCase
{
    public function testGetData(): void
    {
        $uuid = UUID::random();
        $authorUuid = UUID::random();
        $articleUuid = UUID::random();
        $text = 'Text';
        $comment = new Comment(
            $uuid,
            $authorUuid,
            $articleUuid,
            $text
        );

        $this->assertSame($uuid, $comment->getUuid());
        $this->assertSame($authorUuid, $comment->getAuthorUuid());
        $this->assertSame($articleUuid, $comment->getArticleUuid());
        $this->assertSame($text, $comment->getText());
    }
}
