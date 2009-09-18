
<h1 class="rmc_titles mw_titles" style="background-image: url(../images/post32.png);"><?php $edit ? _e('Edit Post','admin_mywords') : _e('Create Post','admin_mywords'); ?></h1>
<input type="text" name="title" id="post-title" class="mw_biginput" value="<?php echo $edit ? $post->getVar('title','e') : ''; ?>" />
<div class="mw_permacont <?php if(!$edit): ?>mw_permainfo<?php endif; ?>">
	<?php if($edit): ?>
		<?php echo $post->permalink(); ?>
	<?php else: ?>
		<?php _e('This post has not been saved. Remember to save it before leave this page.','admin_mywords'); ?>
	<?php endif; ?>
</div>
<?php echo $editor->render(); ?>
<br />
<div class="outer">
	<div class="th">
		<?php _e('Send Trackbacks','admin_mywords'); ?>
	</div>
	<div class="mw_bcontent">
		<?php _e('Send trackbacks to:','admin_mywords'); ?>
		<input type="text" name="trackbacks" id="post-trackbacks" class="mw_large" value="<?php echo $edit ? $post->trackurls() : ''; ?>" />
		(<?php _e('Separate multiple URLs with spaces','admin_mywords'); ?>)
	</div>
</div>
<br />
<div class="outer">
	<div class="th"><?php _e('Custom Fields','admin_mywords'); ?></div>
	<div class="mw_bcontent">
		<table id="metas-container" class="outer<?php echo !$edit || (!isset($post) && !$post->fields()) ? ' mw_hidden' : ''; ?>" cellspacing="0" width="100%" />
			<tr class="head">
				<td width="30%"><?php _e('Name','admin_mywords'); ?></td>
				<td><?php _e('Value','admin_mywords'); ?></td>
			</tr>
			<?php if($edit || (isset($post) && $post->fields())): ?>
			<?php foreach($post->fields() as $field): ?>
				<tr class="<?php echo cycle("even,odd"); ?>">
					<td valign="top"><input type="text" name="meta[<?php echo $field->id(); ?>][key]" id="meta[<?php echo $field->id(); ?>][key]" value="<?php echo $field->name(); ?>" class="mw_large" /></td>
					<td><textarea name="meta[<?php echo $field->id(); ?>][value]" id="meta[<?php echo $field->id(); ?>][value]" class="mw_large"><?php echo $field->value(); ?></textarea></td>
				</tr>
			<?php endforeach; ?>
			<?php endif; ?>
		</table><br />
		<label><strong><?php _e('Add new field:','admin_mywords'); ?></strong></label>
		<table class="outer" cellspacing="0" />
			<tr class="head" align="center">
				<td width="30%"><?php _e('Name','admin_mywords'); ?></td>
				<td><?php _e('Value','admin_mywords'); ?></td>
			</tr>
			<tr class="even">
				<td valign="top">
					<label class="error" style="display: none;" id="error-metaname">Please, select or specify a new meta name</label>
					<?php if(!empty($meta_names)): ?>
					<select name="meta_name_sel" class="mw_large" id="meta-name-sel">
						<option value="" selected="selected"><?php _e('- Select -','admin_mywords'); ?></option>
						<?php foreach ($meta_names as $name): ?>
						<option value="<?php echo $name; ?>"><?php echo $name; ?></option>
						<?php endforeach; ?>
					</select>
					<input type="text" name="meta_name" id="meta-name" value="" class="mw_large" style="display: none; width: 95%;" />
					<a href="javascript:;" class="mw_show_metaname"><?php _e('Enter New','admin_mywords'); ?></a>
					<a href="javascript:;" class="mw_hide_metaname" style="display: none;"><?php _e('Cancel','admin_mywords'); ?></a>
					<?php else: ?>
					
					<?php endif; ?>
				</td>
				<td valign="top">
					<label class="error" style="display: none;" id="error-metavalue">Please provide a value for this meta</label>
					<textarea name="meta_value" id="meta-value" class="mw_large"></textarea>
				</td>
			</tr>
			<tr class="odd">
				<td colspan="2">
					<input type="button" id="mw-addmeta" value="<?php _e('Add custom field','admin_mywords'); ?>" />
				</td>
			</tr>
		</table>
		<label><?php _e('Custom fields can be used to add extra metadata to a post that you can use in your theme.','admin_mywords'); ?></label>
	</div>
</div>
<?php RMEventsApi::get()->run_event('mw_posts_form', isset($post) ? $post : null); ?>
<input type="hidden" name="XOOPS_TOKEN_REQUEST" id="xoops-token" value="<?php echo $xoopsSecurity->createToken(); ?>" />
<input type="hidden" name="op" id="mw-op" value="<?php echo $edit ? 'saveedit' : 'save'; ?>" />