<?php

include('./html2fpdf/html2fpdf.php');

$html = $_REQUEST['pagina'];
echo $html;die();

$pdf = new HTML2FPDF(); // Creamos una instancia de la clase HTML2FPDF

$pdf -> AddPage(); // Creamos una página

$pdf -> WriteHTML($html);//Volcamos el HTML contenido en la variable $html para crear el contenido del PDF

$pdf -> Output(‘impresion.pdf’, ‘D’);//Volcamos el pdf generado con nombre ‘doc.pdf’. En este caso con el parametro ‘D’ forzamos la descarga del mismo.

?>