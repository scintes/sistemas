<?php

// Create page object
if (!isset($v_gasto_mantenieminto_grid)) $v_gasto_mantenieminto_grid = new cv_gasto_mantenieminto_grid();

// Page init
$v_gasto_mantenieminto_grid->Page_Init();

// Page main
$v_gasto_mantenieminto_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$v_gasto_mantenieminto_grid->Page_Render();
?>
<?php if ($v_gasto_mantenieminto->Export == "") { ?>
<script type="text/javascript">

// Page object
var v_gasto_mantenieminto_grid = new ew_Page("v_gasto_mantenieminto_grid");
v_gasto_mantenieminto_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = v_gasto_mantenieminto_grid.PageID; // For backward compatibility

// Form object
var fv_gasto_manteniemintogrid = new ew_Form("fv_gasto_manteniemintogrid");
fv_gasto_manteniemintogrid.FormKeyCountName = '<?php echo $v_gasto_mantenieminto_grid->FormKeyCountName ?>';

// Validate form
fv_gasto_manteniemintogrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($v_gasto_mantenieminto->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_iva");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($v_gasto_mantenieminto->iva->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Importe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($v_gasto_mantenieminto->Importe->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_tipo_gasto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($v_gasto_mantenieminto->id_tipo_gasto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_hoja_mantenimiento");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($v_gasto_mantenieminto->id_hoja_mantenimiento->FldErrMsg()) ?>");

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
fv_gasto_manteniemintogrid.EmptyRow = function(infix) {
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
fv_gasto_manteniemintogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fv_gasto_manteniemintogrid.ValidateRequired = true;
<?php } else { ?>
fv_gasto_manteniemintogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($v_gasto_mantenieminto->CurrentAction == "gridadd") {
	if ($v_gasto_mantenieminto->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$v_gasto_mantenieminto_grid->TotalRecs = $v_gasto_mantenieminto->SelectRecordCount();
			$v_gasto_mantenieminto_grid->Recordset = $v_gasto_mantenieminto_grid->LoadRecordset($v_gasto_mantenieminto_grid->StartRec-1, $v_gasto_mantenieminto_grid->DisplayRecs);
		} else {
			if ($v_gasto_mantenieminto_grid->Recordset = $v_gasto_mantenieminto_grid->LoadRecordset())
				$v_gasto_mantenieminto_grid->TotalRecs = $v_gasto_mantenieminto_grid->Recordset->RecordCount();
		}
		$v_gasto_mantenieminto_grid->StartRec = 1;
		$v_gasto_mantenieminto_grid->DisplayRecs = $v_gasto_mantenieminto_grid->TotalRecs;
	} else {
		$v_gasto_mantenieminto->CurrentFilter = "0=1";
		$v_gasto_mantenieminto_grid->StartRec = 1;
		$v_gasto_mantenieminto_grid->DisplayRecs = $v_gasto_mantenieminto->GridAddRowCount;
	}
	$v_gasto_mantenieminto_grid->TotalRecs = $v_gasto_mantenieminto_grid->DisplayRecs;
	$v_gasto_mantenieminto_grid->StopRec = $v_gasto_mantenieminto_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$v_gasto_mantenieminto_grid->TotalRecs = $v_gasto_mantenieminto->SelectRecordCount();
	} else {
		if ($v_gasto_mantenieminto_grid->Recordset = $v_gasto_mantenieminto_grid->LoadRecordset())
			$v_gasto_mantenieminto_grid->TotalRecs = $v_gasto_mantenieminto_grid->Recordset->RecordCount();
	}
	$v_gasto_mantenieminto_grid->StartRec = 1;
	$v_gasto_mantenieminto_grid->DisplayRecs = $v_gasto_mantenieminto_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$v_gasto_mantenieminto_grid->Recordset = $v_gasto_mantenieminto_grid->LoadRecordset($v_gasto_mantenieminto_grid->StartRec-1, $v_gasto_mantenieminto_grid->DisplayRecs);

	// Set no record found message
	if ($v_gasto_mantenieminto->CurrentAction == "" && $v_gasto_mantenieminto_grid->TotalRecs == 0) {
		if ($v_gasto_mantenieminto_grid->SearchWhere == "0=101")
			$v_gasto_mantenieminto_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$v_gasto_mantenieminto_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$v_gasto_mantenieminto_grid->RenderOtherOptions();
?>
<?php $v_gasto_mantenieminto_grid->ShowPageHeader(); ?>
<?php
$v_gasto_mantenieminto_grid->ShowMessage();
?>
<?php if ($v_gasto_mantenieminto_grid->TotalRecs > 0 || $v_gasto_mantenieminto->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fv_gasto_manteniemintogrid" class="ewForm form-inline">
<div id="gmp_v_gasto_mantenieminto" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_v_gasto_manteniemintogrid" class="table ewTable">
<?php echo $v_gasto_mantenieminto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$v_gasto_mantenieminto_grid->RenderListOptions();

// Render list options (header, left)
$v_gasto_mantenieminto_grid->ListOptions->Render("header", "left");
?>
<?php if ($v_gasto_mantenieminto->codigo->Visible) { // codigo ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->codigo) == "") { ?>
		<th data-name="codigo"><div id="elh_v_gasto_mantenieminto_codigo" class="v_gasto_mantenieminto_codigo"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo"><div><div id="elh_v_gasto_mantenieminto_codigo" class="v_gasto_mantenieminto_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->fecha->Visible) { // fecha ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_v_gasto_mantenieminto_fecha" class="v_gasto_mantenieminto_fecha"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_v_gasto_mantenieminto_fecha" class="v_gasto_mantenieminto_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->detalles->Visible) { // detalles ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->detalles) == "") { ?>
		<th data-name="detalles"><div id="elh_v_gasto_mantenieminto_detalles" class="v_gasto_mantenieminto_detalles"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->detalles->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="detalles"><div><div id="elh_v_gasto_mantenieminto_detalles" class="v_gasto_mantenieminto_detalles">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->detalles->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->detalles->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->detalles->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->iva->Visible) { // iva ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->iva) == "") { ?>
		<th data-name="iva"><div id="elh_v_gasto_mantenieminto_iva" class="v_gasto_mantenieminto_iva"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->iva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iva"><div><div id="elh_v_gasto_mantenieminto_iva" class="v_gasto_mantenieminto_iva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->iva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->iva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->iva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->Importe->Visible) { // Importe ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->Importe) == "") { ?>
		<th data-name="Importe"><div id="elh_v_gasto_mantenieminto_Importe" class="v_gasto_mantenieminto_Importe"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->Importe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Importe"><div><div id="elh_v_gasto_mantenieminto_Importe" class="v_gasto_mantenieminto_Importe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->Importe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->Importe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->Importe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->id_tipo_gasto) == "") { ?>
		<th data-name="id_tipo_gasto"><div id="elh_v_gasto_mantenieminto_id_tipo_gasto" class="v_gasto_mantenieminto_id_tipo_gasto"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->id_tipo_gasto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipo_gasto"><div><div id="elh_v_gasto_mantenieminto_id_tipo_gasto" class="v_gasto_mantenieminto_id_tipo_gasto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->id_tipo_gasto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->id_tipo_gasto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->id_tipo_gasto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($v_gasto_mantenieminto->id_hoja_mantenimiento->Visible) { // id_hoja_mantenimiento ?>
	<?php if ($v_gasto_mantenieminto->SortUrl($v_gasto_mantenieminto->id_hoja_mantenimiento) == "") { ?>
		<th data-name="id_hoja_mantenimiento"><div id="elh_v_gasto_mantenieminto_id_hoja_mantenimiento" class="v_gasto_mantenieminto_id_hoja_mantenimiento"><div class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_hoja_mantenimiento"><div><div id="elh_v_gasto_mantenieminto_id_hoja_mantenimiento" class="v_gasto_mantenieminto_id_hoja_mantenimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($v_gasto_mantenieminto->id_hoja_mantenimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($v_gasto_mantenieminto->id_hoja_mantenimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$v_gasto_mantenieminto_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$v_gasto_mantenieminto_grid->StartRec = 1;
$v_gasto_mantenieminto_grid->StopRec = $v_gasto_mantenieminto_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($v_gasto_mantenieminto_grid->FormKeyCountName) && ($v_gasto_mantenieminto->CurrentAction == "gridadd" || $v_gasto_mantenieminto->CurrentAction == "gridedit" || $v_gasto_mantenieminto->CurrentAction == "F")) {
		$v_gasto_mantenieminto_grid->KeyCount = $objForm->GetValue($v_gasto_mantenieminto_grid->FormKeyCountName);
		$v_gasto_mantenieminto_grid->StopRec = $v_gasto_mantenieminto_grid->StartRec + $v_gasto_mantenieminto_grid->KeyCount - 1;
	}
}
$v_gasto_mantenieminto_grid->RecCnt = $v_gasto_mantenieminto_grid->StartRec - 1;
if ($v_gasto_mantenieminto_grid->Recordset && !$v_gasto_mantenieminto_grid->Recordset->EOF) {
	$v_gasto_mantenieminto_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $v_gasto_mantenieminto_grid->StartRec > 1)
		$v_gasto_mantenieminto_grid->Recordset->Move($v_gasto_mantenieminto_grid->StartRec - 1);
} elseif (!$v_gasto_mantenieminto->AllowAddDeleteRow && $v_gasto_mantenieminto_grid->StopRec == 0) {
	$v_gasto_mantenieminto_grid->StopRec = $v_gasto_mantenieminto->GridAddRowCount;
}

// Initialize aggregate
$v_gasto_mantenieminto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$v_gasto_mantenieminto->ResetAttrs();
$v_gasto_mantenieminto_grid->RenderRow();
if ($v_gasto_mantenieminto->CurrentAction == "gridadd")
	$v_gasto_mantenieminto_grid->RowIndex = 0;
if ($v_gasto_mantenieminto->CurrentAction == "gridedit")
	$v_gasto_mantenieminto_grid->RowIndex = 0;
while ($v_gasto_mantenieminto_grid->RecCnt < $v_gasto_mantenieminto_grid->StopRec) {
	$v_gasto_mantenieminto_grid->RecCnt++;
	if (intval($v_gasto_mantenieminto_grid->RecCnt) >= intval($v_gasto_mantenieminto_grid->StartRec)) {
		$v_gasto_mantenieminto_grid->RowCnt++;
		if ($v_gasto_mantenieminto->CurrentAction == "gridadd" || $v_gasto_mantenieminto->CurrentAction == "gridedit" || $v_gasto_mantenieminto->CurrentAction == "F") {
			$v_gasto_mantenieminto_grid->RowIndex++;
			$objForm->Index = $v_gasto_mantenieminto_grid->RowIndex;
			if ($objForm->HasValue($v_gasto_mantenieminto_grid->FormActionName))
				$v_gasto_mantenieminto_grid->RowAction = strval($objForm->GetValue($v_gasto_mantenieminto_grid->FormActionName));
			elseif ($v_gasto_mantenieminto->CurrentAction == "gridadd")
				$v_gasto_mantenieminto_grid->RowAction = "insert";
			else
				$v_gasto_mantenieminto_grid->RowAction = "";
		}

		// Set up key count
		$v_gasto_mantenieminto_grid->KeyCount = $v_gasto_mantenieminto_grid->RowIndex;

		// Init row class and style
		$v_gasto_mantenieminto->ResetAttrs();
		$v_gasto_mantenieminto->CssClass = "";
		if ($v_gasto_mantenieminto->CurrentAction == "gridadd") {
			if ($v_gasto_mantenieminto->CurrentMode == "copy") {
				$v_gasto_mantenieminto_grid->LoadRowValues($v_gasto_mantenieminto_grid->Recordset); // Load row values
				$v_gasto_mantenieminto_grid->SetRecordKey($v_gasto_mantenieminto_grid->RowOldKey, $v_gasto_mantenieminto_grid->Recordset); // Set old record key
			} else {
				$v_gasto_mantenieminto_grid->LoadDefaultValues(); // Load default values
				$v_gasto_mantenieminto_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$v_gasto_mantenieminto_grid->LoadRowValues($v_gasto_mantenieminto_grid->Recordset); // Load row values
		}
		$v_gasto_mantenieminto->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($v_gasto_mantenieminto->CurrentAction == "gridadd") // Grid add
			$v_gasto_mantenieminto->RowType = EW_ROWTYPE_ADD; // Render add
		if ($v_gasto_mantenieminto->CurrentAction == "gridadd" && $v_gasto_mantenieminto->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$v_gasto_mantenieminto_grid->RestoreCurrentRowFormValues($v_gasto_mantenieminto_grid->RowIndex); // Restore form values
		if ($v_gasto_mantenieminto->CurrentAction == "gridedit") { // Grid edit
			if ($v_gasto_mantenieminto->EventCancelled) {
				$v_gasto_mantenieminto_grid->RestoreCurrentRowFormValues($v_gasto_mantenieminto_grid->RowIndex); // Restore form values
			}
			if ($v_gasto_mantenieminto_grid->RowAction == "insert")
				$v_gasto_mantenieminto->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$v_gasto_mantenieminto->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($v_gasto_mantenieminto->CurrentAction == "gridedit" && ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_EDIT || $v_gasto_mantenieminto->RowType == EW_ROWTYPE_ADD) && $v_gasto_mantenieminto->EventCancelled) // Update failed
			$v_gasto_mantenieminto_grid->RestoreCurrentRowFormValues($v_gasto_mantenieminto_grid->RowIndex); // Restore form values
		if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_EDIT) // Edit row
			$v_gasto_mantenieminto_grid->EditRowCnt++;
		if ($v_gasto_mantenieminto->CurrentAction == "F") // Confirm row
			$v_gasto_mantenieminto_grid->RestoreCurrentRowFormValues($v_gasto_mantenieminto_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$v_gasto_mantenieminto->RowAttrs = array_merge($v_gasto_mantenieminto->RowAttrs, array('data-rowindex'=>$v_gasto_mantenieminto_grid->RowCnt, 'id'=>'r' . $v_gasto_mantenieminto_grid->RowCnt . '_v_gasto_mantenieminto', 'data-rowtype'=>$v_gasto_mantenieminto->RowType));

		// Render row
		$v_gasto_mantenieminto_grid->RenderRow();

		// Render list options
		$v_gasto_mantenieminto_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($v_gasto_mantenieminto_grid->RowAction <> "delete" && $v_gasto_mantenieminto_grid->RowAction <> "insertdelete" && !($v_gasto_mantenieminto_grid->RowAction == "insert" && $v_gasto_mantenieminto->CurrentAction == "F" && $v_gasto_mantenieminto_grid->EmptyRow())) {
?>
	<tr<?php echo $v_gasto_mantenieminto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_gasto_mantenieminto_grid->ListOptions->Render("body", "left", $v_gasto_mantenieminto_grid->RowCnt);
?>
	<?php if ($v_gasto_mantenieminto->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $v_gasto_mantenieminto->codigo->CellAttributes() ?>>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_codigo" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->codigo->OldValue) ?>">
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_codigo" class="form-group v_gasto_mantenieminto_codigo">
<span<?php echo $v_gasto_mantenieminto->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->codigo->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->codigo->CurrentValue) ?>">
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gasto_mantenieminto->codigo->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->codigo->ListViewValue() ?></span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->codigo->FormValue) ?>">
<input type="hidden" data-field="x_codigo" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->codigo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $v_gasto_mantenieminto_grid->PageObjName . "_row_" . $v_gasto_mantenieminto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $v_gasto_mantenieminto->fecha->CellAttributes() ?>>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_fecha" class="form-group v_gasto_mantenieminto_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->fecha->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->fecha->EditValue ?>"<?php echo $v_gasto_mantenieminto->fecha->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->fecha->OldValue) ?>">
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_fecha" class="form-group v_gasto_mantenieminto_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->fecha->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->fecha->EditValue ?>"<?php echo $v_gasto_mantenieminto->fecha->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gasto_mantenieminto->fecha->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->detalles->Visible) { // detalles ?>
		<td data-name="detalles"<?php echo $v_gasto_mantenieminto->detalles->CellAttributes() ?>>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_detalles" class="form-group v_gasto_mantenieminto_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->detalles->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->detalles->EditValue ?>"<?php echo $v_gasto_mantenieminto->detalles->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_detalles" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->detalles->OldValue) ?>">
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_detalles" class="form-group v_gasto_mantenieminto_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->detalles->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->detalles->EditValue ?>"<?php echo $v_gasto_mantenieminto->detalles->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gasto_mantenieminto->detalles->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->detalles->ListViewValue() ?></span>
<input type="hidden" data-field="x_detalles" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->detalles->FormValue) ?>">
<input type="hidden" data-field="x_detalles" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->detalles->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->iva->Visible) { // iva ?>
		<td data-name="iva"<?php echo $v_gasto_mantenieminto->iva->CellAttributes() ?>>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_iva" class="form-group v_gasto_mantenieminto_iva">
<input type="text" data-field="x_iva" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->iva->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->iva->EditValue ?>"<?php echo $v_gasto_mantenieminto->iva->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_iva" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->iva->OldValue) ?>">
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_iva" class="form-group v_gasto_mantenieminto_iva">
<input type="text" data-field="x_iva" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->iva->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->iva->EditValue ?>"<?php echo $v_gasto_mantenieminto->iva->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gasto_mantenieminto->iva->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->iva->ListViewValue() ?></span>
<input type="hidden" data-field="x_iva" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->iva->FormValue) ?>">
<input type="hidden" data-field="x_iva" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->iva->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->Importe->Visible) { // Importe ?>
		<td data-name="Importe"<?php echo $v_gasto_mantenieminto->Importe->CellAttributes() ?>>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_Importe" class="form-group v_gasto_mantenieminto_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->Importe->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->Importe->EditValue ?>"<?php echo $v_gasto_mantenieminto->Importe->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Importe" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->Importe->OldValue) ?>">
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_Importe" class="form-group v_gasto_mantenieminto_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->Importe->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->Importe->EditValue ?>"<?php echo $v_gasto_mantenieminto->Importe->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gasto_mantenieminto->Importe->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->Importe->ListViewValue() ?></span>
<input type="hidden" data-field="x_Importe" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->Importe->FormValue) ?>">
<input type="hidden" data-field="x_Importe" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->Importe->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td data-name="id_tipo_gasto"<?php echo $v_gasto_mantenieminto->id_tipo_gasto->CellAttributes() ?>>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_id_tipo_gasto" class="form-group v_gasto_mantenieminto_id_tipo_gasto">
<input type="text" data-field="x_id_tipo_gasto" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_tipo_gasto->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->id_tipo_gasto->EditValue ?>"<?php echo $v_gasto_mantenieminto->id_tipo_gasto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_tipo_gasto->OldValue) ?>">
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_id_tipo_gasto" class="form-group v_gasto_mantenieminto_id_tipo_gasto">
<input type="text" data-field="x_id_tipo_gasto" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_tipo_gasto->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->id_tipo_gasto->EditValue ?>"<?php echo $v_gasto_mantenieminto->id_tipo_gasto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gasto_mantenieminto->id_tipo_gasto->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->id_tipo_gasto->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_tipo_gasto->FormValue) ?>">
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_tipo_gasto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->id_hoja_mantenimiento->Visible) { // id_hoja_mantenimiento ?>
		<td data-name="id_hoja_mantenimiento"<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->CellAttributes() ?>>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($v_gasto_mantenieminto->id_hoja_mantenimiento->getSessionValue() <> "") { ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_id_hoja_mantenimiento" class="form-group v_gasto_mantenieminto_id_hoja_mantenimiento">
<span<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_id_hoja_mantenimiento" class="form-group v_gasto_mantenieminto_id_hoja_mantenimiento">
<input type="text" data-field="x_id_hoja_mantenimiento" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->EditValue ?>"<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-field="x_id_hoja_mantenimiento" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->OldValue) ?>">
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($v_gasto_mantenieminto->id_hoja_mantenimiento->getSessionValue() <> "") { ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_id_hoja_mantenimiento" class="form-group v_gasto_mantenieminto_id_hoja_mantenimiento">
<span<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $v_gasto_mantenieminto_grid->RowCnt ?>_v_gasto_mantenieminto_id_hoja_mantenimiento" class="form-group v_gasto_mantenieminto_id_hoja_mantenimiento">
<input type="text" data-field="x_id_hoja_mantenimiento" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->EditValue ?>"<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ViewAttributes() ?>>
<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_hoja_mantenimiento" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->FormValue) ?>">
<input type="hidden" data-field="x_id_hoja_mantenimiento" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_gasto_mantenieminto_grid->ListOptions->Render("body", "right", $v_gasto_mantenieminto_grid->RowCnt);
?>
	</tr>
<?php if ($v_gasto_mantenieminto->RowType == EW_ROWTYPE_ADD || $v_gasto_mantenieminto->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fv_gasto_manteniemintogrid.UpdateOpts(<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($v_gasto_mantenieminto->CurrentAction <> "gridadd" || $v_gasto_mantenieminto->CurrentMode == "copy")
		if (!$v_gasto_mantenieminto_grid->Recordset->EOF) $v_gasto_mantenieminto_grid->Recordset->MoveNext();
}
?>
<?php
	if ($v_gasto_mantenieminto->CurrentMode == "add" || $v_gasto_mantenieminto->CurrentMode == "copy" || $v_gasto_mantenieminto->CurrentMode == "edit") {
		$v_gasto_mantenieminto_grid->RowIndex = '$rowindex$';
		$v_gasto_mantenieminto_grid->LoadDefaultValues();

		// Set row properties
		$v_gasto_mantenieminto->ResetAttrs();
		$v_gasto_mantenieminto->RowAttrs = array_merge($v_gasto_mantenieminto->RowAttrs, array('data-rowindex'=>$v_gasto_mantenieminto_grid->RowIndex, 'id'=>'r0_v_gasto_mantenieminto', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($v_gasto_mantenieminto->RowAttrs["class"], "ewTemplate");
		$v_gasto_mantenieminto->RowType = EW_ROWTYPE_ADD;

		// Render row
		$v_gasto_mantenieminto_grid->RenderRow();

		// Render list options
		$v_gasto_mantenieminto_grid->RenderListOptions();
		$v_gasto_mantenieminto_grid->StartRowCnt = 0;
?>
	<tr<?php echo $v_gasto_mantenieminto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$v_gasto_mantenieminto_grid->ListOptions->Render("body", "left", $v_gasto_mantenieminto_grid->RowIndex);
?>
	<?php if ($v_gasto_mantenieminto->codigo->Visible) { // codigo ?>
		<td>
<?php if ($v_gasto_mantenieminto->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_codigo" class="form-group v_gasto_mantenieminto_codigo">
<span<?php echo $v_gasto_mantenieminto->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->codigo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->codigo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codigo" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->codigo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->fecha->Visible) { // fecha ?>
		<td>
<?php if ($v_gasto_mantenieminto->CurrentAction <> "F") { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_fecha" class="form-group v_gasto_mantenieminto_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->fecha->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->fecha->EditValue ?>"<?php echo $v_gasto_mantenieminto->fecha->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_fecha" class="form-group v_gasto_mantenieminto_fecha">
<span<?php echo $v_gasto_mantenieminto->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->detalles->Visible) { // detalles ?>
		<td>
<?php if ($v_gasto_mantenieminto->CurrentAction <> "F") { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_detalles" class="form-group v_gasto_mantenieminto_detalles">
<input type="text" data-field="x_detalles" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->detalles->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->detalles->EditValue ?>"<?php echo $v_gasto_mantenieminto->detalles->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_detalles" class="form-group v_gasto_mantenieminto_detalles">
<span<?php echo $v_gasto_mantenieminto->detalles->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->detalles->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_detalles" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->detalles->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_detalles" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_detalles" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->detalles->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->iva->Visible) { // iva ?>
		<td>
<?php if ($v_gasto_mantenieminto->CurrentAction <> "F") { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_iva" class="form-group v_gasto_mantenieminto_iva">
<input type="text" data-field="x_iva" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->iva->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->iva->EditValue ?>"<?php echo $v_gasto_mantenieminto->iva->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_iva" class="form-group v_gasto_mantenieminto_iva">
<span<?php echo $v_gasto_mantenieminto->iva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->iva->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iva" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->iva->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iva" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->iva->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->Importe->Visible) { // Importe ?>
		<td>
<?php if ($v_gasto_mantenieminto->CurrentAction <> "F") { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_Importe" class="form-group v_gasto_mantenieminto_Importe">
<input type="text" data-field="x_Importe" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->Importe->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->Importe->EditValue ?>"<?php echo $v_gasto_mantenieminto->Importe->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_Importe" class="form-group v_gasto_mantenieminto_Importe">
<span<?php echo $v_gasto_mantenieminto->Importe->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->Importe->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Importe" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->Importe->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_Importe" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_Importe" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->Importe->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->id_tipo_gasto->Visible) { // id_tipo_gasto ?>
		<td>
<?php if ($v_gasto_mantenieminto->CurrentAction <> "F") { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_id_tipo_gasto" class="form-group v_gasto_mantenieminto_id_tipo_gasto">
<input type="text" data-field="x_id_tipo_gasto" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_tipo_gasto->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->id_tipo_gasto->EditValue ?>"<?php echo $v_gasto_mantenieminto->id_tipo_gasto->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_id_tipo_gasto" class="form-group v_gasto_mantenieminto_id_tipo_gasto">
<span<?php echo $v_gasto_mantenieminto->id_tipo_gasto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->id_tipo_gasto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_tipo_gasto" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_tipo_gasto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_tipo_gasto" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_tipo_gasto" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_tipo_gasto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($v_gasto_mantenieminto->id_hoja_mantenimiento->Visible) { // id_hoja_mantenimiento ?>
		<td>
<?php if ($v_gasto_mantenieminto->CurrentAction <> "F") { ?>
<?php if ($v_gasto_mantenieminto->id_hoja_mantenimiento->getSessionValue() <> "") { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_id_hoja_mantenimiento" class="form-group v_gasto_mantenieminto_id_hoja_mantenimiento">
<span<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_id_hoja_mantenimiento" class="form-group v_gasto_mantenieminto_id_hoja_mantenimiento">
<input type="text" data-field="x_id_hoja_mantenimiento" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" size="30" placeholder="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->PlaceHolder) ?>" value="<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->EditValue ?>"<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_v_gasto_mantenieminto_id_hoja_mantenimiento" class="form-group v_gasto_mantenieminto_id_hoja_mantenimiento">
<span<?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $v_gasto_mantenieminto->id_hoja_mantenimiento->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_hoja_mantenimiento" name="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" id="x<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_hoja_mantenimiento" name="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" id="o<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>_id_hoja_mantenimiento" value="<?php echo ew_HtmlEncode($v_gasto_mantenieminto->id_hoja_mantenimiento->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$v_gasto_mantenieminto_grid->ListOptions->Render("body", "right", $v_gasto_mantenieminto_grid->RowCnt);
?>
<script type="text/javascript">
fv_gasto_manteniemintogrid.UpdateOpts(<?php echo $v_gasto_mantenieminto_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($v_gasto_mantenieminto->CurrentMode == "add" || $v_gasto_mantenieminto->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $v_gasto_mantenieminto_grid->FormKeyCountName ?>" id="<?php echo $v_gasto_mantenieminto_grid->FormKeyCountName ?>" value="<?php echo $v_gasto_mantenieminto_grid->KeyCount ?>">
<?php echo $v_gasto_mantenieminto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($v_gasto_mantenieminto->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $v_gasto_mantenieminto_grid->FormKeyCountName ?>" id="<?php echo $v_gasto_mantenieminto_grid->FormKeyCountName ?>" value="<?php echo $v_gasto_mantenieminto_grid->KeyCount ?>">
<?php echo $v_gasto_mantenieminto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($v_gasto_mantenieminto->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fv_gasto_manteniemintogrid">
</div>
<?php

// Close recordset
if ($v_gasto_mantenieminto_grid->Recordset)
	$v_gasto_mantenieminto_grid->Recordset->Close();
?>
<?php if ($v_gasto_mantenieminto_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($v_gasto_mantenieminto_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($v_gasto_mantenieminto_grid->TotalRecs == 0 && $v_gasto_mantenieminto->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($v_gasto_mantenieminto_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($v_gasto_mantenieminto->Export == "") { ?>
<script type="text/javascript">
fv_gasto_manteniemintogrid.Init();
</script>
<?php } ?>
<?php
$v_gasto_mantenieminto_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$v_gasto_mantenieminto_grid->Page_Terminate();
?>
