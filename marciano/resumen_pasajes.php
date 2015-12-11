<?php
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");
include_once("propia.php");


$id_suc = $_SESSION['id_sucursal'];	
$id_t_u = $_SESSION['id_tipo_de_usuario'];


set_file("resumenpasajes", "resumen_pasajes.html");
    set_var("v_sucursal", $_SESSION['sucursal']); 
    set_var("v_vendedor",$_SESSION['usuario']  );
    
    set_var("v_fecha_desde", dar_fecha());
    set_var("v_fecha_hasta", dar_fecha());

//------------------------------------------------------------------------------    
// determinamos quien es el usuario en turno para saber que mostramos y que no.    
switch ($id_t_u) {
    case 1: // administrador de casa central                 
        set_var("v_completar_sucursal",'<td><select id="e_listado_sucursales" >{v_listado_sucursal}</select></td>'); 
        set_var("v_completar_usuario",'<td><select id="e_listado_usuarios" >{v_listado_usuarios}</select></td>');                                                 
        set_var("v_completar_boton_devolver",'<div style="float: left;font-size: 11;margin: 2;width: 50px;border: 1px solid #cccccc;background-color: #ffffff;"><input type="image" align="center" src="./imagenes/destilde.png" border="0" name="i_desprocesar" id="i_desprocesar"><br>Despro.</div>');
        break;
    case 2: // choferes
        set_var("v_completar_sucursal",' '); 
        set_var("v_completar_usuario",' ');                                                 
        set_var("v_completar_boton_devolver",' ');        
        break;
    case 3: // usuarios
        set_var("v_completar_sucursal",' '); 
        set_var("v_completar_usuario",' ');                                                 
        set_var("v_completar_boton_devolver",' ');       
        break;
    case 4: // administrador de sucursales
        set_var("v_completar_sucursal",'<td><select id="e_listado_sucursales" >{v_listado_sucursal}</select></td>'); 
        set_var("v_completar_usuario",'<td><select id="e_listado_usuarios" >{v_listado_usuarios}</select></td>');                                                 
        set_var("v_completar_boton_devolver",'<div style="float: left;font-size: 11;margin: 2;width: 50px;border: 1px solid #cccccc;background-color: #ffffff;"><input type="image" align="center" src="./imagenes/destilde.png" border="0" name="i_desprocesar" id="i_desprocesar"><br>Despro.</div>');
        break;
    case 5: // administrador de sistema
        set_var("v_completar_sucursal",'<td><select id="e_listado_sucursales" >{v_listado_sucursal}</select></td>'); 
        set_var("v_completar_usuario",'<td><select id="e_listado_usuarios" >{v_listado_usuarios}</select></td>');                                                 
        set_var("v_completar_boton_devolver",'<div style="float: left;font-size: 11;margin: 2;width: 50px;border: 1px solid #cccccc;background-color: #ffffff;"><input type="image" align="center" src="./imagenes/destilde.png" border="0" name="i_desprocesar" id="i_desprocesar"><br>Despro.</div>');
        break;
    
}
//------------------------------------------------------------------------------    
    
    
$db = conectar_al_servidor();

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de LOCALIDAD REMITENTE y DESTINATARIO
//----------------------------------------------------------------------------------------------------
$q = "select l.codigo, l.localidad  from localidades l order by  l.localidad ";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    $combobox_loc_remitente = $combobox_loc_remitente . "<option value='-1' selected=true>Seleccione una...</option>";
    $combobox_loc_destinatario   = $combobox_loc_destinatario   . "<option value='-1' selected=true>Seleccione una...</option>";

    while (!$res->EOF) {
       // remitente -------------------------------------------------------------- 
        $combobox_loc_remitente = $combobox_loc_remitente . "<option value=" .
                    $res->fields[0] . ">" . $res->fields[1] . "</option>";
        // destinatario -----------------------------------------------------------
        $combobox_loc_destinatario = $combobox_loc_destinatario . "<option value=" .
                    $res->fields[0] . ">" . $res->fields[1] . "</option>";
        $res->MoveNext();
    }
}
set_var("v_listado_localidad_origen", $combobox_loc_remitente);
set_var("v_listado_localidad_destino",$combobox_loc_destinatario);

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de TIPO DE PASAJES
//----------------------------------------------------------------------------------------------------
$q = "SELECT codigo, tipo_pasaje, campo_interno FROM tipo_pasajes WHERE activo='S' ORDER BY tipo_pasaje";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    $combobox_tipo_pasaje = "<option value='-1'>Seleccione uno...</option>";
    while (!$res->EOF) {
         $combobox_tipo_pasaje =  $combobox_tipo_pasaje . "<option value=".$res->fields[0].">".$res->fields[1]."</option>";
         $res->MoveNext();
    }    
}
set_var("v_listado_tipo_pasaje",  $combobox_tipo_pasaje);

// si es cualquier administrador o informatico
if (in_array($id_t_u, [1, 4, 5])){

    //----------------------------------------------------------------------------------------------------
    //----------------------------------------------------------------------------------------------------
    // Cargamos el comboBOX de sucursales
    //----------------------------------------------------------------------------------------------------
    $q = "SELECT codigo, Nombre FROM sucursales WHERE activa='S' ORDER BY nombre";
    $res = ejecutar_sql($db, $q);
    if (!$res) {
        echo $db->ErrorMsg(); //die();
    } else {
        $combobox_sucursal = "<option value='-1'>Seleccione una...</option>";
        while (!$res->EOF) {
             $combobox_sucursal =  $combobox_sucursal . "<option value=".$res->fields[0].">".$res->fields[1]."</option>";
             $res->MoveNext();
        }    
    }
    set_var("v_listado_sucursal",  $combobox_sucursal);

    //----------------------------------------------------------------------------------------------------
    //----------------------------------------------------------------------------------------------------
    // Cargamos el comboBOX de usuarios
    //----------------------------------------------------------------------------------------------------
    $q = "SELECT id, usuario, concat(nombre,' ',apellido) as nombre FROM usuarios WHERE activo='1' ORDER BY usuario";
    $res = ejecutar_sql($db, $q);
    if (!$res) {
        echo $db->ErrorMsg(); //die();
    } else {
        $combobox_usuarios = "<option value='-1'>Seleccione una...</option>";
        while (!$res->EOF) {
             $combobox_usuarios =  $combobox_usuarios . "<option value=".$res->fields[0].">".$res->fields[1].' - '.$res->fields[2]."</option>";
             $res->MoveNext();
        }    
    }
    set_var("v_listado_usuarios",  $combobox_usuarios);
}

set_var("v_color_cabezera_tabla",    COLOR_ENCOMIENDAS_CABEZERA_TABLA);
set_var("v_color_cabezera_columna",  COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);
set_var("v_color_origen",COLOR_FONDO_CARGA_DATOS_PASAJE_ORIGEN);
set_var("v_color_destino",COLOR_FONDO_CARGA_DATOS_PASAJE_DESTINO); 
set_var("v_color_foco_grilla",COLOR_PASAJES_FOCO); 

set_var("v_total_listado", "0.00");
set_var("v_total_pago_listado", "0.00");
set_var("v_cant_total_pasajes", "0");
set_var("v_cant_total_pagos", "0");
set_var("v_total_comision_listado", "0.00");

parse ('resumenpasajes');
pparse('resumenpasajes');

desconectar($db);

include_once("pie_paginas.php");

?>