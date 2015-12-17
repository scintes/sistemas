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

$trabajos_delete = NULL; // Initialize page object first

class ctrabajos_delete extends ctrabajos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{83DA4882-2FB3-4AE9-BADA-241C2F6A6920}";

	// Table name
	var $TableName = 'trabajos';

	// Page object name
	var $PageObjName = 'trabajos_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("trabajoslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in trabajos class, trabajosinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
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

		$this->usuario->CellCssStyle = "white-space: nowrap;";
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

			// fecha_entrega
			$this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
			$this->fecha_entrega->ViewValue = ew_FormatDateTime($this->fecha_entrega->ViewValue, 7);
			$this->fecha_entrega->ViewCustomAttributes = "";

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

			// cel
			$this->cel->LinkCustomAttributes = "";
			$this->cel->HrefValue = "";
			$this->cel->TooltipValue = "";

			// objetos
			$this->objetos->LinkCustomAttributes = "";
			$this->objetos->HrefValue = "";
			$this->objetos->TooltipValue = "";

			// fecha_entrega
			$this->fecha_entrega->LinkCustomAttributes = "";
			$this->fecha_entrega->HrefValue = "";
			$this->fecha_entrega->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nro_orden'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->foto1->OldUploadPath) . $row['foto1']);
				@unlink(ew_UploadPathEx(TRUE, $this->foto2->OldUploadPath) . $row['foto2']);
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "trabajoslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($trabajos_delete)) $trabajos_delete = new ctrabajos_delete();

// Page init
$trabajos_delete->Page_Init();

// Page main
$trabajos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$trabajos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var trabajos_delete = new ew_Page("trabajos_delete");
trabajos_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = trabajos_delete.PageID; // For backward compatibility

// Form object
var ftrabajosdelete = new ew_Form("ftrabajosdelete");

