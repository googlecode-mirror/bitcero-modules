<?php
// $Id$
// --------------------------------------------------------------
// Designia v1.0
// Theme for Common Utilities 2
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

global $xoopsUser, $xoopsSecurity;

define('DESIGNIA_PATH', RMCPATH.'/themes/designia');
define('DESIGNIA_URL', RMCURL.'/themes/designia');

if(!file_exists(XOOPS_CACHE_PATH.'/designia.css')){
    file_put_contents(XOOPS_CACHE_PATH.'/designia.css',file_get_contents(RMCPATH.'/themes/designia/css/main.css'));
}

include_once DESIGNIA_PATH.'/class/designiafunctions.class.php';

// Cookies
RMTemplate::get()->add_local_script('jquery.ck.js', 'rmcommon','include');

// Get current module menu
DesigniaFunctions::currentModuleMenu();

// System module menu
if($xoopsModule->dirname()!='system')
    $system_menu = DesigniaFunctions::moduleMenu('system');

// Common Utilities module menu
if($xoopsModule->dirname()!='rmcommon')
    $rmcommon_menu = DesigniaFunctions::moduleMenu('rmcommon');

// Other Menus
$other_menu = RMEvents::get()->run_event('designia.other.menu');

// Left Widgets
$left_widgets = array();
$left_widgets = RMEvents::get()->run_event('rmcommon.load.left.widgets', $left_widgets);

// Right widgets
$right_widgets = array();
$right_widgets = RMEvents::get()->run_event('rmcommon.load.right.widgets', $right_widgets);

include 'ajax/modules.php';

// Designia preferences
$dConfig = include(XOOPS_CACHE_PATH.'/designia.php');

// Display theme
include_once 'designia.php';