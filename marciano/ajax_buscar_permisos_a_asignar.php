<?php
// Buscamos los permisos segun el usuario solicitado 
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");


$db = conectar_al_servidor();

// obtenemos los datos pasados por parametros por ajax
$id = $_POST["id"];
/* 
$sql = "SELECT pi.codigo, pi.etiqueta, pi.icono, pi.hint, ps.nombre AS seccion, CONCAT(pi.id_seccion,'-',pi.codigo) AS idsp
        FROM pantallas_items AS pi         
            INNER JOIN pantallas_secciones AS ps ON (pi.id_seccion = ps.codigo AND pi.id_seccion = ps.codigo)
        WHERE pi.activo = 'S' AND  ps.Activo = 'S' AND pi.codigo not in (select pp.id_pantalla from pantallas_permisos as pp where pp.id_usuario = ".$id.")
        ORDER BY pi.etiqueta ASC";
*/
$sql= "SELECT pi.codigo, pi.etiqueta, pi.icono, pi.hint, ps.nombre AS seccion, CONCAT(pi.id_seccion,'-',pi.codigo) AS idsp
FROM pantallas_items AS pi         
		INNER JOIN pantallas_secciones AS ps ON (pi.id_seccion = ps.codigo)
LEFT JOIN pantallas_permisos AS pp ON (pp.id_pantalla=pi.codigo)and(pp.id_usuario=".$id.")
where pp.id_pantalla is null
ORDER BY pi.etiqueta ASC";





$res = ejecutar_sql($db,$sql);

if (!$res){  
    mensaje('Error accediendo a la tabla Pantalla items...');    
}else{
    //--------------------------------------------------------------------------
    // Listado de los permisos otorgados de un usuario determinado
    //--------------------------------------------------------------------------
    $p = '';       
    while (!$res->EOF){                
        // $pasaje = NRO_ASIENTO|(PAGADO+FORMA_VIAJE+POSEE_SEGURO)|
        // Nro Asiento	Nombre	Dirección	Tel	Importe
        $p = $p.$res->fields['icono']."|".$res->fields['codigo']."|".$res->fields['etiqueta']."|".$res->fields['hint'].
                   "|".$res->fields['seccion']."|".$res->fields['idsp'].'@';
        
        $res->MoveNext();
        
    };      
};
echo $p; // Icono, Codigo, Etiqueta, Hint, Seccion
desconectar($db);
die();
?>