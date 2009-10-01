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
define('RMCLOCATION','imgmanager');

/**
* Show all images existing in database
*/
function show_images(){
	global $xoopsModule, $xoopsModuleConfig;
	
	$db = Database::getInstance();
	
	$catnum = RMFunctions::get_num_records("rmc_img_cats");
	if ($catnum<=0){
		redirectMsg('images.php?action=newcat', __('There are not categories yet! Please create one in order to can add images.','rmcommon'), 1);
		die();
	}
	
}

/**
* Show form to create categories
*/
function new_category(){
	define('RMSUBLOCATION','rmc_imgnewcat');
	RMFunctions::create_toolbar();
	xoops_cp_header();
	
	$form = new RMForm('','','');
	$write = new RMFormGroups('','write',true,1, 3, array(XOOPS_GROUP_ADMIN));
	$read = new RMFormGroups('','read',true,1, 3, array(0));
	
	include RMTemplate::get()->get_template('categories_form.php', 'module', 'rmcommon');
	
	xoops_cp_footer();
	
}

$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	case 'newcat':
		new_category();
		break;
	default:
		show_images();
		break;
}