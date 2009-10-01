<h1 class="rmc_titles"><?php _e('Create New Category','rmcommon'); ?></h1>
<form name="frmcats" id="img-cat-form" method="post" accept="images.php">
<div class="form_container">
	<table class="table_form" cellspacing="0">
		<tr valign="top" class="cell_fields">
			<td class="form_captions">
				<label for="cat-name"><?php _e('Category Name:','rmcommon'); ?></label>
			</td>
			<td><input type="text" name="name" id="cat-name" value="" class="required" size="50" />
		</tr>
		<tr valign="top" class="cell_fields">
			<td class="form_captions">
				<label for="cat-status"><?php _e('Category Status:','rmcommon'); ?></label>
			</td>
			<td>
				<label><input type="radio" name="status" id="cat-status" value="close" /> <?php _e('Inactive','rmcommon'); ?></label>
				<label><input type="radio" name="status" id="cat-status" value="open" checked="checked" /> <?php _e('Active','rmcommon'); ?></label>
			</td>
		</tr>
		<tr valign="top" class="cell_fields">
			<td class="form_captions">
				<label for="write[]"><?php _e('Groups that can upload images:','rmcommon'); ?></label>
			</td>
			<td>
				<?php echo $write->render(); ?>
			</td>
		</tr>
		<tr valign="top" class="cell_fields">
			<td class="form_captions">
				<label for="read[]"><?php _e('Groups that can use this ctegory:','rmcommon'); ?></label>
			</td>
			<td>
				<?php echo $read->render(); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><h1 class="rmc_titles"><?php _e('Images Configurations','rmcommon'); ?></h1></td>
		</tr>
		<tr>
			<td colspan="2">
				<?php _e('This configurations stablish the sizes in wich images will be created.','rmcommon'); ?>
				<?php _e('You can specify all sizes that you wish according to your needs.','rmcommon'); ?>
			</td>
		</tr>
		<tr class="cell_fields" valign="top">
			<td colspan="2">
				<table class="outer" cellspacing="0">
					<tr class="head">
						<td><?php _e('Size Names','rmcommon'); ?></td>
						<td><?php _e('Values','rmcommon'); ?></td>
					</tr>
					<tr>
						<td><input type="text" name="sizes[]"
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
</form>