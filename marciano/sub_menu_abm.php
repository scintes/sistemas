<?php
    include_once("seguridad.php");
    include_once("template.php");
    include_once("conexion.php");

    // Cargamos las variables del menu principal
	set_file("sub_menu_abm","sub_menu_abm.html");
		set_var("fecha",date("d/m/Y"));
		set_var("visor",'...Principal...');
		set_var("logo_proyecto",'./imagenes/logo.jpg');
		set_var("titulo_proyecto",'Marciano Tourd SRL');

                set_var('v_imagen_fondo', IMAGEN_FONDO);
//------------------------------------------------------------------------------                
//  Seccion para versionado del sistema
//------------------------------------------------------------------------------                
                set_var('v_sis_version', SIS_VERSION);
                set_var('v_fecha_actualizacion_sistema',FECHA_MODI_SISTEMA);
//------------------------------------------------------------------------------                
    //-------------------------------------------------------------------------    
    // buscamos las secciones del menu     
    // Se verifica en "pantalla_permisos" si se tiene la siguiente combinacion:
    // 
    //-------------------------------------------------------------------------    
    $db = conectar_al_servidor();    
    $id = $_SESSION['id_usuario'];  
    
    $sql = 'select pp.id_seccion, pp.id_pantalla, ps.nombre, pi.link, pi.etiqueta, pi.icono'
            .' from pantallas_permisos as pp '
            .' inner join pantallas_secciones as ps on ps.codigo=pp.id_seccion and ps.activo="S" and ps.nivel=1'
            .' left join pantallas_items as pi on pi.codigo=pp.id_pantalla and pi.activo="S"'												
            .' where pp.id_usuario='.$id.' '
            .' ORDER BY pp.id_seccion, pp.id_pantalla';
    
    $res = ejecutar_sql($db, $sql);
    
    if (!$res) {
        echo $db->ErrorMsg(); //die();
    } else {
        $secciones = "";
        $secciones_id = array();
        $res->MoveFirst();
                
        //----------------------------------------------------------------------
        // Buscamos solo las secciones del menu 
        //----------------------------------------------------------------------
        while (!$res->EOF){ 
            // verificamos que si hay un id_seccion y No un ID_pantalla cargado
            if(($res->fields['id_seccion']>=0)&&(!$res->fields['id_pantalla'])){// si es solo una seccion
                $secciones = $secciones . "<a href='#'>".$res->fields['nombre']."</a>";
                $secciones_id[] = $res->fields['id_seccion']; 
            }
            $res->MoveNext();
        }        
        
        //----------------------------------------------------------------------
        // Buscamos solo las ITEM del menu 
        //----------------------------------------------------------------------                
        $item_pantalla = "";
        
        foreach ($secciones_id as $i => $value){
            //mysql_data_seek($res, 0);
            //unset($i);
            $item_pantalla = $item_pantalla.'<ul>';                
            $res->MoveFirst();           
            while (!$res->EOF) {
                $ii = $value."==".$res->fields['id_seccion']."&&".$res->fields['id_pantalla'];
                if(($value==$res->fields['id_seccion'])&&($res->fields['id_pantalla']>-1)){
                    $item_pantalla = $item_pantalla.' <li> <a href="'.$res->fields['link'].'"> <img src="'.$res->fields['icono'].'" alt="'.$value.'" click=$this.href> <h4> '.$res->fields['etiqueta'].' </h4></a></li> ';                 
                }
                $res->MoveNext();
            }
            $item_pantalla = $item_pantalla.'</ul>';
        }  
        unset($secciones_id);
        // $item_pantalla = $item_pantalla.'</ul>';
        
    }
    set_var("v_sub_menu_secciones", $secciones    );    
    set_var("v_sub_menu_items_abm", $item_pantalla);    
    set_var("v_visor_de_accion", "");

                
                
                
//------------------------------------------------------------------------------                
 set_file("pie_de_pagina", "pie_paginas.html");
        set_var("v_logo_proyecto",              SIS_LOGO); // nro de guia
        set_var("v_acerca_de",                  SIS_ACERCAR_DE); // nro de guia
        set_var("v_usuario",                    $_SESSION['usuario']);
        set_var('v_titulo_proyecto',            SIS_PROYECTO);
        set_var('v_sis_version',                SIS_VERSION);
        set_var('v_fecha_actualizacion_sistema',SIS_FECHA_MODI_SISTEMA);
        set_var('v_icono_sistema',              SIS_ICON_PROYECTO);
        set_var("v_sis_pagina",SIS_PAGINA);
        set_var("v_sucursal",                   $_SESSION['sucursal'] );
        set_var("v_imagen_tipo_usu",            $_SESSION['imagen']   );        
	// Mostramos en el orden deseado
  pparse("sub_menu_abm");
  pparse("pie_de_pagina");

?>

