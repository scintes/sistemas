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
	<title>ABM Configuración de Usuarios</title>
	
	
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

//conexión a la bd
//$db = new class_db("localhost", "root", "123", "prueba");

$db = new class_db(HOST, USUARIO, PASSWORD, BASE);
$db->mostrarErrores = FALSE;
$db->connect();
$abm = new class_abm();
$abm->tabla = "conf_usuario";
$abm->registros_por_pagina = 5;
$abm->campoId = "codigo";
$abm->textoTituloFormularioAgregar = "Agregar Conf.";
$abm->textoTituloFormularioEdicion = "Editar Conf.";

$abm->campos = array(
		array("campo" => "id_loc_remitente_encomienda", 
					"tipo" => "dbCombo", 
					"titulo" => "Localidad Remitente",
					"sqlQuery" => "SELECT l.codigo, CONCAT(localidad, '    ( CP:', codigo_postal,' - ', provincia,' )') AS localidad FROM provincias p inner join  localidades l on (p.codigo=l.id_provincia) order by l.localidad", 
					"campoValor" => "codigo", 
					"campoTexto" => "localidad",                                        
					
					"requerido" => false,
					"hint" => ""
					), 
		array("campo" => "id_loc_destinatario_encomienda", 
					"tipo" => "dbCombo", 
					"titulo" => "Localidad Destinatario",
					"sqlQuery" => "SELECT l.codigo, CONCAT(localidad, '    ( CP:', codigo_postal,' - ', provincia,' )') AS localidad FROM provincias p inner join  localidades l on (p.codigo=l.id_provincia) order by l.localidad", 
					"campoValor" => "l.codigo", 
					"campoTexto" => "localidad",                     
					"maxLen" => 30,
					"noListar" => true,
					"requerido" => false,
					"hint" => ""
					),
		array("campo" => "fecha_modificacion", 
					"tipo" => "fecha",                                         
					"titulo" => "Fecha Mod.",
					"hint" => ""
					),
		array("campo" => "id_provincia_origen_encomienda", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT codigo, provincia FROM provincias ORDER BY provincia ASC", 
					"campoValor" => "codigo", 
					"campoTexto" => "provincia", 
					"titulo" => "Provincia Origen",                    
					"maxLen" => 100,
					"requerido" => true
					),
    		array("campo" => "id_sucursal", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT codigo, nombre FROM sucursales WHERE activa =  'S'", 
					"campoValor" => "codigo", 
					"campoTexto" => "nombre", 
					"titulo" => "Sucursal de trabajo",                                    
					"centrarColumna" => true,
                                        "noListar" => true,
					"hint" => " "
					),

		array("campo" => "id_tipo_usuario", 
					"tipo" => "dbCombo", 
					"titulo" => "Tipo Usuario",                     
					"sqlQuery" => "SELECT codigo, tipo_usuario FROM tipos_de_usuarios ORDER BY tipo_usuario ASC", 
					"campoValor" => "codigo", 
					"campoTexto" => "tipo_usuario",					
                                        "requerido" => true,
                                        "hint" => "Designe un color representativo para la sucursal."
					)
		);

$abm->generarAbm("", "Configuración de Usuarios");
?>
</body>
</html>