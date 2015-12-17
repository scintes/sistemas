<?php

// Global variable for table object
$trabajos = NULL;

//
// Table class for trabajos
//
class ctrabajos extends cTable {
	var $nro_orden;
	var $fecha_recepcion;
	var $cliente;
	var $id_tipo_cliente;
	var $tel;
	var $cel;
	var $objetos;
	var $detalle_a_realizar;
	var $fecha_entrega;
	var $observaciones;
	var $id_estado;
	var $precio;
	var $entrega;
	var $saldo;
	var $foto1;
	var $foto2;
	var $usuario;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'trabajos';
		$this->TableName = 'trabajos';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// nro_orden
		$this->nro_orden = new cField('trabajos', 'trabajos', 'x_nro_orden', 'nro_orden', '`nro_orden`', '`nro_orden`', 3, -1, FALSE, '`nro_orden`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nro_orden->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nro_orden'] = &$this->nro_orden;

		// fecha_recepcion
		$this->fecha_recepcion = new cField('trabajos', 'trabajos', 'x_fecha_recepcion', 'fecha_recepcion', '`fecha_recepcion`', 'DATE_FORMAT(`fecha_recepcion`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_recepcion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_recepcion->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_recepcion'] = &$this->fecha_recepcion;

		// cliente
		$this->cliente = new cField('trabajos', 'trabajos', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 200, -1, FALSE, '`cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cliente'] = &$this->cliente;

		// id_tipo_cliente
		$this->id_tipo_cliente = new cField('trabajos', 'trabajos', 'x_id_tipo_cliente', 'id_tipo_cliente', '`id_tipo_cliente`', '`id_tipo_cliente`', 3, -1, FALSE, '`id_tipo_cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_tipo_cliente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tipo_cliente'] = &$this->id_tipo_cliente;

		// tel
		$this->tel = new cField('trabajos', 'trabajos', 'x_tel', 'tel', '`tel`', '`tel`', 200, -1, FALSE, '`tel`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tel'] = &$this->tel;

		// cel
		$this->cel = new cField('trabajos', 'trabajos', 'x_cel', 'cel', '`cel`', '`cel`', 200, -1, FALSE, '`cel`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cel'] = &$this->cel;

