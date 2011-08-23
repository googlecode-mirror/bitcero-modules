<?php
// $Id: moderate.php 76 2009-02-15 10:52:06Z BitC3R0 $
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

define('BB_LOCATION','moderate');
include '../../mainfile.php';

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
if ($id<=0){
	redirect_header('./', 2, _MS_EXMBB_ERRID);
	die();
}
	
$forum = new BBForum($id);
if ($forum->isNew()){
	redirect_header('./', 2, _MS_EXMBB_ERREXISTS);
	die();
}
	
// Comprobamos los permisos de moderador
if (!$xoopsUser || (!$forum->isModerator($xoopsUser->uid()) && !$xoopsUser->isAdmin())){
	redirect_header('forum.php?id='.$id, 2, _MS_EXMBB_NOPERM);
	die();
}

/**
* @desc Muestra todas las opciones configurables
*/
function showItemsAndOptions(){
	global $xoopsUser, $db, $xoopsOption, $tpl, $xoopsModule, $xoopsConfig, $util;
	global $xoopsModuleConfig, $forum;
	
	$xoopsOption['template_main'] = "exmbb_moderate.html";
	$xoopsOption['module_subpage'] = "moderate";
	include 'header.php';
	
	/**
	* Cargamos los temas
	*/
	$tbl1 = $db->prefix("exmbb_topics");
	$tbl2 = $db->prefix("exmbb_forumtopics");

	$sql = "SELECT COUNT(*) FROM $tbl1 WHERE id_forum='".$forum->id()."' ";
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
	$sql .= " ORDER BY sticky DESC, date DESC LIMIT $start,$limit";
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
    				'sticky'=>$topic->sticky(),'pages'=>$pages, 'tpages'=>$tpages,
				'approved'=>$topic->approved(),'closed'=>$topic->status()));
	}
	
	$tpl->assign('forum', array('id'=>$forum->id(), 'title'=>$forum->name()));
	$tpl->assign('lang_topic', _MS_EXMBB_TOPIC);
	$tpl->assign('lang_replies', _MS_EXMBB_REPLIES);
	$tpl->assign('lang_views', _MS_EXMBB_VIEWS);
	$tpl->assign('lang_lastpost', _MS_EXMBB_LASTPOST);
	$tpl->assign('lang_sticky', _MS_EXMBB_STICKY);
	$tpl->assign('lang_moderating', _MX_EXMBB_MODERATING);
	$tpl->assign('lang_pages', _MS_EXMBB_PAGES);
	$tpl->assign('lang_move', _MS_EXMBB_MOVE);
	$tpl->assign('lang_open', _MS_EXMBB_OPEN);
	$tpl->assign('lang_close', _MS_EXMBB_CLOSE);
	$tpl->assign('lang_dosticky', _MS_EXMBB_DOSTICKY);
	$tpl->assign('lang_dounsticky', _MS_EXMBB_DOUNSTICKY);
	$tpl->assign('lang_approved',_MS_EXMBB_APPROVED);
	$tpl->assign('lang_app',_MS_EXMBB_APP);
	$tpl->assign('lang_noapp',_MS_EXMBB_NOAPP);
	$tpl->assign('lang_delete', _DELETE);
	$tpl->assign('token_input',$util->getTokenHTML());
	
	BBFunctions::makeHeader();
	
	include 'footer.php';
	
}

