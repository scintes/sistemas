<?php

include_once ("seguridad.php");
include_once ("template.php");
include_once ("conexion.php");
include_once ("funciones.php");

include_once('./html2fpdf/html2pdf.class.php');

ob_start();

$w      = $_REQUEST['sql_where'];
$tipo   = $_REQUEST['tipo'];
$id_suc = $_SESSION['id_sucursal'];

if ($tipo==true){
    set_var("v_mostrar_sin_procesados", 'none');
    set_var("v_mostrar_procesados", 'inline');
}else{
    set_var("v_mostrar_sin_procesados", 'inline');
    set_var("v_mostrar_procesados", 'none');
}



//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//                       Cargamos el membrete
//------------------------------------------------------------------------------
set_var("v_path_logo",LOGO);
set_var("v_razon_social",RAZON_SOCIAL);
set_var("v_direc_razon_social",DIREC_RAZON_SOCIAL);
set_var("v_tel_razon_social",TEL_RAZON_SOCIAL);

set_file("imprimir_cierre_encomiendas", "imprimir_cierre_encomienda.html");
set_var("v_b_dni_destinatario", " ");

set_var("v_b_fecha_desde", $fecha_desde);
// fecha_desde
set_var("v_b_fecha_hasta", $fecha_hasta);
// fecha_hasta
set_var("v_b_nro_orden", $b_nro_orden);
set_var("v_b_dni_remitente", $b_dni_remitente);
set_var("v_b_dni_destinatario", $b_destinatario);

set_var("v_sucursal", $_SESSION['sucursal']);
// fecha_hasta
set_var("v_total_ctacte", 0.00);
// sumatoria de cta cte
set_var("v_sucursal", $_SESSION['sucursal']);
// sumatoria de cta cte
set_var("v_vendedor", $_SESSION['usuario']);
// fecha de impresion
set_var("v_fecha", gmDate(" d/m/Y - H:i") );
$db = conectar_al_servidor();


