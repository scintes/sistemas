<?php
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros por ajax
$id_viaje = $_REQUEST["id_viaje"];
 
$sql = "SELECT
          /* 0 */ p.NRO_ASIENTO,
          /* 1 */ p.PAGADO,
          /* 2 */ p.FORMA_VIAJE,
          /* 3 */ p.POSEE_SEGURO,
          /* 4 */ p.CODIGO
        FROM pasajes as p
        WHERE p.ID_VIAJE = ".$id_viaje." ORDER BY p.nro_asiento";    

$res = ejecutar_sql($db,$sql);

if (!$res){  
    mensaje('Error accediendo a las salidas...');    
}else{
    $pasajes = '';       
    $estado = '';
    $codigo = '';

    while (!$res->EOF){                
        // $pasaje = NRO_ASIENTO|(PAGADO+FORMA_VIAJE+POSEE_SEGURO)|id_pasaje
        $pasajes = $pasajes.$res->fields[0]."|"; // conjunto de pasajes
        $estado =  $estado.$res->fields[1].$res->fields[2].$res->fields[3]."|"; // conjunto de estado de los pasajes
        $codigo = $codigo.$res->fields[4]."|"; // id_pasaje 
        $res->MoveNext();        
    };   
    $pasajes = $pasajes."@".$estado."@".$codigo;
    
};
echo $pasajes;
desconectar($db);
die();
?>