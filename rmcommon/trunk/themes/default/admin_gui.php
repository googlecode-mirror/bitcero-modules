<?php
// Javascript Scripts
//RMTemplate::get()->add_script($rm_theme_url.'/js/general.js');
RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.ck.js');

// Widgets (this theme support admin widgets)
global $xoopsUser;
$wcounter = 0;
include_once 'widgets/mods_and_settings.php';

// Next are events to load widgets from modules or plugins
// Themes that support widgets must call these events
// Right Widgets
$fct = rmc_server_var($_GET, 'fct', '');
if ($fct!='blocksadmin'){
	$right_widgets = array();
	$right_widgets = RMEvents::get()->run_event('rmcommon.load.right.widgets', $right_widgets);
}
        
// Left Widgets
$left_widgets = RMEvents::get()->run_event('rmcommon.load.left.widgets', $left_widgets);

RMTemplate::get()->add_style('gui.css','');

if($xoopsModule->dirname()=='system'){
    RMTemplate::get()->add_style('admin.css','system');
    RMTemplate::get()->add_head('<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/xoops.css" />');
}

include 'theme.php';