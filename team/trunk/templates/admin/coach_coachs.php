<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Manejo de Entrenadores','admin_team'); ?></h1>

<form name="frmCoachs" method="post" action="coachs.php">
<table class="outer" cellspacing="1">
	<tr align="center">
		<th width="20">
			<input type="checkbox" name="checkall" onchange="xoopsCheckAll('frmCoachs','checkall');" />
		</th>
		<th><?php _e('ID','admin_team'); ?></th>
		<th nowrap="nowrap"><?php _e('Imagen','admin_team'); ?></th>
		<th><?php _e('Nombre','admin_team'); ?></th>
		<th><?php _e('Equipos','admin_team'); ?></th>
		<th><?php _e('Opciones','admin_team'); ?></th>
	</tr>
	<?php foreach($coachs as $coach): ?>
		<tr align="center" class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
			<td><input type="checkbox" name="coachs[<?php echo $coach['id']; ?>]" value="<?php echo $coach['id']; ?>" /></td>
			<td><strong><?php echo $coach['id']; ?></strong></td>
			<td width="60">
				<?php if($coach['image']!=''): ?>
					<img width="40" src="<?php echo XOOPS_URL; ?>/uploads/teams/coachs/ths/<?php echo $coach['image']; ?>" alt="" /><?php else: ?>&nbsp;<?php endif; ?>
			</td>
			<td align="left"><?php echo $coach['name']; ?></td>
			<td><?php echo $coach['teams']; ?></td>
			<td>
				<a href="?op=edit&amp;id=<?php echo $coach['id']; ?>"><?php _e('Editar','admin_team'); ?></a> |
				<a href="?op=delete&amp;coachs[]=<?php echo $coach['id']; ?>" onclick="return confirm('<?php _e('¿Realmente deseas eliminar estos entrenadores?','admin_team'); ?>\n\n<?php echo $coach['name']; ?>');"><?php _e('Eliminar','admin_team'); ?></a>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr class="foot">
		<td align="right">
			<img src="../../../images/root.gif" alt="" />
		</td>
		<td colspan="5">
			<input type="submit" class="formButton" value="<{$lang_delete}>" />
		</td>
	</tr>
</table>
<input type="hidden" name="op" value="delete" />
</form>