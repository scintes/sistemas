<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "hoja_rutasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "gastosgridcls.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$hoja_rutas_edit = NULL; // Initialize page object first

class choja_rutas_edit extends choja_rutas {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'hoja_rutas';

	// Page object name
	var $PageObjName = 'hoja_rutas_edit';

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

		// Table object (hoja_rutas)
		if (!isset($GLOBALS["hoja_rutas"]) || get_class($GLOBALS["hoja_rutas"]) == "choja_rutas") {
			$GLOBALS["hoja_rutas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["hoja_rutas"];
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
			define("EW_TABLE_NAME", 'hoja_rutas', TRUE);

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
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("hoja_rutaslist.php"));
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

			// Process auto fill for detail table 'gastos'
			if (@$_POST["grid"] == "fgastosgrid") {
				if (!isset($GLOBALS["gastos_grid"])) $GLOBALS["gastos_grid"] = new cgastos_grid;
				$GLOBALS["gastos_grid"]->Page_Init();
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
		global $EW_EXPORT, $hoja_rutas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($hoja_rutas);
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
		if (@$_GET["codigo"] <> "") {
			$this->codigo->setQueryStringValue($_GET["codigo"]);
			$this->RecKey["codigo"] = $this->codigo->QueryStringValue;
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
			$this->Page_Terminate("hoja_rutaslist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->codigo->CurrentValue) == strval($this->Recordset->fields('codigo'))) {
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

			// Set up detail parameters
			$this->SetUpDetailParms();
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
					$this->Page_Terminate("hoja_rutaslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
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
		if (!$this->fecha_ini->FldIsDetailKey) {
			$this->fecha_ini->setFormValue($objForm->GetValue("x_fecha_ini"));
			$this->fecha_ini->CurrentValue = ew_UnFormatDateTime($this->fecha_ini->CurrentValue, 7);
		}
		if (!$this->id_cliente->FldIsDetailKey) {
			$this->id_cliente->setFormValue($objForm->GetValue("x_id_cliente"));
		}
		if (!$this->id_localidad_origen->FldIsDetailKey) {
			$this->id_localidad_origen->setFormValue($objForm->GetValue("x_id_localidad_origen"));
		}
		if (!$this->Origen->FldIsDetailKey) {
			$this->Origen->setFormValue($objForm->GetValue("x_Origen"));
		}
		if (!$this->id_localidad_destino->FldIsDetailKey) {
			$this->id_localidad_destino->setFormValue($objForm->GetValue("x_id_localidad_destino"));
		}
		if (!$this->Destino->FldIsDetailKey) {
			$this->Destino->setFormValue($objForm->GetValue("x_Destino"));
		}
		if (!$this->Km_ini->FldIsDetailKey) {
			$this->Km_ini->setFormValue($objForm->GetValue("x_Km_ini"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->id_vehiculo->FldIsDetailKey) {
			$this->id_vehiculo->setFormValue($objForm->GetValue("x_id_vehiculo"));
		}
		if (!$this->id_tipo_carga->FldIsDetailKey) {
			$this->id_tipo_carga->setFormValue($objForm->GetValue("x_id_tipo_carga"));
		}
		if (!$this->km_fin->FldIsDetailKey) {
			$this->km_fin->setFormValue($objForm->GetValue("x_km_fin"));
		}
		if (!$this->fecha_fin->FldIsDetailKey) {
			$this->fecha_fin->setFormValue($objForm->GetValue("x_fecha_fin"));
			$this->fecha_fin->CurrentValue = ew_UnFormatDateTime($this->fecha_fin->CurrentValue, 7);
		}
		if (!$this->adelanto->FldIsDetailKey) {
			$this->adelanto->setFormValue($objForm->GetValue("x_adelanto"));
		}
		if (!$this->kg_carga->FldIsDetailKey) {
			$this->kg_carga->setFormValue($objForm->GetValue("x_kg_carga"));
		}
		if (!$this->tarifa->FldIsDetailKey) {
			$this->tarifa->setFormValue($objForm->GetValue("x_tarifa"));
		}
		if (!$this->porcentaje->FldIsDetailKey) {
			$this->porcentaje->setFormValue($objForm->GetValue("x_porcentaje"));
		}
		if (!$this->codigo->FldIsDetailKey)
			$this->codigo->setFormValue($objForm->GetValue("x_codigo"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->codigo->CurrentValue = $this->codigo->FormValue;
		$this->fecha_ini->CurrentValue = $this->fecha_ini->FormValue;
		$this->fecha_ini->CurrentValue = ew_UnFormatDateTime($this->fecha_ini->CurrentValue, 7);
		$this->id_cliente->CurrentValue = $this->id_cliente->FormValue;
		$this->id_localidad_origen->CurrentValue = $this->id_localidad_origen->FormValue;
		$this->Origen->CurrentValue = $this->Origen->FormValue;
		$this->id_localidad_destino->CurrentValue = $this->id_localidad_destino->FormValue;
		$this->Destino->CurrentValue = $this->Destino->FormValue;
		$this->Km_ini->CurrentValue = $this->Km_ini->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
		$this->id_vehiculo->CurrentValue = $this->id_vehiculo->FormValue;
		$this->id_tipo_carga->CurrentValue = $this->id_tipo_carga->FormValue;
		$this->km_fin->CurrentValue = $this->km_fin->FormValue;
		$this->fecha_fin->CurrentValue = $this->fecha_fin->FormValue;
		$this->fecha_fin->CurrentValue = ew_UnFormatDateTime($this->fecha_fin->CurrentValue, 7);
		$this->adelanto->CurrentValue = $this->adelanto->FormValue;
		$this->kg_carga->CurrentValue = $this->kg_carga->FormValue;
		$this->tarifa->CurrentValue = $this->tarifa->FormValue;
		$this->porcentaje->CurrentValue = $this->porcentaje->FormValue;
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
		$this->codigo->setDbValue($rs->fields('codigo'));
		$this->fecha_ini->setDbValue($rs->fields('fecha_ini'));
		$this->id_cliente->setDbValue($rs->fields('id_cliente'));
		$this->id_localidad_origen->setDbValue($rs->fields('id_localidad_origen'));
		if (array_key_exists('EV__id_localidad_origen', $rs->fields)) {
			$this->id_localidad_origen->VirtualValue = $rs->fields('EV__id_localidad_origen'); // Set up virtual field value
		} else {
			$this->id_localidad_origen->VirtualValue = ""; // Clear value
		}
		$this->Origen->setDbValue($rs->fields('Origen'));
		$this->id_localidad_destino->setDbValue($rs->fields('id_localidad_destino'));
		if (array_key_exists('EV__id_localidad_destino', $rs->fields)) {
			$this->id_localidad_destino->VirtualValue = $rs->fields('EV__id_localidad_destino'); // Set up virtual field value
		} else {
			$this->id_localidad_destino->VirtualValue = ""; // Clear value
		}
		$this->Destino->setDbValue($rs->fields('Destino'));
		$this->Km_ini->setDbValue($rs->fields('Km_ini'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->id_vehiculo->setDbValue($rs->fields('id_vehiculo'));
		if (array_key_exists('EV__id_vehiculo', $rs->fields)) {
			$this->id_vehiculo->VirtualValue = $rs->fields('EV__id_vehiculo'); // Set up virtual field value
		} else {
			$this->id_vehiculo->VirtualValue = ""; // Clear value
		}
		$this->id_tipo_carga->setDbValue($rs->fields('id_tipo_carga'));
		if (array_key_exists('EV__id_tipo_carga', $rs->fields)) {
			$this->id_tipo_carga->VirtualValue = $rs->fields('EV__id_tipo_carga'); // Set up virtual field value
		} else {
			$this->id_tipo_carga->VirtualValue = ""; // Clear value
		}
		$this->km_fin->setDbValue($rs->fields('km_fin'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->adelanto->setDbValue($rs->fields('adelanto'));
		$this->kg_carga->setDbValue($rs->fields('kg_carga'));
		$this->tarifa->setDbValue($rs->fields('tarifa'));
		$this->porcentaje->setDbValue($rs->fields('porcentaje'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->fecha_ini->DbValue = $row['fecha_ini'];
		$this->id_cliente->DbValue = $row['id_cliente'];
		$this->id_localidad_origen->DbValue = $row['id_localidad_origen'];
		$this->Origen->DbValue = $row['Origen'];
		$this->id_localidad_destino->DbValue = $row['id_localidad_destino'];
		$this->Destino->DbValue = $row['Destino'];
		$this->Km_ini->DbValue = $row['Km_ini'];
		$this->estado->DbValue = $row['estado'];
		$this->id_vehiculo->DbValue = $row['id_vehiculo'];
		$this->id_tipo_carga->DbValue = $row['id_tipo_carga'];
		$this->km_fin->DbValue = $row['km_fin'];
		$this->fecha_fin->DbValue = $row['fecha_fin'];
		$this->adelanto->DbValue = $row['adelanto'];
		$this->kg_carga->DbValue = $row['kg_carga'];
		$this->tarifa->DbValue = $row['tarifa'];
		$this->porcentaje->DbValue = $row['porcentaje'];
		$this->id_usuario->DbValue = $row['id_usuario'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->adelanto->FormValue == $this->adelanto->CurrentValue && is_numeric(ew_StrToFloat($this->adelanto->CurrentValue)))
			$this->adelanto->CurrentValue = ew_StrToFloat($this->adelanto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tarifa->FormValue == $this->tarifa->CurrentValue && is_numeric(ew_StrToFloat($this->tarifa->CurrentValue)))
			$this->tarifa->CurrentValue = ew_StrToFloat($this->tarifa->CurrentValue);

		// Convert decimal values if posted back
		if ($this->porcentaje->FormValue == $this->porcentaje->CurrentValue && is_numeric(ew_StrToFloat($this->porcentaje->CurrentValue)))
			$this->porcentaje->CurrentValue = ew_StrToFloat($this->porcentaje->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// fecha_ini
		// id_cliente
		// id_localidad_origen
		// Origen
		// id_localidad_destino
		// Destino
		// Km_ini
		// estado
		// id_vehiculo
		// id_tipo_carga
		// km_fin
		// fecha_fin
		// adelanto
		// kg_carga
		// tarifa
		// porcentaje
		// id_usuario

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// fecha_ini
			$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
			$this->fecha_ini->ViewValue = ew_FormatDateTime($this->fecha_ini->ViewValue, 7);
			$this->fecha_ini->ViewCustomAttributes = "";

			// id_cliente
			if (strval($this->id_cliente->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `cuit_cuil` AS `DispFld`, `razon_social` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `clientes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_cliente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `razon_social` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_cliente->ViewValue = $rswrk->fields('DispFld');
					$this->id_cliente->ViewValue .= ew_ValueSeparator(1,$this->id_cliente) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_cliente->ViewValue = $this->id_cliente->CurrentValue;
				}
			} else {
				$this->id_cliente->ViewValue = NULL;
			}
			$this->id_cliente->ViewCustomAttributes = "";

			// id_localidad_origen
			if ($this->id_localidad_origen->VirtualValue <> "") {
				$this->id_localidad_origen->ViewValue = $this->id_localidad_origen->VirtualValue;
			} else {
			if (strval($this->id_localidad_origen->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad_origen->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `cp` AS `DispFld`, `localidad` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_localidad_origen, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `localidad` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_localidad_origen->ViewValue = $rswrk->fields('DispFld');
					$this->id_localidad_origen->ViewValue .= ew_ValueSeparator(1,$this->id_localidad_origen) . $rswrk->fields('Disp2Fld');
					$this->id_localidad_origen->ViewValue .= ew_ValueSeparator(2,$this->id_localidad_origen) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_localidad_origen->ViewValue = $this->id_localidad_origen->CurrentValue;
				}
			} else {
				$this->id_localidad_origen->ViewValue = NULL;
			}
			}
			$this->id_localidad_origen->ViewCustomAttributes = "";

			// Origen
			$this->Origen->ViewValue = $this->Origen->CurrentValue;
			$this->Origen->ViewCustomAttributes = "";

			// id_localidad_destino
			if ($this->id_localidad_destino->VirtualValue <> "") {
				$this->id_localidad_destino->ViewValue = $this->id_localidad_destino->VirtualValue;
			} else {
			if (strval($this->id_localidad_destino->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad_destino->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `cp` AS `DispFld`, `localidad` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_localidad_destino, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `localidad` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_localidad_destino->ViewValue = $rswrk->fields('DispFld');
					$this->id_localidad_destino->ViewValue .= ew_ValueSeparator(1,$this->id_localidad_destino) . $rswrk->fields('Disp2Fld');
					$this->id_localidad_destino->ViewValue .= ew_ValueSeparator(2,$this->id_localidad_destino) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_localidad_destino->ViewValue = $this->id_localidad_destino->CurrentValue;
				}
			} else {
				$this->id_localidad_destino->ViewValue = NULL;
			}
			}
			$this->id_localidad_destino->ViewCustomAttributes = "";

			// Destino
			$this->Destino->ViewValue = $this->Destino->CurrentValue;
			$this->Destino->ViewCustomAttributes = "";

			// Km_ini
			$this->Km_ini->ViewValue = $this->Km_ini->CurrentValue;
			$this->Km_ini->ViewValue = ew_FormatNumber($this->Km_ini->ViewValue, 0, -2, -2, -2);
			$this->Km_ini->ViewCustomAttributes = "";

			// estado
			$this->estado->ViewValue = $this->estado->CurrentValue;
			$this->estado->ViewCustomAttributes = "";

			// id_vehiculo
			if ($this->id_vehiculo->VirtualValue <> "") {
				$this->id_vehiculo->ViewValue = $this->id_vehiculo->VirtualValue;
			} else {
			if (strval($this->id_vehiculo->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_vehiculo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_vehiculo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_vehiculo->ViewValue = $rswrk->fields('DispFld');
					$this->id_vehiculo->ViewValue .= ew_ValueSeparator(1,$this->id_vehiculo) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_vehiculo->ViewValue = $this->id_vehiculo->CurrentValue;
				}
			} else {
				$this->id_vehiculo->ViewValue = NULL;
			}
			}
			$this->id_vehiculo->ViewCustomAttributes = "";

			// id_tipo_carga
			if ($this->id_tipo_carga->VirtualValue <> "") {
				$this->id_tipo_carga->ViewValue = $this->id_tipo_carga->VirtualValue;
			} else {
			if (strval($this->id_tipo_carga->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_carga->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `Tipo_carga` AS `DispFld`, `precio_base` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_cargas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_carga, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Tipo_carga` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_carga->ViewValue = $rswrk->fields('DispFld');
					$this->id_tipo_carga->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_carga) . ew_FormatCurrency($rswrk->fields('Disp2Fld'), 2, 0, 0, -1);
					$rswrk->Close();
				} else {
					$this->id_tipo_carga->ViewValue = $this->id_tipo_carga->CurrentValue;
				}
			} else {
				$this->id_tipo_carga->ViewValue = NULL;
			}
			}
			$this->id_tipo_carga->ViewCustomAttributes = "";

			// km_fin
			$this->km_fin->ViewValue = $this->km_fin->CurrentValue;
			$this->km_fin->ViewValue = ew_FormatNumber($this->km_fin->ViewValue, 0, -2, -2, -2);
			$this->km_fin->ViewCustomAttributes = "";

			// fecha_fin
			$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
			$this->fecha_fin->ViewValue = ew_FormatDateTime($this->fecha_fin->ViewValue, 7);
			$this->fecha_fin->ViewCustomAttributes = "";

			// adelanto
			$this->adelanto->ViewValue = $this->adelanto->CurrentValue;
			$this->adelanto->ViewValue = ew_FormatCurrency($this->adelanto->ViewValue, 2, -2, -2, -2);
			$this->adelanto->ViewCustomAttributes = "";

			// kg_carga
			$this->kg_carga->ViewValue = $this->kg_carga->CurrentValue;
			$this->kg_carga->ViewValue = ew_FormatNumber($this->kg_carga->ViewValue, 0, -2, -2, -2);
			$this->kg_carga->ViewCustomAttributes = "";

			// tarifa
			$this->tarifa->ViewValue = $this->tarifa->CurrentValue;
			$this->tarifa->ViewValue = ew_FormatCurrency($this->tarifa->ViewValue, 2, -2, -2, -2);
			$this->tarifa->ViewCustomAttributes = "";

			// porcentaje
			$this->porcentaje->ViewValue = $this->porcentaje->CurrentValue;
			$this->porcentaje->ViewCustomAttributes = "";

			// fecha_ini
			$this->fecha_ini->LinkCustomAttributes = "";
			$this->fecha_ini->HrefValue = "";
			$this->fecha_ini->TooltipValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			$this->id_cliente->HrefValue = "";
			$this->id_cliente->TooltipValue = "";

			// id_localidad_origen
			$this->id_localidad_origen->LinkCustomAttributes = "";
			$this->id_localidad_origen->HrefValue = "";
			$this->id_localidad_origen->TooltipValue = "";

			// Origen
			$this->Origen->LinkCustomAttributes = "";
			$this->Origen->HrefValue = "";
			$this->Origen->TooltipValue = "";

			// id_localidad_destino
			$this->id_localidad_destino->LinkCustomAttributes = "";
			$this->id_localidad_destino->HrefValue = "";
			$this->id_localidad_destino->TooltipValue = "";

			// Destino
			$this->Destino->LinkCustomAttributes = "";
			$this->Destino->HrefValue = "";
			$this->Destino->TooltipValue = "";

			// Km_ini
			$this->Km_ini->LinkCustomAttributes = "";
			$this->Km_ini->HrefValue = "";
			$this->Km_ini->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// id_vehiculo
			$this->id_vehiculo->LinkCustomAttributes = "";
			$this->id_vehiculo->HrefValue = "";
			$this->id_vehiculo->TooltipValue = "";

			// id_tipo_carga
			$this->id_tipo_carga->LinkCustomAttributes = "";
			$this->id_tipo_carga->HrefValue = "";
			$this->id_tipo_carga->TooltipValue = "";

			// km_fin
			$this->km_fin->LinkCustomAttributes = "";
			$this->km_fin->HrefValue = "";
			$this->km_fin->TooltipValue = "";

			// fecha_fin
			$this->fecha_fin->LinkCustomAttributes = "";
			$this->fecha_fin->HrefValue = "";
			$this->fecha_fin->TooltipValue = "";

			// adelanto
			$this->adelanto->LinkCustomAttributes = "";
			$this->adelanto->HrefValue = "";
			$this->adelanto->TooltipValue = "";

			// kg_carga
			$this->kg_carga->LinkCustomAttributes = "";
			$this->kg_carga->HrefValue = "";
			$this->kg_carga->TooltipValue = "";

			// tarifa
			$this->tarifa->LinkCustomAttributes = "";
			$this->tarifa->HrefValue = "";
			$this->tarifa->TooltipValue = "";

			// porcentaje
			$this->porcentaje->LinkCustomAttributes = "";
			$this->porcentaje->HrefValue = "";
			$this->porcentaje->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// fecha_ini
			$this->fecha_ini->EditAttrs["class"] = "form-control";
			$this->fecha_ini->EditCustomAttributes = "";
			$this->fecha_ini->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_ini->CurrentValue, 7));
			$this->fecha_ini->PlaceHolder = ew_RemoveHtml($this->fecha_ini->FldCaption());

			// id_cliente
			$this->id_cliente->EditAttrs["class"] = "form-control";
			$this->id_cliente->EditCustomAttributes = "";
			if (trim(strval($this->id_cliente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `cuit_cuil` AS `DispFld`, `razon_social` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `clientes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_cliente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `razon_social` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_cliente->EditValue = $arwrk;

			// id_localidad_origen
			$this->id_localidad_origen->EditAttrs["class"] = "form-control";
			$this->id_localidad_origen->EditCustomAttributes = "";
			if (trim(strval($this->id_localidad_origen->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad_origen->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `cp` AS `DispFld`, `localidad` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `v_localidad_provincia`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_localidad_origen, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `localidad` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_localidad_origen->EditValue = $arwrk;

			// Origen
			$this->Origen->EditAttrs["class"] = "form-control";
			$this->Origen->EditCustomAttributes = "";
			$this->Origen->EditValue = ew_HtmlEncode($this->Origen->CurrentValue);
			$this->Origen->PlaceHolder = ew_RemoveHtml($this->Origen->FldCaption());

			// id_localidad_destino
			$this->id_localidad_destino->EditAttrs["class"] = "form-control";
			$this->id_localidad_destino->EditCustomAttributes = "";
			if (trim(strval($this->id_localidad_destino->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad_destino->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `cp` AS `DispFld`, `localidad` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `v_localidad_provincia`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_localidad_destino, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `localidad` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_localidad_destino->EditValue = $arwrk;

			// Destino
			$this->Destino->EditAttrs["class"] = "form-control";
			$this->Destino->EditCustomAttributes = "";
			$this->Destino->EditValue = ew_HtmlEncode($this->Destino->CurrentValue);
			$this->Destino->PlaceHolder = ew_RemoveHtml($this->Destino->FldCaption());

			// Km_ini
			$this->Km_ini->EditAttrs["class"] = "form-control";
			$this->Km_ini->EditCustomAttributes = "";
			$this->Km_ini->EditValue = ew_HtmlEncode($this->Km_ini->CurrentValue);
			$this->Km_ini->PlaceHolder = ew_RemoveHtml($this->Km_ini->FldCaption());

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = ew_HtmlEncode($this->estado->CurrentValue);
			$this->estado->PlaceHolder = ew_RemoveHtml($this->estado->FldCaption());

			// id_vehiculo
			$this->id_vehiculo->EditAttrs["class"] = "form-control";
			$this->id_vehiculo->EditCustomAttributes = "";
			if (trim(strval($this->id_vehiculo->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_vehiculo->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `vehiculos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_vehiculo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_vehiculo->EditValue = $arwrk;

			// id_tipo_carga
			$this->id_tipo_carga->EditAttrs["class"] = "form-control";
			$this->id_tipo_carga->EditCustomAttributes = "";
			if (trim(strval($this->id_tipo_carga->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_carga->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `Tipo_carga` AS `DispFld`, `precio_base` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_cargas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_carga, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Tipo_carga` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][2] = ew_FormatCurrency($arwrk[$rowcntwrk][2], 2, 0, 0, -1);
			}
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_tipo_carga->EditValue = $arwrk;

			// km_fin
			$this->km_fin->EditAttrs["class"] = "form-control";
			$this->km_fin->EditCustomAttributes = "";
			$this->km_fin->EditValue = ew_HtmlEncode($this->km_fin->CurrentValue);
			$this->km_fin->PlaceHolder = ew_RemoveHtml($this->km_fin->FldCaption());

			// fecha_fin
			$this->fecha_fin->EditAttrs["class"] = "form-control";
			$this->fecha_fin->EditCustomAttributes = "";
			$this->fecha_fin->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_fin->CurrentValue, 7));
			$this->fecha_fin->PlaceHolder = ew_RemoveHtml($this->fecha_fin->FldCaption());

			// adelanto
			$this->adelanto->EditAttrs["class"] = "form-control";
			$this->adelanto->EditCustomAttributes = "";
			$this->adelanto->EditValue = ew_HtmlEncode($this->adelanto->CurrentValue);
			$this->adelanto->PlaceHolder = ew_RemoveHtml($this->adelanto->FldCaption());
			if (strval($this->adelanto->EditValue) <> "" && is_numeric($this->adelanto->EditValue)) $this->adelanto->EditValue = ew_FormatNumber($this->adelanto->EditValue, -2, -2, -2, -2);

			// kg_carga
			$this->kg_carga->EditAttrs["class"] = "form-control";
			$this->kg_carga->EditCustomAttributes = "";
			$this->kg_carga->EditValue = ew_HtmlEncode($this->kg_carga->CurrentValue);
			$this->kg_carga->PlaceHolder = ew_RemoveHtml($this->kg_carga->FldCaption());

			// tarifa
			$this->tarifa->EditAttrs["class"] = "form-control";
			$this->tarifa->EditCustomAttributes = "";
			$this->tarifa->EditValue = ew_HtmlEncode($this->tarifa->CurrentValue);
			$this->tarifa->PlaceHolder = ew_RemoveHtml($this->tarifa->FldCaption());
			if (strval($this->tarifa->EditValue) <> "" && is_numeric($this->tarifa->EditValue)) $this->tarifa->EditValue = ew_FormatNumber($this->tarifa->EditValue, -2, -2, -2, -2);

			// porcentaje
			$this->porcentaje->EditAttrs["class"] = "form-control";
			$this->porcentaje->EditCustomAttributes = "";
			$this->porcentaje->EditValue = ew_HtmlEncode($this->porcentaje->CurrentValue);
			$this->porcentaje->PlaceHolder = ew_RemoveHtml($this->porcentaje->FldCaption());
			if (strval($this->porcentaje->EditValue) <> "" && is_numeric($this->porcentaje->EditValue)) $this->porcentaje->EditValue = ew_FormatNumber($this->porcentaje->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// fecha_ini

			$this->fecha_ini->HrefValue = "";

			// id_cliente
			$this->id_cliente->HrefValue = "";

			// id_localidad_origen
			$this->id_localidad_origen->HrefValue = "";

			// Origen
			$this->Origen->HrefValue = "";

			// id_localidad_destino
			$this->id_localidad_destino->HrefValue = "";

			// Destino
			$this->Destino->HrefValue = "";

			// Km_ini
			$this->Km_ini->HrefValue = "";

			// estado
			$this->estado->HrefValue = "";

			// id_vehiculo
			$this->id_vehiculo->HrefValue = "";

			// id_tipo_carga
			$this->id_tipo_carga->HrefValue = "";

			// km_fin
			$this->km_fin->HrefValue = "";

			// fecha_fin
			$this->fecha_fin->HrefValue = "";

			// adelanto
			$this->adelanto->HrefValue = "";

			// kg_carga
			$this->kg_carga->HrefValue = "";

			// tarifa
			$this->tarifa->HrefValue = "";

			// porcentaje
			$this->porcentaje->HrefValue = "";
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
		if (!ew_CheckEuroDate($this->fecha_ini->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_ini->FldErrMsg());
		}
		if (!$this->id_cliente->FldIsDetailKey && !is_null($this->id_cliente->FormValue) && $this->id_cliente->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_cliente->FldCaption(), $this->id_cliente->ReqErrMsg));
		}
		if (!$this->id_localidad_origen->FldIsDetailKey && !is_null($this->id_localidad_origen->FormValue) && $this->id_localidad_origen->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_localidad_origen->FldCaption(), $this->id_localidad_origen->ReqErrMsg));
		}
		if (!$this->Origen->FldIsDetailKey && !is_null($this->Origen->FormValue) && $this->Origen->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Origen->FldCaption(), $this->Origen->ReqErrMsg));
		}
		if (!$this->id_localidad_destino->FldIsDetailKey && !is_null($this->id_localidad_destino->FormValue) && $this->id_localidad_destino->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_localidad_destino->FldCaption(), $this->id_localidad_destino->ReqErrMsg));
		}
		if (!$this->Destino->FldIsDetailKey && !is_null($this->Destino->FormValue) && $this->Destino->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Destino->FldCaption(), $this->Destino->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->Km_ini->FormValue)) {
			ew_AddMessage($gsFormError, $this->Km_ini->FldErrMsg());
		}
		if (!$this->id_vehiculo->FldIsDetailKey && !is_null($this->id_vehiculo->FormValue) && $this->id_vehiculo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_vehiculo->FldCaption(), $this->id_vehiculo->ReqErrMsg));
		}
		if (!$this->id_tipo_carga->FldIsDetailKey && !is_null($this->id_tipo_carga->FormValue) && $this->id_tipo_carga->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_tipo_carga->FldCaption(), $this->id_tipo_carga->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->km_fin->FormValue)) {
			ew_AddMessage($gsFormError, $this->km_fin->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_fin->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_fin->FldErrMsg());
		}
		if (!ew_CheckNumber($this->adelanto->FormValue)) {
			ew_AddMessage($gsFormError, $this->adelanto->FldErrMsg());
		}
		if (!ew_CheckInteger($this->kg_carga->FormValue)) {
			ew_AddMessage($gsFormError, $this->kg_carga->FldErrMsg());
		}
		if (!$this->tarifa->FldIsDetailKey && !is_null($this->tarifa->FormValue) && $this->tarifa->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tarifa->FldCaption(), $this->tarifa->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->tarifa->FormValue)) {
			ew_AddMessage($gsFormError, $this->tarifa->FldErrMsg());
		}
		if (!$this->porcentaje->FldIsDetailKey && !is_null($this->porcentaje->FormValue) && $this->porcentaje->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->porcentaje->FldCaption(), $this->porcentaje->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->porcentaje->FormValue)) {
			ew_AddMessage($gsFormError, $this->porcentaje->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("gastos", $DetailTblVar) && $GLOBALS["gastos"]->DetailEdit) {
			if (!isset($GLOBALS["gastos_grid"])) $GLOBALS["gastos_grid"] = new cgastos_grid(); // get detail page object
			$GLOBALS["gastos_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// fecha_ini
			$this->fecha_ini->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_ini->CurrentValue, 7), NULL, $this->fecha_ini->ReadOnly);

			// id_cliente
			$this->id_cliente->SetDbValueDef($rsnew, $this->id_cliente->CurrentValue, NULL, $this->id_cliente->ReadOnly);

			// id_localidad_origen
			$this->id_localidad_origen->SetDbValueDef($rsnew, $this->id_localidad_origen->CurrentValue, NULL, $this->id_localidad_origen->ReadOnly);

			// Origen
			$this->Origen->SetDbValueDef($rsnew, $this->Origen->CurrentValue, NULL, $this->Origen->ReadOnly);

			// id_localidad_destino
			$this->id_localidad_destino->SetDbValueDef($rsnew, $this->id_localidad_destino->CurrentValue, NULL, $this->id_localidad_destino->ReadOnly);

			// Destino
			$this->Destino->SetDbValueDef($rsnew, $this->Destino->CurrentValue, NULL, $this->Destino->ReadOnly);

			// Km_ini
			$this->Km_ini->SetDbValueDef($rsnew, $this->Km_ini->CurrentValue, NULL, $this->Km_ini->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, NULL, $this->estado->ReadOnly);

			// id_vehiculo
			$this->id_vehiculo->SetDbValueDef($rsnew, $this->id_vehiculo->CurrentValue, NULL, $this->id_vehiculo->ReadOnly);

			// id_tipo_carga
			$this->id_tipo_carga->SetDbValueDef($rsnew, $this->id_tipo_carga->CurrentValue, NULL, $this->id_tipo_carga->ReadOnly);

			// km_fin
			$this->km_fin->SetDbValueDef($rsnew, $this->km_fin->CurrentValue, NULL, $this->km_fin->ReadOnly);

			// fecha_fin
			$this->fecha_fin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_fin->CurrentValue, 7), NULL, $this->fecha_fin->ReadOnly);

			// adelanto
			$this->adelanto->SetDbValueDef($rsnew, $this->adelanto->CurrentValue, NULL, $this->adelanto->ReadOnly);

			// kg_carga
			$this->kg_carga->SetDbValueDef($rsnew, $this->kg_carga->CurrentValue, NULL, $this->kg_carga->ReadOnly);

			// tarifa
			$this->tarifa->SetDbValueDef($rsnew, $this->tarifa->CurrentValue, NULL, $this->tarifa->ReadOnly);

			// porcentaje
			$this->porcentaje->SetDbValueDef($rsnew, $this->porcentaje->CurrentValue, NULL, $this->porcentaje->ReadOnly);

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

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("gastos", $DetailTblVar) && $GLOBALS["gastos"]->DetailEdit) {
						if (!isset($GLOBALS["gastos_grid"])) $GLOBALS["gastos_grid"] = new cgastos_grid(); // Get detail page object
						$EditRow = $GLOBALS["gastos_grid"]->GridUpdate();
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
			if (in_array("gastos", $DetailTblVar)) {
				if (!isset($GLOBALS["gastos_grid"]))
					$GLOBALS["gastos_grid"] = new cgastos_grid;
				if ($GLOBALS["gastos_grid"]->DetailEdit) {
					$GLOBALS["gastos_grid"]->CurrentMode = "edit";
					$GLOBALS["gastos_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["gastos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["gastos_grid"]->setStartRecordNumber(1);
					$GLOBALS["gastos_grid"]->id_hoja_ruta->FldIsDetailKey = TRUE;
					$GLOBALS["gastos_grid"]->id_hoja_ruta->CurrentValue = $this->codigo->CurrentValue;
					$GLOBALS["gastos_grid"]->id_hoja_ruta->setSessionValue($GLOBALS["gastos_grid"]->id_hoja_ruta->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "hoja_rutaslist.php", "", $this->TableVar, TRUE);
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
if (!isset($hoja_rutas_edit)) $hoja_rutas_edit = new choja_rutas_edit();

// Page init
$hoja_rutas_edit->Page_Init();

// Page main
$hoja_rutas_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hoja_rutas_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var hoja_rutas_edit = new ew_Page("hoja_rutas_edit");
hoja_rutas_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = hoja_rutas_edit.PageID; // For backward compatibility

// Form object
var fhoja_rutasedit = new ew_Form("fhoja_rutasedit");

// Validate form
fhoja_rutasedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha_ini");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_rutas->fecha_ini->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_cliente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $hoja_rutas->id_cliente->FldCaption(), $hoja_rutas->id_cliente->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_localidad_origen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $hoja_rutas->id_localidad_origen->FldCaption(), $hoja_rutas->id_localidad_origen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Origen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $hoja_rutas->Origen->FldCaption(), $hoja_rutas->Origen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_localidad_destino");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $hoja_rutas->id_localidad_destino->FldCaption(), $hoja_rutas->id_localidad_destino->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Destino");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $hoja_rutas->Destino->FldCaption(), $hoja_rutas->Destino->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Km_ini");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_rutas->Km_ini->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_vehiculo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $hoja_rutas->id_vehiculo->FldCaption(), $hoja_rutas->id_vehiculo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_tipo_carga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $hoja_rutas->id_tipo_carga->FldCaption(), $hoja_rutas->id_tipo_carga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_km_fin");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_rutas->km_fin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_fin");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_rutas->fecha_fin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_adelanto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_rutas->adelanto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kg_carga");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_rutas->kg_carga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tarifa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $hoja_rutas->tarifa->FldCaption(), $hoja_rutas->tarifa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tarifa");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_rutas->tarifa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_porcentaje");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $hoja_rutas->porcentaje->FldCaption(), $hoja_rutas->porcentaje->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_porcentaje");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_rutas->porcentaje->FldErrMsg()) ?>");

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
fhoja_rutasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoja_rutasedit.ValidateRequired = true;
<?php } else { ?>
fhoja_rutasedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhoja_rutasedit.Lists["x_id_cliente"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":true,"DisplayFields":["x_cuit_cuil","x_razon_social","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutasedit.Lists["x_id_localidad_origen"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_cp","x_localidad","x_provincia",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutasedit.Lists["x_id_localidad_destino"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_cp","x_localidad","x_provincia",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutasedit.Lists["x_id_vehiculo"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Patente","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutasedit.Lists["x_id_tipo_carga"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":true,"DisplayFields":["x_Tipo_carga","x_precio_base","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $hoja_rutas_edit->ShowPageHeader(); ?>
<?php
$hoja_rutas_edit->ShowMessage();
?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($hoja_rutas_edit->Pager)) $hoja_rutas_edit->Pager = new cNumericPager($hoja_rutas_edit->StartRec, $hoja_rutas_edit->DisplayRecs, $hoja_rutas_edit->TotalRecs, $hoja_rutas_edit->RecRange) ?>
<?php if ($hoja_rutas_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($hoja_rutas_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_edit->PageUrl() ?>start=<?php echo $hoja_rutas_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_edit->PageUrl() ?>start=<?php echo $hoja_rutas_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($hoja_rutas_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $hoja_rutas_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_edit->PageUrl() ?>start=<?php echo $hoja_rutas_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_edit->PageUrl() ?>start=<?php echo $hoja_rutas_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<form name="fhoja_rutasedit" id="fhoja_rutasedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($hoja_rutas_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $hoja_rutas_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="hoja_rutas">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($hoja_rutas->fecha_ini->Visible) { // fecha_ini ?>
	<div id="r_fecha_ini" class="form-group">
		<label id="elh_hoja_rutas_fecha_ini" for="x_fecha_ini" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->fecha_ini->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->fecha_ini->CellAttributes() ?>>
<span id="el_hoja_rutas_fecha_ini">
<input type="text" data-field="x_fecha_ini" name="x_fecha_ini" id="x_fecha_ini" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->fecha_ini->PlaceHolder) ?>" value="<?php echo $hoja_rutas->fecha_ini->EditValue ?>"<?php echo $hoja_rutas->fecha_ini->EditAttributes() ?>>
<?php if (!$hoja_rutas->fecha_ini->ReadOnly && !$hoja_rutas->fecha_ini->Disabled && !isset($hoja_rutas->fecha_ini->EditAttrs["readonly"]) && !isset($hoja_rutas->fecha_ini->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fhoja_rutasedit", "x_fecha_ini", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $hoja_rutas->fecha_ini->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->id_cliente->Visible) { // id_cliente ?>
	<div id="r_id_cliente" class="form-group">
		<label id="elh_hoja_rutas_id_cliente" for="x_id_cliente" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->id_cliente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->id_cliente->CellAttributes() ?>>
<span id="el_hoja_rutas_id_cliente">
<?php $hoja_rutas->id_cliente->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$hoja_rutas->id_cliente->EditAttrs["onchange"]; ?>
<select data-field="x_id_cliente" id="x_id_cliente" name="x_id_cliente"<?php echo $hoja_rutas->id_cliente->EditAttributes() ?>>
<?php
if (is_array($hoja_rutas->id_cliente->EditValue)) {
	$arwrk = $hoja_rutas->id_cliente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hoja_rutas->id_cliente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$hoja_rutas->id_cliente) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $hoja_rutas->id_cliente->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_cliente',url:'clientesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_cliente"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $hoja_rutas->id_cliente->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `codigo`, `cuit_cuil` AS `DispFld`, `razon_social` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `clientes`";
$sWhereWrk = "";

// Call Lookup selecting
$hoja_rutas->Lookup_Selecting($hoja_rutas->id_cliente, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `razon_social` ASC";
?>
<input type="hidden" name="s_x_id_cliente" id="s_x_id_cliente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
<input type="hidden" name="ln_x_id_cliente" id="ln_x_id_cliente" value="x_Origen,x_Destino,x_id_localidad_origen,x_id_localidad_destino">
</span>
<?php echo $hoja_rutas->id_cliente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->id_localidad_origen->Visible) { // id_localidad_origen ?>
	<div id="r_id_localidad_origen" class="form-group">
		<label id="elh_hoja_rutas_id_localidad_origen" for="x_id_localidad_origen" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->id_localidad_origen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->id_localidad_origen->CellAttributes() ?>>
<span id="el_hoja_rutas_id_localidad_origen">
<select data-field="x_id_localidad_origen" id="x_id_localidad_origen" name="x_id_localidad_origen"<?php echo $hoja_rutas->id_localidad_origen->EditAttributes() ?>>
<?php
if (is_array($hoja_rutas->id_localidad_origen->EditValue)) {
	$arwrk = $hoja_rutas->id_localidad_origen->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hoja_rutas->id_localidad_origen->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$hoja_rutas->id_localidad_origen) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$hoja_rutas->id_localidad_origen) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `codigo`, `cp` AS `DispFld`, `localidad` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
$sWhereWrk = "";

// Call Lookup selecting
$hoja_rutas->Lookup_Selecting($hoja_rutas->id_localidad_origen, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `localidad` ASC";
?>
<input type="hidden" name="s_x_id_localidad_origen" id="s_x_id_localidad_origen" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $hoja_rutas->id_localidad_origen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->Origen->Visible) { // Origen ?>
	<div id="r_Origen" class="form-group">
		<label id="elh_hoja_rutas_Origen" for="x_Origen" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->Origen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->Origen->CellAttributes() ?>>
<span id="el_hoja_rutas_Origen">
<input type="text" data-field="x_Origen" name="x_Origen" id="x_Origen" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->Origen->PlaceHolder) ?>" value="<?php echo $hoja_rutas->Origen->EditValue ?>"<?php echo $hoja_rutas->Origen->EditAttributes() ?>>
</span>
<?php echo $hoja_rutas->Origen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->id_localidad_destino->Visible) { // id_localidad_destino ?>
	<div id="r_id_localidad_destino" class="form-group">
		<label id="elh_hoja_rutas_id_localidad_destino" for="x_id_localidad_destino" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->id_localidad_destino->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->id_localidad_destino->CellAttributes() ?>>
<span id="el_hoja_rutas_id_localidad_destino">
<select data-field="x_id_localidad_destino" id="x_id_localidad_destino" name="x_id_localidad_destino"<?php echo $hoja_rutas->id_localidad_destino->EditAttributes() ?>>
<?php
if (is_array($hoja_rutas->id_localidad_destino->EditValue)) {
	$arwrk = $hoja_rutas->id_localidad_destino->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hoja_rutas->id_localidad_destino->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$hoja_rutas->id_localidad_destino) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$hoja_rutas->id_localidad_destino) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `codigo`, `cp` AS `DispFld`, `localidad` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
$sWhereWrk = "";

// Call Lookup selecting
$hoja_rutas->Lookup_Selecting($hoja_rutas->id_localidad_destino, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `localidad` ASC";
?>
<input type="hidden" name="s_x_id_localidad_destino" id="s_x_id_localidad_destino" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $hoja_rutas->id_localidad_destino->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->Destino->Visible) { // Destino ?>
	<div id="r_Destino" class="form-group">
		<label id="elh_hoja_rutas_Destino" for="x_Destino" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->Destino->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->Destino->CellAttributes() ?>>
<span id="el_hoja_rutas_Destino">
<input type="text" data-field="x_Destino" name="x_Destino" id="x_Destino" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->Destino->PlaceHolder) ?>" value="<?php echo $hoja_rutas->Destino->EditValue ?>"<?php echo $hoja_rutas->Destino->EditAttributes() ?>>
</span>
<?php echo $hoja_rutas->Destino->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->Km_ini->Visible) { // Km_ini ?>
	<div id="r_Km_ini" class="form-group">
		<label id="elh_hoja_rutas_Km_ini" for="x_Km_ini" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->Km_ini->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->Km_ini->CellAttributes() ?>>
<span id="el_hoja_rutas_Km_ini">
<input type="text" data-field="x_Km_ini" name="x_Km_ini" id="x_Km_ini" size="30" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->Km_ini->PlaceHolder) ?>" value="<?php echo $hoja_rutas->Km_ini->EditValue ?>"<?php echo $hoja_rutas->Km_ini->EditAttributes() ?>>
</span>
<?php echo $hoja_rutas->Km_ini->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_hoja_rutas_estado" for="x_estado" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->estado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->estado->CellAttributes() ?>>
<span id="el_hoja_rutas_estado">
<input type="text" data-field="x_estado" name="x_estado" id="x_estado" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->estado->PlaceHolder) ?>" value="<?php echo $hoja_rutas->estado->EditValue ?>"<?php echo $hoja_rutas->estado->EditAttributes() ?>>
</span>
<?php echo $hoja_rutas->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->id_vehiculo->Visible) { // id_vehiculo ?>
	<div id="r_id_vehiculo" class="form-group">
		<label id="elh_hoja_rutas_id_vehiculo" for="x_id_vehiculo" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->id_vehiculo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->id_vehiculo->CellAttributes() ?>>
<span id="el_hoja_rutas_id_vehiculo">
<select data-field="x_id_vehiculo" id="x_id_vehiculo" name="x_id_vehiculo"<?php echo $hoja_rutas->id_vehiculo->EditAttributes() ?>>
<?php
if (is_array($hoja_rutas->id_vehiculo->EditValue)) {
	$arwrk = $hoja_rutas->id_vehiculo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hoja_rutas->id_vehiculo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$hoja_rutas->id_vehiculo) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $hoja_rutas->id_vehiculo->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_vehiculo',url:'vehiculosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_vehiculo"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $hoja_rutas->id_vehiculo->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
$sWhereWrk = "";

// Call Lookup selecting
$hoja_rutas->Lookup_Selecting($hoja_rutas->id_vehiculo, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x_id_vehiculo" id="s_x_id_vehiculo" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $hoja_rutas->id_vehiculo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->id_tipo_carga->Visible) { // id_tipo_carga ?>
	<div id="r_id_tipo_carga" class="form-group">
		<label id="elh_hoja_rutas_id_tipo_carga" for="x_id_tipo_carga" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->id_tipo_carga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->id_tipo_carga->CellAttributes() ?>>
<span id="el_hoja_rutas_id_tipo_carga">
<?php $hoja_rutas->id_tipo_carga->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$hoja_rutas->id_tipo_carga->EditAttrs["onchange"]; ?>
<select data-field="x_id_tipo_carga" id="x_id_tipo_carga" name="x_id_tipo_carga"<?php echo $hoja_rutas->id_tipo_carga->EditAttributes() ?>>
<?php
if (is_array($hoja_rutas->id_tipo_carga->EditValue)) {
	$arwrk = $hoja_rutas->id_tipo_carga->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hoja_rutas->id_tipo_carga->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$hoja_rutas->id_tipo_carga) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $hoja_rutas->id_tipo_carga->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_tipo_carga',url:'tipo_cargasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_tipo_carga"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $hoja_rutas->id_tipo_carga->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `codigo`, `Tipo_carga` AS `DispFld`, `precio_base` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_cargas`";
$sWhereWrk = "";

// Call Lookup selecting
$hoja_rutas->Lookup_Selecting($hoja_rutas->id_tipo_carga, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Tipo_carga` ASC";
?>
<input type="hidden" name="s_x_id_tipo_carga" id="s_x_id_tipo_carga" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
<input type="hidden" name="ln_x_id_tipo_carga" id="ln_x_id_tipo_carga" value="x_tarifa,x_porcentaje">
</span>
<?php echo $hoja_rutas->id_tipo_carga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->km_fin->Visible) { // km_fin ?>
	<div id="r_km_fin" class="form-group">
		<label id="elh_hoja_rutas_km_fin" for="x_km_fin" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->km_fin->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->km_fin->CellAttributes() ?>>
<span id="el_hoja_rutas_km_fin">
<input type="text" data-field="x_km_fin" name="x_km_fin" id="x_km_fin" size="30" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->km_fin->PlaceHolder) ?>" value="<?php echo $hoja_rutas->km_fin->EditValue ?>"<?php echo $hoja_rutas->km_fin->EditAttributes() ?>>
</span>
<?php echo $hoja_rutas->km_fin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->fecha_fin->Visible) { // fecha_fin ?>
	<div id="r_fecha_fin" class="form-group">
		<label id="elh_hoja_rutas_fecha_fin" for="x_fecha_fin" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->fecha_fin->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->fecha_fin->CellAttributes() ?>>
<span id="el_hoja_rutas_fecha_fin">
<input type="text" data-field="x_fecha_fin" name="x_fecha_fin" id="x_fecha_fin" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->fecha_fin->PlaceHolder) ?>" value="<?php echo $hoja_rutas->fecha_fin->EditValue ?>"<?php echo $hoja_rutas->fecha_fin->EditAttributes() ?>>
<?php if (!$hoja_rutas->fecha_fin->ReadOnly && !$hoja_rutas->fecha_fin->Disabled && !isset($hoja_rutas->fecha_fin->EditAttrs["readonly"]) && !isset($hoja_rutas->fecha_fin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fhoja_rutasedit", "x_fecha_fin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $hoja_rutas->fecha_fin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->adelanto->Visible) { // adelanto ?>
	<div id="r_adelanto" class="form-group">
		<label id="elh_hoja_rutas_adelanto" for="x_adelanto" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->adelanto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->adelanto->CellAttributes() ?>>
<span id="el_hoja_rutas_adelanto">
<input type="text" data-field="x_adelanto" name="x_adelanto" id="x_adelanto" size="30" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->adelanto->PlaceHolder) ?>" value="<?php echo $hoja_rutas->adelanto->EditValue ?>"<?php echo $hoja_rutas->adelanto->EditAttributes() ?>>
</span>
<?php echo $hoja_rutas->adelanto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->kg_carga->Visible) { // kg_carga ?>
	<div id="r_kg_carga" class="form-group">
		<label id="elh_hoja_rutas_kg_carga" for="x_kg_carga" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->kg_carga->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->kg_carga->CellAttributes() ?>>
<span id="el_hoja_rutas_kg_carga">
<input type="text" data-field="x_kg_carga" name="x_kg_carga" id="x_kg_carga" size="30" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->kg_carga->PlaceHolder) ?>" value="<?php echo $hoja_rutas->kg_carga->EditValue ?>"<?php echo $hoja_rutas->kg_carga->EditAttributes() ?>>
</span>
<?php echo $hoja_rutas->kg_carga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->tarifa->Visible) { // tarifa ?>
	<div id="r_tarifa" class="form-group">
		<label id="elh_hoja_rutas_tarifa" for="x_tarifa" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->tarifa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->tarifa->CellAttributes() ?>>
<span id="el_hoja_rutas_tarifa">
<input type="text" data-field="x_tarifa" name="x_tarifa" id="x_tarifa" size="30" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->tarifa->PlaceHolder) ?>" value="<?php echo $hoja_rutas->tarifa->EditValue ?>"<?php echo $hoja_rutas->tarifa->EditAttributes() ?>>
</span>
<?php echo $hoja_rutas->tarifa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_rutas->porcentaje->Visible) { // porcentaje ?>
	<div id="r_porcentaje" class="form-group">
		<label id="elh_hoja_rutas_porcentaje" for="x_porcentaje" class="col-sm-2 control-label ewLabel"><?php echo $hoja_rutas->porcentaje->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_rutas->porcentaje->CellAttributes() ?>>
<span id="el_hoja_rutas_porcentaje">
<input type="text" data-field="x_porcentaje" name="x_porcentaje" id="x_porcentaje" size="30" placeholder="<?php echo ew_HtmlEncode($hoja_rutas->porcentaje->PlaceHolder) ?>" value="<?php echo $hoja_rutas->porcentaje->EditValue ?>"<?php echo $hoja_rutas->porcentaje->EditAttributes() ?>>
</span>
<?php echo $hoja_rutas->porcentaje->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_codigo" name="x_codigo" id="x_codigo" value="<?php echo ew_HtmlEncode($hoja_rutas->codigo->CurrentValue) ?>">
<?php
	if (in_array("gastos", explode(",", $hoja_rutas->getCurrentDetailTable())) && $gastos->DetailEdit) {
?>
<?php if ($hoja_rutas->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("gastos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "gastosgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
<?php if (!isset($hoja_rutas_edit->Pager)) $hoja_rutas_edit->Pager = new cNumericPager($hoja_rutas_edit->StartRec, $hoja_rutas_edit->DisplayRecs, $hoja_rutas_edit->TotalRecs, $hoja_rutas_edit->RecRange) ?>
<?php if ($hoja_rutas_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($hoja_rutas_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_edit->PageUrl() ?>start=<?php echo $hoja_rutas_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_edit->PageUrl() ?>start=<?php echo $hoja_rutas_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($hoja_rutas_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $hoja_rutas_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_edit->PageUrl() ?>start=<?php echo $hoja_rutas_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($hoja_rutas_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $hoja_rutas_edit->PageUrl() ?>start=<?php echo $hoja_rutas_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<script type="text/javascript">
fhoja_rutasedit.Init();
</script>
<?php
$hoja_rutas_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$hoja_rutas_edit->Page_Terminate();
?>
