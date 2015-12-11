<?php
// Buscamos los viajes segun la fecha y/o salidas 
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

// obtenemos los datos pasados por parametros por ajax
$fech_d     = $_POST["f_d"];
$fech_h     = $_POST["f_h"];
$loc_o      = $_POST["lo"];
$loc_d      = $_POST["ld"];
$tip_pasaje = $_POST["tip_p"];
$form_pago  = $_POST["fo_p"];

$usu        = $_POST["usu"];
$suc        = $_POST["suc"];
$ord        = $_POST["ord"];



$db = conectar_al_servidor();

$fecha_desde = cambiaf_a_mysql($fech_d);
$fecha_hasta = cambiaf_a_mysql($fech_h);
                          
$sql = "SELECT DISTINCT
            v.nropasaje,
            v.FECHA,
            v.dni,
            v.NOMBRE_Y_APELLIDO,
            v.DIRECCION_DESTINO,
            v.VALOR_SEGURO,
            tp.tipo_pasaje,
            CASE v.forma_pago 
                          WHEN 1 THEN 'Contado'
                          WHEN 2 THEN 'Cta.Cte.'
                          WHEN 3 THEN 'Cheque'
                          WHEN 4 THEN 'Tarjeta'
                          WHEN 5 THEN 'Res. Pasaje'
            END  f_pago, 
            v.importe_pago,
            v.Total,
            v.A AS tipo_movimiento,
            v.comision,
            v.a,
            v.procesado,
            v.fecha_procesado
        FROM v_pasajes_vendidos_con_pagos AS v left join tipo_pasajes as tp on (v.ID_TIPO_PASAJE=tp.codigo)
        WHERE (v.FECHA BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."')and(v.procesado='S')";

if($loc_o!=-1){       
       $sql = $sql . " and  v.loc_origen = '".$loc_o."'";
}
if($loc_d!=-1){
       $sql = $sql . " and v.loc_destino='".$loc_d."'";
}
if($tip_pasaje!=-1){
       $sql = $sql .  " and v.id_tipo_pasaje = '".$tip_pasaje."'"; 
}
if($form_pago!=-1){               
       $sql = $sql .  " and v.forma_pago = '".$form_pago."' ";   
}
if($usu==-1){               
       $sql = $sql .  " and v.USUARIO like '".$_SESSION[usuario]."' ";   
}else{
       $sql = $sql .  " and v.USUARIO like '".$usu."' ";       
}
if($suc==-1){               
       $sql = $sql .  " and v.sucursal like '".$_SESSION[sucursal]."' ";   
}else{
       $sql = $sql .  " and v.sucursal like '".$suc."' ";       
}

$sql = $sql . "order by v.nropasaje, v.a DESC;";



$res = ejecutar_sql($db,$sql);

if (!$res){  
    echo 'Error accediendo a la tabla viaje...'; 
    die();
}else{ 
    $cant = $res -> RecordCount();
    $pasajes = ''; 
    
    if ($cant >= 1) {         
        while (!$res->EOF){
            
            $pasajes = $pasajes.$res->fields[0]."|".cambiaf_a_normal($res->fields[1])."|".$res->fields[2]."|".$res->fields[3]."|".$res->fields[4]."|".$res->fields[5]
                    ."|".$res->fields[6]."|".$res->fields[7]."|".$res->fields[8]."|".$res->fields[9]."|".$res->fields[10]."|".number_format($res->fields[11], 2)."|".$res->fields[12]."|";

            $v_total_lis = $v_total_lis + ($res -> fields[9]);
            $res->MoveNext();
            $pasajes = $pasajes."@";
        }
    }
}
                                    
set_var("v_cant_total",$cant);
set_var("v_total_listado",$v_total_lis);
//echo $pasajes; // No me mostraba porque tenia una columna de mas fields[10]
//die();
echo $pasajes;
desconectar($db);
die();
?>