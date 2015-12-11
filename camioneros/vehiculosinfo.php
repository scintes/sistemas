<?php

// Global variable for table object
$vehiculos = NULL;

//
// Table class for vehiculos
//
class cvehiculos extends cTable {
	var $codigo;
	var $Patente;
	var $cantidad_rueda;
	var $nombre;
	var $modelo;
	var $id_chofer;
	var $id_guarda;
	var $id_marca;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vehiculos';
		$this->TableName = 'vehiculos';
		$this->TableType = 'TABLE';
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
		$this->codigo = new cField('vehiculos', 'vehiculos', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 3, -1, FALSE, '`codigo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->codigo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codigo'] = &$this->codigo;

		// Patente
		$this->Patente = new cField('vehiculos', 'vehiculos', 'x_Patente', 'Patente', '`Patente`', '`Patente`', 200, -1, FALSE, '`Patente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Patente'] = &$this->Patente;

		// cantidad_rueda
		$this->cantidad_rueda = new cField('vehiculos', 'vehiculos', 'x_cantidad_rueda', 'cantidad_rueda', '`cantidad_rueda`', '`cantidad_rueda`', 3, -1, FALSE, '`cantidad_rueda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cantidad_rueda->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cantidad_rueda'] = &$this->cantidad_rueda;

