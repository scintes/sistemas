<?php

include_once ("seguridad.php");
include_once ("template.php");
include_once ("conexion.php");
include_once ("funciones.php");

include_once('./html2fpdf/html2pdf.class.php');

ob_start();
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//                       Cargamos el membrete
//------------------------------------------------------------------------------
set_var("v_path_logo",          LOGO              );
set_var("v_razon_social",       RAZON_SOCIAL      );
set_var("v_direc_razon_social", DIREC_RAZON_SOCIAL);
set_var("v_tel_razon_social",   TEL_RAZON_SOCIAL  );

set_file("imprimir_ctacte", "imprimir_ctacte.html");
//------------------------------------------------------------------------------
// Valores obtenidos del archivo de configuracion CONEXION.PHP
//------------------------------------------------------------------------------
set_var("v_color_cabezera_tabla",         COLOR_ENCOMIENDAS_CABEZERA_TABLA);
set_var("v_color_cabezera_columna_tabla", COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);
set_var("v_color_pie_tabla",              COLOR_ENCOMIENDAS_PIE_TABLA);
set_var("v_color_fila_pago_destino",      COLOR_ENCOMIENDAS_FILA_TIPO_PAGO_EN_DESTINO);
set_var('v_color_columna_remitente',      COLOR_ENCOMIENDAS_REMITENTE);
set_var('v_color_columna_destinatario',   COLOR_ENCOMIENDAS_DESTINATARIO);


$nro_cliente =          $_REQUEST['e_nro_cliente'];
$nombre_cliente =       $_REQUEST['e_nombre_cliente'];
$fd =                   $_REQUEST['fd'];
$fh =                   $_REQUEST['fh'];
$id_suc =               $_SESSION['id_sucursal'];


set_var("v_b_fecha_desde",  $f_d);
set_var("v_b_fecha_hasta",  $f_h);
set_var("v_nro_cliente",    $nro_cliente);
set_var("v_nombre_cliente", $nombre_cliente);
set_var("v_b_fecha_desde",  $fd);
set_var("v_b_fecha_hasta",  $fh);
    

$id_suc = $_SESSION['id_sucursal'];
set_var("v_sucursal", $_SESSION['sucursal']);
set_var("v_vendedor", $_SESSION['usuario']);
$db = conectar_al_servidor();


// Indica la cantidad de registros encontrados.
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
//                       MUESTRA TODOS LOS REGISTROS DE LAS ENCOMIENDAS sin procesar
//----------------------------------------------------------------------------------------------------
$sql = " /* ********************************************************************
         ***   DEUDA - Encomienda  
         ******************************************************************** */
        SELECT 
                cc.fecha,
                cc.id_encomienda,
                CONCAT('Encomienda', '      Nro. Factura: ',COALESCE( f.NRO_SUCURSAL,' '),  '-' , COALESCE(f.NRO_FACTURA,' '), '   Destinatario:  ', e.destinatario) AS tipo_movimiento,
                p.importe AS deuda,
                0 AS pagos,
                'E' AS tipo,
                CONCAT(COALESCE(f.NRO_SUCURSAL,' '),'-',COALESCE(f.NRO_FACTURA,' ')) AS nro_comprobante
        FROM
            ctactes AS cc
                Inner Join encomiendas AS e ON (cc.id_encomienda= e.NRO_GUIA) 
                Inner Join pagos AS p ON (cc.id_pago = p.codigo)
                Left Join facturas AS f ON (f.CODIGO = e.ID_FACTURA)
        WHERE cc.id_cliente=".$nro_cliente." and DATE(cc.fecha) BETWEEN '".cambiaf_a_mysql($fd)."' AND '".cambiaf_a_mysql($fh)."'

        UNION ALL 
         /* ********************************************************************
         ***   DEUDA - Pasajes  
         ******************************************************************** */
        SELECT 
                cc.fecha,
                cc.id_encomienda,
                CONCAT('PASAJES', '      Nro. Factura: ',COALESCE( f.NRO_SUCURSAL,''),  '-' , COALESCE(f.NRO_FACTURA,''), '   Destinatario:  ', ps.Nombre_y_apellido) AS tipo_movimiento,
                ps.precio AS deuda,
                0 AS pagos,
                'P' AS tipo,
                CONCAT(COALESCE(f.NRO_SUCURSAL,''),'-',COALESCE(f.NRO_FACTURA,' ')) AS nro_comprobante
        FROM
            ctactes AS cc
                Inner Join pasajes AS ps ON (cc.id_pasaje= ps.codigo) 
                Inner Join pagos AS p ON (cc.id_pago = p.codigo)
                Left Join facturas AS f ON (f.CODIGO = ps.ID_FACTURA)
        WHERE cc.id_cliente=".$nro_cliente." and DATE(cc.fecha) BETWEEN '".cambiaf_a_mysql($fd)."' AND '".cambiaf_a_mysql($fh)."'
        
        UNION ALL
         /* ********************************************************************
         ***   PAGOS - Ejectivo / cheques  
         ******************************************************************** */

        SELECT 
                cc.fecha,
                cc.id_pago,
                CONCAT('PAGO', '      Nro. comprovante: ',CONCAT(p.NRO_SUCURSAL,'-',p.NRO_RECIBO)) AS tipo_movimiento,
                0 AS deuda,
                p.importe AS pagos,
                'G' AS tipo,
                CONCAT(COALESCE(p.NRO_SUCURSAL,' '),'-',COALESCE(p.NRO_RECIBO,' ')) AS nro_comprobante
        FROM
            ctactes AS cc
            Inner Join pagos AS p ON (cc.id_encomienda is null ) AND (cc.id_pasaje is null) AND (cc.id_pago = p.codigo)
        WHERE cc.id_cliente =".$nro_cliente." and DATE(cc.fecha) BETWEEN '".cambiaf_a_mysql($fd)."' AND '".cambiaf_a_mysql($fh)."' ";



