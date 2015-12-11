<?php
//------------------------------------------------------------------------------
// Insertamos los registros en las siguientes tablas:
//          1) ENCOMIENDA
//          2) DETALLE_ENCOMIENDAS
//          3) CHEQUES
//          4) PAGOS
//          5) CTACTES
//          6) DESTINOS_COMUNES_CLIENTES
//------------------------------------------------------------------------------
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

$res = $_REQUEST['b_aceptar'];
/*$fecha_desde = $_REQUEST['e_fecha_desde'];
$fecha_hasta = $_REQUEST['e_fecha_hasta'];*/

$nro_orden              = $_REQUEST['e_nro_orden'];
$fecha                  = $_REQUEST["e_fecha"];            // fecha
$estado                 = $_REQUEST["e_estado"];           // estado encomienda 
$nro_factura            = $_REQUEST["e_nro_factura"];      // nro factura      */

$dni_remitente          = $_REQUEST["e_dni_remitente"];    // DNI remitente
$remitente              = $_REQUEST["e_remitente"];        // Remitente
$dir_remitente          = $_REQUEST["e_dir_remitente"];    // DNI remitente
$loc_remitente          = $_REQUEST["e_loc_remitente"];    // localidad remitente 
$tel_remitente          = $_REQUEST["e_tel_remitente"];    // tel_remitente

$dni_destinatario       = $_REQUEST["e_dni_destinatario"];  // dni destinatario
$destinatario           = $_REQUEST["e_destinatario"];            //destinatario
$dir_destinatario       = $_REQUEST["e_dir_destinatario"]; // direccion_destinatario
$loc_destinatario       = $_REQUEST["e_loc_destinatario"];  // localidad destinatario
$tel_destinatario       = $_REQUEST["e_tel_destinatario"]; // tel_destinatario

$prioridad              = $_REQUEST["e_prioridad"];               // prioridad 			

$fecha_entrega          = $_REQUEST["e_fecha_entrega"];       // fecha_entrega	
$hora_entrega_desde     = $_REQUEST["e_hora_entrega_desde"]; // Hora desde que se puede entregar la encomienda.
$hora_entrega_hasta     = $_REQUEST["e_hora_entrega_hasta"]; // Hora hasta que se puede entregar la encomienda.	

$porcentaje_seguro      = PORCENTAJE_SEGURO_ENCOMIENDA;              // Porcentaje seguro declarado
$personal               = $_SESSION['usuario'];              // usuario de carga							
$comis_comisionista     = $_REQUEST["e_comi_comi"]; // comision_comisionista
$comis_sucursal         = $_REQUEST["e_comi_suc"];  // comision_sucursal
$iva                    = $_REQUEST["e_iva"]; // Importe del iva 
$porcentaje_iva         = PORCENTAJE_IVA_ENCOMIENDA; // porcentaje del iva 
$importe_seguro         = $_REQUEST["e_importe_seguro"]; // importe seguro  
$precio                 = $_REQUEST["e_precio"];                 // precio encomienda 				
$observaciones          = $_REQUEST["e_observaciones"]; // Observaciones realizadas.
$detalle_enc            = $_REQUEST["e_detalle_encomienda"]; // Detalle de encomienda cargadas
$detalle_pagos          = $_REQUEST["e_pagos"]; // Detalle de pago de la encomienda.
$destinos_comun         = $_REQUEST["e_destinos_comun"]; // Detalle del destino.



$encomienda_asegurada   = ""; 
$con_aviso_restorno     = ""; 


$hora                       = dar_hora();
$id_suc                     = $_SESSION['id_sucursal'];
$id_viaje                   = '0';
$id_salida                  = '0';
$id_factura                 = '0';
$pagada                     = 'N';
$id_ctacte                  = '0';
$cantidad                   = 1;
$importe_contra_reembolso   = 0;
$comis_contra_reembolso     = 0;
$id_tipo_cliente            = '';
$entregada                  = 'N';
$id_encomienda              = 0;    


// ---------------- Area de control de datos ------------- //
 // tipo_encomienda
if ($precio == '')              {$precio = 0.00;}                 
if ($comis_comisionista == '')  {$comis_comisionista = 0.00;} // precio encomienda 				
if ($comis_sucursal == '')      {$comis_sucursal = 0.00;}         // precio encomienda 				
if ($importe_seguro == '')      {$importe_seguro = 0.00;}         // precio encomienda 				 

//                
//       precio encomienda 				
// 
//  Debe tener frio (True o false)
if (isset($_REQUEST["e_debetener_frio"])) {
    if ($_REQUEST["e_debetener_frio"] == true) {$debe_tener_frio = 'S'; } else { $debe_tener_frio = 'N'; }
} else { $debe_tener_frio = 'N';}

if (isset($_REQUEST["e_encomienda_asegurada"])) {
    if ($_REQUEST["e_encomienda_asegurada"] == true) { $encomienda_asegurada = 'S';  } else {  $encomienda_asegurada = 'N';  }
} else { $encomienda_asegurada = 'N';}

