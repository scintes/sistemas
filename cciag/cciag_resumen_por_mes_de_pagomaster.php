<?php

// socio_nro
// comercio
// mes
// anio
// Pagos

?>
<?php if ($resumen_por_mes_de_pago->Visible) { ?>
<table cellspacing="0" id="t_resumen_por_mes_de_pago" class="ewGrid"><tr><td>
<table id="tbl_resumen_por_mes_de_pagomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($resumen_por_mes_de_pago->socio_nro->Visible) { // socio_nro ?>
		<tr id="r_socio_nro">
			<td><?php echo $resumen_por_mes_de_pago->socio_nro->FldCaption() ?></td>
			<td<?php echo $resumen_por_mes_de_pago->socio_nro->CellAttributes() ?>>
<span id="el_resumen_por_mes_de_pago_socio_nro" class="control-group">
<span<?php echo $resumen_por_mes_de_pago->socio_nro->ViewAttributes() ?>>
<?php echo $resumen_por_mes_de_pago->socio_nro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resumen_por_mes_de_pago->comercio->Visible) { // comercio ?>
		<tr id="r_comercio">
			<td><?php echo $resumen_por_mes_de_pago->comercio->FldCaption() ?></td>
			<td<?php echo $resumen_por_mes_de_pago->comercio->CellAttributes() ?>>
<span id="el_resumen_por_mes_de_pago_comercio" class="control-group">
<span<?php echo $resumen_por_mes_de_pago->comercio->ViewAttributes() ?>>
<?php echo $resumen_por_mes_de_pago->comercio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resumen_por_mes_de_pago->mes->Visible) { // mes ?>
		<tr id="r_mes">
			<td><?php echo $resumen_por_mes_de_pago->mes->FldCaption() ?></td>
			<td<?php echo $resumen_por_mes_de_pago->mes->CellAttributes() ?>>
<span id="el_resumen_por_mes_de_pago_mes" class="control-group">
<span<?php echo $resumen_por_mes_de_pago->mes->ViewAttributes() ?>>
<?php echo $resumen_por_mes_de_pago->mes->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resumen_por_mes_de_pago->anio->Visible) { // anio ?>
		<tr id="r_anio">
			<td><?php echo $resumen_por_mes_de_pago->anio->FldCaption() ?></td>
			<td<?php echo $resumen_por_mes_de_pago->anio->CellAttributes() ?>>
<span id="el_resumen_por_mes_de_pago_anio" class="control-group">
<span<?php echo $resumen_por_mes_de_pago->anio->ViewAttributes() ?>>
<?php echo $resumen_por_mes_de_pago->anio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resumen_por_mes_de_pago->Pagos->Visible) { // Pagos ?>
		<tr id="r_Pagos">
			<td><?php echo $resumen_por_mes_de_pago->Pagos->FldCaption() ?></td>
			<td<?php echo $resumen_por_mes_de_pago->Pagos->CellAttributes() ?>>
<span id="el_resumen_por_mes_de_pago_Pagos" class="control-group">
<span<?php echo $resumen_por_mes_de_pago->Pagos->ViewAttributes() ?>>
<?php echo $resumen_por_mes_de_pago->Pagos->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
