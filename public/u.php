<?php

require __DIR__ . "/../vendor/autoload.php";

use App\Controllers\UsuarioController;
use Core\Foundation\Application;

date_default_timezone_set('America/Lima');
$app = Application::getInstance();
$rootdir = dirname(__DIR__);
$_ENV['DIR_ROOT'] = $rootdir;
$dotenv = \Dotenv\Dotenv::createImmutable($rootdir);
$dotenv->load();
$ousuario = new UsuarioController();
$password = '006373';
$obj = $ousuario->BuscarUsuario("SOPORTE");
echo $obj[0]['nomb'] . ' ' . $obj[0]['idusua'];
if (password_verify($password, $obj[0]['clave']) === false) {
  echo 'incorrecta';
} else {
  echo 'Correcta';
}
