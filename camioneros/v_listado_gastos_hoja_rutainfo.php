<?php

// Global variable for table object
$v_listado_gastos_hoja_ruta = NULL;

//
// Table class for v_listado_gastos_hoja_ruta
//
class cv_listado_gastos_hoja_ruta extends cTable {
	var $Patente;
	var $modelo;
	var $marca;
	var $chofer;
	var $categoria_chofer;
	var $categoria_guardia;
	var $guarda;
	var $Origen;
	var $Destino;
	var $Km_ini;
	var $km_fin;
	var $estado;
	var $fecha_ini;
	var $fecha_fin;
	var $fecha_gasto;
	var $nro_gasto;
	var $detalle_gasto;
	var $importe_gasto;
	var $total;
	var $tipo_gasto;
	var $razon_social;
	var $prov_desde;
	var $prov_hasta;
	var $loc_desde;
	var $cp_desde;
	var $loc_hasta;
	var $cp_hasta;
	var $nro_cliente;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'v_listado_gastos_hoja_ruta';
		$this->TableName = 'v_listado_gastos_hoja_ruta';
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

		// Patente
		$this->Patente = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_Patente', 'Patente', '`Patente`', '`Patente`', 200, -1, FALSE, '`Patente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Patente'] = &$this->Patente;

		// modelo
		$this->modelo = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_modelo', 'modelo', '`modelo`', '`modelo`', 3, -1, FALSE, '`modelo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->modelo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['modelo'] = &$this->modelo;

		// marca
		$this->marca = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_marca', 'marca', '`marca`', '`marca`', 200, -1, FALSE, '`marca`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['marca'] = &$this->marca;

		// chofer
		$this->chofer = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_chofer', 'chofer', '`chofer`', '`chofer`', 200, -1, FALSE, '`chofer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['chofer'] = &$this->chofer;

		// categoria_chofer
		$this->categoria_chofer = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_categoria_chofer', 'categoria_chofer', '`categoria_chofer`', '`categoria_chofer`', 3, -1, FALSE, '`categoria_chofer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->categoria_chofer->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['categoria_chofer'] = &$this->categoria_chofer;

		// categoria_guardia
		$this->categoria_guardia = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_categoria_guardia', 'categoria_guardia', '`categoria_guardia`', '`categoria_guardia`', 3, -1, FALSE, '`categoria_guardia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->categoria_guardia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['categoria_guardia'] = &$this->categoria_guardia;

		// guarda
		$this->guarda = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_guarda', 'guarda', '`guarda`', '`guarda`', 200, -1, FALSE, '`guarda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['guarda'] = &$this->guarda;

		// Origen
		$this->Origen = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_Origen', 'Origen', '`Origen`', '`Origen`', 200, -1, FALSE, '`Origen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Origen'] = &$this->Origen;

		// Destino
		$this->Destino = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_Destino', 'Destino', '`Destino`', '`Destino`', 200, -1, FALSE, '`Destino`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Destino'] = &$this->Destino;

		// Km_ini
		$this->Km_ini = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_Km_ini', 'Km_ini', '`Km_ini`', '`Km_ini`', 19, -1, FALSE, '`Km_ini`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Km_ini->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Km_ini'] = &$this->Km_ini;

		// km_fin
		$this->km_fin = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_km_fin', 'km_fin', '`km_fin`', '`km_fin`', 19, -1, FALSE, '`km_fin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->km_fin->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['km_fin'] = &$this->km_fin;

		// estado
		$this->estado = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_estado', 'estado', '`estado`', '`estado`', 200, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['estado'] = &$this->estado;

