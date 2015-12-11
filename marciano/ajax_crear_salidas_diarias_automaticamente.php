<?php
include_once("seguridad.php");
include_once("conexion.php");

$db = conectar_al_servidor();
$fecha = $_POST["fecha"];

$mifecha = explode('/','/'.$fecha);
$fecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];

$sql = "CALL crear_viajes_diarios_auto('".$fecha."');";
$res = ejecutar_sql($db,$sql);

if (!$res){
    echo 'Error accediendo a las localidades...'.$sql;
}else{
    echo 'OK'.$sql;    
}

desconectar($db);
?>