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
<?php if (!$cat->isNew()): ?>
<div id="files-container">

</div>
<a href="javascript:;" onclick="$('#files-container').uploadifyUpload();">Upload</a> |
<a href="javascript:;" onclick="$('#files-container').uploadifyClearQueue();">Clear All</a>
<?php endif; ?>

