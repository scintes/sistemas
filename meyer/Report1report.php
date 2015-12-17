<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php

// Global variable for table object
$Report1 = NULL;

//
// Table class for Report1
//
class cReport1 extends cTableBase {
	var $nro_orden;
	var $fecha_recepcion;
	var $cliente;
	var $objetos;
	var $detalle_a_realizar;
	var $precio;
	var $entrega;
	var $saldo;
	var $observaciones;
	var $tel;
	var $foto1;
	var $foto2;
	var $fecha_entrega;
	var $usuario;
	var $id_estado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Report1';
		$this->TableName = 'Report1';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->PrinterFriendlyForPdf = TRUE;
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// nro_orden
		$this->nro_orden = new cField('Report1', 'Report1', 'x_nro_orden', 'nro_orden', '`nro_orden`', '`nro_orden`', 3, -1, FALSE, '`nro_orden`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nro_orden->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nro_orden'] = &$this->nro_orden;

		// fecha_recepcion
		$this->fecha_recepcion = new cField('Report1', 'Report1', 'x_fecha_recepcion', 'fecha_recepcion', '`fecha_recepcion`', 'DATE_FORMAT(`fecha_recepcion`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_recepcion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_recepcion->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_recepcion'] = &$this->fecha_recepcion;

		// cliente
		$this->cliente = new cField('Report1', 'Report1', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 200, -1, FALSE, '`cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cliente'] = &$this->cliente;

