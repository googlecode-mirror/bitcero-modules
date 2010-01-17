<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RmcommonCorePreload extends XoopsPreloadItem
{
	public function eventCoreIncludeCommonStart(){
		
		require_once XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';
		
	}
	
	public function eventCoreFooterEnd(){
		
		ob_end_flush();

	}
}
