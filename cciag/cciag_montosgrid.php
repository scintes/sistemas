<?php

// Create page object
if (!isset($montos_grid)) $montos_grid = new cmontos_grid();

// Page init
$montos_grid->Page_Init();

// Page main
$montos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$montos_grid->Page_Render();
?>
<?php if ($montos->Export == "") { ?>
<script type="text/javascript">

// Page object
var montos_grid = new ew_Page("montos_grid");
montos_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = montos_grid.PageID; // For backward compatibility

// Form object
var fmontosgrid = new ew_Form("fmontosgrid");
fmontosgrid.FormKeyCountName = '<?php echo $montos_grid->FormKeyCountName ?>';

// Validate form
fmontosgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_importe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($montos->importe->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_creacion");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($montos->fecha_creacion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_usuario");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($montos->id_usuario->FldErrMsg()) ?>");

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
fmontosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "descripcion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "importe", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_creacion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "activa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_usuario", false)) return false;
	return true;
}

// Form_CustomValidate event
fmontosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmontosgrid.ValidateRequired = true;
<?php } else { ?>
fmontosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($montos->getCurrentMasterTable() == "" && $montos_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $montos_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($montos->CurrentAction == "gridadd") {
	if ($montos->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$montos_grid->TotalRecs = $montos->SelectRecordCount();
			$montos_grid->Recordset = $montos_grid->LoadRecordset($montos_grid->StartRec-1, $montos_grid->DisplayRecs);
		} else {
			if ($montos_grid->Recordset = $montos_grid->LoadRecordset())
				$montos_grid->TotalRecs = $montos_grid->Recordset->RecordCount();
		}
		$montos_grid->StartRec = 1;
		$montos_grid->DisplayRecs = $montos_grid->TotalRecs;
	} else {
		$montos->CurrentFilter = "0=1";
		$montos_grid->StartRec = 1;
		$montos_grid->DisplayRecs = $montos->GridAddRowCount;
	}
	$montos_grid->TotalRecs = $montos_grid->DisplayRecs;
	$montos_grid->StopRec = $montos_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$montos_grid->TotalRecs = $montos->SelectRecordCount();
	} else {
		if ($montos_grid->Recordset = $montos_grid->LoadRecordset())
			$montos_grid->TotalRecs = $montos_grid->Recordset->RecordCount();
	}
	$montos_grid->StartRec = 1;
	$montos_grid->DisplayRecs = $montos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$montos_grid->Recordset = $montos_grid->LoadRecordset($montos_grid->StartRec-1, $montos_grid->DisplayRecs);
}
$montos_grid->RenderOtherOptions();
?>
<?php $montos_grid->ShowPageHeader(); ?>
<?php
$montos_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fmontosgrid" class="ewForm form-horizontal">
<?php if ($montos_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel ewListOtherOptions">
<?php
	foreach ($montos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<div id="gmp_montos" class="ewGridMiddlePanel">
<table id="tbl_montosgrid" class="ewTable ewTableSeparate">
<?php echo $montos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$montos_grid->RenderListOptions();

// Render list options (header, left)
$montos_grid->ListOptions->Render("header", "left");
?>
<?php if ($montos->id->Visible) { // id ?>
	<?php if ($montos->SortUrl($montos->id) == "") { ?>
		<td><div id="elh_montos_id" class="montos_id"><div class="ewTableHeaderCaption"><?php echo $montos->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_montos_id" class="montos_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $montos->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($montos->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($montos->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($montos->descripcion->Visible) { // descripcion ?>
	<?php if ($montos->SortUrl($montos->descripcion) == "") { ?>
		<td><div id="elh_montos_descripcion" class="montos_descripcion"><div class="ewTableHeaderCaption"><?php echo $montos->descripcion->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_montos_descripcion" class="montos_descripcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $montos->descripcion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($montos->descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($montos->descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($montos->importe->Visible) { // importe ?>
	<?php if ($montos->SortUrl($montos->importe) == "") { ?>
		<td><div id="elh_montos_importe" class="montos_importe"><div class="ewTableHeaderCaption"><?php echo $montos->importe->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_montos_importe" class="montos_importe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $montos->importe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($montos->importe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($montos->importe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($montos->fecha_creacion->Visible) { // fecha_creacion ?>
	<?php if ($montos->SortUrl($montos->fecha_creacion) == "") { ?>
		<td><div id="elh_montos_fecha_creacion" class="montos_fecha_creacion"><div class="ewTableHeaderCaption"><?php echo $montos->fecha_creacion->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_montos_fecha_creacion" class="montos_fecha_creacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $montos->fecha_creacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($montos->fecha_creacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($montos->fecha_creacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($montos->activa->Visible) { // activa ?>
	<?php if ($montos->SortUrl($montos->activa) == "") { ?>
		<td><div id="elh_montos_activa" class="montos_activa"><div class="ewTableHeaderCaption"><?php echo $montos->activa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_montos_activa" class="montos_activa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $montos->activa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($montos->activa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($montos->activa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($montos->id_usuario->Visible) { // id_usuario ?>
	<?php if ($montos->SortUrl($montos->id_usuario) == "") { ?>
		<td><div id="elh_montos_id_usuario" class="montos_id_usuario"><div class="ewTableHeaderCaption"><?php echo $montos->id_usuario->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_montos_id_usuario" class="montos_id_usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $montos->id_usuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($montos->id_usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($montos->id_usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$montos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$montos_grid->StartRec = 1;
$montos_grid->StopRec = $montos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($montos_grid->FormKeyCountName) && ($montos->CurrentAction == "gridadd" || $montos->CurrentAction == "gridedit" || $montos->CurrentAction == "F")) {
		$montos_grid->KeyCount = $objForm->GetValue($montos_grid->FormKeyCountName);
		$montos_grid->StopRec = $montos_grid->StartRec + $montos_grid->KeyCount - 1;
	}
}
$montos_grid->RecCnt = $montos_grid->StartRec - 1;
if ($montos_grid->Recordset && !$montos_grid->Recordset->EOF) {
	$montos_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $montos_grid->StartRec > 1)
		$montos_grid->Recordset->Move($montos_grid->StartRec - 1);
} elseif (!$montos->AllowAddDeleteRow && $montos_grid->StopRec == 0) {
	$montos_grid->StopRec = $montos->GridAddRowCount;
}

// Initialize aggregate
$montos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$montos->ResetAttrs();
$montos_grid->RenderRow();
if ($montos->CurrentAction == "gridadd")
	$montos_grid->RowIndex = 0;
if ($montos->CurrentAction == "gridedit")
	$montos_grid->RowIndex = 0;
while ($montos_grid->RecCnt < $montos_grid->StopRec) {
	$montos_grid->RecCnt++;
	if (intval($montos_grid->RecCnt) >= intval($montos_grid->StartRec)) {
		$montos_grid->RowCnt++;
		if ($montos->CurrentAction == "gridadd" || $montos->CurrentAction == "gridedit" || $montos->CurrentAction == "F") {
			$montos_grid->RowIndex++;
			$objForm->Index = $montos_grid->RowIndex;
			if ($objForm->HasValue($montos_grid->FormActionName))
				$montos_grid->RowAction = strval($objForm->GetValue($montos_grid->FormActionName));
			elseif ($montos->CurrentAction == "gridadd")
				$montos_grid->RowAction = "insert";
			else
				$montos_grid->RowAction = "";
		}

		// Set up key count
		$montos_grid->KeyCount = $montos_grid->RowIndex;

		// Init row class and style
		$montos->ResetAttrs();
		$montos->CssClass = "";
		if ($montos->CurrentAction == "gridadd") {
			if ($montos->CurrentMode == "copy") {
				$montos_grid->LoadRowValues($montos_grid->Recordset); // Load row values
				$montos_grid->SetRecordKey($montos_grid->RowOldKey, $montos_grid->Recordset); // Set old record key
			} else {
				$montos_grid->LoadDefaultValues(); // Load default values
				$montos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$montos_grid->LoadRowValues($montos_grid->Recordset); // Load row values
		}
		$montos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($montos->CurrentAction == "gridadd") // Grid add
			$montos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($montos->CurrentAction == "gridadd" && $montos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$montos_grid->RestoreCurrentRowFormValues($montos_grid->RowIndex); // Restore form values
		if ($montos->CurrentAction == "gridedit") { // Grid edit
			if ($montos->EventCancelled) {
				$montos_grid->RestoreCurrentRowFormValues($montos_grid->RowIndex); // Restore form values
			}
			if ($montos_grid->RowAction == "insert")
				$montos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$montos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($montos->CurrentAction == "gridedit" && ($montos->RowType == EW_ROWTYPE_EDIT || $montos->RowType == EW_ROWTYPE_ADD) && $montos->EventCancelled) // Update failed
			$montos_grid->RestoreCurrentRowFormValues($montos_grid->RowIndex); // Restore form values
		if ($montos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$montos_grid->EditRowCnt++;
		if ($montos->CurrentAction == "F") // Confirm row
			$montos_grid->RestoreCurrentRowFormValues($montos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$montos->RowAttrs = array_merge($montos->RowAttrs, array('data-rowindex'=>$montos_grid->RowCnt, 'id'=>'r' . $montos_grid->RowCnt . '_montos', 'data-rowtype'=>$montos->RowType));

		// Render row
		$montos_grid->RenderRow();

		// Render list options
		$montos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($montos_grid->RowAction <> "delete" && $montos_grid->RowAction <> "insertdelete" && !($montos_grid->RowAction == "insert" && $montos->CurrentAction == "F" && $montos_grid->EmptyRow())) {
?>
	<tr<?php echo $montos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$montos_grid->ListOptions->Render("body", "left", $montos_grid->RowCnt);
?>
	<?php if ($montos->id->Visible) { // id ?>
		<td<?php echo $montos->id->CellAttributes() ?>>
<?php if ($montos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="o<?php echo $montos_grid->RowIndex ?>_id" id="o<?php echo $montos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($montos->id->OldValue) ?>">
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $montos_grid->RowCnt ?>_montos_id" class="control-group montos_id">
<span<?php echo $montos->id->ViewAttributes() ?>>
<?php echo $montos->id->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id" name="x<?php echo $montos_grid->RowIndex ?>_id" id="x<?php echo $montos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($montos->id->CurrentValue) ?>">
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $montos->id->ViewAttributes() ?>>
<?php echo $montos->id->ListViewValue() ?></span>
<input type="hidden" data-field="x_id" name="x<?php echo $montos_grid->RowIndex ?>_id" id="x<?php echo $montos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($montos->id->FormValue) ?>">
<input type="hidden" data-field="x_id" name="o<?php echo $montos_grid->RowIndex ?>_id" id="o<?php echo $montos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($montos->id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $montos_grid->PageObjName . "_row_" . $montos_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($montos->descripcion->Visible) { // descripcion ?>
		<td<?php echo $montos->descripcion->CellAttributes() ?>>
<?php if ($montos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $montos_grid->RowCnt ?>_montos_descripcion" class="control-group montos_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $montos_grid->RowIndex ?>_descripcion" id="x<?php echo $montos_grid->RowIndex ?>_descripcion" size="30" maxlength="100" placeholder="<?php echo $montos->descripcion->PlaceHolder ?>" value="<?php echo $montos->descripcion->EditValue ?>"<?php echo $montos->descripcion->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $montos_grid->RowIndex ?>_descripcion" id="o<?php echo $montos_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($montos->descripcion->OldValue) ?>">
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $montos_grid->RowCnt ?>_montos_descripcion" class="control-group montos_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $montos_grid->RowIndex ?>_descripcion" id="x<?php echo $montos_grid->RowIndex ?>_descripcion" size="30" maxlength="100" placeholder="<?php echo $montos->descripcion->PlaceHolder ?>" value="<?php echo $montos->descripcion->EditValue ?>"<?php echo $montos->descripcion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $montos->descripcion->ViewAttributes() ?>>
<?php echo $montos->descripcion->ListViewValue() ?></span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $montos_grid->RowIndex ?>_descripcion" id="x<?php echo $montos_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($montos->descripcion->FormValue) ?>">
<input type="hidden" data-field="x_descripcion" name="o<?php echo $montos_grid->RowIndex ?>_descripcion" id="o<?php echo $montos_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($montos->descripcion->OldValue) ?>">
<?php } ?>
<a id="<?php echo $montos_grid->PageObjName . "_row_" . $montos_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($montos->importe->Visible) { // importe ?>
		<td<?php echo $montos->importe->CellAttributes() ?>>
<?php if ($montos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $montos_grid->RowCnt ?>_montos_importe" class="control-group montos_importe">
<input type="text" data-field="x_importe" name="x<?php echo $montos_grid->RowIndex ?>_importe" id="x<?php echo $montos_grid->RowIndex ?>_importe" size="30" placeholder="<?php echo $montos->importe->PlaceHolder ?>" value="<?php echo $montos->importe->EditValue ?>"<?php echo $montos->importe->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_importe" name="o<?php echo $montos_grid->RowIndex ?>_importe" id="o<?php echo $montos_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($montos->importe->OldValue) ?>">
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $montos_grid->RowCnt ?>_montos_importe" class="control-group montos_importe">
<input type="text" data-field="x_importe" name="x<?php echo $montos_grid->RowIndex ?>_importe" id="x<?php echo $montos_grid->RowIndex ?>_importe" size="30" placeholder="<?php echo $montos->importe->PlaceHolder ?>" value="<?php echo $montos->importe->EditValue ?>"<?php echo $montos->importe->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $montos->importe->ViewAttributes() ?>>
<?php echo $montos->importe->ListViewValue() ?></span>
<input type="hidden" data-field="x_importe" name="x<?php echo $montos_grid->RowIndex ?>_importe" id="x<?php echo $montos_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($montos->importe->FormValue) ?>">
<input type="hidden" data-field="x_importe" name="o<?php echo $montos_grid->RowIndex ?>_importe" id="o<?php echo $montos_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($montos->importe->OldValue) ?>">
<?php } ?>
<a id="<?php echo $montos_grid->PageObjName . "_row_" . $montos_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($montos->fecha_creacion->Visible) { // fecha_creacion ?>
		<td<?php echo $montos->fecha_creacion->CellAttributes() ?>>
<?php if ($montos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $montos_grid->RowCnt ?>_montos_fecha_creacion" class="control-group montos_fecha_creacion">
<input type="text" data-field="x_fecha_creacion" name="x<?php echo $montos_grid->RowIndex ?>_fecha_creacion" id="x<?php echo $montos_grid->RowIndex ?>_fecha_creacion" placeholder="<?php echo $montos->fecha_creacion->PlaceHolder ?>" value="<?php echo $montos->fecha_creacion->EditValue ?>"<?php echo $montos->fecha_creacion->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fecha_creacion" name="o<?php echo $montos_grid->RowIndex ?>_fecha_creacion" id="o<?php echo $montos_grid->RowIndex ?>_fecha_creacion" value="<?php echo ew_HtmlEncode($montos->fecha_creacion->OldValue) ?>">
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $montos_grid->RowCnt ?>_montos_fecha_creacion" class="control-group montos_fecha_creacion">
<input type="text" data-field="x_fecha_creacion" name="x<?php echo $montos_grid->RowIndex ?>_fecha_creacion" id="x<?php echo $montos_grid->RowIndex ?>_fecha_creacion" placeholder="<?php echo $montos->fecha_creacion->PlaceHolder ?>" value="<?php echo $montos->fecha_creacion->EditValue ?>"<?php echo $montos->fecha_creacion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $montos->fecha_creacion->ViewAttributes() ?>>
<?php echo $montos->fecha_creacion->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_creacion" name="x<?php echo $montos_grid->RowIndex ?>_fecha_creacion" id="x<?php echo $montos_grid->RowIndex ?>_fecha_creacion" value="<?php echo ew_HtmlEncode($montos->fecha_creacion->FormValue) ?>">
<input type="hidden" data-field="x_fecha_creacion" name="o<?php echo $montos_grid->RowIndex ?>_fecha_creacion" id="o<?php echo $montos_grid->RowIndex ?>_fecha_creacion" value="<?php echo ew_HtmlEncode($montos->fecha_creacion->OldValue) ?>">
<?php } ?>
<a id="<?php echo $montos_grid->PageObjName . "_row_" . $montos_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($montos->activa->Visible) { // activa ?>
		<td<?php echo $montos->activa->CellAttributes() ?>>
<?php if ($montos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $montos_grid->RowCnt ?>_montos_activa" class="control-group montos_activa">
<div id="tp_x<?php echo $montos_grid->RowIndex ?>_activa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $montos_grid->RowIndex ?>_activa" id="x<?php echo $montos_grid->RowIndex ?>_activa" value="{value}"<?php echo $montos->activa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $montos_grid->RowIndex ?>_activa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $montos->activa->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($montos->activa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_activa" name="x<?php echo $montos_grid->RowIndex ?>_activa" id="x<?php echo $montos_grid->RowIndex ?>_activa_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $montos->activa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $montos->activa->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_activa" name="o<?php echo $montos_grid->RowIndex ?>_activa" id="o<?php echo $montos_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($montos->activa->OldValue) ?>">
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $montos_grid->RowCnt ?>_montos_activa" class="control-group montos_activa">
<div id="tp_x<?php echo $montos_grid->RowIndex ?>_activa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $montos_grid->RowIndex ?>_activa" id="x<?php echo $montos_grid->RowIndex ?>_activa" value="{value}"<?php echo $montos->activa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $montos_grid->RowIndex ?>_activa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $montos->activa->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($montos->activa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_activa" name="x<?php echo $montos_grid->RowIndex ?>_activa" id="x<?php echo $montos_grid->RowIndex ?>_activa_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $montos->activa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $montos->activa->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $montos->activa->ViewAttributes() ?>>
<?php echo $montos->activa->ListViewValue() ?></span>
<input type="hidden" data-field="x_activa" name="x<?php echo $montos_grid->RowIndex ?>_activa" id="x<?php echo $montos_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($montos->activa->FormValue) ?>">
<input type="hidden" data-field="x_activa" name="o<?php echo $montos_grid->RowIndex ?>_activa" id="o<?php echo $montos_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($montos->activa->OldValue) ?>">
<?php } ?>
<a id="<?php echo $montos_grid->PageObjName . "_row_" . $montos_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($montos->id_usuario->Visible) { // id_usuario ?>
		<td<?php echo $montos->id_usuario->CellAttributes() ?>>
<?php if ($montos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($montos->id_usuario->getSessionValue() <> "") { ?>
<span<?php echo $montos->id_usuario->ViewAttributes() ?>>
<?php echo $montos->id_usuario->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $montos_grid->RowIndex ?>_id_usuario" name="x<?php echo $montos_grid->RowIndex ?>_id_usuario" value="<?php echo ew_HtmlEncode($montos->id_usuario->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_id_usuario" name="x<?php echo $montos_grid->RowIndex ?>_id_usuario" id="x<?php echo $montos_grid->RowIndex ?>_id_usuario" size="30" placeholder="<?php echo $montos->id_usuario->PlaceHolder ?>" value="<?php echo $montos->id_usuario->EditValue ?>"<?php echo $montos->id_usuario->EditAttributes() ?>>
<?php } ?>
<input type="hidden" data-field="x_id_usuario" name="o<?php echo $montos_grid->RowIndex ?>_id_usuario" id="o<?php echo $montos_grid->RowIndex ?>_id_usuario" value="<?php echo ew_HtmlEncode($montos->id_usuario->OldValue) ?>">
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($montos->id_usuario->getSessionValue() <> "") { ?>
<span<?php echo $montos->id_usuario->ViewAttributes() ?>>
<?php echo $montos->id_usuario->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $montos_grid->RowIndex ?>_id_usuario" name="x<?php echo $montos_grid->RowIndex ?>_id_usuario" value="<?php echo ew_HtmlEncode($montos->id_usuario->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_id_usuario" name="x<?php echo $montos_grid->RowIndex ?>_id_usuario" id="x<?php echo $montos_grid->RowIndex ?>_id_usuario" size="30" placeholder="<?php echo $montos->id_usuario->PlaceHolder ?>" value="<?php echo $montos->id_usuario->EditValue ?>"<?php echo $montos->id_usuario->EditAttributes() ?>>
<?php } ?>
<?php } ?>
<?php if ($montos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $montos->id_usuario->ViewAttributes() ?>>
<?php echo $montos->id_usuario->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_usuario" name="x<?php echo $montos_grid->RowIndex ?>_id_usuario" id="x<?php echo $montos_grid->RowIndex ?>_id_usuario" value="<?php echo ew_HtmlEncode($montos->id_usuario->FormValue) ?>">
<input type="hidden" data-field="x_id_usuario" name="o<?php echo $montos_grid->RowIndex ?>_id_usuario" id="o<?php echo $montos_grid->RowIndex ?>_id_usuario" value="<?php echo ew_HtmlEncode($montos->id_usuario->OldValue) ?>">
<?php } ?>
<a id="<?php echo $montos_grid->PageObjName . "_row_" . $montos_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$montos_grid->ListOptions->Render("body", "right", $montos_grid->RowCnt);
?>
	</tr>
<?php if ($montos->RowType == EW_ROWTYPE_ADD || $montos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fmontosgrid.UpdateOpts(<?php echo $montos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($montos->CurrentAction <> "gridadd" || $montos->CurrentMode == "copy")
		if (!$montos_grid->Recordset->EOF) $montos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($montos->CurrentMode == "add" || $montos->CurrentMode == "copy" || $montos->CurrentMode == "edit") {
		$montos_grid->RowIndex = '$rowindex$';
		$montos_grid->LoadDefaultValues();

		// Set row properties
		$montos->ResetAttrs();
		$montos->RowAttrs = array_merge($montos->RowAttrs, array('data-rowindex'=>$montos_grid->RowIndex, 'id'=>'r0_montos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($montos->RowAttrs["class"], "ewTemplate");
		$montos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$montos_grid->RenderRow();

		// Render list options
		$montos_grid->RenderListOptions();
		$montos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $montos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$montos_grid->ListOptions->Render("body", "left", $montos_grid->RowIndex);
?>
	<?php if ($montos->id->Visible) { // id ?>
		<td>
<?php if ($montos->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_montos_id" class="control-group montos_id">
<span<?php echo $montos->id->ViewAttributes() ?>>
<?php echo $montos->id->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_id" name="x<?php echo $montos_grid->RowIndex ?>_id" id="x<?php echo $montos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($montos->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id" name="o<?php echo $montos_grid->RowIndex ?>_id" id="o<?php echo $montos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($montos->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($montos->descripcion->Visible) { // descripcion ?>
		<td>
<?php if ($montos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_montos_descripcion" class="control-group montos_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $montos_grid->RowIndex ?>_descripcion" id="x<?php echo $montos_grid->RowIndex ?>_descripcion" size="30" maxlength="100" placeholder="<?php echo $montos->descripcion->PlaceHolder ?>" value="<?php echo $montos->descripcion->EditValue ?>"<?php echo $montos->descripcion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_montos_descripcion" class="control-group montos_descripcion">
<span<?php echo $montos->descripcion->ViewAttributes() ?>>
<?php echo $montos->descripcion->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $montos_grid->RowIndex ?>_descripcion" id="x<?php echo $montos_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($montos->descripcion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $montos_grid->RowIndex ?>_descripcion" id="o<?php echo $montos_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($montos->descripcion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($montos->importe->Visible) { // importe ?>
		<td>
<?php if ($montos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_montos_importe" class="control-group montos_importe">
<input type="text" data-field="x_importe" name="x<?php echo $montos_grid->RowIndex ?>_importe" id="x<?php echo $montos_grid->RowIndex ?>_importe" size="30" placeholder="<?php echo $montos->importe->PlaceHolder ?>" value="<?php echo $montos->importe->EditValue ?>"<?php echo $montos->importe->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_montos_importe" class="control-group montos_importe">
<span<?php echo $montos->importe->ViewAttributes() ?>>
<?php echo $montos->importe->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_importe" name="x<?php echo $montos_grid->RowIndex ?>_importe" id="x<?php echo $montos_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($montos->importe->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_importe" name="o<?php echo $montos_grid->RowIndex ?>_importe" id="o<?php echo $montos_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($montos->importe->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($montos->fecha_creacion->Visible) { // fecha_creacion ?>
		<td>
<?php if ($montos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_montos_fecha_creacion" class="control-group montos_fecha_creacion">
<input type="text" data-field="x_fecha_creacion" name="x<?php echo $montos_grid->RowIndex ?>_fecha_creacion" id="x<?php echo $montos_grid->RowIndex ?>_fecha_creacion" placeholder="<?php echo $montos->fecha_creacion->PlaceHolder ?>" value="<?php echo $montos->fecha_creacion->EditValue ?>"<?php echo $montos->fecha_creacion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_montos_fecha_creacion" class="control-group montos_fecha_creacion">
<span<?php echo $montos->fecha_creacion->ViewAttributes() ?>>
<?php echo $montos->fecha_creacion->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_fecha_creacion" name="x<?php echo $montos_grid->RowIndex ?>_fecha_creacion" id="x<?php echo $montos_grid->RowIndex ?>_fecha_creacion" value="<?php echo ew_HtmlEncode($montos->fecha_creacion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_creacion" name="o<?php echo $montos_grid->RowIndex ?>_fecha_creacion" id="o<?php echo $montos_grid->RowIndex ?>_fecha_creacion" value="<?php echo ew_HtmlEncode($montos->fecha_creacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($montos->activa->Visible) { // activa ?>
		<td>
<?php if ($montos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_montos_activa" class="control-group montos_activa">
<div id="tp_x<?php echo $montos_grid->RowIndex ?>_activa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $montos_grid->RowIndex ?>_activa" id="x<?php echo $montos_grid->RowIndex ?>_activa" value="{value}"<?php echo $montos->activa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $montos_grid->RowIndex ?>_activa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $montos->activa->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($montos->activa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_activa" name="x<?php echo $montos_grid->RowIndex ?>_activa" id="x<?php echo $montos_grid->RowIndex ?>_activa_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $montos->activa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $montos->activa->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_montos_activa" class="control-group montos_activa">
<span<?php echo $montos->activa->ViewAttributes() ?>>
<?php echo $montos->activa->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_activa" name="x<?php echo $montos_grid->RowIndex ?>_activa" id="x<?php echo $montos_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($montos->activa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_activa" name="o<?php echo $montos_grid->RowIndex ?>_activa" id="o<?php echo $montos_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($montos->activa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($montos->id_usuario->Visible) { // id_usuario ?>
		<td>
<?php if ($montos->CurrentAction <> "F") { ?>
<?php if ($montos->id_usuario->getSessionValue() <> "") { ?>
<span<?php echo $montos->id_usuario->ViewAttributes() ?>>
<?php echo $montos->id_usuario->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $montos_grid->RowIndex ?>_id_usuario" name="x<?php echo $montos_grid->RowIndex ?>_id_usuario" value="<?php echo ew_HtmlEncode($montos->id_usuario->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_id_usuario" name="x<?php echo $montos_grid->RowIndex ?>_id_usuario" id="x<?php echo $montos_grid->RowIndex ?>_id_usuario" size="30" placeholder="<?php echo $montos->id_usuario->PlaceHolder ?>" value="<?php echo $montos->id_usuario->EditValue ?>"<?php echo $montos->id_usuario->EditAttributes() ?>>
<?php } ?>
<?php } else { ?>
<span<?php echo $montos->id_usuario->ViewAttributes() ?>>
<?php echo $montos->id_usuario->ViewValue ?></span>
<input type="hidden" data-field="x_id_usuario" name="x<?php echo $montos_grid->RowIndex ?>_id_usuario" id="x<?php echo $montos_grid->RowIndex ?>_id_usuario" value="<?php echo ew_HtmlEncode($montos->id_usuario->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_usuario" name="o<?php echo $montos_grid->RowIndex ?>_id_usuario" id="o<?php echo $montos_grid->RowIndex ?>_id_usuario" value="<?php echo ew_HtmlEncode($montos->id_usuario->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$montos_grid->ListOptions->Render("body", "right", $montos_grid->RowCnt);
?>
<script type="text/javascript">
fmontosgrid.UpdateOpts(<?php echo $montos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($montos->CurrentMode == "add" || $montos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $montos_grid->FormKeyCountName ?>" id="<?php echo $montos_grid->FormKeyCountName ?>" value="<?php echo $montos_grid->KeyCount ?>">
<?php echo $montos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($montos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $montos_grid->FormKeyCountName ?>" id="<?php echo $montos_grid->FormKeyCountName ?>" value="<?php echo $montos_grid->KeyCount ?>">
<?php echo $montos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($montos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fmontosgrid">
</div>
<?php

// Close recordset
if ($montos_grid->Recordset)
	$montos_grid->Recordset->Close();
?>
</div>
</td></tr></table>
<?php if ($montos->Export == "") { ?>
<script type="text/javascript">
fmontosgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$montos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$montos_grid->Page_Terminate();
?>
