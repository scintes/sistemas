<?php

// Global variable for table object
$hoja_rutas = NULL;

//
// Table class for hoja_rutas
//
class choja_rutas extends cTable {
	var $codigo;
	var $fecha_ini;
	var $id_cliente;
	var $id_localidad_origen;
	var $Origen;
	var $id_localidad_destino;
	var $Destino;
	var $Km_ini;
	var $estado;
	var $id_vehiculo;
	var $id_tipo_carga;
	var $km_fin;
	var $fecha_fin;
	var $adelanto;
	var $kg_carga;
	var $tarifa;
	var $porcentaje;
	var $id_usuario;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'hoja_rutas';
		$this->TableName = 'hoja_rutas';
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
		$this->codigo = new cField('hoja_rutas', 'hoja_rutas', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 3, -1, FALSE, '`codigo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->codigo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codigo'] = &$this->codigo;

		// fecha_ini
		$this->fecha_ini = new cField('hoja_rutas', 'hoja_rutas', 'x_fecha_ini', 'fecha_ini', '`fecha_ini`', 'DATE_FORMAT(`fecha_ini`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_ini`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_ini->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_ini'] = &$this->fecha_ini;

		// id_cliente
		$this->id_cliente = new cField('hoja_rutas', 'hoja_rutas', 'x_id_cliente', 'id_cliente', '`id_cliente`', '`id_cliente`', 3, -1, FALSE, '`id_cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_cliente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_cliente'] = &$this->id_cliente;

		// id_localidad_origen
		$this->id_localidad_origen = new cField('hoja_rutas', 'hoja_rutas', 'x_id_localidad_origen', 'id_localidad_origen', '`id_localidad_origen`', '`id_localidad_origen`', 3, -1, FALSE, '`EV__id_localidad_origen`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->id_localidad_origen->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_localidad_origen'] = &$this->id_localidad_origen;

		// Origen
		$this->Origen = new cField('hoja_rutas', 'hoja_rutas', 'x_Origen', 'Origen', '`Origen`', '`Origen`', 200, -1, FALSE, '`Origen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Origen'] = &$this->Origen;

		// id_localidad_destino
		$this->id_localidad_destino = new cField('hoja_rutas', 'hoja_rutas', 'x_id_localidad_destino', 'id_localidad_destino', '`id_localidad_destino`', '`id_localidad_destino`', 3, -1, FALSE, '`EV__id_localidad_destino`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->id_localidad_destino->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_localidad_destino'] = &$this->id_localidad_destino;

		// Destino
		$this->Destino = new cField('hoja_rutas', 'hoja_rutas', 'x_Destino', 'Destino', '`Destino`', '`Destino`', 200, -1, FALSE, '`Destino`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Destino'] = &$this->Destino;

		// Km_ini
		$this->Km_ini = new cField('hoja_rutas', 'hoja_rutas', 'x_Km_ini', 'Km_ini', '`Km_ini`', '`Km_ini`', 19, -1, FALSE, '`Km_ini`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Km_ini->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Km_ini'] = &$this->Km_ini;

