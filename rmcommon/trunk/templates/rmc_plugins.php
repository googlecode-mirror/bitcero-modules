<h1 class="rmc_titles"><?php _e('Plugins Manager','rmcommon'); ?></h1>
<div class="descriptions">
<?php _e('Plugins allow to Common Utilities to improve its features and capabilities. Follow is a list with existing plugins, installed and available to install.','rmcommon'); ?>
</div>

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