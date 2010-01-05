<h1 class="rmc_titles mw_titles"><span style="background-position: -64px 0;">&nbsp;</span><?php _e('Tags','admin_mywords'); ?></h1>
<br clear="all" />

<?php if($show_edit): ?>
	
	<div class="edit_form">
	<form name="form_edit" id="form-edit" method="post" action="tags.php">
		<label for="edit-name"><?php _e('Name','admin_mywords'); ?></label>
		<input type="text" name="name" id="edit-name" value="<?php echo $tag->getVar('tag'); ?>" />
		<br clear="all" />
		<label for="edit-short"><?php _e('Short name','admin_mywords'); ?></label>
		<input type="text" name="short" id="edit-short" value="<?php echo $tag->getVar('tag'); ?>" />
		<br clear="all" />
		<div style="padding-left: 160px;">
		<input type="submit" value="<?php _e('Save Changes','admin_mywords'); ?>" />
		<input type="button" value="<?php _e('Cancel','admin_mywords'); ?>" onclick="history.go(-1);" />
		</div>
		<input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
		<input type="hidden" name="action" value="saveedit" />
		<input type="hidden" name="id" value="<?php echo $tag->id(); ?>" />
	</form>
	</div>
	
<?php else: ?>
<div id="tags-form" class="form_options">
	<h4><?php _e('More used tags','admin_mywords'); ?></h4>
	<div class="more_used">
		<?php $f = 28/$size; ?>
		<?php foreach($mtags as $tag): ?>
		<a href="tags.php?action=edit&amp;id=<?php echo $tag['id_tag']; ?>&amp;page=<?php echo $page; ?>" title="<?php echo sprintf(__('%u Posts','admin_mywords'), $tag['posts']); ?>" style="font-size: <?php echo $f*$tag['posts']<11 ? 11 : floor($f*$tag['posts']); ?>px"><?php echo $tag['tag']; ?></a>&nbsp;
		<?php endforeach; ?>
	</div><br clear="all" />
	<h3 class="form_titles"><?php _e('New Tag','admin_mywords'); ?></h3>
	<form name="formTag" id="form-tags" method="post" action="tags.php">
	<label for="new-name"><?php _e('Tag name','admin_mywords'); ?></label>
	<input type="text" name="name" id="new-name" value="" />
	<label for="new-short"><?php _e('Short name','admin_mywords'); ?></label>
	<input type="text" name="short" id="new-short" value="" />
	<p class="submit"><input type="button" id="submit-newtag" class="formButton" value="<?php _e('Add Tag','admin_mywords'); ?>" /></p>
	<input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
	<input type="hidden" name="action" value="new" />
	<input type="hidden" name="page" value="<?php echo $page; ?>" />
	</form>
</div>

<div id="tags-list">
	<form name="form_list_tags" id="form-list-tags" method="post" action="tags.php">
	<div class="options">
		<?php $nav->display(false); ?>
		<select name="action" id="action-list">
			<option value=""><?php _e('Bulk actions','admin_mywords'); ?></option>
			<option value="update"><?php _e('Update posts number','admin_mywords'); ?></option>
			<option value="delete"><?php _e('Delete','admin_mywords'); ?></option>
		</select>
		<input type="button" value="<?php _e('Apply','admin_mywords'); ?>" onclick="submit();"/>
	</div>
	<table class="outer" cellspacing="0">
		<thead>
		<tr>
			<th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-tags").toggleCheckboxes(":not(#checkall)");' /></th>
			<th align="left"><?php _e('Name','admin_mywords'); ?></th>
			<th><?php _e('Short name','admin_mywords'); ?></th>
			<th><?php _e('Posts','admin_mywords'); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th align="center"><input type="checkbox" id="checkall2" onclick='$("#form-list-tags").toggleCheckboxes(":not(#checkall2)");' /></th>
			<th align="left"><?php _e('Name','admin_mywords'); ?></th>
			<th><?php _e('Short name','admin_mywords'); ?></th>
			<th><?php _e('Posts','admin_mywords'); ?></th>
		</tr>
		</tfoot>
		<tbody>
		<?php if(empty($tags)): ?>
		<tr class="even">
			<td colspan="4" class="error" align="center"><?php _e('There is not any tag yet!','admin_mywords'); ?></td>
		</tr>
		<?php endif; ?>
		<?php foreach($tags as $tag): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
			<td align="center"><input type="checkbox" name="tags[<?php echo $tag['id_tag']; ?>]" id="tags[<?php echo $tag['id_tag']; ?>]" value="<?php echo $tag['id_tag']; ?>" /></td>
			<td>
				<strong><a href="tags.php?action=edit&amp;id=<?php echo $tag['id_tag']; ?>&amp;page=<?php echo $page; ?>"><?php echo $tag['tag']; ?></a></strong>
				<span class="mw_options">
					<a href="tags.php?action=edit&amp;id=<?php echo $tag['id_tag']; ?>&amp;page=<?php echo $page; ?>"><?php _e('Edit','admin_mywords'); ?></a> | 
					<a href="javascript:;"><?php _e('Quick Edit','admin_mywords'); ?></a> | 
					<a href="tags.php?action=delete&amp;id=<?php echo $tag['id_tag']; ?>&amp;page=<?php echo $page; ?>"><?php _e('Delete','admin_mywords'); ?></a>
				</span>
			</td>
			<td align="center"><?php echo $tag['shortname']; ?></td>
			<td align="center"><?php echo $tag['posts']; ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div class="options">
		<?php $nav->display(false); ?>
		<select name="action-b" id="action-list-b">
			<option value=""><?php _e('Bulk actions','admin_mywords'); ?></option>
			<option value="update"><?php _e('Update posts number','admin_mywords'); ?></option>
			<option value="delete"><?php _e('Delete','admin_mywords'); ?></option>
		</select>
		<input type="button" value="<?php _e('Apply','admin_mywords'); ?>" onclick="submit();" />
	</div>
	<input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
	</form>
</div>
<?php endif; ?>
<div id="mw-dialog" title=''>

</div>