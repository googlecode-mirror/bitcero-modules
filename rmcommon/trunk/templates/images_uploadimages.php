<h1 class="rmc_titles"><?php _e('Upload Images','rmcommon'); ?></h1>
<div class="select_image_cat">
	<form name="frmcat" method="get" action="images.php">
	<?php _e('Select Category:','rmcommon'); ?> &nbsp;
	<select name="category">
		<option value=""><?php _e('Select...','rmcommon'); ?></option>
		<?php foreach($categories as $category): ?>
		<option value="<?php echo $category['id']; ?>"<?php echo $cat->id()==$category['id'] ? ' selected="selected"' : '' ?>><?php echo $category['name']; ?></option>
		<?php endforeach; ?>
	</select>
	<input type="submit" value="<?php _e('Upload images','rmcommon'); ?>" />
	<input type="hidden" name="action" value="new" />
	</form>
</div>
<div id="upload-controls">
<?php if (!$cat->isNew()): ?>
<input type="button" class="formButton imgcontrols" style="float: left; margin-right: 5px;" onclick="$('#files-container').uploadifyUpload();" value="<?php _e('Upload','rmcommon'); ?>" />
<input type="button" class="imgcontrols" style="float: left; margin-right: 5px;" onclick="$('#files-container').uploadifyClearQueue(); $('#upload-errors').html('');" value="<?php _e('Clear All','rmcommon'); ?>" />
<div id="files-container">

</div>
</div>
<div id="upload-errors">

</div>
<div id="resizer-bar">
<strong><?php _e('Resizing images','rmcommon'); ?></strong>
<div class="thebar">
<div class="indicator" id="bar-indicator">0</div>
</div>
<span><?php _e('Please, do not close the window until resizing process has finished!','rmcommon'); ?></span>
    <div class="donebutton">
        <input type="button" class="donebutton" value="<?php _e('Done! Click to continue...','rmcommon'); ?>" onclick="imgcontinue();" />
    </div>
</div>
<?php endif; ?>

