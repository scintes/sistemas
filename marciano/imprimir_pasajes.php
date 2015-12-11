<?php
include_once ("seguridad.php");
include_once ("template.php");
include_once ("conexion.php");
include_once ("funciones.php");

require('./fpdf/fpdf.php');   

set_time_limit(600);//10 minutos
ini_set("max_execution_time","600");

//base64_decode($_REQUEST["id_viaje"],$strict); 
$nro_asiento    = base64_decode( $_REQUEST["nro_asiento"],$strict);
$nombre         = base64_decode( $_REQUEST["nombre"]     ,$strict);
$dni            = base64_decode( $_REQUEST["dni"]        ,$strict);
$loc_o          = base64_decode( $_REQUEST["loc_o"],$strict);
$dir_o          = base64_decode( $_REQUEST["dir_o"],$strict);
$loc_d          = base64_decode( $_REQUEST["loc_d"],$strict);
$dir_d          = base64_decode( $_REQUEST["dir_d"],$strict);
$telefono       = base64_decode( $_REQUEST["telefono"],$strict);
$celular        = base64_decode( $_REQUEST["celular"],$strict);
$coseguro       = base64_decode( $_REQUEST["coceguro"],$strict);
$nro_pasaje     = base64_decode( $_REQUEST["nro_pasaje"],$strict);
$factura        = base64_decode( $_REQUEST["factura"],$strict);
$fecha_pasaje   = base64_decode( $_REQUEST['fecha_p'],$strict);
$fecha          = base64_decode( $_REQUEST['fecha'],$strict);
$hora           = base64_decode( $_REQUEST['hora'],$strict);
$importe        = base64_decode( $_REQUEST["importe"],$strict);
$observacion    = base64_decode( $_REQUEST["ob"],$strict);
$nro_viaje      = base64_decode( $_REQUEST["nro_viaje"],$strict);
$plataforma     = base64_decode( $_REQUEST["plataforma"],$strict);
$pasaje_ida_y_vuelta = base64_decode( $_REQUEST["piv"],$strict);

// Impresion en PDF
$pdf=new FPDF();
$pdf->AddPage();  

$pdf->SetFont('Arial','',8);
$pdf->SetTextColor(255,0,0); 

