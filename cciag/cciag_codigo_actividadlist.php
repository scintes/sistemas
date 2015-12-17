<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "cciag_ewcfg11.php" ?>
<?php include_once "cciag_ewmysql11.php" ?>
<?php include_once "cciag_phpfn11.php" ?>
<?php include_once "cciag_codigo_actividadinfo.php" ?>
<?php include_once "cciag_usuarioinfo.php" ?>
<?php include_once "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$codigo_actividad_list = NULL; // Initialize page object first

class ccodigo_actividad_list extends ccodigo_actividad {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'codigo_actividad';

	// Page object name
	var $PageObjName = 'codigo_actividad_list';

	// Grid form hidden field names
	var $FormName = 'fcodigo_actividadlist';
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

		// Table object (codigo_actividad)
		if (!isset($GLOBALS["codigo_actividad"]) || get_class($GLOBALS["codigo_actividad"]) == "ccodigo_actividad") {
			$GLOBALS["codigo_actividad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["codigo_actividad"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "cciag_codigo_actividadadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "cciag_codigo_actividaddelete.php";
		$this->MultiUpdateUrl = "cciag_codigo_actividadupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'codigo_actividad', TRUE);

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
		global $EW_EXPORT, $codigo_actividad;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($codigo_actividad);
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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->descripcion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->descripcion_resumida, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->observaciones, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fecha_alta, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fecha_baja, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->objeto_cuantificable, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->manipula_alimento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->id_clanae, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->id_actividad_padre, $arKeywords, $type);
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
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->codigo); // codigo
			$this->UpdateSort($this->descripcion); // descripcion
			$this->UpdateSort($this->descripcion_resumida); // descripcion_resumida
			$this->UpdateSort($this->observaciones); // observaciones
			$this->UpdateSort($this->version); // version
			$this->UpdateSort($this->id_rubro); // id_rubro
			$this->UpdateSort($this->fecha_alta); // fecha_alta
			$this->UpdateSort($this->fecha_baja); // fecha_baja
			$this->UpdateSort($this->objeto_cuantificable); // objeto_cuantificable
			$this->UpdateSort($this->manipula_alimento); // manipula_alimento
			$this->UpdateSort($this->id_clanae); // id_clanae
			$this->UpdateSort($this->id_actividad_padre); // id_actividad_padre
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
				$this->id->setSort("");
				$this->codigo->setSort("");
				$this->descripcion->setSort("");
				$this->descripcion_resumida->setSort("");
				$this->observaciones->setSort("");
				$this->version->setSort("");
				$this->id_rubro->setSort("");
				$this->fecha_alta->setSort("");
				$this->fecha_baja->setSort("");
				$this->objeto_cuantificable->setSort("");
				$this->manipula_alimento->setSort("");
				$this->id_clanae->setSort("");
				$this->id_actividad_padre->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fcodigo_actividadlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fcodigo_actividadlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->id->setDbValue($rs->fields('id'));
		$this->codigo->setDbValue($rs->fields('codigo'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->descripcion_resumida->setDbValue($rs->fields('descripcion_resumida'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->version->setDbValue($rs->fields('version'));
		$this->id_rubro->setDbValue($rs->fields('id_rubro'));
		$this->fecha_alta->setDbValue($rs->fields('fecha_alta'));
		$this->fecha_baja->setDbValue($rs->fields('fecha_baja'));
		$this->objeto_cuantificable->setDbValue($rs->fields('objeto_cuantificable'));
		$this->manipula_alimento->setDbValue($rs->fields('manipula_alimento'));
		$this->id_clanae->setDbValue($rs->fields('id_clanae'));
		$this->id_actividad_padre->setDbValue($rs->fields('id_actividad_padre'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->codigo->DbValue = $row['codigo'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->descripcion_resumida->DbValue = $row['descripcion_resumida'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->version->DbValue = $row['version'];
		$this->id_rubro->DbValue = $row['id_rubro'];
		$this->fecha_alta->DbValue = $row['fecha_alta'];
		$this->fecha_baja->DbValue = $row['fecha_baja'];
		$this->objeto_cuantificable->DbValue = $row['objeto_cuantificable'];
		$this->manipula_alimento->DbValue = $row['manipula_alimento'];
		$this->id_clanae->DbValue = $row['id_clanae'];
		$this->id_actividad_padre->DbValue = $row['id_actividad_padre'];
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// codigo
		// descripcion
		// descripcion_resumida
		// observaciones
		// version
		// id_rubro
		// fecha_alta
		// fecha_baja
		// objeto_cuantificable
		// manipula_alimento
		// id_clanae
		// id_actividad_padre

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
			$this->descripcion->ViewCustomAttributes = "";

			// descripcion_resumida
			$this->descripcion_resumida->ViewValue = $this->descripcion_resumida->CurrentValue;
			$this->descripcion_resumida->ViewCustomAttributes = "";

			// observaciones
			$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
			$this->observaciones->ViewCustomAttributes = "";

			// version
			$this->version->ViewValue = $this->version->CurrentValue;
			$this->version->ViewCustomAttributes = "";

			// id_rubro
			$this->id_rubro->ViewValue = $this->id_rubro->CurrentValue;
			$this->id_rubro->ViewCustomAttributes = "";

			// fecha_alta
			$this->fecha_alta->ViewValue = $this->fecha_alta->CurrentValue;
			$this->fecha_alta->ViewCustomAttributes = "";

			// fecha_baja
			$this->fecha_baja->ViewValue = $this->fecha_baja->CurrentValue;
			$this->fecha_baja->ViewCustomAttributes = "";

			// objeto_cuantificable
			$this->objeto_cuantificable->ViewValue = $this->objeto_cuantificable->CurrentValue;
			$this->objeto_cuantificable->ViewCustomAttributes = "";

			// manipula_alimento
			$this->manipula_alimento->ViewValue = $this->manipula_alimento->CurrentValue;
			$this->manipula_alimento->ViewCustomAttributes = "";

			// id_clanae
			$this->id_clanae->ViewValue = $this->id_clanae->CurrentValue;
			$this->id_clanae->ViewCustomAttributes = "";

			// id_actividad_padre
			$this->id_actividad_padre->ViewValue = $this->id_actividad_padre->CurrentValue;
			$this->id_actividad_padre->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// descripcion_resumida
			$this->descripcion_resumida->LinkCustomAttributes = "";
			$this->descripcion_resumida->HrefValue = "";
			$this->descripcion_resumida->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// version
			$this->version->LinkCustomAttributes = "";
			$this->version->HrefValue = "";
			$this->version->TooltipValue = "";

			// id_rubro
			$this->id_rubro->LinkCustomAttributes = "";
			$this->id_rubro->HrefValue = "";
			$this->id_rubro->TooltipValue = "";

			// fecha_alta
			$this->fecha_alta->LinkCustomAttributes = "";
			$this->fecha_alta->HrefValue = "";
			$this->fecha_alta->TooltipValue = "";

			// fecha_baja
			$this->fecha_baja->LinkCustomAttributes = "";
			$this->fecha_baja->HrefValue = "";
			$this->fecha_baja->TooltipValue = "";

			// objeto_cuantificable
			$this->objeto_cuantificable->LinkCustomAttributes = "";
			$this->objeto_cuantificable->HrefValue = "";
			$this->objeto_cuantificable->TooltipValue = "";

			// manipula_alimento
			$this->manipula_alimento->LinkCustomAttributes = "";
			$this->manipula_alimento->HrefValue = "";
			$this->manipula_alimento->TooltipValue = "";

			// id_clanae
			$this->id_clanae->LinkCustomAttributes = "";
			$this->id_clanae->HrefValue = "";
			$this->id_clanae->TooltipValue = "";

			// id_actividad_padre
			$this->id_actividad_padre->LinkCustomAttributes = "";
			$this->id_actividad_padre->HrefValue = "";
			$this->id_actividad_padre->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_codigo_actividad\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_codigo_actividad',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fcodigo_actividadlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
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
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($codigo_actividad_list)) $codigo_actividad_list = new ccodigo_actividad_list();

// Page init
$codigo_actividad_list->Page_Init();

// Page main
$codigo_actividad_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$codigo_actividad_list->Page_Render();
?>
<?php include_once "cciag_header.php" ?>
<?php if ($codigo_actividad->Export == "") { ?>
<script type="text/javascript">

// Page object
var codigo_actividad_list = new ew_Page("codigo_actividad_list");
codigo_actividad_list.PageID = "list"; // Page ID
var EW_PAGE_ID = codigo_actividad_list.PageID; // For backward compatibility

// Form object
var fcodigo_actividadlist = new ew_Form("fcodigo_actividadlist");
fcodigo_actividadlist.FormKeyCountName = '<?php echo $codigo_actividad_list->FormKeyCountName ?>';

// Form_CustomValidate event
fcodigo_actividadlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcodigo_actividadlist.ValidateRequired = true;
<?php } else { ?>
fcodigo_actividadlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fcodigo_actividadlistsrch = new ew_Form("fcodigo_actividadlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($codigo_actividad->Export == "") { ?>
<div class="ewToolbar">
<?php if ($codigo_actividad->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($codigo_actividad_list->TotalRecs > 0 && $codigo_actividad_list->ExportOptions->Visible()) { ?>
<?php $codigo_actividad_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($codigo_actividad_list->SearchOptions->Visible()) { ?>
<?php $codigo_actividad_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($codigo_actividad->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($codigo_actividad_list->TotalRecs <= 0)
			$codigo_actividad_list->TotalRecs = $codigo_actividad->SelectRecordCount();
	} else {
		if (!$codigo_actividad_list->Recordset && ($codigo_actividad_list->Recordset = $codigo_actividad_list->LoadRecordset()))
			$codigo_actividad_list->TotalRecs = $codigo_actividad_list->Recordset->RecordCount();
	}
	$codigo_actividad_list->StartRec = 1;
	if ($codigo_actividad_list->DisplayRecs <= 0 || ($codigo_actividad->Export <> "" && $codigo_actividad->ExportAll)) // Display all records
		$codigo_actividad_list->DisplayRecs = $codigo_actividad_list->TotalRecs;
	if (!($codigo_actividad->Export <> "" && $codigo_actividad->ExportAll))
		$codigo_actividad_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$codigo_actividad_list->Recordset = $codigo_actividad_list->LoadRecordset($codigo_actividad_list->StartRec-1, $codigo_actividad_list->DisplayRecs);

	// Set no record found message
	if ($codigo_actividad->CurrentAction == "" && $codigo_actividad_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$codigo_actividad_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($codigo_actividad_list->SearchWhere == "0=101")
			$codigo_actividad_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$codigo_actividad_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$codigo_actividad_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($codigo_actividad->Export == "" && $codigo_actividad->CurrentAction == "") { ?>
<form name="fcodigo_actividadlistsrch" id="fcodigo_actividadlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($codigo_actividad_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fcodigo_actividadlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="codigo_actividad">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($codigo_actividad_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($codigo_actividad_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $codigo_actividad_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($codigo_actividad_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($codigo_actividad_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($codigo_actividad_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($codigo_actividad_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $codigo_actividad_list->ShowPageHeader(); ?>
<?php
$codigo_actividad_list->ShowMessage();
?>
<?php if ($codigo_actividad_list->TotalRecs > 0 || $codigo_actividad->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($codigo_actividad->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($codigo_actividad->CurrentAction <> "gridadd" && $codigo_actividad->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($codigo_actividad_list->Pager)) $codigo_actividad_list->Pager = new cPrevNextPager($codigo_actividad_list->StartRec, $codigo_actividad_list->DisplayRecs, $codigo_actividad_list->TotalRecs) ?>
<?php if ($codigo_actividad_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($codigo_actividad_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $codigo_actividad_list->PageUrl() ?>start=<?php echo $codigo_actividad_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($codigo_actividad_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $codigo_actividad_list->PageUrl() ?>start=<?php echo $codigo_actividad_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $codigo_actividad_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($codigo_actividad_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $codigo_actividad_list->PageUrl() ?>start=<?php echo $codigo_actividad_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($codigo_actividad_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $codigo_actividad_list->PageUrl() ?>start=<?php echo $codigo_actividad_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $codigo_actividad_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $codigo_actividad_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $codigo_actividad_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $codigo_actividad_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($codigo_actividad_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fcodigo_actividadlist" id="fcodigo_actividadlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($codigo_actividad_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $codigo_actividad_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="codigo_actividad">
<div id="gmp_codigo_actividad" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($codigo_actividad_list->TotalRecs > 0) { ?>
<table id="tbl_codigo_actividadlist" class="table ewTable">
<?php echo $codigo_actividad->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$codigo_actividad->RowType = EW_ROWTYPE_HEADER;

// Render list options
$codigo_actividad_list->RenderListOptions();

// Render list options (header, left)
$codigo_actividad_list->ListOptions->Render("header", "left");
?>
<?php if ($codigo_actividad->id->Visible) { // id ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->id) == "") { ?>
		<th data-name="id"><div id="elh_codigo_actividad_id" class="codigo_actividad_id"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->id) ?>',1);"><div id="elh_codigo_actividad_id" class="codigo_actividad_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->codigo->Visible) { // codigo ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->codigo) == "") { ?>
		<th data-name="codigo"><div id="elh_codigo_actividad_codigo" class="codigo_actividad_codigo"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->codigo) ?>',1);"><div id="elh_codigo_actividad_codigo" class="codigo_actividad_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->descripcion->Visible) { // descripcion ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->descripcion) == "") { ?>
		<th data-name="descripcion"><div id="elh_codigo_actividad_descripcion" class="codigo_actividad_descripcion"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->descripcion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descripcion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->descripcion) ?>',1);"><div id="elh_codigo_actividad_descripcion" class="codigo_actividad_descripcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->descripcion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->descripcion_resumida->Visible) { // descripcion_resumida ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->descripcion_resumida) == "") { ?>
		<th data-name="descripcion_resumida"><div id="elh_codigo_actividad_descripcion_resumida" class="codigo_actividad_descripcion_resumida"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->descripcion_resumida->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descripcion_resumida"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->descripcion_resumida) ?>',1);"><div id="elh_codigo_actividad_descripcion_resumida" class="codigo_actividad_descripcion_resumida">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->descripcion_resumida->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->descripcion_resumida->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->descripcion_resumida->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->observaciones->Visible) { // observaciones ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->observaciones) == "") { ?>
		<th data-name="observaciones"><div id="elh_codigo_actividad_observaciones" class="codigo_actividad_observaciones"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->observaciones->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="observaciones"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->observaciones) ?>',1);"><div id="elh_codigo_actividad_observaciones" class="codigo_actividad_observaciones">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->observaciones->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->observaciones->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->observaciones->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->version->Visible) { // version ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->version) == "") { ?>
		<th data-name="version"><div id="elh_codigo_actividad_version" class="codigo_actividad_version"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->version->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="version"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->version) ?>',1);"><div id="elh_codigo_actividad_version" class="codigo_actividad_version">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->version->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->version->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->version->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->id_rubro->Visible) { // id_rubro ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->id_rubro) == "") { ?>
		<th data-name="id_rubro"><div id="elh_codigo_actividad_id_rubro" class="codigo_actividad_id_rubro"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->id_rubro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_rubro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->id_rubro) ?>',1);"><div id="elh_codigo_actividad_id_rubro" class="codigo_actividad_id_rubro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->id_rubro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->id_rubro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->id_rubro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->fecha_alta->Visible) { // fecha_alta ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->fecha_alta) == "") { ?>
		<th data-name="fecha_alta"><div id="elh_codigo_actividad_fecha_alta" class="codigo_actividad_fecha_alta"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->fecha_alta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_alta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->fecha_alta) ?>',1);"><div id="elh_codigo_actividad_fecha_alta" class="codigo_actividad_fecha_alta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->fecha_alta->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->fecha_alta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->fecha_alta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->fecha_baja->Visible) { // fecha_baja ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->fecha_baja) == "") { ?>
		<th data-name="fecha_baja"><div id="elh_codigo_actividad_fecha_baja" class="codigo_actividad_fecha_baja"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->fecha_baja->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_baja"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->fecha_baja) ?>',1);"><div id="elh_codigo_actividad_fecha_baja" class="codigo_actividad_fecha_baja">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->fecha_baja->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->fecha_baja->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->fecha_baja->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->objeto_cuantificable->Visible) { // objeto_cuantificable ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->objeto_cuantificable) == "") { ?>
		<th data-name="objeto_cuantificable"><div id="elh_codigo_actividad_objeto_cuantificable" class="codigo_actividad_objeto_cuantificable"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->objeto_cuantificable->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="objeto_cuantificable"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->objeto_cuantificable) ?>',1);"><div id="elh_codigo_actividad_objeto_cuantificable" class="codigo_actividad_objeto_cuantificable">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->objeto_cuantificable->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->objeto_cuantificable->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->objeto_cuantificable->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->manipula_alimento->Visible) { // manipula_alimento ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->manipula_alimento) == "") { ?>
		<th data-name="manipula_alimento"><div id="elh_codigo_actividad_manipula_alimento" class="codigo_actividad_manipula_alimento"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->manipula_alimento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="manipula_alimento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->manipula_alimento) ?>',1);"><div id="elh_codigo_actividad_manipula_alimento" class="codigo_actividad_manipula_alimento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->manipula_alimento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->manipula_alimento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->manipula_alimento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->id_clanae->Visible) { // id_clanae ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->id_clanae) == "") { ?>
		<th data-name="id_clanae"><div id="elh_codigo_actividad_id_clanae" class="codigo_actividad_id_clanae"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->id_clanae->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_clanae"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->id_clanae) ?>',1);"><div id="elh_codigo_actividad_id_clanae" class="codigo_actividad_id_clanae">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->id_clanae->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->id_clanae->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->id_clanae->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($codigo_actividad->id_actividad_padre->Visible) { // id_actividad_padre ?>
	<?php if ($codigo_actividad->SortUrl($codigo_actividad->id_actividad_padre) == "") { ?>
		<th data-name="id_actividad_padre"><div id="elh_codigo_actividad_id_actividad_padre" class="codigo_actividad_id_actividad_padre"><div class="ewTableHeaderCaption"><?php echo $codigo_actividad->id_actividad_padre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_actividad_padre"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $codigo_actividad->SortUrl($codigo_actividad->id_actividad_padre) ?>',1);"><div id="elh_codigo_actividad_id_actividad_padre" class="codigo_actividad_id_actividad_padre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $codigo_actividad->id_actividad_padre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($codigo_actividad->id_actividad_padre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($codigo_actividad->id_actividad_padre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$codigo_actividad_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($codigo_actividad->ExportAll && $codigo_actividad->Export <> "") {
	$codigo_actividad_list->StopRec = $codigo_actividad_list->TotalRecs;
} else {

	// Set the last record to display
	if ($codigo_actividad_list->TotalRecs > $codigo_actividad_list->StartRec + $codigo_actividad_list->DisplayRecs - 1)
		$codigo_actividad_list->StopRec = $codigo_actividad_list->StartRec + $codigo_actividad_list->DisplayRecs - 1;
	else
		$codigo_actividad_list->StopRec = $codigo_actividad_list->TotalRecs;
}
$codigo_actividad_list->RecCnt = $codigo_actividad_list->StartRec - 1;
if ($codigo_actividad_list->Recordset && !$codigo_actividad_list->Recordset->EOF) {
	$codigo_actividad_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $codigo_actividad_list->StartRec > 1)
		$codigo_actividad_list->Recordset->Move($codigo_actividad_list->StartRec - 1);
} elseif (!$codigo_actividad->AllowAddDeleteRow && $codigo_actividad_list->StopRec == 0) {
	$codigo_actividad_list->StopRec = $codigo_actividad->GridAddRowCount;
}

// Initialize aggregate
$codigo_actividad->RowType = EW_ROWTYPE_AGGREGATEINIT;
$codigo_actividad->ResetAttrs();
$codigo_actividad_list->RenderRow();
while ($codigo_actividad_list->RecCnt < $codigo_actividad_list->StopRec) {
	$codigo_actividad_list->RecCnt++;
	if (intval($codigo_actividad_list->RecCnt) >= intval($codigo_actividad_list->StartRec)) {
		$codigo_actividad_list->RowCnt++;

		// Set up key count
		$codigo_actividad_list->KeyCount = $codigo_actividad_list->RowIndex;

		// Init row class and style
		$codigo_actividad->ResetAttrs();
		$codigo_actividad->CssClass = "";
		if ($codigo_actividad->CurrentAction == "gridadd") {
		} else {
			$codigo_actividad_list->LoadRowValues($codigo_actividad_list->Recordset); // Load row values
		}
		$codigo_actividad->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$codigo_actividad->RowAttrs = array_merge($codigo_actividad->RowAttrs, array('data-rowindex'=>$codigo_actividad_list->RowCnt, 'id'=>'r' . $codigo_actividad_list->RowCnt . '_codigo_actividad', 'data-rowtype'=>$codigo_actividad->RowType));

		// Render row
		$codigo_actividad_list->RenderRow();

		// Render list options
		$codigo_actividad_list->RenderListOptions();
?>
	<tr<?php echo $codigo_actividad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$codigo_actividad_list->ListOptions->Render("body", "left", $codigo_actividad_list->RowCnt);
?>
	<?php if ($codigo_actividad->id->Visible) { // id ?>
		<td data-name="id"<?php echo $codigo_actividad->id->CellAttributes() ?>>
<span<?php echo $codigo_actividad->id->ViewAttributes() ?>>
<?php echo $codigo_actividad->id->ListViewValue() ?></span>
<a id="<?php echo $codigo_actividad_list->PageObjName . "_row_" . $codigo_actividad_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($codigo_actividad->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $codigo_actividad->codigo->CellAttributes() ?>>
<span<?php echo $codigo_actividad->codigo->ViewAttributes() ?>>
<?php echo $codigo_actividad->codigo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->descripcion->Visible) { // descripcion ?>
		<td data-name="descripcion"<?php echo $codigo_actividad->descripcion->CellAttributes() ?>>
<span<?php echo $codigo_actividad->descripcion->ViewAttributes() ?>>
<?php echo $codigo_actividad->descripcion->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->descripcion_resumida->Visible) { // descripcion_resumida ?>
		<td data-name="descripcion_resumida"<?php echo $codigo_actividad->descripcion_resumida->CellAttributes() ?>>
<span<?php echo $codigo_actividad->descripcion_resumida->ViewAttributes() ?>>
<?php echo $codigo_actividad->descripcion_resumida->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->observaciones->Visible) { // observaciones ?>
		<td data-name="observaciones"<?php echo $codigo_actividad->observaciones->CellAttributes() ?>>
<span<?php echo $codigo_actividad->observaciones->ViewAttributes() ?>>
<?php echo $codigo_actividad->observaciones->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->version->Visible) { // version ?>
		<td data-name="version"<?php echo $codigo_actividad->version->CellAttributes() ?>>
<span<?php echo $codigo_actividad->version->ViewAttributes() ?>>
<?php echo $codigo_actividad->version->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->id_rubro->Visible) { // id_rubro ?>
		<td data-name="id_rubro"<?php echo $codigo_actividad->id_rubro->CellAttributes() ?>>
<span<?php echo $codigo_actividad->id_rubro->ViewAttributes() ?>>
<?php echo $codigo_actividad->id_rubro->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->fecha_alta->Visible) { // fecha_alta ?>
		<td data-name="fecha_alta"<?php echo $codigo_actividad->fecha_alta->CellAttributes() ?>>
<span<?php echo $codigo_actividad->fecha_alta->ViewAttributes() ?>>
<?php echo $codigo_actividad->fecha_alta->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->fecha_baja->Visible) { // fecha_baja ?>
		<td data-name="fecha_baja"<?php echo $codigo_actividad->fecha_baja->CellAttributes() ?>>
<span<?php echo $codigo_actividad->fecha_baja->ViewAttributes() ?>>
<?php echo $codigo_actividad->fecha_baja->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->objeto_cuantificable->Visible) { // objeto_cuantificable ?>
		<td data-name="objeto_cuantificable"<?php echo $codigo_actividad->objeto_cuantificable->CellAttributes() ?>>
<span<?php echo $codigo_actividad->objeto_cuantificable->ViewAttributes() ?>>
<?php echo $codigo_actividad->objeto_cuantificable->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->manipula_alimento->Visible) { // manipula_alimento ?>
		<td data-name="manipula_alimento"<?php echo $codigo_actividad->manipula_alimento->CellAttributes() ?>>
<span<?php echo $codigo_actividad->manipula_alimento->ViewAttributes() ?>>
<?php echo $codigo_actividad->manipula_alimento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->id_clanae->Visible) { // id_clanae ?>
		<td data-name="id_clanae"<?php echo $codigo_actividad->id_clanae->CellAttributes() ?>>
<span<?php echo $codigo_actividad->id_clanae->ViewAttributes() ?>>
<?php echo $codigo_actividad->id_clanae->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($codigo_actividad->id_actividad_padre->Visible) { // id_actividad_padre ?>
		<td data-name="id_actividad_padre"<?php echo $codigo_actividad->id_actividad_padre->CellAttributes() ?>>
<span<?php echo $codigo_actividad->id_actividad_padre->ViewAttributes() ?>>
<?php echo $codigo_actividad->id_actividad_padre->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$codigo_actividad_list->ListOptions->Render("body", "right", $codigo_actividad_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($codigo_actividad->CurrentAction <> "gridadd")
		$codigo_actividad_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($codigo_actividad->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($codigo_actividad_list->Recordset)
	$codigo_actividad_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($codigo_actividad_list->TotalRecs == 0 && $codigo_actividad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($codigo_actividad_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($codigo_actividad->Export == "") { ?>
<script type="text/javascript">
fcodigo_actividadlistsrch.Init();
fcodigo_actividadlist.Init();
</script>
<?php } ?>
<?php
$codigo_actividad_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($codigo_actividad->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "cciag_footer.php" ?>
<?php
$codigo_actividad_list->Page_Terminate();
?>
