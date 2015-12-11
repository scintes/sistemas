<?php

// codigo
// fecha_ini
// fecha_fin
// id_vehiculo
// id_taller
// id_tipo_mantenimiento

?>
<?php if ($hoja_mantenimientos->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $hoja_mantenimientos->TableCaption() ?></h4> -->
<table id="tbl_hoja_mantenimientosmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($hoja_mantenimientos->codigo->Visible) { // codigo ?>
		<tr id="r_codigo">
			<td><?php echo $hoja_mantenimientos->codigo->FldCaption() ?></td>
			<td<?php echo $hoja_mantenimientos->codigo->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_codigo">
<span<?php echo $hoja_mantenimientos->codigo->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->codigo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_mantenimientos->fecha_ini->Visible) { // fecha_ini ?>
		<tr id="r_fecha_ini">
			<td><?php echo $hoja_mantenimientos->fecha_ini->FldCaption() ?></td>
			<td<?php echo $hoja_mantenimientos->fecha_ini->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_fecha_ini">
<span<?php echo $hoja_mantenimientos->fecha_ini->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->fecha_ini->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_mantenimientos->fecha_fin->Visible) { // fecha_fin ?>
		<tr id="r_fecha_fin">
			<td><?php echo $hoja_mantenimientos->fecha_fin->FldCaption() ?></td>
			<td<?php echo $hoja_mantenimientos->fecha_fin->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_fecha_fin">
<span<?php echo $hoja_mantenimientos->fecha_fin->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->fecha_fin->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_mantenimientos->id_vehiculo->Visible) { // id_vehiculo ?>
		<tr id="r_id_vehiculo">
			<td><?php echo $hoja_mantenimientos->id_vehiculo->FldCaption() ?></td>
			<td<?php echo $hoja_mantenimientos->id_vehiculo->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_id_vehiculo">
<span<?php echo $hoja_mantenimientos->id_vehiculo->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->id_vehiculo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_mantenimientos->id_taller->Visible) { // id_taller ?>
		<tr id="r_id_taller">
			<td><?php echo $hoja_mantenimientos->id_taller->FldCaption() ?></td>
			<td<?php echo $hoja_mantenimientos->id_taller->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_id_taller">
<span<?php echo $hoja_mantenimientos->id_taller->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->id_taller->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_mantenimientos->id_tipo_mantenimiento->Visible) { // id_tipo_mantenimiento ?>
		<tr id="r_id_tipo_mantenimiento">
			<td><?php echo $hoja_mantenimientos->id_tipo_mantenimiento->FldCaption() ?></td>
			<td<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->CellAttributes() ?>>
<span id="el_hoja_mantenimientos_id_tipo_mantenimiento">
<span<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->ViewAttributes() ?>>
<?php echo $hoja_mantenimientos->id_tipo_mantenimiento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
