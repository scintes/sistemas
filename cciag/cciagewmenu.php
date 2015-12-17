<!-- Begin Main Menu -->
<div class="ewMenu">
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
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
</div>
<!-- End Main Menu -->
