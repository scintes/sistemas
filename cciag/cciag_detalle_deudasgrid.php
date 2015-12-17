<?php include_once $EW_RELATIVE_PATH . "cciag_deudasinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($detalle_deudas_grid)) $detalle_deudas_grid = new cdetalle_deudas_grid();

// Page init
$detalle_deudas_grid->Page_Init();

// Page main
$detalle_deudas_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detalle_deudas_grid->Page_Render();
?>
<?php if ($detalle_deudas->Export == "") { ?>
<script type="text/javascript">

// Page object
var detalle_deudas_grid = new ew_Page("detalle_deudas_grid");
detalle_deudas_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = detalle_deudas_grid.PageID; // For backward compatibility

// Form object
var fdetalle_deudasgrid = new ew_Form("fdetalle_deudasgrid");
fdetalle_deudasgrid.FormKeyCountName = '<?php echo $detalle_deudas_grid->FormKeyCountName ?>';

// Validate form
fdetalle_deudasgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($detalle_deudas->importe->FldErrMsg()) ?>");

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
fdetalle_deudasgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_deuda", false)) return false;
	if (ew_ValueChanged(fobj, infix, "detalle", false)) return false;
	if (ew_ValueChanged(fobj, infix, "importe", false)) return false;
	return true;
}

// Form_CustomValidate event
fdetalle_deudasgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetalle_deudasgrid.ValidateRequired = true;
<?php } else { ?>
fdetalle_deudasgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdetalle_deudasgrid.Lists["x_id_deuda"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_mes","x_anio","x_monto",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($detalle_deudas->CurrentAction == "gridadd") {
	if ($detalle_deudas->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$detalle_deudas_grid->TotalRecs = $detalle_deudas->SelectRecordCount();
			$detalle_deudas_grid->Recordset = $detalle_deudas_grid->LoadRecordset($detalle_deudas_grid->StartRec-1, $detalle_deudas_grid->DisplayRecs);
		} else {
			if ($detalle_deudas_grid->Recordset = $detalle_deudas_grid->LoadRecordset())
				$detalle_deudas_grid->TotalRecs = $detalle_deudas_grid->Recordset->RecordCount();
		}
		$detalle_deudas_grid->StartRec = 1;
		$detalle_deudas_grid->DisplayRecs = $detalle_deudas_grid->TotalRecs;
	} else {
		$detalle_deudas->CurrentFilter = "0=1";
		$detalle_deudas_grid->StartRec = 1;
		$detalle_deudas_grid->DisplayRecs = $detalle_deudas->GridAddRowCount;
	}
	$detalle_deudas_grid->TotalRecs = $detalle_deudas_grid->DisplayRecs;
	$detalle_deudas_grid->StopRec = $detalle_deudas_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$detalle_deudas_grid->TotalRecs = $detalle_deudas->SelectRecordCount();
	} else {
		if ($detalle_deudas_grid->Recordset = $detalle_deudas_grid->LoadRecordset())
			$detalle_deudas_grid->TotalRecs = $detalle_deudas_grid->Recordset->RecordCount();
	}
	$detalle_deudas_grid->StartRec = 1;
	$detalle_deudas_grid->DisplayRecs = $detalle_deudas_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$detalle_deudas_grid->Recordset = $detalle_deudas_grid->LoadRecordset($detalle_deudas_grid->StartRec-1, $detalle_deudas_grid->DisplayRecs);

	// Set no record found message
	if ($detalle_deudas->CurrentAction == "" && $detalle_deudas_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$detalle_deudas_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($detalle_deudas_grid->SearchWhere == "0=101")
			$detalle_deudas_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$detalle_deudas_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$detalle_deudas_grid->RenderOtherOptions();
?>
<?php $detalle_deudas_grid->ShowPageHeader(); ?>
<?php
$detalle_deudas_grid->ShowMessage();
?>
<?php if ($detalle_deudas_grid->TotalRecs > 0 || $detalle_deudas->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fdetalle_deudasgrid" class="ewForm form-inline">
<?php if ($detalle_deudas_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($detalle_deudas_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_detalle_deudas" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_detalle_deudasgrid" class="table ewTable">
<?php echo $detalle_deudas->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$detalle_deudas_grid->RenderListOptions();

// Render list options (header, left)
$detalle_deudas_grid->ListOptions->Render("header", "left");
?>
<?php if ($detalle_deudas->id_deuda->Visible) { // id_deuda ?>
	<?php if ($detalle_deudas->SortUrl($detalle_deudas->id_deuda) == "") { ?>
		<th data-name="id_deuda"><div id="elh_detalle_deudas_id_deuda" class="detalle_deudas_id_deuda"><div class="ewTableHeaderCaption"><?php echo $detalle_deudas->id_deuda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_deuda"><div><div id="elh_detalle_deudas_id_deuda" class="detalle_deudas_id_deuda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalle_deudas->id_deuda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalle_deudas->id_deuda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalle_deudas->id_deuda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detalle_deudas->detalle->Visible) { // detalle ?>
	<?php if ($detalle_deudas->SortUrl($detalle_deudas->detalle) == "") { ?>
		<th data-name="detalle"><div id="elh_detalle_deudas_detalle" class="detalle_deudas_detalle"><div class="ewTableHeaderCaption"><?php echo $detalle_deudas->detalle->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="detalle"><div><div id="elh_detalle_deudas_detalle" class="detalle_deudas_detalle">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalle_deudas->detalle->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalle_deudas->detalle->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalle_deudas->detalle->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detalle_deudas->importe->Visible) { // importe ?>
	<?php if ($detalle_deudas->SortUrl($detalle_deudas->importe) == "") { ?>
		<th data-name="importe"><div id="elh_detalle_deudas_importe" class="detalle_deudas_importe"><div class="ewTableHeaderCaption"><?php echo $detalle_deudas->importe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importe"><div><div id="elh_detalle_deudas_importe" class="detalle_deudas_importe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalle_deudas->importe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalle_deudas->importe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalle_deudas->importe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$detalle_deudas_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$detalle_deudas_grid->StartRec = 1;
$detalle_deudas_grid->StopRec = $detalle_deudas_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($detalle_deudas_grid->FormKeyCountName) && ($detalle_deudas->CurrentAction == "gridadd" || $detalle_deudas->CurrentAction == "gridedit" || $detalle_deudas->CurrentAction == "F")) {
		$detalle_deudas_grid->KeyCount = $objForm->GetValue($detalle_deudas_grid->FormKeyCountName);
		$detalle_deudas_grid->StopRec = $detalle_deudas_grid->StartRec + $detalle_deudas_grid->KeyCount - 1;
	}
}
$detalle_deudas_grid->RecCnt = $detalle_deudas_grid->StartRec - 1;
if ($detalle_deudas_grid->Recordset && !$detalle_deudas_grid->Recordset->EOF) {
	$detalle_deudas_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $detalle_deudas_grid->StartRec > 1)
		$detalle_deudas_grid->Recordset->Move($detalle_deudas_grid->StartRec - 1);
} elseif (!$detalle_deudas->AllowAddDeleteRow && $detalle_deudas_grid->StopRec == 0) {
	$detalle_deudas_grid->StopRec = $detalle_deudas->GridAddRowCount;
}

// Initialize aggregate
$detalle_deudas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$detalle_deudas->ResetAttrs();
$detalle_deudas_grid->RenderRow();
if ($detalle_deudas->CurrentAction == "gridadd")
	$detalle_deudas_grid->RowIndex = 0;
if ($detalle_deudas->CurrentAction == "gridedit")
	$detalle_deudas_grid->RowIndex = 0;
while ($detalle_deudas_grid->RecCnt < $detalle_deudas_grid->StopRec) {
	$detalle_deudas_grid->RecCnt++;
	if (intval($detalle_deudas_grid->RecCnt) >= intval($detalle_deudas_grid->StartRec)) {
		$detalle_deudas_grid->RowCnt++;
		if ($detalle_deudas->CurrentAction == "gridadd" || $detalle_deudas->CurrentAction == "gridedit" || $detalle_deudas->CurrentAction == "F") {
			$detalle_deudas_grid->RowIndex++;
			$objForm->Index = $detalle_deudas_grid->RowIndex;
			if ($objForm->HasValue($detalle_deudas_grid->FormActionName))
				$detalle_deudas_grid->RowAction = strval($objForm->GetValue($detalle_deudas_grid->FormActionName));
			elseif ($detalle_deudas->CurrentAction == "gridadd")
				$detalle_deudas_grid->RowAction = "insert";
			else
				$detalle_deudas_grid->RowAction = "";
		}

		// Set up key count
		$detalle_deudas_grid->KeyCount = $detalle_deudas_grid->RowIndex;

		// Init row class and style
		$detalle_deudas->ResetAttrs();
		$detalle_deudas->CssClass = "";
		if ($detalle_deudas->CurrentAction == "gridadd") {
			if ($detalle_deudas->CurrentMode == "copy") {
				$detalle_deudas_grid->LoadRowValues($detalle_deudas_grid->Recordset); // Load row values
				$detalle_deudas_grid->SetRecordKey($detalle_deudas_grid->RowOldKey, $detalle_deudas_grid->Recordset); // Set old record key
			} else {
				$detalle_deudas_grid->LoadDefaultValues(); // Load default values
				$detalle_deudas_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$detalle_deudas_grid->LoadRowValues($detalle_deudas_grid->Recordset); // Load row values
		}
		$detalle_deudas->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($detalle_deudas->CurrentAction == "gridadd") // Grid add
			$detalle_deudas->RowType = EW_ROWTYPE_ADD; // Render add
		if ($detalle_deudas->CurrentAction == "gridadd" && $detalle_deudas->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$detalle_deudas_grid->RestoreCurrentRowFormValues($detalle_deudas_grid->RowIndex); // Restore form values
		if ($detalle_deudas->CurrentAction == "gridedit") { // Grid edit
			if ($detalle_deudas->EventCancelled) {
				$detalle_deudas_grid->RestoreCurrentRowFormValues($detalle_deudas_grid->RowIndex); // Restore form values
			}
			if ($detalle_deudas_grid->RowAction == "insert")
				$detalle_deudas->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$detalle_deudas->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($detalle_deudas->CurrentAction == "gridedit" && ($detalle_deudas->RowType == EW_ROWTYPE_EDIT || $detalle_deudas->RowType == EW_ROWTYPE_ADD) && $detalle_deudas->EventCancelled) // Update failed
			$detalle_deudas_grid->RestoreCurrentRowFormValues($detalle_deudas_grid->RowIndex); // Restore form values
		if ($detalle_deudas->RowType == EW_ROWTYPE_EDIT) // Edit row
			$detalle_deudas_grid->EditRowCnt++;
		if ($detalle_deudas->CurrentAction == "F") // Confirm row
			$detalle_deudas_grid->RestoreCurrentRowFormValues($detalle_deudas_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$detalle_deudas->RowAttrs = array_merge($detalle_deudas->RowAttrs, array('data-rowindex'=>$detalle_deudas_grid->RowCnt, 'id'=>'r' . $detalle_deudas_grid->RowCnt . '_detalle_deudas', 'data-rowtype'=>$detalle_deudas->RowType));

		// Render row
		$detalle_deudas_grid->RenderRow();

		// Render list options
		$detalle_deudas_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($detalle_deudas_grid->RowAction <> "delete" && $detalle_deudas_grid->RowAction <> "insertdelete" && !($detalle_deudas_grid->RowAction == "insert" && $detalle_deudas->CurrentAction == "F" && $detalle_deudas_grid->EmptyRow())) {
?>
	<tr<?php echo $detalle_deudas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detalle_deudas_grid->ListOptions->Render("body", "left", $detalle_deudas_grid->RowCnt);
?>
	<?php if ($detalle_deudas->id_deuda->Visible) { // id_deuda ?>
		<td data-name="id_deuda"<?php echo $detalle_deudas->id_deuda->CellAttributes() ?>>
<?php if ($detalle_deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($detalle_deudas->id_deuda->getSessionValue() <> "") { ?>
<span id="el<?php echo $detalle_deudas_grid->RowCnt ?>_detalle_deudas_id_deuda" class="form-group detalle_deudas_id_deuda">
<span<?php echo $detalle_deudas->id_deuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalle_deudas->id_deuda->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($detalle_deudas->id_deuda->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detalle_deudas_grid->RowCnt ?>_detalle_deudas_id_deuda" class="form-group detalle_deudas_id_deuda">
<select data-field="x_id_deuda" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda"<?php echo $detalle_deudas->id_deuda->EditAttributes() ?>>
<?php
if (is_array($detalle_deudas->id_deuda->EditValue)) {
	$arwrk = $detalle_deudas->id_deuda->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detalle_deudas->id_deuda->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$detalle_deudas->id_deuda) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$detalle_deudas->id_deuda) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $detalle_deudas->id_deuda->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `mes` AS `DispFld`, `anio` AS `Disp2Fld`, `monto` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `deudas`";
 $sWhereWrk = "";
 if (!$GLOBALS["detalle_deudas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["deudas"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $detalle_deudas->Lookup_Selecting($detalle_deudas->id_deuda, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" id="s_x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_deuda" name="o<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" id="o<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($detalle_deudas->id_deuda->OldValue) ?>">
<?php } ?>
<?php if ($detalle_deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($detalle_deudas->id_deuda->getSessionValue() <> "") { ?>
<span id="el<?php echo $detalle_deudas_grid->RowCnt ?>_detalle_deudas_id_deuda" class="form-group detalle_deudas_id_deuda">
<span<?php echo $detalle_deudas->id_deuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalle_deudas->id_deuda->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($detalle_deudas->id_deuda->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detalle_deudas_grid->RowCnt ?>_detalle_deudas_id_deuda" class="form-group detalle_deudas_id_deuda">
<select data-field="x_id_deuda" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda"<?php echo $detalle_deudas->id_deuda->EditAttributes() ?>>
<?php
if (is_array($detalle_deudas->id_deuda->EditValue)) {
	$arwrk = $detalle_deudas->id_deuda->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detalle_deudas->id_deuda->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$detalle_deudas->id_deuda) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$detalle_deudas->id_deuda) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $detalle_deudas->id_deuda->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `mes` AS `DispFld`, `anio` AS `Disp2Fld`, `monto` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `deudas`";
 $sWhereWrk = "";
 if (!$GLOBALS["detalle_deudas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["deudas"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $detalle_deudas->Lookup_Selecting($detalle_deudas->id_deuda, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" id="s_x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($detalle_deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detalle_deudas->id_deuda->ViewAttributes() ?>>
<?php echo $detalle_deudas->id_deuda->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_deuda" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($detalle_deudas->id_deuda->FormValue) ?>">
<input type="hidden" data-field="x_id_deuda" name="o<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" id="o<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($detalle_deudas->id_deuda->OldValue) ?>">
<?php } ?>
<a id="<?php echo $detalle_deudas_grid->PageObjName . "_row_" . $detalle_deudas_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($detalle_deudas->detalle->Visible) { // detalle ?>
		<td data-name="detalle"<?php echo $detalle_deudas->detalle->CellAttributes() ?>>
<?php if ($detalle_deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detalle_deudas_grid->RowCnt ?>_detalle_deudas_detalle" class="form-group detalle_deudas_detalle">
<input type="text" data-field="x_detalle" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detalle_deudas->detalle->PlaceHolder) ?>" value="<?php echo $detalle_deudas->detalle->EditValue ?>"<?php echo $detalle_deudas->detalle->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_detalle" name="o<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" id="o<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" value="<?php echo ew_HtmlEncode($detalle_deudas->detalle->OldValue) ?>">
<?php } ?>
<?php if ($detalle_deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalle_deudas_grid->RowCnt ?>_detalle_deudas_detalle" class="form-group detalle_deudas_detalle">
<input type="text" data-field="x_detalle" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detalle_deudas->detalle->PlaceHolder) ?>" value="<?php echo $detalle_deudas->detalle->EditValue ?>"<?php echo $detalle_deudas->detalle->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detalle_deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detalle_deudas->detalle->ViewAttributes() ?>>
<?php echo $detalle_deudas->detalle->ListViewValue() ?></span>
<input type="hidden" data-field="x_detalle" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" value="<?php echo ew_HtmlEncode($detalle_deudas->detalle->FormValue) ?>">
<input type="hidden" data-field="x_detalle" name="o<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" id="o<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" value="<?php echo ew_HtmlEncode($detalle_deudas->detalle->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detalle_deudas->importe->Visible) { // importe ?>
		<td data-name="importe"<?php echo $detalle_deudas->importe->CellAttributes() ?>>
<?php if ($detalle_deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detalle_deudas_grid->RowCnt ?>_detalle_deudas_importe" class="form-group detalle_deudas_importe">
<input type="text" data-field="x_importe" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_importe" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_importe" size="30" placeholder="<?php echo ew_HtmlEncode($detalle_deudas->importe->PlaceHolder) ?>" value="<?php echo $detalle_deudas->importe->EditValue ?>"<?php echo $detalle_deudas->importe->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_importe" name="o<?php echo $detalle_deudas_grid->RowIndex ?>_importe" id="o<?php echo $detalle_deudas_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($detalle_deudas->importe->OldValue) ?>">
<?php } ?>
<?php if ($detalle_deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalle_deudas_grid->RowCnt ?>_detalle_deudas_importe" class="form-group detalle_deudas_importe">
<input type="text" data-field="x_importe" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_importe" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_importe" size="30" placeholder="<?php echo ew_HtmlEncode($detalle_deudas->importe->PlaceHolder) ?>" value="<?php echo $detalle_deudas->importe->EditValue ?>"<?php echo $detalle_deudas->importe->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detalle_deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detalle_deudas->importe->ViewAttributes() ?>>
<?php echo $detalle_deudas->importe->ListViewValue() ?></span>
<input type="hidden" data-field="x_importe" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_importe" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($detalle_deudas->importe->FormValue) ?>">
<input type="hidden" data-field="x_importe" name="o<?php echo $detalle_deudas_grid->RowIndex ?>_importe" id="o<?php echo $detalle_deudas_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($detalle_deudas->importe->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detalle_deudas_grid->ListOptions->Render("body", "right", $detalle_deudas_grid->RowCnt);
?>
	</tr>
<?php if ($detalle_deudas->RowType == EW_ROWTYPE_ADD || $detalle_deudas->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdetalle_deudasgrid.UpdateOpts(<?php echo $detalle_deudas_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($detalle_deudas->CurrentAction <> "gridadd" || $detalle_deudas->CurrentMode == "copy")
		if (!$detalle_deudas_grid->Recordset->EOF) $detalle_deudas_grid->Recordset->MoveNext();
}
?>
<?php
	if ($detalle_deudas->CurrentMode == "add" || $detalle_deudas->CurrentMode == "copy" || $detalle_deudas->CurrentMode == "edit") {
		$detalle_deudas_grid->RowIndex = '$rowindex$';
		$detalle_deudas_grid->LoadDefaultValues();

		// Set row properties
		$detalle_deudas->ResetAttrs();
		$detalle_deudas->RowAttrs = array_merge($detalle_deudas->RowAttrs, array('data-rowindex'=>$detalle_deudas_grid->RowIndex, 'id'=>'r0_detalle_deudas', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($detalle_deudas->RowAttrs["class"], "ewTemplate");
		$detalle_deudas->RowType = EW_ROWTYPE_ADD;

		// Render row
		$detalle_deudas_grid->RenderRow();

		// Render list options
		$detalle_deudas_grid->RenderListOptions();
		$detalle_deudas_grid->StartRowCnt = 0;
?>
	<tr<?php echo $detalle_deudas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detalle_deudas_grid->ListOptions->Render("body", "left", $detalle_deudas_grid->RowIndex);
?>
	<?php if ($detalle_deudas->id_deuda->Visible) { // id_deuda ?>
		<td>
<?php if ($detalle_deudas->CurrentAction <> "F") { ?>
<?php if ($detalle_deudas->id_deuda->getSessionValue() <> "") { ?>
<span id="el$rowindex$_detalle_deudas_id_deuda" class="form-group detalle_deudas_id_deuda">
<span<?php echo $detalle_deudas->id_deuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalle_deudas->id_deuda->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($detalle_deudas->id_deuda->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_detalle_deudas_id_deuda" class="form-group detalle_deudas_id_deuda">
<select data-field="x_id_deuda" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda"<?php echo $detalle_deudas->id_deuda->EditAttributes() ?>>
<?php
if (is_array($detalle_deudas->id_deuda->EditValue)) {
	$arwrk = $detalle_deudas->id_deuda->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detalle_deudas->id_deuda->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$detalle_deudas->id_deuda) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$detalle_deudas->id_deuda) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $detalle_deudas->id_deuda->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `mes` AS `DispFld`, `anio` AS `Disp2Fld`, `monto` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `deudas`";
 $sWhereWrk = "";
 if (!$GLOBALS["detalle_deudas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["deudas"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $detalle_deudas->Lookup_Selecting($detalle_deudas->id_deuda, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" id="s_x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_detalle_deudas_id_deuda" class="form-group detalle_deudas_id_deuda">
<span<?php echo $detalle_deudas->id_deuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalle_deudas->id_deuda->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_deuda" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($detalle_deudas->id_deuda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_deuda" name="o<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" id="o<?php echo $detalle_deudas_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($detalle_deudas->id_deuda->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detalle_deudas->detalle->Visible) { // detalle ?>
		<td>
<?php if ($detalle_deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detalle_deudas_detalle" class="form-group detalle_deudas_detalle">
<input type="text" data-field="x_detalle" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detalle_deudas->detalle->PlaceHolder) ?>" value="<?php echo $detalle_deudas->detalle->EditValue ?>"<?php echo $detalle_deudas->detalle->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detalle_deudas_detalle" class="form-group detalle_deudas_detalle">
<span<?php echo $detalle_deudas->detalle->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalle_deudas->detalle->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_detalle" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" value="<?php echo ew_HtmlEncode($detalle_deudas->detalle->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_detalle" name="o<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" id="o<?php echo $detalle_deudas_grid->RowIndex ?>_detalle" value="<?php echo ew_HtmlEncode($detalle_deudas->detalle->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detalle_deudas->importe->Visible) { // importe ?>
		<td>
<?php if ($detalle_deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detalle_deudas_importe" class="form-group detalle_deudas_importe">
<input type="text" data-field="x_importe" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_importe" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_importe" size="30" placeholder="<?php echo ew_HtmlEncode($detalle_deudas->importe->PlaceHolder) ?>" value="<?php echo $detalle_deudas->importe->EditValue ?>"<?php echo $detalle_deudas->importe->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detalle_deudas_importe" class="form-group detalle_deudas_importe">
<span<?php echo $detalle_deudas->importe->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalle_deudas->importe->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_importe" name="x<?php echo $detalle_deudas_grid->RowIndex ?>_importe" id="x<?php echo $detalle_deudas_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($detalle_deudas->importe->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_importe" name="o<?php echo $detalle_deudas_grid->RowIndex ?>_importe" id="o<?php echo $detalle_deudas_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($detalle_deudas->importe->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detalle_deudas_grid->ListOptions->Render("body", "right", $detalle_deudas_grid->RowCnt);
?>
<script type="text/javascript">
fdetalle_deudasgrid.UpdateOpts(<?php echo $detalle_deudas_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($detalle_deudas->CurrentMode == "add" || $detalle_deudas->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $detalle_deudas_grid->FormKeyCountName ?>" id="<?php echo $detalle_deudas_grid->FormKeyCountName ?>" value="<?php echo $detalle_deudas_grid->KeyCount ?>">
<?php echo $detalle_deudas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detalle_deudas->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $detalle_deudas_grid->FormKeyCountName ?>" id="<?php echo $detalle_deudas_grid->FormKeyCountName ?>" value="<?php echo $detalle_deudas_grid->KeyCount ?>">
<?php echo $detalle_deudas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detalle_deudas->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdetalle_deudasgrid">
</div>
<?php

// Close recordset
if ($detalle_deudas_grid->Recordset)
	$detalle_deudas_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($detalle_deudas_grid->TotalRecs == 0 && $detalle_deudas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($detalle_deudas_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($detalle_deudas->Export == "") { ?>
<script type="text/javascript">
fdetalle_deudasgrid.Init();
</script>
<?php } ?>
<?php
$detalle_deudas_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$detalle_deudas_grid->Page_Terminate();
?>
