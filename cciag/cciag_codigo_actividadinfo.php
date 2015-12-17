<?php

// Global variable for table object
$codigo_actividad = NULL;

//
// Table class for codigo_actividad
//
class ccodigo_actividad extends cTable {
	var $id;
	var $codigo;
	var $descripcion;
	var $descripcion_resumida;
	var $observaciones;
	var $version;
	var $id_rubro;
	var $fecha_alta;
	var $fecha_baja;
	var $objeto_cuantificable;
	var $manipula_alimento;
	var $id_clanae;
	var $id_actividad_padre;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'codigo_actividad';
		$this->TableName = 'codigo_actividad';
		$this->TableType = 'TABLE';
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

		// id
		$this->id = new cField('codigo_actividad', 'codigo_actividad', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// codigo
		$this->codigo = new cField('codigo_actividad', 'codigo_actividad', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 3, -1, FALSE, '`codigo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->codigo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codigo'] = &$this->codigo;

		// descripcion
		$this->descripcion = new cField('codigo_actividad', 'codigo_actividad', 'x_descripcion', 'descripcion', '`descripcion`', '`descripcion`', 200, -1, FALSE, '`descripcion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion'] = &$this->descripcion;

		// descripcion_resumida
		$this->descripcion_resumida = new cField('codigo_actividad', 'codigo_actividad', 'x_descripcion_resumida', 'descripcion_resumida', '`descripcion_resumida`', '`descripcion_resumida`', 200, -1, FALSE, '`descripcion_resumida`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion_resumida'] = &$this->descripcion_resumida;

		// observaciones
		$this->observaciones = new cField('codigo_actividad', 'codigo_actividad', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 200, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['observaciones'] = &$this->observaciones;

		// version
		$this->version = new cField('codigo_actividad', 'codigo_actividad', 'x_version', 'version', '`version`', '`version`', 3, -1, FALSE, '`version`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->version->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['version'] = &$this->version;

		// id_rubro
		$this->id_rubro = new cField('codigo_actividad', 'codigo_actividad', 'x_id_rubro', 'id_rubro', '`id_rubro`', '`id_rubro`', 3, -1, FALSE, '`id_rubro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_rubro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_rubro'] = &$this->id_rubro;

		// fecha_alta
		$this->fecha_alta = new cField('codigo_actividad', 'codigo_actividad', 'x_fecha_alta', 'fecha_alta', '`fecha_alta`', '`fecha_alta`', 200, -1, FALSE, '`fecha_alta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fecha_alta'] = &$this->fecha_alta;

		// fecha_baja
		$this->fecha_baja = new cField('codigo_actividad', 'codigo_actividad', 'x_fecha_baja', 'fecha_baja', '`fecha_baja`', '`fecha_baja`', 200, -1, FALSE, '`fecha_baja`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fecha_baja'] = &$this->fecha_baja;

		// objeto_cuantificable
		$this->objeto_cuantificable = new cField('codigo_actividad', 'codigo_actividad', 'x_objeto_cuantificable', 'objeto_cuantificable', '`objeto_cuantificable`', '`objeto_cuantificable`', 200, -1, FALSE, '`objeto_cuantificable`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['objeto_cuantificable'] = &$this->objeto_cuantificable;

		// manipula_alimento
		$this->manipula_alimento = new cField('codigo_actividad', 'codigo_actividad', 'x_manipula_alimento', 'manipula_alimento', '`manipula_alimento`', '`manipula_alimento`', 200, -1, FALSE, '`manipula_alimento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['manipula_alimento'] = &$this->manipula_alimento;

		// id_clanae
		$this->id_clanae = new cField('codigo_actividad', 'codigo_actividad', 'x_id_clanae', 'id_clanae', '`id_clanae`', '`id_clanae`', 200, -1, FALSE, '`id_clanae`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['id_clanae'] = &$this->id_clanae;

		// id_actividad_padre
		$this->id_actividad_padre = new cField('codigo_actividad', 'codigo_actividad', 'x_id_actividad_padre', 'id_actividad_padre', '`id_actividad_padre`', '`id_actividad_padre`', 200, -1, FALSE, '`id_actividad_padre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['id_actividad_padre'] = &$this->id_actividad_padre;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`codigo_actividad`";
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
	var $UpdateTable = "`codigo_actividad`";

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
			return "cciag_codigo_actividadlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "cciag_codigo_actividadlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("cciag_codigo_actividadview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("cciag_codigo_actividadview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "cciag_codigo_actividadadd.php?" . $this->UrlParm($parm);
		else
			return "cciag_codigo_actividadadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("cciag_codigo_actividadedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("cciag_codigo_actividadadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("cciag_codigo_actividaddelete.php", $this->UrlParm());
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
		$this->id->setDbValue($rs->fields('id'));
		$this->codigo->setDbValue($rs->fields('codigo'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->descripcion_resumida->setDbValue($rs->fields('descripcion_resumida'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->version->setDbValue($rs->fields('version'));
		$this->id_rubro->setDbValue($rs->fields('id_rubro'));
		$this->fecha_alta->setDbValue($rs->fields('fecha_alta'));
		$this->fecha_baja->setDbValue($rs->fields('fecha_baja'));
		$this->objeto_cuantificable->setDbValue($rs->fields('objeto_cuantificable'));
		$this->manipula_alimento->setDbValue($rs->fields('manipula_alimento'));
		$this->id_clanae->setDbValue($rs->fields('id_clanae'));
		$this->id_actividad_padre->setDbValue($rs->fields('id_actividad_padre'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// codigo
		// descripcion
		// descripcion_resumida
		// observaciones
		// version
		// id_rubro
		// fecha_alta
		// fecha_baja
		// objeto_cuantificable
		// manipula_alimento
		// id_clanae
		// id_actividad_padre
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// codigo
		$this->codigo->ViewValue = $this->codigo->CurrentValue;
		$this->codigo->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// descripcion_resumida
		$this->descripcion_resumida->ViewValue = $this->descripcion_resumida->CurrentValue;
		$this->descripcion_resumida->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// version
		$this->version->ViewValue = $this->version->CurrentValue;
		$this->version->ViewCustomAttributes = "";

		// id_rubro
		$this->id_rubro->ViewValue = $this->id_rubro->CurrentValue;
		$this->id_rubro->ViewCustomAttributes = "";

		// fecha_alta
		$this->fecha_alta->ViewValue = $this->fecha_alta->CurrentValue;
		$this->fecha_alta->ViewCustomAttributes = "";

		// fecha_baja
		$this->fecha_baja->ViewValue = $this->fecha_baja->CurrentValue;
		$this->fecha_baja->ViewCustomAttributes = "";

		// objeto_cuantificable
		$this->objeto_cuantificable->ViewValue = $this->objeto_cuantificable->CurrentValue;
		$this->objeto_cuantificable->ViewCustomAttributes = "";

		// manipula_alimento
		$this->manipula_alimento->ViewValue = $this->manipula_alimento->CurrentValue;
		$this->manipula_alimento->ViewCustomAttributes = "";

		// id_clanae
		$this->id_clanae->ViewValue = $this->id_clanae->CurrentValue;
		$this->id_clanae->ViewCustomAttributes = "";

		// id_actividad_padre
		$this->id_actividad_padre->ViewValue = $this->id_actividad_padre->CurrentValue;
		$this->id_actividad_padre->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// codigo
		$this->codigo->LinkCustomAttributes = "";
		$this->codigo->HrefValue = "";
		$this->codigo->TooltipValue = "";

		// descripcion
		$this->descripcion->LinkCustomAttributes = "";
		$this->descripcion->HrefValue = "";
		$this->descripcion->TooltipValue = "";

		// descripcion_resumida
		$this->descripcion_resumida->LinkCustomAttributes = "";
		$this->descripcion_resumida->HrefValue = "";
		$this->descripcion_resumida->TooltipValue = "";

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

		// version
		$this->version->LinkCustomAttributes = "";
		$this->version->HrefValue = "";
		$this->version->TooltipValue = "";

		// id_rubro
		$this->id_rubro->LinkCustomAttributes = "";
		$this->id_rubro->HrefValue = "";
		$this->id_rubro->TooltipValue = "";

		// fecha_alta
		$this->fecha_alta->LinkCustomAttributes = "";
		$this->fecha_alta->HrefValue = "";
		$this->fecha_alta->TooltipValue = "";

		// fecha_baja
		$this->fecha_baja->LinkCustomAttributes = "";
		$this->fecha_baja->HrefValue = "";
		$this->fecha_baja->TooltipValue = "";

		// objeto_cuantificable
		$this->objeto_cuantificable->LinkCustomAttributes = "";
		$this->objeto_cuantificable->HrefValue = "";
		$this->objeto_cuantificable->TooltipValue = "";

		// manipula_alimento
		$this->manipula_alimento->LinkCustomAttributes = "";
		$this->manipula_alimento->HrefValue = "";
		$this->manipula_alimento->TooltipValue = "";

		// id_clanae
		$this->id_clanae->LinkCustomAttributes = "";
		$this->id_clanae->HrefValue = "";
		$this->id_clanae->TooltipValue = "";

		// id_actividad_padre
		$this->id_actividad_padre->LinkCustomAttributes = "";
		$this->id_actividad_padre->HrefValue = "";
		$this->id_actividad_padre->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
		$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

		// codigo
		$this->codigo->EditAttrs["class"] = "form-control";
		$this->codigo->EditCustomAttributes = "";
		$this->codigo->EditValue = ew_HtmlEncode($this->codigo->CurrentValue);
		$this->codigo->PlaceHolder = ew_RemoveHtml($this->codigo->FldCaption());

		// descripcion
		$this->descripcion->EditAttrs["class"] = "form-control";
		$this->descripcion->EditCustomAttributes = "";
		$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);
		$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

		// descripcion_resumida
		$this->descripcion_resumida->EditAttrs["class"] = "form-control";
		$this->descripcion_resumida->EditCustomAttributes = "";
		$this->descripcion_resumida->EditValue = ew_HtmlEncode($this->descripcion_resumida->CurrentValue);
		$this->descripcion_resumida->PlaceHolder = ew_RemoveHtml($this->descripcion_resumida->FldCaption());

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = "";
		$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
		$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

		// version
		$this->version->EditAttrs["class"] = "form-control";
		$this->version->EditCustomAttributes = "";
		$this->version->EditValue = ew_HtmlEncode($this->version->CurrentValue);
		$this->version->PlaceHolder = ew_RemoveHtml($this->version->FldCaption());

		// id_rubro
		$this->id_rubro->EditAttrs["class"] = "form-control";
		$this->id_rubro->EditCustomAttributes = "";
		$this->id_rubro->EditValue = ew_HtmlEncode($this->id_rubro->CurrentValue);
		$this->id_rubro->PlaceHolder = ew_RemoveHtml($this->id_rubro->FldCaption());

		// fecha_alta
		$this->fecha_alta->EditAttrs["class"] = "form-control";
		$this->fecha_alta->EditCustomAttributes = "";
		$this->fecha_alta->EditValue = ew_HtmlEncode($this->fecha_alta->CurrentValue);
		$this->fecha_alta->PlaceHolder = ew_RemoveHtml($this->fecha_alta->FldCaption());

		// fecha_baja
		$this->fecha_baja->EditAttrs["class"] = "form-control";
		$this->fecha_baja->EditCustomAttributes = "";
		$this->fecha_baja->EditValue = ew_HtmlEncode($this->fecha_baja->CurrentValue);
		$this->fecha_baja->PlaceHolder = ew_RemoveHtml($this->fecha_baja->FldCaption());

		// objeto_cuantificable
		$this->objeto_cuantificable->EditAttrs["class"] = "form-control";
		$this->objeto_cuantificable->EditCustomAttributes = "";
		$this->objeto_cuantificable->EditValue = ew_HtmlEncode($this->objeto_cuantificable->CurrentValue);
		$this->objeto_cuantificable->PlaceHolder = ew_RemoveHtml($this->objeto_cuantificable->FldCaption());

		// manipula_alimento
		$this->manipula_alimento->EditAttrs["class"] = "form-control";
		$this->manipula_alimento->EditCustomAttributes = "";
		$this->manipula_alimento->EditValue = ew_HtmlEncode($this->manipula_alimento->CurrentValue);
		$this->manipula_alimento->PlaceHolder = ew_RemoveHtml($this->manipula_alimento->FldCaption());

		// id_clanae
		$this->id_clanae->EditAttrs["class"] = "form-control";
		$this->id_clanae->EditCustomAttributes = "";
		$this->id_clanae->EditValue = ew_HtmlEncode($this->id_clanae->CurrentValue);
		$this->id_clanae->PlaceHolder = ew_RemoveHtml($this->id_clanae->FldCaption());

		// id_actividad_padre
		$this->id_actividad_padre->EditAttrs["class"] = "form-control";
		$this->id_actividad_padre->EditCustomAttributes = "";
		$this->id_actividad_padre->EditValue = ew_HtmlEncode($this->id_actividad_padre->CurrentValue);
		$this->id_actividad_padre->PlaceHolder = ew_RemoveHtml($this->id_actividad_padre->FldCaption());

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
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->descripcion_resumida->Exportable) $Doc->ExportCaption($this->descripcion_resumida);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->version->Exportable) $Doc->ExportCaption($this->version);
					if ($this->id_rubro->Exportable) $Doc->ExportCaption($this->id_rubro);
					if ($this->fecha_alta->Exportable) $Doc->ExportCaption($this->fecha_alta);
					if ($this->fecha_baja->Exportable) $Doc->ExportCaption($this->fecha_baja);
					if ($this->objeto_cuantificable->Exportable) $Doc->ExportCaption($this->objeto_cuantificable);
					if ($this->manipula_alimento->Exportable) $Doc->ExportCaption($this->manipula_alimento);
					if ($this->id_clanae->Exportable) $Doc->ExportCaption($this->id_clanae);
					if ($this->id_actividad_padre->Exportable) $Doc->ExportCaption($this->id_actividad_padre);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->descripcion_resumida->Exportable) $Doc->ExportCaption($this->descripcion_resumida);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->version->Exportable) $Doc->ExportCaption($this->version);
					if ($this->id_rubro->Exportable) $Doc->ExportCaption($this->id_rubro);
					if ($this->fecha_alta->Exportable) $Doc->ExportCaption($this->fecha_alta);
					if ($this->fecha_baja->Exportable) $Doc->ExportCaption($this->fecha_baja);
					if ($this->objeto_cuantificable->Exportable) $Doc->ExportCaption($this->objeto_cuantificable);
					if ($this->manipula_alimento->Exportable) $Doc->ExportCaption($this->manipula_alimento);
					if ($this->id_clanae->Exportable) $Doc->ExportCaption($this->id_clanae);
					if ($this->id_actividad_padre->Exportable) $Doc->ExportCaption($this->id_actividad_padre);
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
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->descripcion_resumida->Exportable) $Doc->ExportField($this->descripcion_resumida);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->version->Exportable) $Doc->ExportField($this->version);
						if ($this->id_rubro->Exportable) $Doc->ExportField($this->id_rubro);
						if ($this->fecha_alta->Exportable) $Doc->ExportField($this->fecha_alta);
						if ($this->fecha_baja->Exportable) $Doc->ExportField($this->fecha_baja);
						if ($this->objeto_cuantificable->Exportable) $Doc->ExportField($this->objeto_cuantificable);
						if ($this->manipula_alimento->Exportable) $Doc->ExportField($this->manipula_alimento);
						if ($this->id_clanae->Exportable) $Doc->ExportField($this->id_clanae);
						if ($this->id_actividad_padre->Exportable) $Doc->ExportField($this->id_actividad_padre);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->descripcion_resumida->Exportable) $Doc->ExportField($this->descripcion_resumida);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->version->Exportable) $Doc->ExportField($this->version);
						if ($this->id_rubro->Exportable) $Doc->ExportField($this->id_rubro);
						if ($this->fecha_alta->Exportable) $Doc->ExportField($this->fecha_alta);
						if ($this->fecha_baja->Exportable) $Doc->ExportField($this->fecha_baja);
						if ($this->objeto_cuantificable->Exportable) $Doc->ExportField($this->objeto_cuantificable);
						if ($this->manipula_alimento->Exportable) $Doc->ExportField($this->manipula_alimento);
						if ($this->id_clanae->Exportable) $Doc->ExportField($this->id_clanae);
						if ($this->id_actividad_padre->Exportable) $Doc->ExportField($this->id_actividad_padre);
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
