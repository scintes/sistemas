<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "vehiculosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$vehiculos_delete = NULL; // Initialize page object first

class cvehiculos_delete extends cvehiculos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'vehiculos';

	// Page object name
	var $PageObjName = 'vehiculos_delete';

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

		// Table object (vehiculos)
		if (!isset($GLOBALS["vehiculos"]) || get_class($GLOBALS["vehiculos"]) == "cvehiculos") {
			$GLOBALS["vehiculos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vehiculos"];
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
			define("EW_TABLE_NAME", 'vehiculos', TRUE);

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
		global $EW_EXPORT, $vehiculos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($vehiculos);
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
			$this->Page_Terminate("vehiculoslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in vehiculos class, vehiculosinfo.php

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
		$this->Patente->setDbValue($rs->fields('Patente'));
		$this->cantidad_rueda->setDbValue($rs->fields('cantidad_rueda'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->modelo->setDbValue($rs->fields('modelo'));
		$this->id_chofer->setDbValue($rs->fields('id_chofer'));
		if (array_key_exists('EV__id_chofer', $rs->fields)) {
			$this->id_chofer->VirtualValue = $rs->fields('EV__id_chofer'); // Set up virtual field value
		} else {
			$this->id_chofer->VirtualValue = ""; // Clear value
		}
		$this->id_guarda->setDbValue($rs->fields('id_guarda'));
		if (array_key_exists('EV__id_guarda', $rs->fields)) {
			$this->id_guarda->VirtualValue = $rs->fields('EV__id_guarda'); // Set up virtual field value
		} else {
			$this->id_guarda->VirtualValue = ""; // Clear value
		}
		$this->id_marca->setDbValue($rs->fields('id_marca'));
		if (array_key_exists('EV__id_marca', $rs->fields)) {
			$this->id_marca->VirtualValue = $rs->fields('EV__id_marca'); // Set up virtual field value
		} else {
			$this->id_marca->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->Patente->DbValue = $row['Patente'];
		$this->cantidad_rueda->DbValue = $row['cantidad_rueda'];
		$this->nombre->DbValue = $row['nombre'];
		$this->modelo->DbValue = $row['modelo'];
		$this->id_chofer->DbValue = $row['id_chofer'];
		$this->id_guarda->DbValue = $row['id_guarda'];
		$this->id_marca->DbValue = $row['id_marca'];
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
		// Patente
		// cantidad_rueda
		// nombre
		// modelo
		// id_chofer
		// id_guarda
		// id_marca

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// Patente
			$this->Patente->ViewValue = $this->Patente->CurrentValue;
			$this->Patente->ViewCustomAttributes = "";

			// cantidad_rueda
			$this->cantidad_rueda->ViewValue = $this->cantidad_rueda->CurrentValue;
			$this->cantidad_rueda->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// modelo
			$this->modelo->ViewValue = $this->modelo->CurrentValue;
			$this->modelo->ViewCustomAttributes = "";

			// id_chofer
			if ($this->id_chofer->VirtualValue <> "") {
				$this->id_chofer->ViewValue = $this->id_chofer->VirtualValue;
			} else {
			if (strval($this->id_chofer->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_chofer->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `choferes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_chofer, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_chofer->ViewValue = $rswrk->fields('DispFld');
					$this->id_chofer->ViewValue .= ew_ValueSeparator(1,$this->id_chofer) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_chofer->ViewValue = $this->id_chofer->CurrentValue;
				}
			} else {
				$this->id_chofer->ViewValue = NULL;
			}
			}
			$this->id_chofer->ViewCustomAttributes = "";

			// id_guarda
			if ($this->id_guarda->VirtualValue <> "") {
				$this->id_guarda->ViewValue = $this->id_guarda->VirtualValue;
			} else {
			if (strval($this->id_guarda->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_guarda->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `choferes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_guarda, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_guarda->ViewValue = $rswrk->fields('DispFld');
					$this->id_guarda->ViewValue .= ew_ValueSeparator(1,$this->id_guarda) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_guarda->ViewValue = $this->id_guarda->CurrentValue;
				}
			} else {
				$this->id_guarda->ViewValue = NULL;
			}
			}
			$this->id_guarda->ViewCustomAttributes = "";

			// id_marca
			if ($this->id_marca->VirtualValue <> "") {
				$this->id_marca->ViewValue = $this->id_marca->VirtualValue;
			} else {
			if (strval($this->id_marca->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_marca->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `marca` AS `DispFld`, `modelo` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_marca, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `marca` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_marca->ViewValue = $rswrk->fields('DispFld');
					$this->id_marca->ViewValue .= ew_ValueSeparator(1,$this->id_marca) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_marca->ViewValue = $this->id_marca->CurrentValue;
				}
			} else {
				$this->id_marca->ViewValue = NULL;
			}
			}
			$this->id_marca->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// Patente
			$this->Patente->LinkCustomAttributes = "";
			$this->Patente->HrefValue = "";
			$this->Patente->TooltipValue = "";

			// cantidad_rueda
			$this->cantidad_rueda->LinkCustomAttributes = "";
			$this->cantidad_rueda->HrefValue = "";
			$this->cantidad_rueda->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// modelo
			$this->modelo->LinkCustomAttributes = "";
			$this->modelo->HrefValue = "";
			$this->modelo->TooltipValue = "";

			// id_chofer
			$this->id_chofer->LinkCustomAttributes = "";
			$this->id_chofer->HrefValue = "";
			$this->id_chofer->TooltipValue = "";

			// id_guarda
			$this->id_guarda->LinkCustomAttributes = "";
			$this->id_guarda->HrefValue = "";
			$this->id_guarda->TooltipValue = "";

			// id_marca
			$this->id_marca->LinkCustomAttributes = "";
			$this->id_marca->HrefValue = "";
			$this->id_marca->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, "vehiculoslist.php", "", $this->TableVar, TRUE);
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
if (!isset($vehiculos_delete)) $vehiculos_delete = new cvehiculos_delete();

// Page init
$vehiculos_delete->Page_Init();

// Page main
$vehiculos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vehiculos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var vehiculos_delete = new ew_Page("vehiculos_delete");
vehiculos_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = vehiculos_delete.PageID; // For backward compatibility

// Form object
var fvehiculosdelete = new ew_Form("fvehiculosdelete");

// Form_CustomValidate event
fvehiculosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvehiculosdelete.ValidateRequired = true;
<?php } else { ?>
fvehiculosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvehiculosdelete.Lists["x_id_chofer"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fvehiculosdelete.Lists["x_id_guarda"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fvehiculosdelete.Lists["x_id_marca"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_marca","x_modelo","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($vehiculos_delete->Recordset = $vehiculos_delete->LoadRecordset())
	$vehiculos_deleteTotalRecs = $vehiculos_delete->Recordset->RecordCount(); // Get record count
if ($vehiculos_deleteTotalRecs <= 0) { // No record found, exit
	if ($vehiculos_delete->Recordset)
		$vehiculos_delete->Recordset->Close();
	$vehiculos_delete->Page_Terminate("vehiculoslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $vehiculos_delete->ShowPageHeader(); ?>
<?php
$vehiculos_delete->ShowMessage();
?>
<form name="fvehiculosdelete" id="fvehiculosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vehiculos_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vehiculos_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vehiculos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($vehiculos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $vehiculos->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($vehiculos->codigo->Visible) { // codigo ?>
		<th><span id="elh_vehiculos_codigo" class="vehiculos_codigo"><?php echo $vehiculos->codigo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vehiculos->Patente->Visible) { // Patente ?>
		<th><span id="elh_vehiculos_Patente" class="vehiculos_Patente"><?php echo $vehiculos->Patente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vehiculos->cantidad_rueda->Visible) { // cantidad_rueda ?>
		<th><span id="elh_vehiculos_cantidad_rueda" class="vehiculos_cantidad_rueda"><?php echo $vehiculos->cantidad_rueda->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vehiculos->nombre->Visible) { // nombre ?>
		<th><span id="elh_vehiculos_nombre" class="vehiculos_nombre"><?php echo $vehiculos->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vehiculos->modelo->Visible) { // modelo ?>
		<th><span id="elh_vehiculos_modelo" class="vehiculos_modelo"><?php echo $vehiculos->modelo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vehiculos->id_chofer->Visible) { // id_chofer ?>
		<th><span id="elh_vehiculos_id_chofer" class="vehiculos_id_chofer"><?php echo $vehiculos->id_chofer->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vehiculos->id_guarda->Visible) { // id_guarda ?>
		<th><span id="elh_vehiculos_id_guarda" class="vehiculos_id_guarda"><?php echo $vehiculos->id_guarda->FldCaption() ?></span></th>
<?php } ?>
<?php if ($vehiculos->id_marca->Visible) { // id_marca ?>
		<th><span id="elh_vehiculos_id_marca" class="vehiculos_id_marca"><?php echo $vehiculos->id_marca->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$vehiculos_delete->RecCnt = 0;
$i = 0;
while (!$vehiculos_delete->Recordset->EOF) {
	$vehiculos_delete->RecCnt++;
	$vehiculos_delete->RowCnt++;

	// Set row properties
	$vehiculos->ResetAttrs();
	$vehiculos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$vehiculos_delete->LoadRowValues($vehiculos_delete->Recordset);

	// Render row
	$vehiculos_delete->RenderRow();
?>
	<tr<?php echo $vehiculos->RowAttributes() ?>>
<?php if ($vehiculos->codigo->Visible) { // codigo ?>
		<td<?php echo $vehiculos->codigo->CellAttributes() ?>>
<span id="el<?php echo $vehiculos_delete->RowCnt ?>_vehiculos_codigo" class="vehiculos_codigo">
<span<?php echo $vehiculos->codigo->ViewAttributes() ?>>
<?php echo $vehiculos->codigo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($vehiculos->Patente->Visible) { // Patente ?>
		<td<?php echo $vehiculos->Patente->CellAttributes() ?>>
<span id="el<?php echo $vehiculos_delete->RowCnt ?>_vehiculos_Patente" class="vehiculos_Patente">
<span<?php echo $vehiculos->Patente->ViewAttributes() ?>>
<?php echo $vehiculos->Patente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($vehiculos->cantidad_rueda->Visible) { // cantidad_rueda ?>
		<td<?php echo $vehiculos->cantidad_rueda->CellAttributes() ?>>
<span id="el<?php echo $vehiculos_delete->RowCnt ?>_vehiculos_cantidad_rueda" class="vehiculos_cantidad_rueda">
<span<?php echo $vehiculos->cantidad_rueda->ViewAttributes() ?>>
<?php echo $vehiculos->cantidad_rueda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($vehiculos->nombre->Visible) { // nombre ?>
		<td<?php echo $vehiculos->nombre->CellAttributes() ?>>
<span id="el<?php echo $vehiculos_delete->RowCnt ?>_vehiculos_nombre" class="vehiculos_nombre">
<span<?php echo $vehiculos->nombre->ViewAttributes() ?>>
<?php echo $vehiculos->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($vehiculos->modelo->Visible) { // modelo ?>
		<td<?php echo $vehiculos->modelo->CellAttributes() ?>>
<span id="el<?php echo $vehiculos_delete->RowCnt ?>_vehiculos_modelo" class="vehiculos_modelo">
<span<?php echo $vehiculos->modelo->ViewAttributes() ?>>
<?php echo $vehiculos->modelo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($vehiculos->id_chofer->Visible) { // id_chofer ?>
		<td<?php echo $vehiculos->id_chofer->CellAttributes() ?>>
<span id="el<?php echo $vehiculos_delete->RowCnt ?>_vehiculos_id_chofer" class="vehiculos_id_chofer">
<span<?php echo $vehiculos->id_chofer->ViewAttributes() ?>>
<?php echo $vehiculos->id_chofer->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($vehiculos->id_guarda->Visible) { // id_guarda ?>
		<td<?php echo $vehiculos->id_guarda->CellAttributes() ?>>
<span id="el<?php echo $vehiculos_delete->RowCnt ?>_vehiculos_id_guarda" class="vehiculos_id_guarda">
<span<?php echo $vehiculos->id_guarda->ViewAttributes() ?>>
<?php echo $vehiculos->id_guarda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($vehiculos->id_marca->Visible) { // id_marca ?>
		<td<?php echo $vehiculos->id_marca->CellAttributes() ?>>
<span id="el<?php echo $vehiculos_delete->RowCnt ?>_vehiculos_id_marca" class="vehiculos_id_marca">
<span<?php echo $vehiculos->id_marca->ViewAttributes() ?>>
<?php echo $vehiculos->id_marca->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$vehiculos_delete->Recordset->MoveNext();
}
$vehiculos_delete->Recordset->Close();
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
fvehiculosdelete.Init();
</script>
<?php
$vehiculos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vehiculos_delete->Page_Terminate();
?>
