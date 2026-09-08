<?php

namespace Core\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response extends SymfonyResponse
{
    public function json(array $data, int $code = 200)
    {
        $this->setContent(json_encode($data));
        $this->setStatusCode($code);
        $this->headers->set('Content-Type', 'application/json');
        return $this;
    }
}
