<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");

require_once "/comun/class_db.php";
require_once "/comun/class_abm.php";
require_once "/comun/class_paginado.php";
require_once "/comun/class_orderby.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ABM Clientes</title>
	
	
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


$db = new class_db(HOST, USUARIO, PASSWORD, BASE);
$db->mostrarErrores = False;
$db->connect();
$abm = new class_abm();
$abm->tabla = "Clientes";
$abm->registros_por_pagina = CANT_REG_PAGINA;
$abm->campoId = "codigo";
//----- inicio de configuracion de busqueda ------------------------------------
$abm->logo = "../imagenes/cliente.jpg";
$abm->mostrarBuscador = True;
$abm->mostrarPaginado = 3; // muestra arriba y abajo
$abm->textoBusqueda   = 'Buscar por DNI y/o Nombre';
$abm->adicionales_buscar_Select = ' and((dni like "%@1%")or(nombre like "%@1%"))';
// --- fin de configuracion de busqueda ----------------------------------------

$abm->textoTituloFormularioAgregar = "Agregar Clientes";
$abm->textoTituloFormularioEdicion = "Editar Clientes";

//$abm->adicionalesInsert = ", fechaAlta=NOW()";

$abm->campos = array(
		array("campo" => "dni", 
					"tipo" => "num_ent", 
					"titulo" => "DNI", 
					"maxLen" => 11,
					//"customPrintListado" => "<a href='javascript:alert(\"Ejemplo customPrintListado. id={id}\")' title='Ver usuario'>%s</a>",
					"requerido" => true,
					"hint" => "El usuario no debe existir"
					), 
		array("campo" => "nombre", 
					"tipo" => "texto", 
					"titulo" => "Nombre", 
					"maxLen" => 100,
					"noListar" => false,
					"requerido" => true,
					"hint" => "Ingrese el nombre y apellido del cliente"
					),
    		array("campo" => "direccion", 
					"tipo" => "texto", 
					"titulo" => "Dirección", 
					"maxLen" => 100,
					"noListar" => false,
					"requerido" => false,
					"hint" => "Ingrese la dirección del cliente"
					),
                array("campo" => "id_localidad", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT l.codigo, CONCAT(localidad, '    ( CP:', codigo_postal,' - ', provincia,' )') AS localidad FROM provincias p inner join  localidades l on (p.codigo=l.id_provincia) order by l.localidad", 
					"campoValor" => "codigo", 
					"campoTexto" => "localidad", 
					"titulo" => "Localidad",
					"incluirOpcionVacia" => true,
					"noListar" => true,
					"requerido" => true,
                                        "hint" => "Seleccione la localidad que pertenece el cliente"
					),

               array("campo" => "tel", 
					"tipo" => "texto", 
					"titulo" => "Teléfono", 
					"maxLen" => 16,
					"noListar" => false,
					"requerido" => false,
					"hint" => "Ingrese el teléfono fijo del cliente"
					),
               array("campo" => "cel", 
					"tipo" => "texto", 
					"titulo" => "Celular", 
					"maxLen" => 16,
					"noListar" => false,
					"requerido" => false,
					"hint" => "Ingrese el telefono celular del cliente"
					),
              array("campo" => "id_tipo_cliente", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT tc.codigo, CONCAT( tc.tipo_cliente, ' -  % ',tc.descuento) AS tipo_cliente FROM tipos_de_clientes AS tc order by tc.tipo_cliente", 
					"campoValor" => "codigo", 
					"campoTexto" => "tipo_cliente", 
					"titulo" => "Tipo de cliente",
					"incluirOpcionVacia" => true,
					"noListar" => false,
					"requerido" => true,
                                        "hint" => "Seleccione la localidad que pertenece el cliente"
					),
    		array("campo" => "razon_social", 
					"tipo" => "texto", 
					"titulo" => "Razón Social", 
					"maxLen" => 100,
					"noListar" => true,
					"requerido" => false,
					"hint" => "Ingrese el nombre del comercio si posee el cliente."
					),
    		array("campo" => "direccion_razon_social", 
					"tipo" => "texto", 
					"titulo" => "Dirección Raz�n Social", 
					"maxLen" => 100,
					"noListar" => true,
					"requerido" => false,
					"hint" => "Ingrese la direcci�n del cliente"
					),
    
                array("campo" => "id_localidad_razon_social", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT l.codigo, CONCAT(localidad, '    ( CP:', codigo_postal,' - ', provincia,' )') AS localidad FROM provincias p inner join  localidades l on (p.codigo=l.id_provincia) order by l.localidad", 
					"campoValor" => "codigo", 
					"campoTexto" => "localidad", 
					"titulo" => "Localidad",
					"incluirOpcionVacia" => true,
					"noListar" => true,
					"requerido" => true,
                                        "hint" => "Seleccione la localidad que pertenece la Razon Social del cliente"
					),
                   array("campo" => "tel_razon_social", 
					"tipo" => "texto", 
					"titulo" => "Telefono Razon Social", 
					"maxLen" => 16,
					"noListar" => true,
					"requerido" => false,
					"hint" => "Ingrese el telefono fijo de la raz�n social del cliente"
					),
              array("campo" => "fax_razon_social", 
					"tipo" => "texto", 
					"titulo" => "fax Razon Social", 
					"maxLen" => 16,
					"noListar" => true,
					"requerido" => false,
					"hint" => "Ingrese el fax de la raz�n social del cliente"
					),

                array("campo" => "email_razon_social", 
					"tipo" => "texto", 
					"titulo" => "Email Razon Social", 
					"maxLen" => 100,
					"noListar" => true,
					"requerido" => false,
					"hint" => "Ingrese el mail de la razopn social del cliente"
					),
                array("campo" => "condicion_iva", 
					"tipo" => "combo",                     
                                        "datos" => unserialize(TIPO_RESPONSABLES), // constante que provine de Conexiones.php 
                                        "valorPredefinido" => "3", // identifica al consumidor final.
					"titulo" => "Responsable",
					"incluirOpcionVacia" => false,
					"noListar" => true,
					"requerido" => true,
                                        "hint" => "Seleccione el tipo de consumidor que pertenece el cliente"
					)
		);

// SELECT cl.dni, cl.nombre, cl.direccion, cl.tel, cl.cel, l.localidad, p.provincia, l.codigo_postal FROM clientes AS cl Inner Join localidades AS l ON (cl.id_localidad = l.codigo) inner join provincias // AS p on (l.id_provincia=p.codigo) order by cl.nombre, cl.direccion, l.localidad, p.provincia, l.codigo_postal
$abm->generarAbm("", "Administrar Clientes");
?>
</body>
</html>