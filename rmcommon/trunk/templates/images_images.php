<h1 class="rmc_titles"><?php _e('Images Manager','rmcommon'); echo ': '.$category->getVar('name'); ?></h1>
<form name="list_images" method="post" action="images.php" id="list-images">
<div class="rmc_bulkactions">
<?php echo $nav->display(); ?>
<select name="action">
    <option value=""><?php _e('Bulk Actions...','rmcommon'); ?></option>
    <option value="delete"><?php _e('Delete','rmcommon'); ?></option>
    <option value="thumbs"><?php _e('Update thumbnails','rmcommon'); ?></option>
</select>
<input type="submit" value="<?php _e('Apply','rmcommon'); ?>" /> &nbsp;&nbsp;
<select name="category" onchange="window.location = 'images.php?category='+$(this).val();" id="category-select">
	<?php foreach($categories as $catego): ?>
	<option value="<?php echo $catego['id']; ?>"<?php echo $catego['id']==$cat ? ' selected="selected"' : ''; ?>><?php echo $catego['name']; ?></option>
	<?php endforeach; ?>
</select> &nbsp;&nbsp;
<a href="javascript:;" onclick="window.location = 'images.php?action=new&category='+$('#category-select').val();"><?php _e('Create Images','rmcommon'); ?></a>
</div>
<table class="outer" cellspacing="0">
	<tr>
		<th width="30" align="center"><input type="checkbox" name="checkall" id="checkall" onclick="$('#list-images').toggleCheckboxes(':not(#checkall)');" /></th>
		<th align="left" width="70"><?php _e('File','rmcommon'); ?></th>
		<th><?php _e('Details','rmcommon'); ?></th>
		<th><?php _e('Author','rmcommon'); ?></th>
		<th><?php _e('Date','rmcommon'); ?></th>
	</tr>
	<?php if(empty($images)): ?>
	<tr class="even error">
		<td colspan="5">
			<?php _e('There are not images yet!','rmcommon'); ?>
		</td>
	</tr>
	<?php endif; ?>
    <?php foreach($images as $image): ?>
    <tr class="<?php echo tpl_cycle("even,odd"); ?>">
        <td align="center"><input type="checkbox" name="imgs[<?php echo $image['id']; ?>" value="<?php echo $image['id']; ?>" /></td>
        <td><img src="<?php echo $image['file']; ?>" alt="" width="70" height="45" /></td>
        <td>
            <strong><?php echo $image['title']; ?></strong>
            <?php if($image['desc']!=''): ?>
            <span class="description"><?php echo $image['desc']; ?></span>
            <?php endif; ?>
            <span class="rmc_options">
                <a href="images.php?action=edit&amp;id=<?php echo $image['id']; ?>&amp;page=<?php echo $page; ?>"><?php _e('Edit','rmcommon'); ?></a> | 
                <a href="images.php?action=delete&amp;id=<?php echo $image['id']; ?>&amp;page=<?php echo $page; ?>" onclick="return confirm('<?php _e('Do you really want to delete &quot;'.$image['title'].'&quot;?'); ?>');"><?php _e('Delete','rmcommon'); ?></a>
            </span>
        </td>
        <td align="center"><?php echo $image['author']->uname(); ?></td>
        <td align="center"><?php echo formatTimestamp($image['date'], 's'); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</form>