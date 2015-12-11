<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "hoja_rutasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "gastosgridcls.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$hoja_rutas_list = NULL; // Initialize page object first

class choja_rutas_list extends choja_rutas {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'hoja_rutas';

	// Page object name
	var $PageObjName = 'hoja_rutas_list';

	// Grid form hidden field names
	var $FormName = 'fhoja_rutaslist';
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

		// Table object (hoja_rutas)
		if (!isset($GLOBALS["hoja_rutas"]) || get_class($GLOBALS["hoja_rutas"]) == "choja_rutas") {
			$GLOBALS["hoja_rutas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["hoja_rutas"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "hoja_rutasadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "hoja_rutasdelete.php";
		$this->MultiUpdateUrl = "hoja_rutasupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'hoja_rutas', TRUE);

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

			// Process auto fill for detail table 'gastos'
			if (@$_POST["grid"] == "fgastosgrid") {
				if (!isset($GLOBALS["gastos_grid"])) $GLOBALS["gastos_grid"] = new cgastos_grid;
				$GLOBALS["gastos_grid"]->Page_Init();
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
		global $EW_EXPORT, $hoja_rutas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($hoja_rutas);
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
	var $gastos_Count;
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
		$this->BuildSearchSql($sWhere, $this->fecha_ini, $Default, FALSE); // fecha_ini
		$this->BuildSearchSql($sWhere, $this->id_cliente, $Default, FALSE); // id_cliente
		$this->BuildSearchSql($sWhere, $this->id_localidad_origen, $Default, FALSE); // id_localidad_origen
		$this->BuildSearchSql($sWhere, $this->Origen, $Default, FALSE); // Origen
		$this->BuildSearchSql($sWhere, $this->id_localidad_destino, $Default, FALSE); // id_localidad_destino
		$this->BuildSearchSql($sWhere, $this->Destino, $Default, FALSE); // Destino
		$this->BuildSearchSql($sWhere, $this->Km_ini, $Default, FALSE); // Km_ini
		$this->BuildSearchSql($sWhere, $this->estado, $Default, FALSE); // estado
		$this->BuildSearchSql($sWhere, $this->id_vehiculo, $Default, FALSE); // id_vehiculo
		$this->BuildSearchSql($sWhere, $this->id_tipo_carga, $Default, FALSE); // id_tipo_carga
		$this->BuildSearchSql($sWhere, $this->km_fin, $Default, FALSE); // km_fin
		$this->BuildSearchSql($sWhere, $this->fecha_fin, $Default, FALSE); // fecha_fin
		$this->BuildSearchSql($sWhere, $this->adelanto, $Default, FALSE); // adelanto
		$this->BuildSearchSql($sWhere, $this->kg_carga, $Default, FALSE); // kg_carga
		$this->BuildSearchSql($sWhere, $this->tarifa, $Default, FALSE); // tarifa
		$this->BuildSearchSql($sWhere, $this->porcentaje, $Default, FALSE); // porcentaje

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->codigo->AdvancedSearch->Save(); // codigo
			$this->fecha_ini->AdvancedSearch->Save(); // fecha_ini
			$this->id_cliente->AdvancedSearch->Save(); // id_cliente
			$this->id_localidad_origen->AdvancedSearch->Save(); // id_localidad_origen
			$this->Origen->AdvancedSearch->Save(); // Origen
			$this->id_localidad_destino->AdvancedSearch->Save(); // id_localidad_destino
			$this->Destino->AdvancedSearch->Save(); // Destino
			$this->Km_ini->AdvancedSearch->Save(); // Km_ini
			$this->estado->AdvancedSearch->Save(); // estado
			$this->id_vehiculo->AdvancedSearch->Save(); // id_vehiculo
			$this->id_tipo_carga->AdvancedSearch->Save(); // id_tipo_carga
			$this->km_fin->AdvancedSearch->Save(); // km_fin
			$this->fecha_fin->AdvancedSearch->Save(); // fecha_fin
			$this->adelanto->AdvancedSearch->Save(); // adelanto
			$this->kg_carga->AdvancedSearch->Save(); // kg_carga
			$this->tarifa->AdvancedSearch->Save(); // tarifa
			$this->porcentaje->AdvancedSearch->Save(); // porcentaje
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
		$this->BuildBasicSearchSQL($sWhere, $this->Origen, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Destino, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->estado, $arKeywords, $type);
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
		if ($this->fecha_ini->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_cliente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_localidad_origen->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Origen->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_localidad_destino->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Destino->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Km_ini->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_vehiculo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_tipo_carga->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->km_fin->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_fin->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->adelanto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->kg_carga->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tarifa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->porcentaje->AdvancedSearch->IssetSession())
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
		$this->fecha_ini->AdvancedSearch->UnsetSession();
		$this->id_cliente->AdvancedSearch->UnsetSession();
		$this->id_localidad_origen->AdvancedSearch->UnsetSession();
		$this->Origen->AdvancedSearch->UnsetSession();
		$this->id_localidad_destino->AdvancedSearch->UnsetSession();
		$this->Destino->AdvancedSearch->UnsetSession();
		$this->Km_ini->AdvancedSearch->UnsetSession();
		$this->estado->AdvancedSearch->UnsetSession();
		$this->id_vehiculo->AdvancedSearch->UnsetSession();
		$this->id_tipo_carga->AdvancedSearch->UnsetSession();
		$this->km_fin->AdvancedSearch->UnsetSession();
		$this->fecha_fin->AdvancedSearch->UnsetSession();
		$this->adelanto->AdvancedSearch->UnsetSession();
		$this->kg_carga->AdvancedSearch->UnsetSession();
		$this->tarifa->AdvancedSearch->UnsetSession();
		$this->porcentaje->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->codigo->AdvancedSearch->Load();
		$this->fecha_ini->AdvancedSearch->Load();
		$this->id_cliente->AdvancedSearch->Load();
		$this->id_localidad_origen->AdvancedSearch->Load();
		$this->Origen->AdvancedSearch->Load();
		$this->id_localidad_destino->AdvancedSearch->Load();
		$this->Destino->AdvancedSearch->Load();
		$this->Km_ini->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
		$this->id_vehiculo->AdvancedSearch->Load();
		$this->id_tipo_carga->AdvancedSearch->Load();
		$this->km_fin->AdvancedSearch->Load();
		$this->fecha_fin->AdvancedSearch->Load();
		$this->adelanto->AdvancedSearch->Load();
		$this->kg_carga->AdvancedSearch->Load();
		$this->tarifa->AdvancedSearch->Load();
		$this->porcentaje->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->fecha_ini); // fecha_ini
			$this->UpdateSort($this->id_cliente); // id_cliente
			$this->UpdateSort($this->Origen); // Origen
			$this->UpdateSort($this->Destino); // Destino
			$this->UpdateSort($this->estado); // estado
			$this->UpdateSort($this->id_vehiculo); // id_vehiculo
			$this->UpdateSort($this->id_tipo_carga); // id_tipo_carga
			$this->UpdateSort($this->adelanto); // adelanto
			$this->UpdateSort($this->kg_carga); // kg_carga
			$this->UpdateSort($this->tarifa); // tarifa
			$this->UpdateSort($this->porcentaje); // porcentaje
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
				$this->fecha_ini->setSort("ASC");
				$this->id_vehiculo->setSort("ASC");
				$this->id_tipo_carga->setSort("ASC");
				$this->id_cliente->setSort("ASC");
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
				$this->fecha_ini->setSort("");
				$this->id_cliente->setSort("");
				$this->Origen->setSort("");
				$this->Destino->setSort("");
				$this->estado->setSort("");
				$this->id_vehiculo->setSort("");
				$this->id_tipo_carga->setSort("");
				$this->adelanto->setSort("");
				$this->kg_carga->setSort("");
				$this->tarifa->setSort("");
				$this->porcentaje->setSort("");
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

		// "detail_gastos"
		$item = &$this->ListOptions->Add("detail_gastos");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'gastos') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["gastos_grid"])) $GLOBALS["gastos_grid"] = new cgastos_grid;

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

		// "sequence"
		$item = &$this->ListOptions->Add("sequence");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE; // Always on left
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

		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);

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
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_gastos"
		$oListOpt = &$this->ListOptions->Items["detail_gastos"];
		if ($Security->AllowList(CurrentProjectID() . 'gastos')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("gastos", "TblCaption");
			$body .= str_replace("%c", $this->gastos_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("gastoslist.php?" . EW_TABLE_SHOW_MASTER . "=hoja_rutas&fk_codigo=" . urlencode(strval($this->codigo->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["gastos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'gastos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=gastos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "gastos";
			}
			if ($GLOBALS["gastos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'gastos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=gastos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "gastos";
			}
			if ($GLOBALS["gastos_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'gastos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=gastos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "gastos";
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
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_gastos");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=gastos") . "\">" . $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["gastos"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["gastos"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'gastos') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "gastos";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fhoja_rutaslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fhoja_rutaslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// fecha_ini
		$this->fecha_ini->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_ini"]);
		if ($this->fecha_ini->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_ini->AdvancedSearch->SearchOperator = @$_GET["z_fecha_ini"];
		$this->fecha_ini->AdvancedSearch->SearchCondition = @$_GET["v_fecha_ini"];
		$this->fecha_ini->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_fecha_ini"]);
		if ($this->fecha_ini->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->fecha_ini->AdvancedSearch->SearchOperator2 = @$_GET["w_fecha_ini"];

		// id_cliente
		$this->id_cliente->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_cliente"]);
		if ($this->id_cliente->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_cliente->AdvancedSearch->SearchOperator = @$_GET["z_id_cliente"];

		// id_localidad_origen
		$this->id_localidad_origen->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_localidad_origen"]);
		if ($this->id_localidad_origen->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_localidad_origen->AdvancedSearch->SearchOperator = @$_GET["z_id_localidad_origen"];

		// Origen
		$this->Origen->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Origen"]);
		if ($this->Origen->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Origen->AdvancedSearch->SearchOperator = @$_GET["z_Origen"];

		// id_localidad_destino
		$this->id_localidad_destino->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_localidad_destino"]);
		if ($this->id_localidad_destino->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_localidad_destino->AdvancedSearch->SearchOperator = @$_GET["z_id_localidad_destino"];

		// Destino
		$this->Destino->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Destino"]);
		if ($this->Destino->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Destino->AdvancedSearch->SearchOperator = @$_GET["z_Destino"];

		// Km_ini
		$this->Km_ini->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Km_ini"]);
		if ($this->Km_ini->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Km_ini->AdvancedSearch->SearchOperator = @$_GET["z_Km_ini"];

		// estado
		$this->estado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estado"]);
		if ($this->estado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estado->AdvancedSearch->SearchOperator = @$_GET["z_estado"];

		// id_vehiculo
		$this->id_vehiculo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_vehiculo"]);
		if ($this->id_vehiculo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_vehiculo->AdvancedSearch->SearchOperator = @$_GET["z_id_vehiculo"];

		// id_tipo_carga
		$this->id_tipo_carga->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_tipo_carga"]);
		if ($this->id_tipo_carga->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_tipo_carga->AdvancedSearch->SearchOperator = @$_GET["z_id_tipo_carga"];

		// km_fin
		$this->km_fin->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_km_fin"]);
		if ($this->km_fin->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->km_fin->AdvancedSearch->SearchOperator = @$_GET["z_km_fin"];

		// fecha_fin
		$this->fecha_fin->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_fin"]);
		if ($this->fecha_fin->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_fin->AdvancedSearch->SearchOperator = @$_GET["z_fecha_fin"];

		// adelanto
		$this->adelanto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_adelanto"]);
		if ($this->adelanto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->adelanto->AdvancedSearch->SearchOperator = @$_GET["z_adelanto"];

		// kg_carga
		$this->kg_carga->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_kg_carga"]);
		if ($this->kg_carga->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->kg_carga->AdvancedSearch->SearchOperator = @$_GET["z_kg_carga"];

		// tarifa
		$this->tarifa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tarifa"]);
		if ($this->tarifa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tarifa->AdvancedSearch->SearchOperator = @$_GET["z_tarifa"];

		// porcentaje
		$this->porcentaje->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_porcentaje"]);
		if ($this->porcentaje->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->porcentaje->AdvancedSearch->SearchOperator = @$_GET["z_porcentaje"];
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
		$this->fecha_ini->setDbValue($rs->fields('fecha_ini'));
		$this->id_cliente->setDbValue($rs->fields('id_cliente'));
		$this->id_localidad_origen->setDbValue($rs->fields('id_localidad_origen'));
		if (array_key_exists('EV__id_localidad_origen', $rs->fields)) {
			$this->id_localidad_origen->VirtualValue = $rs->fields('EV__id_localidad_origen'); // Set up virtual field value
		} else {
			$this->id_localidad_origen->VirtualValue = ""; // Clear value
		}
		$this->Origen->setDbValue($rs->fields('Origen'));
		$this->id_localidad_destino->setDbValue($rs->fields('id_localidad_destino'));
		if (array_key_exists('EV__id_localidad_destino', $rs->fields)) {
			$this->id_localidad_destino->VirtualValue = $rs->fields('EV__id_localidad_destino'); // Set up virtual field value
		} else {
			$this->id_localidad_destino->VirtualValue = ""; // Clear value
		}
		$this->Destino->setDbValue($rs->fields('Destino'));
		$this->Km_ini->setDbValue($rs->fields('Km_ini'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->id_vehiculo->setDbValue($rs->fields('id_vehiculo'));
		if (array_key_exists('EV__id_vehiculo', $rs->fields)) {
			$this->id_vehiculo->VirtualValue = $rs->fields('EV__id_vehiculo'); // Set up virtual field value
		} else {
			$this->id_vehiculo->VirtualValue = ""; // Clear value
		}
		$this->id_tipo_carga->setDbValue($rs->fields('id_tipo_carga'));
		if (array_key_exists('EV__id_tipo_carga', $rs->fields)) {
			$this->id_tipo_carga->VirtualValue = $rs->fields('EV__id_tipo_carga'); // Set up virtual field value
		} else {
			$this->id_tipo_carga->VirtualValue = ""; // Clear value
		}
		$this->km_fin->setDbValue($rs->fields('km_fin'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->adelanto->setDbValue($rs->fields('adelanto'));
		$this->kg_carga->setDbValue($rs->fields('kg_carga'));
		$this->tarifa->setDbValue($rs->fields('tarifa'));
		$this->porcentaje->setDbValue($rs->fields('porcentaje'));
		if (!isset($GLOBALS["gastos_grid"])) $GLOBALS["gastos_grid"] = new cgastos_grid;
		$sDetailFilter = $GLOBALS["gastos"]->SqlDetailFilter_hoja_rutas();
		$sDetailFilter = str_replace("@id_hoja_ruta@", ew_AdjustSql($this->codigo->DbValue), $sDetailFilter);
		$GLOBALS["gastos"]->setCurrentMasterTable("hoja_rutas");
		$sDetailFilter = $GLOBALS["gastos"]->ApplyUserIDFilters($sDetailFilter);
		$this->gastos_Count = $GLOBALS["gastos"]->LoadRecordCount($sDetailFilter);
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->fecha_ini->DbValue = $row['fecha_ini'];
		$this->id_cliente->DbValue = $row['id_cliente'];
		$this->id_localidad_origen->DbValue = $row['id_localidad_origen'];
		$this->Origen->DbValue = $row['Origen'];
		$this->id_localidad_destino->DbValue = $row['id_localidad_destino'];
		$this->Destino->DbValue = $row['Destino'];
		$this->Km_ini->DbValue = $row['Km_ini'];
		$this->estado->DbValue = $row['estado'];
		$this->id_vehiculo->DbValue = $row['id_vehiculo'];
		$this->id_tipo_carga->DbValue = $row['id_tipo_carga'];
		$this->km_fin->DbValue = $row['km_fin'];
		$this->fecha_fin->DbValue = $row['fecha_fin'];
		$this->adelanto->DbValue = $row['adelanto'];
		$this->kg_carga->DbValue = $row['kg_carga'];
		$this->tarifa->DbValue = $row['tarifa'];
		$this->porcentaje->DbValue = $row['porcentaje'];
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
		if ($this->adelanto->FormValue == $this->adelanto->CurrentValue && is_numeric(ew_StrToFloat($this->adelanto->CurrentValue)))
			$this->adelanto->CurrentValue = ew_StrToFloat($this->adelanto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tarifa->FormValue == $this->tarifa->CurrentValue && is_numeric(ew_StrToFloat($this->tarifa->CurrentValue)))
			$this->tarifa->CurrentValue = ew_StrToFloat($this->tarifa->CurrentValue);

		// Convert decimal values if posted back
		if ($this->porcentaje->FormValue == $this->porcentaje->CurrentValue && is_numeric(ew_StrToFloat($this->porcentaje->CurrentValue)))
			$this->porcentaje->CurrentValue = ew_StrToFloat($this->porcentaje->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// fecha_ini
		// id_cliente
		// id_localidad_origen
		// Origen
		// id_localidad_destino
		// Destino
		// Km_ini
		// estado
		// id_vehiculo
		// id_tipo_carga
		// km_fin
		// fecha_fin
		// adelanto
		// kg_carga
		// tarifa
		// porcentaje

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// fecha_ini
			$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
			$this->fecha_ini->ViewValue = ew_FormatDateTime($this->fecha_ini->ViewValue, 7);
			$this->fecha_ini->ViewCustomAttributes = "";

			// id_cliente
			if (strval($this->id_cliente->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `cuit_cuil` AS `DispFld`, `razon_social` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `clientes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_cliente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `razon_social` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_cliente->ViewValue = $rswrk->fields('DispFld');
					$this->id_cliente->ViewValue .= ew_ValueSeparator(1,$this->id_cliente) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_cliente->ViewValue = $this->id_cliente->CurrentValue;
				}
			} else {
				$this->id_cliente->ViewValue = NULL;
			}
			$this->id_cliente->ViewCustomAttributes = "";

			// Origen
			$this->Origen->ViewValue = $this->Origen->CurrentValue;
			$this->Origen->ViewCustomAttributes = "";

			// Destino
			$this->Destino->ViewValue = $this->Destino->CurrentValue;
			$this->Destino->ViewCustomAttributes = "";

			// estado
			$this->estado->ViewValue = $this->estado->CurrentValue;
			$this->estado->ViewCustomAttributes = "";

			// id_vehiculo
			if ($this->id_vehiculo->VirtualValue <> "") {
				$this->id_vehiculo->ViewValue = $this->id_vehiculo->VirtualValue;
			} else {
			if (strval($this->id_vehiculo->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_vehiculo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_vehiculo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_vehiculo->ViewValue = $rswrk->fields('DispFld');
					$this->id_vehiculo->ViewValue .= ew_ValueSeparator(1,$this->id_vehiculo) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_vehiculo->ViewValue = $this->id_vehiculo->CurrentValue;
				}
			} else {
				$this->id_vehiculo->ViewValue = NULL;
			}
			}
			$this->id_vehiculo->ViewCustomAttributes = "";

			// id_tipo_carga
			if ($this->id_tipo_carga->VirtualValue <> "") {
				$this->id_tipo_carga->ViewValue = $this->id_tipo_carga->VirtualValue;
			} else {
			if (strval($this->id_tipo_carga->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_carga->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `Tipo_carga` AS `DispFld`, `precio_base` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_cargas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_carga, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Tipo_carga` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_carga->ViewValue = $rswrk->fields('DispFld');
					$this->id_tipo_carga->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_carga) . ew_FormatCurrency($rswrk->fields('Disp2Fld'), 2, 0, 0, -1);
					$rswrk->Close();
				} else {
					$this->id_tipo_carga->ViewValue = $this->id_tipo_carga->CurrentValue;
				}
			} else {
				$this->id_tipo_carga->ViewValue = NULL;
			}
			}
			$this->id_tipo_carga->ViewCustomAttributes = "";

			// adelanto
			$this->adelanto->ViewValue = $this->adelanto->CurrentValue;
			$this->adelanto->ViewValue = ew_FormatCurrency($this->adelanto->ViewValue, 2, -2, -2, -2);
			$this->adelanto->ViewCustomAttributes = "";

			// kg_carga
			$this->kg_carga->ViewValue = $this->kg_carga->CurrentValue;
			$this->kg_carga->ViewValue = ew_FormatNumber($this->kg_carga->ViewValue, 0, -2, -2, -2);
			$this->kg_carga->ViewCustomAttributes = "";

			// tarifa
			$this->tarifa->ViewValue = $this->tarifa->CurrentValue;
			$this->tarifa->ViewValue = ew_FormatCurrency($this->tarifa->ViewValue, 2, -2, -2, -2);
			$this->tarifa->ViewCustomAttributes = "";

			// porcentaje
			$this->porcentaje->ViewValue = $this->porcentaje->CurrentValue;
			$this->porcentaje->ViewCustomAttributes = "";

			// fecha_ini
			$this->fecha_ini->LinkCustomAttributes = "";
			$this->fecha_ini->HrefValue = "";
			$this->fecha_ini->TooltipValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			$this->id_cliente->HrefValue = "";
			$this->id_cliente->TooltipValue = "";

			// Origen
			$this->Origen->LinkCustomAttributes = "";
			$this->Origen->HrefValue = "";
			$this->Origen->TooltipValue = "";

			// Destino
			$this->Destino->LinkCustomAttributes = "";
			$this->Destino->HrefValue = "";
			$this->Destino->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// id_vehiculo
			$this->id_vehiculo->LinkCustomAttributes = "";
			$this->id_vehiculo->HrefValue = "";
			$this->id_vehiculo->TooltipValue = "";

			// id_tipo_carga
			$this->id_tipo_carga->LinkCustomAttributes = "";
			$this->id_tipo_carga->HrefValue = "";
			$this->id_tipo_carga->TooltipValue = "";

			// adelanto
			$this->adelanto->LinkCustomAttributes = "";
			$this->adelanto->HrefValue = "";
			$this->adelanto->TooltipValue = "";

			// kg_carga
			$this->kg_carga->LinkCustomAttributes = "";
			$this->kg_carga->HrefValue = "";
			$this->kg_carga->TooltipValue = "";

			// tarifa
			$this->tarifa->LinkCustomAttributes = "";
			$this->tarifa->HrefValue = "";
			$this->tarifa->TooltipValue = "";

			// porcentaje
			$this->porcentaje->LinkCustomAttributes = "";
			$this->porcentaje->HrefValue = "";
			$this->porcentaje->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// fecha_ini
			$this->fecha_ini->EditAttrs["class"] = "form-control";
			$this->fecha_ini->EditCustomAttributes = "";
			$this->fecha_ini->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_ini->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_ini->PlaceHolder = ew_RemoveHtml($this->fecha_ini->FldCaption());
			$this->fecha_ini->EditAttrs["class"] = "form-control";
			$this->fecha_ini->EditCustomAttributes = "";
			$this->fecha_ini->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_ini->AdvancedSearch->SearchValue2, 7), 7));
			$this->fecha_ini->PlaceHolder = ew_RemoveHtml($this->fecha_ini->FldCaption());

			// id_cliente
			$this->id_cliente->EditAttrs["class"] = "form-control";
			$this->id_cliente->EditCustomAttributes = "";
			if (trim(strval($this->id_cliente->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_cliente->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `cuit_cuil` AS `DispFld`, `razon_social` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `clientes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_cliente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `razon_social` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_cliente->EditValue = $arwrk;

			// Origen
			$this->Origen->EditAttrs["class"] = "form-control";
			$this->Origen->EditCustomAttributes = "";
			$this->Origen->EditValue = ew_HtmlEncode($this->Origen->AdvancedSearch->SearchValue);
			$this->Origen->PlaceHolder = ew_RemoveHtml($this->Origen->FldCaption());

			// Destino
			$this->Destino->EditAttrs["class"] = "form-control";
			$this->Destino->EditCustomAttributes = "";
			$this->Destino->EditValue = ew_HtmlEncode($this->Destino->AdvancedSearch->SearchValue);
			$this->Destino->PlaceHolder = ew_RemoveHtml($this->Destino->FldCaption());

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = ew_HtmlEncode($this->estado->AdvancedSearch->SearchValue);
			$this->estado->PlaceHolder = ew_RemoveHtml($this->estado->FldCaption());

			// id_vehiculo
			$this->id_vehiculo->EditAttrs["class"] = "form-control";
			$this->id_vehiculo->EditCustomAttributes = "";
			$this->id_vehiculo->EditValue = ew_HtmlEncode($this->id_vehiculo->AdvancedSearch->SearchValue);
			$this->id_vehiculo->PlaceHolder = ew_RemoveHtml($this->id_vehiculo->FldCaption());

			// id_tipo_carga
			$this->id_tipo_carga->EditAttrs["class"] = "form-control";
			$this->id_tipo_carga->EditCustomAttributes = "";
			$this->id_tipo_carga->EditValue = ew_HtmlEncode($this->id_tipo_carga->AdvancedSearch->SearchValue);
			$this->id_tipo_carga->PlaceHolder = ew_RemoveHtml($this->id_tipo_carga->FldCaption());

			// adelanto
			$this->adelanto->EditAttrs["class"] = "form-control";
			$this->adelanto->EditCustomAttributes = "";
			$this->adelanto->EditValue = ew_HtmlEncode($this->adelanto->AdvancedSearch->SearchValue);
			$this->adelanto->PlaceHolder = ew_RemoveHtml($this->adelanto->FldCaption());

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

			// porcentaje
			$this->porcentaje->EditAttrs["class"] = "form-control";
			$this->porcentaje->EditCustomAttributes = "";
			$this->porcentaje->EditValue = ew_HtmlEncode($this->porcentaje->AdvancedSearch->SearchValue);
			$this->porcentaje->PlaceHolder = ew_RemoveHtml($this->porcentaje->FldCaption());
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
		if (!ew_CheckEuroDate($this->fecha_ini->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha_ini->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_ini->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->fecha_ini->FldErrMsg());
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
		$this->codigo->AdvancedSearch->Load();
		$this->fecha_ini->AdvancedSearch->Load();
		$this->id_cliente->AdvancedSearch->Load();
		$this->id_localidad_origen->AdvancedSearch->Load();
		$this->Origen->AdvancedSearch->Load();
		$this->id_localidad_destino->AdvancedSearch->Load();
		$this->Destino->AdvancedSearch->Load();
		$this->Km_ini->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
		$this->id_vehiculo->AdvancedSearch->Load();
		$this->id_tipo_carga->AdvancedSearch->Load();
		$this->km_fin->AdvancedSearch->Load();
		$this->fecha_fin->AdvancedSearch->Load();
		$this->adelanto->AdvancedSearch->Load();
		$this->kg_carga->AdvancedSearch->Load();
		$this->tarifa->AdvancedSearch->Load();
		$this->porcentaje->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_hoja_rutas\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_hoja_rutas',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fhoja_rutaslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		//-----------------------------------------
		// Se agregan columnas de calculos.
		//-----------------------------------------

		$opt1 = &$this->ListOptions->Add("SubTotal");
		$opt2 = &$this->ListOptions->Add("Comision");
		$opt1->Header = "SubTotal";

	//	$opt->OnLeft = FALSE; // Link on left	
		$opt2->Header = "Comisión";

	//	$opt2->OnLeft = FALSE; // Link on left	
		$opt1->MoveTo(1); // Move to first column
		$opt2->MoveTo(0); // Move to first column	
	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		//-----------------------------------------------------------------------------
		// Calculo de Kg x precio por Kg.
		//-----------------------------------------------------------------------------

		$subtotal = ($this->kg_carga->CurrentValue * $this->tarifa->CurrentValue);
		$this->ListOptions->Items["SubTotal"]->Body = "$ ".number_format($subtotal, 2);

		//-----------------------------------------------------------------------------
		//     Calculo del porcentaje de comision del chofer sobre el subtotal.
		//-----------------------------------------------------------------------------

		$porcentaje = ($this->porcentaje->CurrentValue * $subtotal / 100);
		$this->ListOptions->Items["Comision"]->Body = "$ ".number_format($porcentaje, 2);
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
if (!isset($hoja_rutas_list)) $hoja_rutas_list = new choja_rutas_list();

// Page init
$hoja_rutas_list->Page_Init();

// Page main
$hoja_rutas_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hoja_rutas_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($hoja_rutas->Export == "") { ?>
<script type="text/javascript">

// Page object
var hoja_rutas_list = new ew_Page("hoja_rutas_list");
hoja_rutas_list.PageID = "list"; // Page ID
var EW_PAGE_ID = hoja_rutas_list.PageID; // For backward compatibility

// Form object
var fhoja_rutaslist = new ew_Form("fhoja_rutaslist");
fhoja_rutaslist.FormKeyCountName = '<?php echo $hoja_rutas_list->FormKeyCountName ?>';

// Form_CustomValidate event
fhoja_rutaslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoja_rutaslist.ValidateRequired = true;
<?php } else { ?>
fhoja_rutaslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhoja_rutaslist.Lists["x_id_cliente"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_cuit_cuil","x_razon_social","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutaslist.Lists["x_id_vehiculo"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Patente","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutaslist.Lists["x_id_tipo_carga"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Tipo_carga","x_precio_base","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fhoja_rutaslistsrch = new ew_Form("fhoja_rutaslistsrch");

// Validate function for search
fhoja_rutaslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_fecha_ini");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_rutas->fecha_ini->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fhoja_rutaslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoja_rutaslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fhoja_rutaslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fhoja_rutaslistsrch.Lists["x_id_cliente"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_cuit_cuil","x_razon_social","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($hoja_rutas->Export == "") { ?>
<div class="ewToolbar">
<?php if ($hoja_rutas->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($hoja_rutas_list->TotalRecs > 0 && $hoja_rutas_list->ExportOptions->Visible()) { ?>
<?php $hoja_rutas_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($hoja_rutas_list->SearchOptions->Visible()) { ?>
<?php $hoja_rutas_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($hoja_rutas->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($hoja_rutas_list->TotalRecs <= 0)
			$hoja_rutas_list->TotalRecs = $hoja_rutas->SelectRecordCount();
	} else {
		if (!$hoja_rutas_list->Recordset && ($hoja_rutas_list->Recordset = $hoja_rutas_list->LoadRecordset()))
			$hoja_rutas_list->TotalRecs = $hoja_rutas_list->Recordset->RecordCount();
	}
	$hoja_rutas_list->StartRec = 1;
	if ($hoja_rutas_list->DisplayRecs <= 0 || ($hoja_rutas->Export <> "" && $hoja_rutas->ExportAll)) // Display all records
		$hoja_rutas_list->DisplayRecs = $hoja_rutas_list->TotalRecs;
	if (!($hoja_rutas->Export <> "" && $hoja_rutas->ExportAll))
		$hoja_rutas_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$hoja_rutas_list->Recordset = $hoja_rutas_list->LoadRecordset($hoja_rutas_list->StartRec-1, $hoja_rutas_list->DisplayRecs);

	// Set no record found message
	if ($hoja_rutas->CurrentAction == "" && $hoja_rutas_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$hoja_rutas_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($hoja_rutas_list->SearchWhere == "0=101")
			$hoja_rutas_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$hoja_rutas_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$hoja_rutas_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($hoja_rutas->Export == "" && $hoja_rutas->CurrentAction == "") { ?>
<form name="fhoja_rutaslistsrch" id="fhoja_rutaslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($hoja_rutas_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fhoja_rutaslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="hoja_rutas">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$hoja_rutas_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$hoja_rutas->RowType = EW_ROWTYPE_SEARCH;

// Render row
$hoja_rutas->ResetAttrs();
$hoja_rutas_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($hoja_rutas->fecha_ini->Visible) { // fecha_ini ?>
	<div id="xsc_fecha_ini" class="ewCell form-group">
		<label for="x_fecha_ini" class="ewSearchCaption ewLabel"><?php echo $hoja_rutas->fecha_ini->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_fecha_ini" id="z_fecha_ini" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_fecha_ini" name="x_fecha_ini" id="x_fecha_ini" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->fecha_ini->PlaceHolder) ?>" value="<?php echo $hoja_rutas->fecha_ini->EditValue ?>"<?php echo $hoja_rutas->fecha_ini->EditAttributes() ?>>
<?php if (!$hoja_rutas->fecha_ini->ReadOnly && !$hoja_rutas->fecha_ini->Disabled && !isset($hoja_rutas->fecha_ini->EditAttrs["readonly"]) && !isset($hoja_rutas->fecha_ini->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fhoja_rutaslistsrch", "x_fecha_ini", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_fecha_ini">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_fecha_ini">
<input type="text" data-field="x_fecha_ini" name="y_fecha_ini" id="y_fecha_ini" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->fecha_ini->PlaceHolder) ?>" value="<?php echo $hoja_rutas->fecha_ini->EditValue2 ?>"<?php echo $hoja_rutas->fecha_ini->EditAttributes() ?>>
<?php if (!$hoja_rutas->fecha_ini->ReadOnly && !$hoja_rutas->fecha_ini->Disabled && !isset($hoja_rutas->fecha_ini->EditAttrs["readonly"]) && !isset($hoja_rutas->fecha_ini->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fhoja_rutaslistsrch", "y_fecha_ini", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($hoja_rutas->id_cliente->Visible) { // id_cliente ?>
	<div id="xsc_id_cliente" class="ewCell form-group">
		<label for="x_id_cliente" class="ewSearchCaption ewLabel"><?php echo $hoja_rutas->id_cliente->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_cliente" id="z_id_cliente" value="="></span>
		<span class="ewSearchField">
<select data-field="x_id_cliente" id="x_id_cliente" name="x_id_cliente"<?php echo $hoja_rutas->id_cliente->EditAttributes() ?>>
<?php
if (is_array($hoja_rutas->id_cliente->EditValue)) {
	$arwrk = $hoja_rutas->id_cliente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hoja_rutas->id_cliente->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$hoja_rutas->id_cliente) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `codigo`, `cuit_cuil` AS `DispFld`, `razon_social` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `clientes`";
$sWhereWrk = "";

// Call Lookup selecting
$hoja_rutas->Lookup_Selecting($hoja_rutas->id_cliente, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `razon_social` ASC";
?>
<input type="hidden" name="s_x_id_cliente" id="s_x_id_cliente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($hoja_rutas->Origen->Visible) { // Origen ?>
	<div id="xsc_Origen" class="ewCell form-group">
		<label for="x_Origen" class="ewSearchCaption ewLabel"><?php echo $hoja_rutas->Origen->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Origen" id="z_Origen" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Origen" name="x_Origen" id="x_Origen" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->Origen->PlaceHolder) ?>" value="<?php echo $hoja_rutas->Origen->EditValue ?>"<?php echo $hoja_rutas->Origen->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($hoja_rutas->Destino->Visible) { // Destino ?>
	<div id="xsc_Destino" class="ewCell form-group">
		<label for="x_Destino" class="ewSearchCaption ewLabel"><?php echo $hoja_rutas->Destino->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Destino" id="z_Destino" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Destino" name="x_Destino" id="x_Destino" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->Destino->PlaceHolder) ?>" value="<?php echo $hoja_rutas->Destino->EditValue ?>"<?php echo $hoja_rutas->Destino->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($hoja_rutas_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($hoja_rutas_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $hoja_rutas_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($hoja_rutas_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($hoja_rutas_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($hoja_rutas_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($hoja_rutas_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $hoja_rutas_list->ShowPageHeader(); ?>
<?php
$hoja_rutas_list->ShowMessage();
?>
<?php if ($hoja_rutas_list->TotalRecs > 0 || $hoja_rutas->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($hoja_rutas->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($hoja_rutas->CurrentAction <> "gridadd" && $hoja_rutas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($hoja_rutas_list->Pager)) $hoja_rutas_list->Pager = new cNumericPager($hoja_rutas_list->StartRec, $hoja_rutas_list->DisplayRecs, $hoja_rutas_list->TotalRecs, $hoja_rutas_list->RecRange) ?>
<?php if ($hoja_rutas_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($hoja_rutas_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_list->PageUrl() ?>start=<?php echo $hoja_rutas_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_list->PageUrl() ?>start=<?php echo $hoja_rutas_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($hoja_rutas_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $hoja_rutas_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_list->PageUrl() ?>start=<?php echo $hoja_rutas_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_list->PageUrl() ?>start=<?php echo $hoja_rutas_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $hoja_rutas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $hoja_rutas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $hoja_rutas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($hoja_rutas_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fhoja_rutaslist" id="fhoja_rutaslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($hoja_rutas_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $hoja_rutas_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="hoja_rutas">
<div id="gmp_hoja_rutas" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($hoja_rutas_list->TotalRecs > 0) { ?>
<table id="tbl_hoja_rutaslist" class="table ewTable">
<?php echo $hoja_rutas->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$hoja_rutas->RowType = EW_ROWTYPE_HEADER;

// Render list options
$hoja_rutas_list->RenderListOptions();

// Render list options (header, left)
$hoja_rutas_list->ListOptions->Render("header", "left");
?>
<?php if ($hoja_rutas->fecha_ini->Visible) { // fecha_ini ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->fecha_ini) == "") { ?>
		<th data-name="fecha_ini"><div id="elh_hoja_rutas_fecha_ini" class="hoja_rutas_fecha_ini"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->fecha_ini->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_ini"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->fecha_ini) ?>',1);"><div id="elh_hoja_rutas_fecha_ini" class="hoja_rutas_fecha_ini">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->fecha_ini->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->fecha_ini->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->fecha_ini->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hoja_rutas->id_cliente->Visible) { // id_cliente ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->id_cliente) == "") { ?>
		<th data-name="id_cliente"><div id="elh_hoja_rutas_id_cliente" class="hoja_rutas_id_cliente"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->id_cliente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_cliente"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->id_cliente) ?>',1);"><div id="elh_hoja_rutas_id_cliente" class="hoja_rutas_id_cliente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->id_cliente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->id_cliente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->id_cliente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hoja_rutas->Origen->Visible) { // Origen ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->Origen) == "") { ?>
		<th data-name="Origen"><div id="elh_hoja_rutas_Origen" class="hoja_rutas_Origen"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->Origen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Origen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->Origen) ?>',1);"><div id="elh_hoja_rutas_Origen" class="hoja_rutas_Origen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->Origen->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->Origen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->Origen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hoja_rutas->Destino->Visible) { // Destino ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->Destino) == "") { ?>
		<th data-name="Destino"><div id="elh_hoja_rutas_Destino" class="hoja_rutas_Destino"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->Destino->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Destino"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->Destino) ?>',1);"><div id="elh_hoja_rutas_Destino" class="hoja_rutas_Destino">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->Destino->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->Destino->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->Destino->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hoja_rutas->estado->Visible) { // estado ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->estado) == "") { ?>
		<th data-name="estado"><div id="elh_hoja_rutas_estado" class="hoja_rutas_estado"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->estado) ?>',1);"><div id="elh_hoja_rutas_estado" class="hoja_rutas_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->estado->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hoja_rutas->id_vehiculo->Visible) { // id_vehiculo ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->id_vehiculo) == "") { ?>
		<th data-name="id_vehiculo"><div id="elh_hoja_rutas_id_vehiculo" class="hoja_rutas_id_vehiculo"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->id_vehiculo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_vehiculo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->id_vehiculo) ?>',1);"><div id="elh_hoja_rutas_id_vehiculo" class="hoja_rutas_id_vehiculo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->id_vehiculo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->id_vehiculo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->id_vehiculo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hoja_rutas->id_tipo_carga->Visible) { // id_tipo_carga ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->id_tipo_carga) == "") { ?>
		<th data-name="id_tipo_carga"><div id="elh_hoja_rutas_id_tipo_carga" class="hoja_rutas_id_tipo_carga"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->id_tipo_carga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipo_carga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->id_tipo_carga) ?>',1);"><div id="elh_hoja_rutas_id_tipo_carga" class="hoja_rutas_id_tipo_carga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->id_tipo_carga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->id_tipo_carga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->id_tipo_carga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hoja_rutas->adelanto->Visible) { // adelanto ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->adelanto) == "") { ?>
		<th data-name="adelanto"><div id="elh_hoja_rutas_adelanto" class="hoja_rutas_adelanto"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->adelanto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="adelanto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->adelanto) ?>',1);"><div id="elh_hoja_rutas_adelanto" class="hoja_rutas_adelanto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->adelanto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->adelanto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->adelanto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hoja_rutas->kg_carga->Visible) { // kg_carga ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->kg_carga) == "") { ?>
		<th data-name="kg_carga"><div id="elh_hoja_rutas_kg_carga" class="hoja_rutas_kg_carga"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->kg_carga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kg_carga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->kg_carga) ?>',1);"><div id="elh_hoja_rutas_kg_carga" class="hoja_rutas_kg_carga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->kg_carga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->kg_carga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->kg_carga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hoja_rutas->tarifa->Visible) { // tarifa ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->tarifa) == "") { ?>
		<th data-name="tarifa"><div id="elh_hoja_rutas_tarifa" class="hoja_rutas_tarifa"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->tarifa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tarifa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->tarifa) ?>',1);"><div id="elh_hoja_rutas_tarifa" class="hoja_rutas_tarifa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->tarifa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->tarifa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->tarifa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hoja_rutas->porcentaje->Visible) { // porcentaje ?>
	<?php if ($hoja_rutas->SortUrl($hoja_rutas->porcentaje) == "") { ?>
		<th data-name="porcentaje"><div id="elh_hoja_rutas_porcentaje" class="hoja_rutas_porcentaje"><div class="ewTableHeaderCaption"><?php echo $hoja_rutas->porcentaje->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="porcentaje"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $hoja_rutas->SortUrl($hoja_rutas->porcentaje) ?>',1);"><div id="elh_hoja_rutas_porcentaje" class="hoja_rutas_porcentaje">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hoja_rutas->porcentaje->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hoja_rutas->porcentaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hoja_rutas->porcentaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$hoja_rutas_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($hoja_rutas->ExportAll && $hoja_rutas->Export <> "") {
	$hoja_rutas_list->StopRec = $hoja_rutas_list->TotalRecs;
} else {

	// Set the last record to display
	if ($hoja_rutas_list->TotalRecs > $hoja_rutas_list->StartRec + $hoja_rutas_list->DisplayRecs - 1)
		$hoja_rutas_list->StopRec = $hoja_rutas_list->StartRec + $hoja_rutas_list->DisplayRecs - 1;
	else
		$hoja_rutas_list->StopRec = $hoja_rutas_list->TotalRecs;
}
$hoja_rutas_list->RecCnt = $hoja_rutas_list->StartRec - 1;
if ($hoja_rutas_list->Recordset && !$hoja_rutas_list->Recordset->EOF) {
	$hoja_rutas_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $hoja_rutas_list->StartRec > 1)
		$hoja_rutas_list->Recordset->Move($hoja_rutas_list->StartRec - 1);
} elseif (!$hoja_rutas->AllowAddDeleteRow && $hoja_rutas_list->StopRec == 0) {
	$hoja_rutas_list->StopRec = $hoja_rutas->GridAddRowCount;
}

// Initialize aggregate
$hoja_rutas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$hoja_rutas->ResetAttrs();
$hoja_rutas_list->RenderRow();
while ($hoja_rutas_list->RecCnt < $hoja_rutas_list->StopRec) {
	$hoja_rutas_list->RecCnt++;
	if (intval($hoja_rutas_list->RecCnt) >= intval($hoja_rutas_list->StartRec)) {
		$hoja_rutas_list->RowCnt++;

		// Set up key count
		$hoja_rutas_list->KeyCount = $hoja_rutas_list->RowIndex;

		// Init row class and style
		$hoja_rutas->ResetAttrs();
		$hoja_rutas->CssClass = "";
		if ($hoja_rutas->CurrentAction == "gridadd") {
		} else {
			$hoja_rutas_list->LoadRowValues($hoja_rutas_list->Recordset); // Load row values
		}
		$hoja_rutas->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$hoja_rutas->RowAttrs = array_merge($hoja_rutas->RowAttrs, array('data-rowindex'=>$hoja_rutas_list->RowCnt, 'id'=>'r' . $hoja_rutas_list->RowCnt . '_hoja_rutas', 'data-rowtype'=>$hoja_rutas->RowType));

		// Render row
		$hoja_rutas_list->RenderRow();

		// Render list options
		$hoja_rutas_list->RenderListOptions();
?>
	<tr<?php echo $hoja_rutas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$hoja_rutas_list->ListOptions->Render("body", "left", $hoja_rutas_list->RowCnt);
?>
	<?php if ($hoja_rutas->fecha_ini->Visible) { // fecha_ini ?>
		<td data-name="fecha_ini"<?php echo $hoja_rutas->fecha_ini->CellAttributes() ?>>
<span<?php echo $hoja_rutas->fecha_ini->ViewAttributes() ?>>
<?php echo $hoja_rutas->fecha_ini->ListViewValue() ?></span>
<a id="<?php echo $hoja_rutas_list->PageObjName . "_row_" . $hoja_rutas_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($hoja_rutas->id_cliente->Visible) { // id_cliente ?>
		<td data-name="id_cliente"<?php echo $hoja_rutas->id_cliente->CellAttributes() ?>>
<span<?php echo $hoja_rutas->id_cliente->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_cliente->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($hoja_rutas->Origen->Visible) { // Origen ?>
		<td data-name="Origen"<?php echo $hoja_rutas->Origen->CellAttributes() ?>>
<span<?php echo $hoja_rutas->Origen->ViewAttributes() ?>>
<?php echo $hoja_rutas->Origen->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($hoja_rutas->Destino->Visible) { // Destino ?>
		<td data-name="Destino"<?php echo $hoja_rutas->Destino->CellAttributes() ?>>
<span<?php echo $hoja_rutas->Destino->ViewAttributes() ?>>
<?php echo $hoja_rutas->Destino->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($hoja_rutas->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $hoja_rutas->estado->CellAttributes() ?>>
<span<?php echo $hoja_rutas->estado->ViewAttributes() ?>>
<?php echo $hoja_rutas->estado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($hoja_rutas->id_vehiculo->Visible) { // id_vehiculo ?>
		<td data-name="id_vehiculo"<?php echo $hoja_rutas->id_vehiculo->CellAttributes() ?>>
<span<?php echo $hoja_rutas->id_vehiculo->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_vehiculo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($hoja_rutas->id_tipo_carga->Visible) { // id_tipo_carga ?>
		<td data-name="id_tipo_carga"<?php echo $hoja_rutas->id_tipo_carga->CellAttributes() ?>>
<span<?php echo $hoja_rutas->id_tipo_carga->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_tipo_carga->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($hoja_rutas->adelanto->Visible) { // adelanto ?>
		<td data-name="adelanto"<?php echo $hoja_rutas->adelanto->CellAttributes() ?>>
<span<?php echo $hoja_rutas->adelanto->ViewAttributes() ?>>
<?php echo $hoja_rutas->adelanto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($hoja_rutas->kg_carga->Visible) { // kg_carga ?>
		<td data-name="kg_carga"<?php echo $hoja_rutas->kg_carga->CellAttributes() ?>>
<span<?php echo $hoja_rutas->kg_carga->ViewAttributes() ?>>
<?php echo $hoja_rutas->kg_carga->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($hoja_rutas->tarifa->Visible) { // tarifa ?>
		<td data-name="tarifa"<?php echo $hoja_rutas->tarifa->CellAttributes() ?>>
<span<?php echo $hoja_rutas->tarifa->ViewAttributes() ?>>
<?php echo $hoja_rutas->tarifa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($hoja_rutas->porcentaje->Visible) { // porcentaje ?>
		<td data-name="porcentaje"<?php echo $hoja_rutas->porcentaje->CellAttributes() ?>>
<span<?php echo $hoja_rutas->porcentaje->ViewAttributes() ?>>
<?php echo $hoja_rutas->porcentaje->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$hoja_rutas_list->ListOptions->Render("body", "right", $hoja_rutas_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($hoja_rutas->CurrentAction <> "gridadd")
		$hoja_rutas_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($hoja_rutas->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($hoja_rutas_list->Recordset)
	$hoja_rutas_list->Recordset->Close();
?>
<?php if ($hoja_rutas->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($hoja_rutas->CurrentAction <> "gridadd" && $hoja_rutas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($hoja_rutas_list->Pager)) $hoja_rutas_list->Pager = new cNumericPager($hoja_rutas_list->StartRec, $hoja_rutas_list->DisplayRecs, $hoja_rutas_list->TotalRecs, $hoja_rutas_list->RecRange) ?>
<?php if ($hoja_rutas_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($hoja_rutas_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_list->PageUrl() ?>start=<?php echo $hoja_rutas_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_list->PageUrl() ?>start=<?php echo $hoja_rutas_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($hoja_rutas_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $hoja_rutas_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_list->PageUrl() ?>start=<?php echo $hoja_rutas_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_list->PageUrl() ?>start=<?php echo $hoja_rutas_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $hoja_rutas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $hoja_rutas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $hoja_rutas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($hoja_rutas_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($hoja_rutas_list->TotalRecs == 0 && $hoja_rutas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($hoja_rutas_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($hoja_rutas->Export == "") { ?>
<script type="text/javascript">
fhoja_rutaslistsrch.Init();
fhoja_rutaslist.Init();
</script>
<?php } ?>
<?php
$hoja_rutas_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($hoja_rutas->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$hoja_rutas_list->Page_Terminate();
?>
