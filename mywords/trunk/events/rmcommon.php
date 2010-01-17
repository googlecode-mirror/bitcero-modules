<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MywordsRmcommonPreload
{
	public function eventRmcommonLoad_right_widgets($widgets){
		global $xoopsModule;
	
		if (!isset($xoopsModule) || ($xoopsModule->getVar('dirname')!='system' && $xoopsModule->getVar('dirname')!='mywords'))
			return $widgets;
		
	    if (RMCSUBLOCATION=='new_post'){
			include_once '../widgets/widget_publish.php';
			$widgets[] = mw_widget_publish();
			
			include_once '../widgets/widget_categories.php';
			$widgets[] = mw_widget_categories();
	        
	        include_once '../widgets/widget_tags.php';
	        $widgets[] = mw_widget_addtags();
	        
	    }
		return $widgets;
	}
	
}
