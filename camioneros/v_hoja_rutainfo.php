<?php

// Global variable for table object
$v_hoja_ruta = NULL;

//
// Table class for v_hoja_ruta
//
class cv_hoja_ruta extends cTable {
	var $codigo;
	var $Origen;
	var $Destino;
	var $estado;
	var $Patente;
	var $nombre;
	var $modelo;
	var $razon_social;
	var $responsable;
	var $Tipo_carga;
	var $loc_desde;
	var $loc_hasta;
	var $cp_hasta;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'v_hoja_ruta';
		$this->TableName = 'v_hoja_ruta';
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
		$this->codigo = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 3, -1, FALSE, '`codigo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->codigo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codigo'] = &$this->codigo;

		// Origen
		$this->Origen = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_Origen', 'Origen', '`Origen`', '`Origen`', 200, -1, FALSE, '`Origen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Origen'] = &$this->Origen;

		// Destino
		$this->Destino = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_Destino', 'Destino', '`Destino`', '`Destino`', 200, -1, FALSE, '`Destino`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Destino'] = &$this->Destino;

		// estado
		$this->estado = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_estado', 'estado', '`estado`', '`estado`', 200, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['estado'] = &$this->estado;

		// Patente
		$this->Patente = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_Patente', 'Patente', '`Patente`', '`Patente`', 200, -1, FALSE, '`Patente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Patente'] = &$this->Patente;

		// nombre
		$this->nombre = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nombre'] = &$this->nombre;

		// modelo
		$this->modelo = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_modelo', 'modelo', '`modelo`', '`modelo`', 3, -1, FALSE, '`modelo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->modelo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['modelo'] = &$this->modelo;

		// razon_social
		$this->razon_social = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_razon_social', 'razon_social', '`razon_social`', '`razon_social`', 200, -1, FALSE, '`razon_social`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['razon_social'] = &$this->razon_social;

		// responsable
		$this->responsable = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_responsable', 'responsable', '`responsable`', '`responsable`', 200, -1, FALSE, '`responsable`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['responsable'] = &$this->responsable;

		// Tipo_carga
		$this->Tipo_carga = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_Tipo_carga', 'Tipo_carga', '`Tipo_carga`', '`Tipo_carga`', 200, -1, FALSE, '`Tipo_carga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Tipo_carga'] = &$this->Tipo_carga;

		// loc_desde
		$this->loc_desde = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_loc_desde', 'loc_desde', '`loc_desde`', '`loc_desde`', 200, -1, FALSE, '`loc_desde`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['loc_desde'] = &$this->loc_desde;

		// loc_hasta
		$this->loc_hasta = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_loc_hasta', 'loc_hasta', '`loc_hasta`', '`loc_hasta`', 200, -1, FALSE, '`loc_hasta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['loc_hasta'] = &$this->loc_hasta;

		// cp_hasta
		$this->cp_hasta = new cField('v_hoja_ruta', 'v_hoja_ruta', 'x_cp_hasta', 'cp_hasta', '`cp_hasta`', '`cp_hasta`', 3, -1, FALSE, '`cp_hasta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cp_hasta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cp_hasta'] = &$this->cp_hasta;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_hoja_ruta`";
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
	var $UpdateTable = "`v_hoja_ruta`";

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
			return "v_hoja_rutalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "v_hoja_rutalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("v_hoja_rutaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("v_hoja_rutaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "v_hoja_rutaadd.php?" . $this->UrlParm($parm);
		else
			return "v_hoja_rutaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("v_hoja_rutaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("v_hoja_rutaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("v_hoja_rutadelete.php", $this->UrlParm());
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
		$this->Origen->setDbValue($rs->fields('Origen'));
		$this->Destino->setDbValue($rs->fields('Destino'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->Patente->setDbValue($rs->fields('Patente'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->modelo->setDbValue($rs->fields('modelo'));
		$this->razon_social->setDbValue($rs->fields('razon_social'));
		$this->responsable->setDbValue($rs->fields('responsable'));
		$this->Tipo_carga->setDbValue($rs->fields('Tipo_carga'));
		$this->loc_desde->setDbValue($rs->fields('loc_desde'));
		$this->loc_hasta->setDbValue($rs->fields('loc_hasta'));
		$this->cp_hasta->setDbValue($rs->fields('cp_hasta'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// codigo
		// Origen
		// Destino
		// estado
		// Patente
		// nombre
		// modelo
		// razon_social
		// responsable
		// Tipo_carga
		// loc_desde
		// loc_hasta
		// cp_hasta
		// codigo

		$this->codigo->ViewValue = $this->codigo->CurrentValue;
		$this->codigo->ViewCustomAttributes = "";

		// Origen
		$this->Origen->ViewValue = $this->Origen->CurrentValue;
		$this->Origen->ViewCustomAttributes = "";

		// Destino
		$this->Destino->ViewValue = $this->Destino->CurrentValue;
		$this->Destino->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// Patente
		$this->Patente->ViewValue = $this->Patente->CurrentValue;
		$this->Patente->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// modelo
		$this->modelo->ViewValue = $this->modelo->CurrentValue;
		$this->modelo->ViewCustomAttributes = "";

		// razon_social
		$this->razon_social->ViewValue = $this->razon_social->CurrentValue;
		$this->razon_social->ViewCustomAttributes = "";

		// responsable
		$this->responsable->ViewValue = $this->responsable->CurrentValue;
		$this->responsable->ViewCustomAttributes = "";

		// Tipo_carga
		$this->Tipo_carga->ViewValue = $this->Tipo_carga->CurrentValue;
		$this->Tipo_carga->ViewCustomAttributes = "";

		// loc_desde
		$this->loc_desde->ViewValue = $this->loc_desde->CurrentValue;
		$this->loc_desde->ViewCustomAttributes = "";

		// loc_hasta
		$this->loc_hasta->ViewValue = $this->loc_hasta->CurrentValue;
		$this->loc_hasta->ViewCustomAttributes = "";

		// cp_hasta
		$this->cp_hasta->ViewValue = $this->cp_hasta->CurrentValue;
		$this->cp_hasta->ViewCustomAttributes = "";

		// codigo
		$this->codigo->LinkCustomAttributes = "";
		$this->codigo->HrefValue = "";
		$this->codigo->TooltipValue = "";

		// Origen
		$this->Origen->LinkCustomAttributes = "";
		$this->Origen->HrefValue = "";
		$this->Origen->TooltipValue = "";

		// Destino
		$this->Destino->LinkCustomAttributes = "";
		$this->Destino->HrefValue = "";
		$this->Destino->TooltipValue = "";

		// estado
		$this->estado->LinkCustomAttributes = "";
		$this->estado->HrefValue = "";
		$this->estado->TooltipValue = "";

		// Patente
		$this->Patente->LinkCustomAttributes = "";
		$this->Patente->HrefValue = "";
		$this->Patente->TooltipValue = "";

		// nombre
		$this->nombre->LinkCustomAttributes = "";
		$this->nombre->HrefValue = "";
		$this->nombre->TooltipValue = "";

		// modelo
		$this->modelo->LinkCustomAttributes = "";
		$this->modelo->HrefValue = "";
		$this->modelo->TooltipValue = "";

		// razon_social
		$this->razon_social->LinkCustomAttributes = "";
		$this->razon_social->HrefValue = "";
		$this->razon_social->TooltipValue = "";

		// responsable
		$this->responsable->LinkCustomAttributes = "";
		$this->responsable->HrefValue = "";
		$this->responsable->TooltipValue = "";

		// Tipo_carga
		$this->Tipo_carga->LinkCustomAttributes = "";
		$this->Tipo_carga->HrefValue = "";
		$this->Tipo_carga->TooltipValue = "";

		// loc_desde
		$this->loc_desde->LinkCustomAttributes = "";
		$this->loc_desde->HrefValue = "";
		$this->loc_desde->TooltipValue = "";

		// loc_hasta
		$this->loc_hasta->LinkCustomAttributes = "";
		$this->loc_hasta->HrefValue = "";
		$this->loc_hasta->TooltipValue = "";

		// cp_hasta
		$this->cp_hasta->LinkCustomAttributes = "";
		$this->cp_hasta->HrefValue = "";
		$this->cp_hasta->TooltipValue = "";

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

		// Origen
		$this->Origen->EditAttrs["class"] = "form-control";
		$this->Origen->EditCustomAttributes = "";
		$this->Origen->EditValue = ew_HtmlEncode($this->Origen->CurrentValue);
		$this->Origen->PlaceHolder = ew_RemoveHtml($this->Origen->FldCaption());

		// Destino
		$this->Destino->EditAttrs["class"] = "form-control";
		$this->Destino->EditCustomAttributes = "";
		$this->Destino->EditValue = ew_HtmlEncode($this->Destino->CurrentValue);
		$this->Destino->PlaceHolder = ew_RemoveHtml($this->Destino->FldCaption());

		// estado
		$this->estado->EditAttrs["class"] = "form-control";
		$this->estado->EditCustomAttributes = "";
		$this->estado->EditValue = ew_HtmlEncode($this->estado->CurrentValue);
		$this->estado->PlaceHolder = ew_RemoveHtml($this->estado->FldCaption());

		// Patente
		$this->Patente->EditAttrs["class"] = "form-control";
		$this->Patente->EditCustomAttributes = "";
		$this->Patente->EditValue = ew_HtmlEncode($this->Patente->CurrentValue);
		$this->Patente->PlaceHolder = ew_RemoveHtml($this->Patente->FldCaption());

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

		// razon_social
		$this->razon_social->EditAttrs["class"] = "form-control";
		$this->razon_social->EditCustomAttributes = "";
		$this->razon_social->EditValue = ew_HtmlEncode($this->razon_social->CurrentValue);
		$this->razon_social->PlaceHolder = ew_RemoveHtml($this->razon_social->FldCaption());

		// responsable
		$this->responsable->EditAttrs["class"] = "form-control";
		$this->responsable->EditCustomAttributes = "";
		$this->responsable->EditValue = ew_HtmlEncode($this->responsable->CurrentValue);
		$this->responsable->PlaceHolder = ew_RemoveHtml($this->responsable->FldCaption());

		// Tipo_carga
		$this->Tipo_carga->EditAttrs["class"] = "form-control";
		$this->Tipo_carga->EditCustomAttributes = "";
		$this->Tipo_carga->EditValue = ew_HtmlEncode($this->Tipo_carga->CurrentValue);
		$this->Tipo_carga->PlaceHolder = ew_RemoveHtml($this->Tipo_carga->FldCaption());

		// loc_desde
		$this->loc_desde->EditAttrs["class"] = "form-control";
		$this->loc_desde->EditCustomAttributes = "";
		$this->loc_desde->EditValue = ew_HtmlEncode($this->loc_desde->CurrentValue);
		$this->loc_desde->PlaceHolder = ew_RemoveHtml($this->loc_desde->FldCaption());

		// loc_hasta
		$this->loc_hasta->EditAttrs["class"] = "form-control";
		$this->loc_hasta->EditCustomAttributes = "";
		$this->loc_hasta->EditValue = ew_HtmlEncode($this->loc_hasta->CurrentValue);
		$this->loc_hasta->PlaceHolder = ew_RemoveHtml($this->loc_hasta->FldCaption());

		// cp_hasta
		$this->cp_hasta->EditAttrs["class"] = "form-control";
		$this->cp_hasta->EditCustomAttributes = "";
		$this->cp_hasta->EditValue = ew_HtmlEncode($this->cp_hasta->CurrentValue);
		$this->cp_hasta->PlaceHolder = ew_RemoveHtml($this->cp_hasta->FldCaption());

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
					if ($this->Origen->Exportable) $Doc->ExportCaption($this->Origen);
					if ($this->Destino->Exportable) $Doc->ExportCaption($this->Destino);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
					if ($this->Patente->Exportable) $Doc->ExportCaption($this->Patente);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->modelo->Exportable) $Doc->ExportCaption($this->modelo);
					if ($this->razon_social->Exportable) $Doc->ExportCaption($this->razon_social);
					if ($this->responsable->Exportable) $Doc->ExportCaption($this->responsable);
					if ($this->Tipo_carga->Exportable) $Doc->ExportCaption($this->Tipo_carga);
					if ($this->loc_desde->Exportable) $Doc->ExportCaption($this->loc_desde);
					if ($this->loc_hasta->Exportable) $Doc->ExportCaption($this->loc_hasta);
					if ($this->cp_hasta->Exportable) $Doc->ExportCaption($this->cp_hasta);
				} else {
					if ($this->codigo->Exportable) $Doc->ExportCaption($this->codigo);
					if ($this->Origen->Exportable) $Doc->ExportCaption($this->Origen);
					if ($this->Destino->Exportable) $Doc->ExportCaption($this->Destino);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
					if ($this->Patente->Exportable) $Doc->ExportCaption($this->Patente);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->modelo->Exportable) $Doc->ExportCaption($this->modelo);
					if ($this->razon_social->Exportable) $Doc->ExportCaption($this->razon_social);
					if ($this->responsable->Exportable) $Doc->ExportCaption($this->responsable);
					if ($this->Tipo_carga->Exportable) $Doc->ExportCaption($this->Tipo_carga);
					if ($this->loc_desde->Exportable) $Doc->ExportCaption($this->loc_desde);
					if ($this->loc_hasta->Exportable) $Doc->ExportCaption($this->loc_hasta);
					if ($this->cp_hasta->Exportable) $Doc->ExportCaption($this->cp_hasta);
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
						if ($this->Origen->Exportable) $Doc->ExportField($this->Origen);
						if ($this->Destino->Exportable) $Doc->ExportField($this->Destino);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
						if ($this->Patente->Exportable) $Doc->ExportField($this->Patente);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->modelo->Exportable) $Doc->ExportField($this->modelo);
						if ($this->razon_social->Exportable) $Doc->ExportField($this->razon_social);
						if ($this->responsable->Exportable) $Doc->ExportField($this->responsable);
						if ($this->Tipo_carga->Exportable) $Doc->ExportField($this->Tipo_carga);
						if ($this->loc_desde->Exportable) $Doc->ExportField($this->loc_desde);
						if ($this->loc_hasta->Exportable) $Doc->ExportField($this->loc_hasta);
						if ($this->cp_hasta->Exportable) $Doc->ExportField($this->cp_hasta);
					} else {
						if ($this->codigo->Exportable) $Doc->ExportField($this->codigo);
						if ($this->Origen->Exportable) $Doc->ExportField($this->Origen);
						if ($this->Destino->Exportable) $Doc->ExportField($this->Destino);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
						if ($this->Patente->Exportable) $Doc->ExportField($this->Patente);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->modelo->Exportable) $Doc->ExportField($this->modelo);
						if ($this->razon_social->Exportable) $Doc->ExportField($this->razon_social);
						if ($this->responsable->Exportable) $Doc->ExportField($this->responsable);
						if ($this->Tipo_carga->Exportable) $Doc->ExportField($this->Tipo_carga);
						if ($this->loc_desde->Exportable) $Doc->ExportField($this->loc_desde);
						if ($this->loc_hasta->Exportable) $Doc->ExportField($this->loc_hasta);
						if ($this->cp_hasta->Exportable) $Doc->ExportField($this->cp_hasta);
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
