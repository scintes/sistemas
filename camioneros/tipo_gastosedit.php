<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "tipo_gastosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "gastos_mantenimientosgridcls.php" ?>
<?php include_once "gastosgridcls.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$tipo_gastos_edit = NULL; // Initialize page object first

class ctipo_gastos_edit extends ctipo_gastos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'tipo_gastos';

	// Page object name
	var $PageObjName = 'tipo_gastos_edit';

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

		// Table object (tipo_gastos)
		if (!isset($GLOBALS["tipo_gastos"]) || get_class($GLOBALS["tipo_gastos"]) == "ctipo_gastos") {
			$GLOBALS["tipo_gastos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tipo_gastos"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tipo_gastos', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("tipo_gastoslist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
		global $EW_EXPORT, $tipo_gastos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tipo_gastos);
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
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["codigo"] <> "") {
			$this->codigo->setQueryStringValue($_GET["codigo"]);
			$this->RecKey["codigo"] = $this->codigo->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("tipo_gastoslist.php"); // Return to list page
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

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("tipo_gastoslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
					else
						$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->tipo_gasto->FldIsDetailKey) {
			$this->tipo_gasto->setFormValue($objForm->GetValue("x_tipo_gasto"));
		}
		if (!$this->clase->FldIsDetailKey) {
			$this->clase->setFormValue($objForm->GetValue("x_clase"));
		}
		if (!$this->codigo->FldIsDetailKey)
			$this->codigo->setFormValue($objForm->GetValue("x_codigo"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->codigo->CurrentValue = $this->codigo->FormValue;
		$this->tipo_gasto->CurrentValue = $this->tipo_gasto->FormValue;
		$this->clase->CurrentValue = $this->clase->FormValue;
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
		$this->tipo_gasto->setDbValue($rs->fields('tipo_gasto'));
		$this->clase->setDbValue($rs->fields('clase'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->tipo_gasto->DbValue = $row['tipo_gasto'];
		$this->clase->DbValue = $row['clase'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// tipo_gasto
		// clase

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// tipo_gasto
			$this->tipo_gasto->ViewValue = $this->tipo_gasto->CurrentValue;
			$this->tipo_gasto->ViewCustomAttributes = "";

			// clase
			if (strval($this->clase->CurrentValue) <> "") {
				switch ($this->clase->CurrentValue) {
					case $this->clase->FldTagValue(1):
						$this->clase->ViewValue = $this->clase->FldTagCaption(1) <> "" ? $this->clase->FldTagCaption(1) : $this->clase->CurrentValue;
						break;
					case $this->clase->FldTagValue(2):
						$this->clase->ViewValue = $this->clase->FldTagCaption(2) <> "" ? $this->clase->FldTagCaption(2) : $this->clase->CurrentValue;
						break;
					default:
						$this->clase->ViewValue = $this->clase->CurrentValue;
				}
			} else {
				$this->clase->ViewValue = NULL;
			}
			$this->clase->ViewValue = strtoupper($this->clase->ViewValue);
			$this->clase->ViewCustomAttributes = "";

			// tipo_gasto
			$this->tipo_gasto->LinkCustomAttributes = "";
			$this->tipo_gasto->HrefValue = "";
			$this->tipo_gasto->TooltipValue = "";

			// clase
			$this->clase->LinkCustomAttributes = "";
			$this->clase->HrefValue = "";
			$this->clase->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// tipo_gasto
			$this->tipo_gasto->EditAttrs["class"] = "form-control";
			$this->tipo_gasto->EditCustomAttributes = "";
			$this->tipo_gasto->EditValue = ew_HtmlEncode($this->tipo_gasto->CurrentValue);
			$this->tipo_gasto->PlaceHolder = ew_RemoveHtml($this->tipo_gasto->FldCaption());

			// clase
			$this->clase->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->clase->FldTagValue(1), $this->clase->FldTagCaption(1) <> "" ? $this->clase->FldTagCaption(1) : $this->clase->FldTagValue(1));
			$arwrk[] = array($this->clase->FldTagValue(2), $this->clase->FldTagCaption(2) <> "" ? $this->clase->FldTagCaption(2) : $this->clase->FldTagValue(2));
			$this->clase->EditValue = $arwrk;

			// Edit refer script
			// tipo_gasto

			$this->tipo_gasto->HrefValue = "";

			// clase
			$this->clase->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("gastos_mantenimientos", $DetailTblVar) && $GLOBALS["gastos_mantenimientos"]->DetailEdit) {
			if (!isset($GLOBALS["gastos_mantenimientos_grid"])) $GLOBALS["gastos_mantenimientos_grid"] = new cgastos_mantenimientos_grid(); // get detail page object
			$GLOBALS["gastos_mantenimientos_grid"]->ValidateGridForm();
		}
		if (in_array("gastos", $DetailTblVar) && $GLOBALS["gastos"]->DetailEdit) {
			if (!isset($GLOBALS["gastos_grid"])) $GLOBALS["gastos_grid"] = new cgastos_grid(); // get detail page object
			$GLOBALS["gastos_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// tipo_gasto
			$this->tipo_gasto->SetDbValueDef($rsnew, $this->tipo_gasto->CurrentValue, NULL, $this->tipo_gasto->ReadOnly);

			// clase
			$this->clase->SetDbValueDef($rsnew, $this->clase->CurrentValue, NULL, $this->clase->ReadOnly);

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

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("gastos_mantenimientos", $DetailTblVar) && $GLOBALS["gastos_mantenimientos"]->DetailEdit) {
						if (!isset($GLOBALS["gastos_mantenimientos_grid"])) $GLOBALS["gastos_mantenimientos_grid"] = new cgastos_mantenimientos_grid(); // Get detail page object
						$EditRow = $GLOBALS["gastos_mantenimientos_grid"]->GridUpdate();
					}
					if (in_array("gastos", $DetailTblVar) && $GLOBALS["gastos"]->DetailEdit) {
						if (!isset($GLOBALS["gastos_grid"])) $GLOBALS["gastos_grid"] = new cgastos_grid(); // Get detail page object
						$EditRow = $GLOBALS["gastos_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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
				if ($GLOBALS["gastos_mantenimientos_grid"]->DetailEdit) {
					$GLOBALS["gastos_mantenimientos_grid"]->CurrentMode = "edit";
					$GLOBALS["gastos_mantenimientos_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["gastos_mantenimientos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["gastos_mantenimientos_grid"]->setStartRecordNumber(1);
					$GLOBALS["gastos_mantenimientos_grid"]->id_tipo_gasto->FldIsDetailKey = TRUE;
					$GLOBALS["gastos_mantenimientos_grid"]->id_tipo_gasto->CurrentValue = $this->codigo->CurrentValue;
					$GLOBALS["gastos_mantenimientos_grid"]->id_tipo_gasto->setSessionValue($GLOBALS["gastos_mantenimientos_grid"]->id_tipo_gasto->CurrentValue);
				}
			}
			if (in_array("gastos", $DetailTblVar)) {
				if (!isset($GLOBALS["gastos_grid"]))
					$GLOBALS["gastos_grid"] = new cgastos_grid;
				if ($GLOBALS["gastos_grid"]->DetailEdit) {
					$GLOBALS["gastos_grid"]->CurrentMode = "edit";
					$GLOBALS["gastos_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["gastos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["gastos_grid"]->setStartRecordNumber(1);
					$GLOBALS["gastos_grid"]->id_tipo_gasto->FldIsDetailKey = TRUE;
					$GLOBALS["gastos_grid"]->id_tipo_gasto->CurrentValue = $this->codigo->CurrentValue;
					$GLOBALS["gastos_grid"]->id_tipo_gasto->setSessionValue($GLOBALS["gastos_grid"]->id_tipo_gasto->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "tipo_gastoslist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($tipo_gastos_edit)) $tipo_gastos_edit = new ctipo_gastos_edit();

// Page init
$tipo_gastos_edit->Page_Init();

// Page main
$tipo_gastos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tipo_gastos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tipo_gastos_edit = new ew_Page("tipo_gastos_edit");
tipo_gastos_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = tipo_gastos_edit.PageID; // For backward compatibility

// Form object
var ftipo_gastosedit = new ew_Form("ftipo_gastosedit");

// Validate form
ftipo_gastosedit.Validate = function() {
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

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ftipo_gastosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftipo_gastosedit.ValidateRequired = true;
<?php } else { ?>
ftipo_gastosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $tipo_gastos_edit->ShowPageHeader(); ?>
<?php
$tipo_gastos_edit->ShowMessage();
?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($tipo_gastos_edit->Pager)) $tipo_gastos_edit->Pager = new cNumericPager($tipo_gastos_edit->StartRec, $tipo_gastos_edit->DisplayRecs, $tipo_gastos_edit->TotalRecs, $tipo_gastos_edit->RecRange) ?>
<?php if ($tipo_gastos_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($tipo_gastos_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $tipo_gastos_edit->PageUrl() ?>start=<?php echo $tipo_gastos_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($tipo_gastos_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $tipo_gastos_edit->PageUrl() ?>start=<?php echo $tipo_gastos_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($tipo_gastos_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $tipo_gastos_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($tipo_gastos_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $tipo_gastos_edit->PageUrl() ?>start=<?php echo $tipo_gastos_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($tipo_gastos_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $tipo_gastos_edit->PageUrl() ?>start=<?php echo $tipo_gastos_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<form name="ftipo_gastosedit" id="ftipo_gastosedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tipo_gastos_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tipo_gastos_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tipo_gastos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($tipo_gastos->tipo_gasto->Visible) { // tipo_gasto ?>
	<div id="r_tipo_gasto" class="form-group">
		<label id="elh_tipo_gastos_tipo_gasto" for="x_tipo_gasto" class="col-sm-2 control-label ewLabel"><?php echo $tipo_gastos->tipo_gasto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tipo_gastos->tipo_gasto->CellAttributes() ?>>
<span id="el_tipo_gastos_tipo_gasto">
<input type="text" data-field="x_tipo_gasto" name="x_tipo_gasto" id="x_tipo_gasto" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($tipo_gastos->tipo_gasto->PlaceHolder) ?>" value="<?php echo $tipo_gastos->tipo_gasto->EditValue ?>"<?php echo $tipo_gastos->tipo_gasto->EditAttributes() ?>>
</span>
<?php echo $tipo_gastos->tipo_gasto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tipo_gastos->clase->Visible) { // clase ?>
	<div id="r_clase" class="form-group">
		<label id="elh_tipo_gastos_clase" class="col-sm-2 control-label ewLabel"><?php echo $tipo_gastos->clase->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tipo_gastos->clase->CellAttributes() ?>>
<span id="el_tipo_gastos_clase">
<div id="tp_x_clase" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_clase" id="x_clase" value="{value}"<?php echo $tipo_gastos->clase->EditAttributes() ?>></div>
<div id="dsl_x_clase" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tipo_gastos->clase->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tipo_gastos->clase->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_clase" name="x_clase" id="x_clase_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tipo_gastos->clase->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $tipo_gastos->clase->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_codigo" name="x_codigo" id="x_codigo" value="<?php echo ew_HtmlEncode($tipo_gastos->codigo->CurrentValue) ?>">
<?php
	if (in_array("gastos_mantenimientos", explode(",", $tipo_gastos->getCurrentDetailTable())) && $gastos_mantenimientos->DetailEdit) {
?>
<?php if ($tipo_gastos->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("gastos_mantenimientos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "gastos_mantenimientosgrid.php" ?>
<?php } ?>
<?php
	if (in_array("gastos", explode(",", $tipo_gastos->getCurrentDetailTable())) && $gastos->DetailEdit) {
?>
<?php if ($tipo_gastos->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("gastos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "gastosgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
<?php if (!isset($tipo_gastos_edit->Pager)) $tipo_gastos_edit->Pager = new cNumericPager($tipo_gastos_edit->StartRec, $tipo_gastos_edit->DisplayRecs, $tipo_gastos_edit->TotalRecs, $tipo_gastos_edit->RecRange) ?>
<?php if ($tipo_gastos_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($tipo_gastos_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $tipo_gastos_edit->PageUrl() ?>start=<?php echo $tipo_gastos_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($tipo_gastos_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $tipo_gastos_edit->PageUrl() ?>start=<?php echo $tipo_gastos_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($tipo_gastos_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $tipo_gastos_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($tipo_gastos_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $tipo_gastos_edit->PageUrl() ?>start=<?php echo $tipo_gastos_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($tipo_gastos_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $tipo_gastos_edit->PageUrl() ?>start=<?php echo $tipo_gastos_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<script type="text/javascript">
ftipo_gastosedit.Init();
</script>
<?php
$tipo_gastos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tipo_gastos_edit->Page_Terminate();
?>
