<?php include_once "cciag_sociosinfo.php" ?>
<?php include_once "cciag_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($socios_detalles_grid)) $socios_detalles_grid = new csocios_detalles_grid();

// Page init
$socios_detalles_grid->Page_Init();

// Page main
$socios_detalles_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_detalles_grid->Page_Render();
?>
<?php if ($socios_detalles->Export == "") { ?>
<script type="text/javascript">

// Page object
var socios_detalles_grid = new ew_Page("socios_detalles_grid");
socios_detalles_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = socios_detalles_grid.PageID; // For backward compatibility

// Form object
var fsocios_detallesgrid = new ew_Form("fsocios_detallesgrid");
fsocios_detallesgrid.FormKeyCountName = '<?php echo $socios_detalles_grid->FormKeyCountName ?>';

// Validate form
fsocios_detallesgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_fecha_alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios_detalles->fecha_alta->FldCaption(), $socios_detalles->fecha_alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_alta");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios_detalles->fecha_alta->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_baja");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios_detalles->fecha_baja->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fsocios_detallesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_socio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_detalles", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_alta", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_baja", false)) return false;
	return true;
}

// Form_CustomValidate event
fsocios_detallesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsocios_detallesgrid.ValidateRequired = true;
<?php } else { ?>
fsocios_detallesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsocios_detallesgrid.Lists["x_id_socio"] = {"LinkField":"x_socio_nro","Ajax":true,"AutoFill":false,"DisplayFields":["x_propietario","x_comercio","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsocios_detallesgrid.Lists["x_id_detalles"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($socios_detalles->CurrentAction == "gridadd") {
	if ($socios_detalles->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$socios_detalles_grid->TotalRecs = $socios_detalles->SelectRecordCount();
			$socios_detalles_grid->Recordset = $socios_detalles_grid->LoadRecordset($socios_detalles_grid->StartRec-1, $socios_detalles_grid->DisplayRecs);
		} else {
			if ($socios_detalles_grid->Recordset = $socios_detalles_grid->LoadRecordset())
				$socios_detalles_grid->TotalRecs = $socios_detalles_grid->Recordset->RecordCount();
		}
		$socios_detalles_grid->StartRec = 1;
		$socios_detalles_grid->DisplayRecs = $socios_detalles_grid->TotalRecs;
	} else {
		$socios_detalles->CurrentFilter = "0=1";
		$socios_detalles_grid->StartRec = 1;
		$socios_detalles_grid->DisplayRecs = $socios_detalles->GridAddRowCount;
	}
	$socios_detalles_grid->TotalRecs = $socios_detalles_grid->DisplayRecs;
	$socios_detalles_grid->StopRec = $socios_detalles_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($socios_detalles_grid->TotalRecs <= 0)
			$socios_detalles_grid->TotalRecs = $socios_detalles->SelectRecordCount();
	} else {
		if (!$socios_detalles_grid->Recordset && ($socios_detalles_grid->Recordset = $socios_detalles_grid->LoadRecordset()))
			$socios_detalles_grid->TotalRecs = $socios_detalles_grid->Recordset->RecordCount();
	}
	$socios_detalles_grid->StartRec = 1;
	$socios_detalles_grid->DisplayRecs = $socios_detalles_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$socios_detalles_grid->Recordset = $socios_detalles_grid->LoadRecordset($socios_detalles_grid->StartRec-1, $socios_detalles_grid->DisplayRecs);

	// Set no record found message
	if ($socios_detalles->CurrentAction == "" && $socios_detalles_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$socios_detalles_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($socios_detalles_grid->SearchWhere == "0=101")
			$socios_detalles_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$socios_detalles_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$socios_detalles_grid->RenderOtherOptions();
?>
<?php $socios_detalles_grid->ShowPageHeader(); ?>
<?php
$socios_detalles_grid->ShowMessage();
?>
<?php if ($socios_detalles_grid->TotalRecs > 0 || $socios_detalles->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fsocios_detallesgrid" class="ewForm form-inline">
<?php if ($socios_detalles_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($socios_detalles_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_socios_detalles" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_socios_detallesgrid" class="table ewTable">
<?php echo $socios_detalles->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$socios_detalles->RowType = EW_ROWTYPE_HEADER;

// Render list options
$socios_detalles_grid->RenderListOptions();

// Render list options (header, left)
$socios_detalles_grid->ListOptions->Render("header", "left");
?>
<?php if ($socios_detalles->id_socio->Visible) { // id_socio ?>
	<?php if ($socios_detalles->SortUrl($socios_detalles->id_socio) == "") { ?>
		<th data-name="id_socio"><div id="elh_socios_detalles_id_socio" class="socios_detalles_id_socio"><div class="ewTableHeaderCaption"><?php echo $socios_detalles->id_socio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_socio"><div><div id="elh_socios_detalles_id_socio" class="socios_detalles_id_socio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios_detalles->id_socio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios_detalles->id_socio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios_detalles->id_socio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios_detalles->id_detalles->Visible) { // id_detalles ?>
	<?php if ($socios_detalles->SortUrl($socios_detalles->id_detalles) == "") { ?>
		<th data-name="id_detalles"><div id="elh_socios_detalles_id_detalles" class="socios_detalles_id_detalles"><div class="ewTableHeaderCaption"><?php echo $socios_detalles->id_detalles->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_detalles"><div><div id="elh_socios_detalles_id_detalles" class="socios_detalles_id_detalles">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios_detalles->id_detalles->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios_detalles->id_detalles->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios_detalles->id_detalles->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios_detalles->fecha_alta->Visible) { // fecha_alta ?>
	<?php if ($socios_detalles->SortUrl($socios_detalles->fecha_alta) == "") { ?>
		<th data-name="fecha_alta"><div id="elh_socios_detalles_fecha_alta" class="socios_detalles_fecha_alta"><div class="ewTableHeaderCaption"><?php echo $socios_detalles->fecha_alta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_alta"><div><div id="elh_socios_detalles_fecha_alta" class="socios_detalles_fecha_alta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios_detalles->fecha_alta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios_detalles->fecha_alta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios_detalles->fecha_alta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios_detalles->fecha_baja->Visible) { // fecha_baja ?>
	<?php if ($socios_detalles->SortUrl($socios_detalles->fecha_baja) == "") { ?>
		<th data-name="fecha_baja"><div id="elh_socios_detalles_fecha_baja" class="socios_detalles_fecha_baja"><div class="ewTableHeaderCaption"><?php echo $socios_detalles->fecha_baja->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_baja"><div><div id="elh_socios_detalles_fecha_baja" class="socios_detalles_fecha_baja">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios_detalles->fecha_baja->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios_detalles->fecha_baja->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios_detalles->fecha_baja->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$socios_detalles_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$socios_detalles_grid->StartRec = 1;
$socios_detalles_grid->StopRec = $socios_detalles_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($socios_detalles_grid->FormKeyCountName) && ($socios_detalles->CurrentAction == "gridadd" || $socios_detalles->CurrentAction == "gridedit" || $socios_detalles->CurrentAction == "F")) {
		$socios_detalles_grid->KeyCount = $objForm->GetValue($socios_detalles_grid->FormKeyCountName);
		$socios_detalles_grid->StopRec = $socios_detalles_grid->StartRec + $socios_detalles_grid->KeyCount - 1;
	}
}
$socios_detalles_grid->RecCnt = $socios_detalles_grid->StartRec - 1;
if ($socios_detalles_grid->Recordset && !$socios_detalles_grid->Recordset->EOF) {
	$socios_detalles_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $socios_detalles_grid->StartRec > 1)
		$socios_detalles_grid->Recordset->Move($socios_detalles_grid->StartRec - 1);
} elseif (!$socios_detalles->AllowAddDeleteRow && $socios_detalles_grid->StopRec == 0) {
	$socios_detalles_grid->StopRec = $socios_detalles->GridAddRowCount;
}

// Initialize aggregate
$socios_detalles->RowType = EW_ROWTYPE_AGGREGATEINIT;
$socios_detalles->ResetAttrs();
$socios_detalles_grid->RenderRow();
if ($socios_detalles->CurrentAction == "gridadd")
	$socios_detalles_grid->RowIndex = 0;
if ($socios_detalles->CurrentAction == "gridedit")
	$socios_detalles_grid->RowIndex = 0;
while ($socios_detalles_grid->RecCnt < $socios_detalles_grid->StopRec) {
	$socios_detalles_grid->RecCnt++;
	if (intval($socios_detalles_grid->RecCnt) >= intval($socios_detalles_grid->StartRec)) {
		$socios_detalles_grid->RowCnt++;
		if ($socios_detalles->CurrentAction == "gridadd" || $socios_detalles->CurrentAction == "gridedit" || $socios_detalles->CurrentAction == "F") {
			$socios_detalles_grid->RowIndex++;
			$objForm->Index = $socios_detalles_grid->RowIndex;
			if ($objForm->HasValue($socios_detalles_grid->FormActionName))
				$socios_detalles_grid->RowAction = strval($objForm->GetValue($socios_detalles_grid->FormActionName));
			elseif ($socios_detalles->CurrentAction == "gridadd")
				$socios_detalles_grid->RowAction = "insert";
			else
				$socios_detalles_grid->RowAction = "";
		}

		// Set up key count
		$socios_detalles_grid->KeyCount = $socios_detalles_grid->RowIndex;

		// Init row class and style
		$socios_detalles->ResetAttrs();
		$socios_detalles->CssClass = "";
		if ($socios_detalles->CurrentAction == "gridadd") {
			if ($socios_detalles->CurrentMode == "copy") {
				$socios_detalles_grid->LoadRowValues($socios_detalles_grid->Recordset); // Load row values
				$socios_detalles_grid->SetRecordKey($socios_detalles_grid->RowOldKey, $socios_detalles_grid->Recordset); // Set old record key
			} else {
				$socios_detalles_grid->LoadDefaultValues(); // Load default values
				$socios_detalles_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$socios_detalles_grid->LoadRowValues($socios_detalles_grid->Recordset); // Load row values
		}
		$socios_detalles->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($socios_detalles->CurrentAction == "gridadd") // Grid add
			$socios_detalles->RowType = EW_ROWTYPE_ADD; // Render add
		if ($socios_detalles->CurrentAction == "gridadd" && $socios_detalles->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$socios_detalles_grid->RestoreCurrentRowFormValues($socios_detalles_grid->RowIndex); // Restore form values
		if ($socios_detalles->CurrentAction == "gridedit") { // Grid edit
			if ($socios_detalles->EventCancelled) {
				$socios_detalles_grid->RestoreCurrentRowFormValues($socios_detalles_grid->RowIndex); // Restore form values
			}
			if ($socios_detalles_grid->RowAction == "insert")
				$socios_detalles->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$socios_detalles->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($socios_detalles->CurrentAction == "gridedit" && ($socios_detalles->RowType == EW_ROWTYPE_EDIT || $socios_detalles->RowType == EW_ROWTYPE_ADD) && $socios_detalles->EventCancelled) // Update failed
			$socios_detalles_grid->RestoreCurrentRowFormValues($socios_detalles_grid->RowIndex); // Restore form values
		if ($socios_detalles->RowType == EW_ROWTYPE_EDIT) // Edit row
			$socios_detalles_grid->EditRowCnt++;
		if ($socios_detalles->CurrentAction == "F") // Confirm row
			$socios_detalles_grid->RestoreCurrentRowFormValues($socios_detalles_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$socios_detalles->RowAttrs = array_merge($socios_detalles->RowAttrs, array('data-rowindex'=>$socios_detalles_grid->RowCnt, 'id'=>'r' . $socios_detalles_grid->RowCnt . '_socios_detalles', 'data-rowtype'=>$socios_detalles->RowType));

		// Render row
		$socios_detalles_grid->RenderRow();

		// Render list options
		$socios_detalles_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($socios_detalles_grid->RowAction <> "delete" && $socios_detalles_grid->RowAction <> "insertdelete" && !($socios_detalles_grid->RowAction == "insert" && $socios_detalles->CurrentAction == "F" && $socios_detalles_grid->EmptyRow())) {
?>
	<tr<?php echo $socios_detalles->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_detalles_grid->ListOptions->Render("body", "left", $socios_detalles_grid->RowCnt);
?>
	<?php if ($socios_detalles->id_socio->Visible) { // id_socio ?>
		<td data-name="id_socio"<?php echo $socios_detalles->id_socio->CellAttributes() ?>>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($socios_detalles->id_socio->getSessionValue() <> "") { ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_id_socio" class="form-group socios_detalles_id_socio">
<span<?php echo $socios_detalles->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_detalles->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_detalles->id_socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_id_socio" class="form-group socios_detalles_id_socio">
<select data-field="x_id_socio" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio"<?php echo $socios_detalles->id_socio->EditAttributes() ?>>
<?php
if (is_array($socios_detalles->id_socio->EditValue)) {
	$arwrk = $socios_detalles->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_detalles->id_socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios_detalles->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_detalles->id_socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activo`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }
 if (!$GLOBALS["socios_detalles"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $socios_detalles->Lookup_Selecting($socios_detalles->id_socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `propietario` DESC";
?>
<input type="hidden" name="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" id="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_socio" name="o<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" id="o<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_detalles->id_socio->OldValue) ?>">
<?php } ?>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($socios_detalles->id_socio->getSessionValue() <> "") { ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_id_socio" class="form-group socios_detalles_id_socio">
<span<?php echo $socios_detalles->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_detalles->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_detalles->id_socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_id_socio" class="form-group socios_detalles_id_socio">
<select data-field="x_id_socio" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio"<?php echo $socios_detalles->id_socio->EditAttributes() ?>>
<?php
if (is_array($socios_detalles->id_socio->EditValue)) {
	$arwrk = $socios_detalles->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_detalles->id_socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios_detalles->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_detalles->id_socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activo`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }
 if (!$GLOBALS["socios_detalles"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $socios_detalles->Lookup_Selecting($socios_detalles->id_socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `propietario` DESC";
?>
<input type="hidden" name="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" id="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios_detalles->id_socio->ViewAttributes() ?>>
<?php echo $socios_detalles->id_socio->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_socio" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_detalles->id_socio->FormValue) ?>">
<input type="hidden" data-field="x_id_socio" name="o<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" id="o<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_detalles->id_socio->OldValue) ?>">
<?php } ?>
<a id="<?php echo $socios_detalles_grid->PageObjName . "_row_" . $socios_detalles_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($socios_detalles->id_detalles->Visible) { // id_detalles ?>
		<td data-name="id_detalles"<?php echo $socios_detalles->id_detalles->CellAttributes() ?>>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($socios_detalles->id_detalles->getSessionValue() <> "") { ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_id_detalles" class="form-group socios_detalles_id_detalles">
<span<?php echo $socios_detalles->id_detalles->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_detalles->id_detalles->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="<?php echo ew_HtmlEncode($socios_detalles->id_detalles->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_id_detalles" class="form-group socios_detalles_id_detalles">
<select data-field="x_id_detalles" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles"<?php echo $socios_detalles->id_detalles->EditAttributes() ?>>
<?php
if (is_array($socios_detalles->id_detalles->EditValue)) {
	$arwrk = $socios_detalles->id_detalles->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_detalles->id_detalles->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_detalles->id_detalles->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codigo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `detalles`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activa`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $socios_detalles->Lookup_Selecting($socios_detalles->id_detalles, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nombre` DESC";
?>
<input type="hidden" name="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" id="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_detalles" name="o<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" id="o<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="<?php echo ew_HtmlEncode($socios_detalles->id_detalles->OldValue) ?>">
<?php } ?>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($socios_detalles->id_detalles->getSessionValue() <> "") { ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_id_detalles" class="form-group socios_detalles_id_detalles">
<span<?php echo $socios_detalles->id_detalles->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_detalles->id_detalles->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="<?php echo ew_HtmlEncode($socios_detalles->id_detalles->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_id_detalles" class="form-group socios_detalles_id_detalles">
<select data-field="x_id_detalles" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles"<?php echo $socios_detalles->id_detalles->EditAttributes() ?>>
<?php
if (is_array($socios_detalles->id_detalles->EditValue)) {
	$arwrk = $socios_detalles->id_detalles->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_detalles->id_detalles->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_detalles->id_detalles->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codigo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `detalles`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activa`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $socios_detalles->Lookup_Selecting($socios_detalles->id_detalles, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nombre` DESC";
?>
<input type="hidden" name="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" id="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios_detalles->id_detalles->ViewAttributes() ?>>
<?php echo $socios_detalles->id_detalles->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_detalles" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="<?php echo ew_HtmlEncode($socios_detalles->id_detalles->FormValue) ?>">
<input type="hidden" data-field="x_id_detalles" name="o<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" id="o<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="<?php echo ew_HtmlEncode($socios_detalles->id_detalles->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios_detalles->fecha_alta->Visible) { // fecha_alta ?>
		<td data-name="fecha_alta"<?php echo $socios_detalles->fecha_alta->CellAttributes() ?>>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_fecha_alta" class="form-group socios_detalles_fecha_alta">
<input type="text" data-field="x_fecha_alta" name="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" id="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" placeholder="<?php echo ew_HtmlEncode($socios_detalles->fecha_alta->PlaceHolder) ?>" value="<?php echo $socios_detalles->fecha_alta->EditValue ?>"<?php echo $socios_detalles->fecha_alta->EditAttributes() ?>>
<?php if (!$socios_detalles->fecha_alta->ReadOnly && !$socios_detalles->fecha_alta->Disabled && !isset($socios_detalles->fecha_alta->EditAttrs["readonly"]) && !isset($socios_detalles->fecha_alta->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_detallesgrid", "x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha_alta" name="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" id="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" value="<?php echo ew_HtmlEncode($socios_detalles->fecha_alta->OldValue) ?>">
<?php } ?>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_fecha_alta" class="form-group socios_detalles_fecha_alta">
<input type="text" data-field="x_fecha_alta" name="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" id="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" placeholder="<?php echo ew_HtmlEncode($socios_detalles->fecha_alta->PlaceHolder) ?>" value="<?php echo $socios_detalles->fecha_alta->EditValue ?>"<?php echo $socios_detalles->fecha_alta->EditAttributes() ?>>
<?php if (!$socios_detalles->fecha_alta->ReadOnly && !$socios_detalles->fecha_alta->Disabled && !isset($socios_detalles->fecha_alta->EditAttrs["readonly"]) && !isset($socios_detalles->fecha_alta->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_detallesgrid", "x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios_detalles->fecha_alta->ViewAttributes() ?>>
<?php echo $socios_detalles->fecha_alta->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_alta" name="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" id="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" value="<?php echo ew_HtmlEncode($socios_detalles->fecha_alta->FormValue) ?>">
<input type="hidden" data-field="x_fecha_alta" name="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" id="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" value="<?php echo ew_HtmlEncode($socios_detalles->fecha_alta->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios_detalles->fecha_baja->Visible) { // fecha_baja ?>
		<td data-name="fecha_baja"<?php echo $socios_detalles->fecha_baja->CellAttributes() ?>>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_fecha_baja" class="form-group socios_detalles_fecha_baja">
<input type="text" data-field="x_fecha_baja" name="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" id="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" placeholder="<?php echo ew_HtmlEncode($socios_detalles->fecha_baja->PlaceHolder) ?>" value="<?php echo $socios_detalles->fecha_baja->EditValue ?>"<?php echo $socios_detalles->fecha_baja->EditAttributes() ?>>
<?php if (!$socios_detalles->fecha_baja->ReadOnly && !$socios_detalles->fecha_baja->Disabled && !isset($socios_detalles->fecha_baja->EditAttrs["readonly"]) && !isset($socios_detalles->fecha_baja->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_detallesgrid", "x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha_baja" name="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" id="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" value="<?php echo ew_HtmlEncode($socios_detalles->fecha_baja->OldValue) ?>">
<?php } ?>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_detalles_grid->RowCnt ?>_socios_detalles_fecha_baja" class="form-group socios_detalles_fecha_baja">
<input type="text" data-field="x_fecha_baja" name="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" id="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" placeholder="<?php echo ew_HtmlEncode($socios_detalles->fecha_baja->PlaceHolder) ?>" value="<?php echo $socios_detalles->fecha_baja->EditValue ?>"<?php echo $socios_detalles->fecha_baja->EditAttributes() ?>>
<?php if (!$socios_detalles->fecha_baja->ReadOnly && !$socios_detalles->fecha_baja->Disabled && !isset($socios_detalles->fecha_baja->EditAttrs["readonly"]) && !isset($socios_detalles->fecha_baja->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_detallesgrid", "x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios_detalles->fecha_baja->ViewAttributes() ?>>
<?php echo $socios_detalles->fecha_baja->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_baja" name="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" id="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" value="<?php echo ew_HtmlEncode($socios_detalles->fecha_baja->FormValue) ?>">
<input type="hidden" data-field="x_fecha_baja" name="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" id="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" value="<?php echo ew_HtmlEncode($socios_detalles->fecha_baja->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_detalles_grid->ListOptions->Render("body", "right", $socios_detalles_grid->RowCnt);
?>
	</tr>
<?php if ($socios_detalles->RowType == EW_ROWTYPE_ADD || $socios_detalles->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsocios_detallesgrid.UpdateOpts(<?php echo $socios_detalles_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($socios_detalles->CurrentAction <> "gridadd" || $socios_detalles->CurrentMode == "copy")
		if (!$socios_detalles_grid->Recordset->EOF) $socios_detalles_grid->Recordset->MoveNext();
}
?>
<?php
	if ($socios_detalles->CurrentMode == "add" || $socios_detalles->CurrentMode == "copy" || $socios_detalles->CurrentMode == "edit") {
		$socios_detalles_grid->RowIndex = '$rowindex$';
		$socios_detalles_grid->LoadDefaultValues();

		// Set row properties
		$socios_detalles->ResetAttrs();
		$socios_detalles->RowAttrs = array_merge($socios_detalles->RowAttrs, array('data-rowindex'=>$socios_detalles_grid->RowIndex, 'id'=>'r0_socios_detalles', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($socios_detalles->RowAttrs["class"], "ewTemplate");
		$socios_detalles->RowType = EW_ROWTYPE_ADD;

		// Render row
		$socios_detalles_grid->RenderRow();

		// Render list options
		$socios_detalles_grid->RenderListOptions();
		$socios_detalles_grid->StartRowCnt = 0;
?>
	<tr<?php echo $socios_detalles->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_detalles_grid->ListOptions->Render("body", "left", $socios_detalles_grid->RowIndex);
?>
	<?php if ($socios_detalles->id_socio->Visible) { // id_socio ?>
		<td data-name="id_socio">
<?php if ($socios_detalles->CurrentAction <> "F") { ?>
<?php if ($socios_detalles->id_socio->getSessionValue() <> "") { ?>
<span id="el$rowindex$_socios_detalles_id_socio" class="form-group socios_detalles_id_socio">
<span<?php echo $socios_detalles->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_detalles->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_detalles->id_socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_socios_detalles_id_socio" class="form-group socios_detalles_id_socio">
<select data-field="x_id_socio" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio"<?php echo $socios_detalles->id_socio->EditAttributes() ?>>
<?php
if (is_array($socios_detalles->id_socio->EditValue)) {
	$arwrk = $socios_detalles->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_detalles->id_socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios_detalles->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_detalles->id_socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activo`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }
 if (!$GLOBALS["socios_detalles"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $socios_detalles->Lookup_Selecting($socios_detalles->id_socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `propietario` DESC";
?>
<input type="hidden" name="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" id="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_socios_detalles_id_socio" class="form-group socios_detalles_id_socio">
<span<?php echo $socios_detalles->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_detalles->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_socio" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_detalles->id_socio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_socio" name="o<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" id="o<?php echo $socios_detalles_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_detalles->id_socio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios_detalles->id_detalles->Visible) { // id_detalles ?>
		<td data-name="id_detalles">
<?php if ($socios_detalles->CurrentAction <> "F") { ?>
<?php if ($socios_detalles->id_detalles->getSessionValue() <> "") { ?>
<span id="el$rowindex$_socios_detalles_id_detalles" class="form-group socios_detalles_id_detalles">
<span<?php echo $socios_detalles->id_detalles->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_detalles->id_detalles->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="<?php echo ew_HtmlEncode($socios_detalles->id_detalles->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_socios_detalles_id_detalles" class="form-group socios_detalles_id_detalles">
<select data-field="x_id_detalles" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles"<?php echo $socios_detalles->id_detalles->EditAttributes() ?>>
<?php
if (is_array($socios_detalles->id_detalles->EditValue)) {
	$arwrk = $socios_detalles->id_detalles->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_detalles->id_detalles->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_detalles->id_detalles->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codigo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `detalles`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activa`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $socios_detalles->Lookup_Selecting($socios_detalles->id_detalles, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nombre` DESC";
?>
<input type="hidden" name="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" id="s_x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_socios_detalles_id_detalles" class="form-group socios_detalles_id_detalles">
<span<?php echo $socios_detalles->id_detalles->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_detalles->id_detalles->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_detalles" name="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" id="x<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="<?php echo ew_HtmlEncode($socios_detalles->id_detalles->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_detalles" name="o<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" id="o<?php echo $socios_detalles_grid->RowIndex ?>_id_detalles" value="<?php echo ew_HtmlEncode($socios_detalles->id_detalles->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios_detalles->fecha_alta->Visible) { // fecha_alta ?>
		<td data-name="fecha_alta">
<?php if ($socios_detalles->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_detalles_fecha_alta" class="form-group socios_detalles_fecha_alta">
<input type="text" data-field="x_fecha_alta" name="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" id="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" placeholder="<?php echo ew_HtmlEncode($socios_detalles->fecha_alta->PlaceHolder) ?>" value="<?php echo $socios_detalles->fecha_alta->EditValue ?>"<?php echo $socios_detalles->fecha_alta->EditAttributes() ?>>
<?php if (!$socios_detalles->fecha_alta->ReadOnly && !$socios_detalles->fecha_alta->Disabled && !isset($socios_detalles->fecha_alta->EditAttrs["readonly"]) && !isset($socios_detalles->fecha_alta->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_detallesgrid", "x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_detalles_fecha_alta" class="form-group socios_detalles_fecha_alta">
<span<?php echo $socios_detalles->fecha_alta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_detalles->fecha_alta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_alta" name="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" id="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" value="<?php echo ew_HtmlEncode($socios_detalles->fecha_alta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_alta" name="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" id="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_alta" value="<?php echo ew_HtmlEncode($socios_detalles->fecha_alta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios_detalles->fecha_baja->Visible) { // fecha_baja ?>
		<td data-name="fecha_baja">
<?php if ($socios_detalles->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_detalles_fecha_baja" class="form-group socios_detalles_fecha_baja">
<input type="text" data-field="x_fecha_baja" name="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" id="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" placeholder="<?php echo ew_HtmlEncode($socios_detalles->fecha_baja->PlaceHolder) ?>" value="<?php echo $socios_detalles->fecha_baja->EditValue ?>"<?php echo $socios_detalles->fecha_baja->EditAttributes() ?>>
<?php if (!$socios_detalles->fecha_baja->ReadOnly && !$socios_detalles->fecha_baja->Disabled && !isset($socios_detalles->fecha_baja->EditAttrs["readonly"]) && !isset($socios_detalles->fecha_baja->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_detallesgrid", "x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_detalles_fecha_baja" class="form-group socios_detalles_fecha_baja">
<span<?php echo $socios_detalles->fecha_baja->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_detalles->fecha_baja->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_baja" name="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" id="x<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" value="<?php echo ew_HtmlEncode($socios_detalles->fecha_baja->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_baja" name="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" id="o<?php echo $socios_detalles_grid->RowIndex ?>_fecha_baja" value="<?php echo ew_HtmlEncode($socios_detalles->fecha_baja->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_detalles_grid->ListOptions->Render("body", "right", $socios_detalles_grid->RowCnt);
?>
<script type="text/javascript">
fsocios_detallesgrid.UpdateOpts(<?php echo $socios_detalles_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($socios_detalles->CurrentMode == "add" || $socios_detalles->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $socios_detalles_grid->FormKeyCountName ?>" id="<?php echo $socios_detalles_grid->FormKeyCountName ?>" value="<?php echo $socios_detalles_grid->KeyCount ?>">
<?php echo $socios_detalles_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($socios_detalles->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $socios_detalles_grid->FormKeyCountName ?>" id="<?php echo $socios_detalles_grid->FormKeyCountName ?>" value="<?php echo $socios_detalles_grid->KeyCount ?>">
<?php echo $socios_detalles_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($socios_detalles->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsocios_detallesgrid">
</div>
<?php

// Close recordset
if ($socios_detalles_grid->Recordset)
	$socios_detalles_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($socios_detalles_grid->TotalRecs == 0 && $socios_detalles->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($socios_detalles_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($socios_detalles->Export == "") { ?>
<script type="text/javascript">
fsocios_detallesgrid.Init();
</script>
<?php } ?>
<?php
$socios_detalles_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$socios_detalles_grid->Page_Terminate();
?>
