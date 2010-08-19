<form name="frmsec" method="POST" action="edit.php">
<table class="outer" width="100%" cellspacing="0">
	<tr>
		<td colspan="5" align="right"><a class="addpage" href="<{$newpage_link}>"><?php _e('New Page','docs'); ?></a></td>
	</tr>
	<tr>
		<th colspan="5"><?php _e('Existing Pages','docs'); ?></th>
	</tr>
	<tr class="head" align="center">
		<td><?php _e('ID','docs'); ?></td>
		<td><?php _e('Title','docs'); ?></td>
		<td><?php _e('Order','docs'); ?></td>
		<td><?php _e('Options','docs'); ?></td>
	</tr>
	<?php foreach($sections as $sec): ?>
	<tr align="center"  class="<?php echo tpl_cycle("even,odd"); ?>">
		<td><?php echo $sec['id']; ?></td>
		<td align="left" style="padding-left: <?php echo $sec['jump']*10; ?>px;"><a href="<?php echo $sec['link']; ?>"><?php echo $sec['number']; ?> <?php echo $sec['title']; ?></a></td>
		<td><input type="text" name="orders[<?php echo $sec['id']; ?>]" id="orders[<?php echo $sec['id']; ?>]" size="5" value="<?php echo $sec['order']; ?>" style="text-align: center;" /></td>
		<td>
			<a href="<?php echo $sec['editlink']; ?>"><?php _e('Edit','docs'); ?></a>
		</td>
	</tr>
	<?php endforeach; ?>
	<tr class="foot">
		<td align="right"><img src="<{$xoops_url}>/images/root.gif" alt="" /></td>
		<td colspan="4">
			<input type="submit" value="<{$lang_save}>" class="formButton" onclick="document.forms['frmsec'].op.value='changeorder';" />
		</td>

	</tr>


</table>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="op" value="" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
</form>
