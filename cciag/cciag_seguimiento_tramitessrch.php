<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_seguimiento_tramitesinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_tramitesinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$seguimiento_tramites_search = NULL; // Initialize page object first

class cseguimiento_tramites_search extends cseguimiento_tramites {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'seguimiento_tramites';

	// Page object name
	var $PageObjName = 'seguimiento_tramites_search';

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

		// Table object (seguimiento_tramites)
		if (!isset($GLOBALS["seguimiento_tramites"]) || get_class($GLOBALS["seguimiento_tramites"]) == "cseguimiento_tramites") {
			$GLOBALS["seguimiento_tramites"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["seguimiento_tramites"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Table object (tramites)
		if (!isset($GLOBALS['tramites'])) $GLOBALS['tramites'] = new ctramites();

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'seguimiento_tramites', TRUE);

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
		global $EW_EXPORT, $seguimiento_tramites;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($seguimiento_tramites);
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
						$sSrchStr = "cciag_seguimiento_tramiteslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->id_tramite); // id_tramite
		$this->BuildSearchUrl($sSrchUrl, $this->fecha); // fecha
		$this->BuildSearchUrl($sSrchUrl, $this->hora); // hora
		$this->BuildSearchUrl($sSrchUrl, $this->titulo); // titulo
		$this->BuildSearchUrl($sSrchUrl, $this->descripcion); // descripcion
		$this->BuildSearchUrl($sSrchUrl, $this->archivo); // archivo
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
		// id_tramite

		$this->id_tramite->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_id_tramite"));
		$this->id_tramite->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id_tramite");

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fecha"));
		$this->fecha->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fecha");

		// hora
		$this->hora->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_hora"));
		$this->hora->AdvancedSearch->SearchOperator = $objForm->GetValue("z_hora");

		// titulo
		$this->titulo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_titulo"));
		$this->titulo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_titulo");

		// descripcion
		$this->descripcion->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_descripcion"));
		$this->descripcion->AdvancedSearch->SearchOperator = $objForm->GetValue("z_descripcion");

		// archivo
		$this->archivo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_archivo"));
		$this->archivo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_archivo");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_tramite
		// fecha
		// hora
		// titulo
		// descripcion
		// id_usuario
		// archivo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_tramite
			if ($this->id_tramite->VirtualValue <> "") {
				$this->id_tramite->ViewValue = $this->id_tramite->VirtualValue;
			} else {
			if (strval($this->id_tramite->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tramite->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha` AS `Disp2Fld`, `Titulo` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tramites`";
			$sWhereWrk = "";
			$lookuptblfilter = "`estado`<>'F'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tramite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `fecha` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tramite->ViewValue = $rswrk->fields('DispFld');
					$this->id_tramite->ViewValue .= ew_ValueSeparator(1,$this->id_tramite) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
					$this->id_tramite->ViewValue .= ew_ValueSeparator(2,$this->id_tramite) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_tramite->ViewValue = $this->id_tramite->CurrentValue;
				}
			} else {
				$this->id_tramite->ViewValue = NULL;
			}
			}
			$this->id_tramite->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// hora
			$this->hora->ViewValue = $this->hora->CurrentValue;
			$this->hora->ViewCustomAttributes = "";

			// titulo
			$this->titulo->ViewValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
			$this->descripcion->ViewCustomAttributes = "";

			// archivo
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->ViewValue = $this->archivo->Upload->DbValue;
			} else {
				$this->archivo->ViewValue = "";
			}
			$this->archivo->ViewCustomAttributes = "";

			// id_tramite
			$this->id_tramite->LinkCustomAttributes = "";
			$this->id_tramite->HrefValue = "";
			$this->id_tramite->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// hora
			$this->hora->LinkCustomAttributes = "";
			$this->hora->HrefValue = "";
			$this->hora->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// archivo
			$this->archivo->LinkCustomAttributes = "";
			$this->archivo->HrefValue = "";
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;
			$this->archivo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_tramite
			$this->id_tramite->EditAttrs["class"] = "form-control";
			$this->id_tramite->EditCustomAttributes = "";
			$this->id_tramite->EditValue = ew_HtmlEncode($this->id_tramite->AdvancedSearch->SearchValue);
			$this->id_tramite->PlaceHolder = ew_RemoveHtml($this->id_tramite->FldCaption());

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// hora
			$this->hora->EditAttrs["class"] = "form-control";
			$this->hora->EditCustomAttributes = "";
			$this->hora->EditValue = ew_HtmlEncode($this->hora->AdvancedSearch->SearchValue);
			$this->hora->PlaceHolder = ew_RemoveHtml($this->hora->FldCaption());

			// titulo
			$this->titulo->EditAttrs["class"] = "form-control";
			$this->titulo->EditCustomAttributes = "";
			$this->titulo->EditValue = ew_HtmlEncode($this->titulo->AdvancedSearch->SearchValue);
			$this->titulo->PlaceHolder = ew_RemoveHtml($this->titulo->FldCaption());

			// descripcion
			$this->descripcion->EditAttrs["class"] = "form-control";
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->AdvancedSearch->SearchValue);
			$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

