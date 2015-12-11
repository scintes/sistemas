<?php
include_once("seguridad.php");
include_once("conexion.php");
$db = conectar_al_servidor();

// parametros recibidos desde el javascript 
$prov               = $_GET["code"]; // Provincia de origen para localizar las localidades de las misma.
$orig_dest          = $_GET["es_origen"]; // indica si estamos cargando las localidades TRUE =  origen o FALSE = Destino

$loc_orig           = $_SESSION['id_loc_orig_encom'];  // valor obtenido en LOGIN.php              
$loc_dest           = $_SESSION['id_loc_dest_encom']; // valor obtenido en LOGIN.php
/*
$sql = "select  cu.id_loc_destinatario_encomienda, cu.id_loc_remitente_encomienda, cu.id_provincia_origen_encomienda
        from conf_usuario cu where cu.codigo=".$id_conf_usuario;// $usu;
$aux = ejecutar_sql($db,$sql);
*/
$sql = "select codigo, codigo_postal, localidad from localidades l where l.id_provincia=".$prov;
$res = ejecutar_sql($db,$sql);

if (!$res){
    mensaje('Error accediendo a las localidades...');
}else{
    echo "<option value=0>Seleccione Uno...</option>"; 

    while (!$res->EOF){        
        if ($orig_dest==True){
        
            if ($loc_dest==$res->fields[0]){ 
                echo "<option value=".$res->fields[0]." selected=True >".$res->fields[2]." (".$res->fields[1].") "."</option>";
            }else{
                echo "<option value=".$res->fields[0].">".$res->fields[2]." (".$res->fields[1].") "."</option>";
            }
            
        }else{
            
            if ($loc_orig==$res->fields[0]){ 
                echo "<option value=".$res->fields[0]." selected=True >".$res->fields[2]." (".$res->fields[1].") "."</option>";
            }else{
                echo "<option value=".$res->fields[0].">".$res->fields[2]." (".$res->fields[1].") "."</option>";
            }            
            
        }
        
        $res->MoveNext();
    };   
    
};

desconectar($db);
?>