// Form_CustomValidate event
ftrabajosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftrabajosdelete.ValidateRequired = true;
<?php } else { ?>
ftrabajosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftrabajosdelete.Lists["x_id_tipo_cliente"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_cliente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftrabajosdelete.Lists["x_id_estado"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_estado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($trabajos_delete->Recordset = $trabajos_delete->LoadRecordset())
	$trabajos_deleteTotalRecs = $trabajos_delete->Recordset->RecordCount(); // Get record count
if ($trabajos_deleteTotalRecs <= 0) { // No record found, exit
	if ($trabajos_delete->Recordset)
		$trabajos_delete->Recordset->Close();
	$trabajos_delete->Page_Terminate("trabajoslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $trabajos_delete->ShowPageHeader(); ?>
<?php
$trabajos_delete->ShowMessage();
?>
<form name="ftrabajosdelete" id="ftrabajosdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="trabajos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($trabajos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_trabajosdelete" class="ewTable ewTableSeparate">
<?php echo $trabajos->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($trabajos->nro_orden->Visible) { // nro_orden ?>
		<td><span id="elh_trabajos_nro_orden" class="trabajos_nro_orden"><?php echo $trabajos->nro_orden->FldCaption() ?></span></td>
<?php } ?>
<?php if ($trabajos->fecha_recepcion->Visible) { // fecha_recepcion ?>
		<td><span id="elh_trabajos_fecha_recepcion" class="trabajos_fecha_recepcion"><?php echo $trabajos->fecha_recepcion->FldCaption() ?></span></td>
<?php } ?>
<?php if ($trabajos->cliente->Visible) { // cliente ?>
		<td><span id="elh_trabajos_cliente" class="trabajos_cliente"><?php echo $trabajos->cliente->FldCaption() ?></span></td>
<?php } ?>
<?php if ($trabajos->id_tipo_cliente->Visible) { // id_tipo_cliente ?>
		<td><span id="elh_trabajos_id_tipo_cliente" class="trabajos_id_tipo_cliente"><?php echo $trabajos->id_tipo_cliente->FldCaption() ?></span></td>
<?php } ?>
<?php if ($trabajos->cel->Visible) { // cel ?>
		<td><span id="elh_trabajos_cel" class="trabajos_cel"><?php echo $trabajos->cel->FldCaption() ?></span></td>
<?php } ?>
<?php if ($trabajos->objetos->Visible) { // objetos ?>
		<td><span id="elh_trabajos_objetos" class="trabajos_objetos"><?php echo $trabajos->objetos->FldCaption() ?></span></td>
<?php } ?>
<?php if ($trabajos->fecha_entrega->Visible) { // fecha_entrega ?>
		<td><span id="elh_trabajos_fecha_entrega" class="trabajos_fecha_entrega"><?php echo $trabajos->fecha_entrega->FldCaption() ?></span></td>
<?php } ?>
<?php if ($trabajos->id_estado->Visible) { // id_estado ?>
		<td><span id="elh_trabajos_id_estado" class="trabajos_id_estado"><?php echo $trabajos->id_estado->FldCaption() ?></span></td>
<?php } ?>
<?php if ($trabajos->precio->Visible) { // precio ?>
		<td><span id="elh_trabajos_precio" class="trabajos_precio"><?php echo $trabajos->precio->FldCaption() ?></span></td>
<?php } ?>
<?php if ($trabajos->entrega->Visible) { // entrega ?>
		<td><span id="elh_trabajos_entrega" class="trabajos_entrega"><?php echo $trabajos->entrega->FldCaption() ?></span></td>
<?php } ?>
<?php if ($trabajos->saldo->Visible) { // saldo ?>
		<td><span id="elh_trabajos_saldo" class="trabajos_saldo"><?php echo $trabajos->saldo->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$trabajos_delete->RecCnt = 0;
$i = 0;
while (!$trabajos_delete->Recordset->EOF) {
	$trabajos_delete->RecCnt++;
	$trabajos_delete->RowCnt++;

	// Set row properties
	$trabajos->ResetAttrs();
	$trabajos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$trabajos_delete->LoadRowValues($trabajos_delete->Recordset);

	// Render row
	$trabajos_delete->RenderRow();
?>
	<tr<?php echo $trabajos->RowAttributes() ?>>
<?php if ($trabajos->nro_orden->Visible) { // nro_orden ?>
		<td<?php echo $trabajos->nro_orden->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_nro_orden" class="control-group trabajos_nro_orden">
<span<?php echo $trabajos->nro_orden->ViewAttributes() ?>>
<?php echo $trabajos->nro_orden->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trabajos->fecha_recepcion->Visible) { // fecha_recepcion ?>
		<td<?php echo $trabajos->fecha_recepcion->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_fecha_recepcion" class="control-group trabajos_fecha_recepcion">
<span<?php echo $trabajos->fecha_recepcion->ViewAttributes() ?>>
<?php echo $trabajos->fecha_recepcion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trabajos->cliente->Visible) { // cliente ?>
		<td<?php echo $trabajos->cliente->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_cliente" class="control-group trabajos_cliente">
<span<?php echo $trabajos->cliente->ViewAttributes() ?>>
<?php echo $trabajos->cliente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trabajos->id_tipo_cliente->Visible) { // id_tipo_cliente ?>
		<td<?php echo $trabajos->id_tipo_cliente->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_id_tipo_cliente" class="control-group trabajos_id_tipo_cliente">
<span<?php echo $trabajos->id_tipo_cliente->ViewAttributes() ?>>
<?php echo $trabajos->id_tipo_cliente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trabajos->cel->Visible) { // cel ?>
		<td<?php echo $trabajos->cel->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_cel" class="control-group trabajos_cel">
<span<?php echo $trabajos->cel->ViewAttributes() ?>>
<?php echo $trabajos->cel->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trabajos->objetos->Visible) { // objetos ?>
		<td<?php echo $trabajos->objetos->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_objetos" class="control-group trabajos_objetos">
<span<?php echo $trabajos->objetos->ViewAttributes() ?>>
<?php echo $trabajos->objetos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trabajos->fecha_entrega->Visible) { // fecha_entrega ?>
		<td<?php echo $trabajos->fecha_entrega->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_fecha_entrega" class="control-group trabajos_fecha_entrega">
<span<?php echo $trabajos->fecha_entrega->ViewAttributes() ?>>
<?php echo $trabajos->fecha_entrega->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trabajos->id_estado->Visible) { // id_estado ?>
		<td<?php echo $trabajos->id_estado->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_id_estado" class="control-group trabajos_id_estado">
<span<?php echo $trabajos->id_estado->ViewAttributes() ?>>
<?php echo $trabajos->id_estado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trabajos->precio->Visible) { // precio ?>
		<td<?php echo $trabajos->precio->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_precio" class="control-group trabajos_precio">
<span<?php echo $trabajos->precio->ViewAttributes() ?>>
<?php echo $trabajos->precio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trabajos->entrega->Visible) { // entrega ?>
		<td<?php echo $trabajos->entrega->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_entrega" class="control-group trabajos_entrega">
<span<?php echo $trabajos->entrega->ViewAttributes() ?>>
<?php echo $trabajos->entrega->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trabajos->saldo->Visible) { // saldo ?>
		<td<?php echo $trabajos->saldo->CellAttributes() ?>>
<span id="el<?php echo $trabajos_delete->RowCnt ?>_trabajos_saldo" class="control-group trabajos_saldo">
<span<?php echo $trabajos->saldo->ViewAttributes() ?>>
<?php echo $trabajos->saldo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$trabajos_delete->Recordset->MoveNext();
}
$trabajos_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftrabajosdelete.Init();
</script>
<?php
$trabajos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$trabajos_delete->Page_Terminate();
?>
