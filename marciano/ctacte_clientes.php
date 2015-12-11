<?php
include_once ("seguridad.php");
include_once ("template.php");
include_once ("conexion.php");
include_once ("funciones.php");


require_once 'class.eyemysqladap.inc.php';
require_once 'class.eyedatagrid.inc.php';



//------------------------------------------------------------------------------
// Valores obtenidos del archivo de configuracion CONEXION.PHP
//------------------------------------------------------------------------------
set_var("v_color_cabezera_tabla",         COLOR_ENCOMIENDAS_CABEZERA_TABLA);
set_var("v_color_cabezera_columna_tabla", COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);
set_var("v_color_pie_tabla",              COLOR_ENCOMIENDAS_PIE_TABLA);
set_var("v_color_fila_pago_destino",      COLOR_ENCOMIENDAS_FILA_TIPO_PAGO_EN_DESTINO);
set_var('v_color_columna_remitente',      COLOR_ENCOMIENDAS_REMITENTE);
set_var('v_color_columna_destinatario',   COLOR_ENCOMIENDAS_DESTINATARIO);

set_file("ctacte_clientes", "ctacte_clientes.html");
set_var("v_nro_cliente","");
set_var("v_nombre_cliente","");
set_var("v_direccion_cliente","");
set_var("v_b_fecha_desde", dar_fecha());
set_var("v_b_fecha_hasta", dar_fecha());
set_var("v_cant_reg", 0);

set_var("v_nro_comprobante","");
set_var("v_nro_secuencia", "");
set_var("v_fecha", "");
set_var("v_operacion", "");
set_var("v_importe_debe", "");
set_var("v_importe_haber", "");

parse('listado_ctacte');

set_var("v_total_debe", "0.00");
set_var("v_total_haber", "0.00");

pparse("ctacte_clientes");

?>
