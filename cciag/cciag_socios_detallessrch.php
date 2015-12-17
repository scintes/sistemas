<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "cciag_ewcfg11.php" ?>
<?php include_once "cciag_ewmysql11.php" ?>
<?php include_once "cciag_phpfn11.php" ?>
<?php include_once "cciag_socios_detallesinfo.php" ?>
<?php include_once "cciag_sociosinfo.php" ?>
<?php include_once "cciag_usuarioinfo.php" ?>
<?php include_once "cciag_detallesinfo.php" ?>
<?php include_once "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$socios_detalles_search = NULL; // Initialize page object first

class csocios_detalles_search extends csocios_detalles {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'socios_detalles';

	// Page object name
	var $PageObjName = 'socios_detalles_search';

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

		// Table object (socios_detalles)
		if (!isset($GLOBALS["socios_detalles"]) || get_class($GLOBALS["socios_detalles"]) == "csocios_detalles") {
			$GLOBALS["socios_detalles"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["socios_detalles"];
		}

		// Table object (socios)
		if (!isset($GLOBALS['socios'])) $GLOBALS['socios'] = new csocios();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Table object (detalles)
		if (!isset($GLOBALS['detalles'])) $GLOBALS['detalles'] = new cdetalles();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'socios_detalles', TRUE);

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
		global $EW_EXPORT, $socios_detalles;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($socios_detalles);
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
						$sSrchStr = "cciag_socios_detalleslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->id_socio); // id_socio
		$this->BuildSearchUrl($sSrchUrl, $this->id_detalles); // id_detalles
		$this->BuildSearchUrl($sSrchUrl, $this->fecha_alta); // fecha_alta
		$this->BuildSearchUrl($sSrchUrl, $this->fecha_baja); // fecha_baja
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
		// id_socio

		$this->id_socio->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_id_socio"));
		$this->id_socio->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id_socio");

		// id_detalles
		$this->id_detalles->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_id_detalles"));
		$this->id_detalles->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id_detalles");

		// fecha_alta
		$this->fecha_alta->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fecha_alta"));
		$this->fecha_alta->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fecha_alta");

