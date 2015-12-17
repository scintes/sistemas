<?php include_once "cciag_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($seguimiento_tramites_grid)) $seguimiento_tramites_grid = new cseguimiento_tramites_grid();

// Page init
$seguimiento_tramites_grid->Page_Init();

// Page main
$seguimiento_tramites_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$seguimiento_tramites_grid->Page_Render();
?>
<?php if ($seguimiento_tramites->Export == "") { ?>
<script type="text/javascript">

// Page object
var seguimiento_tramites_grid = new ew_Page("seguimiento_tramites_grid");
seguimiento_tramites_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = seguimiento_tramites_grid.PageID; // For backward compatibility

// Form object
var fseguimiento_tramitesgrid = new ew_Form("fseguimiento_tramitesgrid");
fseguimiento_tramitesgrid.FormKeyCountName = '<?php echo $seguimiento_tramites_grid->FormKeyCountName ?>';

// Validate form
fseguimiento_tramitesgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_tramite");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $seguimiento_tramites->id_tramite->FldCaption(), $seguimiento_tramites->id_tramite->ReqErrMsg)) ?>");

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
fseguimiento_tramitesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_tramite", false)) return false;
	if (ew_ValueChanged(fobj, infix, "titulo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "archivo", false)) return false;
	return true;
}

// Form_CustomValidate event
fseguimiento_tramitesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fseguimiento_tramitesgrid.ValidateRequired = true;
<?php } else { ?>
fseguimiento_tramitesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fseguimiento_tramitesgrid.Lists["x_id_tramite"] = {"LinkField":"x_codigo","Ajax":true,"AutoFill":true,"DisplayFields":["x_codigo","x_fecha","x_Titulo",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($seguimiento_tramites->CurrentAction == "gridadd") {
	if ($seguimiento_tramites->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$seguimiento_tramites_grid->TotalRecs = $seguimiento_tramites->SelectRecordCount();
			$seguimiento_tramites_grid->Recordset = $seguimiento_tramites_grid->LoadRecordset($seguimiento_tramites_grid->StartRec-1, $seguimiento_tramites_grid->DisplayRecs);
		} else {
			if ($seguimiento_tramites_grid->Recordset = $seguimiento_tramites_grid->LoadRecordset())
				$seguimiento_tramites_grid->TotalRecs = $seguimiento_tramites_grid->Recordset->RecordCount();
		}
		$seguimiento_tramites_grid->StartRec = 1;
		$seguimiento_tramites_grid->DisplayRecs = $seguimiento_tramites_grid->TotalRecs;
	} else {
		$seguimiento_tramites->CurrentFilter = "0=1";
		$seguimiento_tramites_grid->StartRec = 1;
		$seguimiento_tramites_grid->DisplayRecs = $seguimiento_tramites->GridAddRowCount;
	}
	$seguimiento_tramites_grid->TotalRecs = $seguimiento_tramites_grid->DisplayRecs;
	$seguimiento_tramites_grid->StopRec = $seguimiento_tramites_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($seguimiento_tramites_grid->TotalRecs <= 0)
			$seguimiento_tramites_grid->TotalRecs = $seguimiento_tramites->SelectRecordCount();
	} else {
		if (!$seguimiento_tramites_grid->Recordset && ($seguimiento_tramites_grid->Recordset = $seguimiento_tramites_grid->LoadRecordset()))
			$seguimiento_tramites_grid->TotalRecs = $seguimiento_tramites_grid->Recordset->RecordCount();
	}
	$seguimiento_tramites_grid->StartRec = 1;
	$seguimiento_tramites_grid->DisplayRecs = $seguimiento_tramites_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$seguimiento_tramites_grid->Recordset = $seguimiento_tramites_grid->LoadRecordset($seguimiento_tramites_grid->StartRec-1, $seguimiento_tramites_grid->DisplayRecs);

	// Set no record found message
	if ($seguimiento_tramites->CurrentAction == "" && $seguimiento_tramites_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$seguimiento_tramites_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($seguimiento_tramites_grid->SearchWhere == "0=101")
			$seguimiento_tramites_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$seguimiento_tramites_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$seguimiento_tramites_grid->RenderOtherOptions();
?>
<?php $seguimiento_tramites_grid->ShowPageHeader(); ?>
<?php
$seguimiento_tramites_grid->ShowMessage();
?>
<?php if ($seguimiento_tramites_grid->TotalRecs > 0 || $seguimiento_tramites->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fseguimiento_tramitesgrid" class="ewForm form-inline">
<?php if ($seguimiento_tramites_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($seguimiento_tramites_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_seguimiento_tramites" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_seguimiento_tramitesgrid" class="table ewTable">
<?php echo $seguimiento_tramites->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$seguimiento_tramites->RowType = EW_ROWTYPE_HEADER;

// Render list options
$seguimiento_tramites_grid->RenderListOptions();

// Render list options (header, left)
$seguimiento_tramites_grid->ListOptions->Render("header", "left");
?>
<?php if ($seguimiento_tramites->id_tramite->Visible) { // id_tramite ?>
	<?php if ($seguimiento_tramites->SortUrl($seguimiento_tramites->id_tramite) == "") { ?>
		<th data-name="id_tramite"><div id="elh_seguimiento_tramites_id_tramite" class="seguimiento_tramites_id_tramite"><div class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->id_tramite->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tramite"><div><div id="elh_seguimiento_tramites_id_tramite" class="seguimiento_tramites_id_tramite">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->id_tramite->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($seguimiento_tramites->id_tramite->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($seguimiento_tramites->id_tramite->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($seguimiento_tramites->fecha->Visible) { // fecha ?>
	<?php if ($seguimiento_tramites->SortUrl($seguimiento_tramites->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_seguimiento_tramites_fecha" class="seguimiento_tramites_fecha"><div class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_seguimiento_tramites_fecha" class="seguimiento_tramites_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($seguimiento_tramites->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($seguimiento_tramites->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($seguimiento_tramites->hora->Visible) { // hora ?>
	<?php if ($seguimiento_tramites->SortUrl($seguimiento_tramites->hora) == "") { ?>
		<th data-name="hora"><div id="elh_seguimiento_tramites_hora" class="seguimiento_tramites_hora"><div class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->hora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hora"><div><div id="elh_seguimiento_tramites_hora" class="seguimiento_tramites_hora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->hora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($seguimiento_tramites->hora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($seguimiento_tramites->hora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($seguimiento_tramites->titulo->Visible) { // titulo ?>
	<?php if ($seguimiento_tramites->SortUrl($seguimiento_tramites->titulo) == "") { ?>
		<th data-name="titulo"><div id="elh_seguimiento_tramites_titulo" class="seguimiento_tramites_titulo"><div class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->titulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="titulo"><div><div id="elh_seguimiento_tramites_titulo" class="seguimiento_tramites_titulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->titulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($seguimiento_tramites->titulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($seguimiento_tramites->titulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($seguimiento_tramites->archivo->Visible) { // archivo ?>
	<?php if ($seguimiento_tramites->SortUrl($seguimiento_tramites->archivo) == "") { ?>
		<th data-name="archivo"><div id="elh_seguimiento_tramites_archivo" class="seguimiento_tramites_archivo"><div class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->archivo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="archivo"><div><div id="elh_seguimiento_tramites_archivo" class="seguimiento_tramites_archivo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $seguimiento_tramites->archivo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($seguimiento_tramites->archivo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($seguimiento_tramites->archivo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$seguimiento_tramites_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$seguimiento_tramites_grid->StartRec = 1;
$seguimiento_tramites_grid->StopRec = $seguimiento_tramites_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($seguimiento_tramites_grid->FormKeyCountName) && ($seguimiento_tramites->CurrentAction == "gridadd" || $seguimiento_tramites->CurrentAction == "gridedit" || $seguimiento_tramites->CurrentAction == "F")) {
		$seguimiento_tramites_grid->KeyCount = $objForm->GetValue($seguimiento_tramites_grid->FormKeyCountName);
		$seguimiento_tramites_grid->StopRec = $seguimiento_tramites_grid->StartRec + $seguimiento_tramites_grid->KeyCount - 1;
	}
}
$seguimiento_tramites_grid->RecCnt = $seguimiento_tramites_grid->StartRec - 1;
if ($seguimiento_tramites_grid->Recordset && !$seguimiento_tramites_grid->Recordset->EOF) {
	$seguimiento_tramites_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $seguimiento_tramites_grid->StartRec > 1)
		$seguimiento_tramites_grid->Recordset->Move($seguimiento_tramites_grid->StartRec - 1);
} elseif (!$seguimiento_tramites->AllowAddDeleteRow && $seguimiento_tramites_grid->StopRec == 0) {
	$seguimiento_tramites_grid->StopRec = $seguimiento_tramites->GridAddRowCount;
}

// Initialize aggregate
$seguimiento_tramites->RowType = EW_ROWTYPE_AGGREGATEINIT;
$seguimiento_tramites->ResetAttrs();
$seguimiento_tramites_grid->RenderRow();
if ($seguimiento_tramites->CurrentAction == "gridadd")
	$seguimiento_tramites_grid->RowIndex = 0;
if ($seguimiento_tramites->CurrentAction == "gridedit")
	$seguimiento_tramites_grid->RowIndex = 0;
while ($seguimiento_tramites_grid->RecCnt < $seguimiento_tramites_grid->StopRec) {
	$seguimiento_tramites_grid->RecCnt++;
	if (intval($seguimiento_tramites_grid->RecCnt) >= intval($seguimiento_tramites_grid->StartRec)) {
		$seguimiento_tramites_grid->RowCnt++;
		if ($seguimiento_tramites->CurrentAction == "gridadd" || $seguimiento_tramites->CurrentAction == "gridedit" || $seguimiento_tramites->CurrentAction == "F") {
			$seguimiento_tramites_grid->RowIndex++;
			$objForm->Index = $seguimiento_tramites_grid->RowIndex;
			if ($objForm->HasValue($seguimiento_tramites_grid->FormActionName))
				$seguimiento_tramites_grid->RowAction = strval($objForm->GetValue($seguimiento_tramites_grid->FormActionName));
			elseif ($seguimiento_tramites->CurrentAction == "gridadd")
				$seguimiento_tramites_grid->RowAction = "insert";
			else
				$seguimiento_tramites_grid->RowAction = "";
		}

		// Set up key count
		$seguimiento_tramites_grid->KeyCount = $seguimiento_tramites_grid->RowIndex;

		// Init row class and style
		$seguimiento_tramites->ResetAttrs();
		$seguimiento_tramites->CssClass = "";
		if ($seguimiento_tramites->CurrentAction == "gridadd") {
			if ($seguimiento_tramites->CurrentMode == "copy") {
				$seguimiento_tramites_grid->LoadRowValues($seguimiento_tramites_grid->Recordset); // Load row values
				$seguimiento_tramites_grid->SetRecordKey($seguimiento_tramites_grid->RowOldKey, $seguimiento_tramites_grid->Recordset); // Set old record key
			} else {
				$seguimiento_tramites_grid->LoadDefaultValues(); // Load default values
				$seguimiento_tramites_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$seguimiento_tramites_grid->LoadRowValues($seguimiento_tramites_grid->Recordset); // Load row values
		}
		$seguimiento_tramites->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($seguimiento_tramites->CurrentAction == "gridadd") // Grid add
			$seguimiento_tramites->RowType = EW_ROWTYPE_ADD; // Render add
		if ($seguimiento_tramites->CurrentAction == "gridadd" && $seguimiento_tramites->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$seguimiento_tramites_grid->RestoreCurrentRowFormValues($seguimiento_tramites_grid->RowIndex); // Restore form values
		if ($seguimiento_tramites->CurrentAction == "gridedit") { // Grid edit
			if ($seguimiento_tramites->EventCancelled) {
				$seguimiento_tramites_grid->RestoreCurrentRowFormValues($seguimiento_tramites_grid->RowIndex); // Restore form values
			}
			if ($seguimiento_tramites_grid->RowAction == "insert")
				$seguimiento_tramites->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$seguimiento_tramites->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($seguimiento_tramites->CurrentAction == "gridedit" && ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT || $seguimiento_tramites->RowType == EW_ROWTYPE_ADD) && $seguimiento_tramites->EventCancelled) // Update failed
			$seguimiento_tramites_grid->RestoreCurrentRowFormValues($seguimiento_tramites_grid->RowIndex); // Restore form values
		if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) // Edit row
			$seguimiento_tramites_grid->EditRowCnt++;
		if ($seguimiento_tramites->CurrentAction == "F") // Confirm row
			$seguimiento_tramites_grid->RestoreCurrentRowFormValues($seguimiento_tramites_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$seguimiento_tramites->RowAttrs = array_merge($seguimiento_tramites->RowAttrs, array('data-rowindex'=>$seguimiento_tramites_grid->RowCnt, 'id'=>'r' . $seguimiento_tramites_grid->RowCnt . '_seguimiento_tramites', 'data-rowtype'=>$seguimiento_tramites->RowType));

		// Render row
		$seguimiento_tramites_grid->RenderRow();

		// Render list options
		$seguimiento_tramites_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($seguimiento_tramites_grid->RowAction <> "delete" && $seguimiento_tramites_grid->RowAction <> "insertdelete" && !($seguimiento_tramites_grid->RowAction == "insert" && $seguimiento_tramites->CurrentAction == "F" && $seguimiento_tramites_grid->EmptyRow())) {
?>
	<tr<?php echo $seguimiento_tramites->RowAttributes() ?>>
<?php

// Render list options (body, left)
$seguimiento_tramites_grid->ListOptions->Render("body", "left", $seguimiento_tramites_grid->RowCnt);
?>
	<?php if ($seguimiento_tramites->id_tramite->Visible) { // id_tramite ?>
		<td data-name="id_tramite"<?php echo $seguimiento_tramites->id_tramite->CellAttributes() ?>>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($seguimiento_tramites->id_tramite->getSessionValue() <> "") { ?>
<span id="el<?php echo $seguimiento_tramites_grid->RowCnt ?>_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<span<?php echo $seguimiento_tramites->id_tramite->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->id_tramite->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $seguimiento_tramites_grid->RowCnt ?>_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<?php $seguimiento_tramites->id_tramite->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$seguimiento_tramites->id_tramite->EditAttrs["onchange"]; ?>
<select data-field="x_id_tramite" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite"<?php echo $seguimiento_tramites->id_tramite->EditAttributes() ?>>
<?php
if (is_array($seguimiento_tramites->id_tramite->EditValue)) {
	$arwrk = $seguimiento_tramites->id_tramite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($seguimiento_tramites->id_tramite->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$seguimiento_tramites->id_tramite) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$seguimiento_tramites->id_tramite) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $seguimiento_tramites->id_tramite->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha` AS `Disp2Fld`, `Titulo` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tramites`";
 $sWhereWrk = "";
 $lookuptblfilter = "`estado`<>'F'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $seguimiento_tramites->Lookup_Selecting($seguimiento_tramites->id_tramite, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `fecha` ASC";
?>
<input type="hidden" name="s_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" id="s_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
<input type="hidden" name="ln_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" id="ln_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_tramite" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->OldValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $seguimiento_tramites_grid->RowCnt ?>_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<span<?php echo $seguimiento_tramites->id_tramite->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->id_tramite->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_tramite" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->CurrentValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $seguimiento_tramites->id_tramite->ViewAttributes() ?>>
<?php echo $seguimiento_tramites->id_tramite->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_tramite" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->FormValue) ?>">
<input type="hidden" data-field="x_id_tramite" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->OldValue) ?>">
<?php } ?>
<a id="<?php echo $seguimiento_tramites_grid->PageObjName . "_row_" . $seguimiento_tramites_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($seguimiento_tramites->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $seguimiento_tramites->fecha->CellAttributes() ?>>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_fecha" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($seguimiento_tramites->fecha->OldValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $seguimiento_tramites->fecha->ViewAttributes() ?>>
<?php echo $seguimiento_tramites->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_fecha" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($seguimiento_tramites->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_fecha" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($seguimiento_tramites->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->hora->Visible) { // hora ?>
		<td data-name="hora"<?php echo $seguimiento_tramites->hora->CellAttributes() ?>>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_hora" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_hora" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_hora" value="<?php echo ew_HtmlEncode($seguimiento_tramites->hora->OldValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $seguimiento_tramites->hora->ViewAttributes() ?>>
<?php echo $seguimiento_tramites->hora->ListViewValue() ?></span>
<input type="hidden" data-field="x_hora" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_hora" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_hora" value="<?php echo ew_HtmlEncode($seguimiento_tramites->hora->FormValue) ?>">
<input type="hidden" data-field="x_hora" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_hora" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_hora" value="<?php echo ew_HtmlEncode($seguimiento_tramites->hora->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->titulo->Visible) { // titulo ?>
		<td data-name="titulo"<?php echo $seguimiento_tramites->titulo->CellAttributes() ?>>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $seguimiento_tramites_grid->RowCnt ?>_seguimiento_tramites_titulo" class="form-group seguimiento_tramites_titulo">
<input type="text" data-field="x_titulo" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->titulo->EditValue ?>"<?php echo $seguimiento_tramites->titulo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_titulo" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->OldValue) ?>">
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $seguimiento_tramites_grid->RowCnt ?>_seguimiento_tramites_titulo" class="form-group seguimiento_tramites_titulo">
<input type="text" data-field="x_titulo" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->titulo->EditValue ?>"<?php echo $seguimiento_tramites->titulo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $seguimiento_tramites->titulo->ViewAttributes() ?>>
<?php echo $seguimiento_tramites->titulo->ListViewValue() ?></span>
<input type="hidden" data-field="x_titulo" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->FormValue) ?>">
<input type="hidden" data-field="x_titulo" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->archivo->Visible) { // archivo ?>
		<td data-name="archivo"<?php echo $seguimiento_tramites->archivo->CellAttributes() ?>>
<?php if ($seguimiento_tramites_grid->RowAction == "insert") { // Add record ?>
<span id="el<?php echo $seguimiento_tramites_grid->RowCnt ?>_seguimiento_tramites_archivo" class="form-group seguimiento_tramites_archivo">
<div id="fd_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo">
<span title="<?php echo $seguimiento_tramites->archivo->FldTitle() ? $seguimiento_tramites->archivo->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($seguimiento_tramites->archivo->ReadOnly || $seguimiento_tramites->archivo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_archivo" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" multiple="multiple">
</span>
<input type="hidden" name="fn_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fn_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fa_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="0">
<input type="hidden" name="fs_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fs_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="255">
<input type="hidden" name="fx_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fx_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fm_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fc_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileCount ?>">
</div>
<table id="ft_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_archivo" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->archivo->OldValue) ?>">
<?php } elseif ($seguimiento_tramites->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $seguimiento_tramites->archivo->ViewAttributes() ?>>
<ul class="list-inline"><?php
$Files = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $seguimiento_tramites->archivo->Upload->DbValue);
$HrefValue = $seguimiento_tramites->archivo->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$seguimiento_tramites->archivo->ViewValue = $Files[$i];
$seguimiento_tramites->archivo->HrefValue = str_replace("%u", ew_HtmlEncode(ew_UploadPathEx(FALSE, $seguimiento_tramites->archivo->UploadPath) . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode(ew_UploadPathEx(FALSE, $seguimiento_tramites->archivo->UploadPath) . $Files[$i]), $seguimiento_tramites->archivo->ListViewValue());
?>
<li>
<?php if ($seguimiento_tramites->archivo->LinkAttributes() <> "") { ?>
<?php if (!empty($seguimiento_tramites->archivo->Upload->DbValue)) { ?>
<?php echo $seguimiento_tramites->archivo->ListViewValue() ?>
<?php } elseif (!in_array($seguimiento_tramites->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($seguimiento_tramites->archivo->Upload->DbValue)) { ?>
<?php echo $seguimiento_tramites->archivo->ListViewValue() ?>
<?php } elseif (!in_array($seguimiento_tramites->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</li>
<?php
}
}
?></ul>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $seguimiento_tramites_grid->RowCnt ?>_seguimiento_tramites_archivo" class="form-group seguimiento_tramites_archivo">
<div id="fd_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo">
<span title="<?php echo $seguimiento_tramites->archivo->FldTitle() ? $seguimiento_tramites->archivo->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($seguimiento_tramites->archivo->ReadOnly || $seguimiento_tramites->archivo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_archivo" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" multiple="multiple">
</span>
<input type="hidden" name="fn_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fn_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fa_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fa_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fs_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="255">
<input type="hidden" name="fx_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fx_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fm_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fc_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileCount ?>">
</div>
<table id="ft_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$seguimiento_tramites_grid->ListOptions->Render("body", "right", $seguimiento_tramites_grid->RowCnt);
?>
	</tr>
<?php if ($seguimiento_tramites->RowType == EW_ROWTYPE_ADD || $seguimiento_tramites->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fseguimiento_tramitesgrid.UpdateOpts(<?php echo $seguimiento_tramites_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($seguimiento_tramites->CurrentAction <> "gridadd" || $seguimiento_tramites->CurrentMode == "copy")
		if (!$seguimiento_tramites_grid->Recordset->EOF) $seguimiento_tramites_grid->Recordset->MoveNext();
}
?>
<?php
	if ($seguimiento_tramites->CurrentMode == "add" || $seguimiento_tramites->CurrentMode == "copy" || $seguimiento_tramites->CurrentMode == "edit") {
		$seguimiento_tramites_grid->RowIndex = '$rowindex$';
		$seguimiento_tramites_grid->LoadDefaultValues();

		// Set row properties
		$seguimiento_tramites->ResetAttrs();
		$seguimiento_tramites->RowAttrs = array_merge($seguimiento_tramites->RowAttrs, array('data-rowindex'=>$seguimiento_tramites_grid->RowIndex, 'id'=>'r0_seguimiento_tramites', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($seguimiento_tramites->RowAttrs["class"], "ewTemplate");
		$seguimiento_tramites->RowType = EW_ROWTYPE_ADD;

		// Render row
		$seguimiento_tramites_grid->RenderRow();

		// Render list options
		$seguimiento_tramites_grid->RenderListOptions();
		$seguimiento_tramites_grid->StartRowCnt = 0;
?>
	<tr<?php echo $seguimiento_tramites->RowAttributes() ?>>
<?php

// Render list options (body, left)
$seguimiento_tramites_grid->ListOptions->Render("body", "left", $seguimiento_tramites_grid->RowIndex);
?>
	<?php if ($seguimiento_tramites->id_tramite->Visible) { // id_tramite ?>
		<td data-name="id_tramite">
<?php if ($seguimiento_tramites->CurrentAction <> "F") { ?>
<?php if ($seguimiento_tramites->id_tramite->getSessionValue() <> "") { ?>
<span id="el$rowindex$_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<span<?php echo $seguimiento_tramites->id_tramite->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->id_tramite->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<?php $seguimiento_tramites->id_tramite->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$seguimiento_tramites->id_tramite->EditAttrs["onchange"]; ?>
<select data-field="x_id_tramite" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite"<?php echo $seguimiento_tramites->id_tramite->EditAttributes() ?>>
<?php
if (is_array($seguimiento_tramites->id_tramite->EditValue)) {
	$arwrk = $seguimiento_tramites->id_tramite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($seguimiento_tramites->id_tramite->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$seguimiento_tramites->id_tramite) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$seguimiento_tramites->id_tramite) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $seguimiento_tramites->id_tramite->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codigo`, `codigo` AS `DispFld`, `fecha` AS `Disp2Fld`, `Titulo` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tramites`";
 $sWhereWrk = "";
 $lookuptblfilter = "`estado`<>'F'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $seguimiento_tramites->Lookup_Selecting($seguimiento_tramites->id_tramite, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `fecha` ASC";
?>
<input type="hidden" name="s_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" id="s_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codigo` = {filter_value}"); ?>&amp;t0=3">
<input type="hidden" name="ln_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" id="ln_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_seguimiento_tramites_id_tramite" class="form-group seguimiento_tramites_id_tramite">
<span<?php echo $seguimiento_tramites->id_tramite->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->id_tramite->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_tramite" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_tramite" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_id_tramite" value="<?php echo ew_HtmlEncode($seguimiento_tramites->id_tramite->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->fecha->Visible) { // fecha ?>
		<td data-name="fecha">
<?php if ($seguimiento_tramites->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_seguimiento_tramites_fecha" class="form-group seguimiento_tramites_fecha">
<span<?php echo $seguimiento_tramites->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_fecha" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($seguimiento_tramites->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_fecha" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($seguimiento_tramites->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->hora->Visible) { // hora ?>
		<td data-name="hora">
<?php if ($seguimiento_tramites->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_seguimiento_tramites_hora" class="form-group seguimiento_tramites_hora">
<span<?php echo $seguimiento_tramites->hora->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->hora->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_hora" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_hora" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_hora" value="<?php echo ew_HtmlEncode($seguimiento_tramites->hora->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_hora" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_hora" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_hora" value="<?php echo ew_HtmlEncode($seguimiento_tramites->hora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->titulo->Visible) { // titulo ?>
		<td data-name="titulo">
<?php if ($seguimiento_tramites->CurrentAction <> "F") { ?>
<span id="el$rowindex$_seguimiento_tramites_titulo" class="form-group seguimiento_tramites_titulo">
<input type="text" data-field="x_titulo" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->PlaceHolder) ?>" value="<?php echo $seguimiento_tramites->titulo->EditValue ?>"<?php echo $seguimiento_tramites->titulo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_seguimiento_tramites_titulo" class="form-group seguimiento_tramites_titulo">
<span<?php echo $seguimiento_tramites->titulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $seguimiento_tramites->titulo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_titulo" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_titulo" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->titulo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($seguimiento_tramites->archivo->Visible) { // archivo ?>
		<td data-name="archivo">
<span id="el$rowindex$_seguimiento_tramites_archivo" class="form-group seguimiento_tramites_archivo">
<div id="fd_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo">
<span title="<?php echo $seguimiento_tramites->archivo->FldTitle() ? $seguimiento_tramites->archivo->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($seguimiento_tramites->archivo->ReadOnly || $seguimiento_tramites->archivo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_archivo" name="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id="x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" multiple="multiple">
</span>
<input type="hidden" name="fn_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fn_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fa_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="0">
<input type="hidden" name="fs_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fs_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="255">
<input type="hidden" name="fx_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fx_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fm_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id= "fc_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo $seguimiento_tramites->archivo->UploadMaxFileCount ?>">
</div>
<table id="ft_x<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_archivo" name="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" id="o<?php echo $seguimiento_tramites_grid->RowIndex ?>_archivo" value="<?php echo ew_HtmlEncode($seguimiento_tramites->archivo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$seguimiento_tramites_grid->ListOptions->Render("body", "right", $seguimiento_tramites_grid->RowCnt);
?>
<script type="text/javascript">
fseguimiento_tramitesgrid.UpdateOpts(<?php echo $seguimiento_tramites_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($seguimiento_tramites->CurrentMode == "add" || $seguimiento_tramites->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $seguimiento_tramites_grid->FormKeyCountName ?>" id="<?php echo $seguimiento_tramites_grid->FormKeyCountName ?>" value="<?php echo $seguimiento_tramites_grid->KeyCount ?>">
<?php echo $seguimiento_tramites_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($seguimiento_tramites->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $seguimiento_tramites_grid->FormKeyCountName ?>" id="<?php echo $seguimiento_tramites_grid->FormKeyCountName ?>" value="<?php echo $seguimiento_tramites_grid->KeyCount ?>">
<?php echo $seguimiento_tramites_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($seguimiento_tramites->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fseguimiento_tramitesgrid">
</div>
<?php

// Close recordset
if ($seguimiento_tramites_grid->Recordset)
	$seguimiento_tramites_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($seguimiento_tramites_grid->TotalRecs == 0 && $seguimiento_tramites->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($seguimiento_tramites_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($seguimiento_tramites->Export == "") { ?>
<script type="text/javascript">
fseguimiento_tramitesgrid.Init();
</script>
<?php } ?>
<?php
$seguimiento_tramites_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$seguimiento_tramites_grid->Page_Terminate();
?>
