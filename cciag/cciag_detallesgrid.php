<?php

// Create page object
if (!isset($detalles_grid)) $detalles_grid = new cdetalles_grid();

// Page init
$detalles_grid->Page_Init();

// Page main
$detalles_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detalles_grid->Page_Render();
?>
<?php if ($detalles->Export == "") { ?>
<script type="text/javascript">

// Page object
var detalles_grid = new ew_Page("detalles_grid");
detalles_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = detalles_grid.PageID; // For backward compatibility

// Form object
var fdetallesgrid = new ew_Form("fdetallesgrid");
fdetallesgrid.FormKeyCountName = '<?php echo $detalles_grid->FormKeyCountName ?>';

// Validate form
fdetallesgrid.Validate = function() {
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
fdetallesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "activa", false)) return false;
	return true;
}

// Form_CustomValidate event
fdetallesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetallesgrid.ValidateRequired = true;
<?php } else { ?>
fdetallesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($detalles->getCurrentMasterTable() == "" && $detalles_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $detalles_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($detalles->CurrentAction == "gridadd") {
	if ($detalles->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$detalles_grid->TotalRecs = $detalles->SelectRecordCount();
			$detalles_grid->Recordset = $detalles_grid->LoadRecordset($detalles_grid->StartRec-1, $detalles_grid->DisplayRecs);
		} else {
			if ($detalles_grid->Recordset = $detalles_grid->LoadRecordset())
				$detalles_grid->TotalRecs = $detalles_grid->Recordset->RecordCount();
		}
		$detalles_grid->StartRec = 1;
		$detalles_grid->DisplayRecs = $detalles_grid->TotalRecs;
	} else {
		$detalles->CurrentFilter = "0=1";
		$detalles_grid->StartRec = 1;
		$detalles_grid->DisplayRecs = $detalles->GridAddRowCount;
	}
	$detalles_grid->TotalRecs = $detalles_grid->DisplayRecs;
	$detalles_grid->StopRec = $detalles_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$detalles_grid->TotalRecs = $detalles->SelectRecordCount();
	} else {
		if ($detalles_grid->Recordset = $detalles_grid->LoadRecordset())
			$detalles_grid->TotalRecs = $detalles_grid->Recordset->RecordCount();
	}
	$detalles_grid->StartRec = 1;
	$detalles_grid->DisplayRecs = $detalles_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$detalles_grid->Recordset = $detalles_grid->LoadRecordset($detalles_grid->StartRec-1, $detalles_grid->DisplayRecs);
}
$detalles_grid->RenderOtherOptions();
?>
<?php $detalles_grid->ShowPageHeader(); ?>
<?php
$detalles_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fdetallesgrid" class="ewForm form-horizontal">
<?php if ($detalles_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel ewListOtherOptions">
<?php
	foreach ($detalles_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<div id="gmp_detalles" class="ewGridMiddlePanel">
<table id="tbl_detallesgrid" class="ewTable ewTableSeparate">
<?php echo $detalles->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$detalles_grid->RenderListOptions();

// Render list options (header, left)
$detalles_grid->ListOptions->Render("header", "left");
?>
<?php if ($detalles->codigo->Visible) { // codigo ?>
	<?php if ($detalles->SortUrl($detalles->codigo) == "") { ?>
		<td><div id="elh_detalles_codigo" class="detalles_codigo"><div class="ewTableHeaderCaption"><?php echo $detalles->codigo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_detalles_codigo" class="detalles_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalles->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalles->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalles->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($detalles->nombre->Visible) { // nombre ?>
	<?php if ($detalles->SortUrl($detalles->nombre) == "") { ?>
		<td><div id="elh_detalles_nombre" class="detalles_nombre"><div class="ewTableHeaderCaption"><?php echo $detalles->nombre->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_detalles_nombre" class="detalles_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalles->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalles->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalles->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($detalles->activa->Visible) { // activa ?>
	<?php if ($detalles->SortUrl($detalles->activa) == "") { ?>
		<td><div id="elh_detalles_activa" class="detalles_activa"><div class="ewTableHeaderCaption"><?php echo $detalles->activa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_detalles_activa" class="detalles_activa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalles->activa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalles->activa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalles->activa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$detalles_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$detalles_grid->StartRec = 1;
$detalles_grid->StopRec = $detalles_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($detalles_grid->FormKeyCountName) && ($detalles->CurrentAction == "gridadd" || $detalles->CurrentAction == "gridedit" || $detalles->CurrentAction == "F")) {
		$detalles_grid->KeyCount = $objForm->GetValue($detalles_grid->FormKeyCountName);
		$detalles_grid->StopRec = $detalles_grid->StartRec + $detalles_grid->KeyCount - 1;
	}
}
$detalles_grid->RecCnt = $detalles_grid->StartRec - 1;
if ($detalles_grid->Recordset && !$detalles_grid->Recordset->EOF) {
	$detalles_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $detalles_grid->StartRec > 1)
		$detalles_grid->Recordset->Move($detalles_grid->StartRec - 1);
} elseif (!$detalles->AllowAddDeleteRow && $detalles_grid->StopRec == 0) {
	$detalles_grid->StopRec = $detalles->GridAddRowCount;
}

// Initialize aggregate
$detalles->RowType = EW_ROWTYPE_AGGREGATEINIT;
$detalles->ResetAttrs();
$detalles_grid->RenderRow();
if ($detalles->CurrentAction == "gridadd")
	$detalles_grid->RowIndex = 0;
if ($detalles->CurrentAction == "gridedit")
	$detalles_grid->RowIndex = 0;
while ($detalles_grid->RecCnt < $detalles_grid->StopRec) {
	$detalles_grid->RecCnt++;
	if (intval($detalles_grid->RecCnt) >= intval($detalles_grid->StartRec)) {
		$detalles_grid->RowCnt++;
		if ($detalles->CurrentAction == "gridadd" || $detalles->CurrentAction == "gridedit" || $detalles->CurrentAction == "F") {
			$detalles_grid->RowIndex++;
			$objForm->Index = $detalles_grid->RowIndex;
			if ($objForm->HasValue($detalles_grid->FormActionName))
				$detalles_grid->RowAction = strval($objForm->GetValue($detalles_grid->FormActionName));
			elseif ($detalles->CurrentAction == "gridadd")
				$detalles_grid->RowAction = "insert";
			else
				$detalles_grid->RowAction = "";
		}

		// Set up key count
		$detalles_grid->KeyCount = $detalles_grid->RowIndex;

		// Init row class and style
		$detalles->ResetAttrs();
		$detalles->CssClass = "";
		if ($detalles->CurrentAction == "gridadd") {
			if ($detalles->CurrentMode == "copy") {
				$detalles_grid->LoadRowValues($detalles_grid->Recordset); // Load row values
				$detalles_grid->SetRecordKey($detalles_grid->RowOldKey, $detalles_grid->Recordset); // Set old record key
			} else {
				$detalles_grid->LoadDefaultValues(); // Load default values
				$detalles_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$detalles_grid->LoadRowValues($detalles_grid->Recordset); // Load row values
		}
		$detalles->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($detalles->CurrentAction == "gridadd") // Grid add
			$detalles->RowType = EW_ROWTYPE_ADD; // Render add
		if ($detalles->CurrentAction == "gridadd" && $detalles->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$detalles_grid->RestoreCurrentRowFormValues($detalles_grid->RowIndex); // Restore form values
		if ($detalles->CurrentAction == "gridedit") { // Grid edit
			if ($detalles->EventCancelled) {
				$detalles_grid->RestoreCurrentRowFormValues($detalles_grid->RowIndex); // Restore form values
			}
			if ($detalles_grid->RowAction == "insert")
				$detalles->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$detalles->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($detalles->CurrentAction == "gridedit" && ($detalles->RowType == EW_ROWTYPE_EDIT || $detalles->RowType == EW_ROWTYPE_ADD) && $detalles->EventCancelled) // Update failed
			$detalles_grid->RestoreCurrentRowFormValues($detalles_grid->RowIndex); // Restore form values
		if ($detalles->RowType == EW_ROWTYPE_EDIT) // Edit row
			$detalles_grid->EditRowCnt++;
		if ($detalles->CurrentAction == "F") // Confirm row
			$detalles_grid->RestoreCurrentRowFormValues($detalles_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$detalles->RowAttrs = array_merge($detalles->RowAttrs, array('data-rowindex'=>$detalles_grid->RowCnt, 'id'=>'r' . $detalles_grid->RowCnt . '_detalles', 'data-rowtype'=>$detalles->RowType));

		// Render row
		$detalles_grid->RenderRow();

		// Render list options
		$detalles_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($detalles_grid->RowAction <> "delete" && $detalles_grid->RowAction <> "insertdelete" && !($detalles_grid->RowAction == "insert" && $detalles->CurrentAction == "F" && $detalles_grid->EmptyRow())) {
?>
	<tr<?php echo $detalles->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detalles_grid->ListOptions->Render("body", "left", $detalles_grid->RowCnt);
?>
	<?php if ($detalles->codigo->Visible) { // codigo ?>
		<td<?php echo $detalles->codigo->CellAttributes() ?>>
<?php if ($detalles->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_codigo" name="o<?php echo $detalles_grid->RowIndex ?>_codigo" id="o<?php echo $detalles_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($detalles->codigo->OldValue) ?>">
<?php } ?>
<?php if ($detalles->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalles_grid->RowCnt ?>_detalles_codigo" class="control-group detalles_codigo">
<span<?php echo $detalles->codigo->ViewAttributes() ?>>
<?php echo $detalles->codigo->EditValue ?></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $detalles_grid->RowIndex ?>_codigo" id="x<?php echo $detalles_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($detalles->codigo->CurrentValue) ?>">
<?php } ?>
<?php if ($detalles->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detalles->codigo->ViewAttributes() ?>>
<?php echo $detalles->codigo->ListViewValue() ?></span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $detalles_grid->RowIndex ?>_codigo" id="x<?php echo $detalles_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($detalles->codigo->FormValue) ?>">
<input type="hidden" data-field="x_codigo" name="o<?php echo $detalles_grid->RowIndex ?>_codigo" id="o<?php echo $detalles_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($detalles->codigo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $detalles_grid->PageObjName . "_row_" . $detalles_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($detalles->nombre->Visible) { // nombre ?>
		<td<?php echo $detalles->nombre->CellAttributes() ?>>
<?php if ($detalles->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detalles_grid->RowCnt ?>_detalles_nombre" class="control-group detalles_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $detalles_grid->RowIndex ?>_nombre" id="x<?php echo $detalles_grid->RowIndex ?>_nombre" size="30" maxlength="255" placeholder="<?php echo $detalles->nombre->PlaceHolder ?>" value="<?php echo $detalles->nombre->EditValue ?>"<?php echo $detalles->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nombre" name="o<?php echo $detalles_grid->RowIndex ?>_nombre" id="o<?php echo $detalles_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($detalles->nombre->OldValue) ?>">
<?php } ?>
<?php if ($detalles->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalles_grid->RowCnt ?>_detalles_nombre" class="control-group detalles_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $detalles_grid->RowIndex ?>_nombre" id="x<?php echo $detalles_grid->RowIndex ?>_nombre" size="30" maxlength="255" placeholder="<?php echo $detalles->nombre->PlaceHolder ?>" value="<?php echo $detalles->nombre->EditValue ?>"<?php echo $detalles->nombre->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detalles->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detalles->nombre->ViewAttributes() ?>>
<?php echo $detalles->nombre->ListViewValue() ?></span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $detalles_grid->RowIndex ?>_nombre" id="x<?php echo $detalles_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($detalles->nombre->FormValue) ?>">
<input type="hidden" data-field="x_nombre" name="o<?php echo $detalles_grid->RowIndex ?>_nombre" id="o<?php echo $detalles_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($detalles->nombre->OldValue) ?>">
<?php } ?>
<a id="<?php echo $detalles_grid->PageObjName . "_row_" . $detalles_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($detalles->activa->Visible) { // activa ?>
		<td<?php echo $detalles->activa->CellAttributes() ?>>
<?php if ($detalles->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detalles_grid->RowCnt ?>_detalles_activa" class="control-group detalles_activa">
<div id="tp_x<?php echo $detalles_grid->RowIndex ?>_activa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $detalles_grid->RowIndex ?>_activa" id="x<?php echo $detalles_grid->RowIndex ?>_activa" value="{value}"<?php echo $detalles->activa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detalles_grid->RowIndex ?>_activa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $detalles->activa->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detalles->activa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_activa" name="x<?php echo $detalles_grid->RowIndex ?>_activa" id="x<?php echo $detalles_grid->RowIndex ?>_activa_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $detalles->activa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $detalles->activa->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_activa" name="o<?php echo $detalles_grid->RowIndex ?>_activa" id="o<?php echo $detalles_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($detalles->activa->OldValue) ?>">
<?php } ?>
<?php if ($detalles->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalles_grid->RowCnt ?>_detalles_activa" class="control-group detalles_activa">
<div id="tp_x<?php echo $detalles_grid->RowIndex ?>_activa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $detalles_grid->RowIndex ?>_activa" id="x<?php echo $detalles_grid->RowIndex ?>_activa" value="{value}"<?php echo $detalles->activa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detalles_grid->RowIndex ?>_activa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $detalles->activa->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detalles->activa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_activa" name="x<?php echo $detalles_grid->RowIndex ?>_activa" id="x<?php echo $detalles_grid->RowIndex ?>_activa_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $detalles->activa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $detalles->activa->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($detalles->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detalles->activa->ViewAttributes() ?>>
<?php echo $detalles->activa->ListViewValue() ?></span>
<input type="hidden" data-field="x_activa" name="x<?php echo $detalles_grid->RowIndex ?>_activa" id="x<?php echo $detalles_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($detalles->activa->FormValue) ?>">
<input type="hidden" data-field="x_activa" name="o<?php echo $detalles_grid->RowIndex ?>_activa" id="o<?php echo $detalles_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($detalles->activa->OldValue) ?>">
<?php } ?>
<a id="<?php echo $detalles_grid->PageObjName . "_row_" . $detalles_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$detalles_grid->ListOptions->Render("body", "right", $detalles_grid->RowCnt);
?>
	</tr>
<?php if ($detalles->RowType == EW_ROWTYPE_ADD || $detalles->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdetallesgrid.UpdateOpts(<?php echo $detalles_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($detalles->CurrentAction <> "gridadd" || $detalles->CurrentMode == "copy")
		if (!$detalles_grid->Recordset->EOF) $detalles_grid->Recordset->MoveNext();
}
?>
<?php
	if ($detalles->CurrentMode == "add" || $detalles->CurrentMode == "copy" || $detalles->CurrentMode == "edit") {
		$detalles_grid->RowIndex = '$rowindex$';
		$detalles_grid->LoadDefaultValues();

		// Set row properties
		$detalles->ResetAttrs();
		$detalles->RowAttrs = array_merge($detalles->RowAttrs, array('data-rowindex'=>$detalles_grid->RowIndex, 'id'=>'r0_detalles', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($detalles->RowAttrs["class"], "ewTemplate");
		$detalles->RowType = EW_ROWTYPE_ADD;

		// Render row
		$detalles_grid->RenderRow();

		// Render list options
		$detalles_grid->RenderListOptions();
		$detalles_grid->StartRowCnt = 0;
?>
	<tr<?php echo $detalles->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detalles_grid->ListOptions->Render("body", "left", $detalles_grid->RowIndex);
?>
	<?php if ($detalles->codigo->Visible) { // codigo ?>
		<td>
<?php if ($detalles->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_detalles_codigo" class="control-group detalles_codigo">
<span<?php echo $detalles->codigo->ViewAttributes() ?>>
<?php echo $detalles->codigo->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $detalles_grid->RowIndex ?>_codigo" id="x<?php echo $detalles_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($detalles->codigo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codigo" name="o<?php echo $detalles_grid->RowIndex ?>_codigo" id="o<?php echo $detalles_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($detalles->codigo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detalles->nombre->Visible) { // nombre ?>
		<td>
<?php if ($detalles->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detalles_nombre" class="control-group detalles_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $detalles_grid->RowIndex ?>_nombre" id="x<?php echo $detalles_grid->RowIndex ?>_nombre" size="30" maxlength="255" placeholder="<?php echo $detalles->nombre->PlaceHolder ?>" value="<?php echo $detalles->nombre->EditValue ?>"<?php echo $detalles->nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detalles_nombre" class="control-group detalles_nombre">
<span<?php echo $detalles->nombre->ViewAttributes() ?>>
<?php echo $detalles->nombre->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $detalles_grid->RowIndex ?>_nombre" id="x<?php echo $detalles_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($detalles->nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nombre" name="o<?php echo $detalles_grid->RowIndex ?>_nombre" id="o<?php echo $detalles_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($detalles->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detalles->activa->Visible) { // activa ?>
		<td>
<?php if ($detalles->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detalles_activa" class="control-group detalles_activa">
<div id="tp_x<?php echo $detalles_grid->RowIndex ?>_activa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $detalles_grid->RowIndex ?>_activa" id="x<?php echo $detalles_grid->RowIndex ?>_activa" value="{value}"<?php echo $detalles->activa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detalles_grid->RowIndex ?>_activa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $detalles->activa->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detalles->activa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_activa" name="x<?php echo $detalles_grid->RowIndex ?>_activa" id="x<?php echo $detalles_grid->RowIndex ?>_activa_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $detalles->activa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $detalles->activa->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_detalles_activa" class="control-group detalles_activa">
<span<?php echo $detalles->activa->ViewAttributes() ?>>
<?php echo $detalles->activa->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_activa" name="x<?php echo $detalles_grid->RowIndex ?>_activa" id="x<?php echo $detalles_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($detalles->activa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_activa" name="o<?php echo $detalles_grid->RowIndex ?>_activa" id="o<?php echo $detalles_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($detalles->activa->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detalles_grid->ListOptions->Render("body", "right", $detalles_grid->RowCnt);
?>
<script type="text/javascript">
fdetallesgrid.UpdateOpts(<?php echo $detalles_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($detalles->CurrentMode == "add" || $detalles->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $detalles_grid->FormKeyCountName ?>" id="<?php echo $detalles_grid->FormKeyCountName ?>" value="<?php echo $detalles_grid->KeyCount ?>">
<?php echo $detalles_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detalles->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $detalles_grid->FormKeyCountName ?>" id="<?php echo $detalles_grid->FormKeyCountName ?>" value="<?php echo $detalles_grid->KeyCount ?>">
<?php echo $detalles_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detalles->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdetallesgrid">
</div>
<?php

// Close recordset
if ($detalles_grid->Recordset)
	$detalles_grid->Recordset->Close();
?>
</div>
</td></tr></table>
<?php if ($detalles->Export == "") { ?>
<script type="text/javascript">
fdetallesgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$detalles_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$detalles_grid->Page_Terminate();
?>
