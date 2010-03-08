<?php
// $Id$
// --------------------------------------------------------
// The Coach
// Manejo de Integrantes de Equipos Deportivos
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
	$form->addElement(new RMText(_MS_TC_FNAME, 'name', 50, 150), true);
	$form->addElement(new RMText(_MS_TC_FEMAIL, 'email', 50, 150, $xoopsUser ? $xoopsUser->getVar('email') : ''), true, 'email');
	$form->addElement(new RMTextArea(_MS_TC_FCOMMENT, 'comment',10, 60, ''), true);
	$form->addElement(new RMSecurityCode(_MS_TC_FCODE, 'code', '4'));
	$form->addElement(new RMButton('sbt', _SUBMIT, 'submit'));
	$form->addElement(new RMHidden('op','send'));
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

?>