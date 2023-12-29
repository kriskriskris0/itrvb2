<?php
class PhysicalProduct extends Product {
    private $weight;
    private $dimensions;

    public function setWeight(float $weight): void {
        $this->weight = $weight;
    }

    public function setDimensions(string $dimensions): void {
        $this->dimensions = $dimensions;
    }

    public function getWeight(): float {
        return $this->weight;
    }

    public function getDimensions(): string {
        return $this->dimensions;
    }
}