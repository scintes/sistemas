<?php include_once $EW_RELATIVE_PATH . "cciag_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($socios_grid)) $socios_grid = new csocios_grid();

// Page init
$socios_grid->Page_Init();

// Page main
$socios_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$socios_grid->Page_Render();
?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">

// Page object
var socios_grid = new ew_Page("socios_grid");
socios_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = socios_grid.PageID; // For backward compatibility

// Form object
var fsociosgrid = new ew_Form("fsociosgrid");
fsociosgrid.FormKeyCountName = '<?php echo $socios_grid->FormKeyCountName ?>';

// Validate form
fsociosgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_socio_nro");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->socio_nro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cuit_cuil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->cuit_cuil->FldCaption(), $socios->cuit_cuil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_propietario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->propietario->FldCaption(), $socios->propietario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_comercio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->comercio->FldCaption(), $socios->comercio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion_comercio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->direccion_comercio->FldCaption(), $socios->direccion_comercio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mail");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($socios->mail->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cel");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $socios->cel->FldCaption(), $socios->cel->ReqErrMsg)) ?>");

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
fsociosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "cuit_cuil", false)) return false;
	if (ew_ValueChanged(fobj, infix, "propietario", false)) return false;
	if (ew_ValueChanged(fobj, infix, "comercio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "direccion_comercio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mail", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "activo", false)) return false;
	return true;
}

// Form_CustomValidate event
fsociosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsociosgrid.ValidateRequired = true;
<?php } else { ?>
fsociosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($socios->CurrentAction == "gridadd") {
	if ($socios->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$socios_grid->TotalRecs = $socios->SelectRecordCount();
			$socios_grid->Recordset = $socios_grid->LoadRecordset($socios_grid->StartRec-1, $socios_grid->DisplayRecs);
		} else {
			if ($socios_grid->Recordset = $socios_grid->LoadRecordset())
				$socios_grid->TotalRecs = $socios_grid->Recordset->RecordCount();
		}
		$socios_grid->StartRec = 1;
		$socios_grid->DisplayRecs = $socios_grid->TotalRecs;
	} else {
		$socios->CurrentFilter = "0=1";
		$socios_grid->StartRec = 1;
		$socios_grid->DisplayRecs = $socios->GridAddRowCount;
	}
	$socios_grid->TotalRecs = $socios_grid->DisplayRecs;
	$socios_grid->StopRec = $socios_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$socios_grid->TotalRecs = $socios->SelectRecordCount();
	} else {
		if ($socios_grid->Recordset = $socios_grid->LoadRecordset())
			$socios_grid->TotalRecs = $socios_grid->Recordset->RecordCount();
	}
	$socios_grid->StartRec = 1;
	$socios_grid->DisplayRecs = $socios_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$socios_grid->Recordset = $socios_grid->LoadRecordset($socios_grid->StartRec-1, $socios_grid->DisplayRecs);

	// Set no record found message
	if ($socios->CurrentAction == "" && $socios_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$socios_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($socios_grid->SearchWhere == "0=101")
			$socios_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$socios_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$socios_grid->RenderOtherOptions();
?>
<?php $socios_grid->ShowPageHeader(); ?>
<?php
$socios_grid->ShowMessage();
?>
<?php if ($socios_grid->TotalRecs > 0 || $socios->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fsociosgrid" class="ewForm form-inline">
<?php if ($socios_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($socios_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_socios" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_sociosgrid" class="table ewTable">
<?php echo $socios->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$socios_grid->RenderListOptions();

// Render list options (header, left)
$socios_grid->ListOptions->Render("header", "left");
?>
<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
	<?php if ($socios->SortUrl($socios->socio_nro) == "") { ?>
		<th data-name="socio_nro"><div id="elh_socios_socio_nro" class="socios_socio_nro"><div class="ewTableHeaderCaption"><?php echo $socios->socio_nro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="socio_nro"><div><div id="elh_socios_socio_nro" class="socios_socio_nro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->socio_nro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->socio_nro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->socio_nro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
	<?php if ($socios->SortUrl($socios->cuit_cuil) == "") { ?>
		<th data-name="cuit_cuil"><div id="elh_socios_cuit_cuil" class="socios_cuit_cuil"><div class="ewTableHeaderCaption"><?php echo $socios->cuit_cuil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cuit_cuil"><div><div id="elh_socios_cuit_cuil" class="socios_cuit_cuil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->cuit_cuil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->cuit_cuil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->cuit_cuil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->propietario->Visible) { // propietario ?>
	<?php if ($socios->SortUrl($socios->propietario) == "") { ?>
		<th data-name="propietario"><div id="elh_socios_propietario" class="socios_propietario"><div class="ewTableHeaderCaption"><?php echo $socios->propietario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="propietario"><div><div id="elh_socios_propietario" class="socios_propietario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->propietario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->propietario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->propietario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->comercio->Visible) { // comercio ?>
	<?php if ($socios->SortUrl($socios->comercio) == "") { ?>
		<th data-name="comercio"><div id="elh_socios_comercio" class="socios_comercio"><div class="ewTableHeaderCaption"><?php echo $socios->comercio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comercio"><div><div id="elh_socios_comercio" class="socios_comercio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->comercio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->comercio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->comercio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
	<?php if ($socios->SortUrl($socios->direccion_comercio) == "") { ?>
		<th data-name="direccion_comercio"><div id="elh_socios_direccion_comercio" class="socios_direccion_comercio"><div class="ewTableHeaderCaption"><?php echo $socios->direccion_comercio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion_comercio"><div><div id="elh_socios_direccion_comercio" class="socios_direccion_comercio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->direccion_comercio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->direccion_comercio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->direccion_comercio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->mail->Visible) { // mail ?>
	<?php if ($socios->SortUrl($socios->mail) == "") { ?>
		<th data-name="mail"><div id="elh_socios_mail" class="socios_mail"><div class="ewTableHeaderCaption"><?php echo $socios->mail->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mail"><div><div id="elh_socios_mail" class="socios_mail">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->mail->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->mail->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->mail->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->tel->Visible) { // tel ?>
	<?php if ($socios->SortUrl($socios->tel) == "") { ?>
		<th data-name="tel"><div id="elh_socios_tel" class="socios_tel"><div class="ewTableHeaderCaption"><?php echo $socios->tel->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tel"><div><div id="elh_socios_tel" class="socios_tel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->tel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->tel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->tel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->cel->Visible) { // cel ?>
	<?php if ($socios->SortUrl($socios->cel) == "") { ?>
		<th data-name="cel"><div id="elh_socios_cel" class="socios_cel"><div class="ewTableHeaderCaption"><?php echo $socios->cel->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cel"><div><div id="elh_socios_cel" class="socios_cel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->cel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->cel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->cel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($socios->activo->Visible) { // activo ?>
	<?php if ($socios->SortUrl($socios->activo) == "") { ?>
		<th data-name="activo"><div id="elh_socios_activo" class="socios_activo"><div class="ewTableHeaderCaption"><?php echo $socios->activo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activo"><div><div id="elh_socios_activo" class="socios_activo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $socios->activo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($socios->activo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($socios->activo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$socios_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$socios_grid->StartRec = 1;
$socios_grid->StopRec = $socios_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($socios_grid->FormKeyCountName) && ($socios->CurrentAction == "gridadd" || $socios->CurrentAction == "gridedit" || $socios->CurrentAction == "F")) {
		$socios_grid->KeyCount = $objForm->GetValue($socios_grid->FormKeyCountName);
		$socios_grid->StopRec = $socios_grid->StartRec + $socios_grid->KeyCount - 1;
	}
}
$socios_grid->RecCnt = $socios_grid->StartRec - 1;
if ($socios_grid->Recordset && !$socios_grid->Recordset->EOF) {
	$socios_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $socios_grid->StartRec > 1)
		$socios_grid->Recordset->Move($socios_grid->StartRec - 1);
} elseif (!$socios->AllowAddDeleteRow && $socios_grid->StopRec == 0) {
	$socios_grid->StopRec = $socios->GridAddRowCount;
}

// Initialize aggregate
$socios->RowType = EW_ROWTYPE_AGGREGATEINIT;
$socios->ResetAttrs();
$socios_grid->RenderRow();
if ($socios->CurrentAction == "gridadd")
	$socios_grid->RowIndex = 0;
if ($socios->CurrentAction == "gridedit")
	$socios_grid->RowIndex = 0;
while ($socios_grid->RecCnt < $socios_grid->StopRec) {
	$socios_grid->RecCnt++;
	if (intval($socios_grid->RecCnt) >= intval($socios_grid->StartRec)) {
		$socios_grid->RowCnt++;
		if ($socios->CurrentAction == "gridadd" || $socios->CurrentAction == "gridedit" || $socios->CurrentAction == "F") {
			$socios_grid->RowIndex++;
			$objForm->Index = $socios_grid->RowIndex;
			if ($objForm->HasValue($socios_grid->FormActionName))
				$socios_grid->RowAction = strval($objForm->GetValue($socios_grid->FormActionName));
			elseif ($socios->CurrentAction == "gridadd")
				$socios_grid->RowAction = "insert";
			else
				$socios_grid->RowAction = "";
		}

		// Set up key count
		$socios_grid->KeyCount = $socios_grid->RowIndex;

		// Init row class and style
		$socios->ResetAttrs();
		$socios->CssClass = "";
		if ($socios->CurrentAction == "gridadd") {
			if ($socios->CurrentMode == "copy") {
				$socios_grid->LoadRowValues($socios_grid->Recordset); // Load row values
				$socios_grid->SetRecordKey($socios_grid->RowOldKey, $socios_grid->Recordset); // Set old record key
			} else {
				$socios_grid->LoadDefaultValues(); // Load default values
				$socios_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$socios_grid->LoadRowValues($socios_grid->Recordset); // Load row values
		}
		$socios->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($socios->CurrentAction == "gridadd") // Grid add
			$socios->RowType = EW_ROWTYPE_ADD; // Render add
		if ($socios->CurrentAction == "gridadd" && $socios->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$socios_grid->RestoreCurrentRowFormValues($socios_grid->RowIndex); // Restore form values
		if ($socios->CurrentAction == "gridedit") { // Grid edit
			if ($socios->EventCancelled) {
				$socios_grid->RestoreCurrentRowFormValues($socios_grid->RowIndex); // Restore form values
			}
			if ($socios_grid->RowAction == "insert")
				$socios->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$socios->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($socios->CurrentAction == "gridedit" && ($socios->RowType == EW_ROWTYPE_EDIT || $socios->RowType == EW_ROWTYPE_ADD) && $socios->EventCancelled) // Update failed
			$socios_grid->RestoreCurrentRowFormValues($socios_grid->RowIndex); // Restore form values
		if ($socios->RowType == EW_ROWTYPE_EDIT) // Edit row
			$socios_grid->EditRowCnt++;
		if ($socios->CurrentAction == "F") // Confirm row
			$socios_grid->RestoreCurrentRowFormValues($socios_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$socios->RowAttrs = array_merge($socios->RowAttrs, array('data-rowindex'=>$socios_grid->RowCnt, 'id'=>'r' . $socios_grid->RowCnt . '_socios', 'data-rowtype'=>$socios->RowType));

		// Render row
		$socios_grid->RenderRow();

		// Render list options
		$socios_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($socios_grid->RowAction <> "delete" && $socios_grid->RowAction <> "insertdelete" && !($socios_grid->RowAction == "insert" && $socios->CurrentAction == "F" && $socios_grid->EmptyRow())) {
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_grid->ListOptions->Render("body", "left", $socios_grid->RowCnt);
?>
	<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
		<td data-name="socio_nro"<?php echo $socios->socio_nro->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_socio_nro" name="o<?php echo $socios_grid->RowIndex ?>_socio_nro" id="o<?php echo $socios_grid->RowIndex ?>_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_socio_nro" class="form-group socios_socio_nro">
<span<?php echo $socios->socio_nro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->socio_nro->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_socio_nro" name="x<?php echo $socios_grid->RowIndex ?>_socio_nro" id="x<?php echo $socios_grid->RowIndex ?>_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->CurrentValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->socio_nro->ViewAttributes() ?>>
<?php echo $socios->socio_nro->ListViewValue() ?></span>
<input type="hidden" data-field="x_socio_nro" name="x<?php echo $socios_grid->RowIndex ?>_socio_nro" id="x<?php echo $socios_grid->RowIndex ?>_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->FormValue) ?>">
<input type="hidden" data-field="x_socio_nro" name="o<?php echo $socios_grid->RowIndex ?>_socio_nro" id="o<?php echo $socios_grid->RowIndex ?>_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->OldValue) ?>">
<?php } ?>
<a id="<?php echo $socios_grid->PageObjName . "_row_" . $socios_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
		<td data-name="cuit_cuil"<?php echo $socios->cuit_cuil->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_cuit_cuil" class="form-group socios_cuit_cuil">
<input type="text" data-field="x_cuit_cuil" name="x<?php echo $socios_grid->RowIndex ?>_cuit_cuil" id="x<?php echo $socios_grid->RowIndex ?>_cuit_cuil" size="30" maxlength="14" placeholder="<?php echo ew_HtmlEncode($socios->cuit_cuil->PlaceHolder) ?>" value="<?php echo $socios->cuit_cuil->EditValue ?>"<?php echo $socios->cuit_cuil->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cuit_cuil" name="o<?php echo $socios_grid->RowIndex ?>_cuit_cuil" id="o<?php echo $socios_grid->RowIndex ?>_cuit_cuil" value="<?php echo ew_HtmlEncode($socios->cuit_cuil->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_cuit_cuil" class="form-group socios_cuit_cuil">
<input type="text" data-field="x_cuit_cuil" name="x<?php echo $socios_grid->RowIndex ?>_cuit_cuil" id="x<?php echo $socios_grid->RowIndex ?>_cuit_cuil" size="30" maxlength="14" placeholder="<?php echo ew_HtmlEncode($socios->cuit_cuil->PlaceHolder) ?>" value="<?php echo $socios->cuit_cuil->EditValue ?>"<?php echo $socios->cuit_cuil->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->cuit_cuil->ViewAttributes() ?>>
<?php echo $socios->cuit_cuil->ListViewValue() ?></span>
<input type="hidden" data-field="x_cuit_cuil" name="x<?php echo $socios_grid->RowIndex ?>_cuit_cuil" id="x<?php echo $socios_grid->RowIndex ?>_cuit_cuil" value="<?php echo ew_HtmlEncode($socios->cuit_cuil->FormValue) ?>">
<input type="hidden" data-field="x_cuit_cuil" name="o<?php echo $socios_grid->RowIndex ?>_cuit_cuil" id="o<?php echo $socios_grid->RowIndex ?>_cuit_cuil" value="<?php echo ew_HtmlEncode($socios->cuit_cuil->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->propietario->Visible) { // propietario ?>
		<td data-name="propietario"<?php echo $socios->propietario->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_propietario" class="form-group socios_propietario">
<input type="text" data-field="x_propietario" name="x<?php echo $socios_grid->RowIndex ?>_propietario" id="x<?php echo $socios_grid->RowIndex ?>_propietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->propietario->PlaceHolder) ?>" value="<?php echo $socios->propietario->EditValue ?>"<?php echo $socios->propietario->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_propietario" name="o<?php echo $socios_grid->RowIndex ?>_propietario" id="o<?php echo $socios_grid->RowIndex ?>_propietario" value="<?php echo ew_HtmlEncode($socios->propietario->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_propietario" class="form-group socios_propietario">
<input type="text" data-field="x_propietario" name="x<?php echo $socios_grid->RowIndex ?>_propietario" id="x<?php echo $socios_grid->RowIndex ?>_propietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->propietario->PlaceHolder) ?>" value="<?php echo $socios->propietario->EditValue ?>"<?php echo $socios->propietario->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->propietario->ViewAttributes() ?>>
<?php echo $socios->propietario->ListViewValue() ?></span>
<input type="hidden" data-field="x_propietario" name="x<?php echo $socios_grid->RowIndex ?>_propietario" id="x<?php echo $socios_grid->RowIndex ?>_propietario" value="<?php echo ew_HtmlEncode($socios->propietario->FormValue) ?>">
<input type="hidden" data-field="x_propietario" name="o<?php echo $socios_grid->RowIndex ?>_propietario" id="o<?php echo $socios_grid->RowIndex ?>_propietario" value="<?php echo ew_HtmlEncode($socios->propietario->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->comercio->Visible) { // comercio ?>
		<td data-name="comercio"<?php echo $socios->comercio->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_comercio" class="form-group socios_comercio">
<input type="text" data-field="x_comercio" name="x<?php echo $socios_grid->RowIndex ?>_comercio" id="x<?php echo $socios_grid->RowIndex ?>_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->comercio->PlaceHolder) ?>" value="<?php echo $socios->comercio->EditValue ?>"<?php echo $socios->comercio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_comercio" name="o<?php echo $socios_grid->RowIndex ?>_comercio" id="o<?php echo $socios_grid->RowIndex ?>_comercio" value="<?php echo ew_HtmlEncode($socios->comercio->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_comercio" class="form-group socios_comercio">
<input type="text" data-field="x_comercio" name="x<?php echo $socios_grid->RowIndex ?>_comercio" id="x<?php echo $socios_grid->RowIndex ?>_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->comercio->PlaceHolder) ?>" value="<?php echo $socios->comercio->EditValue ?>"<?php echo $socios->comercio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->comercio->ViewAttributes() ?>>
<?php echo $socios->comercio->ListViewValue() ?></span>
<input type="hidden" data-field="x_comercio" name="x<?php echo $socios_grid->RowIndex ?>_comercio" id="x<?php echo $socios_grid->RowIndex ?>_comercio" value="<?php echo ew_HtmlEncode($socios->comercio->FormValue) ?>">
<input type="hidden" data-field="x_comercio" name="o<?php echo $socios_grid->RowIndex ?>_comercio" id="o<?php echo $socios_grid->RowIndex ?>_comercio" value="<?php echo ew_HtmlEncode($socios->comercio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
		<td data-name="direccion_comercio"<?php echo $socios->direccion_comercio->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_direccion_comercio" class="form-group socios_direccion_comercio">
<input type="text" data-field="x_direccion_comercio" name="x<?php echo $socios_grid->RowIndex ?>_direccion_comercio" id="x<?php echo $socios_grid->RowIndex ?>_direccion_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->direccion_comercio->PlaceHolder) ?>" value="<?php echo $socios->direccion_comercio->EditValue ?>"<?php echo $socios->direccion_comercio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_direccion_comercio" name="o<?php echo $socios_grid->RowIndex ?>_direccion_comercio" id="o<?php echo $socios_grid->RowIndex ?>_direccion_comercio" value="<?php echo ew_HtmlEncode($socios->direccion_comercio->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_direccion_comercio" class="form-group socios_direccion_comercio">
<input type="text" data-field="x_direccion_comercio" name="x<?php echo $socios_grid->RowIndex ?>_direccion_comercio" id="x<?php echo $socios_grid->RowIndex ?>_direccion_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->direccion_comercio->PlaceHolder) ?>" value="<?php echo $socios->direccion_comercio->EditValue ?>"<?php echo $socios->direccion_comercio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->direccion_comercio->ViewAttributes() ?>>
<?php echo $socios->direccion_comercio->ListViewValue() ?></span>
<input type="hidden" data-field="x_direccion_comercio" name="x<?php echo $socios_grid->RowIndex ?>_direccion_comercio" id="x<?php echo $socios_grid->RowIndex ?>_direccion_comercio" value="<?php echo ew_HtmlEncode($socios->direccion_comercio->FormValue) ?>">
<input type="hidden" data-field="x_direccion_comercio" name="o<?php echo $socios_grid->RowIndex ?>_direccion_comercio" id="o<?php echo $socios_grid->RowIndex ?>_direccion_comercio" value="<?php echo ew_HtmlEncode($socios->direccion_comercio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->mail->Visible) { // mail ?>
		<td data-name="mail"<?php echo $socios->mail->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_mail" class="form-group socios_mail">
<input type="text" data-field="x_mail" name="x<?php echo $socios_grid->RowIndex ?>_mail" id="x<?php echo $socios_grid->RowIndex ?>_mail" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->mail->PlaceHolder) ?>" value="<?php echo $socios->mail->EditValue ?>"<?php echo $socios->mail->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_mail" name="o<?php echo $socios_grid->RowIndex ?>_mail" id="o<?php echo $socios_grid->RowIndex ?>_mail" value="<?php echo ew_HtmlEncode($socios->mail->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_mail" class="form-group socios_mail">
<input type="text" data-field="x_mail" name="x<?php echo $socios_grid->RowIndex ?>_mail" id="x<?php echo $socios_grid->RowIndex ?>_mail" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->mail->PlaceHolder) ?>" value="<?php echo $socios->mail->EditValue ?>"<?php echo $socios->mail->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->mail->ViewAttributes() ?>>
<?php echo $socios->mail->ListViewValue() ?></span>
<input type="hidden" data-field="x_mail" name="x<?php echo $socios_grid->RowIndex ?>_mail" id="x<?php echo $socios_grid->RowIndex ?>_mail" value="<?php echo ew_HtmlEncode($socios->mail->FormValue) ?>">
<input type="hidden" data-field="x_mail" name="o<?php echo $socios_grid->RowIndex ?>_mail" id="o<?php echo $socios_grid->RowIndex ?>_mail" value="<?php echo ew_HtmlEncode($socios->mail->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->tel->Visible) { // tel ?>
		<td data-name="tel"<?php echo $socios->tel->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_tel" class="form-group socios_tel">
<input type="text" data-field="x_tel" name="x<?php echo $socios_grid->RowIndex ?>_tel" id="x<?php echo $socios_grid->RowIndex ?>_tel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->tel->PlaceHolder) ?>" value="<?php echo $socios->tel->EditValue ?>"<?php echo $socios->tel->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_tel" name="o<?php echo $socios_grid->RowIndex ?>_tel" id="o<?php echo $socios_grid->RowIndex ?>_tel" value="<?php echo ew_HtmlEncode($socios->tel->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_tel" class="form-group socios_tel">
<input type="text" data-field="x_tel" name="x<?php echo $socios_grid->RowIndex ?>_tel" id="x<?php echo $socios_grid->RowIndex ?>_tel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->tel->PlaceHolder) ?>" value="<?php echo $socios->tel->EditValue ?>"<?php echo $socios->tel->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->tel->ViewAttributes() ?>>
<?php echo $socios->tel->ListViewValue() ?></span>
<input type="hidden" data-field="x_tel" name="x<?php echo $socios_grid->RowIndex ?>_tel" id="x<?php echo $socios_grid->RowIndex ?>_tel" value="<?php echo ew_HtmlEncode($socios->tel->FormValue) ?>">
<input type="hidden" data-field="x_tel" name="o<?php echo $socios_grid->RowIndex ?>_tel" id="o<?php echo $socios_grid->RowIndex ?>_tel" value="<?php echo ew_HtmlEncode($socios->tel->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->cel->Visible) { // cel ?>
		<td data-name="cel"<?php echo $socios->cel->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_cel" class="form-group socios_cel">
<input type="text" data-field="x_cel" name="x<?php echo $socios_grid->RowIndex ?>_cel" id="x<?php echo $socios_grid->RowIndex ?>_cel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->cel->PlaceHolder) ?>" value="<?php echo $socios->cel->EditValue ?>"<?php echo $socios->cel->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cel" name="o<?php echo $socios_grid->RowIndex ?>_cel" id="o<?php echo $socios_grid->RowIndex ?>_cel" value="<?php echo ew_HtmlEncode($socios->cel->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_cel" class="form-group socios_cel">
<input type="text" data-field="x_cel" name="x<?php echo $socios_grid->RowIndex ?>_cel" id="x<?php echo $socios_grid->RowIndex ?>_cel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->cel->PlaceHolder) ?>" value="<?php echo $socios->cel->EditValue ?>"<?php echo $socios->cel->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->cel->ViewAttributes() ?>>
<?php echo $socios->cel->ListViewValue() ?></span>
<input type="hidden" data-field="x_cel" name="x<?php echo $socios_grid->RowIndex ?>_cel" id="x<?php echo $socios_grid->RowIndex ?>_cel" value="<?php echo ew_HtmlEncode($socios->cel->FormValue) ?>">
<input type="hidden" data-field="x_cel" name="o<?php echo $socios_grid->RowIndex ?>_cel" id="o<?php echo $socios_grid->RowIndex ?>_cel" value="<?php echo ew_HtmlEncode($socios->cel->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($socios->activo->Visible) { // activo ?>
		<td data-name="activo"<?php echo $socios->activo->CellAttributes() ?>>
<?php if ($socios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_activo" class="form-group socios_activo">
<div id="tp_x<?php echo $socios_grid->RowIndex ?>_activo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_grid->RowIndex ?>_activo" id="x<?php echo $socios_grid->RowIndex ?>_activo" value="{value}"<?php echo $socios->activo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_grid->RowIndex ?>_activo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->activo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->activo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activo" name="x<?php echo $socios_grid->RowIndex ?>_activo" id="x<?php echo $socios_grid->RowIndex ?>_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $socios->activo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_activo" name="o<?php echo $socios_grid->RowIndex ?>_activo" id="o<?php echo $socios_grid->RowIndex ?>_activo" value="<?php echo ew_HtmlEncode($socios->activo->OldValue) ?>">
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $socios_grid->RowCnt ?>_socios_activo" class="form-group socios_activo">
<div id="tp_x<?php echo $socios_grid->RowIndex ?>_activo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_grid->RowIndex ?>_activo" id="x<?php echo $socios_grid->RowIndex ?>_activo" value="{value}"<?php echo $socios->activo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_grid->RowIndex ?>_activo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->activo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->activo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activo" name="x<?php echo $socios_grid->RowIndex ?>_activo" id="x<?php echo $socios_grid->RowIndex ?>_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $socios->activo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($socios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $socios->activo->ViewAttributes() ?>>
<?php echo $socios->activo->ListViewValue() ?></span>
<input type="hidden" data-field="x_activo" name="x<?php echo $socios_grid->RowIndex ?>_activo" id="x<?php echo $socios_grid->RowIndex ?>_activo" value="<?php echo ew_HtmlEncode($socios->activo->FormValue) ?>">
<input type="hidden" data-field="x_activo" name="o<?php echo $socios_grid->RowIndex ?>_activo" id="o<?php echo $socios_grid->RowIndex ?>_activo" value="<?php echo ew_HtmlEncode($socios->activo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_grid->ListOptions->Render("body", "right", $socios_grid->RowCnt);
?>
	</tr>
<?php if ($socios->RowType == EW_ROWTYPE_ADD || $socios->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsociosgrid.UpdateOpts(<?php echo $socios_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($socios->CurrentAction <> "gridadd" || $socios->CurrentMode == "copy")
		if (!$socios_grid->Recordset->EOF) $socios_grid->Recordset->MoveNext();
}
?>
<?php
	if ($socios->CurrentMode == "add" || $socios->CurrentMode == "copy" || $socios->CurrentMode == "edit") {
		$socios_grid->RowIndex = '$rowindex$';
		$socios_grid->LoadDefaultValues();

		// Set row properties
		$socios->ResetAttrs();
		$socios->RowAttrs = array_merge($socios->RowAttrs, array('data-rowindex'=>$socios_grid->RowIndex, 'id'=>'r0_socios', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($socios->RowAttrs["class"], "ewTemplate");
		$socios->RowType = EW_ROWTYPE_ADD;

		// Render row
		$socios_grid->RenderRow();

		// Render list options
		$socios_grid->RenderListOptions();
		$socios_grid->StartRowCnt = 0;
?>
	<tr<?php echo $socios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$socios_grid->ListOptions->Render("body", "left", $socios_grid->RowIndex);
?>
	<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
		<td>
<?php if ($socios->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_socios_socio_nro" class="form-group socios_socio_nro">
<span<?php echo $socios->socio_nro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->socio_nro->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_socio_nro" name="x<?php echo $socios_grid->RowIndex ?>_socio_nro" id="x<?php echo $socios_grid->RowIndex ?>_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_socio_nro" name="o<?php echo $socios_grid->RowIndex ?>_socio_nro" id="o<?php echo $socios_grid->RowIndex ?>_socio_nro" value="<?php echo ew_HtmlEncode($socios->socio_nro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
		<td>
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_cuit_cuil" class="form-group socios_cuit_cuil">
<input type="text" data-field="x_cuit_cuil" name="x<?php echo $socios_grid->RowIndex ?>_cuit_cuil" id="x<?php echo $socios_grid->RowIndex ?>_cuit_cuil" size="30" maxlength="14" placeholder="<?php echo ew_HtmlEncode($socios->cuit_cuil->PlaceHolder) ?>" value="<?php echo $socios->cuit_cuil->EditValue ?>"<?php echo $socios->cuit_cuil->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_cuit_cuil" class="form-group socios_cuit_cuil">
<span<?php echo $socios->cuit_cuil->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->cuit_cuil->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_cuit_cuil" name="x<?php echo $socios_grid->RowIndex ?>_cuit_cuil" id="x<?php echo $socios_grid->RowIndex ?>_cuit_cuil" value="<?php echo ew_HtmlEncode($socios->cuit_cuil->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_cuit_cuil" name="o<?php echo $socios_grid->RowIndex ?>_cuit_cuil" id="o<?php echo $socios_grid->RowIndex ?>_cuit_cuil" value="<?php echo ew_HtmlEncode($socios->cuit_cuil->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->propietario->Visible) { // propietario ?>
		<td>
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_propietario" class="form-group socios_propietario">
<input type="text" data-field="x_propietario" name="x<?php echo $socios_grid->RowIndex ?>_propietario" id="x<?php echo $socios_grid->RowIndex ?>_propietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->propietario->PlaceHolder) ?>" value="<?php echo $socios->propietario->EditValue ?>"<?php echo $socios->propietario->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_propietario" class="form-group socios_propietario">
<span<?php echo $socios->propietario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->propietario->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_propietario" name="x<?php echo $socios_grid->RowIndex ?>_propietario" id="x<?php echo $socios_grid->RowIndex ?>_propietario" value="<?php echo ew_HtmlEncode($socios->propietario->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_propietario" name="o<?php echo $socios_grid->RowIndex ?>_propietario" id="o<?php echo $socios_grid->RowIndex ?>_propietario" value="<?php echo ew_HtmlEncode($socios->propietario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->comercio->Visible) { // comercio ?>
		<td>
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_comercio" class="form-group socios_comercio">
<input type="text" data-field="x_comercio" name="x<?php echo $socios_grid->RowIndex ?>_comercio" id="x<?php echo $socios_grid->RowIndex ?>_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->comercio->PlaceHolder) ?>" value="<?php echo $socios->comercio->EditValue ?>"<?php echo $socios->comercio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_comercio" class="form-group socios_comercio">
<span<?php echo $socios->comercio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->comercio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_comercio" name="x<?php echo $socios_grid->RowIndex ?>_comercio" id="x<?php echo $socios_grid->RowIndex ?>_comercio" value="<?php echo ew_HtmlEncode($socios->comercio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_comercio" name="o<?php echo $socios_grid->RowIndex ?>_comercio" id="o<?php echo $socios_grid->RowIndex ?>_comercio" value="<?php echo ew_HtmlEncode($socios->comercio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
		<td>
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_direccion_comercio" class="form-group socios_direccion_comercio">
<input type="text" data-field="x_direccion_comercio" name="x<?php echo $socios_grid->RowIndex ?>_direccion_comercio" id="x<?php echo $socios_grid->RowIndex ?>_direccion_comercio" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->direccion_comercio->PlaceHolder) ?>" value="<?php echo $socios->direccion_comercio->EditValue ?>"<?php echo $socios->direccion_comercio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_direccion_comercio" class="form-group socios_direccion_comercio">
<span<?php echo $socios->direccion_comercio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->direccion_comercio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_direccion_comercio" name="x<?php echo $socios_grid->RowIndex ?>_direccion_comercio" id="x<?php echo $socios_grid->RowIndex ?>_direccion_comercio" value="<?php echo ew_HtmlEncode($socios->direccion_comercio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_direccion_comercio" name="o<?php echo $socios_grid->RowIndex ?>_direccion_comercio" id="o<?php echo $socios_grid->RowIndex ?>_direccion_comercio" value="<?php echo ew_HtmlEncode($socios->direccion_comercio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->mail->Visible) { // mail ?>
		<td>
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_mail" class="form-group socios_mail">
<input type="text" data-field="x_mail" name="x<?php echo $socios_grid->RowIndex ?>_mail" id="x<?php echo $socios_grid->RowIndex ?>_mail" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($socios->mail->PlaceHolder) ?>" value="<?php echo $socios->mail->EditValue ?>"<?php echo $socios->mail->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_mail" class="form-group socios_mail">
<span<?php echo $socios->mail->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->mail->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_mail" name="x<?php echo $socios_grid->RowIndex ?>_mail" id="x<?php echo $socios_grid->RowIndex ?>_mail" value="<?php echo ew_HtmlEncode($socios->mail->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_mail" name="o<?php echo $socios_grid->RowIndex ?>_mail" id="o<?php echo $socios_grid->RowIndex ?>_mail" value="<?php echo ew_HtmlEncode($socios->mail->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->tel->Visible) { // tel ?>
		<td>
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_tel" class="form-group socios_tel">
<input type="text" data-field="x_tel" name="x<?php echo $socios_grid->RowIndex ?>_tel" id="x<?php echo $socios_grid->RowIndex ?>_tel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->tel->PlaceHolder) ?>" value="<?php echo $socios->tel->EditValue ?>"<?php echo $socios->tel->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_tel" class="form-group socios_tel">
<span<?php echo $socios->tel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->tel->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_tel" name="x<?php echo $socios_grid->RowIndex ?>_tel" id="x<?php echo $socios_grid->RowIndex ?>_tel" value="<?php echo ew_HtmlEncode($socios->tel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tel" name="o<?php echo $socios_grid->RowIndex ?>_tel" id="o<?php echo $socios_grid->RowIndex ?>_tel" value="<?php echo ew_HtmlEncode($socios->tel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->cel->Visible) { // cel ?>
		<td>
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_cel" class="form-group socios_cel">
<input type="text" data-field="x_cel" name="x<?php echo $socios_grid->RowIndex ?>_cel" id="x<?php echo $socios_grid->RowIndex ?>_cel" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($socios->cel->PlaceHolder) ?>" value="<?php echo $socios->cel->EditValue ?>"<?php echo $socios->cel->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_cel" class="form-group socios_cel">
<span<?php echo $socios->cel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->cel->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_cel" name="x<?php echo $socios_grid->RowIndex ?>_cel" id="x<?php echo $socios_grid->RowIndex ?>_cel" value="<?php echo ew_HtmlEncode($socios->cel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_cel" name="o<?php echo $socios_grid->RowIndex ?>_cel" id="o<?php echo $socios_grid->RowIndex ?>_cel" value="<?php echo ew_HtmlEncode($socios->cel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($socios->activo->Visible) { // activo ?>
		<td>
<?php if ($socios->CurrentAction <> "F") { ?>
<span id="el$rowindex$_socios_activo" class="form-group socios_activo">
<div id="tp_x<?php echo $socios_grid->RowIndex ?>_activo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $socios_grid->RowIndex ?>_activo" id="x<?php echo $socios_grid->RowIndex ?>_activo" value="{value}"<?php echo $socios->activo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $socios_grid->RowIndex ?>_activo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $socios->activo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($socios->activo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_activo" name="x<?php echo $socios_grid->RowIndex ?>_activo" id="x<?php echo $socios_grid->RowIndex ?>_activo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $socios->activo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $socios->activo->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_socios_activo" class="form-group socios_activo">
<span<?php echo $socios->activo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $socios->activo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_activo" name="x<?php echo $socios_grid->RowIndex ?>_activo" id="x<?php echo $socios_grid->RowIndex ?>_activo" value="<?php echo ew_HtmlEncode($socios->activo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_activo" name="o<?php echo $socios_grid->RowIndex ?>_activo" id="o<?php echo $socios_grid->RowIndex ?>_activo" value="<?php echo ew_HtmlEncode($socios->activo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$socios_grid->ListOptions->Render("body", "right", $socios_grid->RowCnt);
?>
<script type="text/javascript">
fsociosgrid.UpdateOpts(<?php echo $socios_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($socios->CurrentMode == "add" || $socios->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $socios_grid->FormKeyCountName ?>" id="<?php echo $socios_grid->FormKeyCountName ?>" value="<?php echo $socios_grid->KeyCount ?>">
<?php echo $socios_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($socios->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $socios_grid->FormKeyCountName ?>" id="<?php echo $socios_grid->FormKeyCountName ?>" value="<?php echo $socios_grid->KeyCount ?>">
<?php echo $socios_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($socios->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsociosgrid">
</div>
<?php

// Close recordset
if ($socios_grid->Recordset)
	$socios_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($socios_grid->TotalRecs == 0 && $socios->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($socios_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($socios->Export == "") { ?>
<script type="text/javascript">
fsociosgrid.Init();
</script>
<?php } ?>
<?php
$socios_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$socios_grid->Page_Terminate();
?>
