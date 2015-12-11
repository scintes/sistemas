<?php
setlocale(LC_MONETARY, 'es_ES');
setlocale(LC_TIME, 'sp-Sp', 'es-Es', 'es', 'sp');

//------------------------------------------------------------------------------
// Datos de desarrollo del sistema.. solo afines del periodo de desarrollo
//------------------------------------------------------------------------------
define('SIS_PROYECTO','Marciano_web');
define('SIS_VERSION','0.2 Beta');
define('SIS_FECHA_MODI_SISTEMA','26/06/2015');
define('SIS_ICON_PROYECTO','./imagenes/sistema.ico');
define('SIS_LOGO','./imagenes/logo_opt.jpg');
define('SIS_ACERCAR_DE','HardSoft Sistemas');
define('SIS_IMAGEN_FONDO',''); //imagenes/fondo.jpg');
define('SIS_PAGINA','http://www.hardsoftsistemas.com.ar');
//------------------------------------------------------------------------------

// ---------------------------------------------------------------------
//   Configuracion de BASE
// ---------------------------------------------------------------------
define('HOST', '127.0.0.1');
define('USUARIO', 'root');
define('PASSWORD', '');
define('BASE', 'prueba');
define('PUERTO', '3050');
define('TIPOBASE', 'mysql');
//----------------------------------------------------------------------
define('DELIMITACION_FECHA','/');
define('DELIMITACION_HORA', ':');
define('CANT_REG_PAGINA',30); 
define('USAR_ELIMINACION_VIRTUAL',TRUE); // Identifica que tipo de eliminacion hace en las grillas.
define('PORCENTAJE_SEGURO_ENCOMIENDA',2); // Representa el valor en porcentaje del a aplicar a al importe total de la encomienda del seguro..
define('PORCENTAJE_IVA_ENCOMIENDA',21); // Representa el valor en porcentaje del iva a aplicar a las encomienda.
define('PRIORIDAD', serialize(array('URGENTE','RAPIDO','NORMAL','BAJO')));
define('TIPO_RESPONSABLES', serialize(array('Responsable Inscripto','Monotributo','Exento','Consumidor Final')));
define('PORCENTAJE_INTERES_TARJETA',10); // Representa el valor en porcentaje del interes de a aplicar en el pago por tarjetas.
define('TIEMPO_DE_SESION',500); // Segundos de Sesion que esta sin usarce el sistema
define('PLAZO_PASAJES_IDA_VUELTA',30); // cantidad de dias para los pasajes de vuelta una vez viajado. se epera por x dias. 
//------------------------------------------------------------------------------
// Configuración de membrete de las impresiones
//------------------------------------------------------------------------------
define('LOGO','./imagenes/logo.jpg');
define('RAZON_SOCIAL','Marciano Tour SRL');
define('DIREC_RAZON_SOCIAL','Iriondo 200 Reconquista');
define('TEL_RAZON_SOCIAL','Tel:0342-421234 email:info@marcianotoursrl.com.ar');
define('RESPONSABLE', 'RI');
define('LUGARES_DE_SALIDAS',serialize(array(
                                            'Rqta. Plat 1, 2 y 3', 
                                            'Sta Fe Plat. 1 Y 2'
                                            )
                                      ));
//------------------------------------------------------------------------------
// representa la sucursal que puede crear automáticamente las salidas diarias.        
define("SUCURSALES_PARA_CREAR_AUTOMAT_SALIDAS", 1); 

//----------------------------------------------------------------------
//  Parametros para funcionar el sistema
define('TRABAJAR_RANGO_ENTREGA','S'); // Determina si se muestra o no el rango de entrega en encomienda
define('FORMATO_HORA','H:i');       // formato a utilizar en las hora con el sistema
define('FORMATO_FECHA','d/m/Y'); // Formato a utilizar en las fechas por el sistema
define('FACTURA','A'); // tipo de factura a utilizar
define('FACTURA2','B'); // Tipo de factura a utilizar secundaria
define('PORCENTAJE_COSEGURO','7.00'); // porcentaje del coseguro usado en pasajes.
define('ID_CHOFER','2'); // Representa el id del tipo de usuario que se considera chofer.

