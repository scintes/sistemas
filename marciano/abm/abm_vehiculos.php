<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ABM Vehiculos</title>
	
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
$db->mostrarErrores = TRUE;
$db->connect();

$abm = new class_abm(); 
$abm->tabla = 'vehiculos'; 
$abm->campoId = 'PATENTE'; 
$abm->orderByPorDefecto = 'PATENTE DESC'; 
$abm->registros_por_pagina = CANT_REG_PAGINA; 
$abm->textoTituloFormularioAgregar = "Agregar"; 
$abm->textoTituloFormularioEdicion = "Editar"; 
$abm->campos = array( 
	array('campo' => 'PATENTE', 
		'tipo' => 'texto',
		'hint' => "Nro de dominio del vehículo.",
		'tituloListado' => 'Patente',
		'requerido' => true,
		'maxLen' => 7,
		'titulo' => 'Patente'
	),     
        array('campo' => 'NOMBRE', 
		'tipo' => 'texto',
		'hint' => "Identificacion por nombre de la unidad.",
		'tituloListado' => 'Denominación',
		'requerido' => true,
		'maxLen'    => 50,		
		'titulo'    => 'Nombre'
	),     
	array('campo' => 'INTERNO', 
		'tipo' => 'num_ent',
		'tituloListado' => 'Nro Interno',
		'maxLen' => 3,
		'titulo' => 'Nro. Interno'
	), 
	array('campo' => 'MODELO', 
		'tipo' => 'texto',
		'hint' => "Año de inscripción de la unidad.",
		'tituloListado' => 'Modelo',
		'maxLen' => 4,
		'titulo' => 'Modelo'
	),
	array('campo' => 'NRO_ASIENTOS', 
		'tipo' => 'num_ent',
		'hint' => "Cantidad de asientos de pasajeros",
		'tituloListado' => 'Nro Asientos P.B.',
		'requerido' => true,
		'maxLen' => 3,
		'centrarColumna' => true,
		'titulo' => 'Nro. Asientos'
	), 
    
	array('campo' => 'CANTIDAD_CHOFERES', 
		'tipo' => 'num_ent',
		'hint' => "Cantidad de choferes que debe cargar la unidad.",
		'tituloListado' => 'Cant. Chofer',
		'maxLen' => 2,
		'titulo' => 'Cant. Choferes'
	), 
	array('campo' => 'ACTIVO', 
		'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'valorPredefinido' => "S",
		'tituloListado' => 'Esta Activo',
		'maxLen' => 1,
		'centrarColumna' => true,
		'titulo' => 'Activo'
	), 
    
    	array('campo' => 'DOBLE_PISO', 
		'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'tituloListado' => 'Es doble piso',
		'maxLen' => 2,
		'centrarColumna' => true,
		'noOrdenar' => true,
		'titulo' => 'Doble Piso',
                'nolistar' => true
	), 
    
	array('campo' => 'CANTIDAD_ASIENTOS_PISO_2', 
		'tipo' => 'texto',
		'tituloListado' => 'Nro Asientos P.A.',
		'maxLen' => 3,
		'centrarColumna' => true,
		'noOrdenar' => true,
		'titulo' => 'Cant. Asiento 2do piso'
	), 
    
	array('campo' => 'FECHA_VENCIMIENTO_TECNICA', 
		'tipo' => 'fecha',
		'tituloListado' => 'Vencimiento de Técnica',
		'titulo' => 'Fecha Vto. Técnica'
	),

	array('campo' => 'NRO_RUEDAS', 
		'tipo' => 'num_ent',
		'hint' => "cantidad de rueda de la unidad.",
		'tituloListado' => 'Cant. Ruedas',
		'maxLen' => 2,
		'titulo' => 'Nro. Ruedas',
                'centrarColumna' => true,
                'nolistar' => false
            ), 
    
	array('campo' => 'VIDEOS', 
		'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'valorPredefinido' => "S",
		'hint' => "Indique si la unidad posee video abordo.",
		'tituloListado' => 'Posee Video',
		'maxLen' => 1,
		'centrarColumna'=>true,
                'nolistar'=>false,
		'titulo' => 'Videos'
	), 
    	array('campo' => 'BANIO', 
                'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'valorPredefinido' => "S",
		'tituloListado' => 'Baño',
		'maxLen' => 2,
		'centrarColumna' => true,
                'nolistar' => false,
		'titulo' => 'Posee Baño'
	), 
	
        array('campo' => 'COLUMNA_PB_11', 
		'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'hint' => "Fila de asientos de la planta baja del lado ventanilla.",
		'tituloListado' => 'Dist. Asientos P.B. Vent Izq',
		'maxLen' => 2,
		'centrarColumna' => true,
		'noOrdenar' => true,
		'nolistar' => true,
		'titulo' => 'Fila de Asientos PB [0-] [--]'
	), 
	array('campo' => 'COLUMNA_PB_12', 
		'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'hint' => "Fila de asientos de la planta baja del lado pasillo. ",
		'tituloListado' => 'Dist. Asientos P.B. Pasillo Izq',
		'maxLen' => 2,
		'centrarColumna' => true,
		'noOrdenar' => true,
                'nolistar' => true,
		'titulo' => 'Fila de Asientos PB [-0] [--]'
	), 
	array('campo' => 'COLUMNA_PB_21', 
		'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'hint' => "Fila de asientos de la planta baja del lado ventanilla.",
		'tituloListado' => 'Dist. Asientos P.B. Pasillo Der',
		'maxLen' => 2,
		'centrarColumna' => true,
		'noOrdenar' => true,
                'nolistar' => true,
		'titulo' => 'Fila de Asientos PB [--] [0-]'
	), 
	array('campo' => 'COLUMNA_PB_22', 
		'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'tituloListado' => 'Dist. Asientos P.B. Vent Der',
		'maxLen' => 2,
		'centrarColumna' => true,
		'noOrdenar' => true,
                'nolistar' => true,
		'titulo' => 'Fila de Asientos PB [--] [-0]'
	), 
	array('campo' => 'COLUMNA_PA_11', 
		'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'tituloListado' => 'Dist. Asientos P.A. Vent Izq',
		'maxLen' => 2,
		'centrarColumna' => true,
		'noOrdenar' => true,
                'nolistar' => true,
		'titulo' => 'Fila de Asientos PA [0-] [--]'
	), 
	array('campo' => 'COLUMNA_PA_12', 
		'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'tituloListado' => 'Dist. Asientos P.A. Pasillo Izq',
		'maxLen' => 2,
		'centrarColumna' => true,
		'noOrdenar' => true,
                'nolistar' => true,
		'titulo' => 'Fila de Asientos PA [-0] [--]'
	), 
	array('campo' => 'COLUMNA_PA_21', 
		'tipo' => 'combo',
		'datos' => Array('S' => 'SI', 'N' => 'No'),
		'tituloListado' => 'Dist. Asientos P.A. Pasillo Der',
		'maxLen' => 2,
		'centrarColumna' => true,
		'noOrdenar' => true,
                'nolistar' => true,
		'titulo' => 'Fila de Asientos PA [--] [0-]'
	), 
	array('campo' => 'COLUMNA_PA_22', 
		'tipo' => 'combo',
		'datos' => Array('S' => "SI", 'N' => "No"),
		'tituloListado' => 'Dist. Asientos P.A. Vent Der',
		'maxLen' => 2,
		'centrarColumna' => true,
		'noOrdenar' => true,
                'nolistar' => true,
		'titulo' => 'Fila de Asientos PA [--] [-0]'
	)    
); 
$abm->generarAbm('', 'Administrar vehiculos');

?>
</body>
</html>
 