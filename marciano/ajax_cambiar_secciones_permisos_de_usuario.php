<?php
//------------------------------------------------------------------------------
// Ejecutamos la consulta de solo pantallas segun la seccion recibidas.
//          1) PANTALLA_PERMISOS
//------------------------------------------------------------------------------
include_once("seguridad.php");
include_once("template.php" );
include_once("conexion.php" );
include_once("funciones.php");

$id_secc = $_POST["secciones"]; // valor seleccionado por el usuario en el comobox de secciones
  
$db = conectar_al_servidor();
$combobox_pantallas = '';
if($db){
    //----------------------------------------------------------------------------------------------------
    //----------------------------------------------------------------------------------------------------
    // Cargamos el comboBOX de PANTALLAS
    //----------------------------------------------------------------------------------------------------
    $sql = " SELECT pi.codigo, pi.etiqueta, pi.icono, pi.hint, pi.id_seccion
             FROM pantallas_items AS pi 
             WHERE pi.activo='S' and pi.id_seccion=".$id_secc."
             ORDER BY pi.etiqueta ";

    $res = ejecutar_sql($db, $sql);

    if (!$res) {
        echo $db->ErrorMsg();
    } else {
        $combobox_pantallas = "<option value='-1' selected=true>Todas las pantalla</option>";
        //$pantallas = $res->fields['id_seccion'].'-'.$res->fields['codigo'];
        while (!$res->EOF) {
            // destino -------------------------------------------------------------
            $combobox_pantallas = $combobox_pantallas . "<option value=" .
            $res->fields['codigo'].">" . $res->fields['etiqueta'] . "</option>";

            $res->MoveNext();        
          //  $pantallas = $pantallas .",".$res->fields['id_seccion'].'-'.$res->fields['codigo'];
        }
    }
}
echo $combobox_pantallas;
unset($id_secc);
desconectar($db);
die();
?>  