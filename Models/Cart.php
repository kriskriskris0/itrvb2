<?php
class Cart {
    private $userId;
    private $items = [];

    public function __construct($userId) {
        $this->userId = $userId;
    }

    public function addItem(Product $product): void {
        $this->items[] = $product;
    }

    public function removeItem(Product $product): void {
        $this->items = array_filter($this->items, function ($item) use ($product) {
            return $item !== $product;
        });
    }

    public function getTotalPrice(): float {
        $totalPrice = 0.0;
        foreach ($this->items as $item) {
            $totalPrice += $item->getPrice();
        }
        return $totalPrice;
    }
}