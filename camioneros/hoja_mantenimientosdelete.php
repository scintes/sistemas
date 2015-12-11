<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "hoja_mantenimientosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$hoja_mantenimientos_delete = NULL; // Initialize page object first

class choja_mantenimientos_delete extends choja_mantenimientos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'hoja_mantenimientos';

	// Page object name
	var $PageObjName = 'hoja_mantenimientos_delete';

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

		// Table object (hoja_mantenimientos)
		if (!isset($GLOBALS["hoja_mantenimientos"]) || get_class($GLOBALS["hoja_mantenimientos"]) == "choja_mantenimientos") {
			$GLOBALS["hoja_mantenimientos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["hoja_mantenimientos"];
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
			define("EW_TABLE_NAME", 'hoja_mantenimientos', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("hoja_mantenimientoslist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("hoja_mantenimientoslist.php"));
		}
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

			// Process auto fill for detail table 'gastos_mantenimientos'
			if (@$_POST["grid"] == "fgastos_mantenimientosgrid") {
				if (!isset($GLOBALS["gastos_mantenimientos_grid"])) $GLOBALS["gastos_mantenimientos_grid"] = new cgastos_mantenimientos_grid;
				$GLOBALS["gastos_mantenimientos_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $hoja_mantenimientos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($hoja_mantenimientos);
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
			$this->Page_Terminate("hoja_mantenimientoslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in hoja_mantenimientos class, hoja_mantenimientosinfo.php

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
			if (!$res) $this->Page_Terminate("hoja_mantenimientoslist.php"); // Return to list
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
		$this->fecha_ini->setDbValue($rs->fields('fecha_ini'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->id_vehiculo->setDbValue($rs->fields('id_vehiculo'));
		if (array_key_exists('EV__id_vehiculo', $rs->fields)) {
			$this->id_vehiculo->VirtualValue = $rs->fields('EV__id_vehiculo'); // Set up virtual field value
		} else {
			$this->id_vehiculo->VirtualValue = ""; // Clear value
		}
		$this->id_taller->setDbValue($rs->fields('id_taller'));
		if (array_key_exists('EV__id_taller', $rs->fields)) {
			$this->id_taller->VirtualValue = $rs->fields('EV__id_taller'); // Set up virtual field value
		} else {
			$this->id_taller->VirtualValue = ""; // Clear value
		}
		$this->id_tipo_mantenimiento->setDbValue($rs->fields('id_tipo_mantenimiento'));
		if (array_key_exists('EV__id_tipo_mantenimiento', $rs->fields)) {
			$this->id_tipo_mantenimiento->VirtualValue = $rs->fields('EV__id_tipo_mantenimiento'); // Set up virtual field value
		} else {
			$this->id_tipo_mantenimiento->VirtualValue = ""; // Clear value
		}
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->fecha_ini->DbValue = $row['fecha_ini'];
		$this->fecha_fin->DbValue = $row['fecha_fin'];
		$this->id_vehiculo->DbValue = $row['id_vehiculo'];
		$this->id_taller->DbValue = $row['id_taller'];
		$this->id_tipo_mantenimiento->DbValue = $row['id_tipo_mantenimiento'];
		$this->id_usuario->DbValue = $row['id_usuario'];
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
		// fecha_ini
		// fecha_fin
		// id_vehiculo
		// id_taller
		// id_tipo_mantenimiento
		// id_usuario

		$this->id_usuario->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// fecha_ini
			$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
			$this->fecha_ini->ViewCustomAttributes = "";

			// fecha_fin
			$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
			$this->fecha_fin->ViewCustomAttributes = "";

			// id_vehiculo
			if ($this->id_vehiculo->VirtualValue <> "") {
				$this->id_vehiculo->ViewValue = $this->id_vehiculo->VirtualValue;
			} else {
			if (strval($this->id_vehiculo->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_vehiculo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `modelo` AS `Disp2Fld`, `nombre` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_vehiculo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Patente` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_vehiculo->ViewValue = $rswrk->fields('DispFld');
					$this->id_vehiculo->ViewValue .= ew_ValueSeparator(1,$this->id_vehiculo) . $rswrk->fields('Disp2Fld');
					$this->id_vehiculo->ViewValue .= ew_ValueSeparator(2,$this->id_vehiculo) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_vehiculo->ViewValue = $this->id_vehiculo->CurrentValue;
				}
			} else {
				$this->id_vehiculo->ViewValue = NULL;
			}
			}
			$this->id_vehiculo->ViewCustomAttributes = "";

			// id_taller
			if ($this->id_taller->VirtualValue <> "") {
				$this->id_taller->ViewValue = $this->id_taller->VirtualValue;
			} else {
			if (strval($this->id_taller->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_taller->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `taller` AS `DispFld`, `tel` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `talleres`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_taller, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `taller` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_taller->ViewValue = $rswrk->fields('DispFld');
					$this->id_taller->ViewValue .= ew_ValueSeparator(1,$this->id_taller) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_taller->ViewValue = $this->id_taller->CurrentValue;
				}
			} else {
				$this->id_taller->ViewValue = NULL;
			}
			}
			$this->id_taller->ViewCustomAttributes = "";

			// id_tipo_mantenimiento
			if ($this->id_tipo_mantenimiento->VirtualValue <> "") {
				$this->id_tipo_mantenimiento->ViewValue = $this->id_tipo_mantenimiento->VirtualValue;
			} else {
			if (strval($this->id_tipo_mantenimiento->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_mantenimiento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `tipo_mantenimiento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_mantenimientos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_mantenimiento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `tipo_mantenimiento` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_mantenimiento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_tipo_mantenimiento->ViewValue = $this->id_tipo_mantenimiento->CurrentValue;
				}
			} else {
				$this->id_tipo_mantenimiento->ViewValue = NULL;
			}
			}
			$this->id_tipo_mantenimiento->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// fecha_ini
			$this->fecha_ini->LinkCustomAttributes = "";
			$this->fecha_ini->HrefValue = "";
			$this->fecha_ini->TooltipValue = "";

			// fecha_fin
			$this->fecha_fin->LinkCustomAttributes = "";
			$this->fecha_fin->HrefValue = "";
			$this->fecha_fin->TooltipValue = "";

			// id_vehiculo
			$this->id_vehiculo->LinkCustomAttributes = "";
			$this->id_vehiculo->HrefValue = "";
			$this->id_vehiculo->TooltipValue = "";

			// id_taller
			$this->id_taller->LinkCustomAttributes = "";
			$this->id_taller->HrefValue = "";
			$this->id_taller->TooltipValue = "";

			// id_tipo_mantenimiento
			$this->id_tipo_mantenimiento->LinkCustomAttributes = "";
			$this->id_tipo_mantenimiento->HrefValue = "";
			$this->id_tipo_mantenimiento->TooltipValue = "";
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
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "hoja_mantenimientoslist.php", "", $this->TableVar, TRUE);
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
if (!isset($hoja_mantenimientos_delete)) $hoja_mantenimientos_delete = new choja_mantenimientos_delete();

// Page init
$hoja_mantenimientos_delete->Page_Init();

// Page main
$hoja_mantenimientos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hoja_mantenimientos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var hoja_mantenimientos_delete = new ew_Page("hoja_mantenimientos_delete");
hoja_mantenimientos_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = hoja_mantenimientos_delete.PageID; // For backward compatibility

// Form object
var fhoja_mantenimientosdelete = new ew_Form("fhoja_mantenimientosdelete");

// Form_CustomValidate event
fhoja_mantenimientosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoja_mantenimientosdelete.ValidateRequired = true;
<?php } else { ?>
fhoja_mantenimientosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhoja_mantenimientosdelete.Lists["x_id_vehiculo"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Patente","x_modelo","x_nombre",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_mantenimientosdelete.Lists["x_id_taller"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_taller","x_tel","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_mantenimientosdelete.Lists["x_id_tipo_mantenimiento"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_mantenimiento","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($hoja_mantenimientos_delete->Recordset = $hoja_mantenimientos_delete->LoadRecordset())
	$hoja_mantenimientos_deleteTotalRecs = $hoja_mantenimientos_delete->Recordset->RecordCount(); // Get record count
if ($hoja_mantenimientos_deleteTotalRecs <= 0) { // No record found, exit
	if ($hoja_mantenimientos_delete->Recordset)
		$hoja_mantenimientos_delete->Recordset->Close();
	$hoja_mantenimientos_delete->Page_Terminate("hoja_mantenimientoslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $hoja_mantenimientos_delete->ShowPageHeader(); ?>
<?php
$hoja_mantenimientos_delete->ShowMessage();
?>
<form name="fhoja_mantenimientosdelete" id="fhoja_mantenimientosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($hoja_mantenimientos_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $hoja_mantenimientos_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="hoja_mantenimientos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($hoja_mantenimientos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $hoja_mantenimientos->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($hoja_mantenimientos->codigo->Visible) { // codigo ?>
		<th><span id="elh_hoja_mantenimientos_codigo" class="hoja_mantenimientos_codigo"><?php echo $hoja_mantenimientos->codigo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_mantenimientos->fecha_ini->Visible) { // fecha_ini ?>
		<th><span id="elh_hoja_mantenimientos_fecha_ini" class="hoja_mantenimientos_fecha_ini"><?php echo $hoja_mantenimientos->fecha_ini->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_mantenimientos->fecha_fin->Visible) { // fecha_fin ?>
		<th><span id="elh_hoja_mantenimientos_fecha_fin" class="hoja_mantenimientos_fecha_fin"><?php echo $hoja_mantenimientos->fecha_fin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_mantenimientos->id_vehiculo->Visible) { // id_vehiculo ?>
		<th><span id="elh_hoja_mantenimientos_id_vehiculo" class="hoja_mantenimientos_id_vehiculo"><?php echo $hoja_mantenimientos->id_vehiculo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_mantenimientos->id_taller->Visible) { // id_taller ?>
		<th><span id="elh_hoja_mantenimientos_id_taller" class="hoja_mantenimientos_id_taller"><?php echo $hoja_mantenimientos->id_taller->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_mantenimientos->id_tipo_mantenimiento->Visible) { // id_tipo_mantenimiento ?>
		<th><span id="elh_hoja_mantenimientos_id_tipo_mantenimiento" class="hoja_mantenimientos_id_tipo_mantenimiento"><?php echo $hoja_mantenimientos->id_tipo_mantenimiento->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$hoja_mantenimientos_delete->RecCnt = 0;
$i = 0;
while (!$hoja_mantenimientos_delete->Recordset->EOF) {
	$hoja_mantenimientos_delete->RecCnt++;
	$hoja_mantenimientos_delete->RowCnt++;

	// Set row properties
	$hoja_mantenimientos->ResetAttrs();
	$hoja_mantenimientos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$hoja_mantenimientos_delete->LoadRowValues($hoja_mantenimientos_delete->Recordset);

	// Render row
	$hoja_mantenimientos_delete->RenderRow();
?>
	<tr<?php echo $hoja_mantenimientos->RowAttributes() ?>>
<?php if ($hoja_mantenimientos->codigo->Visible) { // codigo ?>
		<td<?php echo $hoja_mantenimientos->codigo->CellAttributes() ?>>
<span id="el<?php echo $hoja_mantenimientos_delete->RowCnt ?>_hoja_mantenimientos_codigo" class="hoja_mantenimientos_codigo">
<span<?php echo $hoja_mantenimientos->codigo->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->codigo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_mantenimientos->fecha_ini->Visible) { // fecha_ini ?>
		<td<?php echo $hoja_mantenimientos->fecha_ini->CellAttributes() ?>>
<span id="el<?php echo $hoja_mantenimientos_delete->RowCnt ?>_hoja_mantenimientos_fecha_ini" class="hoja_mantenimientos_fecha_ini">
<span<?php echo $hoja_mantenimientos->fecha_ini->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->fecha_ini->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_mantenimientos->fecha_fin->Visible) { // fecha_fin ?>
		<td<?php echo $hoja_mantenimientos->fecha_fin->CellAttributes() ?>>
<span id="el<?php echo $hoja_mantenimientos_delete->RowCnt ?>_hoja_mantenimientos_fecha_fin" class="hoja_mantenimientos_fecha_fin">
<span<?php echo $hoja_mantenimientos->fecha_fin->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->fecha_fin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_mantenimientos->id_vehiculo->Visible) { // id_vehiculo ?>
		<td<?php echo $hoja_mantenimientos->id_vehiculo->CellAttributes() ?>>
<span id="el<?php echo $hoja_mantenimientos_delete->RowCnt ?>_hoja_mantenimientos_id_vehiculo" class="hoja_mantenimientos_id_vehiculo">
<span<?php echo $hoja_mantenimientos->id_vehiculo->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->id_vehiculo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_mantenimientos->id_taller->Visible) { // id_taller ?>
		<td<?php echo $hoja_mantenimientos->id_taller->CellAttributes() ?>>
<span id="el<?php echo $hoja_mantenimientos_delete->RowCnt ?>_hoja_mantenimientos_id_taller" class="hoja_mantenimientos_id_taller">
<span<?php echo $hoja_mantenimientos->id_taller->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->id_taller->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_mantenimientos->id_tipo_mantenimiento->Visible) { // id_tipo_mantenimiento ?>
		<td<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->CellAttributes() ?>>
<span id="el<?php echo $hoja_mantenimientos_delete->RowCnt ?>_hoja_mantenimientos_id_tipo_mantenimiento" class="hoja_mantenimientos_id_tipo_mantenimiento">
<span<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$hoja_mantenimientos_delete->Recordset->MoveNext();
}
$hoja_mantenimientos_delete->Recordset->Close();
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
fhoja_mantenimientosdelete.Init();
</script>
<?php
$hoja_mantenimientos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$hoja_mantenimientos_delete->Page_Terminate();
?>
