<?php
//----------------------------------------------------------------------------------------------------------
// Realiza la carga de los datos del pasaje y el pago del mismo.
//----------------------------------------------------------------------------------------------------------
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

set_file("altapasajes", "alta_pasajes.html");

// obtenemos el id del viaje...
$venta_o_reserva = $_REQUEST["t"]; // identificamos si es una venta (V), reserva (R), o (U) Utilizacion
$id_viaje        = base64_decode($_REQUEST["id_viaje"],$strict); // codigo del viaje a donde se asociara el pasaje.
$lista_pasajes   = base64_decode($_REQUEST["asientos"],$strict); // lista de asientos a los cuales se asociaran con el viaje
$id_loc_desde    = base64_decode($_REQUEST["id_ld"],$strict);
$id_loc_hasta    = base64_decode($_REQUEST["id_lh"],$strict);
$forma_viaje     = base64_decode($_REQUEST["fv"],$strict);
$datos_pi        = base64_decode($_REQUEST["datos_pi"],$strict); // obtenemos los datos del pasaje de ida 


$db = conectar_al_servidor();



/*
if($venta_o_reserva=='U'){// si es utilizacion
    $dpi =  explode("#",$datos_pi);
    set_var("v_dni_pasaje",      number_format($dpi[5]) );
    set_var("v_nombre_pasaje",   $dpi[1]);    
}else{ // Si es reserva o venta
}
*/

set_var("v_dni_pasaje",          "");
set_var("v_nombre_pasaje",       "");    


set_var('v_forma_salida_aux', $forma_viaje);
set_var('v_datos_pi',            $datos_pi);

$lp =  explode("|",$lista_pasajes);

$td = '<table>';
$sl = ''; 
for($i=0;$i<=(sizeof($lp)-2); $i++){    
    $td = $td."<tr><td>".$lp[$i]."</td><td>Libre</td></tr>";
    $sl = $sl." <option value=".$lp[$i].">Asiento nro: ".$lp[$i]."</option>";
}
$td = $td."</table>";

set_var("v_listado_asientos_seleccionados", $td);  //
set_var("v_listado_nro_asiento", $sl);
set_var("v_venta_o_reserva",$venta_o_reserva);// V,R o U
set_var("v_operacion_selec_tipo_pasaje","");
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de TIPO DE PASAJES
//----------------------------------------------------------------------------------------------------
if($venta_o_reserva=='U'){// si es utilizacion mostramos todos los pasajes menos los de vuelta.
    $q = "SELECT codigo, CONCAT(tipo_pasaje,' - $:',precio) AS tipo_pasaje, campo_interno FROM tipo_pasajes WHERE activo='S'and left(campo_interno,2)=".'"'."'V".'"'." ORDER BY tipo_pasaje";
}else{ // si es <> a utilizacion no mostramos el resto de los tipos de pasajes sino solo los de vuelta. 
    $q = "SELECT codigo, CONCAT(tipo_pasaje,' - $:',precio) AS tipo_pasaje, campo_interno FROM tipo_pasajes WHERE activo='S'and left(campo_interno,2)<>".'"'."'V".'"'."  ORDER BY tipo_pasaje";    
}    
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    
    if($venta_o_reserva<>'U'){// si es <> a utilizacion no mostramos el resto de los tipos de pasajes 
        $combobox_tipo_pasaje = "<option value=0>Seleccione uno...</option>";
    }

    while (!$res->EOF) {
         $combobox_tipo_pasaje =  $combobox_tipo_pasaje . "<option value=".$res->fields[0]."#".$res->fields[2].">".$res->fields[1]."</option>";
         $res->MoveNext();
    }    
}
set_var("v_listado_tipo_pasaje",  $combobox_tipo_pasaje);
//set_var("h_operaciones_tipo_pasajes",$tipo_pasaje_operaciones);


//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de LOCALIDAD REMITENTE y DESTINATARIO
//----------------------------------------------------------------------------------------------------
$q = "select l.codigo, l.localidad  from localidades l order by  l.localidad ";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    while (!$res->EOF) {

        // remitente -------------------------------------------------------------- 
        if ($id_loc_desde == $res->fields[0]) {
            $combobox_loc_remitente = $combobox_loc_remitente . "<option value=" .
                    $res->fields[0] . " selected=true>" . $res->fields[1] . "</option>";
        } else {
            $combobox_loc_remitente = $combobox_loc_remitente . "<option value=" .
                    $res->fields[0] . ">" . $res->fields[1] . "</option>";
        }
        // destinatario -----------------------------------------------------------
        if ($id_loc_hasta == $res->fields[0]) {
            $combobox_loc_destinatario = $combobox_loc_destinatario . "<option value=" .
                    $res->fields[0] . " selected=true>" . $res->fields[1] . "</option>";
        } else {
            $combobox_loc_destinatario = $combobox_loc_destinatario . "<option value=" .
                    $res->fields[0] . ">" . $res->fields[1] . "</option>";
        }
        $res->MoveNext();
    }
}

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de Pasajes de vuelta 
//----------------------------------------------------------------------------------------------------
$q = "select p.NOMBRE_Y_APELLIDO, p.CODIGO, p.FECHA, v.fecha_vencimiento
      from pasajes_de_vuelta AS v INNER JOIN pasajes AS p on (v.id_pasaje_origen=p.codigo)
      order by  p.NOMBRE_Y_APELLIDO, v.fecha_vencimiento ";
