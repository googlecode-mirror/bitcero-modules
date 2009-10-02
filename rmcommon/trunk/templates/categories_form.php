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
						<td><?php _e('Configured sizes','rmcommon'); ?></td>
					</tr>
					<tr class="even">
						<td><?php _e('This configurations determines how much different sizes will be created when a image has been uploaded to this category.','rmcommon'); ?></td>
					</tr>
					<tr class="odd">
						<td>
							<table class="outer" cellspacing="1" width="100%">
								<tr class="head">
									<td><?php _e('Size name','rmcommon'); ?></td>
			                        <td colspan="2">
			                            <label><input type="checkbox" name="sizes[0][thumbs]" value="1" /> <?php _e('Create thumbnails','rmcommon'); ?></label>
			                        </td>
			                        <td colspan="2"><?php _e('Images','rmcommon'); ?></td>
			                    </tr>
			                    <tr class="even">
			                    	<td rowspan="2"><input type="text" name="sizes[0][name]" id="sizes[0][name]" value="default" size="30" /></td>
			                        <td>
			                            <strong><?php _e('Width:','rmcommon'); ?></strong>
			                            <input type="text" name="sizes[0][twidth]" value="" size="10" />
			                        </td>
			                        <td>
			                            <strong><?php _e('Height:','rmcommon'); ?></strong>
			                            <input type="text" name="sizes[0][theight]" value="" size="10" />
			                        </td>
			                        <td>
			                            <strong><?php _e('Width:','rmcommon'); ?></strong>
			                            <input type="text" name="sizes[0][width]" value="" size="10" />
			                        </td>
			                        <td>
			                            <strong><?php _e('Height:','rmcommon'); ?></strong>
			                            <input type="text" name="sizes[0][height]" value="" size="10" />
			                        </td>
								</tr>
			                    <tr class="even">
			                        <td colspan="2">
                        				<strong><?php _e('Resize method:','rmcommon'); ?></strong>
                        				<label><input type="radio" value="sizes[0][thresize]" value="crop" checked="checked" /> <?php _e('Crop','rmcommon'); ?></label>
                        				<label><input type="radio" value="sizes[0][thresize]" value="scale" /> <?php _e('Scale','rmcommon'); ?></label>
			                        </td>
			                        <td colspan="2">
                        				<strong><?php _e('Resize method:','rmcommon'); ?></strong>
                        				<label><input type="radio" value="sizes[0][imgresize]" value="scale" checked="checked" /> <?php _e('Scale','rmcommon'); ?></label>
                        				<label><input type="radio" value="sizes[0][imgresize]" value="crop" /> <?php _e('Crop','rmcommon'); ?></label>
                        				<label><input type="radio" value="sizes[0][imgresize]" value="none" /> <?php _e('No Resize','rmcommon'); ?></label>
			                        </td>
			                    </tr>
							</table>
							<!-- /Single Configurations -->
						</td>
					</tr>
					<tr class="odd">
						<td align="center">
							<input type="button" value="<?php _e('New Size','rmcommon'); ?>" />
						</td>
					</tr>
				</table>
				<!-- /Images Configuration Container Table -->
			</td>
		</tr>
		<tr class="cell_fields">
			<td align="center" colspan="2">
				<input type="submit" value="<?php _e('Create Category','rmcommon'); ?>" class="button" />
				<input type="button" value="<?php _e('Cancel','rmcommon'); ?>" onclick="window.location = 'images.php?action=showcats';" />
			</td>
		</tr>
	</table>
	<!-- /Form Table -->
</div>
</form>