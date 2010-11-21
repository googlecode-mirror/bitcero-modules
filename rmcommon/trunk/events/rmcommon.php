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
		
		if(!defined('RMCLOCATION')) return;
		
		include_once RMCPATH.'/include/right_widgets.php';
		
		global $xoopsModule;
		if (RMCLOCATION=='modules' && $xoopsModule->dirname()=='rmcommon' && rmc_server_var($_REQUEST, 'action', '')=='')
			$widgets[] = rmc_available_mods();
		
		return $widgets;
		
	}
	
	public function eventRmcommonXoopsCommonEnd(){
		global $xoopsConfig;
        
        // Get preloaders from current theme
        RMEvents::get()->load_extra_preloads(XOOPS_THEME_PATH.'/'.$xoopsConfig['theme_set'], ucfirst($xoopsConfig['theme_set'].'Theme'));
        
		$url = RMFunctions::current_url();
		$p = parse_url($url);
        
        $config = RMFunctions::configs();
		
		if(substr($p['path'], -11)=='backend.php' && $config['rss_enable']){
			include_once RMCPATH.'/rss.php';
			die();
		}
	}
}
