<?php
class Product {
    protected $id;
    protected $title;
    protected $description;
    protected $price;
    protected $count;

    public function __construct($id, $title, $description, $price, $count) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->count = $count;
    }

    public function getDetails(): string {
        return "$this->id / $this->title / $this->description / $this->price / $this->count";
    }

    public function updateCount(int $quantity): int {
        $this->count = $quantity;
        return $this->count;
    }

    public function getPrice(): float {
        return $this->price;
    }
}