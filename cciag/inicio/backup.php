<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "../";
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewcfg11.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = "../"; ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_userfn11.php" ?>
<?php

//
// Page class
//

$backup_php = NULL; // Initialize page object first

class cbackup_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{E85D8E60-21B0-46D8-A725-BE5A2EF61FC0}";

	// Table name
	var $TableName = 'backup.php';

	// Page object name
	var $PageObjName = 'backup_php';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

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

		// User table object (usuario)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'backup.php', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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
			$this->Page_Terminate(ew_GetUrl("cciag_login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

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

		// Export
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

	//
	// Page main
	//
	function Page_Main() {

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb;
		$Breadcrumb = new cBreadcrumb();
		$url = ew_CurrentUrl();
		$Breadcrumb->Add("custom", "backup_php", $url, "", "backup_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($backup_php)) $backup_php = new cbackup_php();

// Page init
$backup_php->Page_Init();

// Page main
$backup_php->Page_Main();
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_header.php" ?>
<?php if (!@$gbSkipHeaderFooter) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
$datos = $_POST["comentarios"];

//aca los parametros de conexion, si tienes aparte la conexión , solo incluyuela
$usuario="root";
$passwd="";
$host="localhost";
$bd="cciag_sauceviejo";
$nombre="backup.bck"; //Este es el nombre del archivo a generar

/* Determina si la tabla será vaciada (si existe) cuando  restauremos la tabla. */            
$drop = false;
$tablas = false; //tablas de la bd

// Tipo de compresion.
// Puede ser "gz", "bz2", o false (sin comprimir)

$compresion = false;

 /* Conexion */
$conexion = mysql_connect($host, $usuario, $passwd)
or die("No se puede conectar con el servidor MySQL: ".mysql_error());
mysql_select_db($bd, $conexion)
or die("No se pudo seleccionar la Base de Datos: ". mysql_error());

/* Se busca las tablas en la base de datos */
if ( empty($tablas) ) {
	$consulta = "SHOW TABLES FROM $bd;";
	$respuesta = mysql_query($consulta, $conexion)
	or die("No se pudo ejecutar la consulta: ".mysql_error());
	while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {
		$tablas[] = $fila[0];
	}
}

/* Se crea la cabecera del archivo */
$info['dumpversion'] = "1.1b";
$info['fecha'] = date("d-m-Y");
$info['hora'] = date("h:m:s A");
$info['mysqlver'] = mysql_get_server_info();
$info['phpver'] = phpversion();
ob_start();
print_r($tablas);
$representacion = ob_get_contents();
ob_end_clean ();
preg_match_all('/(\[\d+\] => .*)\n/', $representacion, $matches);
$info['tablas'] = implode(";  ", $matches[1]);
$dump = <<<EOT
# +===================================================================
# |
# | Generado el {$info['fecha']} a las {$info['hora']} 
# | Servidor: {$_SERVER['HTTP_HOST']}
# | MySQL Version: {$info['mysqlver']}
# | PHP Version: {$info['phpver']}
# | Base de datos: '$bd'
# | Tablas: {$info['tablas']}
# |
# +-------------------------------------------------------------------
$datos
# --------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS=0;
EOT;
foreach ($tablas as $tabla) {
	$drop_table_query = "";
	$create_table_query = "";
	$insert_into_query = "";

	/* Se halla el query que será capaz vaciar la tabla. */
	if ($drop) {
		$drop_table_query = "DROP TABLE IF EXISTS `$tabla`;";
	} else {
		$drop_table_query = "# No especificado.";
	}

	/* Se halla el query que será capaz de recrear la estructura de la tabla. */
	$create_table_query = "";
	$consulta = "SHOW CREATE TABLE $tabla;";
	$respuesta = mysql_query($consulta, $conexion)
	or die("No se pudo ejecutar la consulta: ".mysql_error());
	while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {
			$create_table_query = $fila[1].";";
	}

	/* Se halla el query que será capaz de insertar los datos. */
	$insert_into_query = "";
	$consulta = "SELECT * FROM $tabla;";
	$respuesta = mysql_query($consulta, $conexion)
	or die("No se pudo ejecutar la consulta: ".mysql_error());
	while ($fila = mysql_fetch_array($respuesta, MYSQL_ASSOC)) {
			$columnas = array_keys($fila);
			foreach ($columnas as $columna) {
				if ( gettype($fila[$columna]) == "NULL" ) {
					$values[] = "NULL";
				} else {
					$values[] = "'".mysql_real_escape_string($fila[$columna])."'";
				}
			}
			$insert_into_query .= "INSERT INTO `$tabla` VALUES (".implode(", ", $values).");\n";
			unset($values);
	}
$dump .= <<<EOT
# | Vaciado de tabla '$tabla'
# +------------------------------------->
$drop_table_query
# | Estructura de la tabla '$tabla'
# +------------------------------------->
$create_table_query
# | Carga de datos de la tabla '$tabla'
# +------------------------------------->
$insert_into_query
SET FOREIGN_KEY_CHECKS=1; 
EOT;
}

/* Envio */
if ( !headers_sent() ) {
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Content-Transfer-Encoding: binary");
	header(" ---- ".$datos." ---- ");
	switch ($compresion) {
	case "gz":
		header("Content-Disposition: attachment; filename=$nombre.gz");
		header("Content-type: application/x-gzip");
		echo gzencode($dump, 9);
		break;
	case "bz2": 
		header("Content-Disposition: attachment; filename=$nombre.bz2");
		header("Content-type: application/x-bzip2");
		echo bzcompress($dump, 9);
		break;
	default:
		header("Content-Disposition: attachment; filename=$nombre");
		header("Content-type: application/force-download");
		echo $dump;
	}
} else {
	echo "<b>ATENCION: Probablemente ha ocurrido un error</b><br>\n<pre>\n$dump\n</pre>";
}
?>
<?php include_once $EW_RELATIVE_PATH . "cciag_footer.php" ?>
<?php
$backup_php->Page_Terminate();
?>
