<?php
require __DIR__ . "/vendor/autoload.php";

use Core\Foundation\Application;
use Dompdf\Dompdf;
use FPDF as GlobalFPDF;
use Fpdf\Fpdf;
use Clases\Cletras;
$app= Application::getInstance();

$app->empresa="movicenter";
$st=$app->envio->consultardcto(137383);

// // $dompdf = new Dompdf();
// // ob_start();



// // // instantiate and use the dompdf class
// // $html = ob_get_clean();
// // $dompdf->loadHtml($html);



// // (Optional) Setup the paper size and orientation
// $dompdf->setPaper('A4');

// // Render the HTML as PDF
// $dompdf->render();

// // Output the generated PDF to Browser
// //$dompdf->stream();
// header("Content-type:application/pdf");
// header("Content-Disposition:inline; filename=documento.pdf");
// echo $dompdf->output();

$importeletras=new Cletras();
$pdf = new FPDF();
$pdf->AddPage('P','A4');

$i=1;
foreach($st as $fila){
    if($i==1){
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(40,10,$fila['empresa']);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->setx(120);
        $pdf->cell(80, 6, "RUC   ".$fila['rucempresa'], 'LRT', 1, 'C', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->cell(100, 6, $fila['ptop']);
        $pdf->setx(120);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->cell(80, 6, ' FACTURA ELECTRONICA', 'LR', 1, 'C', 0);
        $pdf->cell(100);
        $pdf->setx(120);
        $pdf->cell(80, 6, $fila['serie'] . '-' . $fila['numero'], 'BLR', 0, 'C', 0);
        $pdf->ln(3);
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',8);
        $pdf->cell(100,5,'RUC......: '.$fila['nruc']);
        $pdf->setx(145);
        $pdf->cell(50,5,'GUIA-REMISION: '.$fila['ndo2']);
        $pdf->Ln();
        $pdf->cell(100,5,'CLIENTE..: '.utf8_decode($fila['razo']));
        $pdf->setx(145);
        $pdf->cell(50,5,'VENDEDOR: ');
        $pdf->Ln();
        $pdf->cell(100,5,'DIRECCION: '.utf8_decode($fila['direccion']));
        $pdf->setx(145);
        $pdf->cell(80,5,'FORMA DE PAGO: ' . ($fila['form']=='E' ? 'CONTADO':'CREDITO'));
        
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->cell(10, 6, 'ITEM', 1, 0, 'C', 0);
        $pdf->cell(20, 6, 'CANTIDAD', 1, 0, 'C', 0);
        $pdf->cell(116, 6, 'PRODUCTO', 1, 0, 'C', 0);
        $pdf->cell(20, 6, 'V.U.', 1, 0, 'C', 0);
        $pdf->cell(25, 6, 'SUBTOTAL', 1, 1, 'C', 0);
    }
    $pdf->SetFont('Arial', '', 7);
    $pdf->cell(10, 6, $i, 1, 0, 'C', 0);
    $pdf->cell(20, 6, number_format($fila['cant'],2,'.',','), 1, 0, 'R', 0);
    $pdf->cell(116, 6, $fila['descri'], 1, 0, 'L', 0);
    $pdf->cell(20, 6, number_format($fila['prec'],2,'.',','), 1, 0, 'R', 0);
    $pdf->cell(25, 6, number_format(round($fila['cant']*$fila['prec'],2),2,'.',','), 1, 1, 'R', 0);
    $i++;
}
$pdf->ln();
$pdf->SetFont('Arial', 'B', 7);
$cletras =$importeletras->ValorenLetras($fila['impo'],$fila['moneda']==='S' ? 'SOLES' : 'DOLARES');
$pdf->cell(120,5,"SON: ".$cletras);
$pdf->setx(148);
$pdf->cell(25, 6, 'VALOR GRAVADO', 1, 0, 'R', 0);
$pdf->cell(29, 6, number_format($fila['valor'],2,'.',','), 1, 0, 'R', 0);
$pdf->ln();
$pdf->setx(148);
$pdf->cell(25, 6, 'I.G.V.', 1, 0, 'R', 0);
$pdf->cell(29, 6, number_format($fila['igv'],2,'.',','), 1, 0, 'R', 0);
$pdf->ln();
$pdf->setx(148);
$pdf->cell(25, 6, 'TOTAL ', 1, 0, 'R', 0);
$pdf->cell(29, 6, number_format($fila['impo'],2,'.',','), 1, 0, 'R', 0);
$pdf->ln();
$pdf->Output();


?>