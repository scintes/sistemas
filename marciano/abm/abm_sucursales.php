<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ABM Sucursales</title>
	
	
	<!-- Estilos -->
	<link href="../css/sitio.css" rel="stylesheet" type="text/css">
	<link href="../css/abm.css" rel="stylesheet" type="text/css">

	<!-- MooTools -->
	<script type="text/javascript" src="../js/mootools-1.2.3-core.js"></script>
	<script type="text/javascript" src="../js/mootools-1.2.3.1-more.js"></script>
	
	<!--FormCheck-->
	<script type="text/javascript" src="../js/formcheck/lang/es.js"></script>
	<script type="text/javascript" src="../js/formcheck/formcheck.js"></script>
	<link rel="stylesheet" href="../js/formcheck/theme/classic/formcheck.css" type="text/css" media="screen"/>

	<!--Datepicker-->
	<link rel="stylesheet" href="../js/datepicker/datepicker_vista/datepicker_vista.css" type="text/css" media="screen"/>
	<script type="text/javascript" src="../js/datepicker/datepicker.js"></script>
</head>
<body>
    
<?php

require_once "comun/class_db.php";
require_once "comun/class_abm.php";
require_once "comun/class_paginado.php";
require_once "comun/class_orderby.php";
//echo ($HOST. $USUARIO. $PASSWORD. $BASE); die();

//conexión a la bd
//$db = new class_db("localhost", "root", "123", "prueba");

$db = new class_db(HOST, USUARIO, PASSWORD, BASE);
$db->mostrarErrores = FALSE;
$db->connect();
$abm = new class_abm();
$abm->tabla = "sucursales";
$abm->registros_por_pagina = 5;
$abm->campoId = "codigo";
$abm->textoTituloFormularioAgregar = "Agregar sucursal";
$abm->textoTituloFormularioEdicion = "Editar sucursal";

$abm->campos = array(
		array("campo" => "nombre", 
					"tipo" => "texto", 
					"titulo" => "Nombre", 
					"maxLen" => 30,					
					"requerido" => true,
					"hint" => "La sucursal no debe existir"
					), 
		array("campo" => "direccion", 
					"tipo" => "texto", 
					"titulo" => "Dirección", 
					"maxLen" => 30,
					"noListar" => true,
					"requerido" => true,
					"hint" => "Direccion de la sucursal."
					),
		array("campo" => "tel", 
					"tipo" => "texto", 
                                        "maxLen" => 16,
					"titulo" => "Teléfono", 
					"noNuevo" => true
					),
		array("campo" => "email", 
					"tipo" => "texto", 
					"titulo" => "Email", 
					"maxLen" => 100,
					"requerido" => false
					),
    		array("campo" => "activa", 
					"tipo" => "bit", 
					"titulo" => "Activa", 
					"datos" => array("S"=>"SI", "N"=>"NO"),
					"valorPredefinido" => "S",                                        
					"centrarColumna" => true,
                                        "noListar" => false,
					"hint" => "Indica si el sucursal esta activa"
					),

		array("campo" => "color", 
					"tipo" => "color", 
					"titulo" => "color", 
					"maxLen" => 9,
                                        "requerido" => false,
                                        "hint" => "Designe un color representativo para la sucursal."
					),
		array("campo" => "porcentaje_comicion", 
					"tipo" => "num_real", 
					"titulo" => "Comición (%)", 
					"maxLen" => 50,
                                        "requerido" => true,
                                        "hint" => "Indique el porcentaje de comisión por venta que obtendra la sucursal."
					),
		array("campo" => "porcentaje_comision_reserva", 
					"tipo" => "num_real", 
					"titulo" => "Comisión Reserva (%)", 
					"noListar" => true,
                                        "hint" => "Indique el porcentaje de comisión por reserva que obtendra la sucursal."
					),
		array("campo" => "nro_recibo", 
					"tipo" => "num_ent", 
					"titulo" => "nro_recibo",
					"noEditar" => true, 
					"noListar" => true,
					"noNuevo" => true,
                                        "hint" => "Indique el ultimo numero impreso de recibo en la sucursal."
					),
                    array("campo" => "nro_factura_A", 
					"tipo" => "num_ent", 
					"titulo" => "Nro. factura A",
					"noEditar" => true, 
					"noListar" => true,
					"noNuevo" => true,
                                        "hint" => "Indique el ultimo numero impreso de las facturas tipo A en la sucursal."
					),
                    array("campo" => "nro_factura_B", 
					"tipo" => "num_ent", 
					"titulo" => "Nro. factura B",
					"noEditar" => true, 
					"noListar" => true,
					"noNuevo" => true,
                                        "hint" => "Indique el ultimo numero impreso de las facturas tipo B en la sucursal."
					),
                    array("campo" => "nro_factura_C", 
					"tipo" => "num_ent", 
					"titulo" => "Nro. factura C",
					"noEditar" => true, 
					"noListar" => true,
					"noNuevo" => true,
                                        "hint" => "Indique el ultimo numero impreso de las facturas tipo C en la sucursal."
					)
		);

$abm->generarAbm("", "Administrador de sucursales");
?>
</body>
</html>