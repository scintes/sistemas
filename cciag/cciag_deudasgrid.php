<?php include_once $EW_RELATIVE_PATH . "cciag_sociosinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($deudas_grid)) $deudas_grid = new cdeudas_grid();

// Page init
$deudas_grid->Page_Init();

// Page main
$deudas_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deudas_grid->Page_Render();
?>
<?php if ($deudas->Export == "") { ?>
<script type="text/javascript">

// Page object
var deudas_grid = new ew_Page("deudas_grid");
deudas_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = deudas_grid.PageID; // For backward compatibility

// Form object
var fdeudasgrid = new ew_Form("fdeudasgrid");
fdeudasgrid.FormKeyCountName = '<?php echo $deudas_grid->FormKeyCountName ?>';

// Validate form
fdeudasgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_mes");
			if (elm && !ew_CheckRange(elm.value, 1, 12))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mes->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_anio");
			if (elm && !ew_CheckRange(elm.value, 2012, 2100))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->anio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_monto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->monto->FldErrMsg()) ?>");

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
fdeudasgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "mes", false)) return false;
	if (ew_ValueChanged(fobj, infix, "anio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha", false)) return false;
	if (ew_ValueChanged(fobj, infix, "monto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_socio", false)) return false;
	return true;
}

// Form_CustomValidate event
fdeudasgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdeudasgrid.ValidateRequired = true;
<?php } else { ?>
fdeudasgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdeudasgrid.Lists["x_id_socio"] = {"LinkField":"x_socio_nro","Ajax":true,"AutoFill":false,"DisplayFields":["x_propietario","x_comercio","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($deudas->CurrentAction == "gridadd") {
	if ($deudas->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$deudas_grid->TotalRecs = $deudas->SelectRecordCount();
			$deudas_grid->Recordset = $deudas_grid->LoadRecordset($deudas_grid->StartRec-1, $deudas_grid->DisplayRecs);
		} else {
			if ($deudas_grid->Recordset = $deudas_grid->LoadRecordset())
				$deudas_grid->TotalRecs = $deudas_grid->Recordset->RecordCount();
		}
		$deudas_grid->StartRec = 1;
		$deudas_grid->DisplayRecs = $deudas_grid->TotalRecs;
	} else {
		$deudas->CurrentFilter = "0=1";
		$deudas_grid->StartRec = 1;
		$deudas_grid->DisplayRecs = $deudas->GridAddRowCount;
	}
	$deudas_grid->TotalRecs = $deudas_grid->DisplayRecs;
	$deudas_grid->StopRec = $deudas_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$deudas_grid->TotalRecs = $deudas->SelectRecordCount();
	} else {
		if ($deudas_grid->Recordset = $deudas_grid->LoadRecordset())
			$deudas_grid->TotalRecs = $deudas_grid->Recordset->RecordCount();
	}
	$deudas_grid->StartRec = 1;
	$deudas_grid->DisplayRecs = $deudas_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$deudas_grid->Recordset = $deudas_grid->LoadRecordset($deudas_grid->StartRec-1, $deudas_grid->DisplayRecs);

	// Set no record found message
	if ($deudas->CurrentAction == "" && $deudas_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$deudas_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($deudas_grid->SearchWhere == "0=101")
			$deudas_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$deudas_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$deudas_grid->RenderOtherOptions();
?>
<?php $deudas_grid->ShowPageHeader(); ?>
<?php
$deudas_grid->ShowMessage();
?>
<?php if ($deudas_grid->TotalRecs > 0 || $deudas->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fdeudasgrid" class="ewForm form-inline">
<?php if ($deudas_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($deudas_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_deudas" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_deudasgrid" class="table ewTable">
<?php echo $deudas->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$deudas_grid->RenderListOptions();

// Render list options (header, left)
$deudas_grid->ListOptions->Render("header", "left");
?>
<?php if ($deudas->id->Visible) { // id ?>
	<?php if ($deudas->SortUrl($deudas->id) == "") { ?>
		<th data-name="id"><div id="elh_deudas_id" class="deudas_id"><div class="ewTableHeaderCaption"><?php echo $deudas->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div><div id="elh_deudas_id" class="deudas_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($deudas->mes->Visible) { // mes ?>
	<?php if ($deudas->SortUrl($deudas->mes) == "") { ?>
		<th data-name="mes"><div id="elh_deudas_mes" class="deudas_mes"><div class="ewTableHeaderCaption"><?php echo $deudas->mes->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mes"><div><div id="elh_deudas_mes" class="deudas_mes">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mes->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mes->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mes->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($deudas->anio->Visible) { // anio ?>
	<?php if ($deudas->SortUrl($deudas->anio) == "") { ?>
		<th data-name="anio"><div id="elh_deudas_anio" class="deudas_anio"><div class="ewTableHeaderCaption"><?php echo $deudas->anio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="anio"><div><div id="elh_deudas_anio" class="deudas_anio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->anio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->anio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->anio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($deudas->fecha->Visible) { // fecha ?>
	<?php if ($deudas->SortUrl($deudas->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_deudas_fecha" class="deudas_fecha"><div class="ewTableHeaderCaption"><?php echo $deudas->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_deudas_fecha" class="deudas_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($deudas->monto->Visible) { // monto ?>
	<?php if ($deudas->SortUrl($deudas->monto) == "") { ?>
		<th data-name="monto"><div id="elh_deudas_monto" class="deudas_monto"><div class="ewTableHeaderCaption"><?php echo $deudas->monto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="monto"><div><div id="elh_deudas_monto" class="deudas_monto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->monto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->monto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->monto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($deudas->id_socio->Visible) { // id_socio ?>
	<?php if ($deudas->SortUrl($deudas->id_socio) == "") { ?>
		<th data-name="id_socio"><div id="elh_deudas_id_socio" class="deudas_id_socio"><div class="ewTableHeaderCaption"><?php echo $deudas->id_socio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_socio"><div><div id="elh_deudas_id_socio" class="deudas_id_socio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->id_socio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->id_socio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->id_socio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$deudas_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$deudas_grid->StartRec = 1;
$deudas_grid->StopRec = $deudas_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($deudas_grid->FormKeyCountName) && ($deudas->CurrentAction == "gridadd" || $deudas->CurrentAction == "gridedit" || $deudas->CurrentAction == "F")) {
		$deudas_grid->KeyCount = $objForm->GetValue($deudas_grid->FormKeyCountName);
		$deudas_grid->StopRec = $deudas_grid->StartRec + $deudas_grid->KeyCount - 1;
	}
}
$deudas_grid->RecCnt = $deudas_grid->StartRec - 1;
if ($deudas_grid->Recordset && !$deudas_grid->Recordset->EOF) {
	$deudas_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $deudas_grid->StartRec > 1)
		$deudas_grid->Recordset->Move($deudas_grid->StartRec - 1);
} elseif (!$deudas->AllowAddDeleteRow && $deudas_grid->StopRec == 0) {
	$deudas_grid->StopRec = $deudas->GridAddRowCount;
}

// Initialize aggregate
$deudas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$deudas->ResetAttrs();
$deudas_grid->RenderRow();
if ($deudas->CurrentAction == "gridadd")
	$deudas_grid->RowIndex = 0;
if ($deudas->CurrentAction == "gridedit")
	$deudas_grid->RowIndex = 0;
while ($deudas_grid->RecCnt < $deudas_grid->StopRec) {
	$deudas_grid->RecCnt++;
	if (intval($deudas_grid->RecCnt) >= intval($deudas_grid->StartRec)) {
		$deudas_grid->RowCnt++;
		if ($deudas->CurrentAction == "gridadd" || $deudas->CurrentAction == "gridedit" || $deudas->CurrentAction == "F") {
			$deudas_grid->RowIndex++;
			$objForm->Index = $deudas_grid->RowIndex;
			if ($objForm->HasValue($deudas_grid->FormActionName))
				$deudas_grid->RowAction = strval($objForm->GetValue($deudas_grid->FormActionName));
			elseif ($deudas->CurrentAction == "gridadd")
				$deudas_grid->RowAction = "insert";
			else
				$deudas_grid->RowAction = "";
		}

		// Set up key count
		$deudas_grid->KeyCount = $deudas_grid->RowIndex;

		// Init row class and style
		$deudas->ResetAttrs();
		$deudas->CssClass = "";
		if ($deudas->CurrentAction == "gridadd") {
			if ($deudas->CurrentMode == "copy") {
				$deudas_grid->LoadRowValues($deudas_grid->Recordset); // Load row values
				$deudas_grid->SetRecordKey($deudas_grid->RowOldKey, $deudas_grid->Recordset); // Set old record key
			} else {
				$deudas_grid->LoadDefaultValues(); // Load default values
				$deudas_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$deudas_grid->LoadRowValues($deudas_grid->Recordset); // Load row values
		}
		$deudas->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($deudas->CurrentAction == "gridadd") // Grid add
			$deudas->RowType = EW_ROWTYPE_ADD; // Render add
		if ($deudas->CurrentAction == "gridadd" && $deudas->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$deudas_grid->RestoreCurrentRowFormValues($deudas_grid->RowIndex); // Restore form values
		if ($deudas->CurrentAction == "gridedit") { // Grid edit
			if ($deudas->EventCancelled) {
				$deudas_grid->RestoreCurrentRowFormValues($deudas_grid->RowIndex); // Restore form values
			}
			if ($deudas_grid->RowAction == "insert")
				$deudas->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$deudas->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($deudas->CurrentAction == "gridedit" && ($deudas->RowType == EW_ROWTYPE_EDIT || $deudas->RowType == EW_ROWTYPE_ADD) && $deudas->EventCancelled) // Update failed
			$deudas_grid->RestoreCurrentRowFormValues($deudas_grid->RowIndex); // Restore form values
		if ($deudas->RowType == EW_ROWTYPE_EDIT) // Edit row
			$deudas_grid->EditRowCnt++;
		if ($deudas->CurrentAction == "F") // Confirm row
			$deudas_grid->RestoreCurrentRowFormValues($deudas_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$deudas->RowAttrs = array_merge($deudas->RowAttrs, array('data-rowindex'=>$deudas_grid->RowCnt, 'id'=>'r' . $deudas_grid->RowCnt . '_deudas', 'data-rowtype'=>$deudas->RowType));

		// Render row
		$deudas_grid->RenderRow();

		// Render list options
		$deudas_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($deudas_grid->RowAction <> "delete" && $deudas_grid->RowAction <> "insertdelete" && !($deudas_grid->RowAction == "insert" && $deudas->CurrentAction == "F" && $deudas_grid->EmptyRow())) {
?>
	<tr<?php echo $deudas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$deudas_grid->ListOptions->Render("body", "left", $deudas_grid->RowCnt);
?>
	<?php if ($deudas->id->Visible) { // id ?>
		<td data-name="id"<?php echo $deudas->id->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="o<?php echo $deudas_grid->RowIndex ?>_id" id="o<?php echo $deudas_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($deudas->id->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id" class="form-group deudas_id">
<span<?php echo $deudas->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_id" name="x<?php echo $deudas_grid->RowIndex ?>_id" id="x<?php echo $deudas_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($deudas->id->CurrentValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $deudas->id->ViewAttributes() ?>>
<?php echo $deudas->id->ListViewValue() ?></span>
<input type="hidden" data-field="x_id" name="x<?php echo $deudas_grid->RowIndex ?>_id" id="x<?php echo $deudas_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($deudas->id->FormValue) ?>">
<input type="hidden" data-field="x_id" name="o<?php echo $deudas_grid->RowIndex ?>_id" id="o<?php echo $deudas_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($deudas->id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $deudas_grid->PageObjName . "_row_" . $deudas_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($deudas->mes->Visible) { // mes ?>
		<td data-name="mes"<?php echo $deudas->mes->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mes" class="form-group deudas_mes">
<input type="text" data-field="x_mes" name="x<?php echo $deudas_grid->RowIndex ?>_mes" id="x<?php echo $deudas_grid->RowIndex ?>_mes" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mes->PlaceHolder) ?>" value="<?php echo $deudas->mes->EditValue ?>"<?php echo $deudas->mes->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_mes" name="o<?php echo $deudas_grid->RowIndex ?>_mes" id="o<?php echo $deudas_grid->RowIndex ?>_mes" value="<?php echo ew_HtmlEncode($deudas->mes->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mes" class="form-group deudas_mes">
<input type="text" data-field="x_mes" name="x<?php echo $deudas_grid->RowIndex ?>_mes" id="x<?php echo $deudas_grid->RowIndex ?>_mes" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mes->PlaceHolder) ?>" value="<?php echo $deudas->mes->EditValue ?>"<?php echo $deudas->mes->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $deudas->mes->ViewAttributes() ?>>
<?php echo $deudas->mes->ListViewValue() ?></span>
<input type="hidden" data-field="x_mes" name="x<?php echo $deudas_grid->RowIndex ?>_mes" id="x<?php echo $deudas_grid->RowIndex ?>_mes" value="<?php echo ew_HtmlEncode($deudas->mes->FormValue) ?>">
<input type="hidden" data-field="x_mes" name="o<?php echo $deudas_grid->RowIndex ?>_mes" id="o<?php echo $deudas_grid->RowIndex ?>_mes" value="<?php echo ew_HtmlEncode($deudas->mes->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->anio->Visible) { // anio ?>
		<td data-name="anio"<?php echo $deudas->anio->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_anio" class="form-group deudas_anio">
<input type="text" data-field="x_anio" name="x<?php echo $deudas_grid->RowIndex ?>_anio" id="x<?php echo $deudas_grid->RowIndex ?>_anio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->anio->PlaceHolder) ?>" value="<?php echo $deudas->anio->EditValue ?>"<?php echo $deudas->anio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_anio" name="o<?php echo $deudas_grid->RowIndex ?>_anio" id="o<?php echo $deudas_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($deudas->anio->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_anio" class="form-group deudas_anio">
<input type="text" data-field="x_anio" name="x<?php echo $deudas_grid->RowIndex ?>_anio" id="x<?php echo $deudas_grid->RowIndex ?>_anio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->anio->PlaceHolder) ?>" value="<?php echo $deudas->anio->EditValue ?>"<?php echo $deudas->anio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $deudas->anio->ViewAttributes() ?>>
<?php echo $deudas->anio->ListViewValue() ?></span>
<input type="hidden" data-field="x_anio" name="x<?php echo $deudas_grid->RowIndex ?>_anio" id="x<?php echo $deudas_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($deudas->anio->FormValue) ?>">
<input type="hidden" data-field="x_anio" name="o<?php echo $deudas_grid->RowIndex ?>_anio" id="o<?php echo $deudas_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($deudas->anio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $deudas->fecha->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_fecha" class="form-group deudas_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $deudas_grid->RowIndex ?>_fecha" id="x<?php echo $deudas_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($deudas->fecha->PlaceHolder) ?>" value="<?php echo $deudas->fecha->EditValue ?>"<?php echo $deudas->fecha->EditAttributes() ?>>
<?php if (!$deudas->fecha->ReadOnly && !$deudas->fecha->Disabled && @$deudas->fecha->EditAttrs["readonly"] == "" && @$deudas->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fdeudasgrid", "x<?php echo $deudas_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $deudas_grid->RowIndex ?>_fecha" id="o<?php echo $deudas_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($deudas->fecha->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_fecha" class="form-group deudas_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $deudas_grid->RowIndex ?>_fecha" id="x<?php echo $deudas_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($deudas->fecha->PlaceHolder) ?>" value="<?php echo $deudas->fecha->EditValue ?>"<?php echo $deudas->fecha->EditAttributes() ?>>
<?php if (!$deudas->fecha->ReadOnly && !$deudas->fecha->Disabled && @$deudas->fecha->EditAttrs["readonly"] == "" && @$deudas->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fdeudasgrid", "x<?php echo $deudas_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $deudas->fecha->ViewAttributes() ?>>
<?php echo $deudas->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $deudas_grid->RowIndex ?>_fecha" id="x<?php echo $deudas_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($deudas->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $deudas_grid->RowIndex ?>_fecha" id="o<?php echo $deudas_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($deudas->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->monto->Visible) { // monto ?>
		<td data-name="monto"<?php echo $deudas->monto->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_monto" class="form-group deudas_monto">
<input type="text" data-field="x_monto" name="x<?php echo $deudas_grid->RowIndex ?>_monto" id="x<?php echo $deudas_grid->RowIndex ?>_monto" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->monto->PlaceHolder) ?>" value="<?php echo $deudas->monto->EditValue ?>"<?php echo $deudas->monto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_monto" name="o<?php echo $deudas_grid->RowIndex ?>_monto" id="o<?php echo $deudas_grid->RowIndex ?>_monto" value="<?php echo ew_HtmlEncode($deudas->monto->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_monto" class="form-group deudas_monto">
<input type="text" data-field="x_monto" name="x<?php echo $deudas_grid->RowIndex ?>_monto" id="x<?php echo $deudas_grid->RowIndex ?>_monto" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->monto->PlaceHolder) ?>" value="<?php echo $deudas->monto->EditValue ?>"<?php echo $deudas->monto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $deudas->monto->ViewAttributes() ?>>
<?php echo $deudas->monto->ListViewValue() ?></span>
<input type="hidden" data-field="x_monto" name="x<?php echo $deudas_grid->RowIndex ?>_monto" id="x<?php echo $deudas_grid->RowIndex ?>_monto" value="<?php echo ew_HtmlEncode($deudas->monto->FormValue) ?>">
<input type="hidden" data-field="x_monto" name="o<?php echo $deudas_grid->RowIndex ?>_monto" id="o<?php echo $deudas_grid->RowIndex ?>_monto" value="<?php echo ew_HtmlEncode($deudas->monto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->id_socio->Visible) { // id_socio ?>
		<td data-name="id_socio"<?php echo $deudas->id_socio->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($deudas->id_socio->getSessionValue() <> "") { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_socio" class="form-group deudas_id_socio">
<span<?php echo $deudas->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $deudas_grid->RowIndex ?>_id_socio" name="x<?php echo $deudas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($deudas->id_socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_socio" class="form-group deudas_id_socio">
<select data-field="x_id_socio" id="x<?php echo $deudas_grid->RowIndex ?>_id_socio" name="x<?php echo $deudas_grid->RowIndex ?>_id_socio"<?php echo $deudas->id_socio->EditAttributes() ?>>
<?php
if (is_array($deudas->id_socio->EditValue)) {
	$arwrk = $deudas->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($deudas->id_socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$deudas->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $deudas->id_socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activo`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }
 if (!$GLOBALS["deudas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $deudas->Lookup_Selecting($deudas->id_socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `propietario` DESC";
?>
<input type="hidden" name="s_x<?php echo $deudas_grid->RowIndex ?>_id_socio" id="s_x<?php echo $deudas_grid->RowIndex ?>_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_socio" name="o<?php echo $deudas_grid->RowIndex ?>_id_socio" id="o<?php echo $deudas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($deudas->id_socio->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($deudas->id_socio->getSessionValue() <> "") { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_socio" class="form-group deudas_id_socio">
<span<?php echo $deudas->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $deudas_grid->RowIndex ?>_id_socio" name="x<?php echo $deudas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($deudas->id_socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_socio" class="form-group deudas_id_socio">
<select data-field="x_id_socio" id="x<?php echo $deudas_grid->RowIndex ?>_id_socio" name="x<?php echo $deudas_grid->RowIndex ?>_id_socio"<?php echo $deudas->id_socio->EditAttributes() ?>>
<?php
if (is_array($deudas->id_socio->EditValue)) {
	$arwrk = $deudas->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($deudas->id_socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$deudas->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $deudas->id_socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activo`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }
 if (!$GLOBALS["deudas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $deudas->Lookup_Selecting($deudas->id_socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `propietario` DESC";
?>
<input type="hidden" name="s_x<?php echo $deudas_grid->RowIndex ?>_id_socio" id="s_x<?php echo $deudas_grid->RowIndex ?>_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $deudas->id_socio->ViewAttributes() ?>>
<?php echo $deudas->id_socio->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_socio" name="x<?php echo $deudas_grid->RowIndex ?>_id_socio" id="x<?php echo $deudas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($deudas->id_socio->FormValue) ?>">
<input type="hidden" data-field="x_id_socio" name="o<?php echo $deudas_grid->RowIndex ?>_id_socio" id="o<?php echo $deudas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($deudas->id_socio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$deudas_grid->ListOptions->Render("body", "right", $deudas_grid->RowCnt);
?>
	</tr>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD || $deudas->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdeudasgrid.UpdateOpts(<?php echo $deudas_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($deudas->CurrentAction <> "gridadd" || $deudas->CurrentMode == "copy")
		if (!$deudas_grid->Recordset->EOF) $deudas_grid->Recordset->MoveNext();
}
?>
<?php
	if ($deudas->CurrentMode == "add" || $deudas->CurrentMode == "copy" || $deudas->CurrentMode == "edit") {
		$deudas_grid->RowIndex = '$rowindex$';
		$deudas_grid->LoadDefaultValues();

		// Set row properties
		$deudas->ResetAttrs();
		$deudas->RowAttrs = array_merge($deudas->RowAttrs, array('data-rowindex'=>$deudas_grid->RowIndex, 'id'=>'r0_deudas', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($deudas->RowAttrs["class"], "ewTemplate");
		$deudas->RowType = EW_ROWTYPE_ADD;

		// Render row
		$deudas_grid->RenderRow();

		// Render list options
		$deudas_grid->RenderListOptions();
		$deudas_grid->StartRowCnt = 0;
?>
	<tr<?php echo $deudas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$deudas_grid->ListOptions->Render("body", "left", $deudas_grid->RowIndex);
?>
	<?php if ($deudas->id->Visible) { // id ?>
		<td>
<?php if ($deudas->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_deudas_id" class="form-group deudas_id">
<span<?php echo $deudas->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id" name="x<?php echo $deudas_grid->RowIndex ?>_id" id="x<?php echo $deudas_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($deudas->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id" name="o<?php echo $deudas_grid->RowIndex ?>_id" id="o<?php echo $deudas_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($deudas->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mes->Visible) { // mes ?>
		<td>
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mes" class="form-group deudas_mes">
<input type="text" data-field="x_mes" name="x<?php echo $deudas_grid->RowIndex ?>_mes" id="x<?php echo $deudas_grid->RowIndex ?>_mes" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mes->PlaceHolder) ?>" value="<?php echo $deudas->mes->EditValue ?>"<?php echo $deudas->mes->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mes" class="form-group deudas_mes">
<span<?php echo $deudas->mes->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mes->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_mes" name="x<?php echo $deudas_grid->RowIndex ?>_mes" id="x<?php echo $deudas_grid->RowIndex ?>_mes" value="<?php echo ew_HtmlEncode($deudas->mes->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_mes" name="o<?php echo $deudas_grid->RowIndex ?>_mes" id="o<?php echo $deudas_grid->RowIndex ?>_mes" value="<?php echo ew_HtmlEncode($deudas->mes->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->anio->Visible) { // anio ?>
		<td>
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_anio" class="form-group deudas_anio">
<input type="text" data-field="x_anio" name="x<?php echo $deudas_grid->RowIndex ?>_anio" id="x<?php echo $deudas_grid->RowIndex ?>_anio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->anio->PlaceHolder) ?>" value="<?php echo $deudas->anio->EditValue ?>"<?php echo $deudas->anio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_anio" class="form-group deudas_anio">
<span<?php echo $deudas->anio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->anio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_anio" name="x<?php echo $deudas_grid->RowIndex ?>_anio" id="x<?php echo $deudas_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($deudas->anio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_anio" name="o<?php echo $deudas_grid->RowIndex ?>_anio" id="o<?php echo $deudas_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($deudas->anio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->fecha->Visible) { // fecha ?>
		<td>
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_fecha" class="form-group deudas_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $deudas_grid->RowIndex ?>_fecha" id="x<?php echo $deudas_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($deudas->fecha->PlaceHolder) ?>" value="<?php echo $deudas->fecha->EditValue ?>"<?php echo $deudas->fecha->EditAttributes() ?>>
<?php if (!$deudas->fecha->ReadOnly && !$deudas->fecha->Disabled && @$deudas->fecha->EditAttrs["readonly"] == "" && @$deudas->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fdeudasgrid", "x<?php echo $deudas_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_fecha" class="form-group deudas_fecha">
<span<?php echo $deudas->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $deudas_grid->RowIndex ?>_fecha" id="x<?php echo $deudas_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($deudas->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $deudas_grid->RowIndex ?>_fecha" id="o<?php echo $deudas_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($deudas->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->monto->Visible) { // monto ?>
		<td>
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_monto" class="form-group deudas_monto">
<input type="text" data-field="x_monto" name="x<?php echo $deudas_grid->RowIndex ?>_monto" id="x<?php echo $deudas_grid->RowIndex ?>_monto" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->monto->PlaceHolder) ?>" value="<?php echo $deudas->monto->EditValue ?>"<?php echo $deudas->monto->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_monto" class="form-group deudas_monto">
<span<?php echo $deudas->monto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->monto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_monto" name="x<?php echo $deudas_grid->RowIndex ?>_monto" id="x<?php echo $deudas_grid->RowIndex ?>_monto" value="<?php echo ew_HtmlEncode($deudas->monto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_monto" name="o<?php echo $deudas_grid->RowIndex ?>_monto" id="o<?php echo $deudas_grid->RowIndex ?>_monto" value="<?php echo ew_HtmlEncode($deudas->monto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->id_socio->Visible) { // id_socio ?>
		<td>
<?php if ($deudas->CurrentAction <> "F") { ?>
<?php if ($deudas->id_socio->getSessionValue() <> "") { ?>
<span id="el$rowindex$_deudas_id_socio" class="form-group deudas_id_socio">
<span<?php echo $deudas->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $deudas_grid->RowIndex ?>_id_socio" name="x<?php echo $deudas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($deudas->id_socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_deudas_id_socio" class="form-group deudas_id_socio">
<select data-field="x_id_socio" id="x<?php echo $deudas_grid->RowIndex ?>_id_socio" name="x<?php echo $deudas_grid->RowIndex ?>_id_socio"<?php echo $deudas->id_socio->EditAttributes() ?>>
<?php
if (is_array($deudas->id_socio->EditValue)) {
	$arwrk = $deudas->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($deudas->id_socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$deudas->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $deudas->id_socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `socio_nro`, `propietario` AS `DispFld`, `comercio` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activo`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }
 if (!$GLOBALS["deudas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $deudas->Lookup_Selecting($deudas->id_socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `propietario` DESC";
?>
<input type="hidden" name="s_x<?php echo $deudas_grid->RowIndex ?>_id_socio" id="s_x<?php echo $deudas_grid->RowIndex ?>_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_deudas_id_socio" class="form-group deudas_id_socio">
<span<?php echo $deudas->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_socio" name="x<?php echo $deudas_grid->RowIndex ?>_id_socio" id="x<?php echo $deudas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($deudas->id_socio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_socio" name="o<?php echo $deudas_grid->RowIndex ?>_id_socio" id="o<?php echo $deudas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($deudas->id_socio->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$deudas_grid->ListOptions->Render("body", "right", $deudas_grid->RowCnt);
?>
<script type="text/javascript">
fdeudasgrid.UpdateOpts(<?php echo $deudas_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($deudas->CurrentMode == "add" || $deudas->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $deudas_grid->FormKeyCountName ?>" id="<?php echo $deudas_grid->FormKeyCountName ?>" value="<?php echo $deudas_grid->KeyCount ?>">
<?php echo $deudas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($deudas->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $deudas_grid->FormKeyCountName ?>" id="<?php echo $deudas_grid->FormKeyCountName ?>" value="<?php echo $deudas_grid->KeyCount ?>">
<?php echo $deudas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($deudas->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdeudasgrid">
</div>
<?php

// Close recordset
if ($deudas_grid->Recordset)
	$deudas_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($deudas_grid->TotalRecs == 0 && $deudas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($deudas_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($deudas->Export == "") { ?>
<script type="text/javascript">
fdeudasgrid.Init();
</script>
<?php } ?>
<?php
$deudas_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$deudas_grid->Page_Terminate();
?>
