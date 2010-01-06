<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','editors');
require('header.php');

include_once '../include/general.func.php';

/**
 * Mostramos la lista de editores junto con
 * el formulario para crear nuevos editores
 */
function showEditors(){
	global $tpl, $xoopsUser, $xoopsSecurity, $xoopsModule;
	
	MWFunctions::include_required_files();
    xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.__('Editors','admin_mywords'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Editors Management','admin_mywords'));
	include_once RMCPATH.'/class/form.class.php';
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	$db = Database::getInstance();
	$tbl1 = $db->prefix("mw_editors");
	$tbl2 = $db->prefix("users");
	
	$result = $db->query("SELECT $tbl2.*, $tbl1.fecha FROM $tbl2, $tbl1 WHERE $tbl2.uid=$tbl1.uid");
	list($posts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE autor='".$xoopsUser->uid()."'"));
	$link = XOOPS_URL.'/modules/mywords/';
	$link .= $mc['permalinks']==1 ? '?author='.$xoopsUser->uid() : ($mc['permalinks']==2 ? "author/".$xoopsUser->uname()."/" : "author/".$xoopsUser->uid());
	$editores = array();
	$tpl->append('editors', array('id'=>$xoopsUser->uid(),'uname'=>$xoopsUser->uname(),'email'=>$xoopsUser->email(),
			'joined'=>formatTimeStamp($xoopsUser->getVar('user_regdate')),'envios'=>$posts,
			'url'=>$xoopsUser->getVar('url'),'edit'=>false,'link'=>$link));
			
	$editores[] = $xoopsUser->uid();
	while($row = $db->fetchArray($result)){
		if ($row['uid']==$xoopsUser->uid()) continue;
		list($posts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE autor='".$row['uid']."'"));
		$link = XOOPS_URL.'/modules/mywords/';
		$link .= $mc['permalinks']==1 ? '?author='.$row['uid'] : ($mc['permalinks']==2 ? "author/".$row['uname']."/" : "author/".$row['uid']);
		$tpl->append('editors', array('id'=>$row['uid'],'uname'=>$row['uname'],'email'=>$row['email'],
				'joined'=>formatTimeStamp($row['user_regdate']),'envios'=>$posts,'url'=>$row['url'],
				'edit'=>true,'link'=>$link));
		$editores[] = $row['uid'];
	}
	
	xoops_cp_header();
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	include RMTemplate::get()->get_template('admin/mywords_editors.php','module','mywords');
	
	xoops_cp_footer();
	
}
/**
 * Agregamos nuevos editores a la base de datos
 */
function addEditors(){
	
	global $db, $util;
	$categos = array(0);
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!isset($uid) || !is_array($uid) || empty($uid)){
		redirectMsg('editors.php?op='.$opaction.'&amp;keyword='.$keyword.'&amp;page='.$actual_page, _AS_MW_ERRSELECT, 1);
		die();
	}
	
	$cats = '';
	foreach ($categos as $k){
		$cats .= $cats=='' ? $k : ",$k";
	}
	
	$sql = "INSERT IGNORE INTO ".$db->prefix("mw_editors")." (`uid`,`fecha`,`categos`) VALUES ";
	$add = '';
	foreach ($uid as $k){
		$add .= $add=='' ? "('$k','".time()."','$cats')" : ", ('$k', '".time()."','$cats')";
	}
	
	if ($db->query($sql . $add)){
		redirectMsg('editors.php?page='.$actual_page, _AS_MW_DBOK, 0);
		die();
	} else {
		redirectMsg('editors.php?op='.$opaction.'&amp;keyword='.$keyword.'&amp;page='.$actual_page, _AS_MW_DBERROR . "<br />" . $db->error(), 1);
		die();
	}
	
}
/**
 * Elimina un editor de la base de datos
 */
function deleteEditor(){
	global $db, $util;
	
	$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	
	if ($uid<=0){
		redirect_header('editors.php', 2, _AS_MW_ERRUID);
		die();
	}
	
	if ($ok){
		
		if (!$util->validateToken()){
			redirectMsg('editors.php', _AS_MW_ERRTOKEN, 1);
			die();
		}
		
		if ($db->queryF("DELETE FROM ".$db->prefix("mw_editors")." WHERE uid='$uid'")){
			redirectMsg('editors.php', _AS_MW_DBOK, 0);
			die();
		} else {
			redirectMsg('editors.php', _AS_MW_DBERROR . "<br />" . $db->error(), 1);
			die();
		}
		
	} else {
		xoops_cp_header();
		$hiddens['ok'] = 1;
		$hiddens['uid'] = $uid;
		$hiddens['op'] = 'del';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['sbt']['type'] = 'submit';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['extra'] = 'onclick="history.go(-1);"';
		$util->msgBox($hiddens, 'editors.php', _AS_MW_CONFIRMDEL, '../images/question.png', $buttons, true, '400px');
		xoops_cp_footer();
	}
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'add':
		addEditors();
		break;
	case 'del':
		deleteEditor();
		break;
	default:
		showEditors();
		break;
}
