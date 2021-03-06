<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_phpfn11.php" ?>
<?php

// Global variable for table object
$r_listado_socios_por_actividad_y_rubro = NULL;

//
// Table class for r_listado_socios_por_actividad_y_rubro
//
class cr_listado_socios_por_actividad_y_rubro extends cTableBase {
	var $id_rubro;
	var $id_actividad;
	var $rubro;
	var $actividad;
	var $cuit_cuil;
	var $propietario;
	var $comercio;
	var $direccion_comercio;
	var $mail;
	var $tel;
	var $cel;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'r_listado_socios_por_actividad_y_rubro';
		$this->TableName = 'r_listado_socios_por_actividad_y_rubro';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// id_rubro
		$this->id_rubro = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_id_rubro', 'id_rubro', '`id_rubro`', '`id_rubro`', 3, -1, FALSE, '`id_rubro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_rubro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_rubro'] = &$this->id_rubro;

		// id_actividad
		$this->id_actividad = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_id_actividad', 'id_actividad', '`id_actividad`', '`id_actividad`', 3, -1, FALSE, '`id_actividad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_actividad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_actividad'] = &$this->id_actividad;

		// rubro
		$this->rubro = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_rubro', 'rubro', '`rubro`', '`rubro`', 200, -1, FALSE, '`rubro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['rubro'] = &$this->rubro;

		// actividad
		$this->actividad = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_actividad', 'actividad', '`actividad`', '`actividad`', 200, -1, FALSE, '`actividad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['actividad'] = &$this->actividad;

		// cuit_cuil
		$this->cuit_cuil = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_cuit_cuil', 'cuit_cuil', '`cuit_cuil`', '`cuit_cuil`', 200, -1, FALSE, '`cuit_cuil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cuit_cuil'] = &$this->cuit_cuil;

		// propietario
		$this->propietario = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_propietario', 'propietario', '`propietario`', '`propietario`', 200, -1, FALSE, '`propietario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['propietario'] = &$this->propietario;

		// comercio
		$this->comercio = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_comercio', 'comercio', '`comercio`', '`comercio`', 200, -1, FALSE, '`comercio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['comercio'] = &$this->comercio;

		// direccion_comercio
		$this->direccion_comercio = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_direccion_comercio', 'direccion_comercio', '`direccion_comercio`', '`direccion_comercio`', 200, -1, FALSE, '`direccion_comercio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['direccion_comercio'] = &$this->direccion_comercio;

		// mail
		$this->mail = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_mail', 'mail', '`mail`', '`mail`', 200, -1, FALSE, '`mail`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['mail'] = &$this->mail;

		// tel
		$this->tel = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_tel', 'tel', '`tel`', '`tel`', 200, -1, FALSE, '`tel`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tel'] = &$this->tel;