		// fecha_ini
		$this->fecha_ini = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_fecha_ini', 'fecha_ini', '`fecha_ini`', 'DATE_FORMAT(`fecha_ini`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_ini`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_ini->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_ini'] = &$this->fecha_ini;

		// fecha_fin
		$this->fecha_fin = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_fecha_fin', 'fecha_fin', '`fecha_fin`', 'DATE_FORMAT(`fecha_fin`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_fin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_fin->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_fin'] = &$this->fecha_fin;

		// fecha_gasto
		$this->fecha_gasto = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_fecha_gasto', 'fecha_gasto', '`fecha_gasto`', 'DATE_FORMAT(`fecha_gasto`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_gasto->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_gasto'] = &$this->fecha_gasto;

		// nro_gasto
		$this->nro_gasto = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_nro_gasto', 'nro_gasto', '`nro_gasto`', '`nro_gasto`', 3, -1, FALSE, '`nro_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nro_gasto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nro_gasto'] = &$this->nro_gasto;

		// detalle_gasto
		$this->detalle_gasto = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_detalle_gasto', 'detalle_gasto', '`detalle_gasto`', '`detalle_gasto`', 200, -1, FALSE, '`detalle_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['detalle_gasto'] = &$this->detalle_gasto;

		// importe_gasto
		$this->importe_gasto = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_importe_gasto', 'importe_gasto', '`importe_gasto`', '`importe_gasto`', 131, -1, FALSE, '`importe_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->importe_gasto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importe_gasto'] = &$this->importe_gasto;

		// total
		$this->total = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_total', 'total', '`total`', '`total`', 131, -1, FALSE, '`total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total'] = &$this->total;

		// tipo_gasto
		$this->tipo_gasto = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_tipo_gasto', 'tipo_gasto', '`tipo_gasto`', '`tipo_gasto`', 200, -1, FALSE, '`tipo_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tipo_gasto'] = &$this->tipo_gasto;

		// razon_social
		$this->razon_social = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_razon_social', 'razon_social', '`razon_social`', '`razon_social`', 200, -1, FALSE, '`razon_social`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['razon_social'] = &$this->razon_social;

		// prov_desde
		$this->prov_desde = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_prov_desde', 'prov_desde', '`prov_desde`', '`prov_desde`', 200, -1, FALSE, '`prov_desde`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['prov_desde'] = &$this->prov_desde;

		// prov_hasta
		$this->prov_hasta = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_prov_hasta', 'prov_hasta', '`prov_hasta`', '`prov_hasta`', 200, -1, FALSE, '`prov_hasta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['prov_hasta'] = &$this->prov_hasta;

		// loc_desde
		$this->loc_desde = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_loc_desde', 'loc_desde', '`loc_desde`', '`loc_desde`', 200, -1, FALSE, '`loc_desde`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['loc_desde'] = &$this->loc_desde;

		// cp_desde
		$this->cp_desde = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_cp_desde', 'cp_desde', '`cp_desde`', '`cp_desde`', 3, -1, FALSE, '`cp_desde`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cp_desde->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cp_desde'] = &$this->cp_desde;

		// loc_hasta
		$this->loc_hasta = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_loc_hasta', 'loc_hasta', '`loc_hasta`', '`loc_hasta`', 200, -1, FALSE, '`loc_hasta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['loc_hasta'] = &$this->loc_hasta;

		// cp_hasta
		$this->cp_hasta = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_cp_hasta', 'cp_hasta', '`cp_hasta`', '`cp_hasta`', 3, -1, FALSE, '`cp_hasta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cp_hasta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cp_hasta'] = &$this->cp_hasta;

		// nro_cliente
		$this->nro_cliente = new cField('v_listado_gastos_hoja_ruta', 'v_listado_gastos_hoja_ruta', 'x_nro_cliente', 'nro_cliente', '`nro_cliente`', '`nro_cliente`', 3, -1, FALSE, '`nro_cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nro_cliente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nro_cliente'] = &$this->nro_cliente;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_listado_gastos_hoja_ruta`";
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
	var $UpdateTable = "`v_listado_gastos_hoja_ruta`";

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
			if (array_key_exists('nro_gasto', $rs))
				ew_AddFilter($where, ew_QuotedName('nro_gasto') . '=' . ew_QuotedValue($rs['nro_gasto'], $this->nro_gasto->FldDataType));
			if (array_key_exists('nro_cliente', $rs))
				ew_AddFilter($where, ew_QuotedName('nro_cliente') . '=' . ew_QuotedValue($rs['nro_cliente'], $this->nro_cliente->FldDataType));
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
		return "`nro_gasto` = @nro_gasto@ AND `nro_cliente` = @nro_cliente@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nro_gasto->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nro_gasto@", ew_AdjustSql($this->nro_gasto->CurrentValue), $sKeyFilter); // Replace key value
		if (!is_numeric($this->nro_cliente->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nro_cliente@", ew_AdjustSql($this->nro_cliente->CurrentValue), $sKeyFilter); // Replace key value
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
			return "v_listado_gastos_hoja_rutalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "v_listado_gastos_hoja_rutalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("v_listado_gastos_hoja_rutaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("v_listado_gastos_hoja_rutaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "v_listado_gastos_hoja_rutaadd.php?" . $this->UrlParm($parm);
		else
			return "v_listado_gastos_hoja_rutaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("v_listado_gastos_hoja_rutaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("v_listado_gastos_hoja_rutaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("v_listado_gastos_hoja_rutadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nro_gasto->CurrentValue)) {
			$sUrl .= "nro_gasto=" . urlencode($this->nro_gasto->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->nro_cliente->CurrentValue)) {
			$sUrl .= "&nro_cliente=" . urlencode($this->nro_cliente->CurrentValue);
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
			$arKey[] = @$_GET["nro_gasto"]; // nro_gasto
			$arKey[] = @$_GET["nro_cliente"]; // nro_cliente
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // nro_gasto
				continue;
			if (!is_numeric($key[1])) // nro_cliente
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
			$this->nro_gasto->CurrentValue = $key[0];
			$this->nro_cliente->CurrentValue = $key[1];
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
		$this->Patente->setDbValue($rs->fields('Patente'));
		$this->modelo->setDbValue($rs->fields('modelo'));
		$this->marca->setDbValue($rs->fields('marca'));
		$this->chofer->setDbValue($rs->fields('chofer'));
		$this->categoria_chofer->setDbValue($rs->fields('categoria_chofer'));
		$this->categoria_guardia->setDbValue($rs->fields('categoria_guardia'));
		$this->guarda->setDbValue($rs->fields('guarda'));
		$this->Origen->setDbValue($rs->fields('Origen'));
		$this->Destino->setDbValue($rs->fields('Destino'));
		$this->Km_ini->setDbValue($rs->fields('Km_ini'));
		$this->km_fin->setDbValue($rs->fields('km_fin'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->fecha_ini->setDbValue($rs->fields('fecha_ini'));
		$this->fecha_fin->setDbValue($rs->fields('fecha_fin'));
		$this->fecha_gasto->setDbValue($rs->fields('fecha_gasto'));
		$this->nro_gasto->setDbValue($rs->fields('nro_gasto'));
		$this->detalle_gasto->setDbValue($rs->fields('detalle_gasto'));
		$this->importe_gasto->setDbValue($rs->fields('importe_gasto'));
		$this->total->setDbValue($rs->fields('total'));
		$this->tipo_gasto->setDbValue($rs->fields('tipo_gasto'));
		$this->razon_social->setDbValue($rs->fields('razon_social'));
		$this->prov_desde->setDbValue($rs->fields('prov_desde'));
		$this->prov_hasta->setDbValue($rs->fields('prov_hasta'));
		$this->loc_desde->setDbValue($rs->fields('loc_desde'));
		$this->cp_desde->setDbValue($rs->fields('cp_desde'));
		$this->loc_hasta->setDbValue($rs->fields('loc_hasta'));
		$this->cp_hasta->setDbValue($rs->fields('cp_hasta'));
		$this->nro_cliente->setDbValue($rs->fields('nro_cliente'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// Patente
		// modelo
		// marca
		// chofer
		// categoria_chofer
		// categoria_guardia
		// guarda
		// Origen
		// Destino
		// Km_ini
		// km_fin
		// estado
		// fecha_ini
		// fecha_fin
		// fecha_gasto
		// nro_gasto
		// detalle_gasto
		// importe_gasto
		// total
		// tipo_gasto
		// razon_social
		// prov_desde
		// prov_hasta
		// loc_desde
		// cp_desde
		// loc_hasta
		// cp_hasta
		// nro_cliente
		// Patente

		$this->Patente->ViewValue = $this->Patente->CurrentValue;
		$this->Patente->ViewCustomAttributes = "";

		// modelo
		$this->modelo->ViewValue = $this->modelo->CurrentValue;
		$this->modelo->ViewCustomAttributes = "";

		// marca
		$this->marca->ViewValue = $this->marca->CurrentValue;
		$this->marca->ViewCustomAttributes = "";

		// chofer
		$this->chofer->ViewValue = $this->chofer->CurrentValue;
		$this->chofer->ViewCustomAttributes = "";

		// categoria_chofer
		$this->categoria_chofer->ViewValue = $this->categoria_chofer->CurrentValue;
		$this->categoria_chofer->ViewCustomAttributes = "";

		// categoria_guardia
		$this->categoria_guardia->ViewValue = $this->categoria_guardia->CurrentValue;
		$this->categoria_guardia->ViewCustomAttributes = "";

		// guarda
		$this->guarda->ViewValue = $this->guarda->CurrentValue;
		$this->guarda->ViewCustomAttributes = "";

		// Origen
		$this->Origen->ViewValue = $this->Origen->CurrentValue;
		$this->Origen->ViewCustomAttributes = "";

		// Destino
		$this->Destino->ViewValue = $this->Destino->CurrentValue;
		$this->Destino->ViewCustomAttributes = "";

		// Km_ini
		$this->Km_ini->ViewValue = $this->Km_ini->CurrentValue;
		$this->Km_ini->ViewCustomAttributes = "";

		// km_fin
		$this->km_fin->ViewValue = $this->km_fin->CurrentValue;
		$this->km_fin->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// fecha_ini
		$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
		$this->fecha_ini->ViewValue = ew_FormatDateTime($this->fecha_ini->ViewValue, 7);
		$this->fecha_ini->ViewCustomAttributes = "";

		// fecha_fin
		$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
		$this->fecha_fin->ViewValue = ew_FormatDateTime($this->fecha_fin->ViewValue, 7);
		$this->fecha_fin->ViewCustomAttributes = "";

		// fecha_gasto
		$this->fecha_gasto->ViewValue = $this->fecha_gasto->CurrentValue;
		$this->fecha_gasto->ViewValue = ew_FormatDateTime($this->fecha_gasto->ViewValue, 7);
		$this->fecha_gasto->ViewCustomAttributes = "";

		// nro_gasto
		$this->nro_gasto->ViewValue = $this->nro_gasto->CurrentValue;
		$this->nro_gasto->ViewCustomAttributes = "";

		// detalle_gasto
		$this->detalle_gasto->ViewValue = $this->detalle_gasto->CurrentValue;
		$this->detalle_gasto->ViewCustomAttributes = "";

		// importe_gasto
		$this->importe_gasto->ViewValue = $this->importe_gasto->CurrentValue;
		$this->importe_gasto->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// tipo_gasto
		$this->tipo_gasto->ViewValue = $this->tipo_gasto->CurrentValue;
		$this->tipo_gasto->ViewCustomAttributes = "";

		// razon_social
		$this->razon_social->ViewValue = $this->razon_social->CurrentValue;
		$this->razon_social->ViewCustomAttributes = "";

		// prov_desde
		$this->prov_desde->ViewValue = $this->prov_desde->CurrentValue;
		$this->prov_desde->ViewCustomAttributes = "";

		// prov_hasta
		$this->prov_hasta->ViewValue = $this->prov_hasta->CurrentValue;
		$this->prov_hasta->ViewCustomAttributes = "";

		// loc_desde
		$this->loc_desde->ViewValue = $this->loc_desde->CurrentValue;
		$this->loc_desde->ViewCustomAttributes = "";

		// cp_desde
		$this->cp_desde->ViewValue = $this->cp_desde->CurrentValue;
		$this->cp_desde->ViewCustomAttributes = "";

		// loc_hasta
		$this->loc_hasta->ViewValue = $this->loc_hasta->CurrentValue;
		$this->loc_hasta->ViewCustomAttributes = "";

		// cp_hasta
		$this->cp_hasta->ViewValue = $this->cp_hasta->CurrentValue;
		$this->cp_hasta->ViewCustomAttributes = "";

		// nro_cliente
		$this->nro_cliente->ViewValue = $this->nro_cliente->CurrentValue;
		$this->nro_cliente->ViewCustomAttributes = "";

		// Patente
		$this->Patente->LinkCustomAttributes = "";
		$this->Patente->HrefValue = "";
		$this->Patente->TooltipValue = "";

		// modelo
		$this->modelo->LinkCustomAttributes = "";
		$this->modelo->HrefValue = "";
		$this->modelo->TooltipValue = "";

		// marca
		$this->marca->LinkCustomAttributes = "";
		$this->marca->HrefValue = "";
		$this->marca->TooltipValue = "";

		// chofer
		$this->chofer->LinkCustomAttributes = "";
		$this->chofer->HrefValue = "";
		$this->chofer->TooltipValue = "";

		// categoria_chofer
		$this->categoria_chofer->LinkCustomAttributes = "";
		$this->categoria_chofer->HrefValue = "";
		$this->categoria_chofer->TooltipValue = "";

		// categoria_guardia
		$this->categoria_guardia->LinkCustomAttributes = "";
		$this->categoria_guardia->HrefValue = "";
		$this->categoria_guardia->TooltipValue = "";

		// guarda
		$this->guarda->LinkCustomAttributes = "";
		$this->guarda->HrefValue = "";
		$this->guarda->TooltipValue = "";

		// Origen
		$this->Origen->LinkCustomAttributes = "";
		$this->Origen->HrefValue = "";
		$this->Origen->TooltipValue = "";

		// Destino
		$this->Destino->LinkCustomAttributes = "";
		$this->Destino->HrefValue = "";
		$this->Destino->TooltipValue = "";

		// Km_ini
		$this->Km_ini->LinkCustomAttributes = "";
		$this->Km_ini->HrefValue = "";
		$this->Km_ini->TooltipValue = "";

		// km_fin
		$this->km_fin->LinkCustomAttributes = "";
		$this->km_fin->HrefValue = "";
		$this->km_fin->TooltipValue = "";

		// estado
		$this->estado->LinkCustomAttributes = "";
		$this->estado->HrefValue = "";
		$this->estado->TooltipValue = "";

		// fecha_ini
		$this->fecha_ini->LinkCustomAttributes = "";
		$this->fecha_ini->HrefValue = "";
		$this->fecha_ini->TooltipValue = "";

		// fecha_fin
		$this->fecha_fin->LinkCustomAttributes = "";
		$this->fecha_fin->HrefValue = "";
		$this->fecha_fin->TooltipValue = "";

		// fecha_gasto
		$this->fecha_gasto->LinkCustomAttributes = "";
		$this->fecha_gasto->HrefValue = "";
		$this->fecha_gasto->TooltipValue = "";

		// nro_gasto
		$this->nro_gasto->LinkCustomAttributes = "";
		$this->nro_gasto->HrefValue = "";
		$this->nro_gasto->TooltipValue = "";

		// detalle_gasto
		$this->detalle_gasto->LinkCustomAttributes = "";
		$this->detalle_gasto->HrefValue = "";
		$this->detalle_gasto->TooltipValue = "";

		// importe_gasto
		$this->importe_gasto->LinkCustomAttributes = "";
		$this->importe_gasto->HrefValue = "";
		$this->importe_gasto->TooltipValue = "";

		// total
		$this->total->LinkCustomAttributes = "";
		$this->total->HrefValue = "";
		$this->total->TooltipValue = "";

		// tipo_gasto
		$this->tipo_gasto->LinkCustomAttributes = "";
		$this->tipo_gasto->HrefValue = "";
		$this->tipo_gasto->TooltipValue = "";

		// razon_social
		$this->razon_social->LinkCustomAttributes = "";
		$this->razon_social->HrefValue = "";
		$this->razon_social->TooltipValue = "";

		// prov_desde
		$this->prov_desde->LinkCustomAttributes = "";
		$this->prov_desde->HrefValue = "";
		$this->prov_desde->TooltipValue = "";

		// prov_hasta
		$this->prov_hasta->LinkCustomAttributes = "";
		$this->prov_hasta->HrefValue = "";
		$this->prov_hasta->TooltipValue = "";

		// loc_desde
		$this->loc_desde->LinkCustomAttributes = "";
		$this->loc_desde->HrefValue = "";
		$this->loc_desde->TooltipValue = "";

		// cp_desde
		$this->cp_desde->LinkCustomAttributes = "";
		$this->cp_desde->HrefValue = "";
		$this->cp_desde->TooltipValue = "";

		// loc_hasta
		$this->loc_hasta->LinkCustomAttributes = "";
		$this->loc_hasta->HrefValue = "";
		$this->loc_hasta->TooltipValue = "";

		// cp_hasta
		$this->cp_hasta->LinkCustomAttributes = "";
		$this->cp_hasta->HrefValue = "";
		$this->cp_hasta->TooltipValue = "";

		// nro_cliente
		$this->nro_cliente->LinkCustomAttributes = "";
		$this->nro_cliente->HrefValue = "";
		$this->nro_cliente->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Patente
		$this->Patente->EditAttrs["class"] = "form-control";
		$this->Patente->EditCustomAttributes = "";
		$this->Patente->EditValue = ew_HtmlEncode($this->Patente->CurrentValue);
		$this->Patente->PlaceHolder = ew_RemoveHtml($this->Patente->FldCaption());

		// modelo
		$this->modelo->EditAttrs["class"] = "form-control";
		$this->modelo->EditCustomAttributes = "";
		$this->modelo->EditValue = ew_HtmlEncode($this->modelo->CurrentValue);
		$this->modelo->PlaceHolder = ew_RemoveHtml($this->modelo->FldCaption());

		// marca
		$this->marca->EditAttrs["class"] = "form-control";
		$this->marca->EditCustomAttributes = "";
		$this->marca->EditValue = ew_HtmlEncode($this->marca->CurrentValue);
		$this->marca->PlaceHolder = ew_RemoveHtml($this->marca->FldCaption());

		// chofer
		$this->chofer->EditAttrs["class"] = "form-control";
		$this->chofer->EditCustomAttributes = "";
		$this->chofer->EditValue = ew_HtmlEncode($this->chofer->CurrentValue);
		$this->chofer->PlaceHolder = ew_RemoveHtml($this->chofer->FldCaption());

		// categoria_chofer
		$this->categoria_chofer->EditAttrs["class"] = "form-control";
		$this->categoria_chofer->EditCustomAttributes = "";
		$this->categoria_chofer->EditValue = ew_HtmlEncode($this->categoria_chofer->CurrentValue);
		$this->categoria_chofer->PlaceHolder = ew_RemoveHtml($this->categoria_chofer->FldCaption());

		// categoria_guardia
		$this->categoria_guardia->EditAttrs["class"] = "form-control";
		$this->categoria_guardia->EditCustomAttributes = "";
		$this->categoria_guardia->EditValue = ew_HtmlEncode($this->categoria_guardia->CurrentValue);
		$this->categoria_guardia->PlaceHolder = ew_RemoveHtml($this->categoria_guardia->FldCaption());

		// guarda
		$this->guarda->EditAttrs["class"] = "form-control";
		$this->guarda->EditCustomAttributes = "";
		$this->guarda->EditValue = ew_HtmlEncode($this->guarda->CurrentValue);
		$this->guarda->PlaceHolder = ew_RemoveHtml($this->guarda->FldCaption());

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

		// Km_ini
		$this->Km_ini->EditAttrs["class"] = "form-control";
		$this->Km_ini->EditCustomAttributes = "";
		$this->Km_ini->EditValue = ew_HtmlEncode($this->Km_ini->CurrentValue);
		$this->Km_ini->PlaceHolder = ew_RemoveHtml($this->Km_ini->FldCaption());

		// km_fin
		$this->km_fin->EditAttrs["class"] = "form-control";
		$this->km_fin->EditCustomAttributes = "";
		$this->km_fin->EditValue = ew_HtmlEncode($this->km_fin->CurrentValue);
		$this->km_fin->PlaceHolder = ew_RemoveHtml($this->km_fin->FldCaption());

		// estado
		$this->estado->EditAttrs["class"] = "form-control";
		$this->estado->EditCustomAttributes = "";
		$this->estado->EditValue = ew_HtmlEncode($this->estado->CurrentValue);
		$this->estado->PlaceHolder = ew_RemoveHtml($this->estado->FldCaption());

		// fecha_ini
		$this->fecha_ini->EditAttrs["class"] = "form-control";
		$this->fecha_ini->EditCustomAttributes = "";
		$this->fecha_ini->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_ini->CurrentValue, 7));
		$this->fecha_ini->PlaceHolder = ew_RemoveHtml($this->fecha_ini->FldCaption());

		// fecha_fin
		$this->fecha_fin->EditAttrs["class"] = "form-control";
		$this->fecha_fin->EditCustomAttributes = "";
		$this->fecha_fin->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_fin->CurrentValue, 7));
		$this->fecha_fin->PlaceHolder = ew_RemoveHtml($this->fecha_fin->FldCaption());

		// fecha_gasto
		$this->fecha_gasto->EditAttrs["class"] = "form-control";
		$this->fecha_gasto->EditCustomAttributes = "";
		$this->fecha_gasto->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_gasto->CurrentValue, 7));
		$this->fecha_gasto->PlaceHolder = ew_RemoveHtml($this->fecha_gasto->FldCaption());

		// nro_gasto
		$this->nro_gasto->EditAttrs["class"] = "form-control";
		$this->nro_gasto->EditCustomAttributes = "";
		$this->nro_gasto->EditValue = $this->nro_gasto->CurrentValue;
		$this->nro_gasto->ViewCustomAttributes = "";

		// detalle_gasto
		$this->detalle_gasto->EditAttrs["class"] = "form-control";
		$this->detalle_gasto->EditCustomAttributes = "";
		$this->detalle_gasto->EditValue = ew_HtmlEncode($this->detalle_gasto->CurrentValue);
		$this->detalle_gasto->PlaceHolder = ew_RemoveHtml($this->detalle_gasto->FldCaption());

		// importe_gasto
		$this->importe_gasto->EditAttrs["class"] = "form-control";
		$this->importe_gasto->EditCustomAttributes = "";
		$this->importe_gasto->EditValue = ew_HtmlEncode($this->importe_gasto->CurrentValue);
		$this->importe_gasto->PlaceHolder = ew_RemoveHtml($this->importe_gasto->FldCaption());
		if (strval($this->importe_gasto->EditValue) <> "" && is_numeric($this->importe_gasto->EditValue)) $this->importe_gasto->EditValue = ew_FormatNumber($this->importe_gasto->EditValue, -2, -1, -2, 0);

		// total
		$this->total->EditAttrs["class"] = "form-control";
		$this->total->EditCustomAttributes = "";
		$this->total->EditValue = ew_HtmlEncode($this->total->CurrentValue);
		$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
		if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

		// tipo_gasto
		$this->tipo_gasto->EditAttrs["class"] = "form-control";
		$this->tipo_gasto->EditCustomAttributes = "";
		$this->tipo_gasto->EditValue = ew_HtmlEncode($this->tipo_gasto->CurrentValue);
		$this->tipo_gasto->PlaceHolder = ew_RemoveHtml($this->tipo_gasto->FldCaption());

		// razon_social
		$this->razon_social->EditAttrs["class"] = "form-control";
		$this->razon_social->EditCustomAttributes = "";
		$this->razon_social->EditValue = ew_HtmlEncode($this->razon_social->CurrentValue);
		$this->razon_social->PlaceHolder = ew_RemoveHtml($this->razon_social->FldCaption());

		// prov_desde
		$this->prov_desde->EditAttrs["class"] = "form-control";
		$this->prov_desde->EditCustomAttributes = "";
		$this->prov_desde->EditValue = ew_HtmlEncode($this->prov_desde->CurrentValue);
		$this->prov_desde->PlaceHolder = ew_RemoveHtml($this->prov_desde->FldCaption());

		// prov_hasta
		$this->prov_hasta->EditAttrs["class"] = "form-control";
		$this->prov_hasta->EditCustomAttributes = "";
		$this->prov_hasta->EditValue = ew_HtmlEncode($this->prov_hasta->CurrentValue);
		$this->prov_hasta->PlaceHolder = ew_RemoveHtml($this->prov_hasta->FldCaption());

		// loc_desde
		$this->loc_desde->EditAttrs["class"] = "form-control";
		$this->loc_desde->EditCustomAttributes = "";
		$this->loc_desde->EditValue = ew_HtmlEncode($this->loc_desde->CurrentValue);
		$this->loc_desde->PlaceHolder = ew_RemoveHtml($this->loc_desde->FldCaption());

		// cp_desde
		$this->cp_desde->EditAttrs["class"] = "form-control";
		$this->cp_desde->EditCustomAttributes = "";
		$this->cp_desde->EditValue = ew_HtmlEncode($this->cp_desde->CurrentValue);
		$this->cp_desde->PlaceHolder = ew_RemoveHtml($this->cp_desde->FldCaption());

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

		// nro_cliente
		$this->nro_cliente->EditAttrs["class"] = "form-control";
		$this->nro_cliente->EditCustomAttributes = "";
		$this->nro_cliente->EditValue = $this->nro_cliente->CurrentValue;
		$this->nro_cliente->ViewCustomAttributes = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->importe_gasto->CurrentValue))
				$this->importe_gasto->Total += $this->importe_gasto->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->importe_gasto->CurrentValue = $this->importe_gasto->Total;
			$this->importe_gasto->ViewValue = $this->importe_gasto->CurrentValue;
			$this->importe_gasto->ViewCustomAttributes = "";
			$this->importe_gasto->HrefValue = ""; // Clear href value

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
					if ($this->Patente->Exportable) $Doc->ExportCaption($this->Patente);
					if ($this->modelo->Exportable) $Doc->ExportCaption($this->modelo);
					if ($this->marca->Exportable) $Doc->ExportCaption($this->marca);
					if ($this->chofer->Exportable) $Doc->ExportCaption($this->chofer);
					if ($this->categoria_chofer->Exportable) $Doc->ExportCaption($this->categoria_chofer);
					if ($this->categoria_guardia->Exportable) $Doc->ExportCaption($this->categoria_guardia);
					if ($this->guarda->Exportable) $Doc->ExportCaption($this->guarda);
					if ($this->Origen->Exportable) $Doc->ExportCaption($this->Origen);
					if ($this->Destino->Exportable) $Doc->ExportCaption($this->Destino);
					if ($this->Km_ini->Exportable) $Doc->ExportCaption($this->Km_ini);
					if ($this->km_fin->Exportable) $Doc->ExportCaption($this->km_fin);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
					if ($this->fecha_ini->Exportable) $Doc->ExportCaption($this->fecha_ini);
					if ($this->fecha_fin->Exportable) $Doc->ExportCaption($this->fecha_fin);
					if ($this->fecha_gasto->Exportable) $Doc->ExportCaption($this->fecha_gasto);
					if ($this->nro_gasto->Exportable) $Doc->ExportCaption($this->nro_gasto);
					if ($this->detalle_gasto->Exportable) $Doc->ExportCaption($this->detalle_gasto);
					if ($this->importe_gasto->Exportable) $Doc->ExportCaption($this->importe_gasto);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
					if ($this->tipo_gasto->Exportable) $Doc->ExportCaption($this->tipo_gasto);
					if ($this->razon_social->Exportable) $Doc->ExportCaption($this->razon_social);
					if ($this->prov_desde->Exportable) $Doc->ExportCaption($this->prov_desde);
					if ($this->prov_hasta->Exportable) $Doc->ExportCaption($this->prov_hasta);
					if ($this->loc_desde->Exportable) $Doc->ExportCaption($this->loc_desde);
					if ($this->cp_desde->Exportable) $Doc->ExportCaption($this->cp_desde);
					if ($this->loc_hasta->Exportable) $Doc->ExportCaption($this->loc_hasta);
					if ($this->cp_hasta->Exportable) $Doc->ExportCaption($this->cp_hasta);
					if ($this->nro_cliente->Exportable) $Doc->ExportCaption($this->nro_cliente);
				} else {
					if ($this->Patente->Exportable) $Doc->ExportCaption($this->Patente);
					if ($this->modelo->Exportable) $Doc->ExportCaption($this->modelo);
					if ($this->marca->Exportable) $Doc->ExportCaption($this->marca);
					if ($this->chofer->Exportable) $Doc->ExportCaption($this->chofer);
					if ($this->categoria_chofer->Exportable) $Doc->ExportCaption($this->categoria_chofer);
					if ($this->categoria_guardia->Exportable) $Doc->ExportCaption($this->categoria_guardia);
					if ($this->guarda->Exportable) $Doc->ExportCaption($this->guarda);
					if ($this->Origen->Exportable) $Doc->ExportCaption($this->Origen);
					if ($this->Destino->Exportable) $Doc->ExportCaption($this->Destino);
					if ($this->Km_ini->Exportable) $Doc->ExportCaption($this->Km_ini);
					if ($this->km_fin->Exportable) $Doc->ExportCaption($this->km_fin);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
					if ($this->fecha_ini->Exportable) $Doc->ExportCaption($this->fecha_ini);
					if ($this->fecha_fin->Exportable) $Doc->ExportCaption($this->fecha_fin);
					if ($this->fecha_gasto->Exportable) $Doc->ExportCaption($this->fecha_gasto);
					if ($this->nro_gasto->Exportable) $Doc->ExportCaption($this->nro_gasto);
					if ($this->detalle_gasto->Exportable) $Doc->ExportCaption($this->detalle_gasto);
					if ($this->importe_gasto->Exportable) $Doc->ExportCaption($this->importe_gasto);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
					if ($this->tipo_gasto->Exportable) $Doc->ExportCaption($this->tipo_gasto);
					if ($this->razon_social->Exportable) $Doc->ExportCaption($this->razon_social);
					if ($this->prov_desde->Exportable) $Doc->ExportCaption($this->prov_desde);
					if ($this->prov_hasta->Exportable) $Doc->ExportCaption($this->prov_hasta);
					if ($this->loc_desde->Exportable) $Doc->ExportCaption($this->loc_desde);
					if ($this->cp_desde->Exportable) $Doc->ExportCaption($this->cp_desde);
					if ($this->loc_hasta->Exportable) $Doc->ExportCaption($this->loc_hasta);
					if ($this->cp_hasta->Exportable) $Doc->ExportCaption($this->cp_hasta);
					if ($this->nro_cliente->Exportable) $Doc->ExportCaption($this->nro_cliente);
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
						if ($this->Patente->Exportable) $Doc->ExportField($this->Patente);
						if ($this->modelo->Exportable) $Doc->ExportField($this->modelo);
						if ($this->marca->Exportable) $Doc->ExportField($this->marca);
						if ($this->chofer->Exportable) $Doc->ExportField($this->chofer);
						if ($this->categoria_chofer->Exportable) $Doc->ExportField($this->categoria_chofer);
						if ($this->categoria_guardia->Exportable) $Doc->ExportField($this->categoria_guardia);
						if ($this->guarda->Exportable) $Doc->ExportField($this->guarda);
						if ($this->Origen->Exportable) $Doc->ExportField($this->Origen);
						if ($this->Destino->Exportable) $Doc->ExportField($this->Destino);
						if ($this->Km_ini->Exportable) $Doc->ExportField($this->Km_ini);
						if ($this->km_fin->Exportable) $Doc->ExportField($this->km_fin);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
						if ($this->fecha_ini->Exportable) $Doc->ExportField($this->fecha_ini);
						if ($this->fecha_fin->Exportable) $Doc->ExportField($this->fecha_fin);
						if ($this->fecha_gasto->Exportable) $Doc->ExportField($this->fecha_gasto);
						if ($this->nro_gasto->Exportable) $Doc->ExportField($this->nro_gasto);
						if ($this->detalle_gasto->Exportable) $Doc->ExportField($this->detalle_gasto);
						if ($this->importe_gasto->Exportable) $Doc->ExportField($this->importe_gasto);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
						if ($this->tipo_gasto->Exportable) $Doc->ExportField($this->tipo_gasto);
						if ($this->razon_social->Exportable) $Doc->ExportField($this->razon_social);
						if ($this->prov_desde->Exportable) $Doc->ExportField($this->prov_desde);
						if ($this->prov_hasta->Exportable) $Doc->ExportField($this->prov_hasta);
						if ($this->loc_desde->Exportable) $Doc->ExportField($this->loc_desde);
						if ($this->cp_desde->Exportable) $Doc->ExportField($this->cp_desde);
						if ($this->loc_hasta->Exportable) $Doc->ExportField($this->loc_hasta);
						if ($this->cp_hasta->Exportable) $Doc->ExportField($this->cp_hasta);
						if ($this->nro_cliente->Exportable) $Doc->ExportField($this->nro_cliente);
					} else {
						if ($this->Patente->Exportable) $Doc->ExportField($this->Patente);
						if ($this->modelo->Exportable) $Doc->ExportField($this->modelo);
						if ($this->marca->Exportable) $Doc->ExportField($this->marca);
						if ($this->chofer->Exportable) $Doc->ExportField($this->chofer);
						if ($this->categoria_chofer->Exportable) $Doc->ExportField($this->categoria_chofer);
						if ($this->categoria_guardia->Exportable) $Doc->ExportField($this->categoria_guardia);
						if ($this->guarda->Exportable) $Doc->ExportField($this->guarda);
						if ($this->Origen->Exportable) $Doc->ExportField($this->Origen);
						if ($this->Destino->Exportable) $Doc->ExportField($this->Destino);
						if ($this->Km_ini->Exportable) $Doc->ExportField($this->Km_ini);
						if ($this->km_fin->Exportable) $Doc->ExportField($this->km_fin);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
						if ($this->fecha_ini->Exportable) $Doc->ExportField($this->fecha_ini);
						if ($this->fecha_fin->Exportable) $Doc->ExportField($this->fecha_fin);
						if ($this->fecha_gasto->Exportable) $Doc->ExportField($this->fecha_gasto);
						if ($this->nro_gasto->Exportable) $Doc->ExportField($this->nro_gasto);
						if ($this->detalle_gasto->Exportable) $Doc->ExportField($this->detalle_gasto);
						if ($this->importe_gasto->Exportable) $Doc->ExportField($this->importe_gasto);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
						if ($this->tipo_gasto->Exportable) $Doc->ExportField($this->tipo_gasto);
						if ($this->razon_social->Exportable) $Doc->ExportField($this->razon_social);
						if ($this->prov_desde->Exportable) $Doc->ExportField($this->prov_desde);
						if ($this->prov_hasta->Exportable) $Doc->ExportField($this->prov_hasta);
						if ($this->loc_desde->Exportable) $Doc->ExportField($this->loc_desde);
						if ($this->cp_desde->Exportable) $Doc->ExportField($this->cp_desde);
						if ($this->loc_hasta->Exportable) $Doc->ExportField($this->loc_hasta);
						if ($this->cp_hasta->Exportable) $Doc->ExportField($this->cp_hasta);
						if ($this->nro_cliente->Exportable) $Doc->ExportField($this->nro_cliente);
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
				$Doc->ExportAggregate($this->Patente, '');
				$Doc->ExportAggregate($this->modelo, '');
				$Doc->ExportAggregate($this->marca, '');
				$Doc->ExportAggregate($this->chofer, '');
				$Doc->ExportAggregate($this->categoria_chofer, '');
				$Doc->ExportAggregate($this->categoria_guardia, '');
				$Doc->ExportAggregate($this->guarda, '');
				$Doc->ExportAggregate($this->Origen, '');
				$Doc->ExportAggregate($this->Destino, '');
				$Doc->ExportAggregate($this->Km_ini, '');
				$Doc->ExportAggregate($this->km_fin, '');
				$Doc->ExportAggregate($this->estado, '');
				$Doc->ExportAggregate($this->fecha_ini, '');
				$Doc->ExportAggregate($this->fecha_fin, '');
				$Doc->ExportAggregate($this->fecha_gasto, '');
				$Doc->ExportAggregate($this->nro_gasto, '');
				$Doc->ExportAggregate($this->detalle_gasto, '');
				$Doc->ExportAggregate($this->importe_gasto, 'TOTAL');
				$Doc->ExportAggregate($this->total, '');
				$Doc->ExportAggregate($this->tipo_gasto, '');
				$Doc->ExportAggregate($this->razon_social, '');
				$Doc->ExportAggregate($this->prov_desde, '');
				$Doc->ExportAggregate($this->prov_hasta, '');
				$Doc->ExportAggregate($this->loc_desde, '');
				$Doc->ExportAggregate($this->cp_desde, '');
				$Doc->ExportAggregate($this->loc_hasta, '');
				$Doc->ExportAggregate($this->cp_hasta, '');
				$Doc->ExportAggregate($this->nro_cliente, '');
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
