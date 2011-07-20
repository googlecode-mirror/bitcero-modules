<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Add batch images','admin_galleries'); ?></h1>

<form name="frmImgs" id="frmImgs" method="POST" action="images.php" enctype="multipart/form-data" onsubmit="validateTags(); return document.tagsvalidation;">
<div class="gs_upload_container">
    <div class="gs_upload_options">
        <strong><?php _e('User:','admin_galleries'); ?></strong>
        <?php echo $users_field; ?>
        <div class="clearer"></div>
        <br />
        <strong><?php _e('Assign to albums:','admin_galleries'); ?></strong>
        <select name="albums[]" id="sets" multiple="multiple" size="5" />
            <?php foreach($sets as $set): ?>
                <option value="<?php echo $set['id']; ?>"<?php echo $set['selected']?' selected="selected"':''; ?>><?php echo $set['title']; ?></option>
            <?php endforeach; ?>        
        </select>
        
        <strong><?php _e('Tags','admin_galleries'); ?></strong>
        <input type="text" id="tags" name="tags" size="50" value="<?php echo $tags; ?>" />
        
        <input type="submit" value="<?php _e('Show Controls','galleries'); ?>" />
        <input type="hidden" name="op" value="newbulk" />
        
    </div>
    <div class="gs_upload_control">
        <?php if($show_controls): ?>
        <div id="upload-controls">
            <input type="button" class="formButton imgcontrols" style="float: left; margin-right: 5px;" onclick="$('#files-container').uploadifyUpload();" value="<?php _e('Upload','rmcommon'); ?>" />
            <input type="button" class="imgcontrols" style="float: left; margin-right: 5px;" onclick="$('#files-container').uploadifyClearQueue(); $('#upload-errors').html('');" value="<?php _e('Clear All','rmcommon'); ?>" />
            <div id="upload-errors">

            </div>
            <div id="files-container">

            </div>
        </div>
        <div id="resizer-bar">
            <span class="message"></span>
            <strong><?php _e('Resizing images','galleries'); ?></strong>
            <div class="thebar">
            <div class="indicator" id="bar-indicator">0</div>
            </div>
            <span><?php _e('Please, do not close the window until resizing process has finished!','galleries'); ?></span>
                <div class="donebutton">
                    <?php if(!$isupdate): ?><input type="button" class="donebutton" value="<?php _e('Done! Upload more...','rmcommon'); ?>" onclick="imgcontinue();" /><?php endif; ?>
                    <input type="button" class="" value="<?php _e('Done! Show images...','rmcommon'); ?>" onclick="window.location = 'images.php?<?php echo $ruta; ?>';" />
                </div>
            </div>
            <div id="gen-thumbnails"></div>
        </div>
        <?php endif; ?>
    </div>
</div>
<input type="hidden" name="num" value="<?php echo $num_fields; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
<input type="hidden" name="search" value="<?php echo $search; ?>" />
<input type="hidden" name="owner" value="<?php echo $owner; ?>" />
<input type="hidden" name="sort" value="<?php echo $sort; ?>" />
<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
