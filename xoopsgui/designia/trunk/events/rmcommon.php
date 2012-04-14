<?php
// $Id$
// --------------------------------------------------------------
// Designia v1.0
// Theme for Common Utilities 2
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DesigniaRmcommonPreload
{
    public function eventRmcommonXoopsCommonEnd(){
		global $xoopsConfig;
              
		$url = RMFunctions::current_url();
		$p = parse_url($url);
		parse_str($p['query']);
        
        if(!isset($designia)) return;
		
		switch($designia){
			case 'settings':
				include_once RMCPATH.'/themes/designia/include/settings.php';
				die();
		}
	}
}