// Indica la cantidad de registros encontrados.
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
//                       MUESTRA TODOS LOS REGISTROS DE LAS ENCOMIENDAS sin procesar
//----------------------------------------------------------------------------------------------------
$sql = "select  DISTINCT
                e.NRO_GUIA,
		e.FECHA,
		e.REMITENTE,
		e.CUIT_ORIGEN,
		e.DIRECCION_REMITENTE,
		e.TEL_ORIGEN,
		e.DESTINATARIO,
		e.CUIT_DESTINO,
		e.DIRECCION_DESTINO,
		e.TEL_DESTINO,
		de.DESCRIPCION as  tipo_encomienda, /* 10 */
		e.PRIORIDAD,
		e.FECHA_ENTREGA,
		e.PRECIO,
		c.usuario AS comisionista,
		e.ESTADO,
		e.USUARIO,
		lr.localidad AS Localidad_remitente,
		ld.localidad AS Localidad_destinatario,
		CONCAT(f.Nro_SUCURSAL,'-',f.NRO_FACTURA) AS nro_factura,
                /* indica si estamos en presencia de una encomienda adeudada por pago en destinatario */
                if( 4 in (select p.forma_pago from pagos p where p.id_encomienda=e.NRO_GUIA), 1,0) AS deuda
		
        from encomiendas AS e
                Inner Join detalle_encomiendas AS de ON (e.NRO_GUIA = de.id_encomienda)and(e.ELIMINADO='N')
                Inner Join usuarios AS c ON (c.id = de.id_comisionista)
		Inner Join localidades AS lr ON (e.ID_LOCALIDAD_REMITENTE = lr.codigo)
		Inner Join localidades AS ld ON (e.ID_LOCALIDAD_DESTINATARIO = ld.codigo)
		Left Join usuarios AS u ON (e.ID_COMISIONISTA = u.id)
		Left Join facturas AS f ON (e.ID_FACTURA = f.CODIGO)";

		$res = ejecutar_sql($db, $sql . $w);
		$v_total = 0;

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
            // vemos que color pintar la fila
            if ($res -> fields[20] == 1) {
		set_var("v_color_fila", COLOR_ENCOMIENDAS_FILA_TIPO_PAGO_EN_DESTINO);
            } else {
		set_var("v_color_fila", COLOR_ENCOMIENDAS_FILA_COMUN);
            }
            set_var("v_nro_operacion", $res -> fields[0]);
            set_var("v_fecha_op", cambiaf_a_normal($res -> fields[1]));
            set_var("v_remitente", $res -> fields[2]);
            set_var("v_destinatario", $res -> fields[6]);
            set_var("v_tipo_encomienda", $res -> fields[10]);
            set_var("v_prioridad", $res -> fields[11]);
            set_var("v_fecha_entregal", cambiaf_a_normal($res -> fields[12]));
            set_var("v_precio", $res -> fields[13]);
            set_var("v_comisionista", $res -> fields[14]);
            set_var("v_estado", $res -> fields[15]);
            set_var("v_personal", $res -> fields[16]);
            set_var("v_localidad_remitente", $res -> fields[17]);
            set_var("v_localidad_destinatario", $res -> fields[18]);
            set_var("v_nro_factura", $res -> fields[19]);
            /* set_var("v_en_ctacte",				$res->fields[20]);  */

            parse('imprimir_listadoencomiendas');
            
            $v_total = $v_total + ($res -> fields[19]);
            $res -> MoveNext();
	}// fin del while

		//-----------------------------------------------------------------------------------
		//-----------------------------------------------------------------------------------
		// <<<<< MOstramos los totales de la consulta.  que NO son cobro en destino  >>>>>
		//-----------------------------------------------------------------------------------
		$sql = "SELECT
                            Sum(de.cantidad * de.PRECIO_UNITARIO) AS total,
                            Sum(COALESCE(e.IMPORTE_SEGURO,0)) AS total_importe_seguro,
                            Sum(COALESCE(e.IMPORTE_CTRA_REEMBOLSO,0)) AS total_importe_contra_reembolso,
                            Sum(COALESCE(e.COMISION_CTRA_REEMBOLSO,0)) AS total_comision_contra_reembolso,
                            Sum(COALESCE(de.COMISION_COMISIONISTA,0)) AS total_comision_comisionista,
                            Sum(COALESCE(de.COMISION_SUCURSAL,0)) AS total_comision_sucursal
                        FROM encomiendas AS e 
                            INNER JOIN detalle_encomiendas AS de ON (e.NRO_GUIA = de.id_encomienda)and(e.ELIMINADO='N')
                            INNER JOIN pagos AS p ON (p.id_encomienda=e.nro_guia)and(p.forma_pago<>4)";

		$total = ejecutar_sql($db, $sql . $w);

		set_var("v_total_procesados", $total->fields[0]);
		set_var("v_total_comisiones_comisionista", $total->fields[4]);
		set_var("v_total_comisiones_sucursal", $total->fields[5]);
		set_var("v_total_seguro", $total->fields[1]);
		set_var("v_total_importe_contrareembolso", $total->fields[2]);
		set_var("v_total_comision_contrareembolso", $total->fields[3]);
		
		//-----------------------------------------------------------------------------------
		//-----------------------------------------------------------------------------------
		// <<<<< MOstramos los totales de la consulta.  que son cobro en destino  >>>>>
		//-----------------------------------------------------------------------------------		
		$sql = "SELECT
                            Sum(de.cantidad * de.PRECIO_UNITARIO) AS total,
                            Sum(COALESCE(e.IMPORTE_SEGURO,0)) AS total_importe_seguro,
                            Sum(COALESCE(e.IMPORTE_CTRA_REEMBOLSO,0)) AS total_importe_contra_reembolso,
                            Sum(COALESCE(e.COMISION_CTRA_REEMBOLSO,0)) AS total_comision_contra_reembolso,
                            Sum(COALESCE(de.COMISION_COMISIONISTA,0)) AS total_comision_comisionista,
                            Sum(COALESCE(de.COMISION_SUCURSAL,0)) AS total_comision_sucursal
                        FROM encomiendas AS e 
				INNER JOIN detalle_encomiendas AS de ON (e.NRO_GUIA = de.id_encomienda)and(e.ELIMINADO='N')
                                INNER JOIN pagos AS p ON (p.id_encomienda=e.nro_guia)and(p.forma_pago=4)";

		$total2 = ejecutar_sql($db, $sql . $w);

		set_var("v_total_procesados_cobro_destino", $total2 -> fields[0]);

		set_var("v_total_comisiones_comisionista_cobro_destino", $total2 -> fields[4]);
		set_var("v_total_comisiones_sucursal_cobro_destino", $total2 -> fields[5]);

		set_var("v_total_seguro_cobro_destino", $total2 -> fields[1]);
		set_var("v_total_importe_contrareembolso_cobro_destino", $total2 -> fields[2]);
		set_var("v_total_comision_contrareembolso_cobro_destino", $total2 -> fields[3]);

		set_var("v_total1_1", $total2 -> fields[2] + $total2 -> fields[3] + $total -> fields[2] + $total -> fields[3]);
		set_var("v_total1_2", $total2 -> fields[1] + $total -> fields[1]);
		set_var("v_total2_1", $total2 -> fields[4] + $total2 -> fields[5]);
		set_var("v_total2_2", $total -> fields[4] + $total -> fields[5]);
		set_var("v_total3", $total2 -> fields[0] + $total -> fields[0]);

	} else {
		set_var("v_nro_operacion", " ");
		set_var("v_fecha_op", " ");
		set_var("v_remitente", " ");
		set_var("v_destinatario", " ");
		set_var("v_tipo_encomienda", " ");
		set_var("v_prioridad", " ");
		set_var("v_fecha_entregal", " ");
		set_var("v_precio", " ");
		set_var("v_comisionista", " ");
		set_var("v_estado", " ");
		set_var("v_personal", " ");
		set_var("v_localidad_remitente", " ");
		set_var("v_localidad_destinatario", " ");
		set_var("v_nro_factura", " ");
		set_var("v_en_ctacte", " ");
		//------------------------------------------------------
		//                Resumen de totales
		set_var("v_total_procesados", 0.00);
		set_var("v_total_comisiones_comisionista", 0.00);
		set_var("v_total_comisiones_sucursal", 0.00);
		set_var("v_total_seguro", 0.00);
		set_var("v_total_importe_contrareembolso", 0.00);
		set_var("v_total_comision_contrareembolso", 0.00);
		$v_total = 0;

		//------------------------------------------------------
		set_var("v_total_procesados_cobro_destino", 0.00);
		set_var("v_total_comisiones_comisionista_cobro_destino", 0.00);
		set_var("v_total_comisiones_sucursal_cobro_destino", 0.00);
		set_var("v_total_seguro_cobro_destino", 0.00);
		set_var("v_total_importe_contrareembolso_cobro_destino", 0.00);
		set_var("v_total_comision_contrareembolso_cobro_destino", 0.00);
                //------------------------------------------------------
		set_var("v_total1_1", 0.00);
		set_var("v_total1_2", 0.00);
		set_var("v_total2_1", 0.00);
		set_var("v_total2_2", 0.00);
		set_var("v_total3", 0.00);

		parse('imprimir_listadoencomiendas');
	}// fin del If cantidad

	set_var("v_total_ctacte", $v_total);

}// fin del else

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
//                       MUESTRA TODOS LOS REGISTROS DE LAS ENCOMIENDAS PROCESADAS
//----------------------------------------------------------------------------------------------------
$sql = "SELECT
            e.NRO_GUIA,
            e.FECHA,
            e.REMITENTE,
            e.CUIT_ORIGEN,
            e.DIRECCION_REMITENTE,
            e.TEL_ORIGEN,
            e.DESTINATARIO,
            e.CUIT_DESTINO,
            e.DIRECCION_DESTINO,
            e.TEL_DESTINO,
            de.DESCRIPCION as  tipo_encomienda,
            e.PRIORIDAD,
            e.FECHA_ENTREGA,
            e.PRECIO,
            c.usuario AS comisionista,
            e.ESTADO,
            e.USUARIO,
            lr.localidad AS Localidad_remitente,
            ld.localidad AS Localidad_destinatario,
            CONCAT(f.Nro_SUCURSAL,'-',f.NRO_FACTURA) AS nro_factura

	FROM encomiendas AS e
            Inner Join detalle_encomiendas AS de ON (e.NRO_GUIA = de.id_encomienda)and(e.ELIMINADO='N')
            Inner Join usuarios AS c ON (c.id = de.id_comisionista)
            Inner Join localidades AS lr ON (e.ID_LOCALIDAD_REMITENTE = lr.codigo)
            Inner Join localidades AS ld ON (e.ID_LOCALIDAD_DESTINATARIO = ld.codigo)
            Left Join usuarios AS u ON (e.ID_COMISIONISTA = u.id)
            Left Join facturas AS f ON (e.ID_FACTURA = f.CODIGO)";

