<?php
// $Id$
// --------------------------------------------------------------
// bXpress Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('BB_LOCATION','posts');
include '../../mainfile.php';

$op = rmc_server_Var($_REQUEST, 'op', '');
$id = rmc_server_Var($_REQUEST, 'id', 0);

if ($id<=0){
	redirect_header('./', 2, __('No post has been specified!','bxpress'));
	die();
}

$post = new bXPost($id);
if ($post->isNew()){
	redirect_header('./', 2, __('Specified post does not exists!','bxpress'));
	die();
}

$topic = new bXTopic($post->topic());
$forum = new bXForum($topic->forum());

// Verificamos si el usuario tiene permisos de edición en el foro
if (!$xoopsUser || !$forum->isAllowed($xoopsUser->getGroups(), 'edit')){
	redirect_header('topic.php?pid='.$id.'#p'.$id, 2, __('You don\'t have permission to edit this post!','bxpress'));
	die();
}

// Verificamos si el usuario tiene permiso de edición para el post
if ($xoopsUser->uid()!=$post->user() && (!$xoopsUser->isAdmin() && !$forum->isModerator($xoopsUser->uid()))){
	redirect_header('topic.php?pid='.$id.'#p'.$id, 2, __('You don\'t have permission to edit this post!','bxpress'));
	die();
}


