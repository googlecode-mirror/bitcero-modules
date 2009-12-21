<h1 class="rmc_titles"><?php _e('Edit Image','rmcommon'); ?></h1>
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
        <td class="head"><?php _e('Title:', 'rmcommon'); ?></td>
        <td><input name="title" type="text" value="<?php echo $image_data['title']; ?>" size="50" class="required" />*</td>
    </tr>
    <tr class="odd" valign="top">
        <td class="head"><?php _e('Description:','rmcommon'); ?></td>
        <td><textarea name="desc" style="width: 80%; height: 100px;"><?php echo $image_data['desc']; ?></textarea></td>
    </tr>
</table>
<div id="image-loader"></div>