		// cel
		$this->cel = new cField('r_listado_socios_por_actividad_y_rubro', 'r_listado_socios_por_actividad_y_rubro', 'x_cel', 'cel', '`cel`', '`cel`', 200, -1, FALSE, '`cel`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cel'] = &$this->cel;
	}

	// Report group level SQL
	var $_SqlGroupSelect = "";

	function getSqlGroupSelect() { // Select
		return ($this->_SqlGroupSelect <> "") ? $this->_SqlGroupSelect : "SELECT DISTINCT `rubro`,`actividad` FROM `v_db_rubro_actividad_socio`";
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
		return ($this->_SqlGroupOrderBy <> "") ? $this->_SqlGroupOrderBy : "`rubro` ASC,`actividad` ASC";
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
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT * FROM `v_db_rubro_actividad_socio`";
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
		return ($this->_SqlDetailOrderBy <> "") ? $this->_SqlDetailOrderBy : "`comercio` ASC,`propietario` ASC,`direccion_comercio` ASC";
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
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "cciag_login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "cciag_r_listado_socios_por_actividad_y_rubroreport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "cciag_r_listado_socios_por_actividad_y_rubroreport.php";
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

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
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
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$r_listado_socios_por_actividad_y_rubro_report = NULL; // Initialize page object first

class cr_listado_socios_por_actividad_y_rubro_report extends cr_listado_socios_por_actividad_y_rubro {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'r_listado_socios_por_actividad_y_rubro';

	// Page object name
	var $PageObjName = 'r_listado_socios_por_actividad_y_rubro_report';

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

		// Table object (r_listado_socios_por_actividad_y_rubro)
		if (!isset($GLOBALS["r_listado_socios_por_actividad_y_rubro"]) || get_class($GLOBALS["r_listado_socios_por_actividad_y_rubro"]) == "cr_listado_socios_por_actividad_y_rubro") {
			$GLOBALS["r_listado_socios_por_actividad_y_rubro"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["r_listado_socios_por_actividad_y_rubro"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'r_listado_socios_por_actividad_y_rubro', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("cciag_login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
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
			if ($this->Export == "email") { // Email
				ob_end_clean();
				$conn->Close(); // Close connection
				header("Location: " . ew_CurrentPage());
				exit();
			}
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
		$this->ReportTotals = &ew_Init2DArray(3, 8, 0);
		$this->ReportMaxs = &ew_Init2DArray(3, 8, 0);
		$this->ReportMins = &ew_Init2DArray(3, 8, 0);

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
			if (!ew_CompareValue($this->rubro->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
				$this->LevelBreak[2] = TRUE;
			}
			if (!ew_CompareValue($this->actividad->CurrentValue, $this->ReportGroups[1])) {
				$this->LevelBreak[2] = TRUE;
			}
		}
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_rubro
		// id_actividad
		// rubro
		// actividad
		// cuit_cuil
		// propietario
		// comercio
		// direccion_comercio
		// mail
		// tel
		// cel

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// rubro
			$this->rubro->ViewValue = $this->rubro->CurrentValue;
			$this->rubro->ViewCustomAttributes = "";

			// actividad
			$this->actividad->ViewValue = $this->actividad->CurrentValue;
			$this->actividad->ViewCustomAttributes = "";

			// cuit_cuil
			$this->cuit_cuil->ViewValue = $this->cuit_cuil->CurrentValue;
			$this->cuit_cuil->ViewCustomAttributes = "";

			// propietario
			$this->propietario->ViewValue = $this->propietario->CurrentValue;
			$this->propietario->ViewCustomAttributes = "";

			// comercio
			$this->comercio->ViewValue = $this->comercio->CurrentValue;
			$this->comercio->ViewCustomAttributes = "";

			// direccion_comercio
			$this->direccion_comercio->ViewValue = $this->direccion_comercio->CurrentValue;
			$this->direccion_comercio->ViewCustomAttributes = "";

			// mail
			$this->mail->ViewValue = $this->mail->CurrentValue;
			$this->mail->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewCustomAttributes = "";

			// rubro
			$this->rubro->LinkCustomAttributes = "";
			$this->rubro->HrefValue = "";
			$this->rubro->TooltipValue = "";

			// actividad
			$this->actividad->LinkCustomAttributes = "";
			$this->actividad->HrefValue = "";
			$this->actividad->TooltipValue = "";

			// cuit_cuil
			$this->cuit_cuil->LinkCustomAttributes = "";
			$this->cuit_cuil->HrefValue = "";
			$this->cuit_cuil->TooltipValue = "";

			// propietario
			$this->propietario->LinkCustomAttributes = "";
			$this->propietario->HrefValue = "";
			$this->propietario->TooltipValue = "";

			// comercio
			$this->comercio->LinkCustomAttributes = "";
			$this->comercio->HrefValue = "";
			$this->comercio->TooltipValue = "";

			// direccion_comercio
			$this->direccion_comercio->LinkCustomAttributes = "";
			$this->direccion_comercio->HrefValue = "";
			$this->direccion_comercio->TooltipValue = "";

			// mail
			$this->mail->LinkCustomAttributes = "";
			$this->mail->HrefValue = "";
			$this->mail->TooltipValue = "";

			// tel
			$this->tel->LinkCustomAttributes = "";
			$this->tel->HrefValue = "";
			$this->tel->TooltipValue = "";

			// cel
			$this->cel->LinkCustomAttributes = "";
			$this->cel->HrefValue = "";
			$this->cel->TooltipValue = "";
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
		$url = ew_CurrentUrl();
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
		//$header = "your header";

		$razon_social = 'CCIAG - Sauce Viejo';
		$listado = 'Listado socios por actividad y rubros';
		$header = "<table cellspacing='0' border=1 width='100%'>
		<thead>
			<tr class='ewTableHeader'>
				<td rowspan='2'>
					<image src='./inicio/logo_reporte.jpg' width='400' height='90'>
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
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($r_listado_socios_por_actividad_y_rubro_report)) $r_listado_socios_por_actividad_y_rubro_report = new cr_listado_socios_por_actividad_y_rubro_report();

// Page init
$r_listado_socios_por_actividad_y_rubro_report->Page_Init();

// Page main
$r_listado_socios_por_actividad_y_rubro_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$r_listado_socios_por_actividad_y_rubro_report->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_header.php" ?>
<?php if ($r_listado_socios_por_actividad_y_rubro->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<div class="ewToolbar">
<?php if ($r_listado_socios_por_actividad_y_rubro->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($r_listado_socios_por_actividad_y_rubro->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
$r_listado_socios_por_actividad_y_rubro_report->DefaultFilter = "";
$r_listado_socios_por_actividad_y_rubro_report->ReportFilter = $r_listado_socios_por_actividad_y_rubro_report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($r_listado_socios_por_actividad_y_rubro_report->ReportFilter <> "") $r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= " AND ";
	$r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= "(0=1)";
}
if ($r_listado_socios_por_actividad_y_rubro_report->DbDetailFilter <> "") {
	if ($r_listado_socios_por_actividad_y_rubro_report->ReportFilter <> "") $r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= " AND ";
	$r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= "(" . $r_listado_socios_por_actividad_y_rubro_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$r_listado_socios_por_actividad_y_rubro->CurrentFilter = $r_listado_socios_por_actividad_y_rubro_report->ReportFilter;
$r_listado_socios_por_actividad_y_rubro_report->ReportSql = $r_listado_socios_por_actividad_y_rubro->GroupSQL();

// Load recordset
$r_listado_socios_por_actividad_y_rubro_report->Recordset = $conn->Execute($r_listado_socios_por_actividad_y_rubro_report->ReportSql);
$r_listado_socios_por_actividad_y_rubro_report->RecordExists = !$r_listado_socios_por_actividad_y_rubro_report->Recordset->EOF;
?>
<?php if ($r_listado_socios_por_actividad_y_rubro->Export == "") { ?>
<?php if ($r_listado_socios_por_actividad_y_rubro_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $r_listado_socios_por_actividad_y_rubro_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $r_listado_socios_por_actividad_y_rubro_report->ShowPageHeader(); ?>
<form method="post">
<?php if ($r_listado_socios_por_actividad_y_rubro_report->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $r_listado_socios_por_actividad_y_rubro_report->Token ?>">
<?php } ?>
<table class="ewReportTable">
<?php

// Get First Row
if ($r_listado_socios_por_actividad_y_rubro_report->RecordExists) {
	$r_listado_socios_por_actividad_y_rubro->rubro->setDbValue($r_listado_socios_por_actividad_y_rubro_report->Recordset->fields('rubro'));
	$r_listado_socios_por_actividad_y_rubro_report->ReportGroups[0] = $r_listado_socios_por_actividad_y_rubro->rubro->DbValue;
	$r_listado_socios_por_actividad_y_rubro->actividad->setDbValue($r_listado_socios_por_actividad_y_rubro_report->Recordset->fields('actividad'));
	$r_listado_socios_por_actividad_y_rubro_report->ReportGroups[1] = $r_listado_socios_por_actividad_y_rubro->actividad->DbValue;
}
$r_listado_socios_por_actividad_y_rubro_report->RecCnt = 0;
$r_listado_socios_por_actividad_y_rubro_report->ReportCounts[0] = 0;
$r_listado_socios_por_actividad_y_rubro_report->ChkLvlBreak();
while (!$r_listado_socios_por_actividad_y_rubro_report->Recordset->EOF) {

	// Render for view
	$r_listado_socios_por_actividad_y_rubro->RowType = EW_ROWTYPE_VIEW;
	$r_listado_socios_por_actividad_y_rubro->ResetAttrs();
	$r_listado_socios_por_actividad_y_rubro_report->RenderRow();

	// Show group headers
	if ($r_listado_socios_por_actividad_y_rubro_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td colspan=2 class="ewGroupField"><?php echo $r_listado_socios_por_actividad_y_rubro->rubro->FldCaption() ?></td>
	<td colspan=7 class="ewGroupName">
<span<?php echo $r_listado_socios_por_actividad_y_rubro->rubro->ViewAttributes() ?>>
<?php echo $r_listado_socios_por_actividad_y_rubro->rubro->ViewValue ?></span>
</td></tr>
<?php
	}
	if ($r_listado_socios_por_actividad_y_rubro_report->LevelBreak[2]) { // Reset counter and aggregation
?>
	<tr><td><div class="ewGroupIndent"></div></td><td class="ewGroupField"><?php echo $r_listado_socios_por_actividad_y_rubro->actividad->FldCaption() ?></td>
	<td colspan=7 class="ewGroupName">
<span<?php echo $r_listado_socios_por_actividad_y_rubro->actividad->ViewAttributes() ?>>
<?php echo $r_listado_socios_por_actividad_y_rubro->actividad->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$r_listado_socios_por_actividad_y_rubro_report->ReportFilter = $r_listado_socios_por_actividad_y_rubro_report->DefaultFilter;
	if ($r_listado_socios_por_actividad_y_rubro_report->ReportFilter <> "") $r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= " AND ";
	if (is_null($r_listado_socios_por_actividad_y_rubro->rubro->CurrentValue)) {
		$r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= "(`rubro` IS NULL)";
	} else {
		$r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= "(`rubro` = '" . ew_AdjustSql($r_listado_socios_por_actividad_y_rubro->rubro->CurrentValue) . "')";
	}
	if ($r_listado_socios_por_actividad_y_rubro_report->ReportFilter <> "") $r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= " AND ";
	if (is_null($r_listado_socios_por_actividad_y_rubro->actividad->CurrentValue)) {
		$r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= "(`actividad` IS NULL)";
	} else {
		$r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= "(`actividad` = '" . ew_AdjustSql($r_listado_socios_por_actividad_y_rubro->actividad->CurrentValue) . "')";
	}
	if ($r_listado_socios_por_actividad_y_rubro_report->DbDetailFilter <> "") {
		if ($r_listado_socios_por_actividad_y_rubro_report->ReportFilter <> "")
			$r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= " AND ";
		$r_listado_socios_por_actividad_y_rubro_report->ReportFilter .= "(" . $r_listado_socios_por_actividad_y_rubro_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$r_listado_socios_por_actividad_y_rubro->CurrentFilter = $r_listado_socios_por_actividad_y_rubro_report->ReportFilter;
	$r_listado_socios_por_actividad_y_rubro_report->ReportSql = $r_listado_socios_por_actividad_y_rubro->DetailSQL();

	// Load detail records
	$r_listado_socios_por_actividad_y_rubro_report->DetailRecordset = $conn->Execute($r_listado_socios_por_actividad_y_rubro_report->ReportSql);
	$r_listado_socios_por_actividad_y_rubro_report->DtlRecordCount = $r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->EOF) {
		$r_listado_socios_por_actividad_y_rubro_report->RecCnt++;
	}
	if ($r_listado_socios_por_actividad_y_rubro_report->RecCnt == 1) {
		$r_listado_socios_por_actividad_y_rubro_report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 2; $i++) {
		if ($r_listado_socios_por_actividad_y_rubro_report->LevelBreak[$i]) { // Reset counter and aggregation
			$r_listado_socios_por_actividad_y_rubro_report->ReportCounts[$i] = 0;
		}
	}
	$r_listado_socios_por_actividad_y_rubro_report->ReportCounts[0] += $r_listado_socios_por_actividad_y_rubro_report->DtlRecordCount;
	$r_listado_socios_por_actividad_y_rubro_report->ReportCounts[1] += $r_listado_socios_por_actividad_y_rubro_report->DtlRecordCount;
	$r_listado_socios_por_actividad_y_rubro_report->ReportCounts[2] += $r_listado_socios_por_actividad_y_rubro_report->DtlRecordCount;
	if ($r_listado_socios_por_actividad_y_rubro_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $r_listado_socios_por_actividad_y_rubro->cuit_cuil->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_socios_por_actividad_y_rubro->propietario->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_socios_por_actividad_y_rubro->comercio->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_socios_por_actividad_y_rubro->direccion_comercio->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_socios_por_actividad_y_rubro->mail->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_socios_por_actividad_y_rubro->tel->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $r_listado_socios_por_actividad_y_rubro->cel->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->EOF) {
		$r_listado_socios_por_actividad_y_rubro->cuit_cuil->setDbValue($r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->fields('cuit_cuil'));
		$r_listado_socios_por_actividad_y_rubro->propietario->setDbValue($r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->fields('propietario'));
		$r_listado_socios_por_actividad_y_rubro->comercio->setDbValue($r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->fields('comercio'));
		$r_listado_socios_por_actividad_y_rubro->direccion_comercio->setDbValue($r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->fields('direccion_comercio'));
		$r_listado_socios_por_actividad_y_rubro->mail->setDbValue($r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->fields('mail'));
		$r_listado_socios_por_actividad_y_rubro->tel->setDbValue($r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->fields('tel'));
		$r_listado_socios_por_actividad_y_rubro->cel->setDbValue($r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->fields('cel'));

		// Render for view
		$r_listado_socios_por_actividad_y_rubro->RowType = EW_ROWTYPE_VIEW;
		$r_listado_socios_por_actividad_y_rubro->ResetAttrs();
		$r_listado_socios_por_actividad_y_rubro_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $r_listado_socios_por_actividad_y_rubro->cuit_cuil->CellAttributes() ?>>
<span<?php echo $r_listado_socios_por_actividad_y_rubro->cuit_cuil->ViewAttributes() ?>>
<?php echo $r_listado_socios_por_actividad_y_rubro->cuit_cuil->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_socios_por_actividad_y_rubro->propietario->CellAttributes() ?>>
<span<?php echo $r_listado_socios_por_actividad_y_rubro->propietario->ViewAttributes() ?>>
<?php echo $r_listado_socios_por_actividad_y_rubro->propietario->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_socios_por_actividad_y_rubro->comercio->CellAttributes() ?>>
<span<?php echo $r_listado_socios_por_actividad_y_rubro->comercio->ViewAttributes() ?>>
<?php echo $r_listado_socios_por_actividad_y_rubro->comercio->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_socios_por_actividad_y_rubro->direccion_comercio->CellAttributes() ?>>
<span<?php echo $r_listado_socios_por_actividad_y_rubro->direccion_comercio->ViewAttributes() ?>>
<?php echo $r_listado_socios_por_actividad_y_rubro->direccion_comercio->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_socios_por_actividad_y_rubro->mail->CellAttributes() ?>>
<span<?php echo $r_listado_socios_por_actividad_y_rubro->mail->ViewAttributes() ?>>
<?php echo $r_listado_socios_por_actividad_y_rubro->mail->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_socios_por_actividad_y_rubro->tel->CellAttributes() ?>>
<span<?php echo $r_listado_socios_por_actividad_y_rubro->tel->ViewAttributes() ?>>
<?php echo $r_listado_socios_por_actividad_y_rubro->tel->ViewValue ?></span>
</td>
		<td<?php echo $r_listado_socios_por_actividad_y_rubro->cel->CellAttributes() ?>>
<span<?php echo $r_listado_socios_por_actividad_y_rubro->cel->ViewAttributes() ?>>
<?php echo $r_listado_socios_por_actividad_y_rubro->cel->ViewValue ?></span>
</td>
	</tr>
<?php
		$r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->MoveNext();
	}
	$r_listado_socios_por_actividad_y_rubro_report->DetailRecordset->Close();

	// Save old group data
	$r_listado_socios_por_actividad_y_rubro_report->ReportGroups[0] = $r_listado_socios_por_actividad_y_rubro->rubro->CurrentValue;
	$r_listado_socios_por_actividad_y_rubro_report->ReportGroups[1] = $r_listado_socios_por_actividad_y_rubro->actividad->CurrentValue;

	// Get next record
	$r_listado_socios_por_actividad_y_rubro_report->Recordset->MoveNext();
	if ($r_listado_socios_por_actividad_y_rubro_report->Recordset->EOF) {
		$r_listado_socios_por_actividad_y_rubro_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$r_listado_socios_por_actividad_y_rubro->rubro->setDbValue($r_listado_socios_por_actividad_y_rubro_report->Recordset->fields('rubro'));
		$r_listado_socios_por_actividad_y_rubro->actividad->setDbValue($r_listado_socios_por_actividad_y_rubro_report->Recordset->fields('actividad'));
	}
	$r_listado_socios_por_actividad_y_rubro_report->ChkLvlBreak();

	// Show footers
	if ($r_listado_socios_por_actividad_y_rubro_report->LevelBreak[2]) {
		$r_listado_socios_por_actividad_y_rubro->actividad->CurrentValue = $r_listado_socios_por_actividad_y_rubro_report->ReportGroups[1];

		// Render row for view
		$r_listado_socios_por_actividad_y_rubro->RowType = EW_ROWTYPE_VIEW;
		$r_listado_socios_por_actividad_y_rubro->ResetAttrs();
		$r_listado_socios_por_actividad_y_rubro_report->RenderRow();
		$r_listado_socios_por_actividad_y_rubro->actividad->CurrentValue = $r_listado_socios_por_actividad_y_rubro->actividad->DbValue;
?>
	<tr><td><div class="ewGroupIndent"></div></td><td colspan=8 class="ewGroupSummary"><?php echo $Language->Phrase("RptSumHead") ?>&nbsp;<?php echo $r_listado_socios_por_actividad_y_rubro->actividad->FldCaption() ?>:&nbsp;<?php echo $r_listado_socios_por_actividad_y_rubro->actividad->ViewValue ?> (<?php echo ew_FormatNumber($r_listado_socios_por_actividad_y_rubro_report->ReportCounts[2],0) ?> <?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
	<tr><td colspan=9>&nbsp;<br></td></tr>
<?php
}
	if ($r_listado_socios_por_actividad_y_rubro_report->LevelBreak[1]) {
		$r_listado_socios_por_actividad_y_rubro->rubro->CurrentValue = $r_listado_socios_por_actividad_y_rubro_report->ReportGroups[0];

		// Render row for view
		$r_listado_socios_por_actividad_y_rubro->RowType = EW_ROWTYPE_VIEW;
		$r_listado_socios_por_actividad_y_rubro->ResetAttrs();
		$r_listado_socios_por_actividad_y_rubro_report->RenderRow();
		$r_listado_socios_por_actividad_y_rubro->rubro->CurrentValue = $r_listado_socios_por_actividad_y_rubro->rubro->DbValue;
?>
	<tr><td colspan=9 class="ewGroupSummary"><?php echo $Language->Phrase("RptSumHead") ?>&nbsp;<?php echo $r_listado_socios_por_actividad_y_rubro->rubro->FldCaption() ?>:&nbsp;<?php echo $r_listado_socios_por_actividad_y_rubro->rubro->ViewValue ?> (<?php echo ew_FormatNumber($r_listado_socios_por_actividad_y_rubro_report->ReportCounts[1],0) ?> <?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
	<tr><td colspan=9>&nbsp;<br></td></tr>
<?php
}
}

// Close recordset
$r_listado_socios_por_actividad_y_rubro_report->Recordset->Close();
?>
<?php if ($r_listado_socios_por_actividad_y_rubro_report->RecordExists) { ?>
	<tr><td colspan=9>&nbsp;<br></td></tr>
	<tr><td colspan=9 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($r_listado_socios_por_actividad_y_rubro_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($r_listado_socios_por_actividad_y_rubro_report->RecordExists) { ?>
	<tr><td colspan=9>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
</form>
<?php
$r_listado_socios_por_actividad_y_rubro_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($r_listado_socios_por_actividad_y_rubro->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_footer.php" ?>
<?php
$r_listado_socios_por_actividad_y_rubro_report->Page_Terminate();
?>
