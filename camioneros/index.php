<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Page object name
	var $PageObjName = 'default';

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

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'default', TRUE);

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

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

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

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		$this->Page_Redirecting($url);

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
		global $Security, $Language;
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$this->Page_Terminate("inicio/inicio.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 'choferes'))
			$this->Page_Terminate("chofereslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'clientes'))
			$this->Page_Terminate("clienteslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'gastos'))
			$this->Page_Terminate("gastoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'hoja_mantenimientos'))
			$this->Page_Terminate("hoja_mantenimientoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'hoja_rutas'))
			$this->Page_Terminate("hoja_rutaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'localidades'))
			$this->Page_Terminate("localidadeslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'marcas'))
			$this->Page_Terminate("marcaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'provincias'))
			$this->Page_Terminate("provinciaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'talleres'))
			$this->Page_Terminate("tallereslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'tipo_cargas'))
			$this->Page_Terminate("tipo_cargaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'tipo_gastos'))
			$this->Page_Terminate("tipo_gastoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'tipo_mantenimientos'))
			$this->Page_Terminate("tipo_mantenimientoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'v_listado_gastos_hoja_ruta'))
			$this->Page_Terminate("v_listado_gastos_hoja_rutalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'vehiculos'))
			$this->Page_Terminate("vehiculoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'Listado Gastos por vehiculo'))
			$this->Page_Terminate("Listado_Gastos_por_vehiculoreport.php");
			$this->Page_Terminate("acerca_de.php");
		if ($Security->AllowList(CurrentProjectID() . 'v_gastos_hoja_ruta'))
			$this->Page_Terminate("v_gastos_hoja_rutalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'usuarios'))
			$this->Page_Terminate("usuarioslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'r_listado_totales_por_hoja_ruta'))
			$this->Page_Terminate("r_listado_totales_por_hoja_rutareport.php");
		if ($Security->AllowList(CurrentProjectID() . 'gastos_mantenimientos'))
			$this->Page_Terminate("gastos_mantenimientoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'nivel_permisos_usuario'))
			$this->Page_Terminate("nivel_permisos_usuariolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'nivel_usuario'))
			$this->Page_Terminate("nivel_usuariolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'backup_v1.php'))
			$this->Page_Terminate("backup_v1.php");
		if ($Security->AllowList(CurrentProjectID() . 'backup.php'))
			$this->Page_Terminate("backup.php");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage($Language->Phrase("NoPermission") . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