$res = ejecutar_sql($db, $sql);

//----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
if (!$res) {
    echo $db -> ErrorMsg();
    die();
} else {

    $cant = $res -> RecordCount();
    set_var("v_cant_reg", $cant);
    if ($cant >= 1) {
        while (!$res -> EOF) {
            set_var("v_nro_comprobante", $res -> fields[6] );
            set_var("v_nro_secuencia", $res -> fields[1]);
            set_var("v_fecha", cambiaf_a_normal($res -> fields[0]));
            set_var("v_operacion", $res -> fields[2]);
            set_var("v_importe_debe",  number_format($res -> fields[3],2));
            set_var("v_importe_haber", number_format($res -> fields[4],2));
            parse('listado_ctacte');           
            $res -> MoveNext();
	}// fin del while

        //-----------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	// <<<<< Mostramos los totales de la consulta.    >>>>>
	//-----------------------------------------------------------------------------------
        $sql2 = "select  FORMAT(sum(deuda),2) ,   FORMAT(sum(pago),2)
                from (
                        SELECT  sum(deuda) as deuda,            0 AS pago

                        FROM     (SELECT sum( COALESCE(p.importe,0) ) AS deuda
                                  FROM ctactes AS cc Inner Join encomiendas AS e ON (cc.id_encomienda= e.NRO_GUIA)   Inner Join pagos AS p ON (cc.id_pago = p.codigo)
                                  WHERE (cc.id_cliente=".$nro_cliente.")
                
		UNION ALL 
                
	        SELECT sum( COALESCE( ps.precio,0) ) AS deuda
                FROM ctactes AS cc2 Inner Join pasajes AS ps ON (cc2.id_pasaje= ps.codigo)  Inner Join pagos AS p2 ON (cc2.id_pago = p2.codigo)
                WHERE (cc2.id_cliente=".$nro_cliente.")
                 ) as Tdeuda

                union all

                SELECT  0 as deuda,     sum(pagos) AS pago
                FROM  (
                        SELECT p.importe AS pagos
                        FROM
                            ctactes AS cc
                            Inner Join pagos AS p ON (cc.id_encomienda is null ) AND (cc.id_pasaje is null) AND (cc.id_pago = p.codigo)
                        WHERE (cc.id_cliente = ".$nro_cliente.")
                ) AS Tpagos
                ) AS TDATOS";

        $total = ejecutar_sql($db, $sql2);
        
        $cant2 = $total -> RecordCount();      
        if ($cant2 > 0) {
            set_var("v_total_debe",  number_format($total->fields[0],2));
            set_var("v_total_haber", number_format($total->fields[1],2));            
            set_var("v_total",       number_format(($total -> fields[0] - $total -> fields[1]), 2) );

        } else {
            set_var("v_total_debe",  number_format(0,2));
            set_var("v_total_haber", number_format(0,2));
            set_var("v_total",       number_format(0,2));
        }
    } else {
        set_var("v_nro_secuencia", "");
        set_var("v_fecha", "");
        set_var("v_operacion", "");
        set_var("v_importe_debe", "");
        set_var("v_importe_haber", "");

        set_var("v_total_debe",  number_format(0,2));
	set_var("v_total_haber", number_format(0,2));
        set_var("v_total",       number_format(0,2));

    }// fin del If cantidad
parse('listado_ctacte');
}
desconectar($db);
pparse("imprimir_ctacte");

// Impresion en PDF

$htmlbuffer=ob_get_contents();
ob_clean();
try{ 
   $fecha = date("ymdhm");   
//   $html2pdf = new HTML2PDF('P', 'A4', 'es');   
   $html2pdf = new HTML2PDF('P','A4','es', false, 'ISO-8859-15', array(5, 5, 5, 10));
   $html2pdf->pdf->SetDisplayMode('fullpage'); 
   $html2pdf->writeHTML($htmlbuffer, isset($_GET['vuehtml']));
   $html2pdf->Output('./cierre_encomienda'.$fecha.'.pdf', 'I');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}        


?>

