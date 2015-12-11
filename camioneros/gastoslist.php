<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "gastosinfo.php" ?>
<?php include_once "hoja_rutasinfo.php" ?>
<?php include_once "tipo_gastosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$gastos_list = NULL; // Initialize page object first

class cgastos_list extends cgastos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'gastos';

	// Page object name
	var $PageObjName = 'gastos_list';

	// Grid form hidden field names
	var $FormName = 'fgastoslist';
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

		// Table object (gastos)
		if (!isset($GLOBALS["gastos"]) || get_class($GLOBALS["gastos"]) == "cgastos") {
			$GLOBALS["gastos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gastos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "gastosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "gastosdelete.php";
		$this->MultiUpdateUrl = "gastosupdate.php";

		// Table object (hoja_rutas)
		if (!isset($GLOBALS['hoja_rutas'])) $GLOBALS['hoja_rutas'] = new choja_rutas();

		// Table object (tipo_gastos)
		if (!isset($GLOBALS['tipo_gastos'])) $GLOBALS['tipo_gastos'] = new ctipo_gastos();

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gastos', TRUE);

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

		// Create form object
		$objForm = new cFormObj();

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
		global $EW_EXPORT, $gastos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($gastos);
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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();
				}
			}

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "hoja_rutas") {
			global $hoja_rutas;
			$rsmaster = $hoja_rutas->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("hoja_rutaslist.php"); // Return to master page
			} else {
				$hoja_rutas->LoadListRowValues($rsmaster);
				$hoja_rutas->RowType = EW_ROWTYPE_MASTER; // Master row
				$hoja_rutas->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "tipo_gastos") {
			global $tipo_gastos;
			$rsmaster = $tipo_gastos->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("tipo_gastoslist.php"); // Return to master page
			} else {
				$tipo_gastos->LoadListRowValues($rsmaster);
				$tipo_gastos->RowType = EW_ROWTYPE_MASTER; // Master row
				$tipo_gastos->RenderListRow();
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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("codigo", ""); // Clear inline edit key
		$this->Importe->FormValue = ""; // Clear form value
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["codigo"] <> "") {
			$this->codigo->setQueryStringValue($_GET["codigo"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("codigo", $this->codigo->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("codigo")) <> strval($this->codigo->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["codigo"] <> "") {
				$this->codigo->setQueryStringValue($_GET["codigo"]);
				$this->setKey("codigo", $this->codigo->CurrentValue); // Set up key
			} else {
				$this->setKey("codigo", ""); // Clear key
				$this->CurrentAction = "add";
			}
		}
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
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
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha
		$this->BuildSearchSql($sWhere, $this->detalles, $Default, FALSE); // detalles
		$this->BuildSearchSql($sWhere, $this->Importe, $Default, FALSE); // Importe
		$this->BuildSearchSql($sWhere, $this->id_tipo_gasto, $Default, FALSE); // id_tipo_gasto
		$this->BuildSearchSql($sWhere, $this->id_hoja_ruta, $Default, FALSE); // id_hoja_ruta

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->codigo->AdvancedSearch->Save(); // codigo
			$this->fecha->AdvancedSearch->Save(); // fecha
			$this->detalles->AdvancedSearch->Save(); // detalles
			$this->Importe->AdvancedSearch->Save(); // Importe
			$this->id_tipo_gasto->AdvancedSearch->Save(); // id_tipo_gasto
			$this->id_hoja_ruta->AdvancedSearch->Save(); // id_hoja_ruta
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
		if ($this->fecha->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->detalles->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Importe->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_tipo_gasto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_hoja_ruta->AdvancedSearch->IssetSession())
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
		$this->fecha->AdvancedSearch->UnsetSession();
		$this->detalles->AdvancedSearch->UnsetSession();
		$this->Importe->AdvancedSearch->UnsetSession();
		$this->id_tipo_gasto->AdvancedSearch->UnsetSession();
		$this->id_hoja_ruta->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->codigo->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->detalles->AdvancedSearch->Load();
		$this->Importe->AdvancedSearch->Load();
		$this->id_tipo_gasto->AdvancedSearch->Load();
		$this->id_hoja_ruta->AdvancedSearch->Load();
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
			$this->UpdateSort($this->Importe); // Importe
			$this->UpdateSort($this->id_tipo_gasto); // id_tipo_gasto
			$this->UpdateSort($this->id_hoja_ruta); // id_hoja_ruta
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
				$this->id_hoja_ruta->setSessionValue("");
				$this->id_tipo_gasto->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->codigo->setSort("");
				$this->fecha->setSort("");
				$this->detalles->setSort("");
				$this->Importe->setSort("");
				$this->id_tipo_gasto->setSort("");
				$this->id_hoja_ruta->setSort("");
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

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanEdit();
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

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->codigo->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . $Language->Phrase("InlineCopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

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
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi update
		$item = &$option->Add("multiupdate");
		$item->Body = "<a class=\"ewAction ewMultiUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitSelected(document.fgastoslist, '" . $this->MultiUpdateUrl . "');return false;\">" . $Language->Phrase("UpdateSelectedLink") . "</a>";
		$item->Visible = ($Security->CanEdit());

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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fgastoslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fgastoslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load default values
	function LoadDefaultValues() {
		$this->codigo->CurrentValue = NULL;
		$this->codigo->OldValue = $this->codigo->CurrentValue;
		$this->fecha->CurrentValue = date('d/m/Y');
		$this->detalles->CurrentValue = NULL;
		$this->detalles->OldValue = $this->detalles->CurrentValue;
		$this->Importe->CurrentValue = NULL;
		$this->Importe->OldValue = $this->Importe->CurrentValue;
		$this->id_tipo_gasto->CurrentValue = NULL;
		$this->id_tipo_gasto->OldValue = $this->id_tipo_gasto->CurrentValue;
		$this->id_hoja_ruta->CurrentValue = NULL;
		$this->id_hoja_ruta->OldValue = $this->id_hoja_ruta->CurrentValue;
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

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha"]);
		if ($this->fecha->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator = @$_GET["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$_GET["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_fecha"]);
		if ($this->fecha->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator2 = @$_GET["w_fecha"];

		// detalles
		$this->detalles->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_detalles"]);
		if ($this->detalles->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->detalles->AdvancedSearch->SearchOperator = @$_GET["z_detalles"];

		// Importe
		$this->Importe->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Importe"]);
		if ($this->Importe->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Importe->AdvancedSearch->SearchOperator = @$_GET["z_Importe"];

		// id_tipo_gasto
		$this->id_tipo_gasto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_tipo_gasto"]);
		if ($this->id_tipo_gasto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_tipo_gasto->AdvancedSearch->SearchOperator = @$_GET["z_id_tipo_gasto"];

		// id_hoja_ruta
		$this->id_hoja_ruta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_hoja_ruta"]);
		if ($this->id_hoja_ruta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_hoja_ruta->AdvancedSearch->SearchOperator = @$_GET["z_id_hoja_ruta"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->codigo->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->codigo->setFormValue($objForm->GetValue("x_codigo"));
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		if (!$this->detalles->FldIsDetailKey) {
			$this->detalles->setFormValue($objForm->GetValue("x_detalles"));
		}
		if (!$this->Importe->FldIsDetailKey) {
			$this->Importe->setFormValue($objForm->GetValue("x_Importe"));
		}
		if (!$this->id_tipo_gasto->FldIsDetailKey) {
			$this->id_tipo_gasto->setFormValue($objForm->GetValue("x_id_tipo_gasto"));
		}
		if (!$this->id_hoja_ruta->FldIsDetailKey) {
			$this->id_hoja_ruta->setFormValue($objForm->GetValue("x_id_hoja_ruta"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->codigo->CurrentValue = $this->codigo->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->detalles->CurrentValue = $this->detalles->FormValue;
		$this->Importe->CurrentValue = $this->Importe->FormValue;
		$this->id_tipo_gasto->CurrentValue = $this->id_tipo_gasto->FormValue;
		$this->id_hoja_ruta->CurrentValue = $this->id_hoja_ruta->FormValue;
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
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->detalles->setDbValue($rs->fields('detalles'));
		$this->Importe->setDbValue($rs->fields('Importe'));
		$this->id_tipo_gasto->setDbValue($rs->fields('id_tipo_gasto'));
		if (array_key_exists('EV__id_tipo_gasto', $rs->fields)) {
			$this->id_tipo_gasto->VirtualValue = $rs->fields('EV__id_tipo_gasto'); // Set up virtual field value
		} else {
			$this->id_tipo_gasto->VirtualValue = ""; // Clear value
		}
		$this->id_hoja_ruta->setDbValue($rs->fields('id_hoja_ruta'));
		if (array_key_exists('EV__id_hoja_ruta', $rs->fields)) {
			$this->id_hoja_ruta->VirtualValue = $rs->fields('EV__id_hoja_ruta'); // Set up virtual field value
		} else {
			$this->id_hoja_ruta->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->fecha->DbValue = $row['fecha'];
		$this->detalles->DbValue = $row['detalles'];
		$this->Importe->DbValue = $row['Importe'];
		$this->id_tipo_gasto->DbValue = $row['id_tipo_gasto'];
		$this->id_hoja_ruta->DbValue = $row['id_hoja_ruta'];
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
		if ($this->Importe->FormValue == $this->Importe->CurrentValue && is_numeric(ew_StrToFloat($this->Importe->CurrentValue)))
			$this->Importe->CurrentValue = ew_StrToFloat($this->Importe->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// fecha
		// detalles
		// Importe
		// id_tipo_gasto
		// id_hoja_ruta

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

			// Importe
			$this->Importe->ViewValue = $this->Importe->CurrentValue;
			$this->Importe->ViewValue = ew_FormatCurrency($this->Importe->ViewValue, 2, 0, 0, -1);
			$this->Importe->ViewCustomAttributes = "";

			// id_tipo_gasto
			if ($this->id_tipo_gasto->VirtualValue <> "") {
				$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->VirtualValue;
			} else {
			if (strval($this->id_tipo_gasto->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='R'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_gasto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_gasto->ViewValue = $rswrk->fields('DispFld');
					$this->id_tipo_gasto->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_gasto) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->CurrentValue;
				}
			} else {
				$this->id_tipo_gasto->ViewValue = NULL;
			}
			}
			$this->id_tipo_gasto->ViewCustomAttributes = "";

			// id_hoja_ruta
			if ($this->id_hoja_ruta->VirtualValue <> "") {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->VirtualValue;
			} else {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
			if (strval($this->id_hoja_ruta->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_hoja_ruta->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hoja_rutas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_hoja_ruta, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `codigo` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_hoja_ruta->ViewValue = $rswrk->fields('DispFld');
					$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(1,$this->id_hoja_ruta) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
					$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(2,$this->id_hoja_ruta) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
				}
			} else {
				$this->id_hoja_ruta->ViewValue = NULL;
			}
			}
			$this->id_hoja_ruta->ViewCustomAttributes = "";

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

			// Importe
			$this->Importe->LinkCustomAttributes = "";
			$this->Importe->HrefValue = "";
			$this->Importe->TooltipValue = "";

			// id_tipo_gasto
			$this->id_tipo_gasto->LinkCustomAttributes = "";
			$this->id_tipo_gasto->HrefValue = "";
			$this->id_tipo_gasto->TooltipValue = "";

			// id_hoja_ruta
			$this->id_hoja_ruta->LinkCustomAttributes = "";
			$this->id_hoja_ruta->HrefValue = "";
			$this->id_hoja_ruta->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// codigo
			// fecha

			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// detalles
			$this->detalles->EditAttrs["class"] = "form-control";
			$this->detalles->EditCustomAttributes = "";
			$this->detalles->EditValue = ew_HtmlEncode($this->detalles->CurrentValue);
			$this->detalles->PlaceHolder = ew_RemoveHtml($this->detalles->FldCaption());

			// Importe
			$this->Importe->EditAttrs["class"] = "form-control";
			$this->Importe->EditCustomAttributes = "";
			$this->Importe->EditValue = ew_HtmlEncode($this->Importe->CurrentValue);
			$this->Importe->PlaceHolder = ew_RemoveHtml($this->Importe->FldCaption());
			if (strval($this->Importe->EditValue) <> "" && is_numeric($this->Importe->EditValue)) $this->Importe->EditValue = ew_FormatNumber($this->Importe->EditValue, -2, 0, 0, -1);

			// id_tipo_gasto
			$this->id_tipo_gasto->EditAttrs["class"] = "form-control";
			$this->id_tipo_gasto->EditCustomAttributes = "";
			if ($this->id_tipo_gasto->getSessionValue() <> "") {
				$this->id_tipo_gasto->CurrentValue = $this->id_tipo_gasto->getSessionValue();
			if ($this->id_tipo_gasto->VirtualValue <> "") {
				$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->VirtualValue;
			} else {
			if (strval($this->id_tipo_gasto->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='R'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_gasto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_gasto->ViewValue = $rswrk->fields('DispFld');
					$this->id_tipo_gasto->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_gasto) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->CurrentValue;
				}
			} else {
				$this->id_tipo_gasto->ViewValue = NULL;
			}
			}
			$this->id_tipo_gasto->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->id_tipo_gasto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='R'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_gasto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_tipo_gasto->EditValue = $arwrk;
			}

			// id_hoja_ruta
			$this->id_hoja_ruta->EditAttrs["class"] = "form-control";
			$this->id_hoja_ruta->EditCustomAttributes = "";
			if ($this->id_hoja_ruta->getSessionValue() <> "") {
				$this->id_hoja_ruta->CurrentValue = $this->id_hoja_ruta->getSessionValue();
			if ($this->id_hoja_ruta->VirtualValue <> "") {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->VirtualValue;
			} else {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
			if (strval($this->id_hoja_ruta->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_hoja_ruta->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hoja_rutas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_hoja_ruta, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `codigo` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_hoja_ruta->ViewValue = $rswrk->fields('DispFld');
					$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(1,$this->id_hoja_ruta) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
					$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(2,$this->id_hoja_ruta) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
				}
			} else {
				$this->id_hoja_ruta->ViewValue = NULL;
			}
			}
			$this->id_hoja_ruta->ViewCustomAttributes = "";
			} else {
			$this->id_hoja_ruta->EditValue = ew_HtmlEncode($this->id_hoja_ruta->CurrentValue);
			$this->id_hoja_ruta->PlaceHolder = ew_RemoveHtml($this->id_hoja_ruta->FldCaption());
			}

			// Edit refer script
			// codigo

			$this->codigo->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// detalles
			$this->detalles->HrefValue = "";

			// Importe
			$this->Importe->HrefValue = "";

			// id_tipo_gasto
			$this->id_tipo_gasto->HrefValue = "";

			// id_hoja_ruta
			$this->id_hoja_ruta->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// codigo
			$this->codigo->EditAttrs["class"] = "form-control";
			$this->codigo->EditCustomAttributes = "";
			$this->codigo->EditValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// detalles
			$this->detalles->EditAttrs["class"] = "form-control";
			$this->detalles->EditCustomAttributes = "";
			$this->detalles->EditValue = ew_HtmlEncode($this->detalles->CurrentValue);
			$this->detalles->PlaceHolder = ew_RemoveHtml($this->detalles->FldCaption());

			// Importe
			$this->Importe->EditAttrs["class"] = "form-control";
			$this->Importe->EditCustomAttributes = "";
			$this->Importe->EditValue = ew_HtmlEncode($this->Importe->CurrentValue);
			$this->Importe->PlaceHolder = ew_RemoveHtml($this->Importe->FldCaption());
			if (strval($this->Importe->EditValue) <> "" && is_numeric($this->Importe->EditValue)) $this->Importe->EditValue = ew_FormatNumber($this->Importe->EditValue, -2, 0, 0, -1);

			// id_tipo_gasto
			$this->id_tipo_gasto->EditAttrs["class"] = "form-control";
			$this->id_tipo_gasto->EditCustomAttributes = "";
			if ($this->id_tipo_gasto->getSessionValue() <> "") {
				$this->id_tipo_gasto->CurrentValue = $this->id_tipo_gasto->getSessionValue();
			if ($this->id_tipo_gasto->VirtualValue <> "") {
				$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->VirtualValue;
			} else {
			if (strval($this->id_tipo_gasto->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='R'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_gasto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_gasto->ViewValue = $rswrk->fields('DispFld');
					$this->id_tipo_gasto->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_gasto) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->CurrentValue;
				}
			} else {
				$this->id_tipo_gasto->ViewValue = NULL;
			}
			}
			$this->id_tipo_gasto->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->id_tipo_gasto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='R'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_gasto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_tipo_gasto->EditValue = $arwrk;
			}

			// id_hoja_ruta
			$this->id_hoja_ruta->EditAttrs["class"] = "form-control";
			$this->id_hoja_ruta->EditCustomAttributes = "";
			if ($this->id_hoja_ruta->getSessionValue() <> "") {
				$this->id_hoja_ruta->CurrentValue = $this->id_hoja_ruta->getSessionValue();
			if ($this->id_hoja_ruta->VirtualValue <> "") {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->VirtualValue;
			} else {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
			if (strval($this->id_hoja_ruta->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_hoja_ruta->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hoja_rutas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_hoja_ruta, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `codigo` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_hoja_ruta->ViewValue = $rswrk->fields('DispFld');
					$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(1,$this->id_hoja_ruta) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
					$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(2,$this->id_hoja_ruta) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
				}
			} else {
				$this->id_hoja_ruta->ViewValue = NULL;
			}
			}
			$this->id_hoja_ruta->ViewCustomAttributes = "";
			} else {
			$this->id_hoja_ruta->EditValue = ew_HtmlEncode($this->id_hoja_ruta->CurrentValue);
			$this->id_hoja_ruta->PlaceHolder = ew_RemoveHtml($this->id_hoja_ruta->FldCaption());
			}

			// Edit refer script
			// codigo

			$this->codigo->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// detalles
			$this->detalles->HrefValue = "";

			// Importe
			$this->Importe->HrefValue = "";

			// id_tipo_gasto
			$this->id_tipo_gasto->HrefValue = "";

			// id_hoja_ruta
			$this->id_hoja_ruta->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// codigo
			$this->codigo->EditAttrs["class"] = "form-control";
			$this->codigo->EditCustomAttributes = "";
			$this->codigo->EditValue = ew_HtmlEncode($this->codigo->AdvancedSearch->SearchValue);
			$this->codigo->PlaceHolder = ew_RemoveHtml($this->codigo->FldCaption());

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue2, 7), 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// detalles
			$this->detalles->EditAttrs["class"] = "form-control";
			$this->detalles->EditCustomAttributes = "";
			$this->detalles->EditValue = ew_HtmlEncode($this->detalles->AdvancedSearch->SearchValue);
			$this->detalles->PlaceHolder = ew_RemoveHtml($this->detalles->FldCaption());

			// Importe
			$this->Importe->EditAttrs["class"] = "form-control";
			$this->Importe->EditCustomAttributes = "";
			$this->Importe->EditValue = ew_HtmlEncode($this->Importe->AdvancedSearch->SearchValue);
			$this->Importe->PlaceHolder = ew_RemoveHtml($this->Importe->FldCaption());

			// id_tipo_gasto
			$this->id_tipo_gasto->EditAttrs["class"] = "form-control";
			$this->id_tipo_gasto->EditCustomAttributes = "";
			$this->id_tipo_gasto->EditValue = ew_HtmlEncode($this->id_tipo_gasto->AdvancedSearch->SearchValue);
			$this->id_tipo_gasto->PlaceHolder = ew_RemoveHtml($this->id_tipo_gasto->FldCaption());

			// id_hoja_ruta
			$this->id_hoja_ruta->EditAttrs["class"] = "form-control";
			$this->id_hoja_ruta->EditCustomAttributes = "";
			$this->id_hoja_ruta->EditValue = ew_HtmlEncode($this->id_hoja_ruta->AdvancedSearch->SearchValue);
			$this->id_hoja_ruta->PlaceHolder = ew_RemoveHtml($this->id_hoja_ruta->FldCaption());
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
		if (!ew_CheckEuroDate($this->fecha->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->fecha->FldErrMsg());
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->codigo->FormValue)) {
			ew_AddMessage($gsFormError, $this->codigo->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckNumber($this->Importe->FormValue)) {
			ew_AddMessage($gsFormError, $this->Importe->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), NULL, $this->fecha->ReadOnly);

			// detalles
			$this->detalles->SetDbValueDef($rsnew, $this->detalles->CurrentValue, NULL, $this->detalles->ReadOnly);

			// Importe
			$this->Importe->SetDbValueDef($rsnew, $this->Importe->CurrentValue, NULL, $this->Importe->ReadOnly);

			// id_tipo_gasto
			$this->id_tipo_gasto->SetDbValueDef($rsnew, $this->id_tipo_gasto->CurrentValue, NULL, $this->id_tipo_gasto->ReadOnly);

			// id_hoja_ruta
			$this->id_hoja_ruta->SetDbValueDef($rsnew, $this->id_hoja_ruta->CurrentValue, NULL, $this->id_hoja_ruta->ReadOnly);

			// Check referential integrity for master table 'hoja_rutas'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_hoja_rutas();
			$KeyValue = isset($rsnew['id_hoja_ruta']) ? $rsnew['id_hoja_ruta'] : $rsold['id_hoja_ruta'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@codigo@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["hoja_rutas"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "hoja_rutas", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

			// Check referential integrity for master table 'tipo_gastos'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_tipo_gastos();
			$KeyValue = isset($rsnew['id_tipo_gasto']) ? $rsnew['id_tipo_gasto'] : $rsold['id_tipo_gasto'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@codigo@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["tipo_gastos"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "tipo_gastos", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Check referential integrity for master table 'hoja_rutas'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_hoja_rutas();
		if (strval($this->id_hoja_ruta->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@codigo@", ew_AdjustSql($this->id_hoja_ruta->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["hoja_rutas"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "hoja_rutas", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Check referential integrity for master table 'tipo_gastos'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_tipo_gastos();
		if (strval($this->id_tipo_gasto->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@codigo@", ew_AdjustSql($this->id_tipo_gasto->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["tipo_gastos"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "tipo_gastos", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// fecha
		$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), NULL, FALSE);

		// detalles
		$this->detalles->SetDbValueDef($rsnew, $this->detalles->CurrentValue, NULL, FALSE);

		// Importe
		$this->Importe->SetDbValueDef($rsnew, $this->Importe->CurrentValue, NULL, FALSE);

		// id_tipo_gasto
		$this->id_tipo_gasto->SetDbValueDef($rsnew, $this->id_tipo_gasto->CurrentValue, NULL, FALSE);

		// id_hoja_ruta
		$this->id_hoja_ruta->SetDbValueDef($rsnew, $this->id_hoja_ruta->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->codigo->setDbValue($conn->Insert_ID());
			$rsnew['codigo'] = $this->codigo->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->codigo->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->detalles->AdvancedSearch->Load();
		$this->Importe->AdvancedSearch->Load();
		$this->id_tipo_gasto->AdvancedSearch->Load();
		$this->id_hoja_ruta->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_gastos\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_gastos',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fgastoslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "hoja_rutas") {
			global $hoja_rutas;
			if (!isset($hoja_rutas)) $hoja_rutas = new choja_rutas;
			$rsmaster = $hoja_rutas->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$hoja_rutas;
					$hoja_rutas->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "tipo_gastos") {
			global $tipo_gastos;
			if (!isset($tipo_gastos)) $tipo_gastos = new ctipo_gastos;
			$rsmaster = $tipo_gastos->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$tipo_gastos;
					$tipo_gastos->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
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
			if ($sMasterTblVar == "hoja_rutas") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_codigo"] <> "") {
					$GLOBALS["hoja_rutas"]->codigo->setQueryStringValue($_GET["fk_codigo"]);
					$this->id_hoja_ruta->setQueryStringValue($GLOBALS["hoja_rutas"]->codigo->QueryStringValue);
					$this->id_hoja_ruta->setSessionValue($this->id_hoja_ruta->QueryStringValue);
					if (!is_numeric($GLOBALS["hoja_rutas"]->codigo->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "tipo_gastos") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_codigo"] <> "") {
					$GLOBALS["tipo_gastos"]->codigo->setQueryStringValue($_GET["fk_codigo"]);
					$this->id_tipo_gasto->setQueryStringValue($GLOBALS["tipo_gastos"]->codigo->QueryStringValue);
					$this->id_tipo_gasto->setSessionValue($this->id_tipo_gasto->QueryStringValue);
					if (!is_numeric($GLOBALS["tipo_gastos"]->codigo->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "hoja_rutas") {
				if ($this->id_hoja_ruta->QueryStringValue == "") $this->id_hoja_ruta->setSessionValue("");
			}
			if ($sMasterTblVar <> "tipo_gastos") {
				if ($this->id_tipo_gasto->QueryStringValue == "") $this->id_tipo_gasto->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($gastos_list)) $gastos_list = new cgastos_list();

// Page init
$gastos_list->Page_Init();

// Page main
$gastos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gastos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($gastos->Export == "") { ?>
<script type="text/javascript">

// Page object
var gastos_list = new ew_Page("gastos_list");
gastos_list.PageID = "list"; // Page ID
var EW_PAGE_ID = gastos_list.PageID; // For backward compatibility

// Form object
var fgastoslist = new ew_Form("fgastoslist");
fgastoslist.FormKeyCountName = '<?php echo $gastos_list->FormKeyCountName ?>';

// Validate form
fgastoslist.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_codigo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos->codigo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Importe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos->Importe->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fgastoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgastoslist.ValidateRequired = true;
<?php } else { ?>
fgastoslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgastoslist.Lists["x_id_tipo_gasto"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_tipo_gasto","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgastoslist.Lists["x_id_hoja_ruta"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_fecha_ini","x_Origen",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fgastoslistsrch = new ew_Form("fgastoslistsrch");

// Validate function for search
fgastoslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_fecha");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($gastos->fecha->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fgastoslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgastoslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fgastoslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($gastos->Export == "") { ?>
<div class="ewToolbar">
<?php if ($gastos->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($gastos_list->TotalRecs > 0 && $gastos_list->ExportOptions->Visible()) { ?>
<?php $gastos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($gastos_list->SearchOptions->Visible()) { ?>
<?php $gastos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($gastos->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($gastos->Export == "") || (EW_EXPORT_MASTER_RECORD && $gastos->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "hoja_rutaslist.php";
if ($gastos_list->DbMasterFilter <> "" && $gastos->getCurrentMasterTable() == "hoja_rutas") {
	if ($gastos_list->MasterRecordExists) {
		if ($gastos->getCurrentMasterTable() == $gastos->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include_once "hoja_rutasmaster.php" ?>
<?php
	}
}
?>
<?php
$gsMasterReturnUrl = "tipo_gastoslist.php";
if ($gastos_list->DbMasterFilter <> "" && $gastos->getCurrentMasterTable() == "tipo_gastos") {
	if ($gastos_list->MasterRecordExists) {
		if ($gastos->getCurrentMasterTable() == $gastos->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include_once "tipo_gastosmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($gastos_list->TotalRecs <= 0)
			$gastos_list->TotalRecs = $gastos->SelectRecordCount();
	} else {
		if (!$gastos_list->Recordset && ($gastos_list->Recordset = $gastos_list->LoadRecordset()))
			$gastos_list->TotalRecs = $gastos_list->Recordset->RecordCount();
	}
	$gastos_list->StartRec = 1;
	if ($gastos_list->DisplayRecs <= 0 || ($gastos->Export <> "" && $gastos->ExportAll)) // Display all records
		$gastos_list->DisplayRecs = $gastos_list->TotalRecs;
	if (!($gastos->Export <> "" && $gastos->ExportAll))
		$gastos_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$gastos_list->Recordset = $gastos_list->LoadRecordset($gastos_list->StartRec-1, $gastos_list->DisplayRecs);

	// Set no record found message
	if ($gastos->CurrentAction == "" && $gastos_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$gastos_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($gastos_list->SearchWhere == "0=101")
			$gastos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$gastos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$gastos_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($gastos->Export == "" && $gastos->CurrentAction == "") { ?>
<form name="fgastoslistsrch" id="fgastoslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($gastos_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fgastoslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="gastos">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$gastos_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$gastos->RowType = EW_ROWTYPE_SEARCH;

// Render row
$gastos->ResetAttrs();
$gastos_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($gastos->fecha->Visible) { // fecha ?>
	<div id="xsc_fecha" class="ewCell form-group">
		<label for="x_fecha" class="ewSearchCaption ewLabel"><?php echo $gastos->fecha->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_fecha" id="z_fecha" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($gastos->fecha->PlaceHolder) ?>" value="<?php echo $gastos->fecha->EditValue ?>"<?php echo $gastos->fecha->EditAttributes() ?>>
<?php if (!$gastos->fecha->ReadOnly && !$gastos->fecha->Disabled && !isset($gastos->fecha->EditAttrs["readonly"]) && !isset($gastos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastoslistsrch", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_fecha">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_fecha">
<input type="text" data-field="x_fecha" name="y_fecha" id="y_fecha" placeholder="<?php echo ew_HtmlEncode($gastos->fecha->PlaceHolder) ?>" value="<?php echo $gastos->fecha->EditValue2 ?>"<?php echo $gastos->fecha->EditAttributes() ?>>
<?php if (!$gastos->fecha->ReadOnly && !$gastos->fecha->Disabled && !isset($gastos->fecha->EditAttrs["readonly"]) && !isset($gastos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastoslistsrch", "y_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($gastos_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($gastos_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $gastos_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($gastos_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($gastos_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($gastos_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($gastos_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $gastos_list->ShowPageHeader(); ?>
<?php
$gastos_list->ShowMessage();
?>
<?php if ($gastos_list->TotalRecs > 0 || $gastos->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($gastos->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($gastos->CurrentAction <> "gridadd" && $gastos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($gastos_list->Pager)) $gastos_list->Pager = new cNumericPager($gastos_list->StartRec, $gastos_list->DisplayRecs, $gastos_list->TotalRecs, $gastos_list->RecRange) ?>
<?php if ($gastos_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($gastos_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $gastos_list->PageUrl() ?>start=<?php echo $gastos_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($gastos_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $gastos_list->PageUrl() ?>start=<?php echo $gastos_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($gastos_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $gastos_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($gastos_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $gastos_list->PageUrl() ?>start=<?php echo $gastos_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($gastos_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $gastos_list->PageUrl() ?>start=<?php echo $gastos_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $gastos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $gastos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $gastos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($gastos_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fgastoslist" id="fgastoslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($gastos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $gastos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="gastos">
<div id="gmp_gastos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($gastos_list->TotalRecs > 0 || $gastos->CurrentAction == "add" || $gastos->CurrentAction == "copy") { ?>
<table id="tbl_gastoslist" class="table ewTable">
<?php echo $gastos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$gastos->RowType = EW_ROWTYPE_HEADER;

// Render list options
$gastos_list->RenderListOptions();

// Render list options (header, left)
$gastos_list->ListOptions->Render("header", "left");
?>
<?php if ($gastos->codigo->Visible) { // codigo ?>
	<?php if ($gastos->SortUrl($gastos->codigo) == "") { ?>
		<th data-name="codigo"><div id="elh_gastos_codigo" class="gastos_codigo"><div class="ewTableHeaderCaption"><?php echo $gastos->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gastos->SortUrl($gastos->codigo) ?>',1);"><div id="elh_gastos_codigo" class="gastos_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos->fecha->Visible) { // fecha ?>
	<?php if ($gastos->SortUrl($gastos->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_gastos_fecha" class="gastos_fecha"><div class="ewTableHeaderCaption"><?php echo $gastos->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gastos->SortUrl($gastos->fecha) ?>',1);"><div id="elh_gastos_fecha" class="gastos_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos->detalles->Visible) { // detalles ?>
	<?php if ($gastos->SortUrl($gastos->detalles) == "") { ?>
		<th data-name="detalles"><div id="elh_gastos_detalles" class="gastos_detalles"><div class="ewTableHeaderCaption"><?php echo $gastos->detalles->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="detalles"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gastos->SortUrl($gastos->detalles) ?>',1);"><div id="elh_gastos_detalles" class="gastos_detalles">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->detalles->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($gastos->detalles->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->detalles->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos->Importe->Visible) { // Importe ?>
	<?php if ($gastos->SortUrl($gastos->Importe) == "") { ?>
		<th data-name="Importe"><div id="elh_gastos_Importe" class="gastos_Importe"><div class="ewTableHeaderCaption"><?php echo $gastos->Importe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Importe"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gastos->SortUrl($gastos->Importe) ?>',1);"><div id="elh_gastos_Importe" class="gastos_Importe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->Importe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->Importe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->Importe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
	<?php if ($gastos->SortUrl($gastos->id_tipo_gasto) == "") { ?>
		<th data-name="id_tipo_gasto"><div id="elh_gastos_id_tipo_gasto" class="gastos_id_tipo_gasto"><div class="ewTableHeaderCaption"><?php echo $gastos->id_tipo_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipo_gasto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gastos->SortUrl($gastos->id_tipo_gasto) ?>',1);"><div id="elh_gastos_id_tipo_gasto" class="gastos_id_tipo_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->id_tipo_gasto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->id_tipo_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->id_tipo_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos->id_hoja_ruta->Visible) { // id_hoja_ruta ?>
	<?php if ($gastos->SortUrl($gastos->id_hoja_ruta) == "") { ?>
		<th data-name="id_hoja_ruta"><div id="elh_gastos_id_hoja_ruta" class="gastos_id_hoja_ruta"><div class="ewTableHeaderCaption"><?php echo $gastos->id_hoja_ruta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_hoja_ruta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gastos->SortUrl($gastos->id_hoja_ruta) ?>',1);"><div id="elh_gastos_id_hoja_ruta" class="gastos_id_hoja_ruta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->id_hoja_ruta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->id_hoja_ruta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->id_hoja_ruta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$gastos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($gastos->CurrentAction == "add" || $gastos->CurrentAction == "copy") {
		$gastos_list->RowIndex = 0;
		$gastos_list->KeyCount = $gastos_list->RowIndex;
		if ($gastos->CurrentAction == "copy" && !$gastos_list->LoadRow())
				$gastos->CurrentAction = "add";
		if ($gastos->CurrentAction == "add")
			$gastos_list->LoadDefaultValues();
		if ($gastos->EventCancelled) // Insert failed
			$gastos_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$gastos->ResetAttrs();
		$gastos->RowAttrs = array_merge($gastos->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_gastos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$gastos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$gastos_list->RenderRow();

		// Render list options
		$gastos_list->RenderListOptions();
		$gastos_list->StartRowCnt = 0;
?>
	<tr<?php echo $gastos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gastos_list->ListOptions->Render("body", "left", $gastos_list->RowCnt);
?>
	<?php if ($gastos->codigo->Visible) { // codigo ?>
		<td data-name="codigo">
<input type="hidden" data-field="x_codigo" name="o<?php echo $gastos_list->RowIndex ?>_codigo" id="o<?php echo $gastos_list->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos->codigo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos->fecha->Visible) { // fecha ?>
		<td data-name="fecha">
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_fecha" class="form-group gastos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $gastos_list->RowIndex ?>_fecha" id="x<?php echo $gastos_list->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($gastos->fecha->PlaceHolder) ?>" value="<?php echo $gastos->fecha->EditValue ?>"<?php echo $gastos->fecha->EditAttributes() ?>>
<?php if (!$gastos->fecha->ReadOnly && !$gastos->fecha->Disabled && !isset($gastos->fecha->EditAttrs["readonly"]) && !isset($gastos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastoslist", "x<?php echo $gastos_list->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $gastos_list->RowIndex ?>_fecha" id="o<?php echo $gastos_list->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos->detalles->Visible) { // detalles ?>
		<td data-name="detalles">
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_detalles" class="form-group gastos_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $gastos_list->RowIndex ?>_detalles" id="x<?php echo $gastos_list->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos->detalles->PlaceHolder) ?>" value="<?php echo $gastos->detalles->EditValue ?>"<?php echo $gastos->detalles->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_detalles" name="o<?php echo $gastos_list->RowIndex ?>_detalles" id="o<?php echo $gastos_list->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($gastos->detalles->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos->Importe->Visible) { // Importe ?>
		<td data-name="Importe">
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_Importe" class="form-group gastos_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $gastos_list->RowIndex ?>_Importe" id="x<?php echo $gastos_list->RowIndex ?>_Importe" size="20" placeholder="<?php echo ew_HtmlEncode($gastos->Importe->PlaceHolder) ?>" value="<?php echo $gastos->Importe->EditValue ?>"<?php echo $gastos->Importe->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Importe" name="o<?php echo $gastos_list->RowIndex ?>_Importe" id="o<?php echo $gastos_list->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($gastos->Importe->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td data-name="id_tipo_gasto">
<?php if ($gastos->id_tipo_gasto->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<span<?php echo $gastos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<select data-field="x_id_tipo_gasto" id="x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto"<?php echo $gastos->id_tipo_gasto->EditAttributes() ?>>
<?php
if (is_array($gastos->id_tipo_gasto->EditValue)) {
	$arwrk = $gastos->id_tipo_gasto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gastos->id_tipo_gasto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$gastos->id_tipo_gasto) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $gastos->id_tipo_gasto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto',url:'tipo_gastosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $gastos->id_tipo_gasto->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
$sWhereWrk = "";
$lookuptblfilter = "`clase`='R'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$gastos->Lookup_Selecting($gastos->id_tipo_gasto, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
?>
<input type="hidden" name="s_x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" id="s_x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" id="o<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos->id_hoja_ruta->Visible) { // id_hoja_ruta ?>
		<td data-name="id_hoja_ruta">
<?php if ($gastos->id_hoja_ruta->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<span<?php echo $gastos->id_hoja_ruta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_hoja_ruta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" name="x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<?php
	$wrkonchange = trim(" " . @$gastos->id_hoja_ruta->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gastos->id_hoja_ruta->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" style="white-space: nowrap; z-index: <?php echo (9000 - $gastos_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" id="sv_x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" value="<?php echo $gastos->id_hoja_ruta->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->PlaceHolder) ?>"<?php echo $gastos->id_hoja_ruta->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_hoja_ruta" name="x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" id="x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld` FROM `hoja_rutas`";
$sWhereWrk = "`codigo` LIKE '{query_value}%' OR CONCAT(`codigo`,'" . ew_ValueSeparator(1, $Page->id_hoja_ruta) . "',DATE_FORMAT(`fecha_ini`, '%d/%m/%Y'),'" . ew_ValueSeparator(2, $Page->id_hoja_ruta) . "',`Origen`) LIKE '{query_value}%'";

// Call Lookup selecting
$gastos->Lookup_Selecting($gastos->id_hoja_ruta, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `codigo` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" id="q_x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fgastoslist.CreateAutoSuggest("x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta", false);
</script>
</span>
<?php } ?>
<input type="hidden" data-field="x_id_hoja_ruta" name="o<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" id="o<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$gastos_list->ListOptions->Render("body", "right", $gastos_list->RowCnt);
?>
<script type="text/javascript">
fgastoslist.UpdateOpts(<?php echo $gastos_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($gastos->ExportAll && $gastos->Export <> "") {
	$gastos_list->StopRec = $gastos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($gastos_list->TotalRecs > $gastos_list->StartRec + $gastos_list->DisplayRecs - 1)
		$gastos_list->StopRec = $gastos_list->StartRec + $gastos_list->DisplayRecs - 1;
	else
		$gastos_list->StopRec = $gastos_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($gastos_list->FormKeyCountName) && ($gastos->CurrentAction == "gridadd" || $gastos->CurrentAction == "gridedit" || $gastos->CurrentAction == "F")) {
		$gastos_list->KeyCount = $objForm->GetValue($gastos_list->FormKeyCountName);
		$gastos_list->StopRec = $gastos_list->StartRec + $gastos_list->KeyCount - 1;
	}
}
$gastos_list->RecCnt = $gastos_list->StartRec - 1;
if ($gastos_list->Recordset && !$gastos_list->Recordset->EOF) {
	$gastos_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $gastos_list->StartRec > 1)
		$gastos_list->Recordset->Move($gastos_list->StartRec - 1);
} elseif (!$gastos->AllowAddDeleteRow && $gastos_list->StopRec == 0) {
	$gastos_list->StopRec = $gastos->GridAddRowCount;
}

// Initialize aggregate
$gastos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$gastos->ResetAttrs();
$gastos_list->RenderRow();
$gastos_list->EditRowCnt = 0;
if ($gastos->CurrentAction == "edit")
	$gastos_list->RowIndex = 1;
while ($gastos_list->RecCnt < $gastos_list->StopRec) {
	$gastos_list->RecCnt++;
	if (intval($gastos_list->RecCnt) >= intval($gastos_list->StartRec)) {
		$gastos_list->RowCnt++;

		// Set up key count
		$gastos_list->KeyCount = $gastos_list->RowIndex;

		// Init row class and style
		$gastos->ResetAttrs();
		$gastos->CssClass = "";
		if ($gastos->CurrentAction == "gridadd") {
			$gastos_list->LoadDefaultValues(); // Load default values
		} else {
			$gastos_list->LoadRowValues($gastos_list->Recordset); // Load row values
		}
		$gastos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($gastos->CurrentAction == "edit") {
			if ($gastos_list->CheckInlineEditKey() && $gastos_list->EditRowCnt == 0) { // Inline edit
				$gastos->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($gastos->CurrentAction == "edit" && $gastos->RowType == EW_ROWTYPE_EDIT && $gastos->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$gastos_list->RestoreFormValues(); // Restore form values
		}
		if ($gastos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$gastos_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$gastos->RowAttrs = array_merge($gastos->RowAttrs, array('data-rowindex'=>$gastos_list->RowCnt, 'id'=>'r' . $gastos_list->RowCnt . '_gastos', 'data-rowtype'=>$gastos->RowType));

		// Render row
		$gastos_list->RenderRow();

		// Render list options
		$gastos_list->RenderListOptions();
?>
	<tr<?php echo $gastos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gastos_list->ListOptions->Render("body", "left", $gastos_list->RowCnt);
?>
	<?php if ($gastos->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $gastos->codigo->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_codigo" class="form-group gastos_codigo">
<span<?php echo $gastos->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->codigo->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $gastos_list->RowIndex ?>_codigo" id="x<?php echo $gastos_list->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos->codigo->CurrentValue) ?>">
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->codigo->ViewAttributes() ?>>
<?php echo $gastos->codigo->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $gastos_list->PageObjName . "_row_" . $gastos_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gastos->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $gastos->fecha->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_fecha" class="form-group gastos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $gastos_list->RowIndex ?>_fecha" id="x<?php echo $gastos_list->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($gastos->fecha->PlaceHolder) ?>" value="<?php echo $gastos->fecha->EditValue ?>"<?php echo $gastos->fecha->EditAttributes() ?>>
<?php if (!$gastos->fecha->ReadOnly && !$gastos->fecha->Disabled && !isset($gastos->fecha->EditAttrs["readonly"]) && !isset($gastos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastoslist", "x<?php echo $gastos_list->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->fecha->ViewAttributes() ?>>
<?php echo $gastos->fecha->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos->detalles->Visible) { // detalles ?>
		<td data-name="detalles"<?php echo $gastos->detalles->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_detalles" class="form-group gastos_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $gastos_list->RowIndex ?>_detalles" id="x<?php echo $gastos_list->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos->detalles->PlaceHolder) ?>" value="<?php echo $gastos->detalles->EditValue ?>"<?php echo $gastos->detalles->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->detalles->ViewAttributes() ?>>
<?php echo $gastos->detalles->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos->Importe->Visible) { // Importe ?>
		<td data-name="Importe"<?php echo $gastos->Importe->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_Importe" class="form-group gastos_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $gastos_list->RowIndex ?>_Importe" id="x<?php echo $gastos_list->RowIndex ?>_Importe" size="20" placeholder="<?php echo ew_HtmlEncode($gastos->Importe->PlaceHolder) ?>" value="<?php echo $gastos->Importe->EditValue ?>"<?php echo $gastos->Importe->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->Importe->ViewAttributes() ?>>
<?php echo $gastos->Importe->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td data-name="id_tipo_gasto"<?php echo $gastos->id_tipo_gasto->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($gastos->id_tipo_gasto->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<span<?php echo $gastos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<select data-field="x_id_tipo_gasto" id="x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto"<?php echo $gastos->id_tipo_gasto->EditAttributes() ?>>
<?php
if (is_array($gastos->id_tipo_gasto->EditValue)) {
	$arwrk = $gastos->id_tipo_gasto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gastos->id_tipo_gasto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$gastos->id_tipo_gasto) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $gastos->id_tipo_gasto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto',url:'tipo_gastosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $gastos->id_tipo_gasto->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
$sWhereWrk = "";
$lookuptblfilter = "`clase`='R'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$gastos->Lookup_Selecting($gastos->id_tipo_gasto, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
?>
<input type="hidden" name="s_x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" id="s_x<?php echo $gastos_list->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->id_tipo_gasto->ViewAttributes() ?>>
<?php echo $gastos->id_tipo_gasto->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos->id_hoja_ruta->Visible) { // id_hoja_ruta ?>
		<td data-name="id_hoja_ruta"<?php echo $gastos->id_hoja_ruta->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($gastos->id_hoja_ruta->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<span<?php echo $gastos->id_hoja_ruta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_hoja_ruta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" name="x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_list->RowCnt ?>_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<?php
	$wrkonchange = trim(" " . @$gastos->id_hoja_ruta->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gastos->id_hoja_ruta->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" style="white-space: nowrap; z-index: <?php echo (9000 - $gastos_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" id="sv_x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" value="<?php echo $gastos->id_hoja_ruta->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->PlaceHolder) ?>"<?php echo $gastos->id_hoja_ruta->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_hoja_ruta" name="x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" id="x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld` FROM `hoja_rutas`";
$sWhereWrk = "`codigo` LIKE '{query_value}%' OR CONCAT(`codigo`,'" . ew_ValueSeparator(1, $Page->id_hoja_ruta) . "',DATE_FORMAT(`fecha_ini`, '%d/%m/%Y'),'" . ew_ValueSeparator(2, $Page->id_hoja_ruta) . "',`Origen`) LIKE '{query_value}%'";

// Call Lookup selecting
$gastos->Lookup_Selecting($gastos->id_hoja_ruta, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `codigo` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" id="q_x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fgastoslist.CreateAutoSuggest("x<?php echo $gastos_list->RowIndex ?>_id_hoja_ruta", false);
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->id_hoja_ruta->ViewAttributes() ?>>
<?php echo $gastos->id_hoja_ruta->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$gastos_list->ListOptions->Render("body", "right", $gastos_list->RowCnt);
?>
	</tr>
<?php if ($gastos->RowType == EW_ROWTYPE_ADD || $gastos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fgastoslist.UpdateOpts(<?php echo $gastos_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	if ($gastos->CurrentAction <> "gridadd")
		$gastos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($gastos->CurrentAction == "add" || $gastos->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $gastos_list->FormKeyCountName ?>" id="<?php echo $gastos_list->FormKeyCountName ?>" value="<?php echo $gastos_list->KeyCount ?>">
<?php } ?>
<?php if ($gastos->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $gastos_list->FormKeyCountName ?>" id="<?php echo $gastos_list->FormKeyCountName ?>" value="<?php echo $gastos_list->KeyCount ?>">
<?php } ?>
<?php if ($gastos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($gastos_list->Recordset)
	$gastos_list->Recordset->Close();
?>
<?php if ($gastos->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($gastos->CurrentAction <> "gridadd" && $gastos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($gastos_list->Pager)) $gastos_list->Pager = new cNumericPager($gastos_list->StartRec, $gastos_list->DisplayRecs, $gastos_list->TotalRecs, $gastos_list->RecRange) ?>
<?php if ($gastos_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($gastos_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $gastos_list->PageUrl() ?>start=<?php echo $gastos_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($gastos_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $gastos_list->PageUrl() ?>start=<?php echo $gastos_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($gastos_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $gastos_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($gastos_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $gastos_list->PageUrl() ?>start=<?php echo $gastos_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($gastos_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $gastos_list->PageUrl() ?>start=<?php echo $gastos_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $gastos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $gastos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $gastos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($gastos_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($gastos_list->TotalRecs == 0 && $gastos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($gastos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($gastos->Export == "") { ?>
<script type="text/javascript">
fgastoslistsrch.Init();
fgastoslist.Init();
</script>
<?php } ?>
<?php
$gastos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($gastos->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$gastos_list->Page_Terminate();
?>
