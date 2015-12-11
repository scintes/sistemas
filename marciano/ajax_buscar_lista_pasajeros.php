<?php
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros por ajax
$id_viaje = $_REQUEST["id_viaje"];
$ord = $_REQUEST["ord"];

$sql = "SELECT
            p.NRO_ASIENTO,
            p.NOMBRE_Y_APELLIDO,
            p.DIRECCION_ORIGEN,
            p.TELEFONO_ORIGEN,
            COALESCE(p.PRECIO,0) + COALESCE(p.valor_seguro,0) as precio,
            p.codigo
        FROM pasajes AS p
        WHERE p.ID_VIAJE = ".$id_viaje." ORDER BY ".$ord;    


$res = ejecutar_sql($db,$sql);

if (!$res){  
    mensaje('Error accediendo a la tabla viaje...');    
}else{
    $pasajes = '';       
    while (!$res->EOF){                
        // $pasaje = NRO_ASIENTO|(PAGADO+FORMA_VIAJE+POSEE_SEGURO)|
        // Nro Asiento	Nombre	Dirección	Tel	Importe
        $pasajes = $pasajes.$res->fields[0]."|".$res->fields[1]."|".$res->fields[2]."|".$res->fields[3].
                   "|".$res->fields[4]."|".$res->fields[5].'@';
        
        $res->MoveNext();
        
    };      
};
echo $pasajes;
desconectar($db);
die();
?>