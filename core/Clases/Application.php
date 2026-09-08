<?php
namespace Clases;
use Clases\envio;
date_default_timezone_set('America/Lima');

class Application{
  public $empresa;
  protected static $instance;
  
  protected function __construct(){
     $this->empresa="";
     $this->envio=new envio();
  }
  public static function getInstance(): Application
  {
      if (is_null(self::$instance)) {
          self::$instance = new Application();
      }
      return self::$instance;
  }
  
}
