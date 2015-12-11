<?php
//------------------------------------------------------------------------------
// Insertamos los registros en las siguientes tablas:
//          1) CHEQUES
//          2) PAGOS
//          5) CTACTES
// Modificamos el contenido en los siguientes          
//          6) PASAJES_ADELANTADO
//          7) pasajes
//------------------------------------------------------------------------------
include_once("seguridad.php");
include_once("template.php" );
include_once("conexion.php" );
include_once("funciones.php");

$detalle_pagos  = $_POST["e_pagos"];    // Detalle de pago de la encomienda.
$id_cliente     = $_POST["id_cliente"]; // id del cliente al que se le asignara el pago en la cta. cte.
$id_suc         = $_SESSION['id_sucursal'];
$tipo           = $_POST['tipo']; // identifica si se tiene que usar datos adicionales al pago donde 0: indica 
$datos_adic     = $_POST["d_a"]; 
$id             = $_POST["id"]; // id del pasaje que esta e modo reservado y se quiere pagar

//echo 'Detalle_pagos: '.$detalle_pagos.'Id_cliente: '.$id_cliente.'id_suc: '.$id_suc.'tipo: '.$tipo.'datos_adic: '.$datos_adic.'id: '. $id;


$db = conectar_al_servidor_e_iniciar_transaccion();

