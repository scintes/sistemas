<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");
require_once "comun/class_db.php";
require_once "comun/class_abm.php";
require_once "comun/class_paginado.php";
require_once "comun/class_orderby.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>Demo abm</title>
	
	
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


//echo ($HOST. $USUARIO. $PASSWORD. $BASE); die();

//conexi�n a la bd
//$db = new class_db("localhost", "root", "123", "prueba");

$db = new class_db(HOST, USUARIO, PASSWORD, BASE);
$db->mostrarErrores = FALSE;
$db->connect();
$abm = new class_abm();
$abm->tabla = "comisiones_y_descuentos";
$abm->registros_por_pagina = 10;
$abm->campoId = "codigo";
$abm->textoTituloFormularioAgregar = "Agregar registro";
$abm->textoTituloFormularioEdicion = "Editar registro";
//$abm->adicionalesInsert = ", fechaAlta=NOW()";

//----- inicio de configuracion de busqueda ------------------------------------
$abm->logo = "../imagenes/comisiones_descuentos.jpg";
$abm->mostrarBuscador = False;
$abm->mostrarPaginado = 3; // muestra arriba y abajo
$abm->textoBusqueda   = '';
$abm->adicionales_buscar_Select = '';
// --- fin de configuracion de busqueda ----------------------------------------

$abm->campos = array(
		array("campo" => "tipo", 
					"tipo" => "combo", 
					"titulo" => "Tipo", 
                                        "datos" => array("D"=>"Descuentos", "C"=>"Comisión"),
                                        "valorPredefinido" => "D",
					"maxLen" => 1,
					"requerido" => true,
					"hint" => "Tipo de registro a trabajar"
					), 
		array("campo" => "nombre", 
					"tipo" => "texto", 
					"titulo" => "Nombre del registro", 
					"maxLen" => 50,
					"noListar" => false,
					"requerido" => true,
					"hint" => "Ingrese el nombre que identificará al regsitro."
                                        ),
		array("campo" => "porcentaje", 
					"tipo" => "texto", 
					"titulo" => "Porcentaje", 
					"maxLen" => 6,
					"noListar" => false,
					"requerido" => true,
					"hint" => "Ingrese el porcentaje a aplicar."
					),					
		array("campo" => "importe", 
					"tipo" => "num_real", 
					"titulo" => "Importe $", 
					"maxLen" => 6,
					"noListar" => false,
					"requerido" => true,
					"hint" => "Ingrese el valor  a aplicar."
					)					
		                
		);

$abm->generarAbm("", "Administrar Comisiones y descuentos");
?>
</body>
</html>