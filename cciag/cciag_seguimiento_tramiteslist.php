<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_seguimiento_tramitesinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_tramitesinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$seguimiento_tramites_list = NULL; // Initialize page object first

class cseguimiento_tramites_list extends cseguimiento_tramites {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'seguimiento_tramites';

	// Page object name
	var $PageObjName = 'seguimiento_tramites_list';

	// Grid form hidden field names
	var $FormName = 'fseguimiento_tramiteslist';
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

		// Table object (seguimiento_tramites)
		if (!isset($GLOBALS["seguimiento_tramites"]) || get_class($GLOBALS["seguimiento_tramites"]) == "cseguimiento_tramites") {
			$GLOBALS["seguimiento_tramites"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["seguimiento_tramites"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "cciag_seguimiento_tramitesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "cciag_seguimiento_tramitesdelete.php";
		$this->MultiUpdateUrl = "cciag_seguimiento_tramitesupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Table object (tramites)
		if (!isset($GLOBALS['tramites'])) $GLOBALS['tramites'] = new ctramites();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'seguimiento_tramites', TRUE);

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
		$this->fecha->Visible = !$this->IsAddOrEdit();
		$this->hora->Visible = !$this->IsAddOrEdit();

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
		global $EW_EXPORT, $seguimiento_tramites;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($seguimiento_tramites);
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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "tramites") {
			global $tramites;
			$rsmaster = $tramites->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("cciag_tramiteslist.php"); // Return to master page
			} else {
				$tramites->LoadListRowValues($rsmaster);
				$tramites->RowType = EW_ROWTYPE_MASTER; // Master row
				$tramites->RenderListRow();
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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("id_tramite", ""); // Clear inline edit key
		$this->setKey("fecha", ""); // Clear inline edit key
		$this->setKey("hora", ""); // Clear inline edit key
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
		if (@$_GET["id_tramite"] <> "") {
			$this->id_tramite->setQueryStringValue($_GET["id_tramite"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if (@$_GET["fecha"] <> "") {
			$this->fecha->setQueryStringValue($_GET["fecha"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if (@$_GET["hora"] <> "") {
			$this->hora->setQueryStringValue($_GET["hora"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("id_tramite", $this->id_tramite->CurrentValue); // Set up inline edit key
				$this->setKey("fecha", $this->fecha->CurrentValue); // Set up inline edit key
				$this->setKey("hora", $this->hora->CurrentValue); // Set up inline edit key
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
		if (strval($this->getKey("id_tramite")) <> strval($this->id_tramite->CurrentValue))
			return FALSE;
		if (strval($this->getKey("fecha")) <> strval($this->fecha->CurrentValue))
			return FALSE;
		if (strval($this->getKey("hora")) <> strval($this->hora->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("cciag_login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["id_tramite"] <> "") {
				$this->id_tramite->setQueryStringValue($_GET["id_tramite"]);
				$this->setKey("id_tramite", $this->id_tramite->CurrentValue); // Set up key
			} else {
				$this->setKey("id_tramite", ""); // Clear key
				$this->CurrentAction = "add";
			}
			if (@$_GET["fecha"] <> "") {
				$this->fecha->setQueryStringValue($_GET["fecha"]);
				$this->setKey("fecha", $this->fecha->CurrentValue); // Set up key
			} else {
				$this->setKey("fecha", ""); // Clear key
				$this->CurrentAction = "add";
			}
			if (@$_GET["hora"] <> "") {
				$this->hora->setQueryStringValue($_GET["hora"]);
				$this->setKey("hora", $this->hora->CurrentValue); // Set up key
			} else {
				$this->setKey("hora", ""); // Clear key
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
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
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
		if (count($arrKeyFlds) >= 3) {
			$this->id_tramite->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_tramite->FormValue))
				return FALSE;
			$this->fecha->setFormValue($arrKeyFlds[1]);
			$this->hora->setFormValue($arrKeyFlds[2]);
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
					$sKey .= $this->id_tramite->CurrentValue;
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->fecha->CurrentValue;
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->hora->CurrentValue;

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
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_id_tramite") && $objForm->HasValue("o_id_tramite") && $this->id_tramite->CurrentValue <> $this->id_tramite->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_titulo") && $objForm->HasValue("o_titulo") && $this->titulo->CurrentValue <> $this->titulo->OldValue)
			return FALSE;
		if (!ew_Empty($this->archivo->Upload->Value))
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
		$this->BuildSearchSql($sWhere, $this->id_tramite, $Default, FALSE); // id_tramite
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha
		$this->BuildSearchSql($sWhere, $this->hora, $Default, FALSE); // hora
		$this->BuildSearchSql($sWhere, $this->titulo, $Default, FALSE); // titulo
		$this->BuildSearchSql($sWhere, $this->descripcion, $Default, FALSE); // descripcion
		$this->BuildSearchSql($sWhere, $this->archivo, $Default, FALSE); // archivo

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id_tramite->AdvancedSearch->Save(); // id_tramite
			$this->fecha->AdvancedSearch->Save(); // fecha
			$this->hora->AdvancedSearch->Save(); // hora
			$this->titulo->AdvancedSearch->Save(); // titulo
			$this->descripcion->AdvancedSearch->Save(); // descripcion
			$this->archivo->AdvancedSearch->Save(); // archivo
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
		$this->BuildBasicSearchSQL($sWhere, $this->titulo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->descripcion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->archivo, $arKeywords, $type);
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
		if ($this->id_tramite->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->hora->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->titulo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->descripcion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->archivo->AdvancedSearch->IssetSession())
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
		$this->id_tramite->AdvancedSearch->UnsetSession();
		$this->fecha->AdvancedSearch->UnsetSession();
		$this->hora->AdvancedSearch->UnsetSession();
		$this->titulo->AdvancedSearch->UnsetSession();
		$this->descripcion->AdvancedSearch->UnsetSession();
		$this->archivo->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id_tramite->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->hora->AdvancedSearch->Load();
		$this->titulo->AdvancedSearch->Load();
		$this->descripcion->AdvancedSearch->Load();
		$this->archivo->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_tramite); // id_tramite
			$this->UpdateSort($this->fecha); // fecha
			$this->UpdateSort($this->hora); // hora
			$this->UpdateSort($this->titulo); // titulo
			$this->UpdateSort($this->archivo); // archivo
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
				$this->id_tramite->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->id_tramite->setSort("");
				$this->fecha->setSort("");
				$this->hora->setSort("");
				$this->titulo->setSort("");
				$this->archivo->setSort("");
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
				if (is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
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
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->id_tramite->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->fecha->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->hora->CurrentValue) . "\">";
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
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . $Language->Phrase("InlineCopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_tramite->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->fecha->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->hora->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->id_tramite->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->fecha->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->hora->CurrentValue . "\">";
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

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());

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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fseguimiento_tramiteslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
					$item->Visible = FALSE;
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
					$item->Visible = FALSE;
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fseguimiento_tramiteslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere);

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"cciag_seguimiento_tramitessrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->archivo->Upload->Index = $objForm->Index;
		$this->archivo->Upload->UploadFile();
		$this->archivo->CurrentValue = $this->archivo->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id_tramite->CurrentValue = NULL;
		$this->id_tramite->OldValue = $this->id_tramite->CurrentValue;
		$this->fecha->CurrentValue = date("d/m/Y");
		$this->fecha->OldValue = $this->fecha->CurrentValue;
		$this->hora->CurrentValue = NULL;
		$this->hora->OldValue = $this->hora->CurrentValue;
		$this->titulo->CurrentValue = NULL;
		$this->titulo->OldValue = $this->titulo->CurrentValue;
		$this->archivo->Upload->DbValue = NULL;
		$this->archivo->OldValue = $this->archivo->Upload->DbValue;
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
		// id_tramite

		$this->id_tramite->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_tramite"]);
		if ($this->id_tramite->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_tramite->AdvancedSearch->SearchOperator = @$_GET["z_id_tramite"];

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha"]);
		if ($this->fecha->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator = @$_GET["z_fecha"];

		// hora
		$this->hora->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_hora"]);
		if ($this->hora->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->hora->AdvancedSearch->SearchOperator = @$_GET["z_hora"];

		// titulo
		$this->titulo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_titulo"]);
		if ($this->titulo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->titulo->AdvancedSearch->SearchOperator = @$_GET["z_titulo"];

		// descripcion
		$this->descripcion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_descripcion"]);
		if ($this->descripcion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->descripcion->AdvancedSearch->SearchOperator = @$_GET["z_descripcion"];

		// archivo
		$this->archivo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_archivo"]);
		if ($this->archivo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->archivo->AdvancedSearch->SearchOperator = @$_GET["z_archivo"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id_tramite->FldIsDetailKey) {
			$this->id_tramite->setFormValue($objForm->GetValue("x_id_tramite"));
		}
		$this->id_tramite->setOldValue($objForm->GetValue("o_id_tramite"));
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		$this->fecha->setOldValue($objForm->GetValue("o_fecha"));
		if (!$this->hora->FldIsDetailKey) {
			$this->hora->setFormValue($objForm->GetValue("x_hora"));
		}
		$this->hora->setOldValue($objForm->GetValue("o_hora"));
		if (!$this->titulo->FldIsDetailKey) {
			$this->titulo->setFormValue($objForm->GetValue("x_titulo"));
		}
		$this->titulo->setOldValue($objForm->GetValue("o_titulo"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_tramite->CurrentValue = $this->id_tramite->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->hora->CurrentValue = $this->hora->FormValue;
		$this->titulo->CurrentValue = $this->titulo->FormValue;
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
		$this->id_tramite->setDbValue($rs->fields('id_tramite'));
		if (array_key_exists('EV__id_tramite', $rs->fields)) {
			$this->id_tramite->VirtualValue = $rs->fields('EV__id_tramite'); // Set up virtual field value
		} else {
			$this->id_tramite->VirtualValue = ""; // Clear value
		}
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->hora->setDbValue($rs->fields('hora'));
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		$this->archivo->Upload->DbValue = $rs->fields('archivo');
		$this->archivo->CurrentValue = $this->archivo->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_tramite->DbValue = $row['id_tramite'];
		$this->fecha->DbValue = $row['fecha'];
		$this->hora->DbValue = $row['hora'];
		$this->titulo->DbValue = $row['titulo'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->id_usuario->DbValue = $row['id_usuario'];
		$this->archivo->Upload->DbValue = $row['archivo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_tramite")) <> "")
			$this->id_tramite->CurrentValue = $this->getKey("id_tramite"); // id_tramite
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("fecha")) <> "")
			$this->fecha->CurrentValue = $this->getKey("fecha"); // fecha
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("hora")) <> "")
			$this->hora->CurrentValue = $this->getKey("hora"); // hora
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
		// id_tramite
		// fecha
		// hora
		// titulo
		// descripcion
		// id_usuario

		$this->id_usuario->CellCssStyle = "white-space: nowrap;";

		// archivo
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_tramite
			if ($this->id_tramite->VirtualValue <> "") {
				$this->id_tramite->ViewValue = $this->id_tramite->VirtualValue;
			} else {
			if (strval($this->id_tramite->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tramite->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha` AS `Disp2Fld`, `Titulo` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tramites`";
			$sWhereWrk = "";
			$lookuptblfilter = "`estado`<>'F'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tramite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `fecha` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tramite->ViewValue = $rswrk->fields('DispFld');
					$this->id_tramite->ViewValue .= ew_ValueSeparator(1,$this->id_tramite) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
					$this->id_tramite->ViewValue .= ew_ValueSeparator(2,$this->id_tramite) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_tramite->ViewValue = $this->id_tramite->CurrentValue;
				}
			} else {
				$this->id_tramite->ViewValue = NULL;
			}
			}
			$this->id_tramite->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// hora
			$this->hora->ViewValue = $this->hora->CurrentValue;
			$this->hora->ViewCustomAttributes = "";

			// titulo
			$this->titulo->ViewValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// archivo
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->ViewValue = $this->archivo->Upload->DbValue;
			} else {
				$this->archivo->ViewValue = "";
			}
			$this->archivo->ViewCustomAttributes = "";

			// id_tramite
			$this->id_tramite->LinkCustomAttributes = "";
			$this->id_tramite->HrefValue = "";
			$this->id_tramite->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// hora
			$this->hora->LinkCustomAttributes = "";
			$this->hora->HrefValue = "";
			$this->hora->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// archivo
			$this->archivo->LinkCustomAttributes = "";
			$this->archivo->HrefValue = "";
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;
			$this->archivo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_tramite
			$this->id_tramite->EditAttrs["class"] = "form-control";
			$this->id_tramite->EditCustomAttributes = "";
			if ($this->id_tramite->getSessionValue() <> "") {
				$this->id_tramite->CurrentValue = $this->id_tramite->getSessionValue();
				$this->id_tramite->OldValue = $this->id_tramite->CurrentValue;
			if ($this->id_tramite->VirtualValue <> "") {
				$this->id_tramite->ViewValue = $this->id_tramite->VirtualValue;
			} else {
			if (strval($this->id_tramite->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tramite->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha` AS `Disp2Fld`, `Titulo` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tramites`";
			$sWhereWrk = "";
			$lookuptblfilter = "`estado`<>'F'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tramite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `fecha` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tramite->ViewValue = $rswrk->fields('DispFld');
					$this->id_tramite->ViewValue .= ew_ValueSeparator(1,$this->id_tramite) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
					$this->id_tramite->ViewValue .= ew_ValueSeparator(2,$this->id_tramite) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_tramite->ViewValue = $this->id_tramite->CurrentValue;
				}
			} else {
				$this->id_tramite->ViewValue = NULL;
			}
			}
			$this->id_tramite->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->id_tramite->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tramite->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha` AS `Disp2Fld`, `Titulo` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tramites`";
			$sWhereWrk = "";
			$lookuptblfilter = "`estado`<>'F'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tramite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `fecha` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][2] = ew_FormatDateTime($arwrk[$rowcntwrk][2], 7);
			}
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_tramite->EditValue = $arwrk;
			}

			// fecha
			// hora
			// titulo

			$this->titulo->EditAttrs["class"] = "form-control";
			$this->titulo->EditCustomAttributes = "";
			$this->titulo->EditValue = ew_HtmlEncode($this->titulo->CurrentValue);
			$this->titulo->PlaceHolder = ew_RemoveHtml($this->titulo->FldCaption());

			// archivo
			$this->archivo->EditAttrs["class"] = "form-control";
			$this->archivo->EditCustomAttributes = "";
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->EditValue = $this->archivo->Upload->DbValue;
			} else {
				$this->archivo->EditValue = "";
			}
			if (!ew_Empty($this->archivo->CurrentValue))
				$this->archivo->Upload->FileName = $this->archivo->CurrentValue;
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->archivo, $this->RowIndex);

			// Edit refer script
			// id_tramite

			$this->id_tramite->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// hora
			$this->hora->HrefValue = "";

			// titulo
			$this->titulo->HrefValue = "";

			// archivo
			$this->archivo->HrefValue = "";
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_tramite
			$this->id_tramite->EditAttrs["class"] = "form-control";
			$this->id_tramite->EditCustomAttributes = "";
			if ($this->id_tramite->VirtualValue <> "") {
				$this->id_tramite->ViewValue = $this->id_tramite->VirtualValue;
			} else {
			if (strval($this->id_tramite->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tramite->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha` AS `Disp2Fld`, `Titulo` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tramites`";
			$sWhereWrk = "";
			$lookuptblfilter = "`estado`<>'F'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tramite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `fecha` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tramite->EditValue = $rswrk->fields('DispFld');
					$this->id_tramite->EditValue .= ew_ValueSeparator(1,$this->id_tramite) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
					$this->id_tramite->EditValue .= ew_ValueSeparator(2,$this->id_tramite) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_tramite->EditValue = $this->id_tramite->CurrentValue;
				}
			} else {
				$this->id_tramite->EditValue = NULL;
			}
			}
			$this->id_tramite->ViewCustomAttributes = "";

			// fecha
			// hora
			// titulo

			$this->titulo->EditAttrs["class"] = "form-control";
			$this->titulo->EditCustomAttributes = "";
			$this->titulo->EditValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// archivo
			$this->archivo->EditAttrs["class"] = "form-control";
			$this->archivo->EditCustomAttributes = "";
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->EditValue = $this->archivo->Upload->DbValue;
			} else {
				$this->archivo->EditValue = "";
			}
			$this->archivo->ViewCustomAttributes = "";

			// Edit refer script
			// id_tramite

			$this->id_tramite->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// hora
			$this->hora->HrefValue = "";

			// titulo
			$this->titulo->HrefValue = "";

			// archivo
			$this->archivo->HrefValue = "";
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;
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
		if (!$this->id_tramite->FldIsDetailKey && !is_null($this->id_tramite->FormValue) && $this->id_tramite->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_tramite->FldCaption(), $this->id_tramite->ReqErrMsg));
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
				$sThisKey .= $row['id_tramite'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['fecha'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['hora'];
				$this->LoadDbValues($row);
				$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $row['archivo']);
				$FileCount = count($OldFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					@unlink(ew_UploadPathEx(TRUE, $this->archivo->OldUploadPath) . $OldFiles[$i]);
				}
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

			// id_tramite
			// Check referential integrity for master table 'tramites'

			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_tramites();
			$KeyValue = isset($rsnew['id_tramite']) ? $rsnew['id_tramite'] : $rsold['id_tramite'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@codigo@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["tramites"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "tramites", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

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
		$rs->Close();
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Check referential integrity for master table 'tramites'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_tramites();
		if (strval($this->id_tramite->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@codigo@", ew_AdjustSql($this->id_tramite->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["tramites"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "tramites", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// id_tramite
		$this->id_tramite->SetDbValueDef($rsnew, $this->id_tramite->CurrentValue, 0, FALSE);

		// fecha
		$this->fecha->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
		$rsnew['fecha'] = &$this->fecha->DbValue;

		// hora
		$this->hora->SetDbValueDef($rsnew, ew_CurrentTime(), ew_CurrentTime());
		$rsnew['hora'] = &$this->hora->DbValue;

		// titulo
		$this->titulo->SetDbValueDef($rsnew, $this->titulo->CurrentValue, NULL, FALSE);

		// archivo
		if (!$this->archivo->Upload->KeepFile) {
			$this->archivo->Upload->DbValue = ""; // No need to delete old file
			if ($this->archivo->Upload->FileName == "") {
				$rsnew['archivo'] = NULL;
			} else {
				$rsnew['archivo'] = $this->archivo->Upload->FileName;
			}
		}
		if (!$this->archivo->Upload->KeepFile) {
			$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->archivo->Upload->DbValue);
			if (!ew_Empty($this->archivo->Upload->FileName)) {
				$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->archivo->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->archivo->Upload->Index < 0) ? $this->archivo->FldVar : substr($this->archivo->FldVar, 0, 1) . $this->archivo->Upload->Index . substr($this->archivo->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $file)) {
							if (!in_array($file, $OldFiles)) {
								$file1 = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->archivo->UploadPath), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $file1)) // Make sure did not clash with existing upload file
										$file1 = ew_UniqueFilename(ew_UploadPathEx(TRUE, $this->archivo->UploadPath), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $file, ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
				}
				$this->archivo->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$rsnew['archivo'] = $this->archivo->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['id_tramite']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['fecha']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['hora']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->archivo->Upload->KeepFile) {
					$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->archivo->Upload->DbValue);
					if (!ew_Empty($this->archivo->Upload->FileName)) {
						$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->archivo->Upload->FileName);
						$NewFiles2 = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $rsnew['archivo']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->archivo->Upload->Index < 0) ? $this->archivo->FldVar : substr($this->archivo->FldVar, 0, 1) . $this->archivo->Upload->Index . substr($this->archivo->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->archivo->Upload->SaveToFile($this->archivo->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE, $i); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->archivo->OldUploadPath) . $OldFiles[$i]);
					}
				}
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
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// archivo
		ew_CleanUploadTempPath($this->archivo, $this->archivo->Upload->Index);
		return $AddRow;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id_tramite->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->hora->AdvancedSearch->Load();
		$this->titulo->AdvancedSearch->Load();
		$this->descripcion->AdvancedSearch->Load();
		$this->archivo->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_seguimiento_tramites\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_seguimiento_tramites',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fseguimiento_tramiteslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "tramites") {
			global $tramites;
			if (!isset($tramites)) $tramites = new ctramites;
			$rsmaster = $tramites->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$tramites->ExportDocument($Doc, $rsmaster, 1, 1);
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
		$this->AddSearchQueryString($sQry, $this->id_tramite); // id_tramite
		$this->AddSearchQueryString($sQry, $this->fecha); // fecha
		$this->AddSearchQueryString($sQry, $this->hora); // hora
		$this->AddSearchQueryString($sQry, $this->titulo); // titulo
		$this->AddSearchQueryString($sQry, $this->descripcion); // descripcion

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
			if ($sMasterTblVar == "tramites") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_codigo"] <> "") {
					$GLOBALS["tramites"]->codigo->setQueryStringValue($_GET["fk_codigo"]);
					$this->id_tramite->setQueryStringValue($GLOBALS["tramites"]->codigo->QueryStringValue);
					$this->id_tramite->setSessionValue($this->id_tramite->QueryStringValue);
					if (!is_numeric($GLOBALS["tramites"]->codigo->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "tramites") {
				if ($this->id_tramite->QueryStringValue == "") $this->id_tramite->setSessionValue("");
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
if (!isset($seguimiento_tramites_list)) $seguimiento_tramites_list = new cseguimiento_tramites_list();

// Page init
$seguimiento_tramites_list->Page_Init();

// Page main
$seguimiento_tramites_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$seguimiento_tramites_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_header.php" ?>
<?php if ($seguimiento_tramites->Export == "") { ?>
<script type="text/javascript">

// Page object
var seguimiento_tramites_list = new ew_Page("seguimiento_tramites_list");
seguimiento_tramites_list.PageID = "list"; // Page ID
var EW_PAGE_ID = seguimiento_tramites_list.PageID; // For backward compatibility

// Form object
var fseguimiento_tramiteslist = new ew_Form("fseguimiento_tramiteslist");
fseguimiento_tramiteslist.FormKeyCountName = '<?php echo $seguimiento_tramites_list->FormKeyCountName ?>';

// Validate form
fseguimiento_tramiteslist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_tramite");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $seguimiento_tramites->id_tramite->FldCaption(), $seguimiento_tramites->id_tramite->ReqErrMsg)) ?>");

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
fseguimiento_tramiteslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_tramite", false)) return false;
	if (ew_ValueChanged(fobj, infix, "titulo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "archivo", false)) return false;
	return true;
}

// Form_CustomValidate event
fseguimiento_tramiteslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fseguimiento_tramiteslist.ValidateRequired = true;
<?php } else { ?>
fseguimiento_tramiteslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fseguimiento_tramiteslist.Lists["x_id_tramite"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_fecha","x_Titulo",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fseguimiento_tramiteslistsrch = new ew_Form("fseguimiento_tramiteslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($seguimiento_tramites->Export == "") { ?>
<div class="ewToolbar">
<?php if ($seguimiento_tramites->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($seguimiento_tramites_list->TotalRecs > 0 && $seguimiento_tramites->getCurrentMasterTable() == "" && $seguimiento_tramites_list->ExportOptions->Visible()) { ?>
<?php $seguimiento_tramites_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($seguimiento_tramites_list->SearchOptions->Visible()) { ?>
<?php $seguimiento_tramites_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($seguimiento_tramites->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($seguimiento_tramites->Export == "") || (EW_EXPORT_MASTER_RECORD && $seguimiento_tramites->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "cciag_tramiteslist.php";
if ($seguimiento_tramites_list->DbMasterFilter <> "" && $seguimiento_tramites->getCurrentMasterTable() == "tramites") {
	if ($seguimiento_tramites_list->MasterRecordExists) {
		if ($seguimiento_tramites->getCurrentMasterTable() == $seguimiento_tramites->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($seguimiento_tramites_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $seguimiento_tramites_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_tramitesmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
if ($seguimiento_tramites->CurrentAction == "gridadd") {
	$seguimiento_tramites->CurrentFilter = "0=1";
	$seguimiento_tramites_list->StartRec = 1;
	$seguimiento_tramites_list->DisplayRecs = $seguimiento_tramites->GridAddRowCount;
	$seguimiento_tramites_list->TotalRecs = $seguimiento_tramites_list->DisplayRecs;
	$seguimiento_tramites_list->StopRec = $seguimiento_tramites_list->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$seguimiento_tramites_list->TotalRecs = $seguimiento_tramites->SelectRecordCount();
	} else {
		if ($seguimiento_tramites_list->Recordset = $seguimiento_tramites_list->LoadRecordset())
			$seguimiento_tramites_list->TotalRecs = $seguimiento_tramites_list->Recordset->RecordCount();
	}
	$seguimiento_tramites_list->StartRec = 1;
	if ($seguimiento_tramites_list->DisplayRecs <= 0 || ($seguimiento_tramites->Export <> "" && $seguimiento_tramites->ExportAll)) // Display all records
		$seguimiento_tramites_list->DisplayRecs = $seguimiento_tramites_list->TotalRecs;
	if (!($seguimiento_tramites->Export <> "" && $seguimiento_tramites->ExportAll))
		$seguimiento_tramites_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$seguimiento_tramites_list->Recordset = $seguimiento_tramites_list->LoadRecordset($seguimiento_tramites_list->StartRec-1, $seguimiento_tramites_list->DisplayRecs);

	// Set no record found message
	if ($seguimiento_tramites->CurrentAction == "" && $seguimiento_tramites_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$seguimiento_tramites_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($seguimiento_tramites_list->SearchWhere == "0=101")
			$seguimiento_tramites_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$seguimiento_tramites_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$seguimiento_tramites_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($seguimiento_tramites->Export == "" && $seguimiento_tramites->CurrentAction == "") { ?>
<form name="fseguimiento_tramiteslistsrch" id="fseguimiento_tramiteslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($seguimiento_tramites_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fseguimiento_tramiteslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="seguimiento_tramites">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($seguimiento_tramites_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($seguimiento_tramites_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $seguimiento_tramites_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($seguimiento_tramites_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($seguimiento_tramites_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($seguimiento_tramites_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($seguimiento_tramites_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $seguimiento_tramites_list->ShowPageHeader(); ?>
<?php
$seguimiento_tramites_list->ShowMessage();
?>
<?php if ($seguimiento_tramites_list->TotalRecs > 0 || $seguimiento_tramites->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($seguimiento_tramites->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($seguimiento_tramites->CurrentAction <> "gridadd" && $seguimiento_tramites->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($seguimiento_tramites_list->Pager)) $seguimiento_tramites_list->Pager = new cPrevNextPager($seguimiento_tramites_list->StartRec, $seguimiento_tramites_list->DisplayRecs, $seguimiento_tramites_list->TotalRecs) ?>
<?php if ($seguimiento_tramites_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($seguimiento_tramites_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $seguimiento_tramites_list->PageUrl() ?>start=<?php echo $seguimiento_tramites_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($seguimiento_tramites_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $seguimiento_tramites_list->PageUrl() ?>start=<?php echo $seguimiento_tramites_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $seguimiento_tramites_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($seguimiento_tramites_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $seguimiento_tramites_list->PageUrl() ?>start=<?php echo $seguimiento_tramites_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($seguimiento_tramites_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $seguimiento_tramites_list->PageUrl() ?>start=<?php echo $seguimiento_tramites_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $seguimiento_tramites_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $seguimiento_tramites_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $seguimiento_tramites_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $seguimiento_tramites_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($seguimiento_tramites_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fseguimiento_tramiteslist" id="fseguimiento_tramiteslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($seguimiento_tramites_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $seguimiento_tramites_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="seguimiento_tramites">
<div id="gmp_seguimiento_tramites" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($seguimiento_tramites_list->TotalRecs > 0 || $seguimiento_tramites->CurrentAction == "add" || $seguimiento_tramites->CurrentAction == "copy") { ?>
<table id="tbl_seguimiento_tramiteslist" class="table ewTable">
<?php echo $seguimiento_tramites->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$seguimiento_tramites_list->RenderListOptions();

// Render list options (header, left)
$seguimiento_tramites_list->ListOptions->Render("header", "left");
?>
<?php if ($seguimiento_tramites->id_tramite->Visible) { // id_tramite ?>
	<?php if ($seguimiento_tramites->SortUrl($seguimiento_tramites->id_tramite) == "") { ?>
		<th data-name="id_tramite"><div id="elh_seguimiento_tramites_id_tramite" class="seguimiento_tramites_id_tramite"><div class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->id_tramite->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tramite"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $seguimiento_tramites->SortUrl($seguimiento_tramites->id_tramite) ?>',1);"><div id="elh_seguimiento_tramites_id_tramite" class="seguimiento_tramites_id_tramite">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->id_tramite->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($seguimiento_tramites->id_tramite->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($seguimiento_tramites->id_tramite->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($seguimiento_tramites->fecha->Visible) { // fecha ?>
	<?php if ($seguimiento_tramites->SortUrl($seguimiento_tramites->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_seguimiento_tramites_fecha" class="seguimiento_tramites_fecha"><div class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $seguimiento_tramites->SortUrl($seguimiento_tramites->fecha) ?>',1);"><div id="elh_seguimiento_tramites_fecha" class="seguimiento_tramites_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($seguimiento_tramites->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($seguimiento_tramites->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($seguimiento_tramites->hora->Visible) { // hora ?>
	<?php if ($seguimiento_tramites->SortUrl($seguimiento_tramites->hora) == "") { ?>
		<th data-name="hora"><div id="elh_seguimiento_tramites_hora" class="seguimiento_tramites_hora"><div class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->hora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hora"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $seguimiento_tramites->SortUrl($seguimiento_tramites->hora) ?>',1);"><div id="elh_seguimiento_tramites_hora" class="seguimiento_tramites_hora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->hora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($seguimiento_tramites->hora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($seguimiento_tramites->hora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($seguimiento_tramites->titulo->Visible) { // titulo ?>
	<?php if ($seguimiento_tramites->SortUrl($seguimiento_tramites->titulo) == "") { ?>
		<th data-name="titulo"><div id="elh_seguimiento_tramites_titulo" class="seguimiento_tramites_titulo"><div class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->titulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="titulo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $seguimiento_tramites->SortUrl($seguimiento_tramites->titulo) ?>',1);"><div id="elh_seguimiento_tramites_titulo" class="seguimiento_tramites_titulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->titulo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($seguimiento_tramites->titulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($seguimiento_tramites->titulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($seguimiento_tramites->archivo->Visible) { // archivo ?>
	<?php if ($seguimiento_tramites->SortUrl($seguimiento_tramites->archivo) == "") { ?>
		<th data-name="archivo"><div id="elh_seguimiento_tramites_archivo" class="seguimiento_tramites_archivo"><div class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->archivo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="archivo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $seguimiento_tramites->SortUrl($seguimiento_tramites->archivo) ?>',1);"><div id="elh_seguimiento_tramites_archivo" class="seguimiento_tramites_archivo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->archivo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($seguimiento_tramites->archivo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($seguimiento_tramites->archivo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$seguimiento_tramites_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($seguimiento_tramites->CurrentAction == "add" || $seguimiento_tramites->CurrentAction == "copy") {
		$seguimiento_tramites_list->RowIndex = 0;
		$seguimiento_tramites_list->KeyCount = $seguimiento_tramites_list->RowIndex;
		if ($seguimiento_tramites->CurrentAction == "copy" && !$seguimiento_tramites_list->LoadRow())
				$seguimiento_tramites->CurrentAction = "add";
		if ($seguimiento_tramites->CurrentAction == "add")
			$seguimiento_tramites_list->LoadDefaultValues();
		if ($seguimiento_tramites->EventCancelled) // Insert failed
			$seguimiento_tramites_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$seguimiento_tramites->ResetAttrs();
		$seguimiento_tramites->RowAttrs = array_merge($seguimiento_tramites->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_seguimiento_tramites', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$seguimiento_tramites->RowType = EW_ROWTYPE_ADD;

		// Render row
		$seguimiento_tramites_list->RenderRow();

		// Render list options
		$seguimiento_tramites_list->RenderListOptions();
		$seguimiento_tramites_list->StartRowCnt = 0;
?>
	<tr<?php echo $seguimiento_tramites->RowAttributes() ?>>
<?php

// Render list options (body, left)
$seguimiento_tramites_list->ListOptions->Render("body", "left", $seguimiento_tramites_list->RowCnt);
?>
	<?php if ($seguimiento_tramites->id_tramite->Visible) { // id_tramite ?>
		<td>
<?php if ($seguimiento_tramites->id_tramite->getSessionValue() <> "") { ?>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<span<?php echo $seguimiento_tramites->id_tramite->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->id_tramite->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<?php $seguimiento_tramites->id_tramite->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$seguimiento_tramites->id_tramite->EditAttrs["onchange"]; ?>
<select data-field="x_id_tramite" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite"<?php echo $seguimiento_tramites->id_tramite->EditAttributes() ?>>
<?php
if (is_array($seguimiento_tramites->id_tramite->EditValue)) {
	$arwrk = $seguimiento_tramites->id_tramite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($seguimiento_tramites->id_tramite->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$seguimiento_tramites->id_tramite) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$seguimiento_tramites->id_tramite) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $seguimiento_tramites->id_tramite->OldValue = "";
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $seguimiento_tramites->id_tramite->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite',url:'cciag_tramitesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $seguimiento_tramites->id_tramite->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha` AS `Disp2Fld`, `Titulo` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tramites`";
$sWhereWrk = "";
$lookuptblfilter = "`estado`<>'F'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$seguimiento_tramites->Lookup_Selecting($seguimiento_tramites->id_tramite, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `fecha` ASC";
?>
<input type="hidden" name="s_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" id="s_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
<input type="hidden" name="ln_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" id="ln_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_tramite" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->fecha->Visible) { // fecha ?>
		<td>
<input type="hidden" data-field="x_fecha" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_fecha" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($seguimiento_tramites->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->hora->Visible) { // hora ?>
		<td>
<input type="hidden" data-field="x_hora" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_hora" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_hora" value="<?php echo ew_HtmlEncode($seguimiento_tramites->hora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->titulo->Visible) { // titulo ?>
		<td>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_titulo" class="form-group seguimiento_tramites_titulo">
<input type="text" data-field="x_titulo" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->titulo->EditValue ?>"<?php echo $seguimiento_tramites->titulo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_titulo" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->archivo->Visible) { // archivo ?>
		<td>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_archivo" class="form-group seguimiento_tramites_archivo">
<div id="fd_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo">
<span title="<?php echo $seguimiento_tramites->archivo->FldTitle() ? $seguimiento_tramites->archivo->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($seguimiento_tramites->archivo->ReadOnly || $seguimiento_tramites->archivo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_archivo" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" multiple="multiple">
</span>
<input type="hidden" name="fn_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fn_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fa_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="0">
<input type="hidden" name="fs_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fs_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="255">
<input type="hidden" name="fx_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fx_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fm_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fc_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileCount ?>">
</div>
<table id="ft_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_archivo" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->archivo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$seguimiento_tramites_list->ListOptions->Render("body", "right", $seguimiento_tramites_list->RowCnt);
?>
<script type="text/javascript">
fseguimiento_tramiteslist.UpdateOpts(<?php echo $seguimiento_tramites_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($seguimiento_tramites->ExportAll && $seguimiento_tramites->Export <> "") {
	$seguimiento_tramites_list->StopRec = $seguimiento_tramites_list->TotalRecs;
} else {

	// Set the last record to display
	if ($seguimiento_tramites_list->TotalRecs > $seguimiento_tramites_list->StartRec + $seguimiento_tramites_list->DisplayRecs - 1)
		$seguimiento_tramites_list->StopRec = $seguimiento_tramites_list->StartRec + $seguimiento_tramites_list->DisplayRecs - 1;
	else
		$seguimiento_tramites_list->StopRec = $seguimiento_tramites_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($seguimiento_tramites_list->FormKeyCountName) && ($seguimiento_tramites->CurrentAction == "gridadd" || $seguimiento_tramites->CurrentAction == "gridedit" || $seguimiento_tramites->CurrentAction == "F")) {
		$seguimiento_tramites_list->KeyCount = $objForm->GetValue($seguimiento_tramites_list->FormKeyCountName);
		$seguimiento_tramites_list->StopRec = $seguimiento_tramites_list->StartRec + $seguimiento_tramites_list->KeyCount - 1;
	}
}
$seguimiento_tramites_list->RecCnt = $seguimiento_tramites_list->StartRec - 1;
if ($seguimiento_tramites_list->Recordset && !$seguimiento_tramites_list->Recordset->EOF) {
	$seguimiento_tramites_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $seguimiento_tramites_list->StartRec > 1)
		$seguimiento_tramites_list->Recordset->Move($seguimiento_tramites_list->StartRec - 1);
} elseif (!$seguimiento_tramites->AllowAddDeleteRow && $seguimiento_tramites_list->StopRec == 0) {
	$seguimiento_tramites_list->StopRec = $seguimiento_tramites->GridAddRowCount;
}

// Initialize aggregate
$seguimiento_tramites->RowType = EW_ROWTYPE_AGGREGATEINIT;
$seguimiento_tramites->ResetAttrs();
$seguimiento_tramites_list->RenderRow();
$seguimiento_tramites_list->EditRowCnt = 0;
if ($seguimiento_tramites->CurrentAction == "edit")
	$seguimiento_tramites_list->RowIndex = 1;
if ($seguimiento_tramites->CurrentAction == "gridadd")
	$seguimiento_tramites_list->RowIndex = 0;
if ($seguimiento_tramites->CurrentAction == "gridedit")
	$seguimiento_tramites_list->RowIndex = 0;
while ($seguimiento_tramites_list->RecCnt < $seguimiento_tramites_list->StopRec) {
	$seguimiento_tramites_list->RecCnt++;
	if (intval($seguimiento_tramites_list->RecCnt) >= intval($seguimiento_tramites_list->StartRec)) {
		$seguimiento_tramites_list->RowCnt++;
		if ($seguimiento_tramites->CurrentAction == "gridadd" || $seguimiento_tramites->CurrentAction == "gridedit" || $seguimiento_tramites->CurrentAction == "F") {
			$seguimiento_tramites_list->RowIndex++;
			$objForm->Index = $seguimiento_tramites_list->RowIndex;
			if ($objForm->HasValue($seguimiento_tramites_list->FormActionName))
				$seguimiento_tramites_list->RowAction = strval($objForm->GetValue($seguimiento_tramites_list->FormActionName));
			elseif ($seguimiento_tramites->CurrentAction == "gridadd")
				$seguimiento_tramites_list->RowAction = "insert";
			else
				$seguimiento_tramites_list->RowAction = "";
		}

		// Set up key count
		$seguimiento_tramites_list->KeyCount = $seguimiento_tramites_list->RowIndex;

		// Init row class and style
		$seguimiento_tramites->ResetAttrs();
		$seguimiento_tramites->CssClass = "";
		if ($seguimiento_tramites->CurrentAction == "gridadd") {
			$seguimiento_tramites_list->LoadDefaultValues(); // Load default values
		} else {
			$seguimiento_tramites_list->LoadRowValues($seguimiento_tramites_list->Recordset); // Load row values
		}
		$seguimiento_tramites->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($seguimiento_tramites->CurrentAction == "gridadd") // Grid add
			$seguimiento_tramites->RowType = EW_ROWTYPE_ADD; // Render add
		if ($seguimiento_tramites->CurrentAction == "gridadd" && $seguimiento_tramites->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$seguimiento_tramites_list->RestoreCurrentRowFormValues($seguimiento_tramites_list->RowIndex); // Restore form values
		if ($seguimiento_tramites->CurrentAction == "edit") {
			if ($seguimiento_tramites_list->CheckInlineEditKey() && $seguimiento_tramites_list->EditRowCnt == 0) { // Inline edit
				$seguimiento_tramites->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($seguimiento_tramites->CurrentAction == "gridedit") { // Grid edit
			if ($seguimiento_tramites->EventCancelled) {
				$seguimiento_tramites_list->RestoreCurrentRowFormValues($seguimiento_tramites_list->RowIndex); // Restore form values
			}
			if ($seguimiento_tramites_list->RowAction == "insert")
				$seguimiento_tramites->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$seguimiento_tramites->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($seguimiento_tramites->CurrentAction == "edit" && $seguimiento_tramites->RowType == EW_ROWTYPE_EDIT && $seguimiento_tramites->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$seguimiento_tramites_list->RestoreFormValues(); // Restore form values
		}
		if ($seguimiento_tramites->CurrentAction == "gridedit" && ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT || $seguimiento_tramites->RowType == EW_ROWTYPE_ADD) && $seguimiento_tramites->EventCancelled) // Update failed
			$seguimiento_tramites_list->RestoreCurrentRowFormValues($seguimiento_tramites_list->RowIndex); // Restore form values
		if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) // Edit row
			$seguimiento_tramites_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$seguimiento_tramites->RowAttrs = array_merge($seguimiento_tramites->RowAttrs, array('data-rowindex'=>$seguimiento_tramites_list->RowCnt, 'id'=>'r' . $seguimiento_tramites_list->RowCnt . '_seguimiento_tramites', 'data-rowtype'=>$seguimiento_tramites->RowType));

		// Render row
		$seguimiento_tramites_list->RenderRow();

		// Render list options
		$seguimiento_tramites_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($seguimiento_tramites_list->RowAction <> "delete" && $seguimiento_tramites_list->RowAction <> "insertdelete" && !($seguimiento_tramites_list->RowAction == "insert" && $seguimiento_tramites->CurrentAction == "F" && $seguimiento_tramites_list->EmptyRow())) {
?>
	<tr<?php echo $seguimiento_tramites->RowAttributes() ?>>
<?php

// Render list options (body, left)
$seguimiento_tramites_list->ListOptions->Render("body", "left", $seguimiento_tramites_list->RowCnt);
?>
	<?php if ($seguimiento_tramites->id_tramite->Visible) { // id_tramite ?>
		<td data-name="id_tramite"<?php echo $seguimiento_tramites->id_tramite->CellAttributes() ?>>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($seguimiento_tramites->id_tramite->getSessionValue() <> "") { ?>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<span<?php echo $seguimiento_tramites->id_tramite->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->id_tramite->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<?php $seguimiento_tramites->id_tramite->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$seguimiento_tramites->id_tramite->EditAttrs["onchange"]; ?>
<select data-field="x_id_tramite" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite"<?php echo $seguimiento_tramites->id_tramite->EditAttributes() ?>>
<?php
if (is_array($seguimiento_tramites->id_tramite->EditValue)) {
	$arwrk = $seguimiento_tramites->id_tramite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($seguimiento_tramites->id_tramite->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$seguimiento_tramites->id_tramite) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$seguimiento_tramites->id_tramite) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $seguimiento_tramites->id_tramite->OldValue = "";
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $seguimiento_tramites->id_tramite->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite',url:'cciag_tramitesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $seguimiento_tramites->id_tramite->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha` AS `Disp2Fld`, `Titulo` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tramites`";
$sWhereWrk = "";
$lookuptblfilter = "`estado`<>'F'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$seguimiento_tramites->Lookup_Selecting($seguimiento_tramites->id_tramite, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `fecha` ASC";
?>
<input type="hidden" name="s_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" id="s_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
<input type="hidden" name="ln_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" id="ln_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_tramite" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->OldValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<span<?php echo $seguimiento_tramites->id_tramite->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->id_tramite->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_tramite" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->CurrentValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $seguimiento_tramites->id_tramite->ViewAttributes() ?>>
<?php echo $seguimiento_tramites->id_tramite->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $seguimiento_tramites_list->PageObjName . "_row_" . $seguimiento_tramites_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($seguimiento_tramites->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $seguimiento_tramites->fecha->CellAttributes() ?>>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_fecha" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($seguimiento_tramites->fecha->OldValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $seguimiento_tramites->fecha->ViewAttributes() ?>>
<?php echo $seguimiento_tramites->fecha->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->hora->Visible) { // hora ?>
		<td data-name="hora"<?php echo $seguimiento_tramites->hora->CellAttributes() ?>>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_hora" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_hora" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_hora" value="<?php echo ew_HtmlEncode($seguimiento_tramites->hora->OldValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $seguimiento_tramites->hora->ViewAttributes() ?>>
<?php echo $seguimiento_tramites->hora->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->titulo->Visible) { // titulo ?>
		<td data-name="titulo"<?php echo $seguimiento_tramites->titulo->CellAttributes() ?>>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_titulo" class="form-group seguimiento_tramites_titulo">
<input type="text" data-field="x_titulo" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->titulo->EditValue ?>"<?php echo $seguimiento_tramites->titulo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_titulo" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->OldValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_titulo" class="form-group seguimiento_tramites_titulo">
<span<?php echo $seguimiento_tramites->titulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->titulo->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_titulo" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->CurrentValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $seguimiento_tramites->titulo->ViewAttributes() ?>>
<?php echo $seguimiento_tramites->titulo->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->archivo->Visible) { // archivo ?>
		<td data-name="archivo"<?php echo $seguimiento_tramites->archivo->CellAttributes() ?>>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_archivo" class="form-group seguimiento_tramites_archivo">
<div id="fd_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo">
<span title="<?php echo $seguimiento_tramites->archivo->FldTitle() ? $seguimiento_tramites->archivo->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($seguimiento_tramites->archivo->ReadOnly || $seguimiento_tramites->archivo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_archivo" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" multiple="multiple">
</span>
<input type="hidden" name="fn_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fn_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fa_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="0">
<input type="hidden" name="fs_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fs_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="255">
<input type="hidden" name="fx_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fx_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fm_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fc_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileCount ?>">
</div>
<table id="ft_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_archivo" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->archivo->OldValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $seguimiento_tramites_list->RowCnt ?>_seguimiento_tramites_archivo" class="form-group seguimiento_tramites_archivo">
<span<?php echo $seguimiento_tramites->archivo->ViewAttributes() ?>>
<ul class="list-inline"><?php
$Files = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $seguimiento_tramites->archivo->Upload->DbValue);
$HrefValue = $seguimiento_tramites->archivo->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$seguimiento_tramites->archivo->ViewValue = $Files[$i];
$seguimiento_tramites->archivo->HrefValue = str_replace("%u", ew_HtmlEncode(ew_UploadPathEx(FALSE, $seguimiento_tramites->archivo->UploadPath) . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode(ew_UploadPathEx(FALSE, $seguimiento_tramites->archivo->UploadPath) . $Files[$i]), $seguimiento_tramites->archivo->EditValue);
?>
<li>
<?php if ($seguimiento_tramites->archivo->LinkAttributes() <> "") { ?>
<?php if (!empty($seguimiento_tramites->archivo->Upload->DbValue)) { ?>
<?php echo $seguimiento_tramites->archivo->EditValue ?>
<?php } elseif (!in_array($seguimiento_tramites->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($seguimiento_tramites->archivo->Upload->DbValue)) { ?>
<?php echo $seguimiento_tramites->archivo->EditValue ?>
<?php } elseif (!in_array($seguimiento_tramites->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</li>
<?php
}
}
?></ul>
</span>
</span>
<input type="hidden" data-field="x_archivo" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->archivo->CurrentValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $seguimiento_tramites->archivo->ViewAttributes() ?>>
<ul class="list-inline"><?php
$Files = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $seguimiento_tramites->archivo->Upload->DbValue);
$HrefValue = $seguimiento_tramites->archivo->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$seguimiento_tramites->archivo->ViewValue = $Files[$i];
$seguimiento_tramites->archivo->HrefValue = str_replace("%u", ew_HtmlEncode(ew_UploadPathEx(FALSE, $seguimiento_tramites->archivo->UploadPath) . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode(ew_UploadPathEx(FALSE, $seguimiento_tramites->archivo->UploadPath) . $Files[$i]), $seguimiento_tramites->archivo->ListViewValue());
?>
<li>
<?php if ($seguimiento_tramites->archivo->LinkAttributes() <> "") { ?>
<?php if (!empty($seguimiento_tramites->archivo->Upload->DbValue)) { ?>
<?php echo $seguimiento_tramites->archivo->ListViewValue() ?>
<?php } elseif (!in_array($seguimiento_tramites->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($seguimiento_tramites->archivo->Upload->DbValue)) { ?>
<?php echo $seguimiento_tramites->archivo->ListViewValue() ?>
<?php } elseif (!in_array($seguimiento_tramites->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</li>
<?php
}
}
?></ul>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$seguimiento_tramites_list->ListOptions->Render("body", "right", $seguimiento_tramites_list->RowCnt);
?>
	</tr>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD || $seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fseguimiento_tramiteslist.UpdateOpts(<?php echo $seguimiento_tramites_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($seguimiento_tramites->CurrentAction <> "gridadd")
		if (!$seguimiento_tramites_list->Recordset->EOF) $seguimiento_tramites_list->Recordset->MoveNext();
}
?>
<?php
	if ($seguimiento_tramites->CurrentAction == "gridadd" || $seguimiento_tramites->CurrentAction == "gridedit") {
		$seguimiento_tramites_list->RowIndex = '$rowindex$';
		$seguimiento_tramites_list->LoadDefaultValues();

		// Set row properties
		$seguimiento_tramites->ResetAttrs();
		$seguimiento_tramites->RowAttrs = array_merge($seguimiento_tramites->RowAttrs, array('data-rowindex'=>$seguimiento_tramites_list->RowIndex, 'id'=>'r0_seguimiento_tramites', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($seguimiento_tramites->RowAttrs["class"], "ewTemplate");
		$seguimiento_tramites->RowType = EW_ROWTYPE_ADD;

		// Render row
		$seguimiento_tramites_list->RenderRow();

		// Render list options
		$seguimiento_tramites_list->RenderListOptions();
		$seguimiento_tramites_list->StartRowCnt = 0;
?>
	<tr<?php echo $seguimiento_tramites->RowAttributes() ?>>
<?php

// Render list options (body, left)
$seguimiento_tramites_list->ListOptions->Render("body", "left", $seguimiento_tramites_list->RowIndex);
?>
	<?php if ($seguimiento_tramites->id_tramite->Visible) { // id_tramite ?>
		<td>
<?php if ($seguimiento_tramites->id_tramite->getSessionValue() <> "") { ?>
<span id="el$rowindex$_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<span<?php echo $seguimiento_tramites->id_tramite->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->id_tramite->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<?php $seguimiento_tramites->id_tramite->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$seguimiento_tramites->id_tramite->EditAttrs["onchange"]; ?>
<select data-field="x_id_tramite" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite"<?php echo $seguimiento_tramites->id_tramite->EditAttributes() ?>>
<?php
if (is_array($seguimiento_tramites->id_tramite->EditValue)) {
	$arwrk = $seguimiento_tramites->id_tramite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($seguimiento_tramites->id_tramite->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$seguimiento_tramites->id_tramite) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$seguimiento_tramites->id_tramite) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $seguimiento_tramites->id_tramite->OldValue = "";
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $seguimiento_tramites->id_tramite->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite',url:'cciag_tramitesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $seguimiento_tramites->id_tramite->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha` AS `Disp2Fld`, `Titulo` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tramites`";
$sWhereWrk = "";
$lookuptblfilter = "`estado`<>'F'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$seguimiento_tramites->Lookup_Selecting($seguimiento_tramites->id_tramite, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `fecha` ASC";
?>
<input type="hidden" name="s_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" id="s_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
<input type="hidden" name="ln_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" id="ln_x<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_tramite" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->fecha->Visible) { // fecha ?>
		<td>
<input type="hidden" data-field="x_fecha" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_fecha" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($seguimiento_tramites->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->hora->Visible) { // hora ?>
		<td>
<input type="hidden" data-field="x_hora" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_hora" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_hora" value="<?php echo ew_HtmlEncode($seguimiento_tramites->hora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->titulo->Visible) { // titulo ?>
		<td>
<span id="el$rowindex$_seguimiento_tramites_titulo" class="form-group seguimiento_tramites_titulo">
<input type="text" data-field="x_titulo" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->titulo->EditValue ?>"<?php echo $seguimiento_tramites->titulo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_titulo" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->archivo->Visible) { // archivo ?>
		<td>
<span id="el$rowindex$_seguimiento_tramites_archivo" class="form-group seguimiento_tramites_archivo">
<div id="fd_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo">
<span title="<?php echo $seguimiento_tramites->archivo->FldTitle() ? $seguimiento_tramites->archivo->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($seguimiento_tramites->archivo->ReadOnly || $seguimiento_tramites->archivo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_archivo" name="x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id="x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" multiple="multiple">
</span>
<input type="hidden" name="fn_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fn_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fa_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="0">
<input type="hidden" name="fs_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fs_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="255">
<input type="hidden" name="fx_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fx_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fm_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id= "fc_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileCount ?>">
</div>
<table id="ft_x<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_archivo" name="o<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" id="o<?php echo $seguimiento_tramites_list->RowIndex ?>_archivo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->archivo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$seguimiento_tramites_list->ListOptions->Render("body", "right", $seguimiento_tramites_list->RowCnt);
?>
<script type="text/javascript">
fseguimiento_tramiteslist.UpdateOpts(<?php echo $seguimiento_tramites_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($seguimiento_tramites->CurrentAction == "add" || $seguimiento_tramites->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $seguimiento_tramites_list->FormKeyCountName ?>" id="<?php echo $seguimiento_tramites_list->FormKeyCountName ?>" value="<?php echo $seguimiento_tramites_list->KeyCount ?>">
<?php } ?>
<?php if ($seguimiento_tramites->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $seguimiento_tramites_list->FormKeyCountName ?>" id="<?php echo $seguimiento_tramites_list->FormKeyCountName ?>" value="<?php echo $seguimiento_tramites_list->KeyCount ?>">
<?php echo $seguimiento_tramites_list->MultiSelectKey ?>
<?php } ?>
<?php if ($seguimiento_tramites->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $seguimiento_tramites_list->FormKeyCountName ?>" id="<?php echo $seguimiento_tramites_list->FormKeyCountName ?>" value="<?php echo $seguimiento_tramites_list->KeyCount ?>">
<?php } ?>
<?php if ($seguimiento_tramites->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $seguimiento_tramites_list->FormKeyCountName ?>" id="<?php echo $seguimiento_tramites_list->FormKeyCountName ?>" value="<?php echo $seguimiento_tramites_list->KeyCount ?>">
<?php echo $seguimiento_tramites_list->MultiSelectKey ?>
<?php } ?>
<?php if ($seguimiento_tramites->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($seguimiento_tramites_list->Recordset)
	$seguimiento_tramites_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($seguimiento_tramites_list->TotalRecs == 0 && $seguimiento_tramites->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($seguimiento_tramites_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($seguimiento_tramites->Export == "") { ?>
<script type="text/javascript">
fseguimiento_tramiteslistsrch.Init();
fseguimiento_tramiteslist.Init();
</script>
<?php } ?>
<?php
$seguimiento_tramites_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($seguimiento_tramites->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_footer.php" ?>
<?php
$seguimiento_tramites_list->Page_Terminate();
?>
