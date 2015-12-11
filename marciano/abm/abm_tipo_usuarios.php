<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ABM Tipo Usuario</title>
	
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

//conexi�n a la bd
//$db = new class_db("localhost", "root", "123", "prueba");

$db = new class_db(HOST, USUARIO, PASSWORD, BASE);
$db->mostrarErrores = FALSE;
$db->connect();
$abm = new class_abm();
$abm->tabla = "tipos_de_usuarios";
$abm->registros_por_pagina = CANT_REG_PAGINA;
$abm->campoId = "codigo";
$abm->textoTituloFormularioAgregar = "Agregar tipo de usuario";
$abm->textoTituloFormularioEdicion = "Editar Tipo de usuario";
//$abm->adicionalesInsert = ", fechaAlta=NOW()";




$abm->campos = array(
		array("campo" => "codigo", 
					"tipo" => "texto", 
					"titulo" => "Codigo", 
					"maxLen" => 5,
					"requerido" => true,
					"hint" => "Identificador del tipo de usuario.",
                                        "nomodificar"=>true
					),     
		array("campo" => "tipo_usuario", 
					"tipo" => "texto", 
					"titulo" => "Tipo de usuario", 
					"maxLen" => 50,
					"requerido" => true,
					"hint" => "El tipo de usuario no debe existir."
					), 
		array("campo" => "descripcion", 
					"tipo" => "textarea", 
					"titulo" => "Descripción", 
					"maxLen" => 200,
					"noListar" => true,
					"requerido" => false,
					"hint" => "Ingrese una descripción para el tipo de usuario."
					),
		array("campo" => "activo", 
					"tipo" => "combo", 
					"titulo" => "Activo", 
					"datos" => array("S"=>"SI", "N"=>"NO"),
					"valorPredefinido" => "S",
					"centrarColumna" => true,
					"hint" => "Indica si el tipo de usuario estara activo"
					),					
		array("campo" => "comision", 
					"tipo" => "texto", 
					"titulo" => "Comisión venta", 
					"maxLen" => 10,
					"noListar" => false,
					"requerido" => true,
					"hint" => "Ingrese la comisión para el tipo de usuario para cuando hace una venta."
					),
                array("campo" => "comision_reserva", 
					"tipo" => "texto", 
					"titulo" => "Comisión reserva", 
					"maxLen" => 10,
					"noListar" => false,
					"requerido" => true,
					"hint" => "Ingrese la comisión para el tipo de usuario para cuando hace una reserva."
					)
					
		);

$abm->generarAbm("", "Administrar tipo de usuario");
?>
</body>
</html>