<?php
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

$db = conectar_al_servidor();

// parametros recibidos desde el javascript 
$id_vehiculo = base64_decode( $_REQUEST["code"],$strict); // patente del vehiculo seleccionado.
$id_viaje    = base64_decode( $_REQUEST["id_v"],$strict); // clave unica del viaje seleccionado para agregar el vehiculo.
$c_ao        = base64_decode( $_REQUEST["c_ao"],$strict); // cantidad de asientos ocupados
$c_ar        = base64_decode( $_REQUEST["c_ar"],$strict); // cantidad de asientos reservados
$destino     = base64_decode( $_REQUEST["destino"],$strict); // cantidad de asientos reservados
$fecha       = base64_decode( $_REQUEST["fecha"],$strict); // fecha del viaje
$hora        = base64_decode( $_REQUEST["hora"],$strict); // hora del viaje

// Consulta sql. Trae todos los vehiculos cargados al sistema que se encuentran con activos (campo activo = 'S')
$sql = "SELECT v.PATENTE, v.NOMBRE, v.INTERNO, v.MODELO, v.NRO_ASIENTOS, v.FECHA_VENCIMIENTO_TECNICA, 
               v.COLUMNA_PB_11, v.COLUMNA_PB_12, v.COLUMNA_CENTRAL_PB, v.COLUMNA_PB_21, v.COLUMNA_PB_22,  
               v.COLUMNA_PA_11, v.COLUMNA_PA_12, v.COLUMNA_CENTRAL_PA, v.COLUMNA_PA_21, v.COLUMNA_PA_22, v.DOBLE_PISO  
        FROM vehiculos AS v
        WHERE v.activo='S' ";
        

set_file("seleccion_vehiculo", "seleccion_vehiculo.html");

$res = ejecutar_sql($db, $sql);

$datos ='';
$vehiculos= '';

$imagen1 = './imagenes/sinasientos.jpg';
$imagen2 = './imagenes/sinasientos.jpg';
$imagen3 = './imagenes/sinasientos.jpg';
$imagen4 = './imagenes/sinasientos.jpg';
$imagen5 = './imagenes/sinasientos.jpg';
$imagen6 = './imagenes/sinasientos.jpg';
$imagen7 = './imagenes/sinasientos.jpg';
$imagen8 = './imagenes/sinasientos.jpg';
$imagen9 = './imagenes/sinasientos.jpg';
$imagen10 = './imagenes/sinasientos.jpg';

set_var('v_patente',       '');
set_var('v_nombre',        '');
set_var('v_interno',       '');
set_var('v_modelo',        '');
set_var('v_nro_asientos',  '');
set_var('v_fecha_tecnica', '');

//set_var('v_id_viaje',$id_viaje);
set_var("v_cant_asientos_ocupados", $c_ao);
set_var("v_cant_asientos_reservado", $c_ar);
set_var("v_destino", $destino);

