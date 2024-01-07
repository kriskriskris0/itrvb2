<?php

namespace my;

class User {
    private $uuid;
    public $name;
    public $surname;

    public function __construct($uuid) {
        $this->uuid = $uuid;
    }
}

?>