<h1 class="rmc_titles"><?php _e('Create New Category','rmcommon'); ?></h1>
<form name="frmcats" id="img-cat-form" method="post" accept="images.php">
<div class="form_container">
	<table class="table_form" cellspacing="0">
		<tr valign="top" class="cell_fields">
			<td class="form_captions">
				<label for="cat-name"><?php _e('Category Name:','rmcommon'); ?></label>
			</td>
			<td><input type="text" name="name" id="cat-name" value="<?php echo $edit ? $cat->getVar('name') : $name; ?>" class="required" size="50" />
		</tr>
		<tr valign="top" class="cell_fields">
			<td class="form_captions">
				<label for="cat-status"><?php _e('Category Status:','rmcommon'); ?></label>
			</td>
			<td>
				<label><input type="radio" name="status" id="cat-status" value="close"<?php echo $active!='open' ? ' checked="checked"' : ''; ?> /> <?php _e('Inactive','rmcommon'); ?></label>
				<label><input type="radio" name="status" id="cat-status" value="open"<?php echo $active=='open' ? ' checked="checked"' : ($edit ? '' : ' checked="checked"'); ?> /> <?php _e('Active','rmcommon'); ?></label>
			</td>
		</tr>
		<tr valign="top" class="cell_fields">
			<td class="form_captions">
				<label for="write[]"><?php _e('Groups that can upload images:','rmcommon'); ?></label>
			</td>
			<td>
				<?php echo $fwrite->render(); ?>
			</td>
		</tr>
		<tr valign="top" class="cell_fields">
			<td class="form_captions">
				<label for="read[]"><?php _e('Groups that can use this category:','rmcommon'); ?></label>
			</td>
			<td>
				<?php echo $fread->render(); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><h1 class="rmc_titles"><?php _e('Images Configurations','rmcommon'); ?></h1></td>
		</tr>
		<tr>
			<td colspan="2" id="sizes-container-all">
				
                <?php _e('This configurations stablish the sizes in wich images will be created.','rmcommon'); ?>
				<?php _e('You can specify all sizes that you wish according to your needs.','rmcommon'); ?>
                
                <?php if($edit): ?>
                	<?php $scounter = 0 ?>
                	<?php foreach($cat->getVar('sizes') as $size): ?>
                		<table class="outer sinlge_size" cellspacing="1" width="100%" id="table-single-size-<?php echo $scounter; ?>">
		                    <tr class="head">
		                        <td width="170"><?php _e('Size name','rmcommon'); ?></td>
		                        <td>
		                            <a href="javascript:;" class="delsize" id="delete-<?php echo $scounter; ?>" onclick="delete_size(this);"><?php _e('Delete','rmcommon'); ?></a>
		                            <?php _e('Images','rmcommon'); ?>
		                        </td>
		                    </tr>
		                    <tr class="even">
		                        <td rowspan="2" valign="top"><input type="text" name="sizes[<?php echo $scounter; ?>][name]" id="sizes[<?php echo $scounter; ?>][name]" value="<?php echo $size['name']; ?>" size="20" class="required" /></td>
		                        <td>
		                            <strong><?php _e('Width:','rmcommon'); ?></strong>
		                            <input type="text" name="sizes[<?php echo $scounter; ?>][width]" value="<?php echo $size['width']; ?>" size="5" /> &nbsp; 
		                            <strong><?php _e('Height:','rmcommon'); ?></strong>
		                            <input type="text" name="sizes[<?php echo $scounter; ?>][height]" value="<?php echo $size['height']; ?>" size="5" />
		                        </td>
		                    </tr>
		                    <tr class="even">
		                        <td colspan="2">
		                            <label><input type="radio" name="sizes[<?php echo $scounter; ?>][type]" value="scale"<?php echo $size['type']=='scale' ? ' checked="checked"' : ''; ?> /> <?php _e('Scale','rmcommon'); ?></label>
		                            <label><input type="radio" name="sizes[<?php echo $scounter; ?>][type]" value="crop"<?php echo $size['type']=='crop' ? ' checked="checked"' : ''; ?> /> <?php _e('Crop','rmcommon'); ?></label>
		                            <label><input type="radio" name="sizes[<?php echo $scounter; ?>][type]" value="none"<?php echo $size['type']=='none' ? ' checked="checked"' : ''; ?> /> <?php _e('No Resize','rmcommon'); ?></label>
		                        </td>
		                    </tr>
		                </table>
		                <?php $scounter++; ?>
                	<?php endforeach; ?>
                <?php else: ?>
	                <table class="outer sinlge_size" cellspacing="1" width="100%" id="table-single-size-0">
	                    <tr class="head">
	                        <td width="170"><?php _e('Size name','rmcommon'); ?></td>
	                        <td>
	                            <a href="javascript:;" class="delsize" id="delete-0" onclick="delete_size(this);"><?php _e('Delete','rmcommon'); ?></a>
	                            <?php _e('Images','rmcommon'); ?>
	                        </td>
	                    </tr>
	                    <tr class="even">
	                        <td rowspan="2" valign="top"><input type="text" name="sizes[0][name]" id="sizes[0][name]" value="" size="20" class="required" /></td>
	                        <td>
	                            <strong><?php _e('Width:','rmcommon'); ?></strong>
	                            <input type="text" name="sizes[0][width]" value="" size="5" /> &nbsp; 
	                            <strong><?php _e('Height:','rmcommon'); ?></strong>
	                            <input type="text" name="sizes[0][height]" value="" size="5" />
	                        </td>
	                    </tr>
	                    <tr class="even">
	                        <td colspan="2">
	                            <label><input type="radio" name="sizes[0][type]" value="scale" /> <?php _e('Scale','rmcommon'); ?></label>
	                            <label><input type="radio" name="sizes[0][type]" value="crop" /> <?php _e('Crop','rmcommon'); ?></label>
	                            <label><input type="radio" name="sizes[0][type]" value="none" checked="checked" /> <?php _e('No Resize','rmcommon'); ?></label>
	                        </td>
	                    </tr>
	                </table>
	            <?php endif; ?>
                <div class="new_container"><input type="button" value="<?php _e('New Size','rmcommon'); ?>" id="new-size-button" /></div>
                
			</td>
		</tr>
		<tr class="cell_fields">
			<td align="left" colspan="2">
				<input type="submit" value="<?php _e($edit ? 'Update Category' : 'Create Category','rmcommon'); ?>" class="button" />
				<input type="button" value="<?php _e('Cancel','rmcommon'); ?>" onclick="window.location = 'images.php?action=showcats';" />
			</td>
		</tr>
	</table>
	<!-- /Form Table -->
</div>
<input type="hidden" name="action" value="<?php echo $edit ? 'saveedit' : 'save'; ?>" />
<?php if($edit): ?><input type="hidden" name="id" value="<?php echo $cat->id(); ?>" /><?php endif; ?>
</form>