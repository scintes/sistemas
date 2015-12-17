<?php

// Global variable for table object
$seguimiento_tramites = NULL;

//
// Table class for seguimiento_tramites
//
class cseguimiento_tramites extends cTable {
	var $id_tramite;
	var $fecha;
	var $hora;
	var $titulo;
	var $descripcion;
	var $id_usuario;
	var $archivo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'seguimiento_tramites';
		$this->TableName = 'seguimiento_tramites';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
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

		// id_tramite
		$this->id_tramite = new cField('seguimiento_tramites', 'seguimiento_tramites', 'x_id_tramite', 'id_tramite', '`id_tramite`', '`id_tramite`', 3, -1, FALSE, '`EV__id_tramite`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->id_tramite->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tramite'] = &$this->id_tramite;

		// fecha
		$this->fecha = new cField('seguimiento_tramites', 'seguimiento_tramites', 'x_fecha', 'fecha', '`fecha`', 'DATE_FORMAT(`fecha`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha'] = &$this->fecha;

		// hora
		$this->hora = new cField('seguimiento_tramites', 'seguimiento_tramites', 'x_hora', 'hora', '`hora`', 'DATE_FORMAT(`hora`, \'%d/%m/%Y\')', 134, -1, FALSE, '`hora`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->hora->FldDefaultErrMsg = $Language->Phrase("IncorrectTime");
		$this->fields['hora'] = &$this->hora;

		// titulo
		$this->titulo = new cField('seguimiento_tramites', 'seguimiento_tramites', 'x_titulo', 'titulo', '`titulo`', '`titulo`', 200, -1, FALSE, '`titulo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo'] = &$this->titulo;

		// descripcion
		$this->descripcion = new cField('seguimiento_tramites', 'seguimiento_tramites', 'x_descripcion', 'descripcion', '`descripcion`', '`descripcion`', 201, -1, FALSE, '`descripcion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion'] = &$this->descripcion;

		// id_usuario
		$this->id_usuario = new cField('seguimiento_tramites', 'seguimiento_tramites', 'x_id_usuario', 'id_usuario', '`id_usuario`', '`id_usuario`', 200, -1, FALSE, '`id_usuario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['id_usuario'] = &$this->id_usuario;

		// archivo
		$this->archivo = new cField('seguimiento_tramites', 'seguimiento_tramites', 'x_archivo', 'archivo', '`archivo`', '`archivo`', 200, -1, TRUE, '`archivo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->archivo->UploadMultiple = TRUE;
		$this->fields['archivo'] = &$this->archivo;
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
		if ($this->getCurrentMasterTable() == "tramites") {
			if ($this->id_tramite->getSessionValue() <> "")
				$sMasterFilter .= "`codigo`=" . ew_QuotedValue($this->id_tramite->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "tramites") {
			if ($this->id_tramite->getSessionValue() <> "")
				$sDetailFilter .= "`id_tramite`=" . ew_QuotedValue($this->id_tramite->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_tramites() {
		return "`codigo`=@codigo@";
	}

	// Detail filter
	function SqlDetailFilter_tramites() {
		return "`id_tramite`=@id_tramite@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`seguimiento_tramites`";
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
			"SELECT *, (SELECT CONCAT(`codigo`,'" . ew_ValueSeparator(1, $this->id_tramite) . "',`fecha`,'" . ew_ValueSeparator(2, $this->id_tramite) . "',`Titulo`) FROM `tramites` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`codigo` = `seguimiento_tramites`.`id_tramite` LIMIT 1) AS `EV__id_tramite` FROM `seguimiento_tramites`" .
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
		if ($this->id_tramite->AdvancedSearch->SearchValue <> "" ||
			$this->id_tramite->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_tramite->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_tramite->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "`seguimiento_tramites`";

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
			if (array_key_exists('id_tramite', $rs))
				ew_AddFilter($where, ew_QuotedName('id_tramite') . '=' . ew_QuotedValue($rs['id_tramite'], $this->id_tramite->FldDataType));
			if (array_key_exists('fecha', $rs))
				ew_AddFilter($where, ew_QuotedName('fecha') . '=' . ew_QuotedValue($rs['fecha'], $this->fecha->FldDataType));
			if (array_key_exists('hora', $rs))
				ew_AddFilter($where, ew_QuotedName('hora') . '=' . ew_QuotedValue($rs['hora'], $this->hora->FldDataType));
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
		return "`id_tramite` = @id_tramite@ AND `fecha` = '@fecha@' AND `hora` = '@hora@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_tramite->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_tramite@", ew_AdjustSql($this->id_tramite->CurrentValue), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@fecha@", ew_AdjustSql(ew_UnFormatDateTime($this->fecha->CurrentValue,7)), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@hora@", ew_AdjustSql($this->hora->CurrentValue), $sKeyFilter); // Replace key value
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
			return "cciag_seguimiento_tramiteslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "cciag_seguimiento_tramiteslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("cciag_seguimiento_tramitesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("cciag_seguimiento_tramitesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "cciag_seguimiento_tramitesadd.php?" . $this->UrlParm($parm);
		else
			return "cciag_seguimiento_tramitesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("cciag_seguimiento_tramitesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("cciag_seguimiento_tramitesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("cciag_seguimiento_tramitesdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_tramite->CurrentValue)) {
			$sUrl .= "id_tramite=" . urlencode($this->id_tramite->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->fecha->CurrentValue)) {
			$sUrl .= "&fecha=" . urlencode($this->fecha->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->hora->CurrentValue)) {
			$sUrl .= "&hora=" . urlencode($this->hora->CurrentValue);
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
			$arKey[] = @$_GET["id_tramite"]; // id_tramite
			$arKey[] = @$_GET["fecha"]; // fecha
			$arKey[] = @$_GET["hora"]; // hora
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 3)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // id_tramite
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
			$this->id_tramite->CurrentValue = $key[0];
			$this->fecha->CurrentValue = $key[1];
			$this->hora->CurrentValue = $key[2];
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
		$this->id_tramite->setDbValue($rs->fields('id_tramite'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->hora->setDbValue($rs->fields('hora'));
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		$this->archivo->Upload->DbValue = $rs->fields('archivo');
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_tramite
		// fecha
		// hora
		// titulo
		// descripcion
		// id_usuario

		$this->id_usuario->CellCssStyle = "white-space: nowrap;";

		// archivo
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

		// id_usuario
		$this->id_usuario->ViewValue = $this->id_usuario->CurrentValue;
		$this->id_usuario->ViewCustomAttributes = "";

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

		// id_usuario
		$this->id_usuario->LinkCustomAttributes = "";
		$this->id_usuario->HrefValue = "";
		$this->id_usuario->TooltipValue = "";

		// archivo
		$this->archivo->LinkCustomAttributes = "";
		$this->archivo->HrefValue = "";
		$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;
		$this->archivo->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id_tramite
		$this->id_tramite->EditAttrs["class"] = "form-control";
		$this->id_tramite->EditCustomAttributes = "";
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
				$this->id_tramite->EditValue = $rswrk->fields('DispFld');
				$this->id_tramite->EditValue .= ew_ValueSeparator(1,$this->id_tramite) . ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
				$this->id_tramite->EditValue .= ew_ValueSeparator(2,$this->id_tramite) . $rswrk->fields('Disp3Fld');
				$rswrk->Close();
			} else {
				$this->id_tramite->EditValue = $this->id_tramite->CurrentValue;
			}
		} else {
			$this->id_tramite->EditValue = NULL;
		}
		}
		$this->id_tramite->ViewCustomAttributes = "";

		// fecha
		// hora
		// titulo

		$this->titulo->EditAttrs["class"] = "form-control";
		$this->titulo->EditCustomAttributes = "";
		$this->titulo->EditValue = ew_HtmlEncode($this->titulo->CurrentValue);
		$this->titulo->PlaceHolder = ew_RemoveHtml($this->titulo->FldCaption());

		// descripcion
		$this->descripcion->EditAttrs["class"] = "form-control";
		$this->descripcion->EditCustomAttributes = "";
		$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);
		$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

		// id_usuario
		// archivo

		$this->archivo->EditAttrs["class"] = "form-control";
		$this->archivo->EditCustomAttributes = "";
		if (!ew_Empty($this->archivo->Upload->DbValue)) {
			$this->archivo->EditValue = $this->archivo->Upload->DbValue;
		} else {
			$this->archivo->EditValue = "";
		}
		if (!ew_Empty($this->archivo->CurrentValue))
			$this->archivo->Upload->FileName = $this->archivo->CurrentValue;

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
					if ($this->id_tramite->Exportable) $Doc->ExportCaption($this->id_tramite);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->hora->Exportable) $Doc->ExportCaption($this->hora);
					if ($this->titulo->Exportable) $Doc->ExportCaption($this->titulo);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->archivo->Exportable) $Doc->ExportCaption($this->archivo);
				} else {
					if ($this->id_tramite->Exportable) $Doc->ExportCaption($this->id_tramite);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->hora->Exportable) $Doc->ExportCaption($this->hora);
					if ($this->titulo->Exportable) $Doc->ExportCaption($this->titulo);
					if ($this->archivo->Exportable) $Doc->ExportCaption($this->archivo);
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
						if ($this->id_tramite->Exportable) $Doc->ExportField($this->id_tramite);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->hora->Exportable) $Doc->ExportField($this->hora);
						if ($this->titulo->Exportable) $Doc->ExportField($this->titulo);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->archivo->Exportable) $Doc->ExportField($this->archivo);
					} else {
						if ($this->id_tramite->Exportable) $Doc->ExportField($this->id_tramite);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->hora->Exportable) $Doc->ExportField($this->hora);
						if ($this->titulo->Exportable) $Doc->ExportField($this->titulo);
						if ($this->archivo->Exportable) $Doc->ExportField($this->archivo);
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
		if (preg_match('/^x(\d)*_id_tramite$/', $id)) {
			$sSqlWrk = "SELECT `Titulo` AS FIELD0 FROM `tramites`";
			$sWhereWrk = "(`codigo` = " . ew_AdjustSql($val) . ")";
			$lookuptblfilter = "`estado`<>'F'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tramite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `fecha` ASC";
			if ($rs = ew_LoadRecordset($sSqlWrk)) {
				while ($rs && !$rs->EOF) {
					$ar = array();
					$this->titulo->setDbValue($rs->fields[0]);
					$this->RowType == EW_ROWTYPE_EDIT;
					$this->RenderEditRow();
					$ar[] = ($this->titulo->AutoFillOriginalValue) ? $this->titulo->CurrentValue : $this->titulo->EditValue;
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
