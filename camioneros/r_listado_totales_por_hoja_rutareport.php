<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php

// Global variable for table object
$r_listado_totales_por_hoja_ruta = NULL;

//
// Table class for r_listado_totales_por_hoja_ruta
//
class cr_listado_totales_por_hoja_ruta extends cTableBase {
	var $codigo;
	var $responsable;
	var $Patente;
	var $kg_carga;
	var $tarifa;
	var $sub_total;
	var $porcentaje;
	var $comision_chofer;
	var $adelanto;
	var $total;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'r_listado_totales_por_hoja_ruta';
		$this->TableName = 'r_listado_totales_por_hoja_ruta';
		$this->TableType = 'REPORT';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// codigo
		$this->codigo = new cField('r_listado_totales_por_hoja_ruta', 'r_listado_totales_por_hoja_ruta', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 3, -1, FALSE, '`codigo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->codigo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codigo'] = &$this->codigo;

		// responsable
		$this->responsable = new cField('r_listado_totales_por_hoja_ruta', 'r_listado_totales_por_hoja_ruta', 'x_responsable', 'responsable', '`responsable`', '`responsable`', 200, -1, FALSE, '`responsable`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['responsable'] = &$this->responsable;

		// Patente
		$this->Patente = new cField('r_listado_totales_por_hoja_ruta', 'r_listado_totales_por_hoja_ruta', 'x_Patente', 'Patente', '`Patente`', '`Patente`', 200, -1, FALSE, '`Patente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Patente'] = &$this->Patente;

		// kg_carga
		$this->kg_carga = new cField('r_listado_totales_por_hoja_ruta', 'r_listado_totales_por_hoja_ruta', 'x_kg_carga', 'kg_carga', '`kg_carga`', '`kg_carga`', 3, -1, FALSE, '`kg_carga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->kg_carga->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['kg_carga'] = &$this->kg_carga;

		// tarifa
		$this->tarifa = new cField('r_listado_totales_por_hoja_ruta', 'r_listado_totales_por_hoja_ruta', 'x_tarifa', 'tarifa', '`tarifa`', '`tarifa`', 131, -1, FALSE, '`tarifa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tarifa->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['tarifa'] = &$this->tarifa;

		// sub_total
		$this->sub_total = new cField('r_listado_totales_por_hoja_ruta', 'r_listado_totales_por_hoja_ruta', 'x_sub_total', 'sub_total', '`sub_total`', '`sub_total`', 131, -1, FALSE, '`sub_total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->sub_total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['sub_total'] = &$this->sub_total;

		// porcentaje
		$this->porcentaje = new cField('r_listado_totales_por_hoja_ruta', 'r_listado_totales_por_hoja_ruta', 'x_porcentaje', 'porcentaje', '`porcentaje`', '`porcentaje`', 131, -1, FALSE, '`porcentaje`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->porcentaje->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['porcentaje'] = &$this->porcentaje;

		// comision_chofer
		$this->comision_chofer = new cField('r_listado_totales_por_hoja_ruta', 'r_listado_totales_por_hoja_ruta', 'x_comision_chofer', 'comision_chofer', '`comision_chofer`', '`comision_chofer`', 131, -1, FALSE, '`comision_chofer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->comision_chofer->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['comision_chofer'] = &$this->comision_chofer;

		// adelanto
		$this->adelanto = new cField('r_listado_totales_por_hoja_ruta', 'r_listado_totales_por_hoja_ruta', 'x_adelanto', 'adelanto', '`adelanto`', '`adelanto`', 131, -1, FALSE, '`adelanto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->adelanto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['adelanto'] = &$this->adelanto;

		// total
		$this->total = new cField('r_listado_totales_por_hoja_ruta', 'r_listado_totales_por_hoja_ruta', 'x_total', 'total', '`total`', '`total`', 131, -1, FALSE, '`total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total'] = &$this->total;
	}

	// Report group level SQL
	var $_SqlGroupSelect = "";

	function getSqlGroupSelect() { // Select
		return ($this->_SqlGroupSelect <> "") ? $this->_SqlGroupSelect : "SELECT DISTINCT `Patente`,`responsable` FROM `v_listado_totales_por_hoja_ruta`";
	}

	function SqlGroupSelect() { // For backward compatibility
    	return $this->getSqlGroupSelect();
	}

	function setSqlGroupSelect($v) {
    	$this->_SqlGroupSelect = $v;
	}
	var $_SqlGroupWhere = "";

	function getSqlGroupWhere() { // Where
		return ($this->_SqlGroupWhere <> "") ? $this->_SqlGroupWhere : "";
	}

	function SqlGroupWhere() { // For backward compatibility
    	return $this->getSqlGroupWhere();
	}

	function setSqlGroupWhere($v) {
    	$this->_SqlGroupWhere = $v;
	}
	var $_SqlGroupGroupBy = "";

	function getSqlGroupGroupBy() { // Group By
		return ($this->_SqlGroupGroupBy <> "") ? $this->_SqlGroupGroupBy : "";
	}

	function SqlGroupGroupBy() { // For backward compatibility
    	return $this->getSqlGroupGroupBy();
	}

	function setSqlGroupGroupBy($v) {
    	$this->_SqlGroupGroupBy = $v;
	}
	var $_SqlGroupHaving = "";

	function getSqlGroupHaving() { // Having
		return ($this->_SqlGroupHaving <> "") ? $this->_SqlGroupHaving : "";
	}

	function SqlGroupHaving() { // For backward compatibility
    	return $this->getSqlGroupHaving();
	}

	function setSqlGroupHaving($v) {
    	$this->_SqlGroupHaving = $v;
	}
	var $_SqlGroupOrderBy = "";

	function getSqlGroupOrderBy() { // Order By
		return ($this->_SqlGroupOrderBy <> "") ? $this->_SqlGroupOrderBy : "`Patente`,`responsable`";
	}

	function SqlGroupOrderBy() { // For backward compatibility
    	return $this->getSqlGroupOrderBy();
	}

	function setSqlGroupOrderBy($v) {
    	$this->_SqlGroupOrderBy = $v;
	}

	// Report detail level SQL
	var $_SqlDetailSelect = "";

	function getSqlDetailSelect() { // Select
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT * FROM `v_listado_totales_por_hoja_ruta`";
	}

	function SqlDetailSelect() { // For backward compatibility
    	return $this->getSqlDetailSelect();
	}

	function setSqlDetailSelect($v) {
    	$this->_SqlDetailSelect = $v;
	}
	var $_SqlDetailWhere = "";

	function getSqlDetailWhere() { // Where
		return ($this->_SqlDetailWhere <> "") ? $this->_SqlDetailWhere : "";
	}

	function SqlDetailWhere() { // For backward compatibility
    	return $this->getSqlDetailWhere();
	}

	function setSqlDetailWhere($v) {
    	$this->_SqlDetailWhere = $v;
	}
	var $_SqlDetailGroupBy = "";

	function getSqlDetailGroupBy() { // Group By
		return ($this->_SqlDetailGroupBy <> "") ? $this->_SqlDetailGroupBy : "";
	}

	function SqlDetailGroupBy() { // For backward compatibility
    	return $this->getSqlDetailGroupBy();
	}

	function setSqlDetailGroupBy($v) {
    	$this->_SqlDetailGroupBy = $v;
	}
	var $_SqlDetailHaving = "";

	function getSqlDetailHaving() { // Having
		return ($this->_SqlDetailHaving <> "") ? $this->_SqlDetailHaving : "";
	}

	function SqlDetailHaving() { // For backward compatibility
    	return $this->getSqlDetailHaving();
	}

	function setSqlDetailHaving($v) {
    	$this->_SqlDetailHaving = $v;
	}
	var $_SqlDetailOrderBy = "";

	function getSqlDetailOrderBy() { // Order By
		return ($this->_SqlDetailOrderBy <> "") ? $this->_SqlDetailOrderBy : "`codigo` ASC";
	}

	function SqlDetailOrderBy() { // For backward compatibility
    	return $this->getSqlDetailOrderBy();
	}

	function setSqlDetailOrderBy($v) {
    	$this->_SqlDetailOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Report group SQL
	function GroupSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlGroupSelect(), $this->getSqlGroupWhere(),
			 $this->getSqlGroupGroupBy(), $this->getSqlGroupHaving(),
			 $this->getSqlGroupOrderBy(), $sFilter, $sSort);
	}

	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlDetailSelect(), $this->getSqlDetailWhere(),
			$this->getSqlDetailGroupBy(), $this->getSqlDetailHaving(),
			$this->getSqlDetailOrderBy(), $sFilter, $sSort);
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "r_listado_totales_por_hoja_rutareport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "r_listado_totales_por_hoja_rutareport.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("", $this->UrlParm($parm));
		else
			return $this->KeyUrl("", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "?" . $this->UrlParm($parm);
		else
			return "";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->codigo->CurrentValue)) {
			$sUrl .= "codigo=" . urlencode($this->codigo->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["codigo"]; // codigo

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->codigo->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$r_listado_totales_por_hoja_ruta_report = NULL; // Initialize page object first

class cr_listado_totales_por_hoja_ruta_report extends cr_listado_totales_por_hoja_ruta {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'r_listado_totales_por_hoja_ruta';

	// Page object name
	var $PageObjName = 'r_listado_totales_por_hoja_ruta_report';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		return TRUE;
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

		// Parent constuctor
		parent::__construct();

		// Table object (r_listado_totales_por_hoja_ruta)
		if (!isset($GLOBALS["r_listado_totales_por_hoja_ruta"]) || get_class($GLOBALS["r_listado_totales_por_hoja_ruta"]) == "cr_listado_totales_por_hoja_ruta") {
			$GLOBALS["r_listado_totales_por_hoja_ruta"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["r_listado_totales_por_hoja_ruta"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'r_listado_totales_por_hoja_ruta', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";
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
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if (!$Security->CanReport()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();

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
		global $EW_EXPORT_REPORT;
		if ($this->Export <> "" && array_key_exists($this->Export, $EW_EXPORT_REPORT)) {
			$sContent = ob_get_contents();
			$fn = $EW_EXPORT_REPORT[$this->Export];
			$this->$fn($sContent);
		}
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
	var $ExportOptions; // Export options
	var $RecCnt = 0;
	var $ReportSql = "";
	var $ReportFilter = "";
	var $DefaultFilter = "";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $MasterRecordExists;
	var $Command;
	var $DtlRecordCount;
	var $ReportGroups;
	var $ReportCounts;
	var $LevelBreak;
	var $ReportTotals;
	var $ReportMaxs;
	var $ReportMins;
	var $Recordset;
	var $DetailRecordset;
	var $RecordExists;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$this->ReportGroups = &ew_InitArray(3, NULL);
		$this->ReportCounts = &ew_InitArray(3, 0);
		$this->LevelBreak = &ew_InitArray(3, FALSE);
		$this->ReportTotals = &ew_Init2DArray(3, 9, 0);
		$this->ReportMaxs = &ew_Init2DArray(3, 9, 0);
		$this->ReportMins = &ew_Init2DArray(3, 9, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		$this->LevelBreak[2] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
			$this->LevelBreak[2] = TRUE;
		} else {
			if (!ew_CompareValue($this->Patente->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
				$this->LevelBreak[2] = TRUE;
			}
			if (!ew_CompareValue($this->responsable->CurrentValue, $this->ReportGroups[1])) {
				$this->LevelBreak[2] = TRUE;
			}
		}
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->tarifa->FormValue == $this->tarifa->CurrentValue && is_numeric(ew_StrToFloat($this->tarifa->CurrentValue)))
			$this->tarifa->CurrentValue = ew_StrToFloat($this->tarifa->CurrentValue);

		// Convert decimal values if posted back
		if ($this->sub_total->FormValue == $this->sub_total->CurrentValue && is_numeric(ew_StrToFloat($this->sub_total->CurrentValue)))
			$this->sub_total->CurrentValue = ew_StrToFloat($this->sub_total->CurrentValue);

		// Convert decimal values if posted back
		if ($this->porcentaje->FormValue == $this->porcentaje->CurrentValue && is_numeric(ew_StrToFloat($this->porcentaje->CurrentValue)))
			$this->porcentaje->CurrentValue = ew_StrToFloat($this->porcentaje->CurrentValue);

		// Convert decimal values if posted back
		if ($this->comision_chofer->FormValue == $this->comision_chofer->CurrentValue && is_numeric(ew_StrToFloat($this->comision_chofer->CurrentValue)))
			$this->comision_chofer->CurrentValue = ew_StrToFloat($this->comision_chofer->CurrentValue);

		// Convert decimal values if posted back
		if ($this->adelanto->FormValue == $this->adelanto->CurrentValue && is_numeric(ew_StrToFloat($this->adelanto->CurrentValue)))
			$this->adelanto->CurrentValue = ew_StrToFloat($this->adelanto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// responsable
		// Patente
		// kg_carga
		// tarifa
		// sub_total
		// porcentaje
		// comision_chofer
		// adelanto
		// total

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// responsable
			$this->responsable->ViewValue = $this->responsable->CurrentValue;
			$this->responsable->ViewCustomAttributes = "";

			// Patente
			$this->Patente->ViewValue = $this->Patente->CurrentValue;
			$this->Patente->ViewCustomAttributes = "";

			// kg_carga
			$this->kg_carga->ViewValue = $this->kg_carga->CurrentValue;
			$this->kg_carga->ViewCustomAttributes = "";

			// tarifa
			$this->tarifa->ViewValue = $this->tarifa->CurrentValue;
			$this->tarifa->ViewCustomAttributes = "";

			// sub_total
			$this->sub_total->ViewValue = $this->sub_total->CurrentValue;
			$this->sub_total->ViewValue = ew_FormatCurrency($this->sub_total->ViewValue, 2, -2, -2, -2);
			$this->sub_total->ViewCustomAttributes = "";

			// porcentaje
			$this->porcentaje->ViewValue = $this->porcentaje->CurrentValue;
			$this->porcentaje->ViewValue = ew_FormatPercent($this->porcentaje->ViewValue, 2, -2, -2, -2);
			$this->porcentaje->ViewCustomAttributes = "";

			// comision_chofer
			$this->comision_chofer->ViewValue = $this->comision_chofer->CurrentValue;
			$this->comision_chofer->ViewValue = ew_FormatCurrency($this->comision_chofer->ViewValue, 2, -2, -2, -2);
			$this->comision_chofer->ViewCustomAttributes = "";

			// adelanto
			$this->adelanto->ViewValue = $this->adelanto->CurrentValue;
			$this->adelanto->ViewValue = ew_FormatCurrency($this->adelanto->ViewValue, 2, -2, -2, -2);
			$this->adelanto->ViewCustomAttributes = "";

			// total
			$this->total->ViewValue = $this->total->CurrentValue;
			$this->total->ViewValue = ew_FormatCurrency($this->total->ViewValue, 2, -2, -2, -2);
			$this->total->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// responsable
			$this->responsable->LinkCustomAttributes = "";
			$this->responsable->HrefValue = "";
			$this->responsable->TooltipValue = "";

			// Patente
			$this->Patente->LinkCustomAttributes = "";
			$this->Patente->HrefValue = "";
			$this->Patente->TooltipValue = "";

			// kg_carga
			$this->kg_carga->LinkCustomAttributes = "";
			$this->kg_carga->HrefValue = "";
			$this->kg_carga->TooltipValue = "";

			// tarifa
			$this->tarifa->LinkCustomAttributes = "";
			$this->tarifa->HrefValue = "";
			$this->tarifa->TooltipValue = "";

			// sub_total
			$this->sub_total->LinkCustomAttributes = "";
			$this->sub_total->HrefValue = "";
			$this->sub_total->TooltipValue = "";

			// porcentaje
			$this->porcentaje->LinkCustomAttributes = "";
			$this->porcentaje->HrefValue = "";
			$this->porcentaje->TooltipValue = "";

			// comision_chofer
			$this->comision_chofer->LinkCustomAttributes = "";
			$this->comision_chofer->HrefValue = "";
			$this->comision_chofer->TooltipValue = "";

			// adelanto
			$this->adelanto->LinkCustomAttributes = "";
			$this->adelanto->HrefValue = "";
			$this->adelanto->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("report", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Export report to HTML
	function ExportReportHtml($html) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');
		//echo $html;

	}

	// Export report to WORD
	function ExportReportWord($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-word' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
		echo $html;
	}

	// Export report to EXCEL
	function ExportReportExcel($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-excel' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		echo $html;
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
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		$razon_social = 'CintesSoft Sistemas';
		$listado = 'Listado de hoja de rutas...';
		$header = "
	<table cellspacing='0' border=1 width='100%'>
		<thead>
			<tr class='ewTableHeader'>
				<td rowspan='2'>
					<image src='./phpimages/phpmkrlogo11.png' width='400' height='90'>
				</td>
				<td align='center'>
					<FONT FACE='impact' SIZE=5>".$razon_social."</font>
				</td>
			</tr>
			<tr class='ewTableHeader'>
				<td align='center'>".$listado."</td>
			</tr>    
		</thead>          
	</table>";
	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($r_listado_totales_por_hoja_ruta_report)) $r_listado_totales_por_hoja_ruta_report = new cr_listado_totales_por_hoja_ruta_report();

// Page init
$r_listado_totales_por_hoja_ruta_report->Page_Init();

// Page main
$r_listado_totales_por_hoja_ruta_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$r_listado_totales_por_hoja_ruta_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($r_listado_totales_por_hoja_ruta->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<div class="ewToolbar">
<?php if ($r_listado_totales_por_hoja_ruta->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($r_listado_totales_por_hoja_ruta->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
$r_listado_totales_por_hoja_ruta_report->DefaultFilter = "";
$r_listado_totales_por_hoja_ruta_report->ReportFilter = $r_listado_totales_por_hoja_ruta_report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($r_listado_totales_por_hoja_ruta_report->ReportFilter <> "") $r_listado_totales_por_hoja_ruta_report->ReportFilter .= " AND ";
	$r_listado_totales_por_hoja_ruta_report->ReportFilter .= "(0=1)";
}
if ($r_listado_totales_por_hoja_ruta_report->DbDetailFilter <> "") {
	if ($r_listado_totales_por_hoja_ruta_report->ReportFilter <> "") $r_listado_totales_por_hoja_ruta_report->ReportFilter .= " AND ";
	$r_listado_totales_por_hoja_ruta_report->ReportFilter .= "(" . $r_listado_totales_por_hoja_ruta_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$r_listado_totales_por_hoja_ruta->CurrentFilter = $r_listado_totales_por_hoja_ruta_report->ReportFilter;
$r_listado_totales_por_hoja_ruta_report->ReportSql = $r_listado_totales_por_hoja_ruta->GroupSQL();

// Load recordset
$r_listado_totales_por_hoja_ruta_report->Recordset = $conn->Execute($r_listado_totales_por_hoja_ruta_report->ReportSql);
$r_listado_totales_por_hoja_ruta_report->RecordExists = !$r_listado_totales_por_hoja_ruta_report->Recordset->EOF;
?>
<?php if ($r_listado_totales_por_hoja_ruta->Export == "") { ?>
<?php if ($r_listado_totales_por_hoja_ruta_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $r_listado_totales_por_hoja_ruta_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $r_listado_totales_por_hoja_ruta_report->ShowPageHeader(); ?>
<table class="ewReportTable">
<?php

// Get First Row
if ($r_listado_totales_por_hoja_ruta_report->RecordExists) {
	$r_listado_totales_por_hoja_ruta->Patente->setDbValue($r_listado_totales_por_hoja_ruta_report->Recordset->fields('Patente'));
	$r_listado_totales_por_hoja_ruta_report->ReportGroups[0] = $r_listado_totales_por_hoja_ruta->Patente->DbValue;
	$r_listado_totales_por_hoja_ruta->responsable->setDbValue($r_listado_totales_por_hoja_ruta_report->Recordset->fields('responsable'));
	$r_listado_totales_por_hoja_ruta_report->ReportGroups[1] = $r_listado_totales_por_hoja_ruta->responsable->DbValue;
}
$r_listado_totales_por_hoja_ruta_report->RecCnt = 0;
$r_listado_totales_por_hoja_ruta_report->ReportCounts[0] = 0;
$r_listado_totales_por_hoja_ruta_report->ChkLvlBreak();
while (!$r_listado_totales_por_hoja_ruta_report->Recordset->EOF) {

	// Render for view
	$r_listado_totales_por_hoja_ruta->RowType = EW_ROWTYPE_VIEW;
	$r_listado_totales_por_hoja_ruta->ResetAttrs();
	$r_listado_totales_por_hoja_ruta_report->RenderRow();

	// Show group headers
	if ($r_listado_totales_por_hoja_ruta_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td colspan=2 class="ewGroupField"><?php echo $r_listado_totales_por_hoja_ruta->Patente->FldCaption() ?></td>
	<td colspan=8 class="ewGroupName">
<span<?php echo $r_listado_totales_por_hoja_ruta->Patente->ViewAttributes() ?>>
<?php echo $r_listado_totales_por_hoja_ruta->Patente->ViewValue ?></span>
</td></tr>
<?php
	}
	if ($r_listado_totales_por_hoja_ruta_report->LevelBreak[2]) { // Reset counter and aggregation
?>
	<tr><td><div class="ewGroupIndent"></div></td><td class="ewGroupField"><?php echo $r_listado_totales_por_hoja_ruta->responsable->FldCaption() ?></td>
	<td colspan=8 class="ewGroupName">
<span<?php echo $r_listado_totales_por_hoja_ruta->responsable->ViewAttributes() ?>>
<?php echo $r_listado_totales_por_hoja_ruta->responsable->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$r_listado_totales_por_hoja_ruta_report->ReportFilter = $r_listado_totales_por_hoja_ruta_report->DefaultFilter;
	if ($r_listado_totales_por_hoja_ruta_report->ReportFilter <> "") $r_listado_totales_por_hoja_ruta_report->ReportFilter .= " AND ";
	if (is_null($r_listado_totales_por_hoja_ruta->Patente->CurrentValue)) {
		$r_listado_totales_por_hoja_ruta_report->ReportFilter .= "(`Patente` IS NULL)";
	} else {
		$r_listado_totales_por_hoja_ruta_report->ReportFilter .= "(`Patente` = '" . ew_AdjustSql($r_listado_totales_por_hoja_ruta->Patente->CurrentValue) . "')";
	}
	if ($r_listado_totales_por_hoja_ruta_report->ReportFilter <> "") $r_listado_totales_por_hoja_ruta_report->ReportFilter .= " AND ";
	if (is_null($r_listado_totales_por_hoja_ruta->responsable->CurrentValue)) {
		$r_listado_totales_por_hoja_ruta_report->ReportFilter .= "(`responsable` IS NULL)";
	} else {
		$r_listado_totales_por_hoja_ruta_report->ReportFilter .= "(`responsable` = '" . ew_AdjustSql($r_listado_totales_por_hoja_ruta->responsable->CurrentValue) . "')";
	}
	if ($r_listado_totales_por_hoja_ruta_report->DbDetailFilter <> "") {
		if ($r_listado_totales_por_hoja_ruta_report->ReportFilter <> "")
			$r_listado_totales_por_hoja_ruta_report->ReportFilter .= " AND ";
		$r_listado_totales_por_hoja_ruta_report->ReportFilter .= "(" . $r_listado_totales_por_hoja_ruta_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$r_listado_totales_por_hoja_ruta->CurrentFilter = $r_listado_totales_por_hoja_ruta_report->ReportFilter;
	$r_listado_totales_por_hoja_ruta_report->ReportSql = $r_listado_totales_por_hoja_ruta->DetailSQL();

	// Load detail records
	$r_listado_totales_por_hoja_ruta_report->DetailRecordset = $conn->Execute($r_listado_totales_por_hoja_ruta_report->ReportSql);
	$r_listado_totales_por_hoja_ruta_report->DtlRecordCount = $r_listado_totales_por_hoja_ruta_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$r_listado_totales_por_hoja_ruta_report->DetailRecordset->EOF) {
		$r_listado_totales_por_hoja_ruta_report->RecCnt++;
		$r_listado_totales_por_hoja_ruta->sub_total->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('sub_total'));
		$r_listado_totales_por_hoja_ruta->comision_chofer->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('comision_chofer'));
		$r_listado_totales_por_hoja_ruta->total->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('total'));
	}
	if ($r_listado_totales_por_hoja_ruta_report->RecCnt == 1) {
		$r_listado_totales_por_hoja_ruta_report->ReportCounts[0] = 0;
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[0][3] = 0;
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[0][5] = 0;
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[0][7] = 0;
	}
	for ($i = 1; $i <= 2; $i++) {
		if ($r_listado_totales_por_hoja_ruta_report->LevelBreak[$i]) { // Reset counter and aggregation
			$r_listado_totales_por_hoja_ruta_report->ReportCounts[$i] = 0;
			$r_listado_totales_por_hoja_ruta_report->ReportTotals[$i][3] = 0;
			$r_listado_totales_por_hoja_ruta_report->ReportTotals[$i][5] = 0;
			$r_listado_totales_por_hoja_ruta_report->ReportTotals[$i][7] = 0;
		}
	}
	$r_listado_totales_por_hoja_ruta_report->ReportCounts[0] += $r_listado_totales_por_hoja_ruta_report->DtlRecordCount;
	$r_listado_totales_por_hoja_ruta_report->ReportCounts[1] += $r_listado_totales_por_hoja_ruta_report->DtlRecordCount;
	$r_listado_totales_por_hoja_ruta_report->ReportCounts[2] += $r_listado_totales_por_hoja_ruta_report->DtlRecordCount;
	if ($r_listado_totales_por_hoja_ruta_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $r_listado_totales_por_hoja_ruta->codigo->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_totales_por_hoja_ruta->kg_carga->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_totales_por_hoja_ruta->tarifa->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_totales_por_hoja_ruta->sub_total->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_totales_por_hoja_ruta->porcentaje->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_totales_por_hoja_ruta->comision_chofer->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_totales_por_hoja_ruta->adelanto->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_totales_por_hoja_ruta->total->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$r_listado_totales_por_hoja_ruta_report->DetailRecordset->EOF) {
		$r_listado_totales_por_hoja_ruta->codigo->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('codigo'));
		$r_listado_totales_por_hoja_ruta->kg_carga->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('kg_carga'));
		$r_listado_totales_por_hoja_ruta->tarifa->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('tarifa'));
		$r_listado_totales_por_hoja_ruta->sub_total->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('sub_total'));
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[0][3] += $r_listado_totales_por_hoja_ruta->sub_total->CurrentValue;
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[1][3] += $r_listado_totales_por_hoja_ruta->sub_total->CurrentValue;
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[2][3] += $r_listado_totales_por_hoja_ruta->sub_total->CurrentValue;
		$r_listado_totales_por_hoja_ruta->porcentaje->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('porcentaje'));
		$r_listado_totales_por_hoja_ruta->comision_chofer->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('comision_chofer'));
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[0][5] += $r_listado_totales_por_hoja_ruta->comision_chofer->CurrentValue;
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[1][5] += $r_listado_totales_por_hoja_ruta->comision_chofer->CurrentValue;
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[2][5] += $r_listado_totales_por_hoja_ruta->comision_chofer->CurrentValue;
		$r_listado_totales_por_hoja_ruta->adelanto->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('adelanto'));
		$r_listado_totales_por_hoja_ruta->total->setDbValue($r_listado_totales_por_hoja_ruta_report->DetailRecordset->fields('total'));
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[0][7] += $r_listado_totales_por_hoja_ruta->total->CurrentValue;
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[1][7] += $r_listado_totales_por_hoja_ruta->total->CurrentValue;
		$r_listado_totales_por_hoja_ruta_report->ReportTotals[2][7] += $r_listado_totales_por_hoja_ruta->total->CurrentValue;

		// Render for view
		$r_listado_totales_por_hoja_ruta->RowType = EW_ROWTYPE_VIEW;
		$r_listado_totales_por_hoja_ruta->ResetAttrs();
		$r_listado_totales_por_hoja_ruta_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $r_listado_totales_por_hoja_ruta->codigo->CellAttributes() ?>>
<span<?php echo $r_listado_totales_por_hoja_ruta->codigo->ViewAttributes() ?>>
<?php echo $r_listado_totales_por_hoja_ruta->codigo->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_totales_por_hoja_ruta->kg_carga->CellAttributes() ?>>
<span<?php echo $r_listado_totales_por_hoja_ruta->kg_carga->ViewAttributes() ?>>
<?php echo $r_listado_totales_por_hoja_ruta->kg_carga->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_totales_por_hoja_ruta->tarifa->CellAttributes() ?>>
<span<?php echo $r_listado_totales_por_hoja_ruta->tarifa->ViewAttributes() ?>>
<?php echo $r_listado_totales_por_hoja_ruta->tarifa->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_totales_por_hoja_ruta->sub_total->CellAttributes() ?>>
<span<?php echo $r_listado_totales_por_hoja_ruta->sub_total->ViewAttributes() ?>>
<?php echo $r_listado_totales_por_hoja_ruta->sub_total->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_totales_por_hoja_ruta->porcentaje->CellAttributes() ?>>
<span<?php echo $r_listado_totales_por_hoja_ruta->porcentaje->ViewAttributes() ?>>
<?php echo $r_listado_totales_por_hoja_ruta->porcentaje->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_totales_por_hoja_ruta->comision_chofer->CellAttributes() ?>>
<span<?php echo $r_listado_totales_por_hoja_ruta->comision_chofer->ViewAttributes() ?>>
<?php echo $r_listado_totales_por_hoja_ruta->comision_chofer->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_totales_por_hoja_ruta->adelanto->CellAttributes() ?>>
<span<?php echo $r_listado_totales_por_hoja_ruta->adelanto->ViewAttributes() ?>>
<?php echo $r_listado_totales_por_hoja_ruta->adelanto->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_totales_por_hoja_ruta->total->CellAttributes() ?>>
<span<?php echo $r_listado_totales_por_hoja_ruta->total->ViewAttributes() ?>>
<?php echo $r_listado_totales_por_hoja_ruta->total->ViewValue ?></span>
</td>
	</tr>
<?php
		$r_listado_totales_por_hoja_ruta_report->DetailRecordset->MoveNext();
	}
	$r_listado_totales_por_hoja_ruta_report->DetailRecordset->Close();

	// Save old group data
	$r_listado_totales_por_hoja_ruta_report->ReportGroups[0] = $r_listado_totales_por_hoja_ruta->Patente->CurrentValue;
	$r_listado_totales_por_hoja_ruta_report->ReportGroups[1] = $r_listado_totales_por_hoja_ruta->responsable->CurrentValue;

	// Get next record
	$r_listado_totales_por_hoja_ruta_report->Recordset->MoveNext();
	if ($r_listado_totales_por_hoja_ruta_report->Recordset->EOF) {
		$r_listado_totales_por_hoja_ruta_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$r_listado_totales_por_hoja_ruta->Patente->setDbValue($r_listado_totales_por_hoja_ruta_report->Recordset->fields('Patente'));
		$r_listado_totales_por_hoja_ruta->responsable->setDbValue($r_listado_totales_por_hoja_ruta_report->Recordset->fields('responsable'));
	}
	$r_listado_totales_por_hoja_ruta_report->ChkLvlBreak();

	// Show footers
	if ($r_listado_totales_por_hoja_ruta_report->LevelBreak[2]) {
		$r_listado_totales_por_hoja_ruta->responsable->CurrentValue = $r_listado_totales_por_hoja_ruta_report->ReportGroups[1];

		// Render row for view
		$r_listado_totales_por_hoja_ruta->RowType = EW_ROWTYPE_VIEW;
		$r_listado_totales_por_hoja_ruta->ResetAttrs();
		$r_listado_totales_por_hoja_ruta_report->RenderRow();
		$r_listado_totales_por_hoja_ruta->responsable->CurrentValue = $r_listado_totales_por_hoja_ruta->responsable->DbValue;
?>
<?php
}
	if ($r_listado_totales_por_hoja_ruta_report->LevelBreak[1]) {
		$r_listado_totales_por_hoja_ruta->Patente->CurrentValue = $r_listado_totales_por_hoja_ruta_report->ReportGroups[0];

		// Render row for view
		$r_listado_totales_por_hoja_ruta->RowType = EW_ROWTYPE_VIEW;
		$r_listado_totales_por_hoja_ruta->ResetAttrs();
		$r_listado_totales_por_hoja_ruta_report->RenderRow();
		$r_listado_totales_por_hoja_ruta->Patente->CurrentValue = $r_listado_totales_por_hoja_ruta->Patente->DbValue;
?>
<?php
}
}

// Close recordset
$r_listado_totales_por_hoja_ruta_report->Recordset->Close();
?>
<?php if ($r_listado_totales_por_hoja_ruta_report->RecordExists) { ?>
	<tr><td colspan=10>&nbsp;<br></td></tr>
	<tr><td colspan=10 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($r_listado_totales_por_hoja_ruta_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($r_listado_totales_por_hoja_ruta_report->RecordExists) { ?>
	<tr><td colspan=10>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
<?php
$r_listado_totales_por_hoja_ruta_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($r_listado_totales_por_hoja_ruta->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$r_listado_totales_por_hoja_ruta_report->Page_Terminate();
?>
