<?php

// Global variable for table object
$v_trabajos_a_entregar = NULL;

//
// Table class for v_trabajos_a_entregar
//
class cv_trabajos_a_entregar extends cTable {
	var $cliente;
	var $objetos;
	var $precio;
	var $entrega;
	var $saldo;
	var $estado;
	var $fecha_recepcion;
	var $fecha_entrega;
	var $foto1;
	var $foto2;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'v_trabajos_a_entregar';
		$this->TableName = 'v_trabajos_a_entregar';
		$this->TableType = 'VIEW';
		$this->ExportAll = TRUE;
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

		// cliente
		$this->cliente = new cField('v_trabajos_a_entregar', 'v_trabajos_a_entregar', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 200, -1, FALSE, '`cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cliente'] = &$this->cliente;

		// objetos
		$this->objetos = new cField('v_trabajos_a_entregar', 'v_trabajos_a_entregar', 'x_objetos', 'objetos', '`objetos`', '`objetos`', 200, -1, FALSE, '`objetos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['objetos'] = &$this->objetos;

		// precio
		$this->precio = new cField('v_trabajos_a_entregar', 'v_trabajos_a_entregar', 'x_precio', 'precio', '`precio`', '`precio`', 131, -1, FALSE, '`precio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->precio->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['precio'] = &$this->precio;

		// entrega
		$this->entrega = new cField('v_trabajos_a_entregar', 'v_trabajos_a_entregar', 'x_entrega', 'entrega', '`entrega`', '`entrega`', 131, -1, FALSE, '`entrega`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->entrega->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['entrega'] = &$this->entrega;

		// saldo
		$this->saldo = new cField('v_trabajos_a_entregar', 'v_trabajos_a_entregar', 'x_saldo', 'saldo', '`saldo`', '`saldo`', 131, -1, FALSE, '`saldo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->saldo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo'] = &$this->saldo;

		// estado
		$this->estado = new cField('v_trabajos_a_entregar', 'v_trabajos_a_entregar', 'x_estado', 'estado', '`estado`', '`estado`', 200, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['estado'] = &$this->estado;

		// fecha_recepcion
		$this->fecha_recepcion = new cField('v_trabajos_a_entregar', 'v_trabajos_a_entregar', 'x_fecha_recepcion', 'fecha_recepcion', '`fecha_recepcion`', 'DATE_FORMAT(`fecha_recepcion`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_recepcion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_recepcion->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_recepcion'] = &$this->fecha_recepcion;

		// fecha_entrega
		$this->fecha_entrega = new cField('v_trabajos_a_entregar', 'v_trabajos_a_entregar', 'x_fecha_entrega', 'fecha_entrega', '`fecha_entrega`', 'DATE_FORMAT(`fecha_entrega`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_entrega`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_entrega->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_entrega'] = &$this->fecha_entrega;

		// foto1
		$this->foto1 = new cField('v_trabajos_a_entregar', 'v_trabajos_a_entregar', 'x_foto1', 'foto1', '`foto1`', '`foto1`', 200, -1, TRUE, '`foto1`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['foto1'] = &$this->foto1;

		// foto2
		$this->foto2 = new cField('v_trabajos_a_entregar', 'v_trabajos_a_entregar', 'x_foto2', 'foto2', '`foto2`', '`foto2`', 200, -1, TRUE, '`foto2`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['foto2'] = &$this->foto2;
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
		return "`v_trabajos_a_entregar`";
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
	var $UpdateTable = "`v_trabajos_a_entregar`";

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
			return "v_trabajos_a_entregarlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "v_trabajos_a_entregarlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("v_trabajos_a_entregarview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("v_trabajos_a_entregarview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "v_trabajos_a_entregaradd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("v_trabajos_a_entregaredit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("v_trabajos_a_entregaradd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("v_trabajos_a_entregardelete.php", $this->UrlParm());
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
		$this->cliente->setDbValue($rs->fields('cliente'));
		$this->objetos->setDbValue($rs->fields('objetos'));
		$this->precio->setDbValue($rs->fields('precio'));
		$this->entrega->setDbValue($rs->fields('entrega'));
		$this->saldo->setDbValue($rs->fields('saldo'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->fecha_recepcion->setDbValue($rs->fields('fecha_recepcion'));
		$this->fecha_entrega->setDbValue($rs->fields('fecha_entrega'));
		$this->foto1->Upload->DbValue = $rs->fields('foto1');
		$this->foto2->Upload->DbValue = $rs->fields('foto2');
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// cliente

		$this->cliente->CellCssStyle = "white-space: nowrap;";

		// objetos
		$this->objetos->CellCssStyle = "white-space: nowrap;";

		// precio
		$this->precio->CellCssStyle = "white-space: nowrap;";

		// entrega
		$this->entrega->CellCssStyle = "white-space: nowrap;";

		// saldo
		$this->saldo->CellCssStyle = "white-space: nowrap;";

		// estado
		$this->estado->CellCssStyle = "white-space: nowrap;";

		// fecha_recepcion
		$this->fecha_recepcion->CellCssStyle = "white-space: nowrap;";

		// fecha_entrega
		$this->fecha_entrega->CellCssStyle = "white-space: nowrap;";

		// foto1
		$this->foto1->CellCssStyle = "white-space: nowrap;";

		// foto2
		$this->foto2->CellCssStyle = "white-space: nowrap;";

		// cliente
		$this->cliente->ViewValue = $this->cliente->CurrentValue;
		$this->cliente->ViewCustomAttributes = "";

		// objetos
		$this->objetos->ViewValue = $this->objetos->CurrentValue;
		$this->objetos->ViewCustomAttributes = "";

		// precio
		$this->precio->ViewValue = $this->precio->CurrentValue;
		$this->precio->ViewCustomAttributes = "";

		// entrega
		$this->entrega->ViewValue = $this->entrega->CurrentValue;
		$this->entrega->ViewCustomAttributes = "";

		// saldo
		$this->saldo->ViewValue = $this->saldo->CurrentValue;
		$this->saldo->ViewCustomAttributes = "";

		// estado
		if (strval($this->estado->CurrentValue) <> "") {
			$sFilterWrk = "`estado`" . ew_SearchString("=", $this->estado->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT `estado`, `estado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estados`";
		$sWhereWrk = "";
		$lookuptblfilter = "`activo`='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->estado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `codigo` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->estado->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->estado->ViewValue = $this->estado->CurrentValue;
			}
		} else {
			$this->estado->ViewValue = NULL;
		}
		$this->estado->ViewCustomAttributes = "";

		// fecha_recepcion
		$this->fecha_recepcion->ViewValue = $this->fecha_recepcion->CurrentValue;
		$this->fecha_recepcion->ViewValue = ew_FormatDateTime($this->fecha_recepcion->ViewValue, 7);
		$this->fecha_recepcion->ViewCustomAttributes = "";

		// fecha_entrega
		$this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
		$this->fecha_entrega->ViewValue = ew_FormatDateTime($this->fecha_entrega->ViewValue, 7);
		$this->fecha_entrega->ViewCustomAttributes = "";

		// foto1
		if (!ew_Empty($this->foto1->Upload->DbValue)) {
			$this->foto1->ImageWidth = 40;
			$this->foto1->ImageHeight = 40;
			$this->foto1->ImageAlt = $this->foto1->FldAlt();
			$this->foto1->ViewValue = ew_UploadPathEx(FALSE, $this->foto1->UploadPath) . $this->foto1->Upload->DbValue;
		} else {
			$this->foto1->ViewValue = "";
		}
		$this->foto1->ViewCustomAttributes = "";

		// foto2
		if (!ew_Empty($this->foto2->Upload->DbValue)) {
			$this->foto2->ImageWidth = 10;
			$this->foto2->ImageHeight = 10;
			$this->foto2->ImageAlt = $this->foto2->FldAlt();
			$this->foto2->ViewValue = ew_UploadPathEx(FALSE, $this->foto2->UploadPath) . $this->foto2->Upload->DbValue;
		} else {
			$this->foto2->ViewValue = "";
		}
		$this->foto2->ViewCustomAttributes = "";

		// cliente
		$this->cliente->LinkCustomAttributes = "";
		$this->cliente->HrefValue = "";
		$this->cliente->TooltipValue = "";

		// objetos
		$this->objetos->LinkCustomAttributes = "";
		$this->objetos->HrefValue = "";
		$this->objetos->TooltipValue = "";

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

		// estado
		$this->estado->LinkCustomAttributes = "";
		$this->estado->HrefValue = "";
		$this->estado->TooltipValue = "";

		// fecha_recepcion
		$this->fecha_recepcion->LinkCustomAttributes = "";
		$this->fecha_recepcion->HrefValue = "";
		$this->fecha_recepcion->TooltipValue = "";

		// fecha_entrega
		$this->fecha_entrega->LinkCustomAttributes = "";
		$this->fecha_entrega->HrefValue = "";
		$this->fecha_entrega->TooltipValue = "";

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
			$this->precio->ViewCustomAttributes = "";
			$this->precio->HrefValue = ""; // Clear href value
			$this->entrega->CurrentValue = $this->entrega->Total;
			$this->entrega->ViewValue = $this->entrega->CurrentValue;
			$this->entrega->ViewCustomAttributes = "";
			$this->entrega->HrefValue = ""; // Clear href value
			$this->saldo->CurrentValue = $this->saldo->Total;
			$this->saldo->ViewValue = $this->saldo->CurrentValue;
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
				if ($this->cliente->Exportable) $Doc->ExportCaption($this->cliente);
				if ($this->objetos->Exportable) $Doc->ExportCaption($this->objetos);
				if ($this->precio->Exportable) $Doc->ExportCaption($this->precio);
				if ($this->entrega->Exportable) $Doc->ExportCaption($this->entrega);
				if ($this->saldo->Exportable) $Doc->ExportCaption($this->saldo);
				if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
				if ($this->fecha_recepcion->Exportable) $Doc->ExportCaption($this->fecha_recepcion);
				if ($this->fecha_entrega->Exportable) $Doc->ExportCaption($this->fecha_entrega);
				if ($this->foto1->Exportable) $Doc->ExportCaption($this->foto1);
				if ($this->foto2->Exportable) $Doc->ExportCaption($this->foto2);
			} else {
				if ($this->cliente->Exportable) $Doc->ExportCaption($this->cliente);
				if ($this->objetos->Exportable) $Doc->ExportCaption($this->objetos);
				if ($this->precio->Exportable) $Doc->ExportCaption($this->precio);
				if ($this->entrega->Exportable) $Doc->ExportCaption($this->entrega);
				if ($this->saldo->Exportable) $Doc->ExportCaption($this->saldo);
				if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
				if ($this->fecha_recepcion->Exportable) $Doc->ExportCaption($this->fecha_recepcion);
				if ($this->fecha_entrega->Exportable) $Doc->ExportCaption($this->fecha_entrega);
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
					if ($this->cliente->Exportable) $Doc->ExportField($this->cliente);
					if ($this->objetos->Exportable) $Doc->ExportField($this->objetos);
					if ($this->precio->Exportable) $Doc->ExportField($this->precio);
					if ($this->entrega->Exportable) $Doc->ExportField($this->entrega);
					if ($this->saldo->Exportable) $Doc->ExportField($this->saldo);
					if ($this->estado->Exportable) $Doc->ExportField($this->estado);
					if ($this->fecha_recepcion->Exportable) $Doc->ExportField($this->fecha_recepcion);
					if ($this->fecha_entrega->Exportable) $Doc->ExportField($this->fecha_entrega);
					if ($this->foto1->Exportable) $Doc->ExportField($this->foto1);
					if ($this->foto2->Exportable) $Doc->ExportField($this->foto2);
				} else {
					if ($this->cliente->Exportable) $Doc->ExportField($this->cliente);
					if ($this->objetos->Exportable) $Doc->ExportField($this->objetos);
					if ($this->precio->Exportable) $Doc->ExportField($this->precio);
					if ($this->entrega->Exportable) $Doc->ExportField($this->entrega);
					if ($this->saldo->Exportable) $Doc->ExportField($this->saldo);
					if ($this->estado->Exportable) $Doc->ExportField($this->estado);
					if ($this->fecha_recepcion->Exportable) $Doc->ExportField($this->fecha_recepcion);
					if ($this->fecha_entrega->Exportable) $Doc->ExportField($this->fecha_entrega);
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
			$Doc->ExportAggregate($this->cliente, '');
			$Doc->ExportAggregate($this->objetos, '');
			$Doc->ExportAggregate($this->precio, 'TOTAL');
			$Doc->ExportAggregate($this->entrega, 'TOTAL');
			$Doc->ExportAggregate($this->saldo, 'TOTAL');
			$Doc->ExportAggregate($this->estado, '');
			$Doc->ExportAggregate($this->fecha_recepcion, '');
			$Doc->ExportAggregate($this->fecha_entrega, '');
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
