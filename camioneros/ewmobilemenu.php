<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(23, "mmi_inicio_php", $Language->MenuPhrase("23", "MenuText"), "inicio/inicio.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(15, "mmci_ABM_de_Datos_Base", $Language->MenuPhrase("15", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(11, "mmi_tipo_cargas", $Language->MenuPhrase("11", "MenuText"), "tipo_cargaslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(12, "mmi_tipo_gastos", $Language->MenuPhrase("12", "MenuText"), "tipo_gastoslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(13, "mmi_tipo_mantenimientos", $Language->MenuPhrase("13", "MenuText"), "tipo_mantenimientoslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(47, "mmci_Regiones", $Language->MenuPhrase("47", "MenuText"), "", 15, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(7, "mmi_localidades", $Language->MenuPhrase("7", "MenuText"), "localidadeslist.php", 47, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(9, "mmi_provincias", $Language->MenuPhrase("9", "MenuText"), "provinciaslist.php", 47, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(48, "mmci_Vehiculos", $Language->MenuPhrase("48", "MenuText"), "", 15, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10, "mmi_talleres", $Language->MenuPhrase("10", "MenuText"), "tallereslist.php", 48, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(8, "mmi_marcas", $Language->MenuPhrase("8", "MenuText"), "marcaslist.php", 48, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(14, "mmi_vehiculos", $Language->MenuPhrase("14", "MenuText"), "vehiculoslist.php", 48, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(84, "mmci_Personas", $Language->MenuPhrase("84", "MenuText"), "", 15, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mmi_clientes", $Language->MenuPhrase("2", "MenuText"), "clienteslist.php", 84, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(1, "mmi_choferes", $Language->MenuPhrase("1", "MenuText"), "chofereslist.php", 84, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(76, "mmi_usuarios", $Language->MenuPhrase("76", "MenuText"), "usuarioslist.php", 84, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(116, "mmci_Hojas_de_Rutas", $Language->MenuPhrase("116", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mmi_hoja_rutas", $Language->MenuPhrase("6", "MenuText"), "hoja_rutaslist.php", 116, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mmi_gastos", $Language->MenuPhrase("4", "MenuText"), "gastoslist.php?cmd=resetall", 116, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(75, "mmci_Listados", $Language->MenuPhrase("75", "MenuText"), "", 116, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(50, "mmi_v_gastos_hoja_ruta", $Language->MenuPhrase("50", "MenuText"), "v_gastos_hoja_rutalist.php", 75, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(24, "mmi_Listado_Gastos_por_vehiculo", $Language->MenuPhrase("24", "MenuText"), "Listado_Gastos_por_vehiculoreport.php", 75, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(86, "mmi_r_listado_totales_por_hoja_ruta", $Language->MenuPhrase("86", "MenuText"), "r_listado_totales_por_hoja_rutareport.php", 75, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(22, "mmi_v_listado_gastos_hoja_ruta", $Language->MenuPhrase("22", "MenuText"), "v_listado_gastos_hoja_rutalist.php", 75, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(117, "mmci_Mantenimientos", $Language->MenuPhrase("117", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mmi_hoja_mantenimientos", $Language->MenuPhrase("5", "MenuText"), "hoja_mantenimientoslist.php", 117, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(87, "mmi_gastos_mantenimientos", $Language->MenuPhrase("87", "MenuText"), "gastos_mantenimientoslist.php?cmd=resetall", 117, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(118, "mmci_Listados", $Language->MenuPhrase("118", "MenuText"), "", 117, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(49, "mmi_acerca_de_php", $Language->MenuPhrase("49", "MenuText"), "inicio/acerca_de.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
