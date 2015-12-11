<?php

include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");
include_once("propia.php");

set_file("configuracion", "configuracion.html");
    set_var('v_titulo_proyecto',            SIS_PROYECTO);
    set_var('v_icono_sistema',              SIS_ICON_PROYECTO);

$aux = leer_ini_('./configuracion.ini');

set_var("v_color",   '#FFCC33');
//------------------------------------------------------------------------------
// datos de base de datos
//------------------------------------------------------------------------------
//echo $aux['sistemas']['HOST'];
//die();

set_var("v_host",    $aux);
set_var("v_usuario",    "root");
set_var("v_contrasenia", "123");
set_var("v_base",       'Prueba');
set_var("v_puerto",     "3166");
set_var("v_tipo_base",  "Mysql");

//------------------------------------------------------------------------------
// datos de base de datos
//------------------------------------------------------------------------------
set_var('v_cantidad_registro_por_pagina', 10);
set_var('v_usar_eliminacion_virtual', 'S'); // identifica que tipo de eliminacion hace en las grillas.
set_var('v_porcentaje_seguro_encomienda', 2); // representa el valor en porcentaje del a aplicar a al importe total de la encomienda del seguro..
set_var('v_porsentaje_iva_encomienda', 21); // representa el valor en porcentaje del iva a aplicar a las encomienda.
set_var('v_prioridad', "'urgente', 'rapido', 'normal', 'bajo'");
set_var('v_tipo_presponsable', "'responsable inscripto', 'monotributo', 'exento', 'consumidor final'");

//----------------------------------------------------------------------
// configuracion de membrete de las impresiones
//----------------------------------------------------------------------
set_var('v_logo', './imagenes/logo.jpg');
set_var('v_razon_social', 'marciano tourd srl');
set_var('v_direccion', 'iriondo 200 reconquista');
set_var('v_telefono', 'tel:0342-421234 email:info@marcianotoursrl.com.ar');
set_var('v_responsable', 'ri');
set_var('v_imagen_de_fondo', ''); //imagenes/fondo.jpg');
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//  parametros para funcionar el sistema
set_var('imagen_logo','./imagen/logo.jpg');
set_var('v_trabajar_con_rango_de_entrega', 'S'); // determina si se muestra o no el rango de entrega en encomienda
set_var('v_formato_de_hora', 'hh:mm');       // formato a utilizar en las hora con el sistema
set_var('v_formato_de_fecha', 'dd/mm/yyyy'); // formato a utilizar en las fechas por el sistema
set_var('v_factura_a', 'A'); // tipo de factura a utilizar
set_var('v_factura_b', 'B'); // tipo de factura a utilizar secundaria
set_var('v_porcentaje_cooseguro', '7.00'); // porcentaje del coseguro usado en pasajes.
// ---------------------------------------------------------------------
$mensaje_error_conexion = "error en la conexión de mysql: ";
$mensaje_error_ado = "error al crear el componente ado";
// ---------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//  configuracion de color para encomiendas
//-------------------------------------------------------------------------------------------------
set_var('v_color_encomiendas_remitente', '#f2f5a9');
set_var('v_color_encomiendas_destinatario', '#d1ffd3');

set_var('v_color_encomiendas_fila_comun', '#ffffff'); // usado en el archivo buscar_encomienda_a_cerrar.php; cierre
set_var('v_color_encomiendas_fila_tipo_pago_en_destino', '#f78181');
set_var('v_color_encomiendas_fila_tipo_pago_contado', '#bcf5a9');
set_var('v_color_encomiendas_fila_tipo_pago_ctacte', '#a9d0f5');
set_var('v_color_encomiendas_cabezera_tabla', '#ffcc33');
set_var('v_color_encomiendas_cabezera_columna', '#b3b4fa');
set_var('v_color_encomiendas_pie_tabla', '#fbcfe1');

//-------------------------------------------------------------------------------------------------
//  configuracion de color para pasajes
//-------------------------------------------------------------------------------------------------
set_var('v_color_pasajes_fila_comun', '#ffffff'); // usado en el archivo buscar_pasajes_a_cerrar.php; cierre
set_var('v_color_pasajes_fila_tipo_pago_en_destino', '#f78181');
set_var('v_color_pasajes_foco', '#f2f5a9');
set_var('v_color_pasajes_seleccionado', '#bef781');

set_var('v_color_viaje_diario_listado', '#e2a5b8');
set_var('v_color_viaje_especial_listado', '#a5e2ad');
set_var('v_color_fondo_carga_datos_pasaje_origen', "#f2f5a9");
set_var('v_color_fondo_carga_datos_pasaje_destino', '#d0f5a9');


pparse('configuracion');
?>
