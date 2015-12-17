<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_sociosinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_socios_detallesgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_deudasgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_socios_cuotasgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$socios_list = NULL; // Initialize page object first

class csocios_list extends csocios {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'socios';

	// Page object name
	var $PageObjName = 'socios_list';

	// Grid form hidden field names
	var $FormName = 'fsocioslist';
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
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;

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

		// Table object (socios)
		if (!isset($GLOBALS["socios"]) || get_class($GLOBALS["socios"]) == "csocios") {
			$GLOBALS["socios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["socios"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "cciag_sociosadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "cciag_sociosdelete.php";
		$this->MultiUpdateUrl = "cciag_sociosupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'socios', TRUE);

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
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate();
		}

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
		$this->socio_nro->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

			// Process auto fill for detail table 'socios_detalles'
			if (@$_POST["grid"] == "fsocios_detallesgrid") {
				if (!isset($GLOBALS["socios_detalles_grid"])) $GLOBALS["socios_detalles_grid"] = new csocios_detalles_grid;
				$GLOBALS["socios_detalles_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'deudas'
			if (@$_POST["grid"] == "fdeudasgrid") {
				if (!isset($GLOBALS["deudas_grid"])) $GLOBALS["deudas_grid"] = new cdeudas_grid;
				$GLOBALS["deudas_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'socios_cuotas'
			if (@$_POST["grid"] == "fsocios_cuotasgrid") {
				if (!isset($GLOBALS["socios_cuotas_grid"])) $GLOBALS["socios_cuotas_grid"] = new csocios_cuotas_grid;
				$GLOBALS["socios_cuotas_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $socios;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($socios);
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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("socio_nro", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("cciag_login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["socio_nro"] <> "") {
			$this->socio_nro->setQueryStringValue($_GET["socio_nro"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {

				// Check if valid user id
				if (!$this->ShowOptionLink('edit')) {
					$sUserIdMsg = $Language->Phrase("NoEditPermission");
					$this->setFailureMessage($sUserIdMsg);
					$this->ClearInlineMode(); // Clear inline edit mode
					return;
				}
				$this->setKey("socio_nro", $this->socio_nro->CurrentValue); // Set up inline edit key
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
		if (strval($this->getKey("socio_nro")) <> strval($this->socio_nro->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("cciag_login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["socio_nro"] <> "") {
				$this->socio_nro->setQueryStringValue($_GET["socio_nro"]);
				$this->setKey("socio_nro", $this->socio_nro->CurrentValue); // Set up key
			} else {
				$this->setKey("socio_nro", ""); // Clear key
				$this->CurrentAction = "add";
			}
		}

		// Check if valid user id
		if ($this->LoadRow() && !$this->ShowOptionLink('add')) {
			$sUserIdMsg = $Language->Phrase("NoAddPermission");
			$this->setFailureMessage($sUserIdMsg);
			$this->ClearInlineMode(); // Clear inline edit mode
			return;
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

	// Perform update to grid
	function GridUpdate() {
		global $conn, $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateBegin")); // Batch update begin
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateRollback")); // Batch update rollback
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
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
			$this->socio_nro->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->socio_nro->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $conn, $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertBegin")); // Batch insert begin
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->socio_nro->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertSuccess")); // Batch insert success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertRollback")); // Batch insert rollback
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_propietario") && $objForm->HasValue("o_propietario") && $this->propietario->CurrentValue <> $this->propietario->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_comercio") && $objForm->HasValue("o_comercio") && $this->comercio->CurrentValue <> $this->comercio->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_direccion_comercio") && $objForm->HasValue("o_direccion_comercio") && $this->direccion_comercio->CurrentValue <> $this->direccion_comercio->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_activo") && $objForm->HasValue("o_activo") && $this->activo->CurrentValue <> $this->activo->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mail") && $objForm->HasValue("o_mail") && $this->mail->CurrentValue <> $this->mail->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tel") && $objForm->HasValue("o_tel") && $this->tel->CurrentValue <> $this->tel->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_cel") && $objForm->HasValue("o_cel") && $this->cel->CurrentValue <> $this->cel->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_cuit_cuil") && $objForm->HasValue("o_cuit_cuil") && $this->cuit_cuil->CurrentValue <> $this->cuit_cuil->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->socio_nro, $Default, FALSE); // socio_nro
		$this->BuildSearchSql($sWhere, $this->id_actividad, $Default, FALSE); // id_actividad
		$this->BuildSearchSql($sWhere, $this->propietario, $Default, FALSE); // propietario
		$this->BuildSearchSql($sWhere, $this->comercio, $Default, FALSE); // comercio
		$this->BuildSearchSql($sWhere, $this->direccion_comercio, $Default, FALSE); // direccion_comercio
		$this->BuildSearchSql($sWhere, $this->activo, $Default, FALSE); // activo
		$this->BuildSearchSql($sWhere, $this->mail, $Default, FALSE); // mail
		$this->BuildSearchSql($sWhere, $this->tel, $Default, FALSE); // tel
		$this->BuildSearchSql($sWhere, $this->cel, $Default, FALSE); // cel
		$this->BuildSearchSql($sWhere, $this->cuit_cuil, $Default, FALSE); // cuit_cuil

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->socio_nro->AdvancedSearch->Save(); // socio_nro
			$this->id_actividad->AdvancedSearch->Save(); // id_actividad
			$this->propietario->AdvancedSearch->Save(); // propietario
			$this->comercio->AdvancedSearch->Save(); // comercio
			$this->direccion_comercio->AdvancedSearch->Save(); // direccion_comercio
			$this->activo->AdvancedSearch->Save(); // activo
			$this->mail->AdvancedSearch->Save(); // mail
			$this->tel->AdvancedSearch->Save(); // tel
			$this->cel->AdvancedSearch->Save(); // cel
			$this->cuit_cuil->AdvancedSearch->Save(); // cuit_cuil
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
		$this->BuildBasicSearchSQL($sWhere, $this->propietario, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->comercio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->direccion_comercio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mail, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tel, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cel, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cuit_cuil, $arKeywords, $type);
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
		if ($this->id_actividad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->propietario->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->comercio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccion_comercio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mail->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tel->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->cel->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->cuit_cuil->AdvancedSearch->IssetSession())
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
		$this->id_actividad->AdvancedSearch->UnsetSession();
		$this->propietario->AdvancedSearch->UnsetSession();
		$this->comercio->AdvancedSearch->UnsetSession();
		$this->direccion_comercio->AdvancedSearch->UnsetSession();
		$this->activo->AdvancedSearch->UnsetSession();
		$this->mail->AdvancedSearch->UnsetSession();
		$this->tel->AdvancedSearch->UnsetSession();
		$this->cel->AdvancedSearch->UnsetSession();
		$this->cuit_cuil->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->socio_nro->AdvancedSearch->Load();
		$this->id_actividad->AdvancedSearch->Load();
		$this->propietario->AdvancedSearch->Load();
		$this->comercio->AdvancedSearch->Load();
		$this->direccion_comercio->AdvancedSearch->Load();
		$this->activo->AdvancedSearch->Load();
		$this->mail->AdvancedSearch->Load();
		$this->tel->AdvancedSearch->Load();
		$this->cel->AdvancedSearch->Load();
		$this->cuit_cuil->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->socio_nro); // socio_nro
			$this->UpdateSort($this->propietario); // propietario
			$this->UpdateSort($this->comercio); // comercio
			$this->UpdateSort($this->direccion_comercio); // direccion_comercio
			$this->UpdateSort($this->activo); // activo
			$this->UpdateSort($this->mail); // mail
			$this->UpdateSort($this->tel); // tel
			$this->UpdateSort($this->cel); // cel
			$this->UpdateSort($this->cuit_cuil); // cuit_cuil
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
				$this->setSessionOrderByList($sOrderBy);
				$this->socio_nro->setSort("");
				$this->propietario->setSort("");
				$this->comercio->setSort("");
				$this->direccion_comercio->setSort("");
				$this->activo->setSort("");
				$this->mail->setSort("");
				$this->tel->setSort("");
				$this->cel->setSort("");
				$this->cuit_cuil->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = FALSE;
			$item->Visible = FALSE; // Default hidden
		}

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

		// "detail_socios_detalles"
		$item = &$this->ListOptions->Add("detail_socios_detalles");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'socios_detalles') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["socios_detalles_grid"])) $GLOBALS["socios_detalles_grid"] = new csocios_detalles_grid;

		// "detail_deudas"
		$item = &$this->ListOptions->Add("detail_deudas");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'deudas') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["deudas_grid"])) $GLOBALS["deudas_grid"] = new cdeudas_grid;

