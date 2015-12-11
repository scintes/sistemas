<?php
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

$db = conectar_al_servidor();

// parametros recibidos desde el javascript 
$id_chofer = base64_decode( $_REQUEST["code"],$strict); // patente del vehiculo seleccionado.
$id_guarda = base64_decode( $_REQUEST["code2"],$strict); // patente del vehiculo seleccionado.
$id_viaje  = base64_decode( $_REQUEST["id_v"],$strict); // clave unica del viaje seleccionado para agregar el vehiculo.
$destino   = base64_decode( $_REQUEST["destino"],$strict); // cantidad de asientos reservados
$fecha     = base64_decode( $_REQUEST["fecha"],$strict); // fecha del viaje
$hora      = base64_decode( $_REQUEST["hora"],$strict);   // hora del viaje     

// Consulta sql. Trae todos los vehiculos cargados al sistema que se encuentran con activos (campo activo = 'S')
$sql = "SELECT u.id, u.usuario, u.nombre, u.apellido, u.dni, u.fecha_tecnica 
        FROM usuarios AS u  
        WHERE u.id_tipo_usuario =  '".ID_CHOFER."' and u.activo='1'";
        

set_file("seleccion_choferes", "seleccion_choferes.html");

$res = ejecutar_sql($db, $sql);


$datos    = '';
$datos2   = '';
$vehiculos= '';
$chofer   = '';
$guarda   = '';

set_var('v_id_chofer',           $id_chofer);
set_var('v_nombre_chofer',       '');
set_var('v_apellido_chofer',     '');
set_var('v_fecha_tecnica_chofer','');
set_var('v_dni_chofer',          '');
set_var('v_fecha_tecnica_chofer_sel','  /  /    ');
set_var('v_fecha_tecnica_guarda_sel','  /  /    ');

set_var('v_id_guarda',           $id_guarda);
set_var('v_nombre_guarda',        '');
set_var('v_apellido_guarda',      '');
set_var('v_fecha_tecnica_guarda', '');
set_var('v_dni_guarda',           '');

if(!$res){
    mensaje('Error accediendo al listado del personal...');
}else{
    
    if ($id_chofer==0){
       $datos =  "<option value=-1 selected=True >Seleccione un chofer</option>";
    }
    if ($id_guarda==0){
       $datos2 =  "<option value=-1 selected=True >Seleccione un guarda</option>";
    }
    
    while (!$res->EOF){                
        // RESOLUCION de CHOFER
        if ($id_chofer==$res->fields[0]){ 
            $datos = $datos."<option value=".$res->fields[0]." selected=True >".$res->fields[3]." ".$res->fields[2]."</option>";
            
            set_var('v_id_chofer',              $res->fields[0]);
            set_var('v_nombre_chofer',          $res->fields[2]);
            set_var('v_apellido_chofer',        $res->fields[3]);
            set_var('v_dni_chofer',             $res->fields[4]);
            set_var('v_fecha_tecnica_chofer', cambiaf_a_normal($res->fields[5]));
            
        }else{                   
            $datos = $datos . "<option value=".$res->fields[0].">".$res->fields[3]." ".$res->fields[2]."</option>";
        }
        
        // RESOLUCION de GUARDIA
        if ($id_guardia==$res->fields[0]){ 
            $datos2 = $datos2."<option value=".$res->fields[0]." selected=True >".$res->fields[3]." ".$res->fields[2]."</option>";
            
            set_var('v_id_guarda',            $res->fields[0]);
            set_var('v_nombre_guarda',        $res->fields[2]);
            set_var('v_apellido_guarda',      $res->fields[3]);
            set_var('v_dni_guarda',           $res->fields[4]);
            set_var('v_fecha_tecnica', cambiaf_a_normal($res->fields[5]));
            
        }else{                   
            $datos2 = $datos2 . "<option value=".$res->fields[0].">".$res->fields[3]." ".$res->fields[2]."</option>";
        }
        
        // guardamos la informacion de los vehiculos cargados en la tabla con la estructura <dato1>@<dato2>@<dato ..n> y cada dato <dato1.a>|<dato1b>|<dato1..n> 
        $chofer =  $chofer."@".$res->fields[0].'|'.$res->fields[1].'|'.$res->fields[2].'|'.$res->fields[3].'|'.$res->fields[4].'|'.$res->fields[5].'|';
        // guardamos la informacion de los vehiculos cargados en la tabla con la estructura <dato1>@<dato2>@<dato ..n> y cada dato <dato1.a>|<dato1b>|<dato1..n> 
        $guarda =  $chofer;
        
        $res->MoveNext();
    }; // fin while  
};


set_var("v_datos_chofer",$chofer);
set_var("v_datos_guarda",$guarda);

set_var("v_destino", $destino);
set_var('v_id_viaje', $id_viaje);

set_var("v_color_cabezera_tabla",    COLOR_ENCOMIENDAS_CABEZERA_TABLA);
set_var("v_color_cabezera_columna",  COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);

set_var('v_chofer', $datos);
set_var('v_guarda', $datos2);

set_var('v_id_chofer_sel',  '');
set_var('v_nombre_chofer_sel',     '');
set_var('v_apellido_chofer_sel',   '');
set_var('v_dni_chofer_sel',        '');

set_var('v_id_guarda_sel',  '');
set_var('v_nombre_guarda_sel',     '');
set_var('v_apellido_guarda_sel',   '');
set_var('v_dni_guarda_sel',        '');

set_var('v_fecha_viaje', $fecha);
set_var('v_hora_viaje', $hora);



parse ('seleccion_choferes');
pparse('seleccion_choferes');

desconectar($db);
?>
