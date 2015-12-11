<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "v_gastos_hoja_mantenimientoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$v_gastos_hoja_mantenimiento_list = NULL; // Initialize page object first

class cv_gastos_hoja_mantenimiento_list extends cv_gastos_hoja_mantenimiento {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'v_gastos_hoja_mantenimiento';

	// Page object name
	var $PageObjName = 'v_gastos_hoja_mantenimiento_list';

	// Grid form hidden field names
	var $FormName = 'fv_gastos_hoja_mantenimientolist';
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

		// Table object (v_gastos_hoja_mantenimiento)
		if (!isset($GLOBALS["v_gastos_hoja_mantenimiento"]) || get_class($GLOBALS["v_gastos_hoja_mantenimiento"]) == "cv_gastos_hoja_mantenimiento") {
			$GLOBALS["v_gastos_hoja_mantenimiento"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["v_gastos_hoja_mantenimiento"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "v_gastos_hoja_mantenimientoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "v_gastos_hoja_mantenimientodelete.php";
		$this->MultiUpdateUrl = "v_gastos_hoja_mantenimientoupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'v_gastos_hoja_mantenimiento', TRUE);

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
		global $EW_EXPORT, $v_gastos_hoja_mantenimiento;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($v_gastos_hoja_mantenimiento);
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
	var $DisplayRecs = 30;
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

			// Get basic search values
			$this->LoadBasicSearchValues();

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
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 30; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
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

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->fecha_ini, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fecha_fin, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Patente, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->taller, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipo_mantenimiento, $arKeywords, $type);
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
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->fecha_ini); // fecha_ini
			$this->UpdateSort($this->fecha_fin); // fecha_fin
			$this->UpdateSort($this->Patente); // Patente
			$this->UpdateSort($this->taller); // taller
			$this->UpdateSort($this->tipo_mantenimiento); // tipo_mantenimiento
			$this->UpdateSort($this->nro_hoja_mantenimiento); // nro_hoja_mantenimiento
			$this->UpdateSort($this->total_gasto); // total_gasto
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
				$this->fecha_ini->setSort("");
				$this->fecha_fin->setSort("");
				$this->Patente->setSort("");
				$this->taller->setSort("");
				$this->tipo_mantenimiento->setSort("");
				$this->nro_hoja_mantenimiento->setSort("");
				$this->total_gasto->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fv_gastos_hoja_mantenimientolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fv_gastos_hoja_mantenimientolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->fecha_ini->setDbValue($rs->fields('fecha_ini'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->Patente->setDbValue($rs->fields('Patente'));
		$this->taller->setDbValue($rs->fields('taller'));
		$this->tipo_mantenimiento->setDbValue($rs->fields('tipo_mantenimiento'));
		$this->nro_hoja_mantenimiento->setDbValue($rs->fields('nro_hoja_mantenimiento'));
		$this->total_gasto->setDbValue($rs->fields('total_gasto'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->fecha_ini->DbValue = $row['fecha_ini'];
		$this->fecha_fin->DbValue = $row['fecha_fin'];
		$this->Patente->DbValue = $row['Patente'];
		$this->taller->DbValue = $row['taller'];
		$this->tipo_mantenimiento->DbValue = $row['tipo_mantenimiento'];
		$this->nro_hoja_mantenimiento->DbValue = $row['nro_hoja_mantenimiento'];
		$this->total_gasto->DbValue = $row['total_gasto'];
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
		if ($this->total_gasto->FormValue == $this->total_gasto->CurrentValue && is_numeric(ew_StrToFloat($this->total_gasto->CurrentValue)))
			$this->total_gasto->CurrentValue = ew_StrToFloat($this->total_gasto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// fecha_ini
		// fecha_fin
		// Patente
		// taller
		// tipo_mantenimiento
		// nro_hoja_mantenimiento
		// total_gasto

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// fecha_ini
			$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
			$this->fecha_ini->ViewCustomAttributes = "";

			// fecha_fin
			$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
			$this->fecha_fin->ViewCustomAttributes = "";

			// Patente
			$this->Patente->ViewValue = $this->Patente->CurrentValue;
			$this->Patente->ViewCustomAttributes = "";

			// taller
			$this->taller->ViewValue = $this->taller->CurrentValue;
			$this->taller->ViewCustomAttributes = "";

			// tipo_mantenimiento
			$this->tipo_mantenimiento->ViewValue = $this->tipo_mantenimiento->CurrentValue;
			$this->tipo_mantenimiento->ViewCustomAttributes = "";

			// nro_hoja_mantenimiento
			$this->nro_hoja_mantenimiento->ViewValue = $this->nro_hoja_mantenimiento->CurrentValue;
			$this->nro_hoja_mantenimiento->ViewCustomAttributes = "";

			// total_gasto
			$this->total_gasto->ViewValue = $this->total_gasto->CurrentValue;
			$this->total_gasto->ViewCustomAttributes = "";

			// fecha_ini
			$this->fecha_ini->LinkCustomAttributes = "";
			$this->fecha_ini->HrefValue = "";
			$this->fecha_ini->TooltipValue = "";

			// fecha_fin
			$this->fecha_fin->LinkCustomAttributes = "";
			$this->fecha_fin->HrefValue = "";
			$this->fecha_fin->TooltipValue = "";

			// Patente
			$this->Patente->LinkCustomAttributes = "";
			$this->Patente->HrefValue = "";
			$this->Patente->TooltipValue = "";

			// taller
			$this->taller->LinkCustomAttributes = "";
			$this->taller->HrefValue = "";
			$this->taller->TooltipValue = "";

			// tipo_mantenimiento
			$this->tipo_mantenimiento->LinkCustomAttributes = "";
			$this->tipo_mantenimiento->HrefValue = "";
			$this->tipo_mantenimiento->TooltipValue = "";

			// nro_hoja_mantenimiento
			$this->nro_hoja_mantenimiento->LinkCustomAttributes = "";
			$this->nro_hoja_mantenimiento->HrefValue = "";
			$this->nro_hoja_mantenimiento->TooltipValue = "";

			// total_gasto
			$this->total_gasto->LinkCustomAttributes = "";
			$this->total_gasto->HrefValue = "";
			$this->total_gasto->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_v_gastos_hoja_mantenimiento\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_v_gastos_hoja_mantenimiento',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fv_gastos_hoja_mantenimientolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

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
		$Doc->Export();
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
if (!isset($v_gastos_hoja_mantenimiento_list)) $v_gastos_hoja_mantenimiento_list = new cv_gastos_hoja_mantenimiento_list();

// Page init
$v_gastos_hoja_mantenimiento_list->Page_Init();

// Page main
$v_gastos_hoja_mantenimiento_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$v_gastos_hoja_mantenimiento_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<?php if ($v_gastos_hoja_mantenimiento->Export == "") { ?>
<script type="text/javascript">

// Page object
var v_gastos_hoja_mantenimiento_list = new ew_Page("v_gastos_hoja_mantenimiento_list");
v_gastos_hoja_mantenimiento_list.PageID = "list"; // Page ID
var EW_PAGE_ID = v_gastos_hoja_mantenimiento_list.PageID; // For backward compatibility

// Form object
var fv_gastos_hoja_mantenimientolist = new ew_Form("fv_gastos_hoja_mantenimientolist");
fv_gastos_hoja_mantenimientolist.FormKeyCountName = '<?php echo $v_gastos_hoja_mantenimiento_list->FormKeyCountName ?>';

// Form_CustomValidate event
fv_gastos_hoja_mantenimientolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_gastos_hoja_mantenimientolist.ValidateRequired = true;
<?php } else { ?>
fv_gastos_hoja_mantenimientolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fv_gastos_hoja_mantenimientolistsrch = new ew_Form("fv_gastos_hoja_mantenimientolistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($v_gastos_hoja_mantenimiento->Export == "") { ?>
<div class="ewToolbar">
<?php if ($v_gastos_hoja_mantenimiento->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($v_gastos_hoja_mantenimiento_list->TotalRecs > 0 && $v_gastos_hoja_mantenimiento_list->ExportOptions->Visible()) { ?>
<?php $v_gastos_hoja_mantenimiento_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($v_gastos_hoja_mantenimiento_list->SearchOptions->Visible()) { ?>
<?php $v_gastos_hoja_mantenimiento_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($v_gastos_hoja_mantenimiento->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$v_gastos_hoja_mantenimiento_list->TotalRecs = $v_gastos_hoja_mantenimiento->SelectRecordCount();
	} else {
		if ($v_gastos_hoja_mantenimiento_list->Recordset = $v_gastos_hoja_mantenimiento_list->LoadRecordset())
			$v_gastos_hoja_mantenimiento_list->TotalRecs = $v_gastos_hoja_mantenimiento_list->Recordset->RecordCount();
	}
	$v_gastos_hoja_mantenimiento_list->StartRec = 1;
	if ($v_gastos_hoja_mantenimiento_list->DisplayRecs <= 0 || ($v_gastos_hoja_mantenimiento->Export <> "" && $v_gastos_hoja_mantenimiento->ExportAll)) // Display all records
		$v_gastos_hoja_mantenimiento_list->DisplayRecs = $v_gastos_hoja_mantenimiento_list->TotalRecs;
	if (!($v_gastos_hoja_mantenimiento->Export <> "" && $v_gastos_hoja_mantenimiento->ExportAll))
		$v_gastos_hoja_mantenimiento_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$v_gastos_hoja_mantenimiento_list->Recordset = $v_gastos_hoja_mantenimiento_list->LoadRecordset($v_gastos_hoja_mantenimiento_list->StartRec-1, $v_gastos_hoja_mantenimiento_list->DisplayRecs);

	// Set no record found message
	if ($v_gastos_hoja_mantenimiento->CurrentAction == "" && $v_gastos_hoja_mantenimiento_list->TotalRecs == 0) {
		if ($v_gastos_hoja_mantenimiento_list->SearchWhere == "0=101")
			$v_gastos_hoja_mantenimiento_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$v_gastos_hoja_mantenimiento_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$v_gastos_hoja_mantenimiento_list->RenderOtherOptions();
?>
<?php if ($v_gastos_hoja_mantenimiento->Export == "" && $v_gastos_hoja_mantenimiento->CurrentAction == "") { ?>
<form name="fv_gastos_hoja_mantenimientolistsrch" id="fv_gastos_hoja_mantenimientolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($v_gastos_hoja_mantenimiento_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fv_gastos_hoja_mantenimientolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="v_gastos_hoja_mantenimiento">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($v_gastos_hoja_mantenimiento_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($v_gastos_hoja_mantenimiento_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $v_gastos_hoja_mantenimiento_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($v_gastos_hoja_mantenimiento_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($v_gastos_hoja_mantenimiento_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($v_gastos_hoja_mantenimiento_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($v_gastos_hoja_mantenimiento_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $v_gastos_hoja_mantenimiento_list->ShowPageHeader(); ?>
<?php
$v_gastos_hoja_mantenimiento_list->ShowMessage();
?>
<?php if ($v_gastos_hoja_mantenimiento_list->TotalRecs > 0 || $v_gastos_hoja_mantenimiento->CurrentAction <> "") { ?>
<div class="ewGrid">
<form name="fv_gastos_hoja_mantenimientolist" id="fv_gastos_hoja_mantenimientolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($v_gastos_hoja_mantenimiento_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $v_gastos_hoja_mantenimiento_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="v_gastos_hoja_mantenimiento">
<div id="gmp_v_gastos_hoja_mantenimiento" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($v_gastos_hoja_mantenimiento_list->TotalRecs > 0) { ?>
<table id="tbl_v_gastos_hoja_mantenimientolist" class="table ewTable">
<?php echo $v_gastos_hoja_mantenimiento->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$v_gastos_hoja_mantenimiento_list->RenderListOptions();

// Render list options (header, left)
$v_gastos_hoja_mantenimiento_list->ListOptions->Render("header", "left");
?>
<?php if ($v_gastos_hoja_mantenimiento->fecha_ini->Visible) { // fecha_ini ?>
	<?php if ($v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->fecha_ini) == "") { ?>
		<th data-name="fecha_ini"><div id="elh_v_gastos_hoja_mantenimiento_fecha_ini" class="v_gastos_hoja_mantenimiento_fecha_ini"><div class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->fecha_ini->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_ini"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->fecha_ini) ?>',1);"><div id="elh_v_gastos_hoja_mantenimiento_fecha_ini" class="v_gastos_hoja_mantenimiento_fecha_ini">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->fecha_ini->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_hoja_mantenimiento->fecha_ini->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_hoja_mantenimiento->fecha_ini->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_hoja_mantenimiento->fecha_fin->Visible) { // fecha_fin ?>
	<?php if ($v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->fecha_fin) == "") { ?>
		<th data-name="fecha_fin"><div id="elh_v_gastos_hoja_mantenimiento_fecha_fin" class="v_gastos_hoja_mantenimiento_fecha_fin"><div class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->fecha_fin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_fin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->fecha_fin) ?>',1);"><div id="elh_v_gastos_hoja_mantenimiento_fecha_fin" class="v_gastos_hoja_mantenimiento_fecha_fin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->fecha_fin->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_hoja_mantenimiento->fecha_fin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_hoja_mantenimiento->fecha_fin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_hoja_mantenimiento->Patente->Visible) { // Patente ?>
	<?php if ($v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->Patente) == "") { ?>
		<th data-name="Patente"><div id="elh_v_gastos_hoja_mantenimiento_Patente" class="v_gastos_hoja_mantenimiento_Patente"><div class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->Patente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Patente"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->Patente) ?>',1);"><div id="elh_v_gastos_hoja_mantenimiento_Patente" class="v_gastos_hoja_mantenimiento_Patente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->Patente->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_hoja_mantenimiento->Patente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_hoja_mantenimiento->Patente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_hoja_mantenimiento->taller->Visible) { // taller ?>
	<?php if ($v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->taller) == "") { ?>
		<th data-name="taller"><div id="elh_v_gastos_hoja_mantenimiento_taller" class="v_gastos_hoja_mantenimiento_taller"><div class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->taller->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="taller"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->taller) ?>',1);"><div id="elh_v_gastos_hoja_mantenimiento_taller" class="v_gastos_hoja_mantenimiento_taller">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->taller->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_hoja_mantenimiento->taller->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_hoja_mantenimiento->taller->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_hoja_mantenimiento->tipo_mantenimiento->Visible) { // tipo_mantenimiento ?>
	<?php if ($v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->tipo_mantenimiento) == "") { ?>
		<th data-name="tipo_mantenimiento"><div id="elh_v_gastos_hoja_mantenimiento_tipo_mantenimiento" class="v_gastos_hoja_mantenimiento_tipo_mantenimiento"><div class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->tipo_mantenimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_mantenimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->tipo_mantenimiento) ?>',1);"><div id="elh_v_gastos_hoja_mantenimiento_tipo_mantenimiento" class="v_gastos_hoja_mantenimiento_tipo_mantenimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->tipo_mantenimiento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_hoja_mantenimiento->tipo_mantenimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_hoja_mantenimiento->tipo_mantenimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento->Visible) { // nro_hoja_mantenimiento ?>
	<?php if ($v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento) == "") { ?>
		<th data-name="nro_hoja_mantenimiento"><div id="elh_v_gastos_hoja_mantenimiento_nro_hoja_mantenimiento" class="v_gastos_hoja_mantenimiento_nro_hoja_mantenimiento"><div class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nro_hoja_mantenimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento) ?>',1);"><div id="elh_v_gastos_hoja_mantenimiento_nro_hoja_mantenimiento" class="v_gastos_hoja_mantenimiento_nro_hoja_mantenimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_hoja_mantenimiento->total_gasto->Visible) { // total_gasto ?>
	<?php if ($v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->total_gasto) == "") { ?>
		<th data-name="total_gasto"><div id="elh_v_gastos_hoja_mantenimiento_total_gasto" class="v_gastos_hoja_mantenimiento_total_gasto"><div class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->total_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="total_gasto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gastos_hoja_mantenimiento->SortUrl($v_gastos_hoja_mantenimiento->total_gasto) ?>',1);"><div id="elh_v_gastos_hoja_mantenimiento_total_gasto" class="v_gastos_hoja_mantenimiento_total_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_hoja_mantenimiento->total_gasto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_hoja_mantenimiento->total_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_hoja_mantenimiento->total_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$v_gastos_hoja_mantenimiento_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($v_gastos_hoja_mantenimiento->ExportAll && $v_gastos_hoja_mantenimiento->Export <> "") {
	$v_gastos_hoja_mantenimiento_list->StopRec = $v_gastos_hoja_mantenimiento_list->TotalRecs;
} else {

	// Set the last record to display
	if ($v_gastos_hoja_mantenimiento_list->TotalRecs > $v_gastos_hoja_mantenimiento_list->StartRec + $v_gastos_hoja_mantenimiento_list->DisplayRecs - 1)
		$v_gastos_hoja_mantenimiento_list->StopRec = $v_gastos_hoja_mantenimiento_list->StartRec + $v_gastos_hoja_mantenimiento_list->DisplayRecs - 1;
	else
		$v_gastos_hoja_mantenimiento_list->StopRec = $v_gastos_hoja_mantenimiento_list->TotalRecs;
}
$v_gastos_hoja_mantenimiento_list->RecCnt = $v_gastos_hoja_mantenimiento_list->StartRec - 1;
if ($v_gastos_hoja_mantenimiento_list->Recordset && !$v_gastos_hoja_mantenimiento_list->Recordset->EOF) {
	$v_gastos_hoja_mantenimiento_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $v_gastos_hoja_mantenimiento_list->StartRec > 1)
		$v_gastos_hoja_mantenimiento_list->Recordset->Move($v_gastos_hoja_mantenimiento_list->StartRec - 1);
} elseif (!$v_gastos_hoja_mantenimiento->AllowAddDeleteRow && $v_gastos_hoja_mantenimiento_list->StopRec == 0) {
	$v_gastos_hoja_mantenimiento_list->StopRec = $v_gastos_hoja_mantenimiento->GridAddRowCount;
}

// Initialize aggregate
$v_gastos_hoja_mantenimiento->RowType = EW_ROWTYPE_AGGREGATEINIT;
$v_gastos_hoja_mantenimiento->ResetAttrs();
$v_gastos_hoja_mantenimiento_list->RenderRow();
while ($v_gastos_hoja_mantenimiento_list->RecCnt < $v_gastos_hoja_mantenimiento_list->StopRec) {
	$v_gastos_hoja_mantenimiento_list->RecCnt++;
	if (intval($v_gastos_hoja_mantenimiento_list->RecCnt) >= intval($v_gastos_hoja_mantenimiento_list->StartRec)) {
		$v_gastos_hoja_mantenimiento_list->RowCnt++;

		// Set up key count
		$v_gastos_hoja_mantenimiento_list->KeyCount = $v_gastos_hoja_mantenimiento_list->RowIndex;

		// Init row class and style
		$v_gastos_hoja_mantenimiento->ResetAttrs();
		$v_gastos_hoja_mantenimiento->CssClass = "";
		if ($v_gastos_hoja_mantenimiento->CurrentAction == "gridadd") {
		} else {
			$v_gastos_hoja_mantenimiento_list->LoadRowValues($v_gastos_hoja_mantenimiento_list->Recordset); // Load row values
		}
		$v_gastos_hoja_mantenimiento->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$v_gastos_hoja_mantenimiento->RowAttrs = array_merge($v_gastos_hoja_mantenimiento->RowAttrs, array('data-rowindex'=>$v_gastos_hoja_mantenimiento_list->RowCnt, 'id'=>'r' . $v_gastos_hoja_mantenimiento_list->RowCnt . '_v_gastos_hoja_mantenimiento', 'data-rowtype'=>$v_gastos_hoja_mantenimiento->RowType));

		// Render row
		$v_gastos_hoja_mantenimiento_list->RenderRow();

		// Render list options
		$v_gastos_hoja_mantenimiento_list->RenderListOptions();
?>
	<tr<?php echo $v_gastos_hoja_mantenimiento->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_gastos_hoja_mantenimiento_list->ListOptions->Render("body", "left", $v_gastos_hoja_mantenimiento_list->RowCnt);
?>
	<?php if ($v_gastos_hoja_mantenimiento->fecha_ini->Visible) { // fecha_ini ?>
		<td data-name="fecha_ini"<?php echo $v_gastos_hoja_mantenimiento->fecha_ini->CellAttributes() ?>>
<span<?php echo $v_gastos_hoja_mantenimiento->fecha_ini->ViewAttributes() ?>>
<?php echo $v_gastos_hoja_mantenimiento->fecha_ini->ListViewValue() ?></span>
<a id="<?php echo $v_gastos_hoja_mantenimiento_list->PageObjName . "_row_" . $v_gastos_hoja_mantenimiento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_gastos_hoja_mantenimiento->fecha_fin->Visible) { // fecha_fin ?>
		<td data-name="fecha_fin"<?php echo $v_gastos_hoja_mantenimiento->fecha_fin->CellAttributes() ?>>
<span<?php echo $v_gastos_hoja_mantenimiento->fecha_fin->ViewAttributes() ?>>
<?php echo $v_gastos_hoja_mantenimiento->fecha_fin->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_gastos_hoja_mantenimiento->Patente->Visible) { // Patente ?>
		<td data-name="Patente"<?php echo $v_gastos_hoja_mantenimiento->Patente->CellAttributes() ?>>
<span<?php echo $v_gastos_hoja_mantenimiento->Patente->ViewAttributes() ?>>
<?php echo $v_gastos_hoja_mantenimiento->Patente->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_gastos_hoja_mantenimiento->taller->Visible) { // taller ?>
		<td data-name="taller"<?php echo $v_gastos_hoja_mantenimiento->taller->CellAttributes() ?>>
<span<?php echo $v_gastos_hoja_mantenimiento->taller->ViewAttributes() ?>>
<?php echo $v_gastos_hoja_mantenimiento->taller->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_gastos_hoja_mantenimiento->tipo_mantenimiento->Visible) { // tipo_mantenimiento ?>
		<td data-name="tipo_mantenimiento"<?php echo $v_gastos_hoja_mantenimiento->tipo_mantenimiento->CellAttributes() ?>>
<span<?php echo $v_gastos_hoja_mantenimiento->tipo_mantenimiento->ViewAttributes() ?>>
<?php echo $v_gastos_hoja_mantenimiento->tipo_mantenimiento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento->Visible) { // nro_hoja_mantenimiento ?>
		<td data-name="nro_hoja_mantenimiento"<?php echo $v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento->CellAttributes() ?>>
<span<?php echo $v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento->ViewAttributes() ?>>
<?php echo $v_gastos_hoja_mantenimiento->nro_hoja_mantenimiento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_gastos_hoja_mantenimiento->total_gasto->Visible) { // total_gasto ?>
		<td data-name="total_gasto"<?php echo $v_gastos_hoja_mantenimiento->total_gasto->CellAttributes() ?>>
<span<?php echo $v_gastos_hoja_mantenimiento->total_gasto->ViewAttributes() ?>>
<?php echo $v_gastos_hoja_mantenimiento->total_gasto->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_gastos_hoja_mantenimiento_list->ListOptions->Render("body", "right", $v_gastos_hoja_mantenimiento_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($v_gastos_hoja_mantenimiento->CurrentAction <> "gridadd")
		$v_gastos_hoja_mantenimiento_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($v_gastos_hoja_mantenimiento->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($v_gastos_hoja_mantenimiento_list->Recordset)
	$v_gastos_hoja_mantenimiento_list->Recordset->Close();
?>
<?php if ($v_gastos_hoja_mantenimiento->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($v_gastos_hoja_mantenimiento->CurrentAction <> "gridadd" && $v_gastos_hoja_mantenimiento->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($v_gastos_hoja_mantenimiento_list->Pager)) $v_gastos_hoja_mantenimiento_list->Pager = new cNumericPager($v_gastos_hoja_mantenimiento_list->StartRec, $v_gastos_hoja_mantenimiento_list->DisplayRecs, $v_gastos_hoja_mantenimiento_list->TotalRecs, $v_gastos_hoja_mantenimiento_list->RecRange) ?>
<?php if ($v_gastos_hoja_mantenimiento_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($v_gastos_hoja_mantenimiento_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $v_gastos_hoja_mantenimiento_list->PageUrl() ?>start=<?php echo $v_gastos_hoja_mantenimiento_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($v_gastos_hoja_mantenimiento_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $v_gastos_hoja_mantenimiento_list->PageUrl() ?>start=<?php echo $v_gastos_hoja_mantenimiento_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($v_gastos_hoja_mantenimiento_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $v_gastos_hoja_mantenimiento_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($v_gastos_hoja_mantenimiento_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $v_gastos_hoja_mantenimiento_list->PageUrl() ?>start=<?php echo $v_gastos_hoja_mantenimiento_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($v_gastos_hoja_mantenimiento_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $v_gastos_hoja_mantenimiento_list->PageUrl() ?>start=<?php echo $v_gastos_hoja_mantenimiento_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $v_gastos_hoja_mantenimiento_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $v_gastos_hoja_mantenimiento_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $v_gastos_hoja_mantenimiento_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_gastos_hoja_mantenimiento_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($v_gastos_hoja_mantenimiento_list->TotalRecs == 0 && $v_gastos_hoja_mantenimiento->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_gastos_hoja_mantenimiento_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($v_gastos_hoja_mantenimiento->Export == "") { ?>
<script type="text/javascript">
fv_gastos_hoja_mantenimientolistsrch.Init();
fv_gastos_hoja_mantenimientolist.Init();
</script>
<?php } ?>
<?php
$v_gastos_hoja_mantenimiento_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($v_gastos_hoja_mantenimiento->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$v_gastos_hoja_mantenimiento_list->Page_Terminate();
?>
