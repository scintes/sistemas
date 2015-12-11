<?php
// Muestra la grilla del resultado de la busqueda   
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros por ajax
$dato = $_POST["code"]; // dato a buscar.
 
$sql = 'SELECT pa.fecha_emision, tp.tipo_pasaje, pa.cantidad
        FROM pasajes_adelantados AS pa 
	Inner join tipo_pasajes AS tp ON tp.codigo = pa.id_tipo_pasaje  and  pa.id_cliente='.$dato;

$res = ejecutar_sql($db,$sql);

if (!$res){  
    $pasajes = 'Error accediendo a los pasajes adelantados...';    
}else{
    
    while (!$res->EOF){              
        $pasajes = $pasajes."<tr>".
                            "<td align='left'>".cambiaf_a_normal($res->fields['fecha_emision'])."</td>".
                            "<td align='left'>".$res->fields['tipo_pasaje']."</td>".
                            "<td align='left'>".$res->fields['cantidad']."</td>".        
                            '</tr>';        
        $res->MoveNext();        
    };      
};

echo $pasajes;
desconectar($db);
die();

?>
