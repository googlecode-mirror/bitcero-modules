<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once '../../include/cp_header.php';
define('RMCLOCATION','dashboard');

function get_modules_ajax(){
    
    XoopsLogger::getInstance()->activated = false;
    XoopsLogger::getInstance()->renderingEnabled = false;
    
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("modules");
    $page = rmc_server_var($_POST, 'page', 1);
    $limit = RMFunctions::configs('mods_number');
    list($num) = $db->fetchRow($db->query($sql));
    
    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('javascript:;" onclick="get_mods_page({PAGE_NUM})');
    
    $sql = 'SELECT * FROM ' . $db->prefix('modules')." ORDER BY mid, weight LIMIT $start,$limit";
    $result = $db->query($sql);
    $installed_mods = array();
    while($row = $db->fetchArray($result)){
        $mod = new XoopsModule();
        $mod->assignVars($row);
        $installed_mods[] = $mod;
    }
    
    include RMTemplate::get()->get_template('rmc_mods_installed.php', 'module', 'rmcommon');
    die();
    
}

function show_dashboard(){
    global $xoopsModule;
    
    RMFunctions::create_toolbar();
    
    $db = Database::getInstance();
    $sql = 'SELECT * FROM ' . $db->prefix('modules');
    $result = $db->query($sql);
    $installed_mods = array();
    while($row = $db->fetchArray($result)){
        $installed_mods[] = $row['dirname'];
    }
    
    require_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
    $dirlist = XoopsLists::getModulesList();
    $available_mods = array();
    $module_handler =& xoops_gethandler('module');
    $i = 0;
    foreach ($dirlist as $file) {
        if ($i>5) break;
        clearstatcache();
        $file = trim($file);
        if (!in_array($file, $installed_mods)) {
            $module =& $module_handler->create();
            if (!$module->loadInfo($file, false)) {
                continue;
            }
            $available_mods[] = $module;
        }
        $i++;
    }

    xoops_cp_header();

    RMTemplate::get()->add_style('dashboard.css', 'rmcommon');
    RMTemplate::get()->add_script(RMCURL.'/include/js/dashboard.js');
    RMTemplate::get()->add_style('pagenav.css', 'rmcommon');
    RMTemplate::get()->set_help('http://redmexico.com.mx/docs/common-utilities');
    include RMTemplate::get()->get_template('rmc_dashboard.php', 'module', 'rmcommon');

    xoops_cp_footer();
}


function rm_change_theme(){
    global $xoopsModule;
    
    $theme = rmc_server_var($_GET,'theme','');
    
    if (is_file(RMCPATH.'/themes/'.$theme.'/admin_gui.php')){
        $db = Database::getInstance();
        $sql = "UPDATE ".$db->prefix("config")." SET conf_value='$theme' WHERE conf_name='theme' AND conf_modid='".$xoopsModule->mid()."'";
        if ($db->queryF($sql)){
            redirectMsg('index.php', __('Theme changed successfully!','rmcommon'), 0);
            die();
        } else {
            redirectMsg('index.php', __('Theme could not be changed!','rmcommon').'<br />'.$db->error(), 0);
            die();
        }
    }
    
    redirectMsg('index.php', __('Specified theme does not exist!','rmcommon'), 1);
    die();
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'list':
        get_modules_ajax();
        die();
    case 'theme':
        rm_change_theme();
        break;
    default:
        show_dashboard();
        break;
}