$res = ejecutar_sql($db, $q);
if (!$res){
    echo $db->ErrorMsg(); //die();
}else{
    while (!$res->EOF) {
        // remitente -------------------------------------------------------------- 
        $comboBox_pasaje_vuelta = $comboBox_pasaje_vuelta . "<option value=" .
                  $res->fields[1] . ">" . $res->fields[0].' - '. $res->fields[3]. "</option>";
        $res->MoveNext();
    }
    set_var("v_comboBox_pasaje_vuelta",$comboBox_pasaje_vuelta);
}

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de Cuentas de pasajes adelantados
//----------------------------------------------------------------------------------------------------
$q = "SELECT pa.codigo, pa.cantidad, pa.fecha_emision, cl.dni, cl.razon_social
      FROM pasajes_adelantados AS pa
      INNER JOIN clientes AS cl ON pa.id_cliente = cl.codigo
      ORDER BY cl.razon_social ASC ";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    $combobox_cuenta = "<option value=0>Seleccione uno...</option>";
    while (!$res->EOF) {
        $combobox_cuenta = $combobox_cuenta . "<option value=".$res->fields[0]."@".$res->fields[1]. ">" .$res->fields[4]." - ".$res->fields[1]."</option>";
        $res->MoveNext();
    }
}
set_var("v_comboBox_cuenta", $combobox_cuenta);
set_var('v_cantidad_pasaje_reservados',0);

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de tarjetas de creditos con las cuotas
//----------------------------------------------------------------------------------------------------
$q = " SELECT tc.codigo, tc.nombre, tc.porcentaje, tc.cuotas
       FROM prueba.tarjetas_creditos AS tc WHERE tc.activa='S'         ORDER BY tc.nombre, tc.cuotas";

$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    $combobox_tarjetas = "<option value='0' selected=true>Seleccionar uno...</option>";
    while (!$res->EOF) {
        // destino -------------------------------------------------------------
        $combobox_tarjetas = $combobox_tarjetas . "<option value=".$res->fields[0]."@".$res->fields[2].">"
            .$res->fields[3]." Cuotas en ".$res->fields[1]."</option>";
        $res->MoveNext();
    }
}
set_var("v_comboBox_tarjeta_credito", $combobox_tarjetas);

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
//------------------------------------------------------------------------------

set_var("v_comboBox_banco", $combobox_bancos);

set_var("v_titulo_pagina","ABM Pasajes");
set_var("v_listado_localidad_origen", $combobox_loc_remitente);
set_var("v_listado_localidad_destino",$combobox_loc_destinatario);
set_var("v_loc_or", $id_loc_desde); // guardamos la loc origen de la salida
set_var("v_loc_de", $id_loc_hasta); // guardamos la loc destino de la salida

set_var("v_color_cabezera_tabla",    COLOR_ENCOMIENDAS_CABEZERA_TABLA);
set_var("v_color_cabezera_columna",  COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);
set_var("v_datos_de_la_salidas", "");  //

set_var("v_id_viaje", $id_viaje);
set_var("v_nro_asiento",     "");
//set_var("v_dni_pasaje",      "");
//set_var("v_nombre_pasaje",   "");

set_var("v_direccion_origen_pasaje",  "");
set_var("v_direccion_destino_pasaje", "");
set_var("v_telefono_origen_pasaje",   "");
set_var("v_telefono_destino_pasaje",  "");
set_var("v_celular_origen_pasaje",    "");
set_var("v_celular_destino_pasaje",   "");
                        
set_var("v_coseguro_pasaje",        "");   
set_var("v_dni_coseguro_pasaje",    "");                        
set_var("v_nombre_coseguro_pasaje", "");

set_var("v_fecha_nacimiento_coseguro_pasaje",     "");

set_var("v_importe_pasaje",                   "0.00");
set_var("v_porsentaje_coseguro", PORCENTAJE_COSEGURO);
set_var("v_importe_coseguro_pasaje",          "0.00");
                
set_var("v_nro_siento_cliente", "");    
set_var("v_nombre_cliente",     "");    
set_var("v_direccion_cliente",  ""); 
set_var("v_tel_cliente",        "");   
set_var("v_importe_cliente",    "");
set_var("v_direccion_cliente",  "");
set_var("v_observaciones",      "");

set_var("v_color_origen",  COLOR_FONDO_CARGA_DATOS_PASAJE_ORIGEN);
set_var("v_color_destino",COLOR_FONDO_CARGA_DATOS_PASAJE_DESTINO); 

set_var("v_total_pago", "0.00");

set_var("v_lineas",LINEAS);
set_var("v_guarda",    "");

set_var("v_color_mando_botonera_mando",COLOR_FONDO_BOTONERA_MANDO);
set_var("v_color_fondo_boton_mando",      COLOR_FONDO_BOTON_MANDO);
set_var("v_color_texto_boton_mando",      COLOR_TEXTO_BOTON_MANDO);

set_var("v_pag_a_ver",CANT_REG_PAGINA); // cantidad de registro a visualizar
set_var("v_indise_pag_a_ver",       1); // Desde que registro visualizar.
set_var("v_cantidad_registro_total",0);
set_var("v_cantidad_registros",     0);

parse ('altapasajes');
pparse('altapasajes');

desconectar($db);
?>