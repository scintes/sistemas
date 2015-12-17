<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(27, "mi_inicio_php", $Language->MenuPhrase("27", "MenuText"), "inicio/inicio.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(13, "mci_Socios", $Language->MenuPhrase("13", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mi_socios", $Language->MenuPhrase("6", "MenuText"), "cciag_socioslist.php", 13, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(26, "mi_socios_detalles", $Language->MenuPhrase("26", "MenuText"), "cciag_socios_detalleslist.php?cmd=resetall", 13, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(16, "mi_cantidad_socios_por_actividad", $Language->MenuPhrase("16", "MenuText"), "cciag_cantidad_socios_por_actividadreport.php", 13, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(60, "mci_Tramites", $Language->MenuPhrase("60", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(62, "mi_tramites", $Language->MenuPhrase("62", "MenuText"), "cciag_tramiteslist.php", 60, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(61, "mi_seguimiento_tramites", $Language->MenuPhrase("61", "MenuText"), "cciag_seguimiento_tramiteslist.php?cmd=resetall", 60, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(12, "mci_Contables", $Language->MenuPhrase("12", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mi_deudas", $Language->MenuPhrase("2", "MenuText"), "cciag_deudaslist.php?cmd=resetall", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mi_pagos", $Language->MenuPhrase("4", "MenuText"), "cciag_pagoslist.php?cmd=resetall", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mi_socios_cuotas", $Language->MenuPhrase("7", "MenuText"), "cciag_socios_cuotaslist.php?cmd=resetall", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(22, "mi_detalle_deudas", $Language->MenuPhrase("22", "MenuText"), "cciag_detalle_deudaslist.php?cmd=resetall", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(14, "mci_Listados", $Language->MenuPhrase("14", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(50, "mci_Configuracif3n", $Language->MenuPhrase("50", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(51, "mci_Usuarios", $Language->MenuPhrase("51", "MenuText"), "", 50, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(8, "mi_usuario", $Language->MenuPhrase("8", "MenuText"), "cciag_usuariolist.php", 51, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(18, "mi_userlevels", $Language->MenuPhrase("18", "MenuText"), "cciag_userlevelslist.php", 51, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(17, "mi_userlevelpermissions", $Language->MenuPhrase("17", "MenuText"), "cciag_userlevelpermissionslist.php", 51, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(11, "mci_Datos_de_Base", $Language->MenuPhrase("11", "MenuText"), "", 50, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mi_rubros", $Language->MenuPhrase("5", "MenuText"), "cciag_rubroslist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(1, "mi_actividad", $Language->MenuPhrase("1", "MenuText"), "cciag_actividadlist.php?cmd=resetall", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, "mi_montos", $Language->MenuPhrase("3", "MenuText"), "cciag_montoslist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(29, "mi_backup_v1_php", $Language->MenuPhrase("29", "MenuText"), "inicio/backup_v1.php", 50, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(28, "mi_acerca_de_php", $Language->MenuPhrase("28", "MenuText"), "inicio/acerca_de.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "cciag_changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "cciag_logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "cciag_login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("cciag_login.php")) <> "cciag_login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
