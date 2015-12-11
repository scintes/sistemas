<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "clientesinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$clientes_delete = NULL; // Initialize page object first

class cclientes_delete extends cclientes {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'clientes';

	// Page object name
	var $PageObjName = 'clientes_delete';

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

		// Table object (clientes)
		if (!isset($GLOBALS["clientes"]) || get_class($GLOBALS["clientes"]) == "cclientes") {
			$GLOBALS["clientes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["clientes"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'clientes', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("clienteslist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->codigo->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $clientes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($clientes);
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
			$this->Page_Terminate("clienteslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in clientes class, clientesinfo.php

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
		$this->cuit_cuil->setDbValue($rs->fields('cuit_cuil'));
		$this->razon_social->setDbValue($rs->fields('razon_social'));
		$this->responsable->setDbValue($rs->fields('responsable'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->id_localidad->setDbValue($rs->fields('id_localidad'));
		if (array_key_exists('EV__id_localidad', $rs->fields)) {
			$this->id_localidad->VirtualValue = $rs->fields('EV__id_localidad'); // Set up virtual field value
		} else {
			$this->id_localidad->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->cuit_cuil->DbValue = $row['cuit_cuil'];
		$this->razon_social->DbValue = $row['razon_social'];
		$this->responsable->DbValue = $row['responsable'];
		$this->tel->DbValue = $row['tel'];
		$this->cel->DbValue = $row['cel'];
		$this->_email->DbValue = $row['email'];
		$this->direccion->DbValue = $row['direccion'];
		$this->id_localidad->DbValue = $row['id_localidad'];
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
		// cuit_cuil
		// razon_social
		// responsable
		// tel
		// cel
		// email
		// direccion
		// id_localidad

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// cuit_cuil
			$this->cuit_cuil->ViewValue = $this->cuit_cuil->CurrentValue;
			$this->cuit_cuil->ViewCustomAttributes = "";

			// razon_social
			$this->razon_social->ViewValue = $this->razon_social->CurrentValue;
			$this->razon_social->ViewCustomAttributes = "";

			// responsable
			$this->responsable->ViewValue = $this->responsable->CurrentValue;
			$this->responsable->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->ViewCustomAttributes = "";

			// id_localidad
			if ($this->id_localidad->VirtualValue <> "") {
				$this->id_localidad->ViewValue = $this->id_localidad->VirtualValue;
			} else {
			if (strval($this->id_localidad->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `localidad` AS `DispFld`, `cp` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_localidad, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `localidad` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_localidad->ViewValue = $rswrk->fields('DispFld');
					$this->id_localidad->ViewValue .= ew_ValueSeparator(1,$this->id_localidad) . $rswrk->fields('Disp2Fld');
					$this->id_localidad->ViewValue .= ew_ValueSeparator(2,$this->id_localidad) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_localidad->ViewValue = $this->id_localidad->CurrentValue;
				}
			} else {
				$this->id_localidad->ViewValue = NULL;
			}
			}
			$this->id_localidad->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// cuit_cuil
			$this->cuit_cuil->LinkCustomAttributes = "";
			$this->cuit_cuil->HrefValue = "";
			$this->cuit_cuil->TooltipValue = "";

			// razon_social
			$this->razon_social->LinkCustomAttributes = "";
			$this->razon_social->HrefValue = "";
			$this->razon_social->TooltipValue = "";

			// responsable
			$this->responsable->LinkCustomAttributes = "";
			$this->responsable->HrefValue = "";
			$this->responsable->TooltipValue = "";

			// tel
			$this->tel->LinkCustomAttributes = "";
			$this->tel->HrefValue = "";
			$this->tel->TooltipValue = "";

			// cel
			$this->cel->LinkCustomAttributes = "";
			$this->cel->HrefValue = "";
			$this->cel->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// id_localidad
			$this->id_localidad->LinkCustomAttributes = "";
			$this->id_localidad->HrefValue = "";
			$this->id_localidad->TooltipValue = "";
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
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
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
				$sThisKey .= $row['codigo'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "clienteslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($clientes_delete)) $clientes_delete = new cclientes_delete();

// Page init
$clientes_delete->Page_Init();

// Page main
$clientes_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$clientes_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var clientes_delete = new ew_Page("clientes_delete");
clientes_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = clientes_delete.PageID; // For backward compatibility

// Form object
var fclientesdelete = new ew_Form("fclientesdelete");

// Form_CustomValidate event
fclientesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclientesdelete.ValidateRequired = true;
<?php } else { ?>
fclientesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclientesdelete.Lists["x_id_localidad"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_localidad","x_cp","x_provincia",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($clientes_delete->Recordset = $clientes_delete->LoadRecordset())
	$clientes_deleteTotalRecs = $clientes_delete->Recordset->RecordCount(); // Get record count
if ($clientes_deleteTotalRecs <= 0) { // No record found, exit
	if ($clientes_delete->Recordset)
		$clientes_delete->Recordset->Close();
	$clientes_delete->Page_Terminate("clienteslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $clientes_delete->ShowPageHeader(); ?>
<?php
$clientes_delete->ShowMessage();
?>
<form name="fclientesdelete" id="fclientesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($clientes_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $clientes_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="clientes">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($clientes_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $clientes->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($clientes->codigo->Visible) { // codigo ?>
		<th><span id="elh_clientes_codigo" class="clientes_codigo"><?php echo $clientes->codigo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($clientes->cuit_cuil->Visible) { // cuit_cuil ?>
		<th><span id="elh_clientes_cuit_cuil" class="clientes_cuit_cuil"><?php echo $clientes->cuit_cuil->FldCaption() ?></span></th>
<?php } ?>
<?php if ($clientes->razon_social->Visible) { // razon_social ?>
		<th><span id="elh_clientes_razon_social" class="clientes_razon_social"><?php echo $clientes->razon_social->FldCaption() ?></span></th>
<?php } ?>
<?php if ($clientes->responsable->Visible) { // responsable ?>
		<th><span id="elh_clientes_responsable" class="clientes_responsable"><?php echo $clientes->responsable->FldCaption() ?></span></th>
<?php } ?>
<?php if ($clientes->tel->Visible) { // tel ?>
		<th><span id="elh_clientes_tel" class="clientes_tel"><?php echo $clientes->tel->FldCaption() ?></span></th>
<?php } ?>
<?php if ($clientes->cel->Visible) { // cel ?>
		<th><span id="elh_clientes_cel" class="clientes_cel"><?php echo $clientes->cel->FldCaption() ?></span></th>
<?php } ?>
<?php if ($clientes->_email->Visible) { // email ?>
		<th><span id="elh_clientes__email" class="clientes__email"><?php echo $clientes->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($clientes->direccion->Visible) { // direccion ?>
		<th><span id="elh_clientes_direccion" class="clientes_direccion"><?php echo $clientes->direccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($clientes->id_localidad->Visible) { // id_localidad ?>
		<th><span id="elh_clientes_id_localidad" class="clientes_id_localidad"><?php echo $clientes->id_localidad->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$clientes_delete->RecCnt = 0;
$i = 0;
while (!$clientes_delete->Recordset->EOF) {
	$clientes_delete->RecCnt++;
	$clientes_delete->RowCnt++;

	// Set row properties
	$clientes->ResetAttrs();
	$clientes->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$clientes_delete->LoadRowValues($clientes_delete->Recordset);

	// Render row
	$clientes_delete->RenderRow();
?>
	<tr<?php echo $clientes->RowAttributes() ?>>
<?php if ($clientes->codigo->Visible) { // codigo ?>
		<td<?php echo $clientes->codigo->CellAttributes() ?>>
<span id="el<?php echo $clientes_delete->RowCnt ?>_clientes_codigo" class="clientes_codigo">
<span<?php echo $clientes->codigo->ViewAttributes() ?>>
<?php echo $clientes->codigo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clientes->cuit_cuil->Visible) { // cuit_cuil ?>
		<td<?php echo $clientes->cuit_cuil->CellAttributes() ?>>
<span id="el<?php echo $clientes_delete->RowCnt ?>_clientes_cuit_cuil" class="clientes_cuit_cuil">
<span<?php echo $clientes->cuit_cuil->ViewAttributes() ?>>
<?php echo $clientes->cuit_cuil->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clientes->razon_social->Visible) { // razon_social ?>
		<td<?php echo $clientes->razon_social->CellAttributes() ?>>
<span id="el<?php echo $clientes_delete->RowCnt ?>_clientes_razon_social" class="clientes_razon_social">
<span<?php echo $clientes->razon_social->ViewAttributes() ?>>
<?php echo $clientes->razon_social->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clientes->responsable->Visible) { // responsable ?>
		<td<?php echo $clientes->responsable->CellAttributes() ?>>
<span id="el<?php echo $clientes_delete->RowCnt ?>_clientes_responsable" class="clientes_responsable">
<span<?php echo $clientes->responsable->ViewAttributes() ?>>
<?php echo $clientes->responsable->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clientes->tel->Visible) { // tel ?>
		<td<?php echo $clientes->tel->CellAttributes() ?>>
<span id="el<?php echo $clientes_delete->RowCnt ?>_clientes_tel" class="clientes_tel">
<span<?php echo $clientes->tel->ViewAttributes() ?>>
<?php echo $clientes->tel->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clientes->cel->Visible) { // cel ?>
		<td<?php echo $clientes->cel->CellAttributes() ?>>
<span id="el<?php echo $clientes_delete->RowCnt ?>_clientes_cel" class="clientes_cel">
<span<?php echo $clientes->cel->ViewAttributes() ?>>
<?php echo $clientes->cel->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clientes->_email->Visible) { // email ?>
		<td<?php echo $clientes->_email->CellAttributes() ?>>
<span id="el<?php echo $clientes_delete->RowCnt ?>_clientes__email" class="clientes__email">
<span<?php echo $clientes->_email->ViewAttributes() ?>>
<?php echo $clientes->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clientes->direccion->Visible) { // direccion ?>
		<td<?php echo $clientes->direccion->CellAttributes() ?>>
<span id="el<?php echo $clientes_delete->RowCnt ?>_clientes_direccion" class="clientes_direccion">
<span<?php echo $clientes->direccion->ViewAttributes() ?>>
<?php echo $clientes->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clientes->id_localidad->Visible) { // id_localidad ?>
		<td<?php echo $clientes->id_localidad->CellAttributes() ?>>
<span id="el<?php echo $clientes_delete->RowCnt ?>_clientes_id_localidad" class="clientes_id_localidad">
<span<?php echo $clientes->id_localidad->ViewAttributes() ?>>
<?php echo $clientes->id_localidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$clientes_delete->Recordset->MoveNext();
}
$clientes_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fclientesdelete.Init();
</script>
<?php
$clientes_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$clientes_delete->Page_Terminate();
?>
