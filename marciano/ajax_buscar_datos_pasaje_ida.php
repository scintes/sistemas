<?php
// Muestra la grilla del resultado de la busqueda   
include_once("seguridad.php");
include_once("conexion.php");
/*
$id_viaje               = $_POST["code"    ]; // dato a buscar.
$desde_registro     = $_POST["pag_d"   ]; // desde que registro se visualiza.
$pag_amostrar       = $_POST["pag_v"   ]; // Cantidad de registros a visualizar.
$det_cant_registros = $_POST["det_cant"]; // desde que registro se visualiza

$db = conectar_al_servidor();

$sql = '';    

$res = ejecutar_sql($db,$sql);

if (!$res){      
    mensaje('Error accediendo a las salidas...');    
}else{
    if ($det_cant_registros=='si'){   
        echo $res->fields[0];    
    }else{
        $registros = '';       
        $resultado = '';
        while (!$res->EOF){     
            //            codigo              DNI                 nombre              Direccion            tel                cel.    
            $registros = $res->fields[0]."|".$res->fields[1]."|".$res->fields[2]."|".$res->fields[3]."|".$res->fields[4]."|".$res->fields[5]."|"; 
            $resultado = $resultado.'@'.$registros;
            $res->MoveNext();        
        };   
        echo $resultado;
    }
};
desconectar($db);
die();*/
?>
