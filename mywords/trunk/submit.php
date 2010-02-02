<?php
// $Id: submit.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
$xoopsOption['template_main'] = 'mywords_submit_form.html';
$xoopsOption['module_subpage'] = 'submit';
include 'header.php';

if (!$mc['submit']){
	redirect_header(MWFunctions::get_url(), 2, _MS_MW_UNABLESEND);
	die();
}

if (empty($xoopsUser) && !$mc['anonimo']){
	redirect_header(mw_get_url(), 2, _MS_MW_YOUNOTSEND);
	die();
}

$edit = false;
if (isset($vars['edit']) && $vars['edit']>0){
	
	if (empty($xoopsUser)){
		redirect_header(mw_get_url(), 2, _MS_MW_YOUNOTSEND);
		die();
	}
	
	$edit = true;
	$post = new MWPost($vars['edit']);
	if ($post->isNew()){
		redirect_header(mw_get_url(), 2, _MS_MW_ERRNOPOST);
		die();
	}
	
}

switch($op){
	case 'publishedit':
	case 'saveedit':
	case 'saveretedit':
		
		foreach ($_POST as $k => $v){
			$$k = $v;
		}
		$id = $post;
		if ($id<=0){
			redirect_header(mw_get_url(), 2, _MS_MW_ERRNOPOST);
			die();
		}
		if (!$util->checkSecurityCode($codigo)){
			redirect_header('?edit='.$id, 2, _MS_MW_ERRCODE);
			die();
		}
		$post = new MWPost($id);
		if ($post->isNew()){
			redirect_header(mw_get_url(), 2, _MS_MW_ERRNOPOST);
			die();
		}
		
		if ($titulo==''){
			redirect_header('?edit='.$id, 2, _MS_MW_ERRTITLE);
			die();
		}
		$titulo = $myts->addSlashes($titulo);
		
		if ($texto==''){
			redirect_header('?edit='.$id, 2, _MS_MW_ERRSUBMITTEXT);
			die();
		}
		
		if (empty($categos)){
			redirect_header('?edit='.$id, 2, _MS_MW_ERRCATS);
			die();
		}
		
		if ($titulo_amigo==''){
			$titulo_amigo = $util->sweetstring($titulo);
		} else {
			$titulo_amigo = $util->sweetstring($titulo_amigo);
		}
		
		$tracks = explode(" ", $trackbacks);
		$pinged = $post->getPinged(true);
		$oktracks = array();
		foreach ($tracks as $k){
			if (in_array($k, $pinged)) continue;
			$oktracks[] = $k;
		}
		
		$editorcategos = editorCategos($categos);
		$editor = false;
		if (count($editorcategos)==0){
			$editorcategos = $categos;
		} else {
			$editor = true;
		}
		
		$post->setTitle($titulo);
		$post->setFriendTitle($titulo_amigo);
		
		$post->setModDate(time());
		$post->setText($texto);
		$post->setAllowPings(1);
		$post->addToCategos($editorcategos);
		$post->setTrackBacks($oktracks);
		$post->setStatus($estado);
		$post->setExcerpt($excerpt);
		$post->setHTML(isset($dohtml) ? 1 : 0);
		$post->setXCode(isset($doxcode) ? 1 : 0);
		$post->setBR(isset($dobr) ? 1 : 0);
		$post->setDoImage(isset($doimage) ? 1 : 0);
		$post->setSmiley(isset($dosmiley) ? 1 : 0);
		$post->setAuthor($xoopsUser ? $xoopsUser->uid() : 0);
		$post->setAuthorName($xoopsUser ? $xoopsUser->uname() : _MS_MW_ANONYMOUS);
		
		$post->setApproved($editor || $xoopsUser->isAdmin() ? 1 : $mc['aproveuser']);
		
		if ($post->update()){
			redirect_header($post->getPermalink(), 1, _MS_MW_DBOK);
		} else {
			redirect_header($post->getPermalink(), 2, _MS_MW_DBERROR);
		}
		
		break;
	case 'publish':
	case 'save':
	case 'saveret':
		
		foreach ($_POST as $k => $v){
			$$k = $v;
		}
		
		/*if (!$util->validateToken()){
			redirect_header('?submit', 2, _MS_MW_ERRTOKEN);
			die();
		}*/

		if (!$util->checkSecurityCode($codigo)){
			redirect_header('?submit', 2, _MS_MW_ERRCODE);
			die();
		}
		
		if ($titulo==''){
			redirect_header('?submit', 2, _MS_MW_ERRTITLE);
			die();
		}
		$titulo = $myts->addSlashes($titulo);
		
		if ($texto==''){
			redirect_header('?submit', 2, _MS_MW_ERRSUBMITTEXT);
			die();
		}
		
		if (empty($categos)){
			redirect_header('?submit', 2, _MS_MW_ERRCATS);
			die();
		}
		
		$time = time();
		$rango = $time - 86400;
		$result = $db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE fecha>=$rango AND titulo='$titulo'");
		list($num) = $db->fetchRow($result);
		
		if ($num>0){
			redirect_header('?submit', 2, _MS_MW_ERREXISTS);
			die();
		}
		
		$editorcategos = editorCategos($categos);
		$editor = false;
		if (count($editorcategos)==0){
			$editorcategos = $categos;
		} else {
			$editor = true;
		}
		
		#Guardamos los datos del Post
		$post = new MWPost();
		$post->setTitle($titulo);
		$post->setFriendTitle($util->sweetstring($titulo));
		$post->setAuthor(empty($xoopsUser) ? 0 : $xoopsUser->uid());
		$post->setDate(time());
		$post->setModDate(time());
		$post->setText($texto);
		$post->setComments(0);
		$post->addToCategos($editorcategos);
		$post->setAllowPings(1);
		$post->setExcerpt(isset($excerpt) ? $excerpt : '');
		$post->setHTML(isset($dohtml) ? 1 : 0);
		$post->setXCode(isset($doxcode) ? 1 : 0);
		$post->setBR(isset($dobr) ? 1 : 0);
		$post->setDoImage(isset($doimage) ? 1 : 0);
		$post->setSmiley(isset($dosmiley) ? 1 : 0);
		$post->setAuthor($xoopsUser ? $xoopsUser->uid() : 0);
		$post->setAuthorName($xoopsUser ? $xoopsUser->uname() : _MS_MW_ANONYMOUS);
		
		if (empty($xoopsUser)){
			$post->setApproved($mc['aproveano']);
		} else {
			$post->setApproved($editor || $xoopsUser->isAdmin() ? 1 : $mc['aproveuser']);
		}
		
		if ($op=='save' || $op=='saveret'){
			$post->setStatus(0);
		} else {
			$post->setStatus(1);
		}
		$post->setTrackBacks($trackbacks);
		
		if ($op!='saveret'){
			$redirect = mw_get_url();
		} elseif ($op=='saveret') {
			$redirect = mw_get_url() . $mc['permalinks']==1 ? '?edit='.$post->getID() : 'edit/'.$post->getID();
		}
		
		if ($post->save()){
			if (!empty($xoopsUser)) $xoopsUser->incrementPost();
			redirect_header($redirect, 1, _MS_MW_DBOK);
		} else {
			redirect_header('?submit', 2, _MS_MW_DBERROR);
		}
		
		break;
	default:
		
		include_once XOOPS_ROOT_PATH.'/rmcommon/form.class.php';
		$form = new RMForm($edit ? sprintf(_MS_MW_EDITINGTITLE, $post->getTitle()) : _MS_MW_FORMTITLE, 'frmSubmit', mw_get_url().'?submit');
		$form->tinyCSS(MW_URL.'/styles/editor.css');
		$form->addElement(new RMText(_MS_MW_POSTTITLE, 'titulo', 50, 255, $edit ? $post->getTitle() : ''), true);
		if ($edit) $form->addElement(new RMText(_MS_MW_POSTFRIEND, 'titulo_amigo', 50, 255, $post->getFriendTitle()), true);
		$ele = new RMEditor(_MS_MW_CONTENT, 'texto', '90%','350px',$edit ? $post->getVar('texto','e') : '',$xoopsConfig['editor_type']);
		$ele->editorFormAction(MW_URL.'/?submit');
		$ele->setTheme('advanced');
		$form->addElement($ele, true);
		$form->addElement(new RMTextOptions(_OPTIONS, 1,0,0,1,0));
		$ele = new RMSelect(_MS_MW_POSTCATS, 'categos[]', 1);
		$ele->setRows(10);
		$ele->setExtra('style="min-width: 300px;"');
		$categos = array();
		arrayCategos($categos);
		if ($edit) $postCats = $post->categos();
		foreach ($categos as $k){
			$catObject = new MWCategory();
			$catObject->assignVars($k);
			if (!$catObject->userCanWrite()) continue;
			if ($edit){
				$estado = in_array($k['id_cat'], $postCats) ? 1: 0;
			} else {
				$estado = $k['id_cat']==1 ? 1 : 0;
			}
			$ele->addOption($k['id_cat'], str_repeat("-", $k['saltos']) . " " . $k['nombre'], $estado);
		}
		$form->addElement($ele, true, "Select:0");
		
		$ele = new RMText(_MS_MW_SENDTRACKS, 'trackbacks', 60, 600, $edit ? $post->getTrackBacks(false) : '');
		$ele->setDescription(_MS_MW_TRACKSDESC);
		$form->addElement($ele);
		$ele = new RMTextArea(_MS_MW_EXCERPT, 'excerpt', 5, 65, $edit ? ($post->getExcerpt()!='' ? $post->getExcerpt() : $util->filterTags(substr($post->getText(), 0, 400), array('br'=>array()))) : '');
		$ele->setDescription(_MS_MW_EXCERPTDESC);
		$form->addElement($ele);
		
		if ($edit){
			$form->addElement(new RMSubTitle(_MS_MW_OPTIONALDATA, 1));
			$form->addElement(new RMLabel(_MS_MW_TRACKSPINGED, $post->getPinged(false)!='' ? "<ul><li>".str_replace(" ", "</li><li>", $post->getPinged(false))."</li></ul>" : ''));		
			$ele = new RMRadio(_MS_MW_STATUS, 'estado');
			if ($xoopsUser && $xoopsUser->isAdmin()){
				$ele->addOption(_MS_MW_PRIVATE, 0, $post->getStatus()==0 ? 1 : 0);
				$ele->addOption(_MS_MW_PUBLIC, 1, $post->getStatus()==1 ? 1 : 0);
			}
			$form->addElement($ele);
			$form->addElement(new RMHidden('post',$post->getID()));
		}
		
		$ele = new RMSecurityCode(_MS_MW_SECURITYCODE, 'codigo', 5, 30, 5,'document.forms[\'frmSubmit\'].elements[\'op\'].value=\'\'; document.forms[\'frmSubmit\'].submit();');
		$form->AddElement($ele, true);
		
		$ele = new RMButtonGroup();
		if ($xoopsUser!='') if (!$edit) $ele->addButton('saveret', _MS_MW_SAVEANDRETURN, 'submit');
		if ($xoopsUser!='') $ele->addButton('onlysave', _MS_MW_SAVE, 'submit');
		if (!$edit) $ele->addButton('publish', _MS_MW_BUTPUBLISH, 'submit');
		if (!$edit) $ele->setExtra('saveret', 'onclick="document.forms[\'frmSubmit\'].op.value=\''.($edit ? "saveretedit" : "saveret").'\';"');
		$ele->setExtra('onlysave', 'onclick="document.forms[\'frmSubmit\'].op.value=\''.($edit ? 'saveedit' : 'save').'\';"');
		if (!$edit) $ele->setExtra('publish', 'onclick="document.forms[\'frmSubmit\'].op.value=\''.($edit ? 'publishedit' : 'publish').'\';"');
		
		$form->addElement($ele);
		$form->addElement(new RMHidden('op', $edit ? 'publishedit' : 'publish'));
		
		$tpl->assign('form_submit', $form->render());
		
		$xmh .= "\n<script type='text/javascript' src='".MW_URL."/include/functions.js'></script>";
		include 'footer.php';
}

?>