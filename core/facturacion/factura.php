<?php


require __DIR__ . "/vendor/autoload.php";

use Core\Foundation\Application;
$data=file_get_contents("empresa.json");

$empresas=json_decode($data,true);
$app=Application::getInstance();
foreach($empresas as $empresa)
{
   // $oenvio->ReiniciacorrelativoResumenes();
  //  echo "</br>".$empresa['empresa'];
  //  echo "</br>".$empresa['dias'];
   // $oenvio->empresa=$empresa['empresa'];
   // $oenvio->dias=$empresa['dias'];
  //  $oenvio->consultacdr();
  //  $oenvio->generaenvios('01');
   // $oenvio->generaenvios('07');

   $app->empresa=$empresa['empresa'];
   $app->envio->empresa=$empresa['empresa'];
   $app->envio->dias=$empresa['dias'];

   $app->envio->consultacdr();
   $app->envio->generaenvios('01');
   $app->envio->generaenvios('07');


   /* $oenvio->generaenvios('03');*/
}
