<?php
// $Id$
// --------------------------------------------------------------
// bXpress Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../mainfile.php';

$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
// Id del Post
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

if ($id<=0){
	redirect_header('./', 2, __('Please specify a post id to delete!','bxpress'));
	die();
}

$post = new bXPost($id);
if ($post->isNew()){
	redirect_header('./', 2, __('Specified post does not exists!','bxpress'));
	die();
}

$topic = new bXTopic($post->topic());
$forum = new bXForum($post->forum());

// Verificamos que el usuario tenga permiso
if (!$xoopsUser || !$forum->isAllowed($xoopsUser->getGroups(), 'delete')){
	redirect_header('topic.php?pid='.$id.'#p'.$id, 2, __('Sorry, you don\'t have permission to do this action!','bxpress'));
	die();
}

// Verificamos si el usuario tiene permiso de eliminación para el post
if ($xoopsUser->uid()!=$post->user() && (!$xoopsUser->isAdmin() && !$forum->isModerator($xoopsUser->uid()))){
	redirect_header('topic.php?pid='.$id.'#p'.$id, 2, __('Sorry, you don\'t have permission to do this action!','bxpress'));
	die();
}

if ($ok){
	
	if (!$xoopsSecurity->check()){
		redirect_header('topic.php?pid='.$id.'#p'.$id, 2, __('Session token expired!','bxpress'));
		die();
	}
	
	if ($post->id()==bXFunctions::getFirstId($topic->id())){
		$ret = $topic->delete();
		$wtopic = true;
	} else {
		$ret = $post->delete();
		$wtopic = false;
	}
	
	if ($ret){
		redirect_header($wtopic ? 'forum.php?id='.$forum->id() : 'topic.php?id='.$topic->id(), 1, $wtopic ? __('Topic deleted successfully!','bxpress') : __('Post deleted successfully!','bxpress'));
	} else {
		redirect_header('topic.php?pid='.$id, 1, ($wtopic ? __('The topic could not be deleted!','bxpress') : __('The post could not be deleted!','bxpress')).'<br />'.($wtopic ? $topic->errors() : $post->errors()));
	}
	
	
} else {
	
	include 'header.php';
	
	$myts =& MyTextSanitizer::getInstance();
	$hiddens['ok'] = 1;
	$hiddens['id'] = $id;
	$buttons['sbt']['value'] = __('Delete','bxpress');
	$buttons['sbt']['type'] = 'submit';
	$buttons['cancel']['value'] = __('Cancel','bxpress');
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
