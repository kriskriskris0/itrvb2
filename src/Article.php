<?php

namespace my;

class Article {
    private $uuid;
    private $authorUuid;
    private $title;
    private $text;

    public function __construct($uuid, $authorUuid, $title, $text) {
        $this->uuid = $uuid;
        $this->authorUuid = $authorUuid;
        $this->title = $title;
        $this->text = $text;
    }
}

?>