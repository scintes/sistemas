<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "../";
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewcfg11.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = "../"; ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$acerca_de_php = NULL; // Initialize page object first

class cacerca_de_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'acerca_de.php';

	// Page object name
	var $PageObjName = 'acerca_de_php';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'acerca_de.php', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("cciag_login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		 // Close connection

		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("custom", "acerca_de_php", $url, "", "acerca_de_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($acerca_de_php)) $acerca_de_php = new cacerca_de_php();

// Page init
$acerca_de_php->Page_Init();

// Page main
$acerca_de_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_header.php" ?>
<?php if (!@$gbSkipHeaderFooter) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<div class="panel panel-default">
	<div class="panel-heading" align="center">Sistema CCIAG de Sauce Viejo</div>
	<div class="panel-body">
	<p align="center">
		<image src="logo.jpg" alt="logo" border="1" align="center"/><br>
		</p>
<style>
table, td, th {
	border: 1px solid black;
}
</style>
<table align="center">
  <tr>
	<th width='350' align='center'>Etapas</th>
	<th align='center' >Descripción</th>
  </tr>
  <tr>
	<td>  1 - Administración de CCIAG</td>
	<td> Administracion de HOJA de RUTA.</p>
		Esta etapa, trata de dar solución al seguimiento de la cargas y el tipo de carga</td>
  </tr>
  <tr>
	<td>  2 - Administración de HOJA de MANTENIEMINTO</td>
	<td>Esta etapa trata de dar solución al seguimiento de los mantenimiento decada vehiculo</td>
  </tr>
  <tr>
	<td>  3 - Administracion de CUENTAS</td>
	<td>Esta etapa permite llevar el movimiento contable de las hojas de rutas y de manteniemintos</td>
  </tr>
</table>		
		<p align="center">CintesSoft Sistemas...</p><br>
		<p align="center">Copyrigth Noviembre 2015 - Versión Desarrollo</p>		
		<p align="center">Versión de modulos:</p>
		<table align="center">
			<tr><td>ABM - Choferes</td><td width='30'>1.0</td></tr>
			<tr><td>ABM - Clientes</td><td>1.0</td></tr>
			<tr><td>ABM - Localidades</td><td>1.0</td></tr>
			<tr><td>ABM - Marcas</td><td>1.0</td></tr>
			<tr><td>ABM - Provincia</td><td>1.0</td></tr>
			<tr><td>ABM - Talleres</td><td>1.0</td></tr>
			<tr><td>ABM - Tipo de Cargas</td><td>1.0</td></tr>
			<tr><td>ABM - Tipo de Gastos</td><td>1.0</td></tr>
			<tr><td>ABM - Tipo de Mantenimientos</td><td>1.0</td></tr>
			<tr><td>ABM - Vehículos</td><td>1.0</td></tr>
			<tr><td>ABM - Usuarios</td><td>1.0</td></tr>
			<tr><td>Hoja de Mantenimiento</td><td>1.0</td></tr>
			<tr><td>Hoja de Ruta</td><td>1.0</td></tr>
			<tr><td>Gastos de Mantenimiento</td><td>1.0</td></tr>
			<tr><td>Gastos de Hoja de Rutas</td><td>1.0</td></tr>
		</table>
	</div>
</div>
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_footer.php" ?>
<?php
$acerca_de_php->Page_Terminate();
?>
