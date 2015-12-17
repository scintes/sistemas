<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "cciag_ewcfg11.php" ?>
<?php include_once "cciag_ewmysql11.php" ?>
<?php include_once "cciag_phpfn11.php" ?>
<?php include_once "cciag_usuarioinfo.php" ?>
<?php include_once "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$usuario_add = NULL; // Initialize page object first

class cusuario_add extends cusuario {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'usuario';

	// Page object name
	var $PageObjName = 'usuario_add';

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
	var $AuditTrailOnAdd = TRUE;

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

		// Table object (usuario)
		if (!isset($GLOBALS["usuario"]) || get_class($GLOBALS["usuario"]) == "cusuario") {
			$GLOBALS["usuario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["usuario"];
		}

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'usuario', TRUE);

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
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("cciag_usuariolist.php"));
		}

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
		global $EW_EXPORT, $usuario;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($usuario);
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
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
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
					$this->Page_Terminate("cciag_usuariolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "cciag_usuarioview.php")
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
		$this->usuario->CurrentValue = NULL;
		$this->usuario->OldValue = $this->usuario->CurrentValue;
		$this->contrasenia->CurrentValue = NULL;
		$this->contrasenia->OldValue = $this->contrasenia->CurrentValue;
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->cel->CurrentValue = NULL;
		$this->cel->OldValue = $this->cel->CurrentValue;
		$this->activo->CurrentValue = S;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->usuario->FldIsDetailKey) {
			$this->usuario->setFormValue($objForm->GetValue("x_usuario"));
		}
		if (!$this->contrasenia->FldIsDetailKey) {
			$this->contrasenia->setFormValue($objForm->GetValue("x_contrasenia"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->cel->FldIsDetailKey) {
			$this->cel->setFormValue($objForm->GetValue("x_cel"));
		}
		if (!$this->activo->FldIsDetailKey) {
			$this->activo->setFormValue($objForm->GetValue("x_activo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->usuario->CurrentValue = $this->usuario->FormValue;
		$this->contrasenia->CurrentValue = $this->contrasenia->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->cel->CurrentValue = $this->cel->FormValue;
		$this->activo->CurrentValue = $this->activo->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('add');
			if (!$res) {
				$sUserIdMsg = $Language->Phrase("NoPermission");
				$this->setFailureMessage($sUserIdMsg);
			}
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
		$this->usuario->setDbValue($rs->fields('usuario'));
		$this->contrasenia->setDbValue($rs->fields('contrasenia'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->activo->setDbValue($rs->fields('activo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->usuario->DbValue = $row['usuario'];
		$this->contrasenia->DbValue = $row['contrasenia'];
		$this->nombre->DbValue = $row['nombre'];
		$this->_email->DbValue = $row['email'];
		$this->cel->DbValue = $row['cel'];
		$this->activo->DbValue = $row['activo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		// id
		// usuario
		// contrasenia
		// nombre
		// email
		// cel
		// activo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// usuario
			$this->usuario->ViewValue = $this->usuario->CurrentValue;
			$this->usuario->ViewCustomAttributes = "";

			// contrasenia
			$this->contrasenia->ViewValue = "********";
			$this->contrasenia->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewValue = strtolower($this->_email->ViewValue);
			$this->_email->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewValue = ew_FormatNumber($this->cel->ViewValue, 0, -2, -2, -2);
			$this->cel->ViewCustomAttributes = "";

			// activo
			if (strval($this->activo->CurrentValue) <> "") {
				switch ($this->activo->CurrentValue) {
					case $this->activo->FldTagValue(1):
						$this->activo->ViewValue = $this->activo->FldTagCaption(1) <> "" ? $this->activo->FldTagCaption(1) : $this->activo->CurrentValue;
						break;
					case $this->activo->FldTagValue(2):
						$this->activo->ViewValue = $this->activo->FldTagCaption(2) <> "" ? $this->activo->FldTagCaption(2) : $this->activo->CurrentValue;
						break;
					default:
						$this->activo->ViewValue = $this->activo->CurrentValue;
				}
			} else {
				$this->activo->ViewValue = NULL;
			}
			$this->activo->ViewCustomAttributes = "";

			// usuario
			$this->usuario->LinkCustomAttributes = "";
			$this->usuario->HrefValue = "";
			$this->usuario->TooltipValue = "";

			// contrasenia
			$this->contrasenia->LinkCustomAttributes = "";
			$this->contrasenia->HrefValue = "";
			$this->contrasenia->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// cel
			$this->cel->LinkCustomAttributes = "";
			$this->cel->HrefValue = "";
			$this->cel->TooltipValue = "";

			// activo
			$this->activo->LinkCustomAttributes = "";
			$this->activo->HrefValue = "";
			$this->activo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// usuario
			$this->usuario->EditAttrs["class"] = "form-control";
			$this->usuario->EditCustomAttributes = "";
			$this->usuario->EditValue = ew_HtmlEncode($this->usuario->CurrentValue);
			$this->usuario->PlaceHolder = ew_RemoveHtml($this->usuario->FldCaption());

			// contrasenia
			$this->contrasenia->EditAttrs["class"] = "form-control";
			$this->contrasenia->EditCustomAttributes = "";
			$this->contrasenia->EditValue = ew_HtmlEncode($this->contrasenia->CurrentValue);
			$this->contrasenia->PlaceHolder = ew_RemoveHtml($this->contrasenia->FldCaption());

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// cel
			$this->cel->EditAttrs["class"] = "form-control";
			$this->cel->EditCustomAttributes = "";
			$this->cel->EditValue = ew_HtmlEncode($this->cel->CurrentValue);
			$this->cel->PlaceHolder = ew_RemoveHtml($this->cel->FldCaption());

			// activo
			$this->activo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->activo->FldTagValue(1), $this->activo->FldTagCaption(1) <> "" ? $this->activo->FldTagCaption(1) : $this->activo->FldTagValue(1));
			$arwrk[] = array($this->activo->FldTagValue(2), $this->activo->FldTagCaption(2) <> "" ? $this->activo->FldTagCaption(2) : $this->activo->FldTagValue(2));
			$this->activo->EditValue = $arwrk;

			// Edit refer script
			// usuario

			$this->usuario->HrefValue = "";

			// contrasenia
			$this->contrasenia->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// cel
			$this->cel->HrefValue = "";

			// activo
			$this->activo->HrefValue = "";
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
		if (!$this->usuario->FldIsDetailKey && !is_null($this->usuario->FormValue) && $this->usuario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->usuario->FldCaption(), $this->usuario->ReqErrMsg));
		}
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!ew_CheckEmail($this->_email->FormValue)) {
			ew_AddMessage($gsFormError, $this->_email->FldErrMsg());
		}
		if (!ew_CheckPhone($this->cel->FormValue)) {
			ew_AddMessage($gsFormError, $this->cel->FldErrMsg());
		}
		if ($this->activo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->activo->FldCaption(), $this->activo->ReqErrMsg));
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
		if ($this->usuario->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(usuario = '" . ew_AdjustSql($this->usuario->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->usuario->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->usuario->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// usuario
		$this->usuario->SetDbValueDef($rsnew, $this->usuario->CurrentValue, "", FALSE);

		// contrasenia
		$this->contrasenia->SetDbValueDef($rsnew, $this->contrasenia->CurrentValue, NULL, FALSE);

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// cel
		$this->cel->SetDbValueDef($rsnew, $this->cel->CurrentValue, NULL, FALSE);

		// activo
		$this->activo->SetDbValueDef($rsnew, $this->activo->CurrentValue, 0, strval($this->activo->CurrentValue) == "");

		// id
		if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin
			$rsnew['id'] = CurrentUserID();
		}

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
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->id->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "cciag_usuariolist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'usuario';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'usuario';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				if (!EW_ENCRYPTED_PASSWORD && $fldname == 'contrasenia')
					$newvalue = ew_EncryptPassword($newvalue);
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
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
if (!isset($usuario_add)) $usuario_add = new cusuario_add();

// Page init
$usuario_add->Page_Init();

// Page main
$usuario_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$usuario_add->Page_Render();
?>
<?php include_once "cciag_header.php" ?>
<script type="text/javascript">

// Page object
var usuario_add = new ew_Page("usuario_add");
usuario_add.PageID = "add"; // Page ID
var EW_PAGE_ID = usuario_add.PageID; // For backward compatibility

// Form object
var fusuarioadd = new ew_Form("fusuarioadd");

// Validate form
fusuarioadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_usuario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->usuario->FldCaption(), $usuario->usuario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->nombre->FldCaption(), $usuario->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->_email->FldCaption(), $usuario->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($usuario->_email->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cel");
			if (elm && !ew_CheckPhone(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($usuario->cel->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_activo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->activo->FldCaption(), $usuario->activo->ReqErrMsg)) ?>");

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
fusuarioadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusuarioadd.ValidateRequired = true;
<?php } else { ?>
fusuarioadd.ValidateRequired = false; 
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
<?php $usuario_add->ShowPageHeader(); ?>
<?php
$usuario_add->ShowMessage();
?>
<form name="fusuarioadd" id="fusuarioadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($usuario_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $usuario_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="usuario">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($usuario->usuario->Visible) { // usuario ?>
	<div id="r_usuario" class="form-group">
		<label id="elh_usuario_usuario" for="x_usuario" class="col-sm-2 control-label ewLabel"><?php echo $usuario->usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->usuario->CellAttributes() ?>>
<span id="el_usuario_usuario">
<input type="text" data-field="x_usuario" name="x_usuario" id="x_usuario" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($usuario->usuario->PlaceHolder) ?>" value="<?php echo $usuario->usuario->EditValue ?>"<?php echo $usuario->usuario->EditAttributes() ?>>
</span>
<?php echo $usuario->usuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($usuario->contrasenia->Visible) { // contrasenia ?>
	<div id="r_contrasenia" class="form-group">
		<label id="elh_usuario_contrasenia" for="x_contrasenia" class="col-sm-2 control-label ewLabel"><?php echo $usuario->contrasenia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->contrasenia->CellAttributes() ?>>
<span id="el_usuario_contrasenia">
<input type="password" data-field="x_contrasenia" name="x_contrasenia" id="x_contrasenia" size="50" maxlength="10" placeholder="<?php echo ew_HtmlEncode($usuario->contrasenia->PlaceHolder) ?>"<?php echo $usuario->contrasenia->EditAttributes() ?>>
</span>
<?php echo $usuario->contrasenia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($usuario->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_usuario_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $usuario->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->nombre->CellAttributes() ?>>
<span id="el_usuario_nombre">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($usuario->nombre->PlaceHolder) ?>" value="<?php echo $usuario->nombre->EditValue ?>"<?php echo $usuario->nombre->EditAttributes() ?>>
</span>
<?php echo $usuario->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($usuario->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_usuario__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $usuario->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->_email->CellAttributes() ?>>
<span id="el_usuario__email">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($usuario->_email->PlaceHolder) ?>" value="<?php echo $usuario->_email->EditValue ?>"<?php echo $usuario->_email->EditAttributes() ?>>
</span>
<?php echo $usuario->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($usuario->cel->Visible) { // cel ?>
	<div id="r_cel" class="form-group">
		<label id="elh_usuario_cel" for="x_cel" class="col-sm-2 control-label ewLabel"><?php echo $usuario->cel->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->cel->CellAttributes() ?>>
<span id="el_usuario_cel">
<input type="text" data-field="x_cel" name="x_cel" id="x_cel" size="30" maxlength="16" placeholder="<?php echo ew_HtmlEncode($usuario->cel->PlaceHolder) ?>" value="<?php echo $usuario->cel->EditValue ?>"<?php echo $usuario->cel->EditAttributes() ?>>
</span>
<?php echo $usuario->cel->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($usuario->activo->Visible) { // activo ?>
	<div id="r_activo" class="form-group">
		<label id="elh_usuario_activo" class="col-sm-2 control-label ewLabel"><?php echo $usuario->activo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->activo->CellAttributes() ?>>
<span id="el_usuario_activo">
<div id="tp_x_activo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_activo" id="x_activo" value="{value}"<?php echo $usuario->activo->EditAttributes() ?>></div>
<div id="dsl_x_activo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $usuario->activo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($usuario->activo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activo" name="x_activo" id="x_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $usuario->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $usuario->activo->CustomMsg ?></div></div>
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
fusuarioadd.Init();
</script>
<?php
$usuario_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "cciag_footer.php" ?>
<?php
$usuario_add->Page_Terminate();
?>
