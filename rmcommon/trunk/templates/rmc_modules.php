<h1 class="rmc_titles"><?php _e('Modules Management','rmcommon'); ?></h1>

<?php foreach($modules as $mod): ?>
<div class="rmc_item_module rounded" id="module-<?php echo $mod['id']; ?>">
	<input type="checkbox" name="ids[]" id="item-<?php echo $mod['id']; ?>" value="<?php echo $mod['id']; ?>" />
	<div class="mod_image">
		<a class="rounded" href="<?php echo $mod['admin_link']; ?>" title="<?php echo $mod['realname']; ?>" style="background: url(<?php echo $mod['image']; ?>) no-repeat center;"><span class="rounded">&nbsp;</span></a>
	</div>
	<div class="mod_data">
		<span class="name"><a href="<?php echo $mod['link']; ?>"><?php echo $mod['name']; ?></a></span>
		<span class="data">
			<span><?php echo sprintf(__('Version: %s', 'rmcommon'), $mod['version']); ?></span>
			<span><?php echo sprintf(__('Updated: %s', 'rmcommon'), $mod['updated']); ?></span>
		</span>
		<span class="options">
			<a href="javascript:;" class="show" id="show-<?php echo $mod['id']; ?>">Show</a>
			<a href="javascript:;">Disable</a> &nbsp;
		</span>
	</div>
	<div class="data_storage">
		<span class="version"><?php echo $mod['version']; ?></span>
		<span class="author"><?php echo $mod['author']; ?></span>
		<span class="authormail"><?php echo $mod['author_mail']; ?></span>
		<span class="authorweb"><?php echo $mod['author_web']; ?></span>
		<span class="authorurl"><?php echo $mod['author_url']; ?></span>
		<span class="name"><?php echo $mod['name']; ?></span>
		<span class="realname"><a href="<?php echo $mod['admin_link']; ?>"><?php echo $mod['realname']; ?></a></span>
		<span class="description"><?php echo $mod['description']; ?></span>
		<span class="license"><?php echo $mod['license']; ?></span>
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
</div>