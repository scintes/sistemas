<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>Administrador Viajes</title>
	
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
$db->mostrarErrores = FALSE;
$db->connect();

$abm = new class_abm(); 
$abm->tabla = 'viajes'; 
$abm->campoId = 'codigo'; 
$abm->orderByPorDefecto = 'fecha DESC'; 
$abm->registros_por_pagina = CANT_REG_PAGINA; 
$abm->textoTituloFormularioAgregar = "Agregar Viaje"; 
$abm->textoTituloFormularioEdicion = "Modificar Viaje "; 
$abm->campos = array( 

	array('campo'      => 'id_salida', 
		'tipo'     => 'dbCombo',
		'sqlQuery' => "SELECT s.codigo, CONCAT( CASE s.tipo_salida WHEN 'D' THEN 'Diario' WHEN 'E' THEN 'Especial' END ,' - ',
		                      DATE_FORMAT(s.fecha,'".'%d/%m/%y'."'),' - ', s.hora,'  ', s.destino) AS nombre 
 					   FROM salidas AS s WHERE s.activa='S' ",
		'campoValor' => 'codigo',
		'campoTexto' => 'nombre',
		'incluirOpcionVacia' => false,
		'maxLen' => 11,
		'titulo' => 'Salida'
	), 
	array('campo' => 'id_chofer', 
		'tipo' => 'dbCombo',
		'sqlQuery' => 'SELECT u.id, concat(u.apellido,', ', u.nombre) FROM usuarios AS u
					   where id_tipo_usuario=3',
		'campoValor' => 'id',
		'campoTexto' => 'nombre',
		'incluirOpcionVacia' => false,
		'maxLen' => 6,
		'titulo' => 'Chofer'
	), 
	array('campo' => 'id_guarda', 
		'tipo' => 'dbCombo',
		'sqlQuery' => 'SELECT u.id, u.nombre FROM usuarios AS u
					   where id_tipo_usuario='.ID_CHOFER,
		'campoValor' => 'id',
		'campoTexto' => 'nombre',
		'incluirOpcionVacia' => false,
		'maxLen' => 6,
		'titulo' => 'Guarda'
	), 
	array('campo'      => 'id_vehiculo', 
		'tipo'     => 'dbCombo',
		'sqlQuery' => "select v.patente, CONCAT(v.patente,'  ',v.nombre,'  (',v.NRO_ASIENTOS,')') as nombre
					   from vehiculos v where v.activo='S'",
		'campoValor' => 'patente',
		'campoTexto' => 'nombre',
		'incluirOpcionVacia' => false,
		'maxLen' => 7,
		'titulo' => 'Vehiculo'
	), 
	array('campo' => 'estado', 
		'tipo' => 'bit',
		'datos' => Array('S' => 'SI', 'N' => 'NO'),
		'valorPredefinido' => "S",
		'maxLen' => 1,
		'centrarColumna' => true,
		'titulo' => 'Estado'
	), 
	array('campo' => 'fecha', 
		'tipo' => 'fecha',
		'titulo' => 'Fecha'
	), 
	array('campo' => 'contratante', 
		'tipo' => 'texto',
		'maxLen' => 50,
		'titulo' => 'Contratante'
	), 
	array('campo' => 'dni', 
		'tipo' => 'num_ent',
		'maxLen' => 11,
		'titulo' => 'Dni'
	), 
	array('campo' => 'domicilio', 
		'tipo' => 'texto',
		'maxLen' => 50,
		'titulo' => 'Domicilio'
	), 
	array('campo' => 'id_localidad', 
		'tipo' => 'dbCombo',
		'sqlQuery' => "SELECT l.codigo, CONCAT(localidad, '    ( CP:', codigo_postal,' - ', provincia,' )') AS localidad FROM provincias p inner join  localidades l on (p.codigo=l.id_provincia) order by l.localidad",
		'campoValor' => 'codigo',
		'campoTexto' => 'localidad',
		'incluirOpcionVacia' => false,
		'maxLen' => 11,
		'titulo' => 'Id localidad'
	), 
	array('campo' => 'litros_cargados', 
		'tipo' => 'num_real',
		'maxLen' => 11,
		'titulo' => 'Litros cargados'
	), 
	array('campo' => 'km_origen', 
		'tipo' => 'num_ent',
		'maxLen' => 11,
		'titulo' => 'Km origen'
	), 
	array('campo' => 'km_llegada', 
		'tipo' => 'num_ent',
		'maxLen' => 11,
		'titulo' => 'Km llegada'
	)
); 

$sql = 'SELECT
s.destino,
s.tipo_salida,
ld.localidad,
lo.localidad,
v.fecha,
v.contratante,
v.dni,
v.domicilio,
s.hora,
vh.PATENTE,
vh.NOMBRE,
v.estado,
v.id_localidad,
v.litros_cargados,
v.km_origen,
v.km_llegada,
v.plataforma_salida,
v.id_salida,
v.id_guarda,
v.id_chofer,
v.id_vehiculo,
vh.NRO_ASIENTOS,
g.usuario,
ch.usuario,
lo.localidad,
ldv.localidad
FROM viajes AS v Inner Join salidas AS s ON v.id_salida = s.codigo
		 inner join localidades AS lo ON lo.codigo = s.id_localidad_origen
		inner join localidades AS ld ON ld.codigo = s.id_localidad_destino 
		Inner Join localidades AS ldv ON ldv.codigo = v.id_localidad
		Left Join usuarios AS ch ON ch.id = v.id_chofer
		Left Join usuarios AS g ON g.id = v.id_guarda
		Left Join vehiculos AS vh ON vh.PATENTE =  v.id_vehiculo
';

$abm->generarAbm($sql, 'Administrar viajes'); 
?>
</body>
</html>
 