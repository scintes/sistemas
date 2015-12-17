<!-- Begin Main Menu -->
<div class="ewMenu">
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(5, $Language->MenuPhrase("5", "MenuText"), "rubroslist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "actividadlist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "montoslist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "usuariolist.php", 11, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(12, $Language->MenuPhrase("12", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "deudaslist.php", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "pagoslist.php", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "socios_cuotaslist.php", 12, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(13, $Language->MenuPhrase("13", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "socioslist.php", 13, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(14, $Language->MenuPhrase("14", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(9, $Language->MenuPhrase("9", "MenuText"), "socios_activoslist.php", 14, "", TRUE, FALSE);
$RootMenu->AddMenuItem(-2, $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
