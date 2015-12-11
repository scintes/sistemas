<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "choferesinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$choferes_addopt = NULL; // Initialize page object first

class cchoferes_addopt extends cchoferes {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'choferes';

	// Page object name
	var $PageObjName = 'choferes_addopt';

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

		// Table object (choferes)
		if (!isset($GLOBALS["choferes"]) || get_class($GLOBALS["choferes"]) == "cchoferes") {
			$GLOBALS["choferes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["choferes"];
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
			define("EW_TABLE_NAME", 'choferes', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("chofereslist.php"));
		}
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
		global $EW_EXPORT, $choferes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($choferes);
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
					$row["x_nombre"] = $this->nombre->DbValue;
					$row["x_direccion"] = $this->direccion->DbValue;
					$row["x_fecha_nacimiento"] = $this->fecha_nacimiento->DbValue;
					$row["x_tel"] = $this->tel->DbValue;
					$row["x_cel"] = $this->cel->DbValue;
					$row["x__email"] = $this->_email->DbValue;
					$row["x_fecha_inicio"] = $this->fecha_inicio->DbValue;
					$row["x_cetegoria"] = $this->cetegoria->DbValue;
					$row["x_datos"] = $this->datos->DbValue;
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
		$this->codigo->CurrentValue = NULL;
		$this->codigo->OldValue = $this->codigo->CurrentValue;
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->fecha_nacimiento->CurrentValue = date('d/m/Y');
		$this->tel->CurrentValue = NULL;
		$this->tel->OldValue = $this->tel->CurrentValue;
		$this->cel->CurrentValue = NULL;
		$this->cel->OldValue = $this->cel->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->fecha_inicio->CurrentValue = date('d/m/Y');
		$this->cetegoria->CurrentValue = NULL;
		$this->cetegoria->OldValue = $this->cetegoria->CurrentValue;
		$this->datos->CurrentValue = NULL;
		$this->datos->OldValue = $this->datos->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nombre")));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_direccion")));
		}
		if (!$this->fecha_nacimiento->FldIsDetailKey) {
			$this->fecha_nacimiento->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_fecha_nacimiento")));
			$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		}
		if (!$this->tel->FldIsDetailKey) {
			$this->tel->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_tel")));
		}
		if (!$this->cel->FldIsDetailKey) {
			$this->cel->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_cel")));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x__email")));
		}
		if (!$this->fecha_inicio->FldIsDetailKey) {
			$this->fecha_inicio->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_fecha_inicio")));
			$this->fecha_inicio->CurrentValue = ew_UnFormatDateTime($this->fecha_inicio->CurrentValue, 7);
		}
		if (!$this->cetegoria->FldIsDetailKey) {
			$this->cetegoria->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_cetegoria")));
		}
		if (!$this->datos->FldIsDetailKey) {
			$this->datos->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_datos")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->nombre->CurrentValue = ew_ConvertToUtf8($this->nombre->FormValue);
		$this->direccion->CurrentValue = ew_ConvertToUtf8($this->direccion->FormValue);
		$this->fecha_nacimiento->CurrentValue = ew_ConvertToUtf8($this->fecha_nacimiento->FormValue);
		$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		$this->tel->CurrentValue = ew_ConvertToUtf8($this->tel->FormValue);
		$this->cel->CurrentValue = ew_ConvertToUtf8($this->cel->FormValue);
		$this->_email->CurrentValue = ew_ConvertToUtf8($this->_email->FormValue);
		$this->fecha_inicio->CurrentValue = ew_ConvertToUtf8($this->fecha_inicio->FormValue);
		$this->fecha_inicio->CurrentValue = ew_UnFormatDateTime($this->fecha_inicio->CurrentValue, 7);
		$this->cetegoria->CurrentValue = ew_ConvertToUtf8($this->cetegoria->FormValue);
		$this->datos->CurrentValue = ew_ConvertToUtf8($this->datos->FormValue);
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
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->fecha_inicio->setDbValue($rs->fields('fecha_inicio'));
		$this->cetegoria->setDbValue($rs->fields('cetegoria'));
		$this->datos->setDbValue($rs->fields('datos'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->nombre->DbValue = $row['nombre'];
		$this->direccion->DbValue = $row['direccion'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->tel->DbValue = $row['tel'];
		$this->cel->DbValue = $row['cel'];
		$this->_email->DbValue = $row['email'];
		$this->fecha_inicio->DbValue = $row['fecha_inicio'];
		$this->cetegoria->DbValue = $row['cetegoria'];
		$this->datos->DbValue = $row['datos'];
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
		// nombre
		// direccion
		// fecha_nacimiento
		// tel
		// cel
		// email
		// fecha_inicio
		// cetegoria
		// datos

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->ViewCustomAttributes = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
			$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 7);
			$this->fecha_nacimiento->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewValue = strtolower($this->_email->ViewValue);
			$this->_email->ViewCustomAttributes = "";

			// fecha_inicio
			$this->fecha_inicio->ViewValue = $this->fecha_inicio->CurrentValue;
			$this->fecha_inicio->ViewValue = ew_FormatDateTime($this->fecha_inicio->ViewValue, 7);
			$this->fecha_inicio->ViewCustomAttributes = "";

			// cetegoria
			$this->cetegoria->ViewValue = $this->cetegoria->CurrentValue;
			$this->cetegoria->ViewValue = ew_FormatNumber($this->cetegoria->ViewValue, 0, -2, -2, -2);
			$this->cetegoria->ViewCustomAttributes = "";

			// datos
			$this->datos->ViewValue = $this->datos->CurrentValue;
			$this->datos->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// tel
			$this->tel->LinkCustomAttributes = "";
			$this->tel->HrefValue = "";
			$this->tel->TooltipValue = "";

			// cel
			$this->cel->LinkCustomAttributes = "";
			$this->cel->HrefValue = "";
			$this->cel->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// fecha_inicio
			$this->fecha_inicio->LinkCustomAttributes = "";
			$this->fecha_inicio->HrefValue = "";
			$this->fecha_inicio->TooltipValue = "";

			// cetegoria
			$this->cetegoria->LinkCustomAttributes = "";
			$this->cetegoria->HrefValue = "";
			$this->cetegoria->TooltipValue = "";

			// datos
			$this->datos->LinkCustomAttributes = "";
			$this->datos->HrefValue = "";
			$this->datos->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// codigo
			// nombre

			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// fecha_nacimiento
			$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->fecha_nacimiento->EditCustomAttributes = "";
			$this->fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_nacimiento->CurrentValue, 7));
			$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

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

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// fecha_inicio
			$this->fecha_inicio->EditAttrs["class"] = "form-control";
			$this->fecha_inicio->EditCustomAttributes = "";
			$this->fecha_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_inicio->CurrentValue, 7));
			$this->fecha_inicio->PlaceHolder = ew_RemoveHtml($this->fecha_inicio->FldCaption());

			// cetegoria
			$this->cetegoria->EditAttrs["class"] = "form-control";
			$this->cetegoria->EditCustomAttributes = "";
			$this->cetegoria->EditValue = ew_HtmlEncode($this->cetegoria->CurrentValue);
			$this->cetegoria->PlaceHolder = ew_RemoveHtml($this->cetegoria->FldCaption());

			// datos
			$this->datos->EditAttrs["class"] = "form-control";
			$this->datos->EditCustomAttributes = "";
			$this->datos->EditValue = ew_HtmlEncode($this->datos->CurrentValue);
			$this->datos->PlaceHolder = ew_RemoveHtml($this->datos->FldCaption());

			// Edit refer script
			// codigo

			$this->codigo->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->HrefValue = "";

			// tel
			$this->tel->HrefValue = "";

			// cel
			$this->cel->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// fecha_inicio
			$this->fecha_inicio->HrefValue = "";

			// cetegoria
			$this->cetegoria->HrefValue = "";

			// datos
			$this->datos->HrefValue = "";
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
		if (!ew_CheckEuroDate($this->fecha_nacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_nacimiento->FldErrMsg());
		}
		if (!ew_CheckEmail($this->_email->FormValue)) {
			ew_AddMessage($gsFormError, $this->_email->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_inicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_inicio->FldErrMsg());
		}
		if (!ew_CheckInteger($this->cetegoria->FormValue)) {
			ew_AddMessage($gsFormError, $this->cetegoria->FldErrMsg());
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

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, FALSE);

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, FALSE);

		// fecha_nacimiento
		$this->fecha_nacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7), NULL, FALSE);

		// tel
		$this->tel->SetDbValueDef($rsnew, $this->tel->CurrentValue, NULL, FALSE);

		// cel
		$this->cel->SetDbValueDef($rsnew, $this->cel->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// fecha_inicio
		$this->fecha_inicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_inicio->CurrentValue, 7), NULL, FALSE);

		// cetegoria
		$this->cetegoria->SetDbValueDef($rsnew, $this->cetegoria->CurrentValue, NULL, FALSE);

		// datos
		$this->datos->SetDbValueDef($rsnew, $this->datos->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, "chofereslist.php", "", $this->TableVar, TRUE);
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
if (!isset($choferes_addopt)) $choferes_addopt = new cchoferes_addopt();

