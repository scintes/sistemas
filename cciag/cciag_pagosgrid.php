<?php include_once "cciag_deudasinfo.php" ?>
<?php include_once "cciag_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($pagos_grid)) $pagos_grid = new cpagos_grid();

// Page init
$pagos_grid->Page_Init();

// Page main
$pagos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pagos_grid->Page_Render();
?>
<?php if ($pagos->Export == "") { ?>
<script type="text/javascript">

// Page object
var pagos_grid = new ew_Page("pagos_grid");
pagos_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = pagos_grid.PageID; // For backward compatibility

// Form object
var fpagosgrid = new ew_Form("fpagosgrid");
fpagosgrid.FormKeyCountName = '<?php echo $pagos_grid->FormKeyCountName ?>';

// Validate form
fpagosgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($pagos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_monto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pagos->monto->FldErrMsg()) ?>");

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
fpagosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_deuda", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha", false)) return false;
	if (ew_ValueChanged(fobj, infix, "monto", false)) return false;
	return true;
}

// Form_CustomValidate event
fpagosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpagosgrid.ValidateRequired = true;
<?php } else { ?>
fpagosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpagosgrid.Lists["x_id_deuda"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_mes","x_anio","x_monto",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($pagos->CurrentAction == "gridadd") {
	if ($pagos->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$pagos_grid->TotalRecs = $pagos->SelectRecordCount();
			$pagos_grid->Recordset = $pagos_grid->LoadRecordset($pagos_grid->StartRec-1, $pagos_grid->DisplayRecs);
		} else {
			if ($pagos_grid->Recordset = $pagos_grid->LoadRecordset())
				$pagos_grid->TotalRecs = $pagos_grid->Recordset->RecordCount();
		}
		$pagos_grid->StartRec = 1;
		$pagos_grid->DisplayRecs = $pagos_grid->TotalRecs;
	} else {
		$pagos->CurrentFilter = "0=1";
		$pagos_grid->StartRec = 1;
		$pagos_grid->DisplayRecs = $pagos->GridAddRowCount;
	}
	$pagos_grid->TotalRecs = $pagos_grid->DisplayRecs;
	$pagos_grid->StopRec = $pagos_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($pagos_grid->TotalRecs <= 0)
			$pagos_grid->TotalRecs = $pagos->SelectRecordCount();
	} else {
		if (!$pagos_grid->Recordset && ($pagos_grid->Recordset = $pagos_grid->LoadRecordset()))
			$pagos_grid->TotalRecs = $pagos_grid->Recordset->RecordCount();
	}
	$pagos_grid->StartRec = 1;
	$pagos_grid->DisplayRecs = $pagos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$pagos_grid->Recordset = $pagos_grid->LoadRecordset($pagos_grid->StartRec-1, $pagos_grid->DisplayRecs);

	// Set no record found message
	if ($pagos->CurrentAction == "" && $pagos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$pagos_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($pagos_grid->SearchWhere == "0=101")
			$pagos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pagos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$pagos_grid->RenderOtherOptions();
?>
<?php $pagos_grid->ShowPageHeader(); ?>
<?php
$pagos_grid->ShowMessage();
?>
<?php if ($pagos_grid->TotalRecs > 0 || $pagos->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fpagosgrid" class="ewForm form-inline">
<?php if ($pagos_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($pagos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_pagos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_pagosgrid" class="table ewTable">
<?php echo $pagos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$pagos->RowType = EW_ROWTYPE_HEADER;

// Render list options
$pagos_grid->RenderListOptions();

// Render list options (header, left)
$pagos_grid->ListOptions->Render("header", "left");
?>
<?php if ($pagos->id->Visible) { // id ?>
	<?php if ($pagos->SortUrl($pagos->id) == "") { ?>
		<th data-name="id"><div id="elh_pagos_id" class="pagos_id"><div class="ewTableHeaderCaption"><?php echo $pagos->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div><div id="elh_pagos_id" class="pagos_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pagos->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pagos->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pagos->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pagos->id_deuda->Visible) { // id_deuda ?>
	<?php if ($pagos->SortUrl($pagos->id_deuda) == "") { ?>
		<th data-name="id_deuda"><div id="elh_pagos_id_deuda" class="pagos_id_deuda"><div class="ewTableHeaderCaption"><?php echo $pagos->id_deuda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_deuda"><div><div id="elh_pagos_id_deuda" class="pagos_id_deuda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pagos->id_deuda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pagos->id_deuda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pagos->id_deuda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pagos->fecha->Visible) { // fecha ?>
	<?php if ($pagos->SortUrl($pagos->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_pagos_fecha" class="pagos_fecha"><div class="ewTableHeaderCaption"><?php echo $pagos->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_pagos_fecha" class="pagos_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pagos->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pagos->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pagos->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pagos->monto->Visible) { // monto ?>
	<?php if ($pagos->SortUrl($pagos->monto) == "") { ?>
		<th data-name="monto"><div id="elh_pagos_monto" class="pagos_monto"><div class="ewTableHeaderCaption"><?php echo $pagos->monto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="monto"><div><div id="elh_pagos_monto" class="pagos_monto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pagos->monto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pagos->monto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pagos->monto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$pagos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$pagos_grid->StartRec = 1;
$pagos_grid->StopRec = $pagos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($pagos_grid->FormKeyCountName) && ($pagos->CurrentAction == "gridadd" || $pagos->CurrentAction == "gridedit" || $pagos->CurrentAction == "F")) {
		$pagos_grid->KeyCount = $objForm->GetValue($pagos_grid->FormKeyCountName);
		$pagos_grid->StopRec = $pagos_grid->StartRec + $pagos_grid->KeyCount - 1;
	}
}
$pagos_grid->RecCnt = $pagos_grid->StartRec - 1;
if ($pagos_grid->Recordset && !$pagos_grid->Recordset->EOF) {
	$pagos_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $pagos_grid->StartRec > 1)
		$pagos_grid->Recordset->Move($pagos_grid->StartRec - 1);
} elseif (!$pagos->AllowAddDeleteRow && $pagos_grid->StopRec == 0) {
	$pagos_grid->StopRec = $pagos->GridAddRowCount;
}

// Initialize aggregate
$pagos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pagos->ResetAttrs();
$pagos_grid->RenderRow();
if ($pagos->CurrentAction == "gridadd")
	$pagos_grid->RowIndex = 0;
if ($pagos->CurrentAction == "gridedit")
	$pagos_grid->RowIndex = 0;
while ($pagos_grid->RecCnt < $pagos_grid->StopRec) {
	$pagos_grid->RecCnt++;
	if (intval($pagos_grid->RecCnt) >= intval($pagos_grid->StartRec)) {
		$pagos_grid->RowCnt++;
		if ($pagos->CurrentAction == "gridadd" || $pagos->CurrentAction == "gridedit" || $pagos->CurrentAction == "F") {
			$pagos_grid->RowIndex++;
			$objForm->Index = $pagos_grid->RowIndex;
			if ($objForm->HasValue($pagos_grid->FormActionName))
				$pagos_grid->RowAction = strval($objForm->GetValue($pagos_grid->FormActionName));
			elseif ($pagos->CurrentAction == "gridadd")
				$pagos_grid->RowAction = "insert";
			else
				$pagos_grid->RowAction = "";
		}

		// Set up key count
		$pagos_grid->KeyCount = $pagos_grid->RowIndex;

		// Init row class and style
		$pagos->ResetAttrs();
		$pagos->CssClass = "";
		if ($pagos->CurrentAction == "gridadd") {
			if ($pagos->CurrentMode == "copy") {
				$pagos_grid->LoadRowValues($pagos_grid->Recordset); // Load row values
				$pagos_grid->SetRecordKey($pagos_grid->RowOldKey, $pagos_grid->Recordset); // Set old record key
			} else {
				$pagos_grid->LoadDefaultValues(); // Load default values
				$pagos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$pagos_grid->LoadRowValues($pagos_grid->Recordset); // Load row values
		}
		$pagos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($pagos->CurrentAction == "gridadd") // Grid add
			$pagos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($pagos->CurrentAction == "gridadd" && $pagos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$pagos_grid->RestoreCurrentRowFormValues($pagos_grid->RowIndex); // Restore form values
		if ($pagos->CurrentAction == "gridedit") { // Grid edit
			if ($pagos->EventCancelled) {
				$pagos_grid->RestoreCurrentRowFormValues($pagos_grid->RowIndex); // Restore form values
			}
			if ($pagos_grid->RowAction == "insert")
				$pagos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$pagos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($pagos->CurrentAction == "gridedit" && ($pagos->RowType == EW_ROWTYPE_EDIT || $pagos->RowType == EW_ROWTYPE_ADD) && $pagos->EventCancelled) // Update failed
			$pagos_grid->RestoreCurrentRowFormValues($pagos_grid->RowIndex); // Restore form values
		if ($pagos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$pagos_grid->EditRowCnt++;
		if ($pagos->CurrentAction == "F") // Confirm row
			$pagos_grid->RestoreCurrentRowFormValues($pagos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$pagos->RowAttrs = array_merge($pagos->RowAttrs, array('data-rowindex'=>$pagos_grid->RowCnt, 'id'=>'r' . $pagos_grid->RowCnt . '_pagos', 'data-rowtype'=>$pagos->RowType));

		// Render row
		$pagos_grid->RenderRow();

		// Render list options
		$pagos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($pagos_grid->RowAction <> "delete" && $pagos_grid->RowAction <> "insertdelete" && !($pagos_grid->RowAction == "insert" && $pagos->CurrentAction == "F" && $pagos_grid->EmptyRow())) {
?>
	<tr<?php echo $pagos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pagos_grid->ListOptions->Render("body", "left", $pagos_grid->RowCnt);
?>
	<?php if ($pagos->id->Visible) { // id ?>
		<td data-name="id"<?php echo $pagos->id->CellAttributes() ?>>
<?php if ($pagos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="o<?php echo $pagos_grid->RowIndex ?>_id" id="o<?php echo $pagos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pagos->id->OldValue) ?>">
<?php } ?>
<?php if ($pagos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pagos_grid->RowCnt ?>_pagos_id" class="form-group pagos_id">
<span<?php echo $pagos->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pagos->id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_id" name="x<?php echo $pagos_grid->RowIndex ?>_id" id="x<?php echo $pagos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pagos->id->CurrentValue) ?>">
<?php } ?>
<?php if ($pagos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pagos->id->ViewAttributes() ?>>
<?php echo $pagos->id->ListViewValue() ?></span>
<input type="hidden" data-field="x_id" name="x<?php echo $pagos_grid->RowIndex ?>_id" id="x<?php echo $pagos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pagos->id->FormValue) ?>">
<input type="hidden" data-field="x_id" name="o<?php echo $pagos_grid->RowIndex ?>_id" id="o<?php echo $pagos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pagos->id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $pagos_grid->PageObjName . "_row_" . $pagos_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pagos->id_deuda->Visible) { // id_deuda ?>
		<td data-name="id_deuda"<?php echo $pagos->id_deuda->CellAttributes() ?>>
<?php if ($pagos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($pagos->id_deuda->getSessionValue() <> "") { ?>
<span id="el<?php echo $pagos_grid->RowCnt ?>_pagos_id_deuda" class="form-group pagos_id_deuda">
<span<?php echo $pagos->id_deuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pagos->id_deuda->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" name="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($pagos->id_deuda->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $pagos_grid->RowCnt ?>_pagos_id_deuda" class="form-group pagos_id_deuda">
<select data-field="x_id_deuda" id="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" name="x<?php echo $pagos_grid->RowIndex ?>_id_deuda"<?php echo $pagos->id_deuda->EditAttributes() ?>>
<?php
if (is_array($pagos->id_deuda->EditValue)) {
	$arwrk = $pagos->id_deuda->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pagos->id_deuda->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$pagos->id_deuda) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$pagos->id_deuda) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $pagos->id_deuda->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `mes` AS `DispFld`, `anio` AS `Disp2Fld`, `monto` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `deudas`";
 $sWhereWrk = "";
 if (!$GLOBALS["pagos"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["deudas"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $pagos->Lookup_Selecting($pagos->id_deuda, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $pagos_grid->RowIndex ?>_id_deuda" id="s_x<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_deuda" name="o<?php echo $pagos_grid->RowIndex ?>_id_deuda" id="o<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($pagos->id_deuda->OldValue) ?>">
<?php } ?>
<?php if ($pagos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($pagos->id_deuda->getSessionValue() <> "") { ?>
<span id="el<?php echo $pagos_grid->RowCnt ?>_pagos_id_deuda" class="form-group pagos_id_deuda">
<span<?php echo $pagos->id_deuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pagos->id_deuda->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" name="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($pagos->id_deuda->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $pagos_grid->RowCnt ?>_pagos_id_deuda" class="form-group pagos_id_deuda">
<select data-field="x_id_deuda" id="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" name="x<?php echo $pagos_grid->RowIndex ?>_id_deuda"<?php echo $pagos->id_deuda->EditAttributes() ?>>
<?php
if (is_array($pagos->id_deuda->EditValue)) {
	$arwrk = $pagos->id_deuda->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pagos->id_deuda->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$pagos->id_deuda) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$pagos->id_deuda) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $pagos->id_deuda->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `mes` AS `DispFld`, `anio` AS `Disp2Fld`, `monto` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `deudas`";
 $sWhereWrk = "";
 if (!$GLOBALS["pagos"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["deudas"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $pagos->Lookup_Selecting($pagos->id_deuda, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $pagos_grid->RowIndex ?>_id_deuda" id="s_x<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($pagos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pagos->id_deuda->ViewAttributes() ?>>
<?php echo $pagos->id_deuda->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_deuda" name="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" id="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($pagos->id_deuda->FormValue) ?>">
<input type="hidden" data-field="x_id_deuda" name="o<?php echo $pagos_grid->RowIndex ?>_id_deuda" id="o<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($pagos->id_deuda->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pagos->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $pagos->fecha->CellAttributes() ?>>
<?php if ($pagos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pagos_grid->RowCnt ?>_pagos_fecha" class="form-group pagos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $pagos_grid->RowIndex ?>_fecha" id="x<?php echo $pagos_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($pagos->fecha->PlaceHolder) ?>" value="<?php echo $pagos->fecha->EditValue ?>"<?php echo $pagos->fecha->EditAttributes() ?>>
<?php if (!$pagos->fecha->ReadOnly && !$pagos->fecha->Disabled && !isset($pagos->fecha->EditAttrs["readonly"]) && !isset($pagos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fpagosgrid", "x<?php echo $pagos_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $pagos_grid->RowIndex ?>_fecha" id="o<?php echo $pagos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($pagos->fecha->OldValue) ?>">
<?php } ?>
<?php if ($pagos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pagos_grid->RowCnt ?>_pagos_fecha" class="form-group pagos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $pagos_grid->RowIndex ?>_fecha" id="x<?php echo $pagos_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($pagos->fecha->PlaceHolder) ?>" value="<?php echo $pagos->fecha->EditValue ?>"<?php echo $pagos->fecha->EditAttributes() ?>>
<?php if (!$pagos->fecha->ReadOnly && !$pagos->fecha->Disabled && !isset($pagos->fecha->EditAttrs["readonly"]) && !isset($pagos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fpagosgrid", "x<?php echo $pagos_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($pagos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pagos->fecha->ViewAttributes() ?>>
<?php echo $pagos->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $pagos_grid->RowIndex ?>_fecha" id="x<?php echo $pagos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($pagos->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $pagos_grid->RowIndex ?>_fecha" id="o<?php echo $pagos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($pagos->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pagos->monto->Visible) { // monto ?>
		<td data-name="monto"<?php echo $pagos->monto->CellAttributes() ?>>
<?php if ($pagos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pagos_grid->RowCnt ?>_pagos_monto" class="form-group pagos_monto">
<input type="text" data-field="x_monto" name="x<?php echo $pagos_grid->RowIndex ?>_monto" id="x<?php echo $pagos_grid->RowIndex ?>_monto" size="30" placeholder="<?php echo ew_HtmlEncode($pagos->monto->PlaceHolder) ?>" value="<?php echo $pagos->monto->EditValue ?>"<?php echo $pagos->monto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_monto" name="o<?php echo $pagos_grid->RowIndex ?>_monto" id="o<?php echo $pagos_grid->RowIndex ?>_monto" value="<?php echo ew_HtmlEncode($pagos->monto->OldValue) ?>">
<?php } ?>
<?php if ($pagos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pagos_grid->RowCnt ?>_pagos_monto" class="form-group pagos_monto">
<input type="text" data-field="x_monto" name="x<?php echo $pagos_grid->RowIndex ?>_monto" id="x<?php echo $pagos_grid->RowIndex ?>_monto" size="30" placeholder="<?php echo ew_HtmlEncode($pagos->monto->PlaceHolder) ?>" value="<?php echo $pagos->monto->EditValue ?>"<?php echo $pagos->monto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pagos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pagos->monto->ViewAttributes() ?>>
<?php echo $pagos->monto->ListViewValue() ?></span>
<input type="hidden" data-field="x_monto" name="x<?php echo $pagos_grid->RowIndex ?>_monto" id="x<?php echo $pagos_grid->RowIndex ?>_monto" value="<?php echo ew_HtmlEncode($pagos->monto->FormValue) ?>">
<input type="hidden" data-field="x_monto" name="o<?php echo $pagos_grid->RowIndex ?>_monto" id="o<?php echo $pagos_grid->RowIndex ?>_monto" value="<?php echo ew_HtmlEncode($pagos->monto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pagos_grid->ListOptions->Render("body", "right", $pagos_grid->RowCnt);
?>
	</tr>
<?php if ($pagos->RowType == EW_ROWTYPE_ADD || $pagos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpagosgrid.UpdateOpts(<?php echo $pagos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($pagos->CurrentAction <> "gridadd" || $pagos->CurrentMode == "copy")
		if (!$pagos_grid->Recordset->EOF) $pagos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($pagos->CurrentMode == "add" || $pagos->CurrentMode == "copy" || $pagos->CurrentMode == "edit") {
		$pagos_grid->RowIndex = '$rowindex$';
		$pagos_grid->LoadDefaultValues();

		// Set row properties
		$pagos->ResetAttrs();
		$pagos->RowAttrs = array_merge($pagos->RowAttrs, array('data-rowindex'=>$pagos_grid->RowIndex, 'id'=>'r0_pagos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($pagos->RowAttrs["class"], "ewTemplate");
		$pagos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pagos_grid->RenderRow();

		// Render list options
		$pagos_grid->RenderListOptions();
		$pagos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $pagos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pagos_grid->ListOptions->Render("body", "left", $pagos_grid->RowIndex);
?>
	<?php if ($pagos->id->Visible) { // id ?>
		<td data-name="id">
<?php if ($pagos->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_pagos_id" class="form-group pagos_id">
<span<?php echo $pagos->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pagos->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id" name="x<?php echo $pagos_grid->RowIndex ?>_id" id="x<?php echo $pagos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pagos->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id" name="o<?php echo $pagos_grid->RowIndex ?>_id" id="o<?php echo $pagos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pagos->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pagos->id_deuda->Visible) { // id_deuda ?>
		<td data-name="id_deuda">
<?php if ($pagos->CurrentAction <> "F") { ?>
<?php if ($pagos->id_deuda->getSessionValue() <> "") { ?>
<span id="el$rowindex$_pagos_id_deuda" class="form-group pagos_id_deuda">
<span<?php echo $pagos->id_deuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pagos->id_deuda->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" name="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($pagos->id_deuda->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_pagos_id_deuda" class="form-group pagos_id_deuda">
<select data-field="x_id_deuda" id="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" name="x<?php echo $pagos_grid->RowIndex ?>_id_deuda"<?php echo $pagos->id_deuda->EditAttributes() ?>>
<?php
if (is_array($pagos->id_deuda->EditValue)) {
	$arwrk = $pagos->id_deuda->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pagos->id_deuda->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$pagos->id_deuda) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$pagos->id_deuda) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $pagos->id_deuda->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `mes` AS `DispFld`, `anio` AS `Disp2Fld`, `monto` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `deudas`";
 $sWhereWrk = "";
 if (!$GLOBALS["pagos"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["deudas"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $pagos->Lookup_Selecting($pagos->id_deuda, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $pagos_grid->RowIndex ?>_id_deuda" id="s_x<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_pagos_id_deuda" class="form-group pagos_id_deuda">
<span<?php echo $pagos->id_deuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pagos->id_deuda->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_deuda" name="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" id="x<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($pagos->id_deuda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_deuda" name="o<?php echo $pagos_grid->RowIndex ?>_id_deuda" id="o<?php echo $pagos_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($pagos->id_deuda->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pagos->fecha->Visible) { // fecha ?>
		<td data-name="fecha">
<?php if ($pagos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pagos_fecha" class="form-group pagos_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $pagos_grid->RowIndex ?>_fecha" id="x<?php echo $pagos_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($pagos->fecha->PlaceHolder) ?>" value="<?php echo $pagos->fecha->EditValue ?>"<?php echo $pagos->fecha->EditAttributes() ?>>
<?php if (!$pagos->fecha->ReadOnly && !$pagos->fecha->Disabled && !isset($pagos->fecha->EditAttrs["readonly"]) && !isset($pagos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fpagosgrid", "x<?php echo $pagos_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_pagos_fecha" class="form-group pagos_fecha">
<span<?php echo $pagos->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pagos->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $pagos_grid->RowIndex ?>_fecha" id="x<?php echo $pagos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($pagos->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $pagos_grid->RowIndex ?>_fecha" id="o<?php echo $pagos_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($pagos->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pagos->monto->Visible) { // monto ?>
		<td data-name="monto">
<?php if ($pagos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pagos_monto" class="form-group pagos_monto">
<input type="text" data-field="x_monto" name="x<?php echo $pagos_grid->RowIndex ?>_monto" id="x<?php echo $pagos_grid->RowIndex ?>_monto" size="30" placeholder="<?php echo ew_HtmlEncode($pagos->monto->PlaceHolder) ?>" value="<?php echo $pagos->monto->EditValue ?>"<?php echo $pagos->monto->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pagos_monto" class="form-group pagos_monto">
<span<?php echo $pagos->monto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pagos->monto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_monto" name="x<?php echo $pagos_grid->RowIndex ?>_monto" id="x<?php echo $pagos_grid->RowIndex ?>_monto" value="<?php echo ew_HtmlEncode($pagos->monto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_monto" name="o<?php echo $pagos_grid->RowIndex ?>_monto" id="o<?php echo $pagos_grid->RowIndex ?>_monto" value="<?php echo ew_HtmlEncode($pagos->monto->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pagos_grid->ListOptions->Render("body", "right", $pagos_grid->RowCnt);
?>
<script type="text/javascript">
fpagosgrid.UpdateOpts(<?php echo $pagos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($pagos->CurrentMode == "add" || $pagos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $pagos_grid->FormKeyCountName ?>" id="<?php echo $pagos_grid->FormKeyCountName ?>" value="<?php echo $pagos_grid->KeyCount ?>">
<?php echo $pagos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pagos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $pagos_grid->FormKeyCountName ?>" id="<?php echo $pagos_grid->FormKeyCountName ?>" value="<?php echo $pagos_grid->KeyCount ?>">
<?php echo $pagos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pagos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fpagosgrid">
</div>
<?php

// Close recordset
if ($pagos_grid->Recordset)
	$pagos_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($pagos_grid->TotalRecs == 0 && $pagos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pagos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($pagos->Export == "") { ?>
<script type="text/javascript">
fpagosgrid.Init();
</script>
<?php } ?>
<?php
$pagos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$pagos_grid->Page_Terminate();
?>
