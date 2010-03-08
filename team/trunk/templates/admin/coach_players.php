<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Manejo de Jugadores','admin_team'); ?></h1>

<div class="even">
<form name="selTeam" method="get" action="players.php">
	<?php _e('Equipo:','admin_team'); ?>
	<select name="team" onchange="submit();">
		<option value="0"<?php if($team==0): ?> selected="selected"<?php endif; ?>><?php _e('Seleccionar...','admin_team'); ?></option>
		<?php foreach($teams as $steam): ?>
			<option value="<?php echo $steam['id']; ?>"<?php if($team==$steam['id']): ?> selected="selected"<?php endif; ?><?php echo $steam['name']; ?></option>
		<?php endforeach; ?>
	</select>
</form>
</div>
<{if $coachs}>
<div class="foot" style="margin-top: 1px; color: #333; text-align: right;">
	<strong><{$lang_coachs}></strong> 
	<{foreach item=coach from=$coachs}>
		<a href="coachs.php?op=edit&amp;id=<{$coach.id}>"><{$coach.name}></a> |
	<{/foreach}>
</div>
<{/if}><br />
<form name="frmPlayers" method="post" action="players.php">
<table class="outer" cellspacing="1">
	<tr><th colspan="8"><{$lang_existing}></th></tr>
	<tr class="head" align="center">
		<td width="20"><input type="checkbox" name="checkall" onclick="xoopsCheckAll('frmPlayers','checkall');" /></td>
		<td width="30"><{$lang_id}></td>
		<td width="50"><{$lang_image}></td>
		<td><{$lang_name}></td>
		<td><{$lang_number}></td>
		<td><{$lang_age}></td>
		<td><{$lang_date}></td>
		<td><{$lang_options}></td>
	</tr>
	<{foreach item=player from=$players}>
		<tr class="<{cycle values="even,odd"}>" align="center">
			<td><input type="checkbox" name="players[]" value="<{$payer.id}>" /></td>
			<td><strong><{$player.id}></strong></td>
			<td>
				<{if $player.image!=''}>
					<img src="<{$xoops_url}>/uploads/teams/players/ths/<{$player.image}>" alt="" />
				<{else}>
					&nbsp;
				<{/if}>
			</td>
			<td align="left"><{$player.name}></td>
			<td><{$player.number}></td>
			<td><{$player.age}></td>
			<td><{$player.date}></td>
			<td>
				<a href="?op=edit&amp;id=<{$player.id}>&amp;team=<{$team}>"><{$lang_edit}></a> |
				<a href="?team=<{$team}>&amp;op=delete&amp;players[]=<{$player.id}>" onclick="return confirm('<{$lang_confirmdel}>\n\n<{$player.name}>')"><{$lang_delete}></a>
			</td>
		</tr>
	<{/foreach}>
	<tr class="foot">
		<td align="right">
			<img src="../../../images/root.gif" alt="" />
		</td>
		<td colspan="7"><input type="submit" value="<{$lang_delete}>" class="formButton" onclick="return confirm('<{$lang_confirmdels}>');" />
	</tr>
</table>
<input type="hidden" name="op" value="delete" />
<input type="hidden" name="team" value="<{$team}>" />
</form>