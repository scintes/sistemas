<?php
/* *****************************************************************************
// Muestra el pie de pagina con los datos de informacion para el usuario
 ******************************************************************************/
include_once("seguridad.php");
include_once("conexion.php");
include_once("template.php");




set_file("pie_de_pagina", "pie_paginas.html");

    set_var("v_logo_proyecto",              SIS_LOGO              );
    set_var("v_acerca_de",                  SIS_ACERCAR_DE        );
    
    set_var('v_titulo_proyecto',            SIS_PROYECTO          );
    set_var('v_sis_version',                SIS_VERSION           );
    set_var('v_fecha_actualizacion_sistema',SIS_FECHA_MODI_SISTEMA);
    set_var('v_icono_sistema',              SIS_ICON_PROYECTO     );
    set_var("v_sis_pagina",                 SIS_PAGINA            );
    
    set_var('v_mostrar_botonera',           true);
    
    set_var("v_usuario",                    $_SESSION['usuario']  );
    set_var("v_sucursal",                   $_SESSION['sucursal'] );
    set_var("v_imagen_tipo_usu",            $_SESSION['imagen']   );
    


pparse("pie_de_pagina");



?>
 