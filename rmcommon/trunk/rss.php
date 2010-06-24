<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Show all RSS options
*/
function show_rss_options(){
	global $xoopsTpl, $xoopsConfig;
	
	include XOOPS_ROOT_PATH.'/header.php';
	$xoopsTpl->assign('xoops_pagetitle', __('RSS Center','rmcommon'));
	
	$feeds = array();
	$feeds = RMEvents::get()->run_event('rmcommon.get.feeds.list', $feeds);
	
	RMTemplate::get()->add_style('rss.css', 'rmcommon');
	include RMTemplate::get()->get_template('rmc_rss_center.php', 'module', 'rmcommon');
	
	include XOOPS_ROOT_PATH.'/footer.php';
	
}

function show_rss_content(){
	$GLOBALS['xoopsLogger']->activated = false;
	if (function_exists('mb_http_output')) {
	    mb_http_output('pass');
	}
	header('Content-Type:text/xml; charset=utf-8');
}

$action = rmc_server_var($_GET, 'action', '');

switch($action){
	default:
		show_rss_options();
		break;
}