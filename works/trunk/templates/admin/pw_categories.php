<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Works Categories','works'); ?></h1>

<form name="frmCategos" id="frm-categos" method="POST" action="categos.php">
<div class="pw_options">
	<select name="op" id="bulk-top">
		<option value=""><?php _e('Bulk actions...','works'); ?></option>
		<option value="update"><?php _e('Save changes','works'); ?></option>
		<option value="delete"><?php _e('Delete','works'); ?></option>
		<option value="active"><?php _e('Enable categories','works'); ?></option>
		<option value="desactive"><?php _e('Disable categories','works'); ?></option>
	</select>
	<input type="button" id="the-op-top" value="<?php _e('Apply','works'); ?>" onclick="before_submit('frm-categos');" />
    <?php echo $works_extra_options; ?>
</div>
<table class="outer" cellspacing="1">
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-categos").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><?php _e('ID','works'); ?></th>
		<th align="left"><?php _e('Name','works'); ?></th>
        <th><?php _e('Short name','works'); ?></th>
        <th align="left"><?php _e('Description','works'); ?></th>
		<th><?php _e('Works','works'); ?></th>
		<th><?php _e('Active','works'); ?></th>
		<th><?php _e('Order','works'); ?></th>
	</tr>
    </thead>
    
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-categos").toggleCheckboxes(":not(#checkall2)");' /></th>
        <th width="30"><?php _e('ID','works'); ?></th>
        <th align="left"><?php _e('Name','works'); ?></th>
        <th><?php _e('Short name','works'); ?></th>
        <th align="left"><?php _e('Description','works'); ?></th>
        <th><?php _e('Works','works'); ?></th>
        <th><?php _e('Active','works'); ?></th>
        <th><?php _e('Order','works'); ?></th>
    </tr>
    </tfoot>
    
    <tbody>
	<?php foreach($categories as $cat): ?>
	<tr align="center" class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
		<td><input type="checkbox" name="ids[]" value="<?php echo $cat['id']; ?>" id="item-<?php echo $cat['id']; ?>" /></td>
		<td><strong><?php echo $cat['id']; ?></strong></td>
		<td align="left"><a href="<?php echo $cat['link']; ?>"><?php echo $cat['name']; ?></a>
        <span class="rmc_options">
            <a href="./categos.php?op=edit&amp;id=<?php echo $cat['id']; ?>"><?php _e('Edit','works'); ?></a> | 
            <a href="javascript:;" onclick="select_option(<?php echo $cat['id']; ?>,'delete','frm-categos');"><?php _e('Delete','works'); ?></a>
        </span>
        </td>
        <td align="center"><?php echo $cat['nameid']; ?></td>
        <td align="left"><?php echo $cat['description']; ?></td>
		<td><?php echo $cat['works']; ?></td>
		<td><?php if($cat['active']): ?><img src="<?php echo PW_URL; ?>/images/ok.png" /><?php else: ?><img src="<?php echo PW_URL; ?>/images/no.png" /><?php endif; ?></td>
		<td><input type="text" name="order[<?php echo $cat['id']; ?>]" value="<?php echo $cat['order']; ?>" size="3" style="text-align: center;" /></td>
	</tr>
	<?php endforeach; ?>
    </tbody>
</table>
<div class="pw_options">
	<select name="opb" id="bulk-bottom">
		<option value=""><?php _e('Bulk actions...','works'); ?></option>
		<option value="update"><?php _e('Save changes','works'); ?></option>
		<option value="delete"><?php _e('Delete','works'); ?></option>
		<option value="active"><?php _e('Enable categories','works'); ?></option>
		<option value="desactive"><?php _e('Disable categories','works'); ?></option>
	</select>
	<input type="button" id="the-op-bottom" value="<?php _e('Apply','works'); ?>" onclick="before_submit('frm-categos');" />
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
