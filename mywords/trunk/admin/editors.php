<?php
// $Id: editors.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------
// MyWords
// Manejo de Artículos
// CopyRight © 2007 - 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.net
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
// @copyright: 2007 - 2008 Red México
// @author: BitC3R0

define('MW_LOCATION','editors');
require('header.php');

/**
 * Mostramos la lista de editores junto con
 * el formulario para crear nuevos editores
 */
function showEditors(){
	global $db, $tpl, $xoopsUser, $mc, $adminTemplate;
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	$tbl1 = $db->prefix("mw_editors");
	$tbl2 = $db->prefix("users");
	
	$result = $db->query("SELECT $tbl2.*, $tbl1.fecha FROM $tbl2, $tbl1 WHERE $tbl2.uid=$tbl1.uid");
	list($posts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE autor='".$xoopsUser->uid()."'"));
	$link = XOOPS_URL.'/modules/mywords/';
	$link .= $mc['permalinks']==1 ? '?author='.$xoopsUser->uid() : ($mc['permalinks']==2 ? "author/".$xoopsUser->uname()."/" : "author/".$xoopsUser->uid());
	$editores = array();
	$tpl->append('editores', array('uid'=>$xoopsUser->uid(),'uname'=>$xoopsUser->uname(),'email'=>$xoopsUser->email(),
			'joined'=>formatTimeStamp($xoopsUser->getVar('user_regdate')),'envios'=>$posts,
			'url'=>$xoopsUser->getVar('url'),'edit'=>false,'link'=>$link));
	$editores[] = $xoopsUser->uid();
	while($row = $db->fetchArray($result)){
		if ($row['uid']==$xoopsUser->uid()) continue;
		list($posts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE autor='".$row['uid']."'"));
		$link = XOOPS_URL.'/modules/mywords/';
		$link .= $mc['permalinks']==1 ? '?author='.$row['uid'] : ($mc['permalinks']==2 ? "author/".$row['uname']."/" : "author/".$row['uid']);
		$tpl->append('editores', array('uid'=>$row['uid'],'uname'=>$row['uname'],'email'=>$row['email'],
				'joined'=>formatTimeStamp($row['user_regdate']),'envios'=>$posts,'url'=>$row['url'],
				'edit'=>true,'link'=>$link));
		$editores[] = $row['uid'];
	}
	
	$adminTemplate = "admin/mywords_editors.html";
	xoops_cp_header();
	
	
	$tpl->assign('lang_editors', _AS_MW_EDITORSLNG);
	$tpl->assign('editors_tip', _AS_MW_EDITORSTIP);
	$tpl->assign('lang_id', _AS_MW_ID);
	$tpl->assign('lang_uname', _AS_MW_UNAME);
	$tpl->assign('lang_joined', _AS_MW_JOINED);
	$tpl->assign('lang_postssend', _AS_MW_POSTSSEND);
	$tpl->assign('lang_options', _OPTIONS);
	$tpl->assign('lang_seeposts', _AS_MW_SEEPOSTS);
	$tpl->assign('lang_delete', _DELETE);
	$tpl->assign('lang_srhuser', _AS_MW_SEARCHUSR);
	$tpl->assign('lang_srhusers',_AS_MW_SEARCHUSERS);
	
	$form = new RMForm(_AS_MW_SELECTUSER,'frmUsers','editors.php');
	$form->addElement(new RMSubTitle(_AS_MW_USERDESC,'1','even'));
	$ele = new RMFormUserEXM(_AS_MW_SELECTUSER,'uid',1, $editores, 30);
	$form->addElement($ele);
	// Categorias
	$ele = new RMSelect(_AS_MW_EDCATEGOS, 'categos[]', 1);
	arrayCategos($categos);
	$ele->addOption(0, _AS_MW_ALL, 1);
	foreach ($categos as $k){
		$ele->addOption($k['id_cat'], str_repeat("-", $k['saltos']) . $k['nombre']);
	}
	$form->addElement($ele);
	
	$ele = new RMButton('sbt', _AS_MW_ADDEDITORS);
	$ele->setExtra("style='font-size: 14px; font-weight: bold; padding: 8px;'");
	$form->addElement($ele);
	$form->addElement(new RMHidden('op','add'));
	
	$tpl->assign('form_editors', $form->render());
	
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

?>