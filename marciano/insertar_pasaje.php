<?php
//------------------------------------------------------------------------------
// Insertamos los registros en las siguientes tablas:
//          1) PASAJE
//          2) CHEQUES
//          4) PAGOS
//          5) CTACTES
//          6) DESTINOS_COMUNES_CLIENTES
//          7) Pasajes_de_vueltas
//------------------------------------------------------------------------------
include_once("seguridad.php");
include_once("template.php");
include_once("conexion.php");
include_once("funciones.php");

//---------------------------------------------------------------------------------------------------------------
// modalidad de pasajes como parametros
//---------------------------------------------------------------------------------------------------------------
// // $listado_pasaje = "<pasaje 1>@<pasaje 2>@...@"
// pasaje_n = DNI| NOMBRE_Y_APELLIDO| ID_LOCALIDAD_ORIGEN| ID_LOCALIDAD_DESTINO| DIRECCION_ORIGEN|
//            DIRECCION_DESTINO| TELEFONO_ORIGEN| TELEFONO_DESTINO|CELULAR_ORIGEN| CELULAR_DESTINO| 
//            ID_TIPO_PASAJE| POSEE_SEGURO|DNI_PERSONA_SEGURO| NOMBRE_PERSONA_SEGURO| FECHA_NAC_PERSONA_SEGURO|
//            PRECIO| OBSERVACIONES|FORMA_VIAJE| PAGADO| FECHA| ID_VIAJE| NRO_ASIENTO| USUARIO|pago|
// pago = <pago 1>,<pago 2>,...
// pago n = tipo pago#monto pago######
//---------------------------------------------------------------------------------------------------------------

$lista_pasaje          = $_REQUEST['datos']; // datos de o los pasajes
$forma_salida          = $_REQUEST['fv']; // indica si es una salida diaria o especial
$id_viaje              = $_REQUEST['id_viaje'];
$tipo                  = $_REQUEST['tipo']; // indica si es una V:venta R: Reserva o U: Utilizacion de pasajes de vuelta
$detalle_pagos         = $_REQUEST["e_pagos"]; // Detalle de pago de los pasajes.
$destinos_comun        = $_REQUEST["e_destinos_comun"]; // Detalle del destino.
$usuario               = $_SESSION['usuario'];
$fecha_viaje           = $_REQUEST['fecha_viaje'];
$piv                   = $_POST['piv']; // Si esto es "S" escribe en la tabla de Pasajes_de_vueltas
$ddp                   = $_POST['ddp'];




$id_dat_ida = explode("#",$ddp);



$bandera_ejecucion_exitosa = 'V';

$campos = "DNI, NOMBRE_Y_APELLIDO, ID_LOCALIDAD_ORIGEN, ID_LOCALIDAD_DESTINO, DIRECCION_ORIGEN, DIRECCION_DESTINO, TELEFONO_ORIGEN, TELEFONO_DESTINO,
           CELULAR_ORIGEN, CELULAR_DESTINO, ID_TIPO_PASAJE, POSEE_SEGURO,DNI_PERSONA_SEGURO, NOMBRE_PERSONA_SEGURO, FECHA_NAC_PERSONA_SEGURO, PRECIO, OBSERVACIONES,          
           FORMA_VIAJE, PAGADO, FECHA, ID_VIAJE, NRO_ASIENTO, USUARIO, VALOR_SEGURO";


$lista = explode("@",$lista_pasaje); // generamos el arreglo de pasajes
//$det_pa = explode(",", $detalle_pagos);

$db = conectar_al_servidor_e_iniciar_transaccion();

