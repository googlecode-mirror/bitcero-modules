<h1 class="rmc_titles"><?php _e('Modules Management','rmcommon'); ?></h1>
<script type="text/javascript">
<!--
    var message = "<?php _e('Do you really want to uninstall selected module?','rmcommon'); ?>";
    var message_upd = "<?php _e('Do you really want to update selected module?','rmcommon'); ?>";
    var message_dis = "<?php _e('Do you really want to disable selected module?','rmcommon'); ?>";
    var message_name = "<?php _e('New name must be different from current name!','rmcommon'); ?>";
-->
</script>

<form action="modules.php" method="post" id="form-modules">
<input type="hidden" name="action" id="mod-action" value="" />
<input type="hidden" name="module" id="mod-dir" value="" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>

<?php foreach($modules as $mod): ?>
<div class="rmc_item_module rounded<?php echo !$mod['active'] ? ' inactive' : ''; ?>" id="module-<?php echo $mod['id']; ?>">
	<!--<input type="checkbox" name="ids[]" id="item-<?php echo $mod['id']; ?>" value="<?php echo $mod['id']; ?>" />-->
	<div class="mod_image">
		<a class="rounded" href="<?php if($mod['active']): ?><?php echo $mod['admin_link']; ?><?php else: ?>javascript:;<?php endif; ?>" title="<?php echo $mod['realname']; ?>" style="background: url(<?php echo $mod['image']; ?>) no-repeat center;"><span class="rounded">&nbsp;</span></a>
	</div>
	<div class="mod_data">
        <?php if($mod['active']): ?>
		<span class="name"><a href="<?php echo $mod['link']; ?>"><?php echo strlen($mod['name'])>20 ? substr($mod['name'], 0, 20) : $mod['name']; ?></a></span>
        <?php else: ?>
        <span class="name"><?php echo $mod['name']; ?></span>
        <?php endif; ?>
		<span class="data">
			<span><?php echo sprintf(__('Version: %s', 'rmcommon'), $mod['version']); ?></span>
			<span><?php echo sprintf(__('Updated: %s', 'rmcommon'), $mod['updated']); ?></span>
		</span>
		<span class="options">
			<a href="javascript:;" class="show" id="show-<?php echo $mod['id']; ?>">Show</a>
			<a href="javascript:;" class="rename" id="rename-<?php echo $mod['id']; ?>">Rename</a>
		</span>
	</div>
	<div class="data_storage">
		<span class="version"><?php echo $mod['version']; ?></span>
        <span class="dirname"><?php echo $mod['dirname']; ?></span>
		<span class="author"><?php echo $mod['author']; ?></span>
		<span class="authormail"><?php echo $mod['author_mail']; ?></span>
		<span class="authorweb"><?php echo $mod['author_web']; ?></span>
		<span class="authorurl"><?php echo $mod['author_url']; ?></span>
		<span class="name"><?php echo $mod['name']; ?></span>
		<span class="realname"><a href="<?php echo $mod['admin_link']; ?>"><?php echo $mod['realname']; ?></a></span>
		<span class="description"><?php echo $mod['description']; ?></span>
		<span class="license"><?php echo $mod['license']; ?></span>
        <span class="active"><?php echo $mod['active']; ?></span>
	</div>
</div>
<?php endforeach; ?>

<!-- Module data displayed -->
<div id="data-display" class="rounded">
	<div class="data_head">
		<div class="mod_image"></div>
		<span class="name"></span>
	</div>
	<div class="data_description"></div>
	<div class="data_values">
		<table cellspacing="0" border="0">
			<tr>
				<td><strong><?php _e('Version:','rmcommon'); ?></strong></td>
				<td class="version"></td>
			</tr>
			<tr>
				<td><strong><?php _e('Author:','rmcommon'); ?></strong></td>
				<td class="author"></td>
			</tr>
			<tr>
				<td><strong><?php _e('Web:','rmcommon'); ?></strong></td>
				<td class="web"></td>
			</tr>
			<tr>
				<td><strong><?php _e('License:','rmcommon'); ?></strong></td>
				<td class="license"></td>
			</tr>
			<tr>
				<td><strong><?php _e('Name:','rmcommon'); ?></strong></td>
				<td class="name"></td>
			</tr>
		</table>
	</div>
    <div class="data_buttons">
        <a href="javascript:;" class="update_button"><?php _e('Update','rmcommon'); ?></a>
        <a href="javascript:;" class="uninstall_button" id=""><?php _e('Uninstall','rmcommon'); ?></a>
        <a href="javascript:;" class="disable_button"><?php _e('Disable','rmcommon'); ?></a>
        <a href="javascript:;" class="enable_button" style="display: none;"><?php _e('Enable','rmcommon'); ?></a>
        <a href="#" onclick="$('#data-display').slideUp('fast');">Close</a>
        <input type="hidden" id="the-id" value="" />
    </div>
</div>
<div id="rename-blocker"></div>
<div id="rename">
	<span><?php _e('Change name:','rmcommon'); ?> <span></span></span>
	<input type="text" size="50" id="rename-name" value="" />
	<input type="submit" id="rename-save" value="<?php _e('Rename','rmcommon'); ?>" />
	<input type="button" onclick="$('#rename-blocker').click();" value="<?php _e('Cancel','rmcommon'); ?>" />
	<input type="hidden" name="id" id="id-module" value="" />
</div>