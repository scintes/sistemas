<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "hoja_mantenimientosinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$hoja_mantenimientos_update = NULL; // Initialize page object first

class choja_mantenimientos_update extends choja_mantenimientos {

	// Page ID
	var $PageID = 'update';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'hoja_mantenimientos';

	// Page object name
	var $PageObjName = 'hoja_mantenimientos_update';

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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'update', TRUE);

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
	var $RecKeys;
	var $Disabled;
	var $Recordset;
	var $UpdateCount = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Try to load keys from list form
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		if (@$_POST["a_update"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_update"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->LoadMultiUpdateValues(); // Load initial values to form
		}
		if (count($this->RecKeys) <= 0)
			$this->Page_Terminate("hoja_mantenimientoslist.php"); // No records selected, return to list
		switch ($this->CurrentAction) {
			case "U": // Update
				if ($this->UpdateRows()) { // Update Records based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				} else {
					$this->RestoreFormValues(); // Restore form values
				}
		}

		// Render row
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view
			$this->Disabled = " disabled=\"true\"";
		} else {
			$this->RowType = EW_ROWTYPE_EDIT; // Render edit
			$this->Disabled = "";
		}
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Load initial values to form if field values are identical in all selected records
	function LoadMultiUpdateValues() {
		$this->CurrentFilter = $this->GetKeyFilter();

		// Load recordset
		if ($this->Recordset = $this->LoadRecordset()) {
			$i = 1;
			while (!$this->Recordset->EOF) {
				if ($i == 1) {
					$this->fecha_ini->setDbValue($this->Recordset->fields('fecha_ini'));
					$this->fecha_fin->setDbValue($this->Recordset->fields('fecha_fin'));
					$this->id_vehiculo->setDbValue($this->Recordset->fields('id_vehiculo'));
					$this->id_taller->setDbValue($this->Recordset->fields('id_taller'));
					$this->id_tipo_mantenimiento->setDbValue($this->Recordset->fields('id_tipo_mantenimiento'));
				} else {
					if (!ew_CompareValue($this->fecha_ini->DbValue, $this->Recordset->fields('fecha_ini')))
						$this->fecha_ini->CurrentValue = NULL;
					if (!ew_CompareValue($this->fecha_fin->DbValue, $this->Recordset->fields('fecha_fin')))
						$this->fecha_fin->CurrentValue = NULL;
					if (!ew_CompareValue($this->id_vehiculo->DbValue, $this->Recordset->fields('id_vehiculo')))
						$this->id_vehiculo->CurrentValue = NULL;
					if (!ew_CompareValue($this->id_taller->DbValue, $this->Recordset->fields('id_taller')))
						$this->id_taller->CurrentValue = NULL;
					if (!ew_CompareValue($this->id_tipo_mantenimiento->DbValue, $this->Recordset->fields('id_tipo_mantenimiento')))
						$this->id_tipo_mantenimiento->CurrentValue = NULL;
				}
				$i++;
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
		}
	}

	// Set up key value
	function SetupKeyValues($key) {
		$sKeyFld = $key;
		if (!is_numeric($sKeyFld))
			return FALSE;
		$this->codigo->CurrentValue = $sKeyFld;
		return TRUE;
	}

	// Update all selected rows
	function UpdateRows() {
		global $conn, $Language;
		$conn->BeginTrans();

		// Get old recordset
		$this->CurrentFilter = $this->GetKeyFilter();
		$sSql = $this->SQL();
		$rsold = $conn->Execute($sSql);

		// Update all rows
		$sKey = "";
		foreach ($this->RecKeys as $key) {
			if ($this->SetupKeyValues($key)) {
				$sThisKey = $key;
				$this->SendEmail = FALSE; // Do not send email on update success
				$this->UpdateCount += 1; // Update record count for records being updated
				$UpdateRows = $this->EditRow(); // Update this row
			} else {
				$UpdateRows = FALSE;
			}
			if (!$UpdateRows)
				break; // Update failed
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}

		// Check if all rows updated
		if ($UpdateRows) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$rsnew = $conn->Execute($sSql);
		} else {
			$conn->RollbackTrans(); // Rollback transaction
		}
		return $UpdateRows;
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
		}
		$this->fecha_ini->MultiUpdate = $objForm->GetValue("u_fecha_ini");
		if (!$this->fecha_fin->FldIsDetailKey) {
			$this->fecha_fin->setFormValue($objForm->GetValue("x_fecha_fin"));
		}
		$this->fecha_fin->MultiUpdate = $objForm->GetValue("u_fecha_fin");
		if (!$this->id_vehiculo->FldIsDetailKey) {
			$this->id_vehiculo->setFormValue($objForm->GetValue("x_id_vehiculo"));
		}
		$this->id_vehiculo->MultiUpdate = $objForm->GetValue("u_id_vehiculo");
		if (!$this->id_taller->FldIsDetailKey) {
			$this->id_taller->setFormValue($objForm->GetValue("x_id_taller"));
		}
		$this->id_taller->MultiUpdate = $objForm->GetValue("u_id_taller");
		if (!$this->id_tipo_mantenimiento->FldIsDetailKey) {
			$this->id_tipo_mantenimiento->setFormValue($objForm->GetValue("x_id_tipo_mantenimiento"));
		}
		$this->id_tipo_mantenimiento->MultiUpdate = $objForm->GetValue("u_id_tipo_mantenimiento");
		if (!$this->codigo->FldIsDetailKey)
			$this->codigo->setFormValue($objForm->GetValue("x_codigo"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->codigo->CurrentValue = $this->codigo->FormValue;
		$this->fecha_ini->CurrentValue = $this->fecha_ini->FormValue;
		$this->fecha_fin->CurrentValue = $this->fecha_fin->FormValue;
		$this->id_vehiculo->CurrentValue = $this->id_vehiculo->FormValue;
		$this->id_taller->CurrentValue = $this->id_taller->FormValue;
		$this->id_tipo_mantenimiento->CurrentValue = $this->id_tipo_mantenimiento->FormValue;
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = 'ew_ErrorFn';
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
			switch (@$gsLanguage) {
				case "sp":
					$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
					$sWhereWrk = "";
					break;
			}
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
			switch (@$gsLanguage) {
				case "sp":
					$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `talleres`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `talleres`";
					$sWhereWrk = "";
					break;
			}
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
			switch (@$gsLanguage) {
				case "sp":
					$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_mantenimientos`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_mantenimientos`";
					$sWhereWrk = "";
					break;
			}
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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
			switch (@$gsLanguage) {
				case "sp":
					$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `vehiculos`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `vehiculos`";
					$sWhereWrk = "";
					break;
			}
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
			switch (@$gsLanguage) {
				case "sp":
					$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `talleres`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `talleres`";
					$sWhereWrk = "";
					break;
			}
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
			switch (@$gsLanguage) {
				case "sp":
					$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_mantenimientos`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_mantenimientos`";
					$sWhereWrk = "";
					break;
			}
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
		$lUpdateCnt = 0;
		if ($this->fecha_ini->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->fecha_fin->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->id_vehiculo->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->id_taller->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->id_tipo_mantenimiento->MultiUpdate == "1") $lUpdateCnt++;
		if ($lUpdateCnt == 0) {
			$gsFormError = $Language->Phrase("NoFieldSelected");
			return FALSE;
		}

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if ($this->fecha_ini->MultiUpdate <> "") {
			if (!ew_CheckEuroDate($this->fecha_ini->FormValue)) {
				ew_AddMessage($gsFormError, $this->fecha_ini->FldErrMsg());
			}
		}
		if ($this->fecha_fin->MultiUpdate <> "") {
			if (!ew_CheckEuroDate($this->fecha_fin->FormValue)) {
				ew_AddMessage($gsFormError, $this->fecha_fin->FldErrMsg());
			}
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// fecha_ini
			$this->fecha_ini->SetDbValueDef($rsnew, $this->fecha_ini->CurrentValue, NULL, $this->fecha_ini->ReadOnly || $this->fecha_ini->MultiUpdate <> "1");

			// fecha_fin
			$this->fecha_fin->SetDbValueDef($rsnew, $this->fecha_fin->CurrentValue, NULL, $this->fecha_fin->ReadOnly || $this->fecha_fin->MultiUpdate <> "1");

			// id_vehiculo
			$this->id_vehiculo->SetDbValueDef($rsnew, $this->id_vehiculo->CurrentValue, NULL, $this->id_vehiculo->ReadOnly || $this->id_vehiculo->MultiUpdate <> "1");

			// id_taller
			$this->id_taller->SetDbValueDef($rsnew, $this->id_taller->CurrentValue, NULL, $this->id_taller->ReadOnly || $this->id_taller->MultiUpdate <> "1");

			// id_tipo_mantenimiento
			$this->id_tipo_mantenimiento->SetDbValueDef($rsnew, $this->id_tipo_mantenimiento->CurrentValue, NULL, $this->id_tipo_mantenimiento->ReadOnly || $this->id_tipo_mantenimiento->MultiUpdate <> "1");

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
		$Breadcrumb->Add("list", $this->TableVar, "hoja_mantenimientoslist.php", "", $this->TableVar, TRUE);
		$PageId = "update";
		$Breadcrumb->Add("update", $PageId, ew_CurrentUrl());
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
if (!isset($hoja_mantenimientos_update)) $hoja_mantenimientos_update = new choja_mantenimientos_update();

// Page init
$hoja_mantenimientos_update->Page_Init();

// Page main
$hoja_mantenimientos_update->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hoja_mantenimientos_update->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var hoja_mantenimientos_update = new ew_Page("hoja_mantenimientos_update");
hoja_mantenimientos_update.PageID = "update"; // Page ID
var EW_PAGE_ID = hoja_mantenimientos_update.PageID; // For backward compatibility

// Form object
var fhoja_mantenimientosupdate = new ew_Form("fhoja_mantenimientosupdate");

// Validate form
fhoja_mantenimientosupdate.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	if (!ew_UpdateSelected(fobj)) {
		alert(ewLanguage.Phrase("NoFieldSelected"));
		return false;
	}
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_fecha_ini");
			uelm = this.GetElements("u" + infix + "_fecha_ini");
			if (uelm && uelm.checked && elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_mantenimientos->fecha_ini->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_fin");
			uelm = this.GetElements("u" + infix + "_fecha_fin");
			if (uelm && uelm.checked && elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($hoja_mantenimientos->fecha_fin->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fhoja_mantenimientosupdate.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoja_mantenimientosupdate.ValidateRequired = true;
<?php } else { ?>
fhoja_mantenimientosupdate.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhoja_mantenimientosupdate.Lists["x_id_vehiculo"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Patente","x_modelo","x_nombre",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_mantenimientosupdate.Lists["x_id_taller"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_taller","x_tel","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_mantenimientosupdate.Lists["x_id_tipo_mantenimiento"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_mantenimiento","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $hoja_mantenimientos_update->ShowPageHeader(); ?>
<?php
$hoja_mantenimientos_update->ShowMessage();
?>
<form name="fhoja_mantenimientosupdate" id="fhoja_mantenimientosupdate" class="form-horizontal ewForm ewUpdateForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($hoja_mantenimientos_update->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $hoja_mantenimientos_update->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="hoja_mantenimientos">
<input type="hidden" name="a_update" id="a_update" value="U">
<?php if ($hoja_mantenimientos->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<?php foreach ($hoja_mantenimientos_update->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_hoja_mantenimientosupdate">
	<div class="form-group">
		<label class="col-sm-2"><input type="checkbox" name="u" id="u" onclick="ew_SelectAll(this);"<?php echo $hoja_mantenimientos_update->Disabled ?>> <?php echo $Language->Phrase("UpdateSelectAll") ?></label>
	</div>
<?php if ($hoja_mantenimientos->fecha_ini->Visible) { // fecha_ini ?>
	<div id="r_fecha_ini" class="form-group">
		<label for="x_fecha_ini" class="col-sm-2 control-label">
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { ?>
<input type="checkbox" name="u_fecha_ini" id="u_fecha_ini" value="1"<?php echo ($hoja_mantenimientos->fecha_ini->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
<?php } else { ?>
<input type="checkbox" disabled="disabled"<?php echo ($hoja_mantenimientos->fecha_ini->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
<input type="hidden" name="u_fecha_ini" id="u_fecha_ini" value="<?php echo $hoja_mantenimientos->fecha_ini->MultiUpdate ?>">
<?php } ?>
 <?php echo $hoja_mantenimientos->fecha_ini->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_mantenimientos->fecha_ini->CellAttributes() ?>>
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { ?>
<span id="el_hoja_mantenimientos_fecha_ini">
<input type="text" data-field="x_fecha_ini" name="x_fecha_ini" id="x_fecha_ini" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($hoja_mantenimientos->fecha_ini->PlaceHolder) ?>" value="<?php echo $hoja_mantenimientos->fecha_ini->EditValue ?>"<?php echo $hoja_mantenimientos->fecha_ini->EditAttributes() ?>>
<?php if (!$hoja_mantenimientos->fecha_ini->ReadOnly && !$hoja_mantenimientos->fecha_ini->Disabled && @$hoja_mantenimientos->fecha_ini->EditAttrs["readonly"] == "" && @$hoja_mantenimientos->fecha_ini->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fhoja_mantenimientosupdate", "x_fecha_ini", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_hoja_mantenimientos_fecha_ini">
<span<?php echo $hoja_mantenimientos->fecha_ini->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $hoja_mantenimientos->fecha_ini->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_ini" name="x_fecha_ini" id="x_fecha_ini" value="<?php echo ew_HtmlEncode($hoja_mantenimientos->fecha_ini->FormValue) ?>">
<?php } ?>
<?php echo $hoja_mantenimientos->fecha_ini->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_mantenimientos->fecha_fin->Visible) { // fecha_fin ?>
	<div id="r_fecha_fin" class="form-group">
		<label for="x_fecha_fin" class="col-sm-2 control-label">
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { ?>
<input type="checkbox" name="u_fecha_fin" id="u_fecha_fin" value="1"<?php echo ($hoja_mantenimientos->fecha_fin->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
<?php } else { ?>
<input type="checkbox" disabled="disabled"<?php echo ($hoja_mantenimientos->fecha_fin->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
<input type="hidden" name="u_fecha_fin" id="u_fecha_fin" value="<?php echo $hoja_mantenimientos->fecha_fin->MultiUpdate ?>">
<?php } ?>
 <?php echo $hoja_mantenimientos->fecha_fin->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_mantenimientos->fecha_fin->CellAttributes() ?>>
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { ?>
<span id="el_hoja_mantenimientos_fecha_fin">
<input type="text" data-field="x_fecha_fin" name="x_fecha_fin" id="x_fecha_fin" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($hoja_mantenimientos->fecha_fin->PlaceHolder) ?>" value="<?php echo $hoja_mantenimientos->fecha_fin->EditValue ?>"<?php echo $hoja_mantenimientos->fecha_fin->EditAttributes() ?>>
<?php if (!$hoja_mantenimientos->fecha_fin->ReadOnly && !$hoja_mantenimientos->fecha_fin->Disabled && @$hoja_mantenimientos->fecha_fin->EditAttrs["readonly"] == "" && @$hoja_mantenimientos->fecha_fin->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fhoja_mantenimientosupdate", "x_fecha_fin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_hoja_mantenimientos_fecha_fin">
<span<?php echo $hoja_mantenimientos->fecha_fin->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $hoja_mantenimientos->fecha_fin->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_fin" name="x_fecha_fin" id="x_fecha_fin" value="<?php echo ew_HtmlEncode($hoja_mantenimientos->fecha_fin->FormValue) ?>">
<?php } ?>
<?php echo $hoja_mantenimientos->fecha_fin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_mantenimientos->id_vehiculo->Visible) { // id_vehiculo ?>
	<div id="r_id_vehiculo" class="form-group">
		<label for="x_id_vehiculo" class="col-sm-2 control-label">
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { ?>
<input type="checkbox" name="u_id_vehiculo" id="u_id_vehiculo" value="1"<?php echo ($hoja_mantenimientos->id_vehiculo->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
<?php } else { ?>
<input type="checkbox" disabled="disabled"<?php echo ($hoja_mantenimientos->id_vehiculo->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
<input type="hidden" name="u_id_vehiculo" id="u_id_vehiculo" value="<?php echo $hoja_mantenimientos->id_vehiculo->MultiUpdate ?>">
<?php } ?>
 <?php echo $hoja_mantenimientos->id_vehiculo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_mantenimientos->id_vehiculo->CellAttributes() ?>>
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { ?>
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
<?php
switch (@$gsLanguage) {
	case "sp":
		$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
		$sWhereWrk = "";
		break;
}

// Call Lookup selecting
$hoja_mantenimientos->Lookup_Selecting($hoja_mantenimientos->id_vehiculo, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Patente` ASC";
?>
<input type="hidden" name="s_x_id_vehiculo" id="s_x_id_vehiculo" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el_hoja_mantenimientos_id_vehiculo">
<span<?php echo $hoja_mantenimientos->id_vehiculo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $hoja_mantenimientos->id_vehiculo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_vehiculo" name="x_id_vehiculo" id="x_id_vehiculo" value="<?php echo ew_HtmlEncode($hoja_mantenimientos->id_vehiculo->FormValue) ?>">
<?php } ?>
<?php echo $hoja_mantenimientos->id_vehiculo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_mantenimientos->id_taller->Visible) { // id_taller ?>
	<div id="r_id_taller" class="form-group">
		<label for="x_id_taller" class="col-sm-2 control-label">
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { ?>
<input type="checkbox" name="u_id_taller" id="u_id_taller" value="1"<?php echo ($hoja_mantenimientos->id_taller->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
<?php } else { ?>
<input type="checkbox" disabled="disabled"<?php echo ($hoja_mantenimientos->id_taller->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
<input type="hidden" name="u_id_taller" id="u_id_taller" value="<?php echo $hoja_mantenimientos->id_taller->MultiUpdate ?>">
<?php } ?>
 <?php echo $hoja_mantenimientos->id_taller->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_mantenimientos->id_taller->CellAttributes() ?>>
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { ?>
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
<?php
switch (@$gsLanguage) {
	case "sp":
		$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `talleres`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `talleres`";
		$sWhereWrk = "";
		break;
}

// Call Lookup selecting
$hoja_mantenimientos->Lookup_Selecting($hoja_mantenimientos->id_taller, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `taller` ASC";
?>
<input type="hidden" name="s_x_id_taller" id="s_x_id_taller" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el_hoja_mantenimientos_id_taller">
<span<?php echo $hoja_mantenimientos->id_taller->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $hoja_mantenimientos->id_taller->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_taller" name="x_id_taller" id="x_id_taller" value="<?php echo ew_HtmlEncode($hoja_mantenimientos->id_taller->FormValue) ?>">
<?php } ?>
<?php echo $hoja_mantenimientos->id_taller->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hoja_mantenimientos->id_tipo_mantenimiento->Visible) { // id_tipo_mantenimiento ?>
	<div id="r_id_tipo_mantenimiento" class="form-group">
		<label for="x_id_tipo_mantenimiento" class="col-sm-2 control-label">
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { ?>
<input type="checkbox" name="u_id_tipo_mantenimiento" id="u_id_tipo_mantenimiento" value="1"<?php echo ($hoja_mantenimientos->id_tipo_mantenimiento->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
<?php } else { ?>
<input type="checkbox" disabled="disabled"<?php echo ($hoja_mantenimientos->id_tipo_mantenimiento->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
<input type="hidden" name="u_id_tipo_mantenimiento" id="u_id_tipo_mantenimiento" value="<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->MultiUpdate ?>">
<?php } ?>
 <?php echo $hoja_mantenimientos->id_tipo_mantenimiento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->CellAttributes() ?>>
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { ?>
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
<?php
switch (@$gsLanguage) {
	case "sp":
		$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_mantenimientos`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_mantenimientos`";
		$sWhereWrk = "";
		break;
}

// Call Lookup selecting
$hoja_mantenimientos->Lookup_Selecting($hoja_mantenimientos->id_tipo_mantenimiento, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `tipo_mantenimiento` ASC";
?>
<input type="hidden" name="s_x_id_tipo_mantenimiento" id="s_x_id_tipo_mantenimiento" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el_hoja_mantenimientos_id_tipo_mantenimiento">
<span<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $hoja_mantenimientos->id_tipo_mantenimiento->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_tipo_mantenimiento" name="x_id_tipo_mantenimiento" id="x_id_tipo_mantenimiento" value="<?php echo ew_HtmlEncode($hoja_mantenimientos->id_tipo_mantenimiento->FormValue) ?>">
<?php } ?>
<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
<?php if ($hoja_mantenimientos->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_update.value='F';"><?php echo $Language->Phrase("UpdateBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_update.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
		</div>
	</div>
</div>
</form>
<script type="text/javascript">
fhoja_mantenimientosupdate.Init();
</script>
<?php
$hoja_mantenimientos_update->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$hoja_mantenimientos_update->Page_Terminate();
?>
