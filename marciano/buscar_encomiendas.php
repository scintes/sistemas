<?php
	include_once("seguridad.php");
	include_once("template.php");
	include_once("conexion.php");
	include_once("funciones.php");
        
        require_once 'class.eyemysqladap.inc.php';
        require_once 'class.eyedatagrid.inc.php';


	set_file("menu","menu_principal.html");
            set_var("fecha",date("d/m/Y"));
            set_var("visor",'...Administrador de encomiendas...         [ '.$_SESSION['sucursal'].' ]');
	
	$fecha_desde  		= $_REQUEST['e_fecha_desde'];
	$fecha_hasta 		= $_REQUEST['e_fecha_hasta'];
	$b_nro_orden 		= $_REQUEST['e_nro_orden'];
	$b_direcciones          = $_REQUEST['e_direcciones'];
	$b_nombres              = $_REQUEST['e_nombres'];
	$b_dni_remitente        = $_REQUEST['e_dni_remitente'];
	$b_dni_destinatario     = $_REQUEST['e_dni_destinatario'];        
        $b_ver_eliminados       = $_REQUEST['e_ver_eliminados'];
        	
	set_var("v_b_dni_destinatario"," ");
            $id_suc = $_SESSION['id_sucursal'];	
 
        set_file("encomiendas","encomiendas.html");
            set_var("v_b_fecha_desde", $fecha_desde); // fecha_desde
            set_var("v_b_fecha_hasta", $fecha_hasta); // fecha_hasta
            set_var("v_b_nro_orden", $b_nro_orden);
            set_var("v_b_direcciones", $b_direcciones);
            set_var("v_b_nombres",$b_nombres);
            set_var("v_b_dni_remitente",$b_dni_remitente);
            set_var("v_b_dni_destinatario",$b_destinatario);

            set_var("v_sucursal", $_SESSION['sucursal']); // fecha_hasta
            set_var("v_total_ctacte", 0.00); // sumatoria de cta cte
            set_var("v_sucursal",$_SESSION['sucursal'] ); // sumatoria de cta cte
            set_var("v_vendedor",$_SESSION['usuario']  );
            set_var("v_ver_eliminados", $b_ver_eliminados );

        //----------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
	//                    MUESTRA TODOS LOS REGISTRA TODAS LAS ENCOMIENDAS ENCONTRADAS
	//----------------------------------------------------------------------------------------------------
        $db = conectar_al_servidor();

        //-----------------------------------------------------------------------------------------------------
        // select
        $se = " e.ELIMINADO,
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
		/*te.tipo_encomienda,*/
		e.PRIORIDAD,
		e.FECHA_ENTREGA,
		e.PRECIO,
		/*u.usuario AS comisionista,*/
		e.ESTADO,
		e.USUARIO,
		lr.localidad AS Localidad_remitente,
		ld.localidad AS Localidad_destinatario,
		CONCAT(f.Nro_SUCURSAL,'-',f.NRO_FACTURA)as nro_factura";
        
        //-----------------------------------------------------------------------------------------------------
        // From
        $fr = "encomiendas AS e
		Inner Join localidades AS lr ON (e.ID_LOCALIDAD_REMITENTE = lr.codigo)
		Inner Join localidades AS ld ON (e.ID_LOCALIDAD_DESTINATARIO = ld.codigo)
		/*Inner Join tipos_de_encomiendas AS te ON (e.ID_TIPO_CORRESPONDENCIA = te.codigo)*/
		/*Left Join usuarios AS u ON (e.ID_COMISIONISTA = u.id)*/
		Left Join facturas AS f ON (e.ID_FACTURA = f.CODIGO)       ";
        
        //-----------------------------------------------------------------------------------------------------
        // Where
        if ($b_ver_eliminados){
            $q = "e.fecha >= '".cambiaf_a_mysql($fecha_desde)."' and  e.fecha <= '".cambiaf_a_mysql($fecha_hasta)."'";
        }else{
            $q = "e.eliminado='N' and e.fecha >= '".cambiaf_a_mysql($fecha_desde)."' and  e.fecha <= '".cambiaf_a_mysql($fecha_hasta)."'";
        }
        //--------------------------------------------------------------------------------------------------
        // FILTRAMOS SEGUN LOS EDIT COMPLETADOS
        //--------------------------------------------------------------------------------------------------
        if (trim($b_nro_orden)!='')  
            $q = $q . " and (e.nro_guia=".$b_nro_orden.")";
  	if (trim($b_direcciones)!='') 
            $q = $q . " and ((e.DIRECCION_REMITENTE like '%".$b_direcciones."%')or(e.DIRECCION_DESTINO like '%".$b_direcciones."%'))";
  	if (trim($b_nombres)!='')     
            $q = $q . " and ((e.remitente like '%".$b_nombres."%')or(e.destinatario  like '%".$b_nombres."%'))";
        
        $db1 = new EyeMySQLAdap(HOST, USUARIO, PASSWORD, BASE);
        
        // Load the datagrid class
        $x = new EyeDataGrid($db1);
        
        // Set the query       
        $x->setQuery($se, $fr,'NRO', $q);
        
        // Show reset grid control
        //$x->showReset();

        // Mostrar checkbox para seleccion de filas
        // $x->showCheckboxes();
        // $x->showRadiobutton();
        // 
        // Mostrar numero de filas 
        // $x->showRowNumber();
        //----------------------------------------------------------------------
        // Damos formato a los datos a mostrar 
        $x->setColumnType('FECHA',         EyeDataGrid::TYPE_DATE,   'd M, Y', true); // Change the date format       
        $x->setColumnType('ELIMINADO',     EyeDataGrid::TYPE_ARRAY,  array('S' => 'Eliminado', 'N' => '', ''=>'')); // Convert db values to something better
        $x->setColumnType('FECHA_ENTREGA', EyeDataGrid::TYPE_DATE,   'd, M, Y', true); // Change the date format       
        $x->setColumnType('PRECIO',        EyeDataGrid::TYPE_DOLLAR, FALSE, array('Back' => '#c3daf9', 'Fore' => 'black'));
        
        //----------------------------------------------------------------------
        // Normalizamos las cabezeras de la tabla.
        $x->setColumnHeader('FECHA', 'Fecha emisión');
        $x->setColumnHeader('nro_factura', 'Nro. Factura');
        //----------------------------------------------------------------------
        //Ocultamos los campos no deseados.
        if (!USAR_ELIMINACION_VIRTUAL){
            $x->hideColumn('ELIMINADO');
        };
        $x->hideColumn('CUIT_ORIGEN');
	$x->hideColumn('TEL_ORIGEN');
        $x->hideColumn('DESTINATARIO');
        $x->hideColumn('CUIT_DESTINO');
        $x->hideColumn('DIRECCION_DESTINO');
        $x->hideColumn('TEL_DESTINO');
        $x->hideColumn('Localidad_remitente');
        $x->hideColumn('Localidad_destinatario');
                
        //----------------------------------------------------------------------
        // Mostramos los botones de ABM
        $x->addStandardControl(EyeDataGrid::STDCTRL_EDIT,      "preguntar_por_modificacion(%NRO_GUIA%, '%REMITENTE%', '%DESTINATARIO%')",EyeDataGrid::TYPE_ONCLICK);
        // Mostramos solo la eliminacion segun se configuro.
        if (USAR_ELIMINACION_VIRTUAL){
            $x->addStandardControl(EyeDataGrid::STDCTRL_DELETE_VIRTUAL, "preguntar_por_eliminacion('V', %NRO_GUIA%, '%REMITENTE%', '%DESTINATARIO%')",EyeDataGrid::TYPE_ONCLICK);
            $x->addStandardControl(EyeDataGrid::STDCTRL_RECUPERAR, "preguntar_por_recuperacion(%NRO_GUIA%, '%REMITENTE%', '%DESTINATARIO%')",EyeDataGrid::TYPE_ONCLICK);
        }else{
            $x->addStandardControl(EyeDataGrid::STDCTRL_DELETE, "preguntar_por_eliminacion('', %NRO_GUIA%, '%REMITENTE%', '%DESTINATARIO%')",EyeDataGrid::TYPE_ONCLICK);
        }
        //----------------------------------------------------------------------
        

//        $x->addStandardControl(EyeDataGrid::STDCTRL_IMPRESION, "preguntar_visualizacion(%NRO_GUIA%)",EyeDataGrid::TYPE_ONCLICK);
        $x->addStandardControl(EyeDataGrid::STDCTRL_IMPRESION, "preguntar_pdf(%NRO_GUIA%)",EyeDataGrid::TYPE_ONCLICK);
        
    
        
        //$x->showCreateButton("alert('Esto es lo que dice')", EyeDataGrid::TYPE_ONCLICK, 'Cerrar');
        //$x->showCreateButton2("./agregar_encomienda.php", EyeDataGrid::TYPE_HREF, 'Agregar', 'imprimir.png');
        
        desconectar($db);
        
        //--------------------------------------------------------------------------------------------------------------------
        set_file("pie","pie_pagina.html");
            set_var("v_usuario",$_SESSION['usuario']);	
            set_var("nombre_proyecto",'Marciano Tour SRL');	
        
//pparse("menu");
pparse("encomiendas");

$x->printTable();

//pparse("pie");	
?>

