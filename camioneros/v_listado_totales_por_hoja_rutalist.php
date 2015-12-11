<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "v_listado_totales_por_hoja_rutainfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$v_listado_totales_por_hoja_ruta_list = NULL; // Initialize page object first

class cv_listado_totales_por_hoja_ruta_list extends cv_listado_totales_por_hoja_ruta {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'v_listado_totales_por_hoja_ruta';

	// Page object name
	var $PageObjName = 'v_listado_totales_por_hoja_ruta_list';

	// Grid form hidden field names
	var $FormName = 'fv_listado_totales_por_hoja_rutalist';
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

		// Table object (v_listado_totales_por_hoja_ruta)
		if (!isset($GLOBALS["v_listado_totales_por_hoja_ruta"]) || get_class($GLOBALS["v_listado_totales_por_hoja_ruta"]) == "cv_listado_totales_por_hoja_ruta") {
			$GLOBALS["v_listado_totales_por_hoja_ruta"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["v_listado_totales_por_hoja_ruta"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "v_listado_totales_por_hoja_rutaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "v_listado_totales_por_hoja_rutadelete.php";
		$this->MultiUpdateUrl = "v_listado_totales_por_hoja_rutaupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'v_listado_totales_por_hoja_ruta', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();

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
		global $EW_EXPORT, $v_listado_totales_por_hoja_ruta;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($v_listado_totales_por_hoja_ruta);
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
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = EW_SELECT_LIMIT;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
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

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->codigo, $Default, FALSE); // codigo
		$this->BuildSearchSql($sWhere, $this->responsable, $Default, FALSE); // responsable
		$this->BuildSearchSql($sWhere, $this->Patente, $Default, FALSE); // Patente
		$this->BuildSearchSql($sWhere, $this->kg_carga, $Default, FALSE); // kg_carga
		$this->BuildSearchSql($sWhere, $this->tarifa, $Default, FALSE); // tarifa
		$this->BuildSearchSql($sWhere, $this->sub_total, $Default, FALSE); // sub_total
		$this->BuildSearchSql($sWhere, $this->porcentaje, $Default, FALSE); // porcentaje
		$this->BuildSearchSql($sWhere, $this->comision_chofer, $Default, FALSE); // comision_chofer
		$this->BuildSearchSql($sWhere, $this->adelanto, $Default, FALSE); // adelanto
		$this->BuildSearchSql($sWhere, $this->total, $Default, FALSE); // total

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->codigo->AdvancedSearch->Save(); // codigo
			$this->responsable->AdvancedSearch->Save(); // responsable
			$this->Patente->AdvancedSearch->Save(); // Patente
			$this->kg_carga->AdvancedSearch->Save(); // kg_carga
			$this->tarifa->AdvancedSearch->Save(); // tarifa
			$this->sub_total->AdvancedSearch->Save(); // sub_total
			$this->porcentaje->AdvancedSearch->Save(); // porcentaje
			$this->comision_chofer->AdvancedSearch->Save(); // comision_chofer
			$this->adelanto->AdvancedSearch->Save(); // adelanto
			$this->total->AdvancedSearch->Save(); // total
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
		$this->BuildBasicSearchSQL($sWhere, $this->responsable, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Patente, $arKeywords, $type);
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
		if ($this->codigo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->responsable->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Patente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->kg_carga->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tarifa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sub_total->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->porcentaje->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->comision_chofer->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->adelanto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->total->AdvancedSearch->IssetSession())
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
		$this->codigo->AdvancedSearch->UnsetSession();
		$this->responsable->AdvancedSearch->UnsetSession();
		$this->Patente->AdvancedSearch->UnsetSession();
		$this->kg_carga->AdvancedSearch->UnsetSession();
		$this->tarifa->AdvancedSearch->UnsetSession();
		$this->sub_total->AdvancedSearch->UnsetSession();
		$this->porcentaje->AdvancedSearch->UnsetSession();
		$this->comision_chofer->AdvancedSearch->UnsetSession();
		$this->adelanto->AdvancedSearch->UnsetSession();
		$this->total->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->codigo->AdvancedSearch->Load();
		$this->responsable->AdvancedSearch->Load();
		$this->Patente->AdvancedSearch->Load();
		$this->kg_carga->AdvancedSearch->Load();
		$this->tarifa->AdvancedSearch->Load();
		$this->sub_total->AdvancedSearch->Load();
		$this->porcentaje->AdvancedSearch->Load();
		$this->comision_chofer->AdvancedSearch->Load();
		$this->adelanto->AdvancedSearch->Load();
		$this->total->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->codigo); // codigo
			$this->UpdateSort($this->responsable); // responsable
			$this->UpdateSort($this->Patente); // Patente
			$this->UpdateSort($this->kg_carga); // kg_carga
			$this->UpdateSort($this->tarifa); // tarifa
			$this->UpdateSort($this->sub_total); // sub_total
			$this->UpdateSort($this->porcentaje); // porcentaje
			$this->UpdateSort($this->comision_chofer); // comision_chofer
			$this->UpdateSort($this->adelanto); // adelanto
			$this->UpdateSort($this->total); // total
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
				$this->codigo->setSort("");
				$this->responsable->setSort("");
				$this->Patente->setSort("");
				$this->kg_carga->setSort("");
				$this->tarifa->setSort("");
				$this->sub_total->setSort("");
				$this->porcentaje->setSort("");
				$this->comision_chofer->setSort("");
				$this->adelanto->setSort("");
				$this->total->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fv_listado_totales_por_hoja_rutalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fv_listado_totales_por_hoja_rutalistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

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
		// codigo

		$this->codigo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_codigo"]);
		if ($this->codigo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->codigo->AdvancedSearch->SearchOperator = @$_GET["z_codigo"];

		// responsable
		$this->responsable->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_responsable"]);
		if ($this->responsable->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->responsable->AdvancedSearch->SearchOperator = @$_GET["z_responsable"];

		// Patente
		$this->Patente->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Patente"]);
		if ($this->Patente->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Patente->AdvancedSearch->SearchOperator = @$_GET["z_Patente"];

		// kg_carga
		$this->kg_carga->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_kg_carga"]);
		if ($this->kg_carga->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->kg_carga->AdvancedSearch->SearchOperator = @$_GET["z_kg_carga"];

		// tarifa
		$this->tarifa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tarifa"]);
		if ($this->tarifa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tarifa->AdvancedSearch->SearchOperator = @$_GET["z_tarifa"];

		// sub_total
		$this->sub_total->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_sub_total"]);
		if ($this->sub_total->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->sub_total->AdvancedSearch->SearchOperator = @$_GET["z_sub_total"];

		// porcentaje
		$this->porcentaje->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_porcentaje"]);
		if ($this->porcentaje->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->porcentaje->AdvancedSearch->SearchOperator = @$_GET["z_porcentaje"];

		// comision_chofer
		$this->comision_chofer->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_comision_chofer"]);
		if ($this->comision_chofer->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->comision_chofer->AdvancedSearch->SearchOperator = @$_GET["z_comision_chofer"];

		// adelanto
		$this->adelanto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_adelanto"]);
		if ($this->adelanto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->adelanto->AdvancedSearch->SearchOperator = @$_GET["z_adelanto"];

		// total
		$this->total->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_total"]);
		if ($this->total->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->total->AdvancedSearch->SearchOperator = @$_GET["z_total"];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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
		$this->responsable->setDbValue($rs->fields('responsable'));
		$this->Patente->setDbValue($rs->fields('Patente'));
		$this->kg_carga->setDbValue($rs->fields('kg_carga'));
		$this->tarifa->setDbValue($rs->fields('tarifa'));
		$this->sub_total->setDbValue($rs->fields('sub_total'));
		$this->porcentaje->setDbValue($rs->fields('porcentaje'));
		$this->comision_chofer->setDbValue($rs->fields('comision_chofer'));
		$this->adelanto->setDbValue($rs->fields('adelanto'));
		$this->total->setDbValue($rs->fields('total'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->responsable->DbValue = $row['responsable'];
		$this->Patente->DbValue = $row['Patente'];
		$this->kg_carga->DbValue = $row['kg_carga'];
		$this->tarifa->DbValue = $row['tarifa'];
		$this->sub_total->DbValue = $row['sub_total'];
		$this->porcentaje->DbValue = $row['porcentaje'];
		$this->comision_chofer->DbValue = $row['comision_chofer'];
		$this->adelanto->DbValue = $row['adelanto'];
		$this->total->DbValue = $row['total'];
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
		// Accumulate aggregate value

		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
			if (is_numeric($this->sub_total->CurrentValue))
				$this->sub_total->Total += $this->sub_total->CurrentValue; // Accumulate total
			if (is_numeric($this->comision_chofer->CurrentValue))
				$this->comision_chofer->Total += $this->comision_chofer->CurrentValue; // Accumulate total
			if (is_numeric($this->total->CurrentValue))
				$this->total->Total += $this->total->CurrentValue; // Accumulate total
		}
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
			$this->kg_carga->ViewValue = ew_FormatNumber($this->kg_carga->ViewValue, 2, -2, -2, -2);
			$this->kg_carga->ViewCustomAttributes = "";

			// tarifa
			$this->tarifa->ViewValue = $this->tarifa->CurrentValue;
			$this->tarifa->ViewValue = ew_FormatCurrency($this->tarifa->ViewValue, 2, -2, -2, -2);
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
			$this->comision_chofer->ViewValue = ew_FormatCurrency($this->comision_chofer->ViewValue, 2, -2, -1, -2);
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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// codigo
			$this->codigo->EditAttrs["class"] = "form-control";
			$this->codigo->EditCustomAttributes = "";
			$this->codigo->EditValue = ew_HtmlEncode($this->codigo->AdvancedSearch->SearchValue);
			$this->codigo->PlaceHolder = ew_RemoveHtml($this->codigo->FldCaption());

			// responsable
			$this->responsable->EditAttrs["class"] = "form-control";
			$this->responsable->EditCustomAttributes = "";
			$this->responsable->EditValue = ew_HtmlEncode($this->responsable->AdvancedSearch->SearchValue);
			$this->responsable->PlaceHolder = ew_RemoveHtml($this->responsable->FldCaption());

			// Patente
			$this->Patente->EditAttrs["class"] = "form-control";
			$this->Patente->EditCustomAttributes = "";
			$this->Patente->EditValue = ew_HtmlEncode($this->Patente->AdvancedSearch->SearchValue);
			$this->Patente->PlaceHolder = ew_RemoveHtml($this->Patente->FldCaption());

			// kg_carga
			$this->kg_carga->EditAttrs["class"] = "form-control";
			$this->kg_carga->EditCustomAttributes = "";
			$this->kg_carga->EditValue = ew_HtmlEncode($this->kg_carga->AdvancedSearch->SearchValue);
			$this->kg_carga->PlaceHolder = ew_RemoveHtml($this->kg_carga->FldCaption());

			// tarifa
			$this->tarifa->EditAttrs["class"] = "form-control";
			$this->tarifa->EditCustomAttributes = "";
			$this->tarifa->EditValue = ew_HtmlEncode($this->tarifa->AdvancedSearch->SearchValue);
			$this->tarifa->PlaceHolder = ew_RemoveHtml($this->tarifa->FldCaption());

			// sub_total
			$this->sub_total->EditAttrs["class"] = "form-control";
			$this->sub_total->EditCustomAttributes = "";
			$this->sub_total->EditValue = ew_HtmlEncode($this->sub_total->AdvancedSearch->SearchValue);
			$this->sub_total->PlaceHolder = ew_RemoveHtml($this->sub_total->FldCaption());

			// porcentaje
			$this->porcentaje->EditAttrs["class"] = "form-control";
			$this->porcentaje->EditCustomAttributes = "";
			$this->porcentaje->EditValue = ew_HtmlEncode($this->porcentaje->AdvancedSearch->SearchValue);
			$this->porcentaje->PlaceHolder = ew_RemoveHtml($this->porcentaje->FldCaption());

			// comision_chofer
			$this->comision_chofer->EditAttrs["class"] = "form-control";
			$this->comision_chofer->EditCustomAttributes = "";
			$this->comision_chofer->EditValue = ew_HtmlEncode($this->comision_chofer->AdvancedSearch->SearchValue);
			$this->comision_chofer->PlaceHolder = ew_RemoveHtml($this->comision_chofer->FldCaption());

			// adelanto
			$this->adelanto->EditAttrs["class"] = "form-control";
			$this->adelanto->EditCustomAttributes = "";
			$this->adelanto->EditValue = ew_HtmlEncode($this->adelanto->AdvancedSearch->SearchValue);
			$this->adelanto->PlaceHolder = ew_RemoveHtml($this->adelanto->FldCaption());

			// total
			$this->total->EditAttrs["class"] = "form-control";
			$this->total->EditCustomAttributes = "";
			$this->total->EditValue = ew_HtmlEncode($this->total->AdvancedSearch->SearchValue);
			$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
			$this->sub_total->Total = 0; // Initialize total
			$this->comision_chofer->Total = 0; // Initialize total
			$this->total->Total = 0; // Initialize total
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row
			$this->sub_total->CurrentValue = $this->sub_total->Total;
			$this->sub_total->ViewValue = $this->sub_total->CurrentValue;
			$this->sub_total->ViewValue = ew_FormatCurrency($this->sub_total->ViewValue, 2, -2, -2, -2);
			$this->sub_total->ViewCustomAttributes = "";
			$this->sub_total->HrefValue = ""; // Clear href value
			$this->comision_chofer->CurrentValue = $this->comision_chofer->Total;
			$this->comision_chofer->ViewValue = $this->comision_chofer->CurrentValue;
			$this->comision_chofer->ViewValue = ew_FormatCurrency($this->comision_chofer->ViewValue, 2, -2, -1, -2);
			$this->comision_chofer->ViewCustomAttributes = "";
			$this->comision_chofer->HrefValue = ""; // Clear href value
			$this->total->CurrentValue = $this->total->Total;
			$this->total->ViewValue = $this->total->CurrentValue;
			$this->total->ViewValue = ew_FormatCurrency($this->total->ViewValue, 2, -2, -2, -2);
			$this->total->ViewCustomAttributes = "";
			$this->total->HrefValue = ""; // Clear href value
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
		$this->codigo->AdvancedSearch->Load();
		$this->responsable->AdvancedSearch->Load();
		$this->Patente->AdvancedSearch->Load();
		$this->kg_carga->AdvancedSearch->Load();
		$this->tarifa->AdvancedSearch->Load();
		$this->sub_total->AdvancedSearch->Load();
		$this->porcentaje->AdvancedSearch->Load();
		$this->comision_chofer->AdvancedSearch->Load();
		$this->adelanto->AdvancedSearch->Load();
		$this->total->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_v_listado_totales_por_hoja_ruta\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_v_listado_totales_por_hoja_ruta',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fv_listado_totales_por_hoja_rutalist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
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
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
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

		$razon_social = 'CintesSoft Sistemas';
		$listado = 'Listado de hoja de rutas...';
		$this->ExportDoc->Text = "
									<table cellspacing='0' border=1 width='100%'>
										<thead>
											<tr class='ewTableHeader'>
												<td rowspan='2'>
													<image src='./phpimages/phpmkrlogo11.png' width='400' height='80'>
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
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($v_listado_totales_por_hoja_ruta_list)) $v_listado_totales_por_hoja_ruta_list = new cv_listado_totales_por_hoja_ruta_list();

// Page init
$v_listado_totales_por_hoja_ruta_list->Page_Init();

// Page main
$v_listado_totales_por_hoja_ruta_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$v_listado_totales_por_hoja_ruta_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($v_listado_totales_por_hoja_ruta->Export == "") { ?>
<script type="text/javascript">

// Page object
var v_listado_totales_por_hoja_ruta_list = new ew_Page("v_listado_totales_por_hoja_ruta_list");
v_listado_totales_por_hoja_ruta_list.PageID = "list"; // Page ID
var EW_PAGE_ID = v_listado_totales_por_hoja_ruta_list.PageID; // For backward compatibility

// Form object
var fv_listado_totales_por_hoja_rutalist = new ew_Form("fv_listado_totales_por_hoja_rutalist");
fv_listado_totales_por_hoja_rutalist.FormKeyCountName = '<?php echo $v_listado_totales_por_hoja_ruta_list->FormKeyCountName ?>';

// Form_CustomValidate event
fv_listado_totales_por_hoja_rutalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_listado_totales_por_hoja_rutalist.ValidateRequired = true;
<?php } else { ?>
fv_listado_totales_por_hoja_rutalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fv_listado_totales_por_hoja_rutalistsrch = new ew_Form("fv_listado_totales_por_hoja_rutalistsrch");

// Validate function for search
fv_listado_totales_por_hoja_rutalistsrch.Validate = function(fobj) {
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
fv_listado_totales_por_hoja_rutalistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_listado_totales_por_hoja_rutalistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fv_listado_totales_por_hoja_rutalistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($v_listado_totales_por_hoja_ruta->Export == "") { ?>
<div class="ewToolbar">
<?php if ($v_listado_totales_por_hoja_ruta->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($v_listado_totales_por_hoja_ruta_list->TotalRecs > 0 && $v_listado_totales_por_hoja_ruta_list->ExportOptions->Visible()) { ?>
<?php $v_listado_totales_por_hoja_ruta_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($v_listado_totales_por_hoja_ruta_list->SearchOptions->Visible()) { ?>
<?php $v_listado_totales_por_hoja_ruta_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($v_listado_totales_por_hoja_ruta->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($v_listado_totales_por_hoja_ruta_list->TotalRecs <= 0)
			$v_listado_totales_por_hoja_ruta_list->TotalRecs = $v_listado_totales_por_hoja_ruta->SelectRecordCount();
	} else {
		if (!$v_listado_totales_por_hoja_ruta_list->Recordset && ($v_listado_totales_por_hoja_ruta_list->Recordset = $v_listado_totales_por_hoja_ruta_list->LoadRecordset()))
			$v_listado_totales_por_hoja_ruta_list->TotalRecs = $v_listado_totales_por_hoja_ruta_list->Recordset->RecordCount();
	}
	$v_listado_totales_por_hoja_ruta_list->StartRec = 1;
	if ($v_listado_totales_por_hoja_ruta_list->DisplayRecs <= 0 || ($v_listado_totales_por_hoja_ruta->Export <> "" && $v_listado_totales_por_hoja_ruta->ExportAll)) // Display all records
		$v_listado_totales_por_hoja_ruta_list->DisplayRecs = $v_listado_totales_por_hoja_ruta_list->TotalRecs;
	if (!($v_listado_totales_por_hoja_ruta->Export <> "" && $v_listado_totales_por_hoja_ruta->ExportAll))
		$v_listado_totales_por_hoja_ruta_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$v_listado_totales_por_hoja_ruta_list->Recordset = $v_listado_totales_por_hoja_ruta_list->LoadRecordset($v_listado_totales_por_hoja_ruta_list->StartRec-1, $v_listado_totales_por_hoja_ruta_list->DisplayRecs);

	// Set no record found message
	if ($v_listado_totales_por_hoja_ruta->CurrentAction == "" && $v_listado_totales_por_hoja_ruta_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$v_listado_totales_por_hoja_ruta_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($v_listado_totales_por_hoja_ruta_list->SearchWhere == "0=101")
			$v_listado_totales_por_hoja_ruta_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$v_listado_totales_por_hoja_ruta_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$v_listado_totales_por_hoja_ruta_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($v_listado_totales_por_hoja_ruta->Export == "" && $v_listado_totales_por_hoja_ruta->CurrentAction == "") { ?>
<form name="fv_listado_totales_por_hoja_rutalistsrch" id="fv_listado_totales_por_hoja_rutalistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($v_listado_totales_por_hoja_ruta_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fv_listado_totales_por_hoja_rutalistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="v_listado_totales_por_hoja_ruta">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$v_listado_totales_por_hoja_ruta_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$v_listado_totales_por_hoja_ruta->RowType = EW_ROWTYPE_SEARCH;

// Render row
$v_listado_totales_por_hoja_ruta->ResetAttrs();
$v_listado_totales_por_hoja_ruta_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($v_listado_totales_por_hoja_ruta->responsable->Visible) { // responsable ?>
	<div id="xsc_responsable" class="ewCell form-group">
		<label for="x_responsable" class="ewSearchCaption ewLabel"><?php echo $v_listado_totales_por_hoja_ruta->responsable->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_responsable" id="z_responsable" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_responsable" name="x_responsable" id="x_responsable" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($v_listado_totales_por_hoja_ruta->responsable->PlaceHolder) ?>" value="<?php echo $v_listado_totales_por_hoja_ruta->responsable->EditValue ?>"<?php echo $v_listado_totales_por_hoja_ruta->responsable->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($v_listado_totales_por_hoja_ruta->Patente->Visible) { // Patente ?>
	<div id="xsc_Patente" class="ewCell form-group">
		<label for="x_Patente" class="ewSearchCaption ewLabel"><?php echo $v_listado_totales_por_hoja_ruta->Patente->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Patente" id="z_Patente" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Patente" name="x_Patente" id="x_Patente" size="30" maxlength="7" placeholder="<?php echo ew_HtmlEncode($v_listado_totales_por_hoja_ruta->Patente->PlaceHolder) ?>" value="<?php echo $v_listado_totales_por_hoja_ruta->Patente->EditValue ?>"<?php echo $v_listado_totales_por_hoja_ruta->Patente->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($v_listado_totales_por_hoja_ruta_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($v_listado_totales_por_hoja_ruta_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $v_listado_totales_por_hoja_ruta_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($v_listado_totales_por_hoja_ruta_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($v_listado_totales_por_hoja_ruta_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($v_listado_totales_por_hoja_ruta_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($v_listado_totales_por_hoja_ruta_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $v_listado_totales_por_hoja_ruta_list->ShowPageHeader(); ?>
<?php
$v_listado_totales_por_hoja_ruta_list->ShowMessage();
?>
<?php if ($v_listado_totales_por_hoja_ruta_list->TotalRecs > 0 || $v_listado_totales_por_hoja_ruta->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($v_listado_totales_por_hoja_ruta->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($v_listado_totales_por_hoja_ruta->CurrentAction <> "gridadd" && $v_listado_totales_por_hoja_ruta->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($v_listado_totales_por_hoja_ruta_list->Pager)) $v_listado_totales_por_hoja_ruta_list->Pager = new cNumericPager($v_listado_totales_por_hoja_ruta_list->StartRec, $v_listado_totales_por_hoja_ruta_list->DisplayRecs, $v_listado_totales_por_hoja_ruta_list->TotalRecs, $v_listado_totales_por_hoja_ruta_list->RecRange) ?>
<?php if ($v_listado_totales_por_hoja_ruta_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($v_listado_totales_por_hoja_ruta_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_totales_por_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_totales_por_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($v_listado_totales_por_hoja_ruta_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $v_listado_totales_por_hoja_ruta_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_totales_por_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_totales_por_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_listado_totales_por_hoja_ruta_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fv_listado_totales_por_hoja_rutalist" id="fv_listado_totales_por_hoja_rutalist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($v_listado_totales_por_hoja_ruta_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $v_listado_totales_por_hoja_ruta_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="v_listado_totales_por_hoja_ruta">
<div id="gmp_v_listado_totales_por_hoja_ruta" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($v_listado_totales_por_hoja_ruta_list->TotalRecs > 0) { ?>
<table id="tbl_v_listado_totales_por_hoja_rutalist" class="table ewTable">
<?php echo $v_listado_totales_por_hoja_ruta->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$v_listado_totales_por_hoja_ruta->RowType = EW_ROWTYPE_HEADER;

// Render list options
$v_listado_totales_por_hoja_ruta_list->RenderListOptions();

// Render list options (header, left)
$v_listado_totales_por_hoja_ruta_list->ListOptions->Render("header", "left");
?>
<?php if ($v_listado_totales_por_hoja_ruta->codigo->Visible) { // codigo ?>
	<?php if ($v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->codigo) == "") { ?>
		<th data-name="codigo"><div id="elh_v_listado_totales_por_hoja_ruta_codigo" class="v_listado_totales_por_hoja_ruta_codigo"><div class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->codigo) ?>',1);"><div id="elh_v_listado_totales_por_hoja_ruta_codigo" class="v_listado_totales_por_hoja_ruta_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_totales_por_hoja_ruta->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_totales_por_hoja_ruta->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_totales_por_hoja_ruta->responsable->Visible) { // responsable ?>
	<?php if ($v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->responsable) == "") { ?>
		<th data-name="responsable"><div id="elh_v_listado_totales_por_hoja_ruta_responsable" class="v_listado_totales_por_hoja_ruta_responsable"><div class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->responsable->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="responsable"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->responsable) ?>',1);"><div id="elh_v_listado_totales_por_hoja_ruta_responsable" class="v_listado_totales_por_hoja_ruta_responsable">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->responsable->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_totales_por_hoja_ruta->responsable->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_totales_por_hoja_ruta->responsable->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_totales_por_hoja_ruta->Patente->Visible) { // Patente ?>
	<?php if ($v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->Patente) == "") { ?>
		<th data-name="Patente"><div id="elh_v_listado_totales_por_hoja_ruta_Patente" class="v_listado_totales_por_hoja_ruta_Patente"><div class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->Patente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Patente"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->Patente) ?>',1);"><div id="elh_v_listado_totales_por_hoja_ruta_Patente" class="v_listado_totales_por_hoja_ruta_Patente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->Patente->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_totales_por_hoja_ruta->Patente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_totales_por_hoja_ruta->Patente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_totales_por_hoja_ruta->kg_carga->Visible) { // kg_carga ?>
	<?php if ($v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->kg_carga) == "") { ?>
		<th data-name="kg_carga"><div id="elh_v_listado_totales_por_hoja_ruta_kg_carga" class="v_listado_totales_por_hoja_ruta_kg_carga"><div class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->kg_carga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kg_carga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->kg_carga) ?>',1);"><div id="elh_v_listado_totales_por_hoja_ruta_kg_carga" class="v_listado_totales_por_hoja_ruta_kg_carga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->kg_carga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_totales_por_hoja_ruta->kg_carga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_totales_por_hoja_ruta->kg_carga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_totales_por_hoja_ruta->tarifa->Visible) { // tarifa ?>
	<?php if ($v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->tarifa) == "") { ?>
		<th data-name="tarifa"><div id="elh_v_listado_totales_por_hoja_ruta_tarifa" class="v_listado_totales_por_hoja_ruta_tarifa"><div class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->tarifa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tarifa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->tarifa) ?>',1);"><div id="elh_v_listado_totales_por_hoja_ruta_tarifa" class="v_listado_totales_por_hoja_ruta_tarifa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->tarifa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_totales_por_hoja_ruta->tarifa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_totales_por_hoja_ruta->tarifa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_totales_por_hoja_ruta->sub_total->Visible) { // sub_total ?>
	<?php if ($v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->sub_total) == "") { ?>
		<th data-name="sub_total"><div id="elh_v_listado_totales_por_hoja_ruta_sub_total" class="v_listado_totales_por_hoja_ruta_sub_total"><div class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->sub_total->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sub_total"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->sub_total) ?>',1);"><div id="elh_v_listado_totales_por_hoja_ruta_sub_total" class="v_listado_totales_por_hoja_ruta_sub_total">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->sub_total->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_totales_por_hoja_ruta->sub_total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_totales_por_hoja_ruta->sub_total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_totales_por_hoja_ruta->porcentaje->Visible) { // porcentaje ?>
	<?php if ($v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->porcentaje) == "") { ?>
		<th data-name="porcentaje"><div id="elh_v_listado_totales_por_hoja_ruta_porcentaje" class="v_listado_totales_por_hoja_ruta_porcentaje"><div class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->porcentaje->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="porcentaje"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->porcentaje) ?>',1);"><div id="elh_v_listado_totales_por_hoja_ruta_porcentaje" class="v_listado_totales_por_hoja_ruta_porcentaje">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->porcentaje->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_totales_por_hoja_ruta->porcentaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_totales_por_hoja_ruta->porcentaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_totales_por_hoja_ruta->comision_chofer->Visible) { // comision_chofer ?>
	<?php if ($v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->comision_chofer) == "") { ?>
		<th data-name="comision_chofer"><div id="elh_v_listado_totales_por_hoja_ruta_comision_chofer" class="v_listado_totales_por_hoja_ruta_comision_chofer"><div class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->comision_chofer->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comision_chofer"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->comision_chofer) ?>',1);"><div id="elh_v_listado_totales_por_hoja_ruta_comision_chofer" class="v_listado_totales_por_hoja_ruta_comision_chofer">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->comision_chofer->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_totales_por_hoja_ruta->comision_chofer->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_totales_por_hoja_ruta->comision_chofer->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_totales_por_hoja_ruta->adelanto->Visible) { // adelanto ?>
	<?php if ($v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->adelanto) == "") { ?>
		<th data-name="adelanto"><div id="elh_v_listado_totales_por_hoja_ruta_adelanto" class="v_listado_totales_por_hoja_ruta_adelanto"><div class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->adelanto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="adelanto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->adelanto) ?>',1);"><div id="elh_v_listado_totales_por_hoja_ruta_adelanto" class="v_listado_totales_por_hoja_ruta_adelanto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->adelanto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_totales_por_hoja_ruta->adelanto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_totales_por_hoja_ruta->adelanto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_totales_por_hoja_ruta->total->Visible) { // total ?>
	<?php if ($v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->total) == "") { ?>
		<th data-name="total"><div id="elh_v_listado_totales_por_hoja_ruta_total" class="v_listado_totales_por_hoja_ruta_total"><div class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->total->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="total"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_totales_por_hoja_ruta->SortUrl($v_listado_totales_por_hoja_ruta->total) ?>',1);"><div id="elh_v_listado_totales_por_hoja_ruta_total" class="v_listado_totales_por_hoja_ruta_total">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_totales_por_hoja_ruta->total->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_totales_por_hoja_ruta->total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_totales_por_hoja_ruta->total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$v_listado_totales_por_hoja_ruta_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($v_listado_totales_por_hoja_ruta->ExportAll && $v_listado_totales_por_hoja_ruta->Export <> "") {
	$v_listado_totales_por_hoja_ruta_list->StopRec = $v_listado_totales_por_hoja_ruta_list->TotalRecs;
} else {

	// Set the last record to display
	if ($v_listado_totales_por_hoja_ruta_list->TotalRecs > $v_listado_totales_por_hoja_ruta_list->StartRec + $v_listado_totales_por_hoja_ruta_list->DisplayRecs - 1)
		$v_listado_totales_por_hoja_ruta_list->StopRec = $v_listado_totales_por_hoja_ruta_list->StartRec + $v_listado_totales_por_hoja_ruta_list->DisplayRecs - 1;
	else
		$v_listado_totales_por_hoja_ruta_list->StopRec = $v_listado_totales_por_hoja_ruta_list->TotalRecs;
}
$v_listado_totales_por_hoja_ruta_list->RecCnt = $v_listado_totales_por_hoja_ruta_list->StartRec - 1;
if ($v_listado_totales_por_hoja_ruta_list->Recordset && !$v_listado_totales_por_hoja_ruta_list->Recordset->EOF) {
	$v_listado_totales_por_hoja_ruta_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $v_listado_totales_por_hoja_ruta_list->StartRec > 1)
		$v_listado_totales_por_hoja_ruta_list->Recordset->Move($v_listado_totales_por_hoja_ruta_list->StartRec - 1);
} elseif (!$v_listado_totales_por_hoja_ruta->AllowAddDeleteRow && $v_listado_totales_por_hoja_ruta_list->StopRec == 0) {
	$v_listado_totales_por_hoja_ruta_list->StopRec = $v_listado_totales_por_hoja_ruta->GridAddRowCount;
}

// Initialize aggregate
$v_listado_totales_por_hoja_ruta->RowType = EW_ROWTYPE_AGGREGATEINIT;
$v_listado_totales_por_hoja_ruta->ResetAttrs();
$v_listado_totales_por_hoja_ruta_list->RenderRow();
while ($v_listado_totales_por_hoja_ruta_list->RecCnt < $v_listado_totales_por_hoja_ruta_list->StopRec) {
	$v_listado_totales_por_hoja_ruta_list->RecCnt++;
	if (intval($v_listado_totales_por_hoja_ruta_list->RecCnt) >= intval($v_listado_totales_por_hoja_ruta_list->StartRec)) {
		$v_listado_totales_por_hoja_ruta_list->RowCnt++;

		// Set up key count
		$v_listado_totales_por_hoja_ruta_list->KeyCount = $v_listado_totales_por_hoja_ruta_list->RowIndex;

		// Init row class and style
		$v_listado_totales_por_hoja_ruta->ResetAttrs();
		$v_listado_totales_por_hoja_ruta->CssClass = "";
		if ($v_listado_totales_por_hoja_ruta->CurrentAction == "gridadd") {
		} else {
			$v_listado_totales_por_hoja_ruta_list->LoadRowValues($v_listado_totales_por_hoja_ruta_list->Recordset); // Load row values
		}
		$v_listado_totales_por_hoja_ruta->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$v_listado_totales_por_hoja_ruta->RowAttrs = array_merge($v_listado_totales_por_hoja_ruta->RowAttrs, array('data-rowindex'=>$v_listado_totales_por_hoja_ruta_list->RowCnt, 'id'=>'r' . $v_listado_totales_por_hoja_ruta_list->RowCnt . '_v_listado_totales_por_hoja_ruta', 'data-rowtype'=>$v_listado_totales_por_hoja_ruta->RowType));

		// Render row
		$v_listado_totales_por_hoja_ruta_list->RenderRow();

		// Render list options
		$v_listado_totales_por_hoja_ruta_list->RenderListOptions();
?>
	<tr<?php echo $v_listado_totales_por_hoja_ruta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_listado_totales_por_hoja_ruta_list->ListOptions->Render("body", "left", $v_listado_totales_por_hoja_ruta_list->RowCnt);
?>
	<?php if ($v_listado_totales_por_hoja_ruta->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $v_listado_totales_por_hoja_ruta->codigo->CellAttributes() ?>>
<span<?php echo $v_listado_totales_por_hoja_ruta->codigo->ViewAttributes() ?>>
<?php echo $v_listado_totales_por_hoja_ruta->codigo->ListViewValue() ?></span>
<a id="<?php echo $v_listado_totales_por_hoja_ruta_list->PageObjName . "_row_" . $v_listado_totales_por_hoja_ruta_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->responsable->Visible) { // responsable ?>
		<td data-name="responsable"<?php echo $v_listado_totales_por_hoja_ruta->responsable->CellAttributes() ?>>
<span<?php echo $v_listado_totales_por_hoja_ruta->responsable->ViewAttributes() ?>>
<?php echo $v_listado_totales_por_hoja_ruta->responsable->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->Patente->Visible) { // Patente ?>
		<td data-name="Patente"<?php echo $v_listado_totales_por_hoja_ruta->Patente->CellAttributes() ?>>
<span<?php echo $v_listado_totales_por_hoja_ruta->Patente->ViewAttributes() ?>>
<?php echo $v_listado_totales_por_hoja_ruta->Patente->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->kg_carga->Visible) { // kg_carga ?>
		<td data-name="kg_carga"<?php echo $v_listado_totales_por_hoja_ruta->kg_carga->CellAttributes() ?>>
<span<?php echo $v_listado_totales_por_hoja_ruta->kg_carga->ViewAttributes() ?>>
<?php echo $v_listado_totales_por_hoja_ruta->kg_carga->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->tarifa->Visible) { // tarifa ?>
		<td data-name="tarifa"<?php echo $v_listado_totales_por_hoja_ruta->tarifa->CellAttributes() ?>>
<span<?php echo $v_listado_totales_por_hoja_ruta->tarifa->ViewAttributes() ?>>
<?php echo $v_listado_totales_por_hoja_ruta->tarifa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->sub_total->Visible) { // sub_total ?>
		<td data-name="sub_total"<?php echo $v_listado_totales_por_hoja_ruta->sub_total->CellAttributes() ?>>
<span<?php echo $v_listado_totales_por_hoja_ruta->sub_total->ViewAttributes() ?>>
<?php echo $v_listado_totales_por_hoja_ruta->sub_total->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->porcentaje->Visible) { // porcentaje ?>
		<td data-name="porcentaje"<?php echo $v_listado_totales_por_hoja_ruta->porcentaje->CellAttributes() ?>>
<span<?php echo $v_listado_totales_por_hoja_ruta->porcentaje->ViewAttributes() ?>>
<?php echo $v_listado_totales_por_hoja_ruta->porcentaje->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->comision_chofer->Visible) { // comision_chofer ?>
		<td data-name="comision_chofer"<?php echo $v_listado_totales_por_hoja_ruta->comision_chofer->CellAttributes() ?>>
<span<?php echo $v_listado_totales_por_hoja_ruta->comision_chofer->ViewAttributes() ?>>
<?php echo $v_listado_totales_por_hoja_ruta->comision_chofer->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->adelanto->Visible) { // adelanto ?>
		<td data-name="adelanto"<?php echo $v_listado_totales_por_hoja_ruta->adelanto->CellAttributes() ?>>
<span<?php echo $v_listado_totales_por_hoja_ruta->adelanto->ViewAttributes() ?>>
<?php echo $v_listado_totales_por_hoja_ruta->adelanto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->total->Visible) { // total ?>
		<td data-name="total"<?php echo $v_listado_totales_por_hoja_ruta->total->CellAttributes() ?>>
<span<?php echo $v_listado_totales_por_hoja_ruta->total->ViewAttributes() ?>>
<?php echo $v_listado_totales_por_hoja_ruta->total->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_listado_totales_por_hoja_ruta_list->ListOptions->Render("body", "right", $v_listado_totales_por_hoja_ruta_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($v_listado_totales_por_hoja_ruta->CurrentAction <> "gridadd")
		$v_listado_totales_por_hoja_ruta_list->Recordset->MoveNext();
}
?>
</tbody>
<?php

// Render aggregate row
$v_listado_totales_por_hoja_ruta->RowType = EW_ROWTYPE_AGGREGATE;
$v_listado_totales_por_hoja_ruta->ResetAttrs();
$v_listado_totales_por_hoja_ruta_list->RenderRow();
?>
<?php if ($v_listado_totales_por_hoja_ruta_list->TotalRecs > 0 && ($v_listado_totales_por_hoja_ruta->CurrentAction <> "gridadd" && $v_listado_totales_por_hoja_ruta->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options
$v_listado_totales_por_hoja_ruta_list->RenderListOptions();

// Render list options (footer, left)
$v_listado_totales_por_hoja_ruta_list->ListOptions->Render("footer", "left");
?>
	<?php if ($v_listado_totales_por_hoja_ruta->codigo->Visible) { // codigo ?>
		<td data-name="codigo"><span id="elf_v_listado_totales_por_hoja_ruta_codigo" class="v_listado_totales_por_hoja_ruta_codigo">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->responsable->Visible) { // responsable ?>
		<td data-name="responsable"><span id="elf_v_listado_totales_por_hoja_ruta_responsable" class="v_listado_totales_por_hoja_ruta_responsable">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->Patente->Visible) { // Patente ?>
		<td data-name="Patente"><span id="elf_v_listado_totales_por_hoja_ruta_Patente" class="v_listado_totales_por_hoja_ruta_Patente">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->kg_carga->Visible) { // kg_carga ?>
		<td data-name="kg_carga"><span id="elf_v_listado_totales_por_hoja_ruta_kg_carga" class="v_listado_totales_por_hoja_ruta_kg_carga">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->tarifa->Visible) { // tarifa ?>
		<td data-name="tarifa"><span id="elf_v_listado_totales_por_hoja_ruta_tarifa" class="v_listado_totales_por_hoja_ruta_tarifa">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->sub_total->Visible) { // sub_total ?>
		<td data-name="sub_total"><span id="elf_v_listado_totales_por_hoja_ruta_sub_total" class="v_listado_totales_por_hoja_ruta_sub_total">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $v_listado_totales_por_hoja_ruta->sub_total->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->porcentaje->Visible) { // porcentaje ?>
		<td data-name="porcentaje"><span id="elf_v_listado_totales_por_hoja_ruta_porcentaje" class="v_listado_totales_por_hoja_ruta_porcentaje">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->comision_chofer->Visible) { // comision_chofer ?>
		<td data-name="comision_chofer"><span id="elf_v_listado_totales_por_hoja_ruta_comision_chofer" class="v_listado_totales_por_hoja_ruta_comision_chofer">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $v_listado_totales_por_hoja_ruta->comision_chofer->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->adelanto->Visible) { // adelanto ?>
		<td data-name="adelanto"><span id="elf_v_listado_totales_por_hoja_ruta_adelanto" class="v_listado_totales_por_hoja_ruta_adelanto">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta->total->Visible) { // total ?>
		<td data-name="total"><span id="elf_v_listado_totales_por_hoja_ruta_total" class="v_listado_totales_por_hoja_ruta_total">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $v_listado_totales_por_hoja_ruta->total->ViewValue ?>
		</span></td>
	<?php } ?>
<?php

// Render list options (footer, right)
$v_listado_totales_por_hoja_ruta_list->ListOptions->Render("footer", "right");
?>
	</tr>
</tfoot>	
<?php } ?>
</table>
<?php } ?>
<?php if ($v_listado_totales_por_hoja_ruta->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($v_listado_totales_por_hoja_ruta_list->Recordset)
	$v_listado_totales_por_hoja_ruta_list->Recordset->Close();
?>
<?php if ($v_listado_totales_por_hoja_ruta->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($v_listado_totales_por_hoja_ruta->CurrentAction <> "gridadd" && $v_listado_totales_por_hoja_ruta->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($v_listado_totales_por_hoja_ruta_list->Pager)) $v_listado_totales_por_hoja_ruta_list->Pager = new cNumericPager($v_listado_totales_por_hoja_ruta_list->StartRec, $v_listado_totales_por_hoja_ruta_list->DisplayRecs, $v_listado_totales_por_hoja_ruta_list->TotalRecs, $v_listado_totales_por_hoja_ruta_list->RecRange) ?>
<?php if ($v_listado_totales_por_hoja_ruta_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($v_listado_totales_por_hoja_ruta_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_totales_por_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_totales_por_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($v_listado_totales_por_hoja_ruta_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $v_listado_totales_por_hoja_ruta_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_totales_por_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($v_listado_totales_por_hoja_ruta_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_totales_por_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $v_listado_totales_por_hoja_ruta_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_listado_totales_por_hoja_ruta_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($v_listado_totales_por_hoja_ruta_list->TotalRecs == 0 && $v_listado_totales_por_hoja_ruta->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_listado_totales_por_hoja_ruta_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($v_listado_totales_por_hoja_ruta->Export == "") { ?>
<script type="text/javascript">
fv_listado_totales_por_hoja_rutalistsrch.Init();
fv_listado_totales_por_hoja_rutalist.Init();
</script>
<?php } ?>
<?php
$v_listado_totales_por_hoja_ruta_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($v_listado_totales_por_hoja_ruta->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$v_listado_totales_por_hoja_ruta_list->Page_Terminate();
?>
