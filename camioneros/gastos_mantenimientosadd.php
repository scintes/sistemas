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

$gastos_mantenimientos_add = NULL; // Initialize page object first

class cgastos_mantenimientos_add extends cgastos_mantenimientos {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'gastos_mantenimientos';

	// Page object name
	var $PageObjName = 'gastos_mantenimientos_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
					$this->Page_Terminate("gastos_mantenimientoslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "gastos_mantenimientosview.php")
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
		$this->detalle->CurrentValue = NULL;
		$this->detalle->OldValue = $this->detalle->CurrentValue;
		$this->fecha->CurrentValue = NULL;
		$this->fecha->OldValue = $this->fecha->CurrentValue;
		$this->id_tipo_gasto->CurrentValue = NULL;
		$this->id_tipo_gasto->OldValue = $this->id_tipo_gasto->CurrentValue;
		$this->id_hoja_mantenimeinto->CurrentValue = NULL;
		$this->id_hoja_mantenimeinto->OldValue = $this->id_hoja_mantenimeinto->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->detalle->FldIsDetailKey) {
			$this->detalle->setFormValue($objForm->GetValue("x_detalle"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		if (!$this->id_tipo_gasto->FldIsDetailKey) {
			$this->id_tipo_gasto->setFormValue($objForm->GetValue("x_id_tipo_gasto"));
		}
		if (!$this->id_hoja_mantenimeinto->FldIsDetailKey) {
			$this->id_hoja_mantenimeinto->setFormValue($objForm->GetValue("x_id_hoja_mantenimeinto"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->detalle->CurrentValue = $this->detalle->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->id_tipo_gasto->CurrentValue = $this->id_tipo_gasto->FormValue;
		$this->id_hoja_mantenimeinto->CurrentValue = $this->id_hoja_mantenimeinto->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckEuroDate($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id_hoja_mantenimeinto->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_hoja_mantenimeinto->FldErrMsg());
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

		// detalle
		$this->detalle->SetDbValueDef($rsnew, $this->detalle->CurrentValue, NULL, FALSE);

		// fecha
		$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), NULL, FALSE);

		// id_tipo_gasto
		$this->id_tipo_gasto->SetDbValueDef($rsnew, $this->id_tipo_gasto->CurrentValue, NULL, FALSE);

		// id_hoja_mantenimeinto
		$this->id_hoja_mantenimeinto->SetDbValueDef($rsnew, $this->id_hoja_mantenimeinto->CurrentValue, NULL, FALSE);

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
			if ($sMasterTblVar == "hoja_mantenimientos") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_codigo"] <> "") {
					$GLOBALS["hoja_mantenimientos"]->codigo->setQueryStringValue($_GET["fk_codigo"]);
					$this->id_hoja_mantenimeinto->setQueryStringValue($GLOBALS["hoja_mantenimientos"]->codigo->QueryStringValue);
					$this->id_hoja_mantenimeinto->setSessionValue($this->id_hoja_mantenimeinto->QueryStringValue);
					if (!is_numeric($GLOBALS["hoja_mantenimientos"]->codigo->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "tipo_gastos") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_codigo"] <> "") {
					$GLOBALS["tipo_gastos"]->codigo->setQueryStringValue($_GET["fk_codigo"]);
					$this->id_tipo_gasto->setQueryStringValue($GLOBALS["tipo_gastos"]->codigo->QueryStringValue);
					$this->id_tipo_gasto->setSessionValue($this->id_tipo_gasto->QueryStringValue);
					if (!is_numeric($GLOBALS["tipo_gastos"]->codigo->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "hoja_mantenimientos") {
				if ($this->id_hoja_mantenimeinto->QueryStringValue == "") $this->id_hoja_mantenimeinto->setSessionValue("");
			}
			if ($sMasterTblVar <> "tipo_gastos") {
				if ($this->id_tipo_gasto->QueryStringValue == "") $this->id_tipo_gasto->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "gastos_mantenimientoslist.php", "", $this->TableVar, TRUE);
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
if (!isset($gastos_mantenimientos_add)) $gastos_mantenimientos_add = new cgastos_mantenimientos_add();

// Page init
$gastos_mantenimientos_add->Page_Init();

// Page main
$gastos_mantenimientos_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gastos_mantenimientos_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gastos_mantenimientos_add = new ew_Page("gastos_mantenimientos_add");
gastos_mantenimientos_add.PageID = "add"; // Page ID
var EW_PAGE_ID = gastos_mantenimientos_add.PageID; // For backward compatibility

// Form object
var fgastos_mantenimientosadd = new ew_Form("fgastos_mantenimientosadd");

// Validate form
fgastos_mantenimientosadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos_mantenimientos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_hoja_mantenimeinto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos_mantenimientos->id_hoja_mantenimeinto->FldErrMsg()) ?>");

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
fgastos_mantenimientosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgastos_mantenimientosadd.ValidateRequired = true;
<?php } else { ?>
fgastos_mantenimientosadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgastos_mantenimientosadd.Lists["x_id_tipo_gasto"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_gasto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $gastos_mantenimientos_add->ShowPageHeader(); ?>
<?php
$gastos_mantenimientos_add->ShowMessage();
?>
<form name="fgastos_mantenimientosadd" id="fgastos_mantenimientosadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($gastos_mantenimientos_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $gastos_mantenimientos_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="gastos_mantenimientos">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($gastos_mantenimientos->detalle->Visible) { // detalle ?>
	<div id="r_detalle" class="form-group">
		<label id="elh_gastos_mantenimientos_detalle" for="x_detalle" class="col-sm-2 control-label ewLabel"><?php echo $gastos_mantenimientos->detalle->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos_mantenimientos->detalle->CellAttributes() ?>>
<span id="el_gastos_mantenimientos_detalle">
<input type="text" data-field="x_detalle" name="x_detalle" id="x_detalle" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->detalle->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->detalle->EditValue ?>"<?php echo $gastos_mantenimientos->detalle->EditAttributes() ?>>
</span>
<?php echo $gastos_mantenimientos->detalle->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gastos_mantenimientos->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_gastos_mantenimientos_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $gastos_mantenimientos->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos_mantenimientos->fecha->CellAttributes() ?>>
<span id="el_gastos_mantenimientos_fecha">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->fecha->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->fecha->EditValue ?>"<?php echo $gastos_mantenimientos->fecha->EditAttributes() ?>>
<?php if (!$gastos_mantenimientos->fecha->ReadOnly && !$gastos_mantenimientos->fecha->Disabled && !isset($gastos_mantenimientos->fecha->EditAttrs["readonly"]) && !isset($gastos_mantenimientos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastos_mantenimientosadd", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $gastos_mantenimientos->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gastos_mantenimientos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
	<div id="r_id_tipo_gasto" class="form-group">
		<label id="elh_gastos_mantenimientos_id_tipo_gasto" for="x_id_tipo_gasto" class="col-sm-2 control-label ewLabel"><?php echo $gastos_mantenimientos->id_tipo_gasto->FldCaption() ?></label>
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
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $gastos_mantenimientos->id_tipo_gasto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_tipo_gasto',url:'tipo_gastosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_tipo_gasto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $gastos_mantenimientos->id_tipo_gasto->FldCaption() ?></span></button>
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
		<label id="elh_gastos_mantenimientos_id_hoja_mantenimeinto" for="x_id_hoja_mantenimeinto" class="col-sm-2 control-label ewLabel"><?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->FldCaption() ?></label>
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
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fgastos_mantenimientosadd.Init();
</script>
<?php
$gastos_mantenimientos_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gastos_mantenimientos_add->Page_Terminate();
?>
