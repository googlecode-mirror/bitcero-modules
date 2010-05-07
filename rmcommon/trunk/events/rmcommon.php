<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RmcommonRmcommonPreload
{
	public function eventRmcommonLoadRightWidgets($widgets){
		
		include_once RMCPATH.'/include/right_widgets.php';
		
		global $xoopsModule;
		if (RMCLOCATION=='modules' && $xoopsModule->dirname()=='rmcommon')
			$widgets[] = rmc_available_mods();
		
		return $widgets;
		
	}
}