/**
* @desc Mover temas de un foro a otro
*/
function moveTopics(){
	
	global $db, $xoopsModuleConfig, $util, $forum, $xoopsUser, $xoopsOption;
	
	$topics = isset($_REQUEST['topics']) ? $_REQUEST['topics'] : null;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	
	if (empty($topics) || (is_array($topics) && empty($topics))){
		redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_SELTOPIC);
		die();
	}

	$topics = !is_array($topics) ? array($topics) : $topics;
	
	if ($ok){
		
		$util =& RMUtils::getInstance();
		
		if (!$util->validateToken()){
			redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_SESSINVALID);
			die();
		}
		
		$moveforum = isset($_POST['moveforum']) ? intval($_POST['moveforum']) : 0;
		if ($moveforum<=0){
			redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_NOSELMOVEFOR);
			die();
		}
		
		$mf = new BBForum($moveforum);
		if ($mf->isNew()){
			redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_ERREXISTS);
			die();
		}
	
		$lastpost = false;
		
		foreach ($topics as $k){
			$topic = new BBTopic($k);
			if ($topic->forum()!=$forum->id()) continue;

			
			//Verificamos si el tema contiene el último mensaje del foro
			if(!$lastpost && array_key_exists($forum->lastPostId(),$topic->getPosts(0))){
				$lastpost = true;
			}
			
			$topic->setForum($moveforum);
			if ($topic->save()){
				//Decrementa el número de temas
				$forum->setTopics(($forum->topics()-1>0) ? $forum->topics()-1 : 0);
				$forum->setPosts(($forum->posts()-($topic->replies() + 1)>0) ? $forum->posts()-($topic->replies()+1) : 0);
				$forum->save();
				
				$mf->setPosts($mf->posts()+($topic->replies() + 1));
				$mf->addTopic();
				$mf->save();

				//Cambiamos el foro de los mensajes del tema
				if ($topic->getPosts()){
					foreach ($topic->getPosts() as $k=>$v){
						$v->setForum($moveforum);
						$v->save();
					}

				}
			}				
		}
		
		//Actualizamos el último mensaje del foro
		if($lastpost){
			
			$post = $forum->getLastPost();
			$forum->setPostId($post);
			$forum->save();	
		}

		//Actualizamos el último mensaje del foro al que fue movido el tema
		$post = $mf->getLastPost();
		$post ? $mf->setPostId($post) : '';
		$mf->save();	

		redirect_header('moderate.php?id='.$forum->id(), 1, _MS_EXMBB_MOVEOK);
		die();
		
	} else {
		
		global $tpl;
		$xoopsOption['template_main'] = "exmbb_moderateforms.html";
		$xoopsOption['module_subpage'] = "moderate";
		include 'header.php';
		
		BBFunctions::makeHeader();
		$form = new RMForm(_MS_EXMBB_MOVETITLE, 'frmMove', 'moderate.php');
		$form->addElement(new RMHidden('id', $forum->id()));
		$form->addElement(new RMHidden('op','move'));
		$form->addElement(new RMHidden('ok','1'));
		$i = 0;
		foreach ($topics as $k){
			$form->addElement(new RMHidden('topics['.$i.']',$k));
			++$i;
		}
		$form->addElement(new RMSubTitle('&nbsp',1,''));
		$form->addElement(new RMSubTitle(_MS_EXMBB_MOVETARGET,1,'even'));
		$ele = new RMSelect('Foro', 'moveforum');
		$ele->addOption(0,'',1);
		
		$tbl1 = $db->prefix("exmbb_categories");
		$tbl2 = $db->prefix("exmbb_forums");
		$sql = "SELECT b.*, a.title FROM $tbl1 a, $tbl2 b WHERE b.cat=a.id_cat AND b.active='1' AND id_forum<>".$forum->id()." ORDER BY a.order, b.order";
		$result = $db->query($sql);
		$categories = array();
		while ($row = $db->fetchArray($result)){
			$cforum = array('id'=>$row['id_forum'], 'name'=>$row['name']);
			if (isset($categores[$row['cat']])){
				$categories[$row['cat']]['forums'][] = $cforum;
			} else {
				$categories[$row['cat']]['title'] = $row['title'];
				$categories[$row['cat']]['forums'][] = $cforum;
			}
		}
		
		foreach ($categories as $cat){
			
			$ele->addOption(0, $cat['title'], 0, true, 'color: #000; font-weight: bold; font-style: italic; border-bottom: 1px solid #c8c8c8;');
			foreach ($cat['forums'] as $cforum){
				$ele->addOption($cforum['id'], $cforum['name'],0,false,'padding-left: 10px;');
			}
			
		}
		$form->addElement($ele, true, "noselect:0");
		$ele = new RMButtonGroup();
		$ele->addButton('sbt',_MS_EXMBB_MOVENOW,'submit');
		$ele->addButton('cancel', _CANCEL, 'button', 'onclick="history.go(-1);"');
		$form->addElement($ele);
		$tpl->assign('moderate_form', $form->render());
		
		include 'footer.php';
		
	}
	
}

/**
* @desc Cerrar o abrir un tema
*/
function closeTopic($close){
	global $forum;
	$util =& RMUtils::getInstance();
		
	if (!$util->validateToken()){
		redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_SESSINVALID);
		die();
	}	

	$topics = isset($_REQUEST['topics']) ? $_REQUEST['topics'] : null;
		
	if (empty($topics) || (is_array($topics) && empty($topics))){
		redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_SELTOPIC);
		die();
	}
	
	$topics = !is_array($topics) ? array($topics) : $topics;
	
	foreach ($topics as $k){
		$topic = new BBTopic($k);
		if ($topic->isNew()) continue;
		
		$topic->setStatus($close);
		$topic->save();
		
	}
	
	redirect_header('moderate.php?id='.$forum->id(), 1, _MS_EXMBB_ACTIONOK);
	
}

/**
* @desc Cerrar o abrir un tema
*/
function stickyTopic($sticky){
	global $forum;
	
	$util =& RMUtils::getInstance();
		
	if (!$util->validateToken()){
		redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_SESSINVALID);
		die();
	}
	
	$topics = isset($_REQUEST['topics']) ? $_REQUEST['topics'] : null;
		
	if (empty($topics) || (is_array($topics) && empty($topics))){
		redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_SELTOPIC);
		die();
	}
	
	$topics = !is_array($topics) ? array($topics) : $topics;
	
	foreach ($topics as $k){
		$topic = new BBTopic($k);
		if ($topic->isNew()) continue;
		
		$topic->setSticky($sticky);
		$topic->save();
		
	}
	
	redirect_header('moderate.php?id='.$forum->id(), 1, _MS_EXMBB_ACTIONOK);
	
}

