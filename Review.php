<?php
class Review {
    private $id;
    private $productId;
    private $userId;
    private $content;
    private $rating;

    public function __construct($id, $productId, $userId, $content, $rating) {
        $this->id = $id;
        $this->productId = $productId;
        $this->userId = $userId;
        $this->content = $content;
        $this->rating = $rating;
    }

    public function publish(): string {
        return "Отзыв №$this->id для продукта №$this->productId\n$this->content\nРейтинг: $this->rating";
    }

    public function editContent(string $newContent): void {
        $this->content = $newContent;
    }

    public function send() {}
}