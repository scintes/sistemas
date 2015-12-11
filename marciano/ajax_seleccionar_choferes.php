<?php
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros
$id_chofer = $_REQUEST["code"];
$id_guarda = $_REQUEST["code2"];
$nro_viaje = $_REQUEST["nro_v"];
 
$sql = "UPDATE viajes SET id_chofer ='".$id_chofer."', "
                     . "  id_guarda ='".$id_guarda."' "
       . "WHERE  codigo=".$nro_viaje;
$res = ejecutar_sql($db,$sql);

if (!$res){      
    echo "code:".$patente."   nro_viaje".$nro_viaje." ---  ".$sql;
}else{    
    echo 'OK';
};

desconectar($db);
?>