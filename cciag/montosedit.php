<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "montosinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$montos_edit = NULL; // Initialize page object first

class cmontos_edit extends cmontos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'montos';

	// Page object name
	var $PageObjName = 'montos_edit';

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
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
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
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
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

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (montos)
		if (!isset($GLOBALS["montos"])) {
			$GLOBALS["montos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["montos"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'montos', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
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
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("montoslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("montoslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
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
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->importe->FldIsDetailKey) {
			$this->importe->setFormValue($objForm->GetValue("x_importe"));
		}
		if (!$this->fecha_creacion->FldIsDetailKey) {
			$this->fecha_creacion->setFormValue($objForm->GetValue("x_fecha_creacion"));
			$this->fecha_creacion->CurrentValue = ew_UnFormatDateTime($this->fecha_creacion->CurrentValue, 7);
		}
		if (!$this->activa->FldIsDetailKey) {
			$this->activa->setFormValue($objForm->GetValue("x_activa"));
		}
		if (!$this->id_usuario->FldIsDetailKey) {
			$this->id_usuario->setFormValue($objForm->GetValue("x_id_usuario"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->importe->CurrentValue = $this->importe->FormValue;
		$this->fecha_creacion->CurrentValue = $this->fecha_creacion->FormValue;
		$this->fecha_creacion->CurrentValue = ew_UnFormatDateTime($this->fecha_creacion->CurrentValue, 7);
		$this->activa->CurrentValue = $this->activa->FormValue;
		$this->id_usuario->CurrentValue = $this->id_usuario->FormValue;
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
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->importe->setDbValue($rs->fields('importe'));
		$this->fecha_creacion->setDbValue($rs->fields('fecha_creacion'));
		$this->activa->setDbValue($rs->fields('activa'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->importe->DbValue = $row['importe'];
		$this->fecha_creacion->DbValue = $row['fecha_creacion'];
		$this->activa->DbValue = $row['activa'];
		$this->id_usuario->DbValue = $row['id_usuario'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->importe->FormValue == $this->importe->CurrentValue && is_numeric(ew_StrToFloat($this->importe->CurrentValue)))
			$this->importe->CurrentValue = ew_StrToFloat($this->importe->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// descripcion
		// importe
		// fecha_creacion
		// activa
		// id_usuario

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
			$this->descripcion->ViewCustomAttributes = "";

			// importe
			$this->importe->ViewValue = $this->importe->CurrentValue;
			$this->importe->ViewCustomAttributes = "";

			// fecha_creacion
			$this->fecha_creacion->ViewValue = $this->fecha_creacion->CurrentValue;
			$this->fecha_creacion->ViewValue = ew_FormatDateTime($this->fecha_creacion->ViewValue, 7);
			$this->fecha_creacion->ViewCustomAttributes = "";

			// activa
			if (strval($this->activa->CurrentValue) <> "") {
				switch ($this->activa->CurrentValue) {
					case $this->activa->FldTagValue(1):
						$this->activa->ViewValue = $this->activa->FldTagCaption(1) <> "" ? $this->activa->FldTagCaption(1) : $this->activa->CurrentValue;
						break;
					case $this->activa->FldTagValue(2):
						$this->activa->ViewValue = $this->activa->FldTagCaption(2) <> "" ? $this->activa->FldTagCaption(2) : $this->activa->CurrentValue;
						break;
					default:
						$this->activa->ViewValue = $this->activa->CurrentValue;
				}
			} else {
				$this->activa->ViewValue = NULL;
			}
			$this->activa->ViewCustomAttributes = "";

			// id_usuario
			$this->id_usuario->ViewValue = $this->id_usuario->CurrentValue;
			$this->id_usuario->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// importe
			$this->importe->LinkCustomAttributes = "";
			$this->importe->HrefValue = "";
			$this->importe->TooltipValue = "";

			// fecha_creacion
			$this->fecha_creacion->LinkCustomAttributes = "";
			$this->fecha_creacion->HrefValue = "";
			$this->fecha_creacion->TooltipValue = "";

			// activa
			$this->activa->LinkCustomAttributes = "";
			$this->activa->HrefValue = "";
			$this->activa->TooltipValue = "";

			// id_usuario
			$this->id_usuario->LinkCustomAttributes = "";
			$this->id_usuario->HrefValue = "";
			$this->id_usuario->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);
			$this->descripcion->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->descripcion->FldCaption()));

			// importe
			$this->importe->EditCustomAttributes = "";
			$this->importe->EditValue = ew_HtmlEncode($this->importe->CurrentValue);
			$this->importe->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->importe->FldCaption()));
			if (strval($this->importe->EditValue) <> "" && is_numeric($this->importe->EditValue)) $this->importe->EditValue = ew_FormatNumber($this->importe->EditValue, -2, -1, -2, 0);

			// fecha_creacion
			$this->fecha_creacion->EditCustomAttributes = "";
			$this->fecha_creacion->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_creacion->CurrentValue, 7));
			$this->fecha_creacion->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->fecha_creacion->FldCaption()));

			// activa
			$this->activa->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->activa->FldTagValue(1), $this->activa->FldTagCaption(1) <> "" ? $this->activa->FldTagCaption(1) : $this->activa->FldTagValue(1));
			$arwrk[] = array($this->activa->FldTagValue(2), $this->activa->FldTagCaption(2) <> "" ? $this->activa->FldTagCaption(2) : $this->activa->FldTagValue(2));
			$this->activa->EditValue = $arwrk;

			// id_usuario
			$this->id_usuario->EditCustomAttributes = "";
			$this->id_usuario->EditValue = ew_HtmlEncode($this->id_usuario->CurrentValue);
			$this->id_usuario->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id_usuario->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// descripcion
			$this->descripcion->HrefValue = "";

			// importe
			$this->importe->HrefValue = "";

			// fecha_creacion
			$this->fecha_creacion->HrefValue = "";

			// activa
			$this->activa->HrefValue = "";

			// id_usuario
			$this->id_usuario->HrefValue = "";
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
		if (!ew_CheckNumber($this->importe->FormValue)) {
			ew_AddMessage($gsFormError, $this->importe->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_creacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_creacion->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id_usuario->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_usuario->FldErrMsg());
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
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// descripcion
			$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, NULL, $this->descripcion->ReadOnly);

			// importe
			$this->importe->SetDbValueDef($rsnew, $this->importe->CurrentValue, NULL, $this->importe->ReadOnly);

			// fecha_creacion
			$this->fecha_creacion->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_creacion->CurrentValue, 7), NULL, $this->fecha_creacion->ReadOnly);

			// activa
			$this->activa->SetDbValueDef($rsnew, $this->activa->CurrentValue, NULL, $this->activa->ReadOnly);

			// id_usuario
			$this->id_usuario->SetDbValueDef($rsnew, $this->id_usuario->CurrentValue, NULL, $this->id_usuario->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "montoslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($montos_edit)) $montos_edit = new cmontos_edit();

// Page init
$montos_edit->Page_Init();

// Page main
$montos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$montos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var montos_edit = new ew_Page("montos_edit");
montos_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = montos_edit.PageID; // For backward compatibility

// Form object
var fmontosedit = new ew_Form("fmontosedit");

// Validate form
fmontosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_importe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($montos->importe->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_creacion");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($montos->fecha_creacion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_usuario");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($montos->id_usuario->FldErrMsg()) ?>");

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
fmontosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmontosedit.ValidateRequired = true;
<?php } else { ?>
fmontosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $montos_edit->ShowPageHeader(); ?>
<?php
$montos_edit->ShowMessage();
?>
<form name="fmontosedit" id="fmontosedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="montos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_montosedit" class="table table-bordered table-striped">
<?php if ($montos->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_montos_id"><?php echo $montos->id->FldCaption() ?></span></td>
		<td<?php echo $montos->id->CellAttributes() ?>>
<span id="el_montos_id" class="control-group">
<span<?php echo $montos->id->ViewAttributes() ?>>
<?php echo $montos->id->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($montos->id->CurrentValue) ?>">
<?php echo $montos->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($montos->descripcion->Visible) { // descripcion ?>
	<tr id="r_descripcion">
		<td><span id="elh_montos_descripcion"><?php echo $montos->descripcion->FldCaption() ?></span></td>
		<td<?php echo $montos->descripcion->CellAttributes() ?>>
<span id="el_montos_descripcion" class="control-group">
<input type="text" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="100" placeholder="<?php echo $montos->descripcion->PlaceHolder ?>" value="<?php echo $montos->descripcion->EditValue ?>"<?php echo $montos->descripcion->EditAttributes() ?>>
</span>
<?php echo $montos->descripcion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($montos->importe->Visible) { // importe ?>
	<tr id="r_importe">
		<td><span id="elh_montos_importe"><?php echo $montos->importe->FldCaption() ?></span></td>
		<td<?php echo $montos->importe->CellAttributes() ?>>
<span id="el_montos_importe" class="control-group">
<input type="text" data-field="x_importe" name="x_importe" id="x_importe" size="30" placeholder="<?php echo $montos->importe->PlaceHolder ?>" value="<?php echo $montos->importe->EditValue ?>"<?php echo $montos->importe->EditAttributes() ?>>
</span>
<?php echo $montos->importe->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($montos->fecha_creacion->Visible) { // fecha_creacion ?>
	<tr id="r_fecha_creacion">
		<td><span id="elh_montos_fecha_creacion"><?php echo $montos->fecha_creacion->FldCaption() ?></span></td>
		<td<?php echo $montos->fecha_creacion->CellAttributes() ?>>
<span id="el_montos_fecha_creacion" class="control-group">
<input type="text" data-field="x_fecha_creacion" name="x_fecha_creacion" id="x_fecha_creacion" placeholder="<?php echo $montos->fecha_creacion->PlaceHolder ?>" value="<?php echo $montos->fecha_creacion->EditValue ?>"<?php echo $montos->fecha_creacion->EditAttributes() ?>>
</span>
<?php echo $montos->fecha_creacion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($montos->activa->Visible) { // activa ?>
	<tr id="r_activa">
		<td><span id="elh_montos_activa"><?php echo $montos->activa->FldCaption() ?></span></td>
		<td<?php echo $montos->activa->CellAttributes() ?>>
<span id="el_montos_activa" class="control-group">
<div id="tp_x_activa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_activa" id="x_activa" value="{value}"<?php echo $montos->activa->EditAttributes() ?>></div>
<div id="dsl_x_activa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $montos->activa->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($montos->activa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_activa" name="x_activa" id="x_activa_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $montos->activa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $montos->activa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($montos->id_usuario->Visible) { // id_usuario ?>
	<tr id="r_id_usuario">
		<td><span id="elh_montos_id_usuario"><?php echo $montos->id_usuario->FldCaption() ?></span></td>
		<td<?php echo $montos->id_usuario->CellAttributes() ?>>
<span id="el_montos_id_usuario" class="control-group">
<input type="text" data-field="x_id_usuario" name="x_id_usuario" id="x_id_usuario" size="30" placeholder="<?php echo $montos->id_usuario->PlaceHolder ?>" value="<?php echo $montos->id_usuario->EditValue ?>"<?php echo $montos->id_usuario->EditAttributes() ?>>
</span>
<?php echo $montos->id_usuario->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fmontosedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$montos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$montos_edit->Page_Terminate();
?>
