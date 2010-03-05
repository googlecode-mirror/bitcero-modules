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
		<th width="20"><input type="checkbox" name="checkall" onchange="xoopsCheckAll('frmTags','checkall');" /></th>
		<th width="30"><?php _e('ID','admin_galleries'); ?></th>
		<th><?php _e('Tag','admin_galleries'); ?></th>
		<th><?php _e('Pictures','admin_galleries'); ?></th>
	</tr>
    </thead>
    <tbody>
    <?php if(empty($tags)): ?>
    <tr class="even" align="center">
        <td colspan="4"><?php _e('There are any tags yet!','admin_galleries'); ?></td>
    </tr>
    <?php endif; ?>
	<?php foreach($tags as $tag): ?>
    <tr class='<?php echo tpl_cycle("even,odd"); ?>'>
		<td align="center"><input type="checkbox" name="ids[]" value="<{$tag.id}>" /></td>
		<td align="center"><strong><{$tag.id}></strong></td>
		<td align="left"><a href="<{$tag.url}>"><strong><{$tag.name}></strong></a></td>
		<td align="center"><{$tag.pics}></td>
		<td align="center"><a href="./tags.php?op=edit&amp;ids=<{$tag.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>&amp;search=<{$search}>"><{$lang_edit}></a> &bull; <a href="./tags.php?op=delete&amp;ids=<{$tag.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>&amp;search=<{$search}>"><{$lang_del}></a></td>
    </tr>
	<?php endforeach; ?>
    </tbody>
</table>
<div class="gs_options">
    <select name="op" id="select-op-bottom">
        <option value="" selected="selected"><?php _e('Bulk actions...','admin_galleries'); ?></option>
        <option value="edit"><?php _e('Edit','admin_galleries'); ?></option>
        <option value="delete"><?php _e('Delete','admin_galleries'); ?></option>
    </select>
    <input type="button" value="<?php _e('Apply','admin_galleries'); ?>" id="op-bottom" />
</div>
<input type="hidden" name="pag" value="<{$pag}>" />
<input type="hidden" name="limit" value="<{$limit}>" />
<input type="hidden" name="search" value="<{$search}>" />
</form>