//$w = " WHERE  e.estado='ENTREGADA' and e.fecha >= '" . cambiaf_a_mysql($fecha_desde) . "' and  e.fecha <= '" . cambiaf_a_mysql($fecha_hasta) . "'";
$res = ejecutar_sql($db, $sql . $w);
$v_total = 0;

//----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
if (!$res) {
	echo $db -> ErrorMsg();
	die();
} else {
	$cant = $res -> RecordCount();
        
	set_var("v_cant_reg_procesados",$cant);
	if ($cant >= 1) {
                // vemos que color pintar la fila
                set_var("v_color_fila", COLOR_ENCOMIENDAS_FILA_COMUN);
            
		while (!$res -> EOF) {                   
			set_var("v_nro_operacion",          $res -> fields[0]);
			set_var("v_fecha_op",               cambiaf_a_normal($res -> fields[1]));
			set_var("v_remitente",              $res -> fields[2]);
			set_var("v_destinatario",           $res -> fields[6]);
			set_var("v_tipo_encomienda",        $res -> fields[10]);
			set_var("v_prioridad",              $res -> fields[11]);
			set_var("v_fecha_entregal",         cambiaf_a_normal($res -> fields[12]));
			set_var("v_precio",                 $res -> fields[13]);
			set_var("v_comisionista",           $res -> fields[14]);
			set_var("v_estado",                 $res -> fields[15]);
			set_var("v_personal",               $res -> fields[16]);
			set_var("v_localidad_remitente",    $res -> fields[17]);
			set_var("v_localidad_destinatario", $res -> fields[18]);
			set_var("v_nro_factura",            $res -> fields[19]);
			set_var("v_en_ctacte",              $res -> fields[20]);
			parse('imprimir_listadoencomiendas_procesadas');
			$v_total = $v_total + ($res -> fields[19]);
			$res -> MoveNext();
		}// fin del while
		//------------------------------------------------------
		//------------------------------------------------------
		// <<<<< MOstramos los totales de la consulta. >>>>>
		//------------------------------------------------------
		$sql = "SELECT
                            Sum(de.cantidad * de.PRECIO_UNITARIO) AS total,
                            Sum(COALESCE(e.IMPORTE_SEGURO,0)) AS total_importe_seguro,
                            Sum(COALESCE(e.IMPORTE_CTRA_REEMBOLSO,0)) AS total_importe_contra_reembolso,
                            Sum(COALESCE(e.COMISION_CTRA_REEMBOLSO,0)) AS total_comision_contra_reembolso,
                            Sum(COALESCE(de.COMISION_COMISIONISTA,0)) AS total_comision_comisionista,
                            Sum(COALESCE(de.COMISION_SUCURSAL,0)) AS total_comision_sucursal
                        FROM encomiendas AS e 
                            INNER JOIN detalle_encomiendas AS de ON (e.NRO_GUIA = de.id_encomienda)and(e.ELIMINADO='N')
                            INNER JOIN pagos AS p ON (p.id_encomienda=e.nro_guia)";

		$total = ejecutar_sql($db, $sql . $w);

		set_var("v_total_procesados_procesados",               $total->fields[0]);
		set_var("v_total_comisiones_comisionista_procesados",  $total->fields[4]);
		set_var("v_total_comisiones_sucursal_procesados",      $total->fields[5]);
		set_var("v_total_seguro_procesados",                   $total->fields[1]);
		set_var("v_total_importe_contrareembolso_procesados",  $total->fields[2]);
		set_var("v_total_comision_contrareembolso_procesados", $total->fields[3]);
		

		set_var("v_total1_1_procesados",                       $total -> fields[2] + $total -> fields[3]);
		set_var("v_total1_2_procesados",                       $total -> fields[1]);
		set_var("v_total2_2_procesados",                       $total -> fields[4] + $total -> fields[5]);
		set_var("v_total3_procesados",                         $total -> fields[0]);

	} else {
		set_var("v_nro_operacion",           " ");
		set_var("v_fecha_op",                " ");
		set_var("v_remitente",               " ");
		set_var("v_destinatario",            " ");
		set_var("v_tipo_encomienda",         " ");
		set_var("v_prioridad",               " ");
		set_var("v_fecha_entregal",          " ");
		set_var("v_precio",                  " ");
		set_var("v_comisionista",            " ");
		set_var("v_estado",                  " ");
		set_var("v_personal",                " ");
		set_var("v_localidad_remitente",     " ");
		set_var("v_localidad_destinatario",  " ");
		set_var("v_nro_factura",             " ");
		set_var("v_en_ctacte",               " ");

		//------------------------------------------------------
		//                Resumen de totales
		set_var("v_total_procesados_cobro_destino_procesados",               '0.00');
		set_var("v_total_comisiones_comisionista_cobro_destino_procesados",  '0.00');
		set_var("v_total_comisiones_sucursal_cobro_destino_procesados",      '0.00');
		set_var("v_total_seguro_cobro_destino_procesados",                   '0.00');
		set_var("v_total_importe_contrareembolso_cobro_destino_procesados",  '0.00');
		set_var("v_total_comision_contrareembolso_cobro_destino_procesados", '0.00');

		set_var("v_total_procesados_procesados",               '0.00');
		set_var("v_total_comisiones_comisionista_procesados",  '0.00');
		set_var("v_total_comisiones_sucursal_procesados",      '0.00');
		set_var("v_total_seguro_procesados",                   '0.00');
		set_var("v_total_importe_contrareembolso_procesados",  '0.00');
		set_var("v_total_comision_contrareembolso_procesados", '0.00');

		
		
        set_var("v_total1_1_procesados",                                     '0.00');
		set_var("v_total1_2_procesados",                                     '0.00');
		set_var("v_total2_1_procesados",                                     0.00);
		set_var("v_total2_2_procesados",                                     0.00);
		set_var("v_total3_procesados",                                       0.00);                
                
		$v_total = 0;
                parse('imprimir_listadoencomiendas_procesadas');
	}// fin del If cantidad
}// fin del else
desconectar($db);

// set_var("v_sql_where",$w_sin_precesar.$q.$o);
// set_var("v_tipo",$tipo);




pparse("imprimir_cierre_encomiendas");

// Impresion en PDF

$htmlbuffer=ob_get_contents();
ob_clean();
try{ 
   $fecha = date("ymdhm");   
//   $html2pdf = new HTML2PDF('P', 'A4', 'es');   
   $html2pdf = new HTML2PDF('l','A4','es', false, 'ISO-8859-15', array(5, 5, 5, 10));
   $html2pdf->pdf->SetDisplayMode('fullpage'); 
   $html2pdf->writeHTML($htmlbuffer, isset($_GET['vuehtml']));
   $html2pdf->Output('./cierre_encomienda'.$fecha.'.pdf', 'I');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}        


?>

