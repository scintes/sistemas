<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php

// Global variable for table object
$Listado_Gastos_por_vehiculo = NULL;

//
// Table class for Listado Gastos por vehiculo
//
class cListado_Gastos_por_vehiculo extends cTableBase {
	var $nro_cliente;
	var $Patente;
	var $modelo;
	var $marca;
	var $chofer;
	var $categoria_chofer;
	var $guarda;
	var $categoria_guardia;
	var $Origen;
	var $fecha_ini;
	var $Km_ini;
	var $fecha_fin;
	var $km_fin;
	var $Destino;
	var $estado;
	var $nro_gasto;
	var $fecha_gasto;
	var $detalle_gasto;
	var $tipo_gasto;
	var $razon_social;
	var $prov_desde;
	var $prov_hasta;
	var $loc_desde;
	var $cp_desde;
	var $loc_hasta;
	var $cp_hasta;
	var $importe_gasto;
	var $total;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Listado_Gastos_por_vehiculo';
		$this->TableName = 'Listado Gastos por vehiculo';
		$this->TableType = 'REPORT';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// nro_cliente
		$this->nro_cliente = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_nro_cliente', 'nro_cliente', '`nro_cliente`', '`nro_cliente`', 3, -1, FALSE, '`nro_cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nro_cliente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nro_cliente'] = &$this->nro_cliente;

		// Patente
		$this->Patente = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_Patente', 'Patente', '`Patente`', '`Patente`', 200, -1, FALSE, '`Patente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Patente'] = &$this->Patente;

		// modelo
		$this->modelo = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_modelo', 'modelo', '`modelo`', '`modelo`', 3, -1, FALSE, '`modelo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->modelo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['modelo'] = &$this->modelo;

		// marca
		$this->marca = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_marca', 'marca', '`marca`', '`marca`', 200, -1, FALSE, '`marca`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['marca'] = &$this->marca;

		// chofer
		$this->chofer = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_chofer', 'chofer', '`chofer`', '`chofer`', 200, -1, FALSE, '`chofer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['chofer'] = &$this->chofer;

		// categoria_chofer
		$this->categoria_chofer = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_categoria_chofer', 'categoria_chofer', '`categoria_chofer`', '`categoria_chofer`', 3, -1, FALSE, '`categoria_chofer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->categoria_chofer->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['categoria_chofer'] = &$this->categoria_chofer;

		// guarda
		$this->guarda = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_guarda', 'guarda', '`guarda`', '`guarda`', 200, -1, FALSE, '`guarda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['guarda'] = &$this->guarda;

		// categoria_guardia
		$this->categoria_guardia = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_categoria_guardia', 'categoria_guardia', '`categoria_guardia`', '`categoria_guardia`', 3, -1, FALSE, '`categoria_guardia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->categoria_guardia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['categoria_guardia'] = &$this->categoria_guardia;

		// Origen
		$this->Origen = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_Origen', 'Origen', '`Origen`', '`Origen`', 200, -1, FALSE, '`Origen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Origen'] = &$this->Origen;

