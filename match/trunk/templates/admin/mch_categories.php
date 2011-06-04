<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Teams Categories','match'); ?></h1>

<form name="frmCategos" id="frm-categos" method="POST" action="categories.php">
<div class="mch_options">
	<select name="action" id="bulk-top">
		<option value=""><?php _e('Bulk actions...','match'); ?></option>
		<option value="delete"><?php _e('Delete','match'); ?></option>
		<option value="active"><?php _e('Enable categories','match'); ?></option>
		<option value="desactive"><?php _e('Disable categories','match'); ?></option>
	</select>
	<input type="button" id="the-op-top" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-categos');" />
    <?php echo $match_extra_options; ?>
</div>
<table class="outer" cellspacing="1">
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-categos").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><?php _e('ID','match'); ?></th>
		<th align="left"><?php _e('Name','match'); ?></th>
        <th><?php _e('Short name','match'); ?></th>
        <th align="left"><?php _e('Description','match'); ?></th>
		<th><?php _e('Active','match'); ?></th>
	</tr>
    </thead>
    
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-categos").toggleCheckboxes(":not(#checkall2)");' /></th>
        <th width="30"><?php _e('ID','match'); ?></th>
        <th align="left"><?php _e('Name','match'); ?></th>
        <th><?php _e('Short name','match'); ?></th>
        <th align="left"><?php _e('Description','match'); ?></th>
        <th><?php _e('Active','match'); ?></th>
    </tr>
    </tfoot>
    
    <tbody>
	<?php foreach($categories as $cat): ?>
	<tr align="center" class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
		<td><input type="checkbox" name="ids[]" value="<?php echo $cat['id']; ?>" id="item-<?php echo $cat['id']; ?>" /></td>
		<td><strong><?php echo $cat['id']; ?></strong></td>
		<td align="left"><a href="<?php echo $cat['link']; ?>"><?php echo str_repeat('&#151;', $cat['indent']).' '.$cat['name']; ?></a>
        <span class="rmc_options">
            <a href="teams.php?category=<?php echo $cat['id']; ?>"><?php _e('Teams','match'); ?></a> |
            <a href="./categories.php?action=edit&amp;id=<?php echo $cat['id']; ?>"><?php _e('Edit','match'); ?></a> | 
            <a href="javascript:;" onclick="select_option(<?php echo $cat['id']; ?>,'delete','frm-categos');"><?php _e('Delete','match'); ?></a>
        </span>
        </td>
        <td align="center"><?php echo $cat['nameid']; ?></td>
        <td align="left"><?php echo $cat['description']; ?></td>
		<td><?php if($cat['active']): ?><img src="../images/ok.png" /><?php else: ?><img src="../images/no.png" /><?php endif; ?></td>
	</tr>
	<?php endforeach; ?>
    </tbody>
</table>
<div class="mch_options">
	<select name="actionb" id="bulk-bottom">
		<option value=""><?php _e('Bulk actions...','match'); ?></option>
		<option value="delete"><?php _e('Delete','match'); ?></option>
		<option value="active"><?php _e('Enable categories','match'); ?></option>
		<option value="desactive"><?php _e('Disable categories','match'); ?></option>
	</select>
	<input type="button" id="the-op-bottom" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-categos');" />
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