		// objetos
		$this->objetos = new cField('trabajos', 'trabajos', 'x_objetos', 'objetos', '`objetos`', '`objetos`', 200, -1, FALSE, '`objetos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['objetos'] = &$this->objetos;

		// detalle_a_realizar
		$this->detalle_a_realizar = new cField('trabajos', 'trabajos', 'x_detalle_a_realizar', 'detalle_a_realizar', '`detalle_a_realizar`', '`detalle_a_realizar`', 201, -1, FALSE, '`detalle_a_realizar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['detalle_a_realizar'] = &$this->detalle_a_realizar;

		// fecha_entrega
		$this->fecha_entrega = new cField('trabajos', 'trabajos', 'x_fecha_entrega', 'fecha_entrega', '`fecha_entrega`', 'DATE_FORMAT(`fecha_entrega`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_entrega`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_entrega->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_entrega'] = &$this->fecha_entrega;

		// observaciones
		$this->observaciones = new cField('trabajos', 'trabajos', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 201, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['observaciones'] = &$this->observaciones;

		// id_estado
		$this->id_estado = new cField('trabajos', 'trabajos', 'x_id_estado', 'id_estado', '`id_estado`', '`id_estado`', 3, -1, FALSE, '`id_estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_estado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_estado'] = &$this->id_estado;

		// precio
		$this->precio = new cField('trabajos', 'trabajos', 'x_precio', 'precio', '`precio`', '`precio`', 131, -1, FALSE, '`precio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->precio->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['precio'] = &$this->precio;

		// entrega
		$this->entrega = new cField('trabajos', 'trabajos', 'x_entrega', 'entrega', '`entrega`', '`entrega`', 131, -1, FALSE, '`entrega`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->entrega->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['entrega'] = &$this->entrega;

		// saldo
		$this->saldo = new cField('trabajos', 'trabajos', 'x_saldo', 'saldo', '`saldo`', '`saldo`', 131, -1, FALSE, '`saldo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->saldo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo'] = &$this->saldo;

		// foto1
		$this->foto1 = new cField('trabajos', 'trabajos', 'x_foto1', 'foto1', '`foto1`', '`foto1`', 200, -1, TRUE, '`foto1`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['foto1'] = &$this->foto1;

		// foto2
		$this->foto2 = new cField('trabajos', 'trabajos', 'x_foto2', 'foto2', '`foto2`', '`foto2`', 200, -1, TRUE, '`foto2`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['foto2'] = &$this->foto2;

		// usuario
		$this->usuario = new cField('trabajos', 'trabajos', 'x_usuario', 'usuario', '`usuario`', '`usuario`', 200, -1, FALSE, '`usuario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['usuario'] = &$this->usuario;
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
	function SqlFrom() { // From
		return "`trabajos`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
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
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
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
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`trabajos`";

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
			if (array_key_exists('nro_orden', $rs))
				ew_AddFilter($where, ew_QuotedName('nro_orden') . '=' . ew_QuotedValue($rs['nro_orden'], $this->nro_orden->FldDataType));
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
		return "`nro_orden` = @nro_orden@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nro_orden->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nro_orden@", ew_AdjustSql($this->nro_orden->CurrentValue), $sKeyFilter); // Replace key value
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
			return "trabajoslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "trabajoslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("trabajosview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("trabajosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "trabajosadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("trabajosedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("trabajosadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("trabajosdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nro_orden->CurrentValue)) {
			$sUrl .= "nro_orden=" . urlencode($this->nro_orden->CurrentValue);
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
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
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
			$arKeys[] = @$_GET["nro_orden"]; // nro_orden

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
			$this->nro_orden->CurrentValue = $key;
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
		$this->nro_orden->setDbValue($rs->fields('nro_orden'));
		$this->fecha_recepcion->setDbValue($rs->fields('fecha_recepcion'));
		$this->cliente->setDbValue($rs->fields('cliente'));
		$this->id_tipo_cliente->setDbValue($rs->fields('id_tipo_cliente'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->cel->setDbValue($rs->fields('cel'));
		$this->objetos->setDbValue($rs->fields('objetos'));
		$this->detalle_a_realizar->setDbValue($rs->fields('detalle_a_realizar'));
		$this->fecha_entrega->setDbValue($rs->fields('fecha_entrega'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->id_estado->setDbValue($rs->fields('id_estado'));
		$this->precio->setDbValue($rs->fields('precio'));
		$this->entrega->setDbValue($rs->fields('entrega'));
		$this->saldo->setDbValue($rs->fields('saldo'));
		$this->foto1->Upload->DbValue = $rs->fields('foto1');
		$this->foto2->Upload->DbValue = $rs->fields('foto2');
		$this->usuario->setDbValue($rs->fields('usuario'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nro_orden
		// fecha_recepcion
		// cliente
		// id_tipo_cliente
		// tel
		// cel
		// objetos
		// detalle_a_realizar
		// fecha_entrega
		// observaciones
		// id_estado
		// precio
		// entrega
		// saldo
		// foto1
		// foto2
		// usuario

		$this->usuario->CellCssStyle = "white-space: nowrap;";

		// nro_orden
		$this->nro_orden->ViewValue = $this->nro_orden->CurrentValue;
		$this->nro_orden->ViewCustomAttributes = "";

		// fecha_recepcion
		$this->fecha_recepcion->ViewValue = $this->fecha_recepcion->CurrentValue;
		$this->fecha_recepcion->ViewValue = ew_FormatDateTime($this->fecha_recepcion->ViewValue, 7);
		$this->fecha_recepcion->ViewCustomAttributes = "";

		// cliente
		$this->cliente->ViewValue = $this->cliente->CurrentValue;
		$this->cliente->ViewCustomAttributes = "";

		// id_tipo_cliente
		if (strval($this->id_tipo_cliente->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_tipo_cliente->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT DISTINCT `codigo`, `tipo_cliente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_clientes`";
		$sWhereWrk = "";
		$lookuptblfilter = "`activo`='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_tipo_cliente, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `tipo_cliente` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_tipo_cliente->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->id_tipo_cliente->ViewValue = $this->id_tipo_cliente->CurrentValue;
			}
		} else {
			$this->id_tipo_cliente->ViewValue = NULL;
		}
		$this->id_tipo_cliente->ViewCustomAttributes = "";

		// tel
		$this->tel->ViewValue = $this->tel->CurrentValue;
		$this->tel->ViewCustomAttributes = "";

		// cel
		$this->cel->ViewValue = $this->cel->CurrentValue;
		$this->cel->ViewCustomAttributes = "";

		// objetos
		$this->objetos->ViewValue = $this->objetos->CurrentValue;
		$this->objetos->ViewCustomAttributes = "";

		// detalle_a_realizar
		$this->detalle_a_realizar->ViewValue = $this->detalle_a_realizar->CurrentValue;
		$this->detalle_a_realizar->ViewCustomAttributes = "";

		// fecha_entrega
		$this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
		$this->fecha_entrega->ViewValue = ew_FormatDateTime($this->fecha_entrega->ViewValue, 7);
		$this->fecha_entrega->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// id_estado
		if (strval($this->id_estado->CurrentValue) <> "") {
			$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->id_estado->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT DISTINCT `codigo`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estados`";
		$sWhereWrk = "";
		$lookuptblfilter = "`activo`='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_estado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `codigo` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_estado->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->id_estado->ViewValue = $this->id_estado->CurrentValue;
			}
		} else {
			$this->id_estado->ViewValue = NULL;
		}
		$this->id_estado->ViewCustomAttributes = "";

		// precio
		$this->precio->ViewValue = $this->precio->CurrentValue;
		$this->precio->ViewValue = ew_FormatCurrency($this->precio->ViewValue, 2, -2, -2, -2);
		$this->precio->ViewCustomAttributes = "";

		// entrega
		$this->entrega->ViewValue = $this->entrega->CurrentValue;
		$this->entrega->ViewValue = ew_FormatCurrency($this->entrega->ViewValue, 2, -2, -2, -2);
		$this->entrega->ViewCustomAttributes = "";

		// saldo
		$this->saldo->ViewValue = $this->saldo->CurrentValue;
		$this->saldo->ViewValue = ew_FormatCurrency($this->saldo->ViewValue, 2, -2, -2, -2);
		$this->saldo->ViewCustomAttributes = "";

		// foto1
		if (!ew_Empty($this->foto1->Upload->DbValue)) {
			$this->foto1->ImageAlt = $this->foto1->FldAlt();
			$this->foto1->ViewValue = ew_UploadPathEx(FALSE, $this->foto1->UploadPath) . $this->foto1->Upload->DbValue;
		} else {
			$this->foto1->ViewValue = "";
		}
		$this->foto1->ViewCustomAttributes = "";

		// foto2
		if (!ew_Empty($this->foto2->Upload->DbValue)) {
			$this->foto2->ImageAlt = $this->foto2->FldAlt();
			$this->foto2->ViewValue = ew_UploadPathEx(FALSE, $this->foto2->UploadPath) . $this->foto2->Upload->DbValue;
		} else {
			$this->foto2->ViewValue = "";
		}
		$this->foto2->ViewCustomAttributes = "";

		// usuario
		$this->usuario->ViewValue = $this->usuario->CurrentValue;
		$this->usuario->ViewCustomAttributes = "";

		// nro_orden
		$this->nro_orden->LinkCustomAttributes = "";
		$this->nro_orden->HrefValue = "";
		$this->nro_orden->TooltipValue = "";

		// fecha_recepcion
		$this->fecha_recepcion->LinkCustomAttributes = "";
		$this->fecha_recepcion->HrefValue = "";
		$this->fecha_recepcion->TooltipValue = "";

		// cliente
		$this->cliente->LinkCustomAttributes = "";
		$this->cliente->HrefValue = "";
		$this->cliente->TooltipValue = "";

		// id_tipo_cliente
		$this->id_tipo_cliente->LinkCustomAttributes = "";
		$this->id_tipo_cliente->HrefValue = "";
		$this->id_tipo_cliente->TooltipValue = "";

		// tel
		$this->tel->LinkCustomAttributes = "";
		$this->tel->HrefValue = "";
		$this->tel->TooltipValue = "";

		// cel
		$this->cel->LinkCustomAttributes = "";
		$this->cel->HrefValue = "";
		$this->cel->TooltipValue = "";

		// objetos
		$this->objetos->LinkCustomAttributes = "";
		$this->objetos->HrefValue = "";
		$this->objetos->TooltipValue = "";

		// detalle_a_realizar
		$this->detalle_a_realizar->LinkCustomAttributes = "";
		$this->detalle_a_realizar->HrefValue = "";
		$this->detalle_a_realizar->TooltipValue = "";

		// fecha_entrega
		$this->fecha_entrega->LinkCustomAttributes = "";
		$this->fecha_entrega->HrefValue = "";
		$this->fecha_entrega->TooltipValue = "";

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

		// id_estado
		$this->id_estado->LinkCustomAttributes = "";
		$this->id_estado->HrefValue = "";
		$this->id_estado->TooltipValue = "";

		// precio
		$this->precio->LinkCustomAttributes = "";
		$this->precio->HrefValue = "";
		$this->precio->TooltipValue = "";

		// entrega
		$this->entrega->LinkCustomAttributes = "";
		$this->entrega->HrefValue = "";
		$this->entrega->TooltipValue = "";

		// saldo
		$this->saldo->LinkCustomAttributes = "";
		$this->saldo->HrefValue = "";
		$this->saldo->TooltipValue = "";

		// foto1
		$this->foto1->LinkCustomAttributes = "";
		$this->foto1->HrefValue = "";
		$this->foto1->HrefValue2 = $this->foto1->UploadPath . $this->foto1->Upload->DbValue;
		$this->foto1->TooltipValue = "";

		// foto2
		$this->foto2->LinkCustomAttributes = "";
		$this->foto2->HrefValue = "";
		$this->foto2->HrefValue2 = $this->foto2->UploadPath . $this->foto2->Upload->DbValue;
		$this->foto2->TooltipValue = "";

		// usuario
		$this->usuario->LinkCustomAttributes = "";
		$this->usuario->HrefValue = "";
		$this->usuario->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->precio->CurrentValue))
				$this->precio->Total += $this->precio->CurrentValue; // Accumulate total
			if (is_numeric($this->entrega->CurrentValue))
				$this->entrega->Total += $this->entrega->CurrentValue; // Accumulate total
			if (is_numeric($this->saldo->CurrentValue))
				$this->saldo->Total += $this->saldo->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->precio->CurrentValue = $this->precio->Total;
			$this->precio->ViewValue = $this->precio->CurrentValue;
			$this->precio->ViewValue = ew_FormatCurrency($this->precio->ViewValue, 2, -2, -2, -2);
			$this->precio->ViewCustomAttributes = "";
			$this->precio->HrefValue = ""; // Clear href value
			$this->entrega->CurrentValue = $this->entrega->Total;
			$this->entrega->ViewValue = $this->entrega->CurrentValue;
			$this->entrega->ViewValue = ew_FormatCurrency($this->entrega->ViewValue, 2, -2, -2, -2);
			$this->entrega->ViewCustomAttributes = "";
			$this->entrega->HrefValue = ""; // Clear href value
			$this->saldo->CurrentValue = $this->saldo->Total;
			$this->saldo->ViewValue = $this->saldo->CurrentValue;
			$this->saldo->ViewValue = ew_FormatCurrency($this->saldo->ViewValue, 2, -2, -2, -2);
			$this->saldo->ViewCustomAttributes = "";
			$this->saldo->HrefValue = ""; // Clear href value
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->nro_orden->Exportable) $Doc->ExportCaption($this->nro_orden);
				if ($this->fecha_recepcion->Exportable) $Doc->ExportCaption($this->fecha_recepcion);
				if ($this->cliente->Exportable) $Doc->ExportCaption($this->cliente);
				if ($this->id_tipo_cliente->Exportable) $Doc->ExportCaption($this->id_tipo_cliente);
				if ($this->tel->Exportable) $Doc->ExportCaption($this->tel);
				if ($this->cel->Exportable) $Doc->ExportCaption($this->cel);
				if ($this->objetos->Exportable) $Doc->ExportCaption($this->objetos);
				if ($this->detalle_a_realizar->Exportable) $Doc->ExportCaption($this->detalle_a_realizar);
				if ($this->fecha_entrega->Exportable) $Doc->ExportCaption($this->fecha_entrega);
				if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
				if ($this->id_estado->Exportable) $Doc->ExportCaption($this->id_estado);
				if ($this->precio->Exportable) $Doc->ExportCaption($this->precio);
				if ($this->entrega->Exportable) $Doc->ExportCaption($this->entrega);
				if ($this->saldo->Exportable) $Doc->ExportCaption($this->saldo);
				if ($this->foto1->Exportable) $Doc->ExportCaption($this->foto1);
				if ($this->foto2->Exportable) $Doc->ExportCaption($this->foto2);
			} else {
				if ($this->nro_orden->Exportable) $Doc->ExportCaption($this->nro_orden);
				if ($this->fecha_recepcion->Exportable) $Doc->ExportCaption($this->fecha_recepcion);
				if ($this->cliente->Exportable) $Doc->ExportCaption($this->cliente);
				if ($this->id_tipo_cliente->Exportable) $Doc->ExportCaption($this->id_tipo_cliente);
				if ($this->tel->Exportable) $Doc->ExportCaption($this->tel);
				if ($this->cel->Exportable) $Doc->ExportCaption($this->cel);
				if ($this->objetos->Exportable) $Doc->ExportCaption($this->objetos);
				if ($this->fecha_entrega->Exportable) $Doc->ExportCaption($this->fecha_entrega);
				if ($this->id_estado->Exportable) $Doc->ExportCaption($this->id_estado);
				if ($this->precio->Exportable) $Doc->ExportCaption($this->precio);
				if ($this->entrega->Exportable) $Doc->ExportCaption($this->entrega);
				if ($this->saldo->Exportable) $Doc->ExportCaption($this->saldo);
				if ($this->foto1->Exportable) $Doc->ExportCaption($this->foto1);
				if ($this->foto2->Exportable) $Doc->ExportCaption($this->foto2);
			}
			$Doc->EndExportRow();
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
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->nro_orden->Exportable) $Doc->ExportField($this->nro_orden);
					if ($this->fecha_recepcion->Exportable) $Doc->ExportField($this->fecha_recepcion);
					if ($this->cliente->Exportable) $Doc->ExportField($this->cliente);
					if ($this->id_tipo_cliente->Exportable) $Doc->ExportField($this->id_tipo_cliente);
					if ($this->tel->Exportable) $Doc->ExportField($this->tel);
					if ($this->cel->Exportable) $Doc->ExportField($this->cel);
					if ($this->objetos->Exportable) $Doc->ExportField($this->objetos);
					if ($this->detalle_a_realizar->Exportable) $Doc->ExportField($this->detalle_a_realizar);
					if ($this->fecha_entrega->Exportable) $Doc->ExportField($this->fecha_entrega);
					if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
					if ($this->id_estado->Exportable) $Doc->ExportField($this->id_estado);
					if ($this->precio->Exportable) $Doc->ExportField($this->precio);
					if ($this->entrega->Exportable) $Doc->ExportField($this->entrega);
					if ($this->saldo->Exportable) $Doc->ExportField($this->saldo);
					if ($this->foto1->Exportable) $Doc->ExportField($this->foto1);
					if ($this->foto2->Exportable) $Doc->ExportField($this->foto2);
				} else {
					if ($this->nro_orden->Exportable) $Doc->ExportField($this->nro_orden);
					if ($this->fecha_recepcion->Exportable) $Doc->ExportField($this->fecha_recepcion);
					if ($this->cliente->Exportable) $Doc->ExportField($this->cliente);
					if ($this->id_tipo_cliente->Exportable) $Doc->ExportField($this->id_tipo_cliente);
					if ($this->tel->Exportable) $Doc->ExportField($this->tel);
					if ($this->cel->Exportable) $Doc->ExportField($this->cel);
					if ($this->objetos->Exportable) $Doc->ExportField($this->objetos);
					if ($this->fecha_entrega->Exportable) $Doc->ExportField($this->fecha_entrega);
					if ($this->id_estado->Exportable) $Doc->ExportField($this->id_estado);
					if ($this->precio->Exportable) $Doc->ExportField($this->precio);
					if ($this->entrega->Exportable) $Doc->ExportField($this->entrega);
					if ($this->saldo->Exportable) $Doc->ExportField($this->saldo);
					if ($this->foto1->Exportable) $Doc->ExportField($this->foto1);
					if ($this->foto2->Exportable) $Doc->ExportField($this->foto2);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}

		// Export aggregates (horizontal format only)
		if ($Doc->Horizontal) {
			$this->RowType = EW_ROWTYPE_AGGREGATE;
			$this->ResetAttrs();
			$this->AggregateListRow();
			$Doc->BeginExportRow(-1);
			$Doc->ExportAggregate($this->nro_orden, '');
			$Doc->ExportAggregate($this->fecha_recepcion, '');
			$Doc->ExportAggregate($this->cliente, '');
			$Doc->ExportAggregate($this->id_tipo_cliente, '');
			$Doc->ExportAggregate($this->tel, '');
			$Doc->ExportAggregate($this->cel, '');
			$Doc->ExportAggregate($this->objetos, '');
			$Doc->ExportAggregate($this->fecha_entrega, '');
			$Doc->ExportAggregate($this->id_estado, '');
			$Doc->ExportAggregate($this->precio, 'TOTAL');
			$Doc->ExportAggregate($this->entrega, 'TOTAL');
			$Doc->ExportAggregate($this->saldo, 'TOTAL');
			$Doc->ExportAggregate($this->foto1, '');
			$Doc->ExportAggregate($this->foto2, '');
			$Doc->EndExportRow();
		}
		$Doc->ExportTableFooter();
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
		//----------------------------
		// codigo agregado por sergio 
	// --- if ($rsnew["Percentage"] > 100) 

			$rsnew["saldo"] = $rsnew["precio"] - $rsnew["entrega"];
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
		 // Linea agregada por sergio

		 $rsnew["saldo"] = $rsnew["precio"] - $rsnew["entrega"];
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
