<?php
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros ajax
$nro_pasaje = $_REQUEST["nro_pasaje"];
 
$sql = "DELETE FROM pasajes WHERE  codigo=".$nro_pasaje;
$res = ejecutar_sql($db,$sql);

if (!$res){      
    echo "";
}else{    
    echo 'OK';
};

desconectar($db);
?>