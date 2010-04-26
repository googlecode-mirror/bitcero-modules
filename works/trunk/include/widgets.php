<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function works_widget_categories(){
	global $xoopsSecurity;
	
	$widget['title'] = "Quick Categories";
	$widget['icon'] = '../images/cats16.png';
	ob_start();
?>
<form name="frm_categos" id="frm-categos" method="post" action="categos.php">
	<label for="title"><?php _e('Title','admin_works'); ?></label>
	<input type="text" name="name" id="title" size="30" />
	<label for="description"><?php _e('Description','admin_works'); ?></label>
	<textarea name="desc" id="description"></textarea>
	<input type="hidden" name="op" value="save" />
	<input type="hidden" name="return" value="<?php echo urlencode("admin/index.php"); ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
	<input type="submit" value="<?php _e('Create Category','admin_works'); ?>" />
</form>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
}

function works_widget_types(){
	global $xoopsSecurity;
	
	$widget['title'] = "Quick Customers Types";
	$widget['icon'] = '../images/types.png';
	ob_start();
?>
<form name="frm_types" id="frm-types" method="post" action="types.php">
	<label for="type-name"><?php _e('Type name','admin_works'); ?></label>
	<input type="text" name="type[]" id="type-name" size="30" />
	<input type="hidden" name="op" value="save" />
	<input type="hidden" name="return" value="<?php echo urlencode("admin/index.php"); ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
	<input type="submit" value="<?php _e('Create Category','admin_works'); ?>" />
</form>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
}