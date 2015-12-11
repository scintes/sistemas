<?php
// Muestra la grilla del resultado de la busqueda   
include_once("seguridad.php");
include_once("conexion.php");

$dato               = $_POST["code"    ]; // dato a buscar.
$desde_registro     = $_POST["pag_d"   ]; // desde que registro se visualiza.
$pag_amostrar       = $_POST["pag_v"   ]; // Cantidad de registros a visualizar.
$det_cant_registros = $_POST["det_cant"]; // desde que registro se visualiza

$db = conectar_al_servidor();

if ($det_cant_registros=='S'){
    $select = " SELECT count(*) ";
    $limit = "";    
}else{
    $select = " SELECT l.codigo, l.dni, l.nombre, l.direccion, l.tel, l.cel, o.localidad, p.provincia,  l.razon_social, l.direccion_razon_social ";
    $limit = " LIMIT ".$pag_amostrar." OFFSET ".$desde_registro;    
}
$from  = " FROM  clientes l inner join localidades o on (l.id_localidad=o.codigo) inner join provincias p on (o.id_provincia=p.codigo) ";
$where = " WHERE l.nombre LIKE '%".$dato."%' or l.codigo LIKE '%".$dato."%' or l.direccion LIKE '%".$dato."%' or l.razon_social LIKE '%".$dato."%' or l.direccion_razon_social LIKE '%".$dato."%' ";

$sql = $select.$from.$where.$limit;    

$res = ejecutar_sql($db,$sql);

if (!$res){      
    mensaje('Error accediendo a las salidas...');    
}else{
    if ($det_cant_registros=='S'){   
//        echo $res->fields['codigo'];    
        echo $res->fields[0];    
    }else{
        $registros = '';       
        $resultado = '';
        while (!$res->EOF){     
            //            codigo              DNI                 nombre              Direccion            tel                cel.    
//            $registros = $res->fields['codigo']."|".$res->fields['dni']."|".$res->fields['nombre']."|".$res->fields['direccion']."|".$res->fields['tel']."|".$res->fields['cel']."|"; 
            $registros = $res->fields[0]."|".$res->fields[1]."|".$res->fields[2]."|".$res->fields[3]."|".$res->fields[4]."|".$res->fields[5]."|"; 
            $resultado = $resultado.'@'.$registros;
            $res->MoveNext();        
        };   
        echo $resultado;
    }
};
desconectar($db);
die();
?>
