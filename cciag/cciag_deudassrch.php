<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "cciag_ewcfg11.php" ?>
<?php include_once "cciag_ewmysql11.php" ?>
<?php include_once "cciag_phpfn11.php" ?>
<?php include_once "cciag_deudasinfo.php" ?>
<?php include_once "cciag_sociosinfo.php" ?>
<?php include_once "cciag_usuarioinfo.php" ?>
<?php include_once "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$deudas_search = NULL; // Initialize page object first

class cdeudas_search extends cdeudas {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'deudas';

	// Page object name
	var $PageObjName = 'deudas_search';

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

		// Table object (deudas)
		if (!isset($GLOBALS["deudas"]) || get_class($GLOBALS["deudas"]) == "cdeudas") {
			$GLOBALS["deudas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["deudas"];
		}

		// Table object (socios)
		if (!isset($GLOBALS['socios'])) $GLOBALS['socios'] = new csocios();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'deudas', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("cciag_login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("cciag_deudaslist.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

			// Process auto fill for detail table 'detalle_deudas'
			if (@$_POST["grid"] == "fdetalle_deudasgrid") {
				if (!isset($GLOBALS["detalle_deudas_grid"])) $GLOBALS["detalle_deudas_grid"] = new cdetalle_deudas_grid;
				$GLOBALS["detalle_deudas_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'pagos'
			if (@$_POST["grid"] == "fpagosgrid") {
				if (!isset($GLOBALS["pagos_grid"])) $GLOBALS["pagos_grid"] = new cpagos_grid;
				$GLOBALS["pagos_grid"]->Page_Init();
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
		global $EW_EXPORT, $deudas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($deudas);
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
	var $IsModal = FALSE;
	var $SearchLabelClass = "col-sm-3 control-label ewLabel";
	var $SearchRightColumnClass = "col-sm-9";

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "cciag_deudaslist.php" . "?" . $sSrchStr;
						if ($this->IsModal) {
							$row = array();
							$row["url"] = $sSrchStr;
							echo ew_ArrayToJson(array($row));
							$this->Page_Terminate();
							exit();
						} else {
							$this->Page_Terminate($sSrchStr); // Go to list page
						}
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->id); // id
		$this->BuildSearchUrl($sSrchUrl, $this->mes); // mes
		$this->BuildSearchUrl($sSrchUrl, $this->anio); // anio
		$this->BuildSearchUrl($sSrchUrl, $this->fecha); // fecha
		$this->BuildSearchUrl($sSrchUrl, $this->monto); // monto
		$this->BuildSearchUrl($sSrchUrl, $this->id_socio); // id_socio
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_id"));
		$this->id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id");

		// mes
		$this->mes->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_mes"));
		$this->mes->AdvancedSearch->SearchOperator = $objForm->GetValue("z_mes");

		// anio
		$this->anio->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_anio"));
		$this->anio->AdvancedSearch->SearchOperator = $objForm->GetValue("z_anio");

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fecha"));
		$this->fecha->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fecha");

		// monto
		$this->monto->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_monto"));
		$this->monto->AdvancedSearch->SearchOperator = $objForm->GetValue("z_monto");

		// id_socio
		$this->id_socio->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_id_socio"));
		$this->id_socio->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id_socio");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->monto->FormValue == $this->monto->CurrentValue && is_numeric(ew_StrToFloat($this->monto->CurrentValue)))
			$this->monto->CurrentValue = ew_StrToFloat($this->monto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// mes
		// anio
		// fecha
		// monto
		// id_usuario
		// id_socio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewCustomAttributes = "";

			// mes
			$this->mes->ViewValue = $this->mes->CurrentValue;
			$this->mes->ViewCustomAttributes = "";

			// anio
			$this->anio->ViewValue = $this->anio->CurrentValue;
			$this->anio->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// monto
			$this->monto->ViewValue = $this->monto->CurrentValue;
			$this->monto->ViewValue = ew_FormatCurrency($this->monto->ViewValue, 0, -2, -2, -2);
			$this->monto->ViewCustomAttributes = "";

			// id_socio
			if (strval($this->id_socio->CurrentValue) <> "") {
				$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->id_socio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_socio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `propietario` DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_socio->ViewValue = $rswrk->fields('DispFld');
					$this->id_socio->ViewValue .= ew_ValueSeparator(1,$this->id_socio) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_socio->ViewValue = $this->id_socio->CurrentValue;
				}
			} else {
				$this->id_socio->ViewValue = NULL;
			}
			$this->id_socio->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// mes
			$this->mes->LinkCustomAttributes = "";
			$this->mes->HrefValue = "";
			$this->mes->TooltipValue = "";

			// anio
			$this->anio->LinkCustomAttributes = "";
			$this->anio->HrefValue = "";
			$this->anio->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// monto
			$this->monto->LinkCustomAttributes = "";
			$this->monto->HrefValue = "";
			$this->monto->TooltipValue = "";

			// id_socio
			$this->id_socio->LinkCustomAttributes = "";
			$this->id_socio->HrefValue = "";
			$this->id_socio->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";

			// mes
			$this->mes->EditAttrs["class"] = "form-control";
			$this->mes->EditCustomAttributes = "";
			$this->mes->EditValue = ew_HtmlEncode($this->mes->AdvancedSearch->SearchValue);
			$this->mes->PlaceHolder = ew_RemoveHtml($this->mes->FldCaption());

			// anio
			$this->anio->EditAttrs["class"] = "form-control";
			$this->anio->EditCustomAttributes = "";
			$this->anio->EditValue = ew_HtmlEncode($this->anio->AdvancedSearch->SearchValue);
			$this->anio->PlaceHolder = ew_RemoveHtml($this->anio->FldCaption());

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// monto
			$this->monto->EditAttrs["class"] = "form-control";
			$this->monto->EditCustomAttributes = "";
			$this->monto->EditValue = ew_HtmlEncode($this->monto->AdvancedSearch->SearchValue);
			$this->monto->PlaceHolder = ew_RemoveHtml($this->monto->FldCaption());

			// id_socio
			$this->id_socio->EditAttrs["class"] = "form-control";
			$this->id_socio->EditCustomAttributes = "";
			if (trim(strval($this->id_socio->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->id_socio->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `socios`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activo`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["deudas"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_socio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `propietario` DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_socio->EditValue = $arwrk;
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckRange($this->mes->AdvancedSearch->SearchValue, 1, 12)) {
			ew_AddMessage($gsSearchError, $this->mes->FldErrMsg());
		}
		if (!ew_CheckRange($this->anio->AdvancedSearch->SearchValue, 2012, 2100)) {
			ew_AddMessage($gsSearchError, $this->anio->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckNumber($this->monto->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->monto->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id->AdvancedSearch->Load();
		$this->mes->AdvancedSearch->Load();
		$this->anio->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->monto->AdvancedSearch->Load();
		$this->id_socio->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "cciag_deudaslist.php", "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($deudas_search)) $deudas_search = new cdeudas_search();

// Page init
$deudas_search->Page_Init();

// Page main
$deudas_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deudas_search->Page_Render();
?>
<?php include_once "cciag_header.php" ?>
<script type="text/javascript">

// Page object
var deudas_search = new ew_Page("deudas_search");
deudas_search.PageID = "search"; // Page ID
var EW_PAGE_ID = deudas_search.PageID; // For backward compatibility

// Form object
var fdeudassearch = new ew_Form("fdeudassearch");

// Form_CustomValidate event
fdeudassearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdeudassearch.ValidateRequired = true;
<?php } else { ?>
fdeudassearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdeudassearch.Lists["x_id_socio"] = {"LinkField":"x_socio_nro","Ajax":true,"AutoFill":false,"DisplayFields":["x_propietario","x_comercio","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
// Validate function for search

fdeudassearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_mes");
	if (elm && !ew_CheckRange(elm.value, 1, 12))
		return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mes->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_anio");
	if (elm && !ew_CheckRange(elm.value, 2012, 2100))
		return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->anio->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fecha");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->fecha->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_monto");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->monto->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$deudas_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $deudas_search->ShowPageHeader(); ?>
<?php
$deudas_search->ShowMessage();
?>
<form name="fdeudassearch" id="fdeudassearch" class="form-horizontal ewForm ewSearchForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($deudas_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $deudas_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="deudas">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($deudas_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($deudas->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label for="x_id" class="<?php echo $deudas_search->SearchLabelClass ?>"><span id="elh_deudas_id"><?php echo $deudas->id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></p>
		</label>
		<div class="<?php echo $deudas_search->SearchRightColumnClass ?>"><div<?php echo $deudas->id->CellAttributes() ?>>
			<span id="el_deudas_id">
<select data-field="x_id" id="x_id" name="x_id"<?php echo $deudas->id->EditAttributes() ?>>
<?php
if (is_array($deudas->id->EditValue)) {
	$arwrk = $deudas->id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($deudas->id->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($deudas->mes->Visible) { // mes ?>
	<div id="r_mes" class="form-group">
		<label for="x_mes" class="<?php echo $deudas_search->SearchLabelClass ?>"><span id="elh_deudas_mes"><?php echo $deudas->mes->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mes" id="z_mes" value="LIKE"></p>
		</label>
		<div class="<?php echo $deudas_search->SearchRightColumnClass ?>"><div<?php echo $deudas->mes->CellAttributes() ?>>
			<span id="el_deudas_mes">
<input type="text" data-field="x_mes" name="x_mes" id="x_mes" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mes->PlaceHolder) ?>" value="<?php echo $deudas->mes->EditValue ?>"<?php echo $deudas->mes->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($deudas->anio->Visible) { // anio ?>
	<div id="r_anio" class="form-group">
		<label for="x_anio" class="<?php echo $deudas_search->SearchLabelClass ?>"><span id="elh_deudas_anio"><?php echo $deudas->anio->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_anio" id="z_anio" value="LIKE"></p>
		</label>
		<div class="<?php echo $deudas_search->SearchRightColumnClass ?>"><div<?php echo $deudas->anio->CellAttributes() ?>>
			<span id="el_deudas_anio">
<input type="text" data-field="x_anio" name="x_anio" id="x_anio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->anio->PlaceHolder) ?>" value="<?php echo $deudas->anio->EditValue ?>"<?php echo $deudas->anio->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($deudas->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label for="x_fecha" class="<?php echo $deudas_search->SearchLabelClass ?>"><span id="elh_deudas_fecha"><?php echo $deudas->fecha->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fecha" id="z_fecha" value="="></p>
		</label>
		<div class="<?php echo $deudas_search->SearchRightColumnClass ?>"><div<?php echo $deudas->fecha->CellAttributes() ?>>
			<span id="el_deudas_fecha">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($deudas->fecha->PlaceHolder) ?>" value="<?php echo $deudas->fecha->EditValue ?>"<?php echo $deudas->fecha->EditAttributes() ?>>
<?php if (!$deudas->fecha->ReadOnly && !$deudas->fecha->Disabled && !isset($deudas->fecha->EditAttrs["readonly"]) && !isset($deudas->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fdeudassearch", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($deudas->monto->Visible) { // monto ?>
	<div id="r_monto" class="form-group">
		<label for="x_monto" class="<?php echo $deudas_search->SearchLabelClass ?>"><span id="elh_deudas_monto"><?php echo $deudas->monto->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_monto" id="z_monto" value="="></p>
		</label>
		<div class="<?php echo $deudas_search->SearchRightColumnClass ?>"><div<?php echo $deudas->monto->CellAttributes() ?>>
			<span id="el_deudas_monto">
<input type="text" data-field="x_monto" name="x_monto" id="x_monto" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->monto->PlaceHolder) ?>" value="<?php echo $deudas->monto->EditValue ?>"<?php echo $deudas->monto->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($deudas->id_socio->Visible) { // id_socio ?>
	<div id="r_id_socio" class="form-group">
		<label for="x_id_socio" class="<?php echo $deudas_search->SearchLabelClass ?>"><span id="elh_deudas_id_socio"><?php echo $deudas->id_socio->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_socio" id="z_id_socio" value="="></p>
		</label>
		<div class="<?php echo $deudas_search->SearchRightColumnClass ?>"><div<?php echo $deudas->id_socio->CellAttributes() ?>>
			<span id="el_deudas_id_socio">
<select data-field="x_id_socio" id="x_id_socio" name="x_id_socio"<?php echo $deudas->id_socio->EditAttributes() ?>>
<?php
if (is_array($deudas->id_socio->EditValue)) {
	$arwrk = $deudas->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($deudas->id_socio->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$deudas->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
$sWhereWrk = "";
$lookuptblfilter = "`activo`='S'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}
if (!$GLOBALS["deudas"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

// Call Lookup selecting
$deudas->Lookup_Selecting($deudas->id_socio, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `propietario` DESC";
?>
<input type="hidden" name="s_x_id_socio" id="s_x_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$deudas_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fdeudassearch.Init();
</script>
<?php
$deudas_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "cciag_footer.php" ?>
<?php
$deudas_search->Page_Terminate();
?>
