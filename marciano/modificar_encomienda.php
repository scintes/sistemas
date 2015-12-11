<?php
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

$b_nro_orden = $_REQUEST['id'];
set_file("modificar", "modificar_encomienda.html");

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//              MUESTRA TODOS LOS REGISTROS DE LAS ENCOMIENDAS
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
                    lr.localidad AS Localidad_remitente,
                    ld.localidad AS Localidad_destinatario,
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
                Inner Join localidades AS ld ON (e.ID_LOCALIDAD_REMITENTE = ld.codigo)
                Inner Join provincias AS pr ON (lr.id_provincia = pr.codigo)
                Inner Join provincias AS pd ON (ld.id_provincia = pd.codigo)
                Left Join usuarios AS u ON (e.ID_COMISIONISTA = u.id)
                Left Join facturas AS f ON (e.ID_FACTURA = f.CODIGO)
            WHERE (e.nro_guia=".$b_nro_orden.")";
$res = ejecutar_sql($db, $q);
//---------------------------------------------------------------------------------------------------        
	$q2 = "SELECT item, cantidad, descripcion, precio_unitario, id_comisionista, u.usuario, comision_sucursal, comision_comisionista
               FROM   detalle_encomiendas AS de inner join usuarios AS u ON (de.id_comisionista=u.id)
               WHERE (de.id_encomienda=".$b_nro_orden.")";
	
	$res2= ejecutar_sql($db,$q2);
        

//----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
if (!$res) {
    echo $db->ErrorMsg();
    die();
} else {
    
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
		set_var("v_precio_total",		$tota_imp); // $res->fields[14]); // precio encomienda 				
		set_var("v_estado",			$res->fields[15]);  // estado encomienda
		set_var("v_usuario",                    $res->fields[16]);	// usuario de cargfa							
		set_var("v_combobox_loc_remitente",	$res->fields[17]);  //localidad remitente
		set_var("v_combobox_loc_destinatario",	$res->fields[18]);  // localidad destinatario
		set_var("v_combobox_prov_origen",	$res->fields[19]);  //localidad remitente
		set_var("v_combobox_prov_destino",	$res->fields[20]);  // localidad destinatario
		set_var("v_nro_factura",		$res->fields[21]);  // nro factura   
		set_var("v_importe_a_asegurar",		$res->fields[24]);  // importe seguro                 
		set_var("v_porcentaje_para_asegurar",   $res->fields[27]);  // notas sobre la encomienda
		set_var("v_importe_seguro",              $imp_seg);  // notas sobre la encomienda
		set_var("v_subtotal",                   $suma_sub_total); 
		set_var("v_iva",                        $iva_imp);		
		set_var("v_observaciones",		$res->fields[25]);// notas sobre la encomienda                                                
		set_var("v_porcentaje_iva",             $res->fields[28]);
                set_var("v_comision_sucursal",          $total_com_suc );
                set_var("v_comision_comisionista",      $total_com_com);              

 
                // variables a usar para que los combobox muestren los seleccionados el la encomienda.
                $id_loc_rem =                       $res->fields[24];
                $id_loc_des =                       $res->fields[25];
                $id_tipo_corr =                     $res->fields[26]; 
                $prioridad_act =                    $res->fields[11];  // prioridad 
                $id_prov_rem =                      $res->fields[27];   
                $id_pro_des =                       $res->fields[28];   
                $id_comi =                          $res->fields[29];   
                $id_factura =                       $res->fields[30];   
    
                //**************************************************************
                //       Traemos el DETALLE DEL PAGOS
                //**************************************************************
                $q3 = "SELECT p.codigo, p.importe, p.forma_pago, p.fecha, p.id_cheque, p.id_encomienda, p.id_pasaje, cc.id_cliente,
                            ch.nro_cheque, ch.id_banco, ch.importe, ch.fecha_emision, ch.fecha_cobro, ch.fecha_entrega, ch.operacion_ingreso, ch.operacion_salida,
                            ch.propio, ch.propietario, ch.entregado_por, ch.entregado_a, ch.entregado, ch.eliminado, cl.nombre, cl.codigo as nro_cuenta_ctacte
                      FROM pagos AS p Left Join cheques AS ch ON (p.id_cheque = ch.codigo) Left Join ctactes AS cc ON cc.id_pasaje = p.codigo Left Join clientes AS cl ON (cc.id_cliente = cl.codigo)
                      WHERE p.id_encomienda = ".$b_nro_orden;
	
                $res3= ejecutar_sql($db,$q3);

                // Verificamos el resultado de la busqueda de los pagos.
                $detalle_pago = "";
                $suma_pago    = 0;
                $det_pago     = "";
                while (!$res3->EOF){			  			
                    
                    $suma_pago = $suma_pago + $res3->fields[1];  

                    if ($res3->fields[2]==1){
                       $det_pago = "Efectivo";
                    }else{ if ($res3->fields[2]==2){
                         $det_pago = "Cheques: ".$res3->fields[8];                        
                        }else{ $det_pago = "Cta. Cte. Nro:".$res3->fields[23];
                    }}     
                    
                    set_var("v_forma_pago",   $det_pago       );     
                    set_var("v_importe_pago", sprintf("%8.2f", $res3->fields[1]));  
                    
                    parse('detallepago');                
                    
                    $res3->MoveNext();
                    
                } // fin del while
                
                set_var('v_total_pago', sprintf("%8.2f",$suma_pago));    
    
}
//------------------------------------------------------------------------------
// Cargamos LA CONFIGURACION DE ARRANQUE PARA OBTENER LAS LOCALIDAD REMITENTE y 
// DESTINATARIO POR DEFECTO
//------------------------------------------------------------------------------
    $q = "select cf.codigo, cf.id_loc_remitente_encomienda, cf.id_loc_destinatario_encomienda, 
			cf.id_provincia_origen_encomienda 
		  from conf_usuario cf
		  where (cf.codigo = " . $_SESSION['id_conf_usuario'] . ")";

    $res = ejecutar_sql($db, $q);
    if (!$res) {
        echo $db->ErrorMsg(); //die();
    } else {
        // cargamos lo obtenido en las variables
        $id_loc_remit_desde = $res->fields[1];
        $id_loc_dest_desde  = $res->fields[2];
        $id_prov_orig       = $res->fields[3];
    }

  
