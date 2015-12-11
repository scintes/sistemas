<?php
include_once 'seguridad.php';
/*
echo $_SESSION['usuario'];
die();
*/
if(!isset($_SESSION['usuario'])){
    echo "debe logearse"; die();    
}else{
    include_once("template.php");
    include_once("conexion.php");
    include_once("funciones.php");
    include_once("propia.php");

    set_file("adminpasajes", "admin_pasajes.html");

    set_var('v_titulo_proyecto',         SIS_PROYECTO);

    set_var("v_color_cabezera_tabla",    COLOR_ENCOMIENDAS_CABEZERA_TABLA);
    set_var("v_color_cabezera_columna",  COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);
    set_var("v_color_foco",              COLOR_PASAJES_FOCO);
    set_var("v_color_seleccionado",      COLOR_PASAJES_SELECCIONADO);



    set_var("v_fecha", dar_fecha()); // fecha_entrega
    set_var("v_origen", "");  //localidad remitente
    set_var("v_destino", "");  // localidad destinatario

    // ----------------------------------------------------------------------------
    // --------------- Listado de tipo de salidas ---------------------------------
    // ----------------------------------------------------------------------------
    set_var("v_componente", "");
    set_var("v_tipo_salida", "");
    set_var("v_vehiculo", "");
    set_var("v_chofer", "");
    set_var("v_chofer_aux", "");
    set_var("v_cant_asientos", "0");
    set_var("v_cant_asientos_ocupados", "0");
    set_var("v_cant_asientos_reservado", "0");

    set_var("v_nro_viaje",'');
    set_var("v_nro_pasaje", "");
    set_var("v_dni", "");
    set_var("v_nro_asiento", "");
    set_var("v_factura_pasaje", "");
    set_var("v_nombre_pasaje", "");
    set_var("v_fecha_pasaje",'');
    set_var("v_direccion_pasaje", "");
    set_var("v_telefono_pasaje", "");
    set_var("v_celular_pasaje", "");
    set_var("v_importe_pasaje", "");
    set_var("v_coseguro_pasaje", "");
    set_var("v_pasaje_reservado","N"); // N: sin estado; R: Reservado; V: Vendido

    set_var("v_nro_siento_cliente", "");	
    set_var("v_nombre_cliente", "");	
    set_var("v_direccion_cliente", "");	
    set_var("v_tel_cliente", "");	
    set_var("v_importe_cliente", "");
    set_var("v_direccion_cliente", "");


    set_var("v_localidad_pasaje_o", "");
    set_var("v_direccion_pasaje_o", "");
    set_var("v_localidad_pasaje_d", "");
    set_var("v_direccion_pasaje_d", "");


    set_var("v_patente_vehiculo", "");
    set_var("v_fecha_rev_tec", "");
    set_var("v_cant_asiento_vehiculo", "");
    set_var("v_posee_video_vehiculo", "");
    Set_var("v_color_grilla_viajes_especiales",COLOR_VIAJE_ESPECIAL_LISTADO);
    Set_var("v_color_grilla_viajes_diario",COLOR_VIAJE_DIARIO_LISTADO);

    set_var("v_monto_total", "0.00");
    set_var("v_cant_cliente", "0");
    set_var("v_observacion_cliente", "");

    set_var("v_nombre_vehiculo", "");
    set_var("v_modelo", "");
    set_var("v_cantidad_choferes", "");

    set_var("v_id_viaje",'');
    set_var("v_importe_coseguro_pasaje",0.00);
    set_var("v_importe_deuda_pasaje",0.00);
    set_var("v_detalle_pagos_de_pasaje"," ");
    set_var("v_total_pasaje", 0.00);

    if ($_SESSION['id_sucursal']==SUCURSALES_PARA_CREAR_AUTOMAT_SALIDAS){
        set_var("v_desicion_de_crear_salidas", '1' );
}

$db = conectar_al_servidor();
	
//----------------------------------------------------------------------------------------------------
// Cargamos LA CONFIGURACION DE ARRANQUE PARA OBTENER LAS LOCALIDAD REMITENTE y DESTINATARIO POR DEFECTO
//----------------------------------------------------------------------------------------------------
$q = "SELECT cf.codigo, cf.id_loc_remitente_encomienda, cf.id_loc_destinatario_encomienda, cf.id_provincia_origen_encomienda 
      FROM conf_usuario cf  WHERE (cf.codigo = " . $_SESSION['id_conf_usuario'] . ")";

$res = ejecutar_sql($db, $q);

if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    // cargamos lo obtenido en las variables
    $id_loc_remit_desde = $res->fields[1];
    $id_loc_dest_desde  = $res->fields[2];
    $id_prov_orig       = $res->fields[3];
}

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de LOCALIDAD REMITENTE y DESTINATARIO
//----------------------------------------------------------------------------------------------------
$q = "SELECT codigo, concat(tipo_salida,' - ',destino), tipo_salida FROM salidas WHERE activa='S'";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    $combobox_loc_destino = "<option value='T' selected=true>Todos</option>";
    while (!$res->EOF) {
        // destino -------------------------------------------------------------
        $combobox_loc_destino = $combobox_loc_destino . "<option value=" .
        $res->fields[0].'@'.$res->fields[2] . "@>" . $res->fields[1] . "</option>";

        $res->MoveNext();
    }
}
set_var("v_combobox_destino", $combobox_loc_destino);


