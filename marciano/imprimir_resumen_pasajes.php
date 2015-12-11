<?php
include_once ("seguridad.php");
include_once ("template.php");
include_once ("conexion.php");
include_once ("funciones.php");
include_once('./html2fpdf/html2pdf.class.php');

ob_start();

// obtenemos los datos pasados por parametros por ajax
$fech_d     = $_GET["fdesde"];
$fech_h     = $_GET["fhasta"];
$loc_o      = $_GET["lo"];
$loc_d      = $_GET["ld"];
$tip_pasaje = $_GET["tipo"];
$form_pago  = $_GET["fo_p"];

$usu        = $_GET["usu"];
$suc        = $_GET["suc"];

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//                       Cargamos el membrete
//------------------------------------------------------------------------------
set_file("imprimir_resumen_pasajes", "imprimir_resumen_pasajes.html");

set_var("v_path_logo",         LOGO);
set_var("v_razon_social",      RAZON_SOCIAL);
set_var("v_direc_razon_social",DIREC_RAZON_SOCIAL);
set_var("v_tel_razon_social",  TEL_RAZON_SOCIAL);
set_var("v_sucursal",          $_SESSION['sucursal']);
set_var("v_vendedor",          $_SESSION['usuario']);

// fecha de impresion
set_var("v_fecha", Date(" d/m/Y - H:i") );
$db = conectar_al_servidor();
// Indica la cantidad de registros encontrados.
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
//                       MUESTRA TODOS LOS REGISTROS DE Los pasajes procesados
//----------------------------------------------------------------------------------------------------


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
            v.fecha_procesado,
            v.usuario,
            v.sucursal
        FROM v_pasajes_vendidos_con_pagos AS v left join tipo_pasajes as tp on (v.ID_TIPO_PASAJE=tp.codigo)
        WHERE (v.a='pg')and(v.FECHA BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."')and(v.procesado='S')";

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

$sql = $sql . "order by v.nropasaje;";


$res = ejecutar_sql($db,$sql);
$v_total_lis = 0;


////----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
if (!$res) {
    echo $db -> ErrorMsg();
    die();
} else {
    $cant = $res -> RecordCount();
   
    if ($cant >= 1) {
        while (!$res -> EOF) {
          // set_var("v_fecha_entregal", cambiaf_a_normal($res -> fields[12]));
            if(floatval($res -> fields[8])==0) {$aux_t1 = '';}else{$aux_t1 = "$ ".$res -> fields[8]; }
            if(floatval($res -> fields[9])==0) {$aux_t2 = '';}else{$aux_t2 = "$ ".$res -> fields[9]; }
            if(floatval($res -> fields[11])==0){$aux_t3 = '';}else{$aux_t3 = "$ ".number_format($res -> fields[11],2);}
            if(floatval($res -> fields[5])==0) {$aux_t4 = '';}else{$aux_t4 = "$ ".$res -> fields[5]; }

                                                   
            set_var("v_nro_pasaje", $res -> fields[0]);
            set_var("v_fecha", cambiaf_a_normal($res -> fields[1]) );
            //set_var("v_pasajero", $res -> fields[3]);
            //set_var("v_dni_pas", $res -> fields[2]);
            //set_var("v_dir_d", $res -> fields[4]);
            set_var("v_pasajero_seg", $aux_t4);
            set_var("v_dni_pas_seg", $res -> fields[6]);
            set_var("v_nro_ch", $res -> fields[7]);
            set_var("v_importe_ch", $aux_t2);
            set_var("v_total", $aux_t1);
            set_var("v_comision", $aux_t3);
            set_var("v_fecha_procesamiento", $res -> fields[12]);
            
            set_var("v_usuario", $res -> fields[13]);
            set_var("v_sucursal", $res -> fields[14]);

            
            
            parse('imprimir_resumenpasajes_pagos');
            $v_total_lis = $v_total_lis + ($res -> fields[9]);
            $v_total_pagos_lis = $v_total_pagos_lis + ($res -> fields[8]);
            $v_total_comision_lis = $v_total_comision_lis + ($res -> fields[11]);
            
            $res -> MoveNext();
	}// fin del while
    }
    
    }
desconectar($db);

set_var("v_cant_total", $cant);
set_var("v_total_listado",       number_format($v_total_lis,2)       ); 
set_var("v_total_pagos_listado", number_format($v_total_pagos_lis,2) ); 
set_var("v_total_comision_listado", number_format($v_total_comision_lis,2) ); 
set_var("v_color_cabezera_columna_tabla", COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);

pparse("imprimir_resumen_pasajes");

// Impresion en PDF
$htmlbuffer=ob_get_contents();
ob_clean();

try{ 
   $fecha = date("ymdhm");   
//   $html2pdf = new HTML2PDF('P', 'A4', 'es');   
   $html2pdf = new HTML2PDF('P','A4','es', false, 'utf-8', array(5, 5, 5, 5));
   $html2pdf->pdf->SetDisplayMode('fullpage'); 
   $html2pdf->writeHTML($htmlbuffer, isset($_GET['vuehtml']));
   $html2pdf->Output('./resumen_pasajes'.$fecha.'.pdf', 'I');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
} 
?>
