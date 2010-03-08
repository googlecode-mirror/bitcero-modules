<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Categories Management','admin_team'); ?></h1>

<table class="outer" cellspacing="1">
	<tr class="head" align="center">
		<th width="40"><?php _e('ID','admin_team'); ?></th>
		<th align="left"><?php _e('Nombre','admin_team'); ?></th>
		<th><?php _e('Equipos','admin_team'); ?></th>
		<th><?php _e('Opciones','admin_team'); ?></th>
	</tr>
	<?php foreach($categos as $cat): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center">
			<td><strong><?php echo $cat['id']; ?></strong></td>
			<td align="left"><?php echo $cat['name']; ?></td>
			<td><?php echo $cat['teams']; ?></cat>
			<td>
				<a href="?op=edit&amp;id=<?php echo $cat['id']; ?>"><?php _e('Edit','admin_team'); ?></a> |
				<a href="?op=delete&amp;id=<?php echo $cat['id']; ?>" onclick="return confirm('<?php _e('¿Realmente deseas eliminar esta categoría?','admin_team'); ?>\n\n<?php echo $cat['name']; ?>');"><?php _e('Delete','admin_team'); ?></a>
			</td>
		</tr>
	<?php endforeach; ?>
</table>