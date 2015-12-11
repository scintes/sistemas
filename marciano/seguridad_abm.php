<?php
    /* Establecemos que las paginas no pueden ser cacheadas 
    header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");   

    // Iniciamos la sesión 
    //session_name("loginUsuario");
    
    //session_start(); 
    
//antes de hacer los cálculos, compruebo que el usuario está logueado 
//utilizamos el mismo script que antes 
//if ($_SESSION["autentificado"] != "SI") { 
if (!isset($_SESSION['usuario'])){    
    //si no está logueado lo envío a la página de autentificación 
    header("Location:../login.php"); 
} else { 
    include_once '../conexion.php';
    //sino, calculamos el tiempo transcurrido 
    $fechaGuardada = $_SESSION["ultimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 
    
    $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['SKey'] = uniqid(mt_rand(), true);
    $_SESSION['IPaddress'] = ExtractUserIpAddress();
    $_SESSION['LastActivity'] = $_SERVER['REQUEST_TIME'];

    //comparamos el tiempo transcurrido 
     if($tiempo_transcurrido >= TIEMPO_DE_SESION) { 
        //si pasaron 10 minutos o más       
        logOut();
        header("Location:../login.php"); //envío al usuario a la pag. de autenticación 
        //sino, actualizo la fecha de la sesión 
    }else { 
        if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']){
          //si pasaron 10 minutos o más       
          logOut();
          header("Location:../login.php"); //envío al usuario a la pag. de autenticación 
        }else{
            if ($_SESSION['IPaddress'] != $_SERVER["REMOTE_ADDR"]){
                //si pasaron 10 minutos o más       
                logOut();              
                header("Location:../login.php"); //envío al usuario a la pag. de autenticación 
            }else{
                $_SESSION["ultimoAcceso"] = $ahora;                 
            }        
        }
   } 
} 
*/

    include_once '../seguridad.php';

?>
