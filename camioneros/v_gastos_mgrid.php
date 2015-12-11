<?php

// Create page object
if (!isset($v_gastos_m_grid)) $v_gastos_m_grid = new cv_gastos_m_grid();

// Page init
$v_gastos_m_grid->Page_Init();

// Page main
$v_gastos_m_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$v_gastos_m_grid->Page_Render();
?>
<?php if ($v_gastos_m->Export == "") { ?>
<script type="text/javascript">

// Page object
var v_gastos_m_grid = new ew_Page("v_gastos_m_grid");
v_gastos_m_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = v_gastos_m_grid.PageID; // For backward compatibility

// Form object
var fv_gastos_mgrid = new ew_Form("fv_gastos_mgrid");
fv_gastos_mgrid.FormKeyCountName = '<?php echo $v_gastos_m_grid->FormKeyCountName ?>';

// Validate form
fv_gastos_mgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($v_gastos_m->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_iva");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($v_gastos_m->iva->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Importe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($v_gastos_m->Importe->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_hoja_mantenimiento");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($v_gastos_m->id_hoja_mantenimiento->FldErrMsg()) ?>");

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
fv_gastos_mgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "fecha", false)) return false;
	if (ew_ValueChanged(fobj, infix, "detalles", false)) return false;
	if (ew_ValueChanged(fobj, infix, "iva", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Importe", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_tipo_gasto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_hoja_mantenimiento", false)) return false;
	return true;
}

