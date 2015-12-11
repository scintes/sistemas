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
	<title>ABM Cheques</title>	
	<!-- Estilos -->
	<link href="../css/sitio.css" rel="stylesheet" type="text/css">
	<link href="../css/abm.css" rel="stylesheet" type="text/css">
	<!-- MooTools -->
	<script type="text/javascript" src="../js/mootools-1.2.3-core.js"></script>
	<script type="text/javascript" src="../js/mootools-1.2.3.1-more.js"></script>	
        
	<!--FormCheck -->
	<script type="text/javascript" src="../js/formcheck/lang/es.js"></script>
	<script type="text/javascript" src="../js/formcheck/formcheck.js"></script>
	<link rel="stylesheet" href="../js/formcheck/theme/classic/formcheck.css" type="text/css" media="screen"/> 
	<!--Datepicker-->
	<link rel="stylesheet" href="../js/datepicker/datepicker_vista/datepicker_vista.css" type="text/css" media="screen"/>
	<script type="text/javascript" src="../js/datepicker/datepicker.js"></script>
</head>

<body>
    
<?php

$db = new class_db(HOST, USUARIO, PASSWORD, BASE);
$db->mostrarErrores = FALSE;
$db->connect();
$abm = new class_abm();
$abm->tabla = "Cheques";
$abm->registros_por_pagina = 15;
$abm->campoId = "codigo";
$abm->textoTituloFormularioAgregar = "Agregar cheque";
$abm->textoTituloFormularioEdicion = "Editar cheque";

//$abm->adicionalesInsert = ", fechaAlta=NOW()";

//----- inicio de configuracion de busqueda ------------------------------------
$abm->logo = "../imagenes/cheques.jpg";
$abm->mostrarBuscador = True;
$abm->mostrarPaginado = 3; // muestra arriba y abajo
$abm->textoBusqueda   = 'Buscar por Nro Cheque, propietario, operacion de salida:';
$abm->adicionales_buscar_Select = ' and((nro_cheque like "%@1%")or(propietario like "%@1%")or(operacion_salida like "%@1%"))';
// --- fin de configuracion de busqueda ----------------------------------------



$abm->campos = array(
		array("campo" => "nro_cheque", 
					"tipo" => "num_ent", 
					"titulo" => "Nro. De Cheque", 
					"maxLen" => 11,
					"requerido" => true					
					), 
   
		array("campo" => "id_banco", 
					"tipo" => "dbCombo", 
					"titulo" => "Banco", 
					"sqlQuery" => "SELECT codigo, banco FROM bancos WHERE activo='S'", 
					"campoValor" => "codigo", 
					"campoTexto" => "banco", 
					"maxLen" => 100,
					"noListar" => false,
					"requerido" => true,
					"hint" => "Seleccione el banco que pertenece el cheque"
					),
    		array("campo" => "importe", 
					"tipo" => "num_real", 
					"titulo" => "Importe",
					"noListar" => false,
					"requerido" => true,
					"hint" => "Ingrese el importe del cheque"
					),
                array("campo" => "fecha_emision", 
					"tipo" => "fecha", 
					"titulo" => "Fecha emisión",
					"incluirOpcionVacia" => true,
					"noListar" => true,
					"requerido" => true,
                                        "hint" => "indique la fecha en que fue emitido el cheque."
					),

               array("campo" => "fecha_cobro", 
					"tipo" => "fecha", 
					"titulo" => "Fecha Cobro", 
					"noListar" => false,
					"requerido" => false,
					"hint" => "Ingrese el teléfono fijo del cliente"
					),
               array("campo" => "operacion_ingreso", 
					"tipo" => "texto", 
					"titulo" => "Operación Ingreso", 
					"maxLen" => 100,
					"noListar" => false,
					"requerido" => false
					),
    		array("campo" => "propio", 
					"tipo" => "bit", 
					"titulo" => "Propio", 
					"datos" => array("S"=>"SI", "N"=>"NO"),
					"valorPredefinido" => "S",
					"centrarColumna" => true,
					"hint" => "Indica si el cheque es emitido de la chequera propia."
					),
    		array("campo" => "propietario", 
					"tipo" => "texto", 
					"titulo" => "Propietario", 
					"maxLen" => 100,
					"noListar" => true,
					"requerido" => false,
					"hint" => "Ingrese el nombre del comercio si posee el cliente."
					),
    		array("campo" => "entregado_por", 
					"tipo" => "texto", 
					"titulo" => "Entregado por", 
					"maxLen" => 100,
					"noListar" => true,
					"requerido" => false,
					"hint" => "Ingrese la direcci�n del cliente"
					),
    
                array("campo" => "fecha_entrega", 
					"tipo" => "fecha", 
					"titulo" => "Fecha entrega",
					"incluirOpcionVacia" => true,
					"noListar" => true,
					"requerido" => true
					),
                   array("campo" => "operacion_salida", 
					"tipo" => "texto", 
					"titulo" => "Operación de salida", 
					"maxLen" => 100,
					"noListar" => true,
					"requerido" => false
					),
              array("campo" => "entregado_a", 
					"tipo" => "texto", 
					"titulo" => "Entregado a la persona", 
					"maxLen" => 100,
					"noListar" => true,
					"requerido" => false
					),

               array("campo" => "entregado", 
					"tipo" => "bit", 
					"titulo" => "Entregado", 
                                        "datos" => array("S"=>"SI", "N"=>"NO"),
					"valorPredefinido" => "N",
					"maxLen" => 100,
					"noListar" => true,
					"requerido" => false,
					"hint" => "Ingrese el mail de la razopn social del cliente"
					)
    
		);

$sql = "SELECT
	ch.nro_cheque,
        ch.id_banco,
	b.banco,
	ch.importe,
	ch.fecha_emision,
	ch.fecha_cobro,
	ch.fecha_entrega,
	ch.operacion_ingreso,
	ch.operacion_salida
        FROM cheques AS ch
        INNER JOIN bancos AS b ON ch.id_banco = b.codigo";
$abm->generarAbm("", "Administrador de cheques");
?>
</body>
</html>