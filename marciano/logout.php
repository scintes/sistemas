<?php
//session_start();
//session_name("");
include_once 'conexion.php';

unset($_SESSION['usuario']);
unset($_SESSION['sucursal']);
unset($_SESSION['id_sucursal']);
unset($_SESSION['imagen']);                
unset($_SESSION['id_usuario']);
unset($_SESSION['id_conf_usuario']);
unset($_SESSION['id_personal']);
unset($_SESSION['id_loc_orig_encom']);
unset($_SESSION['id_loc_dest_encom']);                
unset($_SESSION["autentificado"]);
unset($_SESSION["ultimoAcceso"]);
logOut();
// lo redirijo al login
header("Location:login.php");
die();
?>
