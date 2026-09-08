<?php
$texto = "Arpeco*2021";
$palabra = "*";
$texto = preg_replace ("/" . preg_quote($palabra, '/') . "/",
                       "<i>" . $palabra . "</i>",
                       $texto);
echo $texto;

return;
$cfile=file_get_contents('cdr/20219083557/R-20219083557-01-F001-00000028.xml');
//echo htmlspecialchars($cfile);



$car='cdr/20219083557/R-20219083557-01-F001-00000028.xml';
//$xml = new SimpleXMLElement($cfile);
$xml = simplexml_load_file($car);
$cbc = $xml->children('cbc', TRUE);
//echo $xml;
//echo $cbc;
$note = $cbc->Note;
$cac = $xml->children('cac', TRUE);
$response = $cac->DocumentResponse->Response;
$status = $response->Status;
$descri = $response->children('cbc', TRUE)->Description;  

$estado= $response->children('cbc', TRUE)->ResponseCode; 

echo  $estado .' '. $descri;   

//echo htmlspecialchars($xml->asXml());


//var_dump(htmlspecialchars($xml->asXml()));

//$doc=new DOMDocument();
//$doc->loadXML($cfile);

////echo "<\br>".$doc;
//$xml= new \SimpleXMLElement($car, null, true);
//echo "</br>".$xml;
//var_dump($xml);
//$xml = simplexml_load_file($car, 'SimpleXMLElement', LIBXML_NOCDATA);
//$líneas = file($car);
//echo "<br>";
//foreach ($líneas as $num_línea => $línea) {
 //   echo  htmlspecialchars($línea) . "<br />\n";
//}


