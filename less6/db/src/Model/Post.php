<?php

namespace src\Model;

class Post {
    public function __construct(
        private UUID $uuid,
        private UUID $authorUuid,
        private string $title,
        private string $text
    ) {
    }

    public function getUuid(): UUID {
        return $this->uuid;
    }

    public function getAuthorUuid(): UUID {
        return $this->authorUuid;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getText(): string {
        return $this->text;
    }

    public function withTitle(string $title): self {
        $newPost = clone $this;
        $newPost->title = $title;
        return $newPost;
    }

    public function withText(string $text): self {
        $newPost = clone $this;
        $newPost->text = $text;
        return $newPost;
    }
}
