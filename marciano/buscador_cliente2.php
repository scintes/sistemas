<?php
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

$db = conectar_al_servidor();


// Consulta sql. Trae todos los vehiculos cargados al sistema que se encuentran con activos (campo activo = 'S')
$sql = "SELECT codigo, dni, nombre, direccion, tel, cel   
        FROM clientes AS c ";     

set_file("buscador_clientes", "buscador_cliente2.html");
$res = ejecutar_sql($db, $sql);



pparse('buscador_clientes');

desconectar($db);

?>