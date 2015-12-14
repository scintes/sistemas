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

$nivel_permisos_usuario_edit = NULL; // Initialize page object first

class cnivel_permisos_usuario_edit extends cnivel_permisos_usuario {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'nivel_permisos_usuario';

	// Page object name
	var $PageObjName = 'nivel_permisos_usuario_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (@$_GET["id_nivel_usuario"] <> "") {
			$this->id_nivel_usuario->setQueryStringValue($_GET["id_nivel_usuario"]);
			$this->RecKey["id_nivel_usuario"] = $this->id_nivel_usuario->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}
		if (@$_GET["nombre_tabla"] <> "") {
			$this->nombre_tabla->setQueryStringValue($_GET["nombre_tabla"]);
			$this->RecKey["nombre_tabla"] = $this->nombre_tabla->QueryStringValue;
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
			$this->Page_Terminate("nivel_permisos_usuariolist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->id_nivel_usuario->CurrentValue) == strval($this->Recordset->fields('id_nivel_usuario')) && strval($this->nombre_tabla->CurrentValue) == strval($this->Recordset->fields('nombre_tabla'))) {
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
					$this->Page_Terminate("nivel_permisos_usuariolist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
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
		global $objForm, $Language;

		// Get upload data
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
		$this->LoadRow();
		$this->id_nivel_usuario->CurrentValue = $this->id_nivel_usuario->FormValue;
		$this->nombre_tabla->CurrentValue = $this->nombre_tabla->FormValue;
		$this->permisos->CurrentValue = $this->permisos->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_nivel_usuario
			$this->id_nivel_usuario->EditAttrs["class"] = "form-control";
			$this->id_nivel_usuario->EditCustomAttributes = "";
			$this->id_nivel_usuario->EditValue = $this->id_nivel_usuario->CurrentValue;
			$this->id_nivel_usuario->ViewCustomAttributes = "";

			// nombre_tabla
			$this->nombre_tabla->EditAttrs["class"] = "form-control";
			$this->nombre_tabla->EditCustomAttributes = "";
			$this->nombre_tabla->EditValue = $this->nombre_tabla->CurrentValue;
			$this->nombre_tabla->ViewCustomAttributes = "";

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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// id_nivel_usuario
			// nombre_tabla
			// permisos

			$this->permisos->SetDbValueDef($rsnew, $this->permisos->CurrentValue, 0, $this->permisos->ReadOnly);

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
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "nivel_permisos_usuariolist.php", "", $this->TableVar, TRUE);
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
if (!isset($nivel_permisos_usuario_edit)) $nivel_permisos_usuario_edit = new cnivel_permisos_usuario_edit();

// Page init
$nivel_permisos_usuario_edit->Page_Init();

// Page main
$nivel_permisos_usuario_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nivel_permisos_usuario_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var nivel_permisos_usuario_edit = new ew_Page("nivel_permisos_usuario_edit");
nivel_permisos_usuario_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = nivel_permisos_usuario_edit.PageID; // For backward compatibility

// Form object
var fnivel_permisos_usuarioedit = new ew_Form("fnivel_permisos_usuarioedit");

// Validate form
fnivel_permisos_usuarioedit.Validate = function() {
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
fnivel_permisos_usuarioedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnivel_permisos_usuarioedit.ValidateRequired = true;
<?php } else { ?>
fnivel_permisos_usuarioedit.ValidateRequired = false; 
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
<?php $nivel_permisos_usuario_edit->ShowPageHeader(); ?>
<?php
$nivel_permisos_usuario_edit->ShowMessage();
?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($nivel_permisos_usuario_edit->Pager)) $nivel_permisos_usuario_edit->Pager = new cNumericPager($nivel_permisos_usuario_edit->StartRec, $nivel_permisos_usuario_edit->DisplayRecs, $nivel_permisos_usuario_edit->TotalRecs, $nivel_permisos_usuario_edit->RecRange) ?>
<?php if ($nivel_permisos_usuario_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($nivel_permisos_usuario_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $nivel_permisos_usuario_edit->PageUrl() ?>start=<?php echo $nivel_permisos_usuario_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($nivel_permisos_usuario_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $nivel_permisos_usuario_edit->PageUrl() ?>start=<?php echo $nivel_permisos_usuario_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($nivel_permisos_usuario_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $nivel_permisos_usuario_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($nivel_permisos_usuario_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $nivel_permisos_usuario_edit->PageUrl() ?>start=<?php echo $nivel_permisos_usuario_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($nivel_permisos_usuario_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $nivel_permisos_usuario_edit->PageUrl() ?>start=<?php echo $nivel_permisos_usuario_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<form name="fnivel_permisos_usuarioedit" id="fnivel_permisos_usuarioedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($nivel_permisos_usuario_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $nivel_permisos_usuario_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="nivel_permisos_usuario">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($nivel_permisos_usuario->id_nivel_usuario->Visible) { // id_nivel_usuario ?>
	<div id="r_id_nivel_usuario" class="form-group">
		<label id="elh_nivel_permisos_usuario_id_nivel_usuario" for="x_id_nivel_usuario" class="col-sm-2 control-label ewLabel"><?php echo $nivel_permisos_usuario->id_nivel_usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $nivel_permisos_usuario->id_nivel_usuario->CellAttributes() ?>>
<span id="el_nivel_permisos_usuario_id_nivel_usuario">
<span<?php echo $nivel_permisos_usuario->id_nivel_usuario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $nivel_permisos_usuario->id_nivel_usuario->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_nivel_usuario" name="x_id_nivel_usuario" id="x_id_nivel_usuario" value="<?php echo ew_HtmlEncode($nivel_permisos_usuario->id_nivel_usuario->CurrentValue) ?>">
<?php echo $nivel_permisos_usuario->id_nivel_usuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($nivel_permisos_usuario->nombre_tabla->Visible) { // nombre_tabla ?>
	<div id="r_nombre_tabla" class="form-group">
		<label id="elh_nivel_permisos_usuario_nombre_tabla" for="x_nombre_tabla" class="col-sm-2 control-label ewLabel"><?php echo $nivel_permisos_usuario->nombre_tabla->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $nivel_permisos_usuario->nombre_tabla->CellAttributes() ?>>
<span id="el_nivel_permisos_usuario_nombre_tabla">
<span<?php echo $nivel_permisos_usuario->nombre_tabla->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $nivel_permisos_usuario->nombre_tabla->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_nombre_tabla" name="x_nombre_tabla" id="x_nombre_tabla" value="<?php echo ew_HtmlEncode($nivel_permisos_usuario->nombre_tabla->CurrentValue) ?>">
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
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
<?php if (!isset($nivel_permisos_usuario_edit->Pager)) $nivel_permisos_usuario_edit->Pager = new cNumericPager($nivel_permisos_usuario_edit->StartRec, $nivel_permisos_usuario_edit->DisplayRecs, $nivel_permisos_usuario_edit->TotalRecs, $nivel_permisos_usuario_edit->RecRange) ?>
<?php if ($nivel_permisos_usuario_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($nivel_permisos_usuario_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $nivel_permisos_usuario_edit->PageUrl() ?>start=<?php echo $nivel_permisos_usuario_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($nivel_permisos_usuario_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $nivel_permisos_usuario_edit->PageUrl() ?>start=<?php echo $nivel_permisos_usuario_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($nivel_permisos_usuario_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $nivel_permisos_usuario_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($nivel_permisos_usuario_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $nivel_permisos_usuario_edit->PageUrl() ?>start=<?php echo $nivel_permisos_usuario_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($nivel_permisos_usuario_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $nivel_permisos_usuario_edit->PageUrl() ?>start=<?php echo $nivel_permisos_usuario_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<script type="text/javascript">
fnivel_permisos_usuarioedit.Init();
</script>
<?php
$nivel_permisos_usuario_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$nivel_permisos_usuario_edit->Page_Terminate();
?>
