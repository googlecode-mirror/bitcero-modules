<?php
// $Id: reports.php 861 2011-12-19 02:38:22Z i.bitcero $
// --------------------------------------------------------------
// bXpress Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


define('RMCLOCATION', 'prune');
include 'header.php';

/**
* @desc Permitirá al administrador elegir los temas que serán 
* eliminados despues de un cierto período
**/
function prune(){

	global $xoopsModule;
        
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Prune Posts','bxpress'));
	xoops_cp_header();
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
	$form=new RMForm(__('Prune Posts','bxpress'),'frmprune','prune.php');
	
	//Lista de foros
	$ele=new RMFormSelect(__('Prune from forum','bxpress'),'forums');
	$ele->addOption('',_SELECT);
	$ele->addOption(0,_AS_EXMBB_ALLFORUMS);
	$sql="SELECT id_forum,name FROM ".$db->prefix('exmbb_forums');	
	$result=$db->queryF($sql);
	while ($row=$db->fetchArray($result)){
		$ele->addOption($row['id_forum'],$row['name']);
	}
	$form->addElement($ele,true);

	//Dias de antigüedad de temas
	$days=new RMFormText(_AS_EXMBB_DAYSOLD,'days',3,3);
	$days->setDescription(_AS_EXMBB_DESCDAYSOLD);
	$form->addElement($days,true);

	//Lista de opciones para purgar temas
	$opc=new RMFormSelect(_AS_EXMBB_DELETE,'option');
	$opc->addOption('',_SELECT);
	$opc->addOption(1,_AS_EXMBB_ALLTOPICS);
	$opc->addOption(2,_AS_EXMBB_TOPICSUNANSWERED);
	
	$form->addElement($opc,true);

	//Temas fijos
	$form->addElement(new RMFormYesno(_AS_EXMBB_TOPICSFIXED,'fixed'));
	
	
	$buttons= new RMFormButtonGroup();
	$buttons->addButton('sbt', _SUBMIT, 'submit', 'onclick="return confirm(\''._AS_EXMBB_DELTOPICS.'\');"');
	$buttons->addButton('cancel', _CANCEL, 'button', 'onclick="history.go(-1);"');
	
	$form->addElement($buttons);
	
	$form->addElement(new RMFormHidden('action','deltopics'));

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
            prune();
	
}
