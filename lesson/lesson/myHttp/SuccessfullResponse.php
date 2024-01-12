<?php

namespace myHttp;

class SuccessfullResponse extends Response
{
    protected const SUCCESS = true;
    protected const PAYLOAD_KEY = 'data';

    public function __construct(
        private array $data = []
    ) {}

    protected function payload(): array
    {
        return [self::PAYLOAD_KEY => $this->data];
    }

    public function getBody(): string
    {
        return json_encode($this->data);
    }
}
