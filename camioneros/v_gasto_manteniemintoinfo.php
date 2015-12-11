<?php

// Global variable for table object
$v_gasto_mantenieminto = NULL;

//
// Table class for v_gasto_mantenieminto
//
class cv_gasto_mantenieminto extends cTable {
	var $codigo;
	var $fecha;
	var $detalles;
	var $iva;
	var $Importe;
	var $id_tipo_gasto;
	var $id_hoja_mantenimiento;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'v_gasto_mantenieminto';
		$this->TableName = 'v_gasto_mantenieminto';
		$this->TableType = 'VIEW';
		$this->ExportAll = FALSE;
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

		// codigo
		$this->codigo = new cField('v_gasto_mantenieminto', 'v_gasto_mantenieminto', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 3, -1, FALSE, '`codigo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->codigo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codigo'] = &$this->codigo;

		// fecha
		$this->fecha = new cField('v_gasto_mantenieminto', 'v_gasto_mantenieminto', 'x_fecha', 'fecha', '`fecha`', 'DATE_FORMAT(`fecha`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha'] = &$this->fecha;

		// detalles
		$this->detalles = new cField('v_gasto_mantenieminto', 'v_gasto_mantenieminto', 'x_detalles', 'detalles', '`detalles`', '`detalles`', 200, -1, FALSE, '`detalles`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['detalles'] = &$this->detalles;

		// iva
		$this->iva = new cField('v_gasto_mantenieminto', 'v_gasto_mantenieminto', 'x_iva', 'iva', '`iva`', '`iva`', 131, -1, FALSE, '`iva`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->iva->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['iva'] = &$this->iva;

		// Importe
		$this->Importe = new cField('v_gasto_mantenieminto', 'v_gasto_mantenieminto', 'x_Importe', 'Importe', '`Importe`', '`Importe`', 131, -1, FALSE, '`Importe`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Importe->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Importe'] = &$this->Importe;

		// id_tipo_gasto
		$this->id_tipo_gasto = new cField('v_gasto_mantenieminto', 'v_gasto_mantenieminto', 'x_id_tipo_gasto', 'id_tipo_gasto', '`id_tipo_gasto`', '`id_tipo_gasto`', 3, -1, FALSE, '`id_tipo_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_tipo_gasto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tipo_gasto'] = &$this->id_tipo_gasto;

		// id_hoja_mantenimiento
		$this->id_hoja_mantenimiento = new cField('v_gasto_mantenieminto', 'v_gasto_mantenieminto', 'x_id_hoja_mantenimiento', 'id_hoja_mantenimiento', '`id_hoja_mantenimiento`', '`id_hoja_mantenimiento`', 3, -1, FALSE, '`id_hoja_mantenimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_hoja_mantenimiento->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_hoja_mantenimiento'] = &$this->id_hoja_mantenimiento;
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

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "hoja_mantenimientos") {
			if ($this->id_hoja_mantenimiento->getSessionValue() <> "")
				$sMasterFilter .= "`codigo`=" . ew_QuotedValue($this->id_hoja_mantenimiento->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "hoja_mantenimientos") {
			if ($this->id_hoja_mantenimiento->getSessionValue() <> "")
				$sDetailFilter .= "`id_hoja_mantenimiento`=" . ew_QuotedValue($this->id_hoja_mantenimiento->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_hoja_mantenimientos() {
		return "`codigo`=@codigo@";
	}

	// Detail filter
	function SqlDetailFilter_hoja_mantenimientos() {
		return "`id_hoja_mantenimiento`=@id_hoja_mantenimiento@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_gasto_mantenieminto`";
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
	var $UpdateTable = "`v_gasto_mantenieminto`";

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
			if (array_key_exists('codigo', $rs))
				ew_AddFilter($where, ew_QuotedName('codigo') . '=' . ew_QuotedValue($rs['codigo'], $this->codigo->FldDataType));
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
		return "`codigo` = @codigo@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->codigo->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@codigo@", ew_AdjustSql($this->codigo->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "v_gasto_manteniemintolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "v_gasto_manteniemintolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("v_gasto_manteniemintoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("v_gasto_manteniemintoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "v_gasto_manteniemintoadd.php?" . $this->UrlParm($parm);
		else
			return "v_gasto_manteniemintoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("v_gasto_manteniemintoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("v_gasto_manteniemintoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("v_gasto_manteniemintodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->codigo->CurrentValue)) {
			$sUrl .= "codigo=" . urlencode($this->codigo->CurrentValue);
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
			$arKeys[] = @$_GET["codigo"]; // codigo

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
			$this->codigo->CurrentValue = $key;
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
		$this->codigo->setDbValue($rs->fields('codigo'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->detalles->setDbValue($rs->fields('detalles'));
		$this->iva->setDbValue($rs->fields('iva'));
		$this->Importe->setDbValue($rs->fields('Importe'));
		$this->id_tipo_gasto->setDbValue($rs->fields('id_tipo_gasto'));
		$this->id_hoja_mantenimiento->setDbValue($rs->fields('id_hoja_mantenimiento'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// codigo
		// fecha
		// detalles
		// iva
		// Importe
		// id_tipo_gasto
		// id_hoja_mantenimiento
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

		// iva
		$this->iva->ViewValue = $this->iva->CurrentValue;
		$this->iva->ViewCustomAttributes = "";

		// Importe
		$this->Importe->ViewValue = $this->Importe->CurrentValue;
		$this->Importe->ViewCustomAttributes = "";

		// id_tipo_gasto
		$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->CurrentValue;
		$this->id_tipo_gasto->ViewCustomAttributes = "";

		// id_hoja_mantenimiento
		$this->id_hoja_mantenimiento->ViewValue = $this->id_hoja_mantenimiento->CurrentValue;
		$this->id_hoja_mantenimiento->ViewCustomAttributes = "";

		// codigo
		$this->codigo->LinkCustomAttributes = "";
		$this->codigo->HrefValue = "";
		$this->codigo->TooltipValue = "";

		// fecha
		$this->fecha->LinkCustomAttributes = "";
		$this->fecha->HrefValue = "";
		$this->fecha->TooltipValue = "";

		// detalles
		$this->detalles->LinkCustomAttributes = "";
		$this->detalles->HrefValue = "";
		$this->detalles->TooltipValue = "";

		// iva
		$this->iva->LinkCustomAttributes = "";
		$this->iva->HrefValue = "";
		$this->iva->TooltipValue = "";

		// Importe
		$this->Importe->LinkCustomAttributes = "";
		$this->Importe->HrefValue = "";
		$this->Importe->TooltipValue = "";

		// id_tipo_gasto
		$this->id_tipo_gasto->LinkCustomAttributes = "";
		$this->id_tipo_gasto->HrefValue = "";
		$this->id_tipo_gasto->TooltipValue = "";

		// id_hoja_mantenimiento
		$this->id_hoja_mantenimiento->LinkCustomAttributes = "";
		$this->id_hoja_mantenimiento->HrefValue = "";
		$this->id_hoja_mantenimiento->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// codigo
		$this->codigo->EditAttrs["class"] = "form-control";
		$this->codigo->EditCustomAttributes = "";
		$this->codigo->EditValue = $this->codigo->CurrentValue;
		$this->codigo->ViewCustomAttributes = "";

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

		// iva
		$this->iva->EditAttrs["class"] = "form-control";
		$this->iva->EditCustomAttributes = "";
		$this->iva->EditValue = ew_HtmlEncode($this->iva->CurrentValue);
		$this->iva->PlaceHolder = ew_RemoveHtml($this->iva->FldCaption());
		if (strval($this->iva->EditValue) <> "" && is_numeric($this->iva->EditValue)) $this->iva->EditValue = ew_FormatNumber($this->iva->EditValue, -2, -1, -2, 0);

		// Importe
		$this->Importe->EditAttrs["class"] = "form-control";
		$this->Importe->EditCustomAttributes = "";
		$this->Importe->EditValue = ew_HtmlEncode($this->Importe->CurrentValue);
		$this->Importe->PlaceHolder = ew_RemoveHtml($this->Importe->FldCaption());
		if (strval($this->Importe->EditValue) <> "" && is_numeric($this->Importe->EditValue)) $this->Importe->EditValue = ew_FormatNumber($this->Importe->EditValue, -2, -1, -2, 0);

		// id_tipo_gasto
		$this->id_tipo_gasto->EditAttrs["class"] = "form-control";
		$this->id_tipo_gasto->EditCustomAttributes = "";
		$this->id_tipo_gasto->EditValue = ew_HtmlEncode($this->id_tipo_gasto->CurrentValue);
		$this->id_tipo_gasto->PlaceHolder = ew_RemoveHtml($this->id_tipo_gasto->FldCaption());

		// id_hoja_mantenimiento
		$this->id_hoja_mantenimiento->EditAttrs["class"] = "form-control";
		$this->id_hoja_mantenimiento->EditCustomAttributes = "";
		if ($this->id_hoja_mantenimiento->getSessionValue() <> "") {
			$this->id_hoja_mantenimiento->CurrentValue = $this->id_hoja_mantenimiento->getSessionValue();
		$this->id_hoja_mantenimiento->ViewValue = $this->id_hoja_mantenimiento->CurrentValue;
		$this->id_hoja_mantenimiento->ViewCustomAttributes = "";
		} else {
		$this->id_hoja_mantenimiento->EditValue = ew_HtmlEncode($this->id_hoja_mantenimiento->CurrentValue);
		$this->id_hoja_mantenimiento->PlaceHolder = ew_RemoveHtml($this->id_hoja_mantenimiento->FldCaption());
		}

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
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
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->detalles->Exportable) $Doc->ExportCaption($this->detalles);
					if ($this->iva->Exportable) $Doc->ExportCaption($this->iva);
					if ($this->Importe->Exportable) $Doc->ExportCaption($this->Importe);
					if ($this->id_tipo_gasto->Exportable) $Doc->ExportCaption($this->id_tipo_gasto);
					if ($this->id_hoja_mantenimiento->Exportable) $Doc->ExportCaption($this->id_hoja_mantenimiento);
				} else {
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->detalles->Exportable) $Doc->ExportCaption($this->detalles);
					if ($this->iva->Exportable) $Doc->ExportCaption($this->iva);
					if ($this->Importe->Exportable) $Doc->ExportCaption($this->Importe);
					if ($this->id_tipo_gasto->Exportable) $Doc->ExportCaption($this->id_tipo_gasto);
					if ($this->id_hoja_mantenimiento->Exportable) $Doc->ExportCaption($this->id_hoja_mantenimiento);
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

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->detalles->Exportable) $Doc->ExportField($this->detalles);
						if ($this->iva->Exportable) $Doc->ExportField($this->iva);
						if ($this->Importe->Exportable) $Doc->ExportField($this->Importe);
						if ($this->id_tipo_gasto->Exportable) $Doc->ExportField($this->id_tipo_gasto);
						if ($this->id_hoja_mantenimiento->Exportable) $Doc->ExportField($this->id_hoja_mantenimiento);
					} else {
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->detalles->Exportable) $Doc->ExportField($this->detalles);
						if ($this->iva->Exportable) $Doc->ExportField($this->iva);
						if ($this->Importe->Exportable) $Doc->ExportField($this->Importe);
						if ($this->id_tipo_gasto->Exportable) $Doc->ExportField($this->id_tipo_gasto);
						if ($this->id_hoja_mantenimiento->Exportable) $Doc->ExportField($this->id_hoja_mantenimiento);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

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
