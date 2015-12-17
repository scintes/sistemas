<?php

// Global variable for table object
$resumen_por_mes_de_pago = NULL;

//
// Table class for resumen_por_mes_de_pago
//
class cresumen_por_mes_de_pago extends cTable {
	var $socio_nro;
	var $comercio;
	var $mes;
	var $anio;
	var $Pagos;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'resumen_por_mes_de_pago';
		$this->TableName = 'resumen_por_mes_de_pago';
		$this->TableType = 'VIEW';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// socio_nro
		$this->socio_nro = new cField('resumen_por_mes_de_pago', 'resumen_por_mes_de_pago', 'x_socio_nro', 'socio_nro', '`socio_nro`', '`socio_nro`', 3, -1, FALSE, '`socio_nro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->socio_nro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['socio_nro'] = &$this->socio_nro;

		// comercio
		$this->comercio = new cField('resumen_por_mes_de_pago', 'resumen_por_mes_de_pago', 'x_comercio', 'comercio', '`comercio`', '`comercio`', 200, -1, FALSE, '`comercio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['comercio'] = &$this->comercio;

		// mes
		$this->mes = new cField('resumen_por_mes_de_pago', 'resumen_por_mes_de_pago', 'x_mes', 'mes', '`mes`', '`mes`', 200, -1, FALSE, '`mes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['mes'] = &$this->mes;

		// anio
		$this->anio = new cField('resumen_por_mes_de_pago', 'resumen_por_mes_de_pago', 'x_anio', 'anio', '`anio`', '`anio`', 200, -1, FALSE, '`anio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['anio'] = &$this->anio;

		// Pagos
		$this->Pagos = new cField('resumen_por_mes_de_pago', 'resumen_por_mes_de_pago', 'x_Pagos', 'Pagos', '`Pagos`', '`Pagos`', 131, -1, FALSE, '`Pagos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Pagos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Pagos'] = &$this->Pagos;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`resumen_por_mes_de_pago`";
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
	var $UpdateTable = "`resumen_por_mes_de_pago`";

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
			if (array_key_exists('mes', $rs))
				ew_AddFilter($where, ew_QuotedName('mes') . '=' . ew_QuotedValue($rs['mes'], $this->mes->FldDataType));
			if (array_key_exists('anio', $rs))
				ew_AddFilter($where, ew_QuotedName('anio') . '=' . ew_QuotedValue($rs['anio'], $this->anio->FldDataType));
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
		return "`socio_nro` = @socio_nro@ AND `mes` = '@mes@' AND `anio` = '@anio@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->socio_nro->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@socio_nro@", ew_AdjustSql($this->socio_nro->CurrentValue), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@mes@", ew_AdjustSql($this->mes->CurrentValue), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@anio@", ew_AdjustSql($this->anio->CurrentValue), $sKeyFilter); // Replace key value
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
			return "cciag_resumen_por_mes_de_pagolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "cciag_resumen_por_mes_de_pagolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("cciag_resumen_por_mes_de_pagoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("cciag_resumen_por_mes_de_pagoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "cciag_resumen_por_mes_de_pagoadd.php?" . $this->UrlParm($parm);
		else
			return "cciag_resumen_por_mes_de_pagoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("cciag_resumen_por_mes_de_pagoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("cciag_resumen_por_mes_de_pagoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("cciag_resumen_por_mes_de_pagodelete.php", $this->UrlParm());
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
		if (!is_null($this->mes->CurrentValue)) {
			$sUrl .= "&mes=" . urlencode($this->mes->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->anio->CurrentValue)) {
			$sUrl .= "&anio=" . urlencode($this->anio->CurrentValue);
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
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET)) {
			$arKey[] = @$_GET["socio_nro"]; // socio_nro
			$arKey[] = @$_GET["mes"]; // mes
			$arKey[] = @$_GET["anio"]; // anio
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 3)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // socio_nro
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
			$this->socio_nro->CurrentValue = $key[0];
			$this->mes->CurrentValue = $key[1];
			$this->anio->CurrentValue = $key[2];
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
		$this->comercio->setDbValue($rs->fields('comercio'));
		$this->mes->setDbValue($rs->fields('mes'));
		$this->anio->setDbValue($rs->fields('anio'));
		$this->Pagos->setDbValue($rs->fields('Pagos'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// socio_nro
		// comercio
		// mes
		// anio
		// Pagos
		// socio_nro

		$this->socio_nro->ViewValue = $this->socio_nro->CurrentValue;
		$this->socio_nro->ViewCustomAttributes = "";

		// comercio
		$this->comercio->ViewValue = $this->comercio->CurrentValue;
		$this->comercio->ViewCustomAttributes = "";

		// mes
		$this->mes->ViewValue = $this->mes->CurrentValue;
		$this->mes->ViewCustomAttributes = "";

		// anio
		$this->anio->ViewValue = $this->anio->CurrentValue;
		$this->anio->ViewCustomAttributes = "";

		// Pagos
		$this->Pagos->ViewValue = $this->Pagos->CurrentValue;
		$this->Pagos->ViewCustomAttributes = "";

		// socio_nro
		$this->socio_nro->LinkCustomAttributes = "";
		$this->socio_nro->HrefValue = "";
		$this->socio_nro->TooltipValue = "";

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

		// Pagos
		$this->Pagos->LinkCustomAttributes = "";
		$this->Pagos->HrefValue = "";
		$this->Pagos->TooltipValue = "";

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
		$this->socio_nro->EditValue = $this->socio_nro->CurrentValue;
		$this->socio_nro->ViewCustomAttributes = "";

		// comercio
		$this->comercio->EditAttrs["class"] = "form-control";
		$this->comercio->EditCustomAttributes = "";
		$this->comercio->EditValue = ew_HtmlEncode($this->comercio->CurrentValue);
		$this->comercio->PlaceHolder = ew_RemoveHtml($this->comercio->FldCaption());

		// mes
		$this->mes->EditAttrs["class"] = "form-control";
		$this->mes->EditCustomAttributes = "";
		$this->mes->EditValue = $this->mes->CurrentValue;
		$this->mes->ViewCustomAttributes = "";

		// anio
		$this->anio->EditAttrs["class"] = "form-control";
		$this->anio->EditCustomAttributes = "";
		$this->anio->EditValue = $this->anio->CurrentValue;
		$this->anio->ViewCustomAttributes = "";

		// Pagos
		$this->Pagos->EditAttrs["class"] = "form-control";
		$this->Pagos->EditCustomAttributes = "";
		$this->Pagos->EditValue = ew_HtmlEncode($this->Pagos->CurrentValue);
		$this->Pagos->PlaceHolder = ew_RemoveHtml($this->Pagos->FldCaption());
		if (strval($this->Pagos->EditValue) <> "" && is_numeric($this->Pagos->EditValue)) $this->Pagos->EditValue = ew_FormatNumber($this->Pagos->EditValue, -2, -1, -2, 0);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
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
					if ($this->comercio->Exportable) $Doc->ExportCaption($this->comercio);
					if ($this->mes->Exportable) $Doc->ExportCaption($this->mes);
					if ($this->anio->Exportable) $Doc->ExportCaption($this->anio);
					if ($this->Pagos->Exportable) $Doc->ExportCaption($this->Pagos);
				} else {
					if ($this->socio_nro->Exportable) $Doc->ExportCaption($this->socio_nro);
					if ($this->comercio->Exportable) $Doc->ExportCaption($this->comercio);
					if ($this->mes->Exportable) $Doc->ExportCaption($this->mes);
					if ($this->anio->Exportable) $Doc->ExportCaption($this->anio);
					if ($this->Pagos->Exportable) $Doc->ExportCaption($this->Pagos);
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
						if ($this->socio_nro->Exportable) $Doc->ExportField($this->socio_nro);
						if ($this->comercio->Exportable) $Doc->ExportField($this->comercio);
						if ($this->mes->Exportable) $Doc->ExportField($this->mes);
						if ($this->anio->Exportable) $Doc->ExportField($this->anio);
						if ($this->Pagos->Exportable) $Doc->ExportField($this->Pagos);
					} else {
						if ($this->socio_nro->Exportable) $Doc->ExportField($this->socio_nro);
						if ($this->comercio->Exportable) $Doc->ExportField($this->comercio);
						if ($this->mes->Exportable) $Doc->ExportField($this->mes);
						if ($this->anio->Exportable) $Doc->ExportField($this->anio);
						if ($this->Pagos->Exportable) $Doc->ExportField($this->Pagos);
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
