<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "choferesinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$choferes_delete = NULL; // Initialize page object first

class cchoferes_delete extends cchoferes {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'choferes';

	// Page object name
	var $PageObjName = 'choferes_delete';

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

		// Table object (choferes)
		if (!isset($GLOBALS["choferes"]) || get_class($GLOBALS["choferes"]) == "cchoferes") {
			$GLOBALS["choferes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["choferes"];
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
			define("EW_TABLE_NAME", 'choferes', TRUE);

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
		global $EW_EXPORT, $choferes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($choferes);
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
			$this->Page_Terminate("chofereslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in choferes class, choferesinfo.php

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
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->fecha_inicio->setDbValue($rs->fields('fecha_inicio'));
		$this->cetegoria->setDbValue($rs->fields('cetegoria'));
		$this->datos->setDbValue($rs->fields('datos'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->nombre->DbValue = $row['nombre'];
		$this->direccion->DbValue = $row['direccion'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->tel->DbValue = $row['tel'];
		$this->cel->DbValue = $row['cel'];
		$this->_email->DbValue = $row['email'];
		$this->fecha_inicio->DbValue = $row['fecha_inicio'];
		$this->cetegoria->DbValue = $row['cetegoria'];
		$this->datos->DbValue = $row['datos'];
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
		// nombre
		// direccion
		// fecha_nacimiento
		// tel
		// cel
		// email
		// fecha_inicio
		// cetegoria
		// datos

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->ViewCustomAttributes = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
			$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 7);
			$this->fecha_nacimiento->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewCustomAttributes = "";

			// cel
			$this->cel->ViewValue = $this->cel->CurrentValue;
			$this->cel->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewValue = strtolower($this->_email->ViewValue);
			$this->_email->ViewCustomAttributes = "";

			// fecha_inicio
			$this->fecha_inicio->ViewValue = $this->fecha_inicio->CurrentValue;
			$this->fecha_inicio->ViewValue = ew_FormatDateTime($this->fecha_inicio->ViewValue, 7);
			$this->fecha_inicio->ViewCustomAttributes = "";

			// cetegoria
			$this->cetegoria->ViewValue = $this->cetegoria->CurrentValue;
			$this->cetegoria->ViewValue = ew_FormatNumber($this->cetegoria->ViewValue, 0, -2, -2, -2);
			$this->cetegoria->ViewCustomAttributes = "";

			// datos
			$this->datos->ViewValue = $this->datos->CurrentValue;
			$this->datos->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

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

			// fecha_inicio
			$this->fecha_inicio->LinkCustomAttributes = "";
			$this->fecha_inicio->HrefValue = "";
			$this->fecha_inicio->TooltipValue = "";

			// cetegoria
			$this->cetegoria->LinkCustomAttributes = "";
			$this->cetegoria->HrefValue = "";
			$this->cetegoria->TooltipValue = "";

			// datos
			$this->datos->LinkCustomAttributes = "";
			$this->datos->HrefValue = "";
			$this->datos->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, "chofereslist.php", "", $this->TableVar, TRUE);
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
if (!isset($choferes_delete)) $choferes_delete = new cchoferes_delete();

// Page init
$choferes_delete->Page_Init();

// Page main
$choferes_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$choferes_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var choferes_delete = new ew_Page("choferes_delete");
choferes_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = choferes_delete.PageID; // For backward compatibility

// Form object
var fchoferesdelete = new ew_Form("fchoferesdelete");

// Form_CustomValidate event
fchoferesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fchoferesdelete.ValidateRequired = true;
<?php } else { ?>
fchoferesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($choferes_delete->Recordset = $choferes_delete->LoadRecordset())
	$choferes_deleteTotalRecs = $choferes_delete->Recordset->RecordCount(); // Get record count
if ($choferes_deleteTotalRecs <= 0) { // No record found, exit
	if ($choferes_delete->Recordset)
		$choferes_delete->Recordset->Close();
	$choferes_delete->Page_Terminate("chofereslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $choferes_delete->ShowPageHeader(); ?>
<?php
$choferes_delete->ShowMessage();
?>
<form name="fchoferesdelete" id="fchoferesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($choferes_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $choferes_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="choferes">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($choferes_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $choferes->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($choferes->codigo->Visible) { // codigo ?>
		<th><span id="elh_choferes_codigo" class="choferes_codigo"><?php echo $choferes->codigo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($choferes->nombre->Visible) { // nombre ?>
		<th><span id="elh_choferes_nombre" class="choferes_nombre"><?php echo $choferes->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($choferes->direccion->Visible) { // direccion ?>
		<th><span id="elh_choferes_direccion" class="choferes_direccion"><?php echo $choferes->direccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($choferes->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<th><span id="elh_choferes_fecha_nacimiento" class="choferes_fecha_nacimiento"><?php echo $choferes->fecha_nacimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($choferes->tel->Visible) { // tel ?>
		<th><span id="elh_choferes_tel" class="choferes_tel"><?php echo $choferes->tel->FldCaption() ?></span></th>
<?php } ?>
<?php if ($choferes->cel->Visible) { // cel ?>
		<th><span id="elh_choferes_cel" class="choferes_cel"><?php echo $choferes->cel->FldCaption() ?></span></th>
<?php } ?>
<?php if ($choferes->_email->Visible) { // email ?>
		<th><span id="elh_choferes__email" class="choferes__email"><?php echo $choferes->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($choferes->fecha_inicio->Visible) { // fecha_inicio ?>
		<th><span id="elh_choferes_fecha_inicio" class="choferes_fecha_inicio"><?php echo $choferes->fecha_inicio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($choferes->cetegoria->Visible) { // cetegoria ?>
		<th><span id="elh_choferes_cetegoria" class="choferes_cetegoria"><?php echo $choferes->cetegoria->FldCaption() ?></span></th>
<?php } ?>
<?php if ($choferes->datos->Visible) { // datos ?>
		<th><span id="elh_choferes_datos" class="choferes_datos"><?php echo $choferes->datos->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$choferes_delete->RecCnt = 0;
$i = 0;
while (!$choferes_delete->Recordset->EOF) {
	$choferes_delete->RecCnt++;
	$choferes_delete->RowCnt++;

	// Set row properties
	$choferes->ResetAttrs();
	$choferes->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$choferes_delete->LoadRowValues($choferes_delete->Recordset);

	// Render row
	$choferes_delete->RenderRow();
?>
	<tr<?php echo $choferes->RowAttributes() ?>>
<?php if ($choferes->codigo->Visible) { // codigo ?>
		<td<?php echo $choferes->codigo->CellAttributes() ?>>
<span id="el<?php echo $choferes_delete->RowCnt ?>_choferes_codigo" class="choferes_codigo">
<span<?php echo $choferes->codigo->ViewAttributes() ?>>
<?php echo $choferes->codigo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($choferes->nombre->Visible) { // nombre ?>
		<td<?php echo $choferes->nombre->CellAttributes() ?>>
<span id="el<?php echo $choferes_delete->RowCnt ?>_choferes_nombre" class="choferes_nombre">
<span<?php echo $choferes->nombre->ViewAttributes() ?>>
<?php echo $choferes->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($choferes->direccion->Visible) { // direccion ?>
		<td<?php echo $choferes->direccion->CellAttributes() ?>>
<span id="el<?php echo $choferes_delete->RowCnt ?>_choferes_direccion" class="choferes_direccion">
<span<?php echo $choferes->direccion->ViewAttributes() ?>>
<?php echo $choferes->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($choferes->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td<?php echo $choferes->fecha_nacimiento->CellAttributes() ?>>
<span id="el<?php echo $choferes_delete->RowCnt ?>_choferes_fecha_nacimiento" class="choferes_fecha_nacimiento">
<span<?php echo $choferes->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $choferes->fecha_nacimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($choferes->tel->Visible) { // tel ?>
		<td<?php echo $choferes->tel->CellAttributes() ?>>
<span id="el<?php echo $choferes_delete->RowCnt ?>_choferes_tel" class="choferes_tel">
<span<?php echo $choferes->tel->ViewAttributes() ?>>
<?php echo $choferes->tel->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($choferes->cel->Visible) { // cel ?>
		<td<?php echo $choferes->cel->CellAttributes() ?>>
<span id="el<?php echo $choferes_delete->RowCnt ?>_choferes_cel" class="choferes_cel">
<span<?php echo $choferes->cel->ViewAttributes() ?>>
<?php echo $choferes->cel->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($choferes->_email->Visible) { // email ?>
		<td<?php echo $choferes->_email->CellAttributes() ?>>
<span id="el<?php echo $choferes_delete->RowCnt ?>_choferes__email" class="choferes__email">
<span<?php echo $choferes->_email->ViewAttributes() ?>>
<?php echo $choferes->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($choferes->fecha_inicio->Visible) { // fecha_inicio ?>
		<td<?php echo $choferes->fecha_inicio->CellAttributes() ?>>
<span id="el<?php echo $choferes_delete->RowCnt ?>_choferes_fecha_inicio" class="choferes_fecha_inicio">
<span<?php echo $choferes->fecha_inicio->ViewAttributes() ?>>
<?php echo $choferes->fecha_inicio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($choferes->cetegoria->Visible) { // cetegoria ?>
		<td<?php echo $choferes->cetegoria->CellAttributes() ?>>
<span id="el<?php echo $choferes_delete->RowCnt ?>_choferes_cetegoria" class="choferes_cetegoria">
<span<?php echo $choferes->cetegoria->ViewAttributes() ?>>
<?php echo $choferes->cetegoria->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($choferes->datos->Visible) { // datos ?>
		<td<?php echo $choferes->datos->CellAttributes() ?>>
<span id="el<?php echo $choferes_delete->RowCnt ?>_choferes_datos" class="choferes_datos">
<span<?php echo $choferes->datos->ViewAttributes() ?>>
<?php echo $choferes->datos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$choferes_delete->Recordset->MoveNext();
}
$choferes_delete->Recordset->Close();
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
fchoferesdelete.Init();
</script>
<?php
$choferes_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$choferes_delete->Page_Terminate();
?>
