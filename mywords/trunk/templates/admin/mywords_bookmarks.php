<h1 class="rmc_titles mw_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Social Sites','admin_mywords'); ?></h1>

<?php if(isset($show_edit) && $show_edit): ?>

	<div class="edit_form">
	<form name="form_edit" id="form-new-bookmark" method="post" action="bookmarks.php">
		<label for="new-title"><?php _e('Title','admin_mywords'); ?></label>
        <input type="text" name="title" id="new-title" value="<?php echo $book->getVar('title'); ?>" />
        <br clear="all" />
        <label for="new-alt">*<?php _e('Short description:','admin_mywords'); ?></label>
		<input type="text" name="alt" id="new-alt" value="<?php echo $book->getVar('alt'); ?>" />
		<br clear="all" />
		<label for="new-url">*<?php _e('Formated URL:','admin_mywords'); ?></label>
		<input type="text" name="url" id="new-url" value="<?php echo $book->getVar('url'); ?>" />
		<br clear="all" />
		<label for="edit-icon"><?php _e('Icon:','admin_mywords'); ?></label>
		<div class="icons_sel" id="edit-icon">
			<?php foreach($icons as $id => $icon): ?>
				<img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['name']; ?>" id="icon-<?php echo $id; ?>" title="<?php echo $icon['name']; ?>"<?php echo $book->getVar('icon')==$icon['name']?' class="selected"' : ''; ?> />
			<?php endforeach; ?>
			<input type="hidden" name="icon" id="new-icon-h" value="<?php echo $book->getVAr('icon'); ?>" /><br />
			<span class="description"><?php echo sprintf(__('You can create new icons by uploading files to %s folder.','admin_mywords'), XOOPS_ROOT_PATH.'/modules/mywords/images'); ?>
		</div>
		<br clear="all" />
		<div style="padding-left: 160px;">
        <input type="submit" value="<?php _e('Save Changes','admin_mywords'); ?>" />
        <input type="button" value="<?php _e('Cancel','admin_mywords'); ?>" onclick="history.go(-1);" />
        </div>
        <?php echo $xoopsSecurity->getTokenHTML(); ?>
        <input type="hidden" name="action" value="saveedit" />
        <input type="hidden" name="id" value="<?php echo $book->id(); ?>" />
	</form>
	</div>