// Form_CustomValidate event
fv_gastos_mgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_gastos_mgrid.ValidateRequired = true;
<?php } else { ?>
fv_gastos_mgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fv_gastos_mgrid.Lists["x_id_tipo_gasto"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_tipo_gasto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($v_gastos_m->CurrentAction == "gridadd") {
	if ($v_gastos_m->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$v_gastos_m_grid->TotalRecs = $v_gastos_m->SelectRecordCount();
			$v_gastos_m_grid->Recordset = $v_gastos_m_grid->LoadRecordset($v_gastos_m_grid->StartRec-1, $v_gastos_m_grid->DisplayRecs);
		} else {
			if ($v_gastos_m_grid->Recordset = $v_gastos_m_grid->LoadRecordset())
				$v_gastos_m_grid->TotalRecs = $v_gastos_m_grid->Recordset->RecordCount();
		}
		$v_gastos_m_grid->StartRec = 1;
		$v_gastos_m_grid->DisplayRecs = $v_gastos_m_grid->TotalRecs;
	} else {
		$v_gastos_m->CurrentFilter = "0=1";
		$v_gastos_m_grid->StartRec = 1;
		$v_gastos_m_grid->DisplayRecs = $v_gastos_m->GridAddRowCount;
	}
	$v_gastos_m_grid->TotalRecs = $v_gastos_m_grid->DisplayRecs;
	$v_gastos_m_grid->StopRec = $v_gastos_m_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$v_gastos_m_grid->TotalRecs = $v_gastos_m->SelectRecordCount();
	} else {
		if ($v_gastos_m_grid->Recordset = $v_gastos_m_grid->LoadRecordset())
			$v_gastos_m_grid->TotalRecs = $v_gastos_m_grid->Recordset->RecordCount();
	}
	$v_gastos_m_grid->StartRec = 1;
	$v_gastos_m_grid->DisplayRecs = $v_gastos_m_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$v_gastos_m_grid->Recordset = $v_gastos_m_grid->LoadRecordset($v_gastos_m_grid->StartRec-1, $v_gastos_m_grid->DisplayRecs);

	// Set no record found message
	if ($v_gastos_m->CurrentAction == "" && $v_gastos_m_grid->TotalRecs == 0) {
		if ($v_gastos_m_grid->SearchWhere == "0=101")
			$v_gastos_m_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$v_gastos_m_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$v_gastos_m_grid->RenderOtherOptions();
?>
<?php $v_gastos_m_grid->ShowPageHeader(); ?>
<?php
$v_gastos_m_grid->ShowMessage();
?>
<?php if ($v_gastos_m_grid->TotalRecs > 0 || $v_gastos_m->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fv_gastos_mgrid" class="ewForm form-inline">
<div id="gmp_v_gastos_m" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_v_gastos_mgrid" class="table ewTable">
<?php echo $v_gastos_m->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$v_gastos_m_grid->RenderListOptions();

// Render list options (header, left)
$v_gastos_m_grid->ListOptions->Render("header", "left");
?>
<?php if ($v_gastos_m->codigo->Visible) { // codigo ?>
	<?php if ($v_gastos_m->SortUrl($v_gastos_m->codigo) == "") { ?>
		<th data-name="codigo"><div id="elh_v_gastos_m_codigo" class="v_gastos_m_codigo"><div class="ewTableHeaderCaption"><?php echo $v_gastos_m->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo"><div><div id="elh_v_gastos_m_codigo" class="v_gastos_m_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_m->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_m->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_m->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_m->fecha->Visible) { // fecha ?>
	<?php if ($v_gastos_m->SortUrl($v_gastos_m->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_v_gastos_m_fecha" class="v_gastos_m_fecha"><div class="ewTableHeaderCaption"><?php echo $v_gastos_m->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_v_gastos_m_fecha" class="v_gastos_m_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_m->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_m->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_m->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_m->detalles->Visible) { // detalles ?>
	<?php if ($v_gastos_m->SortUrl($v_gastos_m->detalles) == "") { ?>
		<th data-name="detalles"><div id="elh_v_gastos_m_detalles" class="v_gastos_m_detalles"><div class="ewTableHeaderCaption"><?php echo $v_gastos_m->detalles->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="detalles"><div><div id="elh_v_gastos_m_detalles" class="v_gastos_m_detalles">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_m->detalles->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_m->detalles->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_m->detalles->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_m->iva->Visible) { // iva ?>
	<?php if ($v_gastos_m->SortUrl($v_gastos_m->iva) == "") { ?>
		<th data-name="iva"><div id="elh_v_gastos_m_iva" class="v_gastos_m_iva"><div class="ewTableHeaderCaption"><?php echo $v_gastos_m->iva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iva"><div><div id="elh_v_gastos_m_iva" class="v_gastos_m_iva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_m->iva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_m->iva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_m->iva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_m->Importe->Visible) { // Importe ?>
	<?php if ($v_gastos_m->SortUrl($v_gastos_m->Importe) == "") { ?>
		<th data-name="Importe"><div id="elh_v_gastos_m_Importe" class="v_gastos_m_Importe"><div class="ewTableHeaderCaption"><?php echo $v_gastos_m->Importe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Importe"><div><div id="elh_v_gastos_m_Importe" class="v_gastos_m_Importe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_m->Importe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_m->Importe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_m->Importe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_m->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
	<?php if ($v_gastos_m->SortUrl($v_gastos_m->id_tipo_gasto) == "") { ?>
		<th data-name="id_tipo_gasto"><div id="elh_v_gastos_m_id_tipo_gasto" class="v_gastos_m_id_tipo_gasto"><div class="ewTableHeaderCaption"><?php echo $v_gastos_m->id_tipo_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipo_gasto"><div><div id="elh_v_gastos_m_id_tipo_gasto" class="v_gastos_m_id_tipo_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_m->id_tipo_gasto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_m->id_tipo_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_m->id_tipo_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gastos_m->id_hoja_mantenimiento->Visible) { // id_hoja_mantenimiento ?>
	<?php if ($v_gastos_m->SortUrl($v_gastos_m->id_hoja_mantenimiento) == "") { ?>
		<th data-name="id_hoja_mantenimiento"><div id="elh_v_gastos_m_id_hoja_mantenimiento" class="v_gastos_m_id_hoja_mantenimiento"><div class="ewTableHeaderCaption"><?php echo $v_gastos_m->id_hoja_mantenimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_hoja_mantenimiento"><div><div id="elh_v_gastos_m_id_hoja_mantenimiento" class="v_gastos_m_id_hoja_mantenimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gastos_m->id_hoja_mantenimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gastos_m->id_hoja_mantenimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gastos_m->id_hoja_mantenimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$v_gastos_m_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$v_gastos_m_grid->StartRec = 1;
$v_gastos_m_grid->StopRec = $v_gastos_m_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($v_gastos_m_grid->FormKeyCountName) && ($v_gastos_m->CurrentAction == "gridadd" || $v_gastos_m->CurrentAction == "gridedit" || $v_gastos_m->CurrentAction == "F")) {
		$v_gastos_m_grid->KeyCount = $objForm->GetValue($v_gastos_m_grid->FormKeyCountName);
		$v_gastos_m_grid->StopRec = $v_gastos_m_grid->StartRec + $v_gastos_m_grid->KeyCount - 1;
	}
}
$v_gastos_m_grid->RecCnt = $v_gastos_m_grid->StartRec - 1;
if ($v_gastos_m_grid->Recordset && !$v_gastos_m_grid->Recordset->EOF) {
	$v_gastos_m_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $v_gastos_m_grid->StartRec > 1)
		$v_gastos_m_grid->Recordset->Move($v_gastos_m_grid->StartRec - 1);
} elseif (!$v_gastos_m->AllowAddDeleteRow && $v_gastos_m_grid->StopRec == 0) {
	$v_gastos_m_grid->StopRec = $v_gastos_m->GridAddRowCount;
}

// Initialize aggregate
$v_gastos_m->RowType = EW_ROWTYPE_AGGREGATEINIT;
$v_gastos_m->ResetAttrs();
$v_gastos_m_grid->RenderRow();
if ($v_gastos_m->CurrentAction == "gridadd")
	$v_gastos_m_grid->RowIndex = 0;
if ($v_gastos_m->CurrentAction == "gridedit")
	$v_gastos_m_grid->RowIndex = 0;
while ($v_gastos_m_grid->RecCnt < $v_gastos_m_grid->StopRec) {
	$v_gastos_m_grid->RecCnt++;
	if (intval($v_gastos_m_grid->RecCnt) >= intval($v_gastos_m_grid->StartRec)) {
		$v_gastos_m_grid->RowCnt++;
		if ($v_gastos_m->CurrentAction == "gridadd" || $v_gastos_m->CurrentAction == "gridedit" || $v_gastos_m->CurrentAction == "F") {
			$v_gastos_m_grid->RowIndex++;
			$objForm->Index = $v_gastos_m_grid->RowIndex;
			if ($objForm->HasValue($v_gastos_m_grid->FormActionName))
				$v_gastos_m_grid->RowAction = strval($objForm->GetValue($v_gastos_m_grid->FormActionName));
			elseif ($v_gastos_m->CurrentAction == "gridadd")
				$v_gastos_m_grid->RowAction = "insert";
			else
				$v_gastos_m_grid->RowAction = "";
		}

		// Set up key count
		$v_gastos_m_grid->KeyCount = $v_gastos_m_grid->RowIndex;

		// Init row class and style
		$v_gastos_m->ResetAttrs();
		$v_gastos_m->CssClass = "";
		if ($v_gastos_m->CurrentAction == "gridadd") {
			if ($v_gastos_m->CurrentMode == "copy") {
				$v_gastos_m_grid->LoadRowValues($v_gastos_m_grid->Recordset); // Load row values
				$v_gastos_m_grid->SetRecordKey($v_gastos_m_grid->RowOldKey, $v_gastos_m_grid->Recordset); // Set old record key
			} else {
				$v_gastos_m_grid->LoadDefaultValues(); // Load default values
				$v_gastos_m_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$v_gastos_m_grid->LoadRowValues($v_gastos_m_grid->Recordset); // Load row values
		}
		$v_gastos_m->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($v_gastos_m->CurrentAction == "gridadd") // Grid add
			$v_gastos_m->RowType = EW_ROWTYPE_ADD; // Render add
		if ($v_gastos_m->CurrentAction == "gridadd" && $v_gastos_m->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$v_gastos_m_grid->RestoreCurrentRowFormValues($v_gastos_m_grid->RowIndex); // Restore form values
		if ($v_gastos_m->CurrentAction == "gridedit") { // Grid edit
			if ($v_gastos_m->EventCancelled) {
				$v_gastos_m_grid->RestoreCurrentRowFormValues($v_gastos_m_grid->RowIndex); // Restore form values
			}
			if ($v_gastos_m_grid->RowAction == "insert")
				$v_gastos_m->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$v_gastos_m->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($v_gastos_m->CurrentAction == "gridedit" && ($v_gastos_m->RowType == EW_ROWTYPE_EDIT || $v_gastos_m->RowType == EW_ROWTYPE_ADD) && $v_gastos_m->EventCancelled) // Update failed
			$v_gastos_m_grid->RestoreCurrentRowFormValues($v_gastos_m_grid->RowIndex); // Restore form values
		if ($v_gastos_m->RowType == EW_ROWTYPE_EDIT) // Edit row
			$v_gastos_m_grid->EditRowCnt++;
		if ($v_gastos_m->CurrentAction == "F") // Confirm row
			$v_gastos_m_grid->RestoreCurrentRowFormValues($v_gastos_m_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$v_gastos_m->RowAttrs = array_merge($v_gastos_m->RowAttrs, array('data-rowindex'=>$v_gastos_m_grid->RowCnt, 'id'=>'r' . $v_gastos_m_grid->RowCnt . '_v_gastos_m', 'data-rowtype'=>$v_gastos_m->RowType));

		// Render row
		$v_gastos_m_grid->RenderRow();

		// Render list options
		$v_gastos_m_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($v_gastos_m_grid->RowAction <> "delete" && $v_gastos_m_grid->RowAction <> "insertdelete" && !($v_gastos_m_grid->RowAction == "insert" && $v_gastos_m->CurrentAction == "F" && $v_gastos_m_grid->EmptyRow())) {
?>
	<tr<?php echo $v_gastos_m->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_gastos_m_grid->ListOptions->Render("body", "left", $v_gastos_m_grid->RowCnt);
?>
	<?php if ($v_gastos_m->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $v_gastos_m->codigo->CellAttributes() ?>>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_codigo" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gastos_m->codigo->OldValue) ?>">
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_codigo" class="form-group v_gastos_m_codigo">
<span<?php echo $v_gastos_m->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->codigo->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gastos_m->codigo->CurrentValue) ?>">
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gastos_m->codigo->ViewAttributes() ?>>
<?php echo $v_gastos_m->codigo->ListViewValue() ?></span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gastos_m->codigo->FormValue) ?>">
<input type="hidden" data-field="x_codigo" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gastos_m->codigo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $v_gastos_m_grid->PageObjName . "_row_" . $v_gastos_m_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_gastos_m->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $v_gastos_m->fecha->CellAttributes() ?>>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_fecha" class="form-group v_gastos_m_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->fecha->PlaceHolder) ?>" value="<?php echo $v_gastos_m->fecha->EditValue ?>"<?php echo $v_gastos_m->fecha->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($v_gastos_m->fecha->OldValue) ?>">
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_fecha" class="form-group v_gastos_m_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->fecha->PlaceHolder) ?>" value="<?php echo $v_gastos_m->fecha->EditValue ?>"<?php echo $v_gastos_m->fecha->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gastos_m->fecha->ViewAttributes() ?>>
<?php echo $v_gastos_m->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($v_gastos_m->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($v_gastos_m->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($v_gastos_m->detalles->Visible) { // detalles ?>
		<td data-name="detalles"<?php echo $v_gastos_m->detalles->CellAttributes() ?>>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_detalles" class="form-group v_gastos_m_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->detalles->PlaceHolder) ?>" value="<?php echo $v_gastos_m->detalles->EditValue ?>"<?php echo $v_gastos_m->detalles->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_detalles" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($v_gastos_m->detalles->OldValue) ?>">
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_detalles" class="form-group v_gastos_m_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->detalles->PlaceHolder) ?>" value="<?php echo $v_gastos_m->detalles->EditValue ?>"<?php echo $v_gastos_m->detalles->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gastos_m->detalles->ViewAttributes() ?>>
<?php echo $v_gastos_m->detalles->ListViewValue() ?></span>
<input type="hidden" data-field="x_detalles" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($v_gastos_m->detalles->FormValue) ?>">
<input type="hidden" data-field="x_detalles" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($v_gastos_m->detalles->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($v_gastos_m->iva->Visible) { // iva ?>
		<td data-name="iva"<?php echo $v_gastos_m->iva->CellAttributes() ?>>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_iva" class="form-group v_gastos_m_iva">
<input type="text" data-field="x_iva" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_iva" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->iva->PlaceHolder) ?>" value="<?php echo $v_gastos_m->iva->EditValue ?>"<?php echo $v_gastos_m->iva->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_iva" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_iva" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($v_gastos_m->iva->OldValue) ?>">
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_iva" class="form-group v_gastos_m_iva">
<input type="text" data-field="x_iva" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_iva" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->iva->PlaceHolder) ?>" value="<?php echo $v_gastos_m->iva->EditValue ?>"<?php echo $v_gastos_m->iva->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gastos_m->iva->ViewAttributes() ?>>
<?php echo $v_gastos_m->iva->ListViewValue() ?></span>
<input type="hidden" data-field="x_iva" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_iva" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($v_gastos_m->iva->FormValue) ?>">
<input type="hidden" data-field="x_iva" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_iva" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($v_gastos_m->iva->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($v_gastos_m->Importe->Visible) { // Importe ?>
		<td data-name="Importe"<?php echo $v_gastos_m->Importe->CellAttributes() ?>>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_Importe" class="form-group v_gastos_m_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->Importe->PlaceHolder) ?>" value="<?php echo $v_gastos_m->Importe->EditValue ?>"<?php echo $v_gastos_m->Importe->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Importe" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($v_gastos_m->Importe->OldValue) ?>">
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_Importe" class="form-group v_gastos_m_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->Importe->PlaceHolder) ?>" value="<?php echo $v_gastos_m->Importe->EditValue ?>"<?php echo $v_gastos_m->Importe->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gastos_m->Importe->ViewAttributes() ?>>
<?php echo $v_gastos_m->Importe->ListViewValue() ?></span>
<input type="hidden" data-field="x_Importe" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($v_gastos_m->Importe->FormValue) ?>">
<input type="hidden" data-field="x_Importe" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($v_gastos_m->Importe->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($v_gastos_m->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td data-name="id_tipo_gasto"<?php echo $v_gastos_m->id_tipo_gasto->CellAttributes() ?>>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_id_tipo_gasto" class="form-group v_gastos_m_id_tipo_gasto">
<?php
	$wrkonchange = trim(" " . @$v_gastos_m->id_tipo_gasto->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$v_gastos_m->id_tipo_gasto->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" style="white-space: nowrap; z-index: <?php echo (9000 - $v_gastos_m_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="sv_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo $v_gastos_m->id_tipo_gasto->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->PlaceHolder) ?>"<?php echo $v_gastos_m->id_tipo_gasto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->CurrentValue) ?>"<?php echo $wrkonchange ?> placeholder="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->PlaceHolder) ?>">
<?php
 $sSqlWrk = "SELECT `codigo`, `tipo_gasto` AS `DispFld` FROM `tipo_gastos`";
 $sWhereWrk = "`tipo_gasto` LIKE '{query_value}%'";
 $lookuptblfilter = "`clase`='M'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $v_gastos_m->Lookup_Selecting($v_gastos_m->id_tipo_gasto, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="q_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fv_gastos_mgrid.CreateAutoSuggest("x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto", true);
</script>
</span>
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->OldValue) ?>">
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_id_tipo_gasto" class="form-group v_gastos_m_id_tipo_gasto">
<?php
	$wrkonchange = trim(" " . @$v_gastos_m->id_tipo_gasto->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$v_gastos_m->id_tipo_gasto->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" style="white-space: nowrap; z-index: <?php echo (9000 - $v_gastos_m_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="sv_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo $v_gastos_m->id_tipo_gasto->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->PlaceHolder) ?>"<?php echo $v_gastos_m->id_tipo_gasto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->CurrentValue) ?>"<?php echo $wrkonchange ?> placeholder="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->PlaceHolder) ?>">
<?php
 $sSqlWrk = "SELECT `codigo`, `tipo_gasto` AS `DispFld` FROM `tipo_gastos`";
 $sWhereWrk = "`tipo_gasto` LIKE '{query_value}%'";
 $lookuptblfilter = "`clase`='M'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $v_gastos_m->Lookup_Selecting($v_gastos_m->id_tipo_gasto, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="q_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fv_gastos_mgrid.CreateAutoSuggest("x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto", true);
</script>
</span>
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gastos_m->id_tipo_gasto->ViewAttributes() ?>>
<?php echo $v_gastos_m->id_tipo_gasto->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->FormValue) ?>">
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($v_gastos_m->id_hoja_mantenimiento->Visible) { // id_hoja_mantenimiento ?>
		<td data-name="id_hoja_mantenimiento"<?php echo $v_gastos_m->id_hoja_mantenimiento->CellAttributes() ?>>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($v_gastos_m->id_hoja_mantenimiento->getSessionValue() <> "") { ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_id_hoja_mantenimiento" class="form-group v_gastos_m_id_hoja_mantenimiento">
<span<?php echo $v_gastos_m->id_hoja_mantenimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->id_hoja_mantenimiento->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_id_hoja_mantenimiento" class="form-group v_gastos_m_id_hoja_mantenimiento">
<input type="text" data-field="x_id_hoja_mantenimiento" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->PlaceHolder) ?>" value="<?php echo $v_gastos_m->id_hoja_mantenimiento->EditValue ?>"<?php echo $v_gastos_m->id_hoja_mantenimiento->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-field="x_id_hoja_mantenimiento" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->OldValue) ?>">
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($v_gastos_m->id_hoja_mantenimiento->getSessionValue() <> "") { ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_id_hoja_mantenimiento" class="form-group v_gastos_m_id_hoja_mantenimiento">
<span<?php echo $v_gastos_m->id_hoja_mantenimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->id_hoja_mantenimiento->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $v_gastos_m_grid->RowCnt ?>_v_gastos_m_id_hoja_mantenimiento" class="form-group v_gastos_m_id_hoja_mantenimiento">
<input type="text" data-field="x_id_hoja_mantenimiento" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->PlaceHolder) ?>" value="<?php echo $v_gastos_m->id_hoja_mantenimiento->EditValue ?>"<?php echo $v_gastos_m->id_hoja_mantenimiento->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gastos_m->id_hoja_mantenimiento->ViewAttributes() ?>>
<?php echo $v_gastos_m->id_hoja_mantenimiento->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_hoja_mantenimiento" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->FormValue) ?>">
<input type="hidden" data-field="x_id_hoja_mantenimiento" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_gastos_m_grid->ListOptions->Render("body", "right", $v_gastos_m_grid->RowCnt);
?>
	</tr>
<?php if ($v_gastos_m->RowType == EW_ROWTYPE_ADD || $v_gastos_m->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fv_gastos_mgrid.UpdateOpts(<?php echo $v_gastos_m_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($v_gastos_m->CurrentAction <> "gridadd" || $v_gastos_m->CurrentMode == "copy")
		if (!$v_gastos_m_grid->Recordset->EOF) $v_gastos_m_grid->Recordset->MoveNext();
}
?>
<?php
	if ($v_gastos_m->CurrentMode == "add" || $v_gastos_m->CurrentMode == "copy" || $v_gastos_m->CurrentMode == "edit") {
		$v_gastos_m_grid->RowIndex = '$rowindex$';
		$v_gastos_m_grid->LoadDefaultValues();

		// Set row properties
		$v_gastos_m->ResetAttrs();
		$v_gastos_m->RowAttrs = array_merge($v_gastos_m->RowAttrs, array('data-rowindex'=>$v_gastos_m_grid->RowIndex, 'id'=>'r0_v_gastos_m', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($v_gastos_m->RowAttrs["class"], "ewTemplate");
		$v_gastos_m->RowType = EW_ROWTYPE_ADD;

		// Render row
		$v_gastos_m_grid->RenderRow();

		// Render list options
		$v_gastos_m_grid->RenderListOptions();
		$v_gastos_m_grid->StartRowCnt = 0;
?>
	<tr<?php echo $v_gastos_m->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_gastos_m_grid->ListOptions->Render("body", "left", $v_gastos_m_grid->RowIndex);
?>
	<?php if ($v_gastos_m->codigo->Visible) { // codigo ?>
		<td>
<?php if ($v_gastos_m->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_v_gastos_m_codigo" class="form-group v_gastos_m_codigo">
<span<?php echo $v_gastos_m->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->codigo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gastos_m->codigo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codigo" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gastos_m->codigo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gastos_m->fecha->Visible) { // fecha ?>
		<td>
<?php if ($v_gastos_m->CurrentAction <> "F") { ?>
<span id="el$rowindex$_v_gastos_m_fecha" class="form-group v_gastos_m_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->fecha->PlaceHolder) ?>" value="<?php echo $v_gastos_m->fecha->EditValue ?>"<?php echo $v_gastos_m->fecha->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_v_gastos_m_fecha" class="form-group v_gastos_m_fecha">
<span<?php echo $v_gastos_m->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($v_gastos_m->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($v_gastos_m->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gastos_m->detalles->Visible) { // detalles ?>
		<td>
<?php if ($v_gastos_m->CurrentAction <> "F") { ?>
<span id="el$rowindex$_v_gastos_m_detalles" class="form-group v_gastos_m_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->detalles->PlaceHolder) ?>" value="<?php echo $v_gastos_m->detalles->EditValue ?>"<?php echo $v_gastos_m->detalles->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_v_gastos_m_detalles" class="form-group v_gastos_m_detalles">
<span<?php echo $v_gastos_m->detalles->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->detalles->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_detalles" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($v_gastos_m->detalles->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_detalles" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($v_gastos_m->detalles->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gastos_m->iva->Visible) { // iva ?>
		<td>
<?php if ($v_gastos_m->CurrentAction <> "F") { ?>
<span id="el$rowindex$_v_gastos_m_iva" class="form-group v_gastos_m_iva">
<input type="text" data-field="x_iva" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_iva" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->iva->PlaceHolder) ?>" value="<?php echo $v_gastos_m->iva->EditValue ?>"<?php echo $v_gastos_m->iva->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_v_gastos_m_iva" class="form-group v_gastos_m_iva">
<span<?php echo $v_gastos_m->iva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->iva->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iva" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_iva" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($v_gastos_m->iva->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iva" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_iva" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($v_gastos_m->iva->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gastos_m->Importe->Visible) { // Importe ?>
		<td>
<?php if ($v_gastos_m->CurrentAction <> "F") { ?>
<span id="el$rowindex$_v_gastos_m_Importe" class="form-group v_gastos_m_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->Importe->PlaceHolder) ?>" value="<?php echo $v_gastos_m->Importe->EditValue ?>"<?php echo $v_gastos_m->Importe->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_v_gastos_m_Importe" class="form-group v_gastos_m_Importe">
<span<?php echo $v_gastos_m->Importe->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->Importe->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Importe" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($v_gastos_m->Importe->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_Importe" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($v_gastos_m->Importe->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gastos_m->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td>
<?php if ($v_gastos_m->CurrentAction <> "F") { ?>
<span id="el$rowindex$_v_gastos_m_id_tipo_gasto" class="form-group v_gastos_m_id_tipo_gasto">
<?php
	$wrkonchange = trim(" " . @$v_gastos_m->id_tipo_gasto->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$v_gastos_m->id_tipo_gasto->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" style="white-space: nowrap; z-index: <?php echo (9000 - $v_gastos_m_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="sv_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo $v_gastos_m->id_tipo_gasto->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->PlaceHolder) ?>"<?php echo $v_gastos_m->id_tipo_gasto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->CurrentValue) ?>"<?php echo $wrkonchange ?> placeholder="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->PlaceHolder) ?>">
<?php
 $sSqlWrk = "SELECT `codigo`, `tipo_gasto` AS `DispFld` FROM `tipo_gastos`";
 $sWhereWrk = "`tipo_gasto` LIKE '{query_value}%'";
 $lookuptblfilter = "`clase`='M'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $v_gastos_m->Lookup_Selecting($v_gastos_m->id_tipo_gasto, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="q_x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fv_gastos_mgrid.CreateAutoSuggest("x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto", true);
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_v_gastos_m_id_tipo_gasto" class="form-group v_gastos_m_id_tipo_gasto">
<span<?php echo $v_gastos_m->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gastos_m->id_tipo_gasto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gastos_m->id_hoja_mantenimiento->Visible) { // id_hoja_mantenimiento ?>
		<td>
<?php if ($v_gastos_m->CurrentAction <> "F") { ?>
<?php if ($v_gastos_m->id_hoja_mantenimiento->getSessionValue() <> "") { ?>
<span id="el$rowindex$_v_gastos_m_id_hoja_mantenimiento" class="form-group v_gastos_m_id_hoja_mantenimiento">
<span<?php echo $v_gastos_m->id_hoja_mantenimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->id_hoja_mantenimiento->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_v_gastos_m_id_hoja_mantenimiento" class="form-group v_gastos_m_id_hoja_mantenimiento">
<input type="text" data-field="x_id_hoja_mantenimiento" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" size="30" placeholder="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->PlaceHolder) ?>" value="<?php echo $v_gastos_m->id_hoja_mantenimiento->EditValue ?>"<?php echo $v_gastos_m->id_hoja_mantenimiento->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_v_gastos_m_id_hoja_mantenimiento" class="form-group v_gastos_m_id_hoja_mantenimiento">
<span<?php echo $v_gastos_m->id_hoja_mantenimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gastos_m->id_hoja_mantenimiento->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_hoja_mantenimiento" name="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" id="x<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_hoja_mantenimiento" name="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" id="o<?php echo $v_gastos_m_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gastos_m->id_hoja_mantenimiento->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_gastos_m_grid->ListOptions->Render("body", "right", $v_gastos_m_grid->RowCnt);
?>
<script type="text/javascript">
fv_gastos_mgrid.UpdateOpts(<?php echo $v_gastos_m_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($v_gastos_m->CurrentMode == "add" || $v_gastos_m->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $v_gastos_m_grid->FormKeyCountName ?>" id="<?php echo $v_gastos_m_grid->FormKeyCountName ?>" value="<?php echo $v_gastos_m_grid->KeyCount ?>">
<?php echo $v_gastos_m_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($v_gastos_m->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $v_gastos_m_grid->FormKeyCountName ?>" id="<?php echo $v_gastos_m_grid->FormKeyCountName ?>" value="<?php echo $v_gastos_m_grid->KeyCount ?>">
<?php echo $v_gastos_m_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($v_gastos_m->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fv_gastos_mgrid">
</div>
<?php

// Close recordset
if ($v_gastos_m_grid->Recordset)
	$v_gastos_m_grid->Recordset->Close();
?>
<?php if ($v_gastos_m_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($v_gastos_m_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($v_gastos_m_grid->TotalRecs == 0 && $v_gastos_m->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_gastos_m_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($v_gastos_m->Export == "") { ?>
<script type="text/javascript">
fv_gastos_mgrid.Init();
</script>
<?php } ?>
<?php
$v_gastos_m_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$v_gastos_m_grid->Page_Terminate();
?>
