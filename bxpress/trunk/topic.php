<?php
// $Id$
// --------------------------------------------------------------
// bXpress Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('BB_LOCATION','topics');
include '../../mainfile.php';

$myts =& MyTextSanitizer::getInstance();

$id = rmc_server_var($_GET, 'id', '');
$pid = rmc_server_var($_GET, 'pid', 0);
$op = rmc_server_var($_GET, 'op', '');

if ($id=='' && $pid<=0){
	redirect_header('./', 2, _MS_EXMBB_ERRID);
	die();
}


if ($pid){
	$id = bXFunctions::pageFromPID($pid);
} elseif ($op=='new' && $xoopsUser){
	
	$result = $db->query('SELECT MIN(id_post) FROM '.$db->prefix('bxpress_posts')." WHERE id_topic='$id' AND post_time>".$xoopsUser->last_login());
	list($newid) = $db->fetchRow($result);

	if ($newid)
		header('Location: topic.php?pid='.$newid.'#p'.$newid);
	else	// If there is no new post, we go to the last post
		header('Location: topic.php?id='.$id.'&op=last');

	die();
	
} elseif ($op=='last'){
	
	$result = $db->query('SELECT MAX(id_post) FROM '.$db->prefix('bxpress_posts')." WHERE id_topic='$id'");
	list($lastid) = $db->fetchRow($result);

	if ($lastid)
	{
		header('Location: topic.php?pid='.$lastid.'#p'.$lastid);
		exit;
	}
	
}

if ($id==''){
	redirect_header('./', 2, _MS_EXMBB_ERRID);
	die();
}

$topic = new bXTopic($id);
if ($topic->isNew()){
	redirect_header('./', 2, _MS_EXMBB_ERREXISTS);
	die();
}

//Determinamos de el mensaje esta aprobado y si el usuario es administrador o moderador
$forum= new bXForum($topic->forum());
if (!$topic->approved() && (!$xoopsUser->isAdmin() || !$forum->isModerator($xoopsUser->uid()))){
	redirect_header('./', 2, _MS_EXMBB_TOPICNOAPPROVED);
	die();
}

$forum = new bXForum($topic->forum());
if (!$forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'view')){
	redirect_header('./', 2, _MS_EXMBB_NOALLOWED);
	die();
}

if (!isset($_SESSION['topics_viewed'])){
	$topic->addView();
	$topic->save();
	$_SESSION['topics_viewed'] = array();
	$_SESSION['topics_viewed'][] = $topic->id();
} else {
	if (!in_array($topic->id(), $_SESSION['topics_viewed'])){
		$topic->addView();
		$topic->save();
		$_SESSION['topics_viewed'][] = $topic->id();
	}
}

$xoopsOption['template_main'] = "bxpress_topic.html";
$xoopsOption['module_subpage'] = "topics";
include 'header.php';

bXFunctions::makeHeader();
$tpl->assign('forum', array('id'=>$forum->id(),'title'=>$forum->name(),'moderator'=>$xoopsUser ? ($forum->isModerator($xoopsUser->uid())) || $xoopsUser->isAdmin(): false));
$tpl->assign('topic', array('id'=>$topic->id(), 'title'=>$topic->title(),'closed'=>$topic->status(),'sticky'=>$topic->sticky(),'approved'=>$topic->approved()));
if ($forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'reply')){
	if ($topic->status()){
		$canreply = $xoopsUser ? $forum->isModerator($xoopsUser->uid()) || $xoopsUser->isAdmin() : 0;
	} else {
		$canreply = true;
	}
    if ($forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'topic')){
        $tpl->assign('lang_newtopic', _MS_EXMBB_NEWTOPIC);
        $tpl->assign('can_topic', 1);
    }
	$tpl->assign('can_reply', $canreply);
	$tpl->assign('lang_reply', _MS_EXMBB_REPLY);
	$tpl->assign('lang_approved',_MS_EXMBB_APPROVED);
	$tpl->assign('lang_noapproved',_MS_EXMBB_NOAPPROVED);
}

// Obtenemos los rangos para usar posteriormente
//$ranks = getRanks();

// Obtenemos los mensajes
$sql = "SELECT COUNT(*) FROM ".$db->prefix("bxpress_posts")." a, ".$db->prefix("bxpress_posts_text")." b WHERE 
		a.id_topic='".$topic->id()."' AND b.post_id=a.id_post";
list($num) = $db->fetchRow($db->query($sql));
$page = isset($_GET['pag']) ? $_GET['pag'] : '';
$limit = $mc['perpage'];
$limit = $limit<=0 ? 15 : $limit;
$page = isset($page) ? $page : 0;
if ($page > 0){ $page -= 1; }
$start = $page * $limit;
$tpages = ceil($num / $limit);
$pactual = $page + 1;
if ($pactual>$tpages){
	$rest = $pactual - $tpages;
    $pactual = $pactual - $rest + 1;
    $start = ($pactual - 1) * $limit;
}

if ($tpages > 1) {
	$nav = new XoopsPageNav($num, $limit, $start, 'pag', 'id='.$topic->id(), 1);
    $tpl->assign('postsNavPage', $nav->renderNav(4));
}

$groups = $xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

