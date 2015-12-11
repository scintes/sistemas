<?php

// codigo
// tipo_gasto
// clase

?>
<?php if ($tipo_gastos->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $tipo_gastos->TableCaption() ?></h4> -->
<table id="tbl_tipo_gastosmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($tipo_gastos->codigo->Visible) { // codigo ?>
		<tr id="r_codigo">
			<td><?php echo $tipo_gastos->codigo->FldCaption() ?></td>
			<td<?php echo $tipo_gastos->codigo->CellAttributes() ?>>
<span id="el_tipo_gastos_codigo">
<span<?php echo $tipo_gastos->codigo->ViewAttributes() ?>>
<?php echo $tipo_gastos->codigo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tipo_gastos->tipo_gasto->Visible) { // tipo_gasto ?>
		<tr id="r_tipo_gasto">
			<td><?php echo $tipo_gastos->tipo_gasto->FldCaption() ?></td>
			<td<?php echo $tipo_gastos->tipo_gasto->CellAttributes() ?>>
<span id="el_tipo_gastos_tipo_gasto">
<span<?php echo $tipo_gastos->tipo_gasto->ViewAttributes() ?>>
<?php echo $tipo_gastos->tipo_gasto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tipo_gastos->clase->Visible) { // clase ?>
		<tr id="r_clase">
			<td><?php echo $tipo_gastos->clase->FldCaption() ?></td>
			<td<?php echo $tipo_gastos->clase->CellAttributes() ?>>
<span id="el_tipo_gastos_clase">
<span<?php echo $tipo_gastos->clase->ViewAttributes() ?>>
<?php echo $tipo_gastos->clase->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
