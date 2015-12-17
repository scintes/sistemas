<?php

// Global variable for table object
$socios = NULL;

//
// Table class for socios
//
class csocios extends cTable {
	var $socio_nro;
	var $id_actividad;
	var $id_usuario;
	var $propietario;
	var $comercio;
	var $direccion_comercio;
	var $activo;
	var $mail;
	var $tel;
	var $cel;
	var $cuit_cuil;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'socios';
		$this->TableName = 'socios';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 104; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// socio_nro
		$this->socio_nro = new cField('socios', 'socios', 'x_socio_nro', 'socio_nro', '`socio_nro`', '`socio_nro`', 3, -1, FALSE, '`socio_nro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->socio_nro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['socio_nro'] = &$this->socio_nro;

		// id_actividad
		$this->id_actividad = new cField('socios', 'socios', 'x_id_actividad', 'id_actividad', '`id_actividad`', '`id_actividad`', 3, -1, FALSE, '`id_actividad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_actividad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_actividad'] = &$this->id_actividad;

		// id_usuario
		$this->id_usuario = new cField('socios', 'socios', 'x_id_usuario', 'id_usuario', '`id_usuario`', '`id_usuario`', 3, -1, FALSE, '`id_usuario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_usuario'] = &$this->id_usuario;

		// propietario
		$this->propietario = new cField('socios', 'socios', 'x_propietario', 'propietario', '`propietario`', '`propietario`', 200, -1, FALSE, '`propietario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['propietario'] = &$this->propietario;

		// comercio
		$this->comercio = new cField('socios', 'socios', 'x_comercio', 'comercio', '`comercio`', '`comercio`', 200, -1, FALSE, '`comercio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['comercio'] = &$this->comercio;

		// direccion_comercio
		$this->direccion_comercio = new cField('socios', 'socios', 'x_direccion_comercio', 'direccion_comercio', '`direccion_comercio`', '`direccion_comercio`', 200, -1, FALSE, '`direccion_comercio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['direccion_comercio'] = &$this->direccion_comercio;

		// activo
		$this->activo = new cField('socios', 'socios', 'x_activo', 'activo', '`activo`', '`activo`', 200, -1, FALSE, '`activo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['activo'] = &$this->activo;

		// mail
		$this->mail = new cField('socios', 'socios', 'x_mail', 'mail', '`mail`', '`mail`', 200, -1, FALSE, '`mail`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->mail->FldDefaultErrMsg = $Language->Phrase("IncorrectEmail");
		$this->fields['mail'] = &$this->mail;

		// tel
		$this->tel = new cField('socios', 'socios', 'x_tel', 'tel', '`tel`', '`tel`', 200, -1, FALSE, '`tel`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tel->FldDefaultErrMsg = $Language->Phrase("IncorrectPhone");
		$this->fields['tel'] = &$this->tel;

		// cel
		$this->cel = new cField('socios', 'socios', 'x_cel', 'cel', '`cel`', '`cel`', 200, -1, FALSE, '`cel`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cel->FldDefaultErrMsg = $Language->Phrase("IncorrectPhone");
		$this->fields['cel'] = &$this->cel;

		// cuit_cuil
		$this->cuit_cuil = new cField('socios', 'socios', 'x_cuit_cuil', 'cuit_cuil', '`cuit_cuil`', '`cuit_cuil`', 200, -1, FALSE, '`cuit_cuil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cuit_cuil'] = &$this->cuit_cuil;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`socios`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return TRUE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		global $Security;

		// Add User ID filter
		if (!$this->AllowAnonymousUser() && $Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`socios`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('socio_nro', $rs))
				ew_AddFilter($where, ew_QuotedName('socio_nro') . '=' . ew_QuotedValue($rs['socio_nro'], $this->socio_nro->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`socio_nro` = @socio_nro@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->socio_nro->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@socio_nro@", ew_AdjustSql($this->socio_nro->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "cciaglogin.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "cciagsocioslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "cciagsocioslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("cciagsociosview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("cciagsociosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "cciagsociosadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("cciagsociosedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("cciagsociosadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("cciagsociosdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->socio_nro->CurrentValue)) {
			$sUrl .= "socio_nro=" . urlencode($this->socio_nro->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["socio_nro"]; // socio_nro

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->socio_nro->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->socio_nro->setDbValue($rs->fields('socio_nro'));
		$this->id_actividad->setDbValue($rs->fields('id_actividad'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		$this->propietario->setDbValue($rs->fields('propietario'));
		$this->comercio->setDbValue($rs->fields('comercio'));
		$this->direccion_comercio->setDbValue($rs->fields('direccion_comercio'));
		$this->activo->setDbValue($rs->fields('activo'));
		$this->mail->setDbValue($rs->fields('mail'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->cuit_cuil->setDbValue($rs->fields('cuit_cuil'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// socio_nro
		// id_actividad

		$this->id_actividad->CellCssStyle = "white-space: nowrap;";

		// id_usuario
		// propietario
		// comercio
		// direccion_comercio
		// activo
		// mail
		// tel
		// cel
		// cuit_cuil
		// socio_nro

		$this->socio_nro->ViewValue = $this->socio_nro->CurrentValue;
		$this->socio_nro->ViewCustomAttributes = "";

		// id_actividad
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
		$this->id_actividad->ViewCustomAttributes = "";

		// id_usuario
		$this->id_usuario->ViewValue = $this->id_usuario->CurrentValue;
		$this->id_usuario->ViewCustomAttributes = "";

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

		// id_usuario
		$this->id_usuario->LinkCustomAttributes = "";
		$this->id_usuario->HrefValue = "";
		$this->id_usuario->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->socio_nro->Exportable) $Doc->ExportCaption($this->socio_nro);
				if ($this->id_usuario->Exportable) $Doc->ExportCaption($this->id_usuario);
				if ($this->propietario->Exportable) $Doc->ExportCaption($this->propietario);
				if ($this->comercio->Exportable) $Doc->ExportCaption($this->comercio);
				if ($this->direccion_comercio->Exportable) $Doc->ExportCaption($this->direccion_comercio);
				if ($this->activo->Exportable) $Doc->ExportCaption($this->activo);
				if ($this->mail->Exportable) $Doc->ExportCaption($this->mail);
				if ($this->tel->Exportable) $Doc->ExportCaption($this->tel);
				if ($this->cel->Exportable) $Doc->ExportCaption($this->cel);
				if ($this->cuit_cuil->Exportable) $Doc->ExportCaption($this->cuit_cuil);
			} else {
				if ($this->socio_nro->Exportable) $Doc->ExportCaption($this->socio_nro);
				if ($this->propietario->Exportable) $Doc->ExportCaption($this->propietario);
				if ($this->comercio->Exportable) $Doc->ExportCaption($this->comercio);
				if ($this->direccion_comercio->Exportable) $Doc->ExportCaption($this->direccion_comercio);
				if ($this->activo->Exportable) $Doc->ExportCaption($this->activo);
				if ($this->mail->Exportable) $Doc->ExportCaption($this->mail);
				if ($this->tel->Exportable) $Doc->ExportCaption($this->tel);
				if ($this->cel->Exportable) $Doc->ExportCaption($this->cel);
				if ($this->cuit_cuil->Exportable) $Doc->ExportCaption($this->cuit_cuil);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->socio_nro->Exportable) $Doc->ExportField($this->socio_nro);
					if ($this->id_usuario->Exportable) $Doc->ExportField($this->id_usuario);
					if ($this->propietario->Exportable) $Doc->ExportField($this->propietario);
					if ($this->comercio->Exportable) $Doc->ExportField($this->comercio);
					if ($this->direccion_comercio->Exportable) $Doc->ExportField($this->direccion_comercio);
					if ($this->activo->Exportable) $Doc->ExportField($this->activo);
					if ($this->mail->Exportable) $Doc->ExportField($this->mail);
					if ($this->tel->Exportable) $Doc->ExportField($this->tel);
					if ($this->cel->Exportable) $Doc->ExportField($this->cel);
					if ($this->cuit_cuil->Exportable) $Doc->ExportField($this->cuit_cuil);
				} else {
					if ($this->socio_nro->Exportable) $Doc->ExportField($this->socio_nro);
					if ($this->propietario->Exportable) $Doc->ExportField($this->propietario);
					if ($this->comercio->Exportable) $Doc->ExportField($this->comercio);
					if ($this->direccion_comercio->Exportable) $Doc->ExportField($this->direccion_comercio);
					if ($this->activo->Exportable) $Doc->ExportField($this->activo);
					if ($this->mail->Exportable) $Doc->ExportField($this->mail);
					if ($this->tel->Exportable) $Doc->ExportField($this->tel);
					if ($this->cel->Exportable) $Doc->ExportField($this->cel);
					if ($this->cuit_cuil->Exportable) $Doc->ExportField($this->cuit_cuil);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '`id_usuario` IN (' . $sFilterWrk . ')';
		}

		// Call Row Rendered event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $conn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `socios`";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $conn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
