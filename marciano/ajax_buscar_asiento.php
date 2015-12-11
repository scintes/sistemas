<?php
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros por ajax
$id_pasaje = $_REQUEST["id_pasaje"];
 
$sql = "SELECT  
            p.CODIGO,
            p.NOMBRE_Y_APELLIDO,
            p.DIRECCION_ORIGEN,
            p.DIRECCION_DESTINO,
            p.DNI,
            p.TELEFONO_ORIGEN,
            p.TELEFONO_DESTINO,
            p.OBSERVACIONES,
            p.FECHA,
            p.NRO_ASIENTO,
            p.PRECIO, 
            p.CELULAR_ORIGEN,
            p.CELULAR_DESTINO,
            p.POSEE_SEGURO,
            p.VALOR_SEGURO,
            p.NOMBRE_PERSONA_SEGURO,
            p.DNI_PERSONA_SEGURO,
            p.FECHA_NAC_PERSONA_SEGURO,            
   	    lo.localidad AS loc_o,
   	    ld.localidad AS loc_d,
            s.hora, 
            s.fecha,
            v.plataforma_salida,            
            tp.campo_interno, 
            p.pagado,
            p.usuario
            
        FROM
            pasajes AS p
            Inner Join tipo_pasajes AS tp ON p.id_tipo_pasaje=tp.codigo
            Inner Join localidades AS lo ON p.ID_LOCALIDAD_ORIGEN = lo.codigo
            Inner Join localidades AS ld ON p.ID_LOCALIDAD_DESTINO = ld.codigo
            Inner Join viajes AS v ON p.ID_VIAJE = v.codigo
            Inner Join salidas AS s ON v.id_salida = s.codigo            
        WHERE p.CODIGO = ".$id_pasaje;    

$res = ejecutar_sql($db,$sql);

if (!$res){  
    mensaje('Error accediendo a las salidas...');    
}else{
    $pasajes = '';       
    while (!$res->EOF){                
        // $pasaje = NRO_ASIENTO|(PAGADO+FORMA_VIAJE+POSEE_SEGURO)|
        $pasajes = $pasajes.$res->fields[0]."|".$pasajes.$res->fields[1]."|".$pasajes.$res->fields[2]."|".$pasajes.$res->fields[3].
                   "|".$pasajes.$res->fields[4]."|".$pasajes.$res->fields[5]."|".$pasajes.$res->fields[6]."|".$pasajes.$res->fields[7].
                   "|".$pasajes.$res->fields[8]."|".$pasajes.$res->fields[9]."|".$pasajes.$res->fields[10]."|".$pasajes.$res->fields[11].
                   "|".$pasajes.$res->fields[12]."|".$pasajes.$res->fields[13]."|".$pasajes.$res->fields[14]."|".$pasajes.$res->fields[15].
                   "|".$pasajes.$res->fields[16]."|".$pasajes.$res->fields[17]."|".$pasajes.$res->fields[18]."|".$pasajes.$res->fields[19].
                   "|".$pasajes.$res->fields[20]."|".$pasajes.$res->fields[21]."|".$pasajes.$res->fields[22]."|".$pasajes.$res->fields[23].
                   "|".$pasajes.$res->fields[24]."|".$pasajes.$res->fields[25];        
        $res->MoveNext();        
    };      
};

echo $pasajes;
desconectar($db);
die();
?>