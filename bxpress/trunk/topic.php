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
	redirect_header('./', 2, __('Specified topic does not exists!','bxpress'));
	die();
}

$db = XoopsDatabaseFactory::getDatabaseConnection();

if ($pid){
	$id = bXFunctions::pageFromPID($pid);
} elseif ($op=='new' && $xoopsUser){
	
	$result = $db->query('SELECT MIN(id_post) FROM '.$db->prefix('bxpress_posts')." WHERE id_topic='$id' AND post_time>".$xoopsUser->getVar('last_login'));
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
	redirect_header('./', 2, __('Specified topic is not valid!','bxpress'));
	die();
}

$topic = new bXTopic($id);
if ($topic->isNew()){
	redirect_header('./', 2, __('Specified topic does not exists!','bxpress'));
	die();
}

//Determinamos de el mensaje esta aprobado y si el usuario es administrador o moderador
$forum= new bXForum($topic->forum());
if (!$topic->approved() && (!$xoopsUser->isAdmin() || !$forum->isModerator($xoopsUser->uid()))){
	redirect_header('./', 2, __('This topic has not been approved yet!','bxpress'));
	die();
}

$forum = new bXForum($topic->forum());
if (!$forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'view')){
	redirect_header('./', 2, __('Sorry, you don\'t have permission to view this forum!','bxpress'));
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

$tpl->assign('forum', array(
    'id'=>$forum->id(),
    'title'=>$forum->name(),
    'moderator'=>$xoopsUser ? ($forum->isModerator($xoopsUser->uid())) || $xoopsUser->isAdmin(): false
));

$tpl->assign('topic', array(
    'id'=>$topic->id(),
    'title'=>$topic->title(),
    'closed'=>$topic->status(),
    'sticky'=>$topic->sticky(),
    'approved'=>$topic->approved()
));

if ($forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'reply')){
	if ($topic->status()){
		$canreply = $xoopsUser ? $forum->isModerator($xoopsUser->uid()) || $xoopsUser->isAdmin() : 0;
	} else {
		$canreply = true;
	}
    if ($forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'topic')){
        $tpl->assign('lang_newtopic', __('New Topic','bxpress'));
        $tpl->assign('can_topic', 1);
    }
	$tpl->assign('can_reply', $canreply);
	$tpl->assign('lang_reply', __('Reply','bxpress'));
	$tpl->assign('lang_approved',__('Approved','bxpress'));
	$tpl->assign('lang_noapproved',__('Not approved','bxpress'));
}

// Obtenemos los rangos para usar posteriormente
$ranks = bXFunctions::getRanks();

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
    $nav = new RMPageNav($num, $limit, $pactual);
    $nav->target_url('topic.php?id='.$topic->id().'&amp;pag={PAGE_NUM}');
    $tpl->assign('postsNavPage', $nav->render(false));
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
		$userData['rank'] = $ranks[$bbUser->getVar('rank')]['title'];
		$userData['rank_image'] = $ranks[$bbUser->getVar('rank')]['image'];
		$userData['registered'] = sprintf(__('Registered: %s','bxpress'), date($mc['dates'], $bbUser->getVar('user_regdate')));
                $userData['avatar'] = RMEvents::get()->run_event("rmcommon.get.avatar", $bbUser->getVar('email'), 0);
		$userData['posts'] = sprintf(__('Posts: %u','bxpress'), $bbUser->getVar('posts'));
		if ($xoopsUser && ($moderator || $admin)) $userData['ip'] = sprintf(__('IP: %s','bxpress'), $post->ip());
		$userData['online'] = $bbUser->isOnline();
	} else {
		$userData = array();
		$userData['id'] = 0;
		$userData['uname'] = $xoopsModuleConfig['anonymous_prefix'].$post->uname();
		$userData['rank'] = $xoopsConfig['anonymous'];
		$userData['rank_image'] = '';
		$userData['registered'] = '';
		$userData['avatar'] = RMEvents::get()->run_event("rmcommon.get.avatar", '', 0);;
		$userData['posts'] = sprintf(__('Posts: %u','bxpress'), 0);
		$userData['online'] = false;
	}
	
	// Adjuntos
	$attachs = array();
	foreach ($post->attachments() as $k){
		$attachs[] = array('title'=>$k->name(),'downs'=>$k->downloads(),'id'=>$k->id(),'ext'=>$k->extension(),
					'size'=>  RMUtilities::formatBytesSize($k->size()),'icon'=>$k->getIcon());
	}
	
	$tpl->append('posts', array(
        'id'=>$post->id(),
        'text'=>$post->text(),
        'edit'=>$post->editText(),
        'approved'=>$post->approved(),
        'date'=>bXFunctions::formatDate($post->date()),
        'canedit'=>$canedit,
        'candelete'=>$candelete,
        'canshow'=>$canshow,
        'canreport'=>$report,
        'poster'=>$userData,
        'attachs'=>$attachs,
        'attachscount'=>count($attachs)
    ));
}

unset($userData, $bbUser, $users);

$tpl->assign('lang_edit', __('Edit','bxpress'));
$tpl->assign('lang_delete', __('Delete','bxpress'));
$tpl->assign('lang_report', __('Report','bxpress'));
$tpl->assign('lang_quote', __('Quote','bxpress'));
$tpl->assign('lang_online', __('Online!','bxpress'));
$tpl->assign('lang_offline', __('Disconnected','bxpress'));
$tpl->assign('lang_attachments', __('Attachments','bxpress'));
$tpl->assign('xoops_pagetitle', $topic->title() ." &raquo; ".$xoopsModuleConfig['forum_title']);
$tpl->assign('token',$xoopsSecurity->createToken());
$tpl->assign('lang_edittext',__('Edit Text','bxpress'));
$tpl->assign('lang_goto', __('Change Forum:','bxpress'));
$tpl->assign('lang_go', __('Go!','bxpress'));
//$tpl->assign('url',XOOPS_URL."/modules/bxpress/report.php");
$tpl->assign('lang_topicclosed', __('Locked Topic','bxpress'));

bXFunctions::forumList();

if ($xoopsUser){
	if ($forum->isModerator($xoopsUser->uid()) || $xoopsUser->isAdmin()){
		$tpl->assign('lang_move', __('Move Topic','bxpress'));
		if ($topic->status()){
			$tpl->assign('lang_open', __('Unlock Topic','bxpress'));
		} else {
			$tpl->assign('lang_close', __('Lock Topic','bxpress'));
		}
		if ($topic->sticky()){
			$tpl->assign('lang_unsticky', __('Unsticky Topic','bxpress'));
		} else {
			$tpl->assign('lang_sticky', __('Sticky Topic','bxpress'));
		}
		$tpl->assign('lang_app',__('Approve','bxpress'));
		$tpl->assign('lang_noapp',__('Unnaprove','bxpress'));
	}
}

bXFunctions::loadAnnouncements(1, $forum->id());

include 'footer.php';
