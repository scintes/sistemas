<?php
// insertamos el registro en la tabla encomienda
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

// parametros recibidos desde el javascript 
$nros_de_guias               = $_REQUEST["code"]; // nro de guia separado por ",".
$db = conectar_al_servidor();  

$sql = "UPDATE encomiendas Set  
               estado='PROCESADA',     
               entregada ='N'
        Where nro_guia in (".$nros_de_guias.")";

$res = ejecutar_sql($db, $sql);

//----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
if (!$res){
    echo $db->ErrorMsg();
    //mensaje('La encomienda no se pudo guardar...');
}else{
    echo 'La/s encomienda/s '.$nros_de_guias.' fue/ron procesada correctamente...';
}

?>