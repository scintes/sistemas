<?php
    include_once("seguridad.php");
    include_once("template.php");
    include_once("conexion.php");
   
    // Cargamos las variables del menu principal
    set_file("menu","menu_principal.html");
        set_var("v_icono_proyecto", SIS_ICON_PROYECTO);
	set_var("v_titulo_proyecto",SIS_PROYECTO     );
        set_var('v_imagen_fondo',   SIS_IMAGEN_FONDO );
        
    //-------------------------------------------------------------------------    
    // buscamos las secciones del menu     
    // Se verifica en "pantalla_permisos" si se tiene la siguiente combinacion:
    // 
    //-------------------------------------------------------------------------    
    $db = conectar_al_servidor();    
    $id = $_SESSION['id_usuario'];  
    
    $sql = 'select pp.id_seccion, pp.id_pantalla, ps.nombre, pi.link, pi.etiqueta, pi.icono, pi.hint'
            .' from pantallas_permisos as pp '
            .'   inner join pantallas_secciones as ps on ps.codigo=pp.id_seccion and ps.activo="S" and ps.nivel=0 '
            .'   left join pantallas_items as pi on pi.codigo=pp.id_pantalla and pi.activo="S"'												
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
                // Buscamos todos los item del menu que tiene asociado al usuario en particular    
                if(($value==$res->fields['id_seccion'])&&($res->fields['id_pantalla'])){
                    $item_pantalla = $item_pantalla.'<li> '
                            . ' <a href="'.$res->fields['link'].'"> '
                            . '   <img '
//                            . '        onmouseover = "'.'function(){ $('."'".'#visor_de_hint'."'".').html('."'".$res->fields["hint"]."'".')};'.'" src="'.$res->fields['icono'].'" alt="'.$value.'"  click=$this.href> '
                            //      "function(){'.'document.getElementById("visor_de_hint").innerHTML='."'".$res->fields["hint"]."';".'};"
                            . '    onmouseOver="document.getElementById('."'".'visor_de_hint'."'".').innerHTML='."'".$res->fields["hint"]."'".'"'
                            . '    onmouseOut ="document.getElementById('."'".'visor_de_hint'."'".').innerHTML='."' '".' "'
                            . '    src="'.$res->fields['icono'].'" alt="'.$value.'"  click=$this.href> '
                            . '<h4> '.$res->fields['etiqueta'].' </h4>'
                            . '</a>'
                            . '</li> ';                 
                }
                
                $res->MoveNext();
            }
            $item_pantalla = $item_pantalla.'</ul>';
        }   
        // $item_pantalla = $item_pantalla.'</ul>';
        
    }
    set_var("v_menu_secciones", $secciones    );    
    set_var("v_menu_items",     $item_pantalla);  
    set_var("v_visor_de_accion", "");
    
    parse("menu");    
    pparse("menu");    
    include_once("pie_paginas.php");    
?>