// Permisos
$edit = $forum->isAllowed($groups, 'edit');
$delete = $forum->isAllowed($groups, 'delete');
$report = $forum->isAllowed($groups, 'reply');
$moderator = $xoopsUser ? $forum->isModerator($xoopsUser->uid()) : false;
$admin = $xoopsUser ? $xoopsUser->isAdmin() : false;

$tbl1 = $db->prefix("bxpress_posts");
$tbl2 = $db->prefix("bxpress_posts_text");

$result = $db->query("SELECT $tbl1.*,$tbl2.* FROM $tbl1,$tbl2 WHERE $tbl1.id_topic='".$topic->id()."' AND $tbl2.post_id=$tbl1.id_post ORDER BY $tbl1.post_time ASC LIMIT $start,$limit");
$users = array();
while ($row = $db->fetchArray($result)){
	$post = new bXPost();
	$post->assignVars($row);
	// Permisos de edición y eliminación
	$canedit = $moderator || $admin ? true : $edit && $post->isOwner();
	$candelete = $moderator || $admin ? true : $delete && $post->isOwner();
	//Permiso de visualizar mensaje
	$canshow = $moderator || $admin ? true : false;
		
	// Datos del usuario
	if ($post->user()>0){
		if (!isset($users[$post->user()])){
			$users[$post->user()] = new XoopsUser($post->user());
		}
		$bbUser = $users[$post->user()];
		$userData = array();
		$userData['id'] = $bbUser->uid();
		$userData['uname'] = $bbUser->uname();
		$userData['rank'] = $ranks[$bbUser->getVar('rank')]['rank_title'];
		$userData['rank_image'] = $ranks[$bbUser->getVar('rank')]['rank_image'];
		$userData['registered'] = sprintf(_MS_EXMBB_REGISTERED, date($mc['dates'], $bbUser->getVar('user_regdate')));
		$userData['avatar'] = $bbUser->getVar('user_avatar');
		$userData['posts'] = sprintf(_MS_EXMBB_UPOSTS, $bbUser->getVar('posts'));
		if ($xoopsUser && ($moderator || $admin)) $userData['ip'] = sprintf(_MS_EXMBB_UIP, $post->ip());
		$userData['online'] = $bbUser->isOnline();
	} else {
		$userData = array();
		$userData['id'] = 0;
		$userData['uname'] = $xoopsModuleConfig['anonymous_prefix'].$post->uname();
		$userData['rank'] = $xoopsConfig['anonymous'];
		$userData['rank_image'] = '';
		$userData['registered'] = '';
		$userData['avatar'] = '';
		$userData['posts'] = sprintf(_MS_EXMBB_UPOSTS, 0);
		$userData['online'] = false;
	}
	
	// Adjuntos
	$attachs = array();
	foreach ($post->attachments() as $k){
		$attachs[] = array('title'=>$k->name(),'downs'=>$k->downloads(),'id'=>$k->id(),'ext'=>$k->extension(),
					'size'=>  RMUtilities::formatBytesSize($k->size()),'icon'=>$k->getIcon());
	}
	
	$tpl->append('posts', array('id'=>$post->id(),'text'=>$post->text(),'edit'=>$post->editText(),'approved'=>$post->approved(),
			'date'=>bXFunctions::formatDate($post->date()),'canedit'=>$canedit, 'candelete'=>$candelete,'canshow'=>$canshow,
			'canreport'=>$report,'poster'=>$userData,'attachs'=>$attachs, 'attachscount'=>count($attachs)));
}

unset($userData, $bbUser, $users);

$tpl->assign('lang_edit', _EDIT);
$tpl->assign('lang_delete', _DELETE);
$tpl->assign('lang_report', _MS_EXMBB_REPORT);
$tpl->assign('lang_quote', _MS_EXMBB_QUOTE);
$tpl->assign('lang_online', _MS_EXMBB_UONLINE);
$tpl->assign('lang_offline', _MS_EXMBB_UOFFLINE);
$tpl->assign('lang_attachments', _MS_EXMBB_ATTACHMENTS);
$tpl->assign('xoops_pagetitle', $topic->title() ." &raquo; ".$xoopsModuleConfig['forum_title']);
$tpl->assign('token',$xoopsSecurity->createToken());
$tpl->assign('lang_edittext',_MS_EXMBB_EDIT);
$tpl->assign('lang_goto', _MS_EXMBB_JUMPO);
$tpl->assign('lang_go', _MS_EXMBB_GO);
$tpl->assign('url',XOOPS_URL."/modules/bxpress/report.php");
$tpl->assign('lang_topicclosed', _MS_EXMBB_TOPICCLOSED);

bXFunctions::forumList();

if ($xoopsUser){
	if ($forum->isModerator($xoopsUser->uid()) || $xoopsUser->isAdmin()){
		$tpl->assign('lang_move', _MS_EXMBB_MOVE);
		if ($topic->status()){
			$tpl->assign('lang_open', _MS_EXMBB_OPEN);
		} else {
			$tpl->assign('lang_close', _MS_EXMBB_CLOSE);
		}
		if ($topic->sticky()){
			$tpl->assign('lang_unsticky', _MS_EXMBB_UNSTICKY);
		} else {
			$tpl->assign('lang_sticky', _MS_EXMBB_STICKY);
		}
		$tpl->assign('lang_app',_MS_EXMBB_APP);
		$tpl->assign('lang_noapp',_MS_EXMBB_NOAPP);
	}
}

include 'footer.php';
?>
