<?php
//------------------------------------------------------------------------------
// Modulo que es llamado de encomienda para para la eliminacion de un registro
//------------------------------------------------------------------------------
include_once("seguridad.php");
include_once('conexion.php');
include_once('funciones.php');

//include('funciones.php');

$nro_guia     = $_REQUEST['id'];
$tipo         = $_REQUEST['tipo']; // determina si es virtual (V) la eliminacion

$fecha_desde  		= $_REQUEST['e_fecha_desde'];
$fecha_hasta 		= $_REQUEST['e_fecha_hasta'];
$b_nro_orden 		= $_REQUEST['e_nro_orden'];
$b_direcciones          = $_REQUEST['e_direcciones'];
$b_nombres              = $_REQUEST['e_nombres'];
$b_dni_remitente        = $_REQUEST['e_dni_remitente'];
$b_dni_destinatario     = $_REQUEST['e_dni_destinatario'];        

echo "<form action='buscar_encomiendas.php' method='post' name='f_buscar_encomiendas' id='f_buscar_encomienda'>";
        
echo "<INPUT TYPE='hidden' NAME='e_fecha_desde' VALUE='$fecha_desde'>";
echo "<INPUT TYPE='hidden' NAME='e_fecha_hasta' VALUE='$fecha_hasta'>";
echo "<INPUT TYPE='hidden' NAME='e_nro_orden' VALUE='$b_nro_orden'>";
echo "<INPUT TYPE='hidden' NAME='e_direcciones' VALUE='$b_direcciones'>";
echo "<INPUT TYPE='hidden' NAME='e_nombres' VALUE='$b_nombres'>"; 
echo "<INPUT TYPE='hidden' NAME='e_dni_remitente' VALUE='$b_dni_remitente'>";
echo "<INPUT TYPE='hidden' NAME='e_dni_destinatario' VALUE='$b_dni_destinatario'>";

echo " <div align='center'>
                <table border='0'>
                    <tr>
                        <td>";
                            if (!borrar_registro('encomiendas', 'nro_guia='.$nro_guia, $tipo,'ELIMINADO')){
                                echo "El registro no se pudo eliminarlo con exito...<br><br>";
                            }else{
                                echo "El registro fue eliminado con exito<br><br>";
                            };"
                            <input TYPE='image' SRC='./imagenes/ok.png' BORDER='0' name='volver' id='volver' value='volver'/>
                                <strong> Aceptar </strong>
                        </td>
               </table>
         </div>";
                            
echo"</form>";  


?>
