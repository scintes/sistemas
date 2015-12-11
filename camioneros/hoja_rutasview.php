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

$hoja_rutas_view = NULL; // Initialize page object first

class choja_rutas_view extends choja_rutas {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'hoja_rutas';

	// Page object name
	var $PageObjName = 'hoja_rutas_view';

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
		$KeyUrl = "";
		if (@$_GET["codigo"] <> "") {
			$this->RecKey["codigo"] = $_GET["codigo"];
			$KeyUrl .= "&amp;codigo=" . urlencode($this->RecKey["codigo"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'hoja_rutas', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (@$_GET["codigo"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["codigo"]);
		}

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $gastos_Count;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["codigo"] <> "") {
				$this->codigo->setQueryStringValue($_GET["codigo"]);
				$this->RecKey["codigo"] = $this->codigo->QueryStringValue;
			} elseif (@$_POST["codigo"] <> "") {
				$this->codigo->setFormValue($_POST["codigo"]);
				$this->RecKey["codigo"] = $this->codigo->FormValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("hoja_rutaslist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->codigo->CurrentValue) == strval($this->Recordset->fields('codigo'))) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "hoja_rutaslist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "hoja_rutaslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_gastos"
		$item = &$option->Add("detail_gastos");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("gastos", "TblCaption");
		$body .= str_replace("%c", $this->gastos_Count, $Language->Phrase("DetailCount"));
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("gastoslist.php?" . EW_TABLE_SHOW_MASTER . "=hoja_rutas&fk_codigo=" . urlencode(strval($this->codigo->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["gastos_grid"] && $GLOBALS["gastos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'gastos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=gastos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "gastos";
		}
		if ($GLOBALS["gastos_grid"] && $GLOBALS["gastos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'gastos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=gastos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "gastos";
		}
		if ($GLOBALS["gastos_grid"] && $GLOBALS["gastos_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'gastos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=gastos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "gastos";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'gastos');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "gastos";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
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
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

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

			// id_localidad_origen
			if ($this->id_localidad_origen->VirtualValue <> "") {
				$this->id_localidad_origen->ViewValue = $this->id_localidad_origen->VirtualValue;
			} else {
			if (strval($this->id_localidad_origen->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad_origen->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `cp` AS `DispFld`, `localidad` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_localidad_origen, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `localidad` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_localidad_origen->ViewValue = $rswrk->fields('DispFld');
					$this->id_localidad_origen->ViewValue .= ew_ValueSeparator(1,$this->id_localidad_origen) . $rswrk->fields('Disp2Fld');
					$this->id_localidad_origen->ViewValue .= ew_ValueSeparator(2,$this->id_localidad_origen) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_localidad_origen->ViewValue = $this->id_localidad_origen->CurrentValue;
				}
			} else {
				$this->id_localidad_origen->ViewValue = NULL;
			}
			}
			$this->id_localidad_origen->ViewCustomAttributes = "";

			// Origen
			$this->Origen->ViewValue = $this->Origen->CurrentValue;
			$this->Origen->ViewCustomAttributes = "";

			// id_localidad_destino
			if ($this->id_localidad_destino->VirtualValue <> "") {
				$this->id_localidad_destino->ViewValue = $this->id_localidad_destino->VirtualValue;
			} else {
			if (strval($this->id_localidad_destino->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad_destino->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `cp` AS `DispFld`, `localidad` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_localidad_destino, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `localidad` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_localidad_destino->ViewValue = $rswrk->fields('DispFld');
					$this->id_localidad_destino->ViewValue .= ew_ValueSeparator(1,$this->id_localidad_destino) . $rswrk->fields('Disp2Fld');
					$this->id_localidad_destino->ViewValue .= ew_ValueSeparator(2,$this->id_localidad_destino) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_localidad_destino->ViewValue = $this->id_localidad_destino->CurrentValue;
				}
			} else {
				$this->id_localidad_destino->ViewValue = NULL;
			}
			}
			$this->id_localidad_destino->ViewCustomAttributes = "";

			// Destino
			$this->Destino->ViewValue = $this->Destino->CurrentValue;
			$this->Destino->ViewCustomAttributes = "";

			// Km_ini
			$this->Km_ini->ViewValue = $this->Km_ini->CurrentValue;
			$this->Km_ini->ViewValue = ew_FormatNumber($this->Km_ini->ViewValue, 0, -2, -2, -2);
			$this->Km_ini->ViewCustomAttributes = "";

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

			// km_fin
			$this->km_fin->ViewValue = $this->km_fin->CurrentValue;
			$this->km_fin->ViewValue = ew_FormatNumber($this->km_fin->ViewValue, 0, -2, -2, -2);
			$this->km_fin->ViewCustomAttributes = "";

			// fecha_fin
			$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
			$this->fecha_fin->ViewValue = ew_FormatDateTime($this->fecha_fin->ViewValue, 7);
			$this->fecha_fin->ViewCustomAttributes = "";

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

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// fecha_ini
			$this->fecha_ini->LinkCustomAttributes = "";
			$this->fecha_ini->HrefValue = "";
			$this->fecha_ini->TooltipValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			$this->id_cliente->HrefValue = "";
			$this->id_cliente->TooltipValue = "";

			// id_localidad_origen
			$this->id_localidad_origen->LinkCustomAttributes = "";
			$this->id_localidad_origen->HrefValue = "";
			$this->id_localidad_origen->TooltipValue = "";

			// Origen
			$this->Origen->LinkCustomAttributes = "";
			$this->Origen->HrefValue = "";
			$this->Origen->TooltipValue = "";

			// id_localidad_destino
			$this->id_localidad_destino->LinkCustomAttributes = "";
			$this->id_localidad_destino->HrefValue = "";
			$this->id_localidad_destino->TooltipValue = "";

			// Destino
			$this->Destino->LinkCustomAttributes = "";
			$this->Destino->HrefValue = "";
			$this->Destino->TooltipValue = "";

			// Km_ini
			$this->Km_ini->LinkCustomAttributes = "";
			$this->Km_ini->HrefValue = "";
			$this->Km_ini->TooltipValue = "";

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

			// km_fin
			$this->km_fin->LinkCustomAttributes = "";
			$this->km_fin->HrefValue = "";
			$this->km_fin->TooltipValue = "";

			// fecha_fin
			$this->fecha_fin->LinkCustomAttributes = "";
			$this->fecha_fin->HrefValue = "";
			$this->fecha_fin->TooltipValue = "";

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
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_hoja_rutas\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_hoja_rutas',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fhoja_rutasview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

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
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");

		// Export detail records (gastos)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("gastos", explode(",", $this->getCurrentDetailTable()))) {
			global $gastos;
			if (!isset($gastos)) $gastos = new cgastos;
			$rsdetail = $gastos->LoadRs($gastos->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$gastos->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
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

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("gastos", $DetailTblVar)) {
				if (!isset($GLOBALS["gastos_grid"]))
					$GLOBALS["gastos_grid"] = new cgastos_grid;
				if ($GLOBALS["gastos_grid"]->DetailView) {
					$GLOBALS["gastos_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["gastos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["gastos_grid"]->setStartRecordNumber(1);
					$GLOBALS["gastos_grid"]->id_hoja_ruta->FldIsDetailKey = TRUE;
					$GLOBALS["gastos_grid"]->id_hoja_ruta->CurrentValue = $this->codigo->CurrentValue;
					$GLOBALS["gastos_grid"]->id_hoja_ruta->setSessionValue($GLOBALS["gastos_grid"]->id_hoja_ruta->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "hoja_rutaslist.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($hoja_rutas_view)) $hoja_rutas_view = new choja_rutas_view();

// Page init
$hoja_rutas_view->Page_Init();

// Page main
$hoja_rutas_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hoja_rutas_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($hoja_rutas->Export == "") { ?>
<script type="text/javascript">

// Page object
var hoja_rutas_view = new ew_Page("hoja_rutas_view");
hoja_rutas_view.PageID = "view"; // Page ID
var EW_PAGE_ID = hoja_rutas_view.PageID; // For backward compatibility

// Form object
var fhoja_rutasview = new ew_Form("fhoja_rutasview");

// Form_CustomValidate event
fhoja_rutasview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoja_rutasview.ValidateRequired = true;
<?php } else { ?>
fhoja_rutasview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhoja_rutasview.Lists["x_id_cliente"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_cuit_cuil","x_razon_social","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutasview.Lists["x_id_localidad_origen"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_cp","x_localidad","x_provincia",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutasview.Lists["x_id_localidad_destino"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_cp","x_localidad","x_provincia",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutasview.Lists["x_id_vehiculo"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Patente","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutasview.Lists["x_id_tipo_carga"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Tipo_carga","x_precio_base","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
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
<?php $hoja_rutas_view->ExportOptions->Render("body") ?>
<?php
	foreach ($hoja_rutas_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if ($hoja_rutas->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $hoja_rutas_view->ShowPageHeader(); ?>
<?php
$hoja_rutas_view->ShowMessage();
?>
<?php if ($hoja_rutas->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($hoja_rutas_view->Pager)) $hoja_rutas_view->Pager = new cNumericPager($hoja_rutas_view->StartRec, $hoja_rutas_view->DisplayRecs, $hoja_rutas_view->TotalRecs, $hoja_rutas_view->RecRange) ?>
<?php if ($hoja_rutas_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($hoja_rutas_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_view->PageUrl() ?>start=<?php echo $hoja_rutas_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_view->PageUrl() ?>start=<?php echo $hoja_rutas_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($hoja_rutas_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $hoja_rutas_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_view->PageUrl() ?>start=<?php echo $hoja_rutas_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_view->PageUrl() ?>start=<?php echo $hoja_rutas_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fhoja_rutasview" id="fhoja_rutasview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($hoja_rutas_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $hoja_rutas_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="hoja_rutas">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($hoja_rutas->codigo->Visible) { // codigo ?>
	<tr id="r_codigo">
		<td><span id="elh_hoja_rutas_codigo"><?php echo $hoja_rutas->codigo->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->codigo->CellAttributes() ?>>
<span id="el_hoja_rutas_codigo">
<span<?php echo $hoja_rutas->codigo->ViewAttributes() ?>>
<?php echo $hoja_rutas->codigo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->fecha_ini->Visible) { // fecha_ini ?>
	<tr id="r_fecha_ini">
		<td><span id="elh_hoja_rutas_fecha_ini"><?php echo $hoja_rutas->fecha_ini->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->fecha_ini->CellAttributes() ?>>
<span id="el_hoja_rutas_fecha_ini">
<span<?php echo $hoja_rutas->fecha_ini->ViewAttributes() ?>>
<?php echo $hoja_rutas->fecha_ini->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->id_cliente->Visible) { // id_cliente ?>
	<tr id="r_id_cliente">
		<td><span id="elh_hoja_rutas_id_cliente"><?php echo $hoja_rutas->id_cliente->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->id_cliente->CellAttributes() ?>>
<span id="el_hoja_rutas_id_cliente">
<span<?php echo $hoja_rutas->id_cliente->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_cliente->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->id_localidad_origen->Visible) { // id_localidad_origen ?>
	<tr id="r_id_localidad_origen">
		<td><span id="elh_hoja_rutas_id_localidad_origen"><?php echo $hoja_rutas->id_localidad_origen->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->id_localidad_origen->CellAttributes() ?>>
<span id="el_hoja_rutas_id_localidad_origen">
<span<?php echo $hoja_rutas->id_localidad_origen->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_localidad_origen->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->Origen->Visible) { // Origen ?>
	<tr id="r_Origen">
		<td><span id="elh_hoja_rutas_Origen"><?php echo $hoja_rutas->Origen->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->Origen->CellAttributes() ?>>
<span id="el_hoja_rutas_Origen">
<span<?php echo $hoja_rutas->Origen->ViewAttributes() ?>>
<?php echo $hoja_rutas->Origen->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->id_localidad_destino->Visible) { // id_localidad_destino ?>
	<tr id="r_id_localidad_destino">
		<td><span id="elh_hoja_rutas_id_localidad_destino"><?php echo $hoja_rutas->id_localidad_destino->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->id_localidad_destino->CellAttributes() ?>>
<span id="el_hoja_rutas_id_localidad_destino">
<span<?php echo $hoja_rutas->id_localidad_destino->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_localidad_destino->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->Destino->Visible) { // Destino ?>
	<tr id="r_Destino">
		<td><span id="elh_hoja_rutas_Destino"><?php echo $hoja_rutas->Destino->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->Destino->CellAttributes() ?>>
<span id="el_hoja_rutas_Destino">
<span<?php echo $hoja_rutas->Destino->ViewAttributes() ?>>
<?php echo $hoja_rutas->Destino->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->Km_ini->Visible) { // Km_ini ?>
	<tr id="r_Km_ini">
		<td><span id="elh_hoja_rutas_Km_ini"><?php echo $hoja_rutas->Km_ini->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->Km_ini->CellAttributes() ?>>
<span id="el_hoja_rutas_Km_ini">
<span<?php echo $hoja_rutas->Km_ini->ViewAttributes() ?>>
<?php echo $hoja_rutas->Km_ini->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_hoja_rutas_estado"><?php echo $hoja_rutas->estado->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->estado->CellAttributes() ?>>
<span id="el_hoja_rutas_estado">
<span<?php echo $hoja_rutas->estado->ViewAttributes() ?>>
<?php echo $hoja_rutas->estado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->id_vehiculo->Visible) { // id_vehiculo ?>
	<tr id="r_id_vehiculo">
		<td><span id="elh_hoja_rutas_id_vehiculo"><?php echo $hoja_rutas->id_vehiculo->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->id_vehiculo->CellAttributes() ?>>
<span id="el_hoja_rutas_id_vehiculo">
<span<?php echo $hoja_rutas->id_vehiculo->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_vehiculo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->id_tipo_carga->Visible) { // id_tipo_carga ?>
	<tr id="r_id_tipo_carga">
		<td><span id="elh_hoja_rutas_id_tipo_carga"><?php echo $hoja_rutas->id_tipo_carga->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->id_tipo_carga->CellAttributes() ?>>
<span id="el_hoja_rutas_id_tipo_carga">
<span<?php echo $hoja_rutas->id_tipo_carga->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_tipo_carga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->km_fin->Visible) { // km_fin ?>
	<tr id="r_km_fin">
		<td><span id="elh_hoja_rutas_km_fin"><?php echo $hoja_rutas->km_fin->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->km_fin->CellAttributes() ?>>
<span id="el_hoja_rutas_km_fin">
<span<?php echo $hoja_rutas->km_fin->ViewAttributes() ?>>
<?php echo $hoja_rutas->km_fin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->fecha_fin->Visible) { // fecha_fin ?>
	<tr id="r_fecha_fin">
		<td><span id="elh_hoja_rutas_fecha_fin"><?php echo $hoja_rutas->fecha_fin->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->fecha_fin->CellAttributes() ?>>
<span id="el_hoja_rutas_fecha_fin">
<span<?php echo $hoja_rutas->fecha_fin->ViewAttributes() ?>>
<?php echo $hoja_rutas->fecha_fin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->adelanto->Visible) { // adelanto ?>
	<tr id="r_adelanto">
		<td><span id="elh_hoja_rutas_adelanto"><?php echo $hoja_rutas->adelanto->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->adelanto->CellAttributes() ?>>
<span id="el_hoja_rutas_adelanto">
<span<?php echo $hoja_rutas->adelanto->ViewAttributes() ?>>
<?php echo $hoja_rutas->adelanto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->kg_carga->Visible) { // kg_carga ?>
	<tr id="r_kg_carga">
		<td><span id="elh_hoja_rutas_kg_carga"><?php echo $hoja_rutas->kg_carga->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->kg_carga->CellAttributes() ?>>
<span id="el_hoja_rutas_kg_carga">
<span<?php echo $hoja_rutas->kg_carga->ViewAttributes() ?>>
<?php echo $hoja_rutas->kg_carga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->tarifa->Visible) { // tarifa ?>
	<tr id="r_tarifa">
		<td><span id="elh_hoja_rutas_tarifa"><?php echo $hoja_rutas->tarifa->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->tarifa->CellAttributes() ?>>
<span id="el_hoja_rutas_tarifa">
<span<?php echo $hoja_rutas->tarifa->ViewAttributes() ?>>
<?php echo $hoja_rutas->tarifa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_rutas->porcentaje->Visible) { // porcentaje ?>
	<tr id="r_porcentaje">
		<td><span id="elh_hoja_rutas_porcentaje"><?php echo $hoja_rutas->porcentaje->FldCaption() ?></span></td>
		<td<?php echo $hoja_rutas->porcentaje->CellAttributes() ?>>
<span id="el_hoja_rutas_porcentaje">
<span<?php echo $hoja_rutas->porcentaje->ViewAttributes() ?>>
<?php echo $hoja_rutas->porcentaje->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($hoja_rutas->Export == "") { ?>
<?php if (!isset($hoja_rutas_view->Pager)) $hoja_rutas_view->Pager = new cNumericPager($hoja_rutas_view->StartRec, $hoja_rutas_view->DisplayRecs, $hoja_rutas_view->TotalRecs, $hoja_rutas_view->RecRange) ?>
<?php if ($hoja_rutas_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($hoja_rutas_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_view->PageUrl() ?>start=<?php echo $hoja_rutas_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_view->PageUrl() ?>start=<?php echo $hoja_rutas_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($hoja_rutas_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $hoja_rutas_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_view->PageUrl() ?>start=<?php echo $hoja_rutas_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_view->PageUrl() ?>start=<?php echo $hoja_rutas_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
<?php
	if (in_array("gastos", explode(",", $hoja_rutas->getCurrentDetailTable())) && $gastos->DetailView) {
?>
<?php if ($hoja_rutas->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("gastos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "gastosgrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fhoja_rutasview.Init();
</script>
<?php
$hoja_rutas_view->ShowPageFooter();
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
$hoja_rutas_view->Page_Terminate();
?>