/**
* @desc Eliminar temas
*/
function deleteTopics(){
	global $db, $xoopsModuleConfig, $util, $forum, $xoopsUser, $xoopsOption;
	
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;	
	$topics = isset($_REQUEST['topics']) ? $_REQUEST['topics'] : null;
		
	if (empty($topics) || (is_array($topics) && empty($topics))){
		redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_SELTOPIC);
		die();
	}
	
	$topics = !is_array($topics) ? array($topics) : $topics;
	
	$lastpost = false;
	if ($ok){
	
		$util =& RMUtils::getInstance();
		
		if (!$util->validateToken()){
			redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_SESSINVALID);
			die();
		}
		foreach ($topics as $k){
			$topic = new BBTopic($k);
			if ($topic->isNew()) continue;
			if ($topic->forum()!=$forum->id()) continue;

			//Verificamos si el tema contiene el último mensaje del foro
			if(!$lastpost && array_key_exists($forum->lastPostId(),$topic->getPosts(0))){
				$lastpost = true;
			}
			
			$topic->delete();
			
		}

		//Actualizamos el último mensaje del foro
		if($lastpost){
			$forum = new BBForum($forum->id());		

			$post = $forum->getLastPost();
			$forum->setPostId($post);
			$forum->save();	
		}
		
		redirect_header('moderate.php?id='.$forum->id(), 1, _MS_EXMBB_ACTIONOK);
	
	} else {
		
		include 'header.php';
		
		$hiddens['op'] = 'delete';
		$hiddens['ok'] = 1;
		$hiddens['id'] = $forum->id();
		
		foreach ($topics as $k){
			$hiddens['topics[]'][] = $k;
		}
		
		$buttons['sbt']['value'] = _MS_EXMBB_DELNOW;
		$buttons['sbt']['type'] = 'submit';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['extra'] = 'onclick="history.go(-1);"';
		
		$util->msgBox($hiddens, 'moderate.php', _MS_EXMBB_DELCONF, XOOPS_ALERT_ICON, $buttons, true, '', _MS_EXMBB_DELTITLE);
		
		include 'footer.php';
		
	}
	
}



/**
* @desc Aprueba o no un tema
**/
function approvedTopics($app=0){

	global $forum;

	$topics = isset($_REQUEST['topics']) ? $_REQUEST['topics'] : null;
		
	if (empty($topics) || (is_array($topics) && empty($topics))){
		redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_SELTOPIC);
		die();
	}
	
	$topics = !is_array($topics) ? array($topics) : $topics;
	
	$util =& RMUtils::getInstance();
		
	if (!$util->validateToken()){
		redirect_header('moderate.php?id='.$forum->id(), 2, _MS_EXMBB_SESSINVALID);
		die();
	}

	$lastpost = false;
	foreach ($topics as $k){
		$topic = new BBTopic($k);
		if ($topic->isNew()) continue;

		$lastapp = $topic->approved();

		$topic->setApproved($app);
		$topic->save();
		
	}

	//Actualizamos el último mensaje del foro
	$post = $forum->getLastPost();
	$forum->setPostId($post);
	$forum->save();	

	redirect_header('moderate.php?id='.$forum->id(), 1, _MS_EXMBB_ACTIONOK);

}


/**
* @desc Aprueba o no un mensaje editado
**/
function approvedPosts($app=0){
	global $xoopsUser;
	$posts=isset($_REQUEST['posts']) ? intval($_REQUEST['posts']) : 0;

	
	//Verifica que el mensaje sea válido
	if ($posts<=0){
		redirect_header('./topic.php?id='.$posts,1,_MS_EXMBB_NOTID);
		die();
	}

	//Comprueba que el mensaje exista
	$post=new BBPost($posts);
	if ($post->isNew()){
		redirect_header('./topic.php?id='.$posts,1,_MS_EXMBB_NOTEXIST);
		die();
	}	

	//Comprueba si usuario es moderador del foro
	$forum=new BBForum($post->forum());
	if (!$forum->isModerator($xoopsUser->uid()) || !$xoopsUser->isAdmin()){
		redirect_header('./topic.php?id='.$posts,1,_MS_EXMBB_NOPERM);
		die();
	}

	
	$util =& RMUtils::getInstance();
		
	if (!$util->validateToken()){
		redirect_header('./topic.php?id='.$posts, 2, _MS_EXMBB_SESSINVALID);
		die();
	}

	$post->setApproved($app);
	if ($post->editText()){
		$post->setText($post->editText());
	}
	$post->setEditText('');
	$post->save();

	redirect_header('./topic.php?id='.$post->topic(), 1, _MS_EXMBB_ACTIONOK);

	

}



$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'move':
		moveTopics();
		break;
	case 'close':
		closeTopic(1);
		break;
	case 'open':
		closeTopic(0);
		break;
	case 'sticky':
		stickyTopic(1);
		break;
	case 'unsticky':
		stickyTopic(0);
		break;
	case 'delete':
		deleteTopics();
		break;
	case 'approved':
		approvedTopics(1);
	break;
	case 'noapproved':
		approvedTopics();
	break;
	case 'approvedpost':
		approvedPosts(1);
	break;
	case 'noapprovedpost':
		approvedPosts();
	break;
	default:
		showItemsAndOptions();
		break;
}
?>
