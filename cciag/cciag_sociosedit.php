<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_sociosinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_socios_detallesgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_socios_cuotasgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_deudasgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$socios_edit = NULL; // Initialize page object first

class csocios_edit extends csocios {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'socios';

	// Page object name
	var $PageObjName = 'socios_edit';

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

		// Table object (socios)
		if (!isset($GLOBALS["socios"]) || get_class($GLOBALS["socios"]) == "csocios") {
			$GLOBALS["socios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["socios"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'socios', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("cciag_socioslist.php"));
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

			// Process auto fill for detail table 'socios_detalles'
			if (@$_POST["grid"] == "fsocios_detallesgrid") {
				if (!isset($GLOBALS["socios_detalles_grid"])) $GLOBALS["socios_detalles_grid"] = new csocios_detalles_grid;
				$GLOBALS["socios_detalles_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'socios_cuotas'
			if (@$_POST["grid"] == "fsocios_cuotasgrid") {
				if (!isset($GLOBALS["socios_cuotas_grid"])) $GLOBALS["socios_cuotas_grid"] = new csocios_cuotas_grid;
				$GLOBALS["socios_cuotas_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'deudas'
			if (@$_POST["grid"] == "fdeudasgrid") {
				if (!isset($GLOBALS["deudas_grid"])) $GLOBALS["deudas_grid"] = new cdeudas_grid;
				$GLOBALS["deudas_grid"]->Page_Init();
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
		global $EW_EXPORT, $socios;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($socios);
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

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["socio_nro"] <> "") {
			$this->socio_nro->setQueryStringValue($_GET["socio_nro"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->socio_nro->CurrentValue == "")
			$this->Page_Terminate("cciag_socioslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("cciag_socioslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
					else
						$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		if (!$this->cuit_cuil->FldIsDetailKey) {
			$this->cuit_cuil->setFormValue($objForm->GetValue("x_cuit_cuil"));
		}
		if (!$this->id_actividad->FldIsDetailKey) {
			$this->id_actividad->setFormValue($objForm->GetValue("x_id_actividad"));
		}
		if (!$this->propietario->FldIsDetailKey) {
			$this->propietario->setFormValue($objForm->GetValue("x_propietario"));
		}
		if (!$this->comercio->FldIsDetailKey) {
			$this->comercio->setFormValue($objForm->GetValue("x_comercio"));
		}
		if (!$this->direccion_comercio->FldIsDetailKey) {
			$this->direccion_comercio->setFormValue($objForm->GetValue("x_direccion_comercio"));
		}
		if (!$this->mail->FldIsDetailKey) {
			$this->mail->setFormValue($objForm->GetValue("x_mail"));
		}
		if (!$this->tel->FldIsDetailKey) {
			$this->tel->setFormValue($objForm->GetValue("x_tel"));
		}
		if (!$this->cel->FldIsDetailKey) {
			$this->cel->setFormValue($objForm->GetValue("x_cel"));
		}
		if (!$this->activo->FldIsDetailKey) {
			$this->activo->setFormValue($objForm->GetValue("x_activo"));
		}
		if (!$this->socio_nro->FldIsDetailKey)
			$this->socio_nro->setFormValue($objForm->GetValue("x_socio_nro"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->socio_nro->CurrentValue = $this->socio_nro->FormValue;
		$this->cuit_cuil->CurrentValue = $this->cuit_cuil->FormValue;
		$this->id_actividad->CurrentValue = $this->id_actividad->FormValue;
		$this->propietario->CurrentValue = $this->propietario->FormValue;
		$this->comercio->CurrentValue = $this->comercio->FormValue;
		$this->direccion_comercio->CurrentValue = $this->direccion_comercio->FormValue;
		$this->mail->CurrentValue = $this->mail->FormValue;
		$this->tel->CurrentValue = $this->tel->FormValue;
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
			$res = $this->ShowOptionLink('edit');
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
		$this->socio_nro->setDbValue($rs->fields('socio_nro'));
		$this->cuit_cuil->setDbValue($rs->fields('cuit_cuil'));
		$this->id_actividad->setDbValue($rs->fields('id_actividad'));
		if (array_key_exists('EV__id_actividad', $rs->fields)) {
			$this->id_actividad->VirtualValue = $rs->fields('EV__id_actividad'); // Set up virtual field value
		} else {
			$this->id_actividad->VirtualValue = ""; // Clear value
		}
		$this->propietario->setDbValue($rs->fields('propietario'));
		$this->comercio->setDbValue($rs->fields('comercio'));
		$this->direccion_comercio->setDbValue($rs->fields('direccion_comercio'));
		$this->mail->setDbValue($rs->fields('mail'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->activo->setDbValue($rs->fields('activo'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->socio_nro->DbValue = $row['socio_nro'];
		$this->cuit_cuil->DbValue = $row['cuit_cuil'];
		$this->id_actividad->DbValue = $row['id_actividad'];
		$this->propietario->DbValue = $row['propietario'];
		$this->comercio->DbValue = $row['comercio'];
		$this->direccion_comercio->DbValue = $row['direccion_comercio'];
		$this->mail->DbValue = $row['mail'];
		$this->tel->DbValue = $row['tel'];
		$this->cel->DbValue = $row['cel'];
		$this->activo->DbValue = $row['activo'];
		$this->id_usuario->DbValue = $row['id_usuario'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// socio_nro
		// cuit_cuil
		// id_actividad
		// propietario
		// comercio
		// direccion_comercio
		// mail
		// tel
		// cel
		// activo
		// id_usuario

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// socio_nro
			$this->socio_nro->ViewValue = $this->socio_nro->CurrentValue;
			$this->socio_nro->ViewCustomAttributes = "";

			// cuit_cuil
			$this->cuit_cuil->ViewValue = $this->cuit_cuil->CurrentValue;
			$this->cuit_cuil->ViewValue = ew_FormatNumber($this->cuit_cuil->ViewValue, 0, -2, -2, -2);
			$this->cuit_cuil->ViewCustomAttributes = "";

			// id_actividad
			if ($this->id_actividad->VirtualValue <> "") {
				$this->id_actividad->ViewValue = $this->id_actividad->VirtualValue;
			} else {
			if (strval($this->id_actividad->CurrentValue) <> "") {
				$sFilterWrk = "`id_actividad`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_actividad`, `rubro` AS `DispFld`, `actividad` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_db_rubro_actividad`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_actividad, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `rubro` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_actividad->ViewValue = $rswrk->fields('DispFld');
					$this->id_actividad->ViewValue .= ew_ValueSeparator(1,$this->id_actividad) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_actividad->ViewValue = $this->id_actividad->CurrentValue;
				}
			} else {
				$this->id_actividad->ViewValue = NULL;
			}
			}
			$this->id_actividad->ViewCustomAttributes = "";

			// propietario
			$this->propietario->ViewValue = $this->propietario->CurrentValue;
			$this->propietario->ViewCustomAttributes = "";

			// comercio
			$this->comercio->ViewValue = $this->comercio->CurrentValue;
			$this->comercio->ViewCustomAttributes = "";

			// direccion_comercio
			$this->direccion_comercio->ViewValue = $this->direccion_comercio->CurrentValue;
			$this->direccion_comercio->ViewCustomAttributes = "";

			// mail
			$this->mail->ViewValue = $this->mail->CurrentValue;
			$this->mail->ViewValue = strtolower($this->mail->ViewValue);
			$this->mail->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewValue = trim($this->tel->ViewValue);
			$this->tel->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewValue = trim($this->cel->ViewValue);
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

			// cuit_cuil
			$this->cuit_cuil->LinkCustomAttributes = "";
			$this->cuit_cuil->HrefValue = "";
			$this->cuit_cuil->TooltipValue = "";

			// id_actividad
			$this->id_actividad->LinkCustomAttributes = "";
			$this->id_actividad->HrefValue = "";
			$this->id_actividad->TooltipValue = "";

			// propietario
			$this->propietario->LinkCustomAttributes = "";
			$this->propietario->HrefValue = "";
			$this->propietario->TooltipValue = "";

			// comercio
			$this->comercio->LinkCustomAttributes = "";
			$this->comercio->HrefValue = "";
			$this->comercio->TooltipValue = "";

			// direccion_comercio
			$this->direccion_comercio->LinkCustomAttributes = "";
			$this->direccion_comercio->HrefValue = "";
			$this->direccion_comercio->TooltipValue = "";

			// mail
			$this->mail->LinkCustomAttributes = "";
			$this->mail->HrefValue = "";
			$this->mail->TooltipValue = "";

			// tel
			$this->tel->LinkCustomAttributes = "";
			$this->tel->HrefValue = "";
			$this->tel->TooltipValue = "";

			// cel
			$this->cel->LinkCustomAttributes = "";
			$this->cel->HrefValue = "";
			$this->cel->TooltipValue = "";

			// activo
			$this->activo->LinkCustomAttributes = "";
			$this->activo->HrefValue = "";
			$this->activo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// cuit_cuil
			$this->cuit_cuil->EditAttrs["class"] = "form-control";
			$this->cuit_cuil->EditCustomAttributes = "";
			$this->cuit_cuil->EditValue = ew_HtmlEncode($this->cuit_cuil->CurrentValue);
			$this->cuit_cuil->PlaceHolder = ew_RemoveHtml($this->cuit_cuil->FldCaption());

			// id_actividad
			$this->id_actividad->EditAttrs["class"] = "form-control";
			$this->id_actividad->EditCustomAttributes = "";
			if (trim(strval($this->id_actividad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_actividad`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `id_actividad`, `rubro` AS `DispFld`, `actividad` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `v_db_rubro_actividad`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_actividad, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `rubro` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_actividad->EditValue = $arwrk;

			// propietario
			$this->propietario->EditAttrs["class"] = "form-control";
			$this->propietario->EditCustomAttributes = "";
			$this->propietario->EditValue = ew_HtmlEncode($this->propietario->CurrentValue);
			$this->propietario->PlaceHolder = ew_RemoveHtml($this->propietario->FldCaption());

			// comercio
			$this->comercio->EditAttrs["class"] = "form-control";
			$this->comercio->EditCustomAttributes = "";
			$this->comercio->EditValue = ew_HtmlEncode($this->comercio->CurrentValue);
			$this->comercio->PlaceHolder = ew_RemoveHtml($this->comercio->FldCaption());

			// direccion_comercio
			$this->direccion_comercio->EditAttrs["class"] = "form-control";
			$this->direccion_comercio->EditCustomAttributes = "";
			$this->direccion_comercio->EditValue = ew_HtmlEncode($this->direccion_comercio->CurrentValue);
			$this->direccion_comercio->PlaceHolder = ew_RemoveHtml($this->direccion_comercio->FldCaption());

			// mail
			$this->mail->EditAttrs["class"] = "form-control";
			$this->mail->EditCustomAttributes = "";
			$this->mail->EditValue = ew_HtmlEncode($this->mail->CurrentValue);
			$this->mail->PlaceHolder = ew_RemoveHtml($this->mail->FldCaption());

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

			// activo
			$this->activo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->activo->FldTagValue(1), $this->activo->FldTagCaption(1) <> "" ? $this->activo->FldTagCaption(1) : $this->activo->FldTagValue(1));
			$arwrk[] = array($this->activo->FldTagValue(2), $this->activo->FldTagCaption(2) <> "" ? $this->activo->FldTagCaption(2) : $this->activo->FldTagValue(2));
			$this->activo->EditValue = $arwrk;

			// Edit refer script
			// cuit_cuil

			$this->cuit_cuil->HrefValue = "";

			// id_actividad
			$this->id_actividad->HrefValue = "";

			// propietario
			$this->propietario->HrefValue = "";

			// comercio
			$this->comercio->HrefValue = "";

			// direccion_comercio
			$this->direccion_comercio->HrefValue = "";

			// mail
			$this->mail->HrefValue = "";

			// tel
			$this->tel->HrefValue = "";

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
		if (!$this->cuit_cuil->FldIsDetailKey && !is_null($this->cuit_cuil->FormValue) && $this->cuit_cuil->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cuit_cuil->FldCaption(), $this->cuit_cuil->ReqErrMsg));
		}
		if (!$this->id_actividad->FldIsDetailKey && !is_null($this->id_actividad->FormValue) && $this->id_actividad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_actividad->FldCaption(), $this->id_actividad->ReqErrMsg));
		}
		if (!$this->propietario->FldIsDetailKey && !is_null($this->propietario->FormValue) && $this->propietario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->propietario->FldCaption(), $this->propietario->ReqErrMsg));
		}
		if (!$this->comercio->FldIsDetailKey && !is_null($this->comercio->FormValue) && $this->comercio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->comercio->FldCaption(), $this->comercio->ReqErrMsg));
		}
		if (!$this->direccion_comercio->FldIsDetailKey && !is_null($this->direccion_comercio->FormValue) && $this->direccion_comercio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->direccion_comercio->FldCaption(), $this->direccion_comercio->ReqErrMsg));
		}
		if (!ew_CheckEmail($this->mail->FormValue)) {
			ew_AddMessage($gsFormError, $this->mail->FldErrMsg());
		}
		if (!$this->cel->FldIsDetailKey && !is_null($this->cel->FormValue) && $this->cel->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cel->FldCaption(), $this->cel->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("socios_detalles", $DetailTblVar) && $GLOBALS["socios_detalles"]->DetailEdit) {
			if (!isset($GLOBALS["socios_detalles_grid"])) $GLOBALS["socios_detalles_grid"] = new csocios_detalles_grid(); // get detail page object
			$GLOBALS["socios_detalles_grid"]->ValidateGridForm();
		}
		if (in_array("socios_cuotas", $DetailTblVar) && $GLOBALS["socios_cuotas"]->DetailEdit) {
			if (!isset($GLOBALS["socios_cuotas_grid"])) $GLOBALS["socios_cuotas_grid"] = new csocios_cuotas_grid(); // get detail page object
			$GLOBALS["socios_cuotas_grid"]->ValidateGridForm();
		}
		if (in_array("deudas", $DetailTblVar) && $GLOBALS["deudas"]->DetailEdit) {
			if (!isset($GLOBALS["deudas_grid"])) $GLOBALS["deudas_grid"] = new cdeudas_grid(); // get detail page object
			$GLOBALS["deudas_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// cuit_cuil
			$this->cuit_cuil->SetDbValueDef($rsnew, $this->cuit_cuil->CurrentValue, NULL, $this->cuit_cuil->ReadOnly);

			// id_actividad
			$this->id_actividad->SetDbValueDef($rsnew, $this->id_actividad->CurrentValue, NULL, $this->id_actividad->ReadOnly);

			// propietario
			$this->propietario->SetDbValueDef($rsnew, $this->propietario->CurrentValue, NULL, $this->propietario->ReadOnly);

			// comercio
			$this->comercio->SetDbValueDef($rsnew, $this->comercio->CurrentValue, NULL, $this->comercio->ReadOnly);

			// direccion_comercio
			$this->direccion_comercio->SetDbValueDef($rsnew, $this->direccion_comercio->CurrentValue, NULL, $this->direccion_comercio->ReadOnly);

			// mail
			$this->mail->SetDbValueDef($rsnew, $this->mail->CurrentValue, NULL, $this->mail->ReadOnly);

			// tel
			$this->tel->SetDbValueDef($rsnew, $this->tel->CurrentValue, NULL, $this->tel->ReadOnly);

			// cel
			$this->cel->SetDbValueDef($rsnew, $this->cel->CurrentValue, NULL, $this->cel->ReadOnly);

			// activo
			$this->activo->SetDbValueDef($rsnew, $this->activo->CurrentValue, NULL, $this->activo->ReadOnly);

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

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("socios_detalles", $DetailTblVar) && $GLOBALS["socios_detalles"]->DetailEdit) {
						if (!isset($GLOBALS["socios_detalles_grid"])) $GLOBALS["socios_detalles_grid"] = new csocios_detalles_grid(); // Get detail page object
						$EditRow = $GLOBALS["socios_detalles_grid"]->GridUpdate();
					}
					if (in_array("socios_cuotas", $DetailTblVar) && $GLOBALS["socios_cuotas"]->DetailEdit) {
						if (!isset($GLOBALS["socios_cuotas_grid"])) $GLOBALS["socios_cuotas_grid"] = new csocios_cuotas_grid(); // Get detail page object
						$EditRow = $GLOBALS["socios_cuotas_grid"]->GridUpdate();
					}
					if (in_array("deudas", $DetailTblVar) && $GLOBALS["deudas"]->DetailEdit) {
						if (!isset($GLOBALS["deudas_grid"])) $GLOBALS["deudas_grid"] = new cdeudas_grid(); // Get detail page object
						$EditRow = $GLOBALS["deudas_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->id_usuario->CurrentValue);
		return TRUE;
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
			if (in_array("socios_detalles", $DetailTblVar)) {
				if (!isset($GLOBALS["socios_detalles_grid"]))
					$GLOBALS["socios_detalles_grid"] = new csocios_detalles_grid;
				if ($GLOBALS["socios_detalles_grid"]->DetailEdit) {
					$GLOBALS["socios_detalles_grid"]->CurrentMode = "edit";
					$GLOBALS["socios_detalles_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["socios_detalles_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["socios_detalles_grid"]->setStartRecordNumber(1);
					$GLOBALS["socios_detalles_grid"]->id_socio->FldIsDetailKey = TRUE;
					$GLOBALS["socios_detalles_grid"]->id_socio->CurrentValue = $this->socio_nro->CurrentValue;
					$GLOBALS["socios_detalles_grid"]->id_socio->setSessionValue($GLOBALS["socios_detalles_grid"]->id_socio->CurrentValue);
				}
			}
			if (in_array("socios_cuotas", $DetailTblVar)) {
				if (!isset($GLOBALS["socios_cuotas_grid"]))
					$GLOBALS["socios_cuotas_grid"] = new csocios_cuotas_grid;
				if ($GLOBALS["socios_cuotas_grid"]->DetailEdit) {
					$GLOBALS["socios_cuotas_grid"]->CurrentMode = "edit";
					$GLOBALS["socios_cuotas_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["socios_cuotas_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["socios_cuotas_grid"]->setStartRecordNumber(1);
					$GLOBALS["socios_cuotas_grid"]->id_socio->FldIsDetailKey = TRUE;
					$GLOBALS["socios_cuotas_grid"]->id_socio->CurrentValue = $this->socio_nro->CurrentValue;
					$GLOBALS["socios_cuotas_grid"]->id_socio->setSessionValue($GLOBALS["socios_cuotas_grid"]->id_socio->CurrentValue);
				}
			}
			if (in_array("deudas", $DetailTblVar)) {
				if (!isset($GLOBALS["deudas_grid"]))
					$GLOBALS["deudas_grid"] = new cdeudas_grid;
				if ($GLOBALS["deudas_grid"]->DetailEdit) {
					$GLOBALS["deudas_grid"]->CurrentMode = "edit";
					$GLOBALS["deudas_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["deudas_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["deudas_grid"]->setStartRecordNumber(1);
					$GLOBALS["deudas_grid"]->id_socio->FldIsDetailKey = TRUE;
					$GLOBALS["deudas_grid"]->id_socio->CurrentValue = $this->socio_nro->CurrentValue;
					$GLOBALS["deudas_grid"]->id_socio->setSessionValue($GLOBALS["deudas_grid"]->id_socio->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "cciag_socioslist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, ew_CurrentUrl());
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
if (!isset($socios_edit)) $socios_edit = new csocios_edit();

// Page init
$socios_edit->Page_Init();

// Page main
$socios_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_header.php" ?>
<script type="text/javascript">

// Page object
var socios_edit = new ew_Page("socios_edit");
socios_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = socios_edit.PageID; // For backward compatibility

// Form object
var fsociosedit = new ew_Form("fsociosedit");

// Validate form
fsociosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_cuit_cuil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->cuit_cuil->FldCaption(), $socios->cuit_cuil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_actividad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->id_actividad->FldCaption(), $socios->id_actividad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_propietario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->propietario->FldCaption(), $socios->propietario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_comercio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->comercio->FldCaption(), $socios->comercio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion_comercio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->direccion_comercio->FldCaption(), $socios->direccion_comercio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mail");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->mail->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cel");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->cel->FldCaption(), $socios->cel->ReqErrMsg)) ?>");

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
fsociosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsociosedit.ValidateRequired = true;
<?php } else { ?>
fsociosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsociosedit.Lists["x_id_actividad"] = {"LinkField":"x_id_actividad","Ajax":true,"AutoFill":false,"DisplayFields":["x_rubro","x_actividad","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $socios_edit->ShowPageHeader(); ?>
<?php
$socios_edit->ShowMessage();
?>
<form name="fsociosedit" id="fsociosedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($socios_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $socios_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="socios">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
	<div id="r_cuit_cuil" class="form-group">
		<label id="elh_socios_cuit_cuil" for="x_cuit_cuil" class="col-sm-2 control-label ewLabel"><?php echo $socios->cuit_cuil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->cuit_cuil->CellAttributes() ?>>
<span id="el_socios_cuit_cuil">
<input type="text" data-field="x_cuit_cuil" name="x_cuit_cuil" id="x_cuit_cuil" size="30" maxlength="14" placeholder="<?php echo ew_HtmlEncode($socios->cuit_cuil->PlaceHolder) ?>" value="<?php echo $socios->cuit_cuil->EditValue ?>"<?php echo $socios->cuit_cuil->EditAttributes() ?>>
</span>
<?php echo $socios->cuit_cuil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->id_actividad->Visible) { // id_actividad ?>
	<div id="r_id_actividad" class="form-group">
		<label id="elh_socios_id_actividad" for="x_id_actividad" class="col-sm-2 control-label ewLabel"><?php echo $socios->id_actividad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->id_actividad->CellAttributes() ?>>
<span id="el_socios_id_actividad">
<select data-field="x_id_actividad" id="x_id_actividad" name="x_id_actividad"<?php echo $socios->id_actividad->EditAttributes() ?>>
<?php
if (is_array($socios->id_actividad->EditValue)) {
	$arwrk = $socios->id_actividad->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->id_actividad->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios->id_actividad) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `id_actividad`, `rubro` AS `DispFld`, `actividad` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_db_rubro_actividad`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->id_actividad, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `rubro` ASC";
?>
<input type="hidden" name="s_x_id_actividad" id="s_x_id_actividad" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id_actividad` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $socios->id_actividad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->propietario->Visible) { // propietario ?>
	<div id="r_propietario" class="form-group">
		<label id="elh_socios_propietario" for="x_propietario" class="col-sm-2 control-label ewLabel"><?php echo $socios->propietario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->propietario->CellAttributes() ?>>
<span id="el_socios_propietario">
<input type="text" data-field="x_propietario" name="x_propietario" id="x_propietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->propietario->PlaceHolder) ?>" value="<?php echo $socios->propietario->EditValue ?>"<?php echo $socios->propietario->EditAttributes() ?>>
</span>
<?php echo $socios->propietario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->comercio->Visible) { // comercio ?>
	<div id="r_comercio" class="form-group">
		<label id="elh_socios_comercio" for="x_comercio" class="col-sm-2 control-label ewLabel"><?php echo $socios->comercio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->comercio->CellAttributes() ?>>
<span id="el_socios_comercio">
<input type="text" data-field="x_comercio" name="x_comercio" id="x_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->comercio->PlaceHolder) ?>" value="<?php echo $socios->comercio->EditValue ?>"<?php echo $socios->comercio->EditAttributes() ?>>
</span>
<?php echo $socios->comercio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
	<div id="r_direccion_comercio" class="form-group">
		<label id="elh_socios_direccion_comercio" for="x_direccion_comercio" class="col-sm-2 control-label ewLabel"><?php echo $socios->direccion_comercio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->direccion_comercio->CellAttributes() ?>>
<span id="el_socios_direccion_comercio">
<input type="text" data-field="x_direccion_comercio" name="x_direccion_comercio" id="x_direccion_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->direccion_comercio->PlaceHolder) ?>" value="<?php echo $socios->direccion_comercio->EditValue ?>"<?php echo $socios->direccion_comercio->EditAttributes() ?>>
</span>
<?php echo $socios->direccion_comercio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->mail->Visible) { // mail ?>
	<div id="r_mail" class="form-group">
		<label id="elh_socios_mail" for="x_mail" class="col-sm-2 control-label ewLabel"><?php echo $socios->mail->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $socios->mail->CellAttributes() ?>>
<span id="el_socios_mail">
<input type="text" data-field="x_mail" name="x_mail" id="x_mail" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->mail->PlaceHolder) ?>" value="<?php echo $socios->mail->EditValue ?>"<?php echo $socios->mail->EditAttributes() ?>>
</span>
<?php echo $socios->mail->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->tel->Visible) { // tel ?>
	<div id="r_tel" class="form-group">
		<label id="elh_socios_tel" for="x_tel" class="col-sm-2 control-label ewLabel"><?php echo $socios->tel->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $socios->tel->CellAttributes() ?>>
<span id="el_socios_tel">
<input type="text" data-field="x_tel" name="x_tel" id="x_tel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->tel->PlaceHolder) ?>" value="<?php echo $socios->tel->EditValue ?>"<?php echo $socios->tel->EditAttributes() ?>>
</span>
<?php echo $socios->tel->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->cel->Visible) { // cel ?>
	<div id="r_cel" class="form-group">
		<label id="elh_socios_cel" for="x_cel" class="col-sm-2 control-label ewLabel"><?php echo $socios->cel->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $socios->cel->CellAttributes() ?>>
<span id="el_socios_cel">
<input type="text" data-field="x_cel" name="x_cel" id="x_cel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->cel->PlaceHolder) ?>" value="<?php echo $socios->cel->EditValue ?>"<?php echo $socios->cel->EditAttributes() ?>>
</span>
<?php echo $socios->cel->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($socios->activo->Visible) { // activo ?>
	<div id="r_activo" class="form-group">
		<label id="elh_socios_activo" class="col-sm-2 control-label ewLabel"><?php echo $socios->activo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $socios->activo->CellAttributes() ?>>
<span id="el_socios_activo">
<div id="tp_x_activo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_activo" id="x_activo" value="{value}"<?php echo $socios->activo->EditAttributes() ?>></div>
<div id="dsl_x_activo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->activo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->activo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activo" name="x_activo" id="x_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $socios->activo->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_socio_nro" name="x_socio_nro" id="x_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->CurrentValue) ?>">
<?php if ($socios->getCurrentDetailTable() <> "") { ?>
<?php
	$FirstActiveDetailTable = "";
	$ActiveTableItemClass = "";
	$ActiveTableDivClass = "";
?>
<div class="ewMultiPage">
<div class="tabbable" id="socios_edit_details">
	<ul class="nav nav-pills">
<?php
	if (in_array("socios_detalles", explode(",", $socios->getCurrentDetailTable())) && $socios_detalles->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "socios_detalles") {
			$FirstActiveDetailTable = "socios_detalles";
			$ActiveTableItemClass = " class=\"active\"";
		} else {
			$ActiveTableItemClass = "";
		}
?>
		<li<?php echo $ActiveTableItemClass ?>><a href="#tab_socios_detalles" data-toggle="tab"><?php echo $Language->TablePhrase("socios_detalles", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("socios_cuotas", explode(",", $socios->getCurrentDetailTable())) && $socios_cuotas->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "socios_cuotas") {
			$FirstActiveDetailTable = "socios_cuotas";
			$ActiveTableItemClass = " class=\"active\"";
		} else {
			$ActiveTableItemClass = "";
		}
?>
		<li<?php echo $ActiveTableItemClass ?>><a href="#tab_socios_cuotas" data-toggle="tab"><?php echo $Language->TablePhrase("socios_cuotas", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("deudas", explode(",", $socios->getCurrentDetailTable())) && $deudas->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "deudas") {
			$FirstActiveDetailTable = "deudas";
			$ActiveTableItemClass = " class=\"active\"";
		} else {
			$ActiveTableItemClass = "";
		}
?>
		<li<?php echo $ActiveTableItemClass ?>><a href="#tab_deudas" data-toggle="tab"><?php echo $Language->TablePhrase("deudas", "TblCaption") ?></a></li>
<?php
	}
?>
	</ul>
	<div class="tab-content">
<?php
	if (in_array("socios_detalles", explode(",", $socios->getCurrentDetailTable())) && $socios_detalles->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "socios_detalles") {
			$FirstActiveDetailTable = "socios_detalles";
			$ActiveTableDivClass = " active";
		} else {
			$ActiveTableDivClass = "";
		}
?>
		<div class="tab-pane<?php echo $ActiveTableDivClass ?>" id="tab_socios_detalles">
<?php include_once "cciag_socios_detallesgrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("socios_cuotas", explode(",", $socios->getCurrentDetailTable())) && $socios_cuotas->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "socios_cuotas") {
			$FirstActiveDetailTable = "socios_cuotas";
			$ActiveTableDivClass = " active";
		} else {
			$ActiveTableDivClass = "";
		}
?>
		<div class="tab-pane<?php echo $ActiveTableDivClass ?>" id="tab_socios_cuotas">
<?php include_once "cciag_socios_cuotasgrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("deudas", explode(",", $socios->getCurrentDetailTable())) && $deudas->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "deudas") {
			$FirstActiveDetailTable = "deudas";
			$ActiveTableDivClass = " active";
		} else {
			$ActiveTableDivClass = "";
		}
?>
		<div class="tab-pane<?php echo $ActiveTableDivClass ?>" id="tab_deudas">
<?php include_once "cciag_deudasgrid.php" ?>
		</div>
<?php } ?>
	</div>
</div>
</div>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsociosedit.Init();
</script>
<?php
$socios_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "cciag_footer.php" ?>
<?php
$socios_edit->Page_Terminate();
?>