// Page init
$choferes_addopt->Page_Init();

// Page main
$choferes_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$choferes_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var choferes_addopt = new ew_Page("choferes_addopt");
choferes_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = choferes_addopt.PageID; // For backward compatibility

// Form object
var fchoferesaddopt = new ew_Form("fchoferesaddopt");

// Validate form
fchoferesaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($choferes->fecha_nacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($choferes->_email->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_inicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($choferes->fecha_inicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cetegoria");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($choferes->cetegoria->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fchoferesaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fchoferesaddopt.ValidateRequired = true;
<?php } else { ?>
fchoferesaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$choferes_addopt->ShowMessage();
?>
<form name="fchoferesaddopt" id="fchoferesaddopt" class="ewForm form-horizontal" action="choferesaddopt.php" method="post">
<?php if ($choferes_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $choferes_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="choferes">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_nombre"><?php echo $choferes->nombre->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($choferes->nombre->PlaceHolder) ?>" value="<?php echo $choferes->nombre->EditValue ?>"<?php echo $choferes->nombre->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_direccion"><?php echo $choferes->direccion->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($choferes->direccion->PlaceHolder) ?>" value="<?php echo $choferes->direccion->EditValue ?>"<?php echo $choferes->direccion->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_fecha_nacimiento"><?php echo $choferes->fecha_nacimiento->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_fecha_nacimiento" name="x_fecha_nacimiento" id="x_fecha_nacimiento" placeholder="<?php echo ew_HtmlEncode($choferes->fecha_nacimiento->PlaceHolder) ?>" value="<?php echo $choferes->fecha_nacimiento->EditValue ?>"<?php echo $choferes->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$choferes->fecha_nacimiento->ReadOnly && !$choferes->fecha_nacimiento->Disabled && !isset($choferes->fecha_nacimiento->EditAttrs["readonly"]) && !isset($choferes->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fchoferesaddopt", "x_fecha_nacimiento", "%d/%m/%Y");
</script>
<?php } ?>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_tel"><?php echo $choferes->tel->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_tel" name="x_tel" id="x_tel" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($choferes->tel->PlaceHolder) ?>" value="<?php echo $choferes->tel->EditValue ?>"<?php echo $choferes->tel->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_cel"><?php echo $choferes->cel->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_cel" name="x_cel" id="x_cel" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($choferes->cel->PlaceHolder) ?>" value="<?php echo $choferes->cel->EditValue ?>"<?php echo $choferes->cel->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x__email"><?php echo $choferes->_email->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($choferes->_email->PlaceHolder) ?>" value="<?php echo $choferes->_email->EditValue ?>"<?php echo $choferes->_email->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_fecha_inicio"><?php echo $choferes->fecha_inicio->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_fecha_inicio" name="x_fecha_inicio" id="x_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($choferes->fecha_inicio->PlaceHolder) ?>" value="<?php echo $choferes->fecha_inicio->EditValue ?>"<?php echo $choferes->fecha_inicio->EditAttributes() ?>>
<?php if (!$choferes->fecha_inicio->ReadOnly && !$choferes->fecha_inicio->Disabled && !isset($choferes->fecha_inicio->EditAttrs["readonly"]) && !isset($choferes->fecha_inicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fchoferesaddopt", "x_fecha_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_cetegoria"><?php echo $choferes->cetegoria->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_cetegoria" name="x_cetegoria" id="x_cetegoria" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($choferes->cetegoria->PlaceHolder) ?>" value="<?php echo $choferes->cetegoria->EditValue ?>"<?php echo $choferes->cetegoria->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_datos"><?php echo $choferes->datos->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_datos" name="x_datos" id="x_datos" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($choferes->datos->PlaceHolder) ?>" value="<?php echo $choferes->datos->EditValue ?>"<?php echo $choferes->datos->EditAttributes() ?>>
</div>
	</div>
</form>
<script type="text/javascript">
fchoferesaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$choferes_addopt->Page_Terminate();
?>