// ---------------------------------------------------------------------
$mensaje_error_conexion = "Error en la conexión de MySQL: ";
$mensaje_error_ado = "Error al crear el componente ADO";
// ---------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
//  CONFIGURACION DE COLOR PARA encomiendas
//-------------------------------------------------------------------------------------------------
define('COLOR_ENCOMIENDAS_REMITENTE','#F2F5A9');
define('COLOR_ENCOMIENDAS_DESTINATARIO','#D1FFD3');

define('COLOR_ENCOMIENDAS_FILA_COMUN','#FFFFFF'); // usado en el archivo buscar_encomienda_a_cerrar.php; cierre
define('COLOR_ENCOMIENDAS_FILA_TIPO_PAGO_EN_DESTINO','#F78181');
define('COLOR_ENCOMIENDAS_FILA_TIPO_PAGO_CONTADO',   '#BCF5A9');
define('COLOR_ENCOMIENDAS_FILA_TIPO_PAGO_CTACTE',    '#A9D0F5');
define('COLOR_ENCOMIENDAS_CABEZERA_TABLA',              '#FFCC33'); 
define('COLOR_ENCOMIENDAS_CABEZERA_COLUMNA', '#B3B4FA');
define('COLOR_ENCOMIENDAS_PIE_TABLA', '#FBCFE1');

//-------------------------------------------------------------------------------------------------
//  CONFIGURACION DE COLOR PARA pasajes
//-------------------------------------------------------------------------------------------------
define('COLOR_PASAJES_FILA_COMUN','#FFFFFF'); // usado en el archivo buscar_pasajes_a_cerrar.php; cierre
define('COLOR_PASAJES_FILA_TIPO_PAGO_EN_DESTINO','#F78181');
define('COLOR_PASAJES_FOCO','#F2F5A9');
define('COLOR_PASAJES_SELECCIONADO','#BEF781');

define('COLOR_VIAJE_DIARIO_LISTADO','#E2A5B8');
define('COLOR_VIAJE_ESPECIAL_LISTADO','#A5E2AD');
define('COLOR_FONDO_CARGA_DATOS_PASAJE_ORIGEN',"#F2F5A9");
define('COLOR_FONDO_CARGA_DATOS_PASAJE_DESTINO','#D0F5A9'); 
//-------------------------------------------------------------------------------------------------
// CONFIGURACION DE COLORES DE BOTONERA DE MANDO
//-------------------------------------------------------------------------------------------------
define('COLOR_FONDO_BOTONERA_MANDO',"#F2F5A9");
define('COLOR_FONDO_BOTON_MANDO','#fff'); 
define('COLOR_TEXTO_BOTON_MANDO','#333'); 



//********************************************************************************************************
//********************************************************************************************************
//
//
//
//
//********************************************************************************************************
//********************************************************************************************************
// determina cuales son las lineas que tiene la empresa. El fomato de cada linea es el siguiente:
//      linea_a = "id loc orig|idloc_1, id_loc_2,...|id_loc_dest"
//      LINEA = "nombre|linea_a@linea_b@...@linea_n"
// Ejemplo practico
//      id localidad
//      -------------
//      1  Reconquista
//      7  Romang
//      9  San Javier
//      2  Rincon
//      3  Santa Fe  
//      --------------- 
//      LINEA = "Rqta-StaFe|1|7,9,2|3@";
define('LINEAS', "Rqta-StaFe|1|7,9,2|3@".
                 "StaFe-Rqta|3|2,9,7|1");



error_reporting(0);
//error_reporting(E_ALL);
ini_set('display_errors', false);
ini_set('display_startup_errors', false);


