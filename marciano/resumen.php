<?php
	include_once("seguridad.php");
	include_once("template.php");
	include_once("conexion.php");
	include_once("funciones.php");

	
	
	$fecha_desde  		= dar_fecha(); //$_REQUEST['e_fecha_desde'];
	$fecha_hasta 		= dar_fecha(); //$_REQUEST['e_fecha_hasta'];
	
	set_file("resumen","resumen.html");
            set_var("v_b_fecha_desde", $fecha_desde); // fecha_desde
            set_var("v_b_fecha_hasta", $fecha_hasta); // fecha_hasta
            set_var("v_sucursal", $_SESSION['sucursal']); // fecha_hasta
            set_var("v_vendedor",$_SESSION['usuario']  );
            set_var("v_cant_reg",0); // Indica la cantidad de registros encontrados.
            		

        $db = conectar_al_servidor();
        
        //----------------------------------------------------------------------------------------------------
        //----------------------------------------------------------------------------------------------------
        // Cargamos el comboBOX de COMISIONISTAS
        //----------------------------------------------------------------------------------------------------
        $q = "select u.id, u.nombre, u.usuario 
              from usuarios u inner join tipos_de_usuarios tu on (u.id_tipo_usuario=tu.codigo) and (tu.codigo=3)";
        $res = ejecutar_sql($db, $q);
        if (!$res) {
            echo $db->ErrorMsg(); //die();
        } else {
            $combobox_comisionista = "<option value=-1>Seleccione un informe</option>";    
            $combobox_comisionista = $combobox_comisionista . "<option value=0>Solo de Sucursales...</option>";    
            while (!$res->EOF) {
                $combobox_comisionista = $combobox_comisionista . "<option value=".$res->fields[0]."> Por el comisionista:<b> ".  strtoupper($res->fields[1]) . "</b></option>";                
                $res->MoveNext();
            }
            $combobox_comisionista =  $combobox_comisionista . "<option value=9999>Completo...</option>";    
        }

        set_var("v_comboBox_comisionista", $combobox_comisionista);   
		set_var('v_resumen_tabla','');
        pparse("resumen");
            		
?>

