<?php
class FeedbackForm {
    private $id;
    private $userId;
    private $content;

    public function __construct($id, $userId, $content) {
        $this->id = $id;
        $this->userId = $userId;
        $this->content = $content;
    }

    public function submit(): string {
        return "Отправлено: $this->id\n$this->userId\n$this->content\n";
    }
}