<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ABM Vinculación de comisones y descuentos</title>
	
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

$db = new class_db(HOST, USUARIO, PASSWORD, BASE);
$db->mostrarErrores = true;
$db->connect();
$abm = new class_abm();
$abm->tabla = "rel_usuario_comision_descuento";
$abm->registros_por_pagina = CANT_REG_PAGINA;
$abm->campoId = "";
$abm->textoTituloFormularioAgregar = "Agregar registro";
$abm->textoTituloFormularioEdicion = "Editar registro";
$abm->adicionalesInsert = "fecha_modificacion=NOW()";




$abm->campos = array(
                array("campo" => "id_tipo_usuario", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT tu.codigo, tu.tipo_usuario FROM tipos_de_usuarios AS tu  WHERE tu.activo =  'S' ORDER BY tu.tipo_usuario ASC", 
					"campoValor" => "codigo", 
					"campoTexto" => "tipo_usuario", 
					"titulo" => "Tipo de usuario",
					"incluirOpcionVacia" => true,                    
					"noListar" => false,
					"requerido" => true,
                                        "hint" => "Seleccione el tipo de usuario para realizar la vinculación"
					),
					
                array("campo" => "id_comisiones_y_descuentos", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT cd.codigo, CONCAT(  COALESCE(cd.nombre,' '),' -  (', COALESCE(cd.tipo,' '),' : ', COALESCE(cd.porcentaje,' '),')') AS comison_descuento FROM comisiones_y_descuentos AS cd order by cd.nombre", 
					"campoValor" => "codigo", 
					"campoTexto" => "comison_descuento", 
					"titulo" => "Comisión/Descuento",
					"incluirOpcionVacia" => TRUE,
					"noListar" => FALSE,
					"requerido" => TRUE,
                                        "hint" => "Seleccione el tipo Comisión/Descuento para realizar la vinculación"
					),
					
                array("campo" => "id_tipo_encomienda", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT te.codigo, te.tipo_encomienda  AS tipo_encomienda FROM tipos_de_encomiendas AS te order by te.tipo_encomienda", 
					"campoValor" => "codigo", 
					"campoTexto" => "tipo_encomienda", 
					"titulo" => "Comisión/Descuento",
					"incluirOpcionVacia" => TRUE,
					"noListar" => FALSE,
					"requerido" => TRUE,
                                        "hint" => "Seleccione el tipo Comisión/Descuento para realizar la vinculación"
					)		                
		);

/*
$sql2 = "SELECT
cd.tipo,
tu.tipo_usuario,
te.tipo_encomienda,
rucd.fecha_modificacion
FROM
rel_usuario_comision_descuento AS rucd
Inner Join tipos_de_usuarios AS tu ON rucd.Id_tipo_usuario = tu.codigo
Inner Join comisiones_y_descuentos AS cd ON rucd.id_comisiones_y_descuentos = cd.codigo
Inner Join tipos_de_encomiendas AS te ON rucd.id_tipo_encomienda = te.codigo
";
*/
$abm->generarAbm("", "Administrar Comisiones y descuentos");
?>
</body>
</html>