<?php
	include_once("seguridad.php");
	include_once("template.php");
	include_once("conexion.php");
	include_once("funciones.php");
	
	require_once 'class.eyemysqladap.inc.php';
	require_once 'class.eyedatagrid.inc.php';


	$fecha_desde  	 = $_REQUEST['fd'];
	$fecha_hasta 	 = $_REQUEST['fh'];
	$id_comisionista = $_REQUEST['comi'];
	
	//echo $fecha_desde.$fecha_hasta.$id_comisionista;
	//die(); 

    set_file("resumen","resumen.html");
		set_var("v_b_fecha_desde", $fecha_desde); // fecha_desde
		set_var("v_b_fecha_hasta", $fecha_hasta); // fecha_hasta
	
	$id_suc = $_SESSION['id_sucursal'];	
    	set_var("v_sucursal", $_SESSION['sucursal']); // fecha_hasta
		set_var("v_sucursal",$_SESSION['sucursal'] ); // sumatoria de cta cte
		set_var("v_vendedor",$_SESSION['usuario']  );
		set_var("v_total_ctacte", 0.00); // sumatoria de cta	cte

    $fecha_desde = cambiaf_a_mysql($fecha_desde);
	$fecha_hasta = cambiaf_a_mysql($fecha_hasta);
	
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
            $combobox_comisionista = $combobox_comisionista . "<option value=0>Solo de Sucursales...</option>";    
            while (!$res->EOF) {
                $combobox_comisionista = $combobox_comisionista . "<option value=".$res->fields[0]."> Por el comisionista:<b> ".  strtoupper($res->fields[1]) . "</b></option>";                
                $res->MoveNext();
            }
            $combobox_comisionista =  $combobox_comisionista . "<option value=9999>Completo...</option>";    
        }

        set_var("v_comboBox_comisionista", $combobox_comisionista);   
     //----------------------------------------------------------------------------------------------------
  
        


	//----------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
	//                       MUESTRA TODOS LOS REGISTROS DE Las encomiendas
	//----------------------------------------------------------------------------------------------------
	$selec = " e.nro_guia, e.fecha, de.cantidad, de.descripcion, de.comision_comisionista, de.comision_sucursal";
	$from  = " encomiendas AS e inner join detalle_encomiendas AS de on (e.nro_guia=de.id_encomienda) left join usuarios AS u on (de.id_comisionista=u.id)";
	//$where = " (e.fecha  BETWEEN '".cambiaf_a_mysql($fecha_desde)."' and '".cambiaf_a_mysql($fecha_hasta)."') ";
	$where = " (e.fecha  BETWEEN '".$fecha_desde."' and '".$fecha_hasta."') ";
	if ($id_comisionista){
		$where = where + "and (de.id_comisionista=".$id_comisionista.")";	
	}

	$db2 = new EyeMySQLAdap(HOST, USUARIO, PASSWORD, BASE);
	$x2  = new EyeDataGrid($db2);

	//$x2->showRadiobutton();

	$x2->setQuery($selec, $from,'nro_guia', $where);
	
	set_var('v_resumen_tabla','');
	
	
pparse("resumen");

$x2->printTable()

?>

