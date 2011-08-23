<?php
// $Id: delete.php 45 2007-12-15 03:17:26Z BitC3R0 $
// --------------------------------------------------------------
// Foros EXMBB
// Módulo para el manejo de Foros en EXM
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
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
// --------------------------------------------------------------
// @copyright: 2007 - 2008 Red México

define('BB_LOCATION','delete');
include '../../mainfile.php';

$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
// Id del Post
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

if ($id<=0){
	redirect_header('./', 2, _MS_EXMBB_NOMSGID);
	die();
}

$post = new BBPost($id);
if ($post->isNew()){
	redirect_header('./', 2, _MS_EXMBB_POSTNOEXISTS);
	die();
}

$topic = new BBTopic($post->topic());
$forum = new BBForum($post->forum());

// Verificamos que el usuario tenga permiso
if (!$xoopsUser || !$forum->isAllowed($xoopsUser->getGroups(), 'delete')){
	redirect_header('topic.php?pid='.$id.'#p'.$id, 2, _MS_EXMBB_NOPERM);
	die();
}

// Verificamos si el usuario tiene permiso de eliminación para el post
if ($xoopsUser->uid()!=$post->user() && (!$xoopsUser->isAdmin() && !$forum->isModerator($xoopsUser->uid()))){
	redirect_header('topic.php?pid='.$id.'#p'.$id, 2, _MS_EXMBB_NOPERM);
	die();
}

if ($ok){
	
	$util =& RMUtils::getInstance();
	if (!$util->validateToken()){
		redirect_header('topic.php?pid='.$id.'#p'.$id, 2, _MS_EXMBB_SESSINVALID);
		die();
	}
	
	if ($post->id()==BBFunctions::getFirstId($topic->id())){
		$ret = $topic->delete();
		$wtopic = true;
	} else {
		$ret = $post->delete();
		$wtopic = false;
	}
	
	if ($ret){
		redirect_header($wtopic ? 'forum.php?id='.$forum->id() : 'topic.php?id='.$topic->id(), 1, $wtopic ? _MS_EXMBB_DELOKTOPIC : _MS_EXMBB_DELOK);
	} else {
		redirect_header('topic.php?pid='.$id, 1, ($wtopic ? _MS_EXMBB_NODELTOPIC : _MS_EXMBB_NODEL).'<br />'.($wtopic ? $topic->errors() : $post->errors()));
	}
	
	
} else {
	
	include 'header.php';
	
	$myts =& MyTextSanitizer::getInstance();
	$hiddens['ok'] = 1;
	$hiddens['id'] = $id;
	$buttons['sbt']['value'] = _DELETE;
	$buttons['sbt']['type'] = 'submit';
	$buttons['cancel']['value'] = _CANCEL;
	$buttons['cancel']['type'] = 'button';
	$buttons['cancel']['extra'] = 'onclick="window.location=\'topic.php?pid='.$id.'#p'.$id.'\';"';
	
	$text = _MS_EXMBB_DELCONF;
	if ($id==BBFunctions::getFirstId($topic->id())){
		$text .= "<br /><br /><span class='bbwarning'>"._MS_EXMBB_DELWARN."</span>";
	}
	
	$text .= "<br /><br /><strong>".$post->uname().":</strong><br />";
	$text .= substr($post->getVar('post_text','e'), 0, 100).'...';
	
	$util->msgBox($hiddens, 'delete.php', $text, XOOPS_ALERT_ICON, $buttons, true, '80%', _MS_EXMBB_DELTITLE);
	$tpl->assign('xoops_pagetitle', _MS_EXMBB_DELTITLE . ' &raquo; '. $xoopsModuleConfig['forum_title']);
	
	include 'footer.php';
	
}
?>