		// fecha_ini
		$this->fecha_ini = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_fecha_ini', 'fecha_ini', '`fecha_ini`', 'DATE_FORMAT(`fecha_ini`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_ini`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_ini->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_ini'] = &$this->fecha_ini;

		// Km_ini
		$this->Km_ini = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_Km_ini', 'Km_ini', '`Km_ini`', '`Km_ini`', 19, -1, FALSE, '`Km_ini`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Km_ini->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Km_ini'] = &$this->Km_ini;

		// fecha_fin
		$this->fecha_fin = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_fecha_fin', 'fecha_fin', '`fecha_fin`', 'DATE_FORMAT(`fecha_fin`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_fin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_fin->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_fin'] = &$this->fecha_fin;

		// km_fin
		$this->km_fin = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_km_fin', 'km_fin', '`km_fin`', '`km_fin`', 19, -1, FALSE, '`km_fin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->km_fin->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['km_fin'] = &$this->km_fin;

		// Destino
		$this->Destino = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_Destino', 'Destino', '`Destino`', '`Destino`', 200, -1, FALSE, '`Destino`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Destino'] = &$this->Destino;

		// estado
		$this->estado = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_estado', 'estado', '`estado`', '`estado`', 200, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['estado'] = &$this->estado;

		// nro_gasto
		$this->nro_gasto = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_nro_gasto', 'nro_gasto', '`nro_gasto`', '`nro_gasto`', 3, -1, FALSE, '`nro_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nro_gasto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nro_gasto'] = &$this->nro_gasto;

		// fecha_gasto
		$this->fecha_gasto = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_fecha_gasto', 'fecha_gasto', '`fecha_gasto`', 'DATE_FORMAT(`fecha_gasto`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_gasto->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_gasto'] = &$this->fecha_gasto;

		// detalle_gasto
		$this->detalle_gasto = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_detalle_gasto', 'detalle_gasto', '`detalle_gasto`', '`detalle_gasto`', 200, -1, FALSE, '`detalle_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['detalle_gasto'] = &$this->detalle_gasto;

		// tipo_gasto
		$this->tipo_gasto = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_tipo_gasto', 'tipo_gasto', '`tipo_gasto`', '`tipo_gasto`', 200, -1, FALSE, '`tipo_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tipo_gasto'] = &$this->tipo_gasto;

		// razon_social
		$this->razon_social = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_razon_social', 'razon_social', '`razon_social`', '`razon_social`', 200, -1, FALSE, '`razon_social`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['razon_social'] = &$this->razon_social;

		// prov_desde
		$this->prov_desde = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_prov_desde', 'prov_desde', '`prov_desde`', '`prov_desde`', 200, -1, FALSE, '`prov_desde`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['prov_desde'] = &$this->prov_desde;

		// prov_hasta
		$this->prov_hasta = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_prov_hasta', 'prov_hasta', '`prov_hasta`', '`prov_hasta`', 200, -1, FALSE, '`prov_hasta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['prov_hasta'] = &$this->prov_hasta;

		// loc_desde
		$this->loc_desde = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_loc_desde', 'loc_desde', '`loc_desde`', '`loc_desde`', 200, -1, FALSE, '`loc_desde`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['loc_desde'] = &$this->loc_desde;

		// cp_desde
		$this->cp_desde = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_cp_desde', 'cp_desde', '`cp_desde`', '`cp_desde`', 3, -1, FALSE, '`cp_desde`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cp_desde->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cp_desde'] = &$this->cp_desde;

		// loc_hasta
		$this->loc_hasta = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_loc_hasta', 'loc_hasta', '`loc_hasta`', '`loc_hasta`', 200, -1, FALSE, '`loc_hasta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['loc_hasta'] = &$this->loc_hasta;

		// cp_hasta
		$this->cp_hasta = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_cp_hasta', 'cp_hasta', '`cp_hasta`', '`cp_hasta`', 3, -1, FALSE, '`cp_hasta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cp_hasta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cp_hasta'] = &$this->cp_hasta;

		// importe_gasto
		$this->importe_gasto = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_importe_gasto', 'importe_gasto', '`importe_gasto`', '`importe_gasto`', 131, -1, FALSE, '`importe_gasto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->importe_gasto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importe_gasto'] = &$this->importe_gasto;

		// total
		$this->total = new cField('Listado_Gastos_por_vehiculo', 'Listado Gastos por vehiculo', 'x_total', 'total', '`total`', '`total`', 131, -1, FALSE, '`total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total'] = &$this->total;
	}

	// Report group level SQL
	var $_SqlGroupSelect = "";

	function getSqlGroupSelect() { // Select
		return ($this->_SqlGroupSelect <> "") ? $this->_SqlGroupSelect : "SELECT DISTINCT `Patente` FROM `v_listado_gastos_hoja_ruta`";
	}

	function SqlGroupSelect() { // For backward compatibility
    	return $this->getSqlGroupSelect();
	}

	function setSqlGroupSelect($v) {
    	$this->_SqlGroupSelect = $v;
	}
	var $_SqlGroupWhere = "";

	function getSqlGroupWhere() { // Where
		return ($this->_SqlGroupWhere <> "") ? $this->_SqlGroupWhere : "";
	}

	function SqlGroupWhere() { // For backward compatibility
    	return $this->getSqlGroupWhere();
	}

