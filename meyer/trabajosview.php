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

$trabajos_view = NULL; // Initialize page object first

class ctrabajos_view extends ctrabajos {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{83DA4882-2FB3-4AE9-BADA-241C2F6A6920}";

	// Table name
	var $TableName = 'trabajos';

	// Page object name
	var $PageObjName = 'trabajos_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["nro_orden"] <> "") {
			$this->RecKey["nro_orden"] = $_GET["nro_orden"];
			$KeyUrl .= "&nro_orden=" . urlencode($this->RecKey["nro_orden"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'trabajos', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["nro_orden"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nro_orden"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nro_orden->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Update url if printer friendly for Pdf
		if ($this->PrinterFriendlyForPdf)
			$this->ExportOptions->Items["pdf"]->Body = str_replace($this->ExportPdfUrl, $this->ExportPrintUrl . "&pdf=1", $this->ExportOptions->Items["pdf"]->Body);
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();
		if ($this->Export == "print" && @$_GET["pdf"] == "1") { // Printer friendly version and with pdf=1 in URL parameters
			$pdf = new cExportPdf($GLOBALS["Table"]);
			$pdf->Text = ob_get_contents(); // Set the content as the HTML of current page (printer friendly version)
			ob_end_clean();
			$pdf->Export();
		}

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["nro_orden"] <> "") {
				$this->nro_orden->setQueryStringValue($_GET["nro_orden"]);
				$this->RecKey["nro_orden"] = $this->nro_orden->QueryStringValue;
			} else {
				$sReturnUrl = "trabajoslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "trabajoslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "trabajoslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "");

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "");

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
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

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Convert decimal values if posted back
		if ($this->precio->FormValue == $this->precio->CurrentValue && is_numeric(ew_StrToFloat($this->precio->CurrentValue)))
			$this->precio->CurrentValue = ew_StrToFloat($this->precio->CurrentValue);

		// Convert decimal values if posted back
		if ($this->entrega->FormValue == $this->entrega->CurrentValue && is_numeric(ew_StrToFloat($this->entrega->CurrentValue)))
			$this->entrega->CurrentValue = ew_StrToFloat($this->entrega->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo->FormValue == $this->saldo->CurrentValue && is_numeric(ew_StrToFloat($this->saldo->CurrentValue)))
			$this->saldo->CurrentValue = ew_StrToFloat($this->saldo->CurrentValue);

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

			// saldo
			$this->saldo->LinkCustomAttributes = "";
			$this->saldo->HrefValue = "";
			$this->saldo->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_trabajos\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_trabajos',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.ftrabajosview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "v");
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$ExportDoc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "trabajoslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("view");
		$Breadcrumb->Add("view", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($trabajos_view)) $trabajos_view = new ctrabajos_view();

// Page init
$trabajos_view->Page_Init();

// Page main
$trabajos_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$trabajos_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($trabajos->Export == "") { ?>
<script type="text/javascript">

// Page object
var trabajos_view = new ew_Page("trabajos_view");
trabajos_view.PageID = "view"; // Page ID
var EW_PAGE_ID = trabajos_view.PageID; // For backward compatibility

// Form object
var ftrabajosview = new ew_Form("ftrabajosview");

// Form_CustomValidate event
ftrabajosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftrabajosview.ValidateRequired = true;
<?php } else { ?>
ftrabajosview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftrabajosview.Lists["x_id_tipo_cliente"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_cliente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftrabajosview.Lists["x_id_estado"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_estado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($trabajos->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($trabajos->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $trabajos_view->ExportOptions->Render("body") ?>
<?php if (!$trabajos_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($trabajos_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $trabajos_view->ShowPageHeader(); ?>
<?php
$trabajos_view->ShowMessage();
?>
<form name="ftrabajosview" id="ftrabajosview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="trabajos">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_trabajosview" class="table table-bordered table-striped">
<?php if ($trabajos->nro_orden->Visible) { // nro_orden ?>
	<tr id="r_nro_orden">
		<td><span id="elh_trabajos_nro_orden"><?php echo $trabajos->nro_orden->FldCaption() ?></span></td>
		<td<?php echo $trabajos->nro_orden->CellAttributes() ?>>
<span id="el_trabajos_nro_orden" class="control-group">
<span<?php echo $trabajos->nro_orden->ViewAttributes() ?>>
<?php echo $trabajos->nro_orden->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->fecha_recepcion->Visible) { // fecha_recepcion ?>
	<tr id="r_fecha_recepcion">
		<td><span id="elh_trabajos_fecha_recepcion"><?php echo $trabajos->fecha_recepcion->FldCaption() ?></span></td>
		<td<?php echo $trabajos->fecha_recepcion->CellAttributes() ?>>
<span id="el_trabajos_fecha_recepcion" class="control-group">
<span<?php echo $trabajos->fecha_recepcion->ViewAttributes() ?>>
<?php echo $trabajos->fecha_recepcion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->cliente->Visible) { // cliente ?>
	<tr id="r_cliente">
		<td><span id="elh_trabajos_cliente"><?php echo $trabajos->cliente->FldCaption() ?></span></td>
		<td<?php echo $trabajos->cliente->CellAttributes() ?>>
<span id="el_trabajos_cliente" class="control-group">
<span<?php echo $trabajos->cliente->ViewAttributes() ?>>
<?php echo $trabajos->cliente->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->id_tipo_cliente->Visible) { // id_tipo_cliente ?>
	<tr id="r_id_tipo_cliente">
		<td><span id="elh_trabajos_id_tipo_cliente"><?php echo $trabajos->id_tipo_cliente->FldCaption() ?></span></td>
		<td<?php echo $trabajos->id_tipo_cliente->CellAttributes() ?>>
<span id="el_trabajos_id_tipo_cliente" class="control-group">
<span<?php echo $trabajos->id_tipo_cliente->ViewAttributes() ?>>
<?php echo $trabajos->id_tipo_cliente->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->tel->Visible) { // tel ?>
	<tr id="r_tel">
		<td><span id="elh_trabajos_tel"><?php echo $trabajos->tel->FldCaption() ?></span></td>
		<td<?php echo $trabajos->tel->CellAttributes() ?>>
<span id="el_trabajos_tel" class="control-group">
<span<?php echo $trabajos->tel->ViewAttributes() ?>>
<?php echo $trabajos->tel->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->cel->Visible) { // cel ?>
	<tr id="r_cel">
		<td><span id="elh_trabajos_cel"><?php echo $trabajos->cel->FldCaption() ?></span></td>
		<td<?php echo $trabajos->cel->CellAttributes() ?>>
<span id="el_trabajos_cel" class="control-group">
<span<?php echo $trabajos->cel->ViewAttributes() ?>>
<?php echo $trabajos->cel->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->objetos->Visible) { // objetos ?>
	<tr id="r_objetos">
		<td><span id="elh_trabajos_objetos"><?php echo $trabajos->objetos->FldCaption() ?></span></td>
		<td<?php echo $trabajos->objetos->CellAttributes() ?>>
<span id="el_trabajos_objetos" class="control-group">
<span<?php echo $trabajos->objetos->ViewAttributes() ?>>
<?php echo $trabajos->objetos->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->detalle_a_realizar->Visible) { // detalle_a_realizar ?>
	<tr id="r_detalle_a_realizar">
		<td><span id="elh_trabajos_detalle_a_realizar"><?php echo $trabajos->detalle_a_realizar->FldCaption() ?></span></td>
		<td<?php echo $trabajos->detalle_a_realizar->CellAttributes() ?>>
<span id="el_trabajos_detalle_a_realizar" class="control-group">
<span<?php echo $trabajos->detalle_a_realizar->ViewAttributes() ?>>
<?php echo $trabajos->detalle_a_realizar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->fecha_entrega->Visible) { // fecha_entrega ?>
	<tr id="r_fecha_entrega">
		<td><span id="elh_trabajos_fecha_entrega"><?php echo $trabajos->fecha_entrega->FldCaption() ?></span></td>
		<td<?php echo $trabajos->fecha_entrega->CellAttributes() ?>>
<span id="el_trabajos_fecha_entrega" class="control-group">
<span<?php echo $trabajos->fecha_entrega->ViewAttributes() ?>>
<?php echo $trabajos->fecha_entrega->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->observaciones->Visible) { // observaciones ?>
	<tr id="r_observaciones">
		<td><span id="elh_trabajos_observaciones"><?php echo $trabajos->observaciones->FldCaption() ?></span></td>
		<td<?php echo $trabajos->observaciones->CellAttributes() ?>>
<span id="el_trabajos_observaciones" class="control-group">
<span<?php echo $trabajos->observaciones->ViewAttributes() ?>>
<?php echo $trabajos->observaciones->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->id_estado->Visible) { // id_estado ?>
	<tr id="r_id_estado">
		<td><span id="elh_trabajos_id_estado"><?php echo $trabajos->id_estado->FldCaption() ?></span></td>
		<td<?php echo $trabajos->id_estado->CellAttributes() ?>>
<span id="el_trabajos_id_estado" class="control-group">
<span<?php echo $trabajos->id_estado->ViewAttributes() ?>>
<?php echo $trabajos->id_estado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->precio->Visible) { // precio ?>
	<tr id="r_precio">
		<td><span id="elh_trabajos_precio"><?php echo $trabajos->precio->FldCaption() ?></span></td>
		<td<?php echo $trabajos->precio->CellAttributes() ?>>
<span id="el_trabajos_precio" class="control-group">
<span<?php echo $trabajos->precio->ViewAttributes() ?>>
<?php echo $trabajos->precio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->entrega->Visible) { // entrega ?>
	<tr id="r_entrega">
		<td><span id="elh_trabajos_entrega"><?php echo $trabajos->entrega->FldCaption() ?></span></td>
		<td<?php echo $trabajos->entrega->CellAttributes() ?>>
<span id="el_trabajos_entrega" class="control-group">
<span<?php echo $trabajos->entrega->ViewAttributes() ?>>
<?php echo $trabajos->entrega->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->saldo->Visible) { // saldo ?>
	<tr id="r_saldo">
		<td><span id="elh_trabajos_saldo"><?php echo $trabajos->saldo->FldCaption() ?></span></td>
		<td<?php echo $trabajos->saldo->CellAttributes() ?>>
<span id="el_trabajos_saldo" class="control-group">
<span<?php echo $trabajos->saldo->ViewAttributes() ?>>
<?php echo $trabajos->saldo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->foto1->Visible) { // foto1 ?>
	<tr id="r_foto1">
		<td><span id="elh_trabajos_foto1"><?php echo $trabajos->foto1->FldCaption() ?></span></td>
		<td<?php echo $trabajos->foto1->CellAttributes() ?>>
<span id="el_trabajos_foto1" class="control-group">
<span>
<?php if ($trabajos->foto1->LinkAttributes() <> "") { ?>
<?php if (!empty($trabajos->foto1->Upload->DbValue)) { ?>
<img src="<?php echo $trabajos->foto1->ViewValue ?>" alt="" style="border: 0;"<?php echo $trabajos->foto1->ViewAttributes() ?>>
<?php } elseif (!in_array($trabajos->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($trabajos->foto1->Upload->DbValue)) { ?>
<img src="<?php echo $trabajos->foto1->ViewValue ?>" alt="" style="border: 0;"<?php echo $trabajos->foto1->ViewAttributes() ?>>
<?php } elseif (!in_array($trabajos->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($trabajos->foto2->Visible) { // foto2 ?>
	<tr id="r_foto2">
		<td><span id="elh_trabajos_foto2"><?php echo $trabajos->foto2->FldCaption() ?></span></td>
		<td<?php echo $trabajos->foto2->CellAttributes() ?>>
<span id="el_trabajos_foto2" class="control-group">
<span>
<?php if ($trabajos->foto2->LinkAttributes() <> "") { ?>
<?php if (!empty($trabajos->foto2->Upload->DbValue)) { ?>
<img src="<?php echo $trabajos->foto2->ViewValue ?>" alt="" style="border: 0;"<?php echo $trabajos->foto2->ViewAttributes() ?>>
<?php } elseif (!in_array($trabajos->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($trabajos->foto2->Upload->DbValue)) { ?>
<img src="<?php echo $trabajos->foto2->ViewValue ?>" alt="" style="border: 0;"<?php echo $trabajos->foto2->ViewAttributes() ?>>
<?php } elseif (!in_array($trabajos->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
ftrabajosview.Init();
</script>
<?php
$trabajos_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($trabajos->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$trabajos_view->Page_Terminate();
?>