function logOut() {
    session_unset();
    session_destroy();
    session_start();
    session_regenerate_id(true);
}


        
include_once("adodb/adodb.inc.php");
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;



function conectar_al_servidor() {
    $db = ADONewConnection(TIPOBASE);
    $db->debug = false; // captura el error y muestra el mismo
    if (!$db) {
        echo "Error al crear el componente ADO";
        die();
    } else {
        $exito = $db->connect(HOST, USUARIO, PASSWORD, BASE);
        if (!$exito) {
            die("Error en la conexión al motor de base de datos de MySQL: ");
        } else {
            return $db; //$exito;
        }
    }
}

function ejecutar_sql_con_coneccion($sql){//, $parametros){
	$db = conectar_al_servidor();
	$res = $db->Execute($sql);//,array($parametros));
	$db->close();
	return $res;
}

function ejecutar_sql($db, $sql) {//, $parametros){
    return $db->Execute($sql); //,array($parametros));
}

function desconectar($db) {
    $db->close();
}

function ejecutar_sql_ref_nombre($db, $sql){
    $us = $db->Execute($sql); //,array($parametros));
    return $us->fetchRow();

}
function ejecutar_sql_con_coneccion_ref_nombre($sql){//, $parametros){
	$db = conectar_al_servidor();
	$res = $db->Execute($sql);//,array($parametros));
        $row = mysql_fetch_array($res); 
	$db->close();
	return $row;
}
//------------------------------------------------------------------------------
// Borra los registros de la tabla 
//   $Tabla --> nombre de la tabla ej: empleados
//   $condicion --> condicion a respetar para la eliminacion Ej dni=123456
//   $tipo --> Indica si es una eliminacion virtual (V) o no
//   $campo --> Indica el campo que se asignara para marcar como eliminado al 
//              registro.
//------------------------------------------------------------------------------
function borrar_registro($tabla,$condicion,$tipo,$campo){
    $db = conectar_al_servidor();
    if ($tipo='V'){
        $sql = "UPDATE $tabla SET $campo = 'S' WHERE $condicion";
    }else{
        $sql = "DELETE FROM $tabla WHERE ".$condicion;
    }
    $us = $db->Execute($sql); 
    desconectar($db);
    return $us;
}


//------------------------------------------------------------------------------
// Borra los registros de la tabla 
//   $Tabla --> nombre de la tabla ej: empleados
//   $condicion --> condicion a respetar para la eliminacion Ej dni=123456
//   $tipo --> Indica si es una eliminacion virtual (V) o no
//   $campo --> Indica el campo que se asignara para marcar como eliminado al 
//              registro.
//------------------------------------------------------------------------------
function recuperar_registro($tabla,$condicion,$tipo,$campo){
    if ($tipo='V'){
        $db = conectar_al_servidor();
        $sql = "UPDATE $tabla SET $campo = 'N' WHERE $condicion";
        $us = $db->Execute($sql); 
        desconectar($db);
        return $us;
    }else{
        return False;
    }
}


//------------------------------------------------------------------------------
// Inserta el registro en la tabla 
//   $Tabla --> nombre de la tabla ej: empleados
//   $campo --> Secuencia de campos a completar en la insercion Ej dni, nombre, tel
//   $datos --> Secuencia de datos a insertar en los campos para la insercion Ej: 123456, dario, 34267733
//------------------------------------------------------------------------------
function insertar_registro($tabla,$campos, $datos){
    $db = conectar_al_servidor();
    $sql = "INSERT INTO ".$tabla." ( ".$campos." ) VALUES (".$datos.")";
    $us = $db->Execute($sql); 
    desconectar($db);
    return $us;
}

