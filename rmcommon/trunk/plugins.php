<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','plugins');
include_once '../../include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/rmcommon/admin_loader.php';

function show_rm_plugins(){
	
	$path = RMCPATH.'/plugins';
	$dir_list = XoopsLists::getDirListAsArray($path);
	
	$available_plugins = array();
	$installed_plugins = array();
	
    foreach ($dir_list as $dir){
        
        if (!file_exists($path.'/'.$dir.'/'.strtolower($dir).'-plugin.php')) continue;
        
        $phand = new RMPlugin($dir); // PLugin handler
        
        if ($phand->isNew()){
			
			$available_plugins[] = $phand->plugin($dir);
			
        } else {
			
			$installed_plugins[] = $phand->plugin();
			
        }
        
    }
    
	
	RMFunctions::create_toolbar();
	xoops_cp_header();
	
	include RMTemplate::get()->get_template('rmc_plugins.php', 'module', 'rmcommon');
	
	xoops_cp_footer();
	
}

/**
* This function install a plugin and all their functionallity
*/
function install_rm_plugin(){
	
	
	
}

// Allow to plugins to take control over this section and show their own options
RMEvents::get()->run_event('rmcommon.plugins.check.actions');

$action = rmc_server_var($_REQUEST,'action','');

switch($action){
	case 'install':
		install_rm_plugin();
		break;
	default:
		show_rm_plugins();
		break;
}