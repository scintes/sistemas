<?php

// Global variable for table object
$v_gastos_hoja_mantenimiento = NULL;

//
// Table class for v_gastos_hoja_mantenimiento
//
class cv_gastos_hoja_mantenimiento extends cTable {
	var $fecha_ini;
	var $fecha_fin;
	var $Patente;
	var $taller;
	var $tipo_mantenimiento;
	var $nro_hoja_mantenimiento;
	var $total_gasto;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'v_gastos_hoja_mantenimiento';
		$this->TableName = 'v_gastos_hoja_mantenimiento';
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

		// fecha_ini
		$this->fecha_ini = new cField('v_gastos_hoja_mantenimiento', 'v_gastos_hoja_mantenimiento', 'x_fecha_ini', 'fecha_ini', '`fecha_ini`', '`fecha_ini`', 200, -1, FALSE, '`fecha_ini`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fecha_ini'] = &$this->fecha_ini;

		// fecha_fin
		$this->fecha_fin = new cField('v_gastos_hoja_mantenimiento', 'v_gastos_hoja_mantenimiento', 'x_fecha_fin', 'fecha_fin', '`fecha_fin`', '`fecha_fin`', 200, -1, FALSE, '`fecha_fin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fecha_fin'] = &$this->fecha_fin;

		// Patente
		$this->Patente = new cField('v_gastos_hoja_mantenimiento', 'v_gastos_hoja_mantenimiento', 'x_Patente', 'Patente', '`Patente`', '`Patente`', 200, -1, FALSE, '`Patente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Patente'] = &$this->Patente;