//------------------------------------------------------------------------------
// Inserta el registro en la tabla 
//   $db    --> conexion realizada
//   $Tabla --> nombre de la tabla ej: empleados
//   $campo --> Secuencia de campos a completar en la insercion Ej dni, nombre, tel
//   $datos --> Secuencia de datos a insertar en los campos para la insercion Ej: 123456, dario, 34267733
//------------------------------------------------------------------------------
function insertar_registro_transaccion($db,$tabla,$campos, $datos){    
    $sql = "INSERT INTO ".$tabla." ( ".$campos." ) VALUES (".$datos.")";
    $res = $db->Execute($sql); 
    if($res){
        return $res;
    }else{
        return -1;
    } 
}

//------------------------------------------------------------------------------
// ACTUALIZA los contenido de la tabla 
//   $db -----> conexion a la base de datos
//   $Tabla --> nombre de la tabla Ej: empleados
//   $campo --> Indica el campo con el nuevo valor. Ej: cantidad=1, dni=2223444
//   $condicion --> condicion a respetar para la eliminacion Ej codigo=120 and dni=222333
//------------------------------------------------------------------------------
function actualizar_registro_con_transaccion($db, $tabla, $campos, $condicion){    
    try{
        $sql = "UPDATE ".$tabla." SET ".$campos." WHERE (".$condicion.")";         
        $res = $db->Execute($sql); 
    }catch(Exception $e){
        return -1;
    }
}


//------------------------------------------------------------------------------
// Inserta el registro en la tabla 
//   $db    --> conexion realizada
//   $Tabla --> nombre de la tabla ej: empleados
//   $campo --> Secuencia de campos a completar en la insercion Ej dni, nombre, tel
//   $datos --> Secuencia de datos a insertar en los campos para la insercion Ej: 123456, dario, 34267733
//------------------------------------------------------------------------------
function insertar_registro_transaccion_dar_id($db,$tabla,$campos, $datos){    
    $sql = "INSERT INTO ".$tabla." ( ".$campos." ) VALUES (".$datos.")";       
    return $sql;
    
    $res = $db->Execute($sql); 
    if ($res){
        $id = mysql_insert_id($db);
    }else{
        $id = -1;
    }
        
    return $id;
}

//------------------------------------------------------------------------------
// Inserta un registro y devuelve el ID de la clave primaria auto incrementada
// Parametros de entrada: $Tabla: cadena de texto; El nombre de la tabla a realizar el insert/delete/modificacion
//                        $campos: cadena de texto; concatenacion de nombre de los campos del sql separados por coma 
//                        $dato: cadena de texto; concatenacion del valor de cada campos del sql separados por coma                         
// devuelve: -1 en caso de error en la ejecucion fallida del sql
//            0 Si no se pudo conectar
//            ID entero : Si se ejecuto correctamente
//------------------------------------------------------------------------------
function ejecutar_sql_y_dar_id($tabla,$campos, $datos){
    $db = ADONewConnection(TIPOBASE);
    $exito = $db->connect(HOST, USUARIO, PASSWORD, BASE);
    if ($exito){
        $sql = 'INSERT '.$tabla.' ( '.$campos.' ) VALUES ('.$datos.')';
        $us  = $db->Execute($sql); 
        if ($us){
            //$id = $db->Execute('SELECT DISTINCT LAST_INSERT_ID() FROM '.$tabla.' ;');
            $id=mysql_insert_id($db);
        
            // borrar esta linea solo para mostrar la consulta
            //print_r("<br>  SQL: $tabla:  ".$sql."<br>Resultado:".$us);
        
            return $id;
        }else{
            return -1;
        }
       $db->close();    
    }else{
        return 0;
    }
}

// Realizamos el insert y devolvemos el ID 
function ejecutar_sql_y_dar_id_con_transaccion($db, $tabla,$campos, $datos){
        try{
            $sql = 'INSERT '.$tabla.' ( '.$campos.' ) VALUES ('.$datos.')';  
            
            $db->Execute($sql);             
            if ($db){
                $res = $db->Execute('SELECT LAST_INSERT_ID ()');//mysql_insert_id($db);            
                return $res->fields[0];
            }else{
                return -1;
            }
        }catch(Exception $e){
            return -1;
        }
       
}


