<?php
include_once ("seguridad.php");
include_once ("template.php");
include_once ("conexion.php");
include_once ("funciones.php");

require_once 'class.eyemysqladap.inc.php';
require_once 'class.eyedatagrid.inc.php';



//------------------------------------------------------------------------------
// Valores obtenidos del archivo de configuracion CONEXION.PHP
//------------------------------------------------------------------------------
set_var("v_color_cabezera_tabla",         COLOR_ENCOMIENDAS_CABEZERA_TABLA);
set_var("v_color_cabezera_columna_tabla", COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);
set_var("v_color_pie_tabla",              COLOR_ENCOMIENDAS_PIE_TABLA);
set_var("v_color_fila_pago_destino",      COLOR_ENCOMIENDAS_FILA_TIPO_PAGO_EN_DESTINO);
set_var('v_color_columna_remitente',      COLOR_ENCOMIENDAS_REMITENTE);
set_var('v_color_columna_destinatario',   COLOR_ENCOMIENDAS_DESTINATARIO);


$fecha_desde =          $_REQUEST['e_fecha_desde'];
$fecha_hasta =          $_REQUEST['e_fecha_hasta'];
$b_nro_orden =          $_REQUEST['e_nro_orden'];
$b_comisionista =       $_REQUEST['e_comisionista'];
$b_dni_remitente =      $_REQUEST['e_dni_remitente'];
$b_dni_destinatario =   $_REQUEST['e_dni_destinatario']; 
$b_tipo_encomienda =    $_REQUEST['e_tipo_encomienda']; // ID_tipo de encomienda

set_file("encomiendas", "cierre_encomienda.html");



set_var("v_b_dni_destinatario", " ");
$id_suc = $_SESSION['id_sucursal'];


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

$db = conectar_al_servidor();

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de COMISIONISTAS
//----------------------------------------------------------------------------------------------------
$q = "select u.id, u.nombre, u.usuario from usuarios u inner join tipos_de_usuarios tu on 
     (u.id_tipo_usuario=tu.codigo) and (tu.codigo=3)";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db -> ErrorMsg();
    //die();
} else {
    $combobox_comisionista = "<option value=0>Todos...</option>";
    while (!$res -> EOF) {
        if ($b_comisionista == $res -> fields[0]) {
            $combobox_comisionista = $combobox_comisionista . "<option value=" . $res -> fields[0] . " selected=True >" . $res -> fields[1] . "</option>";
	} else {
            $combobox_comisionista = $combobox_comisionista . "<option value=" . $res -> fields[0] . ">" . $res -> fields[1] . "</option>";
	}
	$res -> MoveNext();
    }
}
set_var("v_comboBox_comisionista", $combobox_comisionista);
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
// Cargamos el comboBOX de TIPO ENCOMIENDAS
//----------------------------------------------------------------------------------------------------
$q = "select codigo, tipo_encomienda, precio, aplica_descuentos from tipos_de_encomiendas te where te.activa='S'";
$res = ejecutar_sql($db, $q);
if (!$res) {
    echo $db -> ErrorMsg();
} else {
    $combobox_tipo_encomeinda = "<option value=0>Todas...</option>";
    while (!$res -> EOF) {
        if ($b_tipo_encomienda == $res -> fields[0]) {
            $combobox_tipo_encomeinda = $combobox_tipo_encomeinda . "<option value=" . $res -> fields[0] . " selected=True >" . $res -> fields[1] . " - ( $ " . $res -> fields[2] . " ) </option>";
	} else {
            $combobox_tipo_encomeinda = $combobox_tipo_encomeinda . "<option value=" . $res -> fields[0] . ">" . $res -> fields[1] . " - ( $ " . $res -> fields[2] . " ) </option>";
	}
        $res -> MoveNext();
    }
}
set_var("v_comboBox_tipo_encomienda", $combobox_tipo_encomeinda);
set_var("v_cant_reg", 0);

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

$w_env = "where e.fecha >= '" . cambiaf_a_mysql($fecha_desde) . "' and  e.fecha <= '" . cambiaf_a_mysql($fecha_hasta) . "'"; 		
$w_sin_precesar = $w_env." and e.estado <> 'ENTREGADA'";
$w = $w_sin_precesar;
$q = '';

if (trim($b_nro_orden) != '')
    $q = $q . " and (e.nro_guia=" . $b_nro_orden . ")";

if (trim($b_comisionista) != 0)
    $q = $q . " and (de.ID_COMISIONISTA = " . $b_comisionista . ")";

if (trim($b_tipo_encomienda != 0))
    $q = $q . " and (de.id_tipo_encomienda = " . $b_tipo_encomienda . ")";

$o = ' ORDER BY e.NRO_GUIA';

$res = ejecutar_sql($db, $sql . $w . $q . $o);
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

            parse('listadoencomiendas');
            
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

		$total = ejecutar_sql($db, $sql . $w . $q);

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

		$total2 = ejecutar_sql($db, $sql . $w . $q);

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
		set_var("v_total3",   $total2 -> fields[0] + $total -> fields[0]);

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

		parse('listadoencomiendas');
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

$w = $w_env." and e.estado='ENTREGADA'";
$w_precesado = $w;
$res = ejecutar_sql($db, $sql . $w . $q . $o);
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
			parse('listadoencomiendas_procesadas');
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

		$total = ejecutar_sql($db, $sql . $w . $q);

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
		set_var("v_total_procesados_cobro_destino_procesados",               0.00);
		set_var("v_total_comisiones_comisionista_cobro_destino_procesados",  0.00);
		set_var("v_total_comisiones_sucursal_cobro_destino_procesados",      0.00);
		set_var("v_total_seguro_cobro_destino_procesados",                   0.00);
		set_var("v_total_importe_contrareembolso_cobro_destino_procesados",  0.00);
		set_var("v_total_comision_contrareembolso_cobro_destino_procesados", 0.00);
                set_var("v_total1_1_procesados",                                     0.00);
		set_var("v_total1_2_procesados",                                     0.00);
		set_var("v_total2_1_procesados",                                     0.00);
		set_var("v_total2_2_procesados",                                     0.00);
		set_var("v_total3_procesados",                                       0.00);                
                
		$v_total = 0;
                parse('listadoencomiendas_procesadas');
	}// fin del If cantidad
}// fin del else
desconectar($db);

set_var("v_sql_where",$w_sin_precesar.$q.$o);
set_var("v_sql_where_procesados",$w_precesado.$q.$o);

pparse("encomiendas");

?>

