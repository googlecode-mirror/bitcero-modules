<?php
// $Id: trackbacks.php 13 2009-08-31 00:45:24Z i.bitcero $
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

function showTracks(){
	global $db, $tpl, $mc, $myts, $adminTemplate, $xoopsModule;
	
	$id = isset($_GET['post']) ? $_GET['post'] : 0;
	if ($id<=0){
		redirect_header('posts.php', 2, _AS_MW_NOID);
		die();
	}
	
	$post = new MWPost($id);
	if ($post->isNew()){
		redirect_header('posts.php', 2, _AS_MW_NOID);
		die();
	}
	
	$sql = "SELECT * FROM ".$db->prefix("mw_trackbacks")." WHERE post='$id' ORDER BY fecha";
	$result = $db->query($sql);
	
	while ($row = $db->fetchArray($result)){
		$texto = $util->filterTags($row['excerpt']);
		$texto = substr($texto, 0, $mc['tracklen']);
		$texto = $myts->censorString($texto);
		$texto = $myts->displayTarea($texto, 1, 1, 1, 1, 1);
		$tpl->append('trackbacks', array('id'=>$row['id_t'],'titulo'=>$row['title'],'blogname'=>$row['blog_name'],
				'fecha'=>date($mc['date'], $row['fecha']) . " - " . date($mc['hour'], $row['fecha']),'url'=>$row['url'],
				'texto'=>$texto));
	}
	
	# Lenguaje	
	$tpl->assign('table_title',sprintf(_AS_MW_TRACKSFOR, $post->getTitle()));
	$tpl->assign('lang_id', _AS_MW_ID);
	$tpl->assign('lang_title', _AS_MW_TRACKTITLE);
	$tpl->assign('lang_blog', _AS_MW_BLOG);
	$tpl->assign('lang_date', _AS_MW_DATECOM);
	$tpl->assign('lang_url', _AS_MW_URL);
	$tpl->assign('lang_options', _OPTIONS);
	$tpl->assign('post', $id);
	$tpl->assign('lang_delete', _DELETE);
	$tpl->assign('lang_aprove', _AS_MW_APROVAR);
	$tpl->assign('lang_deletesel', _AS_MW_DELSEL);
	$tpl->assign('lang_aprovesel', _AS_MW_APROVESEL);
	
	$adminTemplate = "admin/mywords_trackbacks.html";
	optionsBar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; <a href="posts.php">'.$post->getTitle().'</a> &raquo '._AS_MW_TRACKBACKS);
	xoops_cp_header();
	xoops_cp_footer();
}
/**
 * Elimina un trackback específico
 */
function deleteTrack(){
	global $db, $util;
	
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	if (!isset($post) || $post<=0){
		redirect_header('posts.php', 2, _AS_MW_NOID);
		die();
	}
	if (!isset($track) || $track<=0){
		redirect_header('posts.php?op=trackbacks&amp;post='.$post, 2, _AS_MW_NOTRACKID);
		die();
	}	
	
	if ($ok){
		$sql = "DELETE FROM ".$db->prefix("mw_trackbacks")." WHERE post='$post' AND id_t='$track'";
		if (!$db->queryF($sql)){
			redirect_header('posts.php?op=trackbacks&amp;post='.$post, 1, _AS_MW_DBERROR . '<br />' . $db->error());
			die();
		}
		$post = new MWPost($post);
		$post->setTBCount($post->getTBCount()-1);
		if ($post->update()){
			redirect_header('posts.php?op=trackbacks&amp;post='.$post->getID(), 1, _AS_MW_DBOK);
			die();
		} else {
			redirect_header('posts.php?op=comments&amp;post='.$post->getID(), 1, _AS_MW_DBERROR . '<br />' . $post->errors());
			die();
		}
	} else {
		xoops_cp_header();
		$hiddens['acc'] = 'del';
		$hiddens['post'] = $post;
		$hiddens['ok'] = 1;
		$hiddens['track'] = $track;
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="history.go(-1);"';
		$util->msgBox($hiddens, 'posts.php?op=trackbacks', _AS_MW_CONFIRMDELTRACK, '../images/question.png', $buttons, true, 400);
		xoops_cp_footer();
	}
}
/**
 * Eliminamos múltiples trackbacks al mismo tiempo
 */
function deleteBulk(){
	
	global $db, $util;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	if (!isset($post) || $post<=0){
		redirect_header('posts.php', 2, _AS_MW_NOID);
		die();
	}
	if (!isset($tracks) || count($tracks)<=0){
		redirect_header('posts.php?op=trackbacks&amp;post='.$post, 2, _AS_MW_TRACKNOSEL);
		die();
	}
	
	if (isset($ok) && $ok){
		
		$sql = "DELETE FROM ".$db->prefix("mw_trackbacks")." WHERE post='$post' AND (";
		$dels = '';
		foreach ($tracks as $k){
			$dels .= $dels=='' ? "id_t='$k'" : " OR id_t='$k'";
		}
		$sql .= $dels . ")";
		
		if (!$db->queryF($sql)){
			redirect_header('posts.php?op=trackbacks&amp;post='.$post, 1, _AS_MW_DBERROR . '<br />' . $db->error());
			die();
		}
		
		$num = $db->getAffectedRows();
		
		$post = new MWPost($post);
		$post->setTBCount($post->getTBCount() - $num);
		
		if ($post->update()){
			redirect_header('posts.php?op=trackbacks&amp;post='.$post->getID(), 1, _AS_MW_DBOK);
			die();
		} else {
			redirect_header('posts.php?op=comments&amp;post='.$post->getID(), 1, _AS_MW_DBERROR . '<br />' . $post->errors());
			die();
		}
		
	} else {
		xoops_cp_header();
		$hiddens['acc'] = 'delbulk';
		$hiddens['post'] = $post;
		$hiddens['ok'] = 1;
		foreach ($tracks as $k){
			$hiddens['tracks[]'][] = $k;
		}
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="history.go(-1);"';
		$util->msgBox($hiddens, 'posts.php?op=trackbacks', _AS_MW_CONFIRMDELTRACKS, '../images/question.png', $buttons, true, 400);
		xoops_cp_footer();
	}
	
}

$acc = isset($_REQUEST['acc']) ? $_REQUEST['acc'] : '';

switch ($acc){
	case 'aprove':
		aproveComment();
		break;
	case 'all':
		showComments(2);
		break;
	case 'aproved':
		showComments(1);
		break;
	case 'delbulk':
		deleteBulk();
		break;
	case 'aprovebulk':
		aproveBulk();
		break;
	case 'del':
		deleteTrack();
		break;
	default:
		showTracks(0);
		break;
}

?>