<?php else: ?>

	<div class="descriptions">
		<?php _e('Social Sites allows to publish directly, on these sites, links and content from MyWords posts.','admin_mywords'); ?>
		<?php _e('You can add new sites easily by configuring parameters for each site (eg. Twitter, Facebook, etc.), then your visitors can recommend posts to other users from these social networks.','armin_mywords'); ?></em>
	</div>

	<div class="form_options">
		<form name="form_new" id="form-new-bookmark" method="post" action="bookmarks.php">
		<h3 class="form_titles"><?php _e('Create Site','admin_mywords'); ?></h3>
		<label for="new-title">*<?php _e('Site title:','admin_mywords'); ?></label>
		<input type="text" name="title" id="new-title" value="" class="required" />
		<label for="new-alt">*<?php _e('Short description:','admin_mywords'); ?></label>
		<input type="text" name="alt" id="new-alt" value="" class="required" />
		<label for="new-url">*<?php _e('Formated URL:','admin_mywords'); ?></label>
		<input type="text" name="url" id="new-url" value="" class="required" />
		<span class="description"><?php _e('Please, note that the URL can contain parameters {TITLE}, {URL} and {DESC} that will be replaced with their respective values.','admin_mywords'); ?></span>
		<label for="new-icon"><?php _e('Icon:','admin_mywords'); ?></label>
		<div class="icons_sel" id="new-icon">
			<?php foreach($icons as $id => $icon): ?>
				<img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['name']; ?>" id="icon-<?php echo $id; ?>" title="<?php echo $icon['name']; ?>" />
			<?php endforeach; ?>
			<input type="hidden" name="icon" id="new-icon-h" value="" />
			<span class="description"><?php echo sprintf(__('You can create new icons by uploading files to %s folder.','admin_mywords'), XOOPS_ROOT_PATH.'/modules/mywords/images'); ?>
		</div>
		<input type="submit" value="<?php _e('Create Site','admin_mywords'); ?>" />
		<?php echo $xoopsSecurity->getTokenHTML(); ?>
	    <input type="hidden" name="action" value="new" />
		</form>
	</div>
	<div id="tables-list">
		<form name="frmListB" id="form-list-book" method="post" action="bookmarks.php">
		<div class="options">
		<select name="action" id="action-list">
            <option value=""><?php _e('Bulk actions','admin_mywords'); ?></option>
            <option value="activate"><?php _e('Activate','admin_mywords'); ?></option>
            <option value="deactivate"><?php _e('Deactivate','admin_mywords'); ?></option>
            <option value="delete"><?php _e('Delete','admin_mywords'); ?></option>
        </select>
        <input type="button" value="<?php _e('Apply','admin_mywords'); ?>" onclick="submit();"/>
        </div>
		<table class="outer" cellspacing="0">
			<thead>
			<tr>
				<th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-book").toggleCheckboxes(":not(#checkall)");' /></th>
				<th>&nbsp;</th>
				<th align="left"><?php _e('Title','admin_mywords'); ?></th>
				<th align="left"><?php _e('Description','admin_mywords'); ?></th>
				<th><?php _e('URL','admin_mywords'); ?></th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-book").toggleCheckboxes(":not(#checkall)");' /></th>
				<th>&nbsp;</th>
				<th align="left"><?php _e('Title','admin_mywords'); ?></th>
				<th align="left"><?php _e('Description','admin_mywords'); ?></th>
				<th><?php _e('URL','admin_mywords'); ?></th>
			</tr>
			</tfoot>
			<tbody>
			<?php if(count($bookmarks)<=0): ?>
			<tr class="even">
				<td colspan="5"><?php _e('There are not social sites registered yet!','admin_mywords'); ?></td>
			</tr>
			<?php endif; ?>
			<?php foreach($bookmarks as $book): ?>
			<tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
				<td align="center"><input type="checkbox" name="books[]" id="book-<?php echo $book['id']; ?>" value="<?php echo $book['id']; ?>" /></td>
				<td align="center"><img src="../images/icons/<?php echo $book['icon']; ?>" alt="<?php echo $book['icon']; ?>" title="<?php echo $book['icon']; ?>" /></td>
				<td>
					<strong><?php echo $book['name']; ?></strong>
					<?php echo $book['active']?'':'['.__('Inactive','admin_mywords').']'; ?>
					<span class="mw_options">
	                    <a href="bookmarks.php?id=<?php echo $book['id']; ?>&amp;action=edit"><?php _e('Edit','admin_mywords'); ?></a> |
	                    <?php if($book['active']): ?>
	                    <a href="javascript:;" onclick="goto_activate(<?php echo $book['id']; ?>,false);"><?php _e('Desactivar','admin_mywords'); ?></a> |
	                    <?php else: ?>
	                    <a href="javascript:;" onclick="goto_activate(<?php echo $book['id']; ?>,true);"><?php _e('Activar','admin_mywords'); ?></a> |
	                    <?php endif; ?>
	                    <a href="javascript:;" onclick="goto_delete(<?php echo $book['id']; ?>);"><?php _e('Delete','admin_mywords'); ?></a>
	                </span>
				</td>
				<td><?php echo $book['desc']; ?></td>
				<td class="burl"><?php echo $book['url']; ?></td>
			</tr>
			<?php endforeach; ?>
			</tbody>
			
		</table>
		<div class="options">
		<select name="actionb" id="action-list-b">
            <option value=""><?php _e('Bulk actions','admin_mywords'); ?></option>
            <option value="activate"><?php _e('Activate','admin_mywords'); ?></option>
            <option value="deactivate"><?php _e('Deactivate','admin_mywords'); ?></option>
            <option value="delete"><?php _e('Delete','admin_mywords'); ?></option>
        </select>
        <input type="button" value="<?php _e('Apply','admin_mywords'); ?>" onclick="submit();"/>
        </div>
        <?php echo $xoopsSecurity->getTokenHTML(); ?>
		</form>
	</div>

<?php endif; ?>