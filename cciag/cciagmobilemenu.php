<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "cciagewcfg10.php" ?>
<?php include_once "cciagewmysql10.php" ?>
<?php include_once "cciagphpfn10.php" ?>
<?php include_once "cciaguserfn10.php" ?>
<?php
	ew_Header(TRUE);
	$conn = ew_Connect();
	$Language = new cLanguage();

	// Security
	$Security = new cAdvancedSecurity();
	if (!$Security->IsLoggedIn()) $Security->AutoLogin();
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
$RootMenu->AddMenuItem(17, $Language->MenuPhrase("17", "MenuText"), "cciaguserlevelpermissionslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(18, $Language->MenuPhrase("18", "MenuText"), "cciaguserlevelslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(15, $Language->MenuPhrase("15", "MenuText"), "cciagcant_socios_actividadlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(16, $Language->MenuPhrase("16", "MenuText"), "cciagcantidad_socios_por_actividadreport.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(5, $Language->MenuPhrase("5", "MenuText"), "cciagrubroslist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "cciagactividadlist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "cciagmontoslist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "cciagusuariolist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(12, $Language->MenuPhrase("12", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "cciagdeudaslist.php", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "cciagpagoslist.php", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "cciagsocios_cuotaslist.php", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(13, $Language->MenuPhrase("13", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "cciagsocioslist.php", 13, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(14, $Language->MenuPhrase("14", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(-2, $Language->Phrase("ChangePwd"), "cciagchangepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "cciaglogout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "cciaglogin.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("cciaglogin.php")) <> "cciaglogin.php");
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
