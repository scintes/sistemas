<?php
include_once("seguridad.php");
set_file("buscar_clientes", "buscar_clientes.html");
//----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
set_var("v_nro_guia", ""); // nro de guia
/*
 * Realiza la busqueda de clientes para encomienda
 */
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

$db = conectar_al_servidor();
$sql = "SELECT cl.codigo, cl.nombre, cl.direccion, cl.id_localidad, cl.tel, cl.razon_social,
               cl.direccion_razon_social, cl.id_localidad_razon_social, cl.cel, cl.email, 
               cl.tel_razon_social, cl.fax_razon_social, cl.email_razon_social 
        from clientes cl";
$w =   "WHERE cl.nombre LIKE  'nombre'";

$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    // cargamos lo obtenido en las variables
    $id_loc_remit_desde = $res->fields[1];
    $id_loc_dest_desde  = $res->fields[2];
    $id_prov_orig       = $res->fields[3];
}

set_file("buscar_clientes", "buscar_clientes.html");
//----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
set_var("v_nro_guia", ""); // nro de guia     
?>
