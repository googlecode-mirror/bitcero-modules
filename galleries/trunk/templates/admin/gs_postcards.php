<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Postcards Management','admin_galleries'); ?></h1>

<form name="frmPost"  method="POST" id="frm-postcards" action="postcards.php">
<div class="gs_options">
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
	<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center">	
		<td><input type="checkbox" name="ids[]" value="<{$post.id}>" /></td>
		<td><strong><{$post.id}></strong></td>
		<td align="left"><a href="<{$post.link}>"><{$post.title}></a></td>
		<td><{$post.date}></td>
		<td><{$post.name}></td>
		<td><{$post.view}></td>
		<td><a href="./postcards.php?op=delete&amp;ids=<{$post.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>"><{$lang_del}></a></td>
	</tr>
	<?php endforeach; ?>
    </tbody>
</table>
<input type="hidden" name="op" />
<input type="hidden" name="limit" value="<{$limit}>" />
</form>
