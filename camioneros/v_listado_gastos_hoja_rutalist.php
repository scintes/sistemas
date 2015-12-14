<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "v_listado_gastos_hoja_rutainfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$v_listado_gastos_hoja_ruta_list = NULL; // Initialize page object first

class cv_listado_gastos_hoja_ruta_list extends cv_listado_gastos_hoja_ruta {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'v_listado_gastos_hoja_ruta';

	// Page object name
	var $PageObjName = 'v_listado_gastos_hoja_ruta_list';

	// Grid form hidden field names
	var $FormName = 'fv_listado_gastos_hoja_rutalist';
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

		// Table object (v_listado_gastos_hoja_ruta)
		if (!isset($GLOBALS["v_listado_gastos_hoja_ruta"]) || get_class($GLOBALS["v_listado_gastos_hoja_ruta"]) == "cv_listado_gastos_hoja_ruta") {
			$GLOBALS["v_listado_gastos_hoja_ruta"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["v_listado_gastos_hoja_ruta"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "v_listado_gastos_hoja_rutaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "v_listado_gastos_hoja_rutadelete.php";
		$this->MultiUpdateUrl = "v_listado_gastos_hoja_rutaupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'v_listado_gastos_hoja_ruta', TRUE);

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
		$this->nro_gasto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->nro_cliente->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $v_listado_gastos_hoja_ruta;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($v_listado_gastos_hoja_ruta);
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
		if (count($arrKeyFlds) >= 2) {
			$this->nro_gasto->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nro_gasto->FormValue))
				return FALSE;
			$this->nro_cliente->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->nro_cliente->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->Patente, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->marca, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->chofer, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->guarda, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Origen, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Destino, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->estado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->detalle_gasto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipo_gasto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->razon_social, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->prov_desde, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->prov_hasta, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->loc_desde, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->loc_hasta, $arKeywords, $type);
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
			$this->UpdateSort($this->Patente); // Patente
			$this->UpdateSort($this->modelo); // modelo
			$this->UpdateSort($this->marca); // marca
			$this->UpdateSort($this->chofer); // chofer
			$this->UpdateSort($this->categoria_chofer); // categoria_chofer
			$this->UpdateSort($this->categoria_guardia); // categoria_guardia
			$this->UpdateSort($this->guarda); // guarda
			$this->UpdateSort($this->Origen); // Origen
			$this->UpdateSort($this->Destino); // Destino
			$this->UpdateSort($this->Km_ini); // Km_ini
			$this->UpdateSort($this->km_fin); // km_fin
			$this->UpdateSort($this->estado); // estado
			$this->UpdateSort($this->fecha_ini); // fecha_ini
			$this->UpdateSort($this->fecha_fin); // fecha_fin
			$this->UpdateSort($this->fecha_gasto); // fecha_gasto
			$this->UpdateSort($this->nro_gasto); // nro_gasto
			$this->UpdateSort($this->detalle_gasto); // detalle_gasto
			$this->UpdateSort($this->importe_gasto); // importe_gasto
			$this->UpdateSort($this->total); // total
			$this->UpdateSort($this->tipo_gasto); // tipo_gasto
			$this->UpdateSort($this->razon_social); // razon_social
			$this->UpdateSort($this->prov_desde); // prov_desde
			$this->UpdateSort($this->prov_hasta); // prov_hasta
			$this->UpdateSort($this->loc_desde); // loc_desde
			$this->UpdateSort($this->cp_desde); // cp_desde
			$this->UpdateSort($this->loc_hasta); // loc_hasta
			$this->UpdateSort($this->cp_hasta); // cp_hasta
			$this->UpdateSort($this->nro_cliente); // nro_cliente
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
				$this->Patente->setSort("");
				$this->modelo->setSort("");
				$this->marca->setSort("");
				$this->chofer->setSort("");
				$this->categoria_chofer->setSort("");
				$this->categoria_guardia->setSort("");
				$this->guarda->setSort("");
				$this->Origen->setSort("");
				$this->Destino->setSort("");
				$this->Km_ini->setSort("");
				$this->km_fin->setSort("");
				$this->estado->setSort("");
				$this->fecha_ini->setSort("");
				$this->fecha_fin->setSort("");
				$this->fecha_gasto->setSort("");
				$this->nro_gasto->setSort("");
				$this->detalle_gasto->setSort("");
				$this->importe_gasto->setSort("");
				$this->total->setSort("");
				$this->tipo_gasto->setSort("");
				$this->razon_social->setSort("");
				$this->prov_desde->setSort("");
				$this->prov_hasta->setSort("");
				$this->loc_desde->setSort("");
				$this->cp_desde->setSort("");
				$this->loc_hasta->setSort("");
				$this->cp_hasta->setSort("");
				$this->nro_cliente->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->nro_gasto->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->nro_cliente->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fv_listado_gastos_hoja_rutalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fv_listado_gastos_hoja_rutalistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->Patente->setDbValue($rs->fields('Patente'));
		$this->modelo->setDbValue($rs->fields('modelo'));
		$this->marca->setDbValue($rs->fields('marca'));
		$this->chofer->setDbValue($rs->fields('chofer'));
		$this->categoria_chofer->setDbValue($rs->fields('categoria_chofer'));
		$this->categoria_guardia->setDbValue($rs->fields('categoria_guardia'));
		$this->guarda->setDbValue($rs->fields('guarda'));
		$this->Origen->setDbValue($rs->fields('Origen'));
		$this->Destino->setDbValue($rs->fields('Destino'));
		$this->Km_ini->setDbValue($rs->fields('Km_ini'));
		$this->km_fin->setDbValue($rs->fields('km_fin'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->fecha_ini->setDbValue($rs->fields('fecha_ini'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->fecha_gasto->setDbValue($rs->fields('fecha_gasto'));
		$this->nro_gasto->setDbValue($rs->fields('nro_gasto'));
		$this->detalle_gasto->setDbValue($rs->fields('detalle_gasto'));
		$this->importe_gasto->setDbValue($rs->fields('importe_gasto'));
		$this->total->setDbValue($rs->fields('total'));
		$this->tipo_gasto->setDbValue($rs->fields('tipo_gasto'));
		$this->razon_social->setDbValue($rs->fields('razon_social'));
		$this->prov_desde->setDbValue($rs->fields('prov_desde'));
		$this->prov_hasta->setDbValue($rs->fields('prov_hasta'));
		$this->loc_desde->setDbValue($rs->fields('loc_desde'));
		$this->cp_desde->setDbValue($rs->fields('cp_desde'));
		$this->loc_hasta->setDbValue($rs->fields('loc_hasta'));
		$this->cp_hasta->setDbValue($rs->fields('cp_hasta'));
		$this->nro_cliente->setDbValue($rs->fields('nro_cliente'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Patente->DbValue = $row['Patente'];
		$this->modelo->DbValue = $row['modelo'];
		$this->marca->DbValue = $row['marca'];
		$this->chofer->DbValue = $row['chofer'];
		$this->categoria_chofer->DbValue = $row['categoria_chofer'];
		$this->categoria_guardia->DbValue = $row['categoria_guardia'];
		$this->guarda->DbValue = $row['guarda'];
		$this->Origen->DbValue = $row['Origen'];
		$this->Destino->DbValue = $row['Destino'];
		$this->Km_ini->DbValue = $row['Km_ini'];
		$this->km_fin->DbValue = $row['km_fin'];
		$this->estado->DbValue = $row['estado'];
		$this->fecha_ini->DbValue = $row['fecha_ini'];
		$this->fecha_fin->DbValue = $row['fecha_fin'];
		$this->fecha_gasto->DbValue = $row['fecha_gasto'];
		$this->nro_gasto->DbValue = $row['nro_gasto'];
		$this->detalle_gasto->DbValue = $row['detalle_gasto'];
		$this->importe_gasto->DbValue = $row['importe_gasto'];
		$this->total->DbValue = $row['total'];
		$this->tipo_gasto->DbValue = $row['tipo_gasto'];
		$this->razon_social->DbValue = $row['razon_social'];
		$this->prov_desde->DbValue = $row['prov_desde'];
		$this->prov_hasta->DbValue = $row['prov_hasta'];
		$this->loc_desde->DbValue = $row['loc_desde'];
		$this->cp_desde->DbValue = $row['cp_desde'];
		$this->loc_hasta->DbValue = $row['loc_hasta'];
		$this->cp_hasta->DbValue = $row['cp_hasta'];
		$this->nro_cliente->DbValue = $row['nro_cliente'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nro_gasto")) <> "")
			$this->nro_gasto->CurrentValue = $this->getKey("nro_gasto"); // nro_gasto
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nro_cliente")) <> "")
			$this->nro_cliente->CurrentValue = $this->getKey("nro_cliente"); // nro_cliente
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
		if ($this->importe_gasto->FormValue == $this->importe_gasto->CurrentValue && is_numeric(ew_StrToFloat($this->importe_gasto->CurrentValue)))
			$this->importe_gasto->CurrentValue = ew_StrToFloat($this->importe_gasto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Patente
		// modelo
		// marca
		// chofer
		// categoria_chofer
		// categoria_guardia
		// guarda
		// Origen
		// Destino
		// Km_ini
		// km_fin
		// estado
		// fecha_ini
		// fecha_fin
		// fecha_gasto
		// nro_gasto
		// detalle_gasto
		// importe_gasto
		// total
		// tipo_gasto
		// razon_social
		// prov_desde
		// prov_hasta
		// loc_desde
		// cp_desde
		// loc_hasta
		// cp_hasta
		// nro_cliente
		// Accumulate aggregate value

		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
			if (is_numeric($this->importe_gasto->CurrentValue))
				$this->importe_gasto->Total += $this->importe_gasto->CurrentValue; // Accumulate total
		}
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Patente
			$this->Patente->ViewValue = $this->Patente->CurrentValue;
			$this->Patente->ViewCustomAttributes = "";

			// modelo
			$this->modelo->ViewValue = $this->modelo->CurrentValue;
			$this->modelo->ViewCustomAttributes = "";

			// marca
			$this->marca->ViewValue = $this->marca->CurrentValue;
			$this->marca->ViewCustomAttributes = "";

			// chofer
			$this->chofer->ViewValue = $this->chofer->CurrentValue;
			$this->chofer->ViewCustomAttributes = "";

			// categoria_chofer
			$this->categoria_chofer->ViewValue = $this->categoria_chofer->CurrentValue;
			$this->categoria_chofer->ViewCustomAttributes = "";

			// categoria_guardia
			$this->categoria_guardia->ViewValue = $this->categoria_guardia->CurrentValue;
			$this->categoria_guardia->ViewCustomAttributes = "";

			// guarda
			$this->guarda->ViewValue = $this->guarda->CurrentValue;
			$this->guarda->ViewCustomAttributes = "";

			// Origen
			$this->Origen->ViewValue = $this->Origen->CurrentValue;
			$this->Origen->ViewCustomAttributes = "";

			// Destino
			$this->Destino->ViewValue = $this->Destino->CurrentValue;
			$this->Destino->ViewCustomAttributes = "";

			// Km_ini
			$this->Km_ini->ViewValue = $this->Km_ini->CurrentValue;
			$this->Km_ini->ViewCustomAttributes = "";

			// km_fin
			$this->km_fin->ViewValue = $this->km_fin->CurrentValue;
			$this->km_fin->ViewCustomAttributes = "";

			// estado
			$this->estado->ViewValue = $this->estado->CurrentValue;
			$this->estado->ViewCustomAttributes = "";

			// fecha_ini
			$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
			$this->fecha_ini->ViewValue = ew_FormatDateTime($this->fecha_ini->ViewValue, 7);
			$this->fecha_ini->ViewCustomAttributes = "";

			// fecha_fin
			$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
			$this->fecha_fin->ViewValue = ew_FormatDateTime($this->fecha_fin->ViewValue, 7);
			$this->fecha_fin->ViewCustomAttributes = "";

			// fecha_gasto
			$this->fecha_gasto->ViewValue = $this->fecha_gasto->CurrentValue;
			$this->fecha_gasto->ViewValue = ew_FormatDateTime($this->fecha_gasto->ViewValue, 7);
			$this->fecha_gasto->ViewCustomAttributes = "";

			// nro_gasto
			$this->nro_gasto->ViewValue = $this->nro_gasto->CurrentValue;
			$this->nro_gasto->ViewCustomAttributes = "";

			// detalle_gasto
			$this->detalle_gasto->ViewValue = $this->detalle_gasto->CurrentValue;
			$this->detalle_gasto->ViewCustomAttributes = "";

			// importe_gasto
			$this->importe_gasto->ViewValue = $this->importe_gasto->CurrentValue;
			$this->importe_gasto->ViewCustomAttributes = "";

			// total
			$this->total->ViewValue = $this->total->CurrentValue;
			$this->total->ViewCustomAttributes = "";

			// tipo_gasto
			$this->tipo_gasto->ViewValue = $this->tipo_gasto->CurrentValue;
			$this->tipo_gasto->ViewCustomAttributes = "";

			// razon_social
			$this->razon_social->ViewValue = $this->razon_social->CurrentValue;
			$this->razon_social->ViewCustomAttributes = "";

			// prov_desde
			$this->prov_desde->ViewValue = $this->prov_desde->CurrentValue;
			$this->prov_desde->ViewCustomAttributes = "";

			// prov_hasta
			$this->prov_hasta->ViewValue = $this->prov_hasta->CurrentValue;
			$this->prov_hasta->ViewCustomAttributes = "";

			// loc_desde
			$this->loc_desde->ViewValue = $this->loc_desde->CurrentValue;
			$this->loc_desde->ViewCustomAttributes = "";

			// cp_desde
			$this->cp_desde->ViewValue = $this->cp_desde->CurrentValue;
			$this->cp_desde->ViewCustomAttributes = "";

			// loc_hasta
			$this->loc_hasta->ViewValue = $this->loc_hasta->CurrentValue;
			$this->loc_hasta->ViewCustomAttributes = "";

			// cp_hasta
			$this->cp_hasta->ViewValue = $this->cp_hasta->CurrentValue;
			$this->cp_hasta->ViewCustomAttributes = "";

			// nro_cliente
			$this->nro_cliente->ViewValue = $this->nro_cliente->CurrentValue;
			$this->nro_cliente->ViewCustomAttributes = "";

			// Patente
			$this->Patente->LinkCustomAttributes = "";
			$this->Patente->HrefValue = "";
			$this->Patente->TooltipValue = "";

			// modelo
			$this->modelo->LinkCustomAttributes = "";
			$this->modelo->HrefValue = "";
			$this->modelo->TooltipValue = "";

			// marca
			$this->marca->LinkCustomAttributes = "";
			$this->marca->HrefValue = "";
			$this->marca->TooltipValue = "";

			// chofer
			$this->chofer->LinkCustomAttributes = "";
			$this->chofer->HrefValue = "";
			$this->chofer->TooltipValue = "";

			// categoria_chofer
			$this->categoria_chofer->LinkCustomAttributes = "";
			$this->categoria_chofer->HrefValue = "";
			$this->categoria_chofer->TooltipValue = "";

			// categoria_guardia
			$this->categoria_guardia->LinkCustomAttributes = "";
			$this->categoria_guardia->HrefValue = "";
			$this->categoria_guardia->TooltipValue = "";

			// guarda
			$this->guarda->LinkCustomAttributes = "";
			$this->guarda->HrefValue = "";
			$this->guarda->TooltipValue = "";

			// Origen
			$this->Origen->LinkCustomAttributes = "";
			$this->Origen->HrefValue = "";
			$this->Origen->TooltipValue = "";

			// Destino
			$this->Destino->LinkCustomAttributes = "";
			$this->Destino->HrefValue = "";
			$this->Destino->TooltipValue = "";

			// Km_ini
			$this->Km_ini->LinkCustomAttributes = "";
			$this->Km_ini->HrefValue = "";
			$this->Km_ini->TooltipValue = "";

			// km_fin
			$this->km_fin->LinkCustomAttributes = "";
			$this->km_fin->HrefValue = "";
			$this->km_fin->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// fecha_ini
			$this->fecha_ini->LinkCustomAttributes = "";
			$this->fecha_ini->HrefValue = "";
			$this->fecha_ini->TooltipValue = "";

			// fecha_fin
			$this->fecha_fin->LinkCustomAttributes = "";
			$this->fecha_fin->HrefValue = "";
			$this->fecha_fin->TooltipValue = "";

			// fecha_gasto
			$this->fecha_gasto->LinkCustomAttributes = "";
			$this->fecha_gasto->HrefValue = "";
			$this->fecha_gasto->TooltipValue = "";

			// nro_gasto
			$this->nro_gasto->LinkCustomAttributes = "";
			$this->nro_gasto->HrefValue = "";
			$this->nro_gasto->TooltipValue = "";

			// detalle_gasto
			$this->detalle_gasto->LinkCustomAttributes = "";
			$this->detalle_gasto->HrefValue = "";
			$this->detalle_gasto->TooltipValue = "";

			// importe_gasto
			$this->importe_gasto->LinkCustomAttributes = "";
			$this->importe_gasto->HrefValue = "";
			$this->importe_gasto->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

			// tipo_gasto
			$this->tipo_gasto->LinkCustomAttributes = "";
			$this->tipo_gasto->HrefValue = "";
			$this->tipo_gasto->TooltipValue = "";

			// razon_social
			$this->razon_social->LinkCustomAttributes = "";
			$this->razon_social->HrefValue = "";
			$this->razon_social->TooltipValue = "";

			// prov_desde
			$this->prov_desde->LinkCustomAttributes = "";
			$this->prov_desde->HrefValue = "";
			$this->prov_desde->TooltipValue = "";

			// prov_hasta
			$this->prov_hasta->LinkCustomAttributes = "";
			$this->prov_hasta->HrefValue = "";
			$this->prov_hasta->TooltipValue = "";

			// loc_desde
			$this->loc_desde->LinkCustomAttributes = "";
			$this->loc_desde->HrefValue = "";
			$this->loc_desde->TooltipValue = "";

			// cp_desde
			$this->cp_desde->LinkCustomAttributes = "";
			$this->cp_desde->HrefValue = "";
			$this->cp_desde->TooltipValue = "";

			// loc_hasta
			$this->loc_hasta->LinkCustomAttributes = "";
			$this->loc_hasta->HrefValue = "";
			$this->loc_hasta->TooltipValue = "";

			// cp_hasta
			$this->cp_hasta->LinkCustomAttributes = "";
			$this->cp_hasta->HrefValue = "";
			$this->cp_hasta->TooltipValue = "";

			// nro_cliente
			$this->nro_cliente->LinkCustomAttributes = "";
			$this->nro_cliente->HrefValue = "";
			$this->nro_cliente->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
			$this->importe_gasto->Total = 0; // Initialize total
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row
			$this->importe_gasto->CurrentValue = $this->importe_gasto->Total;
			$this->importe_gasto->ViewValue = $this->importe_gasto->CurrentValue;
			$this->importe_gasto->ViewCustomAttributes = "";
			$this->importe_gasto->HrefValue = ""; // Clear href value
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
		$item->Body = "<button id=\"emf_v_listado_gastos_hoja_ruta\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_v_listado_gastos_hoja_ruta',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fv_listado_gastos_hoja_rutalist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($v_listado_gastos_hoja_ruta_list)) $v_listado_gastos_hoja_ruta_list = new cv_listado_gastos_hoja_ruta_list();

// Page init
$v_listado_gastos_hoja_ruta_list->Page_Init();

// Page main
$v_listado_gastos_hoja_ruta_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$v_listado_gastos_hoja_ruta_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($v_listado_gastos_hoja_ruta->Export == "") { ?>
<script type="text/javascript">

// Page object
var v_listado_gastos_hoja_ruta_list = new ew_Page("v_listado_gastos_hoja_ruta_list");
v_listado_gastos_hoja_ruta_list.PageID = "list"; // Page ID
var EW_PAGE_ID = v_listado_gastos_hoja_ruta_list.PageID; // For backward compatibility

// Form object
var fv_listado_gastos_hoja_rutalist = new ew_Form("fv_listado_gastos_hoja_rutalist");
fv_listado_gastos_hoja_rutalist.FormKeyCountName = '<?php echo $v_listado_gastos_hoja_ruta_list->FormKeyCountName ?>';

// Form_CustomValidate event
fv_listado_gastos_hoja_rutalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_listado_gastos_hoja_rutalist.ValidateRequired = true;
<?php } else { ?>
fv_listado_gastos_hoja_rutalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fv_listado_gastos_hoja_rutalistsrch = new ew_Form("fv_listado_gastos_hoja_rutalistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($v_listado_gastos_hoja_ruta->Export == "") { ?>
<div class="ewToolbar">
<?php if ($v_listado_gastos_hoja_ruta->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($v_listado_gastos_hoja_ruta_list->TotalRecs > 0 && $v_listado_gastos_hoja_ruta_list->ExportOptions->Visible()) { ?>
<?php $v_listado_gastos_hoja_ruta_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($v_listado_gastos_hoja_ruta_list->SearchOptions->Visible()) { ?>
<?php $v_listado_gastos_hoja_ruta_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($v_listado_gastos_hoja_ruta->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($v_listado_gastos_hoja_ruta_list->TotalRecs <= 0)
			$v_listado_gastos_hoja_ruta_list->TotalRecs = $v_listado_gastos_hoja_ruta->SelectRecordCount();
	} else {
		if (!$v_listado_gastos_hoja_ruta_list->Recordset && ($v_listado_gastos_hoja_ruta_list->Recordset = $v_listado_gastos_hoja_ruta_list->LoadRecordset()))
			$v_listado_gastos_hoja_ruta_list->TotalRecs = $v_listado_gastos_hoja_ruta_list->Recordset->RecordCount();
	}
	$v_listado_gastos_hoja_ruta_list->StartRec = 1;
	if ($v_listado_gastos_hoja_ruta_list->DisplayRecs <= 0 || ($v_listado_gastos_hoja_ruta->Export <> "" && $v_listado_gastos_hoja_ruta->ExportAll)) // Display all records
		$v_listado_gastos_hoja_ruta_list->DisplayRecs = $v_listado_gastos_hoja_ruta_list->TotalRecs;
	if (!($v_listado_gastos_hoja_ruta->Export <> "" && $v_listado_gastos_hoja_ruta->ExportAll))
		$v_listado_gastos_hoja_ruta_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$v_listado_gastos_hoja_ruta_list->Recordset = $v_listado_gastos_hoja_ruta_list->LoadRecordset($v_listado_gastos_hoja_ruta_list->StartRec-1, $v_listado_gastos_hoja_ruta_list->DisplayRecs);

	// Set no record found message
	if ($v_listado_gastos_hoja_ruta->CurrentAction == "" && $v_listado_gastos_hoja_ruta_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$v_listado_gastos_hoja_ruta_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($v_listado_gastos_hoja_ruta_list->SearchWhere == "0=101")
			$v_listado_gastos_hoja_ruta_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$v_listado_gastos_hoja_ruta_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$v_listado_gastos_hoja_ruta_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($v_listado_gastos_hoja_ruta->Export == "" && $v_listado_gastos_hoja_ruta->CurrentAction == "") { ?>
<form name="fv_listado_gastos_hoja_rutalistsrch" id="fv_listado_gastos_hoja_rutalistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($v_listado_gastos_hoja_ruta_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fv_listado_gastos_hoja_rutalistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="v_listado_gastos_hoja_ruta">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($v_listado_gastos_hoja_ruta_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($v_listado_gastos_hoja_ruta_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $v_listado_gastos_hoja_ruta_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($v_listado_gastos_hoja_ruta_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($v_listado_gastos_hoja_ruta_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($v_listado_gastos_hoja_ruta_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($v_listado_gastos_hoja_ruta_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $v_listado_gastos_hoja_ruta_list->ShowPageHeader(); ?>
<?php
$v_listado_gastos_hoja_ruta_list->ShowMessage();
?>
<?php if ($v_listado_gastos_hoja_ruta_list->TotalRecs > 0 || $v_listado_gastos_hoja_ruta->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($v_listado_gastos_hoja_ruta->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($v_listado_gastos_hoja_ruta->CurrentAction <> "gridadd" && $v_listado_gastos_hoja_ruta->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($v_listado_gastos_hoja_ruta_list->Pager)) $v_listado_gastos_hoja_ruta_list->Pager = new cNumericPager($v_listado_gastos_hoja_ruta_list->StartRec, $v_listado_gastos_hoja_ruta_list->DisplayRecs, $v_listado_gastos_hoja_ruta_list->TotalRecs, $v_listado_gastos_hoja_ruta_list->RecRange) ?>
<?php if ($v_listado_gastos_hoja_ruta_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($v_listado_gastos_hoja_ruta_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_gastos_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_gastos_hoja_ruta_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_gastos_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_gastos_hoja_ruta_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($v_listado_gastos_hoja_ruta_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $v_listado_gastos_hoja_ruta_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_gastos_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_gastos_hoja_ruta_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_gastos_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_gastos_hoja_ruta_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $v_listado_gastos_hoja_ruta_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $v_listado_gastos_hoja_ruta_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $v_listado_gastos_hoja_ruta_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_listado_gastos_hoja_ruta_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fv_listado_gastos_hoja_rutalist" id="fv_listado_gastos_hoja_rutalist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($v_listado_gastos_hoja_ruta_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $v_listado_gastos_hoja_ruta_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="v_listado_gastos_hoja_ruta">
<div id="gmp_v_listado_gastos_hoja_ruta" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($v_listado_gastos_hoja_ruta_list->TotalRecs > 0) { ?>
<table id="tbl_v_listado_gastos_hoja_rutalist" class="table ewTable">
<?php echo $v_listado_gastos_hoja_ruta->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$v_listado_gastos_hoja_ruta->RowType = EW_ROWTYPE_HEADER;

// Render list options
$v_listado_gastos_hoja_ruta_list->RenderListOptions();

// Render list options (header, left)
$v_listado_gastos_hoja_ruta_list->ListOptions->Render("header", "left");
?>
<?php if ($v_listado_gastos_hoja_ruta->Patente->Visible) { // Patente ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->Patente) == "") { ?>
		<th data-name="Patente"><div id="elh_v_listado_gastos_hoja_ruta_Patente" class="v_listado_gastos_hoja_ruta_Patente"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->Patente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Patente"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->Patente) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_Patente" class="v_listado_gastos_hoja_ruta_Patente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->Patente->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->Patente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->Patente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->modelo->Visible) { // modelo ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->modelo) == "") { ?>
		<th data-name="modelo"><div id="elh_v_listado_gastos_hoja_ruta_modelo" class="v_listado_gastos_hoja_ruta_modelo"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->modelo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modelo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->modelo) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_modelo" class="v_listado_gastos_hoja_ruta_modelo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->modelo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->modelo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->modelo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->marca->Visible) { // marca ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->marca) == "") { ?>
		<th data-name="marca"><div id="elh_v_listado_gastos_hoja_ruta_marca" class="v_listado_gastos_hoja_ruta_marca"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->marca->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="marca"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->marca) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_marca" class="v_listado_gastos_hoja_ruta_marca">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->marca->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->marca->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->marca->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->chofer->Visible) { // chofer ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->chofer) == "") { ?>
		<th data-name="chofer"><div id="elh_v_listado_gastos_hoja_ruta_chofer" class="v_listado_gastos_hoja_ruta_chofer"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->chofer->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="chofer"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->chofer) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_chofer" class="v_listado_gastos_hoja_ruta_chofer">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->chofer->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->chofer->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->chofer->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->categoria_chofer->Visible) { // categoria_chofer ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->categoria_chofer) == "") { ?>
		<th data-name="categoria_chofer"><div id="elh_v_listado_gastos_hoja_ruta_categoria_chofer" class="v_listado_gastos_hoja_ruta_categoria_chofer"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->categoria_chofer->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="categoria_chofer"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->categoria_chofer) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_categoria_chofer" class="v_listado_gastos_hoja_ruta_categoria_chofer">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->categoria_chofer->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->categoria_chofer->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->categoria_chofer->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->categoria_guardia->Visible) { // categoria_guardia ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->categoria_guardia) == "") { ?>
		<th data-name="categoria_guardia"><div id="elh_v_listado_gastos_hoja_ruta_categoria_guardia" class="v_listado_gastos_hoja_ruta_categoria_guardia"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->categoria_guardia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="categoria_guardia"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->categoria_guardia) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_categoria_guardia" class="v_listado_gastos_hoja_ruta_categoria_guardia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->categoria_guardia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->categoria_guardia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->categoria_guardia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->guarda->Visible) { // guarda ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->guarda) == "") { ?>
		<th data-name="guarda"><div id="elh_v_listado_gastos_hoja_ruta_guarda" class="v_listado_gastos_hoja_ruta_guarda"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->guarda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="guarda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->guarda) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_guarda" class="v_listado_gastos_hoja_ruta_guarda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->guarda->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->guarda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->guarda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->Origen->Visible) { // Origen ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->Origen) == "") { ?>
		<th data-name="Origen"><div id="elh_v_listado_gastos_hoja_ruta_Origen" class="v_listado_gastos_hoja_ruta_Origen"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->Origen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Origen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->Origen) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_Origen" class="v_listado_gastos_hoja_ruta_Origen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->Origen->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->Origen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->Origen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->Destino->Visible) { // Destino ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->Destino) == "") { ?>
		<th data-name="Destino"><div id="elh_v_listado_gastos_hoja_ruta_Destino" class="v_listado_gastos_hoja_ruta_Destino"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->Destino->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Destino"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->Destino) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_Destino" class="v_listado_gastos_hoja_ruta_Destino">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->Destino->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->Destino->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->Destino->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->Km_ini->Visible) { // Km_ini ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->Km_ini) == "") { ?>
		<th data-name="Km_ini"><div id="elh_v_listado_gastos_hoja_ruta_Km_ini" class="v_listado_gastos_hoja_ruta_Km_ini"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->Km_ini->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Km_ini"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->Km_ini) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_Km_ini" class="v_listado_gastos_hoja_ruta_Km_ini">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->Km_ini->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->Km_ini->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->Km_ini->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->km_fin->Visible) { // km_fin ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->km_fin) == "") { ?>
		<th data-name="km_fin"><div id="elh_v_listado_gastos_hoja_ruta_km_fin" class="v_listado_gastos_hoja_ruta_km_fin"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->km_fin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="km_fin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->km_fin) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_km_fin" class="v_listado_gastos_hoja_ruta_km_fin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->km_fin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->km_fin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->km_fin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->estado->Visible) { // estado ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->estado) == "") { ?>
		<th data-name="estado"><div id="elh_v_listado_gastos_hoja_ruta_estado" class="v_listado_gastos_hoja_ruta_estado"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->estado) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_estado" class="v_listado_gastos_hoja_ruta_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->estado->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->fecha_ini->Visible) { // fecha_ini ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->fecha_ini) == "") { ?>
		<th data-name="fecha_ini"><div id="elh_v_listado_gastos_hoja_ruta_fecha_ini" class="v_listado_gastos_hoja_ruta_fecha_ini"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->fecha_ini->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_ini"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->fecha_ini) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_fecha_ini" class="v_listado_gastos_hoja_ruta_fecha_ini">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->fecha_ini->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->fecha_ini->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->fecha_ini->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->fecha_fin->Visible) { // fecha_fin ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->fecha_fin) == "") { ?>
		<th data-name="fecha_fin"><div id="elh_v_listado_gastos_hoja_ruta_fecha_fin" class="v_listado_gastos_hoja_ruta_fecha_fin"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->fecha_fin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_fin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->fecha_fin) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_fecha_fin" class="v_listado_gastos_hoja_ruta_fecha_fin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->fecha_fin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->fecha_fin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->fecha_fin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->fecha_gasto->Visible) { // fecha_gasto ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->fecha_gasto) == "") { ?>
		<th data-name="fecha_gasto"><div id="elh_v_listado_gastos_hoja_ruta_fecha_gasto" class="v_listado_gastos_hoja_ruta_fecha_gasto"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->fecha_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_gasto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->fecha_gasto) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_fecha_gasto" class="v_listado_gastos_hoja_ruta_fecha_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->fecha_gasto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->fecha_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->fecha_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->nro_gasto->Visible) { // nro_gasto ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->nro_gasto) == "") { ?>
		<th data-name="nro_gasto"><div id="elh_v_listado_gastos_hoja_ruta_nro_gasto" class="v_listado_gastos_hoja_ruta_nro_gasto"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->nro_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nro_gasto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->nro_gasto) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_nro_gasto" class="v_listado_gastos_hoja_ruta_nro_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->nro_gasto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->nro_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->nro_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->detalle_gasto->Visible) { // detalle_gasto ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->detalle_gasto) == "") { ?>
		<th data-name="detalle_gasto"><div id="elh_v_listado_gastos_hoja_ruta_detalle_gasto" class="v_listado_gastos_hoja_ruta_detalle_gasto"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->detalle_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="detalle_gasto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->detalle_gasto) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_detalle_gasto" class="v_listado_gastos_hoja_ruta_detalle_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->detalle_gasto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->detalle_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->detalle_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->importe_gasto->Visible) { // importe_gasto ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->importe_gasto) == "") { ?>
		<th data-name="importe_gasto"><div id="elh_v_listado_gastos_hoja_ruta_importe_gasto" class="v_listado_gastos_hoja_ruta_importe_gasto"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->importe_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importe_gasto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->importe_gasto) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_importe_gasto" class="v_listado_gastos_hoja_ruta_importe_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->importe_gasto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->importe_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->importe_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->total->Visible) { // total ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->total) == "") { ?>
		<th data-name="total"><div id="elh_v_listado_gastos_hoja_ruta_total" class="v_listado_gastos_hoja_ruta_total"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->total->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="total"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->total) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_total" class="v_listado_gastos_hoja_ruta_total">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->total->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->tipo_gasto->Visible) { // tipo_gasto ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->tipo_gasto) == "") { ?>
		<th data-name="tipo_gasto"><div id="elh_v_listado_gastos_hoja_ruta_tipo_gasto" class="v_listado_gastos_hoja_ruta_tipo_gasto"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->tipo_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_gasto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->tipo_gasto) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_tipo_gasto" class="v_listado_gastos_hoja_ruta_tipo_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->tipo_gasto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->tipo_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->tipo_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->razon_social->Visible) { // razon_social ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->razon_social) == "") { ?>
		<th data-name="razon_social"><div id="elh_v_listado_gastos_hoja_ruta_razon_social" class="v_listado_gastos_hoja_ruta_razon_social"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->razon_social->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="razon_social"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->razon_social) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_razon_social" class="v_listado_gastos_hoja_ruta_razon_social">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->razon_social->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->razon_social->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->razon_social->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->prov_desde->Visible) { // prov_desde ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->prov_desde) == "") { ?>
		<th data-name="prov_desde"><div id="elh_v_listado_gastos_hoja_ruta_prov_desde" class="v_listado_gastos_hoja_ruta_prov_desde"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->prov_desde->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="prov_desde"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->prov_desde) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_prov_desde" class="v_listado_gastos_hoja_ruta_prov_desde">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->prov_desde->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->prov_desde->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->prov_desde->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->prov_hasta->Visible) { // prov_hasta ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->prov_hasta) == "") { ?>
		<th data-name="prov_hasta"><div id="elh_v_listado_gastos_hoja_ruta_prov_hasta" class="v_listado_gastos_hoja_ruta_prov_hasta"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->prov_hasta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="prov_hasta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->prov_hasta) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_prov_hasta" class="v_listado_gastos_hoja_ruta_prov_hasta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->prov_hasta->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->prov_hasta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->prov_hasta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->loc_desde->Visible) { // loc_desde ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->loc_desde) == "") { ?>
		<th data-name="loc_desde"><div id="elh_v_listado_gastos_hoja_ruta_loc_desde" class="v_listado_gastos_hoja_ruta_loc_desde"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->loc_desde->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="loc_desde"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->loc_desde) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_loc_desde" class="v_listado_gastos_hoja_ruta_loc_desde">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->loc_desde->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->loc_desde->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->loc_desde->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->cp_desde->Visible) { // cp_desde ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->cp_desde) == "") { ?>
		<th data-name="cp_desde"><div id="elh_v_listado_gastos_hoja_ruta_cp_desde" class="v_listado_gastos_hoja_ruta_cp_desde"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->cp_desde->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cp_desde"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->cp_desde) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_cp_desde" class="v_listado_gastos_hoja_ruta_cp_desde">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->cp_desde->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->cp_desde->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->cp_desde->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->loc_hasta->Visible) { // loc_hasta ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->loc_hasta) == "") { ?>
		<th data-name="loc_hasta"><div id="elh_v_listado_gastos_hoja_ruta_loc_hasta" class="v_listado_gastos_hoja_ruta_loc_hasta"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->loc_hasta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="loc_hasta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->loc_hasta) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_loc_hasta" class="v_listado_gastos_hoja_ruta_loc_hasta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->loc_hasta->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->loc_hasta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->loc_hasta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->cp_hasta->Visible) { // cp_hasta ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->cp_hasta) == "") { ?>
		<th data-name="cp_hasta"><div id="elh_v_listado_gastos_hoja_ruta_cp_hasta" class="v_listado_gastos_hoja_ruta_cp_hasta"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->cp_hasta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cp_hasta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->cp_hasta) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_cp_hasta" class="v_listado_gastos_hoja_ruta_cp_hasta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->cp_hasta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->cp_hasta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->cp_hasta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_listado_gastos_hoja_ruta->nro_cliente->Visible) { // nro_cliente ?>
	<?php if ($v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->nro_cliente) == "") { ?>
		<th data-name="nro_cliente"><div id="elh_v_listado_gastos_hoja_ruta_nro_cliente" class="v_listado_gastos_hoja_ruta_nro_cliente"><div class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->nro_cliente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nro_cliente"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $v_listado_gastos_hoja_ruta->SortUrl($v_listado_gastos_hoja_ruta->nro_cliente) ?>',1);"><div id="elh_v_listado_gastos_hoja_ruta_nro_cliente" class="v_listado_gastos_hoja_ruta_nro_cliente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_listado_gastos_hoja_ruta->nro_cliente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_listado_gastos_hoja_ruta->nro_cliente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_listado_gastos_hoja_ruta->nro_cliente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$v_listado_gastos_hoja_ruta_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($v_listado_gastos_hoja_ruta->ExportAll && $v_listado_gastos_hoja_ruta->Export <> "") {
	$v_listado_gastos_hoja_ruta_list->StopRec = $v_listado_gastos_hoja_ruta_list->TotalRecs;
} else {

	// Set the last record to display
	if ($v_listado_gastos_hoja_ruta_list->TotalRecs > $v_listado_gastos_hoja_ruta_list->StartRec + $v_listado_gastos_hoja_ruta_list->DisplayRecs - 1)
		$v_listado_gastos_hoja_ruta_list->StopRec = $v_listado_gastos_hoja_ruta_list->StartRec + $v_listado_gastos_hoja_ruta_list->DisplayRecs - 1;
	else
		$v_listado_gastos_hoja_ruta_list->StopRec = $v_listado_gastos_hoja_ruta_list->TotalRecs;
}
$v_listado_gastos_hoja_ruta_list->RecCnt = $v_listado_gastos_hoja_ruta_list->StartRec - 1;
if ($v_listado_gastos_hoja_ruta_list->Recordset && !$v_listado_gastos_hoja_ruta_list->Recordset->EOF) {
	$v_listado_gastos_hoja_ruta_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $v_listado_gastos_hoja_ruta_list->StartRec > 1)
		$v_listado_gastos_hoja_ruta_list->Recordset->Move($v_listado_gastos_hoja_ruta_list->StartRec - 1);
} elseif (!$v_listado_gastos_hoja_ruta->AllowAddDeleteRow && $v_listado_gastos_hoja_ruta_list->StopRec == 0) {
	$v_listado_gastos_hoja_ruta_list->StopRec = $v_listado_gastos_hoja_ruta->GridAddRowCount;
}

// Initialize aggregate
$v_listado_gastos_hoja_ruta->RowType = EW_ROWTYPE_AGGREGATEINIT;
$v_listado_gastos_hoja_ruta->ResetAttrs();
$v_listado_gastos_hoja_ruta_list->RenderRow();
while ($v_listado_gastos_hoja_ruta_list->RecCnt < $v_listado_gastos_hoja_ruta_list->StopRec) {
	$v_listado_gastos_hoja_ruta_list->RecCnt++;
	if (intval($v_listado_gastos_hoja_ruta_list->RecCnt) >= intval($v_listado_gastos_hoja_ruta_list->StartRec)) {
		$v_listado_gastos_hoja_ruta_list->RowCnt++;

		// Set up key count
		$v_listado_gastos_hoja_ruta_list->KeyCount = $v_listado_gastos_hoja_ruta_list->RowIndex;

		// Init row class and style
		$v_listado_gastos_hoja_ruta->ResetAttrs();
		$v_listado_gastos_hoja_ruta->CssClass = "";
		if ($v_listado_gastos_hoja_ruta->CurrentAction == "gridadd") {
		} else {
			$v_listado_gastos_hoja_ruta_list->LoadRowValues($v_listado_gastos_hoja_ruta_list->Recordset); // Load row values
		}
		$v_listado_gastos_hoja_ruta->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$v_listado_gastos_hoja_ruta->RowAttrs = array_merge($v_listado_gastos_hoja_ruta->RowAttrs, array('data-rowindex'=>$v_listado_gastos_hoja_ruta_list->RowCnt, 'id'=>'r' . $v_listado_gastos_hoja_ruta_list->RowCnt . '_v_listado_gastos_hoja_ruta', 'data-rowtype'=>$v_listado_gastos_hoja_ruta->RowType));

		// Render row
		$v_listado_gastos_hoja_ruta_list->RenderRow();

		// Render list options
		$v_listado_gastos_hoja_ruta_list->RenderListOptions();
?>
	<tr<?php echo $v_listado_gastos_hoja_ruta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_listado_gastos_hoja_ruta_list->ListOptions->Render("body", "left", $v_listado_gastos_hoja_ruta_list->RowCnt);
?>
	<?php if ($v_listado_gastos_hoja_ruta->Patente->Visible) { // Patente ?>
		<td data-name="Patente"<?php echo $v_listado_gastos_hoja_ruta->Patente->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->Patente->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->Patente->ListViewValue() ?></span>
<a id="<?php echo $v_listado_gastos_hoja_ruta_list->PageObjName . "_row_" . $v_listado_gastos_hoja_ruta_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->modelo->Visible) { // modelo ?>
		<td data-name="modelo"<?php echo $v_listado_gastos_hoja_ruta->modelo->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->modelo->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->modelo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->marca->Visible) { // marca ?>
		<td data-name="marca"<?php echo $v_listado_gastos_hoja_ruta->marca->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->marca->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->marca->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->chofer->Visible) { // chofer ?>
		<td data-name="chofer"<?php echo $v_listado_gastos_hoja_ruta->chofer->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->chofer->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->chofer->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->categoria_chofer->Visible) { // categoria_chofer ?>
		<td data-name="categoria_chofer"<?php echo $v_listado_gastos_hoja_ruta->categoria_chofer->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->categoria_chofer->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->categoria_chofer->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->categoria_guardia->Visible) { // categoria_guardia ?>
		<td data-name="categoria_guardia"<?php echo $v_listado_gastos_hoja_ruta->categoria_guardia->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->categoria_guardia->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->categoria_guardia->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->guarda->Visible) { // guarda ?>
		<td data-name="guarda"<?php echo $v_listado_gastos_hoja_ruta->guarda->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->guarda->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->guarda->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->Origen->Visible) { // Origen ?>
		<td data-name="Origen"<?php echo $v_listado_gastos_hoja_ruta->Origen->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->Origen->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->Origen->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->Destino->Visible) { // Destino ?>
		<td data-name="Destino"<?php echo $v_listado_gastos_hoja_ruta->Destino->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->Destino->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->Destino->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->Km_ini->Visible) { // Km_ini ?>
		<td data-name="Km_ini"<?php echo $v_listado_gastos_hoja_ruta->Km_ini->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->Km_ini->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->Km_ini->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->km_fin->Visible) { // km_fin ?>
		<td data-name="km_fin"<?php echo $v_listado_gastos_hoja_ruta->km_fin->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->km_fin->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->km_fin->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $v_listado_gastos_hoja_ruta->estado->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->estado->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->estado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->fecha_ini->Visible) { // fecha_ini ?>
		<td data-name="fecha_ini"<?php echo $v_listado_gastos_hoja_ruta->fecha_ini->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->fecha_ini->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->fecha_ini->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->fecha_fin->Visible) { // fecha_fin ?>
		<td data-name="fecha_fin"<?php echo $v_listado_gastos_hoja_ruta->fecha_fin->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->fecha_fin->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->fecha_fin->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->fecha_gasto->Visible) { // fecha_gasto ?>
		<td data-name="fecha_gasto"<?php echo $v_listado_gastos_hoja_ruta->fecha_gasto->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->fecha_gasto->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->fecha_gasto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->nro_gasto->Visible) { // nro_gasto ?>
		<td data-name="nro_gasto"<?php echo $v_listado_gastos_hoja_ruta->nro_gasto->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->nro_gasto->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->nro_gasto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->detalle_gasto->Visible) { // detalle_gasto ?>
		<td data-name="detalle_gasto"<?php echo $v_listado_gastos_hoja_ruta->detalle_gasto->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->detalle_gasto->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->detalle_gasto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->importe_gasto->Visible) { // importe_gasto ?>
		<td data-name="importe_gasto"<?php echo $v_listado_gastos_hoja_ruta->importe_gasto->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->importe_gasto->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->importe_gasto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->total->Visible) { // total ?>
		<td data-name="total"<?php echo $v_listado_gastos_hoja_ruta->total->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->total->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->total->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->tipo_gasto->Visible) { // tipo_gasto ?>
		<td data-name="tipo_gasto"<?php echo $v_listado_gastos_hoja_ruta->tipo_gasto->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->tipo_gasto->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->tipo_gasto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->razon_social->Visible) { // razon_social ?>
		<td data-name="razon_social"<?php echo $v_listado_gastos_hoja_ruta->razon_social->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->razon_social->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->razon_social->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->prov_desde->Visible) { // prov_desde ?>
		<td data-name="prov_desde"<?php echo $v_listado_gastos_hoja_ruta->prov_desde->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->prov_desde->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->prov_desde->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->prov_hasta->Visible) { // prov_hasta ?>
		<td data-name="prov_hasta"<?php echo $v_listado_gastos_hoja_ruta->prov_hasta->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->prov_hasta->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->prov_hasta->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->loc_desde->Visible) { // loc_desde ?>
		<td data-name="loc_desde"<?php echo $v_listado_gastos_hoja_ruta->loc_desde->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->loc_desde->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->loc_desde->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->cp_desde->Visible) { // cp_desde ?>
		<td data-name="cp_desde"<?php echo $v_listado_gastos_hoja_ruta->cp_desde->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->cp_desde->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->cp_desde->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->loc_hasta->Visible) { // loc_hasta ?>
		<td data-name="loc_hasta"<?php echo $v_listado_gastos_hoja_ruta->loc_hasta->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->loc_hasta->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->loc_hasta->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->cp_hasta->Visible) { // cp_hasta ?>
		<td data-name="cp_hasta"<?php echo $v_listado_gastos_hoja_ruta->cp_hasta->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->cp_hasta->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->cp_hasta->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->nro_cliente->Visible) { // nro_cliente ?>
		<td data-name="nro_cliente"<?php echo $v_listado_gastos_hoja_ruta->nro_cliente->CellAttributes() ?>>
<span<?php echo $v_listado_gastos_hoja_ruta->nro_cliente->ViewAttributes() ?>>
<?php echo $v_listado_gastos_hoja_ruta->nro_cliente->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_listado_gastos_hoja_ruta_list->ListOptions->Render("body", "right", $v_listado_gastos_hoja_ruta_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($v_listado_gastos_hoja_ruta->CurrentAction <> "gridadd")
		$v_listado_gastos_hoja_ruta_list->Recordset->MoveNext();
}
?>
</tbody>
<?php

// Render aggregate row
$v_listado_gastos_hoja_ruta->RowType = EW_ROWTYPE_AGGREGATE;
$v_listado_gastos_hoja_ruta->ResetAttrs();
$v_listado_gastos_hoja_ruta_list->RenderRow();
?>
<?php if ($v_listado_gastos_hoja_ruta_list->TotalRecs > 0 && ($v_listado_gastos_hoja_ruta->CurrentAction <> "gridadd" && $v_listado_gastos_hoja_ruta->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options
$v_listado_gastos_hoja_ruta_list->RenderListOptions();

// Render list options (footer, left)
$v_listado_gastos_hoja_ruta_list->ListOptions->Render("footer", "left");
?>
	<?php if ($v_listado_gastos_hoja_ruta->Patente->Visible) { // Patente ?>
		<td data-name="Patente"><span id="elf_v_listado_gastos_hoja_ruta_Patente" class="v_listado_gastos_hoja_ruta_Patente">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->modelo->Visible) { // modelo ?>
		<td data-name="modelo"><span id="elf_v_listado_gastos_hoja_ruta_modelo" class="v_listado_gastos_hoja_ruta_modelo">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->marca->Visible) { // marca ?>
		<td data-name="marca"><span id="elf_v_listado_gastos_hoja_ruta_marca" class="v_listado_gastos_hoja_ruta_marca">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->chofer->Visible) { // chofer ?>
		<td data-name="chofer"><span id="elf_v_listado_gastos_hoja_ruta_chofer" class="v_listado_gastos_hoja_ruta_chofer">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->categoria_chofer->Visible) { // categoria_chofer ?>
		<td data-name="categoria_chofer"><span id="elf_v_listado_gastos_hoja_ruta_categoria_chofer" class="v_listado_gastos_hoja_ruta_categoria_chofer">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->categoria_guardia->Visible) { // categoria_guardia ?>
		<td data-name="categoria_guardia"><span id="elf_v_listado_gastos_hoja_ruta_categoria_guardia" class="v_listado_gastos_hoja_ruta_categoria_guardia">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->guarda->Visible) { // guarda ?>
		<td data-name="guarda"><span id="elf_v_listado_gastos_hoja_ruta_guarda" class="v_listado_gastos_hoja_ruta_guarda">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->Origen->Visible) { // Origen ?>
		<td data-name="Origen"><span id="elf_v_listado_gastos_hoja_ruta_Origen" class="v_listado_gastos_hoja_ruta_Origen">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->Destino->Visible) { // Destino ?>
		<td data-name="Destino"><span id="elf_v_listado_gastos_hoja_ruta_Destino" class="v_listado_gastos_hoja_ruta_Destino">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->Km_ini->Visible) { // Km_ini ?>
		<td data-name="Km_ini"><span id="elf_v_listado_gastos_hoja_ruta_Km_ini" class="v_listado_gastos_hoja_ruta_Km_ini">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->km_fin->Visible) { // km_fin ?>
		<td data-name="km_fin"><span id="elf_v_listado_gastos_hoja_ruta_km_fin" class="v_listado_gastos_hoja_ruta_km_fin">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->estado->Visible) { // estado ?>
		<td data-name="estado"><span id="elf_v_listado_gastos_hoja_ruta_estado" class="v_listado_gastos_hoja_ruta_estado">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->fecha_ini->Visible) { // fecha_ini ?>
		<td data-name="fecha_ini"><span id="elf_v_listado_gastos_hoja_ruta_fecha_ini" class="v_listado_gastos_hoja_ruta_fecha_ini">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->fecha_fin->Visible) { // fecha_fin ?>
		<td data-name="fecha_fin"><span id="elf_v_listado_gastos_hoja_ruta_fecha_fin" class="v_listado_gastos_hoja_ruta_fecha_fin">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->fecha_gasto->Visible) { // fecha_gasto ?>
		<td data-name="fecha_gasto"><span id="elf_v_listado_gastos_hoja_ruta_fecha_gasto" class="v_listado_gastos_hoja_ruta_fecha_gasto">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->nro_gasto->Visible) { // nro_gasto ?>
		<td data-name="nro_gasto"><span id="elf_v_listado_gastos_hoja_ruta_nro_gasto" class="v_listado_gastos_hoja_ruta_nro_gasto">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->detalle_gasto->Visible) { // detalle_gasto ?>
		<td data-name="detalle_gasto"><span id="elf_v_listado_gastos_hoja_ruta_detalle_gasto" class="v_listado_gastos_hoja_ruta_detalle_gasto">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->importe_gasto->Visible) { // importe_gasto ?>
		<td data-name="importe_gasto"><span id="elf_v_listado_gastos_hoja_ruta_importe_gasto" class="v_listado_gastos_hoja_ruta_importe_gasto">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $v_listado_gastos_hoja_ruta->importe_gasto->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->total->Visible) { // total ?>
		<td data-name="total"><span id="elf_v_listado_gastos_hoja_ruta_total" class="v_listado_gastos_hoja_ruta_total">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->tipo_gasto->Visible) { // tipo_gasto ?>
		<td data-name="tipo_gasto"><span id="elf_v_listado_gastos_hoja_ruta_tipo_gasto" class="v_listado_gastos_hoja_ruta_tipo_gasto">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->razon_social->Visible) { // razon_social ?>
		<td data-name="razon_social"><span id="elf_v_listado_gastos_hoja_ruta_razon_social" class="v_listado_gastos_hoja_ruta_razon_social">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->prov_desde->Visible) { // prov_desde ?>
		<td data-name="prov_desde"><span id="elf_v_listado_gastos_hoja_ruta_prov_desde" class="v_listado_gastos_hoja_ruta_prov_desde">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->prov_hasta->Visible) { // prov_hasta ?>
		<td data-name="prov_hasta"><span id="elf_v_listado_gastos_hoja_ruta_prov_hasta" class="v_listado_gastos_hoja_ruta_prov_hasta">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->loc_desde->Visible) { // loc_desde ?>
		<td data-name="loc_desde"><span id="elf_v_listado_gastos_hoja_ruta_loc_desde" class="v_listado_gastos_hoja_ruta_loc_desde">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->cp_desde->Visible) { // cp_desde ?>
		<td data-name="cp_desde"><span id="elf_v_listado_gastos_hoja_ruta_cp_desde" class="v_listado_gastos_hoja_ruta_cp_desde">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->loc_hasta->Visible) { // loc_hasta ?>
		<td data-name="loc_hasta"><span id="elf_v_listado_gastos_hoja_ruta_loc_hasta" class="v_listado_gastos_hoja_ruta_loc_hasta">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->cp_hasta->Visible) { // cp_hasta ?>
		<td data-name="cp_hasta"><span id="elf_v_listado_gastos_hoja_ruta_cp_hasta" class="v_listado_gastos_hoja_ruta_cp_hasta">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta->nro_cliente->Visible) { // nro_cliente ?>
		<td data-name="nro_cliente"><span id="elf_v_listado_gastos_hoja_ruta_nro_cliente" class="v_listado_gastos_hoja_ruta_nro_cliente">
		&nbsp;
		</span></td>
	<?php } ?>
<?php

// Render list options (footer, right)
$v_listado_gastos_hoja_ruta_list->ListOptions->Render("footer", "right");
?>
	</tr>
</tfoot>	
<?php } ?>
</table>
<?php } ?>
<?php if ($v_listado_gastos_hoja_ruta->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($v_listado_gastos_hoja_ruta_list->Recordset)
	$v_listado_gastos_hoja_ruta_list->Recordset->Close();
?>
<?php if ($v_listado_gastos_hoja_ruta->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($v_listado_gastos_hoja_ruta->CurrentAction <> "gridadd" && $v_listado_gastos_hoja_ruta->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($v_listado_gastos_hoja_ruta_list->Pager)) $v_listado_gastos_hoja_ruta_list->Pager = new cNumericPager($v_listado_gastos_hoja_ruta_list->StartRec, $v_listado_gastos_hoja_ruta_list->DisplayRecs, $v_listado_gastos_hoja_ruta_list->TotalRecs, $v_listado_gastos_hoja_ruta_list->RecRange) ?>
<?php if ($v_listado_gastos_hoja_ruta_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($v_listado_gastos_hoja_ruta_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_gastos_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_gastos_hoja_ruta_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_gastos_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_gastos_hoja_ruta_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($v_listado_gastos_hoja_ruta_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $v_listado_gastos_hoja_ruta_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_gastos_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_gastos_hoja_ruta_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($v_listado_gastos_hoja_ruta_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $v_listado_gastos_hoja_ruta_list->PageUrl() ?>start=<?php echo $v_listado_gastos_hoja_ruta_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $v_listado_gastos_hoja_ruta_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $v_listado_gastos_hoja_ruta_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $v_listado_gastos_hoja_ruta_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_listado_gastos_hoja_ruta_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($v_listado_gastos_hoja_ruta_list->TotalRecs == 0 && $v_listado_gastos_hoja_ruta->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_listado_gastos_hoja_ruta_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($v_listado_gastos_hoja_ruta->Export == "") { ?>
<script type="text/javascript">
fv_listado_gastos_hoja_rutalistsrch.Init();
fv_listado_gastos_hoja_rutalist.Init();
</script>
<?php } ?>
<?php
$v_listado_gastos_hoja_ruta_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($v_listado_gastos_hoja_ruta->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$v_listado_gastos_hoja_ruta_list->Page_Terminate();
?>