		// nombre
		$this->nombre = new cField('vehiculos', 'vehiculos', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nombre'] = &$this->nombre;

		// modelo
		$this->modelo = new cField('vehiculos', 'vehiculos', 'x_modelo', 'modelo', '`modelo`', '`modelo`', 3, -1, FALSE, '`modelo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->modelo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['modelo'] = &$this->modelo;

		// id_chofer
		$this->id_chofer = new cField('vehiculos', 'vehiculos', 'x_id_chofer', 'id_chofer', '`id_chofer`', '`id_chofer`', 3, -1, FALSE, '`EV__id_chofer`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->id_chofer->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_chofer'] = &$this->id_chofer;

		// id_guarda
		$this->id_guarda = new cField('vehiculos', 'vehiculos', 'x_id_guarda', 'id_guarda', '`id_guarda`', '`id_guarda`', 3, -1, FALSE, '`EV__id_guarda`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->id_guarda->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_guarda'] = &$this->id_guarda;

		// id_marca
		$this->id_marca = new cField('vehiculos', 'vehiculos', 'x_id_marca', 'id_marca', '`id_marca`', '`id_marca`', 3, -1, FALSE, '`EV__id_marca`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->id_marca->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_marca'] = &$this->id_marca;
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

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`vehiculos`";
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
			"SELECT *, (SELECT CONCAT(`codigo`,'" . ew_ValueSeparator(1, $this->id_chofer) . "',`nombre`) FROM `choferes` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`codigo` = `vehiculos`.`id_chofer` LIMIT 1) AS `EV__id_chofer`, (SELECT CONCAT(`codigo`,'" . ew_ValueSeparator(1, $this->id_guarda) . "',`nombre`) FROM `choferes` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`codigo` = `vehiculos`.`id_guarda` LIMIT 1) AS `EV__id_guarda`, (SELECT CONCAT(`marca`,'" . ew_ValueSeparator(1, $this->id_marca) . "',`modelo`) FROM `marcas` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`codigo` = `vehiculos`.`id_marca` LIMIT 1) AS `EV__id_marca` FROM `vehiculos`" .
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
		if ($this->id_chofer->AdvancedSearch->SearchValue <> "" ||
			$this->id_chofer->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_chofer->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_chofer->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_guarda->AdvancedSearch->SearchValue <> "" ||
			$this->id_guarda->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_guarda->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_guarda->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_marca->AdvancedSearch->SearchValue <> "" ||
			$this->id_marca->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_marca->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_marca->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "`vehiculos`";

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
			return "vehiculoslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vehiculoslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("vehiculosview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("vehiculosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "vehiculosadd.php?" . $this->UrlParm($parm);
		else
			return "vehiculosadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("vehiculosedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("vehiculosadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vehiculosdelete.php", $this->UrlParm());
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
		$this->Patente->setDbValue($rs->fields('Patente'));
		$this->cantidad_rueda->setDbValue($rs->fields('cantidad_rueda'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->modelo->setDbValue($rs->fields('modelo'));
		$this->id_chofer->setDbValue($rs->fields('id_chofer'));
		$this->id_guarda->setDbValue($rs->fields('id_guarda'));
		$this->id_marca->setDbValue($rs->fields('id_marca'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// codigo
		// Patente
		// cantidad_rueda
		// nombre
		// modelo
		// id_chofer
		// id_guarda
		// id_marca
		// codigo

		$this->codigo->ViewValue = $this->codigo->CurrentValue;
		$this->codigo->ViewCustomAttributes = "";

		// Patente
		$this->Patente->ViewValue = $this->Patente->CurrentValue;
		$this->Patente->ViewCustomAttributes = "";

		// cantidad_rueda
		$this->cantidad_rueda->ViewValue = $this->cantidad_rueda->CurrentValue;
		$this->cantidad_rueda->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// modelo
		$this->modelo->ViewValue = $this->modelo->CurrentValue;
		$this->modelo->ViewCustomAttributes = "";

		// id_chofer
		if ($this->id_chofer->VirtualValue <> "") {
			$this->id_chofer->ViewValue = $this->id_chofer->VirtualValue;
		} else {
		if (strval($this->id_chofer->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_chofer->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `choferes`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_chofer, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_chofer->ViewValue = $rswrk->fields('DispFld');
				$this->id_chofer->ViewValue .= ew_ValueSeparator(1,$this->id_chofer) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->id_chofer->ViewValue = $this->id_chofer->CurrentValue;
			}
		} else {
			$this->id_chofer->ViewValue = NULL;
		}
		}
		$this->id_chofer->ViewCustomAttributes = "";

		// id_guarda
		if ($this->id_guarda->VirtualValue <> "") {
			$this->id_guarda->ViewValue = $this->id_guarda->VirtualValue;
		} else {
		if (strval($this->id_guarda->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_guarda->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `choferes`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_guarda, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_guarda->ViewValue = $rswrk->fields('DispFld');
				$this->id_guarda->ViewValue .= ew_ValueSeparator(1,$this->id_guarda) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->id_guarda->ViewValue = $this->id_guarda->CurrentValue;
			}
		} else {
			$this->id_guarda->ViewValue = NULL;
		}
		}
		$this->id_guarda->ViewCustomAttributes = "";

		// id_marca
		if ($this->id_marca->VirtualValue <> "") {
			$this->id_marca->ViewValue = $this->id_marca->VirtualValue;
		} else {
		if (strval($this->id_marca->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_marca->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `codigo`, `marca` AS `DispFld`, `modelo` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_marca, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `marca` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_marca->ViewValue = $rswrk->fields('DispFld');
				$this->id_marca->ViewValue .= ew_ValueSeparator(1,$this->id_marca) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->id_marca->ViewValue = $this->id_marca->CurrentValue;
			}
		} else {
			$this->id_marca->ViewValue = NULL;
		}
		}
		$this->id_marca->ViewCustomAttributes = "";

		// codigo
		$this->codigo->LinkCustomAttributes = "";
		$this->codigo->HrefValue = "";
		$this->codigo->TooltipValue = "";

		// Patente
		$this->Patente->LinkCustomAttributes = "";
		$this->Patente->HrefValue = "";
		$this->Patente->TooltipValue = "";

		// cantidad_rueda
		$this->cantidad_rueda->LinkCustomAttributes = "";
		$this->cantidad_rueda->HrefValue = "";
		$this->cantidad_rueda->TooltipValue = "";

		// nombre
		$this->nombre->LinkCustomAttributes = "";
		$this->nombre->HrefValue = "";
		$this->nombre->TooltipValue = "";

		// modelo
		$this->modelo->LinkCustomAttributes = "";
		$this->modelo->HrefValue = "";
		$this->modelo->TooltipValue = "";

		// id_chofer
		$this->id_chofer->LinkCustomAttributes = "";
		$this->id_chofer->HrefValue = "";
		$this->id_chofer->TooltipValue = "";

		// id_guarda
		$this->id_guarda->LinkCustomAttributes = "";
		$this->id_guarda->HrefValue = "";
		$this->id_guarda->TooltipValue = "";

		// id_marca
		$this->id_marca->LinkCustomAttributes = "";
		$this->id_marca->HrefValue = "";
		$this->id_marca->TooltipValue = "";

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

		// Patente
		$this->Patente->EditAttrs["class"] = "form-control";
		$this->Patente->EditCustomAttributes = "";
		$this->Patente->EditValue = ew_HtmlEncode($this->Patente->CurrentValue);
		$this->Patente->PlaceHolder = ew_RemoveHtml($this->Patente->FldCaption());

		// cantidad_rueda
		$this->cantidad_rueda->EditAttrs["class"] = "form-control";
		$this->cantidad_rueda->EditCustomAttributes = "";
		$this->cantidad_rueda->EditValue = ew_HtmlEncode($this->cantidad_rueda->CurrentValue);
		$this->cantidad_rueda->PlaceHolder = ew_RemoveHtml($this->cantidad_rueda->FldCaption());

		// nombre
		$this->nombre->EditAttrs["class"] = "form-control";
		$this->nombre->EditCustomAttributes = "";
		$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
		$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

		// modelo
		$this->modelo->EditAttrs["class"] = "form-control";
		$this->modelo->EditCustomAttributes = "";
		$this->modelo->EditValue = ew_HtmlEncode($this->modelo->CurrentValue);
		$this->modelo->PlaceHolder = ew_RemoveHtml($this->modelo->FldCaption());

		// id_chofer
		$this->id_chofer->EditAttrs["class"] = "form-control";
		$this->id_chofer->EditCustomAttributes = "";

		// id_guarda
		$this->id_guarda->EditAttrs["class"] = "form-control";
		$this->id_guarda->EditCustomAttributes = "";

		// id_marca
		$this->id_marca->EditAttrs["class"] = "form-control";
		$this->id_marca->EditCustomAttributes = "";

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
					if ($this->Patente->Exportable) $Doc->ExportCaption($this->Patente);
					if ($this->cantidad_rueda->Exportable) $Doc->ExportCaption($this->cantidad_rueda);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->modelo->Exportable) $Doc->ExportCaption($this->modelo);
					if ($this->id_chofer->Exportable) $Doc->ExportCaption($this->id_chofer);
					if ($this->id_guarda->Exportable) $Doc->ExportCaption($this->id_guarda);
					if ($this->id_marca->Exportable) $Doc->ExportCaption($this->id_marca);
				} else {
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->Patente->Exportable) $Doc->ExportCaption($this->Patente);
					if ($this->cantidad_rueda->Exportable) $Doc->ExportCaption($this->cantidad_rueda);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->modelo->Exportable) $Doc->ExportCaption($this->modelo);
					if ($this->id_chofer->Exportable) $Doc->ExportCaption($this->id_chofer);
					if ($this->id_guarda->Exportable) $Doc->ExportCaption($this->id_guarda);
					if ($this->id_marca->Exportable) $Doc->ExportCaption($this->id_marca);
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
						if ($this->Patente->Exportable) $Doc->ExportField($this->Patente);
						if ($this->cantidad_rueda->Exportable) $Doc->ExportField($this->cantidad_rueda);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->modelo->Exportable) $Doc->ExportField($this->modelo);
						if ($this->id_chofer->Exportable) $Doc->ExportField($this->id_chofer);
						if ($this->id_guarda->Exportable) $Doc->ExportField($this->id_guarda);
						if ($this->id_marca->Exportable) $Doc->ExportField($this->id_marca);
					} else {
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->Patente->Exportable) $Doc->ExportField($this->Patente);
						if ($this->cantidad_rueda->Exportable) $Doc->ExportField($this->cantidad_rueda);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->modelo->Exportable) $Doc->ExportField($this->modelo);
						if ($this->id_chofer->Exportable) $Doc->ExportField($this->id_chofer);
						if ($this->id_guarda->Exportable) $Doc->ExportField($this->id_guarda);
						if ($this->id_marca->Exportable) $Doc->ExportField($this->id_marca);
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