	function setSqlGroupWhere($v) {
    	$this->_SqlGroupWhere = $v;
	}
	var $_SqlGroupGroupBy = "";

	function getSqlGroupGroupBy() { // Group By
		return ($this->_SqlGroupGroupBy <> "") ? $this->_SqlGroupGroupBy : "";
	}

	function SqlGroupGroupBy() { // For backward compatibility
    	return $this->getSqlGroupGroupBy();
	}

	function setSqlGroupGroupBy($v) {
    	$this->_SqlGroupGroupBy = $v;
	}
	var $_SqlGroupHaving = "";

	function getSqlGroupHaving() { // Having
		return ($this->_SqlGroupHaving <> "") ? $this->_SqlGroupHaving : "";
	}

	function SqlGroupHaving() { // For backward compatibility
    	return $this->getSqlGroupHaving();
	}

	function setSqlGroupHaving($v) {
    	$this->_SqlGroupHaving = $v;
	}
	var $_SqlGroupOrderBy = "";

	function getSqlGroupOrderBy() { // Order By
		return ($this->_SqlGroupOrderBy <> "") ? $this->_SqlGroupOrderBy : "`Patente` ASC";
	}

	function SqlGroupOrderBy() { // For backward compatibility
    	return $this->getSqlGroupOrderBy();
	}

	function setSqlGroupOrderBy($v) {
    	$this->_SqlGroupOrderBy = $v;
	}

	// Report detail level SQL
	var $_SqlDetailSelect = "";

	function getSqlDetailSelect() { // Select
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT * FROM `v_listado_gastos_hoja_ruta`";
	}

	function SqlDetailSelect() { // For backward compatibility
    	return $this->getSqlDetailSelect();
	}

	function setSqlDetailSelect($v) {
    	$this->_SqlDetailSelect = $v;
	}
	var $_SqlDetailWhere = "";

	function getSqlDetailWhere() { // Where
		return ($this->_SqlDetailWhere <> "") ? $this->_SqlDetailWhere : "";
	}

	function SqlDetailWhere() { // For backward compatibility
    	return $this->getSqlDetailWhere();
	}

	function setSqlDetailWhere($v) {
    	$this->_SqlDetailWhere = $v;
	}
	var $_SqlDetailGroupBy = "";

	function getSqlDetailGroupBy() { // Group By
		return ($this->_SqlDetailGroupBy <> "") ? $this->_SqlDetailGroupBy : "";
	}

	function SqlDetailGroupBy() { // For backward compatibility
    	return $this->getSqlDetailGroupBy();
	}

	function setSqlDetailGroupBy($v) {
    	$this->_SqlDetailGroupBy = $v;
	}
	var $_SqlDetailHaving = "";

	function getSqlDetailHaving() { // Having
		return ($this->_SqlDetailHaving <> "") ? $this->_SqlDetailHaving : "";
	}

	function SqlDetailHaving() { // For backward compatibility
    	return $this->getSqlDetailHaving();
	}

	function setSqlDetailHaving($v) {
    	$this->_SqlDetailHaving = $v;
	}
	var $_SqlDetailOrderBy = "";

	function getSqlDetailOrderBy() { // Order By
		return ($this->_SqlDetailOrderBy <> "") ? $this->_SqlDetailOrderBy : "`modelo` ASC";
	}

	function SqlDetailOrderBy() { // For backward compatibility
    	return $this->getSqlDetailOrderBy();
	}

