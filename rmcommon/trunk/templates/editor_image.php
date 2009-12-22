<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<{$xoops_langcode}>" lang="<{$xoops_langcode}>">
<head>
<?php

/*foreach ($this->tpl_scripts as $script){
	echo '<script type="'.$script['type'].'" src="'.$script['url'].'"></script>'."\n";
}*/
		
foreach (RMTemplate::get()->tpl_styles as $style){
	echo '<link rel="stylesheet" type="text/css" media="'.$style['media'].'" href="'.$style['url'].'"'.($style['more']!=''?' '.$style['more']:'').' />'."\n";
}

/*foreach ($this->tpl_head as $head){
	echo $head."\n";
}*/
?>
</head>
</body>
<div id="img-toolbar">
	<a href="javascript:;" class="select"><?php _e('Upload Files','rmcommon'); ?></a>
	<a href="javascript:;"><?php _e('From URL','rmcommon'); ?></a>
	<a href="javascript:;"><?php _e('From Library','rmcommon'); ?></a>
</div>
</body>
</html>