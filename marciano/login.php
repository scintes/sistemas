<?php
include_once("conexion.php");
include_once("template.php");
//include_once("mensajes.php");

set_file("login","login.html");
    set_var('v_usuario',      '');
    set_var('v_contrasenia',  '');
    set_var('v_razon_social', RAZON_SOCIAL);        

$var1= $_POST['usuario'];
$var2= $_POST['contrasenia'];
$var3= $_POST['ok'];

if ((($var1!='')or($var2!=''))and($var3=='Aceptar')){
	
	$db = conectar_al_servidor();
	
	$usuario = "select u.id, u.nombre, u.usuario, u.id_configuracion_usuario, cu.id_loc_destinatario_encomienda, 
                           cu.id_loc_remitente_encomienda, cu.id_provincia_origen_encomienda, s.nombre as sucursal, 
                           cu.id_sucursal, tu.imagen, tu.codigo as id_tipo_de_usuario
                    from usuarios u 
                        inner join conf_usuario cu on (u.id_configuracion_usuario=cu.codigo) 
                              and u.usuario='".$var1."'AND u.pass='".md5($var2)."'
                        inner join tipos_de_usuarios tu on (tu.codigo=u.id_tipo_usuario)
                        inner join sucursales s on (cu.id_sucursal=s.codigo)";

	$us = ejecutar_sql($db, $usuario);    
	$cant = $us->RecordCount();
        desconectar($db);
	if($cant==0){
                $mensaje_error_usuario_incorrecto = "Usuario Incorrecto" ; 
	}else{
                $mensaje_error_usuario_incorrecto = " ";  
                //session_name(‘0a9s88732’);
                session_name("loginUsuario"); 
		session_start();
                
                // inicio la sesión 
                $_SESSION["autentificado"]= "SI"; 
                //defino la sesión que demuestra que el usuario está autorizado 
                $_SESSION["ultimoAcceso"]= date("Y-n-j H:i:s"); 
                //defino la fecha y hora de inicio de sesión en formato aaaa-mm-dd hh:mm:ss 
                
		$arr = $us->fetchRow();
                
		$_SESSION['sucursal']        = $arr['sucursal'];
		$_SESSION['id_sucursal']     = $arr['id_sucursal'];
		$_SESSION['usuario']         = $_REQUEST['usuario'];
                $_SESSION['imagen']          = $arr['imagen'];                
                $_SESSION['id_usuario']      = $arr['id'];
		$_SESSION['id_conf_usuario'] = $arr['id_configuracion_usuario']; // pasa el identificador de configuracion de arranque para el usuario
		$_SESSION['id_personal']           = $arr['id'];
                $_SESSION['id_loc_orig_encom']     = $arr['id_loc_remitente_encomienda'];
                $_SESSION['id_loc_dest_encom']     = $arr['id_loc_destinatario_encomienda'];                
                $_SESSION['id_tipo_de_usuario']       = $arr['id_tipo_de_usuario'];    
                
                $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['sKey'] = uniqid(mt_rand(), true);
                $_SESSION['ipaddress'] = $_SERVER["REMOTE_ADDR"]; //ExtractUserIpAddress();
                $_SESSION['lastActivity'] = $_SERVER['REQUEST_TIME'];
                
                
               
                
		// lo redirijo al menu
		header("Location:principal.php");
                
		die();
	} // fin del if cant = 0
        
}
set_var('v_mensaje',$mensaje_error_usuario_incorrecto ); 

set_file("pie_de_pagina", "pie_paginas.html");
    
    set_var('v_titulo_proyecto',            SIS_PROYECTO);
    set_var("v_logo_proyecto",              SIS_LOGO); // nro de guia
    set_var("v_acerca_de",                  SIS_ACERCAR_DE); // nro de guia
    set_var("v_usuario",                    '');
    set_var('v_sucursal',                   '');    
    set_var('v_titulo_proyecto',            SIS_PROYECTO);
    set_var('v_sis_version',                SIS_VERSION);
    set_var('v_fecha_actualizacion_sistema',SIS_FECHA_MODI_SISTEMA);
    set_var('v_icono_sistema',              SIS_ICON_PROYECTO);
    set_var("v_sis_pagina",                 SIS_PAGINA);
    set_var('v_mostrar_botonera',           'none');
   
pparse('login');
pparse("pie_de_pagina");
die();
?>
