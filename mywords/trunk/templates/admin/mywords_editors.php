<h1 class="rmc_titles mw_titles"><span style="background-position: -128px 0;">&nbsp;</span><?php _e('Editors','admin_mywords'); ?></h1>
<?php if(isset($show_edit) && $show_edit): ?>

<?php else: ?>
<div class="descriptions">
	<?php _e('Editors are people that can send and publish content without admin approval. You can create new editors and assign individuals permissions for them.','admin_mywords'); ?>
	<em><?php _e('All webmasters are allowed as editors with all privileges.','armin_mywords'); ?></em>
</div>
<div class="form_options">
	<form name="form_new" id="form-new-editor" method="post" action="editors.php">
	<h3 class="form_titles"><?php _e('Register Editor','admin_mywords'); ?></h3>
	<label for="new-name"><?php _e('Display name:','admin_mywords'); ?></label>
	<input type="text" name="name" id="new-name" value="" class="required" />
	<label for="new-bio"><?php _e('Biography:','admin_mywords'); ?></label>
	<textarea name="bio" id="new-bio" style="height: 120px;"></textarea>
	<label for="new-user"><?php _e('Registered user:','admin_mywords'); ?></label>
	<?php 
	$ele = new RMFormUser('', 'new_user');
	echo $ele->render();
	?>
	<label for="new-perm"><?php _e('Privilieges:','admin_mywords'); ?></label>
	<div class="permissions">
		<label><input type="checkbox" name="perms[]" value="tags" checked="checked" /> <?php _e('Create tags','admin_mywords'); ?></label>
		<label><input type="checkbox" name="perms[]" value="cats" checked="checked" /> <?php _e('Create categories','admin_mywords'); ?></label>
		<label><input type="checkbox" name="perms[]" value="tracks" checked="checked" /> <?php _e('Send trackbacks','admin_mywords'); ?></label>
		<label><input type="checkbox" name="perms[]" value="comms" checked="checked" /> <?php _e('Manage discussions','admin_mywords'); ?></label>
	</div>
    <br clear="all" />
    <input type="submit" value="<?php _e('Create Editor','admin_mywords'); ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="action" value="new" />
	</form>
</div>

<div id="editors-list">
	<form id="form-list-editors" name="from_editors" method="post" action="editors.php">
    <div class="options">
        <?php $nav->display(false); ?>
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
		<?php if(!$tpl->get_var('editors')): ?>
		<tr class="even">
			<td colspan="5" align="center"><?php _e('There are not editors registered yet.','admin_mywords'); ?></td>
		</tr>
		<?php endif; ?>
		<?php foreach($tpl->get_var('editors') as $editor): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
			<td><input type="checkbox" name="editors[]" id="editors-<?php echo $editor->id(); ?>" value="<?php echo $editor->id(); ?>" /></td>
			<td>
                <strong><?php echo $editor->getVar('name'); ?></strong>
                <span class="mw_options">
                    <a href="editors.php?action=edit"><?php _e('Edit','admin_mywords'); ?></a> |
                    <?php if($editor->getVar('active')): ?>
                    <a href="editors.php?action=deactivate"><?php _e('Desactivar','admin_mywords'); ?></a> |
                    <?php else: ?>
                    <a href="editors.php?action=activar"><?php _e('Activar','admin_mywords'); ?></a> |
                    <?php endif; ?>
                    <a href="editors.php?action=delete"><?php _e('Delete','admin_mywords'); ?></a>
                </span>
            </td>
            <td align="center"><a href="<?php echo XOOPS_URL; ?>/modules/system/admin.php?fct=users&amp;op=modifyUser&amp;uid=<?php echo $editor->getVar('uid'); ?>" title="<?php _e('Edit user','admin_mywords'); ?>"><?php echo $editor->data('uname'); ?></a></td>
            <td align="center">
            <?php 
                foreach($editor->getVar('privileges') as $perm): 
                
                switch($perm){
                    case 'tags':?>
                        <img src="../images/tag16.png" title="<?php _e('Create tags','admin_mywords'); ?>" alt="" />
            <?php
                        break;
                    case 'cats': ?>
                        <img src="../images/categos.png" title="<?php _e('Create categories','admin_mywords'); ?>" alt="" />
            <?php
                        break;
                    case 'tracks': ?>
                        <img src="../images/traks.png" title="<?php _e('Send trackbacks and pings','admin_mywords'); ?>" alt="" />
            <?php
                        break;
                    case 'comms': ?>
                        <img src="../images/comment.png" title="<?php _e('Enable/disable discussions','admin_mywords'); ?>" alt="" />
            <?php
                        break;
                }
                
                endforeach; ?>
            </td>
            <td align="center"><?php echo $editor->posts(); ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
    <div class="options">
        <?php $nav->display(false); ?>
        <select name="action" id="action-list">
            <option value=""><?php _e('Bulk actions','admin_mywords'); ?></option>
            <option value="activate"><?php _e('Activate','admin_mywords'); ?></option>
            <option value="deactivate"><?php _e('Deactivate','admin_mywords'); ?></option>
            <option value="delete"><?php _e('Delete','admin_mywords'); ?></option>
        </select>
        <input type="button" value="<?php _e('Apply','admin_mywords'); ?>" onclick="submit();"/>
    </div>
	</form>
</div>
<?php endif; ?>