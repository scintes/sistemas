<?php

// Global variable for table object
$v_listado_totales_por_hoja_ruta = NULL;

//
// Table class for v_listado_totales_por_hoja_ruta
//
class cv_listado_totales_por_hoja_ruta extends cTable {
	var $codigo;
	var $responsable;
	var $Patente;
	var $kg_carga;
	var $tarifa;
	var $sub_total;
	var $porcentaje;
	var $comision_chofer;
	var $adelanto;
	var $total;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'v_listado_totales_por_hoja_ruta';
		$this->TableName = 'v_listado_totales_por_hoja_ruta';
		$this->TableType = 'VIEW';
		$this->ExportAll = FALSE;
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

		// codigo
		$this->codigo = new cField('v_listado_totales_por_hoja_ruta', 'v_listado_totales_por_hoja_ruta', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 3, -1, FALSE, '`codigo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->codigo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codigo'] = &$this->codigo;

		// responsable
		$this->responsable = new cField('v_listado_totales_por_hoja_ruta', 'v_listado_totales_por_hoja_ruta', 'x_responsable', 'responsable', '`responsable`', '`responsable`', 200, -1, FALSE, '`responsable`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['responsable'] = &$this->responsable;

		// Patente
		$this->Patente = new cField('v_listado_totales_por_hoja_ruta', 'v_listado_totales_por_hoja_ruta', 'x_Patente', 'Patente', '`Patente`', '`Patente`', 200, -1, FALSE, '`Patente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Patente'] = &$this->Patente;

		// kg_carga
		$this->kg_carga = new cField('v_listado_totales_por_hoja_ruta', 'v_listado_totales_por_hoja_ruta', 'x_kg_carga', 'kg_carga', '`kg_carga`', '`kg_carga`', 3, -1, FALSE, '`kg_carga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->kg_carga->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['kg_carga'] = &$this->kg_carga;

		// tarifa
		$this->tarifa = new cField('v_listado_totales_por_hoja_ruta', 'v_listado_totales_por_hoja_ruta', 'x_tarifa', 'tarifa', '`tarifa`', '`tarifa`', 131, -1, FALSE, '`tarifa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tarifa->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['tarifa'] = &$this->tarifa;

		// sub_total
		$this->sub_total = new cField('v_listado_totales_por_hoja_ruta', 'v_listado_totales_por_hoja_ruta', 'x_sub_total', 'sub_total', '`sub_total`', '`sub_total`', 131, -1, FALSE, '`sub_total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->sub_total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['sub_total'] = &$this->sub_total;

		// porcentaje
		$this->porcentaje = new cField('v_listado_totales_por_hoja_ruta', 'v_listado_totales_por_hoja_ruta', 'x_porcentaje', 'porcentaje', '`porcentaje`', '`porcentaje`', 131, -1, FALSE, '`porcentaje`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->porcentaje->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['porcentaje'] = &$this->porcentaje;

		// comision_chofer
		$this->comision_chofer = new cField('v_listado_totales_por_hoja_ruta', 'v_listado_totales_por_hoja_ruta', 'x_comision_chofer', 'comision_chofer', '`comision_chofer`', '`comision_chofer`', 131, -1, FALSE, '`comision_chofer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->comision_chofer->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['comision_chofer'] = &$this->comision_chofer;

		// adelanto
		$this->adelanto = new cField('v_listado_totales_por_hoja_ruta', 'v_listado_totales_por_hoja_ruta', 'x_adelanto', 'adelanto', '`adelanto`', '`adelanto`', 131, -1, FALSE, '`adelanto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->adelanto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['adelanto'] = &$this->adelanto;

		// total
		$this->total = new cField('v_listado_totales_por_hoja_ruta', 'v_listado_totales_por_hoja_ruta', 'x_total', 'total', '`total`', '`total`', 131, -1, FALSE, '`total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total'] = &$this->total;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_listado_totales_por_hoja_ruta`";
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
	var $UpdateTable = "`v_listado_totales_por_hoja_ruta`";

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
			return "v_listado_totales_por_hoja_rutalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "v_listado_totales_por_hoja_rutalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("v_listado_totales_por_hoja_rutaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("v_listado_totales_por_hoja_rutaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "v_listado_totales_por_hoja_rutaadd.php?" . $this->UrlParm($parm);
		else
			return "v_listado_totales_por_hoja_rutaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("v_listado_totales_por_hoja_rutaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("v_listado_totales_por_hoja_rutaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("v_listado_totales_por_hoja_rutadelete.php", $this->UrlParm());
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
		$this->responsable->setDbValue($rs->fields('responsable'));
		$this->Patente->setDbValue($rs->fields('Patente'));
		$this->kg_carga->setDbValue($rs->fields('kg_carga'));
		$this->tarifa->setDbValue($rs->fields('tarifa'));
		$this->sub_total->setDbValue($rs->fields('sub_total'));
		$this->porcentaje->setDbValue($rs->fields('porcentaje'));
		$this->comision_chofer->setDbValue($rs->fields('comision_chofer'));
		$this->adelanto->setDbValue($rs->fields('adelanto'));
		$this->total->setDbValue($rs->fields('total'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// codigo
		// responsable
		// Patente
		// kg_carga
		// tarifa
		// sub_total
		// porcentaje
		// comision_chofer
		// adelanto
		// total
		// codigo

		$this->codigo->ViewValue = $this->codigo->CurrentValue;
		$this->codigo->ViewCustomAttributes = "";

		// responsable
		$this->responsable->ViewValue = $this->responsable->CurrentValue;
		$this->responsable->ViewCustomAttributes = "";

		// Patente
		$this->Patente->ViewValue = $this->Patente->CurrentValue;
		$this->Patente->ViewCustomAttributes = "";

		// kg_carga
		$this->kg_carga->ViewValue = $this->kg_carga->CurrentValue;
		$this->kg_carga->ViewValue = ew_FormatNumber($this->kg_carga->ViewValue, 2, -2, -2, -2);
		$this->kg_carga->ViewCustomAttributes = "";

		// tarifa
		$this->tarifa->ViewValue = $this->tarifa->CurrentValue;
		$this->tarifa->ViewValue = ew_FormatCurrency($this->tarifa->ViewValue, 2, -2, -2, -2);
		$this->tarifa->ViewCustomAttributes = "";

		// sub_total
		$this->sub_total->ViewValue = $this->sub_total->CurrentValue;
		$this->sub_total->ViewValue = ew_FormatCurrency($this->sub_total->ViewValue, 2, -2, -2, -2);
		$this->sub_total->ViewCustomAttributes = "";

		// porcentaje
		$this->porcentaje->ViewValue = $this->porcentaje->CurrentValue;
		$this->porcentaje->ViewValue = ew_FormatPercent($this->porcentaje->ViewValue, 2, -2, -2, -2);
		$this->porcentaje->ViewCustomAttributes = "";

		// comision_chofer
		$this->comision_chofer->ViewValue = $this->comision_chofer->CurrentValue;
		$this->comision_chofer->ViewValue = ew_FormatCurrency($this->comision_chofer->ViewValue, 2, -2, -1, -2);
		$this->comision_chofer->ViewCustomAttributes = "";

		// adelanto
		$this->adelanto->ViewValue = $this->adelanto->CurrentValue;
		$this->adelanto->ViewValue = ew_FormatCurrency($this->adelanto->ViewValue, 2, -2, -2, -2);
		$this->adelanto->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewValue = ew_FormatCurrency($this->total->ViewValue, 2, -2, -2, -2);
		$this->total->ViewCustomAttributes = "";

		// codigo
		$this->codigo->LinkCustomAttributes = "";
		$this->codigo->HrefValue = "";
		$this->codigo->TooltipValue = "";

		// responsable
		$this->responsable->LinkCustomAttributes = "";
		$this->responsable->HrefValue = "";
		$this->responsable->TooltipValue = "";

		// Patente
		$this->Patente->LinkCustomAttributes = "";
		$this->Patente->HrefValue = "";
		$this->Patente->TooltipValue = "";

		// kg_carga
		$this->kg_carga->LinkCustomAttributes = "";
		$this->kg_carga->HrefValue = "";
		$this->kg_carga->TooltipValue = "";

		// tarifa
		$this->tarifa->LinkCustomAttributes = "";
		$this->tarifa->HrefValue = "";
		$this->tarifa->TooltipValue = "";

		// sub_total
		$this->sub_total->LinkCustomAttributes = "";
		$this->sub_total->HrefValue = "";
		$this->sub_total->TooltipValue = "";

		// porcentaje
		$this->porcentaje->LinkCustomAttributes = "";
		$this->porcentaje->HrefValue = "";
		$this->porcentaje->TooltipValue = "";

		// comision_chofer
		$this->comision_chofer->LinkCustomAttributes = "";
		$this->comision_chofer->HrefValue = "";
		$this->comision_chofer->TooltipValue = "";

		// adelanto
		$this->adelanto->LinkCustomAttributes = "";
		$this->adelanto->HrefValue = "";
		$this->adelanto->TooltipValue = "";

		// total
		$this->total->LinkCustomAttributes = "";
		$this->total->HrefValue = "";
		$this->total->TooltipValue = "";

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

		// responsable
		$this->responsable->EditAttrs["class"] = "form-control";
		$this->responsable->EditCustomAttributes = "";
		$this->responsable->EditValue = ew_HtmlEncode($this->responsable->CurrentValue);
		$this->responsable->PlaceHolder = ew_RemoveHtml($this->responsable->FldCaption());

		// Patente
		$this->Patente->EditAttrs["class"] = "form-control";
		$this->Patente->EditCustomAttributes = "";
		$this->Patente->EditValue = ew_HtmlEncode($this->Patente->CurrentValue);
		$this->Patente->PlaceHolder = ew_RemoveHtml($this->Patente->FldCaption());

		// kg_carga
		$this->kg_carga->EditAttrs["class"] = "form-control";
		$this->kg_carga->EditCustomAttributes = "";
		$this->kg_carga->EditValue = ew_HtmlEncode($this->kg_carga->CurrentValue);
		$this->kg_carga->PlaceHolder = ew_RemoveHtml($this->kg_carga->FldCaption());

		// tarifa
		$this->tarifa->EditAttrs["class"] = "form-control";
		$this->tarifa->EditCustomAttributes = "";
		$this->tarifa->EditValue = ew_HtmlEncode($this->tarifa->CurrentValue);
		$this->tarifa->PlaceHolder = ew_RemoveHtml($this->tarifa->FldCaption());
		if (strval($this->tarifa->EditValue) <> "" && is_numeric($this->tarifa->EditValue)) $this->tarifa->EditValue = ew_FormatNumber($this->tarifa->EditValue, -2, -2, -2, -2);

		// sub_total
		$this->sub_total->EditAttrs["class"] = "form-control";
		$this->sub_total->EditCustomAttributes = "";
		$this->sub_total->EditValue = ew_HtmlEncode($this->sub_total->CurrentValue);
		$this->sub_total->PlaceHolder = ew_RemoveHtml($this->sub_total->FldCaption());
		if (strval($this->sub_total->EditValue) <> "" && is_numeric($this->sub_total->EditValue)) $this->sub_total->EditValue = ew_FormatNumber($this->sub_total->EditValue, -2, -2, -2, -2);

		// porcentaje
		$this->porcentaje->EditAttrs["class"] = "form-control";
		$this->porcentaje->EditCustomAttributes = "";
		$this->porcentaje->EditValue = ew_HtmlEncode($this->porcentaje->CurrentValue);
		$this->porcentaje->PlaceHolder = ew_RemoveHtml($this->porcentaje->FldCaption());
		if (strval($this->porcentaje->EditValue) <> "" && is_numeric($this->porcentaje->EditValue)) $this->porcentaje->EditValue = ew_FormatNumber($this->porcentaje->EditValue, -2, -1, -2, 0);

		// comision_chofer
		$this->comision_chofer->EditAttrs["class"] = "form-control";
		$this->comision_chofer->EditCustomAttributes = "";
		$this->comision_chofer->EditValue = ew_HtmlEncode($this->comision_chofer->CurrentValue);
		$this->comision_chofer->PlaceHolder = ew_RemoveHtml($this->comision_chofer->FldCaption());
		if (strval($this->comision_chofer->EditValue) <> "" && is_numeric($this->comision_chofer->EditValue)) $this->comision_chofer->EditValue = ew_FormatNumber($this->comision_chofer->EditValue, -2, -2, -1, -2);

		// adelanto
		$this->adelanto->EditAttrs["class"] = "form-control";
		$this->adelanto->EditCustomAttributes = "";
		$this->adelanto->EditValue = ew_HtmlEncode($this->adelanto->CurrentValue);
		$this->adelanto->PlaceHolder = ew_RemoveHtml($this->adelanto->FldCaption());
		if (strval($this->adelanto->EditValue) <> "" && is_numeric($this->adelanto->EditValue)) $this->adelanto->EditValue = ew_FormatNumber($this->adelanto->EditValue, -2, -2, -2, -2);

		// total
		$this->total->EditAttrs["class"] = "form-control";
		$this->total->EditCustomAttributes = "";
		$this->total->EditValue = ew_HtmlEncode($this->total->CurrentValue);
		$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
		if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -2, -2, -2);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->sub_total->CurrentValue))
				$this->sub_total->Total += $this->sub_total->CurrentValue; // Accumulate total
			if (is_numeric($this->comision_chofer->CurrentValue))
				$this->comision_chofer->Total += $this->comision_chofer->CurrentValue; // Accumulate total
			if (is_numeric($this->total->CurrentValue))
				$this->total->Total += $this->total->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->sub_total->CurrentValue = $this->sub_total->Total;
			$this->sub_total->ViewValue = $this->sub_total->CurrentValue;
			$this->sub_total->ViewValue = ew_FormatCurrency($this->sub_total->ViewValue, 2, -2, -2, -2);
			$this->sub_total->ViewCustomAttributes = "";
			$this->sub_total->HrefValue = ""; // Clear href value
			$this->comision_chofer->CurrentValue = $this->comision_chofer->Total;
			$this->comision_chofer->ViewValue = $this->comision_chofer->CurrentValue;
			$this->comision_chofer->ViewValue = ew_FormatCurrency($this->comision_chofer->ViewValue, 2, -2, -1, -2);
			$this->comision_chofer->ViewCustomAttributes = "";
			$this->comision_chofer->HrefValue = ""; // Clear href value
			$this->total->CurrentValue = $this->total->Total;
			$this->total->ViewValue = $this->total->CurrentValue;
			$this->total->ViewValue = ew_FormatCurrency($this->total->ViewValue, 2, -2, -2, -2);
			$this->total->ViewCustomAttributes = "";
			$this->total->HrefValue = ""; // Clear href value

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
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->responsable->Exportable) $Doc->ExportCaption($this->responsable);
					if ($this->Patente->Exportable) $Doc->ExportCaption($this->Patente);
					if ($this->kg_carga->Exportable) $Doc->ExportCaption($this->kg_carga);
					if ($this->tarifa->Exportable) $Doc->ExportCaption($this->tarifa);
					if ($this->sub_total->Exportable) $Doc->ExportCaption($this->sub_total);
					if ($this->porcentaje->Exportable) $Doc->ExportCaption($this->porcentaje);
					if ($this->comision_chofer->Exportable) $Doc->ExportCaption($this->comision_chofer);
					if ($this->adelanto->Exportable) $Doc->ExportCaption($this->adelanto);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
				} else {
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->responsable->Exportable) $Doc->ExportCaption($this->responsable);
					if ($this->Patente->Exportable) $Doc->ExportCaption($this->Patente);
					if ($this->kg_carga->Exportable) $Doc->ExportCaption($this->kg_carga);
					if ($this->tarifa->Exportable) $Doc->ExportCaption($this->tarifa);
					if ($this->sub_total->Exportable) $Doc->ExportCaption($this->sub_total);
					if ($this->porcentaje->Exportable) $Doc->ExportCaption($this->porcentaje);
					if ($this->comision_chofer->Exportable) $Doc->ExportCaption($this->comision_chofer);
					if ($this->adelanto->Exportable) $Doc->ExportCaption($this->adelanto);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
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
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->responsable->Exportable) $Doc->ExportField($this->responsable);
						if ($this->Patente->Exportable) $Doc->ExportField($this->Patente);
						if ($this->kg_carga->Exportable) $Doc->ExportField($this->kg_carga);
						if ($this->tarifa->Exportable) $Doc->ExportField($this->tarifa);
						if ($this->sub_total->Exportable) $Doc->ExportField($this->sub_total);
						if ($this->porcentaje->Exportable) $Doc->ExportField($this->porcentaje);
						if ($this->comision_chofer->Exportable) $Doc->ExportField($this->comision_chofer);
						if ($this->adelanto->Exportable) $Doc->ExportField($this->adelanto);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
					} else {
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->responsable->Exportable) $Doc->ExportField($this->responsable);
						if ($this->Patente->Exportable) $Doc->ExportField($this->Patente);
						if ($this->kg_carga->Exportable) $Doc->ExportField($this->kg_carga);
						if ($this->tarifa->Exportable) $Doc->ExportField($this->tarifa);
						if ($this->sub_total->Exportable) $Doc->ExportField($this->sub_total);
						if ($this->porcentaje->Exportable) $Doc->ExportField($this->porcentaje);
						if ($this->comision_chofer->Exportable) $Doc->ExportField($this->comision_chofer);
						if ($this->adelanto->Exportable) $Doc->ExportField($this->adelanto);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
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
				$Doc->ExportAggregate($this->codigo, '');
				$Doc->ExportAggregate($this->responsable, '');
				$Doc->ExportAggregate($this->Patente, '');
				$Doc->ExportAggregate($this->kg_carga, '');
				$Doc->ExportAggregate($this->tarifa, '');
				$Doc->ExportAggregate($this->sub_total, 'TOTAL');
				$Doc->ExportAggregate($this->porcentaje, '');
				$Doc->ExportAggregate($this->comision_chofer, 'TOTAL');
				$Doc->ExportAggregate($this->adelanto, '');
				$Doc->ExportAggregate($this->total, 'TOTAL');
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
