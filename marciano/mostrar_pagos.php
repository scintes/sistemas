<?php
	include_once("seguridad.php");
	include_once("template.php");
	include_once("conexion.php");
	include_once("funciones.php");
        include_once ('propias.php');
        include_once('./html2fpdf/html2pdf.class.php');
        
        ob_start();

        $nro_recibo = $_REQUEST['id'];               
        $nombre_cliente = $_REQUEST['nom_cli'];
        $id_sucursal = substr($nro_recibo,0,  strpos($nro_recibo,'-'));
        $nro_comprobante = substr($nro_recibo,strpos($nro_recibo,'-')+1, 1000);

        set_file("mostrar","mostrar_pagos.html");        
        //------------------------------------------------------------------------------
        //------------------------------------------------------------------------------
        //                       Cargamos el membrete
        //------------------------------------------------------------------------------
        set_var("v_path_logo",LOGO);
        set_var("v_razon_social",RAZON_SOCIAL);
        set_var("v_direc_razon_social",DIREC_RAZON_SOCIAL);
        set_var("v_tel_razon_social",TEL_RAZON_SOCIAL);
        
        //------------------------------------------------------------------------------
        //------------------------------------------------------------------------------
        //   MUESTRA TODOS LOS REGISTROS DE LAS ENCOMIENDAS QUE NO SE HAN COMPLETADO
        //------------------------------------------------------------------------------
        set_var("v_nombre_cliente", $nombre_cliente);
        set_var("v_nro_pago",$nro_recibo);        
        set_var("v_tipo_de_pago", "-");
        set_var("v_detalle_del_pago","-");
        set_var("v_importe","0.00");        
        set_var("v_total_pago", "0.00");
       
        
        $db = conectar_al_servidor();
        $sql = "SELECT p.codigo, p.forma_pago, p.fecha, p.importe, p.id_cheque,
                ch.nro_cheque, ch.id_banco, b.banco, ch.fecha_emision, ch.fecha_cobro,
                ch.fecha_entrega, ch.operacion_ingreso, ch.operacion_salida,
                ch.propio, ch.propietario, ch.entregado_por, ch.entregado_a,
                ch.entregado
                FROM pagos AS p 
                        Left Join cheques AS ch ON ch.codigo = p.id_cheque 
                        left Join bancos AS b ON b.codigo = ch.id_banco
                where p.nro_sucursal = ".$id_sucursal." and p.nro_recibo = ".$nro_comprobante;
        
        $res = ejecutar_sql($db, $sql);

        //----------------------------------------------------------------------------------------------------
        // Verificamos el resultado de la busqueda.
        //----------------------------------------------------------------------------------------------------
        if (!$res) {
            echo $db -> ErrorMsg();
            die();
        } else {
            $cant = $res -> RecordCount();
            $total = 0.0;
            set_var("v_cant_reg", $cant);
            if ($cant >= 1) {
                while (!$res -> EOF) {

                    switch ($res -> fields[1]) { // determinamos el tipo de pago (efectivo,cheque)
                    
                        case 1: // solo efectivo
                                $tipo_pago = 'Efectivo'; 
                                $detalle   = 'Solo efectivo.';
                        break; 
                            
                        case 2: // solo el cheque
                                $tipo_pago = ' Cheque:'.$res -> fields[5]; 
                                $detalle   = ' Cheque:'.$res -> fields[5].' Banco:'.$res -> fields[7].' de:'.$res -> fields[14]; 
                        break; 
                            
                    };
                    
                    $total = $total + $res -> fields[3];
                    set_var("v_nro_operacion",$res -> fields[0]);
                    set_var("v_tipo_de_pago",     $tipo_pago);                    
                    set_var("v_detalle_del_pago", $detalle);
                    set_var("v_importe",          number_format($res -> fields[3],2));        
                    set_var("v_total_pago",       $total);
            
                    parse("listado_pago");          
                    $res -> MoveNext();
                }// fin del while
            }
        }
        
        pparse("mostrar");

// Impresion en PDF


$htmlbuffer=ob_get_contents();
ob_clean();
try{ 
   $fecha = date();   
   $html2pdf = new HTML2PDF('P', 'A4', 'es');   
   $html2pdf->pdf->SetDisplayMode('fullpage'); 
   $html2pdf->writeHTML($htmlbuffer, isset($_GET['vuehtml']));
   $html2pdf->Output('encomienda.pdf', 'I');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}               


?>
