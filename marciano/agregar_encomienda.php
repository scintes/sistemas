<?php

include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

set_file("agregar", "agregar_encomienda.html");

//----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
set_var("v_nro_guia", ""); // nro de guia
set_var("v_fecha", dar_fecha()); // fecha
set_var("v_remitente", ""); // Remitente     
set_var("v_dni_remitente", "");  // DNI remitente
set_var("v_dir_remitente", "");  // direccion_remitente
set_var("v_tel_remitente", ""); // tel_remitente
set_var("v_destinatario", "");  //destinatario
set_var("v_dni_destinatario", ""); // dni destinatario
set_var("v_dir_destinatario", ""); // direccion_destinatario
set_var("v_tel_destinatario", ""); // tel_destinatario
set_var("v_tipo_encomienda", ""); // tipo_encomienda
//set_var("v_prioridad", ""); // prioridad 			

set_var("v_fecha_entrega", incrementar_dia_en_fecha(dar_fecha(), 1)); // fecha_entrega		
set_var("v_hora_entrega_desde", dar_hora());
set_var("v_hora_entrega_hasta", dar_hora());

set_var("v_precio",					"0.00"); // precio encomienda 				
set_var("v_precio_total",				"0.00"); // precio encomienda 				
set_var("v_subtotal",					"0.00"); // precio encomienda 				
set_var("v_iva",					"0.00"); // precio encomienda 				
set_var("v_porcentaje_para_asegurar",         PORCENTAJE_SEGURO_ENCOMIENDA); // porcentaje a aplicar al declarar las seguro en la encomienda.
set_var("v_porcentaje_iva",                   PORCENTAJE_IVA_ENCOMIENDA); // porcentaje a aplicar al declarar las seguro en la encomienda.
set_var("v_comisionista", ""); // comisionista		
set_var("v_estado", "CARGANDO");  // estado encomienda
set_var("v_usuario", $_SESSION['usuario'] );  // usuario de carga
//				set_var("v_personal",				$res->fields[16]);	// usuario de cargfa							
set_var("v_localidad_remitente", "");  //localidad remitente
set_var("v_localidad_destinatario", "");  // localidad destinatario
set_var("v_nro_factura", "0.00");  // nro factura   
set_var("v_comision_comisionista", "0.00");  // comision_comisionista
set_var("v_comision_sucursal", "0.00");  // comision_sucursal
set_var("v_importe_seguro", "0.00");  // importe seguro  
set_var("e_importe_a_asegurar", "0.00");  // importe seguro 
set_var("v_observaciones", ""); // Observaciones realizadas 

set_var("e_encomienda_asegurada", "False");
set_var("e_debetener_frio", "False");
set_var("e_con_aviso_de_retorno", "False");

set_var("v_cantidad_pago", 0);
set_var("v_total_pago", 0.00);


$db = conectar_al_servidor();
$_SESSION['buscar_clientes']='';


//----------------------------------------------------------------------------------------------------
// Cargamos LA CONFIGURACION DE ARRANQUE PARA OBTENER LAS LOCALIDAD REMITENTE y DESTINATARIO POR DEFECTO
//----------------------------------------------------------------------------------------------------
$q = "select cf.codigo, cf.id_loc_remitente_encomienda, cf.id_loc_destinatario_encomienda, 
			cf.id_provincia_origen_encomienda 
		  from conf_usuario cf
		  where (cf.codigo = " . $_SESSION['id_conf_usuario'] . ")";

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
// Cargamos el comboBOX de tipo encomienda
//----------------------------------------------------------------------------------------------------
$q = "select codigo, tipo_encomienda, precio, aplica_descuentos from tipos_de_encomiendas te where te.activa='S' order by tipo_encomienda";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    $combobox_tipo_encomeinda = "<option value=0>Seleccione uno...</option>";
    while (!$res->EOF) {
        $combobox_tipo_encomeinda = $combobox_tipo_encomeinda . "<option value=".$res->fields[0] . ">" . $res->fields[1] ." - ( $ ".$res->fields[2]." ) </option>";
        $res->MoveNext();
    }
}
set_var("v_comboBox_tipo_encomienda", $combobox_tipo_encomeinda);
set_var("v_precio", $res->Fields[2]); // precio encomienda 				