if ($db){
    //$sql = "INSERT INTO pasajes (".$campos.") VALUES ";
    $imp_pasajs = '';    
    // INSERTAMOS LOS PASAJES Y LUEGO EL PAGO PERTINENTE
    for ($p=0; $p<=(sizeof($lista)-2); $p++) {           
        $j++;
        
        $pasaje = explode("|",$lista[$p]);    
        // DNI, NOMBRE_Y_APELLIDO, ID_LOCALIDAD_ORIGEN, ID_LOCALIDAD_DESTINO, DIRECCION_ORIGEN, 
        // DIRECCION_DESTINO, TELEFONO_ORIGEN, TELEFONO_DESTINO    
        $datos =       " '".$pasaje[1]."','".
                            $pasaje[2]."',".
                            $pasaje[3].",".
                            $pasaje[4].",'".
                            $pasaje[5]."','".
                            $pasaje[6]."','".
                            $pasaje[7]."','".
                            $pasaje[8]."','".
        // CELULAR_ORIGEN, CELULAR_DESTINO, ID_TIPO_PASAJE, POSEE_SEGURO,DNI_PERSONA_SEGURO, 
        // NOMBRE_PERSONA_SEGURO, ,FECHA_NAC_PERSONA_SEGURO, PRECIO, OBSERVACIONES        
                            $pasaje[9]."','".
                            $pasaje[10]."',".
                            $pasaje[11].",'".
                            $pasaje[12]."','".
                            $pasaje[13]."','".
                            $pasaje[14]."','".
                            cambiaf_a_mysql($pasaje[15])."',".
                            $pasaje[16].",'".
                            $pasaje[17]."','".
        // FORMA_VIAJE, PAGADO, FECHA, ID_VIAJE, NRO_ASIENTO,  USUARIO,  VALOR_SEGURO       
                            $forma_salida."','".
                            $tipo."',CURRENT_DATE,".
                            $id_viaje.",".
                            $pasaje[0].",'".
                            $usuario."',".
                            $pasaje[18];
     
  
        // generamos una lista de nro de asientos
        $imp_pasaje = $imp_pasajs.$pasaje[0]."|";
                
        $id_pasaje = ejecutar_sql_y_dar_id_con_transaccion($db, 'pasajes', $campos, $datos);
        
        //echo $id_pasaje; die();
        if ($id_pasaje <= 0) {
            $bandera_ejecucion_exitosa = 'F';
        }else{
            //---------------------------------------------------------------------------------------
            // como se pudo crear el pasaje se verifica si es un pasaje ida y vuelta.
            if (($piv=='S')&&($tipo!='U')){//si es un pasaje de ida y vuelta (piv='S' y no es un pasajes de vuelta tipo='U'
               // escribimos el pasaje de vuelta en la tabla pasajes_de_vuelta para poder usarlo en UTILIZAR en admin_pasajes.php.
                
                //$d =  strtotime('+'.PLAZO_PASAJES_IDA_VUELTA.' day',cambiaf_a_mysql($fecha_viaje));                
                $sql = "insert into pasajes_de_vuelta (id_pasaje_origen, fecha_vencimiento)
                        values(".$id_pasaje.", DATE_ADD(CURRENT_DATE(),INTERVAL ".PLAZO_PASAJES_IDA_VUELTA." DAY) )";
               ejecutar_sql($db, $sql);                
            }
            //---------------------------------------------------------------------------------------
        }
              // Si el registro de pasaje se efectuo correctamente y es un registro de venta
        if(($bandera_ejecucion_exitosa=='V')&&($tipo=='V')){
            // ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
            // ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
            // ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
            //**************************************************************************
            //                              Escritura del PAGO
            //**************************************************************************
            $det_pa = explode(",", $pasaje[19] ); // obtenemos el pago del pasaje  
            
            $cant_campo2 = 10;
            $cant_campo3 = 5;
            $cant_campo4 = 5;
    
            $id_cheque = "";
            $id_ctacte = "";
    
            $cheque_campos = " nro_cheque, id_banco, importe, fecha_emision, fecha_cobro, operacion_ingreso, propio, propietario, entregado_por, eliminado ";    
            $pago_campos   = " importe, forma_pago, fecha, id_cheque, id_pasaje, porcentaje_tarjeta, id_tarjeta, id_reserva ";
            $ctacte_campos = " id_cliente, id_encominda, id_pago, fecha ";
            //**************************************************************************
            // Entrada: [cadena1,cadena2,...]
            //           cadena1 : tipo pago|dato1|dato2|...
            //           dato1: detalle de pago|importe|datos adicion1|dato adicion2|...
            // *************************************************************************
            for ($k=0; $k<=sizeof($det_pa)-1; $k += 1) {           
                $det_pago = explode('#',$det_pa[$k]);
                $i = 0;
                //for ($i=0; $i<=sizeof($det_pago); $i += $cant_campo2) {           
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
                        $id_cheque = ejecutar_sql_y_dar_id_con_transaccion($db, 'cheques', $cheque_campos, $cheque_datos); // obtenemos el ID del cheque   
                        if ($id_cheque <= 0) 
                            $bandera_ejecucion_exitosa = 'F';
                    }else{
                        $id_cheque = 'NULL';                
                    }// fin del IF  de CHEQUES
                          
                    //------------------------------------------------------------------
                    //------ Fin de la carga de cheques --------------------------------
                    //------------------------------------------------------------------
                   
                    //------------------------------------------------------------------
                    //------ PAGO por PASAJES ADELANTADO --------------------------------
                    //------------------------------------------------------------------
                    if ($det_pago[0 + $i]==5){ // si es igaul a PAGO POR PASAJE ADELANTADO (5)            
                        $id_pasaje_adelantado = $det_pago[2 + $i];
                    }ELSE{
                        $id_pasaje_adelantado = "NULL";                        
                    }
                    //------------------------------------------------------------------
                    //------ Fin de la carga del pago pasaje adelantado ----------------
                    //------------------------------------------------------------------
                    //------------------------------------------------------------------
                    //------ PAGO por TARJETA ------------------------------------------
                    //------------------------------------------------------------------
                    if ($det_pago[0 + $i]==4){ // si es igaul a PAGO POR TARJETA (4)
            
                        $id_tarjeta = $det_pago[2 + $i];
                    }ELSE{
                        $id_tarjeta = "NULL";                        
                    }
                    // Verificamos que tenga interes                              
                    if(trim($det_pago[3 + $i])==''){
                        $porc_tarjeta = '0';
                    }else{
                        $porc_tarjeta = $det_pago[3 + $i];
                    }

                    //------------------------------------------------------------------
                    //------ Fin de la carga del pago con TARJETA ----------------
                    //------------------------------------------------------------------
                                            
                    // CAMPOS ---->       importe,             forma_pago,            fecha,    id_cheque, id_pasaje, porcentaje_tarjeta 
                    $pagos_datos = $det_pago[1 + $i].",".$det_pago[0 + $i].", CURRENT_DATE, ".$id_cheque.",".$id_pasaje.",".$porc_tarjeta.",".$id_tarjeta.",".$id_pasaje_adelantado;
                    //    echo $pago_campos."-----".$pagos_datos; die();
                    
                    $id_pago = ejecutar_sql_y_dar_id_con_transaccion($db, 'pagos', $pago_campos, $pagos_datos);
                    if ($id_pago <= 0) 
                        $bandera_ejecucion_exitosa = 'F';
                                       
                    //--------------------------------------------------------------------------------
                    // Se ejecuta despues, ya que se necesita el id de pago para el insert de CTA CTE
                    if ($det_pago[0 + $i]==3){ // si es igaul a Cta. Cte (3)
                        // armamos el sql para el registro de pago
                        //                  id_cliente  id_encomienda  id_pago    fecha_emision
                        $ctacte_datos = $det_pago[1 + $i].",".$id.",".$id_pago.",CURRENT_DATE";
                        $id_ctacte = ejecutar_sql_y_dar_id_con_transaccion($db, 'ctactes', $ctacte_campos, $ctacte_datos); // obtenemos el ID del ctacte   
                        if ($id_ctacte <= 0) 
                            $bandera_ejecucion_exitosa = 'F';

                    }else{
                        $id_ctacte = '';
                    }             
                    
              //  } // fin del for
            } // fin del for   
            // |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
            // ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||        
            // ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
            // ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||    
        } else{ // fin de id_pasaje > 0
            if($tipo=='U'){// si es una utilizacion de pasaje ida y vuelta                 
                $res = actualizar_registro_con_transaccion($db,'pasajes_de_vuelta', "fecha_uso=CURRENT_DATE, estado='U', id_pasaje_usado=".$id_pasaje, 'id_pasaje_origen='.$id_dat_ida[0]);
                if($res==-1){
                   $bandera_ejecucion_exitosa = 'F'; 
                } 
            }
        }
    }// fin del for de pasajes   
    
    if($bandera_ejecucion_exitosa=='V'){
        commit_transaccion($db);  
        echo "Operación exitosa";
       
    }else{  
        rollback_transaccion($db);
        echo $db->ErrorMsg();
    }    
} // fin del if conec normal    
?>  