<?php
// $Id$
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
// @author: BitC3R0
// @copyright: 2007 - 2008 Red México

define('BB_LOCATION','forum');
include '../../mainfile.php';
$xoopsOption['template_main'] = "exmbb_forum.html";
$xoopsOption['module_subpage'] = "forums";
include 'header.php';
$myts =& MyTextSanitizer::getInstance();

$id = isset($_GET['id']) ? $myts->addSlashes($_GET['id']) : '';
if ($id==''){
    redirect_header(BB_URL, 2, _MS_EXMBB_NOID);
    die();
}

$forum = new BBForum($id);
if ($forum->isNew()){
    redirect_header(BB_URL, 2, _MS_EXMBB_NOEXISTS);
    die();
}

/**
* Comprobamos que el usuario actual tenga permisos
* de acceso al foro
*/
if (!$forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : array(0, XOOPS_GROUP_ANONYMOUS), EXMBB_PERM_VIEW) && !$xoopsUser->isAdmin()){
    redirect_header(BB_URL, 2, _MS_EXMBB_NOALLOWED);
    die();
}

/**
* Cargamos los temas
*/
$tbl1 = $db->prefix("exmbb_topics");
$tbl2 = $db->prefix("exmbb_forumtopics");

$sql = "SELECT COUNT(*) FROM $tbl1 WHERE id_forum='".$forum->id()."' AND approved='1'";
list($num)=$db->fetchRow($db->queryF($sql));
    
$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
$limit = $xoopsModuleConfig['topicperpage'] > 0 ? $xoopsModuleConfig['topicperpage'] : 15;
if ($page > 0){ $page -= 1; }
        
$start = $page * $limit;
$tpages = (int)($num / $limit);
if($num % $limit > 0) $tpages++;
    
$pactual = $page + 1;
if ($pactual>$tpages){
    $rest = $pactual - $tpages;
    $pactual = $pactual - $rest + 1;
    $start = ($pactual - 1) * $limit;
}
    
if ($tpages > 0) {
    $nav = new XoopsPageNav($num, $limit, $start, 'pag', 'id='.$forum->id(), 0);
    $tpl->assign('itemsNavPage', $nav->renderNav(4, 1));
}

$sql = str_replace("COUNT(*)", '*', $sql);
$sql .= " ORDER BY sticky DESC,";
$sql .=$xoopsModuleConfig['order_post'] ? " last_post " : " date ";
$sql .=" DESC LIMIT $start,$limit";
$result = $db->query($sql);

while ($row = $db->fetchArray($result)){
    $topic = new BBTopic();
    $topic->assignVars($row);
    $last = new BBPost($topic->lastPost());
    $lastpost = array();
    if (!$last->isNew()){
    	$lastpost['date'] = BBFunctions::formatDate($last->date());
    	$lastpost['by'] = sprintf(_MS_EXMBB_BY, $last->uname());
    	$lastpost['id'] = $last->id();
    	if ($xoopsUser){
    		$lastpost['new'] = $last->date()>$xoopsUser->last_login() && (time()-$last->date()) < $xoopsModuleConfig['time_new'];
    	} else {
    		$lastpost['new'] = (time()-$last->date())<=$xoopsModuleConfig['time_new'];
		}
	}
	$tpages = ceil($topic->replies()/$xoopsModuleConfig['perpage']);
	if ($tpages>1){
		$pages = BBFunctions::paginateIndex($tpages);
	} else {
		$pages = null;
	}
    $tpl->append('topics', array('id'=>$topic->id(), 'title'=>$topic->title(),'replies'=>$topic->replies(),
    			'views'=>$topic->views(),'by'=>sprintf(_MS_EXMBB_BY, $topic->posterName()),
    			'last'=>$lastpost,'popular'=>($topic->replies()>=$forum->hotThreshold()),
    			'sticky'=>$topic->sticky(),'pages'=>$pages, 'tpages'=>$tpages,'closed'=>$topic->status()));
}

// Datos del Foro
$tpl->assign('forum', array('id'=>$forum->id(), 'title'=>$forum->name(),'moderator'=>$xoopsUser ? $forum->isModerator($xoopsUser->uid()) || $xoopsUser->isAdmin() : false));

$tpl->assign('lang_pages', _MS_EXMBB_PAGES);
$tpl->assign('lang_topic', _MS_EXMBB_TOPIC);
$tpl->assign('lang_replies', _MS_EXMBB_REPLIES);
$tpl->assign('lang_views', _MS_EXMBB_VIEWS);
$tpl->assign('lang_lastpost', _MS_EXMBB_LASTPOST);
$tpl->assign('lang_nonew', _MS_EXMBB_NONEW);
$tpl->assign('lang_withnew', _MS_EXMBB_WITHNEW);
$tpl->assign('lang_hotnonew', _MS_EXMBB_HOTNONEW);
$tpl->assign('lang_hotnew', _MS_EXMBB_HOTNEW);
$tpl->assign('lang_sticky', _MS_EXMBB_STICKY);
$tpl->assign('lang_closed', _MS_EXMBB_CLOSED);
if ($forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'topic')){
	$tpl->assign('lang_newtopic', _MS_EXMBB_NEWTOPIC);
	$tpl->assign('can_topic', 1);
}
$tpl->assign('lang_newposts', _MS_EXMBB_NEWPOSTS);

BBFunctions::makeHeader();
$tpl->assign('xoops_pagetitle', $forum->name().' &raquo; '.$xoopsModuleConfig['forum_title']);
if ($xoopsUser){
	if ($forum->isModerator($xoopsUser->uid()) || $xoopsUser->isAdmin()){
		$tpl->assign('lang_moderate', _MS_EXMBB_MODERATE);
	}
}
$tpl->assign('lang_goto', _MS_EXMBB_JUMPO);
$tpl->assign('lang_go', _MS_EXMBB_GO);

BBFunctions::forumList();
BBFunctions::loadAnnouncements(1, $forum->id());

include 'footer.php';
?>
