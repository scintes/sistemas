<?php

// codigo
// nombre
// activa

?>
<?php if ($detalles->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $detalles->TableCaption() ?></h4> -->
<table id="tbl_detallesmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($detalles->codigo->Visible) { // codigo ?>
		<tr id="r_codigo">
			<td><?php echo $detalles->codigo->FldCaption() ?></td>
			<td<?php echo $detalles->codigo->CellAttributes() ?>>
<span id="el_detalles_codigo" class="form-group">
<span<?php echo $detalles->codigo->ViewAttributes() ?>>
<?php echo $detalles->codigo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($detalles->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $detalles->nombre->FldCaption() ?></td>
			<td<?php echo $detalles->nombre->CellAttributes() ?>>
<span id="el_detalles_nombre" class="form-group">
<span<?php echo $detalles->nombre->ViewAttributes() ?>>
<?php echo $detalles->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($detalles->activa->Visible) { // activa ?>
		<tr id="r_activa">
			<td><?php echo $detalles->activa->FldCaption() ?></td>
			<td<?php echo $detalles->activa->CellAttributes() ?>>
<span id="el_detalles_activa" class="form-group">
<span<?php echo $detalles->activa->ViewAttributes() ?>>
<?php echo $detalles->activa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
