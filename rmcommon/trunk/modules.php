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
	
    $installed_modules = array();
    
    $limit = rmc_server_var($_SESSION, 'mods_limit', 4);
    
    include_once XOOPS_ROOT_PATH.'/kernel/module.php';
    
    $db = Database::getInstance();

    $sql = "SELECT * FROM ".$db->prefix("modules")." ORDER BY `name`";
    $result = $db->query($sql);
    $installed_dirs = array();
    
    while($row = $db->fetchArray($result)){
        $mod = new XoopsModule();
        $mod->assignVars($row);
        $installed_dirs[] = $mod->dirname();
        
        if (file_exists(XOOPS_ROOT_PATH.'/modules/'.$mod->getVar('dirname').'/class/'.strtolower($mod->getVar('dirname').'controller').'.php')){
            include_once XOOPS_ROOT_PATH.'/modules/'.$mod->getVar('dirname').'/class/'.strtolower($mod->getVar('dirname').'controller').'.php';
            $class = ucfirst($mod->getVar('dirname')).'Controller';
            $class = new $class();
            if (method_exists($class, 'get_main_link')){
                $main_link = $class->get_main_link();
            } else {
                
            if ($mod->getVar('hasmain')){
                $main_link = XOOPS_URL.'/modules/'.$mod->dirname();
            } else {
                $main_link = "#";
            }
                
            }
        } else {
            
            if ($mod->getVar('hasmain')){
                $main_link = XOOPS_URL.'/modules/'.$mod->dirname();
            } else {
                $main_link = "#";
            }
            
        }
        
        // Admin section
        $admin_link = $mod->getVar('hasadmin') ? XOOPS_URL.'/modules/'.$mod->dirname().'/'.$mod->getInfo('adminindex') : '';
        
        $modules[] = array(
            'id'            => $mod->getVar('mid'),
            'name'            => $mod->getVar('name'),
            'realname'        => $mod->getInfo('name'),
            'version'        => $mod->getInfo('rmnative') ? RMUtilities::format_version($mod->getInfo('rmversion')) : $mod->getInfo('version'),
            'description'    => $mod->getInfo('description'),
            'image'            => XOOPS_URL.'/modules/'.$mod->getVar('dirname').'/'.$mod->getInfo('image'),
            'link'            => $main_link,
            'admin_link'    => $admin_link,
            'updated'        => formatTimestamp($mod->getVar('last_update'), 's'),
            'author'        => $mod->getInfo('author'),
            'author_mail'    => $mod->getInfo('authormail'),
            'author_web'    => $mod->getInfo('authorweb'),
            'author_url'    => $mod->getInfo('authorurl'),
            'license'        => $mod->getInfo('license')
        );
    }
    
    require_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
    $dirlist = XoopsLists::getModulesList();
    $available_mods = array();
    $module_handler = xoops_gethandler('module');
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

/**
* This function show a resume of the changes that will be made by module
*/
function module_install(){
    global $xoopsSecurity;
    
    $dir = rmc_server_var($_GET,'dir','');
    
    if ($dir=='' || !file_exists(XOOPS_ROOT_PATH.'/modules/'.$dir.'/xoops_version.php')){
        redirectMsg('modules.php', __('Specified module is not valid!','rmcommon'), 1);
        die();
    }
    
    $module_handler = xoops_gethandler('module');
	
	if ($module = $module_handler->getByDirname($dir)){
		redirectMsg('modules.php', sprintf(__('%s is already installed!', 'rmcommon'), $module->name()), 1);
		die();
	}
    
    $module =& $module_handler->create();
    if (!$module->loadInfo($dir, false)) {
        redirectMsg('modules.php',__('Sepecified module is not a valid Xoops Module!','rmcommon'), 1);
        die();
    }
    
    RMTEmplate::get()->add_script('include/js/modules.js');
    RMTemplate::get()->add_style('modules.css', 'rmcommon');
    RMFunctions::create_toolbar();
    xoops_cp_header();
    
    include RMTemplate::get()->get_template('rmc_mod_preinstall.php', 'module', 'rmcommon');
    
    xoops_cp_footer();
    
}

function module_install_now(){
	global $xoopsSecurity, $xoopsConfig;
	
	$mod = rmc_server_var($_POST, 'module', '');
	
	if (!$xoopsSecurity->check()){
		redirectMsg('modules.php?action=install&dir='.$module, __('Sorry, this operation could not be completed!', 'rmcommon'), 1);
		die();
	}
	
	$module_handler = xoops_gethandler('module');
	
	if ($module = $module_handler->getByDirname($mod)){
		redirectMsg('modules.php', sprintf(__('%s is already installed!', 'rmcommon'), $module->name()), 1);
		die();
	}
	
	$file = XOOPS_ROOT_PATH.'/modules/system/language/'.$xoopsConfig['language'].'/admin/modulesadmin.php';
	if (file_exists($file)){
		include_once $file;
	} else {
		include_once str_replace($xoopsConfig['language'], 'english', $file);
	}
	
	
	include_once XOOPS_ROOT_PATH.'/modules/system/admin/modulesadmin/modulesadmin.php';
	$ret = xoops_module_install($mod);
	
	RMFunctions::create_toolbar();
	xoops_cp_header();
	
	echo $ret;
	
	xoops_cp_footer();
	
}

$action = rmc_server_var($_REQUEST, 'action', '');
switch($action){
    case 'install':
        module_install();
        break;
    case 'install_now':
    	module_install_now();
    	break;
	default:
		show_modules_list();
		break;
}
