<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ABM Localidades</title>
	
	
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
$abm->tabla = "localidades";
$abm->registros_por_pagina = 5;
$abm->campoId = "codigo";
$abm->textoTituloFormularioAgregar = "Agregar Localidades";
$abm->textoTituloFormularioEdicion = "Editar Localidades";
//$abm->adicionalesInsert = ", fechaAlta=NOW()";




$abm->campos = array(
		array("campo" => "localidad", 
					"tipo" => "texto", 
					"titulo" => "Localidad", 
					"maxLen" => 50,
					"requerido" => true,
					"hint" => "La localidad no debe existir para la provinc"
					), 
		array("campo" => "codigo_postal", 
					"tipo" => "texto", 
					"titulo" => "Codigo Postal", 
					"maxLen" => 10,
					"noListar" => true,
					"requerido" => true,
					"hint" => "Ingrese el codigo postal de la localidad"
					),
                array("campo" => "id_provincia", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT codigo, provincia FROM provincias ORDER BY provincia ASC", 
					"campoValor" => "codigo", 
					"campoTexto" => "provincia", 
					"titulo" => "Provincia",
					"incluirOpcionVacia" => false,
					"noListar" => true,
					"requerido" => true,
                                        "hint" => "Seleccione la provicnia que pertenece la localidad"
					)
		);

$abm->generarAbm("", "Administrar Localidades");
?>
</body>
</html>