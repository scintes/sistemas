<?php
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros
$patente = $_REQUEST["code"];
$nro_viaje = $_REQUEST["nro_v"];
 
$sql = "UPDATE viajes set id_vehiculo ='".$patente."' WHERE  codigo=".$nro_viaje;
$res = ejecutar_sql($db,$sql);

if (!$res){      
    echo "code:".$patente."   nro_viaje".$nro_viaje." ---  ".$sql;
}else{    
    echo 'OK';
};

desconectar($db);
?>