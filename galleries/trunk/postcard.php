<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// --------------------------------------------------------
// @copyright: 2008 Red México

$toget = 'id';
include 'include/parse.php';

define('GS_LOCATION','postcards');
include '../../mainfile.php';

if (!$exmModuleConfig['postcards']){
	redirect_header(XOOPS_URL.'/modules/galleries/', 1, _MS_GS_SRVDISABLED);
	die();
}

/**
* @desc Muestra el formulario para la creación de la postal
*/
function newPostcard(){
	global $exmUser, $exmModule, $exmModuleConfig, $mc, $tpl, $img, $xoopsOption;
	
	if (!$exmUser){
		redirect_header(XOOPS_URL.'/user.php#register', 1, _MS_GS_ERRUSR);
		die();
	}
	
	$image = new GSImage($img);
	if ($image->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries/', 1, _MS_GS_ERRIMG);
		die();
	}
	
	$xoopsOption['template_main'] = "gs_postcard_form.html";
	include 'header.php';
	
	//Eliminamos las postales que han cumplido si tiempo
	GSFunctions::deletePostcard();

	GSFunctions::makeHeader();
	$tpl->assign('xoops_pagetitle', sprintf(_MS_GS_NEWPOST, $image->title()));

	$postlink = GS_URL.'/'.($mc['urlmode'] ? 'postcard/new/img/'.$image->id().'/' : GS_URL.'/postcard.php?id=postcard/new/img/'.$image->id());
	$sendlink = str_replace('/new/','/send/',$postlink);
	$form = new RMForm(_MS_GS_NEWTITLE, 'frmNewPostcard', $sendlink);
	$form->addElement(new RMText(_MS_GS_YNAME, 'fname', 50, 100, $exmUser->getVar('name')), true);
	$form->addElement(new RMText(_MS_GS_YEMAIL, 'fmail', 50, 150, $exmUser->getVar('email')), true, 'email');
	$form->addElement(new RMText(_MS_GS_TNAME, 'tname', 50, 100, ''), true);
	$form->addElement(new RMText(_MS_GS_TEMAIL, 'tmail', 50, 150, ''), true, 'email');
	$form->addElement(new RMText(_MS_GS_TITLE, 'title', 50, 150, $image->title(false)), true);
	$form->addElement(new RMTextArea(_MS_GS_MESSAGE, 'msg', 0,0,'','90%','150px'), true);
	$ele = new RMSecurityCode(_MS_GS_CODE, 'code', 5);
	$ele->setDescription(_MS_GS_CODE_DESC);
	$ele->setRefreshAction("\$('frmNewPostcard').action='$postlink';\$('frmNewPostcard').submit();");
	$form->addElement($ele, true);
	$ele = new RMButtonGroup();
	$ele->addButton('sbt', _SUBMIT, 'submit','onclick="$(\'op\').value=\'send\';"');
	$previewlink = str_replace('/new/','/preview/',$postlink);
	$ele->addButton('preview', _MS_GS_PREVIEW, 'button','onclick="$(\'frmNewPostcard\').action=\''.$previewlink.'\'; $(\'sbt\').click();"');
	$form->addElement($ele);
	$form->addElement(new RMHidden('op','send'));
	$form->addElement(new RMHidden('img',$image->id()));
	$form->addElement(new RMHidden('uid',$exmUser->uid()));
	$form->addElement(new RMHidden('return',base64_encode($postlink)));
	
	$tpl->assign('postcard_form', $form->render());
	
	include 'footer.php';
	
}

/**
* @desc Muestra una previsualización de la postal
*/
function previewPostcard(){
	global $tpl, $exmModule, $exmModuleConfig, $xoopsModuleConfig, $mc, $exmUser;
	
	$tpl = new XoopsTpl();
	$mc =& $exmModuleConfig;
	__autoload('RMForm');
	
	foreach ($_POST as $k => $v){
		if ($k=='sbt'||$k=='op') continue;
		$$k = $v;
		$ele = new RMHidden($k,$v);
		$tpl->append('hiddens',$ele->render());
	}
	
	if (!$exmUser){
		redirect_header(XOOPS_URL.'/user.php#register', 1, _MS_GS_ERRUSR);
		die();
	}
	
	$img = new GSImage($img);
	if ($img->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries/', 1, _MS_GS_ERRIMG);
		die();
	}
	
	$user = new GSUser($img->owner(), 1);
	$file = $user->filesPath().'/'.$img->image();
	list($ancho,$alto) = getimagesize($file);
	
	$tpl->assign('gs_url', XOOPS_URL.'/modules/galleries');
	$tpl->assign('img', array('id'=>$img->id(),'width'=>$ancho,'height'=>$alto,
			'url'=>$user->filesURL().'/'.$img->image(),'link'=>$user->userURL().'img/'.$img->id().'/'));
	$tpl->assign('title', $title);
	$tpl->assign('message', $msg);
	$tpl->assign('lang_says', sprintf(_MS_GS_SAYS, $fname));
	$tpl->assign('xoops_pagetitle', sprintf(_MS_GS_PTITLE, $title).' &raquo; '.$mc['section_title']);
	$tpl->assign('lang_see', _MS_GS_SEE);
	$tpl->assign('user_link', $user->userURL().'browse/'.$img->id().'/');
	$tpl->assign('lang_seeuser', _MS_GS_SEEUSER);
	$tpl->assign('preview', 1);
	$tpl->assign('lang_submit', _SUBMIT);
	$tpl->assign('lang_edit', _EDIT);
	$tpl->assign('postcard_link', base64_decode($return));
	$sendlink = XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? '' : 'postcard.php?id=').'postcard/send/img/'.$img->id();
	$tpl->assign('send_link',$sendlink);
	
		
	echo $tpl->fetch("db:gs_postcard.html");
	
}