	function setSqlDetailOrderBy($v) {
    	$this->_SqlDetailOrderBy = $v;
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

	// Report group SQL
	function GroupSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlGroupSelect(), $this->getSqlGroupWhere(),
			 $this->getSqlGroupGroupBy(), $this->getSqlGroupHaving(),
			 $this->getSqlGroupOrderBy(), $sFilter, $sSort);
	}

	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlDetailSelect(), $this->getSqlDetailWhere(),
			$this->getSqlDetailGroupBy(), $this->getSqlDetailHaving(),
			$this->getSqlDetailOrderBy(), $sFilter, $sSort);
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
			return "Listado_Gastos_por_vehiculoreport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "Listado_Gastos_por_vehiculoreport.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("", $this->UrlParm($parm));
		else
			return $this->KeyUrl("", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "?" . $this->UrlParm($parm);
		else
			return "";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nro_cliente->CurrentValue)) {
			$sUrl .= "nro_cliente=" . urlencode($this->nro_cliente->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->nro_gasto->CurrentValue)) {
			$sUrl .= "&nro_gasto=" . urlencode($this->nro_gasto->CurrentValue);
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
			$arKey[] = @$_GET["nro_cliente"]; // nro_cliente
			$arKey[] = @$_GET["nro_gasto"]; // nro_gasto
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // nro_cliente
				continue;
			if (!is_numeric($key[1])) // nro_gasto
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
			$this->nro_cliente->CurrentValue = $key[0];
			$this->nro_gasto->CurrentValue = $key[1];
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
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$Listado_Gastos_por_vehiculo_report = NULL; // Initialize page object first

class cListado_Gastos_por_vehiculo_report extends cListado_Gastos_por_vehiculo {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{DFBA9E6C-101B-48AB-A301-122D5C6C603B}";

	// Table name
	var $TableName = 'Listado Gastos por vehiculo';

	// Page object name
	var $PageObjName = 'Listado_Gastos_por_vehiculo_report';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		return TRUE;
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (Listado_Gastos_por_vehiculo)
		if (!isset($GLOBALS["Listado_Gastos_por_vehiculo"]) || get_class($GLOBALS["Listado_Gastos_por_vehiculo"]) == "cListado_Gastos_por_vehiculo") {
			$GLOBALS["Listado_Gastos_por_vehiculo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Listado_Gastos_por_vehiculo"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Listado Gastos por vehiculo', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT_REPORT;
		if ($this->Export <> "" && array_key_exists($this->Export, $EW_EXPORT_REPORT)) {
			$sContent = ob_get_contents();
			$fn = $EW_EXPORT_REPORT[$this->Export];
			$this->$fn($sContent);
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $RecCnt = 0;
	var $ReportSql = "";
	var $ReportFilter = "";
	var $DefaultFilter = "";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $MasterRecordExists;
	var $Command;
	var $DtlRecordCount;
	var $ReportGroups;
	var $ReportCounts;
	var $LevelBreak;
	var $ReportTotals;
	var $ReportMaxs;
	var $ReportMins;
	var $Recordset;
	var $DetailRecordset;
	var $RecordExists;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$this->ReportGroups = &ew_InitArray(2, NULL);
		$this->ReportCounts = &ew_InitArray(2, 0);
		$this->LevelBreak = &ew_InitArray(2, FALSE);
		$this->ReportTotals = &ew_Init2DArray(2, 21, 0);
		$this->ReportMaxs = &ew_Init2DArray(2, 21, 0);
		$this->ReportMins = &ew_Init2DArray(2, 21, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
		} else {
			if (!ew_CompareValue($this->Patente->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
			}
		}
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->importe_gasto->FormValue == $this->importe_gasto->CurrentValue && is_numeric(ew_StrToFloat($this->importe_gasto->CurrentValue)))
			$this->importe_gasto->CurrentValue = ew_StrToFloat($this->importe_gasto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nro_cliente
		// Patente
		// modelo
		// marca
		// chofer
		// categoria_chofer
		// guarda
		// categoria_guardia
		// Origen
		// fecha_ini
		// Km_ini
		// fecha_fin
		// km_fin
		// Destino
		// estado
		// nro_gasto
		// fecha_gasto
		// detalle_gasto
		// tipo_gasto
		// razon_social
		// prov_desde
		// prov_hasta
		// loc_desde
		// cp_desde
		// loc_hasta
		// cp_hasta
		// importe_gasto
		// total

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nro_cliente
			$this->nro_cliente->ViewValue = $this->nro_cliente->CurrentValue;
			$this->nro_cliente->ViewCustomAttributes = "";

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

			// guarda
			$this->guarda->ViewValue = $this->guarda->CurrentValue;
			$this->guarda->ViewCustomAttributes = "";

			// categoria_guardia
			$this->categoria_guardia->ViewValue = $this->categoria_guardia->CurrentValue;
			$this->categoria_guardia->ViewCustomAttributes = "";

			// Origen
			$this->Origen->ViewValue = $this->Origen->CurrentValue;
			$this->Origen->ViewCustomAttributes = "";

			// fecha_ini
			$this->fecha_ini->ViewValue = $this->fecha_ini->CurrentValue;
			$this->fecha_ini->ViewValue = ew_FormatDateTime($this->fecha_ini->ViewValue, 7);
			$this->fecha_ini->ViewCustomAttributes = "";

			// Km_ini
			$this->Km_ini->ViewValue = $this->Km_ini->CurrentValue;
			$this->Km_ini->ViewCustomAttributes = "";

			// fecha_fin
			$this->fecha_fin->ViewValue = $this->fecha_fin->CurrentValue;
			$this->fecha_fin->ViewValue = ew_FormatDateTime($this->fecha_fin->ViewValue, 7);
			$this->fecha_fin->ViewCustomAttributes = "";

			// km_fin
			$this->km_fin->ViewValue = $this->km_fin->CurrentValue;
			$this->km_fin->ViewCustomAttributes = "";

			// Destino
			$this->Destino->ViewValue = $this->Destino->CurrentValue;
			$this->Destino->ViewCustomAttributes = "";

			// estado
			$this->estado->ViewValue = $this->estado->CurrentValue;
			$this->estado->ViewCustomAttributes = "";

			// nro_gasto
			$this->nro_gasto->ViewValue = $this->nro_gasto->CurrentValue;
			$this->nro_gasto->ViewCustomAttributes = "";

			// fecha_gasto
			$this->fecha_gasto->ViewValue = $this->fecha_gasto->CurrentValue;
			$this->fecha_gasto->ViewValue = ew_FormatDateTime($this->fecha_gasto->ViewValue, 7);
			$this->fecha_gasto->ViewCustomAttributes = "";

			// detalle_gasto
			$this->detalle_gasto->ViewValue = $this->detalle_gasto->CurrentValue;
			$this->detalle_gasto->ViewCustomAttributes = "";

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

			// importe_gasto
			$this->importe_gasto->ViewValue = $this->importe_gasto->CurrentValue;
			$this->importe_gasto->ViewCustomAttributes = "";

			// total
			$this->total->ViewValue = $this->total->CurrentValue;
			$this->total->ViewCustomAttributes = "";

			// nro_cliente
			$this->nro_cliente->LinkCustomAttributes = "";
			$this->nro_cliente->HrefValue = "";
			$this->nro_cliente->TooltipValue = "";

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

			// Origen
			$this->Origen->LinkCustomAttributes = "";
			$this->Origen->HrefValue = "";
			$this->Origen->TooltipValue = "";

			// fecha_ini
			$this->fecha_ini->LinkCustomAttributes = "";
			$this->fecha_ini->HrefValue = "";
			$this->fecha_ini->TooltipValue = "";

			// Destino
			$this->Destino->LinkCustomAttributes = "";
			$this->Destino->HrefValue = "";
			$this->Destino->TooltipValue = "";

			// nro_gasto
			$this->nro_gasto->LinkCustomAttributes = "";
			$this->nro_gasto->HrefValue = "";
			$this->nro_gasto->TooltipValue = "";

			// fecha_gasto
			$this->fecha_gasto->LinkCustomAttributes = "";
			$this->fecha_gasto->HrefValue = "";
			$this->fecha_gasto->TooltipValue = "";

			// detalle_gasto
			$this->detalle_gasto->LinkCustomAttributes = "";
			$this->detalle_gasto->HrefValue = "";
			$this->detalle_gasto->TooltipValue = "";

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

			// importe_gasto
			$this->importe_gasto->LinkCustomAttributes = "";
			$this->importe_gasto->HrefValue = "";
			$this->importe_gasto->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("report", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Export report to HTML
	function ExportReportHtml($html) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');
		//echo $html;

	}

	// Export report to WORD
	function ExportReportWord($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-word' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
		echo $html;
	}

	// Export report to EXCEL
	function ExportReportExcel($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-excel' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		echo $html;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		$razon_social = 'CintesSoft Sistemas';
		$listado = 'Listado de gastos por vehiculo...';
		$header = "
	<table cellspacing='0' border=1 width='100%'>
		<thead>
			<tr class='ewTableHeader'>
				<td rowspan='2'>
					<image src='./phpimages/phpmkrlogo11.png' width='400' height='90'>
				</td>
				<td align='center'>
					<FONT FACE='impact' SIZE=5>".$razon_social."</font>
				</td>
			</tr>
			<tr class='ewTableHeader'>
				<td align='center'>".$listado."</td>
			</tr>    
		</thead>          
	</table>";
	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($Listado_Gastos_por_vehiculo_report)) $Listado_Gastos_por_vehiculo_report = new cListado_Gastos_por_vehiculo_report();

// Page init
$Listado_Gastos_por_vehiculo_report->Page_Init();

// Page main
$Listado_Gastos_por_vehiculo_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Listado_Gastos_por_vehiculo_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Listado_Gastos_por_vehiculo->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<div class="ewToolbar">
<?php if ($Listado_Gastos_por_vehiculo->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($Listado_Gastos_por_vehiculo->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
$Listado_Gastos_por_vehiculo_report->DefaultFilter = "";
$Listado_Gastos_por_vehiculo_report->ReportFilter = $Listado_Gastos_por_vehiculo_report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($Listado_Gastos_por_vehiculo_report->ReportFilter <> "") $Listado_Gastos_por_vehiculo_report->ReportFilter .= " AND ";
	$Listado_Gastos_por_vehiculo_report->ReportFilter .= "(0=1)";
}
if ($Listado_Gastos_por_vehiculo_report->DbDetailFilter <> "") {
	if ($Listado_Gastos_por_vehiculo_report->ReportFilter <> "") $Listado_Gastos_por_vehiculo_report->ReportFilter .= " AND ";
	$Listado_Gastos_por_vehiculo_report->ReportFilter .= "(" . $Listado_Gastos_por_vehiculo_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$Listado_Gastos_por_vehiculo->CurrentFilter = $Listado_Gastos_por_vehiculo_report->ReportFilter;
$Listado_Gastos_por_vehiculo_report->ReportSql = $Listado_Gastos_por_vehiculo->GroupSQL();

// Load recordset
$Listado_Gastos_por_vehiculo_report->Recordset = $conn->Execute($Listado_Gastos_por_vehiculo_report->ReportSql);
$Listado_Gastos_por_vehiculo_report->RecordExists = !$Listado_Gastos_por_vehiculo_report->Recordset->EOF;
?>
<?php if ($Listado_Gastos_por_vehiculo->Export == "") { ?>
<?php if ($Listado_Gastos_por_vehiculo_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $Listado_Gastos_por_vehiculo_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $Listado_Gastos_por_vehiculo_report->ShowPageHeader(); ?>
<table class="ewReportTable">
<?php

// Get First Row
if ($Listado_Gastos_por_vehiculo_report->RecordExists) {
	$Listado_Gastos_por_vehiculo->Patente->setDbValue($Listado_Gastos_por_vehiculo_report->Recordset->fields('Patente'));
	$Listado_Gastos_por_vehiculo_report->ReportGroups[0] = $Listado_Gastos_por_vehiculo->Patente->DbValue;
}
$Listado_Gastos_por_vehiculo_report->RecCnt = 0;
$Listado_Gastos_por_vehiculo_report->ReportCounts[0] = 0;
$Listado_Gastos_por_vehiculo_report->ChkLvlBreak();
while (!$Listado_Gastos_por_vehiculo_report->Recordset->EOF) {

	// Render for view
	$Listado_Gastos_por_vehiculo->RowType = EW_ROWTYPE_VIEW;
	$Listado_Gastos_por_vehiculo->ResetAttrs();
	$Listado_Gastos_por_vehiculo_report->RenderRow();

	// Show group headers
	if ($Listado_Gastos_por_vehiculo_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td class="ewGroupField"><?php echo $Listado_Gastos_por_vehiculo->Patente->FldCaption() ?></td>
	<td colspan=20 class="ewGroupName">
<span<?php echo $Listado_Gastos_por_vehiculo->Patente->ViewAttributes() ?>>
<?php echo $Listado_Gastos_por_vehiculo->Patente->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$Listado_Gastos_por_vehiculo_report->ReportFilter = $Listado_Gastos_por_vehiculo_report->DefaultFilter;
	if ($Listado_Gastos_por_vehiculo_report->ReportFilter <> "") $Listado_Gastos_por_vehiculo_report->ReportFilter .= " AND ";
	if (is_null($Listado_Gastos_por_vehiculo->Patente->CurrentValue)) {
		$Listado_Gastos_por_vehiculo_report->ReportFilter .= "(`Patente` IS NULL)";
	} else {
		$Listado_Gastos_por_vehiculo_report->ReportFilter .= "(`Patente` = '" . ew_AdjustSql($Listado_Gastos_por_vehiculo->Patente->CurrentValue) . "')";
	}
	if ($Listado_Gastos_por_vehiculo_report->DbDetailFilter <> "") {
		if ($Listado_Gastos_por_vehiculo_report->ReportFilter <> "")
			$Listado_Gastos_por_vehiculo_report->ReportFilter .= " AND ";
		$Listado_Gastos_por_vehiculo_report->ReportFilter .= "(" . $Listado_Gastos_por_vehiculo_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$Listado_Gastos_por_vehiculo->CurrentFilter = $Listado_Gastos_por_vehiculo_report->ReportFilter;
	$Listado_Gastos_por_vehiculo_report->ReportSql = $Listado_Gastos_por_vehiculo->DetailSQL();

	// Load detail records
	$Listado_Gastos_por_vehiculo_report->DetailRecordset = $conn->Execute($Listado_Gastos_por_vehiculo_report->ReportSql);
	$Listado_Gastos_por_vehiculo_report->DtlRecordCount = $Listado_Gastos_por_vehiculo_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Listado_Gastos_por_vehiculo_report->DetailRecordset->EOF) {
		$Listado_Gastos_por_vehiculo_report->RecCnt++;
		$Listado_Gastos_por_vehiculo->importe_gasto->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('importe_gasto'));
		$Listado_Gastos_por_vehiculo->total->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('total'));
	}
	if ($Listado_Gastos_por_vehiculo_report->RecCnt == 1) {
		$Listado_Gastos_por_vehiculo_report->ReportCounts[0] = 0;
		$Listado_Gastos_por_vehiculo_report->ReportTotals[0][18] = 0;
		$Listado_Gastos_por_vehiculo_report->ReportTotals[0][19] = 0;
	}
	for ($i = 1; $i <= 1; $i++) {
		if ($Listado_Gastos_por_vehiculo_report->LevelBreak[$i]) { // Reset counter and aggregation
			$Listado_Gastos_por_vehiculo_report->ReportCounts[$i] = 0;
			$Listado_Gastos_por_vehiculo_report->ReportTotals[$i][18] = 0;
			$Listado_Gastos_por_vehiculo_report->ReportTotals[$i][19] = 0;
		}
	}
	$Listado_Gastos_por_vehiculo_report->ReportCounts[0] += $Listado_Gastos_por_vehiculo_report->DtlRecordCount;
	$Listado_Gastos_por_vehiculo_report->ReportCounts[1] += $Listado_Gastos_por_vehiculo_report->DtlRecordCount;
	if ($Listado_Gastos_por_vehiculo_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->nro_cliente->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->modelo->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->marca->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->chofer->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->Origen->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->fecha_ini->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->Destino->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->nro_gasto->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->fecha_gasto->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->detalle_gasto->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->tipo_gasto->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->razon_social->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->prov_desde->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->prov_hasta->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->loc_desde->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->cp_desde->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->loc_hasta->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->cp_hasta->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->importe_gasto->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Listado_Gastos_por_vehiculo->total->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$Listado_Gastos_por_vehiculo_report->DetailRecordset->EOF) {
		$Listado_Gastos_por_vehiculo->nro_cliente->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('nro_cliente'));
		$Listado_Gastos_por_vehiculo->modelo->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('modelo'));
		$Listado_Gastos_por_vehiculo->marca->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('marca'));
		$Listado_Gastos_por_vehiculo->chofer->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('chofer'));
		$Listado_Gastos_por_vehiculo->Origen->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('Origen'));
		$Listado_Gastos_por_vehiculo->fecha_ini->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('fecha_ini'));
		$Listado_Gastos_por_vehiculo->Destino->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('Destino'));
		$Listado_Gastos_por_vehiculo->nro_gasto->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('nro_gasto'));
		$Listado_Gastos_por_vehiculo->fecha_gasto->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('fecha_gasto'));
		$Listado_Gastos_por_vehiculo->detalle_gasto->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('detalle_gasto'));
		$Listado_Gastos_por_vehiculo->tipo_gasto->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('tipo_gasto'));
		$Listado_Gastos_por_vehiculo->razon_social->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('razon_social'));
		$Listado_Gastos_por_vehiculo->prov_desde->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('prov_desde'));
		$Listado_Gastos_por_vehiculo->prov_hasta->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('prov_hasta'));
		$Listado_Gastos_por_vehiculo->loc_desde->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('loc_desde'));
		$Listado_Gastos_por_vehiculo->cp_desde->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('cp_desde'));
		$Listado_Gastos_por_vehiculo->loc_hasta->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('loc_hasta'));
		$Listado_Gastos_por_vehiculo->cp_hasta->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('cp_hasta'));
		$Listado_Gastos_por_vehiculo->importe_gasto->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('importe_gasto'));
		$Listado_Gastos_por_vehiculo_report->ReportTotals[0][18] += $Listado_Gastos_por_vehiculo->importe_gasto->CurrentValue;
		$Listado_Gastos_por_vehiculo_report->ReportTotals[1][18] += $Listado_Gastos_por_vehiculo->importe_gasto->CurrentValue;
		$Listado_Gastos_por_vehiculo->total->setDbValue($Listado_Gastos_por_vehiculo_report->DetailRecordset->fields('total'));
		$Listado_Gastos_por_vehiculo_report->ReportTotals[0][19] += $Listado_Gastos_por_vehiculo->total->CurrentValue;
		$Listado_Gastos_por_vehiculo_report->ReportTotals[1][19] += $Listado_Gastos_por_vehiculo->total->CurrentValue;

		// Render for view
		$Listado_Gastos_por_vehiculo->RowType = EW_ROWTYPE_VIEW;
		$Listado_Gastos_por_vehiculo->ResetAttrs();
		$Listado_Gastos_por_vehiculo_report->RenderRow();
