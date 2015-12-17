<?php

// id
// mes
// anio
// fecha
// monto
// id_socio

?>
<?php if ($deudas->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $deudas->TableCaption() ?></h4> -->
<table id="tbl_deudasmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($deudas->id->Visible) { // id ?>
		<tr id="r_id">
			<td><?php echo $deudas->id->FldCaption() ?></td>
			<td<?php echo $deudas->id->CellAttributes() ?>>
<span id="el_deudas_id">
<span<?php echo $deudas->id->ViewAttributes() ?>>
<?php echo $deudas->id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mes->Visible) { // mes ?>
		<tr id="r_mes">
			<td><?php echo $deudas->mes->FldCaption() ?></td>
			<td<?php echo $deudas->mes->CellAttributes() ?>>
<span id="el_deudas_mes">
<span<?php echo $deudas->mes->ViewAttributes() ?>>
<?php echo $deudas->mes->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->anio->Visible) { // anio ?>
		<tr id="r_anio">
			<td><?php echo $deudas->anio->FldCaption() ?></td>
			<td<?php echo $deudas->anio->CellAttributes() ?>>
<span id="el_deudas_anio">
<span<?php echo $deudas->anio->ViewAttributes() ?>>
<?php echo $deudas->anio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->fecha->Visible) { // fecha ?>
		<tr id="r_fecha">
			<td><?php echo $deudas->fecha->FldCaption() ?></td>
			<td<?php echo $deudas->fecha->CellAttributes() ?>>
<span id="el_deudas_fecha">
<span<?php echo $deudas->fecha->ViewAttributes() ?>>
<?php echo $deudas->fecha->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->monto->Visible) { // monto ?>
		<tr id="r_monto">
			<td><?php echo $deudas->monto->FldCaption() ?></td>
			<td<?php echo $deudas->monto->CellAttributes() ?>>
<span id="el_deudas_monto">
<span<?php echo $deudas->monto->ViewAttributes() ?>>
<?php echo $deudas->monto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->id_socio->Visible) { // id_socio ?>
		<tr id="r_id_socio">
			<td><?php echo $deudas->id_socio->FldCaption() ?></td>
			<td<?php echo $deudas->id_socio->CellAttributes() ?>>
<span id="el_deudas_id_socio">
<span<?php echo $deudas->id_socio->ViewAttributes() ?>>
<?php echo $deudas->id_socio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
