<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Tags Management','admin_galleries'); ?></h1>

<form name="frmNav" method="post" action="tags.php">
	<table class="outer" width="100%" cellspacing="1">
		<tr class="even">
			<td align="center">
				<input type="text" size="5" name="limit" value="<?php echo $limit; ?>" />
				<input type="submit" class="formButton" value="<?php _e('Apply','admin_galleries'); ?>" /> &nbsp;
				<?php echo _e('Search:','admin_galleries'); ?>
				<input type="text" size="20" name="search" value="<?php echo $search; ?>" />
				<input type="submit" class="formButton" value="<?php _e('Apply','admin_galleries'); ?>" />
				&nbsp; &nbsp;
				<a href="tags.php"><?php _e('Show all','admin_galleries'); ?></a>
			</td>
		</tr>
	</table>
</form>
<form name="frmTags" id="frm-tags" method="post" action="tags.php">
<div class="gs_options">
	<?php $nav->display(false); ?>
    <select name="op" id="select-op-top">
        <option value="" selected="selected"><?php _e('Bulk actions...','admin_galleries'); ?></option>
        <option value="edit"><?php _e('Edit','admin_galleries'); ?></option>
        <option value="delete"><?php _e('Delete','admin_galleries'); ?></option>
    </select>
    <input type="button" value="<?php _e('Apply','admin_galleries'); ?>" id="op-top" />
</div>
<table width="100%" class="outer" cellspacing="0">
	<thead>
    <tr align="center">
		<th width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#frm-tags").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><?php _e('ID','admin_galleries'); ?></th>
		<th align="left"><?php _e('Tag','admin_galleries'); ?></th>
		<th><?php _e('Pictures','admin_galleries'); ?></th>
	</tr>
    </thead>
    
    <tfoot>
    <tr align="center">
		<th width="20"><input type="checkbox" name="checkall2" id="checkall2" onclick='$("#frm-tags").toggleCheckboxes(":not(#checkall2)");' /></th>
		<th width="30"><?php _e('ID','admin_galleries'); ?></th>
		<th align="left"><?php _e('Tag','admin_galleries'); ?></th>
		<th><?php _e('Pictures','admin_galleries'); ?></th>
	</tr>
    </tfoot>
    
    <tbody>
    <?php if(empty($tags)): ?>
    <tr class="even" align="center">
        <td colspan="4"><?php _e('There are any tags yet!','admin_galleries'); ?></td>
    </tr>
    <?php endif; ?>
	<?php foreach($tags as $tag): ?>
    <tr class='<?php echo tpl_cycle("even,odd"); ?>' valign="top">
		<td align="center"><input type="checkbox" name="ids[]" value="<?php echo $tag['id']; ?>" id="item-<?php echo $tag['id']; ?>" /></td>
		<td align="center"><strong><?php echo $tag['id']; ?></strong></td>
		<td align="left"><a href="<?php echo $tag['url']; ?>"><strong><?php echo $tag['name']; ?></strong></a>
		<span class="rmc_options">
			<a href="javascript:;" class="gs_edit_option" id="edit-<?php echo $tag['id']; ?>"><?php _e('Edit','admin_galleries'); ?></a> |
			<a href="javascript:;" class="gs_delete_option" id="delete-<?php echo $tag['id']; ?>"><?php _e('Delete','admin_galleries'); ?></a>
		</span></td>
		<td align="center"><?php echo $tag['pics']; ?></td>
    </tr>
	<?php endforeach; ?>
    </tbody>
</table>
<div class="gs_options">
	<?php $nav->display(false); ?>
    <select name="opb" id="select-op-bottom">
        <option value="" selected="selected"><?php _e('Bulk actions...','admin_galleries'); ?></option>
        <option value="edit"><?php _e('Edit','admin_galleries'); ?></option>
        <option value="delete"><?php _e('Delete','admin_galleries'); ?></option>
    </select>
    <input type="button" value="<?php _e('Apply','admin_galleries'); ?>" id="op-bottom" />
</div>
<input type="hidden" name="pag" value="<?php echo $page; ?>" />
<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
<input type="hidden" name="search" value="<?php echo $search; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
