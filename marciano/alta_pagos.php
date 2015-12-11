<?php
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");
set_file("altapago", "alta_pagos.html");

$id = $_REQUEST["id"];// id del cliente
$nc = $_REQUEST["nombre"]; // nombre del cliente

set_var("v_total_pago", 0.00);
set_var("v_color_cabezera_tabla", COLOR_ENCOMIENDAS_CABEZERA_TABLA);
set_var("v_id_cliente",$id);
set_var("v_nombre_cliente", $nc);


$db = conectar_al_servidor();

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de bancos
//----------------------------------------------------------------------------------------------------
$q = "select codigo, banco from bancos where activo='S' order by banco";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db->ErrorMsg(); //die();
} else {
    $combobox_bancos = "<option value=0>Seleccione uno...</option>";
    while (!$res->EOF) {
        $combobox_bancos = $combobox_bancos . "<option value=".$res->fields[0] . ">" . $res->fields[1]."</option>";
        $res->MoveNext();
    }
}
set_var("v_comboBox_banco", $combobox_bancos);


desconectar($db);
pparse("altapago");
?>
