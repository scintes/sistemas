<?php
//------------------------------------------------------------------------------
// Insertamos los registros en la siguientes tabla:
//          1) PANTALLA_PERMISOS
//------------------------------------------------------------------------------
include_once("seguridad.php");
include_once("template.php" );
include_once("conexion.php" );
include_once("funciones.php");

$id        = $_POST["id"];    // El valor que llega es entero positivo unico
$secciones = $_POST["secciones"]; // valor seleccionado por el usuario en el comobox de secciones
$pantallas = $_POST['pantallas']; // valor seleccionado por el usuario en el comobox de pantallas
$l_s       = $_POST["l_s"]; // Valor que llega es un string de nro enteros separados con coma, o -1
$l_p       = $_POST['l_p'];// Valor que llega es un string de una composicion seccion-nro pantalla. ambos valores son nro enteros, cada composicion esta separados con coma, o -1

//echo $id." - ".$secciones."  -  ".$pantallas."  @  ".$l_s."  #  ".$l_p; die();
//die();


$campos = '( id_usuario, id_seccion, id_pantalla )';
$datos =  '';


if(!$id){ // si no hay usuario seleccionado sale sin hacer nada.
    echo "Debe seleccionar un usuario...";
}else{  // si ingresa con un usuario seleccionado vemos lo de secciones y pantallas.    
    
    $vl_p = explode(",", $l_p);// pasamos el listado de pantalla (string) a un arreglo

    $tam_p = sizeof($vl_p);
    $tam_s = sizeof($vl_s);
    
    $i = 1; //
    $j = 1;
    if($secciones==-1){ 
        //----------------------------------------------------------------------
        // Si secciones =(-1) significa que selecciona todas las secciones.y por
        //  tal todas las pantallas de las mismas.        
        //----------------------------------------------------------------------     
        $vl_s = explode(",", $l_s); // Pasamos el listado de las secciones 
        foreach ($vl_s as $ls){
                if($tam_s==$j){
                    $datos = $datos.'('.$id.','.$ls[0].',"")';
                }else{
                    $datos = $datos.'('.$id.','.$ls[0].',""),';                
                }
        } 
        
        foreach ($vl_p as $lp) { // por cada pantalla
            $lsp   = explode('-', $lp);
            $i++;
            if(($lp[0])&&($lp[1])){
                if($tam_p==$i){
                    $datos = $datos.'('.$id.','.$lsp[0].','.$lsp[1].')';
                }else{
                    $datos = $datos.'('.$id.','.$lsp[0].','.$lsp[1].'),';                
                }
            }
        }

        
        
    }else{
        //----------------------------------------------------------------------
        // si secciones <>(-1) debe tener una seccion marcada y por tal una o 
        // todas las pantallas en caso que pantallas=(-1).
        //----------------------------------------------------------------------        
        if ($pantallas==-1){
           // echo "ola:::::".$l_p; die();
           // $l_p = explode(",", $l_p);// pasamos el listado en string a un arreglo        
           //  echo $l_p; die();
            foreach ($vl_p as $lp) { // por cada pantalla
                $lsp = explode('-', $lp);
                
                if(($lp[0])){// mientras exista eseccion 
                    if($secciones==$lsp[0]){
                        if($tam_p>$i){
                            $datos = $datos.'('.$id.','.$lsp[0].','.$lsp[1].'),';            
                        }else{
                            $datos = $datos.'('.$id.','.$lsp[0].','.$lsp[1].'),';                                    
                        }
                    }                    
                }
                
            }
            
            $datos = $datos.'('.$id.','.$secciones.',"NULL")';
            
        }else{
            $datos = $datos.'('.$id.','.$secciones.','.$pantallas.')';                        
        }
        
    }
    
    $db = conectar_al_servidor();
    if($db){
        $sql = 'insert into pantallas_permisos '.$campos.' value '.$datos.';'; 
        echo $sql; die();
        $res = ejecutar_sql($db, $sql);
        
        if($res){
            echo 'Inserción con exito!';
            //    echo $sql;//"Pago registrado con exito...";          
        }else{
            echo 'Se produjo un error en la asignación de permisos!!!'; //"Error al escribir en la base...";
        }
        desconectar($db);
    }
}
die();

?>  