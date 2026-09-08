<?php
ini_set('error_reporting', E_ALL | E_NOTICE | E_STRICT);
require __DIR__. "/vendor/autoload.php";

use Core\Foundation\Application;
$app=Application::getInstance();

$data=file_get_contents("empresa.json");
/*$oenvio=new envio();*/
$empresas=json_decode($data,true);
$fa=date('Y-m-d');
$f7=date('Y-m-d',strtotime($fa.'-8 day'));


foreach($empresas as $empresa){
    $app->empresa=$empresa['empresa'];
    $app->envio->empresa=$empresa['empresa'];
    $app->envio->dias=$empresa['dias'];
   
    for ($i=0;$i<5;$i++){
        $df = date("Y-m-d",strtotime ( $f7.'+'.$i .'day'));
  
   
        $app->envio->ObternerBoletasResumidas($df);
     
  
    }
   /* $oenvio->generaenvios('03');*/
}




/*$cticket="202107351226089";


$data="piletasanluis";





$ncon= new Conexion('piletasanluis');
$csql="select resu_tdoc,resu_serie,resu_desd,resu_hast,resu_tick,resu_mens,resu_idre,resu_arch,resu_fech FROM fe_resboletas f where resu_tick=:cticket order by resu_fech,resu_tdoc,resu_serie";

$st=$ncon->prepare($csql);
$st->bindParam(':cticket',$cticket);
$st->execute();

$ncon=null;

foreach($st as $row){
       $cserie=$row['resu_serie'];
       $ctdoc =$row['resu_tdoc'];
       $ndesde= $row['resu_desd'];
       $nhasta=$row['resu_hast'];
       $cmensaje=$row['resu_mens'];
       $df=$row['resu_fech'];
       $carchivo=pathinfo($row['resu_arch'],PATHINFO_FILENAME);
       echo "</br>".$carchivo;
}

$oenvio=new envio();
$oenvio->empresa=$data;
$emisor=$oenvio->ObtenerEmisor($df);
$detalle=$oenvio->ObtenerResumenBoletas($ndesde,$nhasta,$ctdoc);

$oapi1=new apifacturacion();
$oapi1->ConsultarTicket($emisor, $cticket,$carchivo,$data,$detalle);

return;*/
