<?php RMTemplate::get()->add_head('<script type="text/javascript">$(document).ready(function(){$("#frmupdimg").validate();});</script>'); ?>
<h1 class="rmc_titles"><?php _e('Edit Image','rmcommon'); ?></h1>
<form name="frmupdimg" id="frmupdimg" method="post" action="images.php">
<table cellpadding="0" cellspacing="0" width="90%" class="" align="center">
    <tr class="odd">
        <td rowspan="4" width="25%"><img src="<?php echo $image_data['thumbnail']; ?>" alt="" style="max-width: 150px; max-height: 100px;" /></td>
        <td><?php echo $image_data['file']; ?></td>
    </tr>
    <tr class="odd">
        <td><?php echo $image_data['mime']; ?></td>
    </tr>
    <tr class="odd">
        <td><?php echo formatTimestamp($image_data['date'], 'l'); ?></td>
    </tr>
    <tr class="odd">
        <td>
            <?php foreach($image_data['sizes'] as $size): ?>
            <a href="javascript:;" onclick="show_image_pop('<?php echo $size['file']; ?>');"><?php echo $size['name']; ?></a> | 
            <?php endforeach; ?>
            <a href="javascript:;" onclick="show_image_pop('<?php echo $image_data['url']; ?>');"><?php _e('Original Size','rmcommon'); ?></a> 
        </td>
    </tr>
    <tr class="even">
        <td class="head">*<?php _e('Title:', 'rmcommon'); ?></td>
        <td><input name="title" type="text" value="<?php echo $image_data['title']; ?>" size="50" class="required" /></td>
    </tr>
    <tr class="odd">
    	<td class="head"><?php _e('Category:','rmcommon'); ?></td>
    	<td>
    		<select name="cat">
    			<?php foreach($categories as $catego): ?>
    			<option value="<?php echo $catego['id']; ?>"<?php echo $catego['id']==$cat->id() ? ' selected="selected"' : ''; ?>><?php echo $catego['name']; ?></option>
    			<?php endforeach; ?>
    		</select>
    	</td>
    </tr>
    <tr class="even" valign="top">
        <td class="head"><?php _e('Description:','rmcommon'); ?></td>
        <td><textarea name="desc" style="width: 80%; height: 100px;"><?php echo $image_data['desc']; ?></textarea></td>
    </tr>
    <tr class="foot">
    	<td colspan="2">
    		<input type="submit" value="<?php _e('Update Image!','rmcommon'); ?>" />
    		<input type="button" value="<?php _e('Cancel','rmcommon'); ?>" onclick="window.location = 'images.php?category=<?php echo $cat->id(); ?>&page=<?php echo $page; ?>';" />
    	</td>
    </tr>
</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
</form>
<div id="image-loader"></div>