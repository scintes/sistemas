<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "clientesinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$clientes_add = NULL; // Initialize page object first

class cclientes_add extends cclientes {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'clientes';

	// Page object name
	var $PageObjName = 'clientes_add';

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

		// Table object (clientes)
		if (!isset($GLOBALS["clientes"]) || get_class($GLOBALS["clientes"]) == "cclientes") {
			$GLOBALS["clientes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["clientes"];
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
			define("EW_TABLE_NAME", 'clientes', TRUE);

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
		global $EW_EXPORT, $clientes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($clientes);
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
			if (@$_GET["codigo"] != "") {
				$this->codigo->setQueryStringValue($_GET["codigo"]);
				$this->setKey("codigo", $this->codigo->CurrentValue); // Set up key
			} else {
				$this->setKey("codigo", ""); // Clear key
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
					$this->Page_Terminate("clienteslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "clientesview.php")
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
		$this->cuit_cuil->CurrentValue = NULL;
		$this->cuit_cuil->OldValue = $this->cuit_cuil->CurrentValue;
		$this->razon_social->CurrentValue = NULL;
		$this->razon_social->OldValue = $this->razon_social->CurrentValue;
		$this->responsable->CurrentValue = NULL;
		$this->responsable->OldValue = $this->responsable->CurrentValue;
		$this->tel->CurrentValue = NULL;
		$this->tel->OldValue = $this->tel->CurrentValue;
		$this->cel->CurrentValue = NULL;
		$this->cel->OldValue = $this->cel->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->id_localidad->CurrentValue = NULL;
		$this->id_localidad->OldValue = $this->id_localidad->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->cuit_cuil->FldIsDetailKey) {
			$this->cuit_cuil->setFormValue($objForm->GetValue("x_cuit_cuil"));
		}
		if (!$this->razon_social->FldIsDetailKey) {
			$this->razon_social->setFormValue($objForm->GetValue("x_razon_social"));
		}
		if (!$this->responsable->FldIsDetailKey) {
			$this->responsable->setFormValue($objForm->GetValue("x_responsable"));
		}
		if (!$this->tel->FldIsDetailKey) {
			$this->tel->setFormValue($objForm->GetValue("x_tel"));
		}
		if (!$this->cel->FldIsDetailKey) {
			$this->cel->setFormValue($objForm->GetValue("x_cel"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->id_localidad->FldIsDetailKey) {
			$this->id_localidad->setFormValue($objForm->GetValue("x_id_localidad"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->cuit_cuil->CurrentValue = $this->cuit_cuil->FormValue;
		$this->razon_social->CurrentValue = $this->razon_social->FormValue;
		$this->responsable->CurrentValue = $this->responsable->FormValue;
		$this->tel->CurrentValue = $this->tel->FormValue;
		$this->cel->CurrentValue = $this->cel->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->id_localidad->CurrentValue = $this->id_localidad->FormValue;
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
		$this->cuit_cuil->setDbValue($rs->fields('cuit_cuil'));
		$this->razon_social->setDbValue($rs->fields('razon_social'));
		$this->responsable->setDbValue($rs->fields('responsable'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->id_localidad->setDbValue($rs->fields('id_localidad'));
		if (array_key_exists('EV__id_localidad', $rs->fields)) {
			$this->id_localidad->VirtualValue = $rs->fields('EV__id_localidad'); // Set up virtual field value
		} else {
			$this->id_localidad->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->cuit_cuil->DbValue = $row['cuit_cuil'];
		$this->razon_social->DbValue = $row['razon_social'];
		$this->responsable->DbValue = $row['responsable'];
		$this->tel->DbValue = $row['tel'];
		$this->cel->DbValue = $row['cel'];
		$this->_email->DbValue = $row['email'];
		$this->direccion->DbValue = $row['direccion'];
		$this->id_localidad->DbValue = $row['id_localidad'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("codigo")) <> "")
			$this->codigo->CurrentValue = $this->getKey("codigo"); // codigo
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
		// codigo
		// cuit_cuil
		// razon_social
		// responsable
		// tel
		// cel
		// email
		// direccion
		// id_localidad

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// cuit_cuil
			$this->cuit_cuil->ViewValue = $this->cuit_cuil->CurrentValue;
			$this->cuit_cuil->ViewCustomAttributes = "";

			// razon_social
			$this->razon_social->ViewValue = $this->razon_social->CurrentValue;
			$this->razon_social->ViewCustomAttributes = "";

			// responsable
			$this->responsable->ViewValue = $this->responsable->CurrentValue;
			$this->responsable->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->ViewCustomAttributes = "";

			// id_localidad
			if ($this->id_localidad->VirtualValue <> "") {
				$this->id_localidad->ViewValue = $this->id_localidad->VirtualValue;
			} else {
			if (strval($this->id_localidad->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `localidad` AS `DispFld`, `cp` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_localidad, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `localidad` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_localidad->ViewValue = $rswrk->fields('DispFld');
					$this->id_localidad->ViewValue .= ew_ValueSeparator(1,$this->id_localidad) . $rswrk->fields('Disp2Fld');
					$this->id_localidad->ViewValue .= ew_ValueSeparator(2,$this->id_localidad) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_localidad->ViewValue = $this->id_localidad->CurrentValue;
				}
			} else {
				$this->id_localidad->ViewValue = NULL;
			}
			}
			$this->id_localidad->ViewCustomAttributes = "";

			// cuit_cuil
			$this->cuit_cuil->LinkCustomAttributes = "";
			$this->cuit_cuil->HrefValue = "";
			$this->cuit_cuil->TooltipValue = "";

			// razon_social
			$this->razon_social->LinkCustomAttributes = "";
			$this->razon_social->HrefValue = "";
			$this->razon_social->TooltipValue = "";

			// responsable
			$this->responsable->LinkCustomAttributes = "";
			$this->responsable->HrefValue = "";
			$this->responsable->TooltipValue = "";

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

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// id_localidad
			$this->id_localidad->LinkCustomAttributes = "";
			$this->id_localidad->HrefValue = "";
			$this->id_localidad->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// cuit_cuil
			$this->cuit_cuil->EditAttrs["class"] = "form-control";
			$this->cuit_cuil->EditCustomAttributes = "";
			$this->cuit_cuil->EditValue = ew_HtmlEncode($this->cuit_cuil->CurrentValue);
			$this->cuit_cuil->PlaceHolder = ew_RemoveHtml($this->cuit_cuil->FldCaption());

			// razon_social
			$this->razon_social->EditAttrs["class"] = "form-control";
			$this->razon_social->EditCustomAttributes = "";
			$this->razon_social->EditValue = ew_HtmlEncode($this->razon_social->CurrentValue);
			$this->razon_social->PlaceHolder = ew_RemoveHtml($this->razon_social->FldCaption());

			// responsable
			$this->responsable->EditAttrs["class"] = "form-control";
			$this->responsable->EditCustomAttributes = "";
			$this->responsable->EditValue = ew_HtmlEncode($this->responsable->CurrentValue);
			$this->responsable->PlaceHolder = ew_RemoveHtml($this->responsable->FldCaption());

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

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// id_localidad
			$this->id_localidad->EditAttrs["class"] = "form-control";
			$this->id_localidad->EditCustomAttributes = "";
			if (trim(strval($this->id_localidad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `localidad` AS `DispFld`, `cp` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `v_localidad_provincia`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_localidad, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `localidad` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_localidad->EditValue = $arwrk;

			// Edit refer script
			// cuit_cuil

			$this->cuit_cuil->HrefValue = "";

			// razon_social
			$this->razon_social->HrefValue = "";

			// responsable
			$this->responsable->HrefValue = "";

			// tel
			$this->tel->HrefValue = "";

			// cel
			$this->cel->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// id_localidad
			$this->id_localidad->HrefValue = "";
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

		// cuit_cuil
		$this->cuit_cuil->SetDbValueDef($rsnew, $this->cuit_cuil->CurrentValue, NULL, FALSE);

		// razon_social
		$this->razon_social->SetDbValueDef($rsnew, $this->razon_social->CurrentValue, NULL, FALSE);

		// responsable
		$this->responsable->SetDbValueDef($rsnew, $this->responsable->CurrentValue, NULL, FALSE);

		// tel
		$this->tel->SetDbValueDef($rsnew, $this->tel->CurrentValue, NULL, FALSE);

		// cel
		$this->cel->SetDbValueDef($rsnew, $this->cel->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, FALSE);

		// id_localidad
		$this->id_localidad->SetDbValueDef($rsnew, $this->id_localidad->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, "clienteslist.php", "", $this->TableVar, TRUE);
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
if (!isset($clientes_add)) $clientes_add = new cclientes_add();

// Page init
$clientes_add->Page_Init();

// Page main
$clientes_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$clientes_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var clientes_add = new ew_Page("clientes_add");
clientes_add.PageID = "add"; // Page ID
var EW_PAGE_ID = clientes_add.PageID; // For backward compatibility

// Form object
var fclientesadd = new ew_Form("fclientesadd");

// Validate form
fclientesadd.Validate = function() {
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
fclientesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclientesadd.ValidateRequired = true;
<?php } else { ?>
fclientesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclientesadd.Lists["x_id_localidad"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_localidad","x_cp","x_provincia",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $clientes_add->ShowPageHeader(); ?>
<?php
$clientes_add->ShowMessage();
?>
<form name="fclientesadd" id="fclientesadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($clientes_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $clientes_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="clientes">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($clientes->cuit_cuil->Visible) { // cuit_cuil ?>
	<div id="r_cuit_cuil" class="form-group">
		<label id="elh_clientes_cuit_cuil" for="x_cuit_cuil" class="col-sm-2 control-label ewLabel"><?php echo $clientes->cuit_cuil->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $clientes->cuit_cuil->CellAttributes() ?>>
<span id="el_clientes_cuit_cuil">
<input type="text" data-field="x_cuit_cuil" name="x_cuit_cuil" id="x_cuit_cuil" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($clientes->cuit_cuil->PlaceHolder) ?>" value="<?php echo $clientes->cuit_cuil->EditValue ?>"<?php echo $clientes->cuit_cuil->EditAttributes() ?>>
</span>
<?php echo $clientes->cuit_cuil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($clientes->razon_social->Visible) { // razon_social ?>
	<div id="r_razon_social" class="form-group">
		<label id="elh_clientes_razon_social" for="x_razon_social" class="col-sm-2 control-label ewLabel"><?php echo $clientes->razon_social->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $clientes->razon_social->CellAttributes() ?>>
<span id="el_clientes_razon_social">
<input type="text" data-field="x_razon_social" name="x_razon_social" id="x_razon_social" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($clientes->razon_social->PlaceHolder) ?>" value="<?php echo $clientes->razon_social->EditValue ?>"<?php echo $clientes->razon_social->EditAttributes() ?>>
</span>
<?php echo $clientes->razon_social->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($clientes->responsable->Visible) { // responsable ?>
	<div id="r_responsable" class="form-group">
		<label id="elh_clientes_responsable" for="x_responsable" class="col-sm-2 control-label ewLabel"><?php echo $clientes->responsable->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $clientes->responsable->CellAttributes() ?>>
<span id="el_clientes_responsable">
<input type="text" data-field="x_responsable" name="x_responsable" id="x_responsable" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($clientes->responsable->PlaceHolder) ?>" value="<?php echo $clientes->responsable->EditValue ?>"<?php echo $clientes->responsable->EditAttributes() ?>>
</span>
<?php echo $clientes->responsable->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($clientes->tel->Visible) { // tel ?>
	<div id="r_tel" class="form-group">
		<label id="elh_clientes_tel" for="x_tel" class="col-sm-2 control-label ewLabel"><?php echo $clientes->tel->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $clientes->tel->CellAttributes() ?>>
<span id="el_clientes_tel">
<input type="text" data-field="x_tel" name="x_tel" id="x_tel" size="30" maxlength="16" placeholder="<?php echo ew_HtmlEncode($clientes->tel->PlaceHolder) ?>" value="<?php echo $clientes->tel->EditValue ?>"<?php echo $clientes->tel->EditAttributes() ?>>
</span>
<?php echo $clientes->tel->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($clientes->cel->Visible) { // cel ?>
	<div id="r_cel" class="form-group">
		<label id="elh_clientes_cel" for="x_cel" class="col-sm-2 control-label ewLabel"><?php echo $clientes->cel->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $clientes->cel->CellAttributes() ?>>
<span id="el_clientes_cel">
<input type="text" data-field="x_cel" name="x_cel" id="x_cel" size="30" maxlength="16" placeholder="<?php echo ew_HtmlEncode($clientes->cel->PlaceHolder) ?>" value="<?php echo $clientes->cel->EditValue ?>"<?php echo $clientes->cel->EditAttributes() ?>>
</span>
<?php echo $clientes->cel->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($clientes->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_clientes__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $clientes->_email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $clientes->_email->CellAttributes() ?>>
<span id="el_clientes__email">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($clientes->_email->PlaceHolder) ?>" value="<?php echo $clientes->_email->EditValue ?>"<?php echo $clientes->_email->EditAttributes() ?>>
</span>
<?php echo $clientes->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($clientes->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_clientes_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $clientes->direccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $clientes->direccion->CellAttributes() ?>>
<span id="el_clientes_direccion">
<input type="text" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($clientes->direccion->PlaceHolder) ?>" value="<?php echo $clientes->direccion->EditValue ?>"<?php echo $clientes->direccion->EditAttributes() ?>>
</span>
<?php echo $clientes->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($clientes->id_localidad->Visible) { // id_localidad ?>
	<div id="r_id_localidad" class="form-group">
		<label id="elh_clientes_id_localidad" for="x_id_localidad" class="col-sm-2 control-label ewLabel"><?php echo $clientes->id_localidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $clientes->id_localidad->CellAttributes() ?>>
<span id="el_clientes_id_localidad">
<select data-field="x_id_localidad" id="x_id_localidad" name="x_id_localidad"<?php echo $clientes->id_localidad->EditAttributes() ?>>
<?php
if (is_array($clientes->id_localidad->EditValue)) {
	$arwrk = $clientes->id_localidad->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($clientes->id_localidad->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$clientes->id_localidad) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$clientes->id_localidad) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `codigo`, `localidad` AS `DispFld`, `cp` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
$sWhereWrk = "";

// Call Lookup selecting
$clientes->Lookup_Selecting($clientes->id_localidad, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `localidad` ASC";
?>
<input type="hidden" name="s_x_id_localidad" id="s_x_id_localidad" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $clientes->id_localidad->CustomMsg ?></div></div>
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
fclientesadd.Init();
</script>
<?php
$clientes_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$clientes_add->Page_Terminate();
?>