			// archivo
			$this->archivo->EditAttrs["class"] = "form-control";
			$this->archivo->EditCustomAttributes = "";
			$this->archivo->EditValue = ew_HtmlEncode($this->archivo->AdvancedSearch->SearchValue);
			$this->archivo->PlaceHolder = ew_RemoveHtml($this->archivo->FldCaption());
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
		if (!ew_CheckEuroDate($this->fecha->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckTime($this->hora->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->hora->FldErrMsg());
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
		$this->id_tramite->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->hora->AdvancedSearch->Load();
		$this->titulo->AdvancedSearch->Load();
		$this->descripcion->AdvancedSearch->Load();
		$this->archivo->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "cciag_seguimiento_tramiteslist.php", "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, ew_CurrentUrl());
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
if (!isset($seguimiento_tramites_search)) $seguimiento_tramites_search = new cseguimiento_tramites_search();

// Page init
$seguimiento_tramites_search->Page_Init();

// Page main
$seguimiento_tramites_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$seguimiento_tramites_search->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_header.php" ?>
<script type="text/javascript">

// Page object
var seguimiento_tramites_search = new ew_Page("seguimiento_tramites_search");
seguimiento_tramites_search.PageID = "search"; // Page ID
var EW_PAGE_ID = seguimiento_tramites_search.PageID; // For backward compatibility

// Form object
var fseguimiento_tramitessearch = new ew_Form("fseguimiento_tramitessearch");

// Form_CustomValidate event
fseguimiento_tramitessearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fseguimiento_tramitessearch.ValidateRequired = true;
<?php } else { ?>
fseguimiento_tramitessearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fseguimiento_tramitessearch.Lists["x_id_tramite"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_fecha","x_Titulo",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
// Validate function for search

fseguimiento_tramitessearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_fecha");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($seguimiento_tramites->fecha->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_hora");
	if (elm && !ew_CheckTime(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($seguimiento_tramites->hora->FldErrMsg()) ?>");

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
<?php if (!$seguimiento_tramites_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $seguimiento_tramites_search->ShowPageHeader(); ?>
<?php
$seguimiento_tramites_search->ShowMessage();
?>
<form name="fseguimiento_tramitessearch" id="fseguimiento_tramitessearch" class="form-horizontal ewForm ewSearchForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($seguimiento_tramites_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $seguimiento_tramites_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="seguimiento_tramites">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($seguimiento_tramites_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($seguimiento_tramites->id_tramite->Visible) { // id_tramite ?>
	<div id="r_id_tramite" class="form-group">
		<label for="x_id_tramite" class="<?php echo $seguimiento_tramites_search->SearchLabelClass ?>"><span id="elh_seguimiento_tramites_id_tramite"><?php echo $seguimiento_tramites->id_tramite->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_tramite" id="z_id_tramite" value="="></p>
		</label>
		<div class="<?php echo $seguimiento_tramites_search->SearchRightColumnClass ?>"><div<?php echo $seguimiento_tramites->id_tramite->CellAttributes() ?>>
			<span id="el_seguimiento_tramites_id_tramite">
<input type="text" data-field="x_id_tramite" name="x_id_tramite" id="x_id_tramite" size="30" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->id_tramite->EditValue ?>"<?php echo $seguimiento_tramites->id_tramite->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($seguimiento_tramites->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label for="x_fecha" class="<?php echo $seguimiento_tramites_search->SearchLabelClass ?>"><span id="elh_seguimiento_tramites_fecha"><?php echo $seguimiento_tramites->fecha->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fecha" id="z_fecha" value="="></p>
		</label>
		<div class="<?php echo $seguimiento_tramites_search->SearchRightColumnClass ?>"><div<?php echo $seguimiento_tramites->fecha->CellAttributes() ?>>
			<span id="el_seguimiento_tramites_fecha">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->fecha->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->fecha->EditValue ?>"<?php echo $seguimiento_tramites->fecha->EditAttributes() ?>>
<?php if (!$seguimiento_tramites->fecha->ReadOnly && !$seguimiento_tramites->fecha->Disabled && @$seguimiento_tramites->fecha->EditAttrs["readonly"] == "" && @$seguimiento_tramites->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fseguimiento_tramitessearch", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($seguimiento_tramites->hora->Visible) { // hora ?>
	<div id="r_hora" class="form-group">
		<label for="x_hora" class="<?php echo $seguimiento_tramites_search->SearchLabelClass ?>"><span id="elh_seguimiento_tramites_hora"><?php echo $seguimiento_tramites->hora->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_hora" id="z_hora" value="="></p>
		</label>
		<div class="<?php echo $seguimiento_tramites_search->SearchRightColumnClass ?>"><div<?php echo $seguimiento_tramites->hora->CellAttributes() ?>>
			<span id="el_seguimiento_tramites_hora">
<input type="text" data-field="x_hora" name="x_hora" id="x_hora" size="30" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->hora->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->hora->EditValue ?>"<?php echo $seguimiento_tramites->hora->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($seguimiento_tramites->titulo->Visible) { // titulo ?>
	<div id="r_titulo" class="form-group">
		<label for="x_titulo" class="<?php echo $seguimiento_tramites_search->SearchLabelClass ?>"><span id="elh_seguimiento_tramites_titulo"><?php echo $seguimiento_tramites->titulo->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_titulo" id="z_titulo" value="LIKE"></p>
		</label>
		<div class="<?php echo $seguimiento_tramites_search->SearchRightColumnClass ?>"><div<?php echo $seguimiento_tramites->titulo->CellAttributes() ?>>
			<span id="el_seguimiento_tramites_titulo">
<input type="text" data-field="x_titulo" name="x_titulo" id="x_titulo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->titulo->EditValue ?>"<?php echo $seguimiento_tramites->titulo->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($seguimiento_tramites->descripcion->Visible) { // descripcion ?>
	<div id="r_descripcion" class="form-group">
		<label for="x_descripcion" class="<?php echo $seguimiento_tramites_search->SearchLabelClass ?>"><span id="elh_seguimiento_tramites_descripcion"><?php echo $seguimiento_tramites->descripcion->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_descripcion" id="z_descripcion" value="LIKE"></p>
		</label>
		<div class="<?php echo $seguimiento_tramites_search->SearchRightColumnClass ?>"><div<?php echo $seguimiento_tramites->descripcion->CellAttributes() ?>>
			<span id="el_seguimiento_tramites_descripcion">
<input type="text" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="35" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->descripcion->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->descripcion->EditValue ?>"<?php echo $seguimiento_tramites->descripcion->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($seguimiento_tramites->archivo->Visible) { // archivo ?>
	<div id="r_archivo" class="form-group">
		<label class="<?php echo $seguimiento_tramites_search->SearchLabelClass ?>"><span id="elh_seguimiento_tramites_archivo"><?php echo $seguimiento_tramites->archivo->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_archivo" id="z_archivo" value="LIKE"></p>
		</label>
		<div class="<?php echo $seguimiento_tramites_search->SearchRightColumnClass ?>"><div<?php echo $seguimiento_tramites->archivo->CellAttributes() ?>>
			<span id="el_seguimiento_tramites_archivo">
<input type="text" data-field="x_archivo" name="x_archivo" id="x_archivo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->archivo->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->archivo->EditValue ?>"<?php echo $seguimiento_tramites->archivo->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$seguimiento_tramites_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fseguimiento_tramitessearch.Init();
</script>
<?php
$seguimiento_tramites_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "cciag_footer.php" ?>
<?php
$seguimiento_tramites_search->Page_Terminate();
?>
