<?php

// id_socio
// id_detalles
// fecha_alta
// fecha_baja

?>
<?php if ($socios_detalles->Visible) { ?>
<table cellspacing="0" id="t_socios_detalles" class="ewGrid"><tr><td>
<table id="tbl_socios_detallesmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($socios_detalles->id_socio->Visible) { // id_socio ?>
		<tr id="r_id_socio">
			<td><?php echo $socios_detalles->id_socio->FldCaption() ?></td>
			<td<?php echo $socios_detalles->id_socio->CellAttributes() ?>>
<span id="el_socios_detalles_id_socio" class="control-group">
<span<?php echo $socios_detalles->id_socio->ViewAttributes() ?>>
<?php echo $socios_detalles->id_socio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios_detalles->id_detalles->Visible) { // id_detalles ?>
		<tr id="r_id_detalles">
			<td><?php echo $socios_detalles->id_detalles->FldCaption() ?></td>
			<td<?php echo $socios_detalles->id_detalles->CellAttributes() ?>>
<span id="el_socios_detalles_id_detalles" class="control-group">
<span<?php echo $socios_detalles->id_detalles->ViewAttributes() ?>>
<?php echo $socios_detalles->id_detalles->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios_detalles->fecha_alta->Visible) { // fecha_alta ?>
		<tr id="r_fecha_alta">
			<td><?php echo $socios_detalles->fecha_alta->FldCaption() ?></td>
			<td<?php echo $socios_detalles->fecha_alta->CellAttributes() ?>>
<span id="el_socios_detalles_fecha_alta" class="control-group">
<span<?php echo $socios_detalles->fecha_alta->ViewAttributes() ?>>
<?php echo $socios_detalles->fecha_alta->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios_detalles->fecha_baja->Visible) { // fecha_baja ?>
		<tr id="r_fecha_baja">
			<td><?php echo $socios_detalles->fecha_baja->FldCaption() ?></td>
			<td<?php echo $socios_detalles->fecha_baja->CellAttributes() ?>>
<span id="el_socios_detalles_fecha_baja" class="control-group">
<span<?php echo $socios_detalles->fecha_baja->ViewAttributes() ?>>
<?php echo $socios_detalles->fecha_baja->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
