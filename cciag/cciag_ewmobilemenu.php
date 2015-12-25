<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(27, "mmi_inicio_php", $Language->MenuPhrase("27", "MenuText"), "inicio/inicio.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(13, "mmci_Socios", $Language->MenuPhrase("13", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mmi_socios", $Language->MenuPhrase("6", "MenuText"), "cciag_socioslist.php?cmd=resetall", 13, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(26, "mmi_socios_detalles", $Language->MenuPhrase("26", "MenuText"), "cciag_socios_detalleslist.php?cmd=resetall", 13, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(97, "mmci_Listados", $Language->MenuPhrase("97", "MenuText"), "", 13, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(24, "mmi_socios_activos", $Language->MenuPhrase("24", "MenuText"), "cciag_socios_activoslist.php", 97, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(99, "mmi_r_listado_socios_por_actividad_y_rubro", $Language->MenuPhrase("99", "MenuText"), "cciag_r_listado_socios_por_actividad_y_rubroreport.php", 97, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(60, "mmci_Tramites", $Language->MenuPhrase("60", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(62, "mmi_tramites", $Language->MenuPhrase("62", "MenuText"), "cciag_tramiteslist.php", 60, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(61, "mmi_seguimiento_tramites", $Language->MenuPhrase("61", "MenuText"), "cciag_seguimiento_tramiteslist.php?cmd=resetall", 60, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(12, "mmci_Contables", $Language->MenuPhrase("12", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(100, "mmi_v_total_estado_cuenta_x_anio_mes", $Language->MenuPhrase("100", "MenuText"), "cciag_v_total_estado_cuenta_x_anio_meslist.php", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(2, "mmi_deudas", $Language->MenuPhrase("2", "MenuText"), "cciag_deudaslist.php?cmd=resetall", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mmi_pagos", $Language->MenuPhrase("4", "MenuText"), "cciag_pagoslist.php?cmd=resetall", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mmi_socios_cuotas", $Language->MenuPhrase("7", "MenuText"), "cciag_socios_cuotaslist.php?cmd=resetall", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(22, "mmi_detalle_deudas", $Language->MenuPhrase("22", "MenuText"), "cciag_detalle_deudaslist.php?cmd=resetall", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(14, "mmci_Listados", $Language->MenuPhrase("14", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(50, "mmci_Configuracif3n", $Language->MenuPhrase("50", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(51, "mmci_Usuarios", $Language->MenuPhrase("51", "MenuText"), "", 50, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(8, "mmi_usuario", $Language->MenuPhrase("8", "MenuText"), "cciag_usuariolist.php", 51, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(18, "mmi_userlevels", $Language->MenuPhrase("18", "MenuText"), "cciag_userlevelslist.php", 51, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(17, "mmi_userlevelpermissions", $Language->MenuPhrase("17", "MenuText"), "cciag_userlevelpermissionslist.php", 51, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(11, "mmci_Datos_de_Base", $Language->MenuPhrase("11", "MenuText"), "", 50, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mmi_rubros", $Language->MenuPhrase("5", "MenuText"), "cciag_rubroslist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(1, "mmi_actividad", $Language->MenuPhrase("1", "MenuText"), "cciag_actividadlist.php?cmd=resetall", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, "mmi_montos", $Language->MenuPhrase("3", "MenuText"), "cciag_montoslist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(29, "mmi_backup_v1_php", $Language->MenuPhrase("29", "MenuText"), "inicio/backup_v1.php", 50, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(28, "mmi_acerca_de_php", $Language->MenuPhrase("28", "MenuText"), "inicio/acerca_de.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "cciag_changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "cciag_logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "cciag_login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("cciag_login.php")) <> "cciag_login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
