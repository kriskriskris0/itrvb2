<?php

namespace myHttp;

class ErrorResponse extends Response
{
    protected const SUCCESS = false;
    protected const DEFAULT_REASON = 'Wrong query';

    public function __construct(
        private string $reason = self::DEFAULT_REASON
    ) {}

    protected function payload(): array
    {
        return ['reason' => $this->reason];
    }

    public function getBody(): string
    {
        return json_encode($this->reason);
    }
}
