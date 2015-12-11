<?php
	include_once("seguridad.php");
	include_once("template.php");
	include_once("conexion.php");
	include_once("funciones.php");

    $fecha_desde  		= dar_fecha(); //$_REQUEST['e_fecha_desde'];
    $fecha_hasta 		= dar_fecha(); //$_REQUEST['e_fecha_hasta'];
    $b_nro_orden 		= $_REQUEST['e_nro_orden'];
    $b_tipo_encomienda  = $_REQUEST['e_tipo_encomienda'];
        
	
    set_var("v_b_dni_destinatario"," ");
    $id_suc = $_SESSION['id_sucursal'];	
            
        //------------------------------------------------------------------------------
        // Valores obtenidos del archivo de configuracion CONEXION.PHP
        //------------------------------------------------------------------------------
        set_var("v_color_cabezera_tabla",         COLOR_ENCOMIENDAS_CABEZERA_TABLA);
        set_var("v_color_cabezera_columna_tabla", COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);
        set_var("v_color_pie_tabla",              COLOR_ENCOMIENDAS_PIE_TABLA);
        set_var("v_color_fila_pago_destino",      COLOR_ENCOMIENDAS_FILA_TIPO_PAGO_EN_DESTINO);
        set_var('v_color_columna_remitente',      COLOR_ENCOMIENDAS_REMITENTE);
        set_var('v_color_columna_destinatario',   COLOR_ENCOMIENDAS_DESTINATARIO);
            
	set_file("encomiendas","cierre_encomienda.html");
        
        set_var("v_b_fecha_desde", $fecha_desde); // fecha_desde
        set_var("v_b_fecha_hasta", $fecha_hasta); // fecha_hasta
        set_var("v_b_nro_orden", $b_nro_orden);
        set_var("v_b_dni_remitente",$b_dni_remitente);
        set_var("v_b_dni_destinatario",$b_destinatario);

        set_var("v_sucursal", $_SESSION['sucursal']); // fecha_hasta
        set_var("v_total_ctacte", 0.00); // sumatoria de cta	cte
        set_var("v_sucursal",$_SESSION['sucursal'] ); // sumatoria de cta cte
        set_var("v_vendedor",$_SESSION['usuario']  );
        set_var("v_cant_reg",0); // Indica la cantidad de registros encontrados.
		
        $db = conectar_al_servidor();
        
        //----------------------------------------------------------------------------------------------------
        //----------------------------------------------------------------------------------------------------
        // Cargamos el comboBOX de COMISIONISTAS
        //----------------------------------------------------------------------------------------------------
        $q = "select u.id, u.nombre, u.usuario from usuarios u inner join tipos_de_usuarios tu on 
			(u.id_tipo_usuario=tu.codigo) and (tu.codigo=3)";
        $res = ejecutar_sql($db, $q);
        if (!$res) {
            echo $db->ErrorMsg(); //die();
        } else {
            $combobox_comisionista = "<option value=0>Todos...</option>";    
            while (!$res->EOF) {
                $combobox_comisionista = $combobox_comisionista . "<option value=" .$res->fields[0] . ">" . $res->fields[1] . "</option>";
                $res->MoveNext();
            }
        }
        set_var("v_comboBox_comisionista", $combobox_comisionista);
        
        //----------------------------------------------------------------------------------------------------
        //----------------------------------------------------------------------------------------------------
        // Cargamos el comboBOX de TIPO ENCOMIENDAS
        //----------------------------------------------------------------------------------------------------
        $q = "select codigo, tipo_encomienda, precio, aplica_descuentos from tipos_de_encomiendas te where te.activa='S'";
        $res = ejecutar_sql($db, $q);
        if (!$res) {
            echo $db->ErrorMsg(); //die();
        } else {
            $combobox_tipo_encomeinda = "<option value=0>Todas...</option>";
            while (!$res->EOF) {
                if ($b_tipo_encomienda == $res->fields[0]) {
                    $combobox_tipo_encomeinda = $combobox_tipo_encomeinda . "<option value=".$res->fields[0] . " selected=True >" . $res->fields[1] ." - ( $ ".$res->fields[2]." ) </option>";
                }else{
                    $combobox_tipo_encomeinda = $combobox_tipo_encomeinda . "<option value=".$res->fields[0] . ">" . $res->fields[1] ." - ( $ ".$res->fields[2]." ) </option>";
                }
                $res->MoveNext();
            }
        }
        set_var("v_comboBox_tipo_encomienda", $combobox_tipo_encomeinda);        
       
        // Completamos las demas variables que se cargaran en la busqueda
        set_var("v_nro_operacion",			" "); 
	set_var("v_fecha_op",				" ");
	set_var("v_remitente",				" ");     
	set_var("v_dni_remitente",			" ");
	set_var("v_domicilio_remitente",	" ");
	set_var("v_tel_remitente",			" ");
	set_var("v_destinatario",			" ");  
	set_var("v_dni_destinatario",		" ");
	set_var("v_domicilio_destinatario",	" ");
	set_var("v_tel_destinatario",		" ");						
	set_var("v_tipo_encomienda",		" ");		
	set_var("v_prioridad",				" "); 			
	set_var("v_fecha_entregal",			" ");		
	set_var("v_precio",					" ");  				
	set_var("v_comisionista",			" ");			
	set_var("v_estado",					" ");
	set_var("v_personal",				" ");								 				
	set_var("v_localidad_remitente",	" ");  
	set_var("v_localidad_destinatario",	" ");  
	set_var("v_nro_factura",			" ");     
	set_var("v_en_ctacte",				" ");  

        //------------------------------------------------------
        //                Resumen de totales
        set_var("v_total_procesados",0.00);
                        
        set_var("v_total_comisiones_comisionista",0.00);
        set_var("v_total_comisiones_sucursal",0.00);
                        
        set_var("v_total_seguro",0.00);
	set_var("v_total_seguro_cobro_destino",0.00);
		
        set_var("v_total_importe_contrareembolso",0.00);
        set_var("v_total_importe_contrareembolso_cobro_destino",0.00);
				
        set_var("v_total_comision_contrareembolso",0.00);
        set_var("v_total_comision_contrareembolso_cobro_destino",0.00);

        set_var("v_total_comisiones_comisionista_cobro_destino",0.00);
        set_var("v_total_comisiones_sucursal_cobro_destino",0.00);
		
        set_var("v_total_procesados_cobro_destino",0.00);
		
	set_var("v_total1_1",'0.00');
	set_var("v_total1_2",'0.00');
	set_var("v_total2_1",'0.00');
	set_var("v_total2_2",'0.00');
	set_var("v_total3",  '0.00');
						
                                
        //------------------------------------------------------
        //         Resumen de totales de procesados
        set_var("v_total_procesados_procesados",             0.00);                       

        set_var("v_total_comisiones_comisionista_procesados",0.00);
        set_var("v_total_comisiones_sucursal_procesados",    0.00);
                        
        set_var("v_total_seguro_procesados",                0.00);
	set_var("v_total_seguro_cobro_destino_procesados",  0.00);
		
        set_var("v_total_importe_contrareembolso_procesados",0.00);
        set_var("v_total_importe_contrareembolso_cobro_destino_procesados",0.00);
				
        set_var("v_total_comision_contrareembolso_procesados",0.00);
        set_var("v_total_comision_contrareembolso_cobro_destino_procesados",0.00);

        set_var("v_total_comisiones_comisionista_cobro_destino_procesados",0.00);
        set_var("v_total_comisiones_sucursal_cobro_destino_procesados",0.00);
		
        set_var("v_total_procesados_cobro_destino_procesados",0.00);
		
	set_var("v_total1_1_procesados",'0.00');
	set_var("v_total1_2_procesados",'0.00');
	set_var("v_total2_1_procesados",'0.00');
	set_var("v_total2_2_procesados",'0.00');
	set_var("v_total3_procesados",'0.00');
        
        $v_total = 0;				

        parse('listadoencomiendas');
        parse('listadoencomiendas_procesadas');

        desconectar($db);
        pparse("encomiendas");

?>
