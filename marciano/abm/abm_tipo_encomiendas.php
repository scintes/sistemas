<?php
//include_once("../seguridad.php");
include_once("../conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ABM Tipo Encomiendas</title>
	
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
$abm->tabla = "tipos_de_encomiendas";
$abm->registros_por_pagina = 5;
$abm->campoId = "codigo";
$abm->textoTituloFormularioAgregar = "Agregar tipos de encomiendas";
$abm->textoTituloFormularioEdicion = "Editar tipos de encomiendas";
//$abm->adicionalesInsert = ", fechaAlta=NOW()";




$abm->campos = array(
		array("campo" => "tipo_encomienda", 
					"tipo" => "texto", 
					"titulo" => "Tipo de encomienda", 
					"maxLen" => 50,
					"customPrintListado" => "<a href='javascript:alert(\"Ejemplo customPrintListado. id={id}\")' title='Ver usuario'>%s</a>",
					"requerido" => true,
					"hint" => "Ingrese el tipo de encomienda. Esta no debe existir."
					), 
		array("campo" => "descripcion", 
					"tipo" => "textarea", 
					"titulo" => "Descripción", 
					"noListar" => true,
					"hint" => "Ingrese cualquier comentario que desee pero no se abuse porque este es un ejemplo de hint largo."
					),
		array("campo" => "precio", 
					"tipo" => "num_real", 
					"titulo" => "Precio", 
					"maxLen" => 10,
					"requerido" => true,
					"hint" => "Ingrese el importe del tipo de encomienda."
					), 
        
		array("campo" => "aplica_descuentos", 
					"tipo" => "bit", 
					"titulo" => "Aplica descuentos", 
					"datos" => array("1"=>"SI", "0"=>"NO"),
					"valorPredefinido" => "1",
					"centrarColumna" => true,
					"hint" => "Indica si se realizan descuentos sobre el tipo de encomienda"
					),
		array("campo" => "Activa", 
					"tipo" => "bit", 
					"titulo" => "Activo", 
					"datos" => array("1"=>"SI", "0"=>"NO"),
					"valorPredefinido" => "1",
					"centrarColumna" => true,
					"hint" => "Indica si el usuario estar� activo"
					)
		);

$abm->generarAbm("", "Administrar los tipo de encmoiendas");
?>
</body>
</html>