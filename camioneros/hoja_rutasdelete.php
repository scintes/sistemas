<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "hoja_rutasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$hoja_rutas_delete = NULL; // Initialize page object first

class choja_rutas_delete extends choja_rutas {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'hoja_rutas';

	// Page object name
	var $PageObjName = 'hoja_rutas_delete';

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

		// Table object (hoja_rutas)
		if (!isset($GLOBALS["hoja_rutas"]) || get_class($GLOBALS["hoja_rutas"]) == "choja_rutas") {
			$GLOBALS["hoja_rutas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["hoja_rutas"];
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
			define("EW_TABLE_NAME", 'hoja_rutas', TRUE);

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

			// Process auto fill for detail table 'gastos'
			if (@$_POST["grid"] == "fgastosgrid") {
				if (!isset($GLOBALS["gastos_grid"])) $GLOBALS["gastos_grid"] = new cgastos_grid;
				$GLOBALS["gastos_grid"]->Page_Init();
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
		global $EW_EXPORT, $hoja_rutas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($hoja_rutas);
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
			$this->Page_Terminate("hoja_rutaslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in hoja_rutas class, hoja_rutasinfo.php

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
		$this->fecha_ini->setDbValue($rs->fields('fecha_ini'));
		$this->id_cliente->setDbValue($rs->fields('id_cliente'));
		$this->id_localidad_origen->setDbValue($rs->fields('id_localidad_origen'));
		if (array_key_exists('EV__id_localidad_origen', $rs->fields)) {
			$this->id_localidad_origen->VirtualValue = $rs->fields('EV__id_localidad_origen'); // Set up virtual field value
		} else {
			$this->id_localidad_origen->VirtualValue = ""; // Clear value
		}
		$this->Origen->setDbValue($rs->fields('Origen'));
		$this->id_localidad_destino->setDbValue($rs->fields('id_localidad_destino'));
		if (array_key_exists('EV__id_localidad_destino', $rs->fields)) {
			$this->id_localidad_destino->VirtualValue = $rs->fields('EV__id_localidad_destino'); // Set up virtual field value
		} else {
			$this->id_localidad_destino->VirtualValue = ""; // Clear value
		}
		$this->Destino->setDbValue($rs->fields('Destino'));
		$this->Km_ini->setDbValue($rs->fields('Km_ini'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->id_vehiculo->setDbValue($rs->fields('id_vehiculo'));
		if (array_key_exists('EV__id_vehiculo', $rs->fields)) {
			$this->id_vehiculo->VirtualValue = $rs->fields('EV__id_vehiculo'); // Set up virtual field value
		} else {
			$this->id_vehiculo->VirtualValue = ""; // Clear value
		}
		$this->id_tipo_carga->setDbValue($rs->fields('id_tipo_carga'));
		if (array_key_exists('EV__id_tipo_carga', $rs->fields)) {
			$this->id_tipo_carga->VirtualValue = $rs->fields('EV__id_tipo_carga'); // Set up virtual field value
		} else {
			$this->id_tipo_carga->VirtualValue = ""; // Clear value
		}
		$this->km_fin->setDbValue($rs->fields('km_fin'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->adelanto->setDbValue($rs->fields('adelanto'));
		$this->kg_carga->setDbValue($rs->fields('kg_carga'));
		$this->tarifa->setDbValue($rs->fields('tarifa'));
		$this->porcentaje->setDbValue($rs->fields('porcentaje'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->fecha_ini->DbValue = $row['fecha_ini'];
		$this->id_cliente->DbValue = $row['id_cliente'];
		$this->id_localidad_origen->DbValue = $row['id_localidad_origen'];
		$this->Origen->DbValue = $row['Origen'];
		$this->id_localidad_destino->DbValue = $row['id_localidad_destino'];
		$this->Destino->DbValue = $row['Destino'];
		$this->Km_ini->DbValue = $row['Km_ini'];
		$this->estado->DbValue = $row['estado'];
		$this->id_vehiculo->DbValue = $row['id_vehiculo'];
		$this->id_tipo_carga->DbValue = $row['id_tipo_carga'];
		$this->km_fin->DbValue = $row['km_fin'];
		$this->fecha_fin->DbValue = $row['fecha_fin'];
		$this->adelanto->DbValue = $row['adelanto'];
		$this->kg_carga->DbValue = $row['kg_carga'];
		$this->tarifa->DbValue = $row['tarifa'];
		$this->porcentaje->DbValue = $row['porcentaje'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->adelanto->FormValue == $this->adelanto->CurrentValue && is_numeric(ew_StrToFloat($this->adelanto->CurrentValue)))
			$this->adelanto->CurrentValue = ew_StrToFloat($this->adelanto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tarifa->FormValue == $this->tarifa->CurrentValue && is_numeric(ew_StrToFloat($this->tarifa->CurrentValue)))
			$this->tarifa->CurrentValue = ew_StrToFloat($this->tarifa->CurrentValue);

		// Convert decimal values if posted back
		if ($this->porcentaje->FormValue == $this->porcentaje->CurrentValue && is_numeric(ew_StrToFloat($this->porcentaje->CurrentValue)))
			$this->porcentaje->CurrentValue = ew_StrToFloat($this->porcentaje->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// fecha_ini
		// id_cliente
		// id_localidad_origen
		// Origen
		// id_localidad_destino
		// Destino
		// Km_ini
		// estado
		// id_vehiculo
		// id_tipo_carga
		// km_fin
		// fecha_fin
		// adelanto
		// kg_carga
		// tarifa
		// porcentaje

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// fecha_ini
			$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
			$this->fecha_ini->ViewValue = ew_FormatDateTime($this->fecha_ini->ViewValue, 7);
			$this->fecha_ini->ViewCustomAttributes = "";

			// id_cliente
			if (strval($this->id_cliente->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `cuit_cuil` AS `DispFld`, `razon_social` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `clientes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_cliente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `razon_social` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_cliente->ViewValue = $rswrk->fields('DispFld');
					$this->id_cliente->ViewValue .= ew_ValueSeparator(1,$this->id_cliente) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_cliente->ViewValue = $this->id_cliente->CurrentValue;
				}
			} else {
				$this->id_cliente->ViewValue = NULL;
			}
			$this->id_cliente->ViewCustomAttributes = "";

			// Origen
			$this->Origen->ViewValue = $this->Origen->CurrentValue;
			$this->Origen->ViewCustomAttributes = "";

			// Destino
			$this->Destino->ViewValue = $this->Destino->CurrentValue;
			$this->Destino->ViewCustomAttributes = "";

			// estado
			$this->estado->ViewValue = $this->estado->CurrentValue;
			$this->estado->ViewCustomAttributes = "";

			// id_vehiculo
			if ($this->id_vehiculo->VirtualValue <> "") {
				$this->id_vehiculo->ViewValue = $this->id_vehiculo->VirtualValue;
			} else {
			if (strval($this->id_vehiculo->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_vehiculo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_vehiculo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_vehiculo->ViewValue = $rswrk->fields('DispFld');
					$this->id_vehiculo->ViewValue .= ew_ValueSeparator(1,$this->id_vehiculo) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_vehiculo->ViewValue = $this->id_vehiculo->CurrentValue;
				}
			} else {
				$this->id_vehiculo->ViewValue = NULL;
			}
			}
			$this->id_vehiculo->ViewCustomAttributes = "";

			// id_tipo_carga
			if ($this->id_tipo_carga->VirtualValue <> "") {
				$this->id_tipo_carga->ViewValue = $this->id_tipo_carga->VirtualValue;
			} else {
			if (strval($this->id_tipo_carga->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_carga->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `Tipo_carga` AS `DispFld`, `precio_base` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_cargas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_carga, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Tipo_carga` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tipo_carga->ViewValue = $rswrk->fields('DispFld');
					$this->id_tipo_carga->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_carga) . ew_FormatCurrency($rswrk->fields('Disp2Fld'), 2, 0, 0, -1);
					$rswrk->Close();
				} else {
					$this->id_tipo_carga->ViewValue = $this->id_tipo_carga->CurrentValue;
				}
			} else {
				$this->id_tipo_carga->ViewValue = NULL;
			}
			}
			$this->id_tipo_carga->ViewCustomAttributes = "";

			// adelanto
			$this->adelanto->ViewValue = $this->adelanto->CurrentValue;
			$this->adelanto->ViewValue = ew_FormatCurrency($this->adelanto->ViewValue, 2, -2, -2, -2);
			$this->adelanto->ViewCustomAttributes = "";

			// kg_carga
			$this->kg_carga->ViewValue = $this->kg_carga->CurrentValue;
			$this->kg_carga->ViewValue = ew_FormatNumber($this->kg_carga->ViewValue, 0, -2, -2, -2);
			$this->kg_carga->ViewCustomAttributes = "";

			// tarifa
			$this->tarifa->ViewValue = $this->tarifa->CurrentValue;
			$this->tarifa->ViewValue = ew_FormatCurrency($this->tarifa->ViewValue, 2, -2, -2, -2);
			$this->tarifa->ViewCustomAttributes = "";

			// porcentaje
			$this->porcentaje->ViewValue = $this->porcentaje->CurrentValue;
			$this->porcentaje->ViewCustomAttributes = "";

			// fecha_ini
			$this->fecha_ini->LinkCustomAttributes = "";
			$this->fecha_ini->HrefValue = "";
			$this->fecha_ini->TooltipValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			$this->id_cliente->HrefValue = "";
			$this->id_cliente->TooltipValue = "";

			// Origen
			$this->Origen->LinkCustomAttributes = "";
			$this->Origen->HrefValue = "";
			$this->Origen->TooltipValue = "";

			// Destino
			$this->Destino->LinkCustomAttributes = "";
			$this->Destino->HrefValue = "";
			$this->Destino->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// id_vehiculo
			$this->id_vehiculo->LinkCustomAttributes = "";
			$this->id_vehiculo->HrefValue = "";
			$this->id_vehiculo->TooltipValue = "";

			// id_tipo_carga
			$this->id_tipo_carga->LinkCustomAttributes = "";
			$this->id_tipo_carga->HrefValue = "";
			$this->id_tipo_carga->TooltipValue = "";

			// adelanto
			$this->adelanto->LinkCustomAttributes = "";
			$this->adelanto->HrefValue = "";
			$this->adelanto->TooltipValue = "";

			// kg_carga
			$this->kg_carga->LinkCustomAttributes = "";
			$this->kg_carga->HrefValue = "";
			$this->kg_carga->TooltipValue = "";

			// tarifa
			$this->tarifa->LinkCustomAttributes = "";
			$this->tarifa->HrefValue = "";
			$this->tarifa->TooltipValue = "";

			// porcentaje
			$this->porcentaje->LinkCustomAttributes = "";
			$this->porcentaje->HrefValue = "";
			$this->porcentaje->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, "hoja_rutaslist.php", "", $this->TableVar, TRUE);
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
if (!isset($hoja_rutas_delete)) $hoja_rutas_delete = new choja_rutas_delete();

// Page init
$hoja_rutas_delete->Page_Init();

// Page main
$hoja_rutas_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hoja_rutas_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var hoja_rutas_delete = new ew_Page("hoja_rutas_delete");
hoja_rutas_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = hoja_rutas_delete.PageID; // For backward compatibility

// Form object
var fhoja_rutasdelete = new ew_Form("fhoja_rutasdelete");

// Form_CustomValidate event
fhoja_rutasdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhoja_rutasdelete.ValidateRequired = true;
<?php } else { ?>
fhoja_rutasdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhoja_rutasdelete.Lists["x_id_cliente"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_cuit_cuil","x_razon_social","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutasdelete.Lists["x_id_vehiculo"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Patente","x_nombre","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fhoja_rutasdelete.Lists["x_id_tipo_carga"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Tipo_carga","x_precio_base","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($hoja_rutas_delete->Recordset = $hoja_rutas_delete->LoadRecordset())
	$hoja_rutas_deleteTotalRecs = $hoja_rutas_delete->Recordset->RecordCount(); // Get record count
if ($hoja_rutas_deleteTotalRecs <= 0) { // No record found, exit
	if ($hoja_rutas_delete->Recordset)
		$hoja_rutas_delete->Recordset->Close();
	$hoja_rutas_delete->Page_Terminate("hoja_rutaslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $hoja_rutas_delete->ShowPageHeader(); ?>
<?php
$hoja_rutas_delete->ShowMessage();
?>
<form name="fhoja_rutasdelete" id="fhoja_rutasdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($hoja_rutas_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $hoja_rutas_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="hoja_rutas">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($hoja_rutas_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $hoja_rutas->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($hoja_rutas->fecha_ini->Visible) { // fecha_ini ?>
		<th><span id="elh_hoja_rutas_fecha_ini" class="hoja_rutas_fecha_ini"><?php echo $hoja_rutas->fecha_ini->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_rutas->id_cliente->Visible) { // id_cliente ?>
		<th><span id="elh_hoja_rutas_id_cliente" class="hoja_rutas_id_cliente"><?php echo $hoja_rutas->id_cliente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_rutas->Origen->Visible) { // Origen ?>
		<th><span id="elh_hoja_rutas_Origen" class="hoja_rutas_Origen"><?php echo $hoja_rutas->Origen->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_rutas->Destino->Visible) { // Destino ?>
		<th><span id="elh_hoja_rutas_Destino" class="hoja_rutas_Destino"><?php echo $hoja_rutas->Destino->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_rutas->estado->Visible) { // estado ?>
		<th><span id="elh_hoja_rutas_estado" class="hoja_rutas_estado"><?php echo $hoja_rutas->estado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_rutas->id_vehiculo->Visible) { // id_vehiculo ?>
		<th><span id="elh_hoja_rutas_id_vehiculo" class="hoja_rutas_id_vehiculo"><?php echo $hoja_rutas->id_vehiculo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_rutas->id_tipo_carga->Visible) { // id_tipo_carga ?>
		<th><span id="elh_hoja_rutas_id_tipo_carga" class="hoja_rutas_id_tipo_carga"><?php echo $hoja_rutas->id_tipo_carga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_rutas->adelanto->Visible) { // adelanto ?>
		<th><span id="elh_hoja_rutas_adelanto" class="hoja_rutas_adelanto"><?php echo $hoja_rutas->adelanto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_rutas->kg_carga->Visible) { // kg_carga ?>
		<th><span id="elh_hoja_rutas_kg_carga" class="hoja_rutas_kg_carga"><?php echo $hoja_rutas->kg_carga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_rutas->tarifa->Visible) { // tarifa ?>
		<th><span id="elh_hoja_rutas_tarifa" class="hoja_rutas_tarifa"><?php echo $hoja_rutas->tarifa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($hoja_rutas->porcentaje->Visible) { // porcentaje ?>
		<th><span id="elh_hoja_rutas_porcentaje" class="hoja_rutas_porcentaje"><?php echo $hoja_rutas->porcentaje->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$hoja_rutas_delete->RecCnt = 0;
$i = 0;
while (!$hoja_rutas_delete->Recordset->EOF) {
	$hoja_rutas_delete->RecCnt++;
	$hoja_rutas_delete->RowCnt++;

	// Set row properties
	$hoja_rutas->ResetAttrs();
	$hoja_rutas->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$hoja_rutas_delete->LoadRowValues($hoja_rutas_delete->Recordset);

	// Render row
	$hoja_rutas_delete->RenderRow();
?>
	<tr<?php echo $hoja_rutas->RowAttributes() ?>>
<?php if ($hoja_rutas->fecha_ini->Visible) { // fecha_ini ?>
		<td<?php echo $hoja_rutas->fecha_ini->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_fecha_ini" class="hoja_rutas_fecha_ini">
<span<?php echo $hoja_rutas->fecha_ini->ViewAttributes() ?>>
<?php echo $hoja_rutas->fecha_ini->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_rutas->id_cliente->Visible) { // id_cliente ?>
		<td<?php echo $hoja_rutas->id_cliente->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_id_cliente" class="hoja_rutas_id_cliente">
<span<?php echo $hoja_rutas->id_cliente->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_cliente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_rutas->Origen->Visible) { // Origen ?>
		<td<?php echo $hoja_rutas->Origen->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_Origen" class="hoja_rutas_Origen">
<span<?php echo $hoja_rutas->Origen->ViewAttributes() ?>>
<?php echo $hoja_rutas->Origen->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_rutas->Destino->Visible) { // Destino ?>
		<td<?php echo $hoja_rutas->Destino->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_Destino" class="hoja_rutas_Destino">
<span<?php echo $hoja_rutas->Destino->ViewAttributes() ?>>
<?php echo $hoja_rutas->Destino->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_rutas->estado->Visible) { // estado ?>
		<td<?php echo $hoja_rutas->estado->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_estado" class="hoja_rutas_estado">
<span<?php echo $hoja_rutas->estado->ViewAttributes() ?>>
<?php echo $hoja_rutas->estado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_rutas->id_vehiculo->Visible) { // id_vehiculo ?>
		<td<?php echo $hoja_rutas->id_vehiculo->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_id_vehiculo" class="hoja_rutas_id_vehiculo">
<span<?php echo $hoja_rutas->id_vehiculo->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_vehiculo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_rutas->id_tipo_carga->Visible) { // id_tipo_carga ?>
		<td<?php echo $hoja_rutas->id_tipo_carga->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_id_tipo_carga" class="hoja_rutas_id_tipo_carga">
<span<?php echo $hoja_rutas->id_tipo_carga->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_tipo_carga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_rutas->adelanto->Visible) { // adelanto ?>
		<td<?php echo $hoja_rutas->adelanto->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_adelanto" class="hoja_rutas_adelanto">
<span<?php echo $hoja_rutas->adelanto->ViewAttributes() ?>>
<?php echo $hoja_rutas->adelanto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_rutas->kg_carga->Visible) { // kg_carga ?>
		<td<?php echo $hoja_rutas->kg_carga->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_kg_carga" class="hoja_rutas_kg_carga">
<span<?php echo $hoja_rutas->kg_carga->ViewAttributes() ?>>
<?php echo $hoja_rutas->kg_carga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_rutas->tarifa->Visible) { // tarifa ?>
		<td<?php echo $hoja_rutas->tarifa->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_tarifa" class="hoja_rutas_tarifa">
<span<?php echo $hoja_rutas->tarifa->ViewAttributes() ?>>
<?php echo $hoja_rutas->tarifa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($hoja_rutas->porcentaje->Visible) { // porcentaje ?>
		<td<?php echo $hoja_rutas->porcentaje->CellAttributes() ?>>
<span id="el<?php echo $hoja_rutas_delete->RowCnt ?>_hoja_rutas_porcentaje" class="hoja_rutas_porcentaje">
<span<?php echo $hoja_rutas->porcentaje->ViewAttributes() ?>>
<?php echo $hoja_rutas->porcentaje->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$hoja_rutas_delete->Recordset->MoveNext();
}
$hoja_rutas_delete->Recordset->Close();
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
fhoja_rutasdelete.Init();
</script>
<?php
$hoja_rutas_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$hoja_rutas_delete->Page_Terminate();
?>
