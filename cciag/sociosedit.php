<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "sociosinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
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
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
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
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
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

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (socios)
		if (!isset($GLOBALS["socios"])) {
			$GLOBALS["socios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["socios"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

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
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
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
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->socio_nro->CurrentValue == "")
			$this->Page_Terminate("socioslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("socioslist.php"); // No matching record, return to list
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
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->socio_nro->FldIsDetailKey) {
			$this->socio_nro->setFormValue($objForm->GetValue("x_socio_nro"));
		}
		if (!$this->id_rubro->FldIsDetailKey) {
			$this->id_rubro->setFormValue($objForm->GetValue("x_id_rubro"));
		}
		if (!$this->id_usuario->FldIsDetailKey) {
			$this->id_usuario->setFormValue($objForm->GetValue("x_id_usuario"));
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
		if (!$this->activo->FldIsDetailKey) {
			$this->activo->setFormValue($objForm->GetValue("x_activo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->socio_nro->CurrentValue = $this->socio_nro->FormValue;
		$this->id_rubro->CurrentValue = $this->id_rubro->FormValue;
		$this->id_usuario->CurrentValue = $this->id_usuario->FormValue;
		$this->propietario->CurrentValue = $this->propietario->FormValue;
		$this->comercio->CurrentValue = $this->comercio->FormValue;
		$this->direccion_comercio->CurrentValue = $this->direccion_comercio->FormValue;
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
		$this->id_rubro->setDbValue($rs->fields('id_rubro'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		$this->propietario->setDbValue($rs->fields('propietario'));
		$this->comercio->setDbValue($rs->fields('comercio'));
		$this->direccion_comercio->setDbValue($rs->fields('direccion_comercio'));
		$this->activo->setDbValue($rs->fields('activo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->socio_nro->DbValue = $row['socio_nro'];
		$this->id_rubro->DbValue = $row['id_rubro'];
		$this->id_usuario->DbValue = $row['id_usuario'];
		$this->propietario->DbValue = $row['propietario'];
		$this->comercio->DbValue = $row['comercio'];
		$this->direccion_comercio->DbValue = $row['direccion_comercio'];
		$this->activo->DbValue = $row['activo'];
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
		// id_rubro
		// id_usuario
		// propietario
		// comercio
		// direccion_comercio
		// activo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// socio_nro
			$this->socio_nro->ViewValue = $this->socio_nro->CurrentValue;
			$this->socio_nro->ViewCustomAttributes = "";

			// id_rubro
			if (strval($this->id_rubro->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_rubro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `rubro` AS `DispFld`, `Descripcion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_rubro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_rubro->ViewValue = $rswrk->fields('DispFld');
					$this->id_rubro->ViewValue .= ew_ValueSeparator(1,$this->id_rubro) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_rubro->ViewValue = $this->id_rubro->CurrentValue;
				}
			} else {
				$this->id_rubro->ViewValue = NULL;
			}
			$this->id_rubro->ViewCustomAttributes = "";

			// id_usuario
			if (strval($this->id_usuario->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_usuario->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `usuario` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_usuario, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_usuario->ViewValue = $rswrk->fields('DispFld');
					$this->id_usuario->ViewValue .= ew_ValueSeparator(1,$this->id_usuario) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_usuario->ViewValue = $this->id_usuario->CurrentValue;
				}
			} else {
				$this->id_usuario->ViewValue = NULL;
			}
			$this->id_usuario->ViewCustomAttributes = "";

			// propietario
			$this->propietario->ViewValue = $this->propietario->CurrentValue;
			$this->propietario->ViewCustomAttributes = "";

			// comercio
			$this->comercio->ViewValue = $this->comercio->CurrentValue;
			$this->comercio->ViewCustomAttributes = "";

			// direccion_comercio
			$this->direccion_comercio->ViewValue = $this->direccion_comercio->CurrentValue;
			$this->direccion_comercio->ViewCustomAttributes = "";

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

			// socio_nro
			$this->socio_nro->LinkCustomAttributes = "";
			$this->socio_nro->HrefValue = "";
			$this->socio_nro->TooltipValue = "";

			// id_rubro
			$this->id_rubro->LinkCustomAttributes = "";
			$this->id_rubro->HrefValue = "";
			$this->id_rubro->TooltipValue = "";

			// id_usuario
			$this->id_usuario->LinkCustomAttributes = "";
			$this->id_usuario->HrefValue = "";
			$this->id_usuario->TooltipValue = "";

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

			// activo
			$this->activo->LinkCustomAttributes = "";
			$this->activo->HrefValue = "";
			$this->activo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// socio_nro
			$this->socio_nro->EditCustomAttributes = "";
			$this->socio_nro->EditValue = $this->socio_nro->CurrentValue;
			$this->socio_nro->ViewCustomAttributes = "";

			// id_rubro
			$this->id_rubro->EditCustomAttributes = "";
			if (trim(strval($this->id_rubro->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_rubro->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `id`, `rubro` AS `DispFld`, `Descripcion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `rubros`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_rubro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_rubro->EditValue = $arwrk;

			// id_usuario
			$this->id_usuario->EditCustomAttributes = "";
			if (trim(strval($this->id_usuario->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_usuario->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `id`, `usuario` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `usuario`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_usuario, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_usuario->EditValue = $arwrk;

			// propietario
			$this->propietario->EditCustomAttributes = "";
			$this->propietario->EditValue = ew_HtmlEncode($this->propietario->CurrentValue);
			$this->propietario->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->propietario->FldCaption()));

			// comercio
			$this->comercio->EditCustomAttributes = "";
			$this->comercio->EditValue = ew_HtmlEncode($this->comercio->CurrentValue);
			$this->comercio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->comercio->FldCaption()));

			// direccion_comercio
			$this->direccion_comercio->EditCustomAttributes = "";
			$this->direccion_comercio->EditValue = ew_HtmlEncode($this->direccion_comercio->CurrentValue);
			$this->direccion_comercio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->direccion_comercio->FldCaption()));

			// activo
			$this->activo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->activo->FldTagValue(1), $this->activo->FldTagCaption(1) <> "" ? $this->activo->FldTagCaption(1) : $this->activo->FldTagValue(1));
			$arwrk[] = array($this->activo->FldTagValue(2), $this->activo->FldTagCaption(2) <> "" ? $this->activo->FldTagCaption(2) : $this->activo->FldTagValue(2));
			$this->activo->EditValue = $arwrk;

			// Edit refer script
			// socio_nro

			$this->socio_nro->HrefValue = "";

			// id_rubro
			$this->id_rubro->HrefValue = "";

			// id_usuario
			$this->id_usuario->HrefValue = "";

			// propietario
			$this->propietario->HrefValue = "";

			// comercio
			$this->comercio->HrefValue = "";

			// direccion_comercio
			$this->direccion_comercio->HrefValue = "";

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
		if (!$this->socio_nro->FldIsDetailKey && !is_null($this->socio_nro->FormValue) && $this->socio_nro->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->socio_nro->FldCaption());
		}
		if (!ew_CheckInteger($this->socio_nro->FormValue)) {
			ew_AddMessage($gsFormError, $this->socio_nro->FldErrMsg());
		}
		if (!$this->id_rubro->FldIsDetailKey && !is_null($this->id_rubro->FormValue) && $this->id_rubro->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_rubro->FldCaption());
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

			// socio_nro
			// id_rubro

			$this->id_rubro->SetDbValueDef($rsnew, $this->id_rubro->CurrentValue, NULL, $this->id_rubro->ReadOnly);

			// id_usuario
			$this->id_usuario->SetDbValueDef($rsnew, $this->id_usuario->CurrentValue, NULL, $this->id_usuario->ReadOnly);

			// propietario
			$this->propietario->SetDbValueDef($rsnew, $this->propietario->CurrentValue, NULL, $this->propietario->ReadOnly);

			// comercio
			$this->comercio->SetDbValueDef($rsnew, $this->comercio->CurrentValue, NULL, $this->comercio->ReadOnly);

			// direccion_comercio
			$this->direccion_comercio->SetDbValueDef($rsnew, $this->direccion_comercio->CurrentValue, NULL, $this->direccion_comercio->ReadOnly);

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
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "socioslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
<?php include_once "header.php" ?>
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
			elm = this.GetElements("x" + infix + "_socio_nro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($socios->socio_nro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_socio_nro");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->socio_nro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_rubro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($socios->id_rubro->FldCaption()) ?>");

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
fsociosedit.Lists["x_id_rubro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rubro","x_Descripcion","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsociosedit.Lists["x_id_usuario"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_usuario","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $socios_edit->ShowPageHeader(); ?>
<?php
$socios_edit->ShowMessage();
?>
<form name="fsociosedit" id="fsociosedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="socios">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_sociosedit" class="table table-bordered table-striped">
<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
	<tr id="r_socio_nro">
		<td><span id="elh_socios_socio_nro"><?php echo $socios->socio_nro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $socios->socio_nro->CellAttributes() ?>>
<span id="el_socios_socio_nro" class="control-group">
<span<?php echo $socios->socio_nro->ViewAttributes() ?>>
<?php echo $socios->socio_nro->EditValue ?></span>
</span>
<input type="hidden" data-field="x_socio_nro" name="x_socio_nro" id="x_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->CurrentValue) ?>">
<?php echo $socios->socio_nro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($socios->id_rubro->Visible) { // id_rubro ?>
	<tr id="r_id_rubro">
		<td><span id="elh_socios_id_rubro"><?php echo $socios->id_rubro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $socios->id_rubro->CellAttributes() ?>>
<span id="el_socios_id_rubro" class="control-group">
<select data-field="x_id_rubro" id="x_id_rubro" name="x_id_rubro"<?php echo $socios->id_rubro->EditAttributes() ?>>
<?php
if (is_array($socios->id_rubro->EditValue)) {
	$arwrk = $socios->id_rubro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->id_rubro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios->id_rubro) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `id`, `rubro` AS `DispFld`, `Descripcion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->id_rubro, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_id_rubro" id="s_x_id_rubro" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $socios->id_rubro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($socios->id_usuario->Visible) { // id_usuario ?>
	<tr id="r_id_usuario">
		<td><span id="elh_socios_id_usuario"><?php echo $socios->id_usuario->FldCaption() ?></span></td>
		<td<?php echo $socios->id_usuario->CellAttributes() ?>>
<span id="el_socios_id_usuario" class="control-group">
<select data-field="x_id_usuario" id="x_id_usuario" name="x_id_usuario"<?php echo $socios->id_usuario->EditAttributes() ?>>
<?php
if (is_array($socios->id_usuario->EditValue)) {
	$arwrk = $socios->id_usuario->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->id_usuario->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios->id_usuario) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `id`, `usuario` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
$sWhereWrk = "";

// Call Lookup selecting
$socios->Lookup_Selecting($socios->id_usuario, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_id_usuario" id="s_x_id_usuario" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $socios->id_usuario->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($socios->propietario->Visible) { // propietario ?>
	<tr id="r_propietario">
		<td><span id="elh_socios_propietario"><?php echo $socios->propietario->FldCaption() ?></span></td>
		<td<?php echo $socios->propietario->CellAttributes() ?>>
<span id="el_socios_propietario" class="control-group">
<input type="text" data-field="x_propietario" name="x_propietario" id="x_propietario" size="30" maxlength="255" placeholder="<?php echo $socios->propietario->PlaceHolder ?>" value="<?php echo $socios->propietario->EditValue ?>"<?php echo $socios->propietario->EditAttributes() ?>>
</span>
<?php echo $socios->propietario->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($socios->comercio->Visible) { // comercio ?>
	<tr id="r_comercio">
		<td><span id="elh_socios_comercio"><?php echo $socios->comercio->FldCaption() ?></span></td>
		<td<?php echo $socios->comercio->CellAttributes() ?>>
<span id="el_socios_comercio" class="control-group">
<input type="text" data-field="x_comercio" name="x_comercio" id="x_comercio" size="30" maxlength="255" placeholder="<?php echo $socios->comercio->PlaceHolder ?>" value="<?php echo $socios->comercio->EditValue ?>"<?php echo $socios->comercio->EditAttributes() ?>>
</span>
<?php echo $socios->comercio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
	<tr id="r_direccion_comercio">
		<td><span id="elh_socios_direccion_comercio"><?php echo $socios->direccion_comercio->FldCaption() ?></span></td>
		<td<?php echo $socios->direccion_comercio->CellAttributes() ?>>
<span id="el_socios_direccion_comercio" class="control-group">
<input type="text" data-field="x_direccion_comercio" name="x_direccion_comercio" id="x_direccion_comercio" size="30" maxlength="255" placeholder="<?php echo $socios->direccion_comercio->PlaceHolder ?>" value="<?php echo $socios->direccion_comercio->EditValue ?>"<?php echo $socios->direccion_comercio->EditAttributes() ?>>
</span>
<?php echo $socios->direccion_comercio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($socios->activo->Visible) { // activo ?>
	<tr id="r_activo">
		<td><span id="elh_socios_activo"><?php echo $socios->activo->FldCaption() ?></span></td>
		<td<?php echo $socios->activo->CellAttributes() ?>>
<span id="el_socios_activo" class="control-group">
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
<label class="radio"><input type="radio" data-field="x_activo" name="x_activo" id="x_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $socios->activo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fsociosedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
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
<?php include_once "footer.php" ?>
<?php
$socios_edit->Page_Terminate();
?>
