<?php
// Muestra la grilla del resultado de la busqueda   
include_once("seguridad.php");
include_once("conexion.php");
      
require_once 'class.eyemysqladap.inc.php';
require_once 'class.eyedatagrid.inc.php';

$dato = $_REQUEST["code"]; // dato a buscar.

if(!$dato){$dato=0;}

// nro, dni, nombre, direccion, id_loc, localidad, id_prov, provincia, tel 
$selec = " l.codigo, l.dni, l.nombre, l.direccion, o.localidad, p.provincia, l.tel, l.razon_social, l.direccion_razon_social ";
$from  = " clientes l inner join localidades o on (l.id_localidad=o.codigo) inner join provincias p on (o.id_provincia=p.codigo)";
$where = " l.nombre LIKE '%".$dato."%' or l.codigo LIKE '%".$dato."%' or l.direccion LIKE '%".$dato."%' or l.razon_social LIKE '%".$dato."%' or l.direccion_razon_social LIKE '%".$dato."%'";

$db2 = new EyeMySQLAdap(HOST, USUARIO, PASSWORD, BASE);
$x2 = new EyeDataGrid($db2);

$x2->showRadiobutton();

$x2->setQuery($selec, $from,'codigo', $where);
$x2->printTable();

?>
