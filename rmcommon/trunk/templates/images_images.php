<h1 class="rmc_titles"><?php _e('Images Manager','rmcommon'); ?></h1>

<table class="outer" cellspacing="0">
	<tr>
		<th width="30" align="center"><input type="checkbox" name="checkall" id="checkall" onclick="$('#form-images').toggleCheckboxes(':not(#checkall)');" /></th>
		<th align="left"><?php _e('File','rmcommon'); ?></th>
		<th><?php _e('Category','rmcommon'); ?></th>
		<th><?php _e('Author','rmcommon'); ?></th>
		<th><?php _e('Date','rmcommon'); ?></th>
	</tr>
	<?php if(!isset($eimages) || empty($images)): ?>
	<tr class="even error">
		<td colspan="5">
			<?php _e('There are not images yet!','rmcommon'); ?>
		</td>
	</tr>
	<?php endif; ?>
</table>