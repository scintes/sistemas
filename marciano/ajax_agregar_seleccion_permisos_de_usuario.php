<?php
//------------------------------------------------------------------------------
// Insertamos los registros en la siguientes tabla:
//          1) PANTALLA_PERMISOS
//------------------------------------------------------------------------------
include_once("seguridad.php");
include_once("template.php" );
include_once("conexion.php" );
include_once("funciones.php");

$id        = $_POST["id"];    // El valor que llega es entero positivo unico siendo el id de usuario
$datos = $_POST["datos"]; // valor seleccionado en el listado de pantallas a asignar por el usuario

$campos = '( id_usuario, id_seccion, id_pantalla )';

if(!$id){ // si no hay usuario seleccionado sale sin hacer nada.
    echo "Debe seleccionar un usuario...";
}else{  // si ingresa con un usuario seleccionado vemos lo de secciones y pantallas.    
    
    $vl_p = explode(",", $datos);// pasamos el listado de pantalla (string) a un arreglo
   
    $tam_p = sizeof($vl_p);
    
    $i = 0; //
    $datos =  '';
    
        foreach ($vl_p as $lp) { // por cada pantalla
            $lsp   = explode('-', $lp);
            $i++;
            if(($lsp[0])&&($lsp[1])){
                if($tam_p==$i){
                    $datos = $datos.'('.$id.','.$lsp[0].','.$lsp[1].')';
                }else{
                    $datos = $datos.'('.$id.','.$lsp[0].','.$lsp[1].'),';                
                }
            }
        }
        
    }
    
    $db = conectar_al_servidor();
    if($db){
        $sql = 'insert into pantallas_permisos '.$campos.' value '.$datos.';'; 
        $res = ejecutar_sql($db, $sql);
        
        if($res){
            echo 'Inserción con exito!';
            //    echo $sql;//"Pago registrado con exito...";          
        }else{
            echo 'Se produjo un error en la asignación de permisos!!!'; //"Error al escribir en la base...";
        }
        desconectar($db);
    }

die();

?>  