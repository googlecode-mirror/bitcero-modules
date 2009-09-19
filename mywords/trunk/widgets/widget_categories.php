<?php
// $Id: widget_categories.php 50 2009-09-17 20:36:31Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Categories widget
*/
function mw_widget_categories(){
	$widget['title'] = __('Categories','admin_mywords');
    RMTemplate::get()->add_script('../include/js/widget_cats.js');
	$widget['icon'] = '';
	ob_start();
?>
<div class="rmc_widget_content_reduced">
<form id="mw-post-categos-form">
<div class="w_categories" id="w-categos-container">
<?php
$categories = array();
MWFunctions::categos_list($categories);
foreach ($categories as $catego){
?>
<label class="cat_label" style="padding-left: <?php _e($catego['indent']*10); ?>px;"><input type="checkbox" name="categories[]" id="categories[]" value="<?php _e($catego['id_cat']); ?>" /> <?php _e($catego['name']); ?></label>
<?php
}
?>
</div>
<div class="w_catnew_container">
    <a href="#" id="a-show-new"><strong><?php _e('+ Add Categories','admin_mywords'); ?></strong></a>
    <div id="w-catnew-form">
    	<label class="error" style="display: none;" for="w-name"><?php _e('Please provide a name','admin_mywords'); ?></label>
    	<input type="text" name="name" id="w-name" value="" class="required" />
    	<select name="parent" id="w-parent">
    		<option value="0"><?php _e('Parent category','admin_mywords'); ?></option>
    		<?php foreach ($categories as $catego): ?>
    		<option value="<?php _e($catego['id_cat']); ?>"><?php _e($catego['name']); ?></option>
    		<?php endforeach; ?>
    	</select>
    	<input type="button" id="create-new-cat" value="<?php _e('Add','admin_mywords'); ?>" />
    	<a href="javascript:;"><?php _e('Cancel','admin_mywords'); ?></a>
    </div>
</div>
</form>
</div>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
}