//------------------------------------------------------------------------------
// Cargamos el comboBOX de tipo encomienda
//------------------------------------------------------------------------------
    $q = "select codigo, tipo_encomienda, precio, aplica_descuentos from tipos_de_encomiendas te where te.activa='S'";
    $res = ejecutar_sql($db, $q);
    if (!$res) {
        echo $db->ErrorMsg(); //die();
    } else {
        $combobox_tipo_encomeinda = "";//<option value=0>Seleccione uno...</option>";
        while (!$res->EOF) {
              if ($id_tipo_corr == $res->fields[0]) {
                    $combobox_tipo_encomeinda = $combobox_tipo_encomeinda . "<option value=".$res->fields[0] . " selected=True >" . $res->fields[1] ." - ( $ ".$res->fields[2]." ) </option>";
              }else{
                    $combobox_tipo_encomeinda = $combobox_tipo_encomeinda . "<option value=".$res->fields[0] . ">" . $res->fields[1] ." - ( $ ".$res->fields[2]." ) </option>";
              }
              $res->MoveNext();
        }
    }
    set_var("v_comboBox_tipo_encomienda", $combobox_tipo_encomeinda);
  //  set_var("v_precio", $res->Fields[2]); // precio encomienda 				


//------------------------------------------------------------------------------
// Cargamos el comboBOX de prioridades
//------------------------------------------------------------------------------
    $prioridad = unserialize(PRIORIDAD);
    for ($i = 0; $i <= count($prioridad); $i++) {
        if ($prioridad_act == $i) {
            $combobox_prioridad = $combobox_prioridad . "<option value=" . $i . " selected=True>" . $prioridad[$i] . "</option>";
        }else{
            $combobox_prioridad = $combobox_prioridad . "<option value=" . $i . ">" . $prioridad[$i] . "</option>";            
        }
    };
    set_var("v_combobox_prioridad", $combobox_prioridad);

