<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "talleresinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$talleres_addopt = NULL; // Initialize page object first

class ctalleres_addopt extends ctalleres {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'talleres';

	// Page object name
	var $PageObjName = 'talleres_addopt';

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

		// Table object (talleres)
		if (!isset($GLOBALS["talleres"]) || get_class($GLOBALS["talleres"]) == "ctalleres") {
			$GLOBALS["talleres"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["talleres"];
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
			define("EW_TABLE_NAME", 'talleres', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("tallereslist.php"));
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
		global $EW_EXPORT, $talleres;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($talleres);
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
					$row["x_taller"] = $this->taller->DbValue;
					$row["x_direccion"] = $this->direccion->DbValue;
					$row["x_tel"] = $this->tel->DbValue;
					$row["x_cel"] = $this->cel->DbValue;
					$row["x_mail"] = $this->mail->DbValue;
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
		$this->taller->CurrentValue = NULL;
		$this->taller->OldValue = $this->taller->CurrentValue;
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->tel->CurrentValue = NULL;
		$this->tel->OldValue = $this->tel->CurrentValue;
		$this->cel->CurrentValue = NULL;
		$this->cel->OldValue = $this->cel->CurrentValue;
		$this->mail->CurrentValue = NULL;
		$this->mail->OldValue = $this->mail->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->taller->FldIsDetailKey) {
			$this->taller->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_taller")));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_direccion")));
		}
		if (!$this->tel->FldIsDetailKey) {
			$this->tel->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_tel")));
		}
		if (!$this->cel->FldIsDetailKey) {
			$this->cel->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_cel")));
		}
		if (!$this->mail->FldIsDetailKey) {
			$this->mail->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_mail")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->taller->CurrentValue = ew_ConvertToUtf8($this->taller->FormValue);
		$this->direccion->CurrentValue = ew_ConvertToUtf8($this->direccion->FormValue);
		$this->tel->CurrentValue = ew_ConvertToUtf8($this->tel->FormValue);
		$this->cel->CurrentValue = ew_ConvertToUtf8($this->cel->FormValue);
		$this->mail->CurrentValue = ew_ConvertToUtf8($this->mail->FormValue);
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
		$this->taller->setDbValue($rs->fields('taller'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->mail->setDbValue($rs->fields('mail'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->taller->DbValue = $row['taller'];
		$this->direccion->DbValue = $row['direccion'];
		$this->tel->DbValue = $row['tel'];
		$this->cel->DbValue = $row['cel'];
		$this->mail->DbValue = $row['mail'];
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
		// taller
		// direccion
		// tel
		// cel
		// mail

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// taller
			$this->taller->ViewValue = $this->taller->CurrentValue;
			$this->taller->ViewCustomAttributes = "";

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewCustomAttributes = "";

			// mail
			$this->mail->ViewValue = $this->mail->CurrentValue;
			$this->mail->ViewCustomAttributes = "";

			// taller
			$this->taller->LinkCustomAttributes = "";
			$this->taller->HrefValue = "";
			$this->taller->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// tel
			$this->tel->LinkCustomAttributes = "";
			$this->tel->HrefValue = "";
			$this->tel->TooltipValue = "";

			// cel
			$this->cel->LinkCustomAttributes = "";
			$this->cel->HrefValue = "";
			$this->cel->TooltipValue = "";

			// mail
			$this->mail->LinkCustomAttributes = "";
			$this->mail->HrefValue = "";
			$this->mail->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// taller
			$this->taller->EditAttrs["class"] = "form-control";
			$this->taller->EditCustomAttributes = "";
			$this->taller->EditValue = ew_HtmlEncode($this->taller->CurrentValue);
			$this->taller->PlaceHolder = ew_RemoveHtml($this->taller->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// tel
			$this->tel->EditAttrs["class"] = "form-control";
			$this->tel->EditCustomAttributes = "";
			$this->tel->EditValue = ew_HtmlEncode($this->tel->CurrentValue);
			$this->tel->PlaceHolder = ew_RemoveHtml($this->tel->FldCaption());

			// cel
			$this->cel->EditAttrs["class"] = "form-control";
			$this->cel->EditCustomAttributes = "";
			$this->cel->EditValue = ew_HtmlEncode($this->cel->CurrentValue);
			$this->cel->PlaceHolder = ew_RemoveHtml($this->cel->FldCaption());

			// mail
			$this->mail->EditAttrs["class"] = "form-control";
			$this->mail->EditCustomAttributes = "";
			$this->mail->EditValue = ew_HtmlEncode($this->mail->CurrentValue);
			$this->mail->PlaceHolder = ew_RemoveHtml($this->mail->FldCaption());

			// Edit refer script
			// taller

			$this->taller->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// tel
			$this->tel->HrefValue = "";

			// cel
			$this->cel->HrefValue = "";

			// mail
			$this->mail->HrefValue = "";
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

		// taller
		$this->taller->SetDbValueDef($rsnew, $this->taller->CurrentValue, NULL, FALSE);

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, FALSE);

		// tel
		$this->tel->SetDbValueDef($rsnew, $this->tel->CurrentValue, NULL, FALSE);

		// cel
		$this->cel->SetDbValueDef($rsnew, $this->cel->CurrentValue, NULL, FALSE);

		// mail
		$this->mail->SetDbValueDef($rsnew, $this->mail->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, "tallereslist.php", "", $this->TableVar, TRUE);
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
if (!isset($talleres_addopt)) $talleres_addopt = new ctalleres_addopt();

// Page init
$talleres_addopt->Page_Init();

// Page main
$talleres_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$talleres_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var talleres_addopt = new ew_Page("talleres_addopt");
talleres_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = talleres_addopt.PageID; // For backward compatibility

// Form object
var ftalleresaddopt = new ew_Form("ftalleresaddopt");

// Validate form
ftalleresaddopt.Validate = function() {
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
	return true;
}

// Form_CustomValidate event
ftalleresaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftalleresaddopt.ValidateRequired = true;
<?php } else { ?>
ftalleresaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$talleres_addopt->ShowMessage();
?>
<form name="ftalleresaddopt" id="ftalleresaddopt" class="ewForm form-horizontal" action="talleresaddopt.php" method="post">
<?php if ($talleres_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $talleres_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="talleres">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_taller"><?php echo $talleres->taller->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_taller" name="x_taller" id="x_taller" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($talleres->taller->PlaceHolder) ?>" value="<?php echo $talleres->taller->EditValue ?>"<?php echo $talleres->taller->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_direccion"><?php echo $talleres->direccion->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($talleres->direccion->PlaceHolder) ?>" value="<?php echo $talleres->direccion->EditValue ?>"<?php echo $talleres->direccion->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_tel"><?php echo $talleres->tel->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_tel" name="x_tel" id="x_tel" size="30" maxlength="16" placeholder="<?php echo ew_HtmlEncode($talleres->tel->PlaceHolder) ?>" value="<?php echo $talleres->tel->EditValue ?>"<?php echo $talleres->tel->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_cel"><?php echo $talleres->cel->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_cel" name="x_cel" id="x_cel" size="30" maxlength="16" placeholder="<?php echo ew_HtmlEncode($talleres->cel->PlaceHolder) ?>" value="<?php echo $talleres->cel->EditValue ?>"<?php echo $talleres->cel->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_mail"><?php echo $talleres->mail->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_mail" name="x_mail" id="x_mail" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($talleres->mail->PlaceHolder) ?>" value="<?php echo $talleres->mail->EditValue ?>"<?php echo $talleres->mail->EditAttributes() ?>>
</div>
	</div>
</form>
<script type="text/javascript">
ftalleresaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$talleres_addopt->Page_Terminate();
?>
