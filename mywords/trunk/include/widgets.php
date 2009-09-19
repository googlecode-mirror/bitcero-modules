<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function mywords_right_widgets_controller($widgets){
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

function mywords_left_widgets_controller($widgets){
	return $widgets;
}

function mywords_gui_output($output){

	return $output;
	
}