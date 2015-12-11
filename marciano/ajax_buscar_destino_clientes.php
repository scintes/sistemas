<?php
// insertamos el registro en la tabla encomienda
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

// parametros recibidos desde el javascript 
$id_clientes               = $_REQUEST["id_cliente"]; // nro de guia separado por ",".
$db_aux = conectar_al_servidor();  

$sql = "SELECT dc.nombre, dc.direccion, dc.tel, dc.dni, l.id_provincia, dc.id_localidad, p.provincia, l.localidad
        FROM destinos_comunes_clientes AS dc inner join localidades AS l ON (dc.id_localidad=l.codigo) and (dc.id_cliente=".$id_clientes.")
                                             inner join provincias AS p ON (l.id_provincia=p.codigo)";

$res = ejecutar_sql($db_aux, $sql);

//----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
if (!$res){
    echo $db_aux->ErrorMsg();
    //mensaje('La encomienda no se pudo guardar...');
}else{
    // dc.nombre, dc.direccion, dc.tel, dc.dni, dc.id_provincia, dc.id_localidad, p.provincia, l.localidad, dc.dni
    echo $res->fields[0]."|".$res->fields[1]."|".$res->fields[2]."|".$res->fields[3]."|".$res->fields[4]."|".$res->fields[5]."|".$res->fields[6]."|".$res->fields[7]."|".$res->fields[8];           
}
desconectar($db_aux);

?>
