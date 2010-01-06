<h1 class="rmc_titles mw_titles"><span style="background-position: -128px 0;">&nbsp;</span><?php _e('Editors','admin_mywords'); ?></h1>
<div class="descriptions">
	<?php _e('Editors are people that can send and publish content without admin approval. You can create new editors and assign individuals permissions for them.','admin_mywords'); ?>
	<em><?php _e('All webmasters are allowed as editors with all privileges.','armin_mywords'); ?></em>
</div>
<div class="form_options">
	<form name="form_new" id="form-new-editor" method="post" action="editors.php">
	<h3 class="form_titles"><?php _e('Register Editor','admin_mywords'); ?></h3>
	<label for="new-name"><?php _e('Display name:','admin_mywords'); ?></label>
	<input type="text" name="name" id="new-name" value="" />
	<label for="new-bio"><?php _e('Biography:','admin_mywords'); ?></label>
	<textarea name="bio" id="new-bio" style="height: 120px;"></textarea>
	<label for="new-user"><?php _e('Regitered user:','admin_mywords'); ?></label>
	<?php 
	$ele = new RMFormUser('', 'new-user',false,array(0),36,600,500,true);
	echo $ele->render();
	?>
	<label for="new-perm"><?php _e('Privilieges:','admin_mywords'); ?></label>
	<div class="permissions">
		<label><input type="checkbox" name="perms[]" value="tags" checked="checked" /> <?php _e('Create tags','admin_mywords'); ?></label>
		<label><input type="checkbox" name="perms[]" value="cats" checked="checked" /> <?php _e('Create categories','admin_mywords'); ?></label>
		<label><input type="checkbox" name="perms[]" value="tracks" checked="checked" /> <?php _e('Send trackbacks','admin_mywords'); ?></label>
		<label><input type="checkbox" name="perms[]" value="comms" checked="checked" /> <?php _e('Manage discussions','admin_mywords'); ?></label>
	</div>
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
	</form>
</div>

<div id="editors-list">
	<form id="form-list-editors" name="from_editors" method="post" action="editors.php">
	<table class="outer" cellspacing="0">
		<thead>
		<tr>
			<th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-editors").toggleCheckboxes(":not(#checkall)");' /></th>
			<th align="left"><?php _e('Display name','admin_mywords'); ?></th>
			<th><?php _e('User','admin_mywords'); ?></th>
			<th><?php _e('Permissions','admin_mywords'); ?></th>
			<th><?php _e('Posts','admin_mywords'); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th width="20" align="center"><input type="checkbox" id="checkall2" onclick='$("#form-list-editors").toggleCheckboxes(":not(#checkall2)");' /></th>
			<th align="left"><?php _e('Display name','admin_mywords'); ?></th>
			<th><?php _e('User','admin_mywords'); ?></th>
			<th><?php _e('Permissions','admin_mywords'); ?></th>
			<th><?php _e('Posts','admin_mywords'); ?></th>
		</tr>
		</tfoot>
		<tbody>
		<?php if(count($tpl->get_var('editors'))<=0): ?>
		<tr class="even">
			<td colspan="5" align="center"><?php _e('There are not editors registered yet.','admin_mywords'); ?></td>
		</tr>
		<?php endif; ?>
		<?php foreach($tpl->get_var('editors') as $editor): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>">
			<td><input type="checkbox" name="editors[]" id="editors-<?php echo $editor['id']; ?>" value="<?php echo $editor['id']; ?>" /></td>
			
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	</form>
</div>