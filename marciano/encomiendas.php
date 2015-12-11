<?php
include_once("seguridad.php");
include_once("template.php");
include_once("funciones.php");

set_file("menu","menu_principal.html");
	set_var("fecha",dar_fecha());
	set_var("visor",'...Administrador de Encomiendas       '.$_SESSION['sucursal'].'...');
	set_var('logo_proyecto','./imagenes/logo.jpg');
	set_var('nombre_proyecto', 'Marciano Tourd SRL');
	
set_file("encomiendas","encomiendas.html");
	set_var("v_b_fecha_desde", dar_fecha()); // fecha_desde date("d/m/Y")
	set_var("v_b_fecha_hasta", dar_fecha()); // fecha_hasta
	set_var("v_b_nro_orden"," ");
	set_var("v_b_direcciones"," ");
	set_var("v_b_nombres"," ");
	set_var("v_b_dni_remitente"," ");
	set_var("v_b_dni_destinatario"," ");

	set_var("v_total_ctacte", 0.00); // sumatoria de cta cte
	set_var("v_sucursal",' '.$_SESSION['sucursal'] ); // sumatoria de cta cte
	set_var("v_usuario",' '.$_SESSION['usuario']  );
	
	set_var("v_cant_reg",0); // Indica la cantidad de registros encontrados.
        
//set_file("pie","pie_pagina.html");
	set_var("v_usuario",$_SESSION['usuario']);
	set_var('logo_proyecto','./imagenes/logo.jpg');

//pparse("menu");
pparse("encomiendas");
//pparse("pie");	
?>

