<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "hoja_mantenimientosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "gastos_mantenimientosgridcls.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$hoja_mantenimientos_add = NULL; // Initialize page object first

class choja_mantenimientos_add extends choja_mantenimientos {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'hoja_mantenimientos';

	// Page object name
	var $PageObjName = 'hoja_mantenimientos_add';

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

		// Table object (hoja_mantenimientos)
		if (!isset($GLOBALS["hoja_mantenimientos"]) || get_class($GLOBALS["hoja_mantenimientos"]) == "choja_mantenimientos") {
			$GLOBALS["hoja_mantenimientos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["hoja_mantenimientos"];
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
			define("EW_TABLE_NAME", 'hoja_mantenimientos', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("hoja_mantenimientoslist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("hoja_mantenimientoslist.php"));
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

			// Process auto fill for detail table 'gastos_mantenimientos'
			if (@$_POST["grid"] == "fgastos_mantenimientosgrid") {
				if (!isset($GLOBALS["gastos_mantenimientos_grid"])) $GLOBALS["gastos_mantenimientos_grid"] = new cgastos_mantenimientos_grid;
				$GLOBALS["gastos_mantenimientos_grid"]->Page_Init();
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
		global $EW_EXPORT, $hoja_mantenimientos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($hoja_mantenimientos);
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
					$this->Page_Terminate("hoja_mantenimientoslist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "hoja_mantenimientosview.php")
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
		$this->fecha_ini->CurrentValue = date('d/m/Y');
		$this->fecha_fin->CurrentValue = date('d/m/Y');
		$this->id_vehiculo->CurrentValue = NULL;
		$this->id_vehiculo->OldValue = $this->id_vehiculo->CurrentValue;
		$this->id_taller->CurrentValue = NULL;
		$this->id_taller->OldValue = $this->id_taller->CurrentValue;
		$this->id_tipo_mantenimiento->CurrentValue = NULL;
		$this->id_tipo_mantenimiento->OldValue = $this->id_tipo_mantenimiento->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->fecha_ini->FldIsDetailKey) {
			$this->fecha_ini->setFormValue($objForm->GetValue("x_fecha_ini"));
		}
		if (!$this->fecha_fin->FldIsDetailKey) {
			$this->fecha_fin->setFormValue($objForm->GetValue("x_fecha_fin"));
		}
		if (!$this->id_vehiculo->FldIsDetailKey) {
			$this->id_vehiculo->setFormValue($objForm->GetValue("x_id_vehiculo"));
		}
		if (!$this->id_taller->FldIsDetailKey) {
			$this->id_taller->setFormValue($objForm->GetValue("x_id_taller"));
		}
		if (!$this->id_tipo_mantenimiento->FldIsDetailKey) {
			$this->id_tipo_mantenimiento->setFormValue($objForm->GetValue("x_id_tipo_mantenimiento"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->fecha_ini->CurrentValue = $this->fecha_ini->FormValue;
		$this->fecha_fin->CurrentValue = $this->fecha_fin->FormValue;
		$this->id_vehiculo->CurrentValue = $this->id_vehiculo->FormValue;
		$this->id_taller->CurrentValue = $this->id_taller->FormValue;
		$this->id_tipo_mantenimiento->CurrentValue = $this->id_tipo_mantenimiento->FormValue;
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
		$this->codigo->setDbValue($rs->fields('codigo'));
		$this->fecha_ini->setDbValue($rs->fields('fecha_ini'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->id_vehiculo->setDbValue($rs->fields('id_vehiculo'));
		if (array_key_exists('EV__id_vehiculo', $rs->fields)) {
			$this->id_vehiculo->VirtualValue = $rs->fields('EV__id_vehiculo'); // Set up virtual field value
		} else {
			$this->id_vehiculo->VirtualValue = ""; // Clear value
		}
		$this->id_taller->setDbValue($rs->fields('id_taller'));
		if (array_key_exists('EV__id_taller', $rs->fields)) {
			$this->id_taller->VirtualValue = $rs->fields('EV__id_taller'); // Set up virtual field value
		} else {
			$this->id_taller->VirtualValue = ""; // Clear value
		}
		$this->id_tipo_mantenimiento->setDbValue($rs->fields('id_tipo_mantenimiento'));
		if (array_key_exists('EV__id_tipo_mantenimiento', $rs->fields)) {
			$this->id_tipo_mantenimiento->VirtualValue = $rs->fields('EV__id_tipo_mantenimiento'); // Set up virtual field value
		} else {
			$this->id_tipo_mantenimiento->VirtualValue = ""; // Clear value
		}
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->fecha_ini->DbValue = $row['fecha_ini'];
		$this->fecha_fin->DbValue = $row['fecha_fin'];
		$this->id_vehiculo->DbValue = $row['id_vehiculo'];
		$this->id_taller->DbValue = $row['id_taller'];
		$this->id_tipo_mantenimiento->DbValue = $row['id_tipo_mantenimiento'];
		$this->id_usuario->DbValue = $row['id_usuario'];
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
		// fecha_ini
		// fecha_fin
		// id_vehiculo
		// id_taller
		// id_tipo_mantenimiento
		// id_usuario

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// fecha_ini
			$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
			$this->fecha_ini->ViewCustomAttributes = "";

			// fecha_fin
			$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
			$this->fecha_fin->ViewCustomAttributes = "";

			// id_vehiculo
			if ($this->id_vehiculo->VirtualValue <> "") {
				$this->id_vehiculo->ViewValue = $this->id_vehiculo->VirtualValue;
			} else {
			if (strval($this->id_vehiculo->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_vehiculo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_vehiculo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Patente` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_vehiculo->ViewValue = $rswrk->fields('DispFld');
					$this->id_vehiculo->ViewValue .= ew_ValueSeparator(1,$this->id_vehiculo) . $rswrk->fields('Disp2Fld');
					$this->id_vehiculo->ViewValue .= ew_ValueSeparator(2,$this->id_vehiculo) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_vehiculo->ViewValue = $this->id_vehiculo->CurrentValue;
				}
			} else {
				$this->id_vehiculo->ViewValue = NULL;
			}
			}
			$this->id_vehiculo->ViewCustomAttributes = "";

			// id_taller
			if ($this->id_taller->VirtualValue <> "") {
				$this->id_taller->ViewValue = $this->id_taller->VirtualValue;
			} else {
			if (strval($this->id_taller->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_taller->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `talleres`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_taller, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `taller` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_taller->ViewValue = $rswrk->fields('DispFld');
					$this->id_taller->ViewValue .= ew_ValueSeparator(1,$this->id_taller) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_taller->ViewValue = $this->id_taller->CurrentValue;
				}
			} else {
				$this->id_taller->ViewValue = NULL;
			}
			}
			$this->id_taller->ViewCustomAttributes = "";

			// id_tipo_mantenimiento
			if ($this->id_tipo_mantenimiento->VirtualValue <> "") {
				$this->id_tipo_mantenimiento->ViewValue = $this->id_tipo_mantenimiento->VirtualValue;
			} else {
			if (strval($this->id_tipo_mantenimiento->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_mantenimiento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_mantenimientos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_mantenimiento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_mantenimiento` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_mantenimiento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_tipo_mantenimiento->ViewValue = $this->id_tipo_mantenimiento->CurrentValue;
				}
			} else {
				$this->id_tipo_mantenimiento->ViewValue = NULL;
			}
			}
			$this->id_tipo_mantenimiento->ViewCustomAttributes = "";

			// fecha_ini
			$this->fecha_ini->LinkCustomAttributes = "";
			$this->fecha_ini->HrefValue = "";
			$this->fecha_ini->TooltipValue = "";

			// fecha_fin
			$this->fecha_fin->LinkCustomAttributes = "";
			$this->fecha_fin->HrefValue = "";
			$this->fecha_fin->TooltipValue = "";

			// id_vehiculo
			$this->id_vehiculo->LinkCustomAttributes = "";
			$this->id_vehiculo->HrefValue = "";
			$this->id_vehiculo->TooltipValue = "";

			// id_taller
			$this->id_taller->LinkCustomAttributes = "";
			$this->id_taller->HrefValue = "";
			$this->id_taller->TooltipValue = "";

			// id_tipo_mantenimiento
			$this->id_tipo_mantenimiento->LinkCustomAttributes = "";
			$this->id_tipo_mantenimiento->HrefValue = "";
			$this->id_tipo_mantenimiento->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// fecha_ini
			$this->fecha_ini->EditAttrs["class"] = "form-control";
			$this->fecha_ini->EditCustomAttributes = "";
			$this->fecha_ini->EditValue = ew_HtmlEncode($this->fecha_ini->CurrentValue);
			$this->fecha_ini->PlaceHolder = ew_RemoveHtml($this->fecha_ini->FldCaption());

			// fecha_fin
			$this->fecha_fin->EditAttrs["class"] = "form-control";
			$this->fecha_fin->EditCustomAttributes = "";
			$this->fecha_fin->EditValue = ew_HtmlEncode($this->fecha_fin->CurrentValue);
			$this->fecha_fin->PlaceHolder = ew_RemoveHtml($this->fecha_fin->FldCaption());

			// id_vehiculo
			$this->id_vehiculo->EditAttrs["class"] = "form-control";
			$this->id_vehiculo->EditCustomAttributes = "";
			if (trim(strval($this->id_vehiculo->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_vehiculo->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `vehiculos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_vehiculo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Patente` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_vehiculo->EditValue = $arwrk;

			// id_taller
			$this->id_taller->EditAttrs["class"] = "form-control";
			$this->id_taller->EditCustomAttributes = "";
			if (trim(strval($this->id_taller->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_taller->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `talleres`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_taller, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `taller` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_taller->EditValue = $arwrk;

			// id_tipo_mantenimiento
			$this->id_tipo_mantenimiento->EditAttrs["class"] = "form-control";
			$this->id_tipo_mantenimiento->EditCustomAttributes = "";
			if (trim(strval($this->id_tipo_mantenimiento->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_mantenimiento->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_mantenimientos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_mantenimiento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_mantenimiento` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_tipo_mantenimiento->EditValue = $arwrk;

			// Edit refer script
			// fecha_ini

			$this->fecha_ini->HrefValue = "";

			// fecha_fin
			$this->fecha_fin->HrefValue = "";

			// id_vehiculo
			$this->id_vehiculo->HrefValue = "";

			// id_taller
			$this->id_taller->HrefValue = "";

			// id_tipo_mantenimiento
			$this->id_tipo_mantenimiento->HrefValue = "";
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
		if (!ew_CheckEuroDate($this->fecha_fin->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_fin->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("gastos_mantenimientos", $DetailTblVar) && $GLOBALS["gastos_mantenimientos"]->DetailAdd) {
			if (!isset($GLOBALS["gastos_mantenimientos_grid"])) $GLOBALS["gastos_mantenimientos_grid"] = new cgastos_mantenimientos_grid(); // get detail page object
			$GLOBALS["gastos_mantenimientos_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// fecha_ini
		$this->fecha_ini->SetDbValueDef($rsnew, $this->fecha_ini->CurrentValue, NULL, FALSE);

		// fecha_fin
		$this->fecha_fin->SetDbValueDef($rsnew, $this->fecha_fin->CurrentValue, NULL, FALSE);

		// id_vehiculo
		$this->id_vehiculo->SetDbValueDef($rsnew, $this->id_vehiculo->CurrentValue, NULL, FALSE);

		// id_taller
		$this->id_taller->SetDbValueDef($rsnew, $this->id_taller->CurrentValue, NULL, FALSE);

		// id_tipo_mantenimiento
		$this->id_tipo_mantenimiento->SetDbValueDef($rsnew, $this->id_tipo_mantenimiento->CurrentValue, NULL, FALSE);

		// id_usuario
		if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin
			$rsnew['id_usuario'] = CurrentUserID();
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
			$this->codigo->setDbValue($conn->Insert_ID());
			$rsnew['codigo'] = $this->codigo->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("gastos_mantenimientos", $DetailTblVar) && $GLOBALS["gastos_mantenimientos"]->DetailAdd) {
				$GLOBALS["gastos_mantenimientos"]->id_hoja_mantenimeinto->setSessionValue($this->codigo->CurrentValue); // Set master key
				if (!isset($GLOBALS["gastos_mantenimientos_grid"])) $GLOBALS["gastos_mantenimientos_grid"] = new cgastos_mantenimientos_grid(); // Get detail page object
				$AddRow = $GLOBALS["gastos_mantenimientos_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["gastos_mantenimientos"]->id_hoja_mantenimeinto->setSessionValue(""); // Clear master key if insert failed
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
			if (in_array("gastos_mantenimientos", $DetailTblVar)) {
				if (!isset($GLOBALS["gastos_mantenimientos_grid"]))
					$GLOBALS["gastos_mantenimientos_grid"] = new cgastos_mantenimientos_grid;
				if ($GLOBALS["gastos_mantenimientos_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["gastos_mantenimientos_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["gastos_mantenimientos_grid"]->CurrentMode = "add";
					$GLOBALS["gastos_mantenimientos_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["gastos_mantenimientos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["gastos_mantenimientos_grid"]->setStartRecordNumber(1);
					$GLOBALS["gastos_mantenimientos_grid"]->id_hoja_mantenimeinto->FldIsDetailKey = TRUE;
					$GLOBALS["gastos_mantenimientos_grid"]->id_hoja_mantenimeinto->CurrentValue = $this->codigo->CurrentValue;
					$GLOBALS["gastos_mantenimientos_grid"]->id_hoja_mantenimeinto->setSessionValue($GLOBALS["gastos_mantenimientos_grid"]->id_hoja_mantenimeinto->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "hoja_mantenimientoslist.php", "", $this->TableVar, TRUE);
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
if (!isset($hoja_mantenimientos_add)) $hoja_mantenimientos_add = new choja_mantenimientos_add();

// Page init
$hoja_mantenimientos_add->Page_Init();

// Page main
$hoja_mantenimientos_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hoja_mantenimientos_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var hoja_mantenimientos_add = new ew_Page("hoja_mantenimientos_add");
hoja_mantenimientos_add.PageID = "add"; // Page ID
var EW_PAGE_ID = hoja_mantenimientos_add.PageID; // For backward compatibility

// Form object
var fhoja_mantenimientosadd = new ew_Form("fhoja_mantenimientosadd");

// Validate form
fhoja_mantenimientosadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_mantenimientos->fecha_ini->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_fin");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_mantenimientos->fecha_fin->FldErrMsg()) ?>");

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
fhoja_mantenimientosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoja_mantenimientosadd.ValidateRequired = true;
<?php } else { ?>
fhoja_mantenimientosadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhoja_mantenimientosadd.Lists["x_id_vehiculo"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Patente","x_modelo","x_nombre",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_mantenimientosadd.Lists["x_id_taller"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_taller","x_tel","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_mantenimientosadd.Lists["x_id_tipo_mantenimiento"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_mantenimiento","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $hoja_mantenimientos_add->ShowPageHeader(); ?>
<?php
$hoja_mantenimientos_add->ShowMessage();
?>
<form name="fhoja_mantenimientosadd" id="fhoja_mantenimientosadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($hoja_mantenimientos_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $hoja_mantenimientos_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="hoja_mantenimientos">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($hoja_mantenimientos->fecha_ini->Visible) { // fecha_ini ?>
	<div id="r_fecha_ini" class="form-group">
		<label id="elh_hoja_mantenimientos_fecha_ini" for="x_fecha_ini" class="col-sm-2 control-label ewLabel"><?php echo $hoja_mantenimientos->fecha_ini->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_mantenimientos->fecha_ini->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_fecha_ini">
<input type="text" data-field="x_fecha_ini" name="x_fecha_ini" id="x_fecha_ini" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($hoja_mantenimientos->fecha_ini->PlaceHolder) ?>" value="<?php echo $hoja_mantenimientos->fecha_ini->EditValue ?>"<?php echo $hoja_mantenimientos->fecha_ini->EditAttributes() ?>>
<?php if (!$hoja_mantenimientos->fecha_ini->ReadOnly && !$hoja_mantenimientos->fecha_ini->Disabled && !isset($hoja_mantenimientos->fecha_ini->EditAttrs["readonly"]) && !isset($hoja_mantenimientos->fecha_ini->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fhoja_mantenimientosadd", "x_fecha_ini", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $hoja_mantenimientos->fecha_ini->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_mantenimientos->fecha_fin->Visible) { // fecha_fin ?>
	<div id="r_fecha_fin" class="form-group">
		<label id="elh_hoja_mantenimientos_fecha_fin" for="x_fecha_fin" class="col-sm-2 control-label ewLabel"><?php echo $hoja_mantenimientos->fecha_fin->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_mantenimientos->fecha_fin->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_fecha_fin">
<input type="text" data-field="x_fecha_fin" name="x_fecha_fin" id="x_fecha_fin" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($hoja_mantenimientos->fecha_fin->PlaceHolder) ?>" value="<?php echo $hoja_mantenimientos->fecha_fin->EditValue ?>"<?php echo $hoja_mantenimientos->fecha_fin->EditAttributes() ?>>
<?php if (!$hoja_mantenimientos->fecha_fin->ReadOnly && !$hoja_mantenimientos->fecha_fin->Disabled && !isset($hoja_mantenimientos->fecha_fin->EditAttrs["readonly"]) && !isset($hoja_mantenimientos->fecha_fin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fhoja_mantenimientosadd", "x_fecha_fin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $hoja_mantenimientos->fecha_fin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_mantenimientos->id_vehiculo->Visible) { // id_vehiculo ?>
	<div id="r_id_vehiculo" class="form-group">
		<label id="elh_hoja_mantenimientos_id_vehiculo" for="x_id_vehiculo" class="col-sm-2 control-label ewLabel"><?php echo $hoja_mantenimientos->id_vehiculo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_mantenimientos->id_vehiculo->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_id_vehiculo">
<select data-field="x_id_vehiculo" id="x_id_vehiculo" name="x_id_vehiculo"<?php echo $hoja_mantenimientos->id_vehiculo->EditAttributes() ?>>
<?php
if (is_array($hoja_mantenimientos->id_vehiculo->EditValue)) {
	$arwrk = $hoja_mantenimientos->id_vehiculo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hoja_mantenimientos->id_vehiculo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$hoja_mantenimientos->id_vehiculo) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$hoja_mantenimientos->id_vehiculo) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "vehiculos")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $hoja_mantenimientos->id_vehiculo->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_vehiculo',url:'vehiculosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_vehiculo"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $hoja_mantenimientos->id_vehiculo->FldCaption() ?></span></button>
<?php } ?>
<?php
$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
$sWhereWrk = "";

// Call Lookup selecting
$hoja_mantenimientos->Lookup_Selecting($hoja_mantenimientos->id_vehiculo, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Patente` ASC";
?>
<input type="hidden" name="s_x_id_vehiculo" id="s_x_id_vehiculo" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $hoja_mantenimientos->id_vehiculo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_mantenimientos->id_taller->Visible) { // id_taller ?>
	<div id="r_id_taller" class="form-group">
		<label id="elh_hoja_mantenimientos_id_taller" for="x_id_taller" class="col-sm-2 control-label ewLabel"><?php echo $hoja_mantenimientos->id_taller->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_mantenimientos->id_taller->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_id_taller">
<select data-field="x_id_taller" id="x_id_taller" name="x_id_taller"<?php echo $hoja_mantenimientos->id_taller->EditAttributes() ?>>
<?php
if (is_array($hoja_mantenimientos->id_taller->EditValue)) {
	$arwrk = $hoja_mantenimientos->id_taller->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hoja_mantenimientos->id_taller->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$hoja_mantenimientos->id_taller) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "talleres")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $hoja_mantenimientos->id_taller->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_taller',url:'talleresaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_taller"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $hoja_mantenimientos->id_taller->FldCaption() ?></span></button>
<?php } ?>
<?php
$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `talleres`";
$sWhereWrk = "";

// Call Lookup selecting
$hoja_mantenimientos->Lookup_Selecting($hoja_mantenimientos->id_taller, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `taller` ASC";
?>
<input type="hidden" name="s_x_id_taller" id="s_x_id_taller" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $hoja_mantenimientos->id_taller->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_mantenimientos->id_tipo_mantenimiento->Visible) { // id_tipo_mantenimiento ?>
	<div id="r_id_tipo_mantenimiento" class="form-group">
		<label id="elh_hoja_mantenimientos_id_tipo_mantenimiento" for="x_id_tipo_mantenimiento" class="col-sm-2 control-label ewLabel"><?php echo $hoja_mantenimientos->id_tipo_mantenimiento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_id_tipo_mantenimiento">
<select data-field="x_id_tipo_mantenimiento" id="x_id_tipo_mantenimiento" name="x_id_tipo_mantenimiento"<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->EditAttributes() ?>>
<?php
if (is_array($hoja_mantenimientos->id_tipo_mantenimiento->EditValue)) {
	$arwrk = $hoja_mantenimientos->id_tipo_mantenimiento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hoja_mantenimientos->id_tipo_mantenimiento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipo_mantenimientos")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $hoja_mantenimientos->id_tipo_mantenimiento->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_tipo_mantenimiento',url:'tipo_mantenimientosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_tipo_mantenimiento"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->FldCaption() ?></span></button>
<?php } ?>
<?php
$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_mantenimientos`";
$sWhereWrk = "";

// Call Lookup selecting
$hoja_mantenimientos->Lookup_Selecting($hoja_mantenimientos->id_tipo_mantenimiento, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `tipo_mantenimiento` ASC";
?>
<input type="hidden" name="s_x_id_tipo_mantenimiento" id="s_x_id_tipo_mantenimiento" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("gastos_mantenimientos", explode(",", $hoja_mantenimientos->getCurrentDetailTable())) && $gastos_mantenimientos->DetailAdd) {
?>
<?php if ($hoja_mantenimientos->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("gastos_mantenimientos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "gastos_mantenimientosgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fhoja_mantenimientosadd.Init();
</script>
<?php
$hoja_mantenimientos_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$hoja_mantenimientos_add->Page_Terminate();
?>
