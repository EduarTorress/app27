<?php

namespace Core\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    public function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function getUri()
    {
        return $_SERVER["REQUEST_URI"];
    }

    public function getPath()
    {
        $path = $_SERVER["REQUEST_URI"];
        $position = strpos($path, "?");
        if ($position === false) {
            return $path;
        }
        $path = substr($path, 0, $position);
        return $path;
    }

    /**
     * Unir los parametros GET con POST
     *
     * @return array
     */
    public function getBody(): array
    {
        $body = [];

        // usuarios?nombre=Juan&apellidos=Perez
        // $_GET => [
        //      "nombre" => Juan,
        //      "apellidos" => Perez
        // ]
        foreach ($_GET as $clave => $valor) {
            $body[$clave] = $valor;
        }
        // $body => [
        //      "nombre" => Juan,
        //      "apellidos" => Perez
        // ]



        // $_POST => [
        //      "apellido_paterno" => Juan,
        //      "apellido_materno" => Perez
        // ]
        foreach ($_POST as $clave => $valor) {
            $body[$clave] = $valor;
        }

        // $body => [
        //      "nombre" => Juan,
        //      "apellidos" => Perez
        //      "apellido_paterno" => Juan,
        //      "apellido_materno" => Perez
        // ]


        return $body;
    }

    public function get(string $parametro, $default = null)
    {
        $datos = $this->getBody();
        $valor = $datos[$parametro] ?? $default;
        return $valor;
    }
}