		// objetos
		$this->objetos = new cField('Report1', 'Report1', 'x_objetos', 'objetos', '`objetos`', '`objetos`', 200, -1, FALSE, '`objetos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['objetos'] = &$this->objetos;

		// detalle_a_realizar
		$this->detalle_a_realizar = new cField('Report1', 'Report1', 'x_detalle_a_realizar', 'detalle_a_realizar', '`detalle_a_realizar`', '`detalle_a_realizar`', 201, -1, FALSE, '`detalle_a_realizar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['detalle_a_realizar'] = &$this->detalle_a_realizar;

		// precio
		$this->precio = new cField('Report1', 'Report1', 'x_precio', 'precio', '`precio`', '`precio`', 131, -1, FALSE, '`precio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->precio->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['precio'] = &$this->precio;

		// entrega
		$this->entrega = new cField('Report1', 'Report1', 'x_entrega', 'entrega', '`entrega`', '`entrega`', 131, -1, FALSE, '`entrega`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->entrega->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['entrega'] = &$this->entrega;

		// saldo
		$this->saldo = new cField('Report1', 'Report1', 'x_saldo', 'saldo', '`saldo`', '`saldo`', 131, -1, FALSE, '`saldo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->saldo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo'] = &$this->saldo;

		// observaciones
		$this->observaciones = new cField('Report1', 'Report1', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 201, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['observaciones'] = &$this->observaciones;

		// tel
		$this->tel = new cField('Report1', 'Report1', 'x_tel', 'tel', '`tel`', '`tel`', 200, -1, FALSE, '`tel`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tel'] = &$this->tel;

		// foto1
		$this->foto1 = new cField('Report1', 'Report1', 'x_foto1', 'foto1', '`foto1`', '`foto1`', 200, -1, TRUE, '`foto1`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['foto1'] = &$this->foto1;

		// foto2
		$this->foto2 = new cField('Report1', 'Report1', 'x_foto2', 'foto2', '`foto2`', '`foto2`', 200, -1, TRUE, '`foto2`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['foto2'] = &$this->foto2;

		// fecha_entrega
		$this->fecha_entrega = new cField('Report1', 'Report1', 'x_fecha_entrega', 'fecha_entrega', '`fecha_entrega`', 'DATE_FORMAT(`fecha_entrega`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_entrega`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_entrega->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_entrega'] = &$this->fecha_entrega;

		// usuario
		$this->usuario = new cField('Report1', 'Report1', 'x_usuario', 'usuario', '`usuario`', '`usuario`', 200, -1, FALSE, '`usuario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['usuario'] = &$this->usuario;

		// id_estado
		$this->id_estado = new cField('Report1', 'Report1', 'x_id_estado', 'id_estado', '`id_estado`', '`id_estado`', 3, -1, FALSE, '`id_estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_estado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_estado'] = &$this->id_estado;
	}

	// Report group level SQL
	function SqlGroupSelect() { // Select
		return "SELECT DISTINCT `cliente` FROM `trabajos`";
	}

	function SqlGroupWhere() { // Where
		return "";
	}

	function SqlGroupGroupBy() { // Group By
		return "";
	}

	function SqlGroupHaving() { // Having
		return "";
	}

	function SqlGroupOrderBy() { // Order By
		return "`cliente` ASC";
	}

	// Report detail level SQL
	function SqlDetailSelect() { // Select
		return "SELECT * FROM `trabajos`";
	}

	function SqlDetailWhere() { // Where
		return "";
	}

	function SqlDetailGroupBy() { // Group By
		return "";
	}

	function SqlDetailHaving() { // Having
		return "";
	}

	function SqlDetailOrderBy() { // Order By
		return "";
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
		return ew_BuildSelectSql($this->SqlGroupSelect(), $this->SqlGroupWhere(),
			 $this->SqlGroupGroupBy(), $this->SqlGroupHaving(),
			 $this->SqlGroupOrderBy(), $sFilter, $sSort);
	}

	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->SqlDetailSelect(), $this->SqlDetailWhere(),
			$this->SqlDetailGroupBy(), $this->SqlDetailHaving(),
			$this->SqlDetailOrderBy(), $sFilter, $sSort);
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
			return "Report1report.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "Report1report.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("", $this->UrlParm($parm));
		else
			return $this->KeyUrl("", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
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
		if (!is_null($this->nro_orden->CurrentValue)) {
			$sUrl .= "nro_orden=" . urlencode($this->nro_orden->CurrentValue);
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
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
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
			$arKeys[] = @$_GET["nro_orden"]; // nro_orden

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
			$this->nro_orden->CurrentValue = $key;
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
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$Report1_report = NULL; // Initialize page object first

class cReport1_report extends cReport1 {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{83DA4882-2FB3-4AE9-BADA-241C2F6A6920}";

	// Table name
	var $TableName = 'Report1';

	// Page object name
	var $PageObjName = 'Report1_report';

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
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
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
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
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

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (Report1)
		if (!isset($GLOBALS["Report1"])) {
			$GLOBALS["Report1"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Report1"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Report1', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;
		global $EW_EXPORT_REPORT;

		// Page Unload event
		$this->Page_Unload();

		// Export
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

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
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
		$this->ReportGroups = &ew_InitArray(2, NULL);
		$this->ReportCounts = &ew_InitArray(2, 0);
		$this->LevelBreak = &ew_InitArray(2, FALSE);
		$this->ReportTotals = &ew_Init2DArray(2, 15, 0);
		$this->ReportMaxs = &ew_Init2DArray(2, 15, 0);
		$this->ReportMins = &ew_Init2DArray(2, 15, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
		} else {
			if (!ew_CompareValue($this->cliente->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
			}
		}
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->precio->FormValue == $this->precio->CurrentValue && is_numeric(ew_StrToFloat($this->precio->CurrentValue)))
			$this->precio->CurrentValue = ew_StrToFloat($this->precio->CurrentValue);

		// Convert decimal values if posted back
		if ($this->entrega->FormValue == $this->entrega->CurrentValue && is_numeric(ew_StrToFloat($this->entrega->CurrentValue)))
			$this->entrega->CurrentValue = ew_StrToFloat($this->entrega->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo->FormValue == $this->saldo->CurrentValue && is_numeric(ew_StrToFloat($this->saldo->CurrentValue)))
			$this->saldo->CurrentValue = ew_StrToFloat($this->saldo->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nro_orden
		// fecha_recepcion
		// cliente
		// objetos
		// detalle_a_realizar
		// precio
		// entrega
		// saldo
		// observaciones
		// tel
		// foto1
		// foto2
		// fecha_entrega
		// usuario
		// id_estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nro_orden
			$this->nro_orden->ViewValue = $this->nro_orden->CurrentValue;
			$this->nro_orden->ViewCustomAttributes = "";

			// fecha_recepcion
			$this->fecha_recepcion->ViewValue = $this->fecha_recepcion->CurrentValue;
			$this->fecha_recepcion->ViewValue = ew_FormatDateTime($this->fecha_recepcion->ViewValue, 7);
			$this->fecha_recepcion->ViewCustomAttributes = "";

			// cliente
			$this->cliente->ViewValue = $this->cliente->CurrentValue;
			$this->cliente->ViewCustomAttributes = "";

			// objetos
			$this->objetos->ViewValue = $this->objetos->CurrentValue;
			$this->objetos->ViewCustomAttributes = "";

			// detalle_a_realizar
			$this->detalle_a_realizar->ViewValue = $this->detalle_a_realizar->CurrentValue;
			$this->detalle_a_realizar->ViewCustomAttributes = "";

			// precio
			$this->precio->ViewValue = $this->precio->CurrentValue;
			$this->precio->ViewValue = ew_FormatCurrency($this->precio->ViewValue, 0, -2, -2, -2);
			$this->precio->ViewCustomAttributes = "";

			// entrega
			$this->entrega->ViewValue = $this->entrega->CurrentValue;
			$this->entrega->ViewValue = ew_FormatCurrency($this->entrega->ViewValue, 0, -2, -2, -2);
			$this->entrega->ViewCustomAttributes = "";

			// saldo
			$this->saldo->ViewValue = $this->saldo->CurrentValue;
			$this->saldo->ViewValue = ew_FormatCurrency($this->saldo->ViewValue, 0, -2, -2, -2);
			$this->saldo->ViewCustomAttributes = "";

			// observaciones
			$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
			$this->observaciones->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewCustomAttributes = "";

			// foto1
			if (!ew_Empty($this->foto1->Upload->DbValue)) {
				$this->foto1->ImageAlt = $this->foto1->FldAlt();
				$this->foto1->ViewValue = ew_UploadPathEx(FALSE, $this->foto1->UploadPath) . $this->foto1->Upload->DbValue;
				if ($this->Export == "excel" && defined('EW_USE_PHPEXCEL')) {
					$this->foto1->ViewValue = ew_UploadPathEx(TRUE, $this->foto1->UploadPath) . $this->foto1->Upload->DbValue;
				}
			} else {
				$this->foto1->ViewValue = "";
			}
			$this->foto1->ViewCustomAttributes = "";

			// foto2
			if (!ew_Empty($this->foto2->Upload->DbValue)) {
				$this->foto2->ImageAlt = $this->foto2->FldAlt();
				$this->foto2->ViewValue = ew_UploadPathEx(FALSE, $this->foto2->UploadPath) . $this->foto2->Upload->DbValue;
				if ($this->Export == "excel" && defined('EW_USE_PHPEXCEL')) {
					$this->foto2->ViewValue = ew_UploadPathEx(TRUE, $this->foto2->UploadPath) . $this->foto2->Upload->DbValue;
				}
			} else {
				$this->foto2->ViewValue = "";
			}
			$this->foto2->ViewCustomAttributes = "";

			// fecha_entrega
			$this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
			$this->fecha_entrega->ViewValue = ew_FormatDateTime($this->fecha_entrega->ViewValue, 7);
			$this->fecha_entrega->ViewCustomAttributes = "";

			// usuario
			$this->usuario->ViewValue = $this->usuario->CurrentValue;
			$this->usuario->ViewCustomAttributes = "";

			// id_estado
			if (strval($this->id_estado->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_estado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `codigo`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estados`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `codigo` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_estado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_estado->ViewValue = $this->id_estado->CurrentValue;
				}
			} else {
				$this->id_estado->ViewValue = NULL;
			}
			$this->id_estado->ViewCustomAttributes = "";

			// nro_orden
			$this->nro_orden->LinkCustomAttributes = "";
			$this->nro_orden->HrefValue = "";
			$this->nro_orden->TooltipValue = "";

			// fecha_recepcion
			$this->fecha_recepcion->LinkCustomAttributes = "";
			$this->fecha_recepcion->HrefValue = "";
			$this->fecha_recepcion->TooltipValue = "";

			// cliente
			$this->cliente->LinkCustomAttributes = "";
			$this->cliente->HrefValue = "";
			$this->cliente->TooltipValue = "";

			// objetos
			$this->objetos->LinkCustomAttributes = "";
			$this->objetos->HrefValue = "";
			$this->objetos->TooltipValue = "";

			// detalle_a_realizar
			$this->detalle_a_realizar->LinkCustomAttributes = "";
			$this->detalle_a_realizar->HrefValue = "";
			$this->detalle_a_realizar->TooltipValue = "";

			// precio
			$this->precio->LinkCustomAttributes = "";
			$this->precio->HrefValue = "";
			$this->precio->TooltipValue = "";

			// entrega
			$this->entrega->LinkCustomAttributes = "";
			$this->entrega->HrefValue = "";
			$this->entrega->TooltipValue = "";

			// saldo
			$this->saldo->LinkCustomAttributes = "";
			$this->saldo->HrefValue = "";
			$this->saldo->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// tel
			$this->tel->LinkCustomAttributes = "";
			$this->tel->HrefValue = "";
			$this->tel->TooltipValue = "";

			// foto1
			$this->foto1->LinkCustomAttributes = "";
			$this->foto1->HrefValue = "";
			$this->foto1->HrefValue2 = $this->foto1->UploadPath . $this->foto1->Upload->DbValue;
			$this->foto1->TooltipValue = "";

			// foto2
			$this->foto2->LinkCustomAttributes = "";
			$this->foto2->HrefValue = "";
			$this->foto2->HrefValue2 = $this->foto2->UploadPath . $this->foto2->Upload->DbValue;
			$this->foto2->TooltipValue = "";

			// fecha_entrega
			$this->fecha_entrega->LinkCustomAttributes = "";
			$this->fecha_entrega->HrefValue = "";
			$this->fecha_entrega->TooltipValue = "";

			// usuario
			$this->usuario->LinkCustomAttributes = "";
			$this->usuario->HrefValue = "";
			$this->usuario->TooltipValue = "";

			// id_estado
			$this->id_estado->LinkCustomAttributes = "";
			$this->id_estado->HrefValue = "";
			$this->id_estado->TooltipValue = "";
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
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Drop down button for export
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
		$PageCaption = $this->TableCaption();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("report", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", $url, $this->TableVar);
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
if (!isset($Report1_report)) $Report1_report = new cReport1_report();

// Page init
$Report1_report->Page_Init();

// Page main
$Report1_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Report1_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Report1->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Report1->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php
$Report1_report->DefaultFilter = "";
$Report1_report->ReportFilter = $Report1_report->DefaultFilter;
if ($Report1_report->DbDetailFilter <> "") {
	if ($Report1_report->ReportFilter <> "") $Report1_report->ReportFilter .= " AND ";
	$Report1_report->ReportFilter .= "(" . $Report1_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$Report1->CurrentFilter = $Report1_report->ReportFilter;
$Report1_report->ReportSql = $Report1->GroupSQL();

// Load recordset
$Report1_report->Recordset = $conn->Execute($Report1_report->ReportSql);
$Report1_report->RecordExists = !$Report1_report->Recordset->EOF;
?>
<?php if ($Report1->Export == "") { ?>
<?php if ($Report1_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $Report1_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $Report1_report->ShowPageHeader(); ?>
<form method="post">
<table class="ewReportTable">
<?php

// Get First Row
if ($Report1_report->RecordExists) {
	$Report1->cliente->setDbValue($Report1_report->Recordset->fields('cliente'));
	$Report1_report->ReportGroups[0] = $Report1->cliente->DbValue;
}
$Report1_report->RecCnt = 0;
$Report1_report->ReportCounts[0] = 0;
$Report1_report->ChkLvlBreak();
while (!$Report1_report->Recordset->EOF) {

	// Render for view
	$Report1->RowType = EW_ROWTYPE_VIEW;
	$Report1->ResetAttrs();
	$Report1_report->RenderRow();

	// Show group headers
	if ($Report1_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td class="ewGroupField"><?php echo $Report1->cliente->FldCaption() ?></td>
	<td colspan=14 class="ewGroupName">
<span<?php echo $Report1->cliente->ViewAttributes() ?>>
<?php echo $Report1->cliente->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$Report1_report->ReportFilter = $Report1_report->DefaultFilter;
	if ($Report1_report->ReportFilter <> "") $Report1_report->ReportFilter .= " AND ";
	if (is_null($Report1->cliente->CurrentValue)) {
		$Report1_report->ReportFilter .= "(`cliente` IS NULL)";
	} else {
		$Report1_report->ReportFilter .= "(`cliente` = '" . ew_AdjustSql($Report1->cliente->CurrentValue) . "')";
	}
	if ($Report1_report->DbDetailFilter <> "") {
		if ($Report1_report->ReportFilter <> "")
			$Report1_report->ReportFilter .= " AND ";
		$Report1_report->ReportFilter .= "(" . $Report1_report->DbDetailFilter . ")";
	}

	// Set up detail SQL
	$Report1->CurrentFilter = $Report1_report->ReportFilter;
	$Report1_report->ReportSql = $Report1->DetailSQL();

	// Load detail records
	$Report1_report->DetailRecordset = $conn->Execute($Report1_report->ReportSql);
	$Report1_report->DtlRecordCount = $Report1_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Report1_report->DetailRecordset->EOF) {
		$Report1_report->RecCnt++;
		$Report1->precio->setDbValue($Report1_report->DetailRecordset->fields('precio'));
		$Report1->entrega->setDbValue($Report1_report->DetailRecordset->fields('entrega'));
		$Report1->saldo->setDbValue($Report1_report->DetailRecordset->fields('saldo'));
	}
	if ($Report1_report->RecCnt == 1) {
		$Report1_report->ReportCounts[0] = 0;
		$Report1_report->ReportTotals[0][4] = 0;
		$Report1_report->ReportTotals[0][5] = 0;
		$Report1_report->ReportTotals[0][6] = 0;
		if (!$Report1_report->DetailRecordset->EOF) {
			$Report1_report->ReportMins[0][6] = $Report1->saldo->DbValue;
		} else {
			$Report1_report->ReportMins[0][6] = 0;
		}
		if (!$Report1_report->DetailRecordset->EOF) {
			$Report1_report->ReportMaxs[0][6] = $Report1->saldo->DbValue;
		} else {
			$Report1_report->ReportMaxs[0][6] = 0;
		}
	}
	for ($i = 1; $i <= 1; $i++) {
		if ($Report1_report->LevelBreak[$i]) { // Reset counter and aggregation
			$Report1_report->ReportCounts[$i] = 0;
			$Report1_report->ReportTotals[$i][4] = 0;
			$Report1_report->ReportTotals[$i][5] = 0;
			$Report1_report->ReportTotals[$i][6] = 0;
			if (!$Report1_report->DetailRecordset->EOF) {
				$Report1_report->ReportMins[$i][6] = $Report1->saldo->CurrentValue;
			} else {
				$Report1_report->ReportMins[$i][6] = 0;
			}
			if (!$Report1_report->DetailRecordset->EOF) {
				$Report1_report->ReportMaxs[$i][6] = $Report1->saldo->CurrentValue;
			} else {
				$Report1_report->ReportMaxs[$i][6] = 0;
			}
		}
	}
	$Report1_report->ReportCounts[0] += $Report1_report->DtlRecordCount;
	$Report1_report->ReportCounts[1] += $Report1_report->DtlRecordCount;
	if ($Report1_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $Report1->nro_orden->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->fecha_recepcion->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->objetos->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->detalle_a_realizar->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->precio->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->entrega->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->saldo->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->observaciones->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->tel->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->foto1->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->foto2->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->fecha_entrega->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->usuario->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->id_estado->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$Report1_report->DetailRecordset->EOF) {
		$Report1->nro_orden->setDbValue($Report1_report->DetailRecordset->fields('nro_orden'));
		$Report1->fecha_recepcion->setDbValue($Report1_report->DetailRecordset->fields('fecha_recepcion'));
		$Report1->objetos->setDbValue($Report1_report->DetailRecordset->fields('objetos'));
		$Report1->detalle_a_realizar->setDbValue($Report1_report->DetailRecordset->fields('detalle_a_realizar'));
		$Report1->precio->setDbValue($Report1_report->DetailRecordset->fields('precio'));
		$Report1_report->ReportTotals[0][4] += $Report1->precio->CurrentValue;
		$Report1_report->ReportTotals[1][4] += $Report1->precio->CurrentValue;
		$Report1->entrega->setDbValue($Report1_report->DetailRecordset->fields('entrega'));
		$Report1_report->ReportTotals[0][5] += $Report1->entrega->CurrentValue;
		$Report1_report->ReportTotals[1][5] += $Report1->entrega->CurrentValue;
		$Report1->saldo->setDbValue($Report1_report->DetailRecordset->fields('saldo'));
		$Report1_report->ReportTotals[0][6] += $Report1->saldo->CurrentValue;
		if ($Report1_report->ReportMins[0][6] > $Report1->saldo->CurrentValue)
			$Report1_report->ReportMins[0][6] = $Report1->saldo->CurrentValue;
		if ($Report1_report->ReportMaxs[0][6] < $Report1->saldo->CurrentValue)
			$Report1_report->ReportMaxs[0][6] = $Report1->saldo->CurrentValue;
		$Report1_report->ReportTotals[1][6] += $Report1->saldo->CurrentValue;
		if ($Report1_report->ReportMins[1][6] > $Report1->saldo->CurrentValue)
			$Report1_report->ReportMins[1][6] = $Report1->saldo->CurrentValue;
		if ($Report1_report->ReportMaxs[1][6] < $Report1->saldo->CurrentValue)
			$Report1_report->ReportMaxs[1][6] = $Report1->saldo->CurrentValue;
		$Report1->observaciones->setDbValue($Report1_report->DetailRecordset->fields('observaciones'));
		$Report1->tel->setDbValue($Report1_report->DetailRecordset->fields('tel'));
		$Report1->foto1->Upload->DbValue = $Report1_report->DetailRecordset->fields('foto1');
		$Report1->foto2->Upload->DbValue = $Report1_report->DetailRecordset->fields('foto2');
		$Report1->fecha_entrega->setDbValue($Report1_report->DetailRecordset->fields('fecha_entrega'));
		$Report1->usuario->setDbValue($Report1_report->DetailRecordset->fields('usuario'));
		$Report1->id_estado->setDbValue($Report1_report->DetailRecordset->fields('id_estado'));

		// Render for view
		$Report1->RowType = EW_ROWTYPE_VIEW;
		$Report1->ResetAttrs();
		$Report1_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $Report1->nro_orden->CellAttributes() ?>>
<span<?php echo $Report1->nro_orden->ViewAttributes() ?>>
<?php echo $Report1->nro_orden->ViewValue ?></span>
</td>
		<td<?php echo $Report1->fecha_recepcion->CellAttributes() ?>>
<span<?php echo $Report1->fecha_recepcion->ViewAttributes() ?>>
<?php echo $Report1->fecha_recepcion->ViewValue ?></span>
</td>
		<td<?php echo $Report1->objetos->CellAttributes() ?>>
<span<?php echo $Report1->objetos->ViewAttributes() ?>>
<?php echo $Report1->objetos->ViewValue ?></span>
</td>
		<td<?php echo $Report1->detalle_a_realizar->CellAttributes() ?>>
<span<?php echo $Report1->detalle_a_realizar->ViewAttributes() ?>>
<?php echo $Report1->detalle_a_realizar->ViewValue ?></span>
</td>
		<td<?php echo $Report1->precio->CellAttributes() ?>>
<span<?php echo $Report1->precio->ViewAttributes() ?>>
<?php echo $Report1->precio->ViewValue ?></span>
</td>
		<td<?php echo $Report1->entrega->CellAttributes() ?>>
<span<?php echo $Report1->entrega->ViewAttributes() ?>>
<?php echo $Report1->entrega->ViewValue ?></span>
</td>
		<td<?php echo $Report1->saldo->CellAttributes() ?>>
<span<?php echo $Report1->saldo->ViewAttributes() ?>>
<?php echo $Report1->saldo->ViewValue ?></span>
</td>
		<td<?php echo $Report1->observaciones->CellAttributes() ?>>
<span<?php echo $Report1->observaciones->ViewAttributes() ?>>
<?php echo $Report1->observaciones->ViewValue ?></span>
</td>
		<td<?php echo $Report1->tel->CellAttributes() ?>>
<span<?php echo $Report1->tel->ViewAttributes() ?>>
<?php echo $Report1->tel->ViewValue ?></span>
</td>
		<td<?php echo $Report1->foto1->CellAttributes() ?>>
<span>
<?php if ($Report1->Export == "word" || $Report1->Export == "excel") { ?>
<?php if ($Report1->foto1->HrefValue2 <> "" && !empty($Report1->foto1->Upload->DbValue)) { ?>
<a href="<?php echo ew_ConvertFullUrl($Report1->foto1->HrefValue2) ?>"><?php echo $Report1->foto1->FldCaption() ?></a>
<?php } ?>
<?php } elseif ($Report1->foto1->LinkAttributes() <> "") { ?>
<?php if (!empty($Report1->foto1->Upload->DbValue)) { ?>
<img src="<?php echo $Report1->foto1->ViewValue ?>" alt="" style="border: 0;"<?php echo $Report1->foto1->ViewAttributes() ?>>
<?php } elseif (!in_array($Report1->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($Report1->foto1->Upload->DbValue)) { ?>
<img src="<?php echo $Report1->foto1->ViewValue ?>" alt="" style="border: 0;"<?php echo $Report1->foto1->ViewAttributes() ?>>
<?php } elseif (!in_array($Report1->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
		<td<?php echo $Report1->foto2->CellAttributes() ?>>
<span>
<?php if ($Report1->Export == "word" || $Report1->Export == "excel") { ?>
<?php if ($Report1->foto2->HrefValue2 <> "" && !empty($Report1->foto2->Upload->DbValue)) { ?>
<a href="<?php echo ew_ConvertFullUrl($Report1->foto2->HrefValue2) ?>"><?php echo $Report1->foto2->FldCaption() ?></a>
<?php } ?>
<?php } elseif ($Report1->foto2->LinkAttributes() <> "") { ?>
<?php if (!empty($Report1->foto2->Upload->DbValue)) { ?>
<img src="<?php echo $Report1->foto2->ViewValue ?>" alt="" style="border: 0;"<?php echo $Report1->foto2->ViewAttributes() ?>>
<?php } elseif (!in_array($Report1->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($Report1->foto2->Upload->DbValue)) { ?>
<img src="<?php echo $Report1->foto2->ViewValue ?>" alt="" style="border: 0;"<?php echo $Report1->foto2->ViewAttributes() ?>>
<?php } elseif (!in_array($Report1->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
		<td<?php echo $Report1->fecha_entrega->CellAttributes() ?>>
<span<?php echo $Report1->fecha_entrega->ViewAttributes() ?>>
<?php echo $Report1->fecha_entrega->ViewValue ?></span>
</td>
		<td<?php echo $Report1->usuario->CellAttributes() ?>>
<span<?php echo $Report1->usuario->ViewAttributes() ?>>
<?php echo $Report1->usuario->ViewValue ?></span>
</td>
		<td<?php echo $Report1->id_estado->CellAttributes() ?>>
<span<?php echo $Report1->id_estado->ViewAttributes() ?>>
<?php echo $Report1->id_estado->ViewValue ?></span>
</td>
	</tr>
<?php
		$Report1_report->DetailRecordset->MoveNext();
	}
	$Report1_report->DetailRecordset->Close();

	// Save old group data
	$Report1_report->ReportGroups[0] = $Report1->cliente->CurrentValue;

	// Get next record
	$Report1_report->Recordset->MoveNext();
	if ($Report1_report->Recordset->EOF) {
		$Report1_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$Report1->cliente->setDbValue($Report1_report->Recordset->fields('cliente'));
	}
	$Report1_report->ChkLvlBreak();

	// Show footers
	if ($Report1_report->LevelBreak[1]) {
		$Report1->cliente->CurrentValue = $Report1_report->ReportGroups[0];

		// Render row for view
		$Report1->RowType = EW_ROWTYPE_VIEW;
		$Report1->ResetAttrs();
		$Report1_report->RenderRow();
		$Report1->cliente->CurrentValue = $Report1->cliente->DbValue;
?>
	<tr><td colspan=15 class="ewGroupSummary"><?php echo $Language->Phrase("RptSumHead") ?>&nbsp;<?php echo $Report1->cliente->FldCaption() ?>:&nbsp;<?php echo $Report1->cliente->ViewValue ?> (<?php echo ew_FormatNumber($Report1_report->ReportCounts[1],0) ?> <?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php
	$Report1->precio->CurrentValue = $Report1_report->ReportTotals[1][4];
	$Report1->entrega->CurrentValue = $Report1_report->ReportTotals[1][5];
	$Report1->saldo->CurrentValue = $Report1_report->ReportTotals[1][6];

	// Render row for view
	$Report1->RowType = EW_ROWTYPE_VIEW;
	$Report1->ResetAttrs();
	$Report1_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><?php echo $Language->Phrase("RptSum") ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td<?php echo $Report1->precio->CellAttributes() ?>>
<span<?php echo $Report1->precio->ViewAttributes() ?>>
<?php echo $Report1->precio->ViewValue ?></span>
</td>
		<td<?php echo $Report1->entrega->CellAttributes() ?>>
<span<?php echo $Report1->entrega->ViewAttributes() ?>>
<?php echo $Report1->entrega->ViewValue ?></span>
</td>
		<td<?php echo $Report1->saldo->CellAttributes() ?>>
<span<?php echo $Report1->saldo->ViewAttributes() ?>>
<?php echo $Report1->saldo->ViewValue ?></span>
</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?php
	if ($Report1_report->ReportCounts[1] > 0) {
		$Report1->saldo->CurrentValue = $Report1_report->ReportTotals[1][6] / $Report1_report->ReportCounts[1];
	} else {
		$Report1->saldo->CurrentValue = 0;
	}

	// Render row for view
	$Report1->RowType = EW_ROWTYPE_VIEW;
	$Report1->ResetAttrs();
	$Report1_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><?php echo $Language->Phrase("RptAvg") ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td<?php echo $Report1->saldo->CellAttributes() ?>>
<span<?php echo $Report1->saldo->ViewAttributes() ?>>
<?php echo $Report1->saldo->ViewValue ?></span>
</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?php
	$Report1->saldo->CurrentValue = $Report1_report->ReportMins[1][6];

	// Render row for view
	$Report1->RowType = EW_ROWTYPE_VIEW;
	$Report1->ResetAttrs();
	$Report1_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><?php echo $Language->Phrase("RptMin") ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td<?php echo $Report1->saldo->CellAttributes() ?>>
<span<?php echo $Report1->saldo->ViewAttributes() ?>>
<?php echo $Report1->saldo->ViewValue ?></span>
</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?php
	$Report1->saldo->CurrentValue = $Report1_report->ReportMaxs[1][6];

	// Render row for view
	$Report1->RowType = EW_ROWTYPE_VIEW;
	$Report1->ResetAttrs();
	$Report1_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><?php echo $Language->Phrase("RptMax") ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td<?php echo $Report1->saldo->CellAttributes() ?>>
<span<?php echo $Report1->saldo->ViewAttributes() ?>>
<?php echo $Report1->saldo->ViewValue ?></span>
</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr><td colspan=15>&nbsp;<br></td></tr>
<?php
}
}

// Close recordset
$Report1_report->Recordset->Close();
?>
<?php if ($Report1_report->RecordExists) { ?>
	<tr><td colspan=15>&nbsp;<br></td></tr>
	<tr><td colspan=15 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Report1_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php
	$Report1->precio->CurrentValue = $Report1_report->ReportTotals[0][4];
	$Report1->entrega->CurrentValue = $Report1_report->ReportTotals[0][5];
	$Report1->saldo->CurrentValue = $Report1_report->ReportTotals[0][6];

	// Render row for view
	$Report1->RowType = EW_ROWTYPE_VIEW;
	$Report1->ResetAttrs();
	$Report1_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><?php echo $Language->Phrase("RptSum") ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td<?php echo $Report1->precio->CellAttributes() ?>>
<span<?php echo $Report1->precio->ViewAttributes() ?>>
<?php echo $Report1->precio->ViewValue ?></span>
</td>
		<td<?php echo $Report1->entrega->CellAttributes() ?>>
<span<?php echo $Report1->entrega->ViewAttributes() ?>>
<?php echo $Report1->entrega->ViewValue ?></span>
</td>
		<td<?php echo $Report1->saldo->CellAttributes() ?>>
<span<?php echo $Report1->saldo->ViewAttributes() ?>>
<?php echo $Report1->saldo->ViewValue ?></span>
</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?php
	if ($Report1_report->ReportCounts[1] > 0) {
		$Report1->saldo->CurrentValue = $Report1_report->ReportTotals[0][6] / $Report1_report->ReportCounts[0];
	} else {
		$Report1->saldo->CurrentValue = 0;
	}

	// Render row for view
	$Report1->RowType = EW_ROWTYPE_VIEW;
	$Report1->ResetAttrs();
	$Report1_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><?php echo $Language->Phrase("RptAvg") ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td<?php echo $Report1->saldo->CellAttributes() ?>>
<span<?php echo $Report1->saldo->ViewAttributes() ?>>
<?php echo $Report1->saldo->ViewValue ?></span>
</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?php
	$Report1->saldo->CurrentValue = $Report1_report->ReportMins[0][6];

	// Render row for view
	$Report1->RowType = EW_ROWTYPE_VIEW;
	$Report1->ResetAttrs();
	$Report1_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><?php echo $Language->Phrase("RptMin") ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td<?php echo $Report1->saldo->CellAttributes() ?>>
<span<?php echo $Report1->saldo->ViewAttributes() ?>>
<?php echo $Report1->saldo->ViewValue ?></span>
</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?php
	$Report1->saldo->CurrentValue = $Report1_report->ReportMaxs[0][6];

	// Render row for view
	$Report1->RowType = EW_ROWTYPE_VIEW;
	$Report1->ResetAttrs();
	$Report1_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><?php echo $Language->Phrase("RptMax") ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td<?php echo $Report1->saldo->CellAttributes() ?>>
<span<?php echo $Report1->saldo->ViewAttributes() ?>>
<?php echo $Report1->saldo->ViewValue ?></span>
</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?php } ?>
<?php if ($Report1_report->RecordExists) { ?>
	<tr><td colspan=15>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
</form>
<?php
$Report1_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Report1->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Report1_report->Page_Terminate();
?>
