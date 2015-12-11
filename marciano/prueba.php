<?php
include_once 'funciones.php';

$fecha = '21/10/2014';
$fecha2 = cambiaf_a_mysql($fecha);
echo $fecha."   -  ".$fecha2;

$mifecha = explode('/','/'.$fecha);
$lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
echo  $lafecha;
?>
