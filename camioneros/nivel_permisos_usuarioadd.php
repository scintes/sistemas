<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "nivel_permisos_usuarioinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$nivel_permisos_usuario_add = NULL; // Initialize page object first

class cnivel_permisos_usuario_add extends cnivel_permisos_usuario {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'nivel_permisos_usuario';

	// Page object name
	var $PageObjName = 'nivel_permisos_usuario_add';

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

		// Table object (nivel_permisos_usuario)
		if (!isset($GLOBALS["nivel_permisos_usuario"]) || get_class($GLOBALS["nivel_permisos_usuario"]) == "cnivel_permisos_usuario") {
			$GLOBALS["nivel_permisos_usuario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["nivel_permisos_usuario"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'nivel_permisos_usuario', TRUE);

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
		if (!$Security->CanAdmin()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
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
		global $EW_EXPORT, $nivel_permisos_usuario;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($nivel_permisos_usuario);
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_nivel_usuario"] != "") {
				$this->id_nivel_usuario->setQueryStringValue($_GET["id_nivel_usuario"]);
				$this->setKey("id_nivel_usuario", $this->id_nivel_usuario->CurrentValue); // Set up key
			} else {
				$this->setKey("id_nivel_usuario", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["nombre_tabla"] != "") {
				$this->nombre_tabla->setQueryStringValue($_GET["nombre_tabla"]);
				$this->setKey("nombre_tabla", $this->nombre_tabla->CurrentValue); // Set up key
			} else {
				$this->setKey("nombre_tabla", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("nivel_permisos_usuariolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "nivel_permisos_usuarioview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id_nivel_usuario->CurrentValue = NULL;
		$this->id_nivel_usuario->OldValue = $this->id_nivel_usuario->CurrentValue;
		$this->nombre_tabla->CurrentValue = NULL;
		$this->nombre_tabla->OldValue = $this->nombre_tabla->CurrentValue;
		$this->permisos->CurrentValue = NULL;
		$this->permisos->OldValue = $this->permisos->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_nivel_usuario->FldIsDetailKey) {
			$this->id_nivel_usuario->setFormValue($objForm->GetValue("x_id_nivel_usuario"));
		}
		if (!$this->nombre_tabla->FldIsDetailKey) {
			$this->nombre_tabla->setFormValue($objForm->GetValue("x_nombre_tabla"));
		}
		if (!$this->permisos->FldIsDetailKey) {
			$this->permisos->setFormValue($objForm->GetValue("x_permisos"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id_nivel_usuario->CurrentValue = $this->id_nivel_usuario->FormValue;
		$this->nombre_tabla->CurrentValue = $this->nombre_tabla->FormValue;
		$this->permisos->CurrentValue = $this->permisos->FormValue;
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
		$this->id_nivel_usuario->setDbValue($rs->fields('id_nivel_usuario'));
		$this->nombre_tabla->setDbValue($rs->fields('nombre_tabla'));
		$this->permisos->setDbValue($rs->fields('permisos'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_nivel_usuario->DbValue = $row['id_nivel_usuario'];
		$this->nombre_tabla->DbValue = $row['nombre_tabla'];
		$this->permisos->DbValue = $row['permisos'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_nivel_usuario")) <> "")
			$this->id_nivel_usuario->CurrentValue = $this->getKey("id_nivel_usuario"); // id_nivel_usuario
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nombre_tabla")) <> "")
			$this->nombre_tabla->CurrentValue = $this->getKey("nombre_tabla"); // nombre_tabla
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_nivel_usuario
		// nombre_tabla
		// permisos

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_nivel_usuario
			$this->id_nivel_usuario->ViewValue = $this->id_nivel_usuario->CurrentValue;
			$this->id_nivel_usuario->ViewCustomAttributes = "";

			// nombre_tabla
			$this->nombre_tabla->ViewValue = $this->nombre_tabla->CurrentValue;
			$this->nombre_tabla->ViewCustomAttributes = "";

			// permisos
			$this->permisos->ViewValue = $this->permisos->CurrentValue;
			$this->permisos->ViewCustomAttributes = "";

			// id_nivel_usuario
			$this->id_nivel_usuario->LinkCustomAttributes = "";
			$this->id_nivel_usuario->HrefValue = "";
			$this->id_nivel_usuario->TooltipValue = "";

			// nombre_tabla
			$this->nombre_tabla->LinkCustomAttributes = "";
			$this->nombre_tabla->HrefValue = "";
			$this->nombre_tabla->TooltipValue = "";

			// permisos
			$this->permisos->LinkCustomAttributes = "";
			$this->permisos->HrefValue = "";
			$this->permisos->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_nivel_usuario
			$this->id_nivel_usuario->EditAttrs["class"] = "form-control";
			$this->id_nivel_usuario->EditCustomAttributes = "";
			$this->id_nivel_usuario->EditValue = ew_HtmlEncode($this->id_nivel_usuario->CurrentValue);
			$this->id_nivel_usuario->PlaceHolder = ew_RemoveHtml($this->id_nivel_usuario->FldCaption());

			// nombre_tabla
			$this->nombre_tabla->EditAttrs["class"] = "form-control";
			$this->nombre_tabla->EditCustomAttributes = "";
			$this->nombre_tabla->EditValue = ew_HtmlEncode($this->nombre_tabla->CurrentValue);
			$this->nombre_tabla->PlaceHolder = ew_RemoveHtml($this->nombre_tabla->FldCaption());

			// permisos
			$this->permisos->EditAttrs["class"] = "form-control";
			$this->permisos->EditCustomAttributes = "";
			$this->permisos->EditValue = ew_HtmlEncode($this->permisos->CurrentValue);
			$this->permisos->PlaceHolder = ew_RemoveHtml($this->permisos->FldCaption());

			// Edit refer script
			// id_nivel_usuario

			$this->id_nivel_usuario->HrefValue = "";

			// nombre_tabla
			$this->nombre_tabla->HrefValue = "";

			// permisos
			$this->permisos->HrefValue = "";
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
		if (!$this->id_nivel_usuario->FldIsDetailKey && !is_null($this->id_nivel_usuario->FormValue) && $this->id_nivel_usuario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_nivel_usuario->FldCaption(), $this->id_nivel_usuario->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_nivel_usuario->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_nivel_usuario->FldErrMsg());
		}
		if (!$this->nombre_tabla->FldIsDetailKey && !is_null($this->nombre_tabla->FormValue) && $this->nombre_tabla->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre_tabla->FldCaption(), $this->nombre_tabla->ReqErrMsg));
		}
		if (!$this->permisos->FldIsDetailKey && !is_null($this->permisos->FormValue) && $this->permisos->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->permisos->FldCaption(), $this->permisos->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->permisos->FormValue)) {
			ew_AddMessage($gsFormError, $this->permisos->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// id_nivel_usuario
		$this->id_nivel_usuario->SetDbValueDef($rsnew, $this->id_nivel_usuario->CurrentValue, 0, FALSE);

		// nombre_tabla
		$this->nombre_tabla->SetDbValueDef($rsnew, $this->nombre_tabla->CurrentValue, "", FALSE);

		// permisos
		$this->permisos->SetDbValueDef($rsnew, $this->permisos->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['id_nivel_usuario']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['nombre_tabla']) == "") {
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
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "nivel_permisos_usuariolist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($nivel_permisos_usuario_add)) $nivel_permisos_usuario_add = new cnivel_permisos_usuario_add();

// Page init
$nivel_permisos_usuario_add->Page_Init();

// Page main
$nivel_permisos_usuario_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nivel_permisos_usuario_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var nivel_permisos_usuario_add = new ew_Page("nivel_permisos_usuario_add");
nivel_permisos_usuario_add.PageID = "add"; // Page ID
var EW_PAGE_ID = nivel_permisos_usuario_add.PageID; // For backward compatibility

// Form object
var fnivel_permisos_usuarioadd = new ew_Form("fnivel_permisos_usuarioadd");

// Validate form
fnivel_permisos_usuarioadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_nivel_usuario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $nivel_permisos_usuario->id_nivel_usuario->FldCaption(), $nivel_permisos_usuario->id_nivel_usuario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_nivel_usuario");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($nivel_permisos_usuario->id_nivel_usuario->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nombre_tabla");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $nivel_permisos_usuario->nombre_tabla->FldCaption(), $nivel_permisos_usuario->nombre_tabla->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_permisos");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $nivel_permisos_usuario->permisos->FldCaption(), $nivel_permisos_usuario->permisos->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_permisos");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($nivel_permisos_usuario->permisos->FldErrMsg()) ?>");

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
fnivel_permisos_usuarioadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnivel_permisos_usuarioadd.ValidateRequired = true;
<?php } else { ?>
fnivel_permisos_usuarioadd.ValidateRequired = false; 
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
<?php $nivel_permisos_usuario_add->ShowPageHeader(); ?>
<?php
$nivel_permisos_usuario_add->ShowMessage();
?>
<form name="fnivel_permisos_usuarioadd" id="fnivel_permisos_usuarioadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($nivel_permisos_usuario_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $nivel_permisos_usuario_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="nivel_permisos_usuario">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($nivel_permisos_usuario->id_nivel_usuario->Visible) { // id_nivel_usuario ?>
	<div id="r_id_nivel_usuario" class="form-group">
		<label id="elh_nivel_permisos_usuario_id_nivel_usuario" for="x_id_nivel_usuario" class="col-sm-2 control-label ewLabel"><?php echo $nivel_permisos_usuario->id_nivel_usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $nivel_permisos_usuario->id_nivel_usuario->CellAttributes() ?>>
<span id="el_nivel_permisos_usuario_id_nivel_usuario">
<input type="text" data-field="x_id_nivel_usuario" name="x_id_nivel_usuario" id="x_id_nivel_usuario" size="30" placeholder="<?php echo ew_HtmlEncode($nivel_permisos_usuario->id_nivel_usuario->PlaceHolder) ?>" value="<?php echo $nivel_permisos_usuario->id_nivel_usuario->EditValue ?>"<?php echo $nivel_permisos_usuario->id_nivel_usuario->EditAttributes() ?>>
</span>
<?php echo $nivel_permisos_usuario->id_nivel_usuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($nivel_permisos_usuario->nombre_tabla->Visible) { // nombre_tabla ?>
	<div id="r_nombre_tabla" class="form-group">
		<label id="elh_nivel_permisos_usuario_nombre_tabla" for="x_nombre_tabla" class="col-sm-2 control-label ewLabel"><?php echo $nivel_permisos_usuario->nombre_tabla->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $nivel_permisos_usuario->nombre_tabla->CellAttributes() ?>>
<span id="el_nivel_permisos_usuario_nombre_tabla">
<input type="text" data-field="x_nombre_tabla" name="x_nombre_tabla" id="x_nombre_tabla" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($nivel_permisos_usuario->nombre_tabla->PlaceHolder) ?>" value="<?php echo $nivel_permisos_usuario->nombre_tabla->EditValue ?>"<?php echo $nivel_permisos_usuario->nombre_tabla->EditAttributes() ?>>
</span>
<?php echo $nivel_permisos_usuario->nombre_tabla->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($nivel_permisos_usuario->permisos->Visible) { // permisos ?>
	<div id="r_permisos" class="form-group">
		<label id="elh_nivel_permisos_usuario_permisos" for="x_permisos" class="col-sm-2 control-label ewLabel"><?php echo $nivel_permisos_usuario->permisos->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $nivel_permisos_usuario->permisos->CellAttributes() ?>>
<span id="el_nivel_permisos_usuario_permisos">
<input type="text" data-field="x_permisos" name="x_permisos" id="x_permisos" size="30" placeholder="<?php echo ew_HtmlEncode($nivel_permisos_usuario->permisos->PlaceHolder) ?>" value="<?php echo $nivel_permisos_usuario->permisos->EditValue ?>"<?php echo $nivel_permisos_usuario->permisos->EditAttributes() ?>>
</span>
<?php echo $nivel_permisos_usuario->permisos->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fnivel_permisos_usuarioadd.Init();
</script>
<?php
$nivel_permisos_usuario_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$nivel_permisos_usuario_add->Page_Terminate();
?>
