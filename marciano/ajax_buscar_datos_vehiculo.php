<?php
// Buscamos los viajes segun la fecha y/o salidas 
 include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros por ajax
$patente = $_REQUEST["id_viaje"];
$chofer  = $_REQUEST["id_chofer"];
$guarda  = $_REQUEST["id_guarda"];
 
$sql = "SELECT
            v.patente,
            v.nombre,
            v.NRO_ASIENTOS,
            v.CANTIDAD_CHOFERES,
            v.modelo,
            v.FECHA_VENCIMIENTO_TECNICA,
            v.CANTIDAD_CHOFERES
        FROM vehiculos AS v
        WHERE v.patente = '".$patente."'";    

$res = ejecutar_sql($db,$sql);

if (!$res){  
    mensaje('Error accediendo a la tabla viaje...');    
}else{
    $pasajes = '';       
    while (!$res->EOF){
        // patente | nombre | NRO_ASIENTOS | CANTIDAD_CHOFERES | modelo | FECHA_VENCIMIENTO_TECNICA |  CANTIDAD_CHOFERES                        
        $pasajes = $pasajes.$res->fields[0]."|".$res->fields[1]."|".$res->fields[2]."|".$res->fields[3].
                   "|".$res->fields[4]."|".cambiaf_a_normal($res->fields[5])."|".$res->fields[6];
        
        $res->MoveNext();
        
    };      
};
//****************************************************************************************
// chofer
//****************************************************************************************
$sql = "select 
            u.nombre, 
            u.id
        from usuarios as u
        where u.id = '".$chofer."'";    

$res = ejecutar_sql($db,$sql);

if (!$res){  
    mensaje('Error accediendo a la tabla de usuario...');    
}else{
    // nombre del chofer                        
    $pasajes = $pasajes."|".$res->fields[0];
};
//****************************************************************************************
// guarda
//****************************************************************************************
$sql = "select 
            u.nombre, 
            u.id
        from usuarios as u
        where u.id = '".$guarda."'";    

$res = ejecutar_sql($db,$sql);

if (!$res){  
    mensaje('Error accediendo a la tabla de usuario...');    
}else{
    // nombre del guarda                        
    $pasajes = $pasajes."|".$res->fields[0];
};

echo $pasajes;
desconectar($db);
die();
?>