<?php
//require_once("clases/envio.php");
use Clases\envio;
$data=file_get_contents("empresa.json");
$oenvio=new envio();
$empresas=json_decode($data,true);
foreach($empresas as $empresa)
{
    $oenvio->empresa=$empresa['empresa'];
    $oenvio->ReiniciacorrelativoResumenes();
}


?>