function conectar_al_servidor_e_iniciar_transaccion() {
    $db = ADONewConnection(TIPOBASE);    
    $db->debug = false; // captura el error y muestra el mismo
    if (!$db) {
        echo "Error al crear el componente ADO";
        die();
    } else {
        $exito = $db->connect(HOST, USUARIO, PASSWORD, BASE);
        if (!$exito) {
            die("Error en la conexión al motor de base de datos de MySQL: ");
        } else {
          //  $trans = $db->Execute("SET AUTOCOMMIT=0;");
            $trans = $db->Execute("START TRANSACTION;");
            if (!$trans){
                return "Error iniciando la transacción: ";                
                die();
            }else{
                return $db; //$exito;                
            }
        }
    }
}

function commit_al_servidor_e_iniciar_transaccion() {
    $db = ADONewConnection(TIPOBASE);
    $db->debug = false; // captura el error y muestra el mismo
    if (!$db) {
        echo "Error al crear el componente ADO";
        die();
    } else {
        $exito = $db->connect(HOST, USUARIO, PASSWORD, BASE);
        if (!$exito) {
            die("Error en la conexión al motor de base de datos de MySQL: ");
        } else {
            $trans = $db->Execute("START TRANSACTION;");
            if (!$trans){
                die("Error iniciando la transacción: ");                
            }else{
                return $db; //$exito;                
            }
        }
    }
}
function commit_transaccion($db) {
    $trans = $db->Execute("commit;");
            if (!$trans){
                die("Error realizando la commit.");                
                rollback_transaccion($db); 
                return FALSE;
            }else{
                $db->close();
                return TRUE; //$exito;                
            }    
}

function rollback_transaccion($db) {
    $trans = $db->Execute("rollback;");
            if (!$trans){
                die("Error realizando el rollback.");                
                return FALSE;
            }else{
                $db->close();
                return TRUE; //$exito;                
            }    
}


//   ************************************************************************
//------------------------------------------------------------------------------
//   ************************************************************************
//                               ARCHIVOS INI
//   ************************************************************************
//------------------------------------------------------------------------------
//   ************************************************************************
// Devuelve el valor de una clave que pertenece a una seccion del archivo ini
function leer_ini($archivo, $seccion, $clave){
    $mat = parse_ini_file($archivo, true);   
    return $mat[$seccion][$clave];
};
// Devuelve la matriz con todos los datos del archivo ini
function leer_ini_($archivo){
    return parse_ini_file($archivo, true);
     
};

// Escribe archivos ini
function escribe_ini($matriz, $archivo, $multi_secciones = true, $modo = 'w') {
    $salida = '';
  
    # saltos de línea (usar "\r\n" para Windows)
    define('SALTO', "\n");
  
    if (!is_array(current($matriz))) {
          $tmp = $matriz;
         $matriz['tmp'] = $tmp; # no importa el nombre de la sección, no se usará
         unset($tmp);
    }
 
    foreach($matriz as $clave => $matriz_interior) {
         if ($multi_secciones) {
             $salida .= '['.$clave.']'.SALTO;
         }
 
         foreach($matriz_interior as $clave2 => $valor)
             $salida .= $clave2.' = "'.$valor.'"'.SALTO;
 
         if ($multi_secciones) {
             $salida .= SALTO;
         }
    }
 
    $puntero_archivo = fopen($archivo, $modo);
 
    if ($puntero_archivo !== false) {
         $escribo = fwrite($puntero_archivo, $salida);
 
         if ($escribo === false) {
             $devolver = -2;
         } else {
             $devolver = $escribo;
         }
 
         fclose($puntero_archivo);
     } else {
         $devolver = -1;
    }
 
    return $devolver;
}

?>




