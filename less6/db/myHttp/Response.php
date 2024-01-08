<?php

namespace myHttp;

abstract class Response
{
    protected const SUCCESS = true;
    protected const CONTENT_TYPE_JSON = 'Content-Type: application/json';

    public function send(): void
    {
        $data = ['success' => static::SUCCESS] + $this->payload();

        header(self::CONTENT_TYPE_JSON);

        echo json_encode($data, JSON_THROW_ON_ERROR);
    }

    abstract protected function payload(): array;
}
