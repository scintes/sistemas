<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "cciag_ewcfg11.php" ?>
<?php include_once "cciag_ewmysql11.php" ?>
<?php include_once "cciag_phpfn11.php" ?>
<?php include_once "cciag_sociosinfo.php" ?>
<?php include_once "cciag_usuarioinfo.php" ?>
<?php include_once "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$socios_search = NULL; // Initialize page object first

class csocios_search extends csocios {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'socios';

	// Page object name
	var $PageObjName = 'socios_search';

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

		// Table object (socios)
		if (!isset($GLOBALS["socios"]) || get_class($GLOBALS["socios"]) == "csocios") {
			$GLOBALS["socios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["socios"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("cciag_socioslist.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->socio_nro->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

			// Process auto fill for detail table 'socios_detalles'
			if (@$_POST["grid"] == "fsocios_detallesgrid") {
				if (!isset($GLOBALS["socios_detalles_grid"])) $GLOBALS["socios_detalles_grid"] = new csocios_detalles_grid;
				$GLOBALS["socios_detalles_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'deudas'
			if (@$_POST["grid"] == "fdeudasgrid") {
				if (!isset($GLOBALS["deudas_grid"])) $GLOBALS["deudas_grid"] = new cdeudas_grid;
				$GLOBALS["deudas_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'socios_cuotas'
			if (@$_POST["grid"] == "fsocios_cuotasgrid") {
				if (!isset($GLOBALS["socios_cuotas_grid"])) $GLOBALS["socios_cuotas_grid"] = new csocios_cuotas_grid;
				$GLOBALS["socios_cuotas_grid"]->Page_Init();
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
		global $EW_EXPORT, $socios;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($socios);
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
						$sSrchStr = "cciag_socioslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->socio_nro); // socio_nro
		$this->BuildSearchUrl($sSrchUrl, $this->id_actividad); // id_actividad
		$this->BuildSearchUrl($sSrchUrl, $this->propietario); // propietario
		$this->BuildSearchUrl($sSrchUrl, $this->comercio); // comercio
		$this->BuildSearchUrl($sSrchUrl, $this->direccion_comercio); // direccion_comercio
		$this->BuildSearchUrl($sSrchUrl, $this->activo); // activo
		$this->BuildSearchUrl($sSrchUrl, $this->mail); // mail
		$this->BuildSearchUrl($sSrchUrl, $this->tel); // tel
		$this->BuildSearchUrl($sSrchUrl, $this->cel); // cel
		$this->BuildSearchUrl($sSrchUrl, $this->cuit_cuil); // cuit_cuil
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
		// socio_nro

		$this->socio_nro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_socio_nro"));
		$this->socio_nro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_socio_nro");

		// id_actividad
		$this->id_actividad->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_id_actividad"));
		$this->id_actividad->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id_actividad");

		// propietario
		$this->propietario->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_propietario"));
		$this->propietario->AdvancedSearch->SearchOperator = $objForm->GetValue("z_propietario");

		// comercio
		$this->comercio->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_comercio"));
		$this->comercio->AdvancedSearch->SearchOperator = $objForm->GetValue("z_comercio");

		// direccion_comercio
		$this->direccion_comercio->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_direccion_comercio"));
		$this->direccion_comercio->AdvancedSearch->SearchOperator = $objForm->GetValue("z_direccion_comercio");

		// activo
		$this->activo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_activo"));
		$this->activo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activo");

		// mail
		$this->mail->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_mail"));
		$this->mail->AdvancedSearch->SearchOperator = $objForm->GetValue("z_mail");

		// tel
		$this->tel->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_tel"));
		$this->tel->AdvancedSearch->SearchOperator = $objForm->GetValue("z_tel");

		// cel
		$this->cel->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_cel"));
		$this->cel->AdvancedSearch->SearchOperator = $objForm->GetValue("z_cel");

		// cuit_cuil
		$this->cuit_cuil->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_cuit_cuil"));
		$this->cuit_cuil->AdvancedSearch->SearchOperator = $objForm->GetValue("z_cuit_cuil");
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

			// id_actividad
			if ($this->id_actividad->VirtualValue <> "") {
				$this->id_actividad->ViewValue = $this->id_actividad->VirtualValue;
			} else {
			if (strval($this->id_actividad->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_actividad->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `actividad` AS `DispFld`, `descripcion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activa`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_actividad, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_actividad->ViewValue = $rswrk->fields('DispFld');
					$this->id_actividad->ViewValue .= ew_ValueSeparator(1,$this->id_actividad) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_actividad->ViewValue = $this->id_actividad->CurrentValue;
				}
			} else {
				$this->id_actividad->ViewValue = NULL;
			}
			}
			$this->id_actividad->ViewCustomAttributes = "";

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

			// id_actividad
			$this->id_actividad->LinkCustomAttributes = "";
			$this->id_actividad->HrefValue = "";
			$this->id_actividad->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// socio_nro
			$this->socio_nro->EditAttrs["class"] = "form-control";
			$this->socio_nro->EditCustomAttributes = "";
			$this->socio_nro->EditValue = ew_HtmlEncode($this->socio_nro->AdvancedSearch->SearchValue);
			$this->socio_nro->PlaceHolder = ew_RemoveHtml($this->socio_nro->FldCaption());

			// id_actividad
			$this->id_actividad->EditAttrs["class"] = "form-control";
			$this->id_actividad->EditCustomAttributes = "";
			$this->id_actividad->EditValue = ew_HtmlEncode($this->id_actividad->AdvancedSearch->SearchValue);
			$this->id_actividad->PlaceHolder = ew_RemoveHtml($this->id_actividad->FldCaption());

			// propietario
			$this->propietario->EditAttrs["class"] = "form-control";
			$this->propietario->EditCustomAttributes = "";
			$this->propietario->EditValue = ew_HtmlEncode($this->propietario->AdvancedSearch->SearchValue);
			$this->propietario->PlaceHolder = ew_RemoveHtml($this->propietario->FldCaption());

			// comercio
			$this->comercio->EditAttrs["class"] = "form-control";
			$this->comercio->EditCustomAttributes = "";
			$this->comercio->EditValue = ew_HtmlEncode($this->comercio->AdvancedSearch->SearchValue);
			$this->comercio->PlaceHolder = ew_RemoveHtml($this->comercio->FldCaption());

			// direccion_comercio
			$this->direccion_comercio->EditAttrs["class"] = "form-control";
			$this->direccion_comercio->EditCustomAttributes = "";
			$this->direccion_comercio->EditValue = ew_HtmlEncode($this->direccion_comercio->AdvancedSearch->SearchValue);
			$this->direccion_comercio->PlaceHolder = ew_RemoveHtml($this->direccion_comercio->FldCaption());

			// activo
			$this->activo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->activo->FldTagValue(1), $this->activo->FldTagCaption(1) <> "" ? $this->activo->FldTagCaption(1) : $this->activo->FldTagValue(1));
			$arwrk[] = array($this->activo->FldTagValue(2), $this->activo->FldTagCaption(2) <> "" ? $this->activo->FldTagCaption(2) : $this->activo->FldTagValue(2));
			$this->activo->EditValue = $arwrk;

			// mail
			$this->mail->EditAttrs["class"] = "form-control";
			$this->mail->EditCustomAttributes = "";
			$this->mail->EditValue = ew_HtmlEncode($this->mail->AdvancedSearch->SearchValue);
			$this->mail->PlaceHolder = ew_RemoveHtml($this->mail->FldCaption());

			// tel
			$this->tel->EditAttrs["class"] = "form-control";
			$this->tel->EditCustomAttributes = "";
			$this->tel->EditValue = ew_HtmlEncode($this->tel->AdvancedSearch->SearchValue);
			$this->tel->PlaceHolder = ew_RemoveHtml($this->tel->FldCaption());

			// cel
			$this->cel->EditAttrs["class"] = "form-control";
			$this->cel->EditCustomAttributes = "";
			$this->cel->EditValue = ew_HtmlEncode($this->cel->AdvancedSearch->SearchValue);
			$this->cel->PlaceHolder = ew_RemoveHtml($this->cel->FldCaption());

			// cuit_cuil
			$this->cuit_cuil->EditAttrs["class"] = "form-control";
			$this->cuit_cuil->EditCustomAttributes = "";
			$this->cuit_cuil->EditValue = ew_HtmlEncode($this->cuit_cuil->AdvancedSearch->SearchValue);
			$this->cuit_cuil->PlaceHolder = ew_RemoveHtml($this->cuit_cuil->FldCaption());
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
		if (!ew_CheckInteger($this->socio_nro->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->socio_nro->FldErrMsg());
		}
		if (!ew_CheckPhone($this->tel->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->tel->FldErrMsg());
		}
		if (!ew_CheckPhone($this->cel->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->cel->FldErrMsg());
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
		$this->socio_nro->AdvancedSearch->Load();
		$this->id_actividad->AdvancedSearch->Load();
		$this->propietario->AdvancedSearch->Load();
		$this->comercio->AdvancedSearch->Load();
		$this->direccion_comercio->AdvancedSearch->Load();
		$this->activo->AdvancedSearch->Load();
		$this->mail->AdvancedSearch->Load();
		$this->tel->AdvancedSearch->Load();
		$this->cel->AdvancedSearch->Load();
		$this->cuit_cuil->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "cciag_socioslist.php", "", $this->TableVar, TRUE);
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
if (!isset($socios_search)) $socios_search = new csocios_search();

// Page init
$socios_search->Page_Init();

// Page main
$socios_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_search->Page_Render();
?>
<?php include_once "cciag_header.php" ?>
<script type="text/javascript">

// Page object
var socios_search = new ew_Page("socios_search");
socios_search.PageID = "search"; // Page ID
var EW_PAGE_ID = socios_search.PageID; // For backward compatibility

// Form object
var fsociossearch = new ew_Form("fsociossearch");

// Form_CustomValidate event
fsociossearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsociossearch.ValidateRequired = true;
<?php } else { ?>
fsociossearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsociossearch.Lists["x_id_actividad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_actividad","x_descripcion","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
// Validate function for search

fsociossearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_socio_nro");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($socios->socio_nro->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_tel");
	if (elm && !ew_CheckPhone(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($socios->tel->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_cel");
	if (elm && !ew_CheckPhone(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($socios->cel->FldErrMsg()) ?>");

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
<?php if (!$socios_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $socios_search->ShowPageHeader(); ?>
<?php
$socios_search->ShowMessage();
?>
<form name="fsociossearch" id="fsociossearch" class="form-horizontal ewForm ewSearchForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($socios_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $socios_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="socios">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($socios_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
	<div id="r_socio_nro" class="form-group">
		<label for="x_socio_nro" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_socio_nro"><?php echo $socios->socio_nro->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_socio_nro" id="z_socio_nro" value="="></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->socio_nro->CellAttributes() ?>>
			<span id="el_socios_socio_nro">
<input type="text" data-field="x_socio_nro" name="x_socio_nro" id="x_socio_nro" size="30" placeholder="<?php echo ew_HtmlEncode($socios->socio_nro->PlaceHolder) ?>" value="<?php echo $socios->socio_nro->EditValue ?>"<?php echo $socios->socio_nro->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->id_actividad->Visible) { // id_actividad ?>
	<div id="r_id_actividad" class="form-group">
		<label for="x_id_actividad" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_id_actividad"><?php echo $socios->id_actividad->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_actividad" id="z_id_actividad" value="="></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->id_actividad->CellAttributes() ?>>
			<span id="el_socios_id_actividad">
<input type="text" data-field="x_id_actividad" name="x_id_actividad" id="x_id_actividad" size="30" placeholder="<?php echo ew_HtmlEncode($socios->id_actividad->PlaceHolder) ?>" value="<?php echo $socios->id_actividad->EditValue ?>"<?php echo $socios->id_actividad->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->propietario->Visible) { // propietario ?>
	<div id="r_propietario" class="form-group">
		<label for="x_propietario" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_propietario"><?php echo $socios->propietario->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_propietario" id="z_propietario" value="LIKE"></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->propietario->CellAttributes() ?>>
			<span id="el_socios_propietario">
<input type="text" data-field="x_propietario" name="x_propietario" id="x_propietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->propietario->PlaceHolder) ?>" value="<?php echo $socios->propietario->EditValue ?>"<?php echo $socios->propietario->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->comercio->Visible) { // comercio ?>
	<div id="r_comercio" class="form-group">
		<label for="x_comercio" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_comercio"><?php echo $socios->comercio->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_comercio" id="z_comercio" value="LIKE"></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->comercio->CellAttributes() ?>>
			<span id="el_socios_comercio">
<input type="text" data-field="x_comercio" name="x_comercio" id="x_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->comercio->PlaceHolder) ?>" value="<?php echo $socios->comercio->EditValue ?>"<?php echo $socios->comercio->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
	<div id="r_direccion_comercio" class="form-group">
		<label for="x_direccion_comercio" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_direccion_comercio"><?php echo $socios->direccion_comercio->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_direccion_comercio" id="z_direccion_comercio" value="LIKE"></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->direccion_comercio->CellAttributes() ?>>
			<span id="el_socios_direccion_comercio">
<input type="text" data-field="x_direccion_comercio" name="x_direccion_comercio" id="x_direccion_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->direccion_comercio->PlaceHolder) ?>" value="<?php echo $socios->direccion_comercio->EditValue ?>"<?php echo $socios->direccion_comercio->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->activo->Visible) { // activo ?>
	<div id="r_activo" class="form-group">
		<label class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_activo"><?php echo $socios->activo->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activo" id="z_activo" value="LIKE"></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->activo->CellAttributes() ?>>
			<span id="el_socios_activo">
<div id="tp_x_activo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_activo" id="x_activo" value="{value}"<?php echo $socios->activo->EditAttributes() ?>></div>
<div id="dsl_x_activo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->activo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->activo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activo" name="x_activo" id="x_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->mail->Visible) { // mail ?>
	<div id="r_mail" class="form-group">
		<label for="x_mail" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_mail"><?php echo $socios->mail->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mail" id="z_mail" value="LIKE"></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->mail->CellAttributes() ?>>
			<span id="el_socios_mail">
<input type="text" data-field="x_mail" name="x_mail" id="x_mail" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->mail->PlaceHolder) ?>" value="<?php echo $socios->mail->EditValue ?>"<?php echo $socios->mail->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->tel->Visible) { // tel ?>
	<div id="r_tel" class="form-group">
		<label for="x_tel" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_tel"><?php echo $socios->tel->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_tel" id="z_tel" value="LIKE"></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->tel->CellAttributes() ?>>
			<span id="el_socios_tel">
<input type="text" data-field="x_tel" name="x_tel" id="x_tel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->tel->PlaceHolder) ?>" value="<?php echo $socios->tel->EditValue ?>"<?php echo $socios->tel->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->cel->Visible) { // cel ?>
	<div id="r_cel" class="form-group">
		<label for="x_cel" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_cel"><?php echo $socios->cel->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_cel" id="z_cel" value="LIKE"></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->cel->CellAttributes() ?>>
			<span id="el_socios_cel">
<input type="text" data-field="x_cel" name="x_cel" id="x_cel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->cel->PlaceHolder) ?>" value="<?php echo $socios->cel->EditValue ?>"<?php echo $socios->cel->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
	<div id="r_cuit_cuil" class="form-group">
		<label for="x_cuit_cuil" class="<?php echo $socios_search->SearchLabelClass ?>"><span id="elh_socios_cuit_cuil"><?php echo $socios->cuit_cuil->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_cuit_cuil" id="z_cuit_cuil" value="LIKE"></p>
		</label>
		<div class="<?php echo $socios_search->SearchRightColumnClass ?>"><div<?php echo $socios->cuit_cuil->CellAttributes() ?>>
			<span id="el_socios_cuit_cuil">
<input type="text" data-field="x_cuit_cuil" name="x_cuit_cuil" id="x_cuit_cuil" size="30" maxlength="14" placeholder="<?php echo ew_HtmlEncode($socios->cuit_cuil->PlaceHolder) ?>" value="<?php echo $socios->cuit_cuil->EditValue ?>"<?php echo $socios->cuit_cuil->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$socios_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fsociossearch.Init();
</script>
<?php
$socios_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "cciag_footer.php" ?>
<?php
$socios_search->Page_Terminate();
?>
