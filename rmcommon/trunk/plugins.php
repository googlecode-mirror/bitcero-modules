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
	
	$dir = RMCPATH.'/plugins';
	$file_list = XoopsLists::getDirListAsArray($dir);
	print_r($file_list); die();
	
	RMFunctions::create_toolbar();
	xoops_cp_header();
	
	include RMTemplate::get()->get_template('rmc_plugins.php', 'module', 'rmcommon');
	
	xoops_cp_footer();
	
}


$action = rmc_server_var($_REQUEST,'action','');

switch($action){
	default:
		show_rm_plugins();
		break;
}