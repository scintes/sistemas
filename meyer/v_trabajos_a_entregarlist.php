<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "v_trabajos_a_entregarinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$v_trabajos_a_entregar_list = NULL; // Initialize page object first

class cv_trabajos_a_entregar_list extends cv_trabajos_a_entregar {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{83DA4882-2FB3-4AE9-BADA-241C2F6A6920}";

	// Table name
	var $TableName = 'v_trabajos_a_entregar';

	// Page object name
	var $PageObjName = 'v_trabajos_a_entregar_list';

	// Grid form hidden field names
	var $FormName = 'fv_trabajos_a_entregarlist';
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

		// Table object (v_trabajos_a_entregar)
		if (!isset($GLOBALS["v_trabajos_a_entregar"])) {
			$GLOBALS["v_trabajos_a_entregar"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["v_trabajos_a_entregar"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "v_trabajos_a_entregaradd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "v_trabajos_a_entregardelete.php";
		$this->MultiUpdateUrl = "v_trabajos_a_entregarupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'v_trabajos_a_entregar', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "span";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;

		// Update url if printer friendly for Pdf
		if ($this->PrinterFriendlyForPdf)
			$this->ExportOptions->Items["pdf"]->Body = str_replace($this->ExportPdfUrl, $this->ExportPrintUrl . "&pdf=1", $this->ExportOptions->Items["pdf"]->Body);
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();
		if ($this->Export == "print" && @$_GET["pdf"] == "1") { // Printer friendly version and with pdf=1 in URL parameters
			$pdf = new cExportPdf($GLOBALS["Table"]);
			$pdf->Text = ob_get_contents(); // Set the content as the HTML of current page (printer friendly version)
			ob_end_clean();
			$pdf->Export();
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
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
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
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
			$sThisKey = strval($objForm->GetValue("k_key"));
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
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		$this->BuildSearchSql($sWhere, $this->cliente, FALSE); // cliente
		$this->BuildSearchSql($sWhere, $this->objetos, FALSE); // objetos
		$this->BuildSearchSql($sWhere, $this->precio, FALSE); // precio
		$this->BuildSearchSql($sWhere, $this->entrega, FALSE); // entrega
		$this->BuildSearchSql($sWhere, $this->saldo, FALSE); // saldo
		$this->BuildSearchSql($sWhere, $this->estado, FALSE); // estado
		$this->BuildSearchSql($sWhere, $this->fecha_recepcion, FALSE); // fecha_recepcion
		$this->BuildSearchSql($sWhere, $this->fecha_entrega, FALSE); // fecha_entrega

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->cliente->AdvancedSearch->Save(); // cliente
			$this->objetos->AdvancedSearch->Save(); // objetos
			$this->precio->AdvancedSearch->Save(); // precio
			$this->entrega->AdvancedSearch->Save(); // entrega
			$this->saldo->AdvancedSearch->Save(); // saldo
			$this->estado->AdvancedSearch->Save(); // estado
			$this->fecha_recepcion->AdvancedSearch->Save(); // fecha_recepcion
			$this->fecha_entrega->AdvancedSearch->Save(); // fecha_entrega
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
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
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->cliente, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->objetos, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
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
		if ($this->cliente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->objetos->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->precio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->entrega->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->saldo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_recepcion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_entrega->AdvancedSearch->IssetSession())
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
		$this->cliente->AdvancedSearch->UnsetSession();
		$this->objetos->AdvancedSearch->UnsetSession();
		$this->precio->AdvancedSearch->UnsetSession();
		$this->entrega->AdvancedSearch->UnsetSession();
		$this->saldo->AdvancedSearch->UnsetSession();
		$this->estado->AdvancedSearch->UnsetSession();
		$this->fecha_recepcion->AdvancedSearch->UnsetSession();
		$this->fecha_entrega->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->cliente->AdvancedSearch->Load();
		$this->objetos->AdvancedSearch->Load();
		$this->precio->AdvancedSearch->Load();
		$this->entrega->AdvancedSearch->Load();
		$this->saldo->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
		$this->fecha_recepcion->AdvancedSearch->Load();
		$this->fecha_entrega->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->cliente); // cliente
			$this->UpdateSort($this->objetos); // objetos
			$this->UpdateSort($this->precio); // precio
			$this->UpdateSort($this->entrega); // entrega
			$this->UpdateSort($this->saldo); // saldo
			$this->UpdateSort($this->estado); // estado
			$this->UpdateSort($this->fecha_recepcion); // fecha_recepcion
			$this->UpdateSort($this->fecha_entrega); // fecha_entrega
			$this->UpdateSort($this->foto1); // foto1
			$this->UpdateSort($this->foto2); // foto2
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
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
				$this->cliente->setSort("");
				$this->objetos->setSort("");
				$this->precio->setSort("");
				$this->entrega->setSort("");
				$this->saldo->setSort("");
				$this->estado->setSort("");
				$this->fecha_recepcion->setSort("");
				$this->fecha_entrega->setSort("");
				$this->foto1->setSort("");
				$this->foto2->setSort("");
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
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
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
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fv_trabajos_a_entregarlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// cliente

		$this->cliente->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_cliente"]);
		if ($this->cliente->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->cliente->AdvancedSearch->SearchOperator = @$_GET["z_cliente"];

		// objetos
		$this->objetos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_objetos"]);
		if ($this->objetos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->objetos->AdvancedSearch->SearchOperator = @$_GET["z_objetos"];

		// precio
		$this->precio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_precio"]);
		if ($this->precio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->precio->AdvancedSearch->SearchOperator = @$_GET["z_precio"];

		// entrega
		$this->entrega->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_entrega"]);
		if ($this->entrega->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->entrega->AdvancedSearch->SearchOperator = @$_GET["z_entrega"];

		// saldo
		$this->saldo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_saldo"]);
		if ($this->saldo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->saldo->AdvancedSearch->SearchOperator = @$_GET["z_saldo"];

		// estado
		$this->estado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estado"]);
		if ($this->estado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estado->AdvancedSearch->SearchOperator = @$_GET["z_estado"];

		// fecha_recepcion
		$this->fecha_recepcion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_recepcion"]);
		if ($this->fecha_recepcion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_recepcion->AdvancedSearch->SearchOperator = @$_GET["z_fecha_recepcion"];

		// fecha_entrega
		$this->fecha_entrega->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_entrega"]);
		if ($this->fecha_entrega->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_entrega->AdvancedSearch->SearchOperator = @$_GET["z_fecha_entrega"];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

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
		$this->cliente->setDbValue($rs->fields('cliente'));
		$this->objetos->setDbValue($rs->fields('objetos'));
		$this->precio->setDbValue($rs->fields('precio'));
		$this->entrega->setDbValue($rs->fields('entrega'));
		$this->saldo->setDbValue($rs->fields('saldo'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->fecha_recepcion->setDbValue($rs->fields('fecha_recepcion'));
		$this->fecha_entrega->setDbValue($rs->fields('fecha_entrega'));
		$this->foto1->Upload->DbValue = $rs->fields('foto1');
		$this->foto2->Upload->DbValue = $rs->fields('foto2');
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->cliente->DbValue = $row['cliente'];
		$this->objetos->DbValue = $row['objetos'];
		$this->precio->DbValue = $row['precio'];
		$this->entrega->DbValue = $row['entrega'];
		$this->saldo->DbValue = $row['saldo'];
		$this->estado->DbValue = $row['estado'];
		$this->fecha_recepcion->DbValue = $row['fecha_recepcion'];
		$this->fecha_entrega->DbValue = $row['fecha_entrega'];
		$this->foto1->Upload->DbValue = $row['foto1'];
		$this->foto2->Upload->DbValue = $row['foto2'];
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
		// cliente

		$this->cliente->CellCssStyle = "white-space: nowrap;";

		// objetos
		$this->objetos->CellCssStyle = "white-space: nowrap;";

		// precio
		$this->precio->CellCssStyle = "white-space: nowrap;";

		// entrega
		$this->entrega->CellCssStyle = "white-space: nowrap;";

		// saldo
		$this->saldo->CellCssStyle = "white-space: nowrap;";

		// estado
		$this->estado->CellCssStyle = "white-space: nowrap;";

		// fecha_recepcion
		$this->fecha_recepcion->CellCssStyle = "white-space: nowrap;";

		// fecha_entrega
		$this->fecha_entrega->CellCssStyle = "white-space: nowrap;";

		// foto1
		$this->foto1->CellCssStyle = "white-space: nowrap;";

		// foto2
		$this->foto2->CellCssStyle = "white-space: nowrap;";

		// Accumulate aggregate value
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
			if (is_numeric($this->precio->CurrentValue))
				$this->precio->Total += $this->precio->CurrentValue; // Accumulate total
			if (is_numeric($this->entrega->CurrentValue))
				$this->entrega->Total += $this->entrega->CurrentValue; // Accumulate total
			if (is_numeric($this->saldo->CurrentValue))
				$this->saldo->Total += $this->saldo->CurrentValue; // Accumulate total
		}
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// cliente
			$this->cliente->ViewValue = $this->cliente->CurrentValue;
			$this->cliente->ViewCustomAttributes = "";

			// objetos
			$this->objetos->ViewValue = $this->objetos->CurrentValue;
			$this->objetos->ViewCustomAttributes = "";

			// precio
			$this->precio->ViewValue = $this->precio->CurrentValue;
			$this->precio->ViewCustomAttributes = "";

			// entrega
			$this->entrega->ViewValue = $this->entrega->CurrentValue;
			$this->entrega->ViewCustomAttributes = "";

			// saldo
			$this->saldo->ViewValue = $this->saldo->CurrentValue;
			$this->saldo->ViewCustomAttributes = "";

			// estado
			if (strval($this->estado->CurrentValue) <> "") {
				$sFilterWrk = "`estado`" . ew_SearchString("=", $this->estado->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `estado`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estados`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->estado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `codigo` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->estado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->estado->ViewValue = $this->estado->CurrentValue;
				}
			} else {
				$this->estado->ViewValue = NULL;
			}
			$this->estado->ViewCustomAttributes = "";

			// fecha_recepcion
			$this->fecha_recepcion->ViewValue = $this->fecha_recepcion->CurrentValue;
			$this->fecha_recepcion->ViewValue = ew_FormatDateTime($this->fecha_recepcion->ViewValue, 7);
			$this->fecha_recepcion->ViewCustomAttributes = "";

			// fecha_entrega
			$this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
			$this->fecha_entrega->ViewValue = ew_FormatDateTime($this->fecha_entrega->ViewValue, 7);
			$this->fecha_entrega->ViewCustomAttributes = "";

			// foto1
			if (!ew_Empty($this->foto1->Upload->DbValue)) {
				$this->foto1->ImageWidth = 40;
				$this->foto1->ImageHeight = 40;
				$this->foto1->ImageAlt = $this->foto1->FldAlt();
				$this->foto1->ViewValue = ew_UploadPathEx(FALSE, $this->foto1->UploadPath) . $this->foto1->Upload->DbValue;
			} else {
				$this->foto1->ViewValue = "";
			}
			$this->foto1->ViewCustomAttributes = "";

			// foto2
			if (!ew_Empty($this->foto2->Upload->DbValue)) {
				$this->foto2->ImageWidth = 10;
				$this->foto2->ImageHeight = 10;
				$this->foto2->ImageAlt = $this->foto2->FldAlt();
				$this->foto2->ViewValue = ew_UploadPathEx(FALSE, $this->foto2->UploadPath) . $this->foto2->Upload->DbValue;
			} else {
				$this->foto2->ViewValue = "";
			}
			$this->foto2->ViewCustomAttributes = "";

			// cliente
			$this->cliente->LinkCustomAttributes = "";
			$this->cliente->HrefValue = "";
			$this->cliente->TooltipValue = "";

			// objetos
			$this->objetos->LinkCustomAttributes = "";
			$this->objetos->HrefValue = "";
			$this->objetos->TooltipValue = "";

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

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// fecha_recepcion
			$this->fecha_recepcion->LinkCustomAttributes = "";
			$this->fecha_recepcion->HrefValue = "";
			$this->fecha_recepcion->TooltipValue = "";

			// fecha_entrega
			$this->fecha_entrega->LinkCustomAttributes = "";
			$this->fecha_entrega->HrefValue = "";
			$this->fecha_entrega->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// cliente
			$this->cliente->EditCustomAttributes = "";
			$this->cliente->EditValue = ew_HtmlEncode($this->cliente->AdvancedSearch->SearchValue);
			$this->cliente->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->cliente->FldCaption()));

			// objetos
			$this->objetos->EditCustomAttributes = "";
			$this->objetos->EditValue = ew_HtmlEncode($this->objetos->AdvancedSearch->SearchValue);
			$this->objetos->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->objetos->FldCaption()));

			// precio
			$this->precio->EditCustomAttributes = "";
			$this->precio->EditValue = ew_HtmlEncode($this->precio->AdvancedSearch->SearchValue);
			$this->precio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->precio->FldCaption()));

			// entrega
			$this->entrega->EditCustomAttributes = "";
			$this->entrega->EditValue = ew_HtmlEncode($this->entrega->AdvancedSearch->SearchValue);
			$this->entrega->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->entrega->FldCaption()));

			// saldo
			$this->saldo->EditCustomAttributes = "";
			$this->saldo->EditValue = ew_HtmlEncode($this->saldo->AdvancedSearch->SearchValue);
			$this->saldo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->saldo->FldCaption()));

			// estado
			$this->estado->EditCustomAttributes = "";
			if (trim(strval($this->estado->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`estado`" . ew_SearchString("=", $this->estado->AdvancedSearch->SearchValue, EW_DATATYPE_STRING);
			}
			$sSqlWrk = "SELECT `estado`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `estados`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->estado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `codigo` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->estado->EditValue = $arwrk;

			// fecha_recepcion
			$this->fecha_recepcion->EditCustomAttributes = "";
			$this->fecha_recepcion->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_recepcion->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_recepcion->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->fecha_recepcion->FldCaption()));

			// fecha_entrega
			$this->fecha_entrega->EditCustomAttributes = "";
			$this->fecha_entrega->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_entrega->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_entrega->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->fecha_entrega->FldCaption()));

			// foto1
			$this->foto1->EditCustomAttributes = "";
			$this->foto1->EditValue = ew_HtmlEncode($this->foto1->AdvancedSearch->SearchValue);
			$this->foto1->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->foto1->FldCaption()));

			// foto2
			$this->foto2->EditCustomAttributes = "";
			$this->foto2->EditValue = ew_HtmlEncode($this->foto2->AdvancedSearch->SearchValue);
			$this->foto2->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->foto2->FldCaption()));
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
			$this->precio->Total = 0; // Initialize total
			$this->entrega->Total = 0; // Initialize total
			$this->saldo->Total = 0; // Initialize total
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row
			$this->precio->CurrentValue = $this->precio->Total;
			$this->precio->ViewValue = $this->precio->CurrentValue;
			$this->precio->ViewCustomAttributes = "";
			$this->precio->HrefValue = ""; // Clear href value
			$this->entrega->CurrentValue = $this->entrega->Total;
			$this->entrega->ViewValue = $this->entrega->CurrentValue;
			$this->entrega->ViewCustomAttributes = "";
			$this->entrega->HrefValue = ""; // Clear href value
			$this->saldo->CurrentValue = $this->saldo->Total;
			$this->saldo->ViewValue = $this->saldo->CurrentValue;
			$this->saldo->ViewCustomAttributes = "";
			$this->saldo->HrefValue = ""; // Clear href value
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
		if (!ew_CheckEuroDate($this->fecha_recepcion->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha_recepcion->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_entrega->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha_entrega->FldErrMsg());
		}

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
		$this->cliente->AdvancedSearch->Load();
		$this->objetos->AdvancedSearch->Load();
		$this->precio->AdvancedSearch->Load();
		$this->entrega->AdvancedSearch->Load();
		$this->saldo->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
		$this->fecha_recepcion->AdvancedSearch->Load();
		$this->fecha_entrega->AdvancedSearch->Load();
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

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_v_trabajos_a_entregar\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_v_trabajos_a_entregar',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fv_trabajos_a_entregarlist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
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
		$ExportDoc = ew_ExportDocument($this, "h");
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$ExportDoc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", $url, $this->TableVar);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($v_trabajos_a_entregar_list)) $v_trabajos_a_entregar_list = new cv_trabajos_a_entregar_list();

// Page init
$v_trabajos_a_entregar_list->Page_Init();

// Page main
$v_trabajos_a_entregar_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$v_trabajos_a_entregar_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($v_trabajos_a_entregar->Export == "") { ?>
<script type="text/javascript">

// Page object
var v_trabajos_a_entregar_list = new ew_Page("v_trabajos_a_entregar_list");
v_trabajos_a_entregar_list.PageID = "list"; // Page ID
var EW_PAGE_ID = v_trabajos_a_entregar_list.PageID; // For backward compatibility

// Form object
var fv_trabajos_a_entregarlist = new ew_Form("fv_trabajos_a_entregarlist");
fv_trabajos_a_entregarlist.FormKeyCountName = '<?php echo $v_trabajos_a_entregar_list->FormKeyCountName ?>';

// Form_CustomValidate event
fv_trabajos_a_entregarlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_trabajos_a_entregarlist.ValidateRequired = true;
<?php } else { ?>
fv_trabajos_a_entregarlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fv_trabajos_a_entregarlist.Lists["x_estado"] = {"LinkField":"x_estado","Ajax":true,"AutoFill":false,"DisplayFields":["x_estado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fv_trabajos_a_entregarlistsrch = new ew_Form("fv_trabajos_a_entregarlistsrch");

// Validate function for search
fv_trabajos_a_entregarlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_fecha_recepcion");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($v_trabajos_a_entregar->fecha_recepcion->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fecha_entrega");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($v_trabajos_a_entregar->fecha_entrega->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fv_trabajos_a_entregarlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_trabajos_a_entregarlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fv_trabajos_a_entregarlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fv_trabajos_a_entregarlistsrch.Lists["x_estado"] = {"LinkField":"x_estado","Ajax":true,"AutoFill":false,"DisplayFields":["x_estado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($v_trabajos_a_entregar->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($v_trabajos_a_entregar_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $v_trabajos_a_entregar_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$v_trabajos_a_entregar_list->TotalRecs = $v_trabajos_a_entregar->SelectRecordCount();
	} else {
		if ($v_trabajos_a_entregar_list->Recordset = $v_trabajos_a_entregar_list->LoadRecordset())
			$v_trabajos_a_entregar_list->TotalRecs = $v_trabajos_a_entregar_list->Recordset->RecordCount();
	}
	$v_trabajos_a_entregar_list->StartRec = 1;
	if ($v_trabajos_a_entregar_list->DisplayRecs <= 0 || ($v_trabajos_a_entregar->Export <> "" && $v_trabajos_a_entregar->ExportAll)) // Display all records
		$v_trabajos_a_entregar_list->DisplayRecs = $v_trabajos_a_entregar_list->TotalRecs;
	if (!($v_trabajos_a_entregar->Export <> "" && $v_trabajos_a_entregar->ExportAll))
		$v_trabajos_a_entregar_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$v_trabajos_a_entregar_list->Recordset = $v_trabajos_a_entregar_list->LoadRecordset($v_trabajos_a_entregar_list->StartRec-1, $v_trabajos_a_entregar_list->DisplayRecs);
$v_trabajos_a_entregar_list->RenderOtherOptions();
?>
<?php if ($v_trabajos_a_entregar->Export == "" && $v_trabajos_a_entregar->CurrentAction == "") { ?>
<form name="fv_trabajos_a_entregarlistsrch" id="fv_trabajos_a_entregarlistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fv_trabajos_a_entregarlistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fv_trabajos_a_entregarlistsrch_SearchGroup" href="#fv_trabajos_a_entregarlistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fv_trabajos_a_entregarlistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fv_trabajos_a_entregarlistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="v_trabajos_a_entregar">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$v_trabajos_a_entregar_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$v_trabajos_a_entregar->RowType = EW_ROWTYPE_SEARCH;

// Render row
$v_trabajos_a_entregar->ResetAttrs();
$v_trabajos_a_entregar_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($v_trabajos_a_entregar->estado->Visible) { // estado ?>
	<span id="xsc_estado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $v_trabajos_a_entregar->estado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_estado" id="z_estado" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_estado" id="x_estado" name="x_estado"<?php echo $v_trabajos_a_entregar->estado->EditAttributes() ?>>
<?php
if (is_array($v_trabajos_a_entregar->estado->EditValue)) {
	$arwrk = $v_trabajos_a_entregar->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($v_trabajos_a_entregar->estado->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php
$sSqlWrk = "SELECT `estado`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estados`";
$sWhereWrk = "";
$lookuptblfilter = "`activo`='S'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$v_trabajos_a_entregar->Lookup_Selecting($v_trabajos_a_entregar->estado, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `codigo` ASC";
?>
<input type="hidden" name="s_x_estado" id="s_x_estado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`estado` = {filter_value}"); ?>&t0=200">
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($v_trabajos_a_entregar->fecha_recepcion->Visible) { // fecha_recepcion ?>
	<span id="xsc_fecha_recepcion" class="ewCell">
		<span class="ewSearchCaption"><?php echo $v_trabajos_a_entregar->fecha_recepcion->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fecha_recepcion" id="z_fecha_recepcion" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_fecha_recepcion" name="x_fecha_recepcion" id="x_fecha_recepcion" placeholder="<?php echo $v_trabajos_a_entregar->fecha_recepcion->PlaceHolder ?>" value="<?php echo $v_trabajos_a_entregar->fecha_recepcion->EditValue ?>"<?php echo $v_trabajos_a_entregar->fecha_recepcion->EditAttributes() ?>>
<?php if (!$v_trabajos_a_entregar->fecha_recepcion->ReadOnly && !$v_trabajos_a_entregar->fecha_recepcion->Disabled && @$v_trabajos_a_entregar->fecha_recepcion->EditAttrs["readonly"] == "" && @$v_trabajos_a_entregar->fecha_recepcion->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_fecha_recepcion" name="cal_x_fecha_recepcion" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_fecha_recepcion" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fv_trabajos_a_entregarlistsrch", "x_fecha_recepcion", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($v_trabajos_a_entregar->fecha_entrega->Visible) { // fecha_entrega ?>
	<span id="xsc_fecha_entrega" class="ewCell">
		<span class="ewSearchCaption"><?php echo $v_trabajos_a_entregar->fecha_entrega->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fecha_entrega" id="z_fecha_entrega" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_fecha_entrega" name="x_fecha_entrega" id="x_fecha_entrega" placeholder="<?php echo $v_trabajos_a_entregar->fecha_entrega->PlaceHolder ?>" value="<?php echo $v_trabajos_a_entregar->fecha_entrega->EditValue ?>"<?php echo $v_trabajos_a_entregar->fecha_entrega->EditAttributes() ?>>
<?php if (!$v_trabajos_a_entregar->fecha_entrega->ReadOnly && !$v_trabajos_a_entregar->fecha_entrega->Disabled && @$v_trabajos_a_entregar->fecha_entrega->EditAttrs["readonly"] == "" && @$v_trabajos_a_entregar->fecha_entrega->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_fecha_entrega" name="cal_x_fecha_entrega" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_fecha_entrega" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fv_trabajos_a_entregarlistsrch", "x_fecha_entrega", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($v_trabajos_a_entregar_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $v_trabajos_a_entregar_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_5" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($v_trabajos_a_entregar_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($v_trabajos_a_entregar_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($v_trabajos_a_entregar_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</td></tr></table>
</form>
<?php } ?>
<?php $v_trabajos_a_entregar_list->ShowPageHeader(); ?>
<?php
$v_trabajos_a_entregar_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($v_trabajos_a_entregar->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($v_trabajos_a_entregar->CurrentAction <> "gridadd" && $v_trabajos_a_entregar->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($v_trabajos_a_entregar_list->Pager)) $v_trabajos_a_entregar_list->Pager = new cPrevNextPager($v_trabajos_a_entregar_list->StartRec, $v_trabajos_a_entregar_list->DisplayRecs, $v_trabajos_a_entregar_list->TotalRecs) ?>
<?php if ($v_trabajos_a_entregar_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($v_trabajos_a_entregar_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $v_trabajos_a_entregar_list->PageUrl() ?>start=<?php echo $v_trabajos_a_entregar_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($v_trabajos_a_entregar_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $v_trabajos_a_entregar_list->PageUrl() ?>start=<?php echo $v_trabajos_a_entregar_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $v_trabajos_a_entregar_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($v_trabajos_a_entregar_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $v_trabajos_a_entregar_list->PageUrl() ?>start=<?php echo $v_trabajos_a_entregar_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($v_trabajos_a_entregar_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $v_trabajos_a_entregar_list->PageUrl() ?>start=<?php echo $v_trabajos_a_entregar_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $v_trabajos_a_entregar_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $v_trabajos_a_entregar_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $v_trabajos_a_entregar_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $v_trabajos_a_entregar_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($v_trabajos_a_entregar_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_trabajos_a_entregar_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="fv_trabajos_a_entregarlist" id="fv_trabajos_a_entregarlist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="v_trabajos_a_entregar">
<div id="gmp_v_trabajos_a_entregar" class="ewGridMiddlePanel">
<?php if ($v_trabajos_a_entregar_list->TotalRecs > 0) { ?>
<table id="tbl_v_trabajos_a_entregarlist" class="ewTable ewTableSeparate">
<?php echo $v_trabajos_a_entregar->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$v_trabajos_a_entregar_list->RenderListOptions();

// Render list options (header, left)
$v_trabajos_a_entregar_list->ListOptions->Render("header", "left");
?>
<?php if ($v_trabajos_a_entregar->cliente->Visible) { // cliente ?>
	<?php if ($v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->cliente) == "") { ?>
		<td><div id="elh_v_trabajos_a_entregar_cliente" class="v_trabajos_a_entregar_cliente"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $v_trabajos_a_entregar->cliente->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->cliente) ?>',1);"><div id="elh_v_trabajos_a_entregar_cliente" class="v_trabajos_a_entregar_cliente">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $v_trabajos_a_entregar->cliente->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_trabajos_a_entregar->cliente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_trabajos_a_entregar->cliente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_trabajos_a_entregar->objetos->Visible) { // objetos ?>
	<?php if ($v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->objetos) == "") { ?>
		<td><div id="elh_v_trabajos_a_entregar_objetos" class="v_trabajos_a_entregar_objetos"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $v_trabajos_a_entregar->objetos->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->objetos) ?>',1);"><div id="elh_v_trabajos_a_entregar_objetos" class="v_trabajos_a_entregar_objetos">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $v_trabajos_a_entregar->objetos->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_trabajos_a_entregar->objetos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_trabajos_a_entregar->objetos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_trabajos_a_entregar->precio->Visible) { // precio ?>
	<?php if ($v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->precio) == "") { ?>
		<td><div id="elh_v_trabajos_a_entregar_precio" class="v_trabajos_a_entregar_precio"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $v_trabajos_a_entregar->precio->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->precio) ?>',1);"><div id="elh_v_trabajos_a_entregar_precio" class="v_trabajos_a_entregar_precio">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $v_trabajos_a_entregar->precio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_trabajos_a_entregar->precio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_trabajos_a_entregar->precio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_trabajos_a_entregar->entrega->Visible) { // entrega ?>
	<?php if ($v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->entrega) == "") { ?>
		<td><div id="elh_v_trabajos_a_entregar_entrega" class="v_trabajos_a_entregar_entrega"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $v_trabajos_a_entregar->entrega->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->entrega) ?>',1);"><div id="elh_v_trabajos_a_entregar_entrega" class="v_trabajos_a_entregar_entrega">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $v_trabajos_a_entregar->entrega->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_trabajos_a_entregar->entrega->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_trabajos_a_entregar->entrega->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_trabajos_a_entregar->saldo->Visible) { // saldo ?>
	<?php if ($v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->saldo) == "") { ?>
		<td><div id="elh_v_trabajos_a_entregar_saldo" class="v_trabajos_a_entregar_saldo"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $v_trabajos_a_entregar->saldo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->saldo) ?>',1);"><div id="elh_v_trabajos_a_entregar_saldo" class="v_trabajos_a_entregar_saldo">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $v_trabajos_a_entregar->saldo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_trabajos_a_entregar->saldo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_trabajos_a_entregar->saldo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_trabajos_a_entregar->estado->Visible) { // estado ?>
	<?php if ($v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->estado) == "") { ?>
		<td><div id="elh_v_trabajos_a_entregar_estado" class="v_trabajos_a_entregar_estado"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $v_trabajos_a_entregar->estado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->estado) ?>',1);"><div id="elh_v_trabajos_a_entregar_estado" class="v_trabajos_a_entregar_estado">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $v_trabajos_a_entregar->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_trabajos_a_entregar->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_trabajos_a_entregar->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_trabajos_a_entregar->fecha_recepcion->Visible) { // fecha_recepcion ?>
	<?php if ($v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->fecha_recepcion) == "") { ?>
		<td><div id="elh_v_trabajos_a_entregar_fecha_recepcion" class="v_trabajos_a_entregar_fecha_recepcion"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $v_trabajos_a_entregar->fecha_recepcion->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->fecha_recepcion) ?>',1);"><div id="elh_v_trabajos_a_entregar_fecha_recepcion" class="v_trabajos_a_entregar_fecha_recepcion">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $v_trabajos_a_entregar->fecha_recepcion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_trabajos_a_entregar->fecha_recepcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_trabajos_a_entregar->fecha_recepcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_trabajos_a_entregar->fecha_entrega->Visible) { // fecha_entrega ?>
	<?php if ($v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->fecha_entrega) == "") { ?>
		<td><div id="elh_v_trabajos_a_entregar_fecha_entrega" class="v_trabajos_a_entregar_fecha_entrega"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $v_trabajos_a_entregar->fecha_entrega->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->fecha_entrega) ?>',1);"><div id="elh_v_trabajos_a_entregar_fecha_entrega" class="v_trabajos_a_entregar_fecha_entrega">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $v_trabajos_a_entregar->fecha_entrega->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_trabajos_a_entregar->fecha_entrega->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_trabajos_a_entregar->fecha_entrega->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_trabajos_a_entregar->foto1->Visible) { // foto1 ?>
	<?php if ($v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->foto1) == "") { ?>
		<td><div id="elh_v_trabajos_a_entregar_foto1" class="v_trabajos_a_entregar_foto1"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $v_trabajos_a_entregar->foto1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->foto1) ?>',1);"><div id="elh_v_trabajos_a_entregar_foto1" class="v_trabajos_a_entregar_foto1">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $v_trabajos_a_entregar->foto1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_trabajos_a_entregar->foto1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_trabajos_a_entregar->foto1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($v_trabajos_a_entregar->foto2->Visible) { // foto2 ?>
	<?php if ($v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->foto2) == "") { ?>
		<td><div id="elh_v_trabajos_a_entregar_foto2" class="v_trabajos_a_entregar_foto2"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $v_trabajos_a_entregar->foto2->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_trabajos_a_entregar->SortUrl($v_trabajos_a_entregar->foto2) ?>',1);"><div id="elh_v_trabajos_a_entregar_foto2" class="v_trabajos_a_entregar_foto2">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $v_trabajos_a_entregar->foto2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_trabajos_a_entregar->foto2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_trabajos_a_entregar->foto2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$v_trabajos_a_entregar_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($v_trabajos_a_entregar->ExportAll && $v_trabajos_a_entregar->Export <> "") {
	$v_trabajos_a_entregar_list->StopRec = $v_trabajos_a_entregar_list->TotalRecs;
} else {

	// Set the last record to display
	if ($v_trabajos_a_entregar_list->TotalRecs > $v_trabajos_a_entregar_list->StartRec + $v_trabajos_a_entregar_list->DisplayRecs - 1)
		$v_trabajos_a_entregar_list->StopRec = $v_trabajos_a_entregar_list->StartRec + $v_trabajos_a_entregar_list->DisplayRecs - 1;
	else
		$v_trabajos_a_entregar_list->StopRec = $v_trabajos_a_entregar_list->TotalRecs;
}
$v_trabajos_a_entregar_list->RecCnt = $v_trabajos_a_entregar_list->StartRec - 1;
if ($v_trabajos_a_entregar_list->Recordset && !$v_trabajos_a_entregar_list->Recordset->EOF) {
	$v_trabajos_a_entregar_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $v_trabajos_a_entregar_list->StartRec > 1)
		$v_trabajos_a_entregar_list->Recordset->Move($v_trabajos_a_entregar_list->StartRec - 1);
} elseif (!$v_trabajos_a_entregar->AllowAddDeleteRow && $v_trabajos_a_entregar_list->StopRec == 0) {
	$v_trabajos_a_entregar_list->StopRec = $v_trabajos_a_entregar->GridAddRowCount;
}

// Initialize aggregate
$v_trabajos_a_entregar->RowType = EW_ROWTYPE_AGGREGATEINIT;
$v_trabajos_a_entregar->ResetAttrs();
$v_trabajos_a_entregar_list->RenderRow();
while ($v_trabajos_a_entregar_list->RecCnt < $v_trabajos_a_entregar_list->StopRec) {
	$v_trabajos_a_entregar_list->RecCnt++;
	if (intval($v_trabajos_a_entregar_list->RecCnt) >= intval($v_trabajos_a_entregar_list->StartRec)) {
		$v_trabajos_a_entregar_list->RowCnt++;

		// Set up key count
		$v_trabajos_a_entregar_list->KeyCount = $v_trabajos_a_entregar_list->RowIndex;

		// Init row class and style
		$v_trabajos_a_entregar->ResetAttrs();
		$v_trabajos_a_entregar->CssClass = "";
		if ($v_trabajos_a_entregar->CurrentAction == "gridadd") {
		} else {
			$v_trabajos_a_entregar_list->LoadRowValues($v_trabajos_a_entregar_list->Recordset); // Load row values
		}
		$v_trabajos_a_entregar->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$v_trabajos_a_entregar->RowAttrs = array_merge($v_trabajos_a_entregar->RowAttrs, array('data-rowindex'=>$v_trabajos_a_entregar_list->RowCnt, 'id'=>'r' . $v_trabajos_a_entregar_list->RowCnt . '_v_trabajos_a_entregar', 'data-rowtype'=>$v_trabajos_a_entregar->RowType));

		// Render row
		$v_trabajos_a_entregar_list->RenderRow();

		// Render list options
		$v_trabajos_a_entregar_list->RenderListOptions();
?>
	<tr<?php echo $v_trabajos_a_entregar->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_trabajos_a_entregar_list->ListOptions->Render("body", "left", $v_trabajos_a_entregar_list->RowCnt);
?>
	<?php if ($v_trabajos_a_entregar->cliente->Visible) { // cliente ?>
		<td<?php echo $v_trabajos_a_entregar->cliente->CellAttributes() ?>>
<span<?php echo $v_trabajos_a_entregar->cliente->ViewAttributes() ?>>
<?php echo $v_trabajos_a_entregar->cliente->ListViewValue() ?></span>
<a id="<?php echo $v_trabajos_a_entregar_list->PageObjName . "_row_" . $v_trabajos_a_entregar_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->objetos->Visible) { // objetos ?>
		<td<?php echo $v_trabajos_a_entregar->objetos->CellAttributes() ?>>
<span<?php echo $v_trabajos_a_entregar->objetos->ViewAttributes() ?>>
<?php echo $v_trabajos_a_entregar->objetos->ListViewValue() ?></span>
<a id="<?php echo $v_trabajos_a_entregar_list->PageObjName . "_row_" . $v_trabajos_a_entregar_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->precio->Visible) { // precio ?>
		<td<?php echo $v_trabajos_a_entregar->precio->CellAttributes() ?>>
<span<?php echo $v_trabajos_a_entregar->precio->ViewAttributes() ?>>
<?php echo $v_trabajos_a_entregar->precio->ListViewValue() ?></span>
<a id="<?php echo $v_trabajos_a_entregar_list->PageObjName . "_row_" . $v_trabajos_a_entregar_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->entrega->Visible) { // entrega ?>
		<td<?php echo $v_trabajos_a_entregar->entrega->CellAttributes() ?>>
<span<?php echo $v_trabajos_a_entregar->entrega->ViewAttributes() ?>>
<?php echo $v_trabajos_a_entregar->entrega->ListViewValue() ?></span>
<a id="<?php echo $v_trabajos_a_entregar_list->PageObjName . "_row_" . $v_trabajos_a_entregar_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->saldo->Visible) { // saldo ?>
		<td<?php echo $v_trabajos_a_entregar->saldo->CellAttributes() ?>>
<span<?php echo $v_trabajos_a_entregar->saldo->ViewAttributes() ?>>
<?php echo $v_trabajos_a_entregar->saldo->ListViewValue() ?></span>
<a id="<?php echo $v_trabajos_a_entregar_list->PageObjName . "_row_" . $v_trabajos_a_entregar_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->estado->Visible) { // estado ?>
		<td<?php echo $v_trabajos_a_entregar->estado->CellAttributes() ?>>
<span<?php echo $v_trabajos_a_entregar->estado->ViewAttributes() ?>>
<?php echo $v_trabajos_a_entregar->estado->ListViewValue() ?></span>
<a id="<?php echo $v_trabajos_a_entregar_list->PageObjName . "_row_" . $v_trabajos_a_entregar_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->fecha_recepcion->Visible) { // fecha_recepcion ?>
		<td<?php echo $v_trabajos_a_entregar->fecha_recepcion->CellAttributes() ?>>
<span<?php echo $v_trabajos_a_entregar->fecha_recepcion->ViewAttributes() ?>>
<?php echo $v_trabajos_a_entregar->fecha_recepcion->ListViewValue() ?></span>
<a id="<?php echo $v_trabajos_a_entregar_list->PageObjName . "_row_" . $v_trabajos_a_entregar_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->fecha_entrega->Visible) { // fecha_entrega ?>
		<td<?php echo $v_trabajos_a_entregar->fecha_entrega->CellAttributes() ?>>
<span<?php echo $v_trabajos_a_entregar->fecha_entrega->ViewAttributes() ?>>
<?php echo $v_trabajos_a_entregar->fecha_entrega->ListViewValue() ?></span>
<a id="<?php echo $v_trabajos_a_entregar_list->PageObjName . "_row_" . $v_trabajos_a_entregar_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->foto1->Visible) { // foto1 ?>
		<td<?php echo $v_trabajos_a_entregar->foto1->CellAttributes() ?>>
<span>
<?php if ($v_trabajos_a_entregar->foto1->LinkAttributes() <> "") { ?>
<?php if (!empty($v_trabajos_a_entregar->foto1->Upload->DbValue)) { ?>
<img src="<?php echo $v_trabajos_a_entregar->foto1->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $v_trabajos_a_entregar->foto1->ViewAttributes() ?>>
<?php } elseif (!in_array($v_trabajos_a_entregar->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($v_trabajos_a_entregar->foto1->Upload->DbValue)) { ?>
<img src="<?php echo $v_trabajos_a_entregar->foto1->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $v_trabajos_a_entregar->foto1->ViewAttributes() ?>>
<?php } elseif (!in_array($v_trabajos_a_entregar->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
<a id="<?php echo $v_trabajos_a_entregar_list->PageObjName . "_row_" . $v_trabajos_a_entregar_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->foto2->Visible) { // foto2 ?>
		<td<?php echo $v_trabajos_a_entregar->foto2->CellAttributes() ?>>
<span>
<?php if ($v_trabajos_a_entregar->foto2->LinkAttributes() <> "") { ?>
<?php if (!empty($v_trabajos_a_entregar->foto2->Upload->DbValue)) { ?>
<img src="<?php echo $v_trabajos_a_entregar->foto2->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $v_trabajos_a_entregar->foto2->ViewAttributes() ?>>
<?php } elseif (!in_array($v_trabajos_a_entregar->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($v_trabajos_a_entregar->foto2->Upload->DbValue)) { ?>
<img src="<?php echo $v_trabajos_a_entregar->foto2->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $v_trabajos_a_entregar->foto2->ViewAttributes() ?>>
<?php } elseif (!in_array($v_trabajos_a_entregar->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
<a id="<?php echo $v_trabajos_a_entregar_list->PageObjName . "_row_" . $v_trabajos_a_entregar_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_trabajos_a_entregar_list->ListOptions->Render("body", "right", $v_trabajos_a_entregar_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($v_trabajos_a_entregar->CurrentAction <> "gridadd")
		$v_trabajos_a_entregar_list->Recordset->MoveNext();
}
?>
</tbody>
<?php

// Render aggregate row
$v_trabajos_a_entregar->RowType = EW_ROWTYPE_AGGREGATE;
$v_trabajos_a_entregar->ResetAttrs();
$v_trabajos_a_entregar_list->RenderRow();
?>
<?php if ($v_trabajos_a_entregar_list->TotalRecs > 0 && ($v_trabajos_a_entregar->CurrentAction <> "gridadd" && $v_trabajos_a_entregar->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options
$v_trabajos_a_entregar_list->RenderListOptions();

// Render list options (footer, left)
$v_trabajos_a_entregar_list->ListOptions->Render("footer", "left");
?>
	<?php if ($v_trabajos_a_entregar->cliente->Visible) { // cliente ?>
		<td><span id="elf_v_trabajos_a_entregar_cliente" class="v_trabajos_a_entregar_cliente">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->objetos->Visible) { // objetos ?>
		<td><span id="elf_v_trabajos_a_entregar_objetos" class="v_trabajos_a_entregar_objetos">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->precio->Visible) { // precio ?>
		<td><span id="elf_v_trabajos_a_entregar_precio" class="v_trabajos_a_entregar_precio">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?>: </span>
<?php echo $v_trabajos_a_entregar->precio->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->entrega->Visible) { // entrega ?>
		<td><span id="elf_v_trabajos_a_entregar_entrega" class="v_trabajos_a_entregar_entrega">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?>: </span>
<?php echo $v_trabajos_a_entregar->entrega->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->saldo->Visible) { // saldo ?>
		<td><span id="elf_v_trabajos_a_entregar_saldo" class="v_trabajos_a_entregar_saldo">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?>: </span>
<?php echo $v_trabajos_a_entregar->saldo->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->estado->Visible) { // estado ?>
		<td><span id="elf_v_trabajos_a_entregar_estado" class="v_trabajos_a_entregar_estado">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->fecha_recepcion->Visible) { // fecha_recepcion ?>
		<td><span id="elf_v_trabajos_a_entregar_fecha_recepcion" class="v_trabajos_a_entregar_fecha_recepcion">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->fecha_entrega->Visible) { // fecha_entrega ?>
		<td><span id="elf_v_trabajos_a_entregar_fecha_entrega" class="v_trabajos_a_entregar_fecha_entrega">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->foto1->Visible) { // foto1 ?>
		<td><span id="elf_v_trabajos_a_entregar_foto1" class="v_trabajos_a_entregar_foto1">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_trabajos_a_entregar->foto2->Visible) { // foto2 ?>
		<td><span id="elf_v_trabajos_a_entregar_foto2" class="v_trabajos_a_entregar_foto2">
		&nbsp;
		</span></td>
	<?php } ?>
<?php

// Render list options (footer, right)
$v_trabajos_a_entregar_list->ListOptions->Render("footer", "right");
?>
	</tr>
</tfoot>	
<?php } ?>
</table>
<?php } ?>
<?php if ($v_trabajos_a_entregar->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($v_trabajos_a_entregar_list->Recordset)
	$v_trabajos_a_entregar_list->Recordset->Close();
?>
</td></tr></table>
<?php if ($v_trabajos_a_entregar->Export == "") { ?>
<script type="text/javascript">
fv_trabajos_a_entregarlistsrch.Init();
fv_trabajos_a_entregarlist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$v_trabajos_a_entregar_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($v_trabajos_a_entregar->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$v_trabajos_a_entregar_list->Page_Terminate();
?>
