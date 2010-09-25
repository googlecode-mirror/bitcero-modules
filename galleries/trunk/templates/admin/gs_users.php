<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Users Management','admin_galleries'); ?></h1>

<form name="frmNav" method="POST" action="users.php">
<table width="100%" class="outer" cellspacing="1">
	<tr class="even">
		<td align="center">
			<input type="text" size="5" name="limit" value="<?php echo $limit; ?>" />
			<input type="submit" class="formButton" value="<?php _e('Apply','admin_galleries'); ?>" /> &nbsp;
			<?php _e('Search:','admin_galleries'); ?>
			<input type="text" size="20" name="search" value="<?php echo $search; ?>" />
			<input type="submit" class="formButton" value="<?php _e('Apply','admin_galleries'); ?>" /> 
			&nbsp; &nbsp;
			<a href="users.php"><?php _e('Show all','admin_galleries'); ?></a>
		</td>
	</tr>
</table>
</form>

<form name="frmUsers" method="POST" id="frm-users" action="users.php">
<div class="gs_options">
	<?php echo $nav->display(); ?>
    <select name="op" id="select-op-top">
        <option value="" selected="selected"><?php _e('Bulk actions...','admin_galleries'); ?></option>
        <option value="block"><?php _e('Block user','admin_galleries'); ?></option>
        <option value="delete"><?php _e('Delete','admin_galleries'); ?></option>
    </select>
    <input type="button" value="<?php _e('Apply','admin_galleries'); ?>" id="op-top" />
</div>
<table width="100%" class="outer" cellspacing="1">
	<thead>
	<tr align="center">
		<th width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#frm-users").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><?php _e('ID','admin_galleries'); ?></th>
		<th><?php _e('Name','admin_galleries'); ?></th>
		<th><?php _e('Quota','admin_galleries'); ?></th>
		<th><?php _e('Used quota','admin_galleries'); ?></th>
		<th><?php _e('Blocked','admin_galleries'); ?></th>
		<th><?php _e('Pictures','admin_galleries'); ?></th>
		<th><?php _e('Albumes','admin_galleries'); ?></th>
		<th><?php _e('Date','admin_galleries'); ?></th>
	</tr>
	</thead>
	
	<tfoot>
	<tr align="center">
		<th width="20"><input type="checkbox" name="checkall2" id="checkall2" onclick='$("#frm-users").toggleCheckboxes(":not(#checkall2)");' /></th>
		<th width="30"><?php _e('ID','admin_galleries'); ?></th>
		<th><?php _e('Name','admin_galleries'); ?></th>
		<th><?php _e('Quota','admin_galleries'); ?></th>
		<th><?php _e('Used quota','admin_galleries'); ?></th>
		<th><?php _e('Blocked','admin_galleries'); ?></th>
		<th><?php _e('Pictures','admin_galleries'); ?></th>
		<th><?php _e('Albumes','admin_galleries'); ?></th>
		<th><?php _e('Date','admin_galleries'); ?></th>
	</tr>
	</tfoot>
	
	<tbody>
	<?php if(empty($users)): ?>
	<tr class="even"><td colspan="9" align="center"><?php _e('There are not users registered yet!','admin_galleries'); ?></td></tr>
	<?php endif; ?>
	<?foreach($users as $user): ?>
	<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
		<td><input type="checkbox" name="ids[]" value="<?php echo $user['id']; ?>" id="item-<?php echo $user['id']; ?>" /></td>
		<td><strong><?php echo $user['id']; ?></strong></td>
		<td align="left"><a href="<?php echo $user['url']; ?>"><?php echo $user['uname']; ?></a>
		<span class="rmc_options">
			<a href="./users.php?op=edit&amp;id=<?php echo $user['id']; ?>"><?php _e('Edit','admin_galleries'); ?></a> |
			<a href="javascript:;" class="gs_delete_option" id="delete-<?php echo $user['id']; ?>"><?php _e('Delete','admin_galleries'); ?></a>
		</span>
		</td>
		<td><?php echo $user['quota']; ?></td>
		<td><?php echo $user['used']; ?></td>
		<td><?php if($user['blocked']): ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/lock.png" /><?php else: ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/unlock.png" /><?php endif; ?></td>
		<td><?php echo $user['pics']; ?></td>
		<td><?php echo $user['sets']; ?></td>
		<td><?php echo $user['date']; ?></td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<div class="gs_options">
	<?php echo $nav->display(); ?>
    <select name="opb" id="select-op-bottom">
        <option value="" selected="selected"><?php _e('Bulk actions...','admin_galleries'); ?></option>
        <option value="block"><?php _e('Block user','admin_galleries'); ?></option>
        <option value="delete"><?php _e('Delete','admin_galleries'); ?></option>
    </select>
    <input type="button" value="<?php _e('Apply','admin_galleries'); ?>" id="op-bottom" />
</div>
<input type="hidden" name="pag" value="<?php echo $page; ?>" />
<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
<input type="hidden" name="search" value="<?php echo $search; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