if($db){
    //******************************************************************************
    //                        Escritura del  PAGO
    //******************************************************************************
    $detalle_pagos1 = $detalle_pagos; // explode(",", $detalle_pagos);  

    // cantidad de campos a escribir en cada tabla
    $cant_campo2 = 10;
    $cant_campo3 = 9; 
    $cant_campo4 = 3;

    $id_cheque           = "";
    $id_ctacte           = "";
    $id_pasaje_reservado = "";
    $id_tarjeta          = "";
    $interes_tarjeta     = 0;

    //------------------------------------------------------------------------------
    // campos para inserciones de tablas 
    $cheque_campos = " nro_cheque, id_banco, importe, fecha_emision, fecha_cobro, operacion_ingreso, propio, propietario, entregado_por, eliminado ";    
    $pago_campos   = " importe, forma_pago, fecha, id_cheque, nro_sucursal, nro_recibo, id_tarjeta, porcentaje_tarjeta ";
    $ctacte_campos = " id_cliente, id_pago, fecha ";
    
    // verificamos quien llamo a esta pagina para saber que hacer.
    switch ($tipo) {
        case 0:        
               //-------------------------------------------------------------------
               //             datos usados para el pago de encomiendas
               //-------------------------------------------------------------------        
            $pago_campos = $pago_campos.', id_encomienda';
            break;
        case 1: 
            //------------------------------------------------------------------
            //      son los datos que se usaran segun la variable  "tipo" 
            //----------------------------------------------------------------------
            $d_a = explode('|', $datos_adic);
            
            $f_d       = $d_a[0]; // fecha
            $cant      = $d_a[1]; // cantidad de pasajes reservados 
            $id_t_pas  = $d_a[2]; // id de tipo de pasajes reservados   
            $t_pas     = $d_a[3]; // tipo de pasajes reservados
            $raz       = $d_a[4]; // razon social de la empresa que compra los pasajes
            $observ    = $d_a[5]; // observacion sobre la venta de los pasajes adelantados.
            $id_cli    = $d_a[6]; // ID del cliente a cual se imputaran los pasajes adelantados 
            $est       = 'A';

            // verificamos que no exista el registro para hacer un "insert" caso contrario hacemos un "update".
            $sql = " SELECT count(*), pa.codigo
                     FROM pasajes_adelantados AS pa
                     WHERE pa.id_tipo_pasaje=".$id_t_pas." and  pa.id_cliente=".$id_cli;

            $res_aux = ejecutar_sql($db, $sql);
            $numero = $res_aux->fields[0];           
            $f_d = cambiaf_a_mysql($f_d);
//            echo $numero; die();
            if($numero==0){ // si no existen registro hay que hacer un insert 
                // guardo el codigo creado del registro insertado                          
                $id  = insertar_registro_transaccion_dar_id($db, 
                            'pasajes_adelantados',
                            'fecha_emision, observaciones, estado, cantidad, id_cliente, id_tipo_pasaje',
                            '"'.$f_d.'","'.$observ.'","'.$est.'",'.$cant.','.$id_cli.','.$id_t_pas);
                //$sql = 'INSERT INTO pasajes_adelantados (fecha_emision, observaciones, estado, cantidad, id_cliente, id_tipo_pasaje) VALUES("'. 
                //            $f_d.'","'.$observ.'","'.$est.'",'.$cant.','.$id_cli.','.$id_t_pas.');';
                // $res = ejecutar_sql($db, $sql);
                // $id = mysql_insert_id($db);
            }else{
                //$sql = "UPDATE pasajes_adelantados SET cantidad = cantidad + ".$cant." WHERE codigo=".$res_aux->fields[1];
                $aa = actualizar_registro_con_transaccion(
                            $db,
                            "pasajes_adelantados",
                            "cantidad=cantidad+".$cant.", fecha_emision='".$f_d."'",
                            " codigo = ".$res_aux->fields[1]);
               
                $id = $res_aux->fields[1]; // guardo el codigo del registro modificado               
            }
            $pago_campos = $pago_campos.', id_reserva';
            break;
        case 2:
            //-------------------------------------------------------------------
            //             datos usados para el pago de pasajes reservados
            //----------------------------------------------------------------------
            $pago_campos = $pago_campos.', id_pasaje';
           
            break;
    }

    //------------------------------------------------------------------------------
    // campos  para modificaciones de tablas
    $pasaje_adelantado_campos =  " cantidad "; 

    //------------------------------------------------------------------------------
    // Incrementamos el nro de recibo que esta asociado a la sucursal
    //------------------------------------------------------------------------------
    $tabla_nro_recibo  = "sucursales";
    $campos_nro_recibo = "nro_recibo";
    $rs = mysql_query("SELECT MAX(".$campos_nro_recibo.") AS id FROM ".$tabla_nro_recibo." WHERE codigo=".$id_suc);

    if ($row = mysql_fetch_row($rs)) {
        $nro_recibo = trim($row[0]) + 1;
        mysql_query("UPDATE ".$tabla_nro_recibo ." SET ".$campos_nro_recibo."=".$nro_recibo." where codigo = ".$id_suc);     
    }                
    //--------------------- Fin de incremento de nro recibo ------------------------

    //******************************************************************************
    // Entrada: [cadena1,cadena2,...]
    //           cadena1 : tipo pago|dato1|dato2|...
    //           dato1: detalle de pago|importe|datos adicion1|dato adicion2|...
    //              tipo pago = 1: contado, 2: cheque, 3: Cta. cte., 4: pago destino,
    //                          5: tarjeta, 6: reserva de pagos
    // *****************************************************************************
    for ($k=0; $k<=sizeof($detalle_pagos1)-1; $k += 1) {           

        $detalle_pagos12 = explode('#',$detalle_pagos1[$k]);

        for ($i=0; $i<=sizeof($detalle_pagos12)-1; $i += $cant_campo2) {           

            //**********************************************************************
            //completamos el registro de pago con los ID de cheque si corresponde
            // ---------------------------------------------------------------------
            if ($detalle_pagos12[0 + $i]==2){ // si es igual a CHEQUE (2)
                // armamos el sql para el registro de pago
                //                         nro_cheque            id_banco               importe             fecha_emision
                $cheque_datos = $detalle_pagos12[4 + $i].",".$detalle_pagos12[8 + $i].",".$detalle_pagos12[1 + $i].",'".$detalle_pagos12[2 + $i]."','".
                //                         fecha_cobro       operacion_ingreso          propio              propietario
                $detalle_pagos12[3 + $i]."','".$detalle_pagos12[5 + $i]."','".$detalle_pagos12[9 + $i]."','".$detalle_pagos12[6 + $i]."','".
                //                        entregado_por  eliminado  
                $detalle_pagos12[7 + $i]."','N'";
                $id_cheque = ejecutar_sql_y_dar_id_con_transaccion($db, 'cheques',$cheque_campos,$cheque_datos); // obtenemos el ID del cheque   

            }else{
                 $id_cheque = 'NULL';                
            } // fin del IF  de CHEQUES    
            // ---------------------------------------------------------------------
            //------ Fin de la carga del cheque ------------------------------------
            //----------------------------------------------------------------------        
            if($detalle_pagos12[0 + $i]==5){ // si es un pago con tarjeta
                $id_tarjeta = $detalle_pagos12[2 + $i]; // obtenemos el id de la tarjeta
                $interes_tarjeta = $detalle_pagos12[3 + $i]; // obtenemos el interes de la tarjeta
            }else{
                $id_tarjeta = 'NULL';  
                $interes_tarjeta = 'NULL';
            }

            if($detalle_pagos12[0 + $i]==6){ // si es reserva de pasaje el pago
                $id_pasaje_reservado = $detalle_pagos12[2 + $i]; // obtenemos el id de donde se desconto el pasaje            //

                // Restamos 1 pasaje a la cuenta referenciada por ID            
                 actualizar_registro_con_transaccion($db,'pasajes_adelantados','cantidad = (cantidad - 1)',' codigo = '.$id_pasaje_reservado );

            }else{
                $id_pasaje_reservado = 'NULL';  
            }


            //----------------------------------------------------------------------
            // Insertamos el REGISTRO DE PAGO         
            // CAMPOS ---->       importe,                 forma_pago,                 fecha,       id_cheque,    id_sucursal    nro_recibo      tarjeta          interes,            id_reserva de pasaje                                  
            $pagos_datos = $detalle_pagos12[1 + $i].",".$detalle_pagos12[0 + $i].", CURRENT_DATE, ".$id_cheque.", ".$id_suc.", ".$nro_recibo.", ".$id_tarjeta.", ".$interes_tarjeta.", ".$id;

            
            //echo $id." ----  ".$pago_campos."(".$pagos_datos.")"; die();
            
            
           //  echo $pago_campos." ----  ".$pagos_datos;
           // die();

            $id_pago = ejecutar_sql_y_dar_id_con_transaccion($db, 'pagos', $pago_campos, $pagos_datos);
            //echo 'pagos '.$pago_campos."  ".$pagos_datos;
            //echo $id_pago; 
            //die();
            if($tipo==2){// si fue llamado desde admin_pasajes para abonar el pago de un pasaje reservado
                if(($id_pago!=-1)&&($id)){// SI no devuelve -1 es porque se realizo el pago y se marca como pagado en pasaje.
                    mysql_query("UPDATE pasajes SET PAGADO = 'V' where codigo = ".$id);                 

              //  }else{
              //      rollback_transaccion($db);
               }
            }

            //----------------------------------------------------------------------
            // Se ejecuta despues, ya que se necesita el id de pago para el insert de CTA CTE
            $ctacte_datos = $id_cliente.",".$id_pago.",CURRENT_DATE";
            $id_ctacte = ejecutar_sql_y_dar_id_con_transaccion($db, 'ctactes', $ctacte_campos, $ctacte_datos); // obtenemos el ID del ctacte   

           }
        }    
    commit_transaccion($db); 
    desconectar($db);
    echo "Pago registrado con exito...";
}

?>  