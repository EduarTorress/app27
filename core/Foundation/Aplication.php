<?php
namespace Core\Foundation;
use Core\Http\Request;
use Core\Routing\Router;

class Aplication{
    protected  static $instance;
    public $request;
    public $router;
    protected function __construct(){
      $this->request=new Request();
      $this->router=new Router($this->request);
    }
    public static function getInstancia(){
      if (is_null(self::$instance))
      {
          self::$instance=new Aplication();
      }
      return self::$instance;
    }
}
