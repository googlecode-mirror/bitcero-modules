<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('TC_LOCATION','comments');
include '../../mainfile.php';

function showForm(){
	global $xoopsUser, $tpl, $xoopsOption, $xoopsModule, $xoopsModuleConfig;
	$xoopsOption['template_main'] = "coach_comments.html";
	include 'header.php';

	$tpl->assign('comment_text', $mc['comment']);
	$tpl->assign('coach_title', _MS_TC_PTITLE);
	$tpl->assign('lang_comment', _MS_TC_COMMENT);
	$tpl->assign('xoops_pagetitle', _MS_TC_PTITLE);
	$location = "<a href='".TC_URL."'>".$xoopsModule->name()."</a> &raquo; "._MS_TC_PTITLE;
	$tpl->assign('coach_location', $location);

	$form = new RMForm(_MS_TC_FTITLE, 'frmComment','comment.php');
	$form->addElement(new RMFormText(_MS_TC_FNAME, 'name', 50, 150), true);
	$form->addElement(new RMFormText(_MS_TC_FEMAIL, 'email', 50, 150, $xoopsUser ? $xoopsUser->getVar('email') : ''), true, 'email');
	$form->addElement(new RMFormTextArea(_MS_TC_FCOMMENT, 'comment',10, 60, ''), true);
	$form->addElement(new RMFormButton('sbt', _SUBMIT, 'submit'));
	$form->addElement(new RMFormHidden('op','send'));
	$tpl->assign('form', $form->render());

	include 'footer.php';
}

function sendComment(){
	global $xoopsUser, $xoopsModule, $xoopsModuleConfig, $mc, $xoopsConfig;
	$util =& RMUtils::getInstance();
	
	if (!$util->validateToken()){
		redirect_header('comment.php', 2, _MS_TC_ERRID);
		die();
	}
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if ($name=='' || $email=='' || $comment==''){
		redirect_header('comment.php', 2, _MS_TC_ERRFIELDS);
		die();
	}
	
	$xoopsMailer =& getMailer();
	$xoopsMailer->useMail();
	$xoopsMailer->setTemplate('mail.tpl');
	$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
	$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
	$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
	$xoopsMailer->assign('NAME', $name);
	$xoopsMailer->assign('EMAIL', $email);
	$xoopsMailer->assign('COMMENTS', $comment);
	$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/team/language/".$xoopsConfig['language']."/");
	$xoopsMailer->setFromEmail($email);
	$xoopsMailer->setFromName($name);
	$xoopsMailer->setSubject(sprintf(_MS_TC_COMFROM, $xoopsConfig['sitename'].": ".$xoopsModule->name()));
	$xoopsMailer->setToEmails($xoopsModuleConfig['email']);
	if (!$xoopsMailer->send(true)){
		redirect_header('comment.php',2,$xoopsMailer->getErrors());
	} else {
		redirect_header('./',1,_MS_TC_COMTHX);
	}
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'send':
		sendComment();
		break;
	default:
		showForm();
		break;
}
