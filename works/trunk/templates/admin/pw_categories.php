<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Works Categories','admin_works'); ?></h1>

<form name="frmCategos" method="POST" action="categos.php">
<div class="pw_options">
	<select name="op">
		<option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
		<option value="update"><?php _e('Save changes','admin_works'); ?></option>
		<option value="delete"><?php _e('Delete','admin_works'); ?></option>
		<option value="active"><?php _e('Enable categories','admin_works'); ?></option>
		<option value="desactive"><?php _e('Disable categories','admin_works'); ?></option>
	</select>
	<input type="button" value="<?php _e('Apply','admin_works'); ?>" />
</div>
<table class="outer" cellspacing="1">
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frmCategos','checkAll')" /></th>
		<th width="30"><?php _e('ID','admin_works'); ?></th>
		<th align="left"><?php _e('Name','admin_works'); ?></th>
		<th><?php _e('Works','admin_works'); ?></th>
		<th><?php _e('Active','admin_works'); ?></th>
		<th><?php _e('Order','admin_works'); ?></th>
		<th><?php _e('Options','admin_works'); ?></th>
	</tr>
	<?php foreach($categories as $cat): ?>
	<tr align="center" class="<?php echo tpl_cycle('even,odd'); ?>">
		<td><input type="checkbox" name="ids[]" value="<?php echo $cat['id']; ?>" /></td>
		<td><strong><?php echo $cat['id']; ?></strong></td>
		<td align="left"><a href="<?php echo $cat['link']; ?>"><?php echo $cat['name']; ?></a></td>
		<td><?php echo $cat['works']; ?></td>
		<td><?php if($cat['active']): ?><img src="<?php echo PW_URL; ?>/images/ok.png" /><?php else: ?><img src="<?php echo PW_URL; ?>/images/no.png" /><?php endif; ?></td>
		<td><input type="text" name="order[<?php echo $cat['id']; ?>]" value="<?php echo $cat['order']; ?>" size="3" style="text-align: center;" /></td>
		<td><a href="./categos.php?op=edit&amp;id=<?php echo $cat['id']; ?>"><?php _e('Edit','admin_works'); ?></a> &bull; <a href="./categos.php?op=delete&amp;ids=<?php echo $cat['id']; ?>"><?php _e('Delete','admin_works'); ?></a></td>
	</tr>
	<?php endforeach; ?>
</table>
<div class="pw_options">
	<select name="op">
		<option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
		<option value="update"><?php _e('Save changes','admin_works'); ?></option>
		<option value="delete"><?php _e('Delete','admin_works'); ?></option>
		<option value="active"><?php _e('Enable categories','admin_works'); ?></option>
		<option value="desactive"><?php _e('Disable categories','admin_works'); ?></option>
	</select>
	<input type="button" value="<?php _e('Apply','admin_works'); ?>" />
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
