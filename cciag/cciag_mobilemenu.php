<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "cciag_ewcfg10.php" ?>
<?php include_once "cciag_ewmysql10.php" ?>
<?php include_once "cciag_phpfn10.php" ?>
<?php include_once "cciag_userfn10.php" ?>
<?php
	ew_Header(TRUE);
	$conn = ew_Connect();
	$Language = new cLanguage();
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $Language->Phrase("MobileMenu") ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?php echo ew_jQueryFile("jquery.mobile-%v.min.css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo EW_PROJECT_STYLESHEET_FILENAME ?>">
<link rel="stylesheet" type="text/css" href="phpcss/ewmobile.css">
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery-%v.min.js") ?>"></script>
<script type="text/javascript">

	//$(document).bind("mobileinit", function() {
	//	jQuery.mobile.ajaxEnabled = false;
	//	jQuery.mobile.ignoreContentEnabled = true;
	//});

</script>
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery.mobile-%v.min.js") ?>"></script>
<meta name="generator" content="PHPMaker v10.0.1">
</head>
<body>
<div data-role="page">
	<div data-role="header">
		<h1><?php echo $Language->ProjectPhrase("BodyTitle") ?></h1>
	</div>
	<div data-role="content">
<?php $RootMenu = new cMenu("RootMenu", TRUE); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(23, $Language->MenuPhrase("23", "MenuText"), "cciag_codigo_actividadlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(24, $Language->MenuPhrase("24", "MenuText"), "cciag_socios_activoslist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(17, $Language->MenuPhrase("17", "MenuText"), "cciag_userlevelpermissionslist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(18, $Language->MenuPhrase("18", "MenuText"), "cciag_userlevelslist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(5, $Language->MenuPhrase("5", "MenuText"), "cciag_rubroslist.php", 11, "", TRUE, FALSE);
$RootMenu->AddMenuItem(25, $Language->MenuPhrase("25", "MenuText"), "cciag_detalleslist.php", 11, "", TRUE, FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "cciag_actividadlist.php?cmd=resetall", 11, "", TRUE, FALSE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "cciag_montoslist.php", 11, "", TRUE, FALSE);
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "cciag_usuariolist.php", 11, "", TRUE, FALSE);
$RootMenu->AddMenuItem(12, $Language->MenuPhrase("12", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(21, $Language->MenuPhrase("21", "MenuText"), "cciag_resumen_por_mes_de_pagolist.php", 12, "", TRUE, FALSE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "cciag_deudaslist.php?cmd=resetall", 12, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "cciag_pagoslist.php?cmd=resetall", 12, "", TRUE, FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "cciag_socios_cuotaslist.php?cmd=resetall", 12, "", TRUE, FALSE);
$RootMenu->AddMenuItem(22, $Language->MenuPhrase("22", "MenuText"), "cciag_detalle_deudaslist.php?cmd=resetall", 12, "", TRUE, FALSE);
$RootMenu->AddMenuItem(13, $Language->MenuPhrase("13", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "cciag_socioslist.php", 13, "", TRUE, FALSE);
$RootMenu->AddMenuItem(26, $Language->MenuPhrase("26", "MenuText"), "cciag_socios_detalleslist.php?cmd=resetall", 13, "", TRUE, FALSE);
$RootMenu->AddMenuItem(15, $Language->MenuPhrase("15", "MenuText"), "cciag_cant_socios_actividadlist.php", 13, "", TRUE, FALSE);
$RootMenu->AddMenuItem(16, $Language->MenuPhrase("16", "MenuText"), "cciag_cantidad_socios_por_actividadreport.php", 13, "", TRUE, FALSE);
$RootMenu->Render();
?>
	</div><!-- /content -->
</div><!-- /page -->
</body>
</html>
<?php

	 // Close connection
	$conn->Close();
?>