if (isset($_REQUEST["e_con_aviso_de_retorno"])) {
    if ($_REQUEST["e_con_aviso_de_retorno"] == true) { $con_aviso_retorno = 'S'; } else { $con_aviso_retorno = 'N';}
} else {$con_aviso_retorno = 'N';}

$db = conectar_al_servidor_e_iniciar_transaccion();

$tabla = 'encomiendas';
$campos = " FECHA,  HORA,  DESTINATARIO,  REMITENTE,  FECHA_ENTREGA,  HORA_ENTREGA_DESDE,  HORA_ENTREGA_HASTA,
            DIRECCION_REMITENTE,  DIRECCION_DESTINO,  DEBE_TENER_FRIO,  OBSERVACIONES,
 USUARIO,  ENTREGADA,  CON_AVISO_DE_RETORNO,  IMPORTE_SEGURO,  IMPORTE_CTRA_REEMBOLSO,  COMISION_CTRA_REEMBOLSO,
 ID_LOCALIDAD_REMITENTE,  ID_LOCALIDAD_DESTINATARIO,  CONTRAREEMBOLSO,  TEL_ORIGEN,  TEL_DESTINO,  CUIT_ORIGEN,
 CUIT_DESTINO,  PAGADA,  PRECIO,  COMISION_COMISIONISTA,  COMISION_SUCURSAL,  CANTIDAD,  PRIORIDAD,  IVA, PORCENTAJE_SEGURO, ESTADO";


