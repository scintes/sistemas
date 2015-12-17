<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_deudasinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_sociosinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_detalle_deudasgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_pagosgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$deudas_add = NULL; // Initialize page object first

class cdeudas_add extends cdeudas {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'deudas';

	// Page object name
	var $PageObjName = 'deudas_add';

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

		// Table object (deudas)
		if (!isset($GLOBALS["deudas"]) || get_class($GLOBALS["deudas"]) == "cdeudas") {
			$GLOBALS["deudas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["deudas"];
		}

		// Table object (socios)
		if (!isset($GLOBALS['socios'])) $GLOBALS['socios'] = new csocios();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'deudas', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("cciag_deudaslist.php"));
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

			// Process auto fill for detail table 'detalle_deudas'
			if (@$_POST["grid"] == "fdetalle_deudasgrid") {
				if (!isset($GLOBALS["detalle_deudas_grid"])) $GLOBALS["detalle_deudas_grid"] = new cdetalle_deudas_grid;
				$GLOBALS["detalle_deudas_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'pagos'
			if (@$_POST["grid"] == "fpagosgrid") {
				if (!isset($GLOBALS["pagos_grid"])) $GLOBALS["pagos_grid"] = new cpagos_grid;
				$GLOBALS["pagos_grid"]->Page_Init();
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
		global $EW_EXPORT, $deudas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($deudas);
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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("cciag_deudaslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "cciag_deudasview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->mes->CurrentValue = NULL;
		$this->mes->OldValue = $this->mes->CurrentValue;
		$this->anio->CurrentValue = NULL;
		$this->anio->OldValue = $this->anio->CurrentValue;
		$this->fecha->CurrentValue = NULL;
		$this->fecha->OldValue = $this->fecha->CurrentValue;
		$this->monto->CurrentValue = NULL;
		$this->monto->OldValue = $this->monto->CurrentValue;
		$this->id_socio->CurrentValue = NULL;
		$this->id_socio->OldValue = $this->id_socio->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->mes->FldIsDetailKey) {
			$this->mes->setFormValue($objForm->GetValue("x_mes"));
		}
		if (!$this->anio->FldIsDetailKey) {
			$this->anio->setFormValue($objForm->GetValue("x_anio"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		if (!$this->monto->FldIsDetailKey) {
			$this->monto->setFormValue($objForm->GetValue("x_monto"));
		}
		if (!$this->id_socio->FldIsDetailKey) {
			$this->id_socio->setFormValue($objForm->GetValue("x_id_socio"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->mes->CurrentValue = $this->mes->FormValue;
		$this->anio->CurrentValue = $this->anio->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->monto->CurrentValue = $this->monto->FormValue;
		$this->id_socio->CurrentValue = $this->id_socio->FormValue;
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
		$this->mes->setDbValue($rs->fields('mes'));
		$this->anio->setDbValue($rs->fields('anio'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->monto->setDbValue($rs->fields('monto'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		$this->id_socio->setDbValue($rs->fields('id_socio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->mes->DbValue = $row['mes'];
		$this->anio->DbValue = $row['anio'];
		$this->fecha->DbValue = $row['fecha'];
		$this->monto->DbValue = $row['monto'];
		$this->id_usuario->DbValue = $row['id_usuario'];
		$this->id_socio->DbValue = $row['id_socio'];
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
		// Convert decimal values if posted back

		if ($this->monto->FormValue == $this->monto->CurrentValue && is_numeric(ew_StrToFloat($this->monto->CurrentValue)))
			$this->monto->CurrentValue = ew_StrToFloat($this->monto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// mes
		// anio
		// fecha
		// monto
		// id_usuario
		// id_socio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewCustomAttributes = "";

			// mes
			$this->mes->ViewValue = $this->mes->CurrentValue;
			$this->mes->ViewCustomAttributes = "";

			// anio
			$this->anio->ViewValue = $this->anio->CurrentValue;
			$this->anio->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// monto
			$this->monto->ViewValue = $this->monto->CurrentValue;
			$this->monto->ViewValue = ew_FormatCurrency($this->monto->ViewValue, 0, -2, -2, -2);
			$this->monto->ViewCustomAttributes = "";

			// id_socio
			if (strval($this->id_socio->CurrentValue) <> "") {
				$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->id_socio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_socio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `propietario` DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_socio->ViewValue = $rswrk->fields('DispFld');
					$this->id_socio->ViewValue .= ew_ValueSeparator(1,$this->id_socio) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_socio->ViewValue = $this->id_socio->CurrentValue;
				}
			} else {
				$this->id_socio->ViewValue = NULL;
			}
			$this->id_socio->ViewCustomAttributes = "";

			// mes
			$this->mes->LinkCustomAttributes = "";
			$this->mes->HrefValue = "";
			$this->mes->TooltipValue = "";

			// anio
			$this->anio->LinkCustomAttributes = "";
			$this->anio->HrefValue = "";
			$this->anio->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// monto
			$this->monto->LinkCustomAttributes = "";
			$this->monto->HrefValue = "";
			$this->monto->TooltipValue = "";

			// id_socio
			$this->id_socio->LinkCustomAttributes = "";
			$this->id_socio->HrefValue = "";
			$this->id_socio->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// mes
			$this->mes->EditAttrs["class"] = "form-control";
			$this->mes->EditCustomAttributes = "";
			$this->mes->EditValue = ew_HtmlEncode($this->mes->CurrentValue);
			$this->mes->PlaceHolder = ew_RemoveHtml($this->mes->FldCaption());

			// anio
			$this->anio->EditAttrs["class"] = "form-control";
			$this->anio->EditCustomAttributes = "";
			$this->anio->EditValue = ew_HtmlEncode($this->anio->CurrentValue);
			$this->anio->PlaceHolder = ew_RemoveHtml($this->anio->FldCaption());

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// monto
			$this->monto->EditAttrs["class"] = "form-control";
			$this->monto->EditCustomAttributes = "";
			$this->monto->EditValue = ew_HtmlEncode($this->monto->CurrentValue);
			$this->monto->PlaceHolder = ew_RemoveHtml($this->monto->FldCaption());
			if (strval($this->monto->EditValue) <> "" && is_numeric($this->monto->EditValue)) $this->monto->EditValue = ew_FormatNumber($this->monto->EditValue, -2, -2, -2, -2);

			// id_socio
			$this->id_socio->EditAttrs["class"] = "form-control";
			$this->id_socio->EditCustomAttributes = "";
			if ($this->id_socio->getSessionValue() <> "") {
				$this->id_socio->CurrentValue = $this->id_socio->getSessionValue();
			if (strval($this->id_socio->CurrentValue) <> "") {
				$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->id_socio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_socio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `propietario` DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_socio->ViewValue = $rswrk->fields('DispFld');
					$this->id_socio->ViewValue .= ew_ValueSeparator(1,$this->id_socio) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_socio->ViewValue = $this->id_socio->CurrentValue;
				}
			} else {
				$this->id_socio->ViewValue = NULL;
			}
			$this->id_socio->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->id_socio->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->id_socio->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `socios`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["deudas"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_socio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `propietario` DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_socio->EditValue = $arwrk;
			}

			// Edit refer script
			// mes

			$this->mes->HrefValue = "";

			// anio
			$this->anio->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// monto
			$this->monto->HrefValue = "";

			// id_socio
			$this->id_socio->HrefValue = "";
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
		if (!ew_CheckRange($this->mes->FormValue, 1, 12)) {
			ew_AddMessage($gsFormError, $this->mes->FldErrMsg());
		}
		if (!ew_CheckRange($this->anio->FormValue, 2012, 2100)) {
			ew_AddMessage($gsFormError, $this->anio->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckNumber($this->monto->FormValue)) {
			ew_AddMessage($gsFormError, $this->monto->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("detalle_deudas", $DetailTblVar) && $GLOBALS["detalle_deudas"]->DetailAdd) {
			if (!isset($GLOBALS["detalle_deudas_grid"])) $GLOBALS["detalle_deudas_grid"] = new cdetalle_deudas_grid(); // get detail page object
			$GLOBALS["detalle_deudas_grid"]->ValidateGridForm();
		}
		if (in_array("pagos", $DetailTblVar) && $GLOBALS["pagos"]->DetailAdd) {
			if (!isset($GLOBALS["pagos_grid"])) $GLOBALS["pagos_grid"] = new cpagos_grid(); // get detail page object
			$GLOBALS["pagos_grid"]->ValidateGridForm();
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

		// Check if valid key values for master user
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sMasterFilter = $this->SqlMasterFilter_socios();
			if (strval($this->id_socio->CurrentValue) <> "" &&
				$this->getCurrentMasterTable() == "socios") {
				$sMasterFilter = str_replace("@socio_nro@", ew_AdjustSql($this->id_socio->CurrentValue), $sMasterFilter);
			} else {
				$sMasterFilter = "";
			}
			if ($sMasterFilter <> "") {
				$rsmaster = $GLOBALS["socios"]->LoadRs($sMasterFilter);
				$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
				if (!$this->MasterRecordExists) {
					$sMasterUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedMasterUserID"));
					$sMasterUserIdMsg = str_replace("%f", $sMasterFilter, $sMasterUserIdMsg);
					$this->setFailureMessage($sMasterUserIdMsg);
					return FALSE;
				} else {
					$rsmaster->Close();
				}
			}
		}

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// mes
		$this->mes->SetDbValueDef($rsnew, $this->mes->CurrentValue, NULL, FALSE);

		// anio
		$this->anio->SetDbValueDef($rsnew, $this->anio->CurrentValue, NULL, FALSE);

		// fecha
		$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), NULL, FALSE);

		// monto
		$this->monto->SetDbValueDef($rsnew, $this->monto->CurrentValue, NULL, FALSE);

		// id_socio
		$this->id_socio->SetDbValueDef($rsnew, $this->id_socio->CurrentValue, NULL, FALSE);

		// id_usuario
		if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin
			$rsnew['id_usuario'] = CurrentUserID();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("detalle_deudas", $DetailTblVar) && $GLOBALS["detalle_deudas"]->DetailAdd) {
				$GLOBALS["detalle_deudas"]->id_deuda->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["detalle_deudas_grid"])) $GLOBALS["detalle_deudas_grid"] = new cdetalle_deudas_grid(); // Get detail page object
				$AddRow = $GLOBALS["detalle_deudas_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["detalle_deudas"]->id_deuda->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("pagos", $DetailTblVar) && $GLOBALS["pagos"]->DetailAdd) {
				$GLOBALS["pagos"]->id_deuda->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["pagos_grid"])) $GLOBALS["pagos_grid"] = new cpagos_grid(); // Get detail page object
				$AddRow = $GLOBALS["pagos_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["pagos"]->id_deuda->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
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
			return $Security->IsValidUserID($this->id_usuario->CurrentValue);
		return TRUE;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "socios") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_socio_nro"] <> "") {
					$GLOBALS["socios"]->socio_nro->setQueryStringValue($_GET["fk_socio_nro"]);
					$this->id_socio->setQueryStringValue($GLOBALS["socios"]->socio_nro->QueryStringValue);
					$this->id_socio->setSessionValue($this->id_socio->QueryStringValue);
					if (!is_numeric($GLOBALS["socios"]->socio_nro->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "socios") {
				if ($this->id_socio->QueryStringValue == "") $this->id_socio->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
			if (in_array("detalle_deudas", $DetailTblVar)) {
				if (!isset($GLOBALS["detalle_deudas_grid"]))
					$GLOBALS["detalle_deudas_grid"] = new cdetalle_deudas_grid;
				if ($GLOBALS["detalle_deudas_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["detalle_deudas_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["detalle_deudas_grid"]->CurrentMode = "add";
					$GLOBALS["detalle_deudas_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["detalle_deudas_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["detalle_deudas_grid"]->setStartRecordNumber(1);
					$GLOBALS["detalle_deudas_grid"]->id_deuda->FldIsDetailKey = TRUE;
					$GLOBALS["detalle_deudas_grid"]->id_deuda->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["detalle_deudas_grid"]->id_deuda->setSessionValue($GLOBALS["detalle_deudas_grid"]->id_deuda->CurrentValue);
				}
			}
			if (in_array("pagos", $DetailTblVar)) {
				if (!isset($GLOBALS["pagos_grid"]))
					$GLOBALS["pagos_grid"] = new cpagos_grid;
				if ($GLOBALS["pagos_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["pagos_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["pagos_grid"]->CurrentMode = "add";
					$GLOBALS["pagos_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["pagos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["pagos_grid"]->setStartRecordNumber(1);
					$GLOBALS["pagos_grid"]->id_deuda->FldIsDetailKey = TRUE;
					$GLOBALS["pagos_grid"]->id_deuda->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["pagos_grid"]->id_deuda->setSessionValue($GLOBALS["pagos_grid"]->id_deuda->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "cciag_deudaslist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'deudas';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'deudas';

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
if (!isset($deudas_add)) $deudas_add = new cdeudas_add();

// Page init
$deudas_add->Page_Init();

// Page main
$deudas_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deudas_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_header.php" ?>
<script type="text/javascript">

// Page object
var deudas_add = new ew_Page("deudas_add");
deudas_add.PageID = "add"; // Page ID
var EW_PAGE_ID = deudas_add.PageID; // For backward compatibility

// Form object
var fdeudasadd = new ew_Form("fdeudasadd");

// Validate form
fdeudasadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_mes");
			if (elm && !ew_CheckRange(elm.value, 1, 12))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mes->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_anio");
			if (elm && !ew_CheckRange(elm.value, 2012, 2100))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->anio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_monto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->monto->FldErrMsg()) ?>");

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
fdeudasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdeudasadd.ValidateRequired = true;
<?php } else { ?>
fdeudasadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdeudasadd.Lists["x_id_socio"] = {"LinkField":"x_socio_nro","Ajax":true,"AutoFill":false,"DisplayFields":["x_propietario","x_comercio","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $deudas_add->ShowPageHeader(); ?>
<?php
$deudas_add->ShowMessage();
?>
<form name="fdeudasadd" id="fdeudasadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($deudas_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $deudas_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="deudas">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($deudas->mes->Visible) { // mes ?>
	<div id="r_mes" class="form-group">
		<label id="elh_deudas_mes" for="x_mes" class="col-sm-2 control-label ewLabel"><?php echo $deudas->mes->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $deudas->mes->CellAttributes() ?>>
<span id="el_deudas_mes">
<input type="text" data-field="x_mes" name="x_mes" id="x_mes" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mes->PlaceHolder) ?>" value="<?php echo $deudas->mes->EditValue ?>"<?php echo $deudas->mes->EditAttributes() ?>>
</span>
<?php echo $deudas->mes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->anio->Visible) { // anio ?>
	<div id="r_anio" class="form-group">
		<label id="elh_deudas_anio" for="x_anio" class="col-sm-2 control-label ewLabel"><?php echo $deudas->anio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $deudas->anio->CellAttributes() ?>>
<span id="el_deudas_anio">
<input type="text" data-field="x_anio" name="x_anio" id="x_anio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->anio->PlaceHolder) ?>" value="<?php echo $deudas->anio->EditValue ?>"<?php echo $deudas->anio->EditAttributes() ?>>
</span>
<?php echo $deudas->anio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_deudas_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $deudas->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $deudas->fecha->CellAttributes() ?>>
<span id="el_deudas_fecha">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($deudas->fecha->PlaceHolder) ?>" value="<?php echo $deudas->fecha->EditValue ?>"<?php echo $deudas->fecha->EditAttributes() ?>>
<?php if (!$deudas->fecha->ReadOnly && !$deudas->fecha->Disabled && @$deudas->fecha->EditAttrs["readonly"] == "" && @$deudas->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fdeudasadd", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $deudas->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->monto->Visible) { // monto ?>
	<div id="r_monto" class="form-group">
		<label id="elh_deudas_monto" for="x_monto" class="col-sm-2 control-label ewLabel"><?php echo $deudas->monto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $deudas->monto->CellAttributes() ?>>
<span id="el_deudas_monto">
<input type="text" data-field="x_monto" name="x_monto" id="x_monto" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->monto->PlaceHolder) ?>" value="<?php echo $deudas->monto->EditValue ?>"<?php echo $deudas->monto->EditAttributes() ?>>
</span>
<?php echo $deudas->monto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->id_socio->Visible) { // id_socio ?>
	<div id="r_id_socio" class="form-group">
		<label id="elh_deudas_id_socio" for="x_id_socio" class="col-sm-2 control-label ewLabel"><?php echo $deudas->id_socio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $deudas->id_socio->CellAttributes() ?>>
<?php if ($deudas->id_socio->getSessionValue() <> "") { ?>
<span id="el_deudas_id_socio">
<span<?php echo $deudas->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_id_socio" name="x_id_socio" value="<?php echo ew_HtmlEncode($deudas->id_socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el_deudas_id_socio">
<select data-field="x_id_socio" id="x_id_socio" name="x_id_socio"<?php echo $deudas->id_socio->EditAttributes() ?>>
<?php
if (is_array($deudas->id_socio->EditValue)) {
	$arwrk = $deudas->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($deudas->id_socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$deudas->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
$sWhereWrk = "";
$lookuptblfilter = "`activo`='S'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}
if (!$GLOBALS["deudas"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

// Call Lookup selecting
$deudas->Lookup_Selecting($deudas->id_socio, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `propietario` DESC";
?>
<input type="hidden" name="s_x_id_socio" id="s_x_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $deudas->id_socio->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("detalle_deudas", explode(",", $deudas->getCurrentDetailTable())) && $detalle_deudas->DetailAdd) {
?>
<?php if ($deudas->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("detalle_deudas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "cciag_detalle_deudasgrid.php" ?>
<?php } ?>
<?php
	if (in_array("pagos", explode(",", $deudas->getCurrentDetailTable())) && $pagos->DetailAdd) {
?>
<?php if ($deudas->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("pagos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "cciag_pagosgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fdeudasadd.Init();
</script>
<?php
$deudas_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "cciag_footer.php" ?>
<?php
$deudas_add->Page_Terminate();
?>
