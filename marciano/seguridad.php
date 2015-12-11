<?php
    //iniciamos la sesión 
    session_name("loginUsuario"); 
    session_start(); 
   // include_once("../conexion.php");
    /* Establecemos que las paginas no pueden ser cacheadas */
    header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");      


//antes de hacer los cálculos, compruebo que el usuario está logueado 
//utilizamos el mismo script que antes 
    /*
if ($_SESSION["autentificado"] != "SI") { 
    //si no está logueado lo envío a la página de autentificación 
    header("Location:login.php"); 
} else { 

    //sino, calculamos el tiempo transcurrido 
    $fechaGuardada = $_SESSION["ultimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 

    //comparamos el tiempo transcurrido 
     if($tiempo_transcurrido >= 600) { 
     //si pasaron 10 minutos o más 
      session_destroy(); // destruyo la sesión 
      header("Location: login.php"); //envío al usuario a la pag. de autenticación 
      //sino, actualizo la fecha de la sesión 
    }else { 
    $_SESSION["ultimoAcceso"] = $ahora; 
   } 
} 
*/
    
if ($_SESSION["autentificado"] != "SI") { 
    //si no está logueado lo envío a la página de autentificación 
    header("Location:login.php"); 
}else{ 
    include_once 'conexion.php';
    //sino, calculamos el tiempo transcurrido 
    $fechaGuardada = $_SESSION["ultimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 
    //comparamos el tiempo transcurrido 
    if($tiempo_transcurrido >= TIEMPO_DE_SESION){         
      //si pasaron 10 minutos o más       
      logOut();      
      header("Location:login.php"); //envío al usuario a la pag. de autenticación 
      //sino, actualizo la fecha de la sesión 
    }else{ 

        if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']){
          //si pasaron 10 minutos o más       
          logOut();
          header("Location:login.php"); //envío al usuario a la pag. de autenticación 
        }else{            
            if ($_SESSION['ipaddress'] != $_SERVER["REMOTE_ADDR"]){
              //si pasaron 10 minutos o más       
              logOut();
              header("Location:login.php"); //envío al usuario a la pag. de autenticación 
            }else{
                $_SESSION["ultimoAcceso"] = $ahora;          
                //echo $_SESSION['ipaddress']."   ---  ".$_SERVER["REMOTE_ADDR"]. $ahora; die();
            }        
        }
    } 

}

?>
