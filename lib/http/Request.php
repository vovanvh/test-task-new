<?php

namespace http;

abstract class Request
{

    protected $_data;

    public function __construct(string $body)
    {
        $this->_data = $this->parseBody($body);
    }

    public function getData(): array
    {
        return $this->_data;
    }

    abstract protected function parseBody(string $body): array;
}
