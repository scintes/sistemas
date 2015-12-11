<?php
include_once("seguridad_abm.php");
include_once("../conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ABM Usuarios</title>
	
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

//conexión a la bd
//$db = new class_db("localhost", "root", "123", "prueba");

$db = new class_db(HOST, USUARIO, PASSWORD, BASE);
$db->mostrarErrores = FALSE;
$db->connect();
$abm = new class_abm();
$abm->tabla = "usuarios";
$abm->registros_por_pagina = 20;
$abm->campoId = "id";
$abm->textoTituloFormularioAgregar = "Agregar usuario";
$abm->textoTituloFormularioEdicion = "Editar usuario";
$abm->adicionalesInsert = ", fechaAlta=NOW()";

$abm->campos = array(
		array("campo" => "usuario", 
					"tipo" => "texto", 
					"titulo" => "Usuario", 
					"maxLen" => 30,
					"customPrintListado" => "<a href='javascript:alert(\"Ejemplo customPrintListado. id={id}\")' title='Ver usuario'>%s</a>",
					"requerido" => true,
					"hint" => "El usuario no debe existir"
					), 
		array("campo" => "pass", 
					"tipo" => "pass", 
					"titulo" => "Contraseña", 
					"maxLen" => 30,
					"noListar" => true,
					"requerido" => true,
                                      //  "customFuncionValor" => "md5($valo)",
					"hint" => "Elija una contraseña segura, deberá estar compuesta por letras mayuscula, minusculas y ademas numeros"
					),
		array("campo" => "activo", 
					"tipo" => "bit", 
					"titulo" => "Activo", 
					"datos" => array("1"=>"SI", "0"=>"NO"),
					"valorPredefinido" => "1",
					"centrarColumna" => true,
					"hint" => "Indica si el usuario estará activo"
					),
		array("campo" => "nivel", 
					"tipo" => "combo", 
					"titulo" => "Nivel", 
					"datos" => array("ADMIN"=>"Administrador", "USUARIO"=>"Usuario", "CHOFER"=>"Chofer"),
					"valorPredefinido" => "USUARIO"
					),
		array("campo" => "fechaAlta", 
					"tipo" => "fecha", 
					"titulo" => "Fecha alta", 
					"noNuevo" => true
					),
		array("campo" => "email", 
					"tipo" => "texto", 
					"titulo" => "Email", 
					"maxLen" => 100,
					"requerido" => false
					),
		array("campo" => "nombre", 
					"tipo" => "texto", 
					"titulo" => "Nombre", 
					"maxLen" => 50,
                                        "requerido" => true
					),
		array("campo" => "apellido", 
					"tipo" => "texto", 
					"titulo" => "Apellido", 
					"maxLen" => 50,
                                        "requerido" => true
					),
		array("campo" => "comentarios", 
					"tipo" => "textarea", 
					"titulo" => "Comentarios", 
					"noListar" => true,
					"hint" => "Ingrese cualquier comentario que desee pero no se abuse porque este es un ejemplo de hint largo."
					),
		array("campo" => "ultimoLogin", 
					"tipo" => "texto", 
					"titulo" => "Ultimo login",
					"noEditar" => true, 
					"noListar" => true,
					"noNuevo" => true
					),
                    array("campo" => "id_tipo_usuario", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT tu.codigo, tu.tipo_usuario FROM tipos_de_usuarios AS tu ORDER BY tu.tipo_usuario ASC", 
					"campoValor" => "codigo", 
					"campoTexto" => "tipo_usuario", 
					"titulo" => "Tipo de Usuario",
					"incluirOpcionVacia" => false,
					"noListar" => true,
					"requerido" => true,
                                        "hint" => "Seleccione el tipo de usuario que sera el usuario"
					),
                    array("campo" => "id_configuracion_usuario", 
					"tipo" => "dbCombo", 
					"sqlQuery" => "SELECT c.codigo  FROM conf_usuario AS c order by c.codigo", 
					"campoValor" => "codigo", 
					"campoTexto" => "codigo", 
					"titulo" => "Configuración",
					"incluirOpcionVacia" => false,
					"noListar" => true,
					"requerido" => false,
                                        "hint" => "Seleccione la configuracion por defecto a utilizar para el usuario"
					)    

		);

$abm->generarAbm("", "Administrar clientes");
?>
</body>
</html>