		// fecha_baja
		$this->fecha_baja->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fecha_baja"));
		$this->fecha_baja->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fecha_baja");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_socio
		// id_detalles
		// fecha_alta
		// fecha_baja

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// id_detalles
			if (strval($this->id_detalles->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_detalles->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `detalles`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activa`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_detalles, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_detalles->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_detalles->ViewValue = $this->id_detalles->CurrentValue;
				}
			} else {
				$this->id_detalles->ViewValue = NULL;
			}
			$this->id_detalles->ViewCustomAttributes = "";

			// fecha_alta
			$this->fecha_alta->ViewValue = $this->fecha_alta->CurrentValue;
			$this->fecha_alta->ViewValue = ew_FormatDateTime($this->fecha_alta->ViewValue, 7);
			$this->fecha_alta->ViewCustomAttributes = "";

			// fecha_baja
			$this->fecha_baja->ViewValue = $this->fecha_baja->CurrentValue;
			$this->fecha_baja->ViewValue = ew_FormatDateTime($this->fecha_baja->ViewValue, 7);
			$this->fecha_baja->ViewCustomAttributes = "";

			// id_socio
			$this->id_socio->LinkCustomAttributes = "";
			$this->id_socio->HrefValue = "";
			$this->id_socio->TooltipValue = "";

			// id_detalles
			$this->id_detalles->LinkCustomAttributes = "";
			$this->id_detalles->HrefValue = "";
			$this->id_detalles->TooltipValue = "";

			// fecha_alta
			$this->fecha_alta->LinkCustomAttributes = "";
			$this->fecha_alta->HrefValue = "";
			$this->fecha_alta->TooltipValue = "";

			// fecha_baja
			$this->fecha_baja->LinkCustomAttributes = "";
			$this->fecha_baja->HrefValue = "";
			$this->fecha_baja->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

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
			if (!$GLOBALS["socios_detalles"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_socio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `propietario` DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_socio->EditValue = $arwrk;

			// id_detalles
			$this->id_detalles->EditAttrs["class"] = "form-control";
			$this->id_detalles->EditCustomAttributes = "";
			if (trim(strval($this->id_detalles->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_detalles->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codigo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `detalles`";
			$sWhereWrk = "";
			$lookuptblfilter = "`activa`='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_detalles, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_detalles->EditValue = $arwrk;

			// fecha_alta
			$this->fecha_alta->EditAttrs["class"] = "form-control";
			$this->fecha_alta->EditCustomAttributes = "";
			$this->fecha_alta->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_alta->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_alta->PlaceHolder = ew_RemoveHtml($this->fecha_alta->FldCaption());

			// fecha_baja
			$this->fecha_baja->EditAttrs["class"] = "form-control";
			$this->fecha_baja->EditCustomAttributes = "";
			$this->fecha_baja->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_baja->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_baja->PlaceHolder = ew_RemoveHtml($this->fecha_baja->FldCaption());
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
		if (!ew_CheckEuroDate($this->fecha_alta->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha_alta->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_baja->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha_baja->FldErrMsg());
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
		$this->id_socio->AdvancedSearch->Load();
		$this->id_detalles->AdvancedSearch->Load();
		$this->fecha_alta->AdvancedSearch->Load();
		$this->fecha_baja->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "cciag_socios_detalleslist.php", "", $this->TableVar, TRUE);
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
if (!isset($socios_detalles_search)) $socios_detalles_search = new csocios_detalles_search();

// Page init
$socios_detalles_search->Page_Init();

// Page main
$socios_detalles_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_detalles_search->Page_Render();
?>
<?php include_once "cciag_header.php" ?>
<script type="text/javascript">

// Page object
var socios_detalles_search = new ew_Page("socios_detalles_search");
socios_detalles_search.PageID = "search"; // Page ID
var EW_PAGE_ID = socios_detalles_search.PageID; // For backward compatibility

// Form object
var fsocios_detallessearch = new ew_Form("fsocios_detallessearch");

// Form_CustomValidate event
fsocios_detallessearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsocios_detallessearch.ValidateRequired = true;
<?php } else { ?>
fsocios_detallessearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsocios_detallessearch.Lists["x_id_socio"] = {"LinkField":"x_socio_nro","Ajax":true,"AutoFill":false,"DisplayFields":["x_propietario","x_comercio","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsocios_detallessearch.Lists["x_id_detalles"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
// Validate function for search

fsocios_detallessearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_fecha_alta");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($socios_detalles->fecha_alta->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fecha_baja");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($socios_detalles->fecha_baja->FldErrMsg()) ?>");

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
<?php if (!$socios_detalles_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $socios_detalles_search->ShowPageHeader(); ?>
<?php
$socios_detalles_search->ShowMessage();
?>
<form name="fsocios_detallessearch" id="fsocios_detallessearch" class="form-horizontal ewForm ewSearchForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($socios_detalles_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $socios_detalles_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="socios_detalles">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($socios_detalles_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($socios_detalles->id_socio->Visible) { // id_socio ?>
	<div id="r_id_socio" class="form-group">
		<label for="x_id_socio" class="<?php echo $socios_detalles_search->SearchLabelClass ?>"><span id="elh_socios_detalles_id_socio"><?php echo $socios_detalles->id_socio->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_socio" id="z_id_socio" value="="></p>
		</label>
		<div class="<?php echo $socios_detalles_search->SearchRightColumnClass ?>"><div<?php echo $socios_detalles->id_socio->CellAttributes() ?>>
			<span id="el_socios_detalles_id_socio">
<select data-field="x_id_socio" id="x_id_socio" name="x_id_socio"<?php echo $socios_detalles->id_socio->EditAttributes() ?>>
<?php
if (is_array($socios_detalles->id_socio->EditValue)) {
	$arwrk = $socios_detalles->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_detalles->id_socio->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios_detalles->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
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
if (!$GLOBALS["socios_detalles"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

// Call Lookup selecting
$socios_detalles->Lookup_Selecting($socios_detalles->id_socio, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `propietario` DESC";
?>
<input type="hidden" name="s_x_id_socio" id="s_x_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios_detalles->id_detalles->Visible) { // id_detalles ?>
	<div id="r_id_detalles" class="form-group">
		<label for="x_id_detalles" class="<?php echo $socios_detalles_search->SearchLabelClass ?>"><span id="elh_socios_detalles_id_detalles"><?php echo $socios_detalles->id_detalles->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_detalles" id="z_id_detalles" value="="></p>
		</label>
		<div class="<?php echo $socios_detalles_search->SearchRightColumnClass ?>"><div<?php echo $socios_detalles->id_detalles->CellAttributes() ?>>
			<span id="el_socios_detalles_id_detalles">
<select data-field="x_id_detalles" id="x_id_detalles" name="x_id_detalles"<?php echo $socios_detalles->id_detalles->EditAttributes() ?>>
<?php
if (is_array($socios_detalles->id_detalles->EditValue)) {
	$arwrk = $socios_detalles->id_detalles->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_detalles->id_detalles->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php
$sSqlWrk = "SELECT `codigo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `detalles`";
$sWhereWrk = "";
$lookuptblfilter = "`activa`='S'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$socios_detalles->Lookup_Selecting($socios_detalles->id_detalles, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `nombre` DESC";
?>
<input type="hidden" name="s_x_id_detalles" id="s_x_id_detalles" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios_detalles->fecha_alta->Visible) { // fecha_alta ?>
	<div id="r_fecha_alta" class="form-group">
		<label for="x_fecha_alta" class="<?php echo $socios_detalles_search->SearchLabelClass ?>"><span id="elh_socios_detalles_fecha_alta"><?php echo $socios_detalles->fecha_alta->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fecha_alta" id="z_fecha_alta" value="="></p>
		</label>
		<div class="<?php echo $socios_detalles_search->SearchRightColumnClass ?>"><div<?php echo $socios_detalles->fecha_alta->CellAttributes() ?>>
			<span id="el_socios_detalles_fecha_alta">
<input type="text" data-field="x_fecha_alta" name="x_fecha_alta" id="x_fecha_alta" placeholder="<?php echo ew_HtmlEncode($socios_detalles->fecha_alta->PlaceHolder) ?>" value="<?php echo $socios_detalles->fecha_alta->EditValue ?>"<?php echo $socios_detalles->fecha_alta->EditAttributes() ?>>
<?php if (!$socios_detalles->fecha_alta->ReadOnly && !$socios_detalles->fecha_alta->Disabled && !isset($socios_detalles->fecha_alta->EditAttrs["readonly"]) && !isset($socios_detalles->fecha_alta->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_detallessearch", "x_fecha_alta", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($socios_detalles->fecha_baja->Visible) { // fecha_baja ?>
	<div id="r_fecha_baja" class="form-group">
		<label for="x_fecha_baja" class="<?php echo $socios_detalles_search->SearchLabelClass ?>"><span id="elh_socios_detalles_fecha_baja"><?php echo $socios_detalles->fecha_baja->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fecha_baja" id="z_fecha_baja" value="="></p>
		</label>
		<div class="<?php echo $socios_detalles_search->SearchRightColumnClass ?>"><div<?php echo $socios_detalles->fecha_baja->CellAttributes() ?>>
			<span id="el_socios_detalles_fecha_baja">
<input type="text" data-field="x_fecha_baja" name="x_fecha_baja" id="x_fecha_baja" placeholder="<?php echo ew_HtmlEncode($socios_detalles->fecha_baja->PlaceHolder) ?>" value="<?php echo $socios_detalles->fecha_baja->EditValue ?>"<?php echo $socios_detalles->fecha_baja->EditAttributes() ?>>
<?php if (!$socios_detalles->fecha_baja->ReadOnly && !$socios_detalles->fecha_baja->Disabled && !isset($socios_detalles->fecha_baja->EditAttrs["readonly"]) && !isset($socios_detalles->fecha_baja->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_detallessearch", "x_fecha_baja", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$socios_detalles_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fsocios_detallessearch.Init();
</script>
<?php
$socios_detalles_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "cciag_footer.php" ?>
<?php
$socios_detalles_search->Page_Terminate();
?>
