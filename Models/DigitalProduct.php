<?php
class DigitalProduct extends Product {
    private $downloadLink;

    public function setDownloadLink(string $link): void {
        $this->downloadLink = $link;
    }

    public function getDownloadLink(): string {
        return $this->downloadLink;
    }
}