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
// @author: gina
// @copyright: 2007 - 2008 Red México



define('BB_LOCATION', 'purge');
include 'header.php';



/**
* @desc Permitirá al administrador elegir los temas que serán 
* eliminados despues de un cierto período
**/
function formPurge(){

	global $db,$xoopsModule;
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_EXMBB_PURGE);
	xoops_cp_header();

	$form=new RMForm(_AS_EXMBB_PURGE,'frmpurge','purge.php');
	
	//Lista de foros
	$ele=new RMSelect(_AS_EXMBB_FORUMS,'forums');
	$ele->addOption('',_SELECT);
	$ele->addOption(0,_AS_EXMBB_ALLFORUMS);
	$sql="SELECT id_forum,name FROM ".$db->prefix('exmbb_forums');	
	$result=$db->queryF($sql);
	while ($row=$db->fetchArray($result)){
		$ele->addOption($row['id_forum'],$row['name']);
	}
	$form->addElement($ele,true);

	//Dias de antigüedad de temas
	$days=new RMText(_AS_EXMBB_DAYSOLD,'days',3,3);
	$days->setDescription(_AS_EXMBB_DESCDAYSOLD);
	$form->addElement($days,true);

	//Lista de opciones para purgar temas
	$opc=new RMSelect(_AS_EXMBB_DELETE,'option');
	$opc->addOption('',_SELECT);
	$opc->addOption(1,_AS_EXMBB_ALLTOPICS);
	$opc->addOption(2,_AS_EXMBB_TOPICSUNANSWERED);
	
	$form->addElement($opc,true);

	//Temas fijos
	$form->addElement(new RMYesno(_AS_EXMBB_TOPICSFIXED,'fixed'));
	
	
	$buttons= new RMButtonGroup();
	$buttons->addButton('sbt', _SUBMIT, 'submit', 'onclick="return confirm(\''._AS_EXMBB_DELTOPICS.'\');"');
	$buttons->addButton('cancel', _CANCEL, 'button', 'onclick="history.go(-1);"');
	
	$form->addElement($buttons);
	
	$form->addElement(new RMHidden('op','deltopics'));

	$form->display();
	
	
	
   
	xoops_cp_footer();


}


/**
* @desc Elimina los temas especificados
**/
function deleteTopics(){
	global $db;	

	foreach ($_POST as $k=>$v){
		$$k=$v;

	}
	$util =& RMUtils::getInstance();
	if (!$util->validateToken()){
		redirectMsg('./purge.php',_AS_EXMBB_SESSINVALID,0);
		die();
	}

	$sql= "SELECT id_topic FROM ".$db->prefix('exmbb_topics')." WHERE ";
	$sql.=($forums==0 ? '' : "id_forum='$forums' "); //Determinamos de que foro se va a purgar temas	
	$sql.=($forums ? " AND date<".(time()-($days*86400)) : " date<".(time()-($days*86400))); //Determinamos los temas con los dias de antigüedad especificados
	$sql.=($option==2 ? " AND replies=0" : ''); //Determinamos los temas a eliminar
	$sql.=($fixed ? " AND sticky=1 " : '');//Temas fijos
	
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$topic=new BBTopic();
		$topic->assignVars($rows);
		
		$topic->delete();
	}
	
	redirectMsg('./purge.php',_AS_BB_DBOK,0);
	
}




$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op){
	case 'deltopics':
		deleteTopics();
	break;
	default:
		formPurge();
	
}
?>
