<?php
	include_once("seguridad.php");
	include_once("template.php");
	include_once("conexion.php");
	include_once("funciones.php");
        include_once ('propias.php');
        include_once('./html2fpdf/html2pdf.class.php');
        
        //include_once ('./Barcode/Barcode.php');
        //Image_Barcode::draw('1234', 'int25', 'png');        
        
        
        ob_start();
        

	$b_nro_orden = $_REQUEST['id'];
	set_file("mostrar","mostrar_encomienda.html");

        
        //$num = isset($b_nro_orden) ? b_nro_orden : $b_nro_orden;
        //$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'int25';
        //$imgtype = isset($_REQUEST['imgtype']) ? $_REQUEST['imgtype'] : 'png';
        //Image_Barcode::draw($num, $type, $imgtype);        
        
        
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
	$db = conectar_al_servidor();
	$q = "SELECT
                    e.NRO_GUIA, /* 0 */
                    e.FECHA, /* 1 */
                    e.REMITENTE, /* 2 */
                    e.CUIT_ORIGEN, /* 3 */
                    e.DIRECCION_REMITENTE, /* 4 */
                    e.TEL_ORIGEN, /* 5 */
                    e.DESTINATARIO, /* 6 */
                    e.CUIT_DESTINO, /* 7 */
                    e.DIRECCION_DESTINO,
                    e.TEL_DESTINO,
                    e.PRIORIDAD,
                    e.FECHA_ENTREGA,
                    e.HORA_ENTREGA_DESDE,
                    e.HORA_ENTREGA_HASTA,
                    e.PRECIO,
                    e.ESTADO,
                    e.USUARIO,
                    CONCAT(lr.localidad,' (',lr.codigo_postal,')') AS Localidad_remitente,
                    CONCAT(ld.localidad,' (',ld.codigo_postal,')') AS Localidad_destinatario,
                    pr.provincia,
                    pd.provincia,
                    CONCAT(f.Nro_SUCURSAL,'-',f.NRO_FACTURA),
                    e.COMISION_COMISIONISTA,
                    e.COMISION_SUCURSAL,
                    e.IMPORTE_SEGURO, /*  24  */
                    e.OBSERVACIONES,
                    e.ELIMINADO,
                    e.porcentaje_seguro,
                    e.iva, /* 28 */
                    e.DEBE_TENER_FRIO,
                    e.CON_AVISO_DE_RETORNO
            FROM
                encomiendas AS e
                Inner Join localidades AS lr ON (e.ID_LOCALIDAD_REMITENTE = lr.codigo)
                Inner Join localidades AS ld ON (e.ID_LOCALIDAD_DESTINATARIO = ld.codigo)
                Inner Join provincias AS pr ON (lr.id_provincia = pr.codigo)
                Inner Join provincias AS pd ON (ld.id_provincia = pd.codigo)
                Left Join usuarios AS u ON (e.ID_COMISIONISTA = u.id)
                Left Join facturas AS f ON (e.ID_FACTURA = f.CODIGO)
            WHERE (e.nro_guia=".$b_nro_orden.")";
		
        $res= ejecutar_sql($db,$q);
        
//---------------------------------------------------------------------------------------------------        
	$q2 = "SELECT item, cantidad, descripcion, precio_unitario, id_comisionista, u.usuario, comision_sucursal, comision_comisionista
               FROM   detalle_encomiendas AS de inner join usuarios AS u ON (de.id_comisionista=u.id)
               WHERE (de.id_encomienda=".$b_nro_orden.")";
	
	$res2= ejecutar_sql($db,$q2);
        
        if ($res2->fields[24]>0){
            set_var("v_encomienda_asegurada","S");
        }else{
            set_var("v_encomienda_asegurada","N");           
        }        
        if ($res2->fields[28]=="S"){
            set_var("v_debe_tener_frio","S");
        }else{
            set_var("v_debe_tener_frio","N");           
        }        
        if ($res2->fields[29]=="S"){
            set_var("v_con_aviso_de_retorno","S");
        }else{
            set_var("v_con_aviso_de_retorno","N");           
        }        
	        
	$cant = $res2->RecordCount();
	$suma_sub_total = 0;
        $total_com_suc  = 0;
        $total_com_com  = 0; 
	if ($cant>=1){
            while (!$res2->EOF){			  			
		set_var("v_item",	  $res2->fields[0]);
		set_var("v_comis",	  $res2->fields[5]);
		set_var("v_cantidad",	  $res2->fields[1]);     
		set_var("v_tipo",         $res2->fields[2]);  
		set_var("v_precio",	  $res2->fields[3]);		
		parse('detalleencomienda');
		
                $suma_sub_total += $res2->fields[3];
                $total_com_suc  += $res2->fields[6];
                $total_com_com  += $res2->fields[7]; 

                $res2->MoveNext();
            } // fin del while
        }  else {
		set_var("v_item",	  0);
		set_var("v_comis",	  '');
		set_var("v_cantidad",	  0);     
		set_var("v_tipo",        '');  
		set_var("v_precio",	  0.00);		
		parse('detalleencomienda');            
        };