		// estado
		$this->estado = new cField('hoja_rutas', 'hoja_rutas', 'x_estado', 'estado', '`estado`', '`estado`', 200, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['estado'] = &$this->estado;

		// id_vehiculo
		$this->id_vehiculo = new cField('hoja_rutas', 'hoja_rutas', 'x_id_vehiculo', 'id_vehiculo', '`id_vehiculo`', '`id_vehiculo`', 3, -1, FALSE, '`EV__id_vehiculo`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->id_vehiculo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_vehiculo'] = &$this->id_vehiculo;

		// id_tipo_carga
		$this->id_tipo_carga = new cField('hoja_rutas', 'hoja_rutas', 'x_id_tipo_carga', 'id_tipo_carga', '`id_tipo_carga`', '`id_tipo_carga`', 3, -1, FALSE, '`EV__id_tipo_carga`', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->id_tipo_carga->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tipo_carga'] = &$this->id_tipo_carga;

		// km_fin
		$this->km_fin = new cField('hoja_rutas', 'hoja_rutas', 'x_km_fin', 'km_fin', '`km_fin`', '`km_fin`', 19, -1, FALSE, '`km_fin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->km_fin->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['km_fin'] = &$this->km_fin;

		// fecha_fin
		$this->fecha_fin = new cField('hoja_rutas', 'hoja_rutas', 'x_fecha_fin', 'fecha_fin', '`fecha_fin`', 'DATE_FORMAT(`fecha_fin`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_fin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_fin->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_fin'] = &$this->fecha_fin;

		// adelanto
		$this->adelanto = new cField('hoja_rutas', 'hoja_rutas', 'x_adelanto', 'adelanto', '`adelanto`', '`adelanto`', 131, -1, FALSE, '`adelanto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->adelanto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['adelanto'] = &$this->adelanto;

		// kg_carga
		$this->kg_carga = new cField('hoja_rutas', 'hoja_rutas', 'x_kg_carga', 'kg_carga', '`kg_carga`', '`kg_carga`', 3, -1, FALSE, '`kg_carga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->kg_carga->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['kg_carga'] = &$this->kg_carga;

		// tarifa
		$this->tarifa = new cField('hoja_rutas', 'hoja_rutas', 'x_tarifa', 'tarifa', '`tarifa`', '`tarifa`', 131, -1, FALSE, '`tarifa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tarifa->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['tarifa'] = &$this->tarifa;

		// porcentaje
		$this->porcentaje = new cField('hoja_rutas', 'hoja_rutas', 'x_porcentaje', 'porcentaje', '`porcentaje`', '`porcentaje`', 131, -1, FALSE, '`porcentaje`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->porcentaje->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['porcentaje'] = &$this->porcentaje;

		// id_usuario
		$this->id_usuario = new cField('hoja_rutas', 'hoja_rutas', 'x_id_usuario', 'id_usuario', '`id_usuario`', '`id_usuario`', 3, -1, FALSE, '`id_usuario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "gastos") {
			$sDetailUrl = $GLOBALS["gastos"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&fk_codigo=" . urlencode($this->codigo->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "hoja_rutaslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`hoja_rutas`";
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
			"SELECT *, (SELECT CONCAT(`cp`,'" . ew_ValueSeparator(1, $this->id_localidad_origen) . "',`localidad`,'" . ew_ValueSeparator(2, $this->id_localidad_origen) . "',`provincia`) FROM `v_localidad_provincia` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`codigo` = `hoja_rutas`.`id_localidad_origen` LIMIT 1) AS `EV__id_localidad_origen`, (SELECT CONCAT(`cp`,'" . ew_ValueSeparator(1, $this->id_localidad_destino) . "',`localidad`,'" . ew_ValueSeparator(2, $this->id_localidad_destino) . "',`provincia`) FROM `v_localidad_provincia` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`codigo` = `hoja_rutas`.`id_localidad_destino` LIMIT 1) AS `EV__id_localidad_destino`, (SELECT CONCAT(`Patente`,'" . ew_ValueSeparator(1, $this->id_vehiculo) . "',`nombre`) FROM `vehiculos` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`codigo` = `hoja_rutas`.`id_vehiculo` LIMIT 1) AS `EV__id_vehiculo`, (SELECT CONCAT(`Tipo_carga`,'" . ew_ValueSeparator(1, $this->id_tipo_carga) . "',`precio_base`) FROM `tipo_cargas` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`codigo` = `hoja_rutas`.`id_tipo_carga` LIMIT 1) AS `EV__id_tipo_carga` FROM `hoja_rutas`" .
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`fecha_ini` ASC,`id_vehiculo` ASC,`id_tipo_carga` ASC,`id_cliente` ASC";
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
		if ($this->id_localidad_origen->AdvancedSearch->SearchValue <> "" ||
			$this->id_localidad_origen->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_localidad_origen->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_localidad_origen->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_localidad_destino->AdvancedSearch->SearchValue <> "" ||
			$this->id_localidad_destino->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_localidad_destino->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_localidad_destino->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_vehiculo->AdvancedSearch->SearchValue <> "" ||
			$this->id_vehiculo->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_vehiculo->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_vehiculo->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_tipo_carga->AdvancedSearch->SearchValue <> "" ||
			$this->id_tipo_carga->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_tipo_carga->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_tipo_carga->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "`hoja_rutas`";

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

		// Cascade Update detail table 'gastos'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['codigo']) && $rsold['codigo'] <> $rs['codigo'])) { // Update detail field 'id_hoja_ruta'
			$bCascadeUpdate = TRUE;
			$rscascade['id_hoja_ruta'] = $rs['codigo']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["gastos"])) $GLOBALS["gastos"] = new cgastos();
			$GLOBALS["gastos"]->Update($rscascade, "`id_hoja_ruta` = " . ew_QuotedValue($rsold['codigo'], EW_DATATYPE_NUMBER));
		}
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

		// Cascade delete detail table 'gastos'
		if (!isset($GLOBALS["gastos"])) $GLOBALS["gastos"] = new cgastos();
		$rscascade = array();
		$GLOBALS["gastos"]->Delete($rscascade, "`id_hoja_ruta` = " . ew_QuotedValue($rs['codigo'], EW_DATATYPE_NUMBER));
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
			return "hoja_rutaslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "hoja_rutaslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("hoja_rutasview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("hoja_rutasview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "hoja_rutasadd.php?" . $this->UrlParm($parm);
		else
			return "hoja_rutasadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("hoja_rutasedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("hoja_rutasedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("hoja_rutasadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("hoja_rutasadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("hoja_rutasdelete.php", $this->UrlParm());
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
		$this->fecha_ini->setDbValue($rs->fields('fecha_ini'));
		$this->id_cliente->setDbValue($rs->fields('id_cliente'));
		$this->id_localidad_origen->setDbValue($rs->fields('id_localidad_origen'));
		$this->Origen->setDbValue($rs->fields('Origen'));
		$this->id_localidad_destino->setDbValue($rs->fields('id_localidad_destino'));
		$this->Destino->setDbValue($rs->fields('Destino'));
		$this->Km_ini->setDbValue($rs->fields('Km_ini'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->id_vehiculo->setDbValue($rs->fields('id_vehiculo'));
		$this->id_tipo_carga->setDbValue($rs->fields('id_tipo_carga'));
		$this->km_fin->setDbValue($rs->fields('km_fin'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->adelanto->setDbValue($rs->fields('adelanto'));
		$this->kg_carga->setDbValue($rs->fields('kg_carga'));
		$this->tarifa->setDbValue($rs->fields('tarifa'));
		$this->porcentaje->setDbValue($rs->fields('porcentaje'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// codigo
		// fecha_ini
		// id_cliente
		// id_localidad_origen
		// Origen
		// id_localidad_destino
		// Destino
		// Km_ini
		// estado
		// id_vehiculo
		// id_tipo_carga
		// km_fin
		// fecha_fin
		// adelanto
		// kg_carga
		// tarifa
		// porcentaje
		// id_usuario

		$this->id_usuario->CellCssStyle = "white-space: nowrap;";

		// codigo
		$this->codigo->ViewValue = $this->codigo->CurrentValue;
		$this->codigo->ViewCustomAttributes = "";

		// fecha_ini
		$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
		$this->fecha_ini->ViewValue = ew_FormatDateTime($this->fecha_ini->ViewValue, 7);
		$this->fecha_ini->ViewCustomAttributes = "";

		// id_cliente
		if (strval($this->id_cliente->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `codigo`, `cuit_cuil` AS `DispFld`, `razon_social` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `clientes`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_cliente, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `razon_social` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_cliente->ViewValue = $rswrk->fields('DispFld');
				$this->id_cliente->ViewValue .= ew_ValueSeparator(1,$this->id_cliente) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->id_cliente->ViewValue = $this->id_cliente->CurrentValue;
			}
		} else {
			$this->id_cliente->ViewValue = NULL;
		}
		$this->id_cliente->ViewCustomAttributes = "";

		// id_localidad_origen
		if ($this->id_localidad_origen->VirtualValue <> "") {
			$this->id_localidad_origen->ViewValue = $this->id_localidad_origen->VirtualValue;
		} else {
		if (strval($this->id_localidad_origen->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad_origen->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `codigo`, `cp` AS `DispFld`, `localidad` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_localidad_origen, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `localidad` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_localidad_origen->ViewValue = $rswrk->fields('DispFld');
				$this->id_localidad_origen->ViewValue .= ew_ValueSeparator(1,$this->id_localidad_origen) . $rswrk->fields('Disp2Fld');
				$this->id_localidad_origen->ViewValue .= ew_ValueSeparator(2,$this->id_localidad_origen) . $rswrk->fields('Disp3Fld');
				$rswrk->Close();
			} else {
				$this->id_localidad_origen->ViewValue = $this->id_localidad_origen->CurrentValue;
			}
		} else {
			$this->id_localidad_origen->ViewValue = NULL;
		}
		}
		$this->id_localidad_origen->ViewCustomAttributes = "";

		// Origen
		$this->Origen->ViewValue = $this->Origen->CurrentValue;
		$this->Origen->ViewCustomAttributes = "";

		// id_localidad_destino
		if ($this->id_localidad_destino->VirtualValue <> "") {
			$this->id_localidad_destino->ViewValue = $this->id_localidad_destino->VirtualValue;
		} else {
		if (strval($this->id_localidad_destino->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_localidad_destino->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `codigo`, `cp` AS `DispFld`, `localidad` AS `Disp2Fld`, `provincia` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `v_localidad_provincia`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_localidad_destino, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `localidad` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_localidad_destino->ViewValue = $rswrk->fields('DispFld');
				$this->id_localidad_destino->ViewValue .= ew_ValueSeparator(1,$this->id_localidad_destino) . $rswrk->fields('Disp2Fld');
				$this->id_localidad_destino->ViewValue .= ew_ValueSeparator(2,$this->id_localidad_destino) . $rswrk->fields('Disp3Fld');
				$rswrk->Close();
			} else {
				$this->id_localidad_destino->ViewValue = $this->id_localidad_destino->CurrentValue;
			}
		} else {
			$this->id_localidad_destino->ViewValue = NULL;
		}
		}
		$this->id_localidad_destino->ViewCustomAttributes = "";

		// Destino
		$this->Destino->ViewValue = $this->Destino->CurrentValue;
		$this->Destino->ViewCustomAttributes = "";

		// Km_ini
		$this->Km_ini->ViewValue = $this->Km_ini->CurrentValue;
		$this->Km_ini->ViewValue = ew_FormatNumber($this->Km_ini->ViewValue, 0, -2, -2, -2);
		$this->Km_ini->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// id_vehiculo
		if ($this->id_vehiculo->VirtualValue <> "") {
			$this->id_vehiculo->ViewValue = $this->id_vehiculo->VirtualValue;
		} else {
		if (strval($this->id_vehiculo->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_vehiculo->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `codigo`, `Patente` AS `DispFld`, `nombre` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vehiculos`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_vehiculo, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_vehiculo->ViewValue = $rswrk->fields('DispFld');
				$this->id_vehiculo->ViewValue .= ew_ValueSeparator(1,$this->id_vehiculo) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->id_vehiculo->ViewValue = $this->id_vehiculo->CurrentValue;
			}
		} else {
			$this->id_vehiculo->ViewValue = NULL;
		}
		}
		$this->id_vehiculo->ViewCustomAttributes = "";

		// id_tipo_carga
		if ($this->id_tipo_carga->VirtualValue <> "") {
			$this->id_tipo_carga->ViewValue = $this->id_tipo_carga->VirtualValue;
		} else {
		if (strval($this->id_tipo_carga->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_carga->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `codigo`, `Tipo_carga` AS `DispFld`, `precio_base` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_cargas`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_tipo_carga, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `Tipo_carga` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_tipo_carga->ViewValue = $rswrk->fields('DispFld');
				$this->id_tipo_carga->ViewValue .= ew_ValueSeparator(1,$this->id_tipo_carga) . ew_FormatCurrency($rswrk->fields('Disp2Fld'), 2, 0, 0, -1);
				$rswrk->Close();
			} else {
				$this->id_tipo_carga->ViewValue = $this->id_tipo_carga->CurrentValue;
			}
		} else {
			$this->id_tipo_carga->ViewValue = NULL;
		}
		}
		$this->id_tipo_carga->ViewCustomAttributes = "";

		// km_fin
		$this->km_fin->ViewValue = $this->km_fin->CurrentValue;
		$this->km_fin->ViewValue = ew_FormatNumber($this->km_fin->ViewValue, 0, -2, -2, -2);
		$this->km_fin->ViewCustomAttributes = "";

		// fecha_fin
		$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
		$this->fecha_fin->ViewValue = ew_FormatDateTime($this->fecha_fin->ViewValue, 7);
		$this->fecha_fin->ViewCustomAttributes = "";

		// adelanto
		$this->adelanto->ViewValue = $this->adelanto->CurrentValue;
		$this->adelanto->ViewValue = ew_FormatCurrency($this->adelanto->ViewValue, 2, -2, -2, -2);
		$this->adelanto->ViewCustomAttributes = "";

		// kg_carga
		$this->kg_carga->ViewValue = $this->kg_carga->CurrentValue;
		$this->kg_carga->ViewValue = ew_FormatNumber($this->kg_carga->ViewValue, 0, -2, -2, -2);
		$this->kg_carga->ViewCustomAttributes = "";

		// tarifa
		$this->tarifa->ViewValue = $this->tarifa->CurrentValue;
		$this->tarifa->ViewValue = ew_FormatCurrency($this->tarifa->ViewValue, 2, -2, -2, -2);
		$this->tarifa->ViewCustomAttributes = "";

		// porcentaje
		$this->porcentaje->ViewValue = $this->porcentaje->CurrentValue;
		$this->porcentaje->ViewCustomAttributes = "";

		// id_usuario
		$this->id_usuario->ViewValue = $this->id_usuario->CurrentValue;
		$this->id_usuario->ViewCustomAttributes = "";

		// codigo
		$this->codigo->LinkCustomAttributes = "";
		$this->codigo->HrefValue = "";
		$this->codigo->TooltipValue = "";

		// fecha_ini
		$this->fecha_ini->LinkCustomAttributes = "";
		$this->fecha_ini->HrefValue = "";
		$this->fecha_ini->TooltipValue = "";

		// id_cliente
		$this->id_cliente->LinkCustomAttributes = "";
		$this->id_cliente->HrefValue = "";
		$this->id_cliente->TooltipValue = "";

		// id_localidad_origen
		$this->id_localidad_origen->LinkCustomAttributes = "";
		$this->id_localidad_origen->HrefValue = "";
		$this->id_localidad_origen->TooltipValue = "";

		// Origen
		$this->Origen->LinkCustomAttributes = "";
		$this->Origen->HrefValue = "";
		$this->Origen->TooltipValue = "";

		// id_localidad_destino
		$this->id_localidad_destino->LinkCustomAttributes = "";
		$this->id_localidad_destino->HrefValue = "";
		$this->id_localidad_destino->TooltipValue = "";

		// Destino
		$this->Destino->LinkCustomAttributes = "";
		$this->Destino->HrefValue = "";
		$this->Destino->TooltipValue = "";

		// Km_ini
		$this->Km_ini->LinkCustomAttributes = "";
		$this->Km_ini->HrefValue = "";
		$this->Km_ini->TooltipValue = "";

		// estado
		$this->estado->LinkCustomAttributes = "";
		$this->estado->HrefValue = "";
		$this->estado->TooltipValue = "";

		// id_vehiculo
		$this->id_vehiculo->LinkCustomAttributes = "";
		$this->id_vehiculo->HrefValue = "";
		$this->id_vehiculo->TooltipValue = "";

		// id_tipo_carga
		$this->id_tipo_carga->LinkCustomAttributes = "";
		$this->id_tipo_carga->HrefValue = "";
		$this->id_tipo_carga->TooltipValue = "";

		// km_fin
		$this->km_fin->LinkCustomAttributes = "";
		$this->km_fin->HrefValue = "";
		$this->km_fin->TooltipValue = "";

		// fecha_fin
		$this->fecha_fin->LinkCustomAttributes = "";
		$this->fecha_fin->HrefValue = "";
		$this->fecha_fin->TooltipValue = "";

		// adelanto
		$this->adelanto->LinkCustomAttributes = "";
		$this->adelanto->HrefValue = "";
		$this->adelanto->TooltipValue = "";

		// kg_carga
		$this->kg_carga->LinkCustomAttributes = "";
		$this->kg_carga->HrefValue = "";
		$this->kg_carga->TooltipValue = "";

		// tarifa
		$this->tarifa->LinkCustomAttributes = "";
		$this->tarifa->HrefValue = "";
		$this->tarifa->TooltipValue = "";

		// porcentaje
		$this->porcentaje->LinkCustomAttributes = "";
		$this->porcentaje->HrefValue = "";
		$this->porcentaje->TooltipValue = "";

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

		// fecha_ini
		$this->fecha_ini->EditAttrs["class"] = "form-control";
		$this->fecha_ini->EditCustomAttributes = "";
		$this->fecha_ini->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_ini->CurrentValue, 7));
		$this->fecha_ini->PlaceHolder = ew_RemoveHtml($this->fecha_ini->FldCaption());

		// id_cliente
		$this->id_cliente->EditAttrs["class"] = "form-control";
		$this->id_cliente->EditCustomAttributes = "";

		// id_localidad_origen
		$this->id_localidad_origen->EditAttrs["class"] = "form-control";
		$this->id_localidad_origen->EditCustomAttributes = "";

		// Origen
		$this->Origen->EditAttrs["class"] = "form-control";
		$this->Origen->EditCustomAttributes = "";
		$this->Origen->EditValue = ew_HtmlEncode($this->Origen->CurrentValue);
		$this->Origen->PlaceHolder = ew_RemoveHtml($this->Origen->FldCaption());

		// id_localidad_destino
		$this->id_localidad_destino->EditAttrs["class"] = "form-control";
		$this->id_localidad_destino->EditCustomAttributes = "";

		// Destino
		$this->Destino->EditAttrs["class"] = "form-control";
		$this->Destino->EditCustomAttributes = "";
		$this->Destino->EditValue = ew_HtmlEncode($this->Destino->CurrentValue);
		$this->Destino->PlaceHolder = ew_RemoveHtml($this->Destino->FldCaption());

		// Km_ini
		$this->Km_ini->EditAttrs["class"] = "form-control";
		$this->Km_ini->EditCustomAttributes = "";
		$this->Km_ini->EditValue = ew_HtmlEncode($this->Km_ini->CurrentValue);
		$this->Km_ini->PlaceHolder = ew_RemoveHtml($this->Km_ini->FldCaption());

		// estado
		$this->estado->EditAttrs["class"] = "form-control";
		$this->estado->EditCustomAttributes = "";
		$this->estado->EditValue = ew_HtmlEncode($this->estado->CurrentValue);
		$this->estado->PlaceHolder = ew_RemoveHtml($this->estado->FldCaption());

		// id_vehiculo
		$this->id_vehiculo->EditAttrs["class"] = "form-control";
		$this->id_vehiculo->EditCustomAttributes = "";

		// id_tipo_carga
		$this->id_tipo_carga->EditAttrs["class"] = "form-control";
		$this->id_tipo_carga->EditCustomAttributes = "";

		// km_fin
		$this->km_fin->EditAttrs["class"] = "form-control";
		$this->km_fin->EditCustomAttributes = "";
		$this->km_fin->EditValue = ew_HtmlEncode($this->km_fin->CurrentValue);
		$this->km_fin->PlaceHolder = ew_RemoveHtml($this->km_fin->FldCaption());

		// fecha_fin
		$this->fecha_fin->EditAttrs["class"] = "form-control";
		$this->fecha_fin->EditCustomAttributes = "";
		$this->fecha_fin->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_fin->CurrentValue, 7));
		$this->fecha_fin->PlaceHolder = ew_RemoveHtml($this->fecha_fin->FldCaption());

		// adelanto
		$this->adelanto->EditAttrs["class"] = "form-control";
		$this->adelanto->EditCustomAttributes = "";
		$this->adelanto->EditValue = ew_HtmlEncode($this->adelanto->CurrentValue);
		$this->adelanto->PlaceHolder = ew_RemoveHtml($this->adelanto->FldCaption());
		if (strval($this->adelanto->EditValue) <> "" && is_numeric($this->adelanto->EditValue)) $this->adelanto->EditValue = ew_FormatNumber($this->adelanto->EditValue, -2, -2, -2, -2);

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

		// porcentaje
		$this->porcentaje->EditAttrs["class"] = "form-control";
		$this->porcentaje->EditCustomAttributes = "";
		$this->porcentaje->EditValue = ew_HtmlEncode($this->porcentaje->CurrentValue);
		$this->porcentaje->PlaceHolder = ew_RemoveHtml($this->porcentaje->FldCaption());
		if (strval($this->porcentaje->EditValue) <> "" && is_numeric($this->porcentaje->EditValue)) $this->porcentaje->EditValue = ew_FormatNumber($this->porcentaje->EditValue, -2, -1, -2, 0);

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
					if ($this->fecha_ini->Exportable) $Doc->ExportCaption($this->fecha_ini);
					if ($this->id_cliente->Exportable) $Doc->ExportCaption($this->id_cliente);
					if ($this->id_localidad_origen->Exportable) $Doc->ExportCaption($this->id_localidad_origen);
					if ($this->Origen->Exportable) $Doc->ExportCaption($this->Origen);
					if ($this->id_localidad_destino->Exportable) $Doc->ExportCaption($this->id_localidad_destino);
					if ($this->Destino->Exportable) $Doc->ExportCaption($this->Destino);
					if ($this->Km_ini->Exportable) $Doc->ExportCaption($this->Km_ini);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
					if ($this->id_vehiculo->Exportable) $Doc->ExportCaption($this->id_vehiculo);
					if ($this->id_tipo_carga->Exportable) $Doc->ExportCaption($this->id_tipo_carga);
					if ($this->km_fin->Exportable) $Doc->ExportCaption($this->km_fin);
					if ($this->fecha_fin->Exportable) $Doc->ExportCaption($this->fecha_fin);
					if ($this->adelanto->Exportable) $Doc->ExportCaption($this->adelanto);
					if ($this->kg_carga->Exportable) $Doc->ExportCaption($this->kg_carga);
					if ($this->tarifa->Exportable) $Doc->ExportCaption($this->tarifa);
					if ($this->porcentaje->Exportable) $Doc->ExportCaption($this->porcentaje);
				} else {
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
						if ($this->fecha_ini->Exportable) $Doc->ExportField($this->fecha_ini);
						if ($this->id_cliente->Exportable) $Doc->ExportField($this->id_cliente);
						if ($this->id_localidad_origen->Exportable) $Doc->ExportField($this->id_localidad_origen);
						if ($this->Origen->Exportable) $Doc->ExportField($this->Origen);
						if ($this->id_localidad_destino->Exportable) $Doc->ExportField($this->id_localidad_destino);
						if ($this->Destino->Exportable) $Doc->ExportField($this->Destino);
						if ($this->Km_ini->Exportable) $Doc->ExportField($this->Km_ini);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
						if ($this->id_vehiculo->Exportable) $Doc->ExportField($this->id_vehiculo);
						if ($this->id_tipo_carga->Exportable) $Doc->ExportField($this->id_tipo_carga);
						if ($this->km_fin->Exportable) $Doc->ExportField($this->km_fin);
						if ($this->fecha_fin->Exportable) $Doc->ExportField($this->fecha_fin);
						if ($this->adelanto->Exportable) $Doc->ExportField($this->adelanto);
						if ($this->kg_carga->Exportable) $Doc->ExportField($this->kg_carga);
						if ($this->tarifa->Exportable) $Doc->ExportField($this->tarifa);
						if ($this->porcentaje->Exportable) $Doc->ExportField($this->porcentaje);
					} else {
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
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `hoja_rutas`";
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

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;
		if (preg_match('/^x(\d)*_id_cliente$/', $id)) {
			$sSqlWrk = "SELECT `direccion` AS FIELD0, `direccion` AS FIELD1, `id_localidad` AS FIELD2, `id_localidad` AS FIELD3 FROM `clientes`";
			$sWhereWrk = "(`codigo` = " . ew_AdjustSql($val) . ")";

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_cliente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `razon_social` ASC";
			if ($rs = ew_LoadRecordset($sSqlWrk)) {
				while ($rs && !$rs->EOF) {
					$ar = array();
					$this->Origen->setDbValue($rs->fields[0]);
					$this->Destino->setDbValue($rs->fields[1]);
					$this->id_localidad_origen->setDbValue($rs->fields[2]);
					$this->id_localidad_destino->setDbValue($rs->fields[3]);
					$this->RowType == EW_ROWTYPE_EDIT;
					$this->RenderEditRow();
					$ar[] = ($this->Origen->AutoFillOriginalValue) ? $this->Origen->CurrentValue : $this->Origen->EditValue;
					$ar[] = ($this->Destino->AutoFillOriginalValue) ? $this->Destino->CurrentValue : $this->Destino->EditValue;
					$ar[] = $this->id_localidad_origen->CurrentValue;
					$ar[] = $this->id_localidad_destino->CurrentValue;
					$rowcnt += 1;
					$rsarr[] = $ar;
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if (preg_match('/^x(\d)*_id_tipo_carga$/', $id)) {
			$sSqlWrk = "SELECT `precio_base` AS FIELD0, `porcentaje_comision` AS FIELD1 FROM `tipo_cargas`";
			$sWhereWrk = "(`codigo` = " . ew_AdjustSql($val) . ")";

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tipo_carga, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Tipo_carga` ASC";
			if ($rs = ew_LoadRecordset($sSqlWrk)) {
				while ($rs && !$rs->EOF) {
					$ar = array();
					$this->tarifa->setDbValue($rs->fields[0]);
					$this->porcentaje->setDbValue($rs->fields[1]);
					$this->RowType == EW_ROWTYPE_EDIT;
					$this->RenderEditRow();
					$ar[] = ($this->tarifa->AutoFillOriginalValue) ? $this->tarifa->CurrentValue : $this->tarifa->EditValue;
					$ar[] = ($this->porcentaje->AutoFillOriginalValue) ? $this->porcentaje->CurrentValue : $this->porcentaje->EditValue;
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
