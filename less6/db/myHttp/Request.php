<?php

namespace myHttp;

use src\Exceptions\HttpException;

class Request
{
    private const REQUEST_URI = 'REQUEST_URI';
    private const REQUEST_METHOD = 'REQUEST_METHOD';

    public function __construct(
        private array $get,
        private array $post,
        private array $server
    ) {}

    public function path(): string
    {
        $requestUri = $this->getServerValue(self::REQUEST_URI);
        $components = parse_url($requestUri);

        if (!is_array($components) || !array_key_exists('path', $components)) {
            throw new HttpException('Cannot find path query');
        }

        return $components['path'];
    }

    public function query(string $param): string
    {
        $value = $this->getArrayValue($param, $this->get);
        return trim($value);
    }

    public function body(array $params): array
    {
        $values = [];

        foreach ($params as $param) {
            $value = $this->getArrayValue($param, $this->post);
            $values[$param] = trim($value);
        }

        return $values;
    }

    public function header(string $header): string
    {
        $headerName = mb_strtoupper("http_" . str_replace("-", '_', $header));
        $value = $this->getServerValue($headerName);

        return trim($value);
    }

    public function method(): string
    {
        return $this->getServerValue(self::REQUEST_METHOD);
    }

    private function getServerValue(string $key): string
    {
        if (!array_key_exists($key, $this->server)) {
            throw new HttpException("$key not found");
        }

        $value = $this->server[$key];

        if (empty($value)) {
            throw new HttpException("$key is empty");
        }

        return $value;
    }

    private function getArrayValue(string $param, array $array): string
    {
        if (!array_key_exists($param, $array)) {
            throw new HttpException("Incorrect param for query: $param");
        }

        $value = $array[$param];

        if (empty($value)) {
            throw new HttpException("Not found param: $param");
        }

        return $value;
    }
}
