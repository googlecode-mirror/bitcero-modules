<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Manejo de Jugadores','admin_team'); ?></h1>

<div class="even">
<form name="selTeam" method="get" action="players.php">
	<?php _e('Equipo:','admin_team'); ?>
	<select name="team" onchange="submit();">
		<option value="0"<?php if($gteam==0): ?> selected="selected"<?php endif; ?>><?php _e('Seleccionar...','admin_team'); ?></option>
		<?php foreach($teams as $steam): ?>
			<option value="<?php echo $steam['id']; ?>"<?php if($gteam==$steam['id']): ?> selected="selected"<?php endif; ?>><?php echo $steam['name']; ?></option>
		<?php endforeach; ?>
	</select>
</form>
</div>
<?php if($coachs): ?>
<div class="foot" style="margin-top: 1px; color: #333; text-align: right;">
	<strong><?php _e('Entrenadores:','admin_team'); ?></strong>  &nbsp;
	<?php foreach($coachs as $id => $coach): ?>
		<a href="coachs.php?op=edit&amp;id=<?php echo $coach['id']; ?>"><?php echo $coach['name']; ?></a>
        <?php if($id<count($coachs)-1): ?>|<?php endif; ?>
	<?php endforeach; ?>
</div>
<?php endif; ?><br />
<form name="frmPlayers" method="post" action="players.php">
<table class="outer" cellspacing="1">
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" name="checkall" onclick="xoopsCheckAll('frmPlayers','checkall');" /></th>
		<th width="30"><?php _e('ID','admin_team'); ?></th>
		<th width="50"><?php _e('Imagen','admin_team'); ?></th>
		<th><?php _e('Nombre','admin_team'); ?></th>
		<th><?php _e('Número','admin_team'); ?></th>
		<th><?php _e('Edad','admin_team'); ?></th>
		<th><?php _e('Alta','admin_team'); ?></th>
		<th><?php _e('Opciones','admin_team'); ?></th>
	</tr>
	<?php foreach($players as $player): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center">
			<td><input type="checkbox" name="players[]" value="<?php echo $player['id']; ?>" /></td>
			<td><strong><?php echo $player['id']; ?></strong></td>
			<td>
				<?php if($player['image']!=''): ?>
					<img width="40" src="<?php echo XOOPS_URL; ?>/uploads/teams/players/ths/<?php echo $player['image']; ?>" alt="" />
				<?php else: ?>
					&nbsp;
				<?php endif; ?>
			</td>
			<td align="left"><?php echo $player['name']; ?></td>
			<td><?php echo $player['number']; ?></td>
			<td><?php echo $player['age']; ?></td>
			<td><?php echo $player['date']; ?></td>
			<td>
				<a href="?op=edit&amp;id=<?php echo $player['id']; ?>&amp;team=<?php echo $gteam; ?>"><?php _e('Editar','admin_team'); ?></a> |
				<a href="?team=<?php echo $gteam; ?>&amp;op=delete&amp;players[]=<?php echo $player['id']; ?>" onclick="return confirm('<?php echo _e('¿Realmente deseas eliminar este jugador?','admin_team'); ?>\n\n<?php echo $player['name']; ?>')"><?php _e('Eliminar','admin_team'); ?></a>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr class="foot">
		<td align="right">
			<img src="../../../images/root.gif" alt="" />
		</td>
		<td colspan="7"><input type="submit" value="<?php _e('Eliminar','admin_team'); ?>" class="formButton" onclick="return confirm('<?php _e('¿Realmente deseas eliminar los jugadores seleccionados?','admin_team'); ?>');" />
	</tr>
</table>
<input type="hidden" name="op" value="delete" />
<input type="hidden" name="team" value="<?php echo $gteam; ?>" />
</form>