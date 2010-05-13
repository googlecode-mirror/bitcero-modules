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
$forum = new BBForum($topic->forum());

// Verificamos si el usuario tiene permisos de edición en el foro
if (!$xoopsUser || !$forum->isAllowed($xoopsUser->getGroups(), 'edit')){
	redirect_header('topic.php?pid='.$id.'#p'.$id, 2, _MS_EXMBB_NOPERM);
	die();
}

// Verificamos si el usuario tiene permiso de edición para el post
if ($xoopsUser->uid()!=$post->user() && (!$xoopsUser->isAdmin() && !$forum->isModerator($xoopsUser->uid()))){
	redirect_header('topic.php?pid='.$id.'#p'.$id, 2, _MS_EXMBB_NOPERM);
	die();
}

$util =& RMUtils::getInstance();

switch($op){
	case 'post':
		
		foreach ($_POST as $k => $v){
			$$k = $v;
		}
		
		if (!$util->validateToken()){
			redirect_header('edit.php?id='.$id, 2, _MS_EXMBB_SESSINVALID);
			die();
		}
		
		$myts =& MyTextSanitizer::getInstance();
		
		if (BBFunctions::getFirstId($topic->id())==$id){
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
		$post->setHTML(isset($dohtml) ? 1 : 0);
		$post->setBBCode(isset($doxcode) ? 1 : 0);
		$post->setSmiley(isset($dosmiley) ? 1 : 0);
		$post->setBR(isset($dobr) ? 1 : 0);
		$post->setImage(isset($doimg) ? 1 : 0);
		$post->setIcon('');
		$post->setSignature(isset($sig) ? 1 : 0);
		if ($forum->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS, 'approve') || $xoopsUser->isAdmin() || $forum->isModerator()){
			$post->setText($msg);
		} else {
			$post->setEditText($msg);
			BBFunctions::notifyAdmin($forum->moderators(),$forum, $topic, $post,1);
		}
		
		if (!$post->save() || !$topic->save()){
			redirect_header('edit.php?id='.$id, 2, _MS_EXMBB_ERRPOSTEDIT);
			die();
		}
		
		redirect_header('topic.php?pid='.$post->id().'#p'.$post->id(), 1, _MS_EXMBB_POSTOKEDIT);
			
		break;
	
	case 'delete':
		
		/**
		* Eliminamos archivos siempre y cuando el usuario se al propietario
		* del mensaje, sea administrador o moderador
		*/
		if (!$util->validateToken()){
			redirect_header('edit.php?id='.$post->id().'#attachments', 2, _MS_EXMBB_SESSINVALID);
			die();
		}
		
		$files = isset($_POST['files']) ? $_POST['files'] : array();
		
		if (empty($files)){
			redirect_header('edit.php?id='.$post->id().'#attachments', 2, _MS_EXMBB_ERRSELECTFILES);
			die();
		}
		$errors = '';
		foreach ($files as $k){
			$file = new BBAttachment($k);
			if (!$file->delete()) $errors .= $file->errors()."<br />";
		}
		
		redirect_header('edit.php?id='.$post->id().'#attachments', 1, $errors != '' ? _MS_EXMBB_ERRHAPPEN."<br />".$errors : _MS_EXMBB_DELETEOK);
		
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
