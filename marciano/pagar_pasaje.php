<?php
include_once("seguridad.php");
include_once("conexion.php");
include_once("template.php");
include_once("funciones.php");

//------------------------------------------------------------------------------
// parametros recibidos desde el javascript de admin_pasajes.html
//------------------------------------------------------------------------------
$nro_asiento             = base64_decode($_REQUEST["code"],            $strict); // Nro de asiento.
$id_viaje                = base64_decode($_REQUEST["id_v"],            $strict); // clave unica del viaje seleccionado para agregar el vehiculo.
$deuda                   = base64_decode($_REQUEST["deuda"],           $strict); // importe de la deuda de la reserva
$importe_pasaje          = base64_decode($_REQUEST["importe_pasaje"],  $strict); // importe de la deuda de la reserva
$importe_coseguro_pasaje = base64_decode($_REQUEST["importe_coseguro"],$strict); // valor en moneda del cooseguro de la reserva
$destino                 = base64_decode($_REQUEST["destino"],         $strict); // cantidad de asientos reservados
$fecha                   = base64_decode($_REQUEST["fecha"],           $strict); // fecha del viaje
$hora                    = base64_decode($_REQUEST["hora"],            $strict);   // hora del viaje     
$nro_pasaje              = base64_decode($_REQUEST["nro_pasaje"],      $strict)."-";   // nro del  viaje     
//$llamado                 = base64_decode($_REQUEST["llamado_por"],     $strict); //viene un 1 para pasaje adelantado y si es 2 es pasajes (pagaçgo de pasaje reservado)
$llamado                 = $_REQUEST["llamado_por"];
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// usados para cuando en invocada desde psajes_adelantados.php
// datos_Adicionales = f_desde|cantidad|id_tipo_pasaje|tipo_pasaje|razon_s|observacion|ID_clientes|
$datos_adic              = base64_decode($_REQUEST["d_a"],$strict);
$d_a = explode('|', $datos_adic);

$f_d       = $d_a[0]; // fecha
$cant      = $d_a[1]; // cantidad de pasajes reservados 
$id_t_pas  = $d_a[2]; // id de tipo de pasajes reservados   
$t_pas     = $d_a[3]; // tipo de pasajes reservados
$raz       = $d_a[4]; // razon social de la empresa que compra los pasajes
$observ    = $d_a[5]; // observacion sobre la venta de los pasajes adelantados.
$id_cli    = $d_a[6]; // ID del cliente a cual se imputaran los pasajes adelantados 

set_var('v_fecha_desde',                 $f_d); 
set_var('v_cantidad_pasaje_reservados', $cant);
set_var('v_tipo_pas',                  $t_pas);        
set_var('v_razon_soc',                  $raz ); 
set_var("v_id_cliente",               $id_cli);

// se guarda para pasarlo a insertar_pagos
set_var("v_d_a",                  $datos_adic);

//                  fin de pasajes adelantados
//------------------------------------------------------------------------------

$id_pasaje = explode('-', $nro_pasaje)[1];     


set_var('v_llamado', $llamado);//se lo pasa pasajes_adelantados.html en la variable llamado_por y lo pasa a al html en la var hidden v_llamado

set_var('v_fecha_viaje', $fecha     );
set_var('v_hora_viaje',  $hora      );
set_var("v_pasaje",      $nro_pasaje);

set_file("pagar_pasaje", "pagar_pasaje.html");

set_var('v_color_cabezera_columna', COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);
set_var('v_total_pago','0.00');

set_var("v_deuda_pasaje",   "0.00"); 
set_var("v_importe_pasaje", "0.00");
set_var("v_importe_coseguro_pasaje","0.00");

set_var("v_destino", $destino);
set_var('v_id_viaje', $id_viaje);

set_var("v_color_cabezera_tabla",    COLOR_ENCOMIENDAS_CABEZERA_TABLA);
set_var("v_color_cabezera_columna",  COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);
set_var("v_interes_tarjeta", PORCENTAJE_INTERES_TARJETA);

set_var("v_color_mando_botonera_mando",COLOR_FONDO_BOTONERA_MANDO);
set_var("v_color_fondo_boton_mando",COLOR_FONDO_BOTON_MANDO);
set_var("v_color_texto_boton_mando",COLOR_TEXTO_BOTON_MANDO);

set_var("v_interes_tarjeta", 0);
set_var("v_id_tarjeta",     "");
set_var("v_id_banco",       "");

set_var("v_importe_con_interes_pago", $deuda);

set_var("v_deuda_pasaje",   $deuda); 
set_var("v_importe_pasaje", $deuda);

set_var("v_importe_coseguro_pasaje", $importe_coseguro_pasaje);
//set_var("v_cantidad_pasaje_reservados", 0);
set_var("v_id_pasaje",$id_pasaje);

$db = conectar_al_servidor();

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de tarjetas de creditos con las cuotas
//----------------------------------------------------------------------------------------------------
$q = " SELECT tc.codigo, tc.nombre, tc.porcentaje, tc.cuotas
       FROM prueba.tarjetas_creditos AS tc WHERE tc.activa='S'  ORDER BY tc.nombre, tc.cuotas";

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
set_var("v_comboBox_banco", $combobox_bancos);
 				

//------------------------------------------------------------------------------
// verificamos que no se llame a este archivo desde pasajes_adelantados para no 
// cargar todo el sql al dope
if($llamado!=1){
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
}

parse ('pagar_pasaje');
pparse('pagar_pasaje');

desconectar($db);

?>
