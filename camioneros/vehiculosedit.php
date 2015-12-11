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

$vehiculos_edit = NULL; // Initialize page object first

class cvehiculos_edit extends cvehiculos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'vehiculos';

	// Page object name
	var $PageObjName = 'vehiculos_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
			$this->Page_Terminate("vehiculoslist.php"); // Return to list page
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
					$this->Page_Terminate("vehiculoslist.php"); // Return to list page
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
		if (!$this->Patente->FldIsDetailKey) {
			$this->Patente->setFormValue($objForm->GetValue("x_Patente"));
		}
		if (!$this->cantidad_rueda->FldIsDetailKey) {
			$this->cantidad_rueda->setFormValue($objForm->GetValue("x_cantidad_rueda"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->modelo->FldIsDetailKey) {
			$this->modelo->setFormValue($objForm->GetValue("x_modelo"));
		}
		if (!$this->id_chofer->FldIsDetailKey) {
			$this->id_chofer->setFormValue($objForm->GetValue("x_id_chofer"));
		}
		if (!$this->id_guarda->FldIsDetailKey) {
			$this->id_guarda->setFormValue($objForm->GetValue("x_id_guarda"));
		}
		if (!$this->id_marca->FldIsDetailKey) {
			$this->id_marca->setFormValue($objForm->GetValue("x_id_marca"));
		}
		if (!$this->codigo->FldIsDetailKey)
			$this->codigo->setFormValue($objForm->GetValue("x_codigo"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->codigo->CurrentValue = $this->codigo->FormValue;
		$this->Patente->CurrentValue = $this->Patente->FormValue;
		$this->cantidad_rueda->CurrentValue = $this->cantidad_rueda->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->modelo->CurrentValue = $this->modelo->FormValue;
		$this->id_chofer->CurrentValue = $this->id_chofer->FormValue;
		$this->id_guarda->CurrentValue = $this->id_guarda->FormValue;
		$this->id_marca->CurrentValue = $this->id_marca->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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

			// Patente
			$this->Patente->SetDbValueDef($rsnew, $this->Patente->CurrentValue, NULL, $this->Patente->ReadOnly);

			// cantidad_rueda
			$this->cantidad_rueda->SetDbValueDef($rsnew, $this->cantidad_rueda->CurrentValue, NULL, $this->cantidad_rueda->ReadOnly);

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, $this->nombre->ReadOnly);

			// modelo
			$this->modelo->SetDbValueDef($rsnew, $this->modelo->CurrentValue, NULL, $this->modelo->ReadOnly);

			// id_chofer
			$this->id_chofer->SetDbValueDef($rsnew, $this->id_chofer->CurrentValue, NULL, $this->id_chofer->ReadOnly);

			// id_guarda
			$this->id_guarda->SetDbValueDef($rsnew, $this->id_guarda->CurrentValue, NULL, $this->id_guarda->ReadOnly);

			// id_marca
			$this->id_marca->SetDbValueDef($rsnew, $this->id_marca->CurrentValue, NULL, $this->id_marca->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "vehiculoslist.php", "", $this->TableVar, TRUE);
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
if (!isset($vehiculos_edit)) $vehiculos_edit = new cvehiculos_edit();

// Page init
$vehiculos_edit->Page_Init();

// Page main
$vehiculos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vehiculos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var vehiculos_edit = new ew_Page("vehiculos_edit");
vehiculos_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = vehiculos_edit.PageID; // For backward compatibility

// Form object
var fvehiculosedit = new ew_Form("fvehiculosedit");

// Validate form
fvehiculosedit.Validate = function() {
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
fvehiculosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvehiculosedit.ValidateRequired = true;
<?php } else { ?>
fvehiculosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvehiculosedit.Lists["x_id_chofer"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fvehiculosedit.Lists["x_id_guarda"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fvehiculosedit.Lists["x_id_marca"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_marca","x_modelo","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $vehiculos_edit->ShowPageHeader(); ?>
<?php
$vehiculos_edit->ShowMessage();
?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($vehiculos_edit->Pager)) $vehiculos_edit->Pager = new cNumericPager($vehiculos_edit->StartRec, $vehiculos_edit->DisplayRecs, $vehiculos_edit->TotalRecs, $vehiculos_edit->RecRange) ?>
<?php if ($vehiculos_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($vehiculos_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $vehiculos_edit->PageUrl() ?>start=<?php echo $vehiculos_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($vehiculos_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $vehiculos_edit->PageUrl() ?>start=<?php echo $vehiculos_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($vehiculos_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $vehiculos_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($vehiculos_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $vehiculos_edit->PageUrl() ?>start=<?php echo $vehiculos_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($vehiculos_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $vehiculos_edit->PageUrl() ?>start=<?php echo $vehiculos_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<form name="fvehiculosedit" id="fvehiculosedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vehiculos_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vehiculos_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vehiculos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($vehiculos->Patente->Visible) { // Patente ?>
	<div id="r_Patente" class="form-group">
		<label id="elh_vehiculos_Patente" for="x_Patente" class="col-sm-2 control-label ewLabel"><?php echo $vehiculos->Patente->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $vehiculos->Patente->CellAttributes() ?>>
<span id="el_vehiculos_Patente">
<input type="text" data-field="x_Patente" name="x_Patente" id="x_Patente" size="30" maxlength="7" placeholder="<?php echo ew_HtmlEncode($vehiculos->Patente->PlaceHolder) ?>" value="<?php echo $vehiculos->Patente->EditValue ?>"<?php echo $vehiculos->Patente->EditAttributes() ?>>
</span>
<?php echo $vehiculos->Patente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vehiculos->cantidad_rueda->Visible) { // cantidad_rueda ?>
	<div id="r_cantidad_rueda" class="form-group">
		<label id="elh_vehiculos_cantidad_rueda" for="x_cantidad_rueda" class="col-sm-2 control-label ewLabel"><?php echo $vehiculos->cantidad_rueda->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $vehiculos->cantidad_rueda->CellAttributes() ?>>
<span id="el_vehiculos_cantidad_rueda">
<input type="text" data-field="x_cantidad_rueda" name="x_cantidad_rueda" id="x_cantidad_rueda" size="30" placeholder="<?php echo ew_HtmlEncode($vehiculos->cantidad_rueda->PlaceHolder) ?>" value="<?php echo $vehiculos->cantidad_rueda->EditValue ?>"<?php echo $vehiculos->cantidad_rueda->EditAttributes() ?>>
</span>
<?php echo $vehiculos->cantidad_rueda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vehiculos->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_vehiculos_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $vehiculos->nombre->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $vehiculos->nombre->CellAttributes() ?>>
<span id="el_vehiculos_nombre">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($vehiculos->nombre->PlaceHolder) ?>" value="<?php echo $vehiculos->nombre->EditValue ?>"<?php echo $vehiculos->nombre->EditAttributes() ?>>
</span>
<?php echo $vehiculos->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vehiculos->modelo->Visible) { // modelo ?>
	<div id="r_modelo" class="form-group">
		<label id="elh_vehiculos_modelo" for="x_modelo" class="col-sm-2 control-label ewLabel"><?php echo $vehiculos->modelo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $vehiculos->modelo->CellAttributes() ?>>
<span id="el_vehiculos_modelo">
<input type="text" data-field="x_modelo" name="x_modelo" id="x_modelo" size="30" placeholder="<?php echo ew_HtmlEncode($vehiculos->modelo->PlaceHolder) ?>" value="<?php echo $vehiculos->modelo->EditValue ?>"<?php echo $vehiculos->modelo->EditAttributes() ?>>
</span>
<?php echo $vehiculos->modelo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vehiculos->id_chofer->Visible) { // id_chofer ?>
	<div id="r_id_chofer" class="form-group">
		<label id="elh_vehiculos_id_chofer" for="x_id_chofer" class="col-sm-2 control-label ewLabel"><?php echo $vehiculos->id_chofer->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $vehiculos->id_chofer->CellAttributes() ?>>
<span id="el_vehiculos_id_chofer">
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
<?php if (AllowAdd(CurrentProjectID() . "choferes")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $vehiculos->id_chofer->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_chofer',url:'choferesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_chofer"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $vehiculos->id_chofer->FldCaption() ?></span></button>
<?php } ?>
<?php
$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `choferes`";
$sWhereWrk = "";

// Call Lookup selecting
$vehiculos->Lookup_Selecting($vehiculos->id_chofer, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x_id_chofer" id="s_x_id_chofer" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $vehiculos->id_chofer->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vehiculos->id_guarda->Visible) { // id_guarda ?>
	<div id="r_id_guarda" class="form-group">
		<label id="elh_vehiculos_id_guarda" for="x_id_guarda" class="col-sm-2 control-label ewLabel"><?php echo $vehiculos->id_guarda->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $vehiculos->id_guarda->CellAttributes() ?>>
<span id="el_vehiculos_id_guarda">
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
<?php if (AllowAdd(CurrentProjectID() . "choferes")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $vehiculos->id_guarda->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_guarda',url:'choferesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_guarda"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $vehiculos->id_guarda->FldCaption() ?></span></button>
<?php } ?>
<?php
$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `choferes`";
$sWhereWrk = "";

// Call Lookup selecting
$vehiculos->Lookup_Selecting($vehiculos->id_guarda, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x_id_guarda" id="s_x_id_guarda" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $vehiculos->id_guarda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vehiculos->id_marca->Visible) { // id_marca ?>
	<div id="r_id_marca" class="form-group">
		<label id="elh_vehiculos_id_marca" for="x_id_marca" class="col-sm-2 control-label ewLabel"><?php echo $vehiculos->id_marca->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $vehiculos->id_marca->CellAttributes() ?>>
<span id="el_vehiculos_id_marca">
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
<?php if (AllowAdd(CurrentProjectID() . "marcas")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $vehiculos->id_marca->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_marca',url:'marcasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_marca"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $vehiculos->id_marca->FldCaption() ?></span></button>
<?php } ?>
<?php
$sSqlWrk = "SELECT `codigo`, `marca` AS `DispFld`, `modelo` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
$sWhereWrk = "";

// Call Lookup selecting
$vehiculos->Lookup_Selecting($vehiculos->id_marca, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `marca` ASC";
?>
<input type="hidden" name="s_x_id_marca" id="s_x_id_marca" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $vehiculos->id_marca->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_codigo" name="x_codigo" id="x_codigo" value="<?php echo ew_HtmlEncode($vehiculos->codigo->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
<?php if (!isset($vehiculos_edit->Pager)) $vehiculos_edit->Pager = new cNumericPager($vehiculos_edit->StartRec, $vehiculos_edit->DisplayRecs, $vehiculos_edit->TotalRecs, $vehiculos_edit->RecRange) ?>
<?php if ($vehiculos_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($vehiculos_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $vehiculos_edit->PageUrl() ?>start=<?php echo $vehiculos_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($vehiculos_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $vehiculos_edit->PageUrl() ?>start=<?php echo $vehiculos_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($vehiculos_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $vehiculos_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($vehiculos_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $vehiculos_edit->PageUrl() ?>start=<?php echo $vehiculos_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($vehiculos_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $vehiculos_edit->PageUrl() ?>start=<?php echo $vehiculos_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<script type="text/javascript">
fvehiculosedit.Init();
</script>
<?php
$vehiculos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vehiculos_edit->Page_Terminate();
?>