//-----------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
// Cargamos las Salidas con la configuracion de los vehiculos y choferes asociados
// Mustra los viajes Diario de la fecha actual y los viajes especiales desde la fecha actual en adelante
//------------------------------------------------------------------------------------------------------------------
$q = "SELECT 
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
        WHERE s.activa =  'S' and (v.fecha = CURRENT_DATE and s.tipo_salida='D')or(v.fecha >= CURRENT_DATE and s.tipo_salida='E')
        ORDER BY s.fecha, s.hora, s.destino";    
  



$res2 = ejecutar_sql($db, $q);
if (!$res2) {
    echo $db->ErrorMsg(); //die();
} else {
	$t_salidas = '';
    $color = '';
    $i = 0;
    while (!$res2->EOF) {
    	// Determinamos el color de los registro en la tabla 	
    	If ($res2->fields[9]=='E'){
		$color = COLOR_VIAJE_ESPECIAL_LISTADO;
	}else{
		$color = COLOR_VIAJE_DIARIO_LISTADO;
	}		
	// conformamos la secuencia de parametros al concentrador de seleccionador de salida
        $parametro = '"'.$res2->fields[20].'"'.", ".
                $res2->fields[8].", ".
                '"'.$res2->fields[12].'"'.", ".
                '"'.$res2->fields[14].'"'.", ".
                '"'.$res2->fields[17].'"'.", ".
                $res2->fields[18].", ".
                $res2->fields[19].", ".
                $res2->fields[20].", ".
                $res2->fields[21].", ".
                $res2->fields[22].", ".
                '"'.$res2->fields[9].'"'.", ".
                '"'.$res2->fields[0].'"'.", ".
                '"'.$res2->fields[1].'"'.", ".
                $res2->fields[10].", ".
                $res2->fields[11].", ".
                $i.", ".
                '"'.$res2->fields[3].'"';
        
        //echo $parametro; die();
        
        $t_salidas = $t_salidas ."<tr style='font-size: 13'  bgcolor='".$color."' onclick='consentrador_de_accion_al_seleccionar_el_viaje(".$parametro." );'>
        				<td>
                                            <Input type='Radio' Name='selector_salidas' value='Salidas' id='rb_selector_salidas".$i."'
                                            onclick=' consentrador_de_accion_al_seleccionar_el_viaje(".$parametro." );' />
                                        </td>                            		
                                        <td>".$res2->fields[3]."</td>
                                        <td>".cambiaf_a_normal($res2->fields[0])."</td>
                                        <td>".cambiah_a_normal($res2->fields[1])."</td>                            			
                            		<td>".$res2->fields[5]."</td>
                            		<td>".$res2->fields[8]."</td>
                            		<td>".$res2->fields[10]."</td>
                                        <td align='Center'>".$res2->fields[11]."</td>
                                        <td style='display: none' >".$res2->fields[12]."</td> 
                                        <td style='display: none' >".$res2->fields[13]."</td> 
                                        <td style='display: none' >".$res2->fields[14]."</td>
                                        <td style='display: none' >".$res2->fields[15]."</td>                                           
                                        <td align='Center'>".$res2->fields[23]."</td>                                                                                  
                                        <td align='Center'>".$res2->fields[24]."</td>  
                                  </tr>";
    //    var_dump($t_salidas); die();        
        $res2->MoveNext();
        $i++;
    }
}
set_var("v_salidas", $t_salidas);
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de Vehiculos
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de Choferes
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de Pasajes de vuelta todos los pasajes marcados como vueltas que aun no tengan 
// fecha de uso cargada.
//----------------------------------------------------------------------------------------------------
/*
$q = "select p.CODIGO, p.NOMBRE_Y_APELLIDO, p.FECHA, v.fecha_vencimiento, v.estado, p.dni
      from pasajes_de_vuelta AS v INNER JOIN pasajes AS p on (v.id_pasaje_origen=p.codigo)
      where v.fecha_uso is null 
      order by  p.NOMBRE_Y_APELLIDO, v.fecha_vencimiento ";
$res = ejecutar_sql($db, $q);
if (!$res){
    echo $db->ErrorMsg(); //die();
}else{
    $comboBox_pasaje_vuelta = "<option value=0>Seleccione un pasaje</option>";
    while (!$res->EOF) {
        // Datos del que viajo en el pasaje de ida------------------------------------------------- 
        $comboBox_pasaje_vuelta = $comboBox_pasaje_vuelta . "<option value=" .$res->fields[0]."#" .$res->fields[1]."#".$res->fields[2]."#" .$res->fields[3]."#".$res->fields[4]."#".$res->fields[5].">"
                   . $res->fields[0].' - '. $res->fields[3]. "</option>";
        $res->MoveNext();
    }
    set_var("v_comboBox_pasaje_vuelta",$comboBox_pasaje_vuelta);
}
*/
//---------------------------------------------------------------------------------------------------
parse('adminpasajes');

pparse('adminpasajes');

desconectar($db);

}
?>