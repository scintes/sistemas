<?php

$contact_name = $_POST['name'];
$contact_email = $_POST['email'];
$contact_subject = $_POST['subject'];
$contact_message = $_POST['message'];


echo "<br>";

echo $contact_email + $contact_message + $contact_name + $contact_subject;
echo "<br>";
echo "<br>";
echo "<br>";
/*
//if ( $_POST['enviar'] == "1") {
//if ( $_POST['usuario'] != "" && $_POST['passwd'] != "" && $_POST['destinatario'] != "" ) {
// Se incluye la librería necesaria para el envio
//require_once("fzo.mail.php");
//$mail = new SMTP("localhost","contacto@garelifabrizi.com.ar","fibo11235");
// Se configuran los parametros necesarios para el envío
$de = "$contact_email";
$a = "contacto@garelifabrizi.com.ar";
$asunto = "$contact_subject";
$header = "$contact_email\r\n";
$header .= "$contact_subject \r\n";
$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
$header .= "Mime-Version: 1.0 \r\n";
$header .= "Content-Type: text/plain";

$cuerpo .= ".::. Mensaje Enviado desde el formulario de GareliFabrizi.com.ar  .::.\r\n";
$cuerpo .= "Nombre: " . $contact_name . " \r\n";
$cuerpo .= "Su e-mail es: " . $contact_email . " \r\n";
$cuerpo .= "Mensaje: " . $contact_message . " \r\n";
$cuerpo .= "Enviado el " . date('d/m/Y', time());


//$header = $mail->make_header( $de, $a, $header, $cuerpo, $cc, $bcc );
mail($a, $asunto, $cuerpo, $header);



/* Pueden definirse más encabezados. Tener en cuenta la terminación de la linea con (\r\n)
$header .= "Reply-To: ".$_POST['from']." \r\n";
$header .= "Content-Type: text/plain; charset=\"iso-8859-1\" \r\n";
$header .= "Content-Transfer-Encoding: 8bit \r\n";
$header .= "MIME-Version: 1.0 \r\n";
*/ // Se envia el correo y se verifica el error
/*
$error = $mail->smtp_send($de, $a, $header, $cuerpo, $cc, $bcc);
if ($error == "0") echo "E-mail enviado correctamente"; 
else echo $error; 
echo "/n";
echo $contact_email;
/*																							}
else {
echo("Complete todos los campos para ejecutar el ejemplo");

	 }
}*/
?>