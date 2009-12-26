<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<{$xoops_langcode}>" lang="<{$xoops_langcode}>">
<head>
<?php

foreach (RMTemplate::get()->tpl_scripts as $script){
	echo '<script type="'.$script['type'].'" src="'.$script['url'].'"></script>'."\n";
}
		
foreach (RMTemplate::get()->tpl_styles as $style){
	echo '<link rel="stylesheet" type="text/css" media="'.$style['media'].'" href="'.$style['url'].'"'.($style['more']!=''?' '.$style['more']:'').' />'."\n";
}

foreach (RMTemplate::get()->tpl_head as $head){
	echo $head."\n";
}
?>
<title><?php _e('Insert Image','rmcommon'); ?></title>
</head>
<body style="background: #FFF;">
<div id="img-toolbar">
	<a href="javascript:;" class="select" id="a-upload" onclick="show_upload();"><?php _e('Upload Files','rmcommon'); ?></a>
	<a href="javascript:;" id="a-url"><?php _e('From URL','rmcommon'); ?></a>
	<a href="javascript:;" id="a-library" onclick="show_library();"><?php _e('From Library','rmcommon'); ?></a>
    <?php echo RMEventsApi::get()->run_event('rm_imgmgr_editor_options', ''); ?>
</div>
<div id="upload-container">
    <div class="categories_selector">
        <form name="selcat" id="select-category" method="post" action="tiny-images.php">
        <?php _e('Select the category where you wish to upload images','rmcommon'); ?>
        <select name="category" onchange="$('#select-category').submit();">
            <option value="0"<?php echo $cat->isNew() ? ' selected="selected"' : ''; ?>><?php _e('Select...','rmcommon'); ?></option>
            <?php foreach($categories as $catego): ?>
                <?php if(!$catego->user_allowed_toupload($xoopsUser)) continue; ?>
                <option value="<?php echo $catego->id(); ?>"<?php echo $cat->id()==$catego->id() ? ' selected="selected"' : ''; ?>><?php echo $catego->getVar('name'); ?></option>
            <?php endforeach; ?>
        </select>
        </form>
    </div>
    <?php if (!$cat->isNew()): ?>
    <div id="upload-controls">
        <div id="upload-errors"></div>
        <div id="files-container"></div>
    </div>
    <div id="resizer-bar">
        <strong><?php _e('Resizing images','rmcommon'); ?></strong>
        <div class="thebar">
            <div class="indicator" id="bar-indicator">0</div>
        </div>
        <span class="message"></span>
        <span><?php _e('Please, do not close the window until resizing process has finished!','rmcommon'); ?></span>
    </div>
    <div id="gen-thumbnails"></div>
    <?php endif; ?>
</div>

<div id="library-container">
    <div class="categories_selector">
        <?php _e('Select the category where you wish to upload images','rmcommon'); ?>
        <select name="category" id="category-field" onchange="show_library();">
            <option value="0"<?php echo $cat->isNew() ? ' selected="selected"' : ''; ?>><?php _e('Select...','rmcommon'); ?></option>
            <?php foreach($categories as $catego): ?>
                <?php if(!$catego->user_allowed_toupload($xoopsUser)) continue; ?>
                <option value="<?php echo $catego->id(); ?>"<?php echo $cat->id()==$catego->id() ? ' selected="selected"' : ''; ?>><?php echo $catego->getVar('name'); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="XOOPS_TOKEN_REQUEST" id="xoops-token" value="<?php echo $xoopsSecurity->createToken(); ?>" />
    </div>
    <div id="library-content" class="loading">
        
    </div>
</div>
</body>
</html>