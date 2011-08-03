<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Postcards Management','admin_galleries'); ?></h1>

<form name="frmPost"  method="POST" id="frm-postcards" action="postcards.php">
<div class="gs_options">
    <?php $nav->display(false); ?>
    <select name="op" id="select-op-top">
        <option value=""><?php _e('Bulk actions...','admin_galleries'); ?></option>
        <option value="delete"><?php _e('Delete','admin_galleries'); ?></option>
    </select>
</div>
<table class="outer" width="100%" cellspacing="1">
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#frm-postcards").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><?php _e('ID','admin_galleries'); ?></th>
		<th><?php _e('Title','admin_galleries'); ?></th>
		<th><?php _e('Date','admin_galleries'); ?></th>
		<th><?php _e('Sender','admin_galleries'); ?></th>
		<th><?php _e('View','admin_galleries'); ?></th>
	</tr>
    </thead>
    
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" name="checkall2" id="checkall2" onclick='$("#frm-postcards").toggleCheckboxes(":not(#checkall2)");' /></th>
        <th width="30"><?php _e('ID','admin_galleries'); ?></th>
        <th><?php _e('Title','admin_galleries'); ?></th>
        <th><?php _e('Date','admin_galleries'); ?></th>
        <th><?php _e('Sender','admin_galleries'); ?></th>
        <th><?php _e('View','admin_galleries'); ?></th>
    </tr>
    </tfoot>
    
    <tbody>
    <?php if(empty($posts)): ?>
    <tr class="even">
        <td align="center" colspan="6"><?php _e('There are not postcards created yet!','admin_galleries'); ?></td>
    </tr>
    <?php endif; ?>
	<?php foreach($posts as $post): ?>
	<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">	
		<td><input type="checkbox" name="ids[]" value="<?php echo $post['id']; ?>" id="item-<?php echo $post['id']; ?>" /></td>
		<td><strong><?php echo $post['id']; ?></strong></td>
		<td align="left">
            <a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a>
            <span class="rmc_options">
                <a href="#" class="gs_delete_option" id="delete-<?php echo $post['id']; ?>"><?php _e('Delete','galleries'); ?></a>
            </span>
        </td>
		<td><?php echo $post['date']; ?></td>
		<td><?php echo $post['name']; ?></td>
		<td><?php echo $post['view']; ?></td>
	</tr>
	<?php endforeach; ?>
    </tbody>
</table>
<div class="gs_options">
    <?php $nav->display(false); ?>
    <select name="opb" id="select-op-bottom">
        <option value=""><?php _e('Bulk actions...','admin_galleries'); ?></option>
        <option value="delete"><?php _e('Delete','admin_galleries'); ?></option>
    </select>
</div>
<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
