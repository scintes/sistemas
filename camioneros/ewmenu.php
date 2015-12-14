<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(23, "mi_inicio_php", $Language->MenuPhrase("23", "MenuText"), "inicio/inicio.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(116, "mci_Hojas_de_Rutas", $Language->MenuPhrase("116", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mi_hoja_rutas", $Language->MenuPhrase("6", "MenuText"), "hoja_rutaslist.php", 116, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mi_gastos", $Language->MenuPhrase("4", "MenuText"), "gastoslist.php?cmd=resetall", 116, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(75, "mci_Listados", $Language->MenuPhrase("75", "MenuText"), "", 116, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(50, "mi_v_gastos_hoja_ruta", $Language->MenuPhrase("50", "MenuText"), "v_gastos_hoja_rutalist.php", 75, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(24, "mi_Listado_Gastos_por_vehiculo", $Language->MenuPhrase("24", "MenuText"), "Listado_Gastos_por_vehiculoreport.php", 75, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(86, "mi_r_listado_totales_por_hoja_ruta", $Language->MenuPhrase("86", "MenuText"), "r_listado_totales_por_hoja_rutareport.php", 75, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(22, "mi_v_listado_gastos_hoja_ruta", $Language->MenuPhrase("22", "MenuText"), "v_listado_gastos_hoja_rutalist.php", 75, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, "mi_hoja_mantenimientos", $Language->MenuPhrase("5", "MenuText"), "hoja_mantenimientoslist.php", 117, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(87, "mi_gastos_mantenimientos", $Language->MenuPhrase("87", "MenuText"), "gastos_mantenimientoslist.php?cmd=resetall", 117, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(118, "mci_Listados", $Language->MenuPhrase("118", "MenuText"), "", 117, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(153, "mci_Configuracif3n", $Language->MenuPhrase("153", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(155, "mi_backup_v1_php", $Language->MenuPhrase("155", "MenuText"), "inicio/backup_v1.php", 153, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(15, "mci_ABM_de_Datos_Base", $Language->MenuPhrase("15", "MenuText"), "", 153, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(11, "mi_tipo_cargas", $Language->MenuPhrase("11", "MenuText"), "tipo_cargaslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(12, "mi_tipo_gastos", $Language->MenuPhrase("12", "MenuText"), "tipo_gastoslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(13, "mi_tipo_mantenimientos", $Language->MenuPhrase("13", "MenuText"), "tipo_mantenimientoslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(47, "mci_Regiones", $Language->MenuPhrase("47", "MenuText"), "", 15, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(7, "mi_localidades", $Language->MenuPhrase("7", "MenuText"), "localidadeslist.php", 47, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(9, "mi_provincias", $Language->MenuPhrase("9", "MenuText"), "provinciaslist.php", 47, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(48, "mci_Vehiculos", $Language->MenuPhrase("48", "MenuText"), "", 15, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10, "mi_talleres", $Language->MenuPhrase("10", "MenuText"), "tallereslist.php", 48, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(8, "mi_marcas", $Language->MenuPhrase("8", "MenuText"), "marcaslist.php", 48, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(14, "mi_vehiculos", $Language->MenuPhrase("14", "MenuText"), "vehiculoslist.php", 48, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(84, "mci_Personas", $Language->MenuPhrase("84", "MenuText"), "", 15, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mi_clientes", $Language->MenuPhrase("2", "MenuText"), "clienteslist.php", 84, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(1, "mi_choferes", $Language->MenuPhrase("1", "MenuText"), "chofereslist.php", 84, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(154, "mci_Usuarios", $Language->MenuPhrase("154", "MenuText"), "", 153, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(119, "mi_nivel_permisos_usuario", $Language->MenuPhrase("119", "MenuText"), "nivel_permisos_usuariolist.php", 154, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(120, "mi_nivel_usuario", $Language->MenuPhrase("120", "MenuText"), "nivel_usuariolist.php", 154, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(76, "mi_usuarios", $Language->MenuPhrase("76", "MenuText"), "usuarioslist.php", 154, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(49, "mi_acerca_de_php", $Language->MenuPhrase("49", "MenuText"), "inicio/acerca_de.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
