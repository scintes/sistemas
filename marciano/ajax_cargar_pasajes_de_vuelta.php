<?php
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor(); 

//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de Pasajes de vuelta todos los pasajes marcados como vueltas que aun no tengan 
// fecha de uso cargada.
//----------------------------------------------------------------------------------------------------
$q = "select p.CODIGO, p.NOMBRE_Y_APELLIDO, p.FECHA, v.fecha_vencimiento, v.estado, p.dni
      from pasajes_de_vuelta AS v INNER JOIN pasajes AS p on (v.id_pasaje_origen=p.codigo)
      where v.fecha_uso is null 
      order by  p.NOMBRE_Y_APELLIDO, v.fecha_vencimiento ";

$res = ejecutar_sql($db, $q);

if (!$res){
    echo ''; //$db->ErrorMsg(); //die();
    
}else{
    $comboBox_pasaje_vuelta = "<option value=0>Seleccione un pasaje</option>";
    while (!$res->EOF) {
        // Datos del que viajo en el pasaje de ida------------------------------------------------- 
        $comboBox_pasaje_vuelta = $comboBox_pasaje_vuelta . "<option value=" .$res->fields[0]."#" .$res->fields[1]."#".$res->fields[2]."#" .$res->fields[3]."#".$res->fields[4]."#".$res->fields[5].">"
                   . $res->fields[0].' - '. $res->fields[3]. "</option>";
        $res->MoveNext();
    }
    //set_var("v_comboBox_pasaje_vuelta",$comboBox_pasaje_vuelta);
}

echo $comboBox_pasaje_vuelta;

?>
