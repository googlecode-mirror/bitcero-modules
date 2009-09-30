<?php
// Javascript Scripts
RMTemplate::get()->add_script($rm_theme_url.'/js/general.js');
RMTemplate::get()->add_script($rm_theme_url.'/js/jquery.cookie.js');

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
	$right_widgets = RMEventsApi::get()->run_event('rmcevent_load_right_widgets', $right_widgets);
}
        
// Left Widgets
$left_widgets = RMEventsApi::get()->run_event('rmcevent_load_left_widgets', $left_widgets);

RMTemplate::get()->add_style('gui.css','');
include 'theme.php';