/********************** DATOS DEL TALON MAYOR - INTERESADO ************************************/
$w = 73;	//Ancho de celda
$h = 4;		//Alto de celda
$i = 35;
$pdf->SetFont('Arial','B',10); //determinacion del formato
$pdf->SetXY(2,$i);     $pdf->Cell($w, $h, $loc_o,                    0,0,'L');  $pdf->SetXY(52,$i);      $pdf->Cell($w, $h, $loc_o,                    0,0,'L');                                                                        $pdf->SetXY(150,$i-3);    $pdf->Cell($w, $h, $plataforma,0,0,'L');
$pdf->SetXY(2,$i+7);   $pdf->Cell($w, $h, $dir_o,                    0,0,'L');  $pdf->SetXY(52,$i+7);    $pdf->Cell($w, $h, $dir_o,                    0,0,'L');  $pdf->SetXY(105,$i+7);     $pdf->Cell($w, $h, $fecha,0,0,'L');        $pdf->SetXY(150,$i+7);  $pdf->Cell($w, $h, $dir_o,0,0,'L'); 
$pdf->SetXY(2,$i+15);  $pdf->Cell($w, $h, $loc_d,                    0,0,'L');  $pdf->SetXY(52,$i+15);   $pdf->Cell($w, $h, $loc_d,                    0,0,'L');  $pdf->SetXY(105,$i+15);    $pdf->Cell($w, $h, $hora, 0,0,'L');        $pdf->SetXY(150,$i+15); $pdf->Cell($w, $h, $loc_o,0,0,'L');
$pdf->SetXY(2,$i+23);  $pdf->Cell($w, $h, $dir_d,                    0,0,'L');  $pdf->SetXY(52,$i+23);   $pdf->Cell($w, $h, $dir_d,                    0,0,'L');  $pdf->SetXY(105,$i+23);    $pdf->Cell($w, $h, $nro_asiento,0,0,'L');  $pdf->SetXY(150,$i+23); $pdf->Cell($w, $h, $loc_d,0,0,'L'); 
$pdf->SetXY(2,$i+30);  $pdf->Cell($w, $h, $fecha,                    0,0,'L');  $pdf->SetXY(52,$i+30);    $pdf->Cell($w, $h, $fecha,                    0,0,'L');  $pdf->SetXY(105,$i+30);    $pdf->Cell($w, $h, $importe,0,0,'L');     $pdf->SetXY(150,$i+30); $pdf->Cell($w, $h, $dir_d,0,0,'L'); 
$pdf->SetXY(2,$i+38);  $pdf->Cell($w, $h, $hora,                     0,0,'L');  $pdf->SetXY(52,$i+38);   $pdf->Cell($w, $h, $hora,                     0,0,'L');                                                                        $pdf->SetXY(105,$i+38); $pdf->Cell($w, $h, $plataforma,0,0,'L');
$pdf->SetXY(2,$i+45);  $pdf->Cell($w, $h, $nro_asiento,              0,0,'L');  $pdf->SetXY(52,$i+45);   $pdf->Cell($w, $h, $nro_asiento,              0,0,'L');  $pdf->SetXY(105,$i+45);    $pdf->Cell($w, $h, $plataforma,0,0,'L');   $pdf->SetXY(150,$i+45); $pdf->Cell($w, $h, 'DNI:'.$dni,0,0,'L'); 
$pdf->SetXY(2,$i+53);  $pdf->Cell($w, $h, $importe,                  0,0,'L');  $pdf->SetXY(52,$i+53);   $pdf->Cell($w, $h, $importe,                  0,0,'L');                                                                        $pdf->SetXY(150,$i+53); $pdf->Cell($w, $h, $nombre,0,0,'L');
$pdf->SetXY(2,$i+65);  $pdf->Cell($w, $h, $nro_viaje."-".$nro_pasaje,0,0,'L');  $pdf->SetXY(52,$i+65);   $pdf->Cell($w, $h, $nro_viaje."-".$nro_pasaje,0,0,'L');  $pdf->SetXY(105,$i+65);    $pdf->Cell($w, $h, $observacion,0,0,'L');  
$pdf->SetXY(2,$i+73);  $pdf->Cell($w, $h, $_SESSION['sucursal'],     0,0,'L');  $pdf->SetXY(52,$i+73);   $pdf->Cell($w, $h, $_SESSION['sucursal'],     0,0,'L');  $pdf->SetXY(135,$i+80);    $pdf->Cell($w, $h, $loc_o,0,0,'L');        $pdf->SetXY(195,$i+80); $pdf->Cell($w, $h, $nro_asiento,0,0,'L'); 
$pdf->SetXY(2,$i+79);  $pdf->Cell($w, $h, $loc_o,                    0,0,'L');  $pdf->SetXY(52,$i+79);   $pdf->Cell($w, $h, $loc_o,                    0,0,'L');  

