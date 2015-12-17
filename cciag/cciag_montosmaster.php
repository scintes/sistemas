<?php

// id
// descripcion
// importe
// fecha_creacion
// activa
// id_usuario

?>
<?php if ($montos->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $montos->TableCaption() ?></h4> -->
<table id="tbl_montosmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($montos->id->Visible) { // id ?>
		<tr id="r_id">
			<td><?php echo $montos->id->FldCaption() ?></td>
			<td<?php echo $montos->id->CellAttributes() ?>>
<span id="el_montos_id">
<span<?php echo $montos->id->ViewAttributes() ?>>
<?php echo $montos->id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($montos->descripcion->Visible) { // descripcion ?>
		<tr id="r_descripcion">
			<td><?php echo $montos->descripcion->FldCaption() ?></td>
			<td<?php echo $montos->descripcion->CellAttributes() ?>>
<span id="el_montos_descripcion">
<span<?php echo $montos->descripcion->ViewAttributes() ?>>
<?php echo $montos->descripcion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($montos->importe->Visible) { // importe ?>
		<tr id="r_importe">
			<td><?php echo $montos->importe->FldCaption() ?></td>
			<td<?php echo $montos->importe->CellAttributes() ?>>
<span id="el_montos_importe">
<span<?php echo $montos->importe->ViewAttributes() ?>>
<?php echo $montos->importe->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($montos->fecha_creacion->Visible) { // fecha_creacion ?>
		<tr id="r_fecha_creacion">
			<td><?php echo $montos->fecha_creacion->FldCaption() ?></td>
			<td<?php echo $montos->fecha_creacion->CellAttributes() ?>>
<span id="el_montos_fecha_creacion">
<span<?php echo $montos->fecha_creacion->ViewAttributes() ?>>
<?php echo $montos->fecha_creacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($montos->activa->Visible) { // activa ?>
		<tr id="r_activa">
			<td><?php echo $montos->activa->FldCaption() ?></td>
			<td<?php echo $montos->activa->CellAttributes() ?>>
<span id="el_montos_activa">
<span<?php echo $montos->activa->ViewAttributes() ?>>
<?php echo $montos->activa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($montos->id_usuario->Visible) { // id_usuario ?>
		<tr id="r_id_usuario">
			<td><?php echo $montos->id_usuario->FldCaption() ?></td>
			<td<?php echo $montos->id_usuario->CellAttributes() ?>>
<span id="el_montos_id_usuario">
<span<?php echo $montos->id_usuario->ViewAttributes() ?>>
<?php echo $montos->id_usuario->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
