<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "trabajosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$trabajos_edit = NULL; // Initialize page object first

class ctrabajos_edit extends ctrabajos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{83DA4882-2FB3-4AE9-BADA-241C2F6A6920}";

	// Table name
	var $TableName = 'trabajos';

	// Page object name
	var $PageObjName = 'trabajos_edit';

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

		// Table object (trabajos)
		if (!isset($GLOBALS["trabajos"])) {
			$GLOBALS["trabajos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["trabajos"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'trabajos', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nro_orden->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["nro_orden"] <> "") {
			$this->nro_orden->setQueryStringValue($_GET["nro_orden"]);
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
		if ($this->nro_orden->CurrentValue == "")
			$this->Page_Terminate("trabajoslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("trabajoslist.php"); // No matching record, return to list
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
		$this->foto1->Upload->Index = $objForm->Index;
		if ($this->foto1->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->foto1->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->foto1->CurrentValue = $this->foto1->Upload->FileName;
		$this->foto2->Upload->Index = $objForm->Index;
		if ($this->foto2->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->foto2->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->foto2->CurrentValue = $this->foto2->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->nro_orden->FldIsDetailKey)
			$this->nro_orden->setFormValue($objForm->GetValue("x_nro_orden"));
		if (!$this->fecha_recepcion->FldIsDetailKey) {
			$this->fecha_recepcion->setFormValue($objForm->GetValue("x_fecha_recepcion"));
			$this->fecha_recepcion->CurrentValue = ew_UnFormatDateTime($this->fecha_recepcion->CurrentValue, 7);
		}
		if (!$this->cliente->FldIsDetailKey) {
			$this->cliente->setFormValue($objForm->GetValue("x_cliente"));
		}
		if (!$this->id_tipo_cliente->FldIsDetailKey) {
			$this->id_tipo_cliente->setFormValue($objForm->GetValue("x_id_tipo_cliente"));
		}
		if (!$this->tel->FldIsDetailKey) {
			$this->tel->setFormValue($objForm->GetValue("x_tel"));
		}
		if (!$this->cel->FldIsDetailKey) {
			$this->cel->setFormValue($objForm->GetValue("x_cel"));
		}
		if (!$this->objetos->FldIsDetailKey) {
			$this->objetos->setFormValue($objForm->GetValue("x_objetos"));
		}
		if (!$this->detalle_a_realizar->FldIsDetailKey) {
			$this->detalle_a_realizar->setFormValue($objForm->GetValue("x_detalle_a_realizar"));
		}
		if (!$this->fecha_entrega->FldIsDetailKey) {
			$this->fecha_entrega->setFormValue($objForm->GetValue("x_fecha_entrega"));
			$this->fecha_entrega->CurrentValue = ew_UnFormatDateTime($this->fecha_entrega->CurrentValue, 7);
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
		if (!$this->id_estado->FldIsDetailKey) {
			$this->id_estado->setFormValue($objForm->GetValue("x_id_estado"));
		}
		if (!$this->precio->FldIsDetailKey) {
			$this->precio->setFormValue($objForm->GetValue("x_precio"));
		}
		if (!$this->entrega->FldIsDetailKey) {
			$this->entrega->setFormValue($objForm->GetValue("x_entrega"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nro_orden->CurrentValue = $this->nro_orden->FormValue;
		$this->fecha_recepcion->CurrentValue = $this->fecha_recepcion->FormValue;
		$this->fecha_recepcion->CurrentValue = ew_UnFormatDateTime($this->fecha_recepcion->CurrentValue, 7);
		$this->cliente->CurrentValue = $this->cliente->FormValue;
		$this->id_tipo_cliente->CurrentValue = $this->id_tipo_cliente->FormValue;
		$this->tel->CurrentValue = $this->tel->FormValue;
		$this->cel->CurrentValue = $this->cel->FormValue;
		$this->objetos->CurrentValue = $this->objetos->FormValue;
		$this->detalle_a_realizar->CurrentValue = $this->detalle_a_realizar->FormValue;
		$this->fecha_entrega->CurrentValue = $this->fecha_entrega->FormValue;
		$this->fecha_entrega->CurrentValue = ew_UnFormatDateTime($this->fecha_entrega->CurrentValue, 7);
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
		$this->id_estado->CurrentValue = $this->id_estado->FormValue;
		$this->precio->CurrentValue = $this->precio->FormValue;
		$this->entrega->CurrentValue = $this->entrega->FormValue;
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
		$this->nro_orden->setDbValue($rs->fields('nro_orden'));
		$this->fecha_recepcion->setDbValue($rs->fields('fecha_recepcion'));
		$this->cliente->setDbValue($rs->fields('cliente'));
		$this->id_tipo_cliente->setDbValue($rs->fields('id_tipo_cliente'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->objetos->setDbValue($rs->fields('objetos'));
		$this->detalle_a_realizar->setDbValue($rs->fields('detalle_a_realizar'));
		$this->fecha_entrega->setDbValue($rs->fields('fecha_entrega'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->id_estado->setDbValue($rs->fields('id_estado'));
		$this->precio->setDbValue($rs->fields('precio'));
		$this->entrega->setDbValue($rs->fields('entrega'));
		$this->saldo->setDbValue($rs->fields('saldo'));
		$this->foto1->Upload->DbValue = $rs->fields('foto1');
		$this->foto2->Upload->DbValue = $rs->fields('foto2');
		$this->usuario->setDbValue($rs->fields('usuario'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nro_orden->DbValue = $row['nro_orden'];
		$this->fecha_recepcion->DbValue = $row['fecha_recepcion'];
		$this->cliente->DbValue = $row['cliente'];
		$this->id_tipo_cliente->DbValue = $row['id_tipo_cliente'];
		$this->tel->DbValue = $row['tel'];
		$this->cel->DbValue = $row['cel'];
		$this->objetos->DbValue = $row['objetos'];
		$this->detalle_a_realizar->DbValue = $row['detalle_a_realizar'];
		$this->fecha_entrega->DbValue = $row['fecha_entrega'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->id_estado->DbValue = $row['id_estado'];
		$this->precio->DbValue = $row['precio'];
		$this->entrega->DbValue = $row['entrega'];
		$this->saldo->DbValue = $row['saldo'];
		$this->foto1->Upload->DbValue = $row['foto1'];
		$this->foto2->Upload->DbValue = $row['foto2'];
		$this->usuario->DbValue = $row['usuario'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->precio->FormValue == $this->precio->CurrentValue && is_numeric(ew_StrToFloat($this->precio->CurrentValue)))
			$this->precio->CurrentValue = ew_StrToFloat($this->precio->CurrentValue);

		// Convert decimal values if posted back
		if ($this->entrega->FormValue == $this->entrega->CurrentValue && is_numeric(ew_StrToFloat($this->entrega->CurrentValue)))
			$this->entrega->CurrentValue = ew_StrToFloat($this->entrega->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nro_orden
		// fecha_recepcion
		// cliente
		// id_tipo_cliente
		// tel
		// cel
		// objetos
		// detalle_a_realizar
		// fecha_entrega
		// observaciones
		// id_estado
		// precio
		// entrega
		// saldo
		// foto1
		// foto2
		// usuario

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nro_orden
			$this->nro_orden->ViewValue = $this->nro_orden->CurrentValue;
			$this->nro_orden->ViewCustomAttributes = "";

			// fecha_recepcion
			$this->fecha_recepcion->ViewValue = $this->fecha_recepcion->CurrentValue;
			$this->fecha_recepcion->ViewValue = ew_FormatDateTime($this->fecha_recepcion->ViewValue, 7);
			$this->fecha_recepcion->ViewCustomAttributes = "";

			// cliente
			$this->cliente->ViewValue = $this->cliente->CurrentValue;
			$this->cliente->ViewCustomAttributes = "";

			// id_tipo_cliente
			if (strval($this->id_tipo_cliente->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_cliente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `codigo`, `tipo_cliente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_clientes`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_cliente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_cliente` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_cliente->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_tipo_cliente->ViewValue = $this->id_tipo_cliente->CurrentValue;
				}
			} else {
				$this->id_tipo_cliente->ViewValue = NULL;
			}
			$this->id_tipo_cliente->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewCustomAttributes = "";

			// objetos
			$this->objetos->ViewValue = $this->objetos->CurrentValue;
			$this->objetos->ViewCustomAttributes = "";

			// detalle_a_realizar
			$this->detalle_a_realizar->ViewValue = $this->detalle_a_realizar->CurrentValue;
			$this->detalle_a_realizar->ViewCustomAttributes = "";

			// fecha_entrega
			$this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
			$this->fecha_entrega->ViewValue = ew_FormatDateTime($this->fecha_entrega->ViewValue, 7);
			$this->fecha_entrega->ViewCustomAttributes = "";

			// observaciones
			$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
			$this->observaciones->ViewCustomAttributes = "";

			// id_estado
			if (strval($this->id_estado->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_estado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `codigo`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estados`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_estado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `codigo` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_estado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_estado->ViewValue = $this->id_estado->CurrentValue;
				}
			} else {
				$this->id_estado->ViewValue = NULL;
			}
			$this->id_estado->ViewCustomAttributes = "";

			// precio
			$this->precio->ViewValue = $this->precio->CurrentValue;
			$this->precio->ViewValue = ew_FormatCurrency($this->precio->ViewValue, 2, -2, -2, -2);
			$this->precio->ViewCustomAttributes = "";

			// entrega
			$this->entrega->ViewValue = $this->entrega->CurrentValue;
			$this->entrega->ViewValue = ew_FormatCurrency($this->entrega->ViewValue, 2, -2, -2, -2);
			$this->entrega->ViewCustomAttributes = "";

			// saldo
			$this->saldo->ViewValue = $this->saldo->CurrentValue;
			$this->saldo->ViewValue = ew_FormatCurrency($this->saldo->ViewValue, 2, -2, -2, -2);
			$this->saldo->ViewCustomAttributes = "";

			// foto1
			if (!ew_Empty($this->foto1->Upload->DbValue)) {
				$this->foto1->ImageAlt = $this->foto1->FldAlt();
				$this->foto1->ViewValue = ew_UploadPathEx(FALSE, $this->foto1->UploadPath) . $this->foto1->Upload->DbValue;
			} else {
				$this->foto1->ViewValue = "";
			}
			$this->foto1->ViewCustomAttributes = "";

			// foto2
			if (!ew_Empty($this->foto2->Upload->DbValue)) {
				$this->foto2->ImageAlt = $this->foto2->FldAlt();
				$this->foto2->ViewValue = ew_UploadPathEx(FALSE, $this->foto2->UploadPath) . $this->foto2->Upload->DbValue;
			} else {
				$this->foto2->ViewValue = "";
			}
			$this->foto2->ViewCustomAttributes = "";

			// nro_orden
			$this->nro_orden->LinkCustomAttributes = "";
			$this->nro_orden->HrefValue = "";
			$this->nro_orden->TooltipValue = "";

			// fecha_recepcion
			$this->fecha_recepcion->LinkCustomAttributes = "";
			$this->fecha_recepcion->HrefValue = "";
			$this->fecha_recepcion->TooltipValue = "";

			// cliente
			$this->cliente->LinkCustomAttributes = "";
			$this->cliente->HrefValue = "";
			$this->cliente->TooltipValue = "";

			// id_tipo_cliente
			$this->id_tipo_cliente->LinkCustomAttributes = "";
			$this->id_tipo_cliente->HrefValue = "";
			$this->id_tipo_cliente->TooltipValue = "";

			// tel
			$this->tel->LinkCustomAttributes = "";
			$this->tel->HrefValue = "";
			$this->tel->TooltipValue = "";

			// cel
			$this->cel->LinkCustomAttributes = "";
			$this->cel->HrefValue = "";
			$this->cel->TooltipValue = "";

			// objetos
			$this->objetos->LinkCustomAttributes = "";
			$this->objetos->HrefValue = "";
			$this->objetos->TooltipValue = "";

			// detalle_a_realizar
			$this->detalle_a_realizar->LinkCustomAttributes = "";
			$this->detalle_a_realizar->HrefValue = "";
			$this->detalle_a_realizar->TooltipValue = "";

			// fecha_entrega
			$this->fecha_entrega->LinkCustomAttributes = "";
			$this->fecha_entrega->HrefValue = "";
			$this->fecha_entrega->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// id_estado
			$this->id_estado->LinkCustomAttributes = "";
			$this->id_estado->HrefValue = "";
			$this->id_estado->TooltipValue = "";

			// precio
			$this->precio->LinkCustomAttributes = "";
			$this->precio->HrefValue = "";
			$this->precio->TooltipValue = "";

			// entrega
			$this->entrega->LinkCustomAttributes = "";
			$this->entrega->HrefValue = "";
			$this->entrega->TooltipValue = "";

			// foto1
			$this->foto1->LinkCustomAttributes = "";
			$this->foto1->HrefValue = "";
			$this->foto1->HrefValue2 = $this->foto1->UploadPath . $this->foto1->Upload->DbValue;
			$this->foto1->TooltipValue = "";

			// foto2
			$this->foto2->LinkCustomAttributes = "";
			$this->foto2->HrefValue = "";
			$this->foto2->HrefValue2 = $this->foto2->UploadPath . $this->foto2->Upload->DbValue;
			$this->foto2->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nro_orden
			$this->nro_orden->EditCustomAttributes = "";
			$this->nro_orden->EditValue = $this->nro_orden->CurrentValue;
			$this->nro_orden->ViewCustomAttributes = "";

			// fecha_recepcion
			$this->fecha_recepcion->EditCustomAttributes = "";
			$this->fecha_recepcion->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_recepcion->CurrentValue, 7));
			$this->fecha_recepcion->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->fecha_recepcion->FldCaption()));

			// cliente
			$this->cliente->EditCustomAttributes = "";
			$this->cliente->EditValue = ew_HtmlEncode($this->cliente->CurrentValue);
			$this->cliente->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->cliente->FldCaption()));

			// id_tipo_cliente
			$this->id_tipo_cliente->EditCustomAttributes = "";
			if (trim(strval($this->id_tipo_cliente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_cliente->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT DISTINCT `codigo`, `tipo_cliente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_clientes`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_cliente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_cliente` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_tipo_cliente->EditValue = $arwrk;

			// tel
			$this->tel->EditCustomAttributes = "";
			$this->tel->EditValue = ew_HtmlEncode($this->tel->CurrentValue);
			$this->tel->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->tel->FldCaption()));

			// cel
			$this->cel->EditCustomAttributes = "";
			$this->cel->EditValue = ew_HtmlEncode($this->cel->CurrentValue);
			$this->cel->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->cel->FldCaption()));

			// objetos
			$this->objetos->EditCustomAttributes = "";
			$this->objetos->EditValue = ew_HtmlEncode($this->objetos->CurrentValue);
			$this->objetos->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->objetos->FldCaption()));

			// detalle_a_realizar
			$this->detalle_a_realizar->EditCustomAttributes = "";
			$this->detalle_a_realizar->EditValue = $this->detalle_a_realizar->CurrentValue;
			$this->detalle_a_realizar->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->detalle_a_realizar->FldCaption()));

			// fecha_entrega
			$this->fecha_entrega->EditCustomAttributes = "";
			$this->fecha_entrega->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_entrega->CurrentValue, 7));
			$this->fecha_entrega->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->fecha_entrega->FldCaption()));

			// observaciones
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = $this->observaciones->CurrentValue;
			$this->observaciones->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->observaciones->FldCaption()));

			// id_estado
			$this->id_estado->EditCustomAttributes = "";
			if (trim(strval($this->id_estado->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_estado->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT DISTINCT `codigo`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `estados`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_estado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `codigo` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_estado->EditValue = $arwrk;

			// precio
			$this->precio->EditCustomAttributes = "";
			$this->precio->EditValue = ew_HtmlEncode($this->precio->CurrentValue);
			$this->precio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->precio->FldCaption()));
			if (strval($this->precio->EditValue) <> "" && is_numeric($this->precio->EditValue)) $this->precio->EditValue = ew_FormatNumber($this->precio->EditValue, -2, -2, -2, -2);

			// entrega
			$this->entrega->EditCustomAttributes = "";
			$this->entrega->EditValue = ew_HtmlEncode($this->entrega->CurrentValue);
			$this->entrega->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->entrega->FldCaption()));
			if (strval($this->entrega->EditValue) <> "" && is_numeric($this->entrega->EditValue)) $this->entrega->EditValue = ew_FormatNumber($this->entrega->EditValue, -2, -2, -2, -2);

			// foto1
			$this->foto1->EditCustomAttributes = "";
			if (!ew_Empty($this->foto1->Upload->DbValue)) {
				$this->foto1->ImageAlt = $this->foto1->FldAlt();
				$this->foto1->EditValue = ew_UploadPathEx(FALSE, $this->foto1->UploadPath) . $this->foto1->Upload->DbValue;
			} else {
				$this->foto1->EditValue = "";
			}
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->foto1);

			// foto2
			$this->foto2->EditCustomAttributes = "";
			if (!ew_Empty($this->foto2->Upload->DbValue)) {
				$this->foto2->ImageAlt = $this->foto2->FldAlt();
				$this->foto2->EditValue = ew_UploadPathEx(FALSE, $this->foto2->UploadPath) . $this->foto2->Upload->DbValue;
			} else {
				$this->foto2->EditValue = "";
			}
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->foto2);

			// Edit refer script
			// nro_orden

			$this->nro_orden->HrefValue = "";

			// fecha_recepcion
			$this->fecha_recepcion->HrefValue = "";

			// cliente
			$this->cliente->HrefValue = "";

			// id_tipo_cliente
			$this->id_tipo_cliente->HrefValue = "";

			// tel
			$this->tel->HrefValue = "";

			// cel
			$this->cel->HrefValue = "";

			// objetos
			$this->objetos->HrefValue = "";

			// detalle_a_realizar
			$this->detalle_a_realizar->HrefValue = "";

			// fecha_entrega
			$this->fecha_entrega->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";

			// id_estado
			$this->id_estado->HrefValue = "";

			// precio
			$this->precio->HrefValue = "";

			// entrega
			$this->entrega->HrefValue = "";

			// foto1
			$this->foto1->HrefValue = "";
			$this->foto1->HrefValue2 = $this->foto1->UploadPath . $this->foto1->Upload->DbValue;

			// foto2
			$this->foto2->HrefValue = "";
			$this->foto2->HrefValue2 = $this->foto2->UploadPath . $this->foto2->Upload->DbValue;
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
		if (!$this->fecha_recepcion->FldIsDetailKey && !is_null($this->fecha_recepcion->FormValue) && $this->fecha_recepcion->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->fecha_recepcion->FldCaption());
		}
		if (!ew_CheckEuroDate($this->fecha_recepcion->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_recepcion->FldErrMsg());
		}
		if (!$this->objetos->FldIsDetailKey && !is_null($this->objetos->FormValue) && $this->objetos->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->objetos->FldCaption());
		}
		if (!ew_CheckEuroDate($this->fecha_entrega->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_entrega->FldErrMsg());
		}
		if (!$this->id_estado->FldIsDetailKey && !is_null($this->id_estado->FormValue) && $this->id_estado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_estado->FldCaption());
		}
		if (!$this->precio->FldIsDetailKey && !is_null($this->precio->FormValue) && $this->precio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->precio->FldCaption());
		}
		if (!ew_CheckNumber($this->precio->FormValue)) {
			ew_AddMessage($gsFormError, $this->precio->FldErrMsg());
		}
		if (!ew_CheckNumber($this->entrega->FormValue)) {
			ew_AddMessage($gsFormError, $this->entrega->FldErrMsg());
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

			// fecha_recepcion
			$this->fecha_recepcion->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_recepcion->CurrentValue, 7), ew_CurrentDate(), $this->fecha_recepcion->ReadOnly);

			// cliente
			$this->cliente->SetDbValueDef($rsnew, $this->cliente->CurrentValue, NULL, $this->cliente->ReadOnly);

			// id_tipo_cliente
			$this->id_tipo_cliente->SetDbValueDef($rsnew, $this->id_tipo_cliente->CurrentValue, NULL, $this->id_tipo_cliente->ReadOnly);

			// tel
			$this->tel->SetDbValueDef($rsnew, $this->tel->CurrentValue, NULL, $this->tel->ReadOnly);

			// cel
			$this->cel->SetDbValueDef($rsnew, $this->cel->CurrentValue, NULL, $this->cel->ReadOnly);

			// objetos
			$this->objetos->SetDbValueDef($rsnew, $this->objetos->CurrentValue, NULL, $this->objetos->ReadOnly);

			// detalle_a_realizar
			$this->detalle_a_realizar->SetDbValueDef($rsnew, $this->detalle_a_realizar->CurrentValue, NULL, $this->detalle_a_realizar->ReadOnly);

			// fecha_entrega
			$this->fecha_entrega->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_entrega->CurrentValue, 7), NULL, $this->fecha_entrega->ReadOnly);

			// observaciones
			$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, $this->observaciones->ReadOnly);

			// id_estado
			$this->id_estado->SetDbValueDef($rsnew, $this->id_estado->CurrentValue, 0, $this->id_estado->ReadOnly);

			// precio
			$this->precio->SetDbValueDef($rsnew, $this->precio->CurrentValue, NULL, $this->precio->ReadOnly);

			// entrega
			$this->entrega->SetDbValueDef($rsnew, $this->entrega->CurrentValue, NULL, $this->entrega->ReadOnly);

			// foto1
			if (!($this->foto1->ReadOnly) && !$this->foto1->Upload->KeepFile) {
				$this->foto1->Upload->DbValue = $rs->fields('foto1'); // Get original value
				if ($this->foto1->Upload->FileName == "") {
					$rsnew['foto1'] = NULL;
				} else {
					$rsnew['foto1'] = $this->foto1->Upload->FileName;
				}
			}

			// foto2
			if (!($this->foto2->ReadOnly) && !$this->foto2->Upload->KeepFile) {
				$this->foto2->Upload->DbValue = $rs->fields('foto2'); // Get original value
				if ($this->foto2->Upload->FileName == "") {
					$rsnew['foto2'] = NULL;
				} else {
					$rsnew['foto2'] = $this->foto2->Upload->FileName;
				}
			}
			if (!$this->foto1->Upload->KeepFile) {
				if (!ew_Empty($this->foto1->Upload->Value)) {
					if ($this->foto1->Upload->FileName == $this->foto1->Upload->DbValue) { // Overwrite if same file name
						$this->foto1->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['foto1'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->foto1->UploadPath), $rsnew['foto1']); // Get new file name
					}
				}
			}
			if (!$this->foto2->Upload->KeepFile) {
				if (!ew_Empty($this->foto2->Upload->Value)) {
					if ($this->foto2->Upload->FileName == $this->foto2->Upload->DbValue) { // Overwrite if same file name
						$this->foto2->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['foto2'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->foto2->UploadPath), $rsnew['foto2']); // Get new file name
					}
				}
			}

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
					if (!$this->foto1->Upload->KeepFile) {
						if (!ew_Empty($this->foto1->Upload->Value)) {
							$this->foto1->Upload->SaveToFile($this->foto1->UploadPath, $rsnew['foto1'], TRUE);
						}
						if ($this->foto1->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->foto1->OldUploadPath) . $this->foto1->Upload->DbValue);
					}
					if (!$this->foto2->Upload->KeepFile) {
						if (!ew_Empty($this->foto2->Upload->Value)) {
							$this->foto2->Upload->SaveToFile($this->foto2->UploadPath, $rsnew['foto2'], TRUE);
						}
						if ($this->foto2->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->foto2->OldUploadPath) . $this->foto2->Upload->DbValue);
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

		// foto1
		ew_CleanUploadTempPath($this->foto1, $this->foto1->Upload->Index);

		// foto2
		ew_CleanUploadTempPath($this->foto2, $this->foto2->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "trabajoslist.php", $this->TableVar);
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
if (!isset($trabajos_edit)) $trabajos_edit = new ctrabajos_edit();

// Page init
$trabajos_edit->Page_Init();

// Page main
$trabajos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$trabajos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var trabajos_edit = new ew_Page("trabajos_edit");
trabajos_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = trabajos_edit.PageID; // For backward compatibility

// Form object
var ftrabajosedit = new ew_Form("ftrabajosedit");

// Validate form
ftrabajosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha_recepcion");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($trabajos->fecha_recepcion->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_recepcion");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($trabajos->fecha_recepcion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_objetos");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($trabajos->objetos->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_entrega");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($trabajos->fecha_entrega->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_estado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($trabajos->id_estado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_precio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($trabajos->precio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_precio");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($trabajos->precio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_entrega");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($trabajos->entrega->FldErrMsg()) ?>");

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
ftrabajosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftrabajosedit.ValidateRequired = true;
<?php } else { ?>
ftrabajosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftrabajosedit.Lists["x_id_tipo_cliente"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_cliente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftrabajosedit.Lists["x_id_estado"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_estado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $trabajos_edit->ShowPageHeader(); ?>
<?php
$trabajos_edit->ShowMessage();
?>
<form name="ftrabajosedit" id="ftrabajosedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="trabajos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_trabajosedit" class="table table-bordered table-striped">
<?php if ($trabajos->nro_orden->Visible) { // nro_orden ?>
	<tr id="r_nro_orden">
		<td><span id="elh_trabajos_nro_orden"><?php echo $trabajos->nro_orden->FldCaption() ?></span></td>
		<td<?php echo $trabajos->nro_orden->CellAttributes() ?>>
<span id="el_trabajos_nro_orden" class="control-group">
<span<?php echo $trabajos->nro_orden->ViewAttributes() ?>>
<?php echo $trabajos->nro_orden->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nro_orden" name="x_nro_orden" id="x_nro_orden" value="<?php echo ew_HtmlEncode($trabajos->nro_orden->CurrentValue) ?>">
<?php echo $trabajos->nro_orden->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->fecha_recepcion->Visible) { // fecha_recepcion ?>
	<tr id="r_fecha_recepcion">
		<td><span id="elh_trabajos_fecha_recepcion"><?php echo $trabajos->fecha_recepcion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $trabajos->fecha_recepcion->CellAttributes() ?>>
<span id="el_trabajos_fecha_recepcion" class="control-group">
<input type="text" data-field="x_fecha_recepcion" name="x_fecha_recepcion" id="x_fecha_recepcion" placeholder="<?php echo $trabajos->fecha_recepcion->PlaceHolder ?>" value="<?php echo $trabajos->fecha_recepcion->EditValue ?>"<?php echo $trabajos->fecha_recepcion->EditAttributes() ?>>
<?php if (!$trabajos->fecha_recepcion->ReadOnly && !$trabajos->fecha_recepcion->Disabled && @$trabajos->fecha_recepcion->EditAttrs["readonly"] == "" && @$trabajos->fecha_recepcion->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_fecha_recepcion" name="cal_x_fecha_recepcion" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_fecha_recepcion" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ftrabajosedit", "x_fecha_recepcion", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $trabajos->fecha_recepcion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->cliente->Visible) { // cliente ?>
	<tr id="r_cliente">
		<td><span id="elh_trabajos_cliente"><?php echo $trabajos->cliente->FldCaption() ?></span></td>
		<td<?php echo $trabajos->cliente->CellAttributes() ?>>
<span id="el_trabajos_cliente" class="control-group">
<input type="text" data-field="x_cliente" name="x_cliente" id="x_cliente" size="30" maxlength="100" placeholder="<?php echo $trabajos->cliente->PlaceHolder ?>" value="<?php echo $trabajos->cliente->EditValue ?>"<?php echo $trabajos->cliente->EditAttributes() ?>>
</span>
<?php echo $trabajos->cliente->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->id_tipo_cliente->Visible) { // id_tipo_cliente ?>
	<tr id="r_id_tipo_cliente">
		<td><span id="elh_trabajos_id_tipo_cliente"><?php echo $trabajos->id_tipo_cliente->FldCaption() ?></span></td>
		<td<?php echo $trabajos->id_tipo_cliente->CellAttributes() ?>>
<span id="el_trabajos_id_tipo_cliente" class="control-group">
<select data-field="x_id_tipo_cliente" id="x_id_tipo_cliente" name="x_id_tipo_cliente"<?php echo $trabajos->id_tipo_cliente->EditAttributes() ?>>
<?php
if (is_array($trabajos->id_tipo_cliente->EditValue)) {
	$arwrk = $trabajos->id_tipo_cliente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($trabajos->id_tipo_cliente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT DISTINCT `codigo`, `tipo_cliente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_clientes`";
$sWhereWrk = "";
$lookuptblfilter = "`activo`='S'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$trabajos->Lookup_Selecting($trabajos->id_tipo_cliente, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `tipo_cliente` ASC";
?>
<input type="hidden" name="s_x_id_tipo_cliente" id="s_x_id_tipo_cliente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $trabajos->id_tipo_cliente->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->tel->Visible) { // tel ?>
	<tr id="r_tel">
		<td><span id="elh_trabajos_tel"><?php echo $trabajos->tel->FldCaption() ?></span></td>
		<td<?php echo $trabajos->tel->CellAttributes() ?>>
<span id="el_trabajos_tel" class="control-group">
<input type="text" data-field="x_tel" name="x_tel" id="x_tel" size="30" maxlength="16" placeholder="<?php echo $trabajos->tel->PlaceHolder ?>" value="<?php echo $trabajos->tel->EditValue ?>"<?php echo $trabajos->tel->EditAttributes() ?>>
</span>
<?php echo $trabajos->tel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->cel->Visible) { // cel ?>
	<tr id="r_cel">
		<td><span id="elh_trabajos_cel"><?php echo $trabajos->cel->FldCaption() ?></span></td>
		<td<?php echo $trabajos->cel->CellAttributes() ?>>
<span id="el_trabajos_cel" class="control-group">
<input type="text" data-field="x_cel" name="x_cel" id="x_cel" size="30" maxlength="20" placeholder="<?php echo $trabajos->cel->PlaceHolder ?>" value="<?php echo $trabajos->cel->EditValue ?>"<?php echo $trabajos->cel->EditAttributes() ?>>
</span>
<?php echo $trabajos->cel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->objetos->Visible) { // objetos ?>
	<tr id="r_objetos">
		<td><span id="elh_trabajos_objetos"><?php echo $trabajos->objetos->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $trabajos->objetos->CellAttributes() ?>>
<span id="el_trabajos_objetos" class="control-group">
<input type="text" data-field="x_objetos" name="x_objetos" id="x_objetos" size="30" maxlength="100" placeholder="<?php echo $trabajos->objetos->PlaceHolder ?>" value="<?php echo $trabajos->objetos->EditValue ?>"<?php echo $trabajos->objetos->EditAttributes() ?>>
</span>
<?php echo $trabajos->objetos->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->detalle_a_realizar->Visible) { // detalle_a_realizar ?>
	<tr id="r_detalle_a_realizar">
		<td><span id="elh_trabajos_detalle_a_realizar"><?php echo $trabajos->detalle_a_realizar->FldCaption() ?></span></td>
		<td<?php echo $trabajos->detalle_a_realizar->CellAttributes() ?>>
<span id="el_trabajos_detalle_a_realizar" class="control-group">
<textarea data-field="x_detalle_a_realizar" name="x_detalle_a_realizar" id="x_detalle_a_realizar" cols="35" rows="4" placeholder="<?php echo $trabajos->detalle_a_realizar->PlaceHolder ?>"<?php echo $trabajos->detalle_a_realizar->EditAttributes() ?>><?php echo $trabajos->detalle_a_realizar->EditValue ?></textarea>
</span>
<?php echo $trabajos->detalle_a_realizar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->fecha_entrega->Visible) { // fecha_entrega ?>
	<tr id="r_fecha_entrega">
		<td><span id="elh_trabajos_fecha_entrega"><?php echo $trabajos->fecha_entrega->FldCaption() ?></span></td>
		<td<?php echo $trabajos->fecha_entrega->CellAttributes() ?>>
<span id="el_trabajos_fecha_entrega" class="control-group">
<input type="text" data-field="x_fecha_entrega" name="x_fecha_entrega" id="x_fecha_entrega" placeholder="<?php echo $trabajos->fecha_entrega->PlaceHolder ?>" value="<?php echo $trabajos->fecha_entrega->EditValue ?>"<?php echo $trabajos->fecha_entrega->EditAttributes() ?>>
<?php if (!$trabajos->fecha_entrega->ReadOnly && !$trabajos->fecha_entrega->Disabled && @$trabajos->fecha_entrega->EditAttrs["readonly"] == "" && @$trabajos->fecha_entrega->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_fecha_entrega" name="cal_x_fecha_entrega" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_fecha_entrega" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ftrabajosedit", "x_fecha_entrega", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $trabajos->fecha_entrega->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->observaciones->Visible) { // observaciones ?>
	<tr id="r_observaciones">
		<td><span id="elh_trabajos_observaciones"><?php echo $trabajos->observaciones->FldCaption() ?></span></td>
		<td<?php echo $trabajos->observaciones->CellAttributes() ?>>
<span id="el_trabajos_observaciones" class="control-group">
<textarea data-field="x_observaciones" name="x_observaciones" id="x_observaciones" cols="35" rows="4" placeholder="<?php echo $trabajos->observaciones->PlaceHolder ?>"<?php echo $trabajos->observaciones->EditAttributes() ?>><?php echo $trabajos->observaciones->EditValue ?></textarea>
</span>
<?php echo $trabajos->observaciones->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->id_estado->Visible) { // id_estado ?>
	<tr id="r_id_estado">
		<td><span id="elh_trabajos_id_estado"><?php echo $trabajos->id_estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $trabajos->id_estado->CellAttributes() ?>>
<span id="el_trabajos_id_estado" class="control-group">
<select data-field="x_id_estado" id="x_id_estado" name="x_id_estado"<?php echo $trabajos->id_estado->EditAttributes() ?>>
<?php
if (is_array($trabajos->id_estado->EditValue)) {
	$arwrk = $trabajos->id_estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($trabajos->id_estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT DISTINCT `codigo`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estados`";
$sWhereWrk = "";
$lookuptblfilter = "`activo`='S'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$trabajos->Lookup_Selecting($trabajos->id_estado, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `codigo` ASC";
?>
<input type="hidden" name="s_x_id_estado" id="s_x_id_estado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $trabajos->id_estado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->precio->Visible) { // precio ?>
	<tr id="r_precio">
		<td><span id="elh_trabajos_precio"><?php echo $trabajos->precio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $trabajos->precio->CellAttributes() ?>>
<span id="el_trabajos_precio" class="control-group">
<input type="text" data-field="x_precio" name="x_precio" id="x_precio" size="30" placeholder="<?php echo $trabajos->precio->PlaceHolder ?>" value="<?php echo $trabajos->precio->EditValue ?>"<?php echo $trabajos->precio->EditAttributes() ?>>
</span>
<?php echo $trabajos->precio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->entrega->Visible) { // entrega ?>
	<tr id="r_entrega">
		<td><span id="elh_trabajos_entrega"><?php echo $trabajos->entrega->FldCaption() ?></span></td>
		<td<?php echo $trabajos->entrega->CellAttributes() ?>>
<span id="el_trabajos_entrega" class="control-group">
<input type="text" data-field="x_entrega" name="x_entrega" id="x_entrega" size="30" placeholder="<?php echo $trabajos->entrega->PlaceHolder ?>" value="<?php echo $trabajos->entrega->EditValue ?>"<?php echo $trabajos->entrega->EditAttributes() ?>>
</span>
<?php echo $trabajos->entrega->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->foto1->Visible) { // foto1 ?>
	<tr id="r_foto1">
		<td><span id="elh_trabajos_foto1"><?php echo $trabajos->foto1->FldCaption() ?></span></td>
		<td<?php echo $trabajos->foto1->CellAttributes() ?>>
<span id="el_trabajos_foto1" class="control-group">
<span id="fd_x_foto1">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_foto1" name="x_foto1" id="x_foto1">
</span>
<input type="hidden" name="fn_x_foto1" id= "fn_x_foto1" value="<?php echo $trabajos->foto1->Upload->FileName ?>">
<?php if (@$_POST["fa_x_foto1"] == "0") { ?>
<input type="hidden" name="fa_x_foto1" id= "fa_x_foto1" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_foto1" id= "fa_x_foto1" value="1">
<?php } ?>
<input type="hidden" name="fs_x_foto1" id= "fs_x_foto1" value="255">
</span>
<table id="ft_x_foto1" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $trabajos->foto1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajos->foto2->Visible) { // foto2 ?>
	<tr id="r_foto2">
		<td><span id="elh_trabajos_foto2"><?php echo $trabajos->foto2->FldCaption() ?></span></td>
		<td<?php echo $trabajos->foto2->CellAttributes() ?>>
<span id="el_trabajos_foto2" class="control-group">
<span id="fd_x_foto2">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_foto2" name="x_foto2" id="x_foto2">
</span>
<input type="hidden" name="fn_x_foto2" id= "fn_x_foto2" value="<?php echo $trabajos->foto2->Upload->FileName ?>">
<?php if (@$_POST["fa_x_foto2"] == "0") { ?>
<input type="hidden" name="fa_x_foto2" id= "fa_x_foto2" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_foto2" id= "fa_x_foto2" value="1">
<?php } ?>
<input type="hidden" name="fs_x_foto2" id= "fs_x_foto2" value="255">
</span>
<table id="ft_x_foto2" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $trabajos->foto2->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
ftrabajosedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$trabajos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$trabajos_edit->Page_Terminate();
?>