if (!$res){
    mensaje('Error accediendo al listado de vehiculos...');
}else{
    if ($id_vehiculo==0){
       $datos =  "<option value=-1 selected=True >Seleccione un vehiculo</option>";
    }
    while (!$res->EOF){        
        if ($id_vehiculo==$res->fields[0]){ 
            $datos = $datos."<option value=".$res->fields[0]." selected=True >".$res->fields[1]."</option>";
            
            set_var('v_patente',       $res->fields[0]);
            set_var('v_nombre',        $res->fields[1]);
            set_var('v_interno',       $res->fields[2]);
            set_var('v_modelo',        $res->fields[3]);
            set_var('v_nro_asientos',  $res->fields[4]);
            set_var('v_fecha_tecnica', cambiaf_a_normal($res->fields[5]));
            
            //--------------------------------------------------------------
            // Planta BAJA
            //--------------------------------------------------------------
            if($res->fields[6] =='S') {$imagen1 = './imagenes/asientos.jpg';} else {$imagen1='./imagenes/sinasientos.jpg';}
            if($res->fields[7] =='S') {$imagen2 = './imagenes/asientos.jpg';} else {$imagen2='./imagenes/sinasientos.jpg';}
            if($res->fields[8] =='S') {$imagen3 = './imagenes/asientos.jpg';} else {$imagen3='./imagenes/sinasientos.jpg';}
            if($res->fields[9] =='S') {$imagen4 = './imagenes/asientos.jpg';} else {$imagen4='./imagenes/sinasientos.jpg';}
            if($res->fields[10]=='S') {$imagen5 = './imagenes/asientos.jpg';} else {$imagen5='./imagenes/sinasientos.jpg';}
            
            //--------------------------------------------------------------
            // Planta Alta 
            //--------------------------------------------------------------
            if ($res->fields[16]=='S'){ // si es un segundo piso.
                if($res->fields[11]=='S'){$imagen6  = './imagenes/asientos.jpg';} else {$imagen6='./imagenes/sinasientos.jpg'; }
                if($res->fields[12]=='S'){$imagen7  = './imagenes/asientos.jpg';} else {$imagen7='./imagenes/sinasientos.jpg'; }
                if($res->fields[13]=='S'){$imagen8  = './imagenes/asientos.jpg';} else {$imagen8='./imagenes/sinasientos.jpg'; }
                if($res->fields[14]=='S'){$imagen9  = './imagenes/asientos.jpg';} else {$imagen9='./imagenes/sinasientos.jpg'; }
                if($res->fields[15]=='S'){$imagen10 = './imagenes/asientos.jpg';} else {$imagen10='./imagenes/sinasientos.jpg';}
            } else {
                $imagen6='./imagenes/sinasientos.jpg'; 
                $imagen7='./imagenes/sinasientos.jpg'; 
                $imagen8='./imagenes/sinasientos.jpg'; 
                $imagen9='./imagenes/sinasientos.jpg'; 
                $imagen10='./imagenes/sinasientos.jpg';                
            }
            
        }else{                   
            $datos = $datos . "<option value=".$res->fields[0].">".$res->fields[1]."</option>";
        }
        
        // guardamos la informacion de los vehiculos cargados en la tabla con la estructura <dato1>@<dato2>@<dato ..n> y cada dato <dato1.a>|<dato1b>|<dato1..n> 
        $vehiculos =  $vehiculos."@".$res->fields[0].'|'.$res->fields[1].'|'.$res->fields[2].'|'.$res->fields[3]
                      .'|'.$res->fields[4].'|'.$res->fields[5].'|'.$res->fields[6].'|'.$res->fields[7]
                      .'|'.$res->fields[8].'|'.$res->fields[9].'|'.$res->fields[10].'|'.$res->fields[11]
                      .'|'.$res->fields[12].'|'.$res->fields[13].'|'.$res->fields[14].'|'.$res->fields[15].'|';
        $res->MoveNext();
    }; // fin while  
};

set_var('v_id_viaje', $id_viaje);


set_var("v_color_cabezera_tabla",    COLOR_ENCOMIENDAS_CABEZERA_TABLA);
set_var("v_color_cabezera_columna",  COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);

set_var('v_vehiculo', $datos);
set_var('v_datos_vehiculos', $vehiculos);

set_var('v_asiento_usado', './imagenes/asientos.jpg');
set_var('v_asiento_no_usado', './imagenes/sinasientos.jpg');

set_var('v_imagen1',  $imagen1);
set_var('v_imagen2',  $imagen2);
set_var('v_imagen3',  $imagen3);
set_var('v_imagen4',  $imagen4);
set_var('v_imagen5',  $imagen5);
set_var('v_imagen6',  $imagen6);
set_var('v_imagen7',  $imagen7);
set_var('v_imagen8',  $imagen8);
set_var('v_imagen9',  $imagen9);
set_var('v_imagen10', $imagen10);

set_var('v_patente_sel',       '');
set_var('v_nombre_sel',        '');
set_var('v_interno_sel',       '');
set_var('v_modelo_sel',        '');
set_var('v_nro_asientos_sel',  '');
set_var('v_fecha_tecnica_sel', '');
set_var("v_fecha_viaje",$fecha);
set_var("v_hora_viaje",$hora);

parse ('seleccion_vehiculo');
pparse('seleccion_vehiculo');

desconectar($db);
?>
