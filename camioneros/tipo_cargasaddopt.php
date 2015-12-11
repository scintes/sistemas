<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "tipo_cargasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$tipo_cargas_addopt = NULL; // Initialize page object first

class ctipo_cargas_addopt extends ctipo_cargas {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'tipo_cargas';

	// Page object name
	var $PageObjName = 'tipo_cargas_addopt';

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

		// Table object (tipo_cargas)
		if (!isset($GLOBALS["tipo_cargas"]) || get_class($GLOBALS["tipo_cargas"]) == "ctipo_cargas") {
			$GLOBALS["tipo_cargas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tipo_cargas"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tipo_cargas', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("tipo_cargaslist.php"));
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
		global $EW_EXPORT, $tipo_cargas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tipo_cargas);
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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		//$this->SetupBreadcrumb(); // Not used
		// Process form if post back

		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_codigo"] = $this->codigo->DbValue;
					$row["x_Tipo_carga"] = $this->Tipo_carga->DbValue;
					$row["x_precio_base"] = $this->precio_base->DbValue;
					$row["x_porcentaje_comision"] = $this->porcentaje_comision->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
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
		$this->Tipo_carga->CurrentValue = NULL;
		$this->Tipo_carga->OldValue = $this->Tipo_carga->CurrentValue;
		$this->precio_base->CurrentValue = NULL;
		$this->precio_base->OldValue = $this->precio_base->CurrentValue;
		$this->porcentaje_comision->CurrentValue = 0.00;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Tipo_carga->FldIsDetailKey) {
			$this->Tipo_carga->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_Tipo_carga")));
		}
		if (!$this->precio_base->FldIsDetailKey) {
			$this->precio_base->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_precio_base")));
		}
		if (!$this->porcentaje_comision->FldIsDetailKey) {
			$this->porcentaje_comision->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_porcentaje_comision")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->Tipo_carga->CurrentValue = ew_ConvertToUtf8($this->Tipo_carga->FormValue);
		$this->precio_base->CurrentValue = ew_ConvertToUtf8($this->precio_base->FormValue);
		$this->porcentaje_comision->CurrentValue = ew_ConvertToUtf8($this->porcentaje_comision->FormValue);
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
		$this->Tipo_carga->setDbValue($rs->fields('Tipo_carga'));
		$this->precio_base->setDbValue($rs->fields('precio_base'));
		$this->porcentaje_comision->setDbValue($rs->fields('porcentaje_comision'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->Tipo_carga->DbValue = $row['Tipo_carga'];
		$this->precio_base->DbValue = $row['precio_base'];
		$this->porcentaje_comision->DbValue = $row['porcentaje_comision'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->precio_base->FormValue == $this->precio_base->CurrentValue && is_numeric(ew_StrToFloat($this->precio_base->CurrentValue)))
			$this->precio_base->CurrentValue = ew_StrToFloat($this->precio_base->CurrentValue);

		// Convert decimal values if posted back
		if ($this->porcentaje_comision->FormValue == $this->porcentaje_comision->CurrentValue && is_numeric(ew_StrToFloat($this->porcentaje_comision->CurrentValue)))
			$this->porcentaje_comision->CurrentValue = ew_StrToFloat($this->porcentaje_comision->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// Tipo_carga
		// precio_base
		// porcentaje_comision

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// Tipo_carga
			$this->Tipo_carga->ViewValue = $this->Tipo_carga->CurrentValue;
			$this->Tipo_carga->ViewCustomAttributes = "";

			// precio_base
			$this->precio_base->ViewValue = $this->precio_base->CurrentValue;
			$this->precio_base->ViewValue = ew_FormatCurrency($this->precio_base->ViewValue, 2, 0, 0, -1);
			$this->precio_base->ViewCustomAttributes = "";

			// porcentaje_comision
			$this->porcentaje_comision->ViewValue = $this->porcentaje_comision->CurrentValue;
			$this->porcentaje_comision->ViewCustomAttributes = "";

			// Tipo_carga
			$this->Tipo_carga->LinkCustomAttributes = "";
			$this->Tipo_carga->HrefValue = "";
			$this->Tipo_carga->TooltipValue = "";

			// precio_base
			$this->precio_base->LinkCustomAttributes = "";
			$this->precio_base->HrefValue = "";
			$this->precio_base->TooltipValue = "";

			// porcentaje_comision
			$this->porcentaje_comision->LinkCustomAttributes = "";
			$this->porcentaje_comision->HrefValue = "";
			$this->porcentaje_comision->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Tipo_carga
			$this->Tipo_carga->EditAttrs["class"] = "form-control";
			$this->Tipo_carga->EditCustomAttributes = "";
			$this->Tipo_carga->EditValue = ew_HtmlEncode($this->Tipo_carga->CurrentValue);
			$this->Tipo_carga->PlaceHolder = ew_RemoveHtml($this->Tipo_carga->FldCaption());

			// precio_base
			$this->precio_base->EditAttrs["class"] = "form-control";
			$this->precio_base->EditCustomAttributes = "";
			$this->precio_base->EditValue = ew_HtmlEncode($this->precio_base->CurrentValue);
			$this->precio_base->PlaceHolder = ew_RemoveHtml($this->precio_base->FldCaption());
			if (strval($this->precio_base->EditValue) <> "" && is_numeric($this->precio_base->EditValue)) $this->precio_base->EditValue = ew_FormatNumber($this->precio_base->EditValue, -2, 0, 0, -1);

			// porcentaje_comision
			$this->porcentaje_comision->EditAttrs["class"] = "form-control";
			$this->porcentaje_comision->EditCustomAttributes = "";
			$this->porcentaje_comision->EditValue = ew_HtmlEncode($this->porcentaje_comision->CurrentValue);
			$this->porcentaje_comision->PlaceHolder = ew_RemoveHtml($this->porcentaje_comision->FldCaption());
			if (strval($this->porcentaje_comision->EditValue) <> "" && is_numeric($this->porcentaje_comision->EditValue)) $this->porcentaje_comision->EditValue = ew_FormatNumber($this->porcentaje_comision->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// Tipo_carga

			$this->Tipo_carga->HrefValue = "";

			// precio_base
			$this->precio_base->HrefValue = "";

			// porcentaje_comision
			$this->porcentaje_comision->HrefValue = "";
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
		if (!ew_CheckNumber($this->precio_base->FormValue)) {
			ew_AddMessage($gsFormError, $this->precio_base->FldErrMsg());
		}
		if (!ew_CheckNumber($this->porcentaje_comision->FormValue)) {
			ew_AddMessage($gsFormError, $this->porcentaje_comision->FldErrMsg());
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

		// Tipo_carga
		$this->Tipo_carga->SetDbValueDef($rsnew, $this->Tipo_carga->CurrentValue, NULL, FALSE);

		// precio_base
		$this->precio_base->SetDbValueDef($rsnew, $this->precio_base->CurrentValue, NULL, FALSE);

		// porcentaje_comision
		$this->porcentaje_comision->SetDbValueDef($rsnew, $this->porcentaje_comision->CurrentValue, NULL, strval($this->porcentaje_comision->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
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
			$this->codigo->setDbValue($conn->Insert_ID());
			$rsnew['codigo'] = $this->codigo->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, "tipo_cargaslist.php", "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
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

	// Custom validate event
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
if (!isset($tipo_cargas_addopt)) $tipo_cargas_addopt = new ctipo_cargas_addopt();

// Page init
$tipo_cargas_addopt->Page_Init();

// Page main
$tipo_cargas_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tipo_cargas_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var tipo_cargas_addopt = new ew_Page("tipo_cargas_addopt");
tipo_cargas_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = tipo_cargas_addopt.PageID; // For backward compatibility

// Form object
var ftipo_cargasaddopt = new ew_Form("ftipo_cargasaddopt");

// Validate form
ftipo_cargasaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_precio_base");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tipo_cargas->precio_base->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_porcentaje_comision");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tipo_cargas->porcentaje_comision->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
ftipo_cargasaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftipo_cargasaddopt.ValidateRequired = true;
<?php } else { ?>
ftipo_cargasaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$tipo_cargas_addopt->ShowMessage();
?>
<form name="ftipo_cargasaddopt" id="ftipo_cargasaddopt" class="ewForm form-horizontal" action="tipo_cargasaddopt.php" method="post">
<?php if ($tipo_cargas_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tipo_cargas_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tipo_cargas">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_Tipo_carga"><?php echo $tipo_cargas->Tipo_carga->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_Tipo_carga" name="x_Tipo_carga" id="x_Tipo_carga" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($tipo_cargas->Tipo_carga->PlaceHolder) ?>" value="<?php echo $tipo_cargas->Tipo_carga->EditValue ?>"<?php echo $tipo_cargas->Tipo_carga->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_precio_base"><?php echo $tipo_cargas->precio_base->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_precio_base" name="x_precio_base" id="x_precio_base" size="30" placeholder="<?php echo ew_HtmlEncode($tipo_cargas->precio_base->PlaceHolder) ?>" value="<?php echo $tipo_cargas->precio_base->EditValue ?>"<?php echo $tipo_cargas->precio_base->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_porcentaje_comision"><?php echo $tipo_cargas->porcentaje_comision->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_porcentaje_comision" name="x_porcentaje_comision" id="x_porcentaje_comision" size="30" placeholder="<?php echo ew_HtmlEncode($tipo_cargas->porcentaje_comision->PlaceHolder) ?>" value="<?php echo $tipo_cargas->porcentaje_comision->EditValue ?>"<?php echo $tipo_cargas->porcentaje_comision->EditAttributes() ?>>
</div>
	</div>
</form>
<script type="text/javascript">
ftipo_cargasaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$tipo_cargas_addopt->Page_Terminate();
?>