//$pdf->Output('pasaje.pdf','I');  
// verificamos que no sea un pasaje de IDA y VUELTA 
if ($pasaje_ida_y_vuelta=='S'){
// Se esta por imprimir un pasaje de ida y vuelta esta seccion en el pasaje de vuelta donde no se confirman los siguientes campos:
// Fecha, Hora, nro_asiento, importe (Ya se cobro en el anterior), nro pasaje,     
$pdf->AddPage();  
$pdf->SetXY(2,$i);     $pdf->Cell($w, $h, $loc_o,                    0,0,'L');  $pdf->SetXY(52,$i);     $pdf->Cell($w, $h, $loc_o,                    0,0,'L');                                                                         $pdf->SetXY(150,$i-3);    $pdf->Cell($w, $h, $plataforma,0,0,'L');
$pdf->SetXY(2,$i+7);   $pdf->Cell($w, $h, $dir_o,                    0,0,'L');  $pdf->SetXY(52,$i+7);   $pdf->Cell($w, $h, $dir_o,                    0,0,'L');  $pdf->SetXY(105,$i+7);     $pdf->Cell($w, $h, 'A Coordinar', 0,0,'L'); $pdf->SetXY(150,$i+7);  $pdf->Cell($w, $h, $dir_o,0,0,'L'); 
$pdf->SetXY(2,$i+15);  $pdf->Cell($w, $h, $loc_d,                    0,0,'L');  $pdf->SetXY(52,$i+15);  $pdf->Cell($w, $h, $loc_d,                    0,0,'L');  $pdf->SetXY(105,$i+15);    $pdf->Cell($w, $h, 'A Coordinar', 0,0,'L'); $pdf->SetXY(150,$i+15); $pdf->Cell($w, $h, $loc_o,0,0,'L');
$pdf->SetXY(2,$i+23);  $pdf->Cell($w, $h, $dir_d,                    0,0,'L');  $pdf->SetXY(52,$i+23);  $pdf->Cell($w, $h, $dir_d,                    0,0,'L');  $pdf->SetXY(105,$i+23);    $pdf->Cell($w, $h, 'A Coordinar', 0,0,'L'); $pdf->SetXY(150,$i+23); $pdf->Cell($w, $h, $loc_d,0,0,'L'); 
$pdf->SetXY(2,$i+30);  $pdf->Cell($w, $h, 'A Coordinar',             0,0,'L');  $pdf->SetXY(52,$i+30);  $pdf->Cell($w, $h, 'A Coordinar',             0,0,'L');  $pdf->SetXY(105,$i+30);    $pdf->Cell($w, $h, '------',      0,0,'L'); $pdf->SetXY(150,$i+30); $pdf->Cell($w, $h, $dir_d,     0,0,'L'); 
$pdf->SetXY(2,$i+38);  $pdf->Cell($w, $h, 'A Coordinar',             0,0,'L');  $pdf->SetXY(52,$i+38);  $pdf->Cell($w, $h, 'A Coordinar',             0,0,'L');                                                                         $pdf->SetXY(105,$i+38); $pdf->Cell($w, $h, $plataforma,0,0,'L');
$pdf->SetXY(2,$i+45);  $pdf->Cell($w, $h, 'A Coordinar',             0,0,'L');  $pdf->SetXY(52,$i+45);  $pdf->Cell($w, $h, 'A Coordinar',             0,0,'L');  $pdf->SetXY(105,$i+45);    $pdf->Cell($w, $h, $plataforma,   0,0,'L'); $pdf->SetXY(150,$i+45); $pdf->Cell($w, $h, 'DNI:'.$dni,0,0,'L'); 
$pdf->SetXY(2,$i+53);  $pdf->Cell($w, $h, '------',                  0,0,'L');  $pdf->SetXY(52,$i+53);  $pdf->Cell($w, $h, '------',                  0,0,'L');                                                                         $pdf->SetXY(150,$i+53); $pdf->Cell($w, $h, $nombre,    0,0,'L');
$pdf->SetXY(2,$i+65);  $pdf->Cell($w, $h, '------',                  0,0,'L');  $pdf->SetXY(52,$i+65);  $pdf->Cell($w, $h, '------',                  0,0,'L');  $pdf->SetXY(105,$i+65);    $pdf->Cell($w, $h, $observacion,  0,0,'L');  
$pdf->SetXY(2,$i+73);  $pdf->Cell($w, $h, $_SESSION['sucursal'],     0,0,'L');  $pdf->SetXY(52,$i+73);  $pdf->Cell($w, $h, $_SESSION['sucursal'],     0,0,'L');  $pdf->SetXY(135,$i+80);    $pdf->Cell($w, $h, $loc_o,        0,0,'L'); $pdf->SetXY(195,$i+80); $pdf->Cell($w, $h, $nro_asiento,0,0,'L'); 
$pdf->SetXY(2,$i+79);  $pdf->Cell($w, $h, $loc_o,                    0,0,'L');  $pdf->SetXY(52,$i+79);  $pdf->Cell($w, $h, $loc_o,                    0,0,'L');  
     
}
$pdf->Output('pasaje.pdf','I'); 
?>

