<?php
// Muestra la grilla del resultado de la busqueda   
include_once("seguridad.php");
include_once("conexion.php");
include_once("funciones.php");
      

$dato = $_REQUEST["id"]; // dato a buscar.
$db = conectar_al_servidor();

$sql = "SELECT
            p.fecha,            
            CASE p.forma_pago 
                WHEN 1 THEN 'Contado'
                WHEN 2 THEN CONCAT('Cta.Cte.',' - ',cl.nombre,' ( ',cl.razon_social,' )')
                WHEN 3 THEN CONCAT('Cheque',' - nro:',ch.importe,' ( ',ch.entregado_por,' )')
                WHEN 4 THEN CONCAT('Tarjeta',' - nro:',tj.nombre)
                WHEN 5 THEN CONCAT('Reserva de pasaje',' - ','1 de ',pa.cantidad,'(',pa.observaciones,')')
            END  forma_de_pago,
            COALESCE(p.porcentaje_tarjeta, 0 ) as interes,
						p.importe as importe,
           (p.importe + (p.importe * p.porcentaje_tarjeta / 100)) as total
            
        FROM pagos AS p 
		left join ctactes  AS cc ON(p.codigo=cc.id_pago)
		left join clientes AS cl ON(cc.id_cliente=cl.codigo)
		left join cheques AS ch  ON(p.id_cheque=ch.codigo)
		left join tarjetas_creditos AS tj ON(p.id_tarjeta=tj.codigo)
		left join pasajes_adelantados AS pa ON(p.id_reserva=pa.codigo)
        WHERE p.id_pasaje=".$dato."
        ORDER BY p.fecha ASC";

$res = ejecutar_sql($db, $sql);
$pasajes = '';

if (!$res){  
    echo 'Error accediendo a los pagos...'; 
    die();
}else{ 
    $cant = $res->RecordCount();
    $pasajes = '';     
    if ($cant >= 1) {          
        while (!$res->EOF){            
            $pasajes = $pasajes.cambiaf_a_normal($res->fields[0])."|".$res->fields[1]."|".$res->fields[2]."|".$res->fields[3]."|".$res->fields[4]."|";              
            $res->MoveNext();
            $pasajes = $pasajes."@";
        }      
    }
}
                                    
echo $pasajes;
desconectar($db);
die();
?>
