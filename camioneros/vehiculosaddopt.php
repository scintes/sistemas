<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "vehiculosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$vehiculos_addopt = NULL; // Initialize page object first

class cvehiculos_addopt extends cvehiculos {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'vehiculos';

	// Page object name
	var $PageObjName = 'vehiculos_addopt';

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

		// Table object (vehiculos)
		if (!isset($GLOBALS["vehiculos"]) || get_class($GLOBALS["vehiculos"]) == "cvehiculos") {
			$GLOBALS["vehiculos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vehiculos"];
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
			define("EW_TABLE_NAME", 'vehiculos', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("vehiculoslist.php"));
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
		global $EW_EXPORT, $vehiculos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($vehiculos);
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
					$row["x_Patente"] = $this->Patente->DbValue;
					$row["x_cantidad_rueda"] = $this->cantidad_rueda->DbValue;
					$row["x_nombre"] = $this->nombre->DbValue;
					$row["x_modelo"] = $this->modelo->DbValue;
					$row["x_id_chofer"] = $this->id_chofer->DbValue;
					$row["x_id_guarda"] = $this->id_guarda->DbValue;
					$row["x_id_marca"] = $this->id_marca->DbValue;
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
		$this->Patente->CurrentValue = NULL;
		$this->Patente->OldValue = $this->Patente->CurrentValue;
		$this->cantidad_rueda->CurrentValue = NULL;
		$this->cantidad_rueda->OldValue = $this->cantidad_rueda->CurrentValue;
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->modelo->CurrentValue = NULL;
		$this->modelo->OldValue = $this->modelo->CurrentValue;
		$this->id_chofer->CurrentValue = NULL;
		$this->id_chofer->OldValue = $this->id_chofer->CurrentValue;
		$this->id_guarda->CurrentValue = NULL;
		$this->id_guarda->OldValue = $this->id_guarda->CurrentValue;
		$this->id_marca->CurrentValue = NULL;
		$this->id_marca->OldValue = $this->id_marca->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Patente->FldIsDetailKey) {
			$this->Patente->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_Patente")));
		}
		if (!$this->cantidad_rueda->FldIsDetailKey) {
			$this->cantidad_rueda->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_cantidad_rueda")));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nombre")));
		}
		if (!$this->modelo->FldIsDetailKey) {
			$this->modelo->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_modelo")));
		}
		if (!$this->id_chofer->FldIsDetailKey) {
			$this->id_chofer->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_chofer")));
		}
		if (!$this->id_guarda->FldIsDetailKey) {
			$this->id_guarda->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_guarda")));
		}
		if (!$this->id_marca->FldIsDetailKey) {
			$this->id_marca->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_marca")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->Patente->CurrentValue = ew_ConvertToUtf8($this->Patente->FormValue);
		$this->cantidad_rueda->CurrentValue = ew_ConvertToUtf8($this->cantidad_rueda->FormValue);
		$this->nombre->CurrentValue = ew_ConvertToUtf8($this->nombre->FormValue);
		$this->modelo->CurrentValue = ew_ConvertToUtf8($this->modelo->FormValue);
		$this->id_chofer->CurrentValue = ew_ConvertToUtf8($this->id_chofer->FormValue);
		$this->id_guarda->CurrentValue = ew_ConvertToUtf8($this->id_guarda->FormValue);
		$this->id_marca->CurrentValue = ew_ConvertToUtf8($this->id_marca->FormValue);
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
		$this->Patente->setDbValue($rs->fields('Patente'));
		$this->cantidad_rueda->setDbValue($rs->fields('cantidad_rueda'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->modelo->setDbValue($rs->fields('modelo'));
		$this->id_chofer->setDbValue($rs->fields('id_chofer'));
		if (array_key_exists('EV__id_chofer', $rs->fields)) {
			$this->id_chofer->VirtualValue = $rs->fields('EV__id_chofer'); // Set up virtual field value
		} else {
			$this->id_chofer->VirtualValue = ""; // Clear value
		}
		$this->id_guarda->setDbValue($rs->fields('id_guarda'));
		if (array_key_exists('EV__id_guarda', $rs->fields)) {
			$this->id_guarda->VirtualValue = $rs->fields('EV__id_guarda'); // Set up virtual field value
		} else {
			$this->id_guarda->VirtualValue = ""; // Clear value
		}
		$this->id_marca->setDbValue($rs->fields('id_marca'));
		if (array_key_exists('EV__id_marca', $rs->fields)) {
			$this->id_marca->VirtualValue = $rs->fields('EV__id_marca'); // Set up virtual field value
		} else {
			$this->id_marca->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->Patente->DbValue = $row['Patente'];
		$this->cantidad_rueda->DbValue = $row['cantidad_rueda'];
		$this->nombre->DbValue = $row['nombre'];
		$this->modelo->DbValue = $row['modelo'];
		$this->id_chofer->DbValue = $row['id_chofer'];
		$this->id_guarda->DbValue = $row['id_guarda'];
		$this->id_marca->DbValue = $row['id_marca'];
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
		// Patente
		// cantidad_rueda
		// nombre
		// modelo
		// id_chofer
		// id_guarda
		// id_marca

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// Patente
			$this->Patente->ViewValue = $this->Patente->CurrentValue;
			$this->Patente->ViewCustomAttributes = "";

			// cantidad_rueda
			$this->cantidad_rueda->ViewValue = $this->cantidad_rueda->CurrentValue;
			$this->cantidad_rueda->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// modelo
			$this->modelo->ViewValue = $this->modelo->CurrentValue;
			$this->modelo->ViewCustomAttributes = "";

			// id_chofer
			if ($this->id_chofer->VirtualValue <> "") {
				$this->id_chofer->ViewValue = $this->id_chofer->VirtualValue;
			} else {
			if (strval($this->id_chofer->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_chofer->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `choferes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_chofer, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_chofer->ViewValue = $rswrk->fields('DispFld');
					$this->id_chofer->ViewValue .= ew_ValueSeparator(1,$this->id_chofer) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_chofer->ViewValue = $this->id_chofer->CurrentValue;
				}
			} else {
				$this->id_chofer->ViewValue = NULL;
			}
			}
			$this->id_chofer->ViewCustomAttributes = "";

			// id_guarda
			if ($this->id_guarda->VirtualValue <> "") {
				$this->id_guarda->ViewValue = $this->id_guarda->VirtualValue;
			} else {
			if (strval($this->id_guarda->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_guarda->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `choferes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_guarda, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_guarda->ViewValue = $rswrk->fields('DispFld');
					$this->id_guarda->ViewValue .= ew_ValueSeparator(1,$this->id_guarda) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_guarda->ViewValue = $this->id_guarda->CurrentValue;
				}
			} else {
				$this->id_guarda->ViewValue = NULL;
			}
			}
			$this->id_guarda->ViewCustomAttributes = "";

			// id_marca
			if ($this->id_marca->VirtualValue <> "") {
				$this->id_marca->ViewValue = $this->id_marca->VirtualValue;
			} else {
			if (strval($this->id_marca->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_marca->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `marca` AS `DispFld`, `modelo` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_marca, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `marca` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_marca->ViewValue = $rswrk->fields('DispFld');
					$this->id_marca->ViewValue .= ew_ValueSeparator(1,$this->id_marca) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_marca->ViewValue = $this->id_marca->CurrentValue;
				}
			} else {
				$this->id_marca->ViewValue = NULL;
			}
			}
			$this->id_marca->ViewCustomAttributes = "";

			// Patente
			$this->Patente->LinkCustomAttributes = "";
			$this->Patente->HrefValue = "";
			$this->Patente->TooltipValue = "";

			// cantidad_rueda
			$this->cantidad_rueda->LinkCustomAttributes = "";
			$this->cantidad_rueda->HrefValue = "";
			$this->cantidad_rueda->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// modelo
			$this->modelo->LinkCustomAttributes = "";
			$this->modelo->HrefValue = "";
			$this->modelo->TooltipValue = "";

			// id_chofer
			$this->id_chofer->LinkCustomAttributes = "";
			$this->id_chofer->HrefValue = "";
			$this->id_chofer->TooltipValue = "";

			// id_guarda
			$this->id_guarda->LinkCustomAttributes = "";
			$this->id_guarda->HrefValue = "";
			$this->id_guarda->TooltipValue = "";

			// id_marca
			$this->id_marca->LinkCustomAttributes = "";
			$this->id_marca->HrefValue = "";
			$this->id_marca->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Patente
			$this->Patente->EditAttrs["class"] = "form-control";
			$this->Patente->EditCustomAttributes = "";
			$this->Patente->EditValue = ew_HtmlEncode($this->Patente->CurrentValue);
			$this->Patente->PlaceHolder = ew_RemoveHtml($this->Patente->FldCaption());

			// cantidad_rueda
			$this->cantidad_rueda->EditAttrs["class"] = "form-control";
			$this->cantidad_rueda->EditCustomAttributes = "";
			$this->cantidad_rueda->EditValue = ew_HtmlEncode($this->cantidad_rueda->CurrentValue);
			$this->cantidad_rueda->PlaceHolder = ew_RemoveHtml($this->cantidad_rueda->FldCaption());

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// modelo
			$this->modelo->EditAttrs["class"] = "form-control";
			$this->modelo->EditCustomAttributes = "";
			$this->modelo->EditValue = ew_HtmlEncode($this->modelo->CurrentValue);
			$this->modelo->PlaceHolder = ew_RemoveHtml($this->modelo->FldCaption());

			// id_chofer
			$this->id_chofer->EditAttrs["class"] = "form-control";
			$this->id_chofer->EditCustomAttributes = "";
			if (trim(strval($this->id_chofer->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_chofer->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `choferes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_chofer, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_chofer->EditValue = $arwrk;

			// id_guarda
			$this->id_guarda->EditAttrs["class"] = "form-control";
			$this->id_guarda->EditCustomAttributes = "";
			if (trim(strval($this->id_guarda->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_guarda->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `choferes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_guarda, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_guarda->EditValue = $arwrk;

			// id_marca
			$this->id_marca->EditAttrs["class"] = "form-control";
			$this->id_marca->EditCustomAttributes = "";
			if (trim(strval($this->id_marca->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_marca->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `marca` AS `DispFld`, `modelo` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `marcas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_marca, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `marca` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_marca->EditValue = $arwrk;

			// Edit refer script
			// Patente

			$this->Patente->HrefValue = "";

			// cantidad_rueda
			$this->cantidad_rueda->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// modelo
			$this->modelo->HrefValue = "";

			// id_chofer
			$this->id_chofer->HrefValue = "";

			// id_guarda
			$this->id_guarda->HrefValue = "";

			// id_marca
			$this->id_marca->HrefValue = "";
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
		if (!ew_CheckInteger($this->cantidad_rueda->FormValue)) {
			ew_AddMessage($gsFormError, $this->cantidad_rueda->FldErrMsg());
		}
		if (!ew_CheckInteger($this->modelo->FormValue)) {
			ew_AddMessage($gsFormError, $this->modelo->FldErrMsg());
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

		// Patente
		$this->Patente->SetDbValueDef($rsnew, $this->Patente->CurrentValue, NULL, FALSE);

		// cantidad_rueda
		$this->cantidad_rueda->SetDbValueDef($rsnew, $this->cantidad_rueda->CurrentValue, NULL, FALSE);

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, FALSE);

		// modelo
		$this->modelo->SetDbValueDef($rsnew, $this->modelo->CurrentValue, NULL, FALSE);

		// id_chofer
		$this->id_chofer->SetDbValueDef($rsnew, $this->id_chofer->CurrentValue, NULL, FALSE);

		// id_guarda
		$this->id_guarda->SetDbValueDef($rsnew, $this->id_guarda->CurrentValue, NULL, FALSE);

		// id_marca
		$this->id_marca->SetDbValueDef($rsnew, $this->id_marca->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, "vehiculoslist.php", "", $this->TableVar, TRUE);
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
if (!isset($vehiculos_addopt)) $vehiculos_addopt = new cvehiculos_addopt();

// Page init
$vehiculos_addopt->Page_Init();

// Page main
$vehiculos_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vehiculos_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var vehiculos_addopt = new ew_Page("vehiculos_addopt");
vehiculos_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = vehiculos_addopt.PageID; // For backward compatibility

// Form object
var fvehiculosaddopt = new ew_Form("fvehiculosaddopt");

// Validate form
fvehiculosaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_cantidad_rueda");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($vehiculos->cantidad_rueda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modelo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($vehiculos->modelo->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fvehiculosaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvehiculosaddopt.ValidateRequired = true;
<?php } else { ?>
fvehiculosaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvehiculosaddopt.Lists["x_id_chofer"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fvehiculosaddopt.Lists["x_id_guarda"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fvehiculosaddopt.Lists["x_id_marca"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_marca","x_modelo","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$vehiculos_addopt->ShowMessage();
?>
<form name="fvehiculosaddopt" id="fvehiculosaddopt" class="ewForm form-horizontal" action="vehiculosaddopt.php" method="post">
<?php if ($vehiculos_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vehiculos_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vehiculos">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_Patente"><?php echo $vehiculos->Patente->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_Patente" name="x_Patente" id="x_Patente" size="30" maxlength="7" placeholder="<?php echo ew_HtmlEncode($vehiculos->Patente->PlaceHolder) ?>" value="<?php echo $vehiculos->Patente->EditValue ?>"<?php echo $vehiculos->Patente->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_cantidad_rueda"><?php echo $vehiculos->cantidad_rueda->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_cantidad_rueda" name="x_cantidad_rueda" id="x_cantidad_rueda" size="30" placeholder="<?php echo ew_HtmlEncode($vehiculos->cantidad_rueda->PlaceHolder) ?>" value="<?php echo $vehiculos->cantidad_rueda->EditValue ?>"<?php echo $vehiculos->cantidad_rueda->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_nombre"><?php echo $vehiculos->nombre->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($vehiculos->nombre->PlaceHolder) ?>" value="<?php echo $vehiculos->nombre->EditValue ?>"<?php echo $vehiculos->nombre->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_modelo"><?php echo $vehiculos->modelo->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-field="x_modelo" name="x_modelo" id="x_modelo" size="30" placeholder="<?php echo ew_HtmlEncode($vehiculos->modelo->PlaceHolder) ?>" value="<?php echo $vehiculos->modelo->EditValue ?>"<?php echo $vehiculos->modelo->EditAttributes() ?>>
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_id_chofer"><?php echo $vehiculos->id_chofer->FldCaption() ?></label>
		<div class="col-sm-9">
<select data-field="x_id_chofer" id="x_id_chofer" name="x_id_chofer"<?php echo $vehiculos->id_chofer->EditAttributes() ?>>
<?php
if (is_array($vehiculos->id_chofer->EditValue)) {
	$arwrk = $vehiculos->id_chofer->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($vehiculos->id_chofer->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$vehiculos->id_chofer) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `choferes`";
$sWhereWrk = "";

// Call Lookup selecting
$vehiculos->Lookup_Selecting($vehiculos->id_chofer, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x_id_chofer" id="s_x_id_chofer" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_id_guarda"><?php echo $vehiculos->id_guarda->FldCaption() ?></label>
		<div class="col-sm-9">
<select data-field="x_id_guarda" id="x_id_guarda" name="x_id_guarda"<?php echo $vehiculos->id_guarda->EditAttributes() ?>>
<?php
if (is_array($vehiculos->id_guarda->EditValue)) {
	$arwrk = $vehiculos->id_guarda->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($vehiculos->id_guarda->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$vehiculos->id_guarda) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `choferes`";
$sWhereWrk = "";

// Call Lookup selecting
$vehiculos->Lookup_Selecting($vehiculos->id_guarda, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x_id_guarda" id="s_x_id_guarda" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_id_marca"><?php echo $vehiculos->id_marca->FldCaption() ?></label>
		<div class="col-sm-9">
<select data-field="x_id_marca" id="x_id_marca" name="x_id_marca"<?php echo $vehiculos->id_marca->EditAttributes() ?>>
<?php
if (is_array($vehiculos->id_marca->EditValue)) {
	$arwrk = $vehiculos->id_marca->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($vehiculos->id_marca->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$vehiculos->id_marca) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `codigo`, `marca` AS `DispFld`, `modelo` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
$sWhereWrk = "";

// Call Lookup selecting
$vehiculos->Lookup_Selecting($vehiculos->id_marca, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `marca` ASC";
?>
<input type="hidden" name="s_x_id_marca" id="s_x_id_marca" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</div>
	</div>
</form>
<script type="text/javascript">
fvehiculosaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$vehiculos_addopt->Page_Terminate();
?>
