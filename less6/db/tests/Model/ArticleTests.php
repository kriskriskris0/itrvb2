<?php

namespace tests\Model;

use PHPUnit\Framework\TestCase;
use src\Model\Post;
use src\Model\UUID;

class ArticleTests extends TestCase
{
    public function testGetData(): void
    {
        $uuid = UUID::random();
        $authorUuid = UUID::random();
        $title = 'Title1';
        $text = 'Text';
        $article = new Post(
            $uuid,
            $authorUuid,
            $title,
            $text
        );

        $this->assertSame($uuid, $article->getUuid());
        $this->assertSame($authorUuid, $article->getAuthorUuid());
        $this->assertSame($title, $article->getTitle());
        $this->assertSame($text, $article->getText());
    }
}
