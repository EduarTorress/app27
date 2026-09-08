<?php

namespace Core\Http;

abstract class Middleware
{
    protected array $methods = [];

    public function __construct(array $methods = [])
    {
        $this->methods = $methods;
    }

    abstract public function execute(string $method);
}
