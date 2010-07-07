<?php
// $Id$
// --------------------------------------------------------------
// xThemes for XOOPS
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

class XthemesCorePreload extends XoopsPreloadItem
{
	public function eventCoreHeaderAddmeta(){
		global $xoopsTpl, $xoopsConfig;
		
		if(!$xoopsTpl) return;
		
		$dir = XOOPS_THEME_PATH.'/'.$xoopsConfig['theme_set'];
		
		if(is_file($dir.'/config/theme.php')){
            $xoopsTpl->plugins_dir[] = XOOPS_ROOT_PATH.'/modules/xthemes/smarty';
			$xoopsTpl->plugins_dir[] = $dir.'/plugins';
		}
		
	}
}
