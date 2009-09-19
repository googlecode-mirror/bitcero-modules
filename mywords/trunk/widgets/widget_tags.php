<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Widget that show tags selection in a post form
*/
function mw_widget_addtags(){
    global $xoopsModuleConfig;
    
    $widget['title'] = __('Add Tags','admin_mywords');
    RMTemplate::get()->add_script('../include/js/tags.js');
    $widget['icon'] = '';
    ob_start();
?>
<div class="rmc_widget_content_reduced">
<form id="mw-post-tags-form">
<div class="tags_box">
<input type="text" name="tagsm" id="tags-m" class="formInput wtLeftInput" />
<input type="button" name="tags-button" id="tags-button" class="button" value="<?php _e('+ Add','admin_mywords'); ?>" /><br />
<span class="descriptions"><em><?php _e('Separate multiple tags with commas','admin_mywords'); ?></em></span>
</div>
<div id="tags-container">
    <span class="tip_legends" style="display: none;">
        <?php _e('Used Tags','admin_mywords'); ?>
    </span>
</div>
<a href="javascript:;" id="show-used-tags"><?php _e('Choose between most populars tags','admin_mywords'); ?></a>
<div id="popular-tags-container" style="display: none;">
    <?php
        $tags = MWFunctions::get_tags('*','','posts DESC',"0,$xoopsModuleConfig[tags_widget_limit]");
        foreach ($tags as $tag):
    ?>
        <a href="javascript:;" id="tag-<?php echo $tag['id_tag']; ?>" class="add_tag" style="font-size: <?php echo MWFunctions::get()->tag_font_size($tag['posts'],2) ?>em;"><?php echo $tag['tag']; ?></a>
    <?php endforeach; ?>
</div>
</form>
</div>
<?php
    $widget['content'] = ob_get_clean();
    return $widget;
}
