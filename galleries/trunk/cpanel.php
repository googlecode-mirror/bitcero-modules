<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('GS_LOCATION','panel');
$xoopsOption['module_subpage'] = 'panel';

$toget = 's';
include("include/parse.php");

//Verificamos que sea un usuario registrado
if(!$xoopsUser){
	redirect_header(GSFunctions::get_url(),2, __('You don\'t have authorization to view this section','galleries'));
	die();
}

function createLinks(){
	global $tpl, $xoopsModuleConfig, $xmh, $xoopsUser;
	
	$mc =& $xoopsModuleConfig;
	$tpl->assign('link_bookmarks', GSFunctions::get_url().($mc['urlmode'] ? "cp/bookmarks/" : "cp.php?s=cp/bookmarks"));
	$tpl->assign('lang_favourites',__('Favorites','galleries'));
	
	$tpl->assign('user',0);
	$users = GSFunctions::getAllowedUsers();
	if(in_array($xoopsUser->uid(),$users)){
		$tpl->assign('link_friends', GSFunctions::get_url().($mc['urlmode'] ? "cp/friends/" : "cp.php?s=cp/friends"));
		$tpl->assign('link_photos', GSFunctions::get_url().($mc['urlmode'] ? "cp/images/" : "cp.php"));
		$tpl->assign('link_sets', GSFunctions::get_url().($mc['urlmode'] ? "cp/sets/" : "cp.php?s=cp/sets"));
		$tpl->assign('lang_friends',__('Friends','galleries'));
		$tpl->assign('lang_msets',__('My Albums','galleries'));
		$tpl->assign('lang_mpics',__('My Images','galleries'));
		$tpl->assign('user',1);
	}
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
$cp = $op!='' ? $op : $cp;
switch($cp){
	case 'new':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_images.php';
		formImages();
		break;
	case 'edit':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_images.php';
		formImages(1);
		break;
	case 'save':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_images.php';
		saveImages();
		break;
	case 'saveedit':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_images.php';
		saveImages(1);
		break;
	case 'delete':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_images.php';
		deleteImages();
		break;
	case 'saveAll':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_images.php';
		saveAll();
		break;
	case 'toset':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_images.php';
		formSets();
		break;	
	case 'savesets':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_images.php';
		saveSets();
		break;
	case 'sets':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_sets.php';
		showSets();
		break;
	case 'editset':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_sets.php';
		showSets(1);
		break;
	case 'saveeditset':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_sets.php';
		saveSets(1);		
		break;
	case 'saveset':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_sets.php';
		saveSets();		
		break;
	case 'deleteset':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_sets.php';
		deleteSets(1);		
		break;
	case 'bookmarks':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_bookmarks.php';
		if (isset($add)) addBookMarks();		
		showBookMarks(1);	
		break;
	case 'delbookmarks':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_bookmarks.php';
		deleteBookMarks();	
		break;
	case 'friends':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_friends.php';
		if (isset($add)) addFriends();
		showFriends(1);
		break;
	case 'add':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_friends.php';
		addFriends();
		break;
	case 'deletefriend':
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_friends.php';
		deleteFriends();
		break;
    case 'images':
	default:
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_images.php';
		showImages();
		break;
}
