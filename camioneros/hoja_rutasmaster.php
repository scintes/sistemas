<?php

// fecha_ini
// id_cliente
// Origen
// Destino
// estado
// id_vehiculo
// id_tipo_carga
// adelanto
// kg_carga
// tarifa
// porcentaje

?>
<?php if ($hoja_rutas->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $hoja_rutas->TableCaption() ?></h4> -->
<table id="tbl_hoja_rutasmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($hoja_rutas->fecha_ini->Visible) { // fecha_ini ?>
		<tr id="r_fecha_ini">
			<td><?php echo $hoja_rutas->fecha_ini->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->fecha_ini->CellAttributes() ?>>
<span id="el_hoja_rutas_fecha_ini">
<span<?php echo $hoja_rutas->fecha_ini->ViewAttributes() ?>>
<?php echo $hoja_rutas->fecha_ini->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_rutas->id_cliente->Visible) { // id_cliente ?>
		<tr id="r_id_cliente">
			<td><?php echo $hoja_rutas->id_cliente->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->id_cliente->CellAttributes() ?>>
<span id="el_hoja_rutas_id_cliente">
<span<?php echo $hoja_rutas->id_cliente->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_cliente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_rutas->Origen->Visible) { // Origen ?>
		<tr id="r_Origen">
			<td><?php echo $hoja_rutas->Origen->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->Origen->CellAttributes() ?>>
<span id="el_hoja_rutas_Origen">
<span<?php echo $hoja_rutas->Origen->ViewAttributes() ?>>
<?php echo $hoja_rutas->Origen->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_rutas->Destino->Visible) { // Destino ?>
		<tr id="r_Destino">
			<td><?php echo $hoja_rutas->Destino->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->Destino->CellAttributes() ?>>
<span id="el_hoja_rutas_Destino">
<span<?php echo $hoja_rutas->Destino->ViewAttributes() ?>>
<?php echo $hoja_rutas->Destino->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_rutas->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $hoja_rutas->estado->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->estado->CellAttributes() ?>>
<span id="el_hoja_rutas_estado">
<span<?php echo $hoja_rutas->estado->ViewAttributes() ?>>
<?php echo $hoja_rutas->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_rutas->id_vehiculo->Visible) { // id_vehiculo ?>
		<tr id="r_id_vehiculo">
			<td><?php echo $hoja_rutas->id_vehiculo->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->id_vehiculo->CellAttributes() ?>>
<span id="el_hoja_rutas_id_vehiculo">
<span<?php echo $hoja_rutas->id_vehiculo->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_vehiculo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_rutas->id_tipo_carga->Visible) { // id_tipo_carga ?>
		<tr id="r_id_tipo_carga">
			<td><?php echo $hoja_rutas->id_tipo_carga->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->id_tipo_carga->CellAttributes() ?>>
<span id="el_hoja_rutas_id_tipo_carga">
<span<?php echo $hoja_rutas->id_tipo_carga->ViewAttributes() ?>>
<?php echo $hoja_rutas->id_tipo_carga->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_rutas->adelanto->Visible) { // adelanto ?>
		<tr id="r_adelanto">
			<td><?php echo $hoja_rutas->adelanto->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->adelanto->CellAttributes() ?>>
<span id="el_hoja_rutas_adelanto">
<span<?php echo $hoja_rutas->adelanto->ViewAttributes() ?>>
<?php echo $hoja_rutas->adelanto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_rutas->kg_carga->Visible) { // kg_carga ?>
		<tr id="r_kg_carga">
			<td><?php echo $hoja_rutas->kg_carga->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->kg_carga->CellAttributes() ?>>
<span id="el_hoja_rutas_kg_carga">
<span<?php echo $hoja_rutas->kg_carga->ViewAttributes() ?>>
<?php echo $hoja_rutas->kg_carga->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_rutas->tarifa->Visible) { // tarifa ?>
		<tr id="r_tarifa">
			<td><?php echo $hoja_rutas->tarifa->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->tarifa->CellAttributes() ?>>
<span id="el_hoja_rutas_tarifa">
<span<?php echo $hoja_rutas->tarifa->ViewAttributes() ?>>
<?php echo $hoja_rutas->tarifa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hoja_rutas->porcentaje->Visible) { // porcentaje ?>
		<tr id="r_porcentaje">
			<td><?php echo $hoja_rutas->porcentaje->FldCaption() ?></td>
			<td<?php echo $hoja_rutas->porcentaje->CellAttributes() ?>>
<span id="el_hoja_rutas_porcentaje">
<span<?php echo $hoja_rutas->porcentaje->ViewAttributes() ?>>
<?php echo $hoja_rutas->porcentaje->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