?>
<?php
		$Listado_Gastos_por_vehiculo_report->DetailRecordset->MoveNext();
	}
	$Listado_Gastos_por_vehiculo_report->DetailRecordset->Close();

	// Save old group data
	$Listado_Gastos_por_vehiculo_report->ReportGroups[0] = $Listado_Gastos_por_vehiculo->Patente->CurrentValue;

	// Get next record
	$Listado_Gastos_por_vehiculo_report->Recordset->MoveNext();
	if ($Listado_Gastos_por_vehiculo_report->Recordset->EOF) {
		$Listado_Gastos_por_vehiculo_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$Listado_Gastos_por_vehiculo->Patente->setDbValue($Listado_Gastos_por_vehiculo_report->Recordset->fields('Patente'));
	}
	$Listado_Gastos_por_vehiculo_report->ChkLvlBreak();

	// Show footers
	if ($Listado_Gastos_por_vehiculo_report->LevelBreak[1]) {
		$Listado_Gastos_por_vehiculo->Patente->CurrentValue = $Listado_Gastos_por_vehiculo_report->ReportGroups[0];

		// Render row for view
		$Listado_Gastos_por_vehiculo->RowType = EW_ROWTYPE_VIEW;
		$Listado_Gastos_por_vehiculo->ResetAttrs();
		$Listado_Gastos_por_vehiculo_report->RenderRow();
		$Listado_Gastos_por_vehiculo->Patente->CurrentValue = $Listado_Gastos_por_vehiculo->Patente->DbValue;
?>
<?php
}
}

// Close recordset
$Listado_Gastos_por_vehiculo_report->Recordset->Close();
?>
<?php if ($Listado_Gastos_por_vehiculo_report->RecordExists) { ?>
	<tr><td colspan=21>&nbsp;<br></td></tr>
	<tr><td colspan=21 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Listado_Gastos_por_vehiculo_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($Listado_Gastos_por_vehiculo_report->RecordExists) { ?>
	<tr><td colspan=21>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
<?php
$Listado_Gastos_por_vehiculo_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Listado_Gastos_por_vehiculo->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Listado_Gastos_por_vehiculo_report->Page_Terminate();
?>
