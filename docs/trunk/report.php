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


define('BB_LOCATION','report');
include '../../mainfile.php';
	
$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';


if ($op=='report'){
		
	$xoopsOption['template_main']='exmbb_report.html';
	$xoopsOption['module_subpage'] = "report";	

	include 'header.php';
	
	BBFunctions::makeHeader();
	//Id de mensaje
	$pid = isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;
	
	$post=new BBPost($pid);
	$forum=new BBForum($post->forum());
	$topic=new BBTopic($post->topic());


	$form=new RMForm(_MS_EXMBB_REPORT,'formrep','report.php');
	$form->styles('width: 30%;','odd');
	$form->addElement(new RMEditor(_MS_EXMBB_ADDREPORT,'report','90%','300px','','textarea'),true);
	$form->addElement(new RMHidden('op','savereport'));
	$form->addElement(new RMHidden('pid',$pid));
	$form->addElement(new RMHidden('id',$topic->id()));

	$buttons= new RMButtonGroup();
	$buttons->addButton('sbt', _SUBMIT, 'submit');
	$buttons->addButton('cancel', _CANCEL, 'button', 'onclick="history.go(-1);"');
	
	$form->addElement($buttons);

	$tpl->assign('report_contents', $form->render());
	$tpl->assign('forumtitle',$forum->name());
	$tpl->assign('topictitle',$topic->title());	
	$tpl->assign('forumid',$forum->id());
	$tpl->assign('topicid',$topic->id());
	$tpl->assign('report',_MS_EXMBB_REPORTPOST);


	include 'footer.php';

}elseif ($op=='savereport'){
		foreach ($_POST as $k=>$v){
			$$k=$v;
		}

		//Verificamos que el mensaje sea válido
		if ($pid<=0){
			redirect_header('./topic.php?id='.$id,1,_MS_EXMBB_POSTNOTVALID);
			die();
		}
		
		//Comprobamos que el mensaje exista
		$post=new BBPost($pid);
		if ($post->isNew()){
			redirect_header('./topic.php?id='.$id,1,_MS_EXMBB_POSTNOTEXIST);
			die();
		}
		
		
		$util =& RMUtils::getInstance();
		
		if (!$util->validateToken()){
			redirect_header('./topic.php?pid='.$pid.'#p'.$pid, 2, _MS_EXMBB_SESSINVALID);
			die();
		}
		
		$rep=new BBReport();
		$rep->setPost($pid);
		$rep->setUser($xoopsUser->uid());
		$rep->setIp($_SERVER['REMOTE_ADDR']);
		$rep->setTime(time());
		$rep->setReport($report);

		
		if ($rep->save()){
			redirect_header('./topic.php?id='.$id,1,_MS_EXMBB_SAVEREPORT);
		}
		else{
			redirect_header('./topic.php?id='.$id,1,_MS_EXMBB_SAVENOTREPORT);
		}

}

?>
