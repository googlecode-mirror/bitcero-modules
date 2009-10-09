<h1 class="rmc_titles"><?php _e('Categories','rmcommon'); ?></h1>

<table class="outer" cellspacing="0" width="100%">
	<tr>
		<th width="20"><input type="checkbox" name="checkall" id="checkall" /></th>
		<th align="left"><?php _e('Name','rmcommon'); ?></th>
		<th><?php _e('Status','rmcommon'); ?></th>
		<th align="left"><?php _e('Sizes','rmcommon'); ?></th>
		<th align="left"><?php _e('Groups','rmcommon'); ?></th>
		<th><img src="images/images.png" alt="<?php _e('Images','rmcommon'); ?>" /></th>
	</tr>
	<?php if(empty($categories)): ?>
	<tr class="even">
		<td colspan="6"><?php _e('There are not categories yet! You must create at least one category in order to create images.', 'rmcommon'); ?></td>
	</tr>
	<?php endif; ?>
	<?php foreach($categories as $cat): ?>
	<tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
		<td align="center" valign="top"><input type="checkbox" name="cats[]" value="<?php echo $cat['id']; ?>" /></td>
		<td>
			<strong><?php echo $cat['name']; ?></strong>
			<span class="rmc_options">
				<a href="images.php?action=editcat&amp;id=<?php echo $cat['id']; ?>"><?php _e('Edit','rmcommon'); ?></a> |
				<a href="images.php?action=deletecat&amp;id=<?php echo $cat['id']; ?>"><?php _e('Delete','rmcommon'); ?></a> |
				<a href="images.php?category=<?php echo $cat['id']; ?>"><?php _e('Images','rmcommon'); ?></a>
			</span>
		</td>
		<td align="center">
			<?php _e($cat['status']=='open' ? 'Open' : 'Closed','rmcommon'); ?>
		</td>
		<td class="sizes_data">
			<?php foreach ($cat['sizes'] as $size): ?>
				<?php echo $size['name']; ?>
				<?php if($size['type']!='none'): ?>
					(<em><?php echo $size['type']; ?>: <?php echo $size['width']; ?><?php echo $size['height']>0 ? ' x '.$size['height'] : ''; ?></em>)
				<?php else: ?>
					(<em><?php echo _e('No Resize','rmcommon'); ?></em>)
				<?php endif; ?><br />
			<?php endforeach; ?>
		</td>
		<td class="sizes_data">
			<?php _e('Upload:','rmcommon'); ?> <em><?php echo $cat['gwrite']; ?></em><br />
			<?php _e('Access:','rmcommon'); ?> <em><?php echo $cat['gread']; ?></em><br />
		</td>
		<td align="center">
			<strong><?php echo $cat['images']; ?></strong>
		</td>
	</tr>
	<?php endforeach; ?>
</table>