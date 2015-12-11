<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($gastos_mantenimientos_grid)) $gastos_mantenimientos_grid = new cgastos_mantenimientos_grid();

// Page init
$gastos_mantenimientos_grid->Page_Init();

// Page main
$gastos_mantenimientos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gastos_mantenimientos_grid->Page_Render();
?>
<?php if ($gastos_mantenimientos->Export == "") { ?>
<script type="text/javascript">

// Page object
var gastos_mantenimientos_grid = new ew_Page("gastos_mantenimientos_grid");
gastos_mantenimientos_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = gastos_mantenimientos_grid.PageID; // For backward compatibility

// Form object
var fgastos_mantenimientosgrid = new ew_Form("fgastos_mantenimientosgrid");
fgastos_mantenimientosgrid.FormKeyCountName = '<?php echo $gastos_mantenimientos_grid->FormKeyCountName ?>';

// Validate form
fgastos_mantenimientosgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos_mantenimientos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_hoja_mantenimeinto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos_mantenimientos->id_hoja_mantenimeinto->FldErrMsg()) ?>");

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
fgastos_mantenimientosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "detalle", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_tipo_gasto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_hoja_mantenimeinto", false)) return false;
	return true;
}

// Form_CustomValidate event
fgastos_mantenimientosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgastos_mantenimientosgrid.ValidateRequired = true;
<?php } else { ?>
fgastos_mantenimientosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgastos_mantenimientosgrid.Lists["x_id_tipo_gasto"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_gasto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($gastos_mantenimientos->CurrentAction == "gridadd") {
	if ($gastos_mantenimientos->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$gastos_mantenimientos_grid->TotalRecs = $gastos_mantenimientos->SelectRecordCount();
			$gastos_mantenimientos_grid->Recordset = $gastos_mantenimientos_grid->LoadRecordset($gastos_mantenimientos_grid->StartRec-1, $gastos_mantenimientos_grid->DisplayRecs);
		} else {
			if ($gastos_mantenimientos_grid->Recordset = $gastos_mantenimientos_grid->LoadRecordset())
				$gastos_mantenimientos_grid->TotalRecs = $gastos_mantenimientos_grid->Recordset->RecordCount();
		}
		$gastos_mantenimientos_grid->StartRec = 1;
		$gastos_mantenimientos_grid->DisplayRecs = $gastos_mantenimientos_grid->TotalRecs;
	} else {
		$gastos_mantenimientos->CurrentFilter = "0=1";
		$gastos_mantenimientos_grid->StartRec = 1;
		$gastos_mantenimientos_grid->DisplayRecs = $gastos_mantenimientos->GridAddRowCount;
	}
	$gastos_mantenimientos_grid->TotalRecs = $gastos_mantenimientos_grid->DisplayRecs;
	$gastos_mantenimientos_grid->StopRec = $gastos_mantenimientos_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($gastos_mantenimientos_grid->TotalRecs <= 0)
			$gastos_mantenimientos_grid->TotalRecs = $gastos_mantenimientos->SelectRecordCount();
	} else {
		if (!$gastos_mantenimientos_grid->Recordset && ($gastos_mantenimientos_grid->Recordset = $gastos_mantenimientos_grid->LoadRecordset()))
			$gastos_mantenimientos_grid->TotalRecs = $gastos_mantenimientos_grid->Recordset->RecordCount();
	}
	$gastos_mantenimientos_grid->StartRec = 1;
	$gastos_mantenimientos_grid->DisplayRecs = $gastos_mantenimientos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$gastos_mantenimientos_grid->Recordset = $gastos_mantenimientos_grid->LoadRecordset($gastos_mantenimientos_grid->StartRec-1, $gastos_mantenimientos_grid->DisplayRecs);

	// Set no record found message
	if ($gastos_mantenimientos->CurrentAction == "" && $gastos_mantenimientos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$gastos_mantenimientos_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($gastos_mantenimientos_grid->SearchWhere == "0=101")
			$gastos_mantenimientos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$gastos_mantenimientos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$gastos_mantenimientos_grid->RenderOtherOptions();
?>
<?php $gastos_mantenimientos_grid->ShowPageHeader(); ?>
<?php
$gastos_mantenimientos_grid->ShowMessage();
?>
<?php if ($gastos_mantenimientos_grid->TotalRecs > 0 || $gastos_mantenimientos->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fgastos_mantenimientosgrid" class="ewForm form-inline">
<?php if ($gastos_mantenimientos_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($gastos_mantenimientos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_gastos_mantenimientos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_gastos_mantenimientosgrid" class="table ewTable">
<?php echo $gastos_mantenimientos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$gastos_mantenimientos->RowType = EW_ROWTYPE_HEADER;

// Render list options
$gastos_mantenimientos_grid->RenderListOptions();

// Render list options (header, left)
$gastos_mantenimientos_grid->ListOptions->Render("header", "left");
?>
<?php if ($gastos_mantenimientos->codigo->Visible) { // codigo ?>
	<?php if ($gastos_mantenimientos->SortUrl($gastos_mantenimientos->codigo) == "") { ?>
		<th data-name="codigo"><div id="elh_gastos_mantenimientos_codigo" class="gastos_mantenimientos_codigo"><div class="ewTableHeaderCaption"><?php echo $gastos_mantenimientos->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo"><div><div id="elh_gastos_mantenimientos_codigo" class="gastos_mantenimientos_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos_mantenimientos->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos_mantenimientos->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos_mantenimientos->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos_mantenimientos->detalle->Visible) { // detalle ?>
	<?php if ($gastos_mantenimientos->SortUrl($gastos_mantenimientos->detalle) == "") { ?>
		<th data-name="detalle"><div id="elh_gastos_mantenimientos_detalle" class="gastos_mantenimientos_detalle"><div class="ewTableHeaderCaption"><?php echo $gastos_mantenimientos->detalle->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="detalle"><div><div id="elh_gastos_mantenimientos_detalle" class="gastos_mantenimientos_detalle">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos_mantenimientos->detalle->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos_mantenimientos->detalle->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos_mantenimientos->detalle->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos_mantenimientos->fecha->Visible) { // fecha ?>
	<?php if ($gastos_mantenimientos->SortUrl($gastos_mantenimientos->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_gastos_mantenimientos_fecha" class="gastos_mantenimientos_fecha"><div class="ewTableHeaderCaption"><?php echo $gastos_mantenimientos->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_gastos_mantenimientos_fecha" class="gastos_mantenimientos_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos_mantenimientos->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos_mantenimientos->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos_mantenimientos->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos_mantenimientos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
	<?php if ($gastos_mantenimientos->SortUrl($gastos_mantenimientos->id_tipo_gasto) == "") { ?>
		<th data-name="id_tipo_gasto"><div id="elh_gastos_mantenimientos_id_tipo_gasto" class="gastos_mantenimientos_id_tipo_gasto"><div class="ewTableHeaderCaption"><?php echo $gastos_mantenimientos->id_tipo_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipo_gasto"><div><div id="elh_gastos_mantenimientos_id_tipo_gasto" class="gastos_mantenimientos_id_tipo_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos_mantenimientos->id_tipo_gasto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos_mantenimientos->id_tipo_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos_mantenimientos->id_tipo_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos_mantenimientos->id_hoja_mantenimeinto->Visible) { // id_hoja_mantenimeinto ?>
	<?php if ($gastos_mantenimientos->SortUrl($gastos_mantenimientos->id_hoja_mantenimeinto) == "") { ?>
		<th data-name="id_hoja_mantenimeinto"><div id="elh_gastos_mantenimientos_id_hoja_mantenimeinto" class="gastos_mantenimientos_id_hoja_mantenimeinto"><div class="ewTableHeaderCaption"><?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_hoja_mantenimeinto"><div><div id="elh_gastos_mantenimientos_id_hoja_mantenimeinto" class="gastos_mantenimientos_id_hoja_mantenimeinto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos_mantenimientos->id_hoja_mantenimeinto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos_mantenimientos->id_hoja_mantenimeinto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$gastos_mantenimientos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$gastos_mantenimientos_grid->StartRec = 1;
$gastos_mantenimientos_grid->StopRec = $gastos_mantenimientos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($gastos_mantenimientos_grid->FormKeyCountName) && ($gastos_mantenimientos->CurrentAction == "gridadd" || $gastos_mantenimientos->CurrentAction == "gridedit" || $gastos_mantenimientos->CurrentAction == "F")) {
		$gastos_mantenimientos_grid->KeyCount = $objForm->GetValue($gastos_mantenimientos_grid->FormKeyCountName);
		$gastos_mantenimientos_grid->StopRec = $gastos_mantenimientos_grid->StartRec + $gastos_mantenimientos_grid->KeyCount - 1;
	}
}
$gastos_mantenimientos_grid->RecCnt = $gastos_mantenimientos_grid->StartRec - 1;
if ($gastos_mantenimientos_grid->Recordset && !$gastos_mantenimientos_grid->Recordset->EOF) {
	$gastos_mantenimientos_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $gastos_mantenimientos_grid->StartRec > 1)
		$gastos_mantenimientos_grid->Recordset->Move($gastos_mantenimientos_grid->StartRec - 1);
} elseif (!$gastos_mantenimientos->AllowAddDeleteRow && $gastos_mantenimientos_grid->StopRec == 0) {
	$gastos_mantenimientos_grid->StopRec = $gastos_mantenimientos->GridAddRowCount;
}

// Initialize aggregate
$gastos_mantenimientos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$gastos_mantenimientos->ResetAttrs();
$gastos_mantenimientos_grid->RenderRow();
if ($gastos_mantenimientos->CurrentAction == "gridadd")
	$gastos_mantenimientos_grid->RowIndex = 0;
if ($gastos_mantenimientos->CurrentAction == "gridedit")
	$gastos_mantenimientos_grid->RowIndex = 0;
while ($gastos_mantenimientos_grid->RecCnt < $gastos_mantenimientos_grid->StopRec) {
	$gastos_mantenimientos_grid->RecCnt++;
	if (intval($gastos_mantenimientos_grid->RecCnt) >= intval($gastos_mantenimientos_grid->StartRec)) {
		$gastos_mantenimientos_grid->RowCnt++;
		if ($gastos_mantenimientos->CurrentAction == "gridadd" || $gastos_mantenimientos->CurrentAction == "gridedit" || $gastos_mantenimientos->CurrentAction == "F") {
			$gastos_mantenimientos_grid->RowIndex++;
			$objForm->Index = $gastos_mantenimientos_grid->RowIndex;
			if ($objForm->HasValue($gastos_mantenimientos_grid->FormActionName))
				$gastos_mantenimientos_grid->RowAction = strval($objForm->GetValue($gastos_mantenimientos_grid->FormActionName));
			elseif ($gastos_mantenimientos->CurrentAction == "gridadd")
				$gastos_mantenimientos_grid->RowAction = "insert";
			else
				$gastos_mantenimientos_grid->RowAction = "";
		}

		// Set up key count
		$gastos_mantenimientos_grid->KeyCount = $gastos_mantenimientos_grid->RowIndex;

		// Init row class and style
		$gastos_mantenimientos->ResetAttrs();
		$gastos_mantenimientos->CssClass = "";
		if ($gastos_mantenimientos->CurrentAction == "gridadd") {
			if ($gastos_mantenimientos->CurrentMode == "copy") {
				$gastos_mantenimientos_grid->LoadRowValues($gastos_mantenimientos_grid->Recordset); // Load row values
				$gastos_mantenimientos_grid->SetRecordKey($gastos_mantenimientos_grid->RowOldKey, $gastos_mantenimientos_grid->Recordset); // Set old record key
			} else {
				$gastos_mantenimientos_grid->LoadDefaultValues(); // Load default values
				$gastos_mantenimientos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$gastos_mantenimientos_grid->LoadRowValues($gastos_mantenimientos_grid->Recordset); // Load row values
		}
		$gastos_mantenimientos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($gastos_mantenimientos->CurrentAction == "gridadd") // Grid add
			$gastos_mantenimientos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($gastos_mantenimientos->CurrentAction == "gridadd" && $gastos_mantenimientos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$gastos_mantenimientos_grid->RestoreCurrentRowFormValues($gastos_mantenimientos_grid->RowIndex); // Restore form values
		if ($gastos_mantenimientos->CurrentAction == "gridedit") { // Grid edit
			if ($gastos_mantenimientos->EventCancelled) {
				$gastos_mantenimientos_grid->RestoreCurrentRowFormValues($gastos_mantenimientos_grid->RowIndex); // Restore form values
			}
			if ($gastos_mantenimientos_grid->RowAction == "insert")
				$gastos_mantenimientos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$gastos_mantenimientos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($gastos_mantenimientos->CurrentAction == "gridedit" && ($gastos_mantenimientos->RowType == EW_ROWTYPE_EDIT || $gastos_mantenimientos->RowType == EW_ROWTYPE_ADD) && $gastos_mantenimientos->EventCancelled) // Update failed
			$gastos_mantenimientos_grid->RestoreCurrentRowFormValues($gastos_mantenimientos_grid->RowIndex); // Restore form values
		if ($gastos_mantenimientos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$gastos_mantenimientos_grid->EditRowCnt++;
		if ($gastos_mantenimientos->CurrentAction == "F") // Confirm row
			$gastos_mantenimientos_grid->RestoreCurrentRowFormValues($gastos_mantenimientos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$gastos_mantenimientos->RowAttrs = array_merge($gastos_mantenimientos->RowAttrs, array('data-rowindex'=>$gastos_mantenimientos_grid->RowCnt, 'id'=>'r' . $gastos_mantenimientos_grid->RowCnt . '_gastos_mantenimientos', 'data-rowtype'=>$gastos_mantenimientos->RowType));

		// Render row
		$gastos_mantenimientos_grid->RenderRow();

		// Render list options
		$gastos_mantenimientos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($gastos_mantenimientos_grid->RowAction <> "delete" && $gastos_mantenimientos_grid->RowAction <> "insertdelete" && !($gastos_mantenimientos_grid->RowAction == "insert" && $gastos_mantenimientos->CurrentAction == "F" && $gastos_mantenimientos_grid->EmptyRow())) {
?>
	<tr<?php echo $gastos_mantenimientos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gastos_mantenimientos_grid->ListOptions->Render("body", "left", $gastos_mantenimientos_grid->RowCnt);
?>
	<?php if ($gastos_mantenimientos->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $gastos_mantenimientos->codigo->CellAttributes() ?>>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_codigo" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->codigo->OldValue) ?>">
<?php } ?>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_codigo" class="form-group gastos_mantenimientos_codigo">
<span<?php echo $gastos_mantenimientos->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->codigo->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->codigo->CurrentValue) ?>">
<?php } ?>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos_mantenimientos->codigo->ViewAttributes() ?>>
<?php echo $gastos_mantenimientos->codigo->ListViewValue() ?></span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->codigo->FormValue) ?>">
<input type="hidden" data-field="x_codigo" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->codigo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $gastos_mantenimientos_grid->PageObjName . "_row_" . $gastos_mantenimientos_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gastos_mantenimientos->detalle->Visible) { // detalle ?>
		<td data-name="detalle"<?php echo $gastos_mantenimientos->detalle->CellAttributes() ?>>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_detalle" class="form-group gastos_mantenimientos_detalle">
<input type="text" data-field="x_detalle" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->detalle->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->detalle->EditValue ?>"<?php echo $gastos_mantenimientos->detalle->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_detalle" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->detalle->OldValue) ?>">
<?php } ?>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_detalle" class="form-group gastos_mantenimientos_detalle">
<input type="text" data-field="x_detalle" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->detalle->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->detalle->EditValue ?>"<?php echo $gastos_mantenimientos->detalle->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos_mantenimientos->detalle->ViewAttributes() ?>>
<?php echo $gastos_mantenimientos->detalle->ListViewValue() ?></span>
<input type="hidden" data-field="x_detalle" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->detalle->FormValue) ?>">
<input type="hidden" data-field="x_detalle" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->detalle->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos_mantenimientos->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $gastos_mantenimientos->fecha->CellAttributes() ?>>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_fecha" class="form-group gastos_mantenimientos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->fecha->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->fecha->EditValue ?>"<?php echo $gastos_mantenimientos->fecha->EditAttributes() ?>>
<?php if (!$gastos_mantenimientos->fecha->ReadOnly && !$gastos_mantenimientos->fecha->Disabled && !isset($gastos_mantenimientos->fecha->EditAttrs["readonly"]) && !isset($gastos_mantenimientos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastos_mantenimientosgrid", "x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->fecha->OldValue) ?>">
<?php } ?>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_fecha" class="form-group gastos_mantenimientos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->fecha->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->fecha->EditValue ?>"<?php echo $gastos_mantenimientos->fecha->EditAttributes() ?>>
<?php if (!$gastos_mantenimientos->fecha->ReadOnly && !$gastos_mantenimientos->fecha->Disabled && !isset($gastos_mantenimientos->fecha->EditAttrs["readonly"]) && !isset($gastos_mantenimientos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastos_mantenimientosgrid", "x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos_mantenimientos->fecha->ViewAttributes() ?>>
<?php echo $gastos_mantenimientos->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos_mantenimientos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td data-name="id_tipo_gasto"<?php echo $gastos_mantenimientos->id_tipo_gasto->CellAttributes() ?>>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($gastos_mantenimientos->id_tipo_gasto->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_id_tipo_gasto" class="form-group gastos_mantenimientos_id_tipo_gasto">
<span<?php echo $gastos_mantenimientos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_tipo_gasto->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_id_tipo_gasto" class="form-group gastos_mantenimientos_id_tipo_gasto">
<select data-field="x_id_tipo_gasto" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto"<?php echo $gastos_mantenimientos->id_tipo_gasto->EditAttributes() ?>>
<?php
if (is_array($gastos_mantenimientos->id_tipo_gasto->EditValue)) {
	$arwrk = $gastos_mantenimientos->id_tipo_gasto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gastos_mantenimientos->id_tipo_gasto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $gastos_mantenimientos->id_tipo_gasto->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codigo`, `tipo_gasto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
 $sWhereWrk = "";
 $lookuptblfilter = "`clase`='M'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $gastos_mantenimientos->Lookup_Selecting($gastos_mantenimientos->id_tipo_gasto, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
?>
<input type="hidden" name="s_x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" id="s_x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_tipo_gasto->OldValue) ?>">
<?php } ?>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($gastos_mantenimientos->id_tipo_gasto->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_id_tipo_gasto" class="form-group gastos_mantenimientos_id_tipo_gasto">
<span<?php echo $gastos_mantenimientos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_tipo_gasto->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_id_tipo_gasto" class="form-group gastos_mantenimientos_id_tipo_gasto">
<select data-field="x_id_tipo_gasto" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto"<?php echo $gastos_mantenimientos->id_tipo_gasto->EditAttributes() ?>>
<?php
if (is_array($gastos_mantenimientos->id_tipo_gasto->EditValue)) {
	$arwrk = $gastos_mantenimientos->id_tipo_gasto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gastos_mantenimientos->id_tipo_gasto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $gastos_mantenimientos->id_tipo_gasto->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codigo`, `tipo_gasto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
 $sWhereWrk = "";
 $lookuptblfilter = "`clase`='M'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $gastos_mantenimientos->Lookup_Selecting($gastos_mantenimientos->id_tipo_gasto, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
?>
<input type="hidden" name="s_x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" id="s_x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos_mantenimientos->id_tipo_gasto->ViewAttributes() ?>>
<?php echo $gastos_mantenimientos->id_tipo_gasto->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_tipo_gasto->FormValue) ?>">
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_tipo_gasto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos_mantenimientos->id_hoja_mantenimeinto->Visible) { // id_hoja_mantenimeinto ?>
		<td data-name="id_hoja_mantenimeinto"<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->CellAttributes() ?>>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($gastos_mantenimientos->id_hoja_mantenimeinto->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_id_hoja_mantenimeinto" class="form-group gastos_mantenimientos_id_hoja_mantenimeinto">
<span<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_id_hoja_mantenimeinto" class="form-group gastos_mantenimientos_id_hoja_mantenimeinto">
<input type="text" data-field="x_id_hoja_mantenimeinto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" size="30" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->EditValue ?>"<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-field="x_id_hoja_mantenimeinto" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->OldValue) ?>">
<?php } ?>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($gastos_mantenimientos->id_hoja_mantenimeinto->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_id_hoja_mantenimeinto" class="form-group gastos_mantenimientos_id_hoja_mantenimeinto">
<span<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_mantenimientos_grid->RowCnt ?>_gastos_mantenimientos_id_hoja_mantenimeinto" class="form-group gastos_mantenimientos_id_hoja_mantenimeinto">
<input type="text" data-field="x_id_hoja_mantenimeinto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" size="30" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->EditValue ?>"<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewAttributes() ?>>
<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_hoja_mantenimeinto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->FormValue) ?>">
<input type="hidden" data-field="x_id_hoja_mantenimeinto" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$gastos_mantenimientos_grid->ListOptions->Render("body", "right", $gastos_mantenimientos_grid->RowCnt);
?>
	</tr>
<?php if ($gastos_mantenimientos->RowType == EW_ROWTYPE_ADD || $gastos_mantenimientos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fgastos_mantenimientosgrid.UpdateOpts(<?php echo $gastos_mantenimientos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($gastos_mantenimientos->CurrentAction <> "gridadd" || $gastos_mantenimientos->CurrentMode == "copy")
		if (!$gastos_mantenimientos_grid->Recordset->EOF) $gastos_mantenimientos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($gastos_mantenimientos->CurrentMode == "add" || $gastos_mantenimientos->CurrentMode == "copy" || $gastos_mantenimientos->CurrentMode == "edit") {
		$gastos_mantenimientos_grid->RowIndex = '$rowindex$';
		$gastos_mantenimientos_grid->LoadDefaultValues();

		// Set row properties
		$gastos_mantenimientos->ResetAttrs();
		$gastos_mantenimientos->RowAttrs = array_merge($gastos_mantenimientos->RowAttrs, array('data-rowindex'=>$gastos_mantenimientos_grid->RowIndex, 'id'=>'r0_gastos_mantenimientos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($gastos_mantenimientos->RowAttrs["class"], "ewTemplate");
		$gastos_mantenimientos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$gastos_mantenimientos_grid->RenderRow();

		// Render list options
		$gastos_mantenimientos_grid->RenderListOptions();
		$gastos_mantenimientos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $gastos_mantenimientos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gastos_mantenimientos_grid->ListOptions->Render("body", "left", $gastos_mantenimientos_grid->RowIndex);
?>
	<?php if ($gastos_mantenimientos->codigo->Visible) { // codigo ?>
		<td data-name="codigo">
<?php if ($gastos_mantenimientos->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_gastos_mantenimientos_codigo" class="form-group gastos_mantenimientos_codigo">
<span<?php echo $gastos_mantenimientos->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->codigo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->codigo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codigo" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->codigo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos_mantenimientos->detalle->Visible) { // detalle ?>
		<td data-name="detalle">
<?php if ($gastos_mantenimientos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_gastos_mantenimientos_detalle" class="form-group gastos_mantenimientos_detalle">
<input type="text" data-field="x_detalle" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->detalle->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->detalle->EditValue ?>"<?php echo $gastos_mantenimientos->detalle->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_gastos_mantenimientos_detalle" class="form-group gastos_mantenimientos_detalle">
<span<?php echo $gastos_mantenimientos->detalle->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->detalle->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_detalle" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->detalle->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_detalle" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_detalle" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->detalle->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos_mantenimientos->fecha->Visible) { // fecha ?>
		<td data-name="fecha">
<?php if ($gastos_mantenimientos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_gastos_mantenimientos_fecha" class="form-group gastos_mantenimientos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->fecha->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->fecha->EditValue ?>"<?php echo $gastos_mantenimientos->fecha->EditAttributes() ?>>
<?php if (!$gastos_mantenimientos->fecha->ReadOnly && !$gastos_mantenimientos->fecha->Disabled && !isset($gastos_mantenimientos->fecha->EditAttrs["readonly"]) && !isset($gastos_mantenimientos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastos_mantenimientosgrid", "x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_gastos_mantenimientos_fecha" class="form-group gastos_mantenimientos_fecha">
<span<?php echo $gastos_mantenimientos->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos_mantenimientos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td data-name="id_tipo_gasto">
<?php if ($gastos_mantenimientos->CurrentAction <> "F") { ?>
<?php if ($gastos_mantenimientos->id_tipo_gasto->getSessionValue() <> "") { ?>
<span id="el$rowindex$_gastos_mantenimientos_id_tipo_gasto" class="form-group gastos_mantenimientos_id_tipo_gasto">
<span<?php echo $gastos_mantenimientos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_tipo_gasto->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_gastos_mantenimientos_id_tipo_gasto" class="form-group gastos_mantenimientos_id_tipo_gasto">
<select data-field="x_id_tipo_gasto" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto"<?php echo $gastos_mantenimientos->id_tipo_gasto->EditAttributes() ?>>
<?php
if (is_array($gastos_mantenimientos->id_tipo_gasto->EditValue)) {
	$arwrk = $gastos_mantenimientos->id_tipo_gasto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gastos_mantenimientos->id_tipo_gasto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $gastos_mantenimientos->id_tipo_gasto->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codigo`, `tipo_gasto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
 $sWhereWrk = "";
 $lookuptblfilter = "`clase`='M'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $gastos_mantenimientos->Lookup_Selecting($gastos_mantenimientos->id_tipo_gasto, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
?>
<input type="hidden" name="s_x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" id="s_x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_gastos_mantenimientos_id_tipo_gasto" class="form-group gastos_mantenimientos_id_tipo_gasto">
<span<?php echo $gastos_mantenimientos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_tipo_gasto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_tipo_gasto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos_mantenimientos->id_hoja_mantenimeinto->Visible) { // id_hoja_mantenimeinto ?>
		<td data-name="id_hoja_mantenimeinto">
<?php if ($gastos_mantenimientos->CurrentAction <> "F") { ?>
<?php if ($gastos_mantenimientos->id_hoja_mantenimeinto->getSessionValue() <> "") { ?>
<span id="el$rowindex$_gastos_mantenimientos_id_hoja_mantenimeinto" class="form-group gastos_mantenimientos_id_hoja_mantenimeinto">
<span<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_gastos_mantenimientos_id_hoja_mantenimeinto" class="form-group gastos_mantenimientos_id_hoja_mantenimeinto">
<input type="text" data-field="x_id_hoja_mantenimeinto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" size="30" placeholder="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->PlaceHolder) ?>" value="<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->EditValue ?>"<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_gastos_mantenimientos_id_hoja_mantenimeinto" class="form-group gastos_mantenimientos_id_hoja_mantenimeinto">
<span<?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos_mantenimientos->id_hoja_mantenimeinto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_hoja_mantenimeinto" name="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" id="x<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_hoja_mantenimeinto" name="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" id="o<?php echo $gastos_mantenimientos_grid->RowIndex ?>_id_hoja_mantenimeinto" value="<?php echo ew_HtmlEncode($gastos_mantenimientos->id_hoja_mantenimeinto->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$gastos_mantenimientos_grid->ListOptions->Render("body", "right", $gastos_mantenimientos_grid->RowCnt);
?>
<script type="text/javascript">
fgastos_mantenimientosgrid.UpdateOpts(<?php echo $gastos_mantenimientos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($gastos_mantenimientos->CurrentMode == "add" || $gastos_mantenimientos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $gastos_mantenimientos_grid->FormKeyCountName ?>" id="<?php echo $gastos_mantenimientos_grid->FormKeyCountName ?>" value="<?php echo $gastos_mantenimientos_grid->KeyCount ?>">
<?php echo $gastos_mantenimientos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($gastos_mantenimientos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $gastos_mantenimientos_grid->FormKeyCountName ?>" id="<?php echo $gastos_mantenimientos_grid->FormKeyCountName ?>" value="<?php echo $gastos_mantenimientos_grid->KeyCount ?>">
<?php echo $gastos_mantenimientos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($gastos_mantenimientos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fgastos_mantenimientosgrid">
</div>
<?php

// Close recordset
if ($gastos_mantenimientos_grid->Recordset)
	$gastos_mantenimientos_grid->Recordset->Close();
?>
<?php if ($gastos_mantenimientos_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($gastos_mantenimientos_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($gastos_mantenimientos_grid->TotalRecs == 0 && $gastos_mantenimientos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($gastos_mantenimientos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($gastos_mantenimientos->Export == "") { ?>
<script type="text/javascript">
fgastos_mantenimientosgrid.Init();
</script>
<?php } ?>
<?php
$gastos_mantenimientos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$gastos_mantenimientos_grid->Page_Terminate();
?>
