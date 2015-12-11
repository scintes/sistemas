<?php

include_once ("seguridad.php");
include_once ("template.php");
include_once ("conexion.php");
include_once ("funciones.php");

include_once('./html2fpdf/html2pdf.class.php');


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//                       Cargamos el membrete
//------------------------------------------------------------------------------
set_var("v_path_logo",          LOGO              );
set_var("v_razon_social",       RAZON_SOCIAL      );
set_var("v_direc_razon_social", DIREC_RAZON_SOCIAL);
set_var("v_tel_razon_social",   TEL_RAZON_SOCIAL  );

set_file("imprimir_pasajes", "imprimir_lista_pasajero.html");
//------------------------------------------------------------------------------
// Valores obtenidos del archivo de configuracion CONEXION.PHP
//------------------------------------------------------------------------------
set_var("v_color_cabezera_tabla",         COLOR_ENCOMIENDAS_CABEZERA_TABLA);
set_var("v_color_cabezera_columna_tabla", COLOR_ENCOMIENDAS_CABEZERA_COLUMNA);
set_var("v_color_pie_tabla",              COLOR_ENCOMIENDAS_PIE_TABLA);
set_var("v_color_fila_pago_destino",      COLOR_ENCOMIENDAS_FILA_TIPO_PAGO_EN_DESTINO);
set_var('v_color_columna_remitente',      COLOR_ENCOMIENDAS_REMITENTE);
set_var('v_color_columna_destinatario',   COLOR_ENCOMIENDAS_DESTINATARIO);

//base64_decode($_REQUEST["id_viaje"],$strict); 
$id_viaje =  base64_decode($_REQUEST['id'],$strict);
$ord =       $_REQUEST['ord'];
$id_suc =    $_SESSION['id_sucursal'];

set_var("v_sucursal", $_SESSION['sucursal']);
set_var("v_vendedor", $_SESSION['usuario']);
set_var("v_fecha_impresion", date('l jS \of F Y h:i:s A'));

$db = conectar_al_servidor();

//----------------------------------------------------------------------------------------------------
// Indica la cantidad de registros encontrados.
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
//                       MUESTRA TODOS LOS REGISTROS DE LAS ENCOMIENDAS sin procesar
//----------------------------------------------------------------------------------------------------
$sql = "SELECT
                p.NRO_ASIENTO,
                p.NOMBRE_Y_APELLIDO,
                p.DNI,
                p.DIRECCION_ORIGEN,
                p.DIRECCION_DESTINO,
                p.TELEFONO_ORIGEN,
                p.CELULAR_ORIGEN,
                p.PAGADO,
                p.OBSERVACIONES,
                concat(ch1.apellido,' ',ch1.nombre) as chofer,
                concat(ch2.apellido,' ',ch2.nombre) as guarda,
                v.fecha,
                s.hora,
                vh.NOMBRE AS vehiculo,
                vh.patente,
                v.codigo
        FROM
            pasajes AS p inner join viajes AS v on(p.ID_VIAJE=v.CODIGO)
                         inner join salidas AS s on(v.ID_SALIDA=s.CODIGO) 
                         left join  usuarios AS ch1 on(ch1.id=v.id_chofer)
                         left join  usuarios AS ch2 on(ch2.id=v.id_guarda)
                         inner join vehiculos AS vh ON(vh.PATENTE=v.id_vehiculo) 
        WHERE (p.ID_VIAJE = ".$id_viaje.") ORDER BY ".$ord;



$res = ejecutar_sql($db, $sql);

//----------------------------------------------------------------------------------------------------
// Verificamos el resultado de la busqueda.
//----------------------------------------------------------------------------------------------------
if (!$res) {
    echo $db -> ErrorMsg();
    die();
} else {
    
    set_var("v_chofer_asignado",$res->fields[9]);       
    set_var("v_guarda_asignado",$res->fields[10]);
    set_var("v_fecha",cambiaf_a_normal($res->fields[11]) );
    set_var("v_hora",$res->fields[12]);
    set_var("v_vehiculo_asignado","(".$res->fields[14].")-".$res->fields[13]);
    set_var("v_nro_viaje",$res->fields[15]);

    $cant = $res -> RecordCount();
    
    set_var("v_cant_reg", $cant);
    
    if ($cant >= 1) {
        
        while (!$res -> EOF) {
            set_var("v_nro_asiento",         $res -> fields[0] );
            set_var("v_apellido_nombre",     $res -> fields[1]);
            set_var("v_nro_doc",             $res -> fields[2]);
            set_var("v_dir_origen",          $res -> fields[3]);
            set_var("v_dir_destino",         $res -> fields[4]);
            set_var("v_tel_origen",          $res -> fields[5]);
            set_var("v_cel_origen",          $res -> fields[6] );
            set_var("v_pagado",              $res -> fields[7] );
            set_var("v_observacion",         $res -> fields[8] );
            
            parse('listado_pasajes');           
            $res -> MoveNext();
	}// fin del while
        
        $i = $cant;
        
        // completamos los renglones para llegar al final de la hoja
        while($i <= 30){
            set_var("v_nro_asiento",         '' );
            set_var("v_apellido_nombre",     '' );
            set_var("v_nro_doc",             '' );
            set_var("v_dir_origen",          '' );
            set_var("v_dir_destino",         '' );
            set_var("v_tel_origen",          '' );
            set_var("v_cel_origen",          '' );
            set_var("v_pagado",              '' );
            set_var("v_observacion",         '' );            
            parse('listado_pasajes');           
            $i = $i + 1;
        } 
        
    } else {
            set_var("v_chofer_asignado","");       
            set_var("v_guarda_asignado","");
            set_var("v_fecha","");
            set_var("v_hora","");
            set_var("v_vehiculo_asignado","");
        
            set_var("v_nro_asiento",         '' );
            set_var("v_apellido_nombre",     '' );
            set_var("v_nro_doc",             '' );
            set_var("v_dir_origen",          '' );
            set_var("v_dir_destino",         '' );
            set_var("v_tel_origen",          '' );
            set_var("v_cel_origen",          '' );
            set_var("v_pagado",              '' );
            set_var("v_observacion",         '' );

    }// fin del If cantidad
parse('listado_pasajes');
}
desconectar($db);

// ob_start();

pparse("imprimir_pasajes");

//------------------------------------------------------------
//   *****************************************************
// ******************* Impresion en PDF ********************
//   *****************************************************
//------------------------------------------------------------
/*
$htmlbuffer=ob_get_contents();
ob_clean();
try{ 
    
   $fecha = date("ymdhm");   
   //$html2pdf = new HTML2PDF('P', 'A4', 'es');   
   $html2pdf = new HTML2PDF('l','A4','es', false, 'UTF-8', array(2, 2, 2, 2));
   $html2pdf->pdf->SetDisplayMode('fullpage'); 
   $html2pdf->writeHTML($htmlbuffer, isset($_GET['vuehtml']));
   //$html2pdf->Output('./listado_pasajero'.$fecha.'.pdf', 'I');
   $html2pdf->Output('./listado_pasajero.pdf', 'I');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}        
*/

?>

