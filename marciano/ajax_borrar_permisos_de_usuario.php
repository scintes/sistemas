<?php
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros ajax
$id = $_REQUEST["id"]; // id usuario
$sp = $_REQUEST["s_p"]; // listado de seccion-pantalla

$sp_v = explode(", ", $sp);
$tam_v = sizeof($sp_v);
$i = 1;
$sql = '';

foreach ($sp_v as $a){
    $i++;
    $b = explode('-', $a);  
    if ($b[1]){
        if ($tam_v > $i-1){
            $sql = $sql." ( (id_seccion=".$b[0].") and (id_pantalla=".$b[1].") ) or ";
        }else{
            $sql = $sql." ( (id_seccion=".$b[0].") and (id_pantalla=".$b[1].") )";            
        }
    }
    
}

if($sql){
    $sql = "DELETE FROM pantallas_permisos WHERE  id_usuario=".$id." and ( ".$sql.");";    
}

$res = ejecutar_sql($db, $sql);

if (!$res){      
    echo 'Error al quitar los permisos...';
}else{    
    echo 'Se quitaron correctamente los permisos...';
};

desconectar($db);
?>