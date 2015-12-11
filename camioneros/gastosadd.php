<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "gastosinfo.php" ?>
<?php include_once "hoja_rutasinfo.php" ?>
<?php include_once "tipo_gastosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$gastos_add = NULL; // Initialize page object first

class cgastos_add extends cgastos {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'gastos';

	// Page object name
	var $PageObjName = 'gastos_add';

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

		// Table object (gastos)
		if (!isset($GLOBALS["gastos"]) || get_class($GLOBALS["gastos"]) == "cgastos") {
			$GLOBALS["gastos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gastos"];
		}

		// Table object (hoja_rutas)
		if (!isset($GLOBALS['hoja_rutas'])) $GLOBALS['hoja_rutas'] = new choja_rutas();

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
			define("EW_TABLE_NAME", 'gastos', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("gastoslist.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("gastoslist.php"));
		}

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
		global $EW_EXPORT, $gastos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($gastos);
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
					$this->Page_Terminate("gastoslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "gastosview.php")
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
		$this->fecha->CurrentValue = date('d/m/Y');
		$this->detalles->CurrentValue = NULL;
		$this->detalles->OldValue = $this->detalles->CurrentValue;
		$this->Importe->CurrentValue = NULL;
		$this->Importe->OldValue = $this->Importe->CurrentValue;
		$this->id_tipo_gasto->CurrentValue = NULL;
		$this->id_tipo_gasto->OldValue = $this->id_tipo_gasto->CurrentValue;
		$this->id_hoja_ruta->CurrentValue = NULL;
		$this->id_hoja_ruta->OldValue = $this->id_hoja_ruta->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		if (!$this->detalles->FldIsDetailKey) {
			$this->detalles->setFormValue($objForm->GetValue("x_detalles"));
		}
		if (!$this->Importe->FldIsDetailKey) {
			$this->Importe->setFormValue($objForm->GetValue("x_Importe"));
		}
		if (!$this->id_tipo_gasto->FldIsDetailKey) {
			$this->id_tipo_gasto->setFormValue($objForm->GetValue("x_id_tipo_gasto"));
		}
		if (!$this->id_hoja_ruta->FldIsDetailKey) {
			$this->id_hoja_ruta->setFormValue($objForm->GetValue("x_id_hoja_ruta"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->detalles->CurrentValue = $this->detalles->FormValue;
		$this->Importe->CurrentValue = $this->Importe->FormValue;
		$this->id_tipo_gasto->CurrentValue = $this->id_tipo_gasto->FormValue;
		$this->id_hoja_ruta->CurrentValue = $this->id_hoja_ruta->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('add');
			if (!$res) {
				$sUserIdMsg = $Language->Phrase("NoPermission");
				$this->setFailureMessage($sUserIdMsg);
			}
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
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->detalles->setDbValue($rs->fields('detalles'));
		$this->Importe->setDbValue($rs->fields('Importe'));
		$this->id_tipo_gasto->setDbValue($rs->fields('id_tipo_gasto'));
		if (array_key_exists('EV__id_tipo_gasto', $rs->fields)) {
			$this->id_tipo_gasto->VirtualValue = $rs->fields('EV__id_tipo_gasto'); // Set up virtual field value
		} else {
			$this->id_tipo_gasto->VirtualValue = ""; // Clear value
		}
		$this->id_hoja_ruta->setDbValue($rs->fields('id_hoja_ruta'));
		if (array_key_exists('EV__id_hoja_ruta', $rs->fields)) {
			$this->id_hoja_ruta->VirtualValue = $rs->fields('EV__id_hoja_ruta'); // Set up virtual field value
		} else {
			$this->id_hoja_ruta->VirtualValue = ""; // Clear value
		}
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->fecha->DbValue = $row['fecha'];
		$this->detalles->DbValue = $row['detalles'];
		$this->Importe->DbValue = $row['Importe'];
		$this->id_tipo_gasto->DbValue = $row['id_tipo_gasto'];
		$this->id_hoja_ruta->DbValue = $row['id_hoja_ruta'];
		$this->id_usuario->DbValue = $row['id_usuario'];
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
		// Convert decimal values if posted back

		if ($this->Importe->FormValue == $this->Importe->CurrentValue && is_numeric(ew_StrToFloat($this->Importe->CurrentValue)))
			$this->Importe->CurrentValue = ew_StrToFloat($this->Importe->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// fecha
		// detalles
		// Importe
		// id_tipo_gasto
		// id_hoja_ruta
		// id_usuario

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// detalles
			$this->detalles->ViewValue = $this->detalles->CurrentValue;
			$this->detalles->ViewCustomAttributes = "";

			// Importe
			$this->Importe->ViewValue = $this->Importe->CurrentValue;
			$this->Importe->ViewValue = ew_FormatCurrency($this->Importe->ViewValue, 2, 0, 0, -1);
			$this->Importe->ViewCustomAttributes = "";

			// id_tipo_gasto
			if ($this->id_tipo_gasto->VirtualValue <> "") {
				$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->VirtualValue;
			} else {
			if (strval($this->id_tipo_gasto->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='R'";
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
					$this->id_tipo_gasto->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_gasto) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->CurrentValue;
				}
			} else {
				$this->id_tipo_gasto->ViewValue = NULL;
			}
			}
			$this->id_tipo_gasto->ViewCustomAttributes = "";

			// id_hoja_ruta
			if ($this->id_hoja_ruta->VirtualValue <> "") {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->VirtualValue;
			} else {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
			if (strval($this->id_hoja_ruta->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_hoja_ruta->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hoja_rutas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_hoja_ruta, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `codigo` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_hoja_ruta->ViewValue = $rswrk->fields('DispFld');
					$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(1,$this->id_hoja_ruta) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
					$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(2,$this->id_hoja_ruta) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
				}
			} else {
				$this->id_hoja_ruta->ViewValue = NULL;
			}
			}
			$this->id_hoja_ruta->ViewCustomAttributes = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// detalles
			$this->detalles->LinkCustomAttributes = "";
			$this->detalles->HrefValue = "";
			$this->detalles->TooltipValue = "";

			// Importe
			$this->Importe->LinkCustomAttributes = "";
			$this->Importe->HrefValue = "";
			$this->Importe->TooltipValue = "";

			// id_tipo_gasto
			$this->id_tipo_gasto->LinkCustomAttributes = "";
			$this->id_tipo_gasto->HrefValue = "";
			$this->id_tipo_gasto->TooltipValue = "";

			// id_hoja_ruta
			$this->id_hoja_ruta->LinkCustomAttributes = "";
			$this->id_hoja_ruta->HrefValue = "";
			$this->id_hoja_ruta->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// detalles
			$this->detalles->EditAttrs["class"] = "form-control";
			$this->detalles->EditCustomAttributes = "";
			$this->detalles->EditValue = ew_HtmlEncode($this->detalles->CurrentValue);
			$this->detalles->PlaceHolder = ew_RemoveHtml($this->detalles->FldCaption());

			// Importe
			$this->Importe->EditAttrs["class"] = "form-control";
			$this->Importe->EditCustomAttributes = "";
			$this->Importe->EditValue = ew_HtmlEncode($this->Importe->CurrentValue);
			$this->Importe->PlaceHolder = ew_RemoveHtml($this->Importe->FldCaption());
			if (strval($this->Importe->EditValue) <> "" && is_numeric($this->Importe->EditValue)) $this->Importe->EditValue = ew_FormatNumber($this->Importe->EditValue, -2, 0, 0, -1);

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
			$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='R'";
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
					$this->id_tipo_gasto->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_gasto) . $rswrk->fields('Disp2Fld');
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
			$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_gastos`";
			$sWhereWrk = "";
			$lookuptblfilter = "`clase`='R'";
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

			// id_hoja_ruta
			$this->id_hoja_ruta->EditAttrs["class"] = "form-control";
			$this->id_hoja_ruta->EditCustomAttributes = "";
			if ($this->id_hoja_ruta->getSessionValue() <> "") {
				$this->id_hoja_ruta->CurrentValue = $this->id_hoja_ruta->getSessionValue();
			if ($this->id_hoja_ruta->VirtualValue <> "") {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->VirtualValue;
			} else {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
			if (strval($this->id_hoja_ruta->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_hoja_ruta->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hoja_rutas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_hoja_ruta, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `codigo` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_hoja_ruta->ViewValue = $rswrk->fields('DispFld');
					$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(1,$this->id_hoja_ruta) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
					$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(2,$this->id_hoja_ruta) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
				}
			} else {
				$this->id_hoja_ruta->ViewValue = NULL;
			}
			}
			$this->id_hoja_ruta->ViewCustomAttributes = "";
			} else {
			$this->id_hoja_ruta->EditValue = ew_HtmlEncode($this->id_hoja_ruta->CurrentValue);
			$this->id_hoja_ruta->PlaceHolder = ew_RemoveHtml($this->id_hoja_ruta->FldCaption());
			}

			// Edit refer script
			// fecha

			$this->fecha->HrefValue = "";

			// detalles
			$this->detalles->HrefValue = "";

			// Importe
			$this->Importe->HrefValue = "";

			// id_tipo_gasto
			$this->id_tipo_gasto->HrefValue = "";

			// id_hoja_ruta
			$this->id_hoja_ruta->HrefValue = "";
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
		if (!ew_CheckNumber($this->Importe->FormValue)) {
			ew_AddMessage($gsFormError, $this->Importe->FldErrMsg());
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

		// Check if valid key values for master user
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sMasterFilter = $this->SqlMasterFilter_hoja_rutas();
			if (strval($this->id_hoja_ruta->CurrentValue) <> "" &&
				$this->getCurrentMasterTable() == "hoja_rutas") {
				$sMasterFilter = str_replace("@codigo@", ew_AdjustSql($this->id_hoja_ruta->CurrentValue), $sMasterFilter);
			} else {
				$sMasterFilter = "";
			}
			if ($sMasterFilter <> "") {
				$rsmaster = $GLOBALS["hoja_rutas"]->LoadRs($sMasterFilter);
				$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
				if (!$this->MasterRecordExists) {
					$sMasterUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedMasterUserID"));
					$sMasterUserIdMsg = str_replace("%f", $sMasterFilter, $sMasterUserIdMsg);
					$this->setFailureMessage($sMasterUserIdMsg);
					return FALSE;
				} else {
					$rsmaster->Close();
				}
			}
			$sMasterFilter = $this->SqlMasterFilter_tipo_gastos();
			if (strval($this->id_tipo_gasto->CurrentValue) <> "" &&
				$this->getCurrentMasterTable() == "tipo_gastos") {
				$sMasterFilter = str_replace("@codigo@", ew_AdjustSql($this->id_tipo_gasto->CurrentValue), $sMasterFilter);
			} else {
				$sMasterFilter = "";
			}
			if ($sMasterFilter <> "") {
				$rsmaster = $GLOBALS["tipo_gastos"]->LoadRs($sMasterFilter);
				$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
				if (!$this->MasterRecordExists) {
					$sMasterUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedMasterUserID"));
					$sMasterUserIdMsg = str_replace("%f", $sMasterFilter, $sMasterUserIdMsg);
					$this->setFailureMessage($sMasterUserIdMsg);
					return FALSE;
				} else {
					$rsmaster->Close();
				}
			}
		}

		// Check referential integrity for master table 'hoja_rutas'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_hoja_rutas();
		if (strval($this->id_hoja_ruta->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@codigo@", ew_AdjustSql($this->id_hoja_ruta->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["hoja_rutas"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "hoja_rutas", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Check referential integrity for master table 'tipo_gastos'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_tipo_gastos();
		if (strval($this->id_tipo_gasto->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@codigo@", ew_AdjustSql($this->id_tipo_gasto->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["tipo_gastos"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "tipo_gastos", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// fecha
		$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), NULL, FALSE);

		// detalles
		$this->detalles->SetDbValueDef($rsnew, $this->detalles->CurrentValue, NULL, FALSE);

		// Importe
		$this->Importe->SetDbValueDef($rsnew, $this->Importe->CurrentValue, NULL, FALSE);

		// id_tipo_gasto
		$this->id_tipo_gasto->SetDbValueDef($rsnew, $this->id_tipo_gasto->CurrentValue, NULL, FALSE);

		// id_hoja_ruta
		$this->id_hoja_ruta->SetDbValueDef($rsnew, $this->id_hoja_ruta->CurrentValue, NULL, FALSE);

		// id_usuario
		if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin
			$rsnew['id_usuario'] = CurrentUserID();
		}

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

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->id_usuario->CurrentValue);
		return TRUE;
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
			if ($sMasterTblVar == "hoja_rutas") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_codigo"] <> "") {
					$GLOBALS["hoja_rutas"]->codigo->setQueryStringValue($_GET["fk_codigo"]);
					$this->id_hoja_ruta->setQueryStringValue($GLOBALS["hoja_rutas"]->codigo->QueryStringValue);
					$this->id_hoja_ruta->setSessionValue($this->id_hoja_ruta->QueryStringValue);
					if (!is_numeric($GLOBALS["hoja_rutas"]->codigo->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "hoja_rutas") {
				if ($this->id_hoja_ruta->QueryStringValue == "") $this->id_hoja_ruta->setSessionValue("");
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
		$Breadcrumb->Add("list", $this->TableVar, "gastoslist.php", "", $this->TableVar, TRUE);
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
if (!isset($gastos_add)) $gastos_add = new cgastos_add();

// Page init
$gastos_add->Page_Init();

// Page main
$gastos_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gastos_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gastos_add = new ew_Page("gastos_add");
gastos_add.PageID = "add"; // Page ID
var EW_PAGE_ID = gastos_add.PageID; // For backward compatibility

// Form object
var fgastosadd = new ew_Form("fgastosadd");

// Validate form
fgastosadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Importe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos->Importe->FldErrMsg()) ?>");

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
fgastosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgastosadd.ValidateRequired = true;
<?php } else { ?>
fgastosadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgastosadd.Lists["x_id_tipo_gasto"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_tipo_gasto","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgastosadd.Lists["x_id_hoja_ruta"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_fecha_ini","x_Origen",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $gastos_add->ShowPageHeader(); ?>
<?php
$gastos_add->ShowMessage();
?>
<form name="fgastosadd" id="fgastosadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($gastos_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $gastos_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="gastos">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($gastos->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_gastos_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $gastos->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos->fecha->CellAttributes() ?>>
<span id="el_gastos_fecha">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($gastos->fecha->PlaceHolder) ?>" value="<?php echo $gastos->fecha->EditValue ?>"<?php echo $gastos->fecha->EditAttributes() ?>>
<?php if (!$gastos->fecha->ReadOnly && !$gastos->fecha->Disabled && !isset($gastos->fecha->EditAttrs["readonly"]) && !isset($gastos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastosadd", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $gastos->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gastos->detalles->Visible) { // detalles ?>
	<div id="r_detalles" class="form-group">
		<label id="elh_gastos_detalles" for="x_detalles" class="col-sm-2 control-label ewLabel"><?php echo $gastos->detalles->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos->detalles->CellAttributes() ?>>
<span id="el_gastos_detalles">
<input type="text" data-field="x_detalles" name="x_detalles" id="x_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos->detalles->PlaceHolder) ?>" value="<?php echo $gastos->detalles->EditValue ?>"<?php echo $gastos->detalles->EditAttributes() ?>>
</span>
<?php echo $gastos->detalles->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gastos->Importe->Visible) { // Importe ?>
	<div id="r_Importe" class="form-group">
		<label id="elh_gastos_Importe" for="x_Importe" class="col-sm-2 control-label ewLabel"><?php echo $gastos->Importe->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos->Importe->CellAttributes() ?>>
<span id="el_gastos_Importe">
<input type="text" data-field="x_Importe" name="x_Importe" id="x_Importe" size="20" placeholder="<?php echo ew_HtmlEncode($gastos->Importe->PlaceHolder) ?>" value="<?php echo $gastos->Importe->EditValue ?>"<?php echo $gastos->Importe->EditAttributes() ?>>
</span>
<?php echo $gastos->Importe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gastos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
	<div id="r_id_tipo_gasto" class="form-group">
		<label id="elh_gastos_id_tipo_gasto" for="x_id_tipo_gasto" class="col-sm-2 control-label ewLabel"><?php echo $gastos->id_tipo_gasto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos->id_tipo_gasto->CellAttributes() ?>>
<?php if ($gastos->id_tipo_gasto->getSessionValue() <> "") { ?>
<span id="el_gastos_id_tipo_gasto">
<span<?php echo $gastos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_id_tipo_gasto" name="x_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gastos_id_tipo_gasto">
<select data-field="x_id_tipo_gasto" id="x_id_tipo_gasto" name="x_id_tipo_gasto"<?php echo $gastos->id_tipo_gasto->EditAttributes() ?>>
<?php
if (is_array($gastos->id_tipo_gasto->EditValue)) {
	$arwrk = $gastos->id_tipo_gasto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gastos->id_tipo_gasto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$gastos->id_tipo_gasto) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipo_gastos")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $gastos->id_tipo_gasto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_tipo_gasto',url:'tipo_gastosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_tipo_gasto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $gastos->id_tipo_gasto->FldCaption() ?></span></button>
<?php } ?>
<?php
$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
$sWhereWrk = "";
$lookuptblfilter = "`clase`='R'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$gastos->Lookup_Selecting($gastos->id_tipo_gasto, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
?>
<input type="hidden" name="s_x_id_tipo_gasto" id="s_x_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $gastos->id_tipo_gasto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gastos->id_hoja_ruta->Visible) { // id_hoja_ruta ?>
	<div id="r_id_hoja_ruta" class="form-group">
		<label id="elh_gastos_id_hoja_ruta" class="col-sm-2 control-label ewLabel"><?php echo $gastos->id_hoja_ruta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $gastos->id_hoja_ruta->CellAttributes() ?>>
<?php if ($gastos->id_hoja_ruta->getSessionValue() <> "") { ?>
<span id="el_gastos_id_hoja_ruta">
<span<?php echo $gastos->id_hoja_ruta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_hoja_ruta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_id_hoja_ruta" name="x_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gastos_id_hoja_ruta">
<?php
	$wrkonchange = trim(" " . @$gastos->id_hoja_ruta->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gastos->id_hoja_ruta->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_hoja_ruta" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_id_hoja_ruta" id="sv_x_id_hoja_ruta" value="<?php echo $gastos->id_hoja_ruta->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->PlaceHolder) ?>"<?php echo $gastos->id_hoja_ruta->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_hoja_ruta" name="x_id_hoja_ruta" id="x_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld` FROM `hoja_rutas`";
$sWhereWrk = "`codigo` LIKE '{query_value}%' OR CONCAT(`codigo`,'" . ew_ValueSeparator(1, $Page->id_hoja_ruta) . "',DATE_FORMAT(`fecha_ini`, '%d/%m/%Y'),'" . ew_ValueSeparator(2, $Page->id_hoja_ruta) . "',`Origen`) LIKE '{query_value}%'";
if (!$GLOBALS["gastos"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["hoja_rutas"]->AddUserIDFilter($sWhereWrk);

// Call Lookup selecting
$gastos->Lookup_Selecting($gastos->id_hoja_ruta, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `codigo` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_id_hoja_ruta" id="q_x_id_hoja_ruta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fgastosadd.CreateAutoSuggest("x_id_hoja_ruta", false);
</script>
</span>
<?php } ?>
<?php echo $gastos->id_hoja_ruta->CustomMsg ?></div></div>
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
fgastosadd.Init();
</script>
<?php
$gastos_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gastos_add->Page_Terminate();
?>
