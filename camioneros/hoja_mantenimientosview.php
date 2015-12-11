<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "hoja_mantenimientosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "gastos_mantenimientosgridcls.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$hoja_mantenimientos_view = NULL; // Initialize page object first

class choja_mantenimientos_view extends choja_mantenimientos {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'hoja_mantenimientos';

	// Page object name
	var $PageObjName = 'hoja_mantenimientos_view';

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

		// Table object (hoja_mantenimientos)
		if (!isset($GLOBALS["hoja_mantenimientos"]) || get_class($GLOBALS["hoja_mantenimientos"]) == "choja_mantenimientos") {
			$GLOBALS["hoja_mantenimientos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["hoja_mantenimientos"];
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
			define("EW_TABLE_NAME", 'hoja_mantenimientos', TRUE);

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
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("hoja_mantenimientoslist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("hoja_mantenimientoslist.php"));
		}

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

			// Process auto fill for detail table 'gastos_mantenimientos'
			if (@$_POST["grid"] == "fgastos_mantenimientosgrid") {
				if (!isset($GLOBALS["gastos_mantenimientos_grid"])) $GLOBALS["gastos_mantenimientos_grid"] = new cgastos_mantenimientos_grid;
				$GLOBALS["gastos_mantenimientos_grid"]->Page_Init();
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
		global $EW_EXPORT, $hoja_mantenimientos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($hoja_mantenimientos);
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
	var $gastos_mantenimientos_Count;
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
						$this->Page_Terminate("hoja_mantenimientoslist.php"); // Return to list page
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
						$sReturnUrl = "hoja_mantenimientoslist.php"; // No matching record, return to list
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
			$sReturnUrl = "hoja_mantenimientoslist.php"; // Not page request, return to list
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
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit()&& $this->ShowOptionLink('edit'));

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete() && $this->ShowOptionLink('delete'));
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_gastos_mantenimientos"
		$item = &$option->Add("detail_gastos_mantenimientos");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("gastos_mantenimientos", "TblCaption");
		$body .= str_replace("%c", $this->gastos_mantenimientos_Count, $Language->Phrase("DetailCount"));
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("gastos_mantenimientoslist.php?" . EW_TABLE_SHOW_MASTER . "=hoja_mantenimientos&fk_codigo=" . urlencode(strval($this->codigo->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["gastos_mantenimientos_grid"] && $GLOBALS["gastos_mantenimientos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'gastos_mantenimientos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=gastos_mantenimientos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "gastos_mantenimientos";
		}
		if ($GLOBALS["gastos_mantenimientos_grid"] && $GLOBALS["gastos_mantenimientos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'gastos_mantenimientos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=gastos_mantenimientos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "gastos_mantenimientos";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'gastos_mantenimientos');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "gastos_mantenimientos";
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
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->id_vehiculo->setDbValue($rs->fields('id_vehiculo'));
		if (array_key_exists('EV__id_vehiculo', $rs->fields)) {
			$this->id_vehiculo->VirtualValue = $rs->fields('EV__id_vehiculo'); // Set up virtual field value
		} else {
			$this->id_vehiculo->VirtualValue = ""; // Clear value
		}
		$this->id_taller->setDbValue($rs->fields('id_taller'));
		if (array_key_exists('EV__id_taller', $rs->fields)) {
			$this->id_taller->VirtualValue = $rs->fields('EV__id_taller'); // Set up virtual field value
		} else {
			$this->id_taller->VirtualValue = ""; // Clear value
		}
		$this->id_tipo_mantenimiento->setDbValue($rs->fields('id_tipo_mantenimiento'));
		if (array_key_exists('EV__id_tipo_mantenimiento', $rs->fields)) {
			$this->id_tipo_mantenimiento->VirtualValue = $rs->fields('EV__id_tipo_mantenimiento'); // Set up virtual field value
		} else {
			$this->id_tipo_mantenimiento->VirtualValue = ""; // Clear value
		}
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		if (!isset($GLOBALS["gastos_mantenimientos_grid"])) $GLOBALS["gastos_mantenimientos_grid"] = new cgastos_mantenimientos_grid;
		$sDetailFilter = $GLOBALS["gastos_mantenimientos"]->SqlDetailFilter_hoja_mantenimientos();
		$sDetailFilter = str_replace("@id_hoja_mantenimeinto@", ew_AdjustSql($this->codigo->DbValue), $sDetailFilter);
		$GLOBALS["gastos_mantenimientos"]->setCurrentMasterTable("hoja_mantenimientos");
		$sDetailFilter = $GLOBALS["gastos_mantenimientos"]->ApplyUserIDFilters($sDetailFilter);
		$this->gastos_mantenimientos_Count = $GLOBALS["gastos_mantenimientos"]->LoadRecordCount($sDetailFilter);
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->fecha_ini->DbValue = $row['fecha_ini'];
		$this->fecha_fin->DbValue = $row['fecha_fin'];
		$this->id_vehiculo->DbValue = $row['id_vehiculo'];
		$this->id_taller->DbValue = $row['id_taller'];
		$this->id_tipo_mantenimiento->DbValue = $row['id_tipo_mantenimiento'];
		$this->id_usuario->DbValue = $row['id_usuario'];
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// fecha_ini
		// fecha_fin
		// id_vehiculo
		// id_taller
		// id_tipo_mantenimiento
		// id_usuario

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// fecha_ini
			$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
			$this->fecha_ini->ViewCustomAttributes = "";

			// fecha_fin
			$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
			$this->fecha_fin->ViewCustomAttributes = "";

			// id_vehiculo
			if ($this->id_vehiculo->VirtualValue <> "") {
				$this->id_vehiculo->ViewValue = $this->id_vehiculo->VirtualValue;
			} else {
			if (strval($this->id_vehiculo->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_vehiculo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_vehiculo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Patente` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_vehiculo->ViewValue = $rswrk->fields('DispFld');
					$this->id_vehiculo->ViewValue .= ew_ValueSeparator(1,$this->id_vehiculo) . $rswrk->fields('Disp2Fld');
					$this->id_vehiculo->ViewValue .= ew_ValueSeparator(2,$this->id_vehiculo) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_vehiculo->ViewValue = $this->id_vehiculo->CurrentValue;
				}
			} else {
				$this->id_vehiculo->ViewValue = NULL;
			}
			}
			$this->id_vehiculo->ViewCustomAttributes = "";

			// id_taller
			if ($this->id_taller->VirtualValue <> "") {
				$this->id_taller->ViewValue = $this->id_taller->VirtualValue;
			} else {
			if (strval($this->id_taller->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_taller->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `talleres`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_taller, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `taller` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_taller->ViewValue = $rswrk->fields('DispFld');
					$this->id_taller->ViewValue .= ew_ValueSeparator(1,$this->id_taller) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_taller->ViewValue = $this->id_taller->CurrentValue;
				}
			} else {
				$this->id_taller->ViewValue = NULL;
			}
			}
			$this->id_taller->ViewCustomAttributes = "";

			// id_tipo_mantenimiento
			if ($this->id_tipo_mantenimiento->VirtualValue <> "") {
				$this->id_tipo_mantenimiento->ViewValue = $this->id_tipo_mantenimiento->VirtualValue;
			} else {
			if (strval($this->id_tipo_mantenimiento->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_mantenimiento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_mantenimientos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_mantenimiento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_mantenimiento` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_mantenimiento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_tipo_mantenimiento->ViewValue = $this->id_tipo_mantenimiento->CurrentValue;
				}
			} else {
				$this->id_tipo_mantenimiento->ViewValue = NULL;
			}
			}
			$this->id_tipo_mantenimiento->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// fecha_ini
			$this->fecha_ini->LinkCustomAttributes = "";
			$this->fecha_ini->HrefValue = "";
			$this->fecha_ini->TooltipValue = "";

			// fecha_fin
			$this->fecha_fin->LinkCustomAttributes = "";
			$this->fecha_fin->HrefValue = "";
			$this->fecha_fin->TooltipValue = "";

			// id_vehiculo
			$this->id_vehiculo->LinkCustomAttributes = "";
			$this->id_vehiculo->HrefValue = "";
			$this->id_vehiculo->TooltipValue = "";

			// id_taller
			$this->id_taller->LinkCustomAttributes = "";
			$this->id_taller->HrefValue = "";
			$this->id_taller->TooltipValue = "";

			// id_tipo_mantenimiento
			$this->id_tipo_mantenimiento->LinkCustomAttributes = "";
			$this->id_tipo_mantenimiento->HrefValue = "";
			$this->id_tipo_mantenimiento->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_hoja_mantenimientos\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_hoja_mantenimientos',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fhoja_mantenimientosview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export detail records (gastos_mantenimientos)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("gastos_mantenimientos", explode(",", $this->getCurrentDetailTable()))) {
			global $gastos_mantenimientos;
			if (!isset($gastos_mantenimientos)) $gastos_mantenimientos = new cgastos_mantenimientos;
			$rsdetail = $gastos_mantenimientos->LoadRs($gastos_mantenimientos->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$gastos_mantenimientos->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
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

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->id_usuario->CurrentValue);
		return TRUE;
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
			if (in_array("gastos_mantenimientos", $DetailTblVar)) {
				if (!isset($GLOBALS["gastos_mantenimientos_grid"]))
					$GLOBALS["gastos_mantenimientos_grid"] = new cgastos_mantenimientos_grid;
				if ($GLOBALS["gastos_mantenimientos_grid"]->DetailView) {
					$GLOBALS["gastos_mantenimientos_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["gastos_mantenimientos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["gastos_mantenimientos_grid"]->setStartRecordNumber(1);
					$GLOBALS["gastos_mantenimientos_grid"]->id_hoja_mantenimeinto->FldIsDetailKey = TRUE;
					$GLOBALS["gastos_mantenimientos_grid"]->id_hoja_mantenimeinto->CurrentValue = $this->codigo->CurrentValue;
					$GLOBALS["gastos_mantenimientos_grid"]->id_hoja_mantenimeinto->setSessionValue($GLOBALS["gastos_mantenimientos_grid"]->id_hoja_mantenimeinto->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "hoja_mantenimientoslist.php", "", $this->TableVar, TRUE);
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
if (!isset($hoja_mantenimientos_view)) $hoja_mantenimientos_view = new choja_mantenimientos_view();

// Page init
$hoja_mantenimientos_view->Page_Init();

// Page main
$hoja_mantenimientos_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hoja_mantenimientos_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($hoja_mantenimientos->Export == "") { ?>
<script type="text/javascript">

// Page object
var hoja_mantenimientos_view = new ew_Page("hoja_mantenimientos_view");
hoja_mantenimientos_view.PageID = "view"; // Page ID
var EW_PAGE_ID = hoja_mantenimientos_view.PageID; // For backward compatibility

// Form object
var fhoja_mantenimientosview = new ew_Form("fhoja_mantenimientosview");

// Form_CustomValidate event
fhoja_mantenimientosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoja_mantenimientosview.ValidateRequired = true;
<?php } else { ?>
fhoja_mantenimientosview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhoja_mantenimientosview.Lists["x_id_vehiculo"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Patente","x_modelo","x_nombre",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_mantenimientosview.Lists["x_id_taller"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_taller","x_tel","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_mantenimientosview.Lists["x_id_tipo_mantenimiento"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_mantenimiento","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($hoja_mantenimientos->Export == "") { ?>
<div class="ewToolbar">
<?php if ($hoja_mantenimientos->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $hoja_mantenimientos_view->ExportOptions->Render("body") ?>
<?php
	foreach ($hoja_mantenimientos_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if ($hoja_mantenimientos->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $hoja_mantenimientos_view->ShowPageHeader(); ?>
<?php
$hoja_mantenimientos_view->ShowMessage();
?>
<?php if ($hoja_mantenimientos->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($hoja_mantenimientos_view->Pager)) $hoja_mantenimientos_view->Pager = new cNumericPager($hoja_mantenimientos_view->StartRec, $hoja_mantenimientos_view->DisplayRecs, $hoja_mantenimientos_view->TotalRecs, $hoja_mantenimientos_view->RecRange) ?>
<?php if ($hoja_mantenimientos_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($hoja_mantenimientos_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_mantenimientos_view->PageUrl() ?>start=<?php echo $hoja_mantenimientos_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($hoja_mantenimientos_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_mantenimientos_view->PageUrl() ?>start=<?php echo $hoja_mantenimientos_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($hoja_mantenimientos_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $hoja_mantenimientos_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($hoja_mantenimientos_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_mantenimientos_view->PageUrl() ?>start=<?php echo $hoja_mantenimientos_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($hoja_mantenimientos_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_mantenimientos_view->PageUrl() ?>start=<?php echo $hoja_mantenimientos_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fhoja_mantenimientosview" id="fhoja_mantenimientosview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($hoja_mantenimientos_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $hoja_mantenimientos_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="hoja_mantenimientos">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($hoja_mantenimientos->codigo->Visible) { // codigo ?>
	<tr id="r_codigo">
		<td><span id="elh_hoja_mantenimientos_codigo"><?php echo $hoja_mantenimientos->codigo->FldCaption() ?></span></td>
		<td<?php echo $hoja_mantenimientos->codigo->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_codigo">
<span<?php echo $hoja_mantenimientos->codigo->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->codigo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_mantenimientos->fecha_ini->Visible) { // fecha_ini ?>
	<tr id="r_fecha_ini">
		<td><span id="elh_hoja_mantenimientos_fecha_ini"><?php echo $hoja_mantenimientos->fecha_ini->FldCaption() ?></span></td>
		<td<?php echo $hoja_mantenimientos->fecha_ini->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_fecha_ini">
<span<?php echo $hoja_mantenimientos->fecha_ini->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->fecha_ini->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_mantenimientos->fecha_fin->Visible) { // fecha_fin ?>
	<tr id="r_fecha_fin">
		<td><span id="elh_hoja_mantenimientos_fecha_fin"><?php echo $hoja_mantenimientos->fecha_fin->FldCaption() ?></span></td>
		<td<?php echo $hoja_mantenimientos->fecha_fin->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_fecha_fin">
<span<?php echo $hoja_mantenimientos->fecha_fin->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->fecha_fin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_mantenimientos->id_vehiculo->Visible) { // id_vehiculo ?>
	<tr id="r_id_vehiculo">
		<td><span id="elh_hoja_mantenimientos_id_vehiculo"><?php echo $hoja_mantenimientos->id_vehiculo->FldCaption() ?></span></td>
		<td<?php echo $hoja_mantenimientos->id_vehiculo->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_id_vehiculo">
<span<?php echo $hoja_mantenimientos->id_vehiculo->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->id_vehiculo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_mantenimientos->id_taller->Visible) { // id_taller ?>
	<tr id="r_id_taller">
		<td><span id="elh_hoja_mantenimientos_id_taller"><?php echo $hoja_mantenimientos->id_taller->FldCaption() ?></span></td>
		<td<?php echo $hoja_mantenimientos->id_taller->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_id_taller">
<span<?php echo $hoja_mantenimientos->id_taller->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->id_taller->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($hoja_mantenimientos->id_tipo_mantenimiento->Visible) { // id_tipo_mantenimiento ?>
	<tr id="r_id_tipo_mantenimiento">
		<td><span id="elh_hoja_mantenimientos_id_tipo_mantenimiento"><?php echo $hoja_mantenimientos->id_tipo_mantenimiento->FldCaption() ?></span></td>
		<td<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_id_tipo_mantenimiento">
<span<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($hoja_mantenimientos->Export == "") { ?>
<?php if (!isset($hoja_mantenimientos_view->Pager)) $hoja_mantenimientos_view->Pager = new cNumericPager($hoja_mantenimientos_view->StartRec, $hoja_mantenimientos_view->DisplayRecs, $hoja_mantenimientos_view->TotalRecs, $hoja_mantenimientos_view->RecRange) ?>
<?php if ($hoja_mantenimientos_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($hoja_mantenimientos_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_mantenimientos_view->PageUrl() ?>start=<?php echo $hoja_mantenimientos_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($hoja_mantenimientos_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_mantenimientos_view->PageUrl() ?>start=<?php echo $hoja_mantenimientos_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($hoja_mantenimientos_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $hoja_mantenimientos_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($hoja_mantenimientos_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_mantenimientos_view->PageUrl() ?>start=<?php echo $hoja_mantenimientos_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($hoja_mantenimientos_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_mantenimientos_view->PageUrl() ?>start=<?php echo $hoja_mantenimientos_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
<?php
	if (in_array("gastos_mantenimientos", explode(",", $hoja_mantenimientos->getCurrentDetailTable())) && $gastos_mantenimientos->DetailView) {
?>
<?php if ($hoja_mantenimientos->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("gastos_mantenimientos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "gastos_mantenimientosgrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fhoja_mantenimientosview.Init();
</script>
<?php
$hoja_mantenimientos_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($hoja_mantenimientos->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$hoja_mantenimientos_view->Page_Terminate();
?>