		// "detail_socios_cuotas"
		$item = &$this->ListOptions->Add("detail_socios_cuotas");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'socios_cuotas') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["socios_cuotas_grid"])) $GLOBALS["socios_cuotas_grid"] = new csocios_cuotas_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = FALSE;
			$item->ShowInButtonGroup = FALSE;
		}

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

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
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
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->socio_nro->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView() && $this->ShowOptionLink('view'))
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit() && $this->ShowOptionLink('edit')) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd() && $this->ShowOptionLink('add')) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . $Language->Phrase("InlineCopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete() && $this->ShowOptionLink('delete'))
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_socios_detalles"
		$oListOpt = &$this->ListOptions->Items["detail_socios_detalles"];
		if ($Security->AllowList(CurrentProjectID() . 'socios_detalles') && $this->ShowOptionLink()) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("socios_detalles", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("cciag_socios_detalleslist.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio_nro=" . strval($this->socio_nro->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["socios_detalles_grid"]->DetailView && $Security->CanView() && $this->ShowOptionLink('view') && $Security->AllowView(CurrentProjectID() . 'socios_detalles')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=socios_detalles")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "socios_detalles";
			}
			if ($GLOBALS["socios_detalles_grid"]->DetailEdit && $Security->CanEdit() && $this->ShowOptionLink('edit') && $Security->AllowEdit(CurrentProjectID() . 'socios_detalles')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=socios_detalles")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "socios_detalles";
			}
			if ($GLOBALS["socios_detalles_grid"]->DetailAdd && $Security->CanAdd() && $this->ShowOptionLink('add') && $Security->AllowAdd(CurrentProjectID() . 'socios_detalles')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=socios_detalles")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "socios_detalles";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_deudas"
		$oListOpt = &$this->ListOptions->Items["detail_deudas"];
		if ($Security->AllowList(CurrentProjectID() . 'deudas') && $this->ShowOptionLink()) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("deudas", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("cciag_deudaslist.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio_nro=" . strval($this->socio_nro->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["deudas_grid"]->DetailView && $Security->CanView() && $this->ShowOptionLink('view') && $Security->AllowView(CurrentProjectID() . 'deudas')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=deudas")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "deudas";
			}
			if ($GLOBALS["deudas_grid"]->DetailEdit && $Security->CanEdit() && $this->ShowOptionLink('edit') && $Security->AllowEdit(CurrentProjectID() . 'deudas')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=deudas")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "deudas";
			}
			if ($GLOBALS["deudas_grid"]->DetailAdd && $Security->CanAdd() && $this->ShowOptionLink('add') && $Security->AllowAdd(CurrentProjectID() . 'deudas')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=deudas")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "deudas";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_socios_cuotas"
		$oListOpt = &$this->ListOptions->Items["detail_socios_cuotas"];
		if ($Security->AllowList(CurrentProjectID() . 'socios_cuotas') && $this->ShowOptionLink()) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("socios_cuotas", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("cciag_socios_cuotaslist.php?" . EW_TABLE_SHOW_MASTER . "=socios&fk_socio_nro=" . strval($this->socio_nro->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["socios_cuotas_grid"]->DetailView && $Security->CanView() && $this->ShowOptionLink('view') && $Security->AllowView(CurrentProjectID() . 'socios_cuotas')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=socios_cuotas")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "socios_cuotas";
			}
			if ($GLOBALS["socios_cuotas_grid"]->DetailEdit && $Security->CanEdit() && $this->ShowOptionLink('edit') && $Security->AllowEdit(CurrentProjectID() . 'socios_cuotas')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=socios_cuotas")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "socios_cuotas";
			}
			if ($GLOBALS["socios_cuotas_grid"]->DetailAdd && $Security->CanAdd() && $this->ShowOptionLink('add') && $Security->AllowAdd(CurrentProjectID() . 'socios_cuotas')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=socios_cuotas")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "socios_cuotas";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->socio_nro->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->socio_nro->CurrentValue . "\">";
		}
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
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_socios_detalles");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=socios_detalles") . "\">" . $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["socios_detalles"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["socios_detalles"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'socios_detalles') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "socios_detalles";
		}
		$item = &$option->Add("detailadd_deudas");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=deudas") . "\">" . $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["deudas"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["deudas"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'deudas') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "deudas";
		}
		$item = &$option->Add("detailadd_socios_cuotas");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=socios_cuotas") . "\">" . $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["socios_cuotas"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["socios_cuotas"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'socios_cuotas') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "socios_cuotas";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
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
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fsocioslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fsocioslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere);

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"cciag_sociossrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"fsocioslistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
		$item->Visible = ($this->SearchWhere <> "" && $this->TotalRecs > 0);

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
		$this->socio_nro->CurrentValue = NULL;
		$this->socio_nro->OldValue = $this->socio_nro->CurrentValue;
		$this->propietario->CurrentValue = NULL;
		$this->propietario->OldValue = $this->propietario->CurrentValue;
		$this->comercio->CurrentValue = NULL;
		$this->comercio->OldValue = $this->comercio->CurrentValue;
		$this->direccion_comercio->CurrentValue = NULL;
		$this->direccion_comercio->OldValue = $this->direccion_comercio->CurrentValue;
		$this->activo->CurrentValue = 'S';
		$this->activo->OldValue = $this->activo->CurrentValue;
		$this->mail->CurrentValue = NULL;
		$this->mail->OldValue = $this->mail->CurrentValue;
		$this->tel->CurrentValue = NULL;
		$this->tel->OldValue = $this->tel->CurrentValue;
		$this->cel->CurrentValue = NULL;
		$this->cel->OldValue = $this->cel->CurrentValue;
		$this->cuit_cuil->CurrentValue = NULL;
		$this->cuit_cuil->OldValue = $this->cuit_cuil->CurrentValue;
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

		// id_actividad
		$this->id_actividad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_actividad"]);
		if ($this->id_actividad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_actividad->AdvancedSearch->SearchOperator = @$_GET["z_id_actividad"];

		// propietario
		$this->propietario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_propietario"]);
		if ($this->propietario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->propietario->AdvancedSearch->SearchOperator = @$_GET["z_propietario"];

		// comercio
		$this->comercio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_comercio"]);
		if ($this->comercio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->comercio->AdvancedSearch->SearchOperator = @$_GET["z_comercio"];

		// direccion_comercio
		$this->direccion_comercio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_direccion_comercio"]);
		if ($this->direccion_comercio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->direccion_comercio->AdvancedSearch->SearchOperator = @$_GET["z_direccion_comercio"];

		// activo
		$this->activo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_activo"]);
		if ($this->activo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->activo->AdvancedSearch->SearchOperator = @$_GET["z_activo"];

		// mail
		$this->mail->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_mail"]);
		if ($this->mail->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->mail->AdvancedSearch->SearchOperator = @$_GET["z_mail"];

		// tel
		$this->tel->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tel"]);
		if ($this->tel->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tel->AdvancedSearch->SearchOperator = @$_GET["z_tel"];

		// cel
		$this->cel->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_cel"]);
		if ($this->cel->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->cel->AdvancedSearch->SearchOperator = @$_GET["z_cel"];

		// cuit_cuil
		$this->cuit_cuil->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_cuit_cuil"]);
		if ($this->cuit_cuil->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->cuit_cuil->AdvancedSearch->SearchOperator = @$_GET["z_cuit_cuil"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->socio_nro->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->socio_nro->setFormValue($objForm->GetValue("x_socio_nro"));
		if (!$this->propietario->FldIsDetailKey) {
			$this->propietario->setFormValue($objForm->GetValue("x_propietario"));
		}
		$this->propietario->setOldValue($objForm->GetValue("o_propietario"));
		if (!$this->comercio->FldIsDetailKey) {
			$this->comercio->setFormValue($objForm->GetValue("x_comercio"));
		}
		$this->comercio->setOldValue($objForm->GetValue("o_comercio"));
		if (!$this->direccion_comercio->FldIsDetailKey) {
			$this->direccion_comercio->setFormValue($objForm->GetValue("x_direccion_comercio"));
		}
		$this->direccion_comercio->setOldValue($objForm->GetValue("o_direccion_comercio"));
		if (!$this->activo->FldIsDetailKey) {
			$this->activo->setFormValue($objForm->GetValue("x_activo"));
		}
		$this->activo->setOldValue($objForm->GetValue("o_activo"));
		if (!$this->mail->FldIsDetailKey) {
			$this->mail->setFormValue($objForm->GetValue("x_mail"));
		}
		$this->mail->setOldValue($objForm->GetValue("o_mail"));
		if (!$this->tel->FldIsDetailKey) {
			$this->tel->setFormValue($objForm->GetValue("x_tel"));
		}
		$this->tel->setOldValue($objForm->GetValue("o_tel"));
		if (!$this->cel->FldIsDetailKey) {
			$this->cel->setFormValue($objForm->GetValue("x_cel"));
		}
		$this->cel->setOldValue($objForm->GetValue("o_cel"));
		if (!$this->cuit_cuil->FldIsDetailKey) {
			$this->cuit_cuil->setFormValue($objForm->GetValue("x_cuit_cuil"));
		}
		$this->cuit_cuil->setOldValue($objForm->GetValue("o_cuit_cuil"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->socio_nro->CurrentValue = $this->socio_nro->FormValue;
		$this->propietario->CurrentValue = $this->propietario->FormValue;
		$this->comercio->CurrentValue = $this->comercio->FormValue;
		$this->direccion_comercio->CurrentValue = $this->direccion_comercio->FormValue;
		$this->activo->CurrentValue = $this->activo->FormValue;
		$this->mail->CurrentValue = $this->mail->FormValue;
		$this->tel->CurrentValue = $this->tel->FormValue;
		$this->cel->CurrentValue = $this->cel->FormValue;
		$this->cuit_cuil->CurrentValue = $this->cuit_cuil->FormValue;
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
		$this->id_actividad->setDbValue($rs->fields('id_actividad'));
		if (array_key_exists('EV__id_actividad', $rs->fields)) {
			$this->id_actividad->VirtualValue = $rs->fields('EV__id_actividad'); // Set up virtual field value
		} else {
			$this->id_actividad->VirtualValue = ""; // Clear value
		}
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		$this->propietario->setDbValue($rs->fields('propietario'));
		$this->comercio->setDbValue($rs->fields('comercio'));
		$this->direccion_comercio->setDbValue($rs->fields('direccion_comercio'));
		$this->activo->setDbValue($rs->fields('activo'));
		$this->mail->setDbValue($rs->fields('mail'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->cuit_cuil->setDbValue($rs->fields('cuit_cuil'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->socio_nro->DbValue = $row['socio_nro'];
		$this->id_actividad->DbValue = $row['id_actividad'];
		$this->id_usuario->DbValue = $row['id_usuario'];
		$this->propietario->DbValue = $row['propietario'];
		$this->comercio->DbValue = $row['comercio'];
		$this->direccion_comercio->DbValue = $row['direccion_comercio'];
		$this->activo->DbValue = $row['activo'];
		$this->mail->DbValue = $row['mail'];
		$this->tel->DbValue = $row['tel'];
		$this->cel->DbValue = $row['cel'];
		$this->cuit_cuil->DbValue = $row['cuit_cuil'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("socio_nro")) <> "")
			$this->socio_nro->CurrentValue = $this->getKey("socio_nro"); // socio_nro
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// socio_nro
		// id_actividad

		$this->id_actividad->CellCssStyle = "white-space: nowrap;";

		// id_usuario
		$this->id_usuario->CellCssStyle = "white-space: nowrap;";

		// propietario
		// comercio
		// direccion_comercio
		// activo
		// mail
		// tel
		// cel
		// cuit_cuil

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// socio_nro
			$this->socio_nro->ViewValue = $this->socio_nro->CurrentValue;
			$this->socio_nro->ViewCustomAttributes = "";

			// propietario
			$this->propietario->ViewValue = $this->propietario->CurrentValue;
			$this->propietario->ViewCustomAttributes = "";

			// comercio
			$this->comercio->ViewValue = $this->comercio->CurrentValue;
			$this->comercio->ViewCustomAttributes = "";

			// direccion_comercio
			$this->direccion_comercio->ViewValue = $this->direccion_comercio->CurrentValue;
			$this->direccion_comercio->ViewCustomAttributes = "";

			// activo
			if (strval($this->activo->CurrentValue) <> "") {
				switch ($this->activo->CurrentValue) {
					case $this->activo->FldTagValue(1):
						$this->activo->ViewValue = $this->activo->FldTagCaption(1) <> "" ? $this->activo->FldTagCaption(1) : $this->activo->CurrentValue;
						break;
					case $this->activo->FldTagValue(2):
						$this->activo->ViewValue = $this->activo->FldTagCaption(2) <> "" ? $this->activo->FldTagCaption(2) : $this->activo->CurrentValue;
						break;
					default:
						$this->activo->ViewValue = $this->activo->CurrentValue;
				}
			} else {
				$this->activo->ViewValue = NULL;
			}
			$this->activo->ViewCustomAttributes = "";

			// mail
			$this->mail->ViewValue = $this->mail->CurrentValue;
			$this->mail->ViewValue = strtolower($this->mail->ViewValue);
			$this->mail->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewValue = trim($this->tel->ViewValue);
			$this->tel->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewValue = trim($this->cel->ViewValue);
			$this->cel->ViewCustomAttributes = "";

			// cuit_cuil
			$this->cuit_cuil->ViewValue = $this->cuit_cuil->CurrentValue;
			$this->cuit_cuil->ViewValue = ew_FormatNumber($this->cuit_cuil->ViewValue, 0, -2, -2, -2);
			$this->cuit_cuil->ViewCustomAttributes = "";

			// socio_nro
			$this->socio_nro->LinkCustomAttributes = "";
			$this->socio_nro->HrefValue = "";
			$this->socio_nro->TooltipValue = "";
			if ($this->Export == "")
				$this->socio_nro->ViewValue = ew_Highlight($this->HighlightName(), $this->socio_nro->ViewValue, "", "", $this->socio_nro->AdvancedSearch->getValue("x"), "");

			// propietario
			$this->propietario->LinkCustomAttributes = "";
			$this->propietario->HrefValue = "";
			$this->propietario->TooltipValue = "";
			if ($this->Export == "")
				$this->propietario->ViewValue = ew_Highlight($this->HighlightName(), $this->propietario->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->propietario->AdvancedSearch->getValue("x"), "");

			// comercio
			$this->comercio->LinkCustomAttributes = "";
			$this->comercio->HrefValue = "";
			$this->comercio->TooltipValue = "";
			if ($this->Export == "")
				$this->comercio->ViewValue = ew_Highlight($this->HighlightName(), $this->comercio->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->comercio->AdvancedSearch->getValue("x"), "");

			// direccion_comercio
			$this->direccion_comercio->LinkCustomAttributes = "";
			$this->direccion_comercio->HrefValue = "";
			$this->direccion_comercio->TooltipValue = "";
			if ($this->Export == "")
				$this->direccion_comercio->ViewValue = ew_Highlight($this->HighlightName(), $this->direccion_comercio->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->direccion_comercio->AdvancedSearch->getValue("x"), "");

			// activo
			$this->activo->LinkCustomAttributes = "";
			$this->activo->HrefValue = "";
			$this->activo->TooltipValue = "";

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

			// cuit_cuil
			$this->cuit_cuil->LinkCustomAttributes = "";
			$this->cuit_cuil->HrefValue = "";
			$this->cuit_cuil->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// socio_nro
			// propietario

			$this->propietario->EditAttrs["class"] = "form-control";
			$this->propietario->EditCustomAttributes = "";
			$this->propietario->EditValue = ew_HtmlEncode($this->propietario->CurrentValue);
			$this->propietario->PlaceHolder = ew_RemoveHtml($this->propietario->FldCaption());

			// comercio
			$this->comercio->EditAttrs["class"] = "form-control";
			$this->comercio->EditCustomAttributes = "";
			$this->comercio->EditValue = ew_HtmlEncode($this->comercio->CurrentValue);
			$this->comercio->PlaceHolder = ew_RemoveHtml($this->comercio->FldCaption());

			// direccion_comercio
			$this->direccion_comercio->EditAttrs["class"] = "form-control";
			$this->direccion_comercio->EditCustomAttributes = "";
			$this->direccion_comercio->EditValue = ew_HtmlEncode($this->direccion_comercio->CurrentValue);
			$this->direccion_comercio->PlaceHolder = ew_RemoveHtml($this->direccion_comercio->FldCaption());

			// activo
			$this->activo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->activo->FldTagValue(1), $this->activo->FldTagCaption(1) <> "" ? $this->activo->FldTagCaption(1) : $this->activo->FldTagValue(1));
			$arwrk[] = array($this->activo->FldTagValue(2), $this->activo->FldTagCaption(2) <> "" ? $this->activo->FldTagCaption(2) : $this->activo->FldTagValue(2));
			$this->activo->EditValue = $arwrk;

			// mail
			$this->mail->EditAttrs["class"] = "form-control";
			$this->mail->EditCustomAttributes = "";
			$this->mail->EditValue = ew_HtmlEncode($this->mail->CurrentValue);
			$this->mail->PlaceHolder = ew_RemoveHtml($this->mail->FldCaption());

			// tel
			$this->tel->EditAttrs["class"] = "form-control";
			$this->tel->EditCustomAttributes = "";
			$this->tel->EditValue = ew_HtmlEncode($this->tel->CurrentValue);
			$this->tel->PlaceHolder = ew_RemoveHtml($this->tel->FldCaption());

			// cel
			$this->cel->EditAttrs["class"] = "form-control";
			$this->cel->EditCustomAttributes = "";
			$this->cel->EditValue = ew_HtmlEncode($this->cel->CurrentValue);
			$this->cel->PlaceHolder = ew_RemoveHtml($this->cel->FldCaption());

			// cuit_cuil
			$this->cuit_cuil->EditAttrs["class"] = "form-control";
			$this->cuit_cuil->EditCustomAttributes = "";
			$this->cuit_cuil->EditValue = ew_HtmlEncode($this->cuit_cuil->CurrentValue);
			$this->cuit_cuil->PlaceHolder = ew_RemoveHtml($this->cuit_cuil->FldCaption());

			// Edit refer script
			// socio_nro

			$this->socio_nro->HrefValue = "";

			// propietario
			$this->propietario->HrefValue = "";

			// comercio
			$this->comercio->HrefValue = "";

			// direccion_comercio
			$this->direccion_comercio->HrefValue = "";

			// activo
			$this->activo->HrefValue = "";

			// mail
			$this->mail->HrefValue = "";

			// tel
			$this->tel->HrefValue = "";

			// cel
			$this->cel->HrefValue = "";

			// cuit_cuil
			$this->cuit_cuil->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// socio_nro
			$this->socio_nro->EditAttrs["class"] = "form-control";
			$this->socio_nro->EditCustomAttributes = "";
			$this->socio_nro->EditValue = $this->socio_nro->CurrentValue;
			$this->socio_nro->ViewCustomAttributes = "";

			// propietario
			$this->propietario->EditAttrs["class"] = "form-control";
			$this->propietario->EditCustomAttributes = "";
			$this->propietario->EditValue = ew_HtmlEncode($this->propietario->CurrentValue);
			$this->propietario->PlaceHolder = ew_RemoveHtml($this->propietario->FldCaption());

			// comercio
			$this->comercio->EditAttrs["class"] = "form-control";
			$this->comercio->EditCustomAttributes = "";
			$this->comercio->EditValue = ew_HtmlEncode($this->comercio->CurrentValue);
			$this->comercio->PlaceHolder = ew_RemoveHtml($this->comercio->FldCaption());

			// direccion_comercio
			$this->direccion_comercio->EditAttrs["class"] = "form-control";
			$this->direccion_comercio->EditCustomAttributes = "";
			$this->direccion_comercio->EditValue = ew_HtmlEncode($this->direccion_comercio->CurrentValue);
			$this->direccion_comercio->PlaceHolder = ew_RemoveHtml($this->direccion_comercio->FldCaption());

			// activo
			$this->activo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->activo->FldTagValue(1), $this->activo->FldTagCaption(1) <> "" ? $this->activo->FldTagCaption(1) : $this->activo->FldTagValue(1));
			$arwrk[] = array($this->activo->FldTagValue(2), $this->activo->FldTagCaption(2) <> "" ? $this->activo->FldTagCaption(2) : $this->activo->FldTagValue(2));
			$this->activo->EditValue = $arwrk;

			// mail
			$this->mail->EditAttrs["class"] = "form-control";
			$this->mail->EditCustomAttributes = "";
			$this->mail->EditValue = ew_HtmlEncode($this->mail->CurrentValue);
			$this->mail->PlaceHolder = ew_RemoveHtml($this->mail->FldCaption());

			// tel
			$this->tel->EditAttrs["class"] = "form-control";
			$this->tel->EditCustomAttributes = "";
			$this->tel->EditValue = ew_HtmlEncode($this->tel->CurrentValue);
			$this->tel->PlaceHolder = ew_RemoveHtml($this->tel->FldCaption());

			// cel
			$this->cel->EditAttrs["class"] = "form-control";
			$this->cel->EditCustomAttributes = "";
			$this->cel->EditValue = ew_HtmlEncode($this->cel->CurrentValue);
			$this->cel->PlaceHolder = ew_RemoveHtml($this->cel->FldCaption());

			// cuit_cuil
			$this->cuit_cuil->EditAttrs["class"] = "form-control";
			$this->cuit_cuil->EditCustomAttributes = "";
			$this->cuit_cuil->EditValue = ew_HtmlEncode($this->cuit_cuil->CurrentValue);
			$this->cuit_cuil->PlaceHolder = ew_RemoveHtml($this->cuit_cuil->FldCaption());

			// Edit refer script
			// socio_nro

			$this->socio_nro->HrefValue = "";

			// propietario
			$this->propietario->HrefValue = "";

			// comercio
			$this->comercio->HrefValue = "";

			// direccion_comercio
			$this->direccion_comercio->HrefValue = "";

			// activo
			$this->activo->HrefValue = "";

			// mail
			$this->mail->HrefValue = "";

			// tel
			$this->tel->HrefValue = "";

			// cel
			$this->cel->HrefValue = "";

			// cuit_cuil
			$this->cuit_cuil->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->socio_nro->FormValue)) {
			ew_AddMessage($gsFormError, $this->socio_nro->FldErrMsg());
		}
		if (!$this->propietario->FldIsDetailKey && !is_null($this->propietario->FormValue) && $this->propietario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->propietario->FldCaption(), $this->propietario->ReqErrMsg));
		}
		if (!$this->comercio->FldIsDetailKey && !is_null($this->comercio->FormValue) && $this->comercio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->comercio->FldCaption(), $this->comercio->ReqErrMsg));
		}
		if (!$this->direccion_comercio->FldIsDetailKey && !is_null($this->direccion_comercio->FormValue) && $this->direccion_comercio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->direccion_comercio->FldCaption(), $this->direccion_comercio->ReqErrMsg));
		}
		if (!ew_CheckEmail($this->mail->FormValue)) {
			ew_AddMessage($gsFormError, $this->mail->FldErrMsg());
		}
		if (!ew_CheckPhone($this->tel->FormValue)) {
			ew_AddMessage($gsFormError, $this->tel->FldErrMsg());
		}
		if (!ew_CheckPhone($this->cel->FormValue)) {
			ew_AddMessage($gsFormError, $this->cel->FldErrMsg());
		}
		if (!$this->cuit_cuil->FldIsDetailKey && !is_null($this->cuit_cuil->FormValue) && $this->cuit_cuil->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cuit_cuil->FldCaption(), $this->cuit_cuil->ReqErrMsg));
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['socio_nro'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
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

			// propietario
			$this->propietario->SetDbValueDef($rsnew, $this->propietario->CurrentValue, NULL, $this->propietario->ReadOnly);

			// comercio
			$this->comercio->SetDbValueDef($rsnew, $this->comercio->CurrentValue, NULL, $this->comercio->ReadOnly);

			// direccion_comercio
			$this->direccion_comercio->SetDbValueDef($rsnew, $this->direccion_comercio->CurrentValue, NULL, $this->direccion_comercio->ReadOnly);

			// activo
			$this->activo->SetDbValueDef($rsnew, $this->activo->CurrentValue, NULL, $this->activo->ReadOnly);

			// mail
			$this->mail->SetDbValueDef($rsnew, $this->mail->CurrentValue, NULL, $this->mail->ReadOnly);

			// tel
			$this->tel->SetDbValueDef($rsnew, $this->tel->CurrentValue, NULL, $this->tel->ReadOnly);

			// cel
			$this->cel->SetDbValueDef($rsnew, $this->cel->CurrentValue, NULL, $this->cel->ReadOnly);

			// cuit_cuil
			$this->cuit_cuil->SetDbValueDef($rsnew, $this->cuit_cuil->CurrentValue, NULL, $this->cuit_cuil->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
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
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// propietario
		$this->propietario->SetDbValueDef($rsnew, $this->propietario->CurrentValue, NULL, FALSE);

		// comercio
		$this->comercio->SetDbValueDef($rsnew, $this->comercio->CurrentValue, NULL, FALSE);

		// direccion_comercio
		$this->direccion_comercio->SetDbValueDef($rsnew, $this->direccion_comercio->CurrentValue, NULL, FALSE);

		// activo
		$this->activo->SetDbValueDef($rsnew, $this->activo->CurrentValue, NULL, FALSE);

		// mail
		$this->mail->SetDbValueDef($rsnew, $this->mail->CurrentValue, NULL, FALSE);

		// tel
		$this->tel->SetDbValueDef($rsnew, $this->tel->CurrentValue, NULL, FALSE);

		// cel
		$this->cel->SetDbValueDef($rsnew, $this->cel->CurrentValue, NULL, FALSE);

		// cuit_cuil
		$this->cuit_cuil->SetDbValueDef($rsnew, $this->cuit_cuil->CurrentValue, NULL, FALSE);

		// id_usuario
		if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin
			$rsnew['id_usuario'] = CurrentUserID();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
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
			$this->socio_nro->setDbValue($conn->Insert_ID());
			$rsnew['socio_nro'] = $this->socio_nro->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->socio_nro->AdvancedSearch->Load();
		$this->id_actividad->AdvancedSearch->Load();
		$this->propietario->AdvancedSearch->Load();
		$this->comercio->AdvancedSearch->Load();
		$this->direccion_comercio->AdvancedSearch->Load();
		$this->activo->AdvancedSearch->Load();
		$this->mail->AdvancedSearch->Load();
		$this->tel->AdvancedSearch->Load();
		$this->cel->AdvancedSearch->Load();
		$this->cuit_cuil->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_socios\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_socios',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fsocioslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$this->AddSearchQueryString($sQry, $this->id_actividad); // id_actividad
		$this->AddSearchQueryString($sQry, $this->propietario); // propietario
		$this->AddSearchQueryString($sQry, $this->comercio); // comercio
		$this->AddSearchQueryString($sQry, $this->direccion_comercio); // direccion_comercio
		$this->AddSearchQueryString($sQry, $this->activo); // activo
		$this->AddSearchQueryString($sQry, $this->mail); // mail
		$this->AddSearchQueryString($sQry, $this->tel); // tel
		$this->AddSearchQueryString($sQry, $this->cel); // cel
		$this->AddSearchQueryString($sQry, $this->cuit_cuil); // cuit_cuil

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

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->id_usuario->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'socios';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'socios';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['socio_nro'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'socios';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['socio_nro'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'socios';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['socio_nro'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
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
if (!isset($socios_list)) $socios_list = new csocios_list();

// Page init
$socios_list->Page_Init();

// Page main
$socios_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_header.php" ?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">

// Page object
var socios_list = new ew_Page("socios_list");
socios_list.PageID = "list"; // Page ID
var EW_PAGE_ID = socios_list.PageID; // For backward compatibility

// Form object
var fsocioslist = new ew_Form("fsocioslist");
fsocioslist.FormKeyCountName = '<?php echo $socios_list->FormKeyCountName ?>';

// Validate form
fsocioslist.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_socio_nro");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->socio_nro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_propietario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->propietario->FldCaption(), $socios->propietario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_comercio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->comercio->FldCaption(), $socios->comercio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion_comercio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->direccion_comercio->FldCaption(), $socios->direccion_comercio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mail");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->mail->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tel");
			if (elm && !ew_CheckPhone(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->tel->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cel");
			if (elm && !ew_CheckPhone(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->cel->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cuit_cuil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->cuit_cuil->FldCaption(), $socios->cuit_cuil->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
fsocioslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "propietario", false)) return false;
	if (ew_ValueChanged(fobj, infix, "comercio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "direccion_comercio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "activo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mail", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cuit_cuil", false)) return false;
	return true;
}

// Form_CustomValidate event
fsocioslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsocioslist.ValidateRequired = true;
<?php } else { ?>
fsocioslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fsocioslistsrch = new ew_Form("fsocioslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($socios->Export == "") { ?>
<div class="ewToolbar">
<?php if ($socios->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($socios_list->TotalRecs > 0 && $socios_list->ExportOptions->Visible()) { ?>
<?php $socios_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($socios_list->SearchOptions->Visible()) { ?>
<?php $socios_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($socios->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($socios->CurrentAction == "gridadd") {
	$socios->CurrentFilter = "0=1";
	$socios_list->StartRec = 1;
	$socios_list->DisplayRecs = $socios->GridAddRowCount;
	$socios_list->TotalRecs = $socios_list->DisplayRecs;
	$socios_list->StopRec = $socios_list->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$socios_list->TotalRecs = $socios->SelectRecordCount();
	} else {
		if ($socios_list->Recordset = $socios_list->LoadRecordset())
			$socios_list->TotalRecs = $socios_list->Recordset->RecordCount();
	}
	$socios_list->StartRec = 1;
	if ($socios_list->DisplayRecs <= 0 || ($socios->Export <> "" && $socios->ExportAll)) // Display all records
		$socios_list->DisplayRecs = $socios_list->TotalRecs;
	if (!($socios->Export <> "" && $socios->ExportAll))
		$socios_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$socios_list->Recordset = $socios_list->LoadRecordset($socios_list->StartRec-1, $socios_list->DisplayRecs);

	// Set no record found message
	if ($socios->CurrentAction == "" && $socios_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$socios_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($socios_list->SearchWhere == "0=101")
			$socios_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$socios_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$socios_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($socios->Export == "" && $socios->CurrentAction == "") { ?>
<form name="fsocioslistsrch" id="fsocioslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($socios_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fsocioslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="socios">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($socios_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($socios_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $socios_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($socios_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($socios_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($socios_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($socios_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $socios_list->ShowPageHeader(); ?>
<?php
$socios_list->ShowMessage();
?>
<?php if ($socios_list->TotalRecs > 0 || $socios->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($socios->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($socios->CurrentAction <> "gridadd" && $socios->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($socios_list->Pager)) $socios_list->Pager = new cPrevNextPager($socios_list->StartRec, $socios_list->DisplayRecs, $socios_list->TotalRecs) ?>
<?php if ($socios_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($socios_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($socios_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $socios_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($socios_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($socios_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $socios_list->PageUrl() ?>start=<?php echo $socios_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $socios_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $socios_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $socios_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $socios_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($socios_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fsocioslist" id="fsocioslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($socios_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $socios_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="socios">
<div id="gmp_socios" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($socios_list->TotalRecs > 0 || $socios->CurrentAction == "add" || $socios->CurrentAction == "copy") { ?>
<table id="tbl_socioslist" class="table ewTable">
<?php echo $socios->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$socios_list->RenderListOptions();

// Render list options (header, left)
$socios_list->ListOptions->Render("header", "left");
?>
<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
	<?php if ($socios->SortUrl($socios->socio_nro) == "") { ?>
		<th data-name="socio_nro"><div id="elh_socios_socio_nro" class="socios_socio_nro"><div class="ewTableHeaderCaption"><?php echo $socios->socio_nro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="socio_nro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->socio_nro) ?>',1);"><div id="elh_socios_socio_nro" class="socios_socio_nro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->socio_nro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->socio_nro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->socio_nro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->propietario->Visible) { // propietario ?>
	<?php if ($socios->SortUrl($socios->propietario) == "") { ?>
		<th data-name="propietario"><div id="elh_socios_propietario" class="socios_propietario"><div class="ewTableHeaderCaption"><?php echo $socios->propietario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="propietario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->propietario) ?>',1);"><div id="elh_socios_propietario" class="socios_propietario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->propietario->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($socios->propietario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->propietario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->comercio->Visible) { // comercio ?>
	<?php if ($socios->SortUrl($socios->comercio) == "") { ?>
		<th data-name="comercio"><div id="elh_socios_comercio" class="socios_comercio"><div class="ewTableHeaderCaption"><?php echo $socios->comercio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comercio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->comercio) ?>',1);"><div id="elh_socios_comercio" class="socios_comercio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->comercio->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($socios->comercio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->comercio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
	<?php if ($socios->SortUrl($socios->direccion_comercio) == "") { ?>
		<th data-name="direccion_comercio"><div id="elh_socios_direccion_comercio" class="socios_direccion_comercio"><div class="ewTableHeaderCaption"><?php echo $socios->direccion_comercio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion_comercio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->direccion_comercio) ?>',1);"><div id="elh_socios_direccion_comercio" class="socios_direccion_comercio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->direccion_comercio->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($socios->direccion_comercio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->direccion_comercio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->activo->Visible) { // activo ?>
	<?php if ($socios->SortUrl($socios->activo) == "") { ?>
		<th data-name="activo"><div id="elh_socios_activo" class="socios_activo"><div class="ewTableHeaderCaption"><?php echo $socios->activo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->activo) ?>',1);"><div id="elh_socios_activo" class="socios_activo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->activo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->activo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->activo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->mail->Visible) { // mail ?>
	<?php if ($socios->SortUrl($socios->mail) == "") { ?>
		<th data-name="mail"><div id="elh_socios_mail" class="socios_mail"><div class="ewTableHeaderCaption"><?php echo $socios->mail->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mail"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->mail) ?>',1);"><div id="elh_socios_mail" class="socios_mail">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->mail->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($socios->mail->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->mail->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->tel->Visible) { // tel ?>
	<?php if ($socios->SortUrl($socios->tel) == "") { ?>
		<th data-name="tel"><div id="elh_socios_tel" class="socios_tel"><div class="ewTableHeaderCaption"><?php echo $socios->tel->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tel"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->tel) ?>',1);"><div id="elh_socios_tel" class="socios_tel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->tel->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($socios->tel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->tel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->cel->Visible) { // cel ?>
	<?php if ($socios->SortUrl($socios->cel) == "") { ?>
		<th data-name="cel"><div id="elh_socios_cel" class="socios_cel"><div class="ewTableHeaderCaption"><?php echo $socios->cel->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cel"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->cel) ?>',1);"><div id="elh_socios_cel" class="socios_cel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->cel->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($socios->cel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->cel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
	<?php if ($socios->SortUrl($socios->cuit_cuil) == "") { ?>
		<th data-name="cuit_cuil"><div id="elh_socios_cuit_cuil" class="socios_cuit_cuil"><div class="ewTableHeaderCaption"><?php echo $socios->cuit_cuil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cuit_cuil"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $socios->SortUrl($socios->cuit_cuil) ?>',1);"><div id="elh_socios_cuit_cuil" class="socios_cuit_cuil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->cuit_cuil->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($socios->cuit_cuil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->cuit_cuil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$socios_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($socios->CurrentAction == "add" || $socios->CurrentAction == "copy") {
		$socios_list->RowIndex = 0;
		$socios_list->KeyCount = $socios_list->RowIndex;
		if ($socios->CurrentAction == "copy" && !$socios_list->LoadRow())
				$socios->CurrentAction = "add";
		if ($socios->CurrentAction == "add")
			$socios_list->LoadDefaultValues();
		if ($socios->EventCancelled) // Insert failed
			$socios_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$socios->ResetAttrs();
		$socios->RowAttrs = array_merge($socios->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_socios', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$socios->RowType = EW_ROWTYPE_ADD;

		// Render row
		$socios_list->RenderRow();

		// Render list options
		$socios_list->RenderListOptions();
		$socios_list->StartRowCnt = 0;
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_list->ListOptions->Render("body", "left", $socios_list->RowCnt);
?>
	<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
		<td>
<input type="hidden" data-field="x_socio_nro" name="o<?php echo $socios_list->RowIndex ?>_socio_nro" id="o<?php echo $socios_list->RowIndex ?>_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->propietario->Visible) { // propietario ?>
		<td>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_propietario" class="form-group socios_propietario">
<input type="text" data-field="x_propietario" name="x<?php echo $socios_list->RowIndex ?>_propietario" id="x<?php echo $socios_list->RowIndex ?>_propietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->propietario->PlaceHolder) ?>" value="<?php echo $socios->propietario->EditValue ?>"<?php echo $socios->propietario->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_propietario" name="o<?php echo $socios_list->RowIndex ?>_propietario" id="o<?php echo $socios_list->RowIndex ?>_propietario" value="<?php echo ew_HtmlEncode($socios->propietario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->comercio->Visible) { // comercio ?>
		<td>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_comercio" class="form-group socios_comercio">
<input type="text" data-field="x_comercio" name="x<?php echo $socios_list->RowIndex ?>_comercio" id="x<?php echo $socios_list->RowIndex ?>_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->comercio->PlaceHolder) ?>" value="<?php echo $socios->comercio->EditValue ?>"<?php echo $socios->comercio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_comercio" name="o<?php echo $socios_list->RowIndex ?>_comercio" id="o<?php echo $socios_list->RowIndex ?>_comercio" value="<?php echo ew_HtmlEncode($socios->comercio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
		<td>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_direccion_comercio" class="form-group socios_direccion_comercio">
<input type="text" data-field="x_direccion_comercio" name="x<?php echo $socios_list->RowIndex ?>_direccion_comercio" id="x<?php echo $socios_list->RowIndex ?>_direccion_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->direccion_comercio->PlaceHolder) ?>" value="<?php echo $socios->direccion_comercio->EditValue ?>"<?php echo $socios->direccion_comercio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_direccion_comercio" name="o<?php echo $socios_list->RowIndex ?>_direccion_comercio" id="o<?php echo $socios_list->RowIndex ?>_direccion_comercio" value="<?php echo ew_HtmlEncode($socios->direccion_comercio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->activo->Visible) { // activo ?>
		<td>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_activo" class="form-group socios_activo">
<div id="tp_x<?php echo $socios_list->RowIndex ?>_activo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_list->RowIndex ?>_activo" id="x<?php echo $socios_list->RowIndex ?>_activo" value="{value}"<?php echo $socios->activo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_list->RowIndex ?>_activo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->activo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->activo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activo" name="x<?php echo $socios_list->RowIndex ?>_activo" id="x<?php echo $socios_list->RowIndex ?>_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $socios->activo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_activo" name="o<?php echo $socios_list->RowIndex ?>_activo" id="o<?php echo $socios_list->RowIndex ?>_activo" value="<?php echo ew_HtmlEncode($socios->activo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->mail->Visible) { // mail ?>
		<td>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_mail" class="form-group socios_mail">
<input type="text" data-field="x_mail" name="x<?php echo $socios_list->RowIndex ?>_mail" id="x<?php echo $socios_list->RowIndex ?>_mail" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->mail->PlaceHolder) ?>" value="<?php echo $socios->mail->EditValue ?>"<?php echo $socios->mail->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_mail" name="o<?php echo $socios_list->RowIndex ?>_mail" id="o<?php echo $socios_list->RowIndex ?>_mail" value="<?php echo ew_HtmlEncode($socios->mail->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->tel->Visible) { // tel ?>
		<td>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_tel" class="form-group socios_tel">
<input type="text" data-field="x_tel" name="x<?php echo $socios_list->RowIndex ?>_tel" id="x<?php echo $socios_list->RowIndex ?>_tel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->tel->PlaceHolder) ?>" value="<?php echo $socios->tel->EditValue ?>"<?php echo $socios->tel->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_tel" name="o<?php echo $socios_list->RowIndex ?>_tel" id="o<?php echo $socios_list->RowIndex ?>_tel" value="<?php echo ew_HtmlEncode($socios->tel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->cel->Visible) { // cel ?>
		<td>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_cel" class="form-group socios_cel">
<input type="text" data-field="x_cel" name="x<?php echo $socios_list->RowIndex ?>_cel" id="x<?php echo $socios_list->RowIndex ?>_cel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->cel->PlaceHolder) ?>" value="<?php echo $socios->cel->EditValue ?>"<?php echo $socios->cel->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cel" name="o<?php echo $socios_list->RowIndex ?>_cel" id="o<?php echo $socios_list->RowIndex ?>_cel" value="<?php echo ew_HtmlEncode($socios->cel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
		<td>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_cuit_cuil" class="form-group socios_cuit_cuil">
<input type="text" data-field="x_cuit_cuil" name="x<?php echo $socios_list->RowIndex ?>_cuit_cuil" id="x<?php echo $socios_list->RowIndex ?>_cuit_cuil" size="30" maxlength="14" placeholder="<?php echo ew_HtmlEncode($socios->cuit_cuil->PlaceHolder) ?>" value="<?php echo $socios->cuit_cuil->EditValue ?>"<?php echo $socios->cuit_cuil->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cuit_cuil" name="o<?php echo $socios_list->RowIndex ?>_cuit_cuil" id="o<?php echo $socios_list->RowIndex ?>_cuit_cuil" value="<?php echo ew_HtmlEncode($socios->cuit_cuil->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_list->ListOptions->Render("body", "right", $socios_list->RowCnt);
?>
<script type="text/javascript">
fsocioslist.UpdateOpts(<?php echo $socios_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($socios->ExportAll && $socios->Export <> "") {
	$socios_list->StopRec = $socios_list->TotalRecs;
} else {

	// Set the last record to display
	if ($socios_list->TotalRecs > $socios_list->StartRec + $socios_list->DisplayRecs - 1)
		$socios_list->StopRec = $socios_list->StartRec + $socios_list->DisplayRecs - 1;
	else
		$socios_list->StopRec = $socios_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($socios_list->FormKeyCountName) && ($socios->CurrentAction == "gridadd" || $socios->CurrentAction == "gridedit" || $socios->CurrentAction == "F")) {
		$socios_list->KeyCount = $objForm->GetValue($socios_list->FormKeyCountName);
		$socios_list->StopRec = $socios_list->StartRec + $socios_list->KeyCount - 1;
	}
}
$socios_list->RecCnt = $socios_list->StartRec - 1;
if ($socios_list->Recordset && !$socios_list->Recordset->EOF) {
	$socios_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $socios_list->StartRec > 1)
		$socios_list->Recordset->Move($socios_list->StartRec - 1);
} elseif (!$socios->AllowAddDeleteRow && $socios_list->StopRec == 0) {
	$socios_list->StopRec = $socios->GridAddRowCount;
}

// Initialize aggregate
$socios->RowType = EW_ROWTYPE_AGGREGATEINIT;
$socios->ResetAttrs();
$socios_list->RenderRow();
$socios_list->EditRowCnt = 0;
if ($socios->CurrentAction == "edit")
	$socios_list->RowIndex = 1;
if ($socios->CurrentAction == "gridadd")
	$socios_list->RowIndex = 0;
if ($socios->CurrentAction == "gridedit")
	$socios_list->RowIndex = 0;
while ($socios_list->RecCnt < $socios_list->StopRec) {
	$socios_list->RecCnt++;
	if (intval($socios_list->RecCnt) >= intval($socios_list->StartRec)) {
		$socios_list->RowCnt++;
		if ($socios->CurrentAction == "gridadd" || $socios->CurrentAction == "gridedit" || $socios->CurrentAction == "F") {
			$socios_list->RowIndex++;
			$objForm->Index = $socios_list->RowIndex;
			if ($objForm->HasValue($socios_list->FormActionName))
				$socios_list->RowAction = strval($objForm->GetValue($socios_list->FormActionName));
			elseif ($socios->CurrentAction == "gridadd")
				$socios_list->RowAction = "insert";
			else
				$socios_list->RowAction = "";
		}

		// Set up key count
		$socios_list->KeyCount = $socios_list->RowIndex;

		// Init row class and style
		$socios->ResetAttrs();
		$socios->CssClass = "";
		if ($socios->CurrentAction == "gridadd") {
			$socios_list->LoadDefaultValues(); // Load default values
		} else {
			$socios_list->LoadRowValues($socios_list->Recordset); // Load row values
		}
		$socios->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($socios->CurrentAction == "gridadd") // Grid add
			$socios->RowType = EW_ROWTYPE_ADD; // Render add
		if ($socios->CurrentAction == "gridadd" && $socios->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$socios_list->RestoreCurrentRowFormValues($socios_list->RowIndex); // Restore form values
		if ($socios->CurrentAction == "edit") {
			if ($socios_list->CheckInlineEditKey() && $socios_list->EditRowCnt == 0) { // Inline edit
				$socios->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($socios->CurrentAction == "gridedit") { // Grid edit
			if ($socios->EventCancelled) {
				$socios_list->RestoreCurrentRowFormValues($socios_list->RowIndex); // Restore form values
			}
			if ($socios_list->RowAction == "insert")
				$socios->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$socios->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($socios->CurrentAction == "edit" && $socios->RowType == EW_ROWTYPE_EDIT && $socios->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$socios_list->RestoreFormValues(); // Restore form values
		}
		if ($socios->CurrentAction == "gridedit" && ($socios->RowType == EW_ROWTYPE_EDIT || $socios->RowType == EW_ROWTYPE_ADD) && $socios->EventCancelled) // Update failed
			$socios_list->RestoreCurrentRowFormValues($socios_list->RowIndex); // Restore form values
		if ($socios->RowType == EW_ROWTYPE_EDIT) // Edit row
			$socios_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$socios->RowAttrs = array_merge($socios->RowAttrs, array('data-rowindex'=>$socios_list->RowCnt, 'id'=>'r' . $socios_list->RowCnt . '_socios', 'data-rowtype'=>$socios->RowType));

		// Render row
		$socios_list->RenderRow();

		// Render list options
		$socios_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($socios_list->RowAction <> "delete" && $socios_list->RowAction <> "insertdelete" && !($socios_list->RowAction == "insert" && $socios->CurrentAction == "F" && $socios_list->EmptyRow())) {
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_list->ListOptions->Render("body", "left", $socios_list->RowCnt);
?>
	<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
		<td data-name="socio_nro"<?php echo $socios->socio_nro->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_socio_nro" name="o<?php echo $socios_list->RowIndex ?>_socio_nro" id="o<?php echo $socios_list->RowIndex ?>_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_socio_nro" class="form-group socios_socio_nro">
<span<?php echo $socios->socio_nro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->socio_nro->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_socio_nro" name="x<?php echo $socios_list->RowIndex ?>_socio_nro" id="x<?php echo $socios_list->RowIndex ?>_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->CurrentValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->socio_nro->ViewAttributes() ?>>
<?php echo $socios->socio_nro->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $socios_list->PageObjName . "_row_" . $socios_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($socios->propietario->Visible) { // propietario ?>
		<td data-name="propietario"<?php echo $socios->propietario->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_propietario" class="form-group socios_propietario">
<input type="text" data-field="x_propietario" name="x<?php echo $socios_list->RowIndex ?>_propietario" id="x<?php echo $socios_list->RowIndex ?>_propietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->propietario->PlaceHolder) ?>" value="<?php echo $socios->propietario->EditValue ?>"<?php echo $socios->propietario->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_propietario" name="o<?php echo $socios_list->RowIndex ?>_propietario" id="o<?php echo $socios_list->RowIndex ?>_propietario" value="<?php echo ew_HtmlEncode($socios->propietario->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_propietario" class="form-group socios_propietario">
<input type="text" data-field="x_propietario" name="x<?php echo $socios_list->RowIndex ?>_propietario" id="x<?php echo $socios_list->RowIndex ?>_propietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->propietario->PlaceHolder) ?>" value="<?php echo $socios->propietario->EditValue ?>"<?php echo $socios->propietario->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->propietario->ViewAttributes() ?>>
<?php echo $socios->propietario->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->comercio->Visible) { // comercio ?>
		<td data-name="comercio"<?php echo $socios->comercio->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_comercio" class="form-group socios_comercio">
<input type="text" data-field="x_comercio" name="x<?php echo $socios_list->RowIndex ?>_comercio" id="x<?php echo $socios_list->RowIndex ?>_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->comercio->PlaceHolder) ?>" value="<?php echo $socios->comercio->EditValue ?>"<?php echo $socios->comercio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_comercio" name="o<?php echo $socios_list->RowIndex ?>_comercio" id="o<?php echo $socios_list->RowIndex ?>_comercio" value="<?php echo ew_HtmlEncode($socios->comercio->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_comercio" class="form-group socios_comercio">
<input type="text" data-field="x_comercio" name="x<?php echo $socios_list->RowIndex ?>_comercio" id="x<?php echo $socios_list->RowIndex ?>_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->comercio->PlaceHolder) ?>" value="<?php echo $socios->comercio->EditValue ?>"<?php echo $socios->comercio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->comercio->ViewAttributes() ?>>
<?php echo $socios->comercio->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
		<td data-name="direccion_comercio"<?php echo $socios->direccion_comercio->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_direccion_comercio" class="form-group socios_direccion_comercio">
<input type="text" data-field="x_direccion_comercio" name="x<?php echo $socios_list->RowIndex ?>_direccion_comercio" id="x<?php echo $socios_list->RowIndex ?>_direccion_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->direccion_comercio->PlaceHolder) ?>" value="<?php echo $socios->direccion_comercio->EditValue ?>"<?php echo $socios->direccion_comercio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_direccion_comercio" name="o<?php echo $socios_list->RowIndex ?>_direccion_comercio" id="o<?php echo $socios_list->RowIndex ?>_direccion_comercio" value="<?php echo ew_HtmlEncode($socios->direccion_comercio->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_direccion_comercio" class="form-group socios_direccion_comercio">
<input type="text" data-field="x_direccion_comercio" name="x<?php echo $socios_list->RowIndex ?>_direccion_comercio" id="x<?php echo $socios_list->RowIndex ?>_direccion_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->direccion_comercio->PlaceHolder) ?>" value="<?php echo $socios->direccion_comercio->EditValue ?>"<?php echo $socios->direccion_comercio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->direccion_comercio->ViewAttributes() ?>>
<?php echo $socios->direccion_comercio->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->activo->Visible) { // activo ?>
		<td data-name="activo"<?php echo $socios->activo->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_activo" class="form-group socios_activo">
<div id="tp_x<?php echo $socios_list->RowIndex ?>_activo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_list->RowIndex ?>_activo" id="x<?php echo $socios_list->RowIndex ?>_activo" value="{value}"<?php echo $socios->activo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_list->RowIndex ?>_activo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->activo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->activo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activo" name="x<?php echo $socios_list->RowIndex ?>_activo" id="x<?php echo $socios_list->RowIndex ?>_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $socios->activo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_activo" name="o<?php echo $socios_list->RowIndex ?>_activo" id="o<?php echo $socios_list->RowIndex ?>_activo" value="<?php echo ew_HtmlEncode($socios->activo->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_activo" class="form-group socios_activo">
<div id="tp_x<?php echo $socios_list->RowIndex ?>_activo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_list->RowIndex ?>_activo" id="x<?php echo $socios_list->RowIndex ?>_activo" value="{value}"<?php echo $socios->activo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_list->RowIndex ?>_activo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->activo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->activo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activo" name="x<?php echo $socios_list->RowIndex ?>_activo" id="x<?php echo $socios_list->RowIndex ?>_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $socios->activo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->activo->ViewAttributes() ?>>
<?php echo $socios->activo->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->mail->Visible) { // mail ?>
		<td data-name="mail"<?php echo $socios->mail->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_mail" class="form-group socios_mail">
<input type="text" data-field="x_mail" name="x<?php echo $socios_list->RowIndex ?>_mail" id="x<?php echo $socios_list->RowIndex ?>_mail" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->mail->PlaceHolder) ?>" value="<?php echo $socios->mail->EditValue ?>"<?php echo $socios->mail->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_mail" name="o<?php echo $socios_list->RowIndex ?>_mail" id="o<?php echo $socios_list->RowIndex ?>_mail" value="<?php echo ew_HtmlEncode($socios->mail->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_mail" class="form-group socios_mail">
<input type="text" data-field="x_mail" name="x<?php echo $socios_list->RowIndex ?>_mail" id="x<?php echo $socios_list->RowIndex ?>_mail" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->mail->PlaceHolder) ?>" value="<?php echo $socios->mail->EditValue ?>"<?php echo $socios->mail->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->mail->ViewAttributes() ?>>
<?php echo $socios->mail->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->tel->Visible) { // tel ?>
		<td data-name="tel"<?php echo $socios->tel->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_tel" class="form-group socios_tel">
<input type="text" data-field="x_tel" name="x<?php echo $socios_list->RowIndex ?>_tel" id="x<?php echo $socios_list->RowIndex ?>_tel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->tel->PlaceHolder) ?>" value="<?php echo $socios->tel->EditValue ?>"<?php echo $socios->tel->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_tel" name="o<?php echo $socios_list->RowIndex ?>_tel" id="o<?php echo $socios_list->RowIndex ?>_tel" value="<?php echo ew_HtmlEncode($socios->tel->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_tel" class="form-group socios_tel">
<input type="text" data-field="x_tel" name="x<?php echo $socios_list->RowIndex ?>_tel" id="x<?php echo $socios_list->RowIndex ?>_tel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->tel->PlaceHolder) ?>" value="<?php echo $socios->tel->EditValue ?>"<?php echo $socios->tel->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->tel->ViewAttributes() ?>>
<?php echo $socios->tel->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->cel->Visible) { // cel ?>
		<td data-name="cel"<?php echo $socios->cel->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_cel" class="form-group socios_cel">
<input type="text" data-field="x_cel" name="x<?php echo $socios_list->RowIndex ?>_cel" id="x<?php echo $socios_list->RowIndex ?>_cel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->cel->PlaceHolder) ?>" value="<?php echo $socios->cel->EditValue ?>"<?php echo $socios->cel->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cel" name="o<?php echo $socios_list->RowIndex ?>_cel" id="o<?php echo $socios_list->RowIndex ?>_cel" value="<?php echo ew_HtmlEncode($socios->cel->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_cel" class="form-group socios_cel">
<input type="text" data-field="x_cel" name="x<?php echo $socios_list->RowIndex ?>_cel" id="x<?php echo $socios_list->RowIndex ?>_cel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->cel->PlaceHolder) ?>" value="<?php echo $socios->cel->EditValue ?>"<?php echo $socios->cel->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->cel->ViewAttributes() ?>>
<?php echo $socios->cel->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
		<td data-name="cuit_cuil"<?php echo $socios->cuit_cuil->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_cuit_cuil" class="form-group socios_cuit_cuil">
<input type="text" data-field="x_cuit_cuil" name="x<?php echo $socios_list->RowIndex ?>_cuit_cuil" id="x<?php echo $socios_list->RowIndex ?>_cuit_cuil" size="30" maxlength="14" placeholder="<?php echo ew_HtmlEncode($socios->cuit_cuil->PlaceHolder) ?>" value="<?php echo $socios->cuit_cuil->EditValue ?>"<?php echo $socios->cuit_cuil->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cuit_cuil" name="o<?php echo $socios_list->RowIndex ?>_cuit_cuil" id="o<?php echo $socios_list->RowIndex ?>_cuit_cuil" value="<?php echo ew_HtmlEncode($socios->cuit_cuil->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_list->RowCnt ?>_socios_cuit_cuil" class="form-group socios_cuit_cuil">
<input type="text" data-field="x_cuit_cuil" name="x<?php echo $socios_list->RowIndex ?>_cuit_cuil" id="x<?php echo $socios_list->RowIndex ?>_cuit_cuil" size="30" maxlength="14" placeholder="<?php echo ew_HtmlEncode($socios->cuit_cuil->PlaceHolder) ?>" value="<?php echo $socios->cuit_cuil->EditValue ?>"<?php echo $socios->cuit_cuil->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->cuit_cuil->ViewAttributes() ?>>
<?php echo $socios->cuit_cuil->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_list->ListOptions->Render("body", "right", $socios_list->RowCnt);
?>
	</tr>
<?php if ($socios->RowType == EW_ROWTYPE_ADD || $socios->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsocioslist.UpdateOpts(<?php echo $socios_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($socios->CurrentAction <> "gridadd")
		if (!$socios_list->Recordset->EOF) $socios_list->Recordset->MoveNext();
}
?>
<?php
	if ($socios->CurrentAction == "gridadd" || $socios->CurrentAction == "gridedit") {
		$socios_list->RowIndex = '$rowindex$';
		$socios_list->LoadDefaultValues();

		// Set row properties
		$socios->ResetAttrs();
		$socios->RowAttrs = array_merge($socios->RowAttrs, array('data-rowindex'=>$socios_list->RowIndex, 'id'=>'r0_socios', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($socios->RowAttrs["class"], "ewTemplate");
		$socios->RowType = EW_ROWTYPE_ADD;

		// Render row
		$socios_list->RenderRow();

		// Render list options
		$socios_list->RenderListOptions();
		$socios_list->StartRowCnt = 0;
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_list->ListOptions->Render("body", "left", $socios_list->RowIndex);
?>
	<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
		<td>
<input type="hidden" data-field="x_socio_nro" name="o<?php echo $socios_list->RowIndex ?>_socio_nro" id="o<?php echo $socios_list->RowIndex ?>_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->propietario->Visible) { // propietario ?>
		<td>
<span id="el$rowindex$_socios_propietario" class="form-group socios_propietario">
<input type="text" data-field="x_propietario" name="x<?php echo $socios_list->RowIndex ?>_propietario" id="x<?php echo $socios_list->RowIndex ?>_propietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->propietario->PlaceHolder) ?>" value="<?php echo $socios->propietario->EditValue ?>"<?php echo $socios->propietario->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_propietario" name="o<?php echo $socios_list->RowIndex ?>_propietario" id="o<?php echo $socios_list->RowIndex ?>_propietario" value="<?php echo ew_HtmlEncode($socios->propietario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->comercio->Visible) { // comercio ?>
		<td>
<span id="el$rowindex$_socios_comercio" class="form-group socios_comercio">
<input type="text" data-field="x_comercio" name="x<?php echo $socios_list->RowIndex ?>_comercio" id="x<?php echo $socios_list->RowIndex ?>_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->comercio->PlaceHolder) ?>" value="<?php echo $socios->comercio->EditValue ?>"<?php echo $socios->comercio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_comercio" name="o<?php echo $socios_list->RowIndex ?>_comercio" id="o<?php echo $socios_list->RowIndex ?>_comercio" value="<?php echo ew_HtmlEncode($socios->comercio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
		<td>
<span id="el$rowindex$_socios_direccion_comercio" class="form-group socios_direccion_comercio">
<input type="text" data-field="x_direccion_comercio" name="x<?php echo $socios_list->RowIndex ?>_direccion_comercio" id="x<?php echo $socios_list->RowIndex ?>_direccion_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->direccion_comercio->PlaceHolder) ?>" value="<?php echo $socios->direccion_comercio->EditValue ?>"<?php echo $socios->direccion_comercio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_direccion_comercio" name="o<?php echo $socios_list->RowIndex ?>_direccion_comercio" id="o<?php echo $socios_list->RowIndex ?>_direccion_comercio" value="<?php echo ew_HtmlEncode($socios->direccion_comercio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->activo->Visible) { // activo ?>
		<td>
<span id="el$rowindex$_socios_activo" class="form-group socios_activo">
<div id="tp_x<?php echo $socios_list->RowIndex ?>_activo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_list->RowIndex ?>_activo" id="x<?php echo $socios_list->RowIndex ?>_activo" value="{value}"<?php echo $socios->activo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_list->RowIndex ?>_activo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->activo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->activo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activo" name="x<?php echo $socios_list->RowIndex ?>_activo" id="x<?php echo $socios_list->RowIndex ?>_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $socios->activo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_activo" name="o<?php echo $socios_list->RowIndex ?>_activo" id="o<?php echo $socios_list->RowIndex ?>_activo" value="<?php echo ew_HtmlEncode($socios->activo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->mail->Visible) { // mail ?>
		<td>
<span id="el$rowindex$_socios_mail" class="form-group socios_mail">
<input type="text" data-field="x_mail" name="x<?php echo $socios_list->RowIndex ?>_mail" id="x<?php echo $socios_list->RowIndex ?>_mail" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->mail->PlaceHolder) ?>" value="<?php echo $socios->mail->EditValue ?>"<?php echo $socios->mail->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_mail" name="o<?php echo $socios_list->RowIndex ?>_mail" id="o<?php echo $socios_list->RowIndex ?>_mail" value="<?php echo ew_HtmlEncode($socios->mail->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->tel->Visible) { // tel ?>
		<td>
<span id="el$rowindex$_socios_tel" class="form-group socios_tel">
<input type="text" data-field="x_tel" name="x<?php echo $socios_list->RowIndex ?>_tel" id="x<?php echo $socios_list->RowIndex ?>_tel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->tel->PlaceHolder) ?>" value="<?php echo $socios->tel->EditValue ?>"<?php echo $socios->tel->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_tel" name="o<?php echo $socios_list->RowIndex ?>_tel" id="o<?php echo $socios_list->RowIndex ?>_tel" value="<?php echo ew_HtmlEncode($socios->tel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->cel->Visible) { // cel ?>
		<td>
<span id="el$rowindex$_socios_cel" class="form-group socios_cel">
<input type="text" data-field="x_cel" name="x<?php echo $socios_list->RowIndex ?>_cel" id="x<?php echo $socios_list->RowIndex ?>_cel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->cel->PlaceHolder) ?>" value="<?php echo $socios->cel->EditValue ?>"<?php echo $socios->cel->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cel" name="o<?php echo $socios_list->RowIndex ?>_cel" id="o<?php echo $socios_list->RowIndex ?>_cel" value="<?php echo ew_HtmlEncode($socios->cel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
		<td>
<span id="el$rowindex$_socios_cuit_cuil" class="form-group socios_cuit_cuil">
<input type="text" data-field="x_cuit_cuil" name="x<?php echo $socios_list->RowIndex ?>_cuit_cuil" id="x<?php echo $socios_list->RowIndex ?>_cuit_cuil" size="30" maxlength="14" placeholder="<?php echo ew_HtmlEncode($socios->cuit_cuil->PlaceHolder) ?>" value="<?php echo $socios->cuit_cuil->EditValue ?>"<?php echo $socios->cuit_cuil->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cuit_cuil" name="o<?php echo $socios_list->RowIndex ?>_cuit_cuil" id="o<?php echo $socios_list->RowIndex ?>_cuit_cuil" value="<?php echo ew_HtmlEncode($socios->cuit_cuil->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_list->ListOptions->Render("body", "right", $socios_list->RowCnt);
?>
<script type="text/javascript">
fsocioslist.UpdateOpts(<?php echo $socios_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($socios->CurrentAction == "add" || $socios->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $socios_list->FormKeyCountName ?>" id="<?php echo $socios_list->FormKeyCountName ?>" value="<?php echo $socios_list->KeyCount ?>">
<?php } ?>
<?php if ($socios->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $socios_list->FormKeyCountName ?>" id="<?php echo $socios_list->FormKeyCountName ?>" value="<?php echo $socios_list->KeyCount ?>">
<?php echo $socios_list->MultiSelectKey ?>
<?php } ?>
<?php if ($socios->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $socios_list->FormKeyCountName ?>" id="<?php echo $socios_list->FormKeyCountName ?>" value="<?php echo $socios_list->KeyCount ?>">
<?php } ?>
<?php if ($socios->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $socios_list->FormKeyCountName ?>" id="<?php echo $socios_list->FormKeyCountName ?>" value="<?php echo $socios_list->KeyCount ?>">
<?php echo $socios_list->MultiSelectKey ?>
<?php } ?>
<?php if ($socios->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($socios_list->Recordset)
	$socios_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($socios_list->TotalRecs == 0 && $socios->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($socios_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">
fsocioslistsrch.Init();
fsocioslist.Init();
</script>
<?php } ?>
<?php
$socios_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_footer.php" ?>
<?php
$socios_list->Page_Terminate();
?>