switch($op){
	case 'post':
		
		foreach ($_POST as $k => $v){
			$$k = $v;
		}
		
		if (!$xoopsSecurity->check()){
			redirect_header('edit.php?id='.$id, 2, __('Session token expired!','bxpress'));
			die();
		}
		
		$myts =& MyTextSanitizer::getInstance();
		
		if (bXFunctions::getFirstId($topic->id())==$id){
			$topic->setDate(time());
			$topic->setTitle($myts->addSlashes($subject));
			if ($xoopsUser && isset($sticky) && $xoopsModuleConfig['sticky']){
				if ($xoopsUser->isAdmin() || $forum->isModerator($xoopsUser->uid()) || ($xoopsUser->posts()>$xoopsModuleConfig['sticky_posts'] && $xoopsUser->uid()==$topic->poster())){
					$topic->setSticky($sticky);
				}
			}
		}
		
		$post->setPid(0);
		$post->setIP($_SERVER['REMOTE_ADDR']);
		$post->setIcon('');
		$post->setSignature(isset($sig) ? 1 : 0);
		if ($forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'approve') || $xoopsUser->isAdmin() || $forum->isModerator()){
			$post->setText($msg);
		} else {
			$post->setEditText($msg);
			bXFunctions::notifyAdmin($forum->moderators(),$forum, $topic, $post,1);
		}
		
		if (!$post->save() || !$topic->save()){
			redirect_header('edit.php?id='.$id, 2, __('Changes could not be stored. Please try again!','bxpress'));
			die();
		}
		
		redirect_header('topic.php?pid='.$post->id().'#p'.$post->id(), 1, __('Changes stored successfully!','bxpress'));
			
		break;
	
	case 'delete':
		
		/**
		* Eliminamos archivos siempre y cuando el usuario se al propietario
		* del mensaje, sea administrador o moderador
		*/
		if (!$xoopsSecurity->check()){
			redirect_header('edit.php?id='.$post->id().'#attachments', 2, __('Session token expired!','bxpress'));
			die();
		}
		
		$files = rmc_server_var($_POST, 'files', array()); 
		
		if (empty($files)){
			redirect_header('edit.php?id='.$post->id().'#attachments', 2, __('You have not selected any file to delete!','bxpress'));
			die();
		}
		$errors = '';
		foreach ($files as $k){
			$file = new bXAttachment($k);
			if (!$file->delete()) $errors .= $file->errors()."<br />";
		}
		
		redirect_header('edit.php?id='.$post->id().'#attachments', 1, $errors != '' ? __('Errors ocurred during this operation!','bxpress')."<br />".$errors : __('Files deleted successfully!','bxpress'));
		
		break;
	
	case 'upload':
		
		/**
		* Almacenamos archivos siempre y cuando el usuario
		* actual tenga permisos y no haya rebasado el límite de archivos
		* adjuntos por mensaje
		*/
		
		if (!$util->validateToken()){
			redirect_header('edit.php?id='.$post->id().'#attachments', 2, _MS_EXMBB_SESSINVALID);
			die();
		}
		
		if ($forum->attachments() && $forum->isAllowed($xoopsUser->getGroups(), 'attach')){
			
			// Comprobamos si no ha alcanzado su número limite de envios
			if ($post->totalAttachments()>= $xoopsModuleConfig['attachlimit']){
				redirect_header('edit.php?id='.$post->id().'#attachments', 2, _MS_EXMBB_ERRATLIMIT);
				die();
			}
			
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
					if (!$attach->save()){
						redirect_header('edit.php?id='.$post->id().'#attachments', 2, _MS_EXMBB_ERRSAVEATTACH."<br />".$up->getErrors());
						die();
					}
				}
				
			}
			
		} else {
			redirect_header('edit.php?id='.$post->id().'#attachments', 2, _MS_EXMBB_NOPERM);
		}
		
		redirect_header('edit.php?id='.$post->id().'#attachments', 1, _MS_EXMBB_ATTACHOK);
		
		break;
	
	default:
		
		$xoopsOption['template_main'] = "exmbb_postform.html";
		$xoopsOption['module_subpage'] = "edit";
		include 'header.php';

		BBFunctions::makeHeader();
				
		$form = new RMForm(_MS_EXMBB_FEDITTITLE, 'frmTopic', 'edit.php');
		$first_id = BBFunctions::getFirstId($topic->id());
		if ($id==$first_id){
			$form->addElement(new RMText(_MS_EXMBB_SUBJECT, 'subject', 50, 255, $topic->title()), true);
			// Sticky
			if ($xoopsUser && $xoopsModuleConfig['sticky']){
				
				$sticky = $xoopsUser->isAdmin() || $forum->isModerator($xoopsUser->uid()) || ($xoopsUser->posts()>$xoopsModuleConfig['sticky_posts'] && $topic->poster()==$xoopsUser->uid());
				if ($sticky){
					$form->addElement(new RMYesNo(_MS_EXMBB_STICKYTOPIC, 'sticky', $topic->sticky()));
				}
				
			}
		}
		
		// Si se especifico una acotación entonces la cargamos
		$idq = isset($_GET['quote']) ? intval($_GET['quote']) : 0;
		if ($idq>0){
			$post = new BBPost($idq);
			if ($post->isNew()) break;
			$quote = "[quote=".$post->uname()."]".$post->getVar('post_text','e')."[/quote]\n\n";
		}
		
		$form->addElement(new RMEditor(_MS_EXMBB_MSG, 'msg', '90%', '300px', isset($quote) ? $quote : $post->getVar('post_text','e'), $xoopsModuleConfig['editor']), true);
		
		$ele = new RMCheck('');
		$ele->asTable(5);
		$ele->addOption(_MS_EXMBB_BBCODE, 'doxcode', 1, $post->bbcode());
		$ele->addOption(_MS_EXMBB_SMILES, 'dosmiley', 1, $post->smiley());
		if ($xoopsModuleConfig['html'] || $xoopsUser->isAdmin()) $ele->addOption(_MS_EXMBB_HTML, 'dohtml', 1, $post->html());
		$ele->addOption(_MS_EXMBB_BR, 'dobr', 1, $post->br());
		$ele->addOption(_MS_EXMBB_IMG, 'doimg', 1, $post->image());
		$form->addElement($ele);
		$form->addElement(new RMHidden('op','post'));
		$form->addElement(new RMHidden('id', $id));
		$ele = new RMButtonGroup();
		$ele->addButton('sbt', _MS_EXMBB_SAVECHANGES, 'submit');
		$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location = \'topic.php?pid='.$post->id().'#p'.$post->id().'\'";');
		$form->addElement($ele);
		
		// Adjuntar Archivos
		if ($forum->attachments() && $forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'attach')){
			$forma = new RMForm('<a name="attachments"></a>'._MS_EXMBB_EXATTACH, 'frmAttach', 'edit.php');
			$forma->addElement(new RMSubTitle(sprintf(_MS_EXMBB_EXATTACHTIP, $xoopsModuleConfig['attachlimit']), 1, 'even'));
			if ($post->totalAttachments()<$xoopsModuleConfig['attachlimit']){
				$ele = new RMFile(_MS_EXMBB_ATTACH, 'attach', 45, $xoopsModuleConfig['maxfilesize'] * 1024);
				$ele->setDescription(sprintf(_MS_EXMBB_EXTS, implode(',', $forum->extensions())));
				$forma->addElement($ele, true);
				$forma->setExtra('enctype="multipart/form-data"');
			}
			// Lista de Archivos Adjuntos
			$list = new RMCheck(_MS_EXMBB_CURATTACH);
			$list->asTable(1);
			foreach ($post->attachments() as $file){
				$list->addOption("<img src='".$file->getIcon()."' align='absmiddle' /> ".$file->name()." (".formatBytesSize($file->size()).")", 'files[]', $file->id());
			}
			$forma->addElement($list);
			$ele = new RMButtonGroup();
			if ($post->totalAttachments()<$xoopsModuleConfig['attachlimit']) $ele->addButton('upload', _MS_EXMBB_UPLOAD, 'submit');
			$ele->addButton('delete', _MS_EXMBB_DELFILE, 'button', 'onclick="document.forms[\'frmAttach\'].op.value=\'delete\'; submit();"');
			$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location = \'topic.php?pid='.$post->id().'#p'.$post->id().'\'";');
			$forma->addElement($ele);
			$forma->addElement(new RMHidden('op', 'upload'));
			$forma->addElement(new RMHidden('id', $id));
		}

		$tpl->assign('topic_form', $form->render()."<br />".$forma->render());
		
		$tpl->assign('lang_topicreview', _MS_EXMBB_TOPICREV);

		include 'footer.php';
		
		break;
}


?>
