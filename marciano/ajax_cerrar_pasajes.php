<?php
// insertamos el registro en la tabla encomienda
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

// parametros recibidos desde el javascript 
$nros_de_pasaje = $_REQUEST["code"]; // nro de guia separado por ",".
$db = conectar_al_servidor();  

$sql = "UPDATE pagos Set  
               procesado='S',     
               fecha_procesado ='".  dar_fecha() ."'
        Where id_pasaje in (".$nros_de_pasaje.")";

$res = ejecutar_sql($db, $sql);

//----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
if (!$res){
    echo $db->ErrorMsg();
    //mensaje('La encomienda no se pudo guardar...');
}else{
    echo 'El/os siguientes pasajes/s '.$nros_de_pasaje.' fue/ron procesado correctamente...';
}

?>