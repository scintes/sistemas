<?php

// codigo
// Titulo
// fecha
// archivo
// estado

?>
<?php if ($tramites->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $tramites->TableCaption() ?></h4> -->
<table id="tbl_tramitesmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($tramites->codigo->Visible) { // codigo ?>
		<tr id="r_codigo">
			<td><?php echo $tramites->codigo->FldCaption() ?></td>
			<td<?php echo $tramites->codigo->CellAttributes() ?>>
<span id="el_tramites_codigo">
<span<?php echo $tramites->codigo->ViewAttributes() ?>>
<?php echo $tramites->codigo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tramites->Titulo->Visible) { // Titulo ?>
		<tr id="r_Titulo">
			<td><?php echo $tramites->Titulo->FldCaption() ?></td>
			<td<?php echo $tramites->Titulo->CellAttributes() ?>>
<span id="el_tramites_Titulo">
<span<?php echo $tramites->Titulo->ViewAttributes() ?>>
<?php echo $tramites->Titulo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tramites->fecha->Visible) { // fecha ?>
		<tr id="r_fecha">
			<td><?php echo $tramites->fecha->FldCaption() ?></td>
			<td<?php echo $tramites->fecha->CellAttributes() ?>>
<span id="el_tramites_fecha">
<span<?php echo $tramites->fecha->ViewAttributes() ?>>
<?php echo $tramites->fecha->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tramites->archivo->Visible) { // archivo ?>
		<tr id="r_archivo">
			<td><?php echo $tramites->archivo->FldCaption() ?></td>
			<td<?php echo $tramites->archivo->CellAttributes() ?>>
<span id="el_tramites_archivo">
<span<?php echo $tramites->archivo->ViewAttributes() ?>>
<ul class="list-inline"><?php
$Files = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $tramites->archivo->Upload->DbValue);
$HrefValue = $tramites->archivo->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$tramites->archivo->ViewValue = $Files[$i];
$tramites->archivo->HrefValue = str_replace("%u", ew_HtmlEncode(ew_UploadPathEx(FALSE, $tramites->archivo->UploadPath) . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode(ew_UploadPathEx(FALSE, $tramites->archivo->UploadPath) . $Files[$i]), $tramites->archivo->ListViewValue());
?>
<li>
<?php if ($tramites->archivo->LinkAttributes() <> "") { ?>
<?php if (!empty($tramites->archivo->Upload->DbValue)) { ?>
<a<?php echo $tramites->archivo->LinkAttributes() ?>><?php echo $tramites->archivo->ListViewValue() ?></a>
<?php } elseif (!in_array($tramites->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($tramites->archivo->Upload->DbValue)) { ?>
<?php echo $tramites->archivo->ListViewValue() ?>
<?php } elseif (!in_array($tramites->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</li>
<?php
}
}
?></ul>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tramites->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $tramites->estado->FldCaption() ?></td>
			<td<?php echo $tramites->estado->CellAttributes() ?>>
<span id="el_tramites_estado">
<span<?php echo $tramites->estado->ViewAttributes() ?>>
<?php echo $tramites->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
