<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "v_gasto_manteniemintoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "hoja_mantenimientosinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$v_gasto_mantenieminto_list = NULL; // Initialize page object first

class cv_gasto_mantenieminto_list extends cv_gasto_mantenieminto {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'v_gasto_mantenieminto';

	// Page object name
	var $PageObjName = 'v_gasto_mantenieminto_list';

	// Grid form hidden field names
	var $FormName = 'fv_gasto_manteniemintolist';
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

		// Table object (v_gasto_mantenieminto)
		if (!isset($GLOBALS["v_gasto_mantenieminto"]) || get_class($GLOBALS["v_gasto_mantenieminto"]) == "cv_gasto_mantenieminto") {
			$GLOBALS["v_gasto_mantenieminto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["v_gasto_mantenieminto"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "v_gasto_manteniemintoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "v_gasto_manteniemintodelete.php";
		$this->MultiUpdateUrl = "v_gasto_manteniemintoupdate.php";

		// Table object (hoja_mantenimientos)
		if (!isset($GLOBALS['hoja_mantenimientos'])) $GLOBALS['hoja_mantenimientos'] = new choja_mantenimientos();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'v_gasto_mantenieminto', TRUE);

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
		$this->codigo->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $v_gasto_mantenieminto;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($v_gasto_mantenieminto);
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

			// Set up master detail parameters
			$this->SetUpMasterParms();

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "hoja_mantenimientos") {
			global $hoja_mantenimientos;
			$rsmaster = $hoja_mantenimientos->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("hoja_mantenimientoslist.php"); // Return to master page
			} else {
				$hoja_mantenimientos->LoadListRowValues($rsmaster);
				$hoja_mantenimientos->RowType = EW_ROWTYPE_MASTER; // Master row
				$hoja_mantenimientos->RenderListRow();
				$rsmaster->Close();
			}
		}

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
		if (count($arrKeyFlds) >= 1) {
			$this->codigo->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->codigo->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->detalles, $arKeywords, $type);
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
			$this->UpdateSort($this->codigo); // codigo
			$this->UpdateSort($this->fecha); // fecha
			$this->UpdateSort($this->detalles); // detalles
			$this->UpdateSort($this->iva); // iva
			$this->UpdateSort($this->Importe); // Importe
			$this->UpdateSort($this->id_tipo_gasto); // id_tipo_gasto
			$this->UpdateSort($this->id_hoja_mantenimiento); // id_hoja_mantenimiento
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->id_hoja_mantenimiento->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->codigo->setSort("");
				$this->fecha->setSort("");
				$this->detalles->setSort("");
				$this->iva->setSort("");
				$this->Importe->setSort("");
				$this->id_tipo_gasto->setSort("");
				$this->id_hoja_mantenimiento->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->codigo->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fv_gasto_manteniemintolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fv_gasto_manteniemintolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->codigo->setDbValue($rs->fields('codigo'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->detalles->setDbValue($rs->fields('detalles'));
		$this->iva->setDbValue($rs->fields('iva'));
		$this->Importe->setDbValue($rs->fields('Importe'));
		$this->id_tipo_gasto->setDbValue($rs->fields('id_tipo_gasto'));
		$this->id_hoja_mantenimiento->setDbValue($rs->fields('id_hoja_mantenimiento'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->fecha->DbValue = $row['fecha'];
		$this->detalles->DbValue = $row['detalles'];
		$this->iva->DbValue = $row['iva'];
		$this->Importe->DbValue = $row['Importe'];
		$this->id_tipo_gasto->DbValue = $row['id_tipo_gasto'];
		$this->id_hoja_mantenimiento->DbValue = $row['id_hoja_mantenimiento'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("codigo")) <> "")
			$this->codigo->CurrentValue = $this->getKey("codigo"); // codigo
		else
			$bValidKey = FALSE;

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
		if ($this->iva->FormValue == $this->iva->CurrentValue && is_numeric(ew_StrToFloat($this->iva->CurrentValue)))
			$this->iva->CurrentValue = ew_StrToFloat($this->iva->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Importe->FormValue == $this->Importe->CurrentValue && is_numeric(ew_StrToFloat($this->Importe->CurrentValue)))
			$this->Importe->CurrentValue = ew_StrToFloat($this->Importe->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// fecha
		// detalles
		// iva
		// Importe
		// id_tipo_gasto
		// id_hoja_mantenimiento

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// detalles
			$this->detalles->ViewValue = $this->detalles->CurrentValue;
			$this->detalles->ViewCustomAttributes = "";

			// iva
			$this->iva->ViewValue = $this->iva->CurrentValue;
			$this->iva->ViewCustomAttributes = "";

			// Importe
			$this->Importe->ViewValue = $this->Importe->CurrentValue;
			$this->Importe->ViewCustomAttributes = "";

			// id_tipo_gasto
			$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->CurrentValue;
			$this->id_tipo_gasto->ViewCustomAttributes = "";

			// id_hoja_mantenimiento
			$this->id_hoja_mantenimiento->ViewValue = $this->id_hoja_mantenimiento->CurrentValue;
			$this->id_hoja_mantenimiento->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// detalles
			$this->detalles->LinkCustomAttributes = "";
			$this->detalles->HrefValue = "";
			$this->detalles->TooltipValue = "";

			// iva
			$this->iva->LinkCustomAttributes = "";
			$this->iva->HrefValue = "";
			$this->iva->TooltipValue = "";

			// Importe
			$this->Importe->LinkCustomAttributes = "";
			$this->Importe->HrefValue = "";
			$this->Importe->TooltipValue = "";

			// id_tipo_gasto
			$this->id_tipo_gasto->LinkCustomAttributes = "";
			$this->id_tipo_gasto->HrefValue = "";
			$this->id_tipo_gasto->TooltipValue = "";

			// id_hoja_mantenimiento
			$this->id_hoja_mantenimiento->LinkCustomAttributes = "";
			$this->id_hoja_mantenimiento->HrefValue = "";
			$this->id_hoja_mantenimiento->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_v_gasto_mantenieminto\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_v_gasto_mantenieminto',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fv_gasto_manteniemintolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "hoja_mantenimientos") {
			global $hoja_mantenimientos;
			if (!isset($hoja_mantenimientos)) $hoja_mantenimientos = new choja_mantenimientos;
			$rsmaster = $hoja_mantenimientos->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$hoja_mantenimientos->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "hoja_mantenimientos") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_codigo"] <> "") {
					$GLOBALS["hoja_mantenimientos"]->codigo->setQueryStringValue($_GET["fk_codigo"]);
					$this->id_hoja_mantenimiento->setQueryStringValue($GLOBALS["hoja_mantenimientos"]->codigo->QueryStringValue);
					$this->id_hoja_mantenimiento->setSessionValue($this->id_hoja_mantenimiento->QueryStringValue);
					if (!is_numeric($GLOBALS["hoja_mantenimientos"]->codigo->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "hoja_mantenimientos") {
				if ($this->id_hoja_mantenimiento->QueryStringValue == "") $this->id_hoja_mantenimiento->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
if (!isset($v_gasto_mantenieminto_list)) $v_gasto_mantenieminto_list = new cv_gasto_mantenieminto_list();

// Page init
$v_gasto_mantenieminto_list->Page_Init();

// Page main
$v_gasto_mantenieminto_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$v_gasto_mantenieminto_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<?php if ($v_gasto_mantenieminto->Export == "") { ?>
<script type="text/javascript">

// Page object
var v_gasto_mantenieminto_list = new ew_Page("v_gasto_mantenieminto_list");
v_gasto_mantenieminto_list.PageID = "list"; // Page ID
var EW_PAGE_ID = v_gasto_mantenieminto_list.PageID; // For backward compatibility

// Form object
var fv_gasto_manteniemintolist = new ew_Form("fv_gasto_manteniemintolist");
fv_gasto_manteniemintolist.FormKeyCountName = '<?php echo $v_gasto_mantenieminto_list->FormKeyCountName ?>';

// Form_CustomValidate event
fv_gasto_manteniemintolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_gasto_manteniemintolist.ValidateRequired = true;
<?php } else { ?>
fv_gasto_manteniemintolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fv_gasto_manteniemintolistsrch = new ew_Form("fv_gasto_manteniemintolistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($v_gasto_mantenieminto->Export == "") { ?>
<div class="ewToolbar">
<?php if ($v_gasto_mantenieminto->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($v_gasto_mantenieminto_list->TotalRecs > 0 && $v_gasto_mantenieminto->getCurrentMasterTable() == "" && $v_gasto_mantenieminto_list->ExportOptions->Visible()) { ?>
<?php $v_gasto_mantenieminto_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($v_gasto_mantenieminto_list->SearchOptions->Visible()) { ?>
<?php $v_gasto_mantenieminto_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($v_gasto_mantenieminto->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($v_gasto_mantenieminto->Export == "") || (EW_EXPORT_MASTER_RECORD && $v_gasto_mantenieminto->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "hoja_mantenimientoslist.php";
if ($v_gasto_mantenieminto_list->DbMasterFilter <> "" && $v_gasto_mantenieminto->getCurrentMasterTable() == "hoja_mantenimientos") {
	if ($v_gasto_mantenieminto_list->MasterRecordExists) {
		if ($v_gasto_mantenieminto->getCurrentMasterTable() == $v_gasto_mantenieminto->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($v_gasto_mantenieminto_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $v_gasto_mantenieminto_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "hoja_mantenimientosmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$v_gasto_mantenieminto_list->TotalRecs = $v_gasto_mantenieminto->SelectRecordCount();
	} else {
		if ($v_gasto_mantenieminto_list->Recordset = $v_gasto_mantenieminto_list->LoadRecordset())
			$v_gasto_mantenieminto_list->TotalRecs = $v_gasto_mantenieminto_list->Recordset->RecordCount();
	}
	$v_gasto_mantenieminto_list->StartRec = 1;
	if ($v_gasto_mantenieminto_list->DisplayRecs <= 0 || ($v_gasto_mantenieminto->Export <> "" && $v_gasto_mantenieminto->ExportAll)) // Display all records
		$v_gasto_mantenieminto_list->DisplayRecs = $v_gasto_mantenieminto_list->TotalRecs;
	if (!($v_gasto_mantenieminto->Export <> "" && $v_gasto_mantenieminto->ExportAll))
		$v_gasto_mantenieminto_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$v_gasto_mantenieminto_list->Recordset = $v_gasto_mantenieminto_list->LoadRecordset($v_gasto_mantenieminto_list->StartRec-1, $v_gasto_mantenieminto_list->DisplayRecs);

	// Set no record found message
	if ($v_gasto_mantenieminto->CurrentAction == "" && $v_gasto_mantenieminto_list->TotalRecs == 0) {
		if ($v_gasto_mantenieminto_list->SearchWhere == "0=101")
			$v_gasto_mantenieminto_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$v_gasto_mantenieminto_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$v_gasto_mantenieminto_list->RenderOtherOptions();
?>
<?php if ($v_gasto_mantenieminto->Export == "" && $v_gasto_mantenieminto->CurrentAction == "") { ?>
<form name="fv_gasto_manteniemintolistsrch" id="fv_gasto_manteniemintolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($v_gasto_mantenieminto_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fv_gasto_manteniemintolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="v_gasto_mantenieminto">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $v_gasto_mantenieminto_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($v_gasto_mantenieminto_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($v_gasto_mantenieminto_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($v_gasto_mantenieminto_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($v_gasto_mantenieminto_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $v_gasto_mantenieminto_list->ShowPageHeader(); ?>
<?php
$v_gasto_mantenieminto_list->ShowMessage();
?>
<?php if ($v_gasto_mantenieminto_list->TotalRecs > 0 || $v_gasto_mantenieminto->CurrentAction <> "") { ?>
<div class="ewGrid">
<form name="fv_gasto_manteniemintolist" id="fv_gasto_manteniemintolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($v_gasto_mantenieminto_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $v_gasto_mantenieminto_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="v_gasto_mantenieminto">
<div id="gmp_v_gasto_mantenieminto" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($v_gasto_mantenieminto_list->TotalRecs > 0) { ?>
<table id="tbl_v_gasto_manteniemintolist" class="table ewTable">
<?php echo $v_gasto_mantenieminto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$v_gasto_mantenieminto_list->RenderListOptions();

// Render list options (header, left)
$v_gasto_mantenieminto_list->ListOptions->Render("header", "left");
?>
<?php if ($v_gasto_mantenieminto->codigo->Visible) { // codigo ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->codigo) == "") { ?>
		<th data-name="codigo"><div id="elh_v_gasto_mantenieminto_codigo" class="v_gasto_mantenieminto_codigo"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->codigo) ?>',1);"><div id="elh_v_gasto_mantenieminto_codigo" class="v_gasto_mantenieminto_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->fecha->Visible) { // fecha ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_v_gasto_mantenieminto_fecha" class="v_gasto_mantenieminto_fecha"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->fecha) ?>',1);"><div id="elh_v_gasto_mantenieminto_fecha" class="v_gasto_mantenieminto_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->detalles->Visible) { // detalles ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->detalles) == "") { ?>
		<th data-name="detalles"><div id="elh_v_gasto_mantenieminto_detalles" class="v_gasto_mantenieminto_detalles"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->detalles->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="detalles"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->detalles) ?>',1);"><div id="elh_v_gasto_mantenieminto_detalles" class="v_gasto_mantenieminto_detalles">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->detalles->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->detalles->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->detalles->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->iva->Visible) { // iva ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->iva) == "") { ?>
		<th data-name="iva"><div id="elh_v_gasto_mantenieminto_iva" class="v_gasto_mantenieminto_iva"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->iva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->iva) ?>',1);"><div id="elh_v_gasto_mantenieminto_iva" class="v_gasto_mantenieminto_iva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->iva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->iva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->iva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->Importe->Visible) { // Importe ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->Importe) == "") { ?>
		<th data-name="Importe"><div id="elh_v_gasto_mantenieminto_Importe" class="v_gasto_mantenieminto_Importe"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->Importe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Importe"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->Importe) ?>',1);"><div id="elh_v_gasto_mantenieminto_Importe" class="v_gasto_mantenieminto_Importe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->Importe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->Importe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->Importe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->id_tipo_gasto) == "") { ?>
		<th data-name="id_tipo_gasto"><div id="elh_v_gasto_mantenieminto_id_tipo_gasto" class="v_gasto_mantenieminto_id_tipo_gasto"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->id_tipo_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipo_gasto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->id_tipo_gasto) ?>',1);"><div id="elh_v_gasto_mantenieminto_id_tipo_gasto" class="v_gasto_mantenieminto_id_tipo_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->id_tipo_gasto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->id_tipo_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->id_tipo_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->id_hoja_mantenimiento->Visible) { // id_hoja_mantenimiento ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->id_hoja_mantenimiento) == "") { ?>
		<th data-name="id_hoja_mantenimiento"><div id="elh_v_gasto_mantenieminto_id_hoja_mantenimiento" class="v_gasto_mantenieminto_id_hoja_mantenimiento"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_hoja_mantenimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->id_hoja_mantenimiento) ?>',1);"><div id="elh_v_gasto_mantenieminto_id_hoja_mantenimiento" class="v_gasto_mantenieminto_id_hoja_mantenimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->id_hoja_mantenimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->id_hoja_mantenimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$v_gasto_mantenieminto_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($v_gasto_mantenieminto->ExportAll && $v_gasto_mantenieminto->Export <> "") {
	$v_gasto_mantenieminto_list->StopRec = $v_gasto_mantenieminto_list->TotalRecs;
} else {

	// Set the last record to display
	if ($v_gasto_mantenieminto_list->TotalRecs > $v_gasto_mantenieminto_list->StartRec + $v_gasto_mantenieminto_list->DisplayRecs - 1)
		$v_gasto_mantenieminto_list->StopRec = $v_gasto_mantenieminto_list->StartRec + $v_gasto_mantenieminto_list->DisplayRecs - 1;
	else
		$v_gasto_mantenieminto_list->StopRec = $v_gasto_mantenieminto_list->TotalRecs;
}
$v_gasto_mantenieminto_list->RecCnt = $v_gasto_mantenieminto_list->StartRec - 1;
if ($v_gasto_mantenieminto_list->Recordset && !$v_gasto_mantenieminto_list->Recordset->EOF) {
	$v_gasto_mantenieminto_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $v_gasto_mantenieminto_list->StartRec > 1)
		$v_gasto_mantenieminto_list->Recordset->Move($v_gasto_mantenieminto_list->StartRec - 1);
} elseif (!$v_gasto_mantenieminto->AllowAddDeleteRow && $v_gasto_mantenieminto_list->StopRec == 0) {
	$v_gasto_mantenieminto_list->StopRec = $v_gasto_mantenieminto->GridAddRowCount;
}

// Initialize aggregate
$v_gasto_mantenieminto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$v_gasto_mantenieminto->ResetAttrs();
$v_gasto_mantenieminto_list->RenderRow();
while ($v_gasto_mantenieminto_list->RecCnt < $v_gasto_mantenieminto_list->StopRec) {
	$v_gasto_mantenieminto_list->RecCnt++;
	if (intval($v_gasto_mantenieminto_list->RecCnt) >= intval($v_gasto_mantenieminto_list->StartRec)) {
		$v_gasto_mantenieminto_list->RowCnt++;

		// Set up key count
		$v_gasto_mantenieminto_list->KeyCount = $v_gasto_mantenieminto_list->RowIndex;

		// Init row class and style
		$v_gasto_mantenieminto->ResetAttrs();
		$v_gasto_mantenieminto->CssClass = "";
		if ($v_gasto_mantenieminto->CurrentAction == "gridadd") {
		} else {
			$v_gasto_mantenieminto_list->LoadRowValues($v_gasto_mantenieminto_list->Recordset); // Load row values
		}
		$v_gasto_mantenieminto->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$v_gasto_mantenieminto->RowAttrs = array_merge($v_gasto_mantenieminto->RowAttrs, array('data-rowindex'=>$v_gasto_mantenieminto_list->RowCnt, 'id'=>'r' . $v_gasto_mantenieminto_list->RowCnt . '_v_gasto_mantenieminto', 'data-rowtype'=>$v_gasto_mantenieminto->RowType));

		// Render row
		$v_gasto_mantenieminto_list->RenderRow();

		// Render list options
		$v_gasto_mantenieminto_list->RenderListOptions();
?>
	<tr<?php echo $v_gasto_mantenieminto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_gasto_mantenieminto_list->ListOptions->Render("body", "left", $v_gasto_mantenieminto_list->RowCnt);
?>
	<?php if ($v_gasto_mantenieminto->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $v_gasto_mantenieminto->codigo->CellAttributes() ?>>
<span<?php echo $v_gasto_mantenieminto->codigo->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->codigo->ListViewValue() ?></span>
<a id="<?php echo $v_gasto_mantenieminto_list->PageObjName . "_row_" . $v_gasto_mantenieminto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $v_gasto_mantenieminto->fecha->CellAttributes() ?>>
<span<?php echo $v_gasto_mantenieminto->fecha->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->fecha->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->detalles->Visible) { // detalles ?>
		<td data-name="detalles"<?php echo $v_gasto_mantenieminto->detalles->CellAttributes() ?>>
<span<?php echo $v_gasto_mantenieminto->detalles->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->detalles->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->iva->Visible) { // iva ?>
		<td data-name="iva"<?php echo $v_gasto_mantenieminto->iva->CellAttributes() ?>>
<span<?php echo $v_gasto_mantenieminto->iva->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->iva->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->Importe->Visible) { // Importe ?>
		<td data-name="Importe"<?php echo $v_gasto_mantenieminto->Importe->CellAttributes() ?>>
<span<?php echo $v_gasto_mantenieminto->Importe->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->Importe->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td data-name="id_tipo_gasto"<?php echo $v_gasto_mantenieminto->id_tipo_gasto->CellAttributes() ?>>
<span<?php echo $v_gasto_mantenieminto->id_tipo_gasto->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->id_tipo_gasto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->id_hoja_mantenimiento->Visible) { // id_hoja_mantenimiento ?>
		<td data-name="id_hoja_mantenimiento"<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->CellAttributes() ?>>
<span<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_gasto_mantenieminto_list->ListOptions->Render("body", "right", $v_gasto_mantenieminto_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($v_gasto_mantenieminto->CurrentAction <> "gridadd")
		$v_gasto_mantenieminto_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($v_gasto_mantenieminto->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($v_gasto_mantenieminto_list->Recordset)
	$v_gasto_mantenieminto_list->Recordset->Close();
?>
<?php if ($v_gasto_mantenieminto->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($v_gasto_mantenieminto->CurrentAction <> "gridadd" && $v_gasto_mantenieminto->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($v_gasto_mantenieminto_list->Pager)) $v_gasto_mantenieminto_list->Pager = new cNumericPager($v_gasto_mantenieminto_list->StartRec, $v_gasto_mantenieminto_list->DisplayRecs, $v_gasto_mantenieminto_list->TotalRecs, $v_gasto_mantenieminto_list->RecRange) ?>
<?php if ($v_gasto_mantenieminto_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($v_gasto_mantenieminto_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $v_gasto_mantenieminto_list->PageUrl() ?>start=<?php echo $v_gasto_mantenieminto_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $v_gasto_mantenieminto_list->PageUrl() ?>start=<?php echo $v_gasto_mantenieminto_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($v_gasto_mantenieminto_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $v_gasto_mantenieminto_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $v_gasto_mantenieminto_list->PageUrl() ?>start=<?php echo $v_gasto_mantenieminto_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $v_gasto_mantenieminto_list->PageUrl() ?>start=<?php echo $v_gasto_mantenieminto_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $v_gasto_mantenieminto_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $v_gasto_mantenieminto_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $v_gasto_mantenieminto_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_gasto_mantenieminto_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($v_gasto_mantenieminto_list->TotalRecs == 0 && $v_gasto_mantenieminto->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_gasto_mantenieminto_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($v_gasto_mantenieminto->Export == "") { ?>
<script type="text/javascript">
fv_gasto_manteniemintolistsrch.Init();
fv_gasto_manteniemintolist.Init();
</script>
<?php } ?>
<?php
$v_gasto_mantenieminto_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($v_gasto_mantenieminto->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$v_gasto_mantenieminto_list->Page_Terminate();
?>
