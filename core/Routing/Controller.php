<?php

namespace Core\Routing;

use Core\Http\Middleware;

class Controller
{
    protected array $middlewares = [];

    public function registerMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
