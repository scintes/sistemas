<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "gastos_mantenimientosinfo.php" ?>
<?php include_once "hoja_mantenimientosinfo.php" ?>
<?php include_once "tipo_gastosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$gastos_mantenimientos_update = NULL; // Initialize page object first

class cgastos_mantenimientos_update extends cgastos_mantenimientos {

	// Page ID
	var $PageID = 'update';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'gastos_mantenimientos';

	// Page object name
	var $PageObjName = 'gastos_mantenimientos_update';

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

		// Table object (gastos_mantenimientos)
		if (!isset($GLOBALS["gastos_mantenimientos"]) || get_class($GLOBALS["gastos_mantenimientos"]) == "cgastos_mantenimientos") {
			$GLOBALS["gastos_mantenimientos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gastos_mantenimientos"];
		}

		// Table object (hoja_mantenimientos)
		if (!isset($GLOBALS['hoja_mantenimientos'])) $GLOBALS['hoja_mantenimientos'] = new choja_mantenimientos();

		// Table object (tipo_gastos)
		if (!isset($GLOBALS['tipo_gastos'])) $GLOBALS['tipo_gastos'] = new ctipo_gastos();

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'update', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gastos_mantenimientos', TRUE);

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
		global $EW_EXPORT, $gastos_mantenimientos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($gastos_mantenimientos);
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
			$this->Page_Terminate("gastos_mantenimientoslist.php"); // No records selected, return to list
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
		$this->RowType = EW_ROWTYPE_EDIT; // Render edit
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
					$this->detalle->setDbValue($this->Recordset->fields('detalle'));
					$this->fecha->setDbValue($this->Recordset->fields('fecha'));
					$this->id_tipo_gasto->setDbValue($this->Recordset->fields('id_tipo_gasto'));
					$this->id_hoja_mantenimeinto->setDbValue($this->Recordset->fields('id_hoja_mantenimeinto'));
				} else {
					if (!ew_CompareValue($this->detalle->DbValue, $this->Recordset->fields('detalle')))
						$this->detalle->CurrentValue = NULL;
					if (!ew_CompareValue($this->fecha->DbValue, $this->Recordset->fields('fecha')))
						$this->fecha->CurrentValue = NULL;
					if (!ew_CompareValue($this->id_tipo_gasto->DbValue, $this->Recordset->fields('id_tipo_gasto')))
						$this->id_tipo_gasto->CurrentValue = NULL;
					if (!ew_CompareValue($this->id_hoja_mantenimeinto->DbValue, $this->Recordset->fields('id_hoja_mantenimeinto')))
						$this->id_hoja_mantenimeinto->CurrentValue = NULL;
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
		if (!$this->detalle->FldIsDetailKey) {
			$this->detalle->setFormValue($objForm->GetValue("x_detalle"));
		}
		$this->detalle->MultiUpdate = $objForm->GetValue("u_detalle");
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		$this->fecha->MultiUpdate = $objForm->GetValue("u_fecha");
		if (!$this->id_tipo_gasto->FldIsDetailKey) {
			$this->id_tipo_gasto->setFormValue($objForm->GetValue("x_id_tipo_gasto"));
		}
		$this->id_tipo_gasto->MultiUpdate = $objForm->GetValue("u_id_tipo_gasto");
		if (!$this->id_hoja_mantenimeinto->FldIsDetailKey) {
			$this->id_hoja_mantenimeinto->setFormValue($objForm->GetValue("x_id_hoja_mantenimeinto"));
		}
		$this->id_hoja_mantenimeinto->MultiUpdate = $objForm->GetValue("u_id_hoja_mantenimeinto");
		if (!$this->codigo->FldIsDetailKey)
			$this->codigo->setFormValue($objForm->GetValue("x_codigo"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->codigo->CurrentValue = $this->codigo->FormValue;
		$this->detalle->CurrentValue = $this->detalle->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->id_tipo_gasto->CurrentValue = $this->id_tipo_gasto->FormValue;
		$this->id_hoja_mantenimeinto->CurrentValue = $this->id_hoja_mantenimeinto->FormValue;
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
		$this->detalle->setDbValue($rs->fields('detalle'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->id_tipo_gasto->setDbValue($rs->fields('id_tipo_gasto'));
		if (array_key_exists('EV__id_tipo_gasto', $rs->fields)) {
			$this->id_tipo_gasto->VirtualValue = $rs->fields('EV__id_tipo_gasto'); // Set up virtual field value
		} else {
			$this->id_tipo_gasto->VirtualValue = ""; // Clear value
		}
		$this->id_hoja_mantenimeinto->setDbValue($rs->fields('id_hoja_mantenimeinto'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->detalle->DbValue = $row['detalle'];
		$this->fecha->DbValue = $row['fecha'];
		$this->id_tipo_gasto->DbValue = $row['id_tipo_gasto'];
		$this->id_hoja_mantenimeinto->DbValue = $row['id_hoja_mantenimeinto'];
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
		// detalle
		// fecha
		// id_tipo_gasto
		// id_hoja_mantenimeinto

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// detalle
			$this->detalle->ViewValue = $this->detalle->CurrentValue;
			$this->detalle->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// id_tipo_gasto
			if ($this->id_tipo_gasto->VirtualValue <> "") {
				$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->VirtualValue;
			} else {
			if (strval($this->id_tipo_gasto->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `tipo_gasto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='M'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_gasto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_gasto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->CurrentValue;
				}
			} else {
				$this->id_tipo_gasto->ViewValue = NULL;
			}
			}
			$this->id_tipo_gasto->ViewCustomAttributes = "";

			// id_hoja_mantenimeinto
			$this->id_hoja_mantenimeinto->ViewValue = $this->id_hoja_mantenimeinto->CurrentValue;
			$this->id_hoja_mantenimeinto->ViewCustomAttributes = "";

			// detalle
			$this->detalle->LinkCustomAttributes = "";
			$this->detalle->HrefValue = "";
			$this->detalle->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// id_tipo_gasto
			$this->id_tipo_gasto->LinkCustomAttributes = "";
			$this->id_tipo_gasto->HrefValue = "";
			$this->id_tipo_gasto->TooltipValue = "";

			// id_hoja_mantenimeinto
			$this->id_hoja_mantenimeinto->LinkCustomAttributes = "";
			$this->id_hoja_mantenimeinto->HrefValue = "";
			$this->id_hoja_mantenimeinto->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// detalle
			$this->detalle->EditAttrs["class"] = "form-control";
			$this->detalle->EditCustomAttributes = "";
			$this->detalle->EditValue = ew_HtmlEncode($this->detalle->CurrentValue);
			$this->detalle->PlaceHolder = ew_RemoveHtml($this->detalle->FldCaption());

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// id_tipo_gasto
			$this->id_tipo_gasto->EditAttrs["class"] = "form-control";
			$this->id_tipo_gasto->EditCustomAttributes = "";
			if ($this->id_tipo_gasto->getSessionValue() <> "") {
				$this->id_tipo_gasto->CurrentValue = $this->id_tipo_gasto->getSessionValue();
			if ($this->id_tipo_gasto->VirtualValue <> "") {
				$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->VirtualValue;
			} else {
			if (strval($this->id_tipo_gasto->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `tipo_gasto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='M'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_gasto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_gasto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->CurrentValue;
				}
			} else {
				$this->id_tipo_gasto->ViewValue = NULL;
			}
			}
			$this->id_tipo_gasto->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->id_tipo_gasto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `tipo_gasto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='M'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_gasto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_tipo_gasto->EditValue = $arwrk;
			}

			// id_hoja_mantenimeinto
			$this->id_hoja_mantenimeinto->EditAttrs["class"] = "form-control";
			$this->id_hoja_mantenimeinto->EditCustomAttributes = "";
			if ($this->id_hoja_mantenimeinto->getSessionValue() <> "") {
				$this->id_hoja_mantenimeinto->CurrentValue = $this->id_hoja_mantenimeinto->getSessionValue();
			$this->id_hoja_mantenimeinto->ViewValue = $this->id_hoja_mantenimeinto->CurrentValue;
			$this->id_hoja_mantenimeinto->ViewCustomAttributes = "";
			} else {
			$this->id_hoja_mantenimeinto->EditValue = ew_HtmlEncode($this->id_hoja_mantenimeinto->CurrentValue);
			$this->id_hoja_mantenimeinto->PlaceHolder = ew_RemoveHtml($this->id_hoja_mantenimeinto->FldCaption());
			}

			// Edit refer script
			// detalle

			$this->detalle->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// id_tipo_gasto
			$this->id_tipo_gasto->HrefValue = "";

			// id_hoja_mantenimeinto
			$this->id_hoja_mantenimeinto->HrefValue = "";
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
		if ($this->detalle->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->fecha->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->id_tipo_gasto->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->id_hoja_mantenimeinto->MultiUpdate == "1") $lUpdateCnt++;
		if ($lUpdateCnt == 0) {
			$gsFormError = $Language->Phrase("NoFieldSelected");
			return FALSE;
		}

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if ($this->fecha->MultiUpdate <> "") {
			if (!ew_CheckEuroDate($this->fecha->FormValue)) {
				ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
			}
		}
		if ($this->id_hoja_mantenimeinto->MultiUpdate <> "") {
			if (!ew_CheckInteger($this->id_hoja_mantenimeinto->FormValue)) {
				ew_AddMessage($gsFormError, $this->id_hoja_mantenimeinto->FldErrMsg());
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

			// detalle
			$this->detalle->SetDbValueDef($rsnew, $this->detalle->CurrentValue, NULL, $this->detalle->ReadOnly || $this->detalle->MultiUpdate <> "1");

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), NULL, $this->fecha->ReadOnly || $this->fecha->MultiUpdate <> "1");

			// id_tipo_gasto
			$this->id_tipo_gasto->SetDbValueDef($rsnew, $this->id_tipo_gasto->CurrentValue, NULL, $this->id_tipo_gasto->ReadOnly || $this->id_tipo_gasto->MultiUpdate <> "1");

			// id_hoja_mantenimeinto
			$this->id_hoja_mantenimeinto->SetDbValueDef($rsnew, $this->id_hoja_mantenimeinto->CurrentValue, NULL, $this->id_hoja_mantenimeinto->ReadOnly || $this->id_hoja_mantenimeinto->MultiUpdate <> "1");

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
		$Breadcrumb->Add("list", $this->TableVar, "gastos_mantenimientoslist.php", "", $this->TableVar, TRUE);
		$PageId = "update";
		$Breadcrumb->Add("update", $PageId, $url);
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
if (!isset($gastos_mantenimientos_update)) $gastos_mantenimientos_update = new cgastos_mantenimientos_update();

// Page init
$gastos_mantenimientos_update->Page_Init();

// Page main
$gastos_mantenimientos_update->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gastos_mantenimientos_update->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gastos_mantenimientos_update = new ew_Page("gastos_mantenimientos_update");
gastos_mantenimientos_update.PageID = "update"; // Page ID
var EW_PAGE_ID = gastos_mantenimientos_update.PageID; // For backward compatibility

// Form object
var fgastos_mantenimientosupdate = new ew_Form("fgastos_mantenimientosupdate");

// Validate form
fgastos_mantenimientosupdate.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha");
			uelm = this.GetElements("u" + infix + "_fecha");
			if (uelm && uelm.checked && elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos_mantenimientos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_hoja_mantenimeinto");
			uelm = this.GetElements("u" + infix + "_id_hoja_mantenimeinto");
			if (uelm && uelm.checked && elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos_mantenimientos->id_hoja_mantenimeinto->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fgastos_mantenimientosupdate.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgastos_mantenimientosupdate.ValidateRequired = true;
<?php } else { ?>
fgastos_mantenimientosupdate.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgastos_mantenimientosupdate.Lists["x_id_tipo_gasto"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_gasto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $gastos_mantenimientos_update->ShowPageHeader(); ?>
<?php
$gastos_mantenimientos_update->ShowMessage();
?>
<form name="fgastos_mantenimientosupdate" id="fgastos_mantenimientosupdate" class="form-horizontal ewForm ewUpdateForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($gastos_mantenimientos_update->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $gastos_mantenimientos_update->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="gastos_mantenimientos">
<input type="hidden" name="a_update" id="a_update" value="U">
<?php foreach ($gastos_mantenimientos_update->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_gastos_mantenimientosupdate">
	<div class="form-group">
		<label class="col-sm-2"><input type="checkbox" name="u" id="u" onclick="ew_SelectAll(this);"> <?php echo $Language->Phrase("UpdateSelectAll") ?></label>
	</div>
<?php if ($gastos_mantenimientos->detalle->Visible) { // detalle ?>
	<div id="r_detalle" class="form-group">
		<label for="x_detalle" class="col-sm-2 control-label">
<input type="checkbox" name="u_detalle" id="u_detalle" value="1"<?php echo ($gastos_mantenimientos->detalle->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
 <?php echo $gastos_mantenimientos->detalle->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos_mantenimientos->detalle->CellAttributes() ?>>
<span id="el_gastos_mantenimientos_detalle">
<input type="text" data-field="x_detalle" name="x_detalle" id="x_detalle" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->detalle->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->detalle->EditValue ?>"<?php echo $gastos_mantenimientos->detalle->EditAttributes() ?>>
</span>
<?php echo $gastos_mantenimientos->detalle->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gastos_mantenimientos->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label for="x_fecha" class="col-sm-2 control-label">
<input type="checkbox" name="u_fecha" id="u_fecha" value="1"<?php echo ($gastos_mantenimientos->fecha->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
 <?php echo $gastos_mantenimientos->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos_mantenimientos->fecha->CellAttributes() ?>>
<span id="el_gastos_mantenimientos_fecha">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->fecha->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->fecha->EditValue ?>"<?php echo $gastos_mantenimientos->fecha->EditAttributes() ?>>
<?php if (!$gastos_mantenimientos->fecha->ReadOnly && !$gastos_mantenimientos->fecha->Disabled && !isset($gastos_mantenimientos->fecha->EditAttrs["readonly"]) && !isset($gastos_mantenimientos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastos_mantenimientosupdate", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $gastos_mantenimientos->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gastos_mantenimientos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
	<div id="r_id_tipo_gasto" class="form-group">
		<label for="x_id_tipo_gasto" class="col-sm-2 control-label">
<input type="checkbox" name="u_id_tipo_gasto" id="u_id_tipo_gasto" value="1"<?php echo ($gastos_mantenimientos->id_tipo_gasto->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
 <?php echo $gastos_mantenimientos->id_tipo_gasto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos_mantenimientos->id_tipo_gasto->CellAttributes() ?>>
<?php if ($gastos_mantenimientos->id_tipo_gasto->getSessionValue() <> "") { ?>
<span id="el_gastos_mantenimientos_id_tipo_gasto">
<span<?php echo $gastos_mantenimientos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_id_tipo_gasto" name="x_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_tipo_gasto->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gastos_mantenimientos_id_tipo_gasto">
<select data-field="x_id_tipo_gasto" id="x_id_tipo_gasto" name="x_id_tipo_gasto"<?php echo $gastos_mantenimientos->id_tipo_gasto->EditAttributes() ?>>
<?php
if (is_array($gastos_mantenimientos->id_tipo_gasto->EditValue)) {
	$arwrk = $gastos_mantenimientos->id_tipo_gasto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gastos_mantenimientos->id_tipo_gasto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `codigo`, `tipo_gasto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
$sWhereWrk = "";
$lookuptblfilter = "`clase`='M'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$gastos_mantenimientos->Lookup_Selecting($gastos_mantenimientos->id_tipo_gasto, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
?>
<input type="hidden" name="s_x_id_tipo_gasto" id="s_x_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $gastos_mantenimientos->id_tipo_gasto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gastos_mantenimientos->id_hoja_mantenimeinto->Visible) { // id_hoja_mantenimeinto ?>
	<div id="r_id_hoja_mantenimeinto" class="form-group">
		<label for="x_id_hoja_mantenimeinto" class="col-sm-2 control-label">
<input type="checkbox" name="u_id_hoja_mantenimeinto" id="u_id_hoja_mantenimeinto" value="1"<?php echo ($gastos_mantenimientos->id_hoja_mantenimeinto->MultiUpdate == "1") ? " checked=\"checked\"" : "" ?>>
 <?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->CellAttributes() ?>>
<?php if ($gastos_mantenimientos->id_hoja_mantenimeinto->getSessionValue() <> "") { ?>
<span id="el_gastos_mantenimientos_id_hoja_mantenimeinto">
<span<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_id_hoja_mantenimeinto" name="x_id_hoja_mantenimeinto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gastos_mantenimientos_id_hoja_mantenimeinto">
<input type="text" data-field="x_id_hoja_mantenimeinto" name="x_id_hoja_mantenimeinto" id="x_id_hoja_mantenimeinto" size="30" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->EditValue ?>"<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->CustomMsg ?></div></div>
	</div>
<?php } ?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("UpdateBtn") ?></button>
		</div>
	</div>
</div>
</form>
<script type="text/javascript">
fgastos_mantenimientosupdate.Init();
</script>
<?php
$gastos_mantenimientos_update->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gastos_mantenimientos_update->Page_Terminate();
?>
