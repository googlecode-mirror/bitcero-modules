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
// @copyright: 2007 - 2008 Red México

define('BB_LOCATION','posts');
include '../../mainfile.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

$fid = isset($_REQUEST['fid']) ? intval($_REQUEST['fid']) : 0;
$tid = isset($_REQUEST['tid']) ? intval($_REQUEST['tid']) : 0;
if ($fid<=0 && $tid<=0){
	redirect_header('./', 2, _MS_EXMB_ERRFORUMNEW);
	die();
}

if ($fid>0){
	$forum = new BBForum($fid);
	$retlink = './forum.php?id='.$forum->id();
	$create = true;
} else {
	$topic = new BBTopic($tid);
	if ($topic->isNew()){
		redirect_header('./', 2, _MS_EXMBB_TOPICNOEXISTS);
		die();
	}
	$forum = new BBForum($topic->forum());
	$retlink = './topic.php?id='.$topic->id();
	$create = false;
}

if ($forum->isNew()){
	redirect_header('./', 2, _MS_EXMBB_FORUMNOEXISTS);
	die();
}

if (!$forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, $fid>0 ? 'topic' : 'reply')){
	redirect_header($retlink, 2, _MS_EXMBB_NOPERM);
	die();
}

switch($op){
	case 'post':
		
		foreach ($_POST as $k => $v){
			$$k = $v;
		}
		
		$util =& RMUtils::getInstance();
		
		if (!$util->validateToken()){
			redirect_header('./'.($create ? 'forum.php?id='.$forum->id() : 'topic.php?id='.$topic->id()), 2, _MS_EXMBB_SESSINVALID);
			die();
		}
		
		$myts =& MyTextSanitizer::getInstance();
		
		if ($create){
			$topic = new BBTopic();
			$topic->setApproved($forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'approve'));
			$topic->setDate(time());
			$topic->setForum($forum->id());
			$topic->setPoster($xoopsUser ? $xoopsUser->uid() : 0);
			$topic->setPosterName($xoopsUser ? $xoopsUser->uname() : $name);
			$topic->setRating(0);
			$topic->setReplies(0);
			$topic->setStatus(0);
			if ($xoopsUser && $xoopsModuleConfig['sticky']){
				$csticky = $xoopsUser->isAdmin() || $forum->isModerator($xoopsUser->uid()) || $xoopsUser->posts()>$xoopsModuleConfig['sticky_posts'];
				if ($sticky) $topic->sticky(isset($sticky) ? $sticky : 0);
				
			} else {
				$topic->setSticky(0);
			}
			$topic->setTitle($myts->addSlashes($subject));
			$topic->setViews(0);
			$topic->setVotes(0);
			$topic->setFriendName($util->sweetstring($subject));
			if ($xoopsUser && isset($sticky) && $xoopsModuleConfig['sticky']){
				if ($xoopsUser->isAdmin() || $forum->isModerator($xoopsUser->uid()) || $xoopsUser->posts()>$xoopsModuleConfig['sticky_posts']){
					$topic->setSticky($sticky);
				}
			}
			if (!$topic->save()){
				redirect_header('./forum.php?id='.$forum->id(), 2, _MS_EXMBB_ERRPOST);
				die();
			}
		}
		
		
		$post = new BBPost();
		$post->setPid(0);
		
		$post->setTopic($topic->id());
		$post->setForum($forum->id());
		$post->setDate(time());
		$post->setUser($xoopsUser ? $xoopsUser->uid() : 0);
		$post->setUname($xoopsUser ? $xoopsUser->uname() : $name);
		$post->setIP($_SERVER['REMOTE_ADDR']);
		$post->setHTML(isset($dohtml) ? 1 : 0);
		$post->setBBCode(isset($doxcode) ? 1 : 0);
		$post->setSmiley(isset($dosmiley) ? 1 : 0);
		$post->setBR(isset($dobr) ? 1 : 0);
		$post->setImage(isset($doimg) ? 1 : 0);
		$post->setIcon('');
		$post->setApproved($forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'approve'));
		$post->setSignature(isset($sig) ? 1 : 0);
		$post->setText($msg);
		if (!$post->save() && $create){
			$topic->delete();
			redirect_header($retlink, 2, _MS_EXMBB_ERRPOST);
			die();
		}
		if (!$topic->approved()){
			BBFunctions::notifyAdmin($forum->moderators(),$forum, $topic, $post);
		}
		// Adjuntamos archivos si existen
		if ($forum->attachments() && $forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'attach')){
			
			$up = new RMUploader(true);
			$folder = $xoopsModuleConfig['attachdir'];
			$exts = array();
			foreach ($forum->extensions() as $k){
				$exts[] = $up->getMIME($k);
			}
			
			$up->prepareUpload($folder, $exts, $xoopsModuleConfig['maxfilesize']*1024);
			$errors = '';
			$filename = '';
			
			if ($up->fetchMedia('attach')){
				if (!$up->upload()){
					$errors .= $up->getErrors();
				} else {
				
					$filename = $up->getSavedFileName();
					$fullpath = $up->getSavedDestination();
				
					$attach = new BBAttachment();
					$attach->setPost($post->id());
					$attach->setFile($filename);
					$attach->setMime($up->getMediaType());
					$attach->setDate(time());
					$attach->downloads(0);
					$attach->setName($up->getMediaName());
					if (!$attach->save()) $errors .= $attach->getErrors();
				}
				
			}
			
		}
		
		$topic->setLastPost($post->id());
		if (!$create) $topic->addReply();
		$topic->save();
		
		$forum->setPostId($post->id());
		$forum->addPost();
		if ($create) $forum->addTopic();
		$forum->save();
		
		// Incrementamos el nivel de posts del usuario
		if ($xoopsUser){
			$xoopsUser->setVar('posts', $xoopsUser->posts()+1);
			$xoopsUser->save();
		}
		
		// Notificaciones
		$not = new XoopsNotificationHandler($db);
		if ($create){
			//Notificacion de nuevo tema en foro
			$not->exmTriggerEvent('forum',$forum->id(), 'newtopic',array('topic'=>$topic->id()));
			//Notificación de nuevo mensaje en cualquier foro
			$not->exmTriggerEvent('any_forum','', 'postanyforum',array('forum'=>$forum->id(),'post'=>$post->id()));
		}else{
			//Notificación de nuevo mensaje en tema
			$not->exmTriggerEvent('topic',$topic->id(), 'newpost',array('post'=>$post->id()));
			//Notificación de nuevo mensaje en foro especificado
			$not->exmTriggerEvent('forum',$forum->id(), 'postforum',array('post'=>$post->id()));
			//Notificación de nuevo mensaje en cualquier foro
			$not->exmTriggerEvent('any_forum','', 'postanyforum',array('forum'=>$forum->id(),'post'=>$post->id()));
			
		}
		
		redirect_header('topic.php?pid='.$post->id().'#p'.$post->id(), 1, $errors == '' ? _MS_EXMBB_POSTOK : _MS_EXMBB_POSTOKERR .'<br />'.$errors);
			
		break;
		
	default:
		
		$xoopsOption['template_main'] = "exmbb_postform.html";
		$xoopsOption['module_subpage'] = "post";

		include 'header.php';

		BBFunctions::makeHeader();
				
		$form = new RMForm($tid>0 ? _MS_EXMBB_FREPLYTITLE : _MS_EXMBB_FTOPICTITLE, 'frmTopic', 'post.php');
		$form->addElement(new RMSubTitle(_MS_EXMBB_FMSGTIP, 1, 'even'));
		if (!$xoopsUser){
			$form->addElement(new RMText(_MS_EXMBB_NAME, 'name', 50, 255), true);
			$form->addElement(new RMText(_MS_EXMBB_EMAIL, 'email', 50, 255), true, 'email');
		}
		if ($create) $form->addElement(new RMText(_MS_EXMBB_SUBJECT, 'subject', 50, 255, $tid>0 ? $topic->title() : ''), true);
		
		// Sticky
		if ($xoopsUser && $xoopsModuleConfig['sticky'] && $create){
			
			$sticky = $xoopsUser->isAdmin() || $forum->isModerator($xoopsUser->uid()) || $xoopsUser->posts()>$xoopsModuleConfig['sticky_posts'];
			if ($sticky){
				if ($create || BBfunctions::getFirstId($topic->id())==$topic->id())
					$form->addElement(new RMYesNo(_MS_EXMBB_STICKYTOPIC, 'sticky', !$create ? $topic->sticky() : 0));
			}
			
		}
		
		// Si se especifico una acotación entonces la cargamos
		$idq = isset($_GET['quote']) ? intval($_GET['quote']) : 0;
		if ($idq>0){
			$post = new BBPost($idq);
			if ($post->isNew()) break;
			$quote = "[quote=".$post->uname()."]".$post->getVar('post_text','n')."[/quote]\n\n";
		}
		
		$form->addElement(new RMEditor(_MS_EXMBB_MSG, 'msg', '90%', '300px', isset($quote) ? $quote : '', $xoopsModuleConfig['editor']), true);
		
		$ele = new RMCheck('');
		$ele->asTable(5);
		$ele->addOption(_MS_EXMBB_BBCODE, 'doxcode', 1, 1);
		$ele->addOption(_MS_EXMBB_SMILES, 'dosmiley', 1, 1);
		if ($xoopsModuleConfig['html'] || $xoopsUser->isAdmin()) $ele->addOption(_MS_EXMBB_HTML, 'dohtml', 1, 0);
		$ele->addOption(_MS_EXMBB_BR, 'dobr', 1, 1);
		$ele->addOption(_MS_EXMBB_IMG, 'doimg', 1, 1);
		$form->addElement($ele);
		
		// Adjuntar Archivos
		if ($forum->attachments() && $forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'attach')){
			$ele = new RMFile(_MS_EXMBB_ATTACH, 'attach', 45, $xoopsModuleConfig['maxfilesize'] * 1024);
			$ele->setDescription(sprintf(_MS_EXMBB_EXTS, implode(',', $forum->extensions())));
			$form->addElement($ele);
			$form->setExtra('enctype="multipart/form-data"');
		}
		
		$form->addElement(new RMHidden('op','post'));
		$form->addElement(new RMHidden($fid>0 ? 'fid' : 'tid', $fid>0 ? $fid : $tid));
		$ele = new RMButtonGroup();
		$ele->addButton('sbt', _SUBMIT, 'submit');
		$ele->addButton('cancel', _CANCEL, 'button', 'onclick="history.go(-1)";');
		$form->addElement($ele);

		$tpl->assign('topic_form', $form->render());
		
		/**
		* @desc Cargamos los mensajes realizados en este tema
		*/
		if ($mc['numpost']>0 && !$create){
			$sql = "SELECT * FROM ".$db->prefix("exmbb_posts")." WHERE id_topic='".$topic->id()."' ORDER BY post_time DESC LIMIT 0, $mc[numpost]";
			$result = $db->query($sql);
			while ($row = $db->fetchArray($result)){
				$post = new BBPost();
				$post->assignVars($row);
				$tpl->append('posts', array('id'=>$post->id(), 'text'=>$post->text(),
						'time'=>date($xoopsConfig['datestring'], $post->date()),'uname'=>$post->uname()));
			}
		}
		
		$tpl->assign('lang_topicreview', _MS_EXMBB_TOPICREV);

		include 'footer.php';
		
		break;
}


?>
