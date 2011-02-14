<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
load_mod_locale('works');

function works_widget_categories(){
	global $xoopsSecurity;
	
	$widget['title'] = __("Quick Categories", 'works');
	$widget['icon'] = '../images/cats16.png';
	ob_start();
?>
<form name="frm_categos" id="frm-categos" method="post" action="categos.php">
	<label for="title"><?php _e('Title','works'); ?></label>
	<input type="text" name="name" id="title" size="30" />
	<label for="description"><?php _e('Description','works'); ?></label>
	<textarea name="desc" id="description"></textarea>
	<input type="hidden" name="op" value="save" />
	<input type="hidden" name="return" value="<?php echo urlencode("admin/index.php"); ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
	<input type="submit" value="<?php _e('Create Category','works'); ?>" />
</form>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
}

function works_widget_types(){
	global $xoopsSecurity;
	
	$widget['title'] = __("Quick Customers Types",'works');
	$widget['icon'] = '../images/types.png';
	ob_start();
?>
<form name="frm_types" id="frm-types" method="post" action="types.php">
	<label for="type-name"><?php _e('Type name','works'); ?></label>
	<input type="text" name="type[]" id="type-name" size="30" />
	<input type="hidden" name="op" value="save" />
	<input type="hidden" name="return" value="<?php echo urlencode("admin/index.php"); ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
	<input type="submit" value="<?php _e('Create Type','works'); ?>" />
</form>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
}

function works_widget_info(){
	
	$widget['title'] = __("About Professional Works",'works');
	$widget['icon'] = '';
	ob_start();
?>
<ul>
	<li>Created by <strong><a href="http://redmexico.com.mx" target="_blank">BitC3R0</a></strong>.</li>
	<li>A module by <strong><a href="http://redmexico.com.mx" target="_blank">Red México</a></strong>.</li>
	<li>Title icons by <strong><a href="http://pixel-mixer.com" target="_blank">PixelMixer</a></strong>.</li>
</ul>
<span class="pw_descriptions"><?php echo sprintf(__('You can contact me at %s.','works'), '<strong>i.bitcero@gmail.com</strong>'); ?><br />
You can get help by visiting <a href="http://redmexico.com.mx/docs/" target="_blank">Red México Docs</a>.</span>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
	
}