$datos	=  ' "'.cambiaf_a_mysql($fecha).'","'.
           $hora.'","'.
           $destinatario . '","'.
           $remitente . '","'.
           cambiaf_a_mysql($fecha_entrega) . '","'.
           $hora_entrega_desde . '","'.
           $hora_entrega_hasta . '","'.
        
           $dir_remitente .'","'.
           $dir_destinatario .'","'.
           $debe_tener_frio . '","'.
           $observaciones . '","'.
        
           $personal . '","'.
           $entregada.'","'.
           $con_aviso_retorno .'",'. 
	   $importe_seguro.','.
           $importe_contra_reembolso . ','.
           $comis_contra_reembolso.',"'.
           
           $loc_remitente.'","'.
           $loc_destinatario.'","'.
           $es_contrareembolso.'","'.
           $tel_remitente.'","'.
           $tel_destinatario.'","'.
           $dni_remitente.'","'.

           $dni_destinatario.'","'.
           $pagada.'",'.
           $precio.','.
           $comis_comisionista.','.
           $comis_sucursal.','.
           $cantidad.',"'.
           $prioridad.'","'.
           $porcentaje_iva.'","'.
           $porcentaje_seguro.'","'.
           $estado.'" ';

 $id = ejecutar_sql_y_dar_id_con_transaccion($db, $tabla,$campos, $datos);
 echo $id;

 
 
 /* -------------------------------------------------------------------------------------------------------
  *                                Escritura de DETALLE de ENCOMIENDA
  * --------------------------------------------------------------------------------------------------------*/

 if ($id>0) {
    $id_encomienda = $id;
    //************************************************************************** 
    //   Insertamos el Detalle del pago 
    //************************************************************************** 
    $sql_i = "INSERT INTO detalle_encomiendas (id_encomienda, item, cantidad, descripcion, precio_unitario, id_comisionista, comision_sucursal, comision_comisionista, id_tipo_encomienda) VALUES ";    
    
    $det_enc = explode(",", $detalle_enc);    
    $det_enc = str_replace('$', '', $det_enc);
    $det_enc = str_replace(' ', '', $det_enc);      
    $j = 0;
    $cant_campo = 9; // el nro 9 es por los campos que devuelveel array $detalle_enc 
    for ($i=0; $i<sizeof($det_enc); $i += $cant_campo) {           
            $j++;
                     //     id_encomienda   item       cantidad            descripcio
            $sql_i = $sql_i . " (".$id.",".$j.",".$det_enc[3 + $i].",'".$det_enc[4 + $i]."',".
                     //Precio Unitario     id_comisionista    comisionista_sucursal   Comision comisionista    id_tipo_encomienda
                     $det_enc[5 + $i].",".$det_enc[1 + $i].",".$det_enc[6 + $i].",".$det_enc[7 + $i].",".$det_enc[8 + $i].")";
            
            if ($j < (sizeof($det_enc) / $cant_campo) ){
                $sql_i = $sql_i . ",";               
            }
    }   
    $res = ejecutar_sql($db,$sql_i);

    //**************************************************************************
    //                              Escritura del  PAGO
    //**************************************************************************
    //  $sql_b = "INSERT INTO pagos (importe, forma_pago, fecha, id_cheque, id_encomienda)VALUES";   
    $det_pa = explode(",", $detalle_pagos);  
    
    $cant_campo2 = 10;
    $cant_campo3 = 5;
    $cant_campo4 = 5;
    
    $id_cheque = "";
    $id_ctacte = "";
    
    $cheque_campos = " nro_cheque, id_banco, importe, fecha_emision, fecha_cobro, operacion_ingreso, propio, propietario, entregado_por, eliminado ";    
    $pago_campos   = " importe, forma_pago, fecha, id_cheque, id_encomienda ";
    $ctacte_campos = " id_cliente, id_encominda, id_pago, fecha ";
    //**************************************************************************
    // Entrada: [cadena1,cadena2,...]
    //           cadena1 : tipo pago|dato1|dato2|...
    //           dato1: detalle de pago|importe|datos adicion1|dato adicion2|...
    // *************************************************************************
    for ($k=0; $k<=sizeof($det_pa)-1; $k += 1) {           
            $det_pago = explode('|',$det_pa[$k]);
        for ($i=0; $i<=sizeof($det_pago)-1; $i += $cant_campo2) {           
         
            //******************************************************************
            //completamos el registro de pago con los ID de cheque si corresponde
            // ------------------------------------------------------------------
            if ($det_pago[0 + $i]==2){ // si es igaul a CHEQUE (2)
                // armamos el sql para el registro de pago
                //                         nro_cheque            id_banco               importe             fecha_emision
                $cheque_datos = $det_pago[4 + $i].",".$det_pago[8 + $i].",".$det_pago[1 + $i].",'".$det_pago[2 + $i]."','".
                //                         fecha_cobro       operacion_ingreso          propio              propietario
                                     $det_pago[3 + $i]."','".$det_pago[5 + $i]."','".$det_pago[9 + $i]."','".$det_pago[6 + $i]."','".
                //                        entregado_por  eliminado  
                                     $det_pago[7 + $i]."','N'";
                $id_cheque = ejecutar_sql_y_dar_id_con_transaccion($db, 'cheques',$cheque_campos,$cheque_datos); // obtenemos el ID del cheque   
        
//                print_r('tabla: cheques  -> campos:'.$cheque_campos.'      -> datos:'.$cheque_datos.'<br>');
//                print_r('-------------------------------<br>');
                
            }else{
                $id_cheque = 'NULL';                
            } // fin del IF  de CHEQUES      
            // -----------------------------------------------------------------
            //------ Fin de la carga del cheque --------------------------------
            //------------------------------------------------------------------

            //------------------------------------------------------------------
            // Insertamos el REGISTRO DE PAGO         
            // CAMPOS ---->       importe,             forma_pago,            fecha,    id_cheque, id_encomienda 
            $pagos_datos = $det_pago[1 + $i].",".$det_pago[0 + $i].", CURRENT_DATE, ".$id_cheque.",".$id;
            $id_pago = ejecutar_sql_y_dar_id_con_transaccion($db,'pagos',$pago_campos, $pagos_datos);
            
//            print_r('tabla: pagos    -> campos: '.$pago_campos.'    -> datos: '. $pagos_datos.'<br>');
//            print_r('-------------------------------<br>');
            
            //--------------------------------------------------------------------------------
            // Se ejecuta despues, ya que se necesita el id de pago para el insert de CTA CTE
            if ($det_pago[0 + $i]==3){ // si es igaul a Cta. Cte (3)
                // armamos el sql para el registro de pago
                //                  id_cliente  id_encomienda  id_pago    fecha_emision
                $ctacte_datos = $det_pago[1 + $i].",".$id.",".$id_pago.",CURRENT_DATE";
                $id_ctacte = ejecutar_sql_y_dar_id_con_transaccion($db, 'ctactes', $ctacte_campos, $ctacte_datos); // obtenemos el ID del ctacte   

//                print_r('ctactes  '.$ctacte_campos.$ctacte_datos."<br>");
//                print_r('-------------------------------<br>');
                
            }else{
                $id_ctacte = '';
            }             
       }
    }    
    // $res3=ejecutar_sql($db, $sql_b); 
    //$id_pago = ejecutar_sql_y_dar_id_con_transaccion($db,'pagos',$pagos_campos, $pagos_datos);
}

//------------------------------------------------------------------------------
// Escribe el destino del cliente en la tabla  DECTINO_COMUNES_CLIENTES
//------------------------------------------------------------------------------
$det_destino_comun = explode("|", $destinos_comun);  

$sql_aux = "INSERT INTO destinos_comunes_clientes (id_cliente, nombre, direccion, tel, dni, id_localidad)
                VALUES (".$det_destino_comun[0].",'". // Codigo de cliente
                         $det_destino_comun[2]."','". // Nombre 
                         $det_destino_comun[3]."','". // Direccion
                         $det_destino_comun[4]."','". // TEL
                         $det_destino_comun[1]."',". // DNI                         
                         $det_destino_comun[6].");"; // Localidad
$res4 = ejecutar_sql($db, $sql_aux);

commit_transaccion($db); 
?>  