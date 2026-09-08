<?php


namespace Core\Foundation;

ini_set('display_errors', '1');
ini_set('track_errors', 'On');

use Core\Clases\Envio;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Session;
use Core\Routing\Router;
use Valitron\Validator;

class Application
{
    protected static $instance;
    public Request $request;
    public Router $router;
    public Session $session;
    public string  $empresa;
    public static $rootdir;
    public string $dirp;
    public string $urlenvio;
    public string $urlconsulta;
    public string $entidad;
    public string $ht;
    public string $dt;
    public string $us;
    public string $pw;
    public Envio $envio;
    protected function __construct($root_dir, $config, $empresa)
    {
        // ORM::init();
        Validator::lang("es");
        self::$rootdir = $root_dir;
        $this->request = Request::createFromGlobals();
        $this->session = new Session();
        $this->router = new Router($this->request);
        $this->empresa = $empresa;
        $this->urlenvio = "";
        $this->urlconsulta = "";
        $this->dirp = $root_dir;
        $this->entidad = "";
        $this->ht = "";
        $this->dt = "";
        $this->pw = "";
        $this->us = "";
        $this->envio = new Envio();
    }

    // Setter: Modifica y valida el valor
    public function setHt(string $ht): void
    {
        $this->ht = trim($ht);
    }

    // Getter: Devuelve el valor
    public function getHt(): string
    {
        return $this->ht;
    }

    public static function getInstance($rootdir = '', $config = [], $empresa = ''): Application
    {
        if (is_null(self::$instance)) {
            self::$instance = new Application($rootdir, $config, $empresa);
        }
        return self::$instance;
    }

    public function run()
    {
        $respuesta = $this->router->compare(); // hasta ahora lo que retornaban los controladores era texto vista (o redirecciones)
        if (is_string($respuesta)) {
            echo $respuesta;
        } else if ($respuesta instanceof Response) {
            $respuesta->send(); // porque ahora usamos Response de Symfony
        }
    }
    public static function rootdir()
    {
        return self::$rootdir;
    }
}
