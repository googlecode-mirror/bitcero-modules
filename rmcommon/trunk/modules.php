<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','modules');
include_once '../../include/cp_header.php';

function show_modules_list(){
	
	$module_handler =& xoops_gethandler('module');
    $installed_modules = $module_handler->getObjects();
    
    $installed_dirs = array();
    foreach($installed_modules as $mod){
		$installed_dirs[] = $mod->dirname();
		
		$modules[] = array(
			'name'			=> $mod->getVar('name'),
			'realname'		=> $mod->getInfo('name'),
			'version'		=> $mod->getInfo('rmnative') ? RMUtilities::format_version($mod->getInfo('rmversion')) : $mod->getInfo('version')
		);
		
    }
    
    require_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
    $dirlist = XoopsLists::getModulesList();
    $available_mods = array();
    foreach ($dirlist as $file) {
        clearstatcache();
        $file = trim($file);
        if (!in_array($file, $installed_dirs)) {
            $module =& $module_handler->create();
            if (!$module->loadInfo($file, false)) {
                continue;
            }
            $available_mods[] = $module;
            unset($module);
        }
    }
    
    $GLOBALS['available_mods'] = $available_mods;
	
	RMFunctions::create_toolbar();
	RMTemplate::get()->assign('xoops_pagetitle', __('Modules Management','rmcommon'));
	RMTemplate::get()->add_style('modules.css', 'rmcommon');
	RMTemplate::get()->add_script(RMCURL.'/include/js/modules.js');
	xoops_cp_header();
	include RMTemplate::get()->get_template('rmc_modules.php', 'module', 'rmcommon');
	xoops_cp_footer();
	
}

$action = rmc_server_var($_REQUEST, 'action', '');
switch($action){
	default:
		show_modules_list();
		break;
}
