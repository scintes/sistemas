<?php

// id
// rubro
// activa

?>
<?php if ($rubros->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $rubros->TableCaption() ?></h4> -->
<table id="tbl_rubrosmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($rubros->id->Visible) { // id ?>
		<tr id="r_id">
			<td><?php echo $rubros->id->FldCaption() ?></td>
			<td<?php echo $rubros->id->CellAttributes() ?>>
<span id="el_rubros_id">
<span<?php echo $rubros->id->ViewAttributes() ?>>
<?php echo $rubros->id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rubros->rubro->Visible) { // rubro ?>
		<tr id="r_rubro">
			<td><?php echo $rubros->rubro->FldCaption() ?></td>
			<td<?php echo $rubros->rubro->CellAttributes() ?>>
<span id="el_rubros_rubro">
<span<?php echo $rubros->rubro->ViewAttributes() ?>>
<?php echo $rubros->rubro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rubros->activa->Visible) { // activa ?>
		<tr id="r_activa">
			<td><?php echo $rubros->activa->FldCaption() ?></td>
			<td<?php echo $rubros->activa->CellAttributes() ?>>
<span id="el_rubros_activa">
<span<?php echo $rubros->activa->ViewAttributes() ?>>
<?php echo $rubros->activa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
