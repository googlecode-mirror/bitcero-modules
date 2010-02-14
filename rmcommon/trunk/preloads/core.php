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
        
        if(substr($_SERVER['REQUEST_URI'], -16)=='system/admin.php' && empty($_POST)){
            header('location: '.XOOPS_URL.'/modules/rmcommon/');
            die();
        }
        
		require_once XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';
		
	}
	
	public function eventCoreFooterEnd(){
		
		ob_end_flush();

	}
}
