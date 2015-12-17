<?php include_once $EW_RELATIVE_PATH . "cciag_montosinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_sociosinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($socios_cuotas_grid)) $socios_cuotas_grid = new csocios_cuotas_grid();

// Page init
$socios_cuotas_grid->Page_Init();

// Page main
$socios_cuotas_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_cuotas_grid->Page_Render();
?>
<?php if ($socios_cuotas->Export == "") { ?>
<script type="text/javascript">

// Page object
var socios_cuotas_grid = new ew_Page("socios_cuotas_grid");
socios_cuotas_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = socios_cuotas_grid.PageID; // For backward compatibility

// Form object
var fsocios_cuotasgrid = new ew_Form("fsocios_cuotasgrid");
fsocios_cuotasgrid.FormKeyCountName = '<?php echo $socios_cuotas_grid->FormKeyCountName ?>';

// Validate form
fsocios_cuotasgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios_cuotas->fecha->FldErrMsg()) ?>");

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
fsocios_cuotasgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_socio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_montos", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha", false)) return false;
	return true;
}

// Form_CustomValidate event
fsocios_cuotasgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsocios_cuotasgrid.ValidateRequired = true;
<?php } else { ?>
fsocios_cuotasgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsocios_cuotasgrid.Lists["x_id_socio"] = {"LinkField":"x_socio_nro","Ajax":true,"AutoFill":false,"DisplayFields":["x_socio_nro","x_propietario","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsocios_cuotasgrid.Lists["x_id_montos"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","x_importe","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($socios_cuotas->CurrentAction == "gridadd") {
	if ($socios_cuotas->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$socios_cuotas_grid->TotalRecs = $socios_cuotas->SelectRecordCount();
			$socios_cuotas_grid->Recordset = $socios_cuotas_grid->LoadRecordset($socios_cuotas_grid->StartRec-1, $socios_cuotas_grid->DisplayRecs);
		} else {
			if ($socios_cuotas_grid->Recordset = $socios_cuotas_grid->LoadRecordset())
				$socios_cuotas_grid->TotalRecs = $socios_cuotas_grid->Recordset->RecordCount();
		}
		$socios_cuotas_grid->StartRec = 1;
		$socios_cuotas_grid->DisplayRecs = $socios_cuotas_grid->TotalRecs;
	} else {
		$socios_cuotas->CurrentFilter = "0=1";
		$socios_cuotas_grid->StartRec = 1;
		$socios_cuotas_grid->DisplayRecs = $socios_cuotas->GridAddRowCount;
	}
	$socios_cuotas_grid->TotalRecs = $socios_cuotas_grid->DisplayRecs;
	$socios_cuotas_grid->StopRec = $socios_cuotas_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$socios_cuotas_grid->TotalRecs = $socios_cuotas->SelectRecordCount();
	} else {
		if ($socios_cuotas_grid->Recordset = $socios_cuotas_grid->LoadRecordset())
			$socios_cuotas_grid->TotalRecs = $socios_cuotas_grid->Recordset->RecordCount();
	}
	$socios_cuotas_grid->StartRec = 1;
	$socios_cuotas_grid->DisplayRecs = $socios_cuotas_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$socios_cuotas_grid->Recordset = $socios_cuotas_grid->LoadRecordset($socios_cuotas_grid->StartRec-1, $socios_cuotas_grid->DisplayRecs);

	// Set no record found message
	if ($socios_cuotas->CurrentAction == "" && $socios_cuotas_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$socios_cuotas_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($socios_cuotas_grid->SearchWhere == "0=101")
			$socios_cuotas_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$socios_cuotas_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$socios_cuotas_grid->RenderOtherOptions();
?>
<?php $socios_cuotas_grid->ShowPageHeader(); ?>
<?php
$socios_cuotas_grid->ShowMessage();
?>
<?php if ($socios_cuotas_grid->TotalRecs > 0 || $socios_cuotas->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fsocios_cuotasgrid" class="ewForm form-inline">
<?php if ($socios_cuotas_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($socios_cuotas_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_socios_cuotas" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_socios_cuotasgrid" class="table ewTable">
<?php echo $socios_cuotas->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$socios_cuotas_grid->RenderListOptions();

// Render list options (header, left)
$socios_cuotas_grid->ListOptions->Render("header", "left");
?>
<?php if ($socios_cuotas->id_socio->Visible) { // id_socio ?>
	<?php if ($socios_cuotas->SortUrl($socios_cuotas->id_socio) == "") { ?>
		<th data-name="id_socio"><div id="elh_socios_cuotas_id_socio" class="socios_cuotas_id_socio"><div class="ewTableHeaderCaption"><?php echo $socios_cuotas->id_socio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_socio"><div><div id="elh_socios_cuotas_id_socio" class="socios_cuotas_id_socio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios_cuotas->id_socio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios_cuotas->id_socio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios_cuotas->id_socio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios_cuotas->id_montos->Visible) { // id_montos ?>
	<?php if ($socios_cuotas->SortUrl($socios_cuotas->id_montos) == "") { ?>
		<th data-name="id_montos"><div id="elh_socios_cuotas_id_montos" class="socios_cuotas_id_montos"><div class="ewTableHeaderCaption"><?php echo $socios_cuotas->id_montos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_montos"><div><div id="elh_socios_cuotas_id_montos" class="socios_cuotas_id_montos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios_cuotas->id_montos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios_cuotas->id_montos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios_cuotas->id_montos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios_cuotas->fecha->Visible) { // fecha ?>
	<?php if ($socios_cuotas->SortUrl($socios_cuotas->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_socios_cuotas_fecha" class="socios_cuotas_fecha"><div class="ewTableHeaderCaption"><?php echo $socios_cuotas->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_socios_cuotas_fecha" class="socios_cuotas_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios_cuotas->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios_cuotas->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios_cuotas->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$socios_cuotas_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$socios_cuotas_grid->StartRec = 1;
$socios_cuotas_grid->StopRec = $socios_cuotas_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($socios_cuotas_grid->FormKeyCountName) && ($socios_cuotas->CurrentAction == "gridadd" || $socios_cuotas->CurrentAction == "gridedit" || $socios_cuotas->CurrentAction == "F")) {
		$socios_cuotas_grid->KeyCount = $objForm->GetValue($socios_cuotas_grid->FormKeyCountName);
		$socios_cuotas_grid->StopRec = $socios_cuotas_grid->StartRec + $socios_cuotas_grid->KeyCount - 1;
	}
}
$socios_cuotas_grid->RecCnt = $socios_cuotas_grid->StartRec - 1;
if ($socios_cuotas_grid->Recordset && !$socios_cuotas_grid->Recordset->EOF) {
	$socios_cuotas_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $socios_cuotas_grid->StartRec > 1)
		$socios_cuotas_grid->Recordset->Move($socios_cuotas_grid->StartRec - 1);
} elseif (!$socios_cuotas->AllowAddDeleteRow && $socios_cuotas_grid->StopRec == 0) {
	$socios_cuotas_grid->StopRec = $socios_cuotas->GridAddRowCount;
}

// Initialize aggregate
$socios_cuotas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$socios_cuotas->ResetAttrs();
$socios_cuotas_grid->RenderRow();
if ($socios_cuotas->CurrentAction == "gridadd")
	$socios_cuotas_grid->RowIndex = 0;
if ($socios_cuotas->CurrentAction == "gridedit")
	$socios_cuotas_grid->RowIndex = 0;
while ($socios_cuotas_grid->RecCnt < $socios_cuotas_grid->StopRec) {
	$socios_cuotas_grid->RecCnt++;
	if (intval($socios_cuotas_grid->RecCnt) >= intval($socios_cuotas_grid->StartRec)) {
		$socios_cuotas_grid->RowCnt++;
		if ($socios_cuotas->CurrentAction == "gridadd" || $socios_cuotas->CurrentAction == "gridedit" || $socios_cuotas->CurrentAction == "F") {
			$socios_cuotas_grid->RowIndex++;
			$objForm->Index = $socios_cuotas_grid->RowIndex;
			if ($objForm->HasValue($socios_cuotas_grid->FormActionName))
				$socios_cuotas_grid->RowAction = strval($objForm->GetValue($socios_cuotas_grid->FormActionName));
			elseif ($socios_cuotas->CurrentAction == "gridadd")
				$socios_cuotas_grid->RowAction = "insert";
			else
				$socios_cuotas_grid->RowAction = "";
		}

		// Set up key count
		$socios_cuotas_grid->KeyCount = $socios_cuotas_grid->RowIndex;

		// Init row class and style
		$socios_cuotas->ResetAttrs();
		$socios_cuotas->CssClass = "";
		if ($socios_cuotas->CurrentAction == "gridadd") {
			if ($socios_cuotas->CurrentMode == "copy") {
				$socios_cuotas_grid->LoadRowValues($socios_cuotas_grid->Recordset); // Load row values
				$socios_cuotas_grid->SetRecordKey($socios_cuotas_grid->RowOldKey, $socios_cuotas_grid->Recordset); // Set old record key
			} else {
				$socios_cuotas_grid->LoadDefaultValues(); // Load default values
				$socios_cuotas_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$socios_cuotas_grid->LoadRowValues($socios_cuotas_grid->Recordset); // Load row values
		}
		$socios_cuotas->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($socios_cuotas->CurrentAction == "gridadd") // Grid add
			$socios_cuotas->RowType = EW_ROWTYPE_ADD; // Render add
		if ($socios_cuotas->CurrentAction == "gridadd" && $socios_cuotas->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$socios_cuotas_grid->RestoreCurrentRowFormValues($socios_cuotas_grid->RowIndex); // Restore form values
		if ($socios_cuotas->CurrentAction == "gridedit") { // Grid edit
			if ($socios_cuotas->EventCancelled) {
				$socios_cuotas_grid->RestoreCurrentRowFormValues($socios_cuotas_grid->RowIndex); // Restore form values
			}
			if ($socios_cuotas_grid->RowAction == "insert")
				$socios_cuotas->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$socios_cuotas->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($socios_cuotas->CurrentAction == "gridedit" && ($socios_cuotas->RowType == EW_ROWTYPE_EDIT || $socios_cuotas->RowType == EW_ROWTYPE_ADD) && $socios_cuotas->EventCancelled) // Update failed
			$socios_cuotas_grid->RestoreCurrentRowFormValues($socios_cuotas_grid->RowIndex); // Restore form values
		if ($socios_cuotas->RowType == EW_ROWTYPE_EDIT) // Edit row
			$socios_cuotas_grid->EditRowCnt++;
		if ($socios_cuotas->CurrentAction == "F") // Confirm row
			$socios_cuotas_grid->RestoreCurrentRowFormValues($socios_cuotas_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$socios_cuotas->RowAttrs = array_merge($socios_cuotas->RowAttrs, array('data-rowindex'=>$socios_cuotas_grid->RowCnt, 'id'=>'r' . $socios_cuotas_grid->RowCnt . '_socios_cuotas', 'data-rowtype'=>$socios_cuotas->RowType));

		// Render row
		$socios_cuotas_grid->RenderRow();

		// Render list options
		$socios_cuotas_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($socios_cuotas_grid->RowAction <> "delete" && $socios_cuotas_grid->RowAction <> "insertdelete" && !($socios_cuotas_grid->RowAction == "insert" && $socios_cuotas->CurrentAction == "F" && $socios_cuotas_grid->EmptyRow())) {
?>
	<tr<?php echo $socios_cuotas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_cuotas_grid->ListOptions->Render("body", "left", $socios_cuotas_grid->RowCnt);
?>
	<?php if ($socios_cuotas->id_socio->Visible) { // id_socio ?>
		<td data-name="id_socio"<?php echo $socios_cuotas->id_socio->CellAttributes() ?>>
<?php if ($socios_cuotas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($socios_cuotas->id_socio->getSessionValue() <> "") { ?>
<span id="el<?php echo $socios_cuotas_grid->RowCnt ?>_socios_cuotas_id_socio" class="form-group socios_cuotas_id_socio">
<span<?php echo $socios_cuotas->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_cuotas->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_cuotas->id_socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $socios_cuotas_grid->RowCnt ?>_socios_cuotas_id_socio" class="form-group socios_cuotas_id_socio">
<select data-field="x_id_socio" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio"<?php echo $socios_cuotas->id_socio->EditAttributes() ?>>
<?php
if (is_array($socios_cuotas->id_socio->EditValue)) {
	$arwrk = $socios_cuotas->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_cuotas->id_socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios_cuotas->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_cuotas->id_socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `propietario` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
 $sWhereWrk = "";
 if (!$GLOBALS["socios_cuotas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $socios_cuotas->Lookup_Selecting($socios_cuotas->id_socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" id="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_socio" name="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" id="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_cuotas->id_socio->OldValue) ?>">
<?php } ?>
<?php if ($socios_cuotas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($socios_cuotas->id_socio->getSessionValue() <> "") { ?>
<span id="el<?php echo $socios_cuotas_grid->RowCnt ?>_socios_cuotas_id_socio" class="form-group socios_cuotas_id_socio">
<span<?php echo $socios_cuotas->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_cuotas->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_cuotas->id_socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $socios_cuotas_grid->RowCnt ?>_socios_cuotas_id_socio" class="form-group socios_cuotas_id_socio">
<select data-field="x_id_socio" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio"<?php echo $socios_cuotas->id_socio->EditAttributes() ?>>
<?php
if (is_array($socios_cuotas->id_socio->EditValue)) {
	$arwrk = $socios_cuotas->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_cuotas->id_socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios_cuotas->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_cuotas->id_socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `propietario` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
 $sWhereWrk = "";
 if (!$GLOBALS["socios_cuotas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $socios_cuotas->Lookup_Selecting($socios_cuotas->id_socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" id="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($socios_cuotas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios_cuotas->id_socio->ViewAttributes() ?>>
<?php echo $socios_cuotas->id_socio->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_socio" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_cuotas->id_socio->FormValue) ?>">
<input type="hidden" data-field="x_id_socio" name="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" id="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_cuotas->id_socio->OldValue) ?>">
<?php } ?>
<a id="<?php echo $socios_cuotas_grid->PageObjName . "_row_" . $socios_cuotas_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($socios_cuotas->id_montos->Visible) { // id_montos ?>
		<td data-name="id_montos"<?php echo $socios_cuotas->id_montos->CellAttributes() ?>>
<?php if ($socios_cuotas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($socios_cuotas->id_montos->getSessionValue() <> "") { ?>
<span id="el<?php echo $socios_cuotas_grid->RowCnt ?>_socios_cuotas_id_montos" class="form-group socios_cuotas_id_montos">
<span<?php echo $socios_cuotas->id_montos->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_cuotas->id_montos->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="<?php echo ew_HtmlEncode($socios_cuotas->id_montos->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $socios_cuotas_grid->RowCnt ?>_socios_cuotas_id_montos" class="form-group socios_cuotas_id_montos">
<select data-field="x_id_montos" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos"<?php echo $socios_cuotas->id_montos->EditAttributes() ?>>
<?php
if (is_array($socios_cuotas->id_montos->EditValue)) {
	$arwrk = $socios_cuotas->id_montos->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_cuotas->id_montos->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios_cuotas->id_montos) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_cuotas->id_montos->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `descripcion` AS `DispFld`, `importe` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `montos`";
 $sWhereWrk = "";
 if (!$GLOBALS["socios_cuotas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["montos"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $socios_cuotas->Lookup_Selecting($socios_cuotas->id_montos, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" id="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_id_montos" name="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" id="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="<?php echo ew_HtmlEncode($socios_cuotas->id_montos->OldValue) ?>">
<?php } ?>
<?php if ($socios_cuotas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($socios_cuotas->id_montos->getSessionValue() <> "") { ?>
<span id="el<?php echo $socios_cuotas_grid->RowCnt ?>_socios_cuotas_id_montos" class="form-group socios_cuotas_id_montos">
<span<?php echo $socios_cuotas->id_montos->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_cuotas->id_montos->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="<?php echo ew_HtmlEncode($socios_cuotas->id_montos->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $socios_cuotas_grid->RowCnt ?>_socios_cuotas_id_montos" class="form-group socios_cuotas_id_montos">
<select data-field="x_id_montos" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos"<?php echo $socios_cuotas->id_montos->EditAttributes() ?>>
<?php
if (is_array($socios_cuotas->id_montos->EditValue)) {
	$arwrk = $socios_cuotas->id_montos->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_cuotas->id_montos->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios_cuotas->id_montos) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_cuotas->id_montos->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `descripcion` AS `DispFld`, `importe` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `montos`";
 $sWhereWrk = "";
 if (!$GLOBALS["socios_cuotas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["montos"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $socios_cuotas->Lookup_Selecting($socios_cuotas->id_montos, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" id="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($socios_cuotas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios_cuotas->id_montos->ViewAttributes() ?>>
<?php echo $socios_cuotas->id_montos->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_montos" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="<?php echo ew_HtmlEncode($socios_cuotas->id_montos->FormValue) ?>">
<input type="hidden" data-field="x_id_montos" name="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" id="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="<?php echo ew_HtmlEncode($socios_cuotas->id_montos->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios_cuotas->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $socios_cuotas->fecha->CellAttributes() ?>>
<?php if ($socios_cuotas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_cuotas_grid->RowCnt ?>_socios_cuotas_fecha" class="form-group socios_cuotas_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($socios_cuotas->fecha->PlaceHolder) ?>" value="<?php echo $socios_cuotas->fecha->EditValue ?>"<?php echo $socios_cuotas->fecha->EditAttributes() ?>>
<?php if (!$socios_cuotas->fecha->ReadOnly && !$socios_cuotas->fecha->Disabled && @$socios_cuotas->fecha->EditAttrs["readonly"] == "" && @$socios_cuotas->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_cuotasgrid", "x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" id="o<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($socios_cuotas->fecha->OldValue) ?>">
<?php } ?>
<?php if ($socios_cuotas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_cuotas_grid->RowCnt ?>_socios_cuotas_fecha" class="form-group socios_cuotas_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($socios_cuotas->fecha->PlaceHolder) ?>" value="<?php echo $socios_cuotas->fecha->EditValue ?>"<?php echo $socios_cuotas->fecha->EditAttributes() ?>>
<?php if (!$socios_cuotas->fecha->ReadOnly && !$socios_cuotas->fecha->Disabled && @$socios_cuotas->fecha->EditAttrs["readonly"] == "" && @$socios_cuotas->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_cuotasgrid", "x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($socios_cuotas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios_cuotas->fecha->ViewAttributes() ?>>
<?php echo $socios_cuotas->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($socios_cuotas->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" id="o<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($socios_cuotas->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_cuotas_grid->ListOptions->Render("body", "right", $socios_cuotas_grid->RowCnt);
?>
	</tr>
<?php if ($socios_cuotas->RowType == EW_ROWTYPE_ADD || $socios_cuotas->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsocios_cuotasgrid.UpdateOpts(<?php echo $socios_cuotas_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($socios_cuotas->CurrentAction <> "gridadd" || $socios_cuotas->CurrentMode == "copy")
		if (!$socios_cuotas_grid->Recordset->EOF) $socios_cuotas_grid->Recordset->MoveNext();
}
?>
<?php
	if ($socios_cuotas->CurrentMode == "add" || $socios_cuotas->CurrentMode == "copy" || $socios_cuotas->CurrentMode == "edit") {
		$socios_cuotas_grid->RowIndex = '$rowindex$';
		$socios_cuotas_grid->LoadDefaultValues();

		// Set row properties
		$socios_cuotas->ResetAttrs();
		$socios_cuotas->RowAttrs = array_merge($socios_cuotas->RowAttrs, array('data-rowindex'=>$socios_cuotas_grid->RowIndex, 'id'=>'r0_socios_cuotas', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($socios_cuotas->RowAttrs["class"], "ewTemplate");
		$socios_cuotas->RowType = EW_ROWTYPE_ADD;

		// Render row
		$socios_cuotas_grid->RenderRow();

		// Render list options
		$socios_cuotas_grid->RenderListOptions();
		$socios_cuotas_grid->StartRowCnt = 0;
?>
	<tr<?php echo $socios_cuotas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_cuotas_grid->ListOptions->Render("body", "left", $socios_cuotas_grid->RowIndex);
?>
	<?php if ($socios_cuotas->id_socio->Visible) { // id_socio ?>
		<td>
<?php if ($socios_cuotas->CurrentAction <> "F") { ?>
<?php if ($socios_cuotas->id_socio->getSessionValue() <> "") { ?>
<span id="el$rowindex$_socios_cuotas_id_socio" class="form-group socios_cuotas_id_socio">
<span<?php echo $socios_cuotas->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_cuotas->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_cuotas->id_socio->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_socios_cuotas_id_socio" class="form-group socios_cuotas_id_socio">
<select data-field="x_id_socio" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio"<?php echo $socios_cuotas->id_socio->EditAttributes() ?>>
<?php
if (is_array($socios_cuotas->id_socio->EditValue)) {
	$arwrk = $socios_cuotas->id_socio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_cuotas->id_socio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios_cuotas->id_socio) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_cuotas->id_socio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `socio_nro`, `socio_nro` AS `DispFld`, `propietario` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `socios`";
 $sWhereWrk = "";
 if (!$GLOBALS["socios_cuotas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["socios"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $socios_cuotas->Lookup_Selecting($socios_cuotas->id_socio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" id="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`socio_nro` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_socios_cuotas_id_socio" class="form-group socios_cuotas_id_socio">
<span<?php echo $socios_cuotas->id_socio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_cuotas->id_socio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_socio" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_cuotas->id_socio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_socio" name="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" id="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_socio" value="<?php echo ew_HtmlEncode($socios_cuotas->id_socio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios_cuotas->id_montos->Visible) { // id_montos ?>
		<td>
<?php if ($socios_cuotas->CurrentAction <> "F") { ?>
<?php if ($socios_cuotas->id_montos->getSessionValue() <> "") { ?>
<span id="el$rowindex$_socios_cuotas_id_montos" class="form-group socios_cuotas_id_montos">
<span<?php echo $socios_cuotas->id_montos->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_cuotas->id_montos->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="<?php echo ew_HtmlEncode($socios_cuotas->id_montos->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_socios_cuotas_id_montos" class="form-group socios_cuotas_id_montos">
<select data-field="x_id_montos" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos"<?php echo $socios_cuotas->id_montos->EditAttributes() ?>>
<?php
if (is_array($socios_cuotas->id_montos->EditValue)) {
	$arwrk = $socios_cuotas->id_montos->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios_cuotas->id_montos->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$socios_cuotas->id_montos) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $socios_cuotas->id_montos->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id`, `descripcion` AS `DispFld`, `importe` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `montos`";
 $sWhereWrk = "";
 if (!$GLOBALS["socios_cuotas"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["montos"]->AddUserIDFilter($sWhereWrk);

 // Call Lookup selecting
 $socios_cuotas->Lookup_Selecting($socios_cuotas->id_montos, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" id="s_x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_socios_cuotas_id_montos" class="form-group socios_cuotas_id_montos">
<span<?php echo $socios_cuotas->id_montos->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_cuotas->id_montos->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_montos" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="<?php echo ew_HtmlEncode($socios_cuotas->id_montos->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_montos" name="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" id="o<?php echo $socios_cuotas_grid->RowIndex ?>_id_montos" value="<?php echo ew_HtmlEncode($socios_cuotas->id_montos->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios_cuotas->fecha->Visible) { // fecha ?>
		<td>
<?php if ($socios_cuotas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_cuotas_fecha" class="form-group socios_cuotas_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($socios_cuotas->fecha->PlaceHolder) ?>" value="<?php echo $socios_cuotas->fecha->EditValue ?>"<?php echo $socios_cuotas->fecha->EditAttributes() ?>>
<?php if (!$socios_cuotas->fecha->ReadOnly && !$socios_cuotas->fecha->Disabled && @$socios_cuotas->fecha->EditAttrs["readonly"] == "" && @$socios_cuotas->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fsocios_cuotasgrid", "x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_cuotas_fecha" class="form-group socios_cuotas_fecha">
<span<?php echo $socios_cuotas->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios_cuotas->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" id="x<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($socios_cuotas->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" id="o<?php echo $socios_cuotas_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($socios_cuotas->fecha->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_cuotas_grid->ListOptions->Render("body", "right", $socios_cuotas_grid->RowCnt);
?>
<script type="text/javascript">
fsocios_cuotasgrid.UpdateOpts(<?php echo $socios_cuotas_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($socios_cuotas->CurrentMode == "add" || $socios_cuotas->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $socios_cuotas_grid->FormKeyCountName ?>" id="<?php echo $socios_cuotas_grid->FormKeyCountName ?>" value="<?php echo $socios_cuotas_grid->KeyCount ?>">
<?php echo $socios_cuotas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($socios_cuotas->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $socios_cuotas_grid->FormKeyCountName ?>" id="<?php echo $socios_cuotas_grid->FormKeyCountName ?>" value="<?php echo $socios_cuotas_grid->KeyCount ?>">
<?php echo $socios_cuotas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($socios_cuotas->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsocios_cuotasgrid">
</div>
<?php

// Close recordset
if ($socios_cuotas_grid->Recordset)
	$socios_cuotas_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($socios_cuotas_grid->TotalRecs == 0 && $socios_cuotas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($socios_cuotas_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($socios_cuotas->Export == "") { ?>
<script type="text/javascript">
fsocios_cuotasgrid.Init();
</script>
<?php } ?>
<?php
$socios_cuotas_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$socios_cuotas_grid->Page_Terminate();
?>
