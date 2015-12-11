<?php
//------------------------------------------------------------------------------
// Pantalla para cargar, mostrar y eliminar los permisos de usuarios para el uso
//  del sistema.
//  autor: Cintes Sergio
//  USA: abm_permisos.html,  --> Muestra la interface en navegadores de pc.
//       ajax_buscar_permisos_de_usuarios.php --> Busca los permisos otorgados al un usuario determinado
//       
//------------------------------------------------------------------------------
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
   
// Cargamos las variables del menu principal
set_file("abm_permisos","abm_permisos.html");
    set_var("v_icono_proyecto", SIS_ICON_PROYECTO);
    set_var("v_titulo_proyecto",SIS_PROYECTO     );
    set_var('v_imagen_fondo',   SIS_IMAGEN_FONDO );
    set_var("v_color", "#AAE0E0");
        
$db = conectar_al_servidor();

// parametros recibidos desde el javascript 
$id_usu               = $_SESSION["id_usuario"]; // Usuario registrados.

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de USUARIOS
//----------------------------------------------------------------------------------------------------
$q = " select u.id, u.nombre, u.usuario  from usuarios u where u.activo='1' order by id ";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg();
} else {
    $combobox_usuarios = "<option value='-1' selected=true>Selecciones un usuario</option>";
    while (!$res->EOF) {
        // destino -------------------------------------------------------------
        $combobox_usuarios = $combobox_usuarios . "<option value=" .
        $res->fields['id'].">" . $res->fields['nombre']." - (".$res->fields['usuario'] . ")</option>";
        $res->MoveNext();
    }
}
set_var("v_combobox_usuarios", $combobox_usuarios);

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de SECCIONES
//----------------------------------------------------------------------------------------------------
$q = " select ps.codigo, ps.nombre  from pantallas_secciones ps where ps.activo='S' order by codigo ";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg();
} else {
    $combobox_secciones = "<option value='-1' selected=true>Todas las secciones</option>";
    $secciones = $res->fields['codigo'];
    while (!$res->EOF) {
        // destino -------------------------------------------------------------
        $combobox_secciones = $combobox_secciones . "<option value=" .
        $res->fields['codigo'].">". $res->fields['nombre'] . "</option>";

        $res->MoveNext();
        $secciones = $secciones .",". $res->fields['codigo'];
    }
}
set_var("v_combobox_secciones", $combobox_secciones);


//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de PANTALLAS
//----------------------------------------------------------------------------------------------------
$q = " SELECT pi.codigo, pi.etiqueta, pi.icono,	pi.hint, pi.id_seccion
       FROM pantallas_items AS pi 
       WHERE pi.activo='S'
       ORDER BY pi.etiqueta ";

$res = ejecutar_sql($db, $q);

if (!$res) {
    echo $db->ErrorMsg();
} else {
    $combobox_pantallas = "<option value='-1' selected=true>Todas las pantalla</option>";
    $pantallas = $res->fields['id_seccion'].'-'.$res->fields['codigo'];
    while (!$res->EOF) {
        // destino -------------------------------------------------------------
        $combobox_pantallas = $combobox_pantallas . "<option value=" .
        $res->fields['codigo'].">" . $res->fields['etiqueta'] . "</option>";

        $res->MoveNext();        
        $pantallas = $pantallas .",".$res->fields['id_seccion'].'-'.$res->fields['codigo'];
    }
}
set_var("v_combobox_pantallas", $combobox_pantallas);
//------------------------------------------------------------------------------

desconectar($db);

//set_var("v_menu_secciones", $secciones    );    
//set_var("v_menu_items",     $item_pantalla);  
//set_var("v_visor_de_accion", "");

set_var("v_item","");
set_var("v_icono","");
set_var("v_Sección","");
set_var("v_Pantalla","");
set_var("v_Detalles","");

set_var("v_listado_secciones",$secciones);
set_var("v_listado_pantallas",$pantallas);


    
parse("abm_permisos");    
pparse("abm_permisos");    
include_once("pie_paginas.php");    


?>