		// taller
		$this->taller = new cField('v_gastos_hoja_mantenimiento', 'v_gastos_hoja_mantenimiento', 'x_taller', 'taller', '`taller`', '`taller`', 200, -1, FALSE, '`taller`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['taller'] = &$this->taller;

		// tipo_mantenimiento
		$this->tipo_mantenimiento = new cField('v_gastos_hoja_mantenimiento', 'v_gastos_hoja_mantenimiento', 'x_tipo_mantenimiento', 'tipo_mantenimiento', '`tipo_mantenimiento`', '`tipo_mantenimiento`', 200, -1, FALSE, '`tipo_mantenimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tipo_mantenimiento'] = &$this->tipo_mantenimiento;

		// nro_hoja_mantenimiento
		$this->nro_hoja_mantenimiento = new cField('v_gastos_hoja_mantenimiento', 'v_gastos_hoja_mantenimiento', 'x_nro_hoja_mantenimiento', 'nro_hoja_mantenimiento', '`nro_hoja_mantenimiento`', '`nro_hoja_mantenimiento`', 3, -1, FALSE, '`nro_hoja_mantenimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nro_hoja_mantenimiento->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nro_hoja_mantenimiento'] = &$this->nro_hoja_mantenimiento;

		// total_gasto
		$this->total_gasto = new cField('v_gastos_hoja_mantenimiento', 'v_gastos_hoja_mantenimiento', 'x_total_gasto', 'total_gasto', '`total_gasto`', '`total_gasto`', 131, -1, FALSE, '`total_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->total_gasto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total_gasto'] = &$this->total_gasto;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_gastos_hoja_mantenimiento`";
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
	var $UpdateTable = "`v_gastos_hoja_mantenimiento`";

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
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "v_gastos_hoja_mantenimientolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "v_gastos_hoja_mantenimientolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("v_gastos_hoja_mantenimientoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("v_gastos_hoja_mantenimientoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "v_gastos_hoja_mantenimientoadd.php?" . $this->UrlParm($parm);
		else
			return "v_gastos_hoja_mantenimientoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("v_gastos_hoja_mantenimientoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("v_gastos_hoja_mantenimientoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("v_gastos_hoja_mantenimientodelete.php", $this->UrlParm());
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
		$this->fecha_ini->setDbValue($rs->fields('fecha_ini'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->Patente->setDbValue($rs->fields('Patente'));
		$this->taller->setDbValue($rs->fields('taller'));
		$this->tipo_mantenimiento->setDbValue($rs->fields('tipo_mantenimiento'));
		$this->nro_hoja_mantenimiento->setDbValue($rs->fields('nro_hoja_mantenimiento'));
		$this->total_gasto->setDbValue($rs->fields('total_gasto'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// fecha_ini
		// fecha_fin
		// Patente
		// taller
		// tipo_mantenimiento
		// nro_hoja_mantenimiento
		// total_gasto
		// fecha_ini

		$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
		$this->fecha_ini->ViewCustomAttributes = "";

		// fecha_fin
		$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
		$this->fecha_fin->ViewCustomAttributes = "";

		// Patente
		$this->Patente->ViewValue = $this->Patente->CurrentValue;
		$this->Patente->ViewCustomAttributes = "";

		// taller
		$this->taller->ViewValue = $this->taller->CurrentValue;
		$this->taller->ViewCustomAttributes = "";

		// tipo_mantenimiento
		$this->tipo_mantenimiento->ViewValue = $this->tipo_mantenimiento->CurrentValue;
		$this->tipo_mantenimiento->ViewCustomAttributes = "";

		// nro_hoja_mantenimiento
		$this->nro_hoja_mantenimiento->ViewValue = $this->nro_hoja_mantenimiento->CurrentValue;
		$this->nro_hoja_mantenimiento->ViewCustomAttributes = "";

		// total_gasto
		$this->total_gasto->ViewValue = $this->total_gasto->CurrentValue;
		$this->total_gasto->ViewCustomAttributes = "";

		// fecha_ini
		$this->fecha_ini->LinkCustomAttributes = "";
		$this->fecha_ini->HrefValue = "";
		$this->fecha_ini->TooltipValue = "";

		// fecha_fin
		$this->fecha_fin->LinkCustomAttributes = "";
		$this->fecha_fin->HrefValue = "";
		$this->fecha_fin->TooltipValue = "";

		// Patente
		$this->Patente->LinkCustomAttributes = "";
		$this->Patente->HrefValue = "";
		$this->Patente->TooltipValue = "";

		// taller
		$this->taller->LinkCustomAttributes = "";
		$this->taller->HrefValue = "";
		$this->taller->TooltipValue = "";

		// tipo_mantenimiento
		$this->tipo_mantenimiento->LinkCustomAttributes = "";
		$this->tipo_mantenimiento->HrefValue = "";
		$this->tipo_mantenimiento->TooltipValue = "";

		// nro_hoja_mantenimiento
		$this->nro_hoja_mantenimiento->LinkCustomAttributes = "";
		$this->nro_hoja_mantenimiento->HrefValue = "";
		$this->nro_hoja_mantenimiento->TooltipValue = "";

		// total_gasto
		$this->total_gasto->LinkCustomAttributes = "";
		$this->total_gasto->HrefValue = "";
		$this->total_gasto->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// fecha_ini
		$this->fecha_ini->EditAttrs["class"] = "form-control";
		$this->fecha_ini->EditCustomAttributes = "";
		$this->fecha_ini->EditValue = ew_HtmlEncode($this->fecha_ini->CurrentValue);
		$this->fecha_ini->PlaceHolder = ew_RemoveHtml($this->fecha_ini->FldCaption());

		// fecha_fin
		$this->fecha_fin->EditAttrs["class"] = "form-control";
		$this->fecha_fin->EditCustomAttributes = "";
		$this->fecha_fin->EditValue = ew_HtmlEncode($this->fecha_fin->CurrentValue);
		$this->fecha_fin->PlaceHolder = ew_RemoveHtml($this->fecha_fin->FldCaption());

		// Patente
		$this->Patente->EditAttrs["class"] = "form-control";
		$this->Patente->EditCustomAttributes = "";
		$this->Patente->EditValue = ew_HtmlEncode($this->Patente->CurrentValue);
		$this->Patente->PlaceHolder = ew_RemoveHtml($this->Patente->FldCaption());

		// taller
		$this->taller->EditAttrs["class"] = "form-control";
		$this->taller->EditCustomAttributes = "";
		$this->taller->EditValue = ew_HtmlEncode($this->taller->CurrentValue);
		$this->taller->PlaceHolder = ew_RemoveHtml($this->taller->FldCaption());

		// tipo_mantenimiento
		$this->tipo_mantenimiento->EditAttrs["class"] = "form-control";
		$this->tipo_mantenimiento->EditCustomAttributes = "";
		$this->tipo_mantenimiento->EditValue = ew_HtmlEncode($this->tipo_mantenimiento->CurrentValue);
		$this->tipo_mantenimiento->PlaceHolder = ew_RemoveHtml($this->tipo_mantenimiento->FldCaption());

		// nro_hoja_mantenimiento
		$this->nro_hoja_mantenimiento->EditAttrs["class"] = "form-control";
		$this->nro_hoja_mantenimiento->EditCustomAttributes = "";
		$this->nro_hoja_mantenimiento->EditValue = ew_HtmlEncode($this->nro_hoja_mantenimiento->CurrentValue);
		$this->nro_hoja_mantenimiento->PlaceHolder = ew_RemoveHtml($this->nro_hoja_mantenimiento->FldCaption());

		// total_gasto
		$this->total_gasto->EditAttrs["class"] = "form-control";
		$this->total_gasto->EditCustomAttributes = "";
		$this->total_gasto->EditValue = ew_HtmlEncode($this->total_gasto->CurrentValue);
		$this->total_gasto->PlaceHolder = ew_RemoveHtml($this->total_gasto->FldCaption());
		if (strval($this->total_gasto->EditValue) <> "" && is_numeric($this->total_gasto->EditValue)) $this->total_gasto->EditValue = ew_FormatNumber($this->total_gasto->EditValue, -2, -1, -2, 0);

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
					if ($this->fecha_ini->Exportable) $Doc->ExportCaption($this->fecha_ini);
					if ($this->fecha_fin->Exportable) $Doc->ExportCaption($this->fecha_fin);
					if ($this->Patente->Exportable) $Doc->ExportCaption($this->Patente);
					if ($this->taller->Exportable) $Doc->ExportCaption($this->taller);
					if ($this->tipo_mantenimiento->Exportable) $Doc->ExportCaption($this->tipo_mantenimiento);
					if ($this->nro_hoja_mantenimiento->Exportable) $Doc->ExportCaption($this->nro_hoja_mantenimiento);
					if ($this->total_gasto->Exportable) $Doc->ExportCaption($this->total_gasto);
				} else {
					if ($this->fecha_ini->Exportable) $Doc->ExportCaption($this->fecha_ini);
					if ($this->fecha_fin->Exportable) $Doc->ExportCaption($this->fecha_fin);
					if ($this->Patente->Exportable) $Doc->ExportCaption($this->Patente);
					if ($this->taller->Exportable) $Doc->ExportCaption($this->taller);
					if ($this->tipo_mantenimiento->Exportable) $Doc->ExportCaption($this->tipo_mantenimiento);
					if ($this->nro_hoja_mantenimiento->Exportable) $Doc->ExportCaption($this->nro_hoja_mantenimiento);
					if ($this->total_gasto->Exportable) $Doc->ExportCaption($this->total_gasto);
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
						if ($this->fecha_ini->Exportable) $Doc->ExportField($this->fecha_ini);
						if ($this->fecha_fin->Exportable) $Doc->ExportField($this->fecha_fin);
						if ($this->Patente->Exportable) $Doc->ExportField($this->Patente);
						if ($this->taller->Exportable) $Doc->ExportField($this->taller);
						if ($this->tipo_mantenimiento->Exportable) $Doc->ExportField($this->tipo_mantenimiento);
						if ($this->nro_hoja_mantenimiento->Exportable) $Doc->ExportField($this->nro_hoja_mantenimiento);
						if ($this->total_gasto->Exportable) $Doc->ExportField($this->total_gasto);
					} else {
						if ($this->fecha_ini->Exportable) $Doc->ExportField($this->fecha_ini);
						if ($this->fecha_fin->Exportable) $Doc->ExportField($this->fecha_fin);
						if ($this->Patente->Exportable) $Doc->ExportField($this->Patente);
						if ($this->taller->Exportable) $Doc->ExportField($this->taller);
						if ($this->tipo_mantenimiento->Exportable) $Doc->ExportField($this->tipo_mantenimiento);
						if ($this->nro_hoja_mantenimiento->Exportable) $Doc->ExportField($this->nro_hoja_mantenimiento);
						if ($this->total_gasto->Exportable) $Doc->ExportField($this->total_gasto);
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
