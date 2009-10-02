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
						<td><?php _e('Size Name','rmcommon'); ?></td>
						<td colspan="4"><?php _e('Value','rmcommon'); ?></td>
					</tr>
					<tr class="even">
						<td rowspan="3"><input type="text" name="sizes[0][name]" id="sizes[0][name]" value="default" size="30" /></td>
                        <td colspan="2" style="background-color: #FAEBD3;">
                            <?php _e('Thumbnails','rmcommon'); ?>
                            (<em><label><input type="checkbox" name="sizes[0][thumbs]" value="1" /> <?php _e('Create thumbanails','rmcommon'); ?></label></em>)
                        </td>
                        <td colspan="2" style="background: #DBE9F2;"><?php _e('Images','rmcommon'); ?></td>
                    </tr>
                    <tr class="even">
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
                        
                    </tr>
				</table>
			</td>
		</tr>
	</table>
</div>
</form>