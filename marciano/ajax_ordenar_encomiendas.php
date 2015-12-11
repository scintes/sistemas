<?php
// Calculamos el precio
include_once("seguridad.php");
include_once("conexion.php");

$db = conectar_al_servidor();

$comision_misionista = 0;
$comision_sucursal   = 0;
$sub_total           = 0;
$iva                 = 0;
$Total               = 0;

// obtenemos los datos pasados por parametros por ajax
$te = $_GET["code"];
$id_com = $_GET["id_com"];

$sql = "select te.precio
        from  tipos_de_encomiendas te 
        where te.codigo = ".$te;    

$res = ejecutar_sql($db,$sql);


$precio = $res->fields[0]; // precio

$sql = "select tu.activo, tu.comision, tu.comision_reserva, s.porcentaje_comicion, s.porcentaje_comision_reserva 
        from usuarios u inner join conf_usuario cu on (u.id_configuracion_usuario=cu.codigo)and(u.id=".$id_com.")
			inner join tipos_de_usuarios tu on (cu.id_tipo_usuario=tu.codigo)
			inner join sucursales s on (cu.id_sucursal=s.codigo)";
$res2 = ejecutar_sql($db,$sql);

$porc_comisionista = $res2->fields[1]; // comision del comisionista
$porc_sucursal   = $res2->fields[3]; // comision de la sucursal

if ((!$res)and(!$res2)){  
    mensaje('Error accediendo a tipo de encomienda...');    
}else{    
    //$precio = $res[0]; // Precio neto de la encomienda.       
    while (!$res->EOF){        
        
        $comision_misionista = ($precio * $porc_comisionista / 100);
        $comision_sucursal   = ($precio * $porc_sucursal    / 100);
        $sub_total = $precio + $comision_misionista + $comision_sucursal;
 
        $res->MoveNext();
    };   
    $iva = ($sub_total * 21) / 100;
    $Total = $sub_total + $iva;   
};

echo $porc_comisionista.'|'.$comision_misionista.'|'.$porc_sucursal.'|'.$comision_sucursal.'|'.$sub_total.'|'.$iva.'|'.$Total.'|'.$precio;
desconectar($db);
die();


?>