//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de bancos
//----------------------------------------------------------------------------------------------------
$q = "select codigo, banco from bancos where activo='S' order by banco";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    $combobox_bancos = "<option value=0>Seleccione uno...</option>";
    while (!$res->EOF) {
        $combobox_bancos = $combobox_bancos . "<option value=".$res->fields[0] . ">" . $res->fields[1]."</option>";
        $res->MoveNext();
    }
}
set_var("v_comboBox_banco", $combobox_bancos);
 				




//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de prioridades
//----------------------------------------------------------------------------------------------------
$prioridad = unserialize(PRIORIDAD);
    $combobox_prioridad = $combobox_prioridad . "<option value=0 selected=TRUE>" . $prioridad[0] . "</option>";
for ($i = 1; $i <= count($prioridad); $i++) {
    $combobox_prioridad = $combobox_prioridad . "<option value=" . $i . ">" . $prioridad[$i] . "</option>";
};
set_var("v_combobox_prioridad", $combobox_prioridad);



//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de condicion de iva del cliente
//----------------------------------------------------------------------------------------------------
$cond_iva = unserialize(TIPO_RESPONSABLES);
    $combobox_condicion_iva = $combobox_condicion_iva . "<option value=0 selected=TRUE>" . $cond_iva[0] . "</option>";
for ($i = 1; $i <= count($cond_iva); $i++) {
    $combobox_condicion_iva = $combobox_condicion_iva . "<option value=" . $i . ">" . $cond_iva[$i] . "</option>";
};
set_var("v_combobox_condicion_iva", $combobox_condicion_iva);




//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de PROVINCIAS
//----------------------------------------------------------------------------------------------------
$q = "select codigo, provincia from provincias p ";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    while (!$res->EOF) {
    // Cargamos el listado de provincias y seleccionamos la provincia de Origen predeterminada
    if ($res->fields[0] == $id_prov_orig) {
        $combobox_prov_origen = $combobox_prov_origen . "<option value=" . $res->fields[0] . " selected=TRUE>" .
                $res->fields[1] . "</option>";
    } else {
        $combobox_prov_origen = $combobox_prov_origen . "<option value=" . $res->fields[0] . ">" . $res->fields[1]. "</option>";
    }
    // cargamos el listado de provincia de destino 
    $combobox_prov_destino = $combobox_prov_destino . "<option value=" . $res->fields[0] . ">" . $res->fields[1] . "</option>";        
    $res->MoveNext();
    }
}
set_var("v_combobox_prov_origen", $combobox_prov_origen);
set_var("v_combobox_prov_destino", $combobox_prov_destino);


//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de LOCALIDAD REMITENTE y DESTINATARIO
//----------------------------------------------------------------------------------------------------
 $q = "select l.codigo, l.localidad 	from localidades l where l.id_provincia=" . $id_prov_orig;
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    while (!$res->EOF) {

        // remitente --------------------------------------------------------------	
        if ($id_loc_remit_desde == $res->fields[1]) {
            $combobox_loc_remitente = $combobox_loc_remitente . "<option value=" .
                    $res->fields[0] . " selected=true>" . $res->fields[1] . "</option>";
        } else {
            $combobox_loc_remitente = $combobox_loc_remitente . "<option value=" .
                    $res->fields[0] . ">" . $res->fields[1] . "</option>";
        }
        // destinatario -----------------------------------------------------------
        if ($id_loc_dest_desde == $res->fields[1]) {
            $combobox_loc_destinatario = $combobox_loc_destinatario . "<option value=" .
                    $res->fields[0] . " selected=true>" . $res->fields[1] . "</option>";
        } else {
            $combobox_loc_destinatario = $combobox_loc_destinatario . "<option value=" .
                    $res->fields[0] . ">" . $res->fields[1] . "</option>";
        }

        $res->MoveNext();
    }
}
set_var("v_combobox_loc_remitente", $combobox_loc_remitente);
set_var("v_combobox_loc_destinatario", $combobox_loc_destinatario);


//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de COMISIONISTAS
//----------------------------------------------------------------------------------------------------
$q = "select u.id, u.nombre, u.usuario from usuarios u inner join tipos_de_usuarios tu on 
			(u.id_tipo_usuario=tu.codigo) and (tu.codigo=3)";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    $combobox_comisionista = "<option value=0>Seleccione uno...</option>";    
    while (!$res->EOF) {
        $combobox_comisionista = $combobox_comisionista . "<option value=" .$res->fields[0] . ">" . $res->fields[1] . "</option>";
        $res->MoveNext();
    }
}
set_var("v_comboBox_comisionista", $combobox_comisionista);
//----------------------------------------------------------------------------------------------------


desconectar($db);
pparse("agregar");
?>

