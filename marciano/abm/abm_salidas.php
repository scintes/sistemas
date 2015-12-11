<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>Administrador Salidas</title>
	
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
$abm->tabla = "salidas";
$abm->registros_por_pagina = 10;
$abm->campoId = "codigo";
$abm->textoTituloFormularioAgregar = "Agregar salida";
$abm->textoTituloFormularioEdicion = "Editar salida";
//$abm->adicionalesInsert = ", fechaAlta=NOW()";

$abm->campos = array(
		array("campo" => "fecha", 
					"tipo" => "fecha", 
					"titulo" => "Fecha", 					
					"requerido" => true,
					"hint" => "Fecha en que se crea la salidas."
					),
    		array("campo" => "hora", 
					"tipo" => "hora", 
					"titulo" => "Hora", 					
					"requerido" => true,
					"hint" => "Hora en que se crea la salidas."
					),
    		array("campo" => "tipo_salida", 
					"tipo" => "combo", 
					"titulo" => "Tipo de Salida", 					
					"datos" => array("D"=>"Diaria", "E"=>"Especiales"),
					"valorPredefinido" => "D",                       
					"requerido" => false,
                                        "noListar" => false,
					"hint" => "Fecha en que se crea la salidas."
					),
                array("campo" => "destino", 
					"tipo" => "texto", 
					"titulo" => "Destino", 					
					"requerido" => true,
                                        "maxLen" => 100,
                                        "noListar" => false,
					"hint" => "Hora en que se crea la salidas."
					),
                array("campo" => "id_localidad_origen", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT l.codigo, CONCAT(localidad, '    ( CP:', codigo_postal,' - ', provincia,' )') AS localidad FROM provincias p inner join  localidades l on (p.codigo=l.id_provincia) order by l.localidad", 
					"campoValor" => "codigo", 
					"campoTexto" => "localidad", 
					"titulo" => "Localidad Origen",
					"incluirOpcionVacia" => true,
					"noListar" => true,
					"requerido" => true,
                                        "hint" => "Seleccione la localidad que pertenece el cliente"
					),
                array("campo" => "id_localidad_destino", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT l.codigo, CONCAT(localidad, '    ( CP:', codigo_postal,' - ', provincia,' )') AS localidad FROM provincias p inner join  localidades l on (p.codigo=l.id_provincia) order by l.localidad", 
					"campoValor" => "codigo", 
					"campoTexto" => "localidad", 
					"titulo" => "Localidad destino",
					"incluirOpcionVacia" => true,
					"noListar" => true,
					"requerido" => true,
                                        "hint" => "Seleccione la localidad que pertenece el cliente"
					)
              /*  array("campo" => "plataforma_salida", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT s.codigo, Concat(s.plataforma_salida,'  en  ', s.nombre) as plataforma_de_salidas FROM sucursales AS s WHERE s.activa = 'S' ", 
                                        "campoValor" => "plataforma_de_salidas", 
					"campoTexto" => "plataforma_de_salidas", 		
					"titulo" => "Sale desde:",					
					"noListar" => true,
					"requerido" => false,
                                        "hint" => "Indica el lugar de donde parte el vehiculo."
					)    */
		);

$qq = "SELECT
s.fecha,
s.hora,
s.tipo_salida,
s.destino,
s.estado,
ld.localidad,
lo.localidad
FROM
salidas AS s
Inner Join localidades AS lo ON lo.codigo = s.id_localidad_origen
Inner Join localidades AS ld ON ld.codigo = s.id_localidad_destino
";

$abm->generarAbm("", "Administrar salidas");
?>
</body>
</html>