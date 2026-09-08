<?php

use Core\Foundation\Application;
use Core\Routing\Modelo;
use Core\View\View;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

function verificarSesion()
{
    if (empty(session()->get('usuario_id'))) {
        header('Location: /login');
    }
}
function view(string $viewFile, array $parameters = [])
{
    if (!($_SERVER["REQUEST_URI"] == '/login')) {
        verificarSesion();
    }
    $view = new View();
    return $view->render($viewFile, $parameters);
}
function session()
{
    $app = \Core\Foundation\Application::getInstance();
    $session = $app->session;
    return $session;
}
function left($str, $length)
{
    return substr($str, 0, $length);
}
function right($str, $length)
{
    return substr($str, -$length);
}
function muestraempresas()
{
    $empresas = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . 'empresa.json'), true);
    usort($empresas, function (array $elem1, $elem2) {
        return $elem1['empresa'] <=> $elem2['empresa'];
    });
    return $empresas;
}
function response()
{
    $response = new \Core\Http\Response();
    return $response;
}
function setempresa(String $empresa)
{
    $app = Application::getInstance();
    $app->empresa = $empresa;

    $empresas = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/empresa.json'), true);
    $urls = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/urlenvios.json'), true);

    $clave1 = array_search($empresa, array_column($empresas, 'empresa'));
    $entidad = $empresas[$clave1]['envio'];
    $clave = array_search($entidad, array_column($urls, 'entidad'));
    $app->urlenvio = $urls[$clave]['urlenvio'];
    $app->urlconsulta = $urls[$clave]['urlconsulta'];
}
if (!function_exists('auth')) {
    function auth(): \Core\Authentication\Authentication
    {
        return new Core\Authentication\Authentication();
    }
}
function retornavista(String $cruta, String $cvista)
{
    $app = \Core\Foundation\Application::getInstance();
    $filevista = $_ENV['DIR_ROOT'] . '/views/' . $cruta . '/' . $app->empresa . '/' . $cvista . '.php';
    if (file_exists($filevista)) {
        return $cruta . '/' . $app->empresa . '/' . $cvista;
    } else {
        return $cruta . '/' . $cvista;
    }
}
function cargarmenu()
{
    $app = \Core\Foundation\Application::getInstance();
    if (file_exists($_ENV['DIR_ROOT'] . '/app/menus/' . $app->empresa . '/' . 'menu.json')) {
        $menu = json_decode(file_get_contents($_ENV['DIR_ROOT'] . '/app/menus/' . $app->empresa . '/' . 'menu.json'), true);
    } else {
        $menu = json_decode(file_get_contents($_ENV['DIR_ROOT'] . '/app/menus/' .  'menu.json'), true);
    }
    return $menu;
}
function cargarconfig()
{
    $app = \Core\Foundation\Application::getInstance();
    if (file_exists($_ENV['DIR_ROOT'] . '/app/menus/' . $app->empresa . '/' . 'config.json')) {
        $config = json_decode(file_get_contents($_ENV['DIR_ROOT'] . '/app/menus/' . $app->empresa . '/' . 'config.json'), true);
    } else {
        $config = json_decode(file_get_contents($_ENV['DIR_ROOT'] . '/app/menus/' .  'config.json'), true);
    }
    $_SESSION['config'] = $config[0];
}
function datosglobales()
{
    $empresa = new Modelo();

    $rs = $empresa->datosempresa();
    $i = 0;
    foreach ($rs as $r) {
        $claves = array_keys($r);
        $valores = array_values($r);
        foreach ($claves as $clave) {
            $clave1 = 'gene_' . $clave;
            session()->set($clave1, $valores[$i]);
            $i++;
        }
    }
    // \session()->set('fe_gene', $rs);
}
function letraDelSubdominio($url)
{
    $dominio = parse_url($url, PHP_URL_HOST) ?? $url;
    $dominioLimpio = preg_replace('/^www\./', '', $dominio);
    return substr($dominioLimpio, 0, 1);
}
function obtenerUrlActual()
{
    // 1. Detectar si el protocolo es HTTP o HTTPS
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    // 2. Obtener el host (ej: ://ejemplo.com)
    $host = $_SERVER['HTTP_HOST'];
    // 3. Obtener la ruta y los parámetros (ej: /pagina?id=5)
    $ruta = $_SERVER['REQUEST_URI'];
    // Unir todas las partes
    return $protocolo . $host . $ruta;
}
function obtenercodigoisla()
{
    $isla = 1;
    $letrasubdominio = letraDelSubdominio(obtenerUrlActual());
    if (is_numeric($letrasubdominio)) {
        $isla = $letrasubdominio;
    }
    return $isla;
}
function datosglobalesmulti($codt)
{
    $empresa = new Modelo();

    $rs = $empresa->datosempresamulti($codt);
    $i = 0;
    foreach ($rs as $r) {
        $claves = array_keys($r);
        $valores = array_values($r);
        foreach ($claves as $clave) {
            $clave1 = 'gene_' . $clave;
            session()->set($clave1, $valores[$i]);
            $i++;
        }
    }
    // \session()->set('fe_gene', $rs);
}
function cargarmenucontabilidad()
{
    $app = \Core\Foundation\Application::getInstance();
    if (file_exists($_ENV['DIR_ROOT'] . '/app/menus/' . $app->empresa . '/' . 'menucontabilidad.json')) {
        $menu = json_decode(file_get_contents($_ENV['DIR_ROOT'] . '/app/menus/' . $app->empresa . '/' . 'menucontabilidad.json'), true);
    } else {
        $menu = json_decode(file_get_contents($_ENV['DIR_ROOT'] . '/app/menus/' .  'menucontabilidad.json'), true);
    }
    return $menu;
}
function cargarsucursales()
{
    $empresa = new Modelo();
    $rs = $empresa->cargarsucursales();
    return $rs;
}
function getnamemonth($idmonth)
{
    $nombre = "";
    switch ($idmonth) {
        case 1:
            $nombre = "Enero";
            break;
        case 2:
            $nombre = "Febrero";
            break;
        case 3:
            $nombre = "Marzo";
            break;
        case 4:
            $nombre = "Abril";
            break;
        case 5:
            $nombre = "Mayo";
            break;
        case 6:
            $nombre = "Junio";
            break;
        case 7:
            $nombre = "Julio";
            break;
        case 8:
            $nombre = "Agosto";
            break;
        case 9:
            $nombre = "Septiembre";
            break;
        case 10:
            $nombre = "Octubre";
            break;
        case 11:
            $nombre = "Noviembre";
            break;
        case 12:
            $nombre = "Diciembre";
            break;
    }
    return $nombre;
}
function cargarsucursalestbody()
{
    $empresa = new Modelo();
    $sucursales = $empresa->cargarsucursales();
    $i = 1;
    $nomb = [];
    foreach ($sucursales as $s) {
        switch ($i) {
            case 1:
                array_push($nomb, "uno");
                break;
            case 2:
                array_push($nomb, "dos");
                break;
            case 3:
                array_push($nomb, "tre");
                break;
        }
        $i += 1;
    }
    return $nomb;
}
function obtenercuentasbanco()
{
    $app = \Core\Foundation\Application::getInstance();
    if (file($_ENV['DIR_ROOT'] . '/app/menus/' . $app->empresa . '/' . 'cuentasbanco.json')) {
        $menu = json_decode(file_get_contents($_ENV['DIR_ROOT'] . '/app/menus/' . $app->empresa . '/' . 'cuentasbanco.json'), true);
    } else {
        $menu = json_decode(file_get_contents($_ENV['DIR_ROOT'] . '/app/menus/' .  'cuentasbanco.json'), true);
    }
    return $menu;
}
function listarubigeos()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://companiasysven.com/ubigeos.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $ubigeos = json_decode($response, true);
    usort($ubigeos, function (array $elem1, $elem2) {
        return $elem1['distrito'] <=> $elem2['distrito'];
    });
    return $ubigeos;
}
function fechavalida($dfecha)
{
    $valores = explode('/', $dfecha);
    if (count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])) {
        return true;
    }
    return false;
}
function evaluarvalortdoc($tdoc, $valor)
{
    if ($tdoc == '07') {
        if (floatval($valor) > 0) {
            return '-' . number_format($valor, 2, '.', '');
        } else {
            return number_format($valor, 2, '.', '');
        }
    } else {
        return number_format($valor, 2, '.', '');
    }
}
function enviarmensajerror($nombrefuncion, $error)
{
    $app = \Core\Foundation\Application::getInstance();
    $mensaje = "Error en el aplicativo web " . $app->empresa . " \n";
    $mensaje .= $nombrefuncion . " .\n";
    $mensaje .= $error[2];

    // $transport = Transport::fromDsn('smtp://cpe@compania-sysven.com:sysven2021*@mail.compania-sysven.com:587');
    $transport = Transport::fromDsn('smtp://cpe@companysysven.com:Sysven2024*@mail.companysysven.com:587');
    $mailer = new Mailer($transport);
    $email = (new Email());
    // $email->from('cpe@compania-sysven.com');
    $email->from('cpe@companysysven.com');
    $email->to('soporte@compania-sysven.com');
    $email->cc('eduartch@gmail.com');
    $email->subject("Error");
    $email->text($mensaje);

    // $email->attachFromPath($filexml);
    // $email->attachFromPath($filepdf);
    $mailer->send($email);
}
function verificarenquesucursalestoy()
{
    $idsucu = $_SESSION['idalmacen'];
    $nombsucu = "";
    switch ($idsucu) {
        case 1:
            $nombsucu = "uno";
            break;
        case 2:
            $nombsucu = "dos";
            break;
        case 3:
            $nombsucu = "tre";
            break;
    }
    return $nombsucu;
}
function convertirformatofecha($fecha)
{
    $fechac = str_replace('/', '-', $fecha);
    $fechaconvertida = date("Y-m-d", strtotime($fechac));
    return $fechaconvertida;
}
function validarrucregistro($ruc, $tdoc)
{
    if ($tdoc == '01' || $tdoc == '03' || $tdoc == '20') {
        if (trim($ruc) == trim(session()->get("gene_nruc"))) {
            return false;
        }
    }
    return true;
}
function validardiasatrasocpe($fecha)
{
    $fechaactual = date('Y-m-d');
    $fechaminima = date('Y-m-d', (strtotime('-2 day', strtotime($fechaactual))));
    if ($fechaminima > $fecha) {
        return false;
    }
    return true;
}
function validardiasadelantocpe($fecha)
{
    $fechamaxima = date('Y-m-d');
    if ($fecha > $fechamaxima) {
        return false;
    }
    return true;
}
function mostrarformapago($formapago)
{
    switch ($formapago) {
        case 'E':
            return 'EFECTIVO';
            break;
        case 'C':
            return 'CRÉDITO';
            break;
        case 'D':
            return 'DEPÓSITO';
            break;
        case 'T':
            return 'TARJETA';
            break;
        case 'Y':
            return 'YAPE';
            break;
        case 'P':
            return 'PLIN';
            break;
    }
}
function array_columns()
{
    $args = func_get_args();
    $array = array_shift($args);
    if (!$args) {
        return $array;
    }
    $keys = array_flip($args);
    return array_map(function ($element) use ($keys) {
        return array_intersect_key($element, $keys);
    }, $array);
}
function obtenertodasfechasxrango($dfi, $dff)
{
    $currentDate = $dfi;
    $datesArray  = [];
    while (strtotime($currentDate) <= strtotime($dff)) {
        $datesArray[] = $currentDate;
        $currentDate  = date('Y-m-d', strtotime('+1 day', strtotime($currentDate)));
    }
    return $datesArray;
}
function agruparPorClave(array $datos, string $clave): array
{
    $resultado = [];
    foreach ($datos as $item) {
        if (!isset($item[$clave])) {
            continue; // o lanzar una excepción
        }
        $resultado[$item[$clave]][] = $item;
    }
    return $resultado;
}
// function obtenervalor($valorinicial, $valorpordefecto)
// {
//     $valorfinal = (!empty($valorinicial) ? $valorinicial : $valorpordefecto);
//     return $valorfinal;
// }
