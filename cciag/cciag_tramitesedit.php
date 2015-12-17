<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "cciag_ewcfg11.php" ?>
<?php include_once "cciag_ewmysql11.php" ?>
<?php include_once "cciag_phpfn11.php" ?>
<?php include_once "cciag_tramitesinfo.php" ?>
<?php include_once "cciag_usuarioinfo.php" ?>
<?php include_once "cciag_seguimiento_tramitesgridcls.php" ?>
<?php include_once "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$tramites_edit = NULL; // Initialize page object first

class ctramites_edit extends ctramites {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'tramites';

	// Page object name
	var $PageObjName = 'tramites_edit';

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

		// Table object (tramites)
		if (!isset($GLOBALS["tramites"]) || get_class($GLOBALS["tramites"]) == "ctramites") {
			$GLOBALS["tramites"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tramites"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tramites', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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

			// Process auto fill for detail table 'seguimiento_tramites'
			if (@$_POST["grid"] == "fseguimiento_tramitesgrid") {
				if (!isset($GLOBALS["seguimiento_tramites_grid"])) $GLOBALS["seguimiento_tramites_grid"] = new cseguimiento_tramites_grid;
				$GLOBALS["seguimiento_tramites_grid"]->Page_Init();
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
		global $EW_EXPORT, $tramites;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tramites);
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

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["codigo"] <> "") {
			$this->codigo->setQueryStringValue($_GET["codigo"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->codigo->CurrentValue == "")
			$this->Page_Terminate("cciag_tramiteslist.php"); // Invalid key, return to list

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
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("cciag_tramiteslist.php"); // No matching record, return to list
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
		$this->archivo->Upload->Index = $objForm->Index;
		$this->archivo->Upload->UploadFile();
		$this->archivo->CurrentValue = $this->archivo->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->codigo->FldIsDetailKey)
			$this->codigo->setFormValue($objForm->GetValue("x_codigo"));
		if (!$this->Titulo->FldIsDetailKey) {
			$this->Titulo->setFormValue($objForm->GetValue("x_Titulo"));
		}
		if (!$this->Descripcion->FldIsDetailKey) {
			$this->Descripcion->setFormValue($objForm->GetValue("x_Descripcion"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->codigo->CurrentValue = $this->codigo->FormValue;
		$this->Titulo->CurrentValue = $this->Titulo->FormValue;
		$this->Descripcion->CurrentValue = $this->Descripcion->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
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
		$this->Titulo->setDbValue($rs->fields('Titulo'));
		$this->Descripcion->setDbValue($rs->fields('Descripcion'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		$this->archivo->Upload->DbValue = $rs->fields('archivo');
		$this->archivo->CurrentValue = $this->archivo->Upload->DbValue;
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->Titulo->DbValue = $row['Titulo'];
		$this->Descripcion->DbValue = $row['Descripcion'];
		$this->fecha->DbValue = $row['fecha'];
		$this->id_usuario->DbValue = $row['id_usuario'];
		$this->archivo->Upload->DbValue = $row['archivo'];
		$this->estado->DbValue = $row['estado'];
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
		// Titulo
		// Descripcion
		// fecha
		// id_usuario
		// archivo
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// Titulo
			$this->Titulo->ViewValue = $this->Titulo->CurrentValue;
			$this->Titulo->ViewCustomAttributes = "";

			// Descripcion
			$this->Descripcion->ViewValue = $this->Descripcion->CurrentValue;
			$this->Descripcion->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// archivo
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->ViewValue = $this->archivo->Upload->DbValue;
			} else {
				$this->archivo->ViewValue = "";
			}
			$this->archivo->ViewCustomAttributes = "";

			// estado
			if (strval($this->estado->CurrentValue) <> "") {
				switch ($this->estado->CurrentValue) {
					case $this->estado->FldTagValue(1):
						$this->estado->ViewValue = $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->CurrentValue;
						break;
					case $this->estado->FldTagValue(2):
						$this->estado->ViewValue = $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->CurrentValue;
						break;
					case $this->estado->FldTagValue(3):
						$this->estado->ViewValue = $this->estado->FldTagCaption(3) <> "" ? $this->estado->FldTagCaption(3) : $this->estado->CurrentValue;
						break;
					default:
						$this->estado->ViewValue = $this->estado->CurrentValue;
				}
			} else {
				$this->estado->ViewValue = NULL;
			}
			$this->estado->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// Titulo
			$this->Titulo->LinkCustomAttributes = "";
			$this->Titulo->HrefValue = "";
			$this->Titulo->TooltipValue = "";

			// Descripcion
			$this->Descripcion->LinkCustomAttributes = "";
			$this->Descripcion->HrefValue = "";
			$this->Descripcion->TooltipValue = "";

			// archivo
			$this->archivo->LinkCustomAttributes = "";
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->HrefValue = "%u"; // Add prefix/suffix
				$this->archivo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->archivo->HrefValue = ew_ConvertFullUrl($this->archivo->HrefValue);
			} else {
				$this->archivo->HrefValue = "";
			}
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;
			$this->archivo->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// codigo
			$this->codigo->EditAttrs["class"] = "form-control";
			$this->codigo->EditCustomAttributes = "";
			$this->codigo->EditValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// Titulo
			$this->Titulo->EditAttrs["class"] = "form-control";
			$this->Titulo->EditCustomAttributes = "";
			$this->Titulo->EditValue = ew_HtmlEncode($this->Titulo->CurrentValue);
			$this->Titulo->PlaceHolder = ew_RemoveHtml($this->Titulo->FldCaption());

			// Descripcion
			$this->Descripcion->EditAttrs["class"] = "form-control";
			$this->Descripcion->EditCustomAttributes = "";
			$this->Descripcion->EditValue = ew_HtmlEncode($this->Descripcion->CurrentValue);
			$this->Descripcion->PlaceHolder = ew_RemoveHtml($this->Descripcion->FldCaption());

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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->archivo);

			// estado
			$this->estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
			$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
			$arwrk[] = array($this->estado->FldTagValue(3), $this->estado->FldTagCaption(3) <> "" ? $this->estado->FldTagCaption(3) : $this->estado->FldTagValue(3));
			$this->estado->EditValue = $arwrk;

			// Edit refer script
			// codigo

			$this->codigo->HrefValue = "";

			// Titulo
			$this->Titulo->HrefValue = "";

			// Descripcion
			$this->Descripcion->HrefValue = "";

			// archivo
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->HrefValue = "%u"; // Add prefix/suffix
				$this->archivo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->archivo->HrefValue = ew_ConvertFullUrl($this->archivo->HrefValue);
			} else {
				$this->archivo->HrefValue = "";
			}
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;

			// estado
			$this->estado->HrefValue = "";
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
		if (!$this->Titulo->FldIsDetailKey && !is_null($this->Titulo->FormValue) && $this->Titulo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Titulo->FldCaption(), $this->Titulo->ReqErrMsg));
		}
		if ($this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("seguimiento_tramites", $DetailTblVar) && $GLOBALS["seguimiento_tramites"]->DetailEdit) {
			if (!isset($GLOBALS["seguimiento_tramites_grid"])) $GLOBALS["seguimiento_tramites_grid"] = new cseguimiento_tramites_grid(); // get detail page object
			$GLOBALS["seguimiento_tramites_grid"]->ValidateGridForm();
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

			// Titulo
			$this->Titulo->SetDbValueDef($rsnew, $this->Titulo->CurrentValue, NULL, $this->Titulo->ReadOnly);

			// Descripcion
			$this->Descripcion->SetDbValueDef($rsnew, $this->Descripcion->CurrentValue, NULL, $this->Descripcion->ReadOnly);

			// archivo
			if (!($this->archivo->ReadOnly) && !$this->archivo->Upload->KeepFile) {
				$this->archivo->Upload->DbValue = $rsold['archivo']; // Get original value
				if ($this->archivo->Upload->FileName == "") {
					$rsnew['archivo'] = NULL;
				} else {
					$rsnew['archivo'] = $this->archivo->Upload->FileName;
				}
			}

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, NULL, $this->estado->ReadOnly);
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

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("seguimiento_tramites", $DetailTblVar) && $GLOBALS["seguimiento_tramites"]->DetailEdit) {
						if (!isset($GLOBALS["seguimiento_tramites_grid"])) $GLOBALS["seguimiento_tramites_grid"] = new cseguimiento_tramites_grid(); // Get detail page object
						$EditRow = $GLOBALS["seguimiento_tramites_grid"]->GridUpdate();
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

		// archivo
		ew_CleanUploadTempPath($this->archivo, $this->archivo->Upload->Index);
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
			if (in_array("seguimiento_tramites", $DetailTblVar)) {
				if (!isset($GLOBALS["seguimiento_tramites_grid"]))
					$GLOBALS["seguimiento_tramites_grid"] = new cseguimiento_tramites_grid;
				if ($GLOBALS["seguimiento_tramites_grid"]->DetailEdit) {
					$GLOBALS["seguimiento_tramites_grid"]->CurrentMode = "edit";
					$GLOBALS["seguimiento_tramites_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["seguimiento_tramites_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["seguimiento_tramites_grid"]->setStartRecordNumber(1);
					$GLOBALS["seguimiento_tramites_grid"]->id_tramite->FldIsDetailKey = TRUE;
					$GLOBALS["seguimiento_tramites_grid"]->id_tramite->CurrentValue = $this->codigo->CurrentValue;
					$GLOBALS["seguimiento_tramites_grid"]->id_tramite->setSessionValue($GLOBALS["seguimiento_tramites_grid"]->id_tramite->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "cciag_tramiteslist.php", "", $this->TableVar, TRUE);
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
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tramites_edit)) $tramites_edit = new ctramites_edit();

// Page init
$tramites_edit->Page_Init();

// Page main
$tramites_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tramites_edit->Page_Render();
?>
<?php include_once "cciag_header.php" ?>
<script type="text/javascript">

// Page object
var tramites_edit = new ew_Page("tramites_edit");
tramites_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = tramites_edit.PageID; // For backward compatibility

// Form object
var ftramitesedit = new ew_Form("ftramitesedit");

// Validate form
ftramitesedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Titulo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tramites->Titulo->FldCaption(), $tramites->Titulo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tramites->estado->FldCaption(), $tramites->estado->ReqErrMsg)) ?>");

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
ftramitesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftramitesedit.ValidateRequired = true;
<?php } else { ?>
ftramitesedit.ValidateRequired = false; 
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
<?php $tramites_edit->ShowPageHeader(); ?>
<?php
$tramites_edit->ShowMessage();
?>
<form name="ftramitesedit" id="ftramitesedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tramites_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tramites_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tramites">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($tramites->codigo->Visible) { // codigo ?>
	<div id="r_codigo" class="form-group">
		<label id="elh_tramites_codigo" class="col-sm-2 control-label ewLabel"><?php echo $tramites->codigo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tramites->codigo->CellAttributes() ?>>
<span id="el_tramites_codigo">
<span<?php echo $tramites->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tramites->codigo->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo" name="x_codigo" id="x_codigo" value="<?php echo ew_HtmlEncode($tramites->codigo->CurrentValue) ?>">
<?php echo $tramites->codigo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tramites->Titulo->Visible) { // Titulo ?>
	<div id="r_Titulo" class="form-group">
		<label id="elh_tramites_Titulo" for="x_Titulo" class="col-sm-2 control-label ewLabel"><?php echo $tramites->Titulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tramites->Titulo->CellAttributes() ?>>
<span id="el_tramites_Titulo">
<input type="text" data-field="x_Titulo" name="x_Titulo" id="x_Titulo" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($tramites->Titulo->PlaceHolder) ?>" value="<?php echo $tramites->Titulo->EditValue ?>"<?php echo $tramites->Titulo->EditAttributes() ?>>
</span>
<?php echo $tramites->Titulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tramites->Descripcion->Visible) { // Descripcion ?>
	<div id="r_Descripcion" class="form-group">
		<label id="elh_tramites_Descripcion" class="col-sm-2 control-label ewLabel"><?php echo $tramites->Descripcion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tramites->Descripcion->CellAttributes() ?>>
<span id="el_tramites_Descripcion">
<?php ew_AppendClass($tramites->Descripcion->EditAttrs["class"], "editor"); ?>
<textarea data-field="x_Descripcion" name="x_Descripcion" id="x_Descripcion" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($tramites->Descripcion->PlaceHolder) ?>"<?php echo $tramites->Descripcion->EditAttributes() ?>><?php echo $tramites->Descripcion->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("ftramitesedit", "x_Descripcion", 35, 4, <?php echo ($tramites->Descripcion->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $tramites->Descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tramites->archivo->Visible) { // archivo ?>
	<div id="r_archivo" class="form-group">
		<label id="elh_tramites_archivo" class="col-sm-2 control-label ewLabel"><?php echo $tramites->archivo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tramites->archivo->CellAttributes() ?>>
<span id="el_tramites_archivo">
<div id="fd_x_archivo">
<span title="<?php echo $tramites->archivo->FldTitle() ? $tramites->archivo->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($tramites->archivo->ReadOnly || $tramites->archivo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_archivo" name="x_archivo" id="x_archivo" multiple="multiple">
</span>
<input type="hidden" name="fn_x_archivo" id= "fn_x_archivo" value="<?php echo $tramites->archivo->Upload->FileName ?>">
<?php if (@$_POST["fa_x_archivo"] == "0") { ?>
<input type="hidden" name="fa_x_archivo" id= "fa_x_archivo" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_archivo" id= "fa_x_archivo" value="1">
<?php } ?>
<input type="hidden" name="fs_x_archivo" id= "fs_x_archivo" value="255">
<input type="hidden" name="fx_x_archivo" id= "fx_x_archivo" value="<?php echo $tramites->archivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_archivo" id= "fm_x_archivo" value="<?php echo $tramites->archivo->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_archivo" id= "fc_x_archivo" value="<?php echo $tramites->archivo->UploadMaxFileCount ?>">
</div>
<table id="ft_x_archivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $tramites->archivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tramites->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_tramites_estado" class="col-sm-2 control-label ewLabel"><?php echo $tramites->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tramites->estado->CellAttributes() ?>>
<span id="el_tramites_estado">
<div id="tp_x_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_estado" id="x_estado" value="{value}"<?php echo $tramites->estado->EditAttributes() ?>></div>
<div id="dsl_x_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tramites->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tramites->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x_estado" id="x_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tramites->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $tramites->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("seguimiento_tramites", explode(",", $tramites->getCurrentDetailTable())) && $seguimiento_tramites->DetailEdit) {
?>
<?php if ($tramites->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("seguimiento_tramites", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "cciag_seguimiento_tramitesgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
ftramitesedit.Init();
</script>
<?php
$tramites_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "cciag_footer.php" ?>
<?php
$tramites_edit->Page_Terminate();
?>