/**
* @desc Envia la postal
*/
function sendPostcard(){
	global $tpl, $exmModule, $exmModuleConfig, $xoopsModuleConfig, $mc, $exmUser, $xoopsConfig,$util;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$exmUser){
		redirect_header(XOOPS_URL.'/user.php#register', 1, _MS_GS_ERRUSR);
		die();
	}
	
	$img = new GSImage($img);
	if ($img->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries/', 1, _MS_GS_ERRIMG);
		die();
	}
	
	$util =& RMUtils::getInstance();
	if (!$util->checkSecurityCode($code)){
		redirect_header(base64_decode($return), 2, _MS_GS_ERRCODE);
		die();
	}
	
	$user = new GSUser($img->owner(), 1);
	
	$post = new GSPostcard();
	$post->setTitle($title);
	$post->setMessage($msg);
	$post->setDate(time());
	$post->setToName($tname);
	$post->setToEmail($tmail);
	$post->setImage($img->id());
	$post->setName($fname);
	$post->setEmail($fmail);
	$post->setUid($uid);
	$post->setIp($_SERVER['REMOTE_ADDR']);
	$post->setViewed(0);

	//Generamos el código de la postal
	$post->setCode($util->randomString(5));

	if (!$post->save()){
		redirect_header(base64_decode($return), 2, _MS_GS_ERRSEND);
		die();
	}
	
	$xoopsMailer =& getMailer();
	$xoopsMailer->useMail();
	$xoopsMailer->setTemplate('postcard.tpl');
	$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
	$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
	$xoopsMailer->assign('FNAME', $fname);
	$xoopsMailer->assign('FMAIL', $fmail);
	$xoopsMailer->assign('TNAME', $tname);
	$xoopsMailer->assign('MODULE_LINK', XOOPS_URL.'/modules/galleries/');
	$xoopsMailer->assign('POSTAL_LINK', XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? 'postcard/view/id/'.$post->code().'/' : 'postcard.php?id=postcard/view/id/'.$post->code()));
	$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/galleries/language/".$xoopsConfig['language']."/");
	$xoopsMailer->setFromEmail($fmail);
	$xoopsMailer->setFromName($fname);
	$xoopsMailer->setToEmails($tmail);
	$xoopsMailer->setSubject(sprintf(_MS_GS_SUBJECT, $tname));
	if (!$xoopsMailer->send(true)){
		redirect_header(base64_decode($return),2,$xoopsMailer->getErrors());
	} else {
		redirect_header($user->userURL().'img/'.$img->id().'/',1,_MS_GS_SENDOK);
	}
	
}


/**
* @desc Visualiza la postal
**/
function viewPostcard(){

	global $tpl, $exmModule, $exmModuleConfig, $xoopsModuleConfig, $mc, $exmUser, $id;
	
	$tpl = new XoopsTpl();
	$mc =& $exmModuleConfig;
		
	if (!$exmUser){
		redirect_header(XOOPS_URL.'/user.php#register', 1, _MS_GS_ERRUSR);
		die();
	}

	//Verificamos si la postal existe
	$post = new GSPostcard($id);
	if($post->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries/',1,_MS_GS_ERRPOSTEXIST);
		die();
	}

	
	$img = new GSImage($post->image());
	if ($img->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries/', 1, _MS_GS_ERRIMG);
		die();
	}
	
	$user = new GSUser($img->owner(), 1);
	$file = $user->filesPath().'/'.$img->image();
	list($ancho,$alto) = getimagesize($file);
	
	$tpl->assign('gs_url', XOOPS_URL.'/modules/galleries');
	$tpl->assign('img', array('id'=>$img->id(),'width'=>$ancho,'height'=>$alto,
			'url'=>$user->filesURL().'/'.$img->image(),'link'=>$user->userURL().'img/'.$img->id().'/'));
	$tpl->assign('title', $post->title());
	$tpl->assign('message', $post->message());
	$tpl->assign('lang_says', sprintf(_MS_GS_SAYS, $post->name()));
	$tpl->assign('xoops_pagetitle', sprintf(_MS_GS_PTITLE, $post->title()).' &raquo; '.$mc['section_title']);
	$tpl->assign('lang_see', _MS_GS_SEE);
	$tpl->assign('user_link', $user->userURL().'browse/'.$img->id().'/');
	$tpl->assign('lang_seeuser', _MS_GS_SEEUSER);
	$tpl->assign('preview', 0);

	//Actualizar datos de postal
	$post->setViewed(1);
	$post->save();
			
	echo $tpl->fetch("db:gs_postcard.html");
}


$postcard = !isset($postcard) ? '' : $postcard;

switch($postcard){
	case 'preview':
		previewPostcard();
		break;
	case 'send':
		sendPostcard();
		break;
	case 'view':
		viewPostcard();
		break;
	default:
		newPostcard();
		break;
}

?>
