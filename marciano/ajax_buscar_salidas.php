<?php
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("conexion.php");
include_once("funciones.php");

$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros por ajax
$fecha  = $_REQUEST["fecha"];
$data   = $_REQUEST["salida"]; // <codigo de salida>@< letra ("E", "D", "T")> @ 

if($data!='T'){
    $vec = explode('@', $data);
    $letra_salida = $vec[1];
    $cod_salida = $vec[0];
}else{
    $letra_salida = 'T';
}
//echo $data;
//die();


switch ($letra_salida) {
	case 'T': $letra = '';		
		break;
	
	default:
		$letra = "and(s.tipo_salida='".$letra_salida."')and(s.codigo = '".$cod_salida."')";
		break;
}

 
$sql = "SELECT 
            /* 0*/ v.fecha, 
            /* 1*/ s.hora, 
            /* 2*/ CASE s.tipo_salida WHEN 'D' THEN 'Diario' WHEN 'E' THEN 'Especial' END AS tipo, 
            /* 3*/ s.destino, 
            /* 4*/ s.estado, 
            /* 5*/ concat(vh.patente,' - ',vh.nombre) AS nombre , 
            /* 6*/ u.nombre , 
            /* 7*/ u2.nombre, 
            /* 8*/ COALESCE(vh.nro_asientos,0), 
            /* 9*/ s.tipo_salida,
            /*10*/ COALESCE((select count(*) from pasajes AS ps where ps.ID_VIAJE=v.codigo and ps.pagado='V'),0) as cant_pasaje_ocupados,
            /*11*/ COALESCE((select count(*) from pasajes AS ps where ps.ID_VIAJE=v.codigo and ps.pagado='R'),0) as cant_pasaje_reservado,
            /*12*/ concat(vh.COLUMNA_PB_11,'-',vh.COLUMNA_PB_12,'-',vh.COLUMNA_PB_21,'-',vh.COLUMNA_PB_22,'-') as conf_asiento_pb,
            /*13*/ concat(vh.COLUMNA_PA_11,'-',vh.COLUMNA_PA_12,'-',vh.COLUMNA_PA_21,'-',vh.COLUMNA_PA_22,'-') as conf_asiento_pa,
            /*14*/ vh.COLUMNA_CENTRAL_PB, 
            /*15*/ vh.COLUMNA_CENTRAL_PA,
            /*16*/ COALESCE(v.codigo,0) as codigo,
            /*17*/ COALESCE(v.id_vehiculo, 0) as id_vehiculo,
            /*18*/ COALESCE(v.id_chofer, 0) as id_chofer,
            /*19*/ COALESCE(v.id_guarda, 0) as id_guarda,
            /*20*/ COALESCE(v.codigo,0) as codigo,
            /*21*/ s.id_localidad_origen,
            /*22*/ s.id_localidad_destino,
            /*23*/ lo.localidad AS loc_ori,
            /*24*/ ld.localidad AS loc_des
            
            
        FROM viajes AS v
            INNER JOIN salidas AS s ON ( s.codigo = v.id_salida ) 
            LEFT JOIN vehiculos vh ON ( vh.patente = v.id_vehiculo ) 
            
            INNER JOIN localidades AS ld ON ld.codigo = s.id_localidad_destino
            INNER JOIN provincias AS pd ON pd.codigo = ld.id_provincia
            
            INNER JOIN localidades AS lo ON lo.codigo = s.id_localidad_origen            
            INNER JOIN provincias AS po ON po.codigo = lo.id_provincia
            
            LEFT JOIN usuarios u ON ( u.id = v.id_chofer ) 
            LEFT JOIN usuarios u2 ON ( u2.id = v.id_guarda ) 
        WHERE (s.activa ='S') and ((v.fecha = '". cambiaf_a_mysql($fecha)."' and s.tipo_salida='D')or(v.fecha >='". cambiaf_a_mysql($fecha)."' and s.tipo_salida='E'))".$letra."
        ORDER BY s.fecha, s.hora, s.destino";    
        
$res = ejecutar_sql($db,$sql);
  
if (!$res){  
    mensaje('Error accediendo a las salidas...');    
}else{
    $salidas = '';  
    while (!$res->EOF){
                   // tipo salida            -      destino      -              fecha                  -             hora                    - 
        $salidas = $salidas.$res->fields[2]."|".$res->fields[3]."|".cambiaf_a_normal($res->fields[0])."|".cambiah_a_normal($res->fields[1])."|".
                            //vehiculo      -      chofer       -      guarda       -   Cant Asient     - Cant Asien Ocu - 
                            $res->fields[5]."|".$res->fields[6]."|".$res->fields[7]."|".$res->fields[8]."|".$res->fields[10]."|".
                            // cant Asien Reser - Conf asient PB  -   Conf asient PA   -  onf Asient Cent PB - onf Asient Cent PB -                            
                            $res->fields[11]."|".$res->fields[12]."|".$res->fields[13]."|".$res->fields[14]."|".$res->fields[15]."|".
                            // Conf Asient Cent PA - Tipo Salida  -  id vehiculos      -
                            $res->fields[16]."|".$res->fields[9]."|".$res->fields[17]."|".
                            // id_chofer      -       id_guarda,        codigo viaje,       id loc origen,    id local destino,       local origen ,          loc destino
                            $res->fields[18]."|".$res->fields[19]."|".$res->fields[20]."|".$res->fields[21]."|".$res->fields[22]."|".$res->fields[23]."|".$res->fields[24]."|";
        $res->MoveNext();
        $salidas = $salidas."@";
    };     
    
};

echo $salidas;
desconectar($db);
die();
?>