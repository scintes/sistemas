<?php

// Global variable for table object
$gastos = NULL;

//
// Table class for gastos
//
class cgastos extends cTable {
	var $codigo;
	var $fecha;
	var $detalles;
	var $Importe;
	var $id_tipo_gasto;
	var $id_hoja_ruta;
	var $id_usuario;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'gastos';
		$this->TableName = 'gastos';
		$this->TableType = 'TABLE';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// codigo
		$this->codigo = new cField('gastos', 'gastos', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 3, -1, FALSE, '`codigo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->codigo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codigo'] = &$this->codigo;

		// fecha
		$this->fecha = new cField('gastos', 'gastos', 'x_fecha', 'fecha', '`fecha`', 'DATE_FORMAT(`fecha`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha'] = &$this->fecha;

		// detalles
		$this->detalles = new cField('gastos', 'gastos', 'x_detalles', 'detalles', '`detalles`', '`detalles`', 200, -1, FALSE, '`detalles`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['detalles'] = &$this->detalles;

		// Importe
		$this->Importe = new cField('gastos', 'gastos', 'x_Importe', 'Importe', '`Importe`', '`Importe`', 131, -1, FALSE, '`Importe`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Importe->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Importe'] = &$this->Importe;

		// id_tipo_gasto
		$this->id_tipo_gasto = new cField('gastos', 'gastos', 'x_id_tipo_gasto', 'id_tipo_gasto', '`id_tipo_gasto`', '`id_tipo_gasto`', 3, -1, FALSE, '`EV__id_tipo_gasto`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->id_tipo_gasto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tipo_gasto'] = &$this->id_tipo_gasto;

		// id_hoja_ruta
		$this->id_hoja_ruta = new cField('gastos', 'gastos', 'x_id_hoja_ruta', 'id_hoja_ruta', '`id_hoja_ruta`', '`id_hoja_ruta`', 3, -1, FALSE, '`EV__id_hoja_ruta`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->id_hoja_ruta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_hoja_ruta'] = &$this->id_hoja_ruta;

		// id_usuario
		$this->id_usuario = new cField('gastos', 'gastos', 'x_id_usuario', 'id_usuario', '`id_usuario`', '`id_usuario`', 3, -1, FALSE, '`id_usuario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_usuario'] = &$this->id_usuario;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
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
		if ($this->getCurrentMasterTable() == "hoja_rutas") {
			if ($this->id_hoja_ruta->getSessionValue() <> "")
				$sMasterFilter .= "`codigo`=" . ew_QuotedValue($this->id_hoja_ruta->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "tipo_gastos") {
			if ($this->id_tipo_gasto->getSessionValue() <> "")
				$sMasterFilter .= "`codigo`=" . ew_QuotedValue($this->id_tipo_gasto->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "hoja_rutas") {
			if ($this->id_hoja_ruta->getSessionValue() <> "")
				$sDetailFilter .= "`id_hoja_ruta`=" . ew_QuotedValue($this->id_hoja_ruta->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "tipo_gastos") {
			if ($this->id_tipo_gasto->getSessionValue() <> "")
				$sDetailFilter .= "`id_tipo_gasto`=" . ew_QuotedValue($this->id_tipo_gasto->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_hoja_rutas() {
		return "`codigo`=@codigo@";
	}

	// Detail filter
	function SqlDetailFilter_hoja_rutas() {
		return "`id_hoja_ruta`=@id_hoja_ruta@";
	}

	// Master filter
	function SqlMasterFilter_tipo_gastos() {
		return "`codigo`=@codigo@";
	}

	// Detail filter
	function SqlDetailFilter_tipo_gastos() {
		return "`id_tipo_gasto`=@id_tipo_gasto@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`gastos`";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT DISTINCT CONCAT(`codigo`,'" . ew_ValueSeparator(1, $this->id_tipo_gasto) . "',`tipo_gasto`) FROM `tipo_gastos` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`codigo` = `gastos`.`id_tipo_gasto` LIMIT 1) AS `EV__id_tipo_gasto`, (SELECT CONCAT(`codigo`,'" . ew_ValueSeparator(1, $this->id_hoja_ruta) . "',`fecha_ini`,'" . ew_ValueSeparator(2, $this->id_hoja_ruta) . "',`Origen`) FROM `hoja_rutas` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`codigo` = `gastos`.`id_hoja_ruta` LIMIT 1) AS `EV__id_hoja_ruta` FROM `gastos`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
    	return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
    	$this->_SqlSelectList = $v;
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
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->id_tipo_gasto->AdvancedSearch->SearchValue <> "" ||
			$this->id_tipo_gasto->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_tipo_gasto->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_tipo_gasto->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_hoja_ruta->AdvancedSearch->SearchValue <> "" ||
			$this->id_hoja_ruta->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_hoja_ruta->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_hoja_ruta->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
	var $UpdateTable = "`gastos`";

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
			return "gastoslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "gastoslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("gastosview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("gastosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "gastosadd.php?" . $this->UrlParm($parm);
		else
			return "gastosadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("gastosedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("gastosadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("gastosdelete.php", $this->UrlParm());
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
		$this->Importe->setDbValue($rs->fields('Importe'));
		$this->id_tipo_gasto->setDbValue($rs->fields('id_tipo_gasto'));
		$this->id_hoja_ruta->setDbValue($rs->fields('id_hoja_ruta'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
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
		// Importe
		// id_tipo_gasto
		// id_hoja_ruta
		// id_usuario

		$this->id_usuario->CellCssStyle = "white-space: nowrap;";

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

		// Importe
		$this->Importe->ViewValue = $this->Importe->CurrentValue;
		$this->Importe->ViewValue = ew_FormatCurrency($this->Importe->ViewValue, 2, 0, 0, -1);
		$this->Importe->ViewCustomAttributes = "";

		// id_tipo_gasto
		if ($this->id_tipo_gasto->VirtualValue <> "") {
			$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->VirtualValue;
		} else {
		if (strval($this->id_tipo_gasto->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
		$sWhereWrk = "";
		$lookuptblfilter = "`clase`='R'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_tipo_gasto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_tipo_gasto->ViewValue = $rswrk->fields('DispFld');
				$this->id_tipo_gasto->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_gasto) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->CurrentValue;
			}
		} else {
			$this->id_tipo_gasto->ViewValue = NULL;
		}
		}
		$this->id_tipo_gasto->ViewCustomAttributes = "";

		// id_hoja_ruta
		if ($this->id_hoja_ruta->VirtualValue <> "") {
			$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->VirtualValue;
		} else {
			$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
		if (strval($this->id_hoja_ruta->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_hoja_ruta->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hoja_rutas`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_hoja_ruta, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `codigo` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_hoja_ruta->ViewValue = $rswrk->fields('DispFld');
				$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(1,$this->id_hoja_ruta) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
				$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(2,$this->id_hoja_ruta) . $rswrk->fields('Disp3Fld');
				$rswrk->Close();
			} else {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
			}
		} else {
			$this->id_hoja_ruta->ViewValue = NULL;
		}
		}
		$this->id_hoja_ruta->ViewCustomAttributes = "";

		// id_usuario
		$this->id_usuario->ViewValue = $this->id_usuario->CurrentValue;
		$this->id_usuario->ViewCustomAttributes = "";

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

		// Importe
		$this->Importe->LinkCustomAttributes = "";
		$this->Importe->HrefValue = "";
		$this->Importe->TooltipValue = "";

		// id_tipo_gasto
		$this->id_tipo_gasto->LinkCustomAttributes = "";
		$this->id_tipo_gasto->HrefValue = "";
		$this->id_tipo_gasto->TooltipValue = "";

		// id_hoja_ruta
		$this->id_hoja_ruta->LinkCustomAttributes = "";
		$this->id_hoja_ruta->HrefValue = "";
		$this->id_hoja_ruta->TooltipValue = "";

		// id_usuario
		$this->id_usuario->LinkCustomAttributes = "";
		$this->id_usuario->HrefValue = "";
		$this->id_usuario->TooltipValue = "";

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

		// Importe
		$this->Importe->EditAttrs["class"] = "form-control";
		$this->Importe->EditCustomAttributes = "";
		$this->Importe->EditValue = ew_HtmlEncode($this->Importe->CurrentValue);
		$this->Importe->PlaceHolder = ew_RemoveHtml($this->Importe->FldCaption());
		if (strval($this->Importe->EditValue) <> "" && is_numeric($this->Importe->EditValue)) $this->Importe->EditValue = ew_FormatNumber($this->Importe->EditValue, -2, 0, 0, -1);

		// id_tipo_gasto
		$this->id_tipo_gasto->EditAttrs["class"] = "form-control";
		$this->id_tipo_gasto->EditCustomAttributes = "";
		if ($this->id_tipo_gasto->getSessionValue() <> "") {
			$this->id_tipo_gasto->CurrentValue = $this->id_tipo_gasto->getSessionValue();
		if ($this->id_tipo_gasto->VirtualValue <> "") {
			$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->VirtualValue;
		} else {
		if (strval($this->id_tipo_gasto->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_gasto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
		$sWhereWrk = "";
		$lookuptblfilter = "`clase`='R'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_tipo_gasto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_tipo_gasto->ViewValue = $rswrk->fields('DispFld');
				$this->id_tipo_gasto->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_gasto) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->id_tipo_gasto->ViewValue = $this->id_tipo_gasto->CurrentValue;
			}
		} else {
			$this->id_tipo_gasto->ViewValue = NULL;
		}
		}
		$this->id_tipo_gasto->ViewCustomAttributes = "";
		} else {
		}

		// id_hoja_ruta
		$this->id_hoja_ruta->EditAttrs["class"] = "form-control";
		$this->id_hoja_ruta->EditCustomAttributes = "";
		if ($this->id_hoja_ruta->getSessionValue() <> "") {
			$this->id_hoja_ruta->CurrentValue = $this->id_hoja_ruta->getSessionValue();
		if ($this->id_hoja_ruta->VirtualValue <> "") {
			$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->VirtualValue;
		} else {
			$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
		if (strval($this->id_hoja_ruta->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_hoja_ruta->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hoja_rutas`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_hoja_ruta, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `codigo` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_hoja_ruta->ViewValue = $rswrk->fields('DispFld');
				$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(1,$this->id_hoja_ruta) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
				$this->id_hoja_ruta->ViewValue .= ew_ValueSeparator(2,$this->id_hoja_ruta) . $rswrk->fields('Disp3Fld');
				$rswrk->Close();
			} else {
				$this->id_hoja_ruta->ViewValue = $this->id_hoja_ruta->CurrentValue;
			}
		} else {
			$this->id_hoja_ruta->ViewValue = NULL;
		}
		}
		$this->id_hoja_ruta->ViewCustomAttributes = "";
		} else {
		$this->id_hoja_ruta->EditValue = ew_HtmlEncode($this->id_hoja_ruta->CurrentValue);
		$this->id_hoja_ruta->PlaceHolder = ew_RemoveHtml($this->id_hoja_ruta->FldCaption());
		}

		// id_usuario
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
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->detalles->Exportable) $Doc->ExportCaption($this->detalles);
					if ($this->Importe->Exportable) $Doc->ExportCaption($this->Importe);
					if ($this->id_tipo_gasto->Exportable) $Doc->ExportCaption($this->id_tipo_gasto);
					if ($this->id_hoja_ruta->Exportable) $Doc->ExportCaption($this->id_hoja_ruta);
				} else {
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->detalles->Exportable) $Doc->ExportCaption($this->detalles);
					if ($this->Importe->Exportable) $Doc->ExportCaption($this->Importe);
					if ($this->id_tipo_gasto->Exportable) $Doc->ExportCaption($this->id_tipo_gasto);
					if ($this->id_hoja_ruta->Exportable) $Doc->ExportCaption($this->id_hoja_ruta);
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
						if ($this->Importe->Exportable) $Doc->ExportField($this->Importe);
						if ($this->id_tipo_gasto->Exportable) $Doc->ExportField($this->id_tipo_gasto);
						if ($this->id_hoja_ruta->Exportable) $Doc->ExportField($this->id_hoja_ruta);
					} else {
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->detalles->Exportable) $Doc->ExportField($this->detalles);
						if ($this->Importe->Exportable) $Doc->ExportField($this->Importe);
						if ($this->id_tipo_gasto->Exportable) $Doc->ExportField($this->id_tipo_gasto);
						if ($this->id_hoja_ruta->Exportable) $Doc->ExportField($this->id_hoja_ruta);
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

		// Call User ID Filtering event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $conn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `gastos`";
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

	// Add master User ID filter
	function AddMasterUserIDFilter($sFilter, $sCurrentMasterTable) {
		$sFilterWrk = $sFilter;
		if ($sCurrentMasterTable == "hoja_rutas") {
			$sFilterWrk = $GLOBALS["hoja_rutas"]->AddUserIDFilter($sFilterWrk);
		}
		return $sFilterWrk;
	}

	// Add detail User ID filter
	function AddDetailUserIDFilter($sFilter, $sCurrentMasterTable) {
		$sFilterWrk = $sFilter;
		if ($sCurrentMasterTable == "hoja_rutas") {
			$mastertable = $GLOBALS["hoja_rutas"];
			if (!$mastertable->UserIDAllow()) {
				$sSubqueryWrk = $mastertable->GetUserIDSubquery($this->id_hoja_ruta, $mastertable->codigo);
				ew_AddFilter($sFilterWrk, $sSubqueryWrk);
			}
		}
		return $sFilterWrk;
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
