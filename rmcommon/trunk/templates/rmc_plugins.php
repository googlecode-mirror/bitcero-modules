<h1 class="rmc_titles"><?php _e('Plugins Manager','rmcommon'); ?></h1>
<div class="descriptions">
<?php _e('Plugins allow to Common Utilities to improve its features and capabilities. Follow is a list with existing plugins, installed and available to install.','rmcommon'); ?>
</div>

<h3><?php _e('Installed Plugins', 'rmcommon'); ?></h3>
<table class="outer" cellspacing="0" cellpadding="0">
	<thead>
	<tr>
		<th width="20"><input type="checkbox" name="checkall" id="checkall" /></th>
		<th align="left"><?php _e('Name and Description','rmcommon'); ?></th>
		<th><?php _e('Version','rmcommon'); ?></th>
		<th><?php _e('Author','rmcommon'); ?></th>
		<th><?php _e('Status','rmcommon'); ?></th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<th width="20"><input type="checkbox" name="checkallb" id="checkallb" /></th>
		<th align="left"><?php _e('Name and Description','rmcommon'); ?></th>
		<th><?php _e('Version','rmcommon'); ?></th>
		<th><?php _e('Author','rmcommon'); ?></th>
		<th><?php _e('Status','rmcommon'); ?></th>
	</tr>
	</tfoot>
	<tbody>
	<?php if(empty($installed_plugins)): ?>
	<tr class="even">
		<td class="error" colspan="5" align="center"><?php _e('There are not plugins installed yet!','rmcommon'); ?></td>
	</tr>
	<?php endif; ?>
	</tbody>
	
</table>

<h3><?php _e('Available Plugins'); ?></h3>
<table class="outer" cellspacing="0" cellpadding="0">
	<thead>
	<tr>
		<th align="left"><?php _e('Name','rmcommon'); ?></th>
		<th align="left"><?php _e('Description', 'rmcommon'); ?></th>
		<th><?php _e('Version','rmcommon'); ?></th>
		<th><?php _e('Author','rmcommon'); ?></th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<th align="left"><?php _e('Name and Description','rmcommon'); ?></th>
		<th align="left"><?php _e('Description', 'rmcommon'); ?></th>
		<th><?php _e('Version','rmcommon'); ?></th>
		<th><?php _e('Author','rmcommon'); ?></th>
	</tr>
	</tfoot>
	<tbody>
	<?php if(empty($available_plugins)): ?>
	<tr class="even">
		<td class="error" colspan="4" align="center"><?php _e('There are not available plugins yet!','rmcommon'); ?></td>
	</tr>
	<?php endif; ?>
	<?php foreach ($available_plugins as $plugin): ?>
	<tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
		<td>
			<strong><?php echo $plugin->get_info('name'); ?></strong>
			<span class="rmc_options">
				<a href="plugins.php?action=install&amp;plugin=<?php echo $plugin->get_info('dir'); ?>"><?php _e('Install','rmcommon'); ?></a> | 
				<a href="<?php echo $plugin->get_info('web'); ?>"><?php _e('Visit Web site','rmcommon'); ?></a>
			</span>
		</td>
		<td>
			<span class="descriptions"><?php echo $plugin->get_info('description'); ?></span>
		</td>
		<td align="center">
			<strong><?php echo $plugin->get_info('version'); ?></strong>
		</td>
		<td align="center">
			<?php if($plugin->get_info('web')!=''): ?>
			<strong><a href="<?php echo $plugin->get_info('web'); ?>"><?php echo $plugin->get_info('author'); ?></a></strong>
			<?php else: ?>
			<strong><?php echo $plugin->get_info('author'); ?></strong>
			<?php endif; ?><br />
			<?php echo $plugin->get_info('email'); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	
</table>