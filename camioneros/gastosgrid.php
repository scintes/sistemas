<?php include_once "hoja_rutasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($gastos_grid)) $gastos_grid = new cgastos_grid();

// Page init
$gastos_grid->Page_Init();

// Page main
$gastos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gastos_grid->Page_Render();
?>
<?php if ($gastos->Export == "") { ?>
<script type="text/javascript">

// Page object
var gastos_grid = new ew_Page("gastos_grid");
gastos_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = gastos_grid.PageID; // For backward compatibility

// Form object
var fgastosgrid = new ew_Form("fgastosgrid");
fgastosgrid.FormKeyCountName = '<?php echo $gastos_grid->FormKeyCountName ?>';

// Validate form
fgastosgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codigo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos->codigo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Importe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gastos->Importe->FldErrMsg()) ?>");

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
fgastosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "fecha", false)) return false;
	if (ew_ValueChanged(fobj, infix, "detalles", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Importe", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_tipo_gasto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_hoja_ruta", false)) return false;
	return true;
}

// Form_CustomValidate event
fgastosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgastosgrid.ValidateRequired = true;
<?php } else { ?>
fgastosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgastosgrid.Lists["x_id_tipo_gasto"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_tipo_gasto","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgastosgrid.Lists["x_id_hoja_ruta"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":false,"DisplayFields":["x_codigo","x_fecha_ini","x_Origen",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($gastos->CurrentAction == "gridadd") {
	if ($gastos->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$gastos_grid->TotalRecs = $gastos->SelectRecordCount();
			$gastos_grid->Recordset = $gastos_grid->LoadRecordset($gastos_grid->StartRec-1, $gastos_grid->DisplayRecs);
		} else {
			if ($gastos_grid->Recordset = $gastos_grid->LoadRecordset())
				$gastos_grid->TotalRecs = $gastos_grid->Recordset->RecordCount();
		}
		$gastos_grid->StartRec = 1;
		$gastos_grid->DisplayRecs = $gastos_grid->TotalRecs;
	} else {
		$gastos->CurrentFilter = "0=1";
		$gastos_grid->StartRec = 1;
		$gastos_grid->DisplayRecs = $gastos->GridAddRowCount;
	}
	$gastos_grid->TotalRecs = $gastos_grid->DisplayRecs;
	$gastos_grid->StopRec = $gastos_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($gastos_grid->TotalRecs <= 0)
			$gastos_grid->TotalRecs = $gastos->SelectRecordCount();
	} else {
		if (!$gastos_grid->Recordset && ($gastos_grid->Recordset = $gastos_grid->LoadRecordset()))
			$gastos_grid->TotalRecs = $gastos_grid->Recordset->RecordCount();
	}
	$gastos_grid->StartRec = 1;
	$gastos_grid->DisplayRecs = $gastos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$gastos_grid->Recordset = $gastos_grid->LoadRecordset($gastos_grid->StartRec-1, $gastos_grid->DisplayRecs);

	// Set no record found message
	if ($gastos->CurrentAction == "" && $gastos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$gastos_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($gastos_grid->SearchWhere == "0=101")
			$gastos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$gastos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$gastos_grid->RenderOtherOptions();
?>
<?php $gastos_grid->ShowPageHeader(); ?>
<?php
$gastos_grid->ShowMessage();
?>
<?php if ($gastos_grid->TotalRecs > 0 || $gastos->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fgastosgrid" class="ewForm form-inline">
<?php if ($gastos_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($gastos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_gastos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_gastosgrid" class="table ewTable">
<?php echo $gastos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$gastos->RowType = EW_ROWTYPE_HEADER;

// Render list options
$gastos_grid->RenderListOptions();

// Render list options (header, left)
$gastos_grid->ListOptions->Render("header", "left");
?>
<?php if ($gastos->codigo->Visible) { // codigo ?>
	<?php if ($gastos->SortUrl($gastos->codigo) == "") { ?>
		<th data-name="codigo"><div id="elh_gastos_codigo" class="gastos_codigo"><div class="ewTableHeaderCaption"><?php echo $gastos->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo"><div><div id="elh_gastos_codigo" class="gastos_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos->fecha->Visible) { // fecha ?>
	<?php if ($gastos->SortUrl($gastos->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_gastos_fecha" class="gastos_fecha"><div class="ewTableHeaderCaption"><?php echo $gastos->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_gastos_fecha" class="gastos_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos->detalles->Visible) { // detalles ?>
	<?php if ($gastos->SortUrl($gastos->detalles) == "") { ?>
		<th data-name="detalles"><div id="elh_gastos_detalles" class="gastos_detalles"><div class="ewTableHeaderCaption"><?php echo $gastos->detalles->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="detalles"><div><div id="elh_gastos_detalles" class="gastos_detalles">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->detalles->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->detalles->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->detalles->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos->Importe->Visible) { // Importe ?>
	<?php if ($gastos->SortUrl($gastos->Importe) == "") { ?>
		<th data-name="Importe"><div id="elh_gastos_Importe" class="gastos_Importe"><div class="ewTableHeaderCaption"><?php echo $gastos->Importe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Importe"><div><div id="elh_gastos_Importe" class="gastos_Importe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->Importe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->Importe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->Importe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
	<?php if ($gastos->SortUrl($gastos->id_tipo_gasto) == "") { ?>
		<th data-name="id_tipo_gasto"><div id="elh_gastos_id_tipo_gasto" class="gastos_id_tipo_gasto"><div class="ewTableHeaderCaption"><?php echo $gastos->id_tipo_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipo_gasto"><div><div id="elh_gastos_id_tipo_gasto" class="gastos_id_tipo_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->id_tipo_gasto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->id_tipo_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->id_tipo_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gastos->id_hoja_ruta->Visible) { // id_hoja_ruta ?>
	<?php if ($gastos->SortUrl($gastos->id_hoja_ruta) == "") { ?>
		<th data-name="id_hoja_ruta"><div id="elh_gastos_id_hoja_ruta" class="gastos_id_hoja_ruta"><div class="ewTableHeaderCaption"><?php echo $gastos->id_hoja_ruta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_hoja_ruta"><div><div id="elh_gastos_id_hoja_ruta" class="gastos_id_hoja_ruta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gastos->id_hoja_ruta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gastos->id_hoja_ruta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gastos->id_hoja_ruta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$gastos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$gastos_grid->StartRec = 1;
$gastos_grid->StopRec = $gastos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($gastos_grid->FormKeyCountName) && ($gastos->CurrentAction == "gridadd" || $gastos->CurrentAction == "gridedit" || $gastos->CurrentAction == "F")) {
		$gastos_grid->KeyCount = $objForm->GetValue($gastos_grid->FormKeyCountName);
		$gastos_grid->StopRec = $gastos_grid->StartRec + $gastos_grid->KeyCount - 1;
	}
}
$gastos_grid->RecCnt = $gastos_grid->StartRec - 1;
if ($gastos_grid->Recordset && !$gastos_grid->Recordset->EOF) {
	$gastos_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $gastos_grid->StartRec > 1)
		$gastos_grid->Recordset->Move($gastos_grid->StartRec - 1);
} elseif (!$gastos->AllowAddDeleteRow && $gastos_grid->StopRec == 0) {
	$gastos_grid->StopRec = $gastos->GridAddRowCount;
}

// Initialize aggregate
$gastos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$gastos->ResetAttrs();
$gastos_grid->RenderRow();
if ($gastos->CurrentAction == "gridadd")
	$gastos_grid->RowIndex = 0;
if ($gastos->CurrentAction == "gridedit")
	$gastos_grid->RowIndex = 0;
while ($gastos_grid->RecCnt < $gastos_grid->StopRec) {
	$gastos_grid->RecCnt++;
	if (intval($gastos_grid->RecCnt) >= intval($gastos_grid->StartRec)) {
		$gastos_grid->RowCnt++;
		if ($gastos->CurrentAction == "gridadd" || $gastos->CurrentAction == "gridedit" || $gastos->CurrentAction == "F") {
			$gastos_grid->RowIndex++;
			$objForm->Index = $gastos_grid->RowIndex;
			if ($objForm->HasValue($gastos_grid->FormActionName))
				$gastos_grid->RowAction = strval($objForm->GetValue($gastos_grid->FormActionName));
			elseif ($gastos->CurrentAction == "gridadd")
				$gastos_grid->RowAction = "insert";
			else
				$gastos_grid->RowAction = "";
		}

		// Set up key count
		$gastos_grid->KeyCount = $gastos_grid->RowIndex;

		// Init row class and style
		$gastos->ResetAttrs();
		$gastos->CssClass = "";
		if ($gastos->CurrentAction == "gridadd") {
			if ($gastos->CurrentMode == "copy") {
				$gastos_grid->LoadRowValues($gastos_grid->Recordset); // Load row values
				$gastos_grid->SetRecordKey($gastos_grid->RowOldKey, $gastos_grid->Recordset); // Set old record key
			} else {
				$gastos_grid->LoadDefaultValues(); // Load default values
				$gastos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$gastos_grid->LoadRowValues($gastos_grid->Recordset); // Load row values
		}
		$gastos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($gastos->CurrentAction == "gridadd") // Grid add
			$gastos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($gastos->CurrentAction == "gridadd" && $gastos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$gastos_grid->RestoreCurrentRowFormValues($gastos_grid->RowIndex); // Restore form values
		if ($gastos->CurrentAction == "gridedit") { // Grid edit
			if ($gastos->EventCancelled) {
				$gastos_grid->RestoreCurrentRowFormValues($gastos_grid->RowIndex); // Restore form values
			}
			if ($gastos_grid->RowAction == "insert")
				$gastos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$gastos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($gastos->CurrentAction == "gridedit" && ($gastos->RowType == EW_ROWTYPE_EDIT || $gastos->RowType == EW_ROWTYPE_ADD) && $gastos->EventCancelled) // Update failed
			$gastos_grid->RestoreCurrentRowFormValues($gastos_grid->RowIndex); // Restore form values
		if ($gastos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$gastos_grid->EditRowCnt++;
		if ($gastos->CurrentAction == "F") // Confirm row
			$gastos_grid->RestoreCurrentRowFormValues($gastos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$gastos->RowAttrs = array_merge($gastos->RowAttrs, array('data-rowindex'=>$gastos_grid->RowCnt, 'id'=>'r' . $gastos_grid->RowCnt . '_gastos', 'data-rowtype'=>$gastos->RowType));

		// Render row
		$gastos_grid->RenderRow();

		// Render list options
		$gastos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($gastos_grid->RowAction <> "delete" && $gastos_grid->RowAction <> "insertdelete" && !($gastos_grid->RowAction == "insert" && $gastos->CurrentAction == "F" && $gastos_grid->EmptyRow())) {
?>
	<tr<?php echo $gastos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gastos_grid->ListOptions->Render("body", "left", $gastos_grid->RowCnt);
?>
	<?php if ($gastos->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $gastos->codigo->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_codigo" name="o<?php echo $gastos_grid->RowIndex ?>_codigo" id="o<?php echo $gastos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos->codigo->OldValue) ?>">
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_codigo" class="form-group gastos_codigo">
<span<?php echo $gastos->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->codigo->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $gastos_grid->RowIndex ?>_codigo" id="x<?php echo $gastos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos->codigo->CurrentValue) ?>">
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->codigo->ViewAttributes() ?>>
<?php echo $gastos->codigo->ListViewValue() ?></span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $gastos_grid->RowIndex ?>_codigo" id="x<?php echo $gastos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos->codigo->FormValue) ?>">
<input type="hidden" data-field="x_codigo" name="o<?php echo $gastos_grid->RowIndex ?>_codigo" id="o<?php echo $gastos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos->codigo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $gastos_grid->PageObjName . "_row_" . $gastos_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gastos->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $gastos->fecha->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_fecha" class="form-group gastos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $gastos_grid->RowIndex ?>_fecha" id="x<?php echo $gastos_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($gastos->fecha->PlaceHolder) ?>" value="<?php echo $gastos->fecha->EditValue ?>"<?php echo $gastos->fecha->EditAttributes() ?>>
<?php if (!$gastos->fecha->ReadOnly && !$gastos->fecha->Disabled && !isset($gastos->fecha->EditAttrs["readonly"]) && !isset($gastos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastosgrid", "x<?php echo $gastos_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $gastos_grid->RowIndex ?>_fecha" id="o<?php echo $gastos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos->fecha->OldValue) ?>">
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_fecha" class="form-group gastos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $gastos_grid->RowIndex ?>_fecha" id="x<?php echo $gastos_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($gastos->fecha->PlaceHolder) ?>" value="<?php echo $gastos->fecha->EditValue ?>"<?php echo $gastos->fecha->EditAttributes() ?>>
<?php if (!$gastos->fecha->ReadOnly && !$gastos->fecha->Disabled && !isset($gastos->fecha->EditAttrs["readonly"]) && !isset($gastos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastosgrid", "x<?php echo $gastos_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->fecha->ViewAttributes() ?>>
<?php echo $gastos->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $gastos_grid->RowIndex ?>_fecha" id="x<?php echo $gastos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $gastos_grid->RowIndex ?>_fecha" id="o<?php echo $gastos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos->detalles->Visible) { // detalles ?>
		<td data-name="detalles"<?php echo $gastos->detalles->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_detalles" class="form-group gastos_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $gastos_grid->RowIndex ?>_detalles" id="x<?php echo $gastos_grid->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos->detalles->PlaceHolder) ?>" value="<?php echo $gastos->detalles->EditValue ?>"<?php echo $gastos->detalles->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_detalles" name="o<?php echo $gastos_grid->RowIndex ?>_detalles" id="o<?php echo $gastos_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($gastos->detalles->OldValue) ?>">
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_detalles" class="form-group gastos_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $gastos_grid->RowIndex ?>_detalles" id="x<?php echo $gastos_grid->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos->detalles->PlaceHolder) ?>" value="<?php echo $gastos->detalles->EditValue ?>"<?php echo $gastos->detalles->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->detalles->ViewAttributes() ?>>
<?php echo $gastos->detalles->ListViewValue() ?></span>
<input type="hidden" data-field="x_detalles" name="x<?php echo $gastos_grid->RowIndex ?>_detalles" id="x<?php echo $gastos_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($gastos->detalles->FormValue) ?>">
<input type="hidden" data-field="x_detalles" name="o<?php echo $gastos_grid->RowIndex ?>_detalles" id="o<?php echo $gastos_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($gastos->detalles->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos->Importe->Visible) { // Importe ?>
		<td data-name="Importe"<?php echo $gastos->Importe->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_Importe" class="form-group gastos_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $gastos_grid->RowIndex ?>_Importe" id="x<?php echo $gastos_grid->RowIndex ?>_Importe" size="20" placeholder="<?php echo ew_HtmlEncode($gastos->Importe->PlaceHolder) ?>" value="<?php echo $gastos->Importe->EditValue ?>"<?php echo $gastos->Importe->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Importe" name="o<?php echo $gastos_grid->RowIndex ?>_Importe" id="o<?php echo $gastos_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($gastos->Importe->OldValue) ?>">
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_Importe" class="form-group gastos_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $gastos_grid->RowIndex ?>_Importe" id="x<?php echo $gastos_grid->RowIndex ?>_Importe" size="20" placeholder="<?php echo ew_HtmlEncode($gastos->Importe->PlaceHolder) ?>" value="<?php echo $gastos->Importe->EditValue ?>"<?php echo $gastos->Importe->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->Importe->ViewAttributes() ?>>
<?php echo $gastos->Importe->ListViewValue() ?></span>
<input type="hidden" data-field="x_Importe" name="x<?php echo $gastos_grid->RowIndex ?>_Importe" id="x<?php echo $gastos_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($gastos->Importe->FormValue) ?>">
<input type="hidden" data-field="x_Importe" name="o<?php echo $gastos_grid->RowIndex ?>_Importe" id="o<?php echo $gastos_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($gastos->Importe->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td data-name="id_tipo_gasto"<?php echo $gastos->id_tipo_gasto->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($gastos->id_tipo_gasto->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<span<?php echo $gastos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<select data-field="x_id_tipo_gasto" id="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto"<?php echo $gastos->id_tipo_gasto->EditAttributes() ?>>
<?php
if (is_array($gastos->id_tipo_gasto->EditValue)) {
	$arwrk = $gastos->id_tipo_gasto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gastos->id_tipo_gasto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$gastos->id_tipo_gasto) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $gastos->id_tipo_gasto->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
 $sWhereWrk = "";
 $lookuptblfilter = "`clase`='R'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $gastos->Lookup_Selecting($gastos->id_tipo_gasto, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
?>
<input type="hidden" name="s_x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" id="s_x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->OldValue) ?>">
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($gastos->id_tipo_gasto->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<span<?php echo $gastos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<select data-field="x_id_tipo_gasto" id="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto"<?php echo $gastos->id_tipo_gasto->EditAttributes() ?>>
<?php
if (is_array($gastos->id_tipo_gasto->EditValue)) {
	$arwrk = $gastos->id_tipo_gasto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gastos->id_tipo_gasto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$gastos->id_tipo_gasto) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $gastos->id_tipo_gasto->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
 $sWhereWrk = "";
 $lookuptblfilter = "`clase`='R'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $gastos->Lookup_Selecting($gastos->id_tipo_gasto, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
?>
<input type="hidden" name="s_x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" id="s_x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->id_tipo_gasto->ViewAttributes() ?>>
<?php echo $gastos->id_tipo_gasto->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->FormValue) ?>">
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gastos->id_hoja_ruta->Visible) { // id_hoja_ruta ?>
		<td data-name="id_hoja_ruta"<?php echo $gastos->id_hoja_ruta->CellAttributes() ?>>
<?php if ($gastos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($gastos->id_hoja_ruta->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<span<?php echo $gastos->id_hoja_ruta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_hoja_ruta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" name="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<?php
	$wrkonchange = trim(" " . @$gastos->id_hoja_ruta->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gastos->id_hoja_ruta->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" style="white-space: nowrap; z-index: <?php echo (9000 - $gastos_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="sv_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo $gastos->id_hoja_ruta->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->PlaceHolder) ?>"<?php echo $gastos->id_hoja_ruta->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_hoja_ruta" name="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld` FROM `hoja_rutas`";
 $sWhereWrk = "`codigo` LIKE '{query_value}%' OR CONCAT(`codigo`,'" . ew_ValueSeparator(1, $Page->id_hoja_ruta) . "',DATE_FORMAT(`fecha_ini`, '%d/%m/%Y'),'" . ew_ValueSeparator(2, $Page->id_hoja_ruta) . "',`Origen`) LIKE '{query_value}%'";
 if (!$GLOBALS["gastos"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["hoja_rutas"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $gastos->Lookup_Selecting($gastos->id_hoja_ruta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `codigo` ASC";
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="q_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fgastosgrid.CreateAutoSuggest("x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta", false);
</script>
</span>
<?php } ?>
<input type="hidden" data-field="x_id_hoja_ruta" name="o<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="o<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->OldValue) ?>">
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($gastos->id_hoja_ruta->getSessionValue() <> "") { ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<span<?php echo $gastos->id_hoja_ruta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_hoja_ruta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" name="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $gastos_grid->RowCnt ?>_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<?php
	$wrkonchange = trim(" " . @$gastos->id_hoja_ruta->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gastos->id_hoja_ruta->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" style="white-space: nowrap; z-index: <?php echo (9000 - $gastos_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="sv_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo $gastos->id_hoja_ruta->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->PlaceHolder) ?>"<?php echo $gastos->id_hoja_ruta->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_hoja_ruta" name="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld` FROM `hoja_rutas`";
 $sWhereWrk = "`codigo` LIKE '{query_value}%' OR CONCAT(`codigo`,'" . ew_ValueSeparator(1, $Page->id_hoja_ruta) . "',DATE_FORMAT(`fecha_ini`, '%d/%m/%Y'),'" . ew_ValueSeparator(2, $Page->id_hoja_ruta) . "',`Origen`) LIKE '{query_value}%'";
 if (!$GLOBALS["gastos"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["hoja_rutas"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $gastos->Lookup_Selecting($gastos->id_hoja_ruta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `codigo` ASC";
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="q_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fgastosgrid.CreateAutoSuggest("x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta", false);
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($gastos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gastos->id_hoja_ruta->ViewAttributes() ?>>
<?php echo $gastos->id_hoja_ruta->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_hoja_ruta" name="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->FormValue) ?>">
<input type="hidden" data-field="x_id_hoja_ruta" name="o<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="o<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$gastos_grid->ListOptions->Render("body", "right", $gastos_grid->RowCnt);
?>
	</tr>
<?php if ($gastos->RowType == EW_ROWTYPE_ADD || $gastos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fgastosgrid.UpdateOpts(<?php echo $gastos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($gastos->CurrentAction <> "gridadd" || $gastos->CurrentMode == "copy")
		if (!$gastos_grid->Recordset->EOF) $gastos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($gastos->CurrentMode == "add" || $gastos->CurrentMode == "copy" || $gastos->CurrentMode == "edit") {
		$gastos_grid->RowIndex = '$rowindex$';
		$gastos_grid->LoadDefaultValues();

		// Set row properties
		$gastos->ResetAttrs();
		$gastos->RowAttrs = array_merge($gastos->RowAttrs, array('data-rowindex'=>$gastos_grid->RowIndex, 'id'=>'r0_gastos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($gastos->RowAttrs["class"], "ewTemplate");
		$gastos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$gastos_grid->RenderRow();

		// Render list options
		$gastos_grid->RenderListOptions();
		$gastos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $gastos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gastos_grid->ListOptions->Render("body", "left", $gastos_grid->RowIndex);
?>
	<?php if ($gastos->codigo->Visible) { // codigo ?>
		<td data-name="codigo">
<?php if ($gastos->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_gastos_codigo" class="form-group gastos_codigo">
<span<?php echo $gastos->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->codigo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $gastos_grid->RowIndex ?>_codigo" id="x<?php echo $gastos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos->codigo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codigo" name="o<?php echo $gastos_grid->RowIndex ?>_codigo" id="o<?php echo $gastos_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($gastos->codigo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos->fecha->Visible) { // fecha ?>
		<td data-name="fecha">
<?php if ($gastos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_gastos_fecha" class="form-group gastos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $gastos_grid->RowIndex ?>_fecha" id="x<?php echo $gastos_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($gastos->fecha->PlaceHolder) ?>" value="<?php echo $gastos->fecha->EditValue ?>"<?php echo $gastos->fecha->EditAttributes() ?>>
<?php if (!$gastos->fecha->ReadOnly && !$gastos->fecha->Disabled && !isset($gastos->fecha->EditAttrs["readonly"]) && !isset($gastos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fgastosgrid", "x<?php echo $gastos_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_gastos_fecha" class="form-group gastos_fecha">
<span<?php echo $gastos->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $gastos_grid->RowIndex ?>_fecha" id="x<?php echo $gastos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $gastos_grid->RowIndex ?>_fecha" id="o<?php echo $gastos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($gastos->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos->detalles->Visible) { // detalles ?>
		<td data-name="detalles">
<?php if ($gastos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_gastos_detalles" class="form-group gastos_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $gastos_grid->RowIndex ?>_detalles" id="x<?php echo $gastos_grid->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($gastos->detalles->PlaceHolder) ?>" value="<?php echo $gastos->detalles->EditValue ?>"<?php echo $gastos->detalles->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_gastos_detalles" class="form-group gastos_detalles">
<span<?php echo $gastos->detalles->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->detalles->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_detalles" name="x<?php echo $gastos_grid->RowIndex ?>_detalles" id="x<?php echo $gastos_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($gastos->detalles->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_detalles" name="o<?php echo $gastos_grid->RowIndex ?>_detalles" id="o<?php echo $gastos_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($gastos->detalles->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos->Importe->Visible) { // Importe ?>
		<td data-name="Importe">
<?php if ($gastos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_gastos_Importe" class="form-group gastos_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $gastos_grid->RowIndex ?>_Importe" id="x<?php echo $gastos_grid->RowIndex ?>_Importe" size="20" placeholder="<?php echo ew_HtmlEncode($gastos->Importe->PlaceHolder) ?>" value="<?php echo $gastos->Importe->EditValue ?>"<?php echo $gastos->Importe->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_gastos_Importe" class="form-group gastos_Importe">
<span<?php echo $gastos->Importe->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->Importe->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Importe" name="x<?php echo $gastos_grid->RowIndex ?>_Importe" id="x<?php echo $gastos_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($gastos->Importe->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_Importe" name="o<?php echo $gastos_grid->RowIndex ?>_Importe" id="o<?php echo $gastos_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($gastos->Importe->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td data-name="id_tipo_gasto">
<?php if ($gastos->CurrentAction <> "F") { ?>
<?php if ($gastos->id_tipo_gasto->getSessionValue() <> "") { ?>
<span id="el$rowindex$_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<span<?php echo $gastos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<select data-field="x_id_tipo_gasto" id="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" name="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto"<?php echo $gastos->id_tipo_gasto->EditAttributes() ?>>
<?php
if (is_array($gastos->id_tipo_gasto->EditValue)) {
	$arwrk = $gastos->id_tipo_gasto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gastos->id_tipo_gasto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$gastos->id_tipo_gasto) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $gastos->id_tipo_gasto->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT DISTINCT `codigo`, `codigo` AS `DispFld`, `tipo_gasto` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_gastos`";
 $sWhereWrk = "";
 $lookuptblfilter = "`clase`='R'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $gastos->Lookup_Selecting($gastos->id_tipo_gasto, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `tipo_gasto` ASC";
?>
<input type="hidden" name="s_x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" id="s_x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_gastos_id_tipo_gasto" class="form-group gastos_id_tipo_gasto">
<span<?php echo $gastos->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $gastos_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($gastos->id_tipo_gasto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gastos->id_hoja_ruta->Visible) { // id_hoja_ruta ?>
		<td data-name="id_hoja_ruta">
<?php if ($gastos->CurrentAction <> "F") { ?>
<?php if ($gastos->id_hoja_ruta->getSessionValue() <> "") { ?>
<span id="el$rowindex$_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<span<?php echo $gastos->id_hoja_ruta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_hoja_ruta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" name="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<?php
	$wrkonchange = trim(" " . @$gastos->id_hoja_ruta->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gastos->id_hoja_ruta->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" style="white-space: nowrap; z-index: <?php echo (9000 - $gastos_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="sv_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo $gastos->id_hoja_ruta->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->PlaceHolder) ?>"<?php echo $gastos->id_hoja_ruta->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_hoja_ruta" name="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha_ini` AS `Disp2Fld`, `Origen` AS `Disp3Fld` FROM `hoja_rutas`";
 $sWhereWrk = "`codigo` LIKE '{query_value}%' OR CONCAT(`codigo`,'" . ew_ValueSeparator(1, $Page->id_hoja_ruta) . "',DATE_FORMAT(`fecha_ini`, '%d/%m/%Y'),'" . ew_ValueSeparator(2, $Page->id_hoja_ruta) . "',`Origen`) LIKE '{query_value}%'";
 if (!$GLOBALS["gastos"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["hoja_rutas"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $gastos->Lookup_Selecting($gastos->id_hoja_ruta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `codigo` ASC";
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="q_x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fgastosgrid.CreateAutoSuggest("x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta", false);
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_gastos_id_hoja_ruta" class="form-group gastos_id_hoja_ruta">
<span<?php echo $gastos->id_hoja_ruta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gastos->id_hoja_ruta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_hoja_ruta" name="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="x<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_hoja_ruta" name="o<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" id="o<?php echo $gastos_grid->RowIndex ?>_id_hoja_ruta" value="<?php echo ew_HtmlEncode($gastos->id_hoja_ruta->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$gastos_grid->ListOptions->Render("body", "right", $gastos_grid->RowCnt);
?>
<script type="text/javascript">
fgastosgrid.UpdateOpts(<?php echo $gastos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($gastos->CurrentMode == "add" || $gastos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $gastos_grid->FormKeyCountName ?>" id="<?php echo $gastos_grid->FormKeyCountName ?>" value="<?php echo $gastos_grid->KeyCount ?>">
<?php echo $gastos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($gastos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $gastos_grid->FormKeyCountName ?>" id="<?php echo $gastos_grid->FormKeyCountName ?>" value="<?php echo $gastos_grid->KeyCount ?>">
<?php echo $gastos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($gastos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fgastosgrid">
</div>
<?php

// Close recordset
if ($gastos_grid->Recordset)
	$gastos_grid->Recordset->Close();
?>
<?php if ($gastos_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($gastos_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($gastos_grid->TotalRecs == 0 && $gastos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($gastos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($gastos->Export == "") { ?>
<script type="text/javascript">
fgastosgrid.Init();
</script>
<?php } ?>
<?php
$gastos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$gastos_grid->Page_Terminate();
?>