//------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//------------------------------------------------------------------------------
	if(!$res){
		echo $db->ErrorMsg();
		die();
	}else{
		//           imp a aseg          porc del seguro    
		$imp_seg = ($res->fields[24] * $res->fields[27] / 100);		
		//                                 iva
		$iva_imp = (($suma_sub_total + $imp_seg) * $res->fields[28] / 100);		
		$tota_imp = $suma_sub_total + $iva_imp + $imp_seg;	
		$prioridad = unserialize(PRIORIDAD);
                
		set_var("v_nro_guia",		        $res->fields[0]); // nro de guia
		set_var("v_fecha",			cambiaf_a_normal($res->fields[1])); // fecha creacion del registro
		set_var("v_remitente",			$res->fields[2]); // Remitente     
		set_var("v_dni_remitente",		$res->fields[3]);  // DNI remitente
		set_var("v_dir_remitente",		$res->fields[4]);  // direccion_remitente
		set_var("v_tel_remitente",		$res->fields[5]); // tel_remitente
		set_var("v_destinatario",		$res->fields[6]);  //destinatario
		set_var("v_dni_destinatario",		$res->fields[7]); // dni destinatario
		set_var("v_dir_destinatario",		$res->fields[8]); // direccion_destinatario
		set_var("v_tel_destinatario",		$res->fields[9]); // tel_destinatario
		set_var("v_combobox_prioridad",   	$prioridad[ $res->fields[10] ] ); // prioridad 			
		set_var("v_fecha_entrega",		cambiaf_a_normal($res->fields[11])); // fecha_entrega		
		set_var("v_hora_entrega_desde",		$res->fields[12]); // hora entrega desde	
		set_var("v_hora_entrega_hasta",		$res->fields[13]); // hora entrega hasta	
		set_var("v_precio_total",		sprintf('$ %.2f',$tota_imp)); // $res->fields[14]); // precio encomienda 				
		set_var("v_estado",			$res->fields[15]);  // estado encomienda
		set_var("v_usuario",                    $res->fields[16]);	// usuario de cargfa							
		set_var("v_combobox_loc_remitente",	$res->fields[17]);  //localidad remitente
		set_var("v_combobox_loc_destinatario",	$res->fields[18]);  // localidad destinatario
		set_var("v_combobox_prov_origen",	$res->fields[19]);  //localidad remitente
		set_var("v_combobox_prov_destino",	$res->fields[20]);  // localidad destinatario
		set_var("v_nro_factura",		$res->fields[21]);  // nro factura   
		set_var("v_importe_a_asegurar",		sprintf('$ %.2f',$res->fields[24]));  // importe seguro                 
		set_var("v_porcentaje_para_asegurar",   $res->fields[27]);  // notas sobre la encomienda
		set_var("v_importe_seguro",             sprintf('$ %.2f',$imp_seg));  // notas sobre la encomienda
		set_var("v_subtotal",                   sprintf('$ %.2f',$suma_sub_total)); 
		set_var("v_iva",                        sprintf('$ %.2f',$iva_imp));		
		set_var("v_observaciones",		$res->fields[25]);// notas sobre la encomienda                                                
		set_var("v_porcentaje_iva",             $res->fields[28]);
                set_var("v_comision_sucursal",          sprintf('$ %.2f',$total_com_suc ));
                set_var("v_comision_comisionista",      sprintf('$ %.2f',$total_com_com));              
	}
	
//---------------------------------------------------------------------------------------------------        
	$q3 = "SELECT p.codigo, p.importe, p.forma_pago, p.fecha, p.id_cheque, p.id_encomienda, p.id_pasaje
               FROM pagos AS p 
               where p.id_encomienda=167 
               WHERE (p.forma_pago=4) and (p.id_encomienda=".$b_nro_orden.")";
	
	$res3= ejecutar_sql($db,$q3);
        if(!$res){
		echo $db->ErrorMsg();
		die();
        }else{
            set_var("v_tipo_pago",'PAGO EN DESTINO');
        }
        
	desconectar($db);
	
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

