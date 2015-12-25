<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_v_total_estado_cuenta_x_anio_mesinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_sociosinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$v_total_estado_cuenta_x_anio_mes_list = NULL; // Initialize page object first

class cv_total_estado_cuenta_x_anio_mes_list extends cv_total_estado_cuenta_x_anio_mes {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'v_total_estado_cuenta_x_anio_mes';

	// Page object name
	var $PageObjName = 'v_total_estado_cuenta_x_anio_mes_list';

	// Grid form hidden field names
	var $FormName = 'fv_total_estado_cuenta_x_anio_meslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
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
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
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

		// Table object (v_total_estado_cuenta_x_anio_mes)
		if (!isset($GLOBALS["v_total_estado_cuenta_x_anio_mes"]) || get_class($GLOBALS["v_total_estado_cuenta_x_anio_mes"]) == "cv_total_estado_cuenta_x_anio_mes") {
			$GLOBALS["v_total_estado_cuenta_x_anio_mes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["v_total_estado_cuenta_x_anio_mes"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "cciag_v_total_estado_cuenta_x_anio_mesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "cciag_v_total_estado_cuenta_x_anio_mesdelete.php";
		$this->MultiUpdateUrl = "cciag_v_total_estado_cuenta_x_anio_mesupdate.php";

		// Table object (socios)
		if (!isset($GLOBALS['socios'])) $GLOBALS['socios'] = new csocios();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'v_total_estado_cuenta_x_anio_mes', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
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
		global $EW_EXPORT, $v_total_estado_cuenta_x_anio_mes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($v_total_estado_cuenta_x_anio_mes);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount();
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->socio_nro, $Default, FALSE); // socio_nro
		$this->BuildSearchSql($sWhere, $this->cuit_cuil, $Default, FALSE); // cuit_cuil
		$this->BuildSearchSql($sWhere, $this->propietario, $Default, FALSE); // propietario
		$this->BuildSearchSql($sWhere, $this->comercio, $Default, FALSE); // comercio
		$this->BuildSearchSql($sWhere, $this->mes, $Default, FALSE); // mes
		$this->BuildSearchSql($sWhere, $this->anio, $Default, FALSE); // anio
		$this->BuildSearchSql($sWhere, $this->deuda, $Default, FALSE); // deuda
		$this->BuildSearchSql($sWhere, $this->pago, $Default, FALSE); // pago

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->socio_nro->AdvancedSearch->Save(); // socio_nro
			$this->cuit_cuil->AdvancedSearch->Save(); // cuit_cuil
			$this->propietario->AdvancedSearch->Save(); // propietario
			$this->comercio->AdvancedSearch->Save(); // comercio
			$this->mes->AdvancedSearch->Save(); // mes
			$this->anio->AdvancedSearch->Save(); // anio
			$this->deuda->AdvancedSearch->Save(); // deuda
			$this->pago->AdvancedSearch->Save(); // pago
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->cuit_cuil, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->propietario, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->comercio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mes, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->anio, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$sCond = $sDefCond;
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
						$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->socio_nro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->cuit_cuil->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->propietario->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->comercio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->anio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->deuda->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pago->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->socio_nro->AdvancedSearch->UnsetSession();
		$this->cuit_cuil->AdvancedSearch->UnsetSession();
		$this->propietario->AdvancedSearch->UnsetSession();
		$this->comercio->AdvancedSearch->UnsetSession();
		$this->mes->AdvancedSearch->UnsetSession();
		$this->anio->AdvancedSearch->UnsetSession();
		$this->deuda->AdvancedSearch->UnsetSession();
		$this->pago->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->socio_nro->AdvancedSearch->Load();
		$this->cuit_cuil->AdvancedSearch->Load();
		$this->propietario->AdvancedSearch->Load();
		$this->comercio->AdvancedSearch->Load();
		$this->mes->AdvancedSearch->Load();
		$this->anio->AdvancedSearch->Load();
		$this->deuda->AdvancedSearch->Load();
		$this->pago->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->socio_nro); // socio_nro
			$this->UpdateSort($this->cuit_cuil); // cuit_cuil
			$this->UpdateSort($this->propietario); // propietario
			$this->UpdateSort($this->comercio); // comercio
			$this->UpdateSort($this->mes); // mes
			$this->UpdateSort($this->anio); // anio
			$this->UpdateSort($this->deuda); // deuda
			$this->UpdateSort($this->pago); // pago
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->socio_nro->setSort("");
				$this->cuit_cuil->setSort("");
				$this->propietario->setSort("");
				$this->comercio->setSort("");
				$this->mes->setSort("");
				$this->anio->setSort("");
				$this->deuda->setSort("");
				$this->pago->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fv_total_estado_cuenta_x_anio_meslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fv_total_estado_cuenta_x_anio_meslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere);

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch())
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// socio_nro

		$this->socio_nro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_socio_nro"]);
		if ($this->socio_nro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->socio_nro->AdvancedSearch->SearchOperator = @$_GET["z_socio_nro"];

		// cuit_cuil
		$this->cuit_cuil->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_cuit_cuil"]);
		if ($this->cuit_cuil->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->cuit_cuil->AdvancedSearch->SearchOperator = @$_GET["z_cuit_cuil"];

		// propietario
		$this->propietario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_propietario"]);
		if ($this->propietario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->propietario->AdvancedSearch->SearchOperator = @$_GET["z_propietario"];

		// comercio
		$this->comercio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_comercio"]);
		if ($this->comercio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->comercio->AdvancedSearch->SearchOperator = @$_GET["z_comercio"];

		// mes
		$this->mes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_mes"]);
		if ($this->mes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->mes->AdvancedSearch->SearchOperator = @$_GET["z_mes"];
		$this->mes->AdvancedSearch->SearchCondition = @$_GET["v_mes"];
		$this->mes->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_mes"]);
		if ($this->mes->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->mes->AdvancedSearch->SearchOperator2 = @$_GET["w_mes"];

		// anio
		$this->anio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_anio"]);
		if ($this->anio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->anio->AdvancedSearch->SearchOperator = @$_GET["z_anio"];
		$this->anio->AdvancedSearch->SearchCondition = @$_GET["v_anio"];
		$this->anio->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_anio"]);
		if ($this->anio->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->anio->AdvancedSearch->SearchOperator2 = @$_GET["w_anio"];

		// deuda
		$this->deuda->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_deuda"]);
		if ($this->deuda->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->deuda->AdvancedSearch->SearchOperator = @$_GET["z_deuda"];

		// pago
		$this->pago->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pago"]);
		if ($this->pago->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->pago->AdvancedSearch->SearchOperator = @$_GET["z_pago"];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->socio_nro->setDbValue($rs->fields('socio_nro'));
		$this->cuit_cuil->setDbValue($rs->fields('cuit_cuil'));
		$this->propietario->setDbValue($rs->fields('propietario'));
		$this->comercio->setDbValue($rs->fields('comercio'));
		$this->mes->setDbValue($rs->fields('mes'));
		$this->anio->setDbValue($rs->fields('anio'));
		$this->deuda->setDbValue($rs->fields('deuda'));
		$this->pago->setDbValue($rs->fields('pago'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->socio_nro->DbValue = $row['socio_nro'];
		$this->cuit_cuil->DbValue = $row['cuit_cuil'];
		$this->propietario->DbValue = $row['propietario'];
		$this->comercio->DbValue = $row['comercio'];
		$this->mes->DbValue = $row['mes'];
		$this->anio->DbValue = $row['anio'];
		$this->deuda->DbValue = $row['deuda'];
		$this->pago->DbValue = $row['pago'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Convert decimal values if posted back
		if ($this->deuda->FormValue == $this->deuda->CurrentValue && is_numeric(ew_StrToFloat($this->deuda->CurrentValue)))
			$this->deuda->CurrentValue = ew_StrToFloat($this->deuda->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pago->FormValue == $this->pago->CurrentValue && is_numeric(ew_StrToFloat($this->pago->CurrentValue)))
			$this->pago->CurrentValue = ew_StrToFloat($this->pago->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// socio_nro
		// cuit_cuil
		// propietario
		// comercio
		// mes
		// anio
		// deuda
		// pago
		// Accumulate aggregate value

		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
			if (is_numeric($this->deuda->CurrentValue))
				$this->deuda->Total += $this->deuda->CurrentValue; // Accumulate total
			if (is_numeric($this->pago->CurrentValue))
				$this->pago->Total += $this->pago->CurrentValue; // Accumulate total
		}
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// socio_nro
			if (strval($this->socio_nro->CurrentValue) <> "") {
				$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->socio_nro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `comercio` AS `Disp2Fld`, `propietario` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->socio_nro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `comercio` DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->socio_nro->ViewValue = $rswrk->fields('DispFld');
					$this->socio_nro->ViewValue .= ew_ValueSeparator(1,$this->socio_nro) . $rswrk->fields('Disp2Fld');
					$this->socio_nro->ViewValue .= ew_ValueSeparator(2,$this->socio_nro) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->socio_nro->ViewValue = $this->socio_nro->CurrentValue;
				}
			} else {
				$this->socio_nro->ViewValue = NULL;
			}
			$this->socio_nro->ViewValue = ew_FormatNumber($this->socio_nro->ViewValue, 0, -2, -2, -2);
			$this->socio_nro->ViewCustomAttributes = "";

			// cuit_cuil
			$this->cuit_cuil->ViewValue = $this->cuit_cuil->CurrentValue;
			$this->cuit_cuil->ViewCustomAttributes = "";

			// propietario
			if (strval($this->propietario->CurrentValue) <> "") {
				$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->propietario->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `propietario` AS `Disp2Fld`, '' AS `Disp3Fld`, `comercio` AS `Disp4Fld` FROM `socios`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->propietario, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->propietario->ViewValue = $rswrk->fields('DispFld');
					$this->propietario->ViewValue .= ew_ValueSeparator(1,$this->propietario) . $rswrk->fields('Disp2Fld');
					$this->propietario->ViewValue .= ew_ValueSeparator(3,$this->propietario) . $rswrk->fields('Disp4Fld');
					$rswrk->Close();
				} else {
					$this->propietario->ViewValue = $this->propietario->CurrentValue;
				}
			} else {
				$this->propietario->ViewValue = NULL;
			}
			$this->propietario->ViewCustomAttributes = "";

			// comercio
			if (strval($this->comercio->CurrentValue) <> "") {
				$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->comercio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `propietario` AS `Disp2Fld`, '' AS `Disp3Fld`, `comercio` AS `Disp4Fld` FROM `socios`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->comercio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->comercio->ViewValue = $rswrk->fields('DispFld');
					$this->comercio->ViewValue .= ew_ValueSeparator(1,$this->comercio) . $rswrk->fields('Disp2Fld');
					$this->comercio->ViewValue .= ew_ValueSeparator(3,$this->comercio) . $rswrk->fields('Disp4Fld');
					$rswrk->Close();
				} else {
					$this->comercio->ViewValue = $this->comercio->CurrentValue;
				}
			} else {
				$this->comercio->ViewValue = NULL;
			}
			$this->comercio->ViewCustomAttributes = "";

			// mes
			if (strval($this->mes->CurrentValue) <> "") {
				switch ($this->mes->CurrentValue) {
					case $this->mes->FldTagValue(1):
						$this->mes->ViewValue = $this->mes->FldTagCaption(1) <> "" ? $this->mes->FldTagCaption(1) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(2):
						$this->mes->ViewValue = $this->mes->FldTagCaption(2) <> "" ? $this->mes->FldTagCaption(2) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(3):
						$this->mes->ViewValue = $this->mes->FldTagCaption(3) <> "" ? $this->mes->FldTagCaption(3) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(4):
						$this->mes->ViewValue = $this->mes->FldTagCaption(4) <> "" ? $this->mes->FldTagCaption(4) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(5):
						$this->mes->ViewValue = $this->mes->FldTagCaption(5) <> "" ? $this->mes->FldTagCaption(5) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(6):
						$this->mes->ViewValue = $this->mes->FldTagCaption(6) <> "" ? $this->mes->FldTagCaption(6) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(7):
						$this->mes->ViewValue = $this->mes->FldTagCaption(7) <> "" ? $this->mes->FldTagCaption(7) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(8):
						$this->mes->ViewValue = $this->mes->FldTagCaption(8) <> "" ? $this->mes->FldTagCaption(8) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(9):
						$this->mes->ViewValue = $this->mes->FldTagCaption(9) <> "" ? $this->mes->FldTagCaption(9) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(10):
						$this->mes->ViewValue = $this->mes->FldTagCaption(10) <> "" ? $this->mes->FldTagCaption(10) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(11):
						$this->mes->ViewValue = $this->mes->FldTagCaption(11) <> "" ? $this->mes->FldTagCaption(11) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(12):
						$this->mes->ViewValue = $this->mes->FldTagCaption(12) <> "" ? $this->mes->FldTagCaption(12) : $this->mes->CurrentValue;
						break;
					default:
						$this->mes->ViewValue = $this->mes->CurrentValue;
				}
			} else {
				$this->mes->ViewValue = NULL;
			}
			$this->mes->ViewValue = ew_FormatNumber($this->mes->ViewValue, 0, -2, -2, -2);
			$this->mes->ViewCustomAttributes = "";

			// anio
			if (strval($this->anio->CurrentValue) <> "") {
				switch ($this->anio->CurrentValue) {
					case $this->anio->FldTagValue(1):
						$this->anio->ViewValue = $this->anio->FldTagCaption(1) <> "" ? $this->anio->FldTagCaption(1) : $this->anio->CurrentValue;
						break;
					case $this->anio->FldTagValue(2):
						$this->anio->ViewValue = $this->anio->FldTagCaption(2) <> "" ? $this->anio->FldTagCaption(2) : $this->anio->CurrentValue;
						break;
					case $this->anio->FldTagValue(3):
						$this->anio->ViewValue = $this->anio->FldTagCaption(3) <> "" ? $this->anio->FldTagCaption(3) : $this->anio->CurrentValue;
						break;
					case $this->anio->FldTagValue(4):
						$this->anio->ViewValue = $this->anio->FldTagCaption(4) <> "" ? $this->anio->FldTagCaption(4) : $this->anio->CurrentValue;
						break;
					case $this->anio->FldTagValue(5):
						$this->anio->ViewValue = $this->anio->FldTagCaption(5) <> "" ? $this->anio->FldTagCaption(5) : $this->anio->CurrentValue;
						break;
					case $this->anio->FldTagValue(6):
						$this->anio->ViewValue = $this->anio->FldTagCaption(6) <> "" ? $this->anio->FldTagCaption(6) : $this->anio->CurrentValue;
						break;
					case $this->anio->FldTagValue(7):
						$this->anio->ViewValue = $this->anio->FldTagCaption(7) <> "" ? $this->anio->FldTagCaption(7) : $this->anio->CurrentValue;
						break;
					default:
						$this->anio->ViewValue = $this->anio->CurrentValue;
				}
			} else {
				$this->anio->ViewValue = NULL;
			}
			$this->anio->ViewValue = ew_FormatNumber($this->anio->ViewValue, 0, -2, -2, -2);
			$this->anio->ViewCustomAttributes = "";

			// deuda
			$this->deuda->ViewValue = $this->deuda->CurrentValue;
			$this->deuda->ViewCustomAttributes = "";

			// pago
			$this->pago->ViewValue = $this->pago->CurrentValue;
			$this->pago->ViewCustomAttributes = "";

			// socio_nro
			$this->socio_nro->LinkCustomAttributes = "";
			$this->socio_nro->HrefValue = "";
			$this->socio_nro->TooltipValue = "";

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

			// mes
			$this->mes->LinkCustomAttributes = "";
			$this->mes->HrefValue = "";
			$this->mes->TooltipValue = "";

			// anio
			$this->anio->LinkCustomAttributes = "";
			$this->anio->HrefValue = "";
			$this->anio->TooltipValue = "";

			// deuda
			$this->deuda->LinkCustomAttributes = "";
			$this->deuda->HrefValue = "";
			$this->deuda->TooltipValue = "";

			// pago
			$this->pago->LinkCustomAttributes = "";
			$this->pago->HrefValue = "";
			$this->pago->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// socio_nro
			$this->socio_nro->EditAttrs["class"] = "form-control";
			$this->socio_nro->EditCustomAttributes = "";

			// cuit_cuil
			$this->cuit_cuil->EditAttrs["class"] = "form-control";
			$this->cuit_cuil->EditCustomAttributes = "";
			$this->cuit_cuil->EditValue = ew_HtmlEncode($this->cuit_cuil->AdvancedSearch->SearchValue);
			$this->cuit_cuil->PlaceHolder = ew_RemoveHtml($this->cuit_cuil->FldCaption());

			// propietario
			$this->propietario->EditAttrs["class"] = "form-control";
			$this->propietario->EditCustomAttributes = "";

			// comercio
			$this->comercio->EditAttrs["class"] = "form-control";
			$this->comercio->EditCustomAttributes = "";
			if (trim(strval($this->comercio->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->comercio->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `propietario` AS `Disp2Fld`, '' AS `Disp3Fld`, `comercio` AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `socios`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["v_total_estado_cuenta_x_anio_mes"]->UserIDAllow($GLOBALS["v_total_estado_cuenta_x_anio_mes"]->CurrentAction)) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->comercio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->comercio->EditValue = $arwrk;

			// mes
			$this->mes->EditAttrs["class"] = "form-control";
			$this->mes->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->mes->FldTagValue(1), $this->mes->FldTagCaption(1) <> "" ? $this->mes->FldTagCaption(1) : $this->mes->FldTagValue(1));
			$arwrk[] = array($this->mes->FldTagValue(2), $this->mes->FldTagCaption(2) <> "" ? $this->mes->FldTagCaption(2) : $this->mes->FldTagValue(2));
			$arwrk[] = array($this->mes->FldTagValue(3), $this->mes->FldTagCaption(3) <> "" ? $this->mes->FldTagCaption(3) : $this->mes->FldTagValue(3));
			$arwrk[] = array($this->mes->FldTagValue(4), $this->mes->FldTagCaption(4) <> "" ? $this->mes->FldTagCaption(4) : $this->mes->FldTagValue(4));
			$arwrk[] = array($this->mes->FldTagValue(5), $this->mes->FldTagCaption(5) <> "" ? $this->mes->FldTagCaption(5) : $this->mes->FldTagValue(5));
			$arwrk[] = array($this->mes->FldTagValue(6), $this->mes->FldTagCaption(6) <> "" ? $this->mes->FldTagCaption(6) : $this->mes->FldTagValue(6));
			$arwrk[] = array($this->mes->FldTagValue(7), $this->mes->FldTagCaption(7) <> "" ? $this->mes->FldTagCaption(7) : $this->mes->FldTagValue(7));
			$arwrk[] = array($this->mes->FldTagValue(8), $this->mes->FldTagCaption(8) <> "" ? $this->mes->FldTagCaption(8) : $this->mes->FldTagValue(8));
			$arwrk[] = array($this->mes->FldTagValue(9), $this->mes->FldTagCaption(9) <> "" ? $this->mes->FldTagCaption(9) : $this->mes->FldTagValue(9));
			$arwrk[] = array($this->mes->FldTagValue(10), $this->mes->FldTagCaption(10) <> "" ? $this->mes->FldTagCaption(10) : $this->mes->FldTagValue(10));
			$arwrk[] = array($this->mes->FldTagValue(11), $this->mes->FldTagCaption(11) <> "" ? $this->mes->FldTagCaption(11) : $this->mes->FldTagValue(11));
			$arwrk[] = array($this->mes->FldTagValue(12), $this->mes->FldTagCaption(12) <> "" ? $this->mes->FldTagCaption(12) : $this->mes->FldTagValue(12));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->mes->EditValue = $arwrk;
			$this->mes->EditAttrs["class"] = "form-control";
			$this->mes->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->mes->FldTagValue(1), $this->mes->FldTagCaption(1) <> "" ? $this->mes->FldTagCaption(1) : $this->mes->FldTagValue(1));
			$arwrk[] = array($this->mes->FldTagValue(2), $this->mes->FldTagCaption(2) <> "" ? $this->mes->FldTagCaption(2) : $this->mes->FldTagValue(2));
			$arwrk[] = array($this->mes->FldTagValue(3), $this->mes->FldTagCaption(3) <> "" ? $this->mes->FldTagCaption(3) : $this->mes->FldTagValue(3));
			$arwrk[] = array($this->mes->FldTagValue(4), $this->mes->FldTagCaption(4) <> "" ? $this->mes->FldTagCaption(4) : $this->mes->FldTagValue(4));
			$arwrk[] = array($this->mes->FldTagValue(5), $this->mes->FldTagCaption(5) <> "" ? $this->mes->FldTagCaption(5) : $this->mes->FldTagValue(5));
			$arwrk[] = array($this->mes->FldTagValue(6), $this->mes->FldTagCaption(6) <> "" ? $this->mes->FldTagCaption(6) : $this->mes->FldTagValue(6));
			$arwrk[] = array($this->mes->FldTagValue(7), $this->mes->FldTagCaption(7) <> "" ? $this->mes->FldTagCaption(7) : $this->mes->FldTagValue(7));
			$arwrk[] = array($this->mes->FldTagValue(8), $this->mes->FldTagCaption(8) <> "" ? $this->mes->FldTagCaption(8) : $this->mes->FldTagValue(8));
			$arwrk[] = array($this->mes->FldTagValue(9), $this->mes->FldTagCaption(9) <> "" ? $this->mes->FldTagCaption(9) : $this->mes->FldTagValue(9));
			$arwrk[] = array($this->mes->FldTagValue(10), $this->mes->FldTagCaption(10) <> "" ? $this->mes->FldTagCaption(10) : $this->mes->FldTagValue(10));
			$arwrk[] = array($this->mes->FldTagValue(11), $this->mes->FldTagCaption(11) <> "" ? $this->mes->FldTagCaption(11) : $this->mes->FldTagValue(11));
			$arwrk[] = array($this->mes->FldTagValue(12), $this->mes->FldTagCaption(12) <> "" ? $this->mes->FldTagCaption(12) : $this->mes->FldTagValue(12));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->mes->EditValue2 = $arwrk;

			// anio
			$this->anio->EditAttrs["class"] = "form-control";
			$this->anio->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->anio->FldTagValue(1), $this->anio->FldTagCaption(1) <> "" ? $this->anio->FldTagCaption(1) : $this->anio->FldTagValue(1));
			$arwrk[] = array($this->anio->FldTagValue(2), $this->anio->FldTagCaption(2) <> "" ? $this->anio->FldTagCaption(2) : $this->anio->FldTagValue(2));
			$arwrk[] = array($this->anio->FldTagValue(3), $this->anio->FldTagCaption(3) <> "" ? $this->anio->FldTagCaption(3) : $this->anio->FldTagValue(3));
			$arwrk[] = array($this->anio->FldTagValue(4), $this->anio->FldTagCaption(4) <> "" ? $this->anio->FldTagCaption(4) : $this->anio->FldTagValue(4));
			$arwrk[] = array($this->anio->FldTagValue(5), $this->anio->FldTagCaption(5) <> "" ? $this->anio->FldTagCaption(5) : $this->anio->FldTagValue(5));
			$arwrk[] = array($this->anio->FldTagValue(6), $this->anio->FldTagCaption(6) <> "" ? $this->anio->FldTagCaption(6) : $this->anio->FldTagValue(6));
			$arwrk[] = array($this->anio->FldTagValue(7), $this->anio->FldTagCaption(7) <> "" ? $this->anio->FldTagCaption(7) : $this->anio->FldTagValue(7));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->anio->EditValue = $arwrk;
			$this->anio->EditAttrs["class"] = "form-control";
			$this->anio->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->anio->FldTagValue(1), $this->anio->FldTagCaption(1) <> "" ? $this->anio->FldTagCaption(1) : $this->anio->FldTagValue(1));
			$arwrk[] = array($this->anio->FldTagValue(2), $this->anio->FldTagCaption(2) <> "" ? $this->anio->FldTagCaption(2) : $this->anio->FldTagValue(2));
			$arwrk[] = array($this->anio->FldTagValue(3), $this->anio->FldTagCaption(3) <> "" ? $this->anio->FldTagCaption(3) : $this->anio->FldTagValue(3));
			$arwrk[] = array($this->anio->FldTagValue(4), $this->anio->FldTagCaption(4) <> "" ? $this->anio->FldTagCaption(4) : $this->anio->FldTagValue(4));
			$arwrk[] = array($this->anio->FldTagValue(5), $this->anio->FldTagCaption(5) <> "" ? $this->anio->FldTagCaption(5) : $this->anio->FldTagValue(5));
			$arwrk[] = array($this->anio->FldTagValue(6), $this->anio->FldTagCaption(6) <> "" ? $this->anio->FldTagCaption(6) : $this->anio->FldTagValue(6));
			$arwrk[] = array($this->anio->FldTagValue(7), $this->anio->FldTagCaption(7) <> "" ? $this->anio->FldTagCaption(7) : $this->anio->FldTagValue(7));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->anio->EditValue2 = $arwrk;

			// deuda
			$this->deuda->EditAttrs["class"] = "form-control";
			$this->deuda->EditCustomAttributes = "";
			$this->deuda->EditValue = ew_HtmlEncode($this->deuda->AdvancedSearch->SearchValue);
			$this->deuda->PlaceHolder = ew_RemoveHtml($this->deuda->FldCaption());

			// pago
			$this->pago->EditAttrs["class"] = "form-control";
			$this->pago->EditCustomAttributes = "";
			$this->pago->EditValue = ew_HtmlEncode($this->pago->AdvancedSearch->SearchValue);
			$this->pago->PlaceHolder = ew_RemoveHtml($this->pago->FldCaption());
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
			$this->deuda->Total = 0; // Initialize total
			$this->pago->Total = 0; // Initialize total
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row
			$this->deuda->CurrentValue = $this->deuda->Total;
			$this->deuda->ViewValue = $this->deuda->CurrentValue;
			$this->deuda->ViewCustomAttributes = "";
			$this->deuda->HrefValue = ""; // Clear href value
			$this->pago->CurrentValue = $this->pago->Total;
			$this->pago->ViewValue = $this->pago->CurrentValue;
			$this->pago->ViewCustomAttributes = "";
			$this->pago->HrefValue = ""; // Clear href value
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->socio_nro->AdvancedSearch->Load();
		$this->cuit_cuil->AdvancedSearch->Load();
		$this->propietario->AdvancedSearch->Load();
		$this->comercio->AdvancedSearch->Load();
		$this->mes->AdvancedSearch->Load();
		$this->anio->AdvancedSearch->Load();
		$this->deuda->AdvancedSearch->Load();
		$this->pago->AdvancedSearch->Load();
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

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_v_total_estado_cuenta_x_anio_mes\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_v_total_estado_cuenta_x_anio_mes',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fv_total_estado_cuenta_x_anio_meslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Call Page Exported server event
		$this->Page_Exported();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EW_EMAIL_CHARSET;
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= $EmailContent; // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Build QueryString for search
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}
		$this->AddSearchQueryString($sQry, $this->socio_nro); // socio_nro
		$this->AddSearchQueryString($sQry, $this->cuit_cuil); // cuit_cuil
		$this->AddSearchQueryString($sQry, $this->propietario); // propietario
		$this->AddSearchQueryString($sQry, $this->comercio); // comercio
		$this->AddSearchQueryString($sQry, $this->mes); // mes
		$this->AddSearchQueryString($sQry, $this->anio); // anio
		$this->AddSearchQueryString($sQry, $this->deuda); // deuda
		$this->AddSearchQueryString($sQry, $this->pago); // pago

		// Build QueryString for pager
		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($v_total_estado_cuenta_x_anio_mes_list)) $v_total_estado_cuenta_x_anio_mes_list = new cv_total_estado_cuenta_x_anio_mes_list();

// Page init
$v_total_estado_cuenta_x_anio_mes_list->Page_Init();

// Page main
$v_total_estado_cuenta_x_anio_mes_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$v_total_estado_cuenta_x_anio_mes_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_header.php" ?>
<?php if ($v_total_estado_cuenta_x_anio_mes->Export == "") { ?>
<script type="text/javascript">

// Page object
var v_total_estado_cuenta_x_anio_mes_list = new ew_Page("v_total_estado_cuenta_x_anio_mes_list");
v_total_estado_cuenta_x_anio_mes_list.PageID = "list"; // Page ID
var EW_PAGE_ID = v_total_estado_cuenta_x_anio_mes_list.PageID; // For backward compatibility

// Form object
var fv_total_estado_cuenta_x_anio_meslist = new ew_Form("fv_total_estado_cuenta_x_anio_meslist");
fv_total_estado_cuenta_x_anio_meslist.FormKeyCountName = '<?php echo $v_total_estado_cuenta_x_anio_mes_list->FormKeyCountName ?>';

// Form_CustomValidate event
fv_total_estado_cuenta_x_anio_meslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_total_estado_cuenta_x_anio_meslist.ValidateRequired = true;
<?php } else { ?>
fv_total_estado_cuenta_x_anio_meslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fv_total_estado_cuenta_x_anio_meslist.Lists["x_socio_nro"] = {"LinkField":"x_socio_nro","Ajax":true,"AutoFill":false,"DisplayFields":["x_socio_nro","x_comercio","x_propietario",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fv_total_estado_cuenta_x_anio_meslist.Lists["x_propietario"] = {"LinkField":"x_socio_nro","Ajax":true,"AutoFill":false,"DisplayFields":["x_socio_nro","x_propietario","","x_comercio"],"ParentFields":[],"FilterFields":[],"Options":[]};
fv_total_estado_cuenta_x_anio_meslist.Lists["x_comercio"] = {"LinkField":"x_socio_nro","Ajax":true,"AutoFill":false,"DisplayFields":["x_socio_nro","x_propietario","","x_comercio"],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fv_total_estado_cuenta_x_anio_meslistsrch = new ew_Form("fv_total_estado_cuenta_x_anio_meslistsrch");

// Validate function for search
fv_total_estado_cuenta_x_anio_meslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fv_total_estado_cuenta_x_anio_meslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_total_estado_cuenta_x_anio_meslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fv_total_estado_cuenta_x_anio_meslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fv_total_estado_cuenta_x_anio_meslistsrch.Lists["x_comercio"] = {"LinkField":"x_socio_nro","Ajax":true,"AutoFill":false,"DisplayFields":["x_socio_nro","x_propietario","","x_comercio"],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($v_total_estado_cuenta_x_anio_mes->Export == "") { ?>
<div class="ewToolbar">
<?php if ($v_total_estado_cuenta_x_anio_mes->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($v_total_estado_cuenta_x_anio_mes_list->TotalRecs > 0 && $v_total_estado_cuenta_x_anio_mes_list->ExportOptions->Visible()) { ?>
<?php $v_total_estado_cuenta_x_anio_mes_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($v_total_estado_cuenta_x_anio_mes_list->SearchOptions->Visible()) { ?>
<?php $v_total_estado_cuenta_x_anio_mes_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($v_total_estado_cuenta_x_anio_mes->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$v_total_estado_cuenta_x_anio_mes_list->TotalRecs = $v_total_estado_cuenta_x_anio_mes->SelectRecordCount();
	} else {
		if ($v_total_estado_cuenta_x_anio_mes_list->Recordset = $v_total_estado_cuenta_x_anio_mes_list->LoadRecordset())
			$v_total_estado_cuenta_x_anio_mes_list->TotalRecs = $v_total_estado_cuenta_x_anio_mes_list->Recordset->RecordCount();
	}
	$v_total_estado_cuenta_x_anio_mes_list->StartRec = 1;
	if ($v_total_estado_cuenta_x_anio_mes_list->DisplayRecs <= 0 || ($v_total_estado_cuenta_x_anio_mes->Export <> "" && $v_total_estado_cuenta_x_anio_mes->ExportAll)) // Display all records
		$v_total_estado_cuenta_x_anio_mes_list->DisplayRecs = $v_total_estado_cuenta_x_anio_mes_list->TotalRecs;
	if (!($v_total_estado_cuenta_x_anio_mes->Export <> "" && $v_total_estado_cuenta_x_anio_mes->ExportAll))
		$v_total_estado_cuenta_x_anio_mes_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$v_total_estado_cuenta_x_anio_mes_list->Recordset = $v_total_estado_cuenta_x_anio_mes_list->LoadRecordset($v_total_estado_cuenta_x_anio_mes_list->StartRec-1, $v_total_estado_cuenta_x_anio_mes_list->DisplayRecs);

	// Set no record found message
	if ($v_total_estado_cuenta_x_anio_mes->CurrentAction == "" && $v_total_estado_cuenta_x_anio_mes_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$v_total_estado_cuenta_x_anio_mes_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($v_total_estado_cuenta_x_anio_mes_list->SearchWhere == "0=101")
			$v_total_estado_cuenta_x_anio_mes_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$v_total_estado_cuenta_x_anio_mes_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$v_total_estado_cuenta_x_anio_mes_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($v_total_estado_cuenta_x_anio_mes->Export == "" && $v_total_estado_cuenta_x_anio_mes->CurrentAction == "") { ?>
<form name="fv_total_estado_cuenta_x_anio_meslistsrch" id="fv_total_estado_cuenta_x_anio_meslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($v_total_estado_cuenta_x_anio_mes_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fv_total_estado_cuenta_x_anio_meslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="v_total_estado_cuenta_x_anio_mes">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$v_total_estado_cuenta_x_anio_mes_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$v_total_estado_cuenta_x_anio_mes->RowType = EW_ROWTYPE_SEARCH;

// Render row
$v_total_estado_cuenta_x_anio_mes->ResetAttrs();
$v_total_estado_cuenta_x_anio_mes_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($v_total_estado_cuenta_x_anio_mes->comercio->Visible) { // comercio ?>
	<div id="xsc_comercio" class="ewCell form-group">
		<label for="x_comercio" class="ewSearchCaption ewLabel"><?php echo $v_total_estado_cuenta_x_anio_mes->comercio->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_comercio" id="z_comercio" value="LIKE"></span>
		<span class="ewSearchField">
<select data-field="x_comercio" id="x_comercio" name="x_comercio"<?php echo $v_total_estado_cuenta_x_anio_mes->comercio->EditAttributes() ?>>
<?php
if (is_array($v_total_estado_cuenta_x_anio_mes->comercio->EditValue)) {
	$arwrk = $v_total_estado_cuenta_x_anio_mes->comercio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($v_total_estado_cuenta_x_anio_mes->comercio->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$v_total_estado_cuenta_x_anio_mes->comercio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `propietario` AS `Disp2Fld`, '' AS `Disp3Fld`, `comercio` AS `Disp4Fld` FROM `socios`";
$sWhereWrk = "";
if (!$GLOBALS["v_total_estado_cuenta_x_anio_mes"]->UserIDAllow($GLOBALS["v_total_estado_cuenta_x_anio_mes"]->CurrentAction)) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

// Call Lookup selecting
$v_total_estado_cuenta_x_anio_mes->Lookup_Selecting($v_total_estado_cuenta_x_anio_mes->comercio, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_comercio" id="s_x_comercio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($v_total_estado_cuenta_x_anio_mes->mes->Visible) { // mes ?>
	<div id="xsc_mes" class="ewCell form-group">
		<label for="x_mes" class="ewSearchCaption ewLabel"><?php echo $v_total_estado_cuenta_x_anio_mes->mes->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_mes" id="z_mes" value="BETWEEN"></span>
		<span class="ewSearchField">
<select data-field="x_mes" id="x_mes" name="x_mes"<?php echo $v_total_estado_cuenta_x_anio_mes->mes->EditAttributes() ?>>
<?php
if (is_array($v_total_estado_cuenta_x_anio_mes->mes->EditValue)) {
	$arwrk = $v_total_estado_cuenta_x_anio_mes->mes->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($v_total_estado_cuenta_x_anio_mes->mes->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
		<span class="ewSearchCond btw1_mes">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_mes">
<select data-field="x_mes" id="y_mes" name="y_mes"<?php echo $v_total_estado_cuenta_x_anio_mes->mes->EditAttributes() ?>>
<?php
if (is_array($v_total_estado_cuenta_x_anio_mes->mes->EditValue2)) {
	$arwrk = $v_total_estado_cuenta_x_anio_mes->mes->EditValue2;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($v_total_estado_cuenta_x_anio_mes->mes->AdvancedSearch->SearchValue2) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($v_total_estado_cuenta_x_anio_mes->anio->Visible) { // anio ?>
	<div id="xsc_anio" class="ewCell form-group">
		<label for="x_anio" class="ewSearchCaption ewLabel"><?php echo $v_total_estado_cuenta_x_anio_mes->anio->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_anio" id="z_anio" value="BETWEEN"></span>
		<span class="ewSearchField">
<select data-field="x_anio" id="x_anio" name="x_anio"<?php echo $v_total_estado_cuenta_x_anio_mes->anio->EditAttributes() ?>>
<?php
if (is_array($v_total_estado_cuenta_x_anio_mes->anio->EditValue)) {
	$arwrk = $v_total_estado_cuenta_x_anio_mes->anio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($v_total_estado_cuenta_x_anio_mes->anio->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
		<span class="ewSearchCond btw1_anio">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_anio">
<select data-field="x_anio" id="y_anio" name="y_anio"<?php echo $v_total_estado_cuenta_x_anio_mes->anio->EditAttributes() ?>>
<?php
if (is_array($v_total_estado_cuenta_x_anio_mes->anio->EditValue2)) {
	$arwrk = $v_total_estado_cuenta_x_anio_mes->anio->EditValue2;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($v_total_estado_cuenta_x_anio_mes->anio->AdvancedSearch->SearchValue2) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($v_total_estado_cuenta_x_anio_mes_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($v_total_estado_cuenta_x_anio_mes_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $v_total_estado_cuenta_x_anio_mes_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($v_total_estado_cuenta_x_anio_mes_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($v_total_estado_cuenta_x_anio_mes_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($v_total_estado_cuenta_x_anio_mes_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($v_total_estado_cuenta_x_anio_mes_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $v_total_estado_cuenta_x_anio_mes_list->ShowPageHeader(); ?>
<?php
$v_total_estado_cuenta_x_anio_mes_list->ShowMessage();
?>
<?php if ($v_total_estado_cuenta_x_anio_mes_list->TotalRecs > 0 || $v_total_estado_cuenta_x_anio_mes->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($v_total_estado_cuenta_x_anio_mes->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($v_total_estado_cuenta_x_anio_mes->CurrentAction <> "gridadd" && $v_total_estado_cuenta_x_anio_mes->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($v_total_estado_cuenta_x_anio_mes_list->Pager)) $v_total_estado_cuenta_x_anio_mes_list->Pager = new cPrevNextPager($v_total_estado_cuenta_x_anio_mes_list->StartRec, $v_total_estado_cuenta_x_anio_mes_list->DisplayRecs, $v_total_estado_cuenta_x_anio_mes_list->TotalRecs) ?>
<?php if ($v_total_estado_cuenta_x_anio_mes_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($v_total_estado_cuenta_x_anio_mes_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $v_total_estado_cuenta_x_anio_mes_list->PageUrl() ?>start=<?php echo $v_total_estado_cuenta_x_anio_mes_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($v_total_estado_cuenta_x_anio_mes_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $v_total_estado_cuenta_x_anio_mes_list->PageUrl() ?>start=<?php echo $v_total_estado_cuenta_x_anio_mes_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $v_total_estado_cuenta_x_anio_mes_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($v_total_estado_cuenta_x_anio_mes_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $v_total_estado_cuenta_x_anio_mes_list->PageUrl() ?>start=<?php echo $v_total_estado_cuenta_x_anio_mes_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($v_total_estado_cuenta_x_anio_mes_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $v_total_estado_cuenta_x_anio_mes_list->PageUrl() ?>start=<?php echo $v_total_estado_cuenta_x_anio_mes_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $v_total_estado_cuenta_x_anio_mes_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $v_total_estado_cuenta_x_anio_mes_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $v_total_estado_cuenta_x_anio_mes_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $v_total_estado_cuenta_x_anio_mes_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_total_estado_cuenta_x_anio_mes_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fv_total_estado_cuenta_x_anio_meslist" id="fv_total_estado_cuenta_x_anio_meslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($v_total_estado_cuenta_x_anio_mes_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $v_total_estado_cuenta_x_anio_mes_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="v_total_estado_cuenta_x_anio_mes">
<div id="gmp_v_total_estado_cuenta_x_anio_mes" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($v_total_estado_cuenta_x_anio_mes_list->TotalRecs > 0) { ?>
<table id="tbl_v_total_estado_cuenta_x_anio_meslist" class="table ewTable">
<?php echo $v_total_estado_cuenta_x_anio_mes->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$v_total_estado_cuenta_x_anio_mes_list->RenderListOptions();

// Render list options (header, left)
$v_total_estado_cuenta_x_anio_mes_list->ListOptions->Render("header", "left");
?>
<?php if ($v_total_estado_cuenta_x_anio_mes->socio_nro->Visible) { // socio_nro ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->socio_nro) == "") { ?>
		<th data-name="socio_nro"><div id="elh_v_total_estado_cuenta_x_anio_mes_socio_nro" class="v_total_estado_cuenta_x_anio_mes_socio_nro"><div class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->socio_nro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="socio_nro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->socio_nro) ?>',1);"><div id="elh_v_total_estado_cuenta_x_anio_mes_socio_nro" class="v_total_estado_cuenta_x_anio_mes_socio_nro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->socio_nro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_total_estado_cuenta_x_anio_mes->socio_nro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_total_estado_cuenta_x_anio_mes->socio_nro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_total_estado_cuenta_x_anio_mes->cuit_cuil->Visible) { // cuit_cuil ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->cuit_cuil) == "") { ?>
		<th data-name="cuit_cuil"><div id="elh_v_total_estado_cuenta_x_anio_mes_cuit_cuil" class="v_total_estado_cuenta_x_anio_mes_cuit_cuil"><div class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->cuit_cuil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cuit_cuil"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->cuit_cuil) ?>',1);"><div id="elh_v_total_estado_cuenta_x_anio_mes_cuit_cuil" class="v_total_estado_cuenta_x_anio_mes_cuit_cuil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->cuit_cuil->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_total_estado_cuenta_x_anio_mes->cuit_cuil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_total_estado_cuenta_x_anio_mes->cuit_cuil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_total_estado_cuenta_x_anio_mes->propietario->Visible) { // propietario ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->propietario) == "") { ?>
		<th data-name="propietario"><div id="elh_v_total_estado_cuenta_x_anio_mes_propietario" class="v_total_estado_cuenta_x_anio_mes_propietario"><div class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->propietario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="propietario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->propietario) ?>',1);"><div id="elh_v_total_estado_cuenta_x_anio_mes_propietario" class="v_total_estado_cuenta_x_anio_mes_propietario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->propietario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_total_estado_cuenta_x_anio_mes->propietario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_total_estado_cuenta_x_anio_mes->propietario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_total_estado_cuenta_x_anio_mes->comercio->Visible) { // comercio ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->comercio) == "") { ?>
		<th data-name="comercio"><div id="elh_v_total_estado_cuenta_x_anio_mes_comercio" class="v_total_estado_cuenta_x_anio_mes_comercio"><div class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->comercio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comercio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->comercio) ?>',1);"><div id="elh_v_total_estado_cuenta_x_anio_mes_comercio" class="v_total_estado_cuenta_x_anio_mes_comercio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->comercio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_total_estado_cuenta_x_anio_mes->comercio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_total_estado_cuenta_x_anio_mes->comercio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_total_estado_cuenta_x_anio_mes->mes->Visible) { // mes ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->mes) == "") { ?>
		<th data-name="mes"><div id="elh_v_total_estado_cuenta_x_anio_mes_mes" class="v_total_estado_cuenta_x_anio_mes_mes"><div class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->mes->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mes"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->mes) ?>',1);"><div id="elh_v_total_estado_cuenta_x_anio_mes_mes" class="v_total_estado_cuenta_x_anio_mes_mes">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->mes->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_total_estado_cuenta_x_anio_mes->mes->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_total_estado_cuenta_x_anio_mes->mes->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_total_estado_cuenta_x_anio_mes->anio->Visible) { // anio ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->anio) == "") { ?>
		<th data-name="anio"><div id="elh_v_total_estado_cuenta_x_anio_mes_anio" class="v_total_estado_cuenta_x_anio_mes_anio"><div class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->anio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="anio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->anio) ?>',1);"><div id="elh_v_total_estado_cuenta_x_anio_mes_anio" class="v_total_estado_cuenta_x_anio_mes_anio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->anio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_total_estado_cuenta_x_anio_mes->anio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_total_estado_cuenta_x_anio_mes->anio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_total_estado_cuenta_x_anio_mes->deuda->Visible) { // deuda ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->deuda) == "") { ?>
		<th data-name="deuda"><div id="elh_v_total_estado_cuenta_x_anio_mes_deuda" class="v_total_estado_cuenta_x_anio_mes_deuda"><div class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->deuda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="deuda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->deuda) ?>',1);"><div id="elh_v_total_estado_cuenta_x_anio_mes_deuda" class="v_total_estado_cuenta_x_anio_mes_deuda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->deuda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_total_estado_cuenta_x_anio_mes->deuda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_total_estado_cuenta_x_anio_mes->deuda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_total_estado_cuenta_x_anio_mes->pago->Visible) { // pago ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->pago) == "") { ?>
		<th data-name="pago"><div id="elh_v_total_estado_cuenta_x_anio_mes_pago" class="v_total_estado_cuenta_x_anio_mes_pago"><div class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->pago->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pago"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_total_estado_cuenta_x_anio_mes->SortUrl($v_total_estado_cuenta_x_anio_mes->pago) ?>',1);"><div id="elh_v_total_estado_cuenta_x_anio_mes_pago" class="v_total_estado_cuenta_x_anio_mes_pago">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_total_estado_cuenta_x_anio_mes->pago->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_total_estado_cuenta_x_anio_mes->pago->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_total_estado_cuenta_x_anio_mes->pago->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$v_total_estado_cuenta_x_anio_mes_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($v_total_estado_cuenta_x_anio_mes->ExportAll && $v_total_estado_cuenta_x_anio_mes->Export <> "") {
	$v_total_estado_cuenta_x_anio_mes_list->StopRec = $v_total_estado_cuenta_x_anio_mes_list->TotalRecs;
} else {

	// Set the last record to display
	if ($v_total_estado_cuenta_x_anio_mes_list->TotalRecs > $v_total_estado_cuenta_x_anio_mes_list->StartRec + $v_total_estado_cuenta_x_anio_mes_list->DisplayRecs - 1)
		$v_total_estado_cuenta_x_anio_mes_list->StopRec = $v_total_estado_cuenta_x_anio_mes_list->StartRec + $v_total_estado_cuenta_x_anio_mes_list->DisplayRecs - 1;
	else
		$v_total_estado_cuenta_x_anio_mes_list->StopRec = $v_total_estado_cuenta_x_anio_mes_list->TotalRecs;
}
$v_total_estado_cuenta_x_anio_mes_list->RecCnt = $v_total_estado_cuenta_x_anio_mes_list->StartRec - 1;
if ($v_total_estado_cuenta_x_anio_mes_list->Recordset && !$v_total_estado_cuenta_x_anio_mes_list->Recordset->EOF) {
	$v_total_estado_cuenta_x_anio_mes_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $v_total_estado_cuenta_x_anio_mes_list->StartRec > 1)
		$v_total_estado_cuenta_x_anio_mes_list->Recordset->Move($v_total_estado_cuenta_x_anio_mes_list->StartRec - 1);
} elseif (!$v_total_estado_cuenta_x_anio_mes->AllowAddDeleteRow && $v_total_estado_cuenta_x_anio_mes_list->StopRec == 0) {
	$v_total_estado_cuenta_x_anio_mes_list->StopRec = $v_total_estado_cuenta_x_anio_mes->GridAddRowCount;
}

// Initialize aggregate
$v_total_estado_cuenta_x_anio_mes->RowType = EW_ROWTYPE_AGGREGATEINIT;
$v_total_estado_cuenta_x_anio_mes->ResetAttrs();
$v_total_estado_cuenta_x_anio_mes_list->RenderRow();
while ($v_total_estado_cuenta_x_anio_mes_list->RecCnt < $v_total_estado_cuenta_x_anio_mes_list->StopRec) {
	$v_total_estado_cuenta_x_anio_mes_list->RecCnt++;
	if (intval($v_total_estado_cuenta_x_anio_mes_list->RecCnt) >= intval($v_total_estado_cuenta_x_anio_mes_list->StartRec)) {
		$v_total_estado_cuenta_x_anio_mes_list->RowCnt++;

		// Set up key count
		$v_total_estado_cuenta_x_anio_mes_list->KeyCount = $v_total_estado_cuenta_x_anio_mes_list->RowIndex;

		// Init row class and style
		$v_total_estado_cuenta_x_anio_mes->ResetAttrs();
		$v_total_estado_cuenta_x_anio_mes->CssClass = "";
		if ($v_total_estado_cuenta_x_anio_mes->CurrentAction == "gridadd") {
		} else {
			$v_total_estado_cuenta_x_anio_mes_list->LoadRowValues($v_total_estado_cuenta_x_anio_mes_list->Recordset); // Load row values
		}
		$v_total_estado_cuenta_x_anio_mes->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$v_total_estado_cuenta_x_anio_mes->RowAttrs = array_merge($v_total_estado_cuenta_x_anio_mes->RowAttrs, array('data-rowindex'=>$v_total_estado_cuenta_x_anio_mes_list->RowCnt, 'id'=>'r' . $v_total_estado_cuenta_x_anio_mes_list->RowCnt . '_v_total_estado_cuenta_x_anio_mes', 'data-rowtype'=>$v_total_estado_cuenta_x_anio_mes->RowType));

		// Render row
		$v_total_estado_cuenta_x_anio_mes_list->RenderRow();

		// Render list options
		$v_total_estado_cuenta_x_anio_mes_list->RenderListOptions();
?>
	<tr<?php echo $v_total_estado_cuenta_x_anio_mes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_total_estado_cuenta_x_anio_mes_list->ListOptions->Render("body", "left", $v_total_estado_cuenta_x_anio_mes_list->RowCnt);
?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->socio_nro->Visible) { // socio_nro ?>
		<td data-name="socio_nro"<?php echo $v_total_estado_cuenta_x_anio_mes->socio_nro->CellAttributes() ?>>
<span<?php echo $v_total_estado_cuenta_x_anio_mes->socio_nro->ViewAttributes() ?>>
<?php echo $v_total_estado_cuenta_x_anio_mes->socio_nro->ListViewValue() ?></span>
<a id="<?php echo $v_total_estado_cuenta_x_anio_mes_list->PageObjName . "_row_" . $v_total_estado_cuenta_x_anio_mes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->cuit_cuil->Visible) { // cuit_cuil ?>
		<td data-name="cuit_cuil"<?php echo $v_total_estado_cuenta_x_anio_mes->cuit_cuil->CellAttributes() ?>>
<span<?php echo $v_total_estado_cuenta_x_anio_mes->cuit_cuil->ViewAttributes() ?>>
<?php echo $v_total_estado_cuenta_x_anio_mes->cuit_cuil->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->propietario->Visible) { // propietario ?>
		<td data-name="propietario"<?php echo $v_total_estado_cuenta_x_anio_mes->propietario->CellAttributes() ?>>
<span<?php echo $v_total_estado_cuenta_x_anio_mes->propietario->ViewAttributes() ?>>
<?php echo $v_total_estado_cuenta_x_anio_mes->propietario->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->comercio->Visible) { // comercio ?>
		<td data-name="comercio"<?php echo $v_total_estado_cuenta_x_anio_mes->comercio->CellAttributes() ?>>
<span<?php echo $v_total_estado_cuenta_x_anio_mes->comercio->ViewAttributes() ?>>
<?php echo $v_total_estado_cuenta_x_anio_mes->comercio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->mes->Visible) { // mes ?>
		<td data-name="mes"<?php echo $v_total_estado_cuenta_x_anio_mes->mes->CellAttributes() ?>>
<span<?php echo $v_total_estado_cuenta_x_anio_mes->mes->ViewAttributes() ?>>
<?php echo $v_total_estado_cuenta_x_anio_mes->mes->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->anio->Visible) { // anio ?>
		<td data-name="anio"<?php echo $v_total_estado_cuenta_x_anio_mes->anio->CellAttributes() ?>>
<span<?php echo $v_total_estado_cuenta_x_anio_mes->anio->ViewAttributes() ?>>
<?php echo $v_total_estado_cuenta_x_anio_mes->anio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->deuda->Visible) { // deuda ?>
		<td data-name="deuda"<?php echo $v_total_estado_cuenta_x_anio_mes->deuda->CellAttributes() ?>>
<span<?php echo $v_total_estado_cuenta_x_anio_mes->deuda->ViewAttributes() ?>>
<?php echo $v_total_estado_cuenta_x_anio_mes->deuda->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->pago->Visible) { // pago ?>
		<td data-name="pago"<?php echo $v_total_estado_cuenta_x_anio_mes->pago->CellAttributes() ?>>
<span<?php echo $v_total_estado_cuenta_x_anio_mes->pago->ViewAttributes() ?>>
<?php echo $v_total_estado_cuenta_x_anio_mes->pago->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_total_estado_cuenta_x_anio_mes_list->ListOptions->Render("body", "right", $v_total_estado_cuenta_x_anio_mes_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($v_total_estado_cuenta_x_anio_mes->CurrentAction <> "gridadd")
		$v_total_estado_cuenta_x_anio_mes_list->Recordset->MoveNext();
}
?>
</tbody>
<?php

// Render aggregate row
$v_total_estado_cuenta_x_anio_mes->RowType = EW_ROWTYPE_AGGREGATE;
$v_total_estado_cuenta_x_anio_mes->ResetAttrs();
$v_total_estado_cuenta_x_anio_mes_list->RenderRow();
?>
<?php if ($v_total_estado_cuenta_x_anio_mes_list->TotalRecs > 0 && ($v_total_estado_cuenta_x_anio_mes->CurrentAction <> "gridadd" && $v_total_estado_cuenta_x_anio_mes->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options
$v_total_estado_cuenta_x_anio_mes_list->RenderListOptions();

// Render list options (footer, left)
$v_total_estado_cuenta_x_anio_mes_list->ListOptions->Render("footer", "left");
?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->socio_nro->Visible) { // socio_nro ?>
		<td data-name="socio_nro"><span id="elf_v_total_estado_cuenta_x_anio_mes_socio_nro" class="v_total_estado_cuenta_x_anio_mes_socio_nro">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->cuit_cuil->Visible) { // cuit_cuil ?>
		<td data-name="cuit_cuil"><span id="elf_v_total_estado_cuenta_x_anio_mes_cuit_cuil" class="v_total_estado_cuenta_x_anio_mes_cuit_cuil">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->propietario->Visible) { // propietario ?>
		<td data-name="propietario"><span id="elf_v_total_estado_cuenta_x_anio_mes_propietario" class="v_total_estado_cuenta_x_anio_mes_propietario">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->comercio->Visible) { // comercio ?>
		<td data-name="comercio"><span id="elf_v_total_estado_cuenta_x_anio_mes_comercio" class="v_total_estado_cuenta_x_anio_mes_comercio">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->mes->Visible) { // mes ?>
		<td data-name="mes"><span id="elf_v_total_estado_cuenta_x_anio_mes_mes" class="v_total_estado_cuenta_x_anio_mes_mes">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->anio->Visible) { // anio ?>
		<td data-name="anio"><span id="elf_v_total_estado_cuenta_x_anio_mes_anio" class="v_total_estado_cuenta_x_anio_mes_anio">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->deuda->Visible) { // deuda ?>
		<td data-name="deuda"><span id="elf_v_total_estado_cuenta_x_anio_mes_deuda" class="v_total_estado_cuenta_x_anio_mes_deuda">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $v_total_estado_cuenta_x_anio_mes->deuda->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($v_total_estado_cuenta_x_anio_mes->pago->Visible) { // pago ?>
		<td data-name="pago"><span id="elf_v_total_estado_cuenta_x_anio_mes_pago" class="v_total_estado_cuenta_x_anio_mes_pago">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $v_total_estado_cuenta_x_anio_mes->pago->ViewValue ?>
		</span></td>
	<?php } ?>
<?php

// Render list options (footer, right)
$v_total_estado_cuenta_x_anio_mes_list->ListOptions->Render("footer", "right");
?>
	</tr>
</tfoot>	
<?php } ?>
</table>
<?php } ?>
<?php if ($v_total_estado_cuenta_x_anio_mes->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($v_total_estado_cuenta_x_anio_mes_list->Recordset)
	$v_total_estado_cuenta_x_anio_mes_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($v_total_estado_cuenta_x_anio_mes_list->TotalRecs == 0 && $v_total_estado_cuenta_x_anio_mes->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_total_estado_cuenta_x_anio_mes_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($v_total_estado_cuenta_x_anio_mes->Export == "") { ?>
<script type="text/javascript">
fv_total_estado_cuenta_x_anio_meslistsrch.Init();
fv_total_estado_cuenta_x_anio_meslist.Init();
</script>
<?php } ?>
<?php
$v_total_estado_cuenta_x_anio_mes_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($v_total_estado_cuenta_x_anio_mes->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_footer.php" ?>
<?php
$v_total_estado_cuenta_x_anio_mes_list->Page_Terminate();
?>
