<?php

namespace Core\Routing;

use Core\Foundation\Application;
use Core\Http\Request;
use Core\Http\Session;

class Router
{
    protected array $routes = [
        "get" => [],
        "post" => []
    ];

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get(string $path, array $action)
    {
        $this->routes["get"][] = [
            "path" => $path,
            "action" => $action
        ];
    }

    public function post(string $path, array $action)
    {
        $this->routes["post"][] = [
            "path" => $path,
            "action" => $action
        ];
    }

    public function compare()
    {
        $path = $this->request->getPathInfo();
        $method = strtolower($this->request->getMethod());

        $routes = $this->routes[$method];
        $action = false;
        $parametros = [];

        foreach ($routes as $route) {
            if ($path === $route["path"]) {
                $action = $route["action"];
            } else {
                $ruta_separada = explode("/", $route['path']);
                foreach ($ruta_separada as $posicion => $valor) {
                    if (substr($valor, 0, 1) === "{") {
                        $ruta_separada[$posicion] = "([A-Za-z0-9]+)";
                    }
                }
                $ruta_unificada = implode("/", $ruta_separada);
                $ruta_patron = "#^" . $ruta_unificada . "$#";
                if (preg_match($ruta_patron, $path, $coincidencia)) {
                    array_shift($coincidencia);
                    $parametros = $coincidencia;
                    $action = $route["action"];
                }
            }
        }
        if ($action === false) {
             return "Ruta no existe";
        }
        $nombre_controlador = $action[0];
        $nombre_metodo = $action[1];
      

        // $objeto es un objeto de una clase Controlador => Controller
        $objeto = new $nombre_controlador; // creado un objeto de clase Controllador
        $parametros[] = $this->request;

        // aqui ejecutar los filtros/middlewares

        $middlewares = $objeto->getMiddlewares();
        foreach ($middlewares as $middleware) {
            $middleware->execute($nombre_metodo); // inicio
        }
        return call_user_func_array([$objeto, $nombre_metodo], $parametros); // se ejecuta el metodo del controlador
    }
    function buscaempresa($empresa)
    {
     
        $empresas = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . 'empresa.json'), true);
        $encontrado=false;
        foreach($empresas as $valor){
             if($valor['empresa']===$empresa){
                 $encontrado=true;
                 break;
             };
        }
        return $encontrado;
    
    }
}
