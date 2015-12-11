<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ABM Tipo de Pasajes</title>
	
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
$abm->tabla = "tipo_pasajes";
$abm->registros_por_pagina = CANT_REG_PAGINA;
$abm->campoId = "codigo";
$abm->textoTituloFormularioAgregar = "Agregar Tipo de Pasajes";
$abm->textoTituloFormularioEdicion = "Editar Tipo de Pasajes";
//$abm->adicionalesInsert = ", fechaAlta=NOW()";




$abm->campos = array(
		array("campo" => "tipo_pasaje", 
					"tipo" => "texto", 
					"titulo" => "Nombre del Tipo", 
					"maxLen" => 50,
					"requerido" => true,
					"hint" => "Nombre identificatorio del paaje."
					), 
		array("campo" => "precio", 
					"tipo" => "num_real", 
					"titulo" => "Precio:", 
					"maxLen" => 10,
					"noListar" => false,
					"requerido" => true,
					"hint" => "Ingrese el importe de pasaje."
					),
                array('campo' => 'activo', 
                        'tipo' => 'combo',
                        'datos' => Array('S' => 'SI', 'N' => 'NO'),
                        'valorPredefinido' => "S",
                        'maxLen' => 1,
                        'centrarColumna' => true,
                        'titulo' => 'Activo'
                )/*, 
		array("campo" => "campo_interno", 
					"tipo" => "texto", 
					"titulo" => "Parametros Internos", 
					"maxLen" => 50,
					"requerido" => true,                                        
					"hint" => "No se deben modificar si no esta seguro... Por defecto deberia tener :'N#-#-#-#-#' esto imprimira un pasaje. 'S#-#-#-#-#' imprimira un pasaje de ida y vuelta. "
					)  */  
		);

$abm->generarAbm("", "Administrar Tipo de Pasajes");
?>
</body>
</html>