//------------------------------------------------------------------------------
// Cargamos el comboBOX de PROVINCIAS
//------------------------------------------------------------------------------
    $q = "select codigo, provincia from provincias p ";
    $res = ejecutar_sql($db, $q);
    if (!$res) {
        echo $db->ErrorMsg(); //die();
    } else {
        while (!$res->EOF) {
            // Cargamos el listado de provincias y seleccionamos la provincia de Origen predeterminada
            if ($res->fields[0] == $id_prov_rem) {
                $combobox_prov_origen = $combobox_prov_origen . "<option value=" . $res->fields[0] . " selected=True>" .
                $res->fields[1] . "</option>";
            } else {
                $combobox_prov_origen = $combobox_prov_origen . "<option value=" . $res->fields[0] . ">" . $res->fields[1]. "</option>";
            }
            
            
            
            // cargamos el listado de provincia de destino 
            if ($res->fields[0] == $id_prov_des) {
                $combobox_prov_destino = $combobox_prov_destino . "<option value=" . $res->fields[0] . "selected=TRUE >" . $res->fields[1] . "</option>";        
            }else{
                $combobox_prov_destino = $combobox_prov_destino . "<option value=" . $res->fields[0] . ">" . $res->fields[1] . "</option>";        
            }
            $res->MoveNext();
        }
    }
    set_var("v_combobox_prov_origen", $combobox_prov_origen);
    set_var("v_combobox_prov_destino", $combobox_prov_destino);



//------------------------------------------------------------------------------
// Cargamos el comboBOX de LOCALIDAD REMITENTE y DESTINATARIO
//------------------------------------------------------------------------------
    $q = "select l.codigo, l.localidad 	from localidades l where l.id_provincia=" . $id_prov_orig;
    $res = ejecutar_sql($db, $q);
    if (!$res) {
        echo $db->ErrorMsg(); //die();
    } else {
        while (!$res->EOF) {

            // remitente --------------------------------------------------------------	
            if ($id_loc_remit_desde == $res->fields[1]) {
                $combobox_loc_remitente = $combobox_loc_remitente . "<option value=" .
                    $res->fields[0] . " selected='True'>" . $res->fields[1] . "</option>";
            } else {
                $combobox_loc_remitente = $combobox_loc_remitente . "<option value=" .
                    $res->fields[0] . ">" . $res->fields[1] . "</option>";
            }
            // destinatario -----------------------------------------------------------
            if ($id_loc_dest_desde == $res->fields[1]) {
                $combobox_loc_destinatario = $combobox_loc_destinatario . "<option value=" .
                    $res->fields[0] . " selected='True'>" . $res->fields[1] . "</option>";
            } else {
                $combobox_loc_destinatario = $combobox_loc_destinatario . "<option value=" .
                    $res->fields[0] . ">" . $res->fields[1] . "</option>";
            }

            $res->MoveNext();
        }
    }
    set_var("v_combobox_loc_remitente", $combobox_loc_remitente);
    set_var("v_combobox_loc_destinatario", $combobox_loc_destinatario);


//------------------------------------------------------------------------------
// Cargamos el comboBOX de COMISIONISTAS ---> (tu.codigo=3 comisionistas)
//------------------------------------------------------------------------------
    $q = "select u.id, u.nombre, u.usuario from usuarios u inner join tipos_de_usuarios tu on 
			(u.id_tipo_usuario=tu.codigo) and (tu.codigo=3)";
    $res = ejecutar_sql($db, $q);
    if (!$res) {
        echo $db->ErrorMsg(); 
    } else {
        
     //   $combobox_comisionista = "<option value=0>Seleccione uno...</option>";    
        while (!$res->EOF) {
            if ($id_comi == $res->fields[0]) {
                $combobox_comisionista = $combobox_comisionista . "<option value=" .$res->fields[0] . " selected=True >" . $res->fields[1] . "</option>";
            }else{
                $combobox_comisionista = $combobox_comisionista . "<option value=" .$res->fields[0] . ">" . $res->fields[1] . "</option>";
            }
            $res->MoveNext();
        }
    }
    set_var("v_comboBox_comisionista", $combobox_comisionista);
    //----------------------------------------------------------------------------------------------------
}

pparse("modificar");

?>

