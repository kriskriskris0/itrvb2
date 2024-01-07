<?php

namespace my;

class Comment {
    private $uuid;
    private $articleUuid;
    private $authorUuid;
    private $text;

    public function __construct($uuid, $articleUuid, $authorUuid, $text) {
        $this->uuid = $uuid;
        $this->articleUuid = $articleUuid;
        $this->authorUuid = $authorUuid;
        $this->text = $text;
    }
}

?>