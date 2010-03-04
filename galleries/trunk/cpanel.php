<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------
// @copyright: 2008 Red México

define('GS_LOCATION','panel');
include '../../mainfile.php';
$xoopsOption['module_subpage'] = 'panel';

$toget = 's';
include("include/parse.php");

//Verificamos que sea un usuario registrado
if(!$exmUser){
	redirect_header(XOOPS_URL.'/modules/galleries/',2,_MS_GS_ERRUSER);
	die();
}

function createLinks(){
	global $tpl, $xoopsModuleConfig, $xmh, $exmUser;
	
	$mc =& $xoopsModuleConfig;
	$tpl->assign('link_bookmarks', GS_URL.'/'.($mc['urlmode'] ? "cpanel/bookmarks/" : "cpanel.php?s=cpanel/bookmarks"));
	$tpl->assign('lang_favourites',_MS_GS_FAVOURITES);
	
	$tpl->assign('user',0);
	$users = GSFunctions::getAllowedUsers();
	if(in_array($exmUser->uid(),$users)){
		$tpl->assign('link_friends', GS_URL.'/'.($mc['urlmode'] ? "cpanel/friends/" : "cpanel.php?s=cpanel/friends"));
		$tpl->assign('link_photos', GS_URL.'/'.($mc['urlmode'] ? "cpanel/" : "cpanel.php"));
		$tpl->assign('link_sets', GS_URL.'/'.($mc['urlmode'] ? "cpanel/sets/" : "cpanel.php?s=cpanel/sets"));
		$tpl->assign('lang_friends',_MS_GS_FRIENDS);
		$tpl->assign('lang_msets',_MS_GS_MSETS);
		$tpl->assign('lang_mpics',_MS_GS_MPICS);
		$tpl->assign('user',1);
	}
	
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
$cpanel = $op!='' ? $op : $cpanel;
switch($cpanel){
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
	default:
		include XOOPS_ROOT_PATH.'/modules/galleries/include/cp_images.php';
		showImages();
		break;
}
?>
