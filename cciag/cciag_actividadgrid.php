<?php include_once "cciag_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($actividad_grid)) $actividad_grid = new cactividad_grid();

// Page init
$actividad_grid->Page_Init();

// Page main
$actividad_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$actividad_grid->Page_Render();
?>
<?php if ($actividad->Export == "") { ?>
<script type="text/javascript">

// Page object
var actividad_grid = new ew_Page("actividad_grid");
actividad_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = actividad_grid.PageID; // For backward compatibility

// Form object
var factividadgrid = new ew_Form("factividadgrid");
factividadgrid.FormKeyCountName = '<?php echo $actividad_grid->FormKeyCountName ?>';

// Validate form
factividadgrid.Validate = function() {
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
factividadgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_rubro", false)) return false;
	if (ew_ValueChanged(fobj, infix, "actividad", false)) return false;
	if (ew_ValueChanged(fobj, infix, "descripcion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "activa", false)) return false;
	return true;
}

// Form_CustomValidate event
factividadgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
factividadgrid.ValidateRequired = true;
<?php } else { ?>
factividadgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
factividadgrid.Lists["x_id_rubro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rubro","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($actividad->CurrentAction == "gridadd") {
	if ($actividad->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$actividad_grid->TotalRecs = $actividad->SelectRecordCount();
			$actividad_grid->Recordset = $actividad_grid->LoadRecordset($actividad_grid->StartRec-1, $actividad_grid->DisplayRecs);
		} else {
			if ($actividad_grid->Recordset = $actividad_grid->LoadRecordset())
				$actividad_grid->TotalRecs = $actividad_grid->Recordset->RecordCount();
		}
		$actividad_grid->StartRec = 1;
		$actividad_grid->DisplayRecs = $actividad_grid->TotalRecs;
	} else {
		$actividad->CurrentFilter = "0=1";
		$actividad_grid->StartRec = 1;
		$actividad_grid->DisplayRecs = $actividad->GridAddRowCount;
	}
	$actividad_grid->TotalRecs = $actividad_grid->DisplayRecs;
	$actividad_grid->StopRec = $actividad_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($actividad_grid->TotalRecs <= 0)
			$actividad_grid->TotalRecs = $actividad->SelectRecordCount();
	} else {
		if (!$actividad_grid->Recordset && ($actividad_grid->Recordset = $actividad_grid->LoadRecordset()))
			$actividad_grid->TotalRecs = $actividad_grid->Recordset->RecordCount();
	}
	$actividad_grid->StartRec = 1;
	$actividad_grid->DisplayRecs = $actividad_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$actividad_grid->Recordset = $actividad_grid->LoadRecordset($actividad_grid->StartRec-1, $actividad_grid->DisplayRecs);

	// Set no record found message
	if ($actividad->CurrentAction == "" && $actividad_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$actividad_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($actividad_grid->SearchWhere == "0=101")
			$actividad_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$actividad_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$actividad_grid->RenderOtherOptions();
?>
<?php $actividad_grid->ShowPageHeader(); ?>
<?php
$actividad_grid->ShowMessage();
?>
<?php if ($actividad_grid->TotalRecs > 0 || $actividad->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="factividadgrid" class="ewForm form-inline">
<?php if ($actividad_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($actividad_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_actividad" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_actividadgrid" class="table ewTable">
<?php echo $actividad->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$actividad->RowType = EW_ROWTYPE_HEADER;

// Render list options
$actividad_grid->RenderListOptions();

// Render list options (header, left)
$actividad_grid->ListOptions->Render("header", "left");
?>
<?php if ($actividad->id->Visible) { // id ?>
	<?php if ($actividad->SortUrl($actividad->id) == "") { ?>
		<th data-name="id"><div id="elh_actividad_id" class="actividad_id"><div class="ewTableHeaderCaption"><?php echo $actividad->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div><div id="elh_actividad_id" class="actividad_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->id_rubro->Visible) { // id_rubro ?>
	<?php if ($actividad->SortUrl($actividad->id_rubro) == "") { ?>
		<th data-name="id_rubro"><div id="elh_actividad_id_rubro" class="actividad_id_rubro"><div class="ewTableHeaderCaption"><?php echo $actividad->id_rubro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_rubro"><div><div id="elh_actividad_id_rubro" class="actividad_id_rubro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->id_rubro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->id_rubro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->id_rubro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->actividad->Visible) { // actividad ?>
	<?php if ($actividad->SortUrl($actividad->actividad) == "") { ?>
		<th data-name="actividad"><div id="elh_actividad_actividad" class="actividad_actividad"><div class="ewTableHeaderCaption"><?php echo $actividad->actividad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="actividad"><div><div id="elh_actividad_actividad" class="actividad_actividad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->actividad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->actividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->actividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->descripcion->Visible) { // descripcion ?>
	<?php if ($actividad->SortUrl($actividad->descripcion) == "") { ?>
		<th data-name="descripcion"><div id="elh_actividad_descripcion" class="actividad_descripcion"><div class="ewTableHeaderCaption"><?php echo $actividad->descripcion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descripcion"><div><div id="elh_actividad_descripcion" class="actividad_descripcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->descripcion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->activa->Visible) { // activa ?>
	<?php if ($actividad->SortUrl($actividad->activa) == "") { ?>
		<th data-name="activa"><div id="elh_actividad_activa" class="actividad_activa"><div class="ewTableHeaderCaption"><?php echo $actividad->activa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activa"><div><div id="elh_actividad_activa" class="actividad_activa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->activa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->activa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->activa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$actividad_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$actividad_grid->StartRec = 1;
$actividad_grid->StopRec = $actividad_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($actividad_grid->FormKeyCountName) && ($actividad->CurrentAction == "gridadd" || $actividad->CurrentAction == "gridedit" || $actividad->CurrentAction == "F")) {
		$actividad_grid->KeyCount = $objForm->GetValue($actividad_grid->FormKeyCountName);
		$actividad_grid->StopRec = $actividad_grid->StartRec + $actividad_grid->KeyCount - 1;
	}
}
$actividad_grid->RecCnt = $actividad_grid->StartRec - 1;
if ($actividad_grid->Recordset && !$actividad_grid->Recordset->EOF) {
	$actividad_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $actividad_grid->StartRec > 1)
		$actividad_grid->Recordset->Move($actividad_grid->StartRec - 1);
} elseif (!$actividad->AllowAddDeleteRow && $actividad_grid->StopRec == 0) {
	$actividad_grid->StopRec = $actividad->GridAddRowCount;
}

// Initialize aggregate
$actividad->RowType = EW_ROWTYPE_AGGREGATEINIT;
$actividad->ResetAttrs();
$actividad_grid->RenderRow();
if ($actividad->CurrentAction == "gridadd")
	$actividad_grid->RowIndex = 0;
if ($actividad->CurrentAction == "gridedit")
	$actividad_grid->RowIndex = 0;
while ($actividad_grid->RecCnt < $actividad_grid->StopRec) {
	$actividad_grid->RecCnt++;
	if (intval($actividad_grid->RecCnt) >= intval($actividad_grid->StartRec)) {
		$actividad_grid->RowCnt++;
		if ($actividad->CurrentAction == "gridadd" || $actividad->CurrentAction == "gridedit" || $actividad->CurrentAction == "F") {
			$actividad_grid->RowIndex++;
			$objForm->Index = $actividad_grid->RowIndex;
			if ($objForm->HasValue($actividad_grid->FormActionName))
				$actividad_grid->RowAction = strval($objForm->GetValue($actividad_grid->FormActionName));
			elseif ($actividad->CurrentAction == "gridadd")
				$actividad_grid->RowAction = "insert";
			else
				$actividad_grid->RowAction = "";
		}

		// Set up key count
		$actividad_grid->KeyCount = $actividad_grid->RowIndex;

		// Init row class and style
		$actividad->ResetAttrs();
		$actividad->CssClass = "";
		if ($actividad->CurrentAction == "gridadd") {
			if ($actividad->CurrentMode == "copy") {
				$actividad_grid->LoadRowValues($actividad_grid->Recordset); // Load row values
				$actividad_grid->SetRecordKey($actividad_grid->RowOldKey, $actividad_grid->Recordset); // Set old record key
			} else {
				$actividad_grid->LoadDefaultValues(); // Load default values
				$actividad_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$actividad_grid->LoadRowValues($actividad_grid->Recordset); // Load row values
		}
		$actividad->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($actividad->CurrentAction == "gridadd") // Grid add
			$actividad->RowType = EW_ROWTYPE_ADD; // Render add
		if ($actividad->CurrentAction == "gridadd" && $actividad->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$actividad_grid->RestoreCurrentRowFormValues($actividad_grid->RowIndex); // Restore form values
		if ($actividad->CurrentAction == "gridedit") { // Grid edit
			if ($actividad->EventCancelled) {
				$actividad_grid->RestoreCurrentRowFormValues($actividad_grid->RowIndex); // Restore form values
			}
			if ($actividad_grid->RowAction == "insert")
				$actividad->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$actividad->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($actividad->CurrentAction == "gridedit" && ($actividad->RowType == EW_ROWTYPE_EDIT || $actividad->RowType == EW_ROWTYPE_ADD) && $actividad->EventCancelled) // Update failed
			$actividad_grid->RestoreCurrentRowFormValues($actividad_grid->RowIndex); // Restore form values
		if ($actividad->RowType == EW_ROWTYPE_EDIT) // Edit row
			$actividad_grid->EditRowCnt++;
		if ($actividad->CurrentAction == "F") // Confirm row
			$actividad_grid->RestoreCurrentRowFormValues($actividad_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$actividad->RowAttrs = array_merge($actividad->RowAttrs, array('data-rowindex'=>$actividad_grid->RowCnt, 'id'=>'r' . $actividad_grid->RowCnt . '_actividad', 'data-rowtype'=>$actividad->RowType));

		// Render row
		$actividad_grid->RenderRow();

		// Render list options
		$actividad_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($actividad_grid->RowAction <> "delete" && $actividad_grid->RowAction <> "insertdelete" && !($actividad_grid->RowAction == "insert" && $actividad->CurrentAction == "F" && $actividad_grid->EmptyRow())) {
?>
	<tr<?php echo $actividad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$actividad_grid->ListOptions->Render("body", "left", $actividad_grid->RowCnt);
?>
	<?php if ($actividad->id->Visible) { // id ?>
		<td data-name="id"<?php echo $actividad->id->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="o<?php echo $actividad_grid->RowIndex ?>_id" id="o<?php echo $actividad_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($actividad->id->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_id" class="form-group actividad_id">
<span<?php echo $actividad->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_id" name="x<?php echo $actividad_grid->RowIndex ?>_id" id="x<?php echo $actividad_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($actividad->id->CurrentValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $actividad->id->ViewAttributes() ?>>
<?php echo $actividad->id->ListViewValue() ?></span>
<input type="hidden" data-field="x_id" name="x<?php echo $actividad_grid->RowIndex ?>_id" id="x<?php echo $actividad_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($actividad->id->FormValue) ?>">
<input type="hidden" data-field="x_id" name="o<?php echo $actividad_grid->RowIndex ?>_id" id="o<?php echo $actividad_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($actividad->id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $actividad_grid->PageObjName . "_row_" . $actividad_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($actividad->id_rubro->Visible) { // id_rubro ?>
		<td data-name="id_rubro"<?php echo $actividad->id_rubro->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($actividad->id_rubro->getSessionValue() <> "") { ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_id_rubro" class="form-group actividad_id_rubro">
<span<?php echo $actividad->id_rubro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->id_rubro->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" name="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="<?php echo ew_HtmlEncode($actividad->id_rubro->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_id_rubro" class="form-group actividad_id_rubro">
<select data-field="x_id_rubro" id="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" name="x<?php echo $actividad_grid->RowIndex ?>_id_rubro"<?php echo $actividad->id_rubro->EditAttributes() ?>>
<?php
if (is_array($actividad->id_rubro->EditValue)) {
	$arwrk = $actividad->id_rubro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($actividad->id_rubro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $actividad->id_rubro->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `rubro` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activa`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $actividad->Lookup_Selecting($actividad->id_rubro, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `rubro` DESC";
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_id_rubro" id="s_x<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_rubro" name="o<?php echo $actividad_grid->RowIndex ?>_id_rubro" id="o<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="<?php echo ew_HtmlEncode($actividad->id_rubro->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($actividad->id_rubro->getSessionValue() <> "") { ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_id_rubro" class="form-group actividad_id_rubro">
<span<?php echo $actividad->id_rubro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->id_rubro->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" name="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="<?php echo ew_HtmlEncode($actividad->id_rubro->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_id_rubro" class="form-group actividad_id_rubro">
<select data-field="x_id_rubro" id="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" name="x<?php echo $actividad_grid->RowIndex ?>_id_rubro"<?php echo $actividad->id_rubro->EditAttributes() ?>>
<?php
if (is_array($actividad->id_rubro->EditValue)) {
	$arwrk = $actividad->id_rubro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($actividad->id_rubro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $actividad->id_rubro->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `rubro` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activa`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $actividad->Lookup_Selecting($actividad->id_rubro, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `rubro` DESC";
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_id_rubro" id="s_x<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $actividad->id_rubro->ViewAttributes() ?>>
<?php echo $actividad->id_rubro->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_rubro" name="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" id="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="<?php echo ew_HtmlEncode($actividad->id_rubro->FormValue) ?>">
<input type="hidden" data-field="x_id_rubro" name="o<?php echo $actividad_grid->RowIndex ?>_id_rubro" id="o<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="<?php echo ew_HtmlEncode($actividad->id_rubro->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->actividad->Visible) { // actividad ?>
		<td data-name="actividad"<?php echo $actividad->actividad->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_actividad" class="form-group actividad_actividad">
<input type="text" data-field="x_actividad" name="x<?php echo $actividad_grid->RowIndex ?>_actividad" id="x<?php echo $actividad_grid->RowIndex ?>_actividad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->actividad->PlaceHolder) ?>" value="<?php echo $actividad->actividad->EditValue ?>"<?php echo $actividad->actividad->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_actividad" name="o<?php echo $actividad_grid->RowIndex ?>_actividad" id="o<?php echo $actividad_grid->RowIndex ?>_actividad" value="<?php echo ew_HtmlEncode($actividad->actividad->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_actividad" class="form-group actividad_actividad">
<input type="text" data-field="x_actividad" name="x<?php echo $actividad_grid->RowIndex ?>_actividad" id="x<?php echo $actividad_grid->RowIndex ?>_actividad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->actividad->PlaceHolder) ?>" value="<?php echo $actividad->actividad->EditValue ?>"<?php echo $actividad->actividad->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $actividad->actividad->ViewAttributes() ?>>
<?php echo $actividad->actividad->ListViewValue() ?></span>
<input type="hidden" data-field="x_actividad" name="x<?php echo $actividad_grid->RowIndex ?>_actividad" id="x<?php echo $actividad_grid->RowIndex ?>_actividad" value="<?php echo ew_HtmlEncode($actividad->actividad->FormValue) ?>">
<input type="hidden" data-field="x_actividad" name="o<?php echo $actividad_grid->RowIndex ?>_actividad" id="o<?php echo $actividad_grid->RowIndex ?>_actividad" value="<?php echo ew_HtmlEncode($actividad->actividad->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->descripcion->Visible) { // descripcion ?>
		<td data-name="descripcion"<?php echo $actividad->descripcion->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_descripcion" class="form-group actividad_descripcion">
<textarea data-field="x_descripcion" name="x<?php echo $actividad_grid->RowIndex ?>_descripcion" id="x<?php echo $actividad_grid->RowIndex ?>_descripcion" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($actividad->descripcion->PlaceHolder) ?>"<?php echo $actividad->descripcion->EditAttributes() ?>><?php echo $actividad->descripcion->EditValue ?></textarea>
</span>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $actividad_grid->RowIndex ?>_descripcion" id="o<?php echo $actividad_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($actividad->descripcion->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_descripcion" class="form-group actividad_descripcion">
<textarea data-field="x_descripcion" name="x<?php echo $actividad_grid->RowIndex ?>_descripcion" id="x<?php echo $actividad_grid->RowIndex ?>_descripcion" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($actividad->descripcion->PlaceHolder) ?>"<?php echo $actividad->descripcion->EditAttributes() ?>><?php echo $actividad->descripcion->EditValue ?></textarea>
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $actividad->descripcion->ViewAttributes() ?>>
<?php echo $actividad->descripcion->ListViewValue() ?></span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $actividad_grid->RowIndex ?>_descripcion" id="x<?php echo $actividad_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($actividad->descripcion->FormValue) ?>">
<input type="hidden" data-field="x_descripcion" name="o<?php echo $actividad_grid->RowIndex ?>_descripcion" id="o<?php echo $actividad_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($actividad->descripcion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->activa->Visible) { // activa ?>
		<td data-name="activa"<?php echo $actividad->activa->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_activa" class="form-group actividad_activa">
<div id="tp_x<?php echo $actividad_grid->RowIndex ?>_activa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $actividad_grid->RowIndex ?>_activa" id="x<?php echo $actividad_grid->RowIndex ?>_activa" value="{value}"<?php echo $actividad->activa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $actividad_grid->RowIndex ?>_activa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $actividad->activa->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($actividad->activa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activa" name="x<?php echo $actividad_grid->RowIndex ?>_activa" id="x<?php echo $actividad_grid->RowIndex ?>_activa_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $actividad->activa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $actividad->activa->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_activa" name="o<?php echo $actividad_grid->RowIndex ?>_activa" id="o<?php echo $actividad_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($actividad->activa->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_activa" class="form-group actividad_activa">
<div id="tp_x<?php echo $actividad_grid->RowIndex ?>_activa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $actividad_grid->RowIndex ?>_activa" id="x<?php echo $actividad_grid->RowIndex ?>_activa" value="{value}"<?php echo $actividad->activa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $actividad_grid->RowIndex ?>_activa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $actividad->activa->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($actividad->activa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activa" name="x<?php echo $actividad_grid->RowIndex ?>_activa" id="x<?php echo $actividad_grid->RowIndex ?>_activa_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $actividad->activa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $actividad->activa->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $actividad->activa->ViewAttributes() ?>>
<?php echo $actividad->activa->ListViewValue() ?></span>
<input type="hidden" data-field="x_activa" name="x<?php echo $actividad_grid->RowIndex ?>_activa" id="x<?php echo $actividad_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($actividad->activa->FormValue) ?>">
<input type="hidden" data-field="x_activa" name="o<?php echo $actividad_grid->RowIndex ?>_activa" id="o<?php echo $actividad_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($actividad->activa->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$actividad_grid->ListOptions->Render("body", "right", $actividad_grid->RowCnt);
?>
	</tr>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD || $actividad->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
factividadgrid.UpdateOpts(<?php echo $actividad_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($actividad->CurrentAction <> "gridadd" || $actividad->CurrentMode == "copy")
		if (!$actividad_grid->Recordset->EOF) $actividad_grid->Recordset->MoveNext();
}
?>
<?php
	if ($actividad->CurrentMode == "add" || $actividad->CurrentMode == "copy" || $actividad->CurrentMode == "edit") {
		$actividad_grid->RowIndex = '$rowindex$';
		$actividad_grid->LoadDefaultValues();

		// Set row properties
		$actividad->ResetAttrs();
		$actividad->RowAttrs = array_merge($actividad->RowAttrs, array('data-rowindex'=>$actividad_grid->RowIndex, 'id'=>'r0_actividad', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($actividad->RowAttrs["class"], "ewTemplate");
		$actividad->RowType = EW_ROWTYPE_ADD;

		// Render row
		$actividad_grid->RenderRow();

		// Render list options
		$actividad_grid->RenderListOptions();
		$actividad_grid->StartRowCnt = 0;
?>
	<tr<?php echo $actividad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$actividad_grid->ListOptions->Render("body", "left", $actividad_grid->RowIndex);
?>
	<?php if ($actividad->id->Visible) { // id ?>
		<td data-name="id">
<?php if ($actividad->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_actividad_id" class="form-group actividad_id">
<span<?php echo $actividad->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id" name="x<?php echo $actividad_grid->RowIndex ?>_id" id="x<?php echo $actividad_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($actividad->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id" name="o<?php echo $actividad_grid->RowIndex ?>_id" id="o<?php echo $actividad_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($actividad->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->id_rubro->Visible) { // id_rubro ?>
		<td data-name="id_rubro">
<?php if ($actividad->CurrentAction <> "F") { ?>
<?php if ($actividad->id_rubro->getSessionValue() <> "") { ?>
<span id="el$rowindex$_actividad_id_rubro" class="form-group actividad_id_rubro">
<span<?php echo $actividad->id_rubro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->id_rubro->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" name="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="<?php echo ew_HtmlEncode($actividad->id_rubro->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_actividad_id_rubro" class="form-group actividad_id_rubro">
<select data-field="x_id_rubro" id="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" name="x<?php echo $actividad_grid->RowIndex ?>_id_rubro"<?php echo $actividad->id_rubro->EditAttributes() ?>>
<?php
if (is_array($actividad->id_rubro->EditValue)) {
	$arwrk = $actividad->id_rubro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($actividad->id_rubro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $actividad->id_rubro->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `rubro` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
 $sWhereWrk = "";
 $lookuptblfilter = "`activa`='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $actividad->Lookup_Selecting($actividad->id_rubro, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `rubro` DESC";
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_id_rubro" id="s_x<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_actividad_id_rubro" class="form-group actividad_id_rubro">
<span<?php echo $actividad->id_rubro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->id_rubro->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_rubro" name="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" id="x<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="<?php echo ew_HtmlEncode($actividad->id_rubro->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_rubro" name="o<?php echo $actividad_grid->RowIndex ?>_id_rubro" id="o<?php echo $actividad_grid->RowIndex ?>_id_rubro" value="<?php echo ew_HtmlEncode($actividad->id_rubro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->actividad->Visible) { // actividad ?>
		<td data-name="actividad">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_actividad" class="form-group actividad_actividad">
<input type="text" data-field="x_actividad" name="x<?php echo $actividad_grid->RowIndex ?>_actividad" id="x<?php echo $actividad_grid->RowIndex ?>_actividad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($actividad->actividad->PlaceHolder) ?>" value="<?php echo $actividad->actividad->EditValue ?>"<?php echo $actividad->actividad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_actividad" class="form-group actividad_actividad">
<span<?php echo $actividad->actividad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->actividad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_actividad" name="x<?php echo $actividad_grid->RowIndex ?>_actividad" id="x<?php echo $actividad_grid->RowIndex ?>_actividad" value="<?php echo ew_HtmlEncode($actividad->actividad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_actividad" name="o<?php echo $actividad_grid->RowIndex ?>_actividad" id="o<?php echo $actividad_grid->RowIndex ?>_actividad" value="<?php echo ew_HtmlEncode($actividad->actividad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->descripcion->Visible) { // descripcion ?>
		<td data-name="descripcion">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_descripcion" class="form-group actividad_descripcion">
<textarea data-field="x_descripcion" name="x<?php echo $actividad_grid->RowIndex ?>_descripcion" id="x<?php echo $actividad_grid->RowIndex ?>_descripcion" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($actividad->descripcion->PlaceHolder) ?>"<?php echo $actividad->descripcion->EditAttributes() ?>><?php echo $actividad->descripcion->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_descripcion" class="form-group actividad_descripcion">
<span<?php echo $actividad->descripcion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->descripcion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $actividad_grid->RowIndex ?>_descripcion" id="x<?php echo $actividad_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($actividad->descripcion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $actividad_grid->RowIndex ?>_descripcion" id="o<?php echo $actividad_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($actividad->descripcion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->activa->Visible) { // activa ?>
		<td data-name="activa">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_activa" class="form-group actividad_activa">
<div id="tp_x<?php echo $actividad_grid->RowIndex ?>_activa" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $actividad_grid->RowIndex ?>_activa" id="x<?php echo $actividad_grid->RowIndex ?>_activa" value="{value}"<?php echo $actividad->activa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $actividad_grid->RowIndex ?>_activa" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $actividad->activa->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($actividad->activa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activa" name="x<?php echo $actividad_grid->RowIndex ?>_activa" id="x<?php echo $actividad_grid->RowIndex ?>_activa_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $actividad->activa->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $actividad->activa->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_activa" class="form-group actividad_activa">
<span<?php echo $actividad->activa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->activa->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_activa" name="x<?php echo $actividad_grid->RowIndex ?>_activa" id="x<?php echo $actividad_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($actividad->activa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_activa" name="o<?php echo $actividad_grid->RowIndex ?>_activa" id="o<?php echo $actividad_grid->RowIndex ?>_activa" value="<?php echo ew_HtmlEncode($actividad->activa->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$actividad_grid->ListOptions->Render("body", "right", $actividad_grid->RowCnt);
?>
<script type="text/javascript">
factividadgrid.UpdateOpts(<?php echo $actividad_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($actividad->CurrentMode == "add" || $actividad->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $actividad_grid->FormKeyCountName ?>" id="<?php echo $actividad_grid->FormKeyCountName ?>" value="<?php echo $actividad_grid->KeyCount ?>">
<?php echo $actividad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($actividad->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $actividad_grid->FormKeyCountName ?>" id="<?php echo $actividad_grid->FormKeyCountName ?>" value="<?php echo $actividad_grid->KeyCount ?>">
<?php echo $actividad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($actividad->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="factividadgrid">
</div>
<?php

// Close recordset
if ($actividad_grid->Recordset)
	$actividad_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($actividad_grid->TotalRecs == 0 && $actividad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($actividad_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($actividad->Export == "") { ?>
<script type="text/javascript">
factividadgrid.Init();
</script>
<?php } ?>
<?php
$actividad_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$actividad_grid->Page_Terminate();
?>
