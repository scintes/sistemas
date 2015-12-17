<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "cciagewcfg10.php" ?>
<?php include_once "cciagewmysql10.php" ?>
<?php include_once "cciagphpfn10.php" ?>
<?php include_once "cciagsociosinfo.php" ?>
<?php include_once "cciagusuarioinfo.php" ?>
<?php include_once "cciaguserfn10.php" ?>
<?php

//
// Page class
//

$socios_delete = NULL; // Initialize page object first

class csocios_delete extends csocios {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'socios';

	// Page object name
	var $PageObjName = 'socios_delete';

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
	var $AuditTrailOnDelete = TRUE;

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("cciaglogin.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("cciagsocioslist.php");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->socio_nro->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("cciagsocioslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in socios class, sociosinfo.php

		$this->CurrentFilter = $sFilter;

		// Check if valid user id
		$sql = $this->GetSQL($this->CurrentFilter, "");
		if ($this->Recordset = ew_LoadRecordset($sql)) {
			$res = TRUE;
			while (!$this->Recordset->EOF) {
				$this->LoadRowValues($this->Recordset);
				if (!$this->ShowOptionLink('delete')) {
					$sUserIdMsg = $Language->Phrase("NoDeletePermission");
					$this->setFailureMessage($sUserIdMsg);
					$res = FALSE;
					break;
				}
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
			if (!$res) $this->Page_Terminate("cciagsocioslist.php"); // Return to list
		}

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
		$this->socio_nro->setDbValue($rs->fields('socio_nro'));
		$this->id_actividad->setDbValue($rs->fields('id_actividad'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		$this->propietario->setDbValue($rs->fields('propietario'));
		$this->comercio->setDbValue($rs->fields('comercio'));
		$this->direccion_comercio->setDbValue($rs->fields('direccion_comercio'));
		$this->activo->setDbValue($rs->fields('activo'));
		$this->mail->setDbValue($rs->fields('mail'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->cuit_cuil->setDbValue($rs->fields('cuit_cuil'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->socio_nro->DbValue = $row['socio_nro'];
		$this->id_actividad->DbValue = $row['id_actividad'];
		$this->id_usuario->DbValue = $row['id_usuario'];
		$this->propietario->DbValue = $row['propietario'];
		$this->comercio->DbValue = $row['comercio'];
		$this->direccion_comercio->DbValue = $row['direccion_comercio'];
		$this->activo->DbValue = $row['activo'];
		$this->mail->DbValue = $row['mail'];
		$this->tel->DbValue = $row['tel'];
		$this->cel->DbValue = $row['cel'];
		$this->cuit_cuil->DbValue = $row['cuit_cuil'];
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
		// id_actividad

		$this->id_actividad->CellCssStyle = "white-space: nowrap;";

		// id_usuario
		// propietario
		// comercio
		// direccion_comercio
		// activo
		// mail
		// tel
		// cel
		// cuit_cuil

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// socio_nro
			$this->socio_nro->ViewValue = $this->socio_nro->CurrentValue;
			$this->socio_nro->ViewCustomAttributes = "";

			// id_usuario
			$this->id_usuario->ViewValue = $this->id_usuario->CurrentValue;
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

			// mail
			$this->mail->ViewValue = $this->mail->CurrentValue;
			$this->mail->ViewValue = strtolower($this->mail->ViewValue);
			$this->mail->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewValue = trim($this->tel->ViewValue);
			$this->tel->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewValue = trim($this->cel->ViewValue);
			$this->cel->ViewCustomAttributes = "";

			// cuit_cuil
			$this->cuit_cuil->ViewValue = $this->cuit_cuil->CurrentValue;
			$this->cuit_cuil->ViewValue = ew_FormatNumber($this->cuit_cuil->ViewValue, 0, -2, -2, -2);
			$this->cuit_cuil->ViewCustomAttributes = "";

			// socio_nro
			$this->socio_nro->LinkCustomAttributes = "";
			$this->socio_nro->HrefValue = "";
			$this->socio_nro->TooltipValue = "";

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

			// mail
			$this->mail->LinkCustomAttributes = "";
			$this->mail->HrefValue = "";
			$this->mail->TooltipValue = "";

			// tel
			$this->tel->LinkCustomAttributes = "";
			$this->tel->HrefValue = "";
			$this->tel->TooltipValue = "";

			// cel
			$this->cel->LinkCustomAttributes = "";
			$this->cel->HrefValue = "";
			$this->cel->TooltipValue = "";

			// cuit_cuil
			$this->cuit_cuil->LinkCustomAttributes = "";
			$this->cuit_cuil->HrefValue = "";
			$this->cuit_cuil->TooltipValue = "";
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
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

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
				$sThisKey .= $row['socio_nro'];
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
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->id_usuario->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "cciagsocioslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'socios';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'socios';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['socio_nro'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
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
if (!isset($socios_delete)) $socios_delete = new csocios_delete();

// Page init
$socios_delete->Page_Init();

// Page main
$socios_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_delete->Page_Render();
?>
<?php include_once "cciagheader.php" ?>
<script type="text/javascript">

// Page object
var socios_delete = new ew_Page("socios_delete");
socios_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = socios_delete.PageID; // For backward compatibility

// Form object
var fsociosdelete = new ew_Form("fsociosdelete");

// Form_CustomValidate event
fsociosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsociosdelete.ValidateRequired = true;
<?php } else { ?>
fsociosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($socios_delete->Recordset = $socios_delete->LoadRecordset())
	$socios_deleteTotalRecs = $socios_delete->Recordset->RecordCount(); // Get record count
if ($socios_deleteTotalRecs <= 0) { // No record found, exit
	if ($socios_delete->Recordset)
		$socios_delete->Recordset->Close();
	$socios_delete->Page_Terminate("cciagsocioslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $socios_delete->ShowPageHeader(); ?>
<?php
$socios_delete->ShowMessage();
?>
<form name="fsociosdelete" id="fsociosdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="socios">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($socios_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_sociosdelete" class="ewTable ewTableSeparate">
<?php echo $socios->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
		<td><span id="elh_socios_socio_nro" class="socios_socio_nro"><?php echo $socios->socio_nro->FldCaption() ?></span></td>
<?php } ?>
<?php if ($socios->id_usuario->Visible) { // id_usuario ?>
		<td><span id="elh_socios_id_usuario" class="socios_id_usuario"><?php echo $socios->id_usuario->FldCaption() ?></span></td>
<?php } ?>
<?php if ($socios->propietario->Visible) { // propietario ?>
		<td><span id="elh_socios_propietario" class="socios_propietario"><?php echo $socios->propietario->FldCaption() ?></span></td>
<?php } ?>
<?php if ($socios->comercio->Visible) { // comercio ?>
		<td><span id="elh_socios_comercio" class="socios_comercio"><?php echo $socios->comercio->FldCaption() ?></span></td>
<?php } ?>
<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
		<td><span id="elh_socios_direccion_comercio" class="socios_direccion_comercio"><?php echo $socios->direccion_comercio->FldCaption() ?></span></td>
<?php } ?>
<?php if ($socios->activo->Visible) { // activo ?>
		<td><span id="elh_socios_activo" class="socios_activo"><?php echo $socios->activo->FldCaption() ?></span></td>
<?php } ?>
<?php if ($socios->mail->Visible) { // mail ?>
		<td><span id="elh_socios_mail" class="socios_mail"><?php echo $socios->mail->FldCaption() ?></span></td>
<?php } ?>
<?php if ($socios->tel->Visible) { // tel ?>
		<td><span id="elh_socios_tel" class="socios_tel"><?php echo $socios->tel->FldCaption() ?></span></td>
<?php } ?>
<?php if ($socios->cel->Visible) { // cel ?>
		<td><span id="elh_socios_cel" class="socios_cel"><?php echo $socios->cel->FldCaption() ?></span></td>
<?php } ?>
<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
		<td><span id="elh_socios_cuit_cuil" class="socios_cuit_cuil"><?php echo $socios->cuit_cuil->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$socios_delete->RecCnt = 0;
$i = 0;
while (!$socios_delete->Recordset->EOF) {
	$socios_delete->RecCnt++;
	$socios_delete->RowCnt++;

	// Set row properties
	$socios->ResetAttrs();
	$socios->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$socios_delete->LoadRowValues($socios_delete->Recordset);

	// Render row
	$socios_delete->RenderRow();
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
		<td<?php echo $socios->socio_nro->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_socio_nro" class="control-group socios_socio_nro">
<span<?php echo $socios->socio_nro->ViewAttributes() ?>>
<?php echo $socios->socio_nro->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->id_usuario->Visible) { // id_usuario ?>
		<td<?php echo $socios->id_usuario->CellAttributes() ?>>
<div id="orig<?php echo $socios_delete->RowCnt ?>_socios_id_usuario" class="hide">
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_id_usuario" class="control-group socios_id_usuario">
<span<?php echo $socios->id_usuario->ViewAttributes() ?>>
<?php echo $socios->id_usuario->ListViewValue() ?></span>
</span>
</div>
<div id="swfo_x<?php echo $socios_delete->RowCnt ?>_id_usuario"></div><script type="text/javascript">
ewFlashSWFs[ewFlashSWFs.length] = jQuery.extend({"id":"swfo_x<?php echo $socios_delete->RowCnt ?>_id_usuario","width":200,"height":200,"version":"9.0.0","bgcolor":"#FFFFFF"}, {
});
</script>
</td>
<?php } ?>
<?php if ($socios->propietario->Visible) { // propietario ?>
		<td<?php echo $socios->propietario->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_propietario" class="control-group socios_propietario">
<span<?php echo $socios->propietario->ViewAttributes() ?>>
<?php echo $socios->propietario->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->comercio->Visible) { // comercio ?>
		<td<?php echo $socios->comercio->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_comercio" class="control-group socios_comercio">
<span<?php echo $socios->comercio->ViewAttributes() ?>>
<?php echo $socios->comercio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
		<td<?php echo $socios->direccion_comercio->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_direccion_comercio" class="control-group socios_direccion_comercio">
<span<?php echo $socios->direccion_comercio->ViewAttributes() ?>>
<?php echo $socios->direccion_comercio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->activo->Visible) { // activo ?>
		<td<?php echo $socios->activo->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_activo" class="control-group socios_activo">
<span<?php echo $socios->activo->ViewAttributes() ?>>
<?php echo $socios->activo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->mail->Visible) { // mail ?>
		<td<?php echo $socios->mail->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_mail" class="control-group socios_mail">
<span<?php echo $socios->mail->ViewAttributes() ?>>
<?php echo $socios->mail->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->tel->Visible) { // tel ?>
		<td<?php echo $socios->tel->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_tel" class="control-group socios_tel">
<span<?php echo $socios->tel->ViewAttributes() ?>>
<?php echo $socios->tel->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->cel->Visible) { // cel ?>
		<td<?php echo $socios->cel->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_cel" class="control-group socios_cel">
<span<?php echo $socios->cel->ViewAttributes() ?>>
<?php echo $socios->cel->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
		<td<?php echo $socios->cuit_cuil->CellAttributes() ?>>
<span id="el<?php echo $socios_delete->RowCnt ?>_socios_cuit_cuil" class="control-group socios_cuit_cuil">
<span<?php echo $socios->cuit_cuil->ViewAttributes() ?>>
<?php echo $socios->cuit_cuil->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$socios_delete->Recordset->MoveNext();
}
$socios_delete->Recordset->Close();
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
fsociosdelete.Init();
</script>
<?php
$socios_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "cciagfooter.php" ?>
<?php
$socios_delete->Page_Terminate();
?>
