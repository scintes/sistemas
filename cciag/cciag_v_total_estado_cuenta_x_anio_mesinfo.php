<?php

// Global variable for table object
$v_total_estado_cuenta_x_anio_mes = NULL;

//
// Table class for v_total_estado_cuenta_x_anio_mes
//
class cv_total_estado_cuenta_x_anio_mes extends cTable {
	var $socio_nro;
	var $cuit_cuil;
	var $propietario;
	var $comercio;
	var $mes;
	var $anio;
	var $deuda;
	var $pago;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'v_total_estado_cuenta_x_anio_mes';
		$this->TableName = 'v_total_estado_cuenta_x_anio_mes';
		$this->TableType = 'VIEW';
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
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// socio_nro
		$this->socio_nro = new cField('v_total_estado_cuenta_x_anio_mes', 'v_total_estado_cuenta_x_anio_mes', 'x_socio_nro', 'socio_nro', '`socio_nro`', '`socio_nro`', 3, -1, FALSE, '`socio_nro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->socio_nro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['socio_nro'] = &$this->socio_nro;

		// cuit_cuil
		$this->cuit_cuil = new cField('v_total_estado_cuenta_x_anio_mes', 'v_total_estado_cuenta_x_anio_mes', 'x_cuit_cuil', 'cuit_cuil', '`cuit_cuil`', '`cuit_cuil`', 200, -1, FALSE, '`cuit_cuil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cuit_cuil'] = &$this->cuit_cuil;

		// propietario
		$this->propietario = new cField('v_total_estado_cuenta_x_anio_mes', 'v_total_estado_cuenta_x_anio_mes', 'x_propietario', 'propietario', '`propietario`', '`propietario`', 200, -1, FALSE, '`propietario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['propietario'] = &$this->propietario;

		// comercio
		$this->comercio = new cField('v_total_estado_cuenta_x_anio_mes', 'v_total_estado_cuenta_x_anio_mes', 'x_comercio', 'comercio', '`comercio`', '`comercio`', 200, -1, FALSE, '`comercio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['comercio'] = &$this->comercio;

		// mes
		$this->mes = new cField('v_total_estado_cuenta_x_anio_mes', 'v_total_estado_cuenta_x_anio_mes', 'x_mes', 'mes', '`mes`', '`mes`', 200, -1, FALSE, '`mes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['mes'] = &$this->mes;

		// anio
		$this->anio = new cField('v_total_estado_cuenta_x_anio_mes', 'v_total_estado_cuenta_x_anio_mes', 'x_anio', 'anio', '`anio`', '`anio`', 200, -1, FALSE, '`anio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['anio'] = &$this->anio;

		// deuda
		$this->deuda = new cField('v_total_estado_cuenta_x_anio_mes', 'v_total_estado_cuenta_x_anio_mes', 'x_deuda', 'deuda', '`deuda`', '`deuda`', 131, -1, FALSE, '`deuda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->deuda->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['deuda'] = &$this->deuda;

		// pago
		$this->pago = new cField('v_total_estado_cuenta_x_anio_mes', 'v_total_estado_cuenta_x_anio_mes', 'x_pago', 'pago', '`pago`', '`pago`', 131, -1, FALSE, '`pago`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->pago->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pago'] = &$this->pago;
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
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_total_estado_cuenta_x_anio_mes`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
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
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
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
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
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
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`v_total_estado_cuenta_x_anio_mes`";

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
		return "";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "cciag_login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "cciag_v_total_estado_cuenta_x_anio_meslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "cciag_v_total_estado_cuenta_x_anio_meslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("cciag_v_total_estado_cuenta_x_anio_mesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("cciag_v_total_estado_cuenta_x_anio_mesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "cciag_v_total_estado_cuenta_x_anio_mesadd.php?" . $this->UrlParm($parm);
		else
			return "cciag_v_total_estado_cuenta_x_anio_mesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("cciag_v_total_estado_cuenta_x_anio_mesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("cciag_v_total_estado_cuenta_x_anio_mesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("cciag_v_total_estado_cuenta_x_anio_mesdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
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

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
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
		$this->cuit_cuil->setDbValue($rs->fields('cuit_cuil'));
		$this->propietario->setDbValue($rs->fields('propietario'));
		$this->comercio->setDbValue($rs->fields('comercio'));
		$this->mes->setDbValue($rs->fields('mes'));
		$this->anio->setDbValue($rs->fields('anio'));
		$this->deuda->setDbValue($rs->fields('deuda'));
		$this->pago->setDbValue($rs->fields('pago'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// socio_nro
		// cuit_cuil
		// propietario
		// comercio
		// mes
		// anio
		// deuda
		// pago
		// socio_nro

		if (strval($this->socio_nro->CurrentValue) <> "") {
			$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->socio_nro->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `comercio` AS `Disp2Fld`, `propietario` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->socio_nro, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `comercio` DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->socio_nro->ViewValue = $rswrk->fields('DispFld');
				$this->socio_nro->ViewValue .= ew_ValueSeparator(1,$this->socio_nro) . $rswrk->fields('Disp2Fld');
				$this->socio_nro->ViewValue .= ew_ValueSeparator(2,$this->socio_nro) . $rswrk->fields('Disp3Fld');
				$rswrk->Close();
			} else {
				$this->socio_nro->ViewValue = $this->socio_nro->CurrentValue;
			}
		} else {
			$this->socio_nro->ViewValue = NULL;
		}
		$this->socio_nro->ViewValue = ew_FormatNumber($this->socio_nro->ViewValue, 0, -2, -2, -2);
		$this->socio_nro->ViewCustomAttributes = "";

		// cuit_cuil
		$this->cuit_cuil->ViewValue = $this->cuit_cuil->CurrentValue;
		$this->cuit_cuil->ViewCustomAttributes = "";

		// propietario
		if (strval($this->propietario->CurrentValue) <> "") {
			$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->propietario->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `propietario` AS `Disp2Fld`, '' AS `Disp3Fld`, `comercio` AS `Disp4Fld` FROM `socios`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->propietario, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->propietario->ViewValue = $rswrk->fields('DispFld');
				$this->propietario->ViewValue .= ew_ValueSeparator(1,$this->propietario) . $rswrk->fields('Disp2Fld');
				$this->propietario->ViewValue .= ew_ValueSeparator(3,$this->propietario) . $rswrk->fields('Disp4Fld');
				$rswrk->Close();
			} else {
				$this->propietario->ViewValue = $this->propietario->CurrentValue;
			}
		} else {
			$this->propietario->ViewValue = NULL;
		}
		$this->propietario->ViewCustomAttributes = "";

		// comercio
		if (strval($this->comercio->CurrentValue) <> "") {
			$sFilterWrk = "`socio_nro`" . ew_SearchString("=", $this->comercio->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `propietario` AS `Disp2Fld`, '' AS `Disp3Fld`, `comercio` AS `Disp4Fld` FROM `socios`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->comercio, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->comercio->ViewValue = $rswrk->fields('DispFld');
				$this->comercio->ViewValue .= ew_ValueSeparator(1,$this->comercio) . $rswrk->fields('Disp2Fld');
				$this->comercio->ViewValue .= ew_ValueSeparator(3,$this->comercio) . $rswrk->fields('Disp4Fld');
				$rswrk->Close();
			} else {
				$this->comercio->ViewValue = $this->comercio->CurrentValue;
			}
		} else {
			$this->comercio->ViewValue = NULL;
		}
		$this->comercio->ViewCustomAttributes = "";

		// mes
		if (strval($this->mes->CurrentValue) <> "") {
			switch ($this->mes->CurrentValue) {
				case $this->mes->FldTagValue(1):
					$this->mes->ViewValue = $this->mes->FldTagCaption(1) <> "" ? $this->mes->FldTagCaption(1) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(2):
					$this->mes->ViewValue = $this->mes->FldTagCaption(2) <> "" ? $this->mes->FldTagCaption(2) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(3):
					$this->mes->ViewValue = $this->mes->FldTagCaption(3) <> "" ? $this->mes->FldTagCaption(3) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(4):
					$this->mes->ViewValue = $this->mes->FldTagCaption(4) <> "" ? $this->mes->FldTagCaption(4) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(5):
					$this->mes->ViewValue = $this->mes->FldTagCaption(5) <> "" ? $this->mes->FldTagCaption(5) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(6):
					$this->mes->ViewValue = $this->mes->FldTagCaption(6) <> "" ? $this->mes->FldTagCaption(6) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(7):
					$this->mes->ViewValue = $this->mes->FldTagCaption(7) <> "" ? $this->mes->FldTagCaption(7) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(8):
					$this->mes->ViewValue = $this->mes->FldTagCaption(8) <> "" ? $this->mes->FldTagCaption(8) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(9):
					$this->mes->ViewValue = $this->mes->FldTagCaption(9) <> "" ? $this->mes->FldTagCaption(9) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(10):
					$this->mes->ViewValue = $this->mes->FldTagCaption(10) <> "" ? $this->mes->FldTagCaption(10) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(11):
					$this->mes->ViewValue = $this->mes->FldTagCaption(11) <> "" ? $this->mes->FldTagCaption(11) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(12):
					$this->mes->ViewValue = $this->mes->FldTagCaption(12) <> "" ? $this->mes->FldTagCaption(12) : $this->mes->CurrentValue;
					break;
				default:
					$this->mes->ViewValue = $this->mes->CurrentValue;
			}
		} else {
			$this->mes->ViewValue = NULL;
		}
		$this->mes->ViewValue = ew_FormatNumber($this->mes->ViewValue, 0, -2, -2, -2);
		$this->mes->ViewCustomAttributes = "";

		// anio
		if (strval($this->anio->CurrentValue) <> "") {
			switch ($this->anio->CurrentValue) {
				case $this->anio->FldTagValue(1):
					$this->anio->ViewValue = $this->anio->FldTagCaption(1) <> "" ? $this->anio->FldTagCaption(1) : $this->anio->CurrentValue;
					break;
				case $this->anio->FldTagValue(2):
					$this->anio->ViewValue = $this->anio->FldTagCaption(2) <> "" ? $this->anio->FldTagCaption(2) : $this->anio->CurrentValue;
					break;
				case $this->anio->FldTagValue(3):
					$this->anio->ViewValue = $this->anio->FldTagCaption(3) <> "" ? $this->anio->FldTagCaption(3) : $this->anio->CurrentValue;
					break;
				case $this->anio->FldTagValue(4):
					$this->anio->ViewValue = $this->anio->FldTagCaption(4) <> "" ? $this->anio->FldTagCaption(4) : $this->anio->CurrentValue;
					break;
				case $this->anio->FldTagValue(5):
					$this->anio->ViewValue = $this->anio->FldTagCaption(5) <> "" ? $this->anio->FldTagCaption(5) : $this->anio->CurrentValue;
					break;
				case $this->anio->FldTagValue(6):
					$this->anio->ViewValue = $this->anio->FldTagCaption(6) <> "" ? $this->anio->FldTagCaption(6) : $this->anio->CurrentValue;
					break;
				case $this->anio->FldTagValue(7):
					$this->anio->ViewValue = $this->anio->FldTagCaption(7) <> "" ? $this->anio->FldTagCaption(7) : $this->anio->CurrentValue;
					break;
				default:
					$this->anio->ViewValue = $this->anio->CurrentValue;
			}
		} else {
			$this->anio->ViewValue = NULL;
		}
		$this->anio->ViewValue = ew_FormatNumber($this->anio->ViewValue, 0, -2, -2, -2);
		$this->anio->ViewCustomAttributes = "";

		// deuda
		$this->deuda->ViewValue = $this->deuda->CurrentValue;
		$this->deuda->ViewCustomAttributes = "";

		// pago
		$this->pago->ViewValue = $this->pago->CurrentValue;
		$this->pago->ViewCustomAttributes = "";

		// socio_nro
		$this->socio_nro->LinkCustomAttributes = "";
		$this->socio_nro->HrefValue = "";
		$this->socio_nro->TooltipValue = "";

		// cuit_cuil
		$this->cuit_cuil->LinkCustomAttributes = "";
		$this->cuit_cuil->HrefValue = "";
		$this->cuit_cuil->TooltipValue = "";

		// propietario
		$this->propietario->LinkCustomAttributes = "";
		$this->propietario->HrefValue = "";
		$this->propietario->TooltipValue = "";

		// comercio
		$this->comercio->LinkCustomAttributes = "";
		$this->comercio->HrefValue = "";
		$this->comercio->TooltipValue = "";

		// mes
		$this->mes->LinkCustomAttributes = "";
		$this->mes->HrefValue = "";
		$this->mes->TooltipValue = "";

		// anio
		$this->anio->LinkCustomAttributes = "";
		$this->anio->HrefValue = "";
		$this->anio->TooltipValue = "";

		// deuda
		$this->deuda->LinkCustomAttributes = "";
		$this->deuda->HrefValue = "";
		$this->deuda->TooltipValue = "";

		// pago
		$this->pago->LinkCustomAttributes = "";
		$this->pago->HrefValue = "";
		$this->pago->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// socio_nro
		$this->socio_nro->EditAttrs["class"] = "form-control";
		$this->socio_nro->EditCustomAttributes = "";

		// cuit_cuil
		$this->cuit_cuil->EditAttrs["class"] = "form-control";
		$this->cuit_cuil->EditCustomAttributes = "";
		$this->cuit_cuil->EditValue = ew_HtmlEncode($this->cuit_cuil->CurrentValue);
		$this->cuit_cuil->PlaceHolder = ew_RemoveHtml($this->cuit_cuil->FldCaption());

		// propietario
		$this->propietario->EditAttrs["class"] = "form-control";
		$this->propietario->EditCustomAttributes = "";

		// comercio
		$this->comercio->EditAttrs["class"] = "form-control";
		$this->comercio->EditCustomAttributes = "";

		// mes
		$this->mes->EditAttrs["class"] = "form-control";
		$this->mes->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->mes->FldTagValue(1), $this->mes->FldTagCaption(1) <> "" ? $this->mes->FldTagCaption(1) : $this->mes->FldTagValue(1));
		$arwrk[] = array($this->mes->FldTagValue(2), $this->mes->FldTagCaption(2) <> "" ? $this->mes->FldTagCaption(2) : $this->mes->FldTagValue(2));
		$arwrk[] = array($this->mes->FldTagValue(3), $this->mes->FldTagCaption(3) <> "" ? $this->mes->FldTagCaption(3) : $this->mes->FldTagValue(3));
		$arwrk[] = array($this->mes->FldTagValue(4), $this->mes->FldTagCaption(4) <> "" ? $this->mes->FldTagCaption(4) : $this->mes->FldTagValue(4));
		$arwrk[] = array($this->mes->FldTagValue(5), $this->mes->FldTagCaption(5) <> "" ? $this->mes->FldTagCaption(5) : $this->mes->FldTagValue(5));
		$arwrk[] = array($this->mes->FldTagValue(6), $this->mes->FldTagCaption(6) <> "" ? $this->mes->FldTagCaption(6) : $this->mes->FldTagValue(6));
		$arwrk[] = array($this->mes->FldTagValue(7), $this->mes->FldTagCaption(7) <> "" ? $this->mes->FldTagCaption(7) : $this->mes->FldTagValue(7));
		$arwrk[] = array($this->mes->FldTagValue(8), $this->mes->FldTagCaption(8) <> "" ? $this->mes->FldTagCaption(8) : $this->mes->FldTagValue(8));
		$arwrk[] = array($this->mes->FldTagValue(9), $this->mes->FldTagCaption(9) <> "" ? $this->mes->FldTagCaption(9) : $this->mes->FldTagValue(9));
		$arwrk[] = array($this->mes->FldTagValue(10), $this->mes->FldTagCaption(10) <> "" ? $this->mes->FldTagCaption(10) : $this->mes->FldTagValue(10));
		$arwrk[] = array($this->mes->FldTagValue(11), $this->mes->FldTagCaption(11) <> "" ? $this->mes->FldTagCaption(11) : $this->mes->FldTagValue(11));
		$arwrk[] = array($this->mes->FldTagValue(12), $this->mes->FldTagCaption(12) <> "" ? $this->mes->FldTagCaption(12) : $this->mes->FldTagValue(12));
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
		$this->mes->EditValue = $arwrk;

		// anio
		$this->anio->EditAttrs["class"] = "form-control";
		$this->anio->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->anio->FldTagValue(1), $this->anio->FldTagCaption(1) <> "" ? $this->anio->FldTagCaption(1) : $this->anio->FldTagValue(1));
		$arwrk[] = array($this->anio->FldTagValue(2), $this->anio->FldTagCaption(2) <> "" ? $this->anio->FldTagCaption(2) : $this->anio->FldTagValue(2));
		$arwrk[] = array($this->anio->FldTagValue(3), $this->anio->FldTagCaption(3) <> "" ? $this->anio->FldTagCaption(3) : $this->anio->FldTagValue(3));
		$arwrk[] = array($this->anio->FldTagValue(4), $this->anio->FldTagCaption(4) <> "" ? $this->anio->FldTagCaption(4) : $this->anio->FldTagValue(4));
		$arwrk[] = array($this->anio->FldTagValue(5), $this->anio->FldTagCaption(5) <> "" ? $this->anio->FldTagCaption(5) : $this->anio->FldTagValue(5));
		$arwrk[] = array($this->anio->FldTagValue(6), $this->anio->FldTagCaption(6) <> "" ? $this->anio->FldTagCaption(6) : $this->anio->FldTagValue(6));
		$arwrk[] = array($this->anio->FldTagValue(7), $this->anio->FldTagCaption(7) <> "" ? $this->anio->FldTagCaption(7) : $this->anio->FldTagValue(7));
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
		$this->anio->EditValue = $arwrk;

		// deuda
		$this->deuda->EditAttrs["class"] = "form-control";
		$this->deuda->EditCustomAttributes = "";
		$this->deuda->EditValue = ew_HtmlEncode($this->deuda->CurrentValue);
		$this->deuda->PlaceHolder = ew_RemoveHtml($this->deuda->FldCaption());
		if (strval($this->deuda->EditValue) <> "" && is_numeric($this->deuda->EditValue)) $this->deuda->EditValue = ew_FormatNumber($this->deuda->EditValue, -2, -1, -2, 0);

		// pago
		$this->pago->EditAttrs["class"] = "form-control";
		$this->pago->EditCustomAttributes = "";
		$this->pago->EditValue = ew_HtmlEncode($this->pago->CurrentValue);
		$this->pago->PlaceHolder = ew_RemoveHtml($this->pago->FldCaption());
		if (strval($this->pago->EditValue) <> "" && is_numeric($this->pago->EditValue)) $this->pago->EditValue = ew_FormatNumber($this->pago->EditValue, -2, -1, -2, 0);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->deuda->CurrentValue))
				$this->deuda->Total += $this->deuda->CurrentValue; // Accumulate total
			if (is_numeric($this->pago->CurrentValue))
				$this->pago->Total += $this->pago->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->deuda->CurrentValue = $this->deuda->Total;
			$this->deuda->ViewValue = $this->deuda->CurrentValue;
			$this->deuda->ViewCustomAttributes = "";
			$this->deuda->HrefValue = ""; // Clear href value
			$this->pago->CurrentValue = $this->pago->Total;
			$this->pago->ViewValue = $this->pago->CurrentValue;
			$this->pago->ViewCustomAttributes = "";
			$this->pago->HrefValue = ""; // Clear href value
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->socio_nro->Exportable) $Doc->ExportCaption($this->socio_nro);
					if ($this->cuit_cuil->Exportable) $Doc->ExportCaption($this->cuit_cuil);
					if ($this->propietario->Exportable) $Doc->ExportCaption($this->propietario);
					if ($this->comercio->Exportable) $Doc->ExportCaption($this->comercio);
					if ($this->mes->Exportable) $Doc->ExportCaption($this->mes);
					if ($this->anio->Exportable) $Doc->ExportCaption($this->anio);
					if ($this->deuda->Exportable) $Doc->ExportCaption($this->deuda);
					if ($this->pago->Exportable) $Doc->ExportCaption($this->pago);
				} else {
					if ($this->socio_nro->Exportable) $Doc->ExportCaption($this->socio_nro);
					if ($this->cuit_cuil->Exportable) $Doc->ExportCaption($this->cuit_cuil);
					if ($this->propietario->Exportable) $Doc->ExportCaption($this->propietario);
					if ($this->comercio->Exportable) $Doc->ExportCaption($this->comercio);
					if ($this->mes->Exportable) $Doc->ExportCaption($this->mes);
					if ($this->anio->Exportable) $Doc->ExportCaption($this->anio);
					if ($this->deuda->Exportable) $Doc->ExportCaption($this->deuda);
					if ($this->pago->Exportable) $Doc->ExportCaption($this->pago);
				}
				$Doc->EndExportRow();
			}
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
				$this->AggregateListRowValues(); // Aggregate row values

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->socio_nro->Exportable) $Doc->ExportField($this->socio_nro);
						if ($this->cuit_cuil->Exportable) $Doc->ExportField($this->cuit_cuil);
						if ($this->propietario->Exportable) $Doc->ExportField($this->propietario);
						if ($this->comercio->Exportable) $Doc->ExportField($this->comercio);
						if ($this->mes->Exportable) $Doc->ExportField($this->mes);
						if ($this->anio->Exportable) $Doc->ExportField($this->anio);
						if ($this->deuda->Exportable) $Doc->ExportField($this->deuda);
						if ($this->pago->Exportable) $Doc->ExportField($this->pago);
					} else {
						if ($this->socio_nro->Exportable) $Doc->ExportField($this->socio_nro);
						if ($this->cuit_cuil->Exportable) $Doc->ExportField($this->cuit_cuil);
						if ($this->propietario->Exportable) $Doc->ExportField($this->propietario);
						if ($this->comercio->Exportable) $Doc->ExportField($this->comercio);
						if ($this->mes->Exportable) $Doc->ExportField($this->mes);
						if ($this->anio->Exportable) $Doc->ExportField($this->anio);
						if ($this->deuda->Exportable) $Doc->ExportField($this->deuda);
						if ($this->pago->Exportable) $Doc->ExportField($this->pago);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}

		// Export aggregates (horizontal format only)
		if ($Doc->Horizontal) {
			$this->RowType = EW_ROWTYPE_AGGREGATE;
			$this->ResetAttrs();
			$this->AggregateListRow();
			if (!$Doc->ExportCustom) {
				$Doc->BeginExportRow(-1);
				$Doc->ExportAggregate($this->socio_nro, '');
				$Doc->ExportAggregate($this->cuit_cuil, '');
				$Doc->ExportAggregate($this->propietario, '');
				$Doc->ExportAggregate($this->comercio, '');
				$Doc->ExportAggregate($this->mes, '');
				$Doc->ExportAggregate($this->anio, '');
				$Doc->ExportAggregate($this->deuda, 'TOTAL');
				$Doc->ExportAggregate($this->pago, 'TOTAL');
				$Doc->EndExportRow();
			}
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;
		if (preg_match('/^x(\d)*_socio_nro$/', $id)) {
			$sSqlWrk = "SELECT `cuit_cuil` AS FIELD0, `propietario` AS FIELD1, `comercio` AS FIELD2 FROM `socios`";
			$sWhereWrk = "(`socio_nro` = " . ew_AdjustSql($val) . ")";
			if (!$GLOBALS["v_total_estado_cuenta_x_anio_mes"]->UserIDAllow("info")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->socio_nro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `comercio` DESC";
			if ($rs = ew_LoadRecordset($sSqlWrk)) {
				while ($rs && !$rs->EOF) {
					$ar = array();
					$this->cuit_cuil->setDbValue($rs->fields[0]);
					$this->propietario->setDbValue($rs->fields[1]);
					$this->comercio->setDbValue($rs->fields[2]);
					$this->RowType == EW_ROWTYPE_EDIT;
					$this->RenderEditRow();
					$ar[] = ($this->cuit_cuil->AutoFillOriginalValue) ? $this->cuit_cuil->CurrentValue : $this->cuit_cuil->EditValue;
					$ar[] = $this->propietario->CurrentValue;
					$ar[] = $this->comercio->CurrentValue;
					$rowcnt += 1;
					$rsarr[] = $ar;
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
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

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
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
