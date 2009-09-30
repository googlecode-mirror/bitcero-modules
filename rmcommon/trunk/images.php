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
* This is the images manager file for RMCommon. It is based on EXM system
* and as a substitute for xoops image manager
*/

include_once '../../include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/rmcommon/admin_loader.php';

/**
* Show all images existing in database
*/
function show_images(){
	global $xoopsModule, $xoopsModuleConfig;
	
	$db = Database::getInstance();
	
	$catnum = RMFunctions::get_num_records("rmc_img_cats");
	echo $catnum; die();
	
}

$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	default:
		show_images();
		break;
}