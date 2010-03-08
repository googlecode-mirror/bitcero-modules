<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Manejo de Equipos','admin_team'); ?></h1>

<form name="frmTeams" method="post" action="teams.php">
<table class="outer" cellspacing="1">
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" name="checkall" onclick="xoopsCheckAll('frmTeams','checkall');" /></th>
		<th width="30"><?php _e('ID','admin_team'); ?></th>
		<th width="50" nowrap="nowrap"><?php _e('Imagen','admin_team'); ?></th>
		<th><?php _e('Nombre','admin_team'); ?></th>
		<th><?php _e('Categoría','admin_team'); ?></th>
		<th><?php _e('Entrenadores','admin_team'); ?></th>
		<th><?php _e('Alta','admin_team'); ?></th>
		<th><?php _e('Opciones','admin_team'); ?></th>
	</tr>
	<?php foreach($teams as $team): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center">
			<td><input type="checkbox" name="teams[<?php echo $team['id']; ?>]" value="<?php echo $team['id']; ?>" /></td>
			<td><strong><?php echo $team['id']; ?></strong></td>
			<td><?php if($team['image']!=''): ?><img src="<?php echo XOOPS_URL; ?>/uploads/teams/ths/<?php echo $team['image']; ?>" alt="" /><?php else: ?>&nbsp;<?php endif; ?></td>
			<td align="left"><h2><?php echo $team['name']; ?></h2></td>
			<td><strong><?php echo $team['catego']; ?></strong></td>
			<td><?php echo $team['coachs']; ?></td>
			<td><?php echo $team['date']; ?></td>
			<td>
				<a href="?op=edit&amp;id=<?php echo $team['id']; ?>"><?php _e('Edit','admin_team'); ?></a> |
				<a href="?op=delete&amp;teams[]=<?php echo $team['id']; ?>" onclick="return confirm('<?php _e('¿Realmente deseas eliminar este equipo?','admin_team'); ?>\n\n<?php echo $team['name']; ?> (<?php echo $team['catego']; ?>)');"><?php _e('Eliminar','admin_team'); ?></a> |
				<a href="players.php?team=<?php echo $team['id']; ?>"><?php _e('Jugadores','admin_team'); ?></a>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr class="foot">
		<td align="right">
			<img src="../../../images/root.gif" alt="" />
		</td>
		<td colspan="7">
			<input type="submit" value="<?php _e('Eliminar','admin_team'); ?>" class="formButton" onclick="return confirm('<?php _e('¿Realmente deseas eliminar los equipo seleccionados?','admin_team'); ?>');" />
		</td>
	</tr>
</table>
<input type="hidden" name="op" value="delete" />
</form>