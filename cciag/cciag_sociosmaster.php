<?php

// socio_nro
// cuit_cuil
// propietario
// comercio
// direccion_comercio
// mail
// tel
// cel
// activo

?>
<?php if ($socios->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $socios->TableCaption() ?></h4> -->
<table id="tbl_sociosmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($socios->socio_nro->Visible) { // socio_nro ?>
		<tr id="r_socio_nro">
			<td><?php echo $socios->socio_nro->FldCaption() ?></td>
			<td<?php echo $socios->socio_nro->CellAttributes() ?>>
<span id="el_socios_socio_nro" class="form-group">
<span<?php echo $socios->socio_nro->ViewAttributes() ?>>
<?php echo $socios->socio_nro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->cuit_cuil->Visible) { // cuit_cuil ?>
		<tr id="r_cuit_cuil">
			<td><?php echo $socios->cuit_cuil->FldCaption() ?></td>
			<td<?php echo $socios->cuit_cuil->CellAttributes() ?>>
<span id="el_socios_cuit_cuil" class="form-group">
<span<?php echo $socios->cuit_cuil->ViewAttributes() ?>>
<?php echo $socios->cuit_cuil->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->propietario->Visible) { // propietario ?>
		<tr id="r_propietario">
			<td><?php echo $socios->propietario->FldCaption() ?></td>
			<td<?php echo $socios->propietario->CellAttributes() ?>>
<span id="el_socios_propietario" class="form-group">
<span<?php echo $socios->propietario->ViewAttributes() ?>>
<?php echo $socios->propietario->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->comercio->Visible) { // comercio ?>
		<tr id="r_comercio">
			<td><?php echo $socios->comercio->FldCaption() ?></td>
			<td<?php echo $socios->comercio->CellAttributes() ?>>
<span id="el_socios_comercio" class="form-group">
<span<?php echo $socios->comercio->ViewAttributes() ?>>
<?php echo $socios->comercio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->direccion_comercio->Visible) { // direccion_comercio ?>
		<tr id="r_direccion_comercio">
			<td><?php echo $socios->direccion_comercio->FldCaption() ?></td>
			<td<?php echo $socios->direccion_comercio->CellAttributes() ?>>
<span id="el_socios_direccion_comercio" class="form-group">
<span<?php echo $socios->direccion_comercio->ViewAttributes() ?>>
<?php echo $socios->direccion_comercio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->mail->Visible) { // mail ?>
		<tr id="r_mail">
			<td><?php echo $socios->mail->FldCaption() ?></td>
			<td<?php echo $socios->mail->CellAttributes() ?>>
<span id="el_socios_mail" class="form-group">
<span<?php echo $socios->mail->ViewAttributes() ?>>
<?php echo $socios->mail->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->tel->Visible) { // tel ?>
		<tr id="r_tel">
			<td><?php echo $socios->tel->FldCaption() ?></td>
			<td<?php echo $socios->tel->CellAttributes() ?>>
<span id="el_socios_tel" class="form-group">
<span<?php echo $socios->tel->ViewAttributes() ?>>
<?php echo $socios->tel->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->cel->Visible) { // cel ?>
		<tr id="r_cel">
			<td><?php echo $socios->cel->FldCaption() ?></td>
			<td<?php echo $socios->cel->CellAttributes() ?>>
<span id="el_socios_cel" class="form-group">
<span<?php echo $socios->cel->ViewAttributes() ?>>
<?php echo $socios->cel->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($socios->activo->Visible) { // activo ?>
		<tr id="r_activo">
			<td><?php echo $socios->activo->FldCaption() ?></td>
			<td<?php echo $socios->activo->CellAttributes() ?>>
<span id="el_socios_activo" class="form-group">
<span<?php echo $socios->activo->ViewAttributes() ?>>
<?php echo $socios->activo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
