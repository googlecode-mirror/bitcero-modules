<?php
// $Id: logs.php 37 2008-03-03 18:46:45Z BitC3R0 $
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
// http://www.redmexico.com.mx
// http://www.exmsystem.com
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

define('DT_LOCATION','logs');
include ('header.php');

/**
* @desc Muestra la barra de menus
*/
function optionsBar(){
    global $tpl;
    $item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0; 

    $tpl->append('xoopsOptions', array('link' => './logs.php?item='.$item, 'title' => _AS_DT_LOGS, 'icon' => '../images/logs16.png'));
    if ($item){
	    $tpl->append('xoopsOptions', array('link' => './logs.php?op=new&item='.$item, 'title' => _AS_DT_NEWLOG, 'icon' => '../images/add.png'));
    }
}

/**
* @desc Visualiza todos los logs existentes para un determinado software
**/
function showLogs(){
	global $db,$tpl,$adminTemplate,$util,$xoopsConfig,$xoopsModule;

	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$sw=new DTSoftware($item);

	$sql ="SELECT * FROM ".$db->prefix('dtrans_logs')." WHERE id_soft=$item";
	$result=$db->queryF($sql);
	while($rows=$db->fetchArray($result)){
		$log = new DTLog();
		$log->assignVars($rows);

		$tpl->append('logs',array('id'=>$log->id(),'title'=>$log->title(),'log'=>substr($util->filterTags($log->log()),0,80)."...",
		'date'=>date($xoopsConfig['datestring'],$log->date())));
	
	}

	$tpl->assign('lang_exist',sprintf(_AS_DT_EXIST,$sw->name()));
	$tpl->assign('lang_id',_AS_DT_ID);
	$tpl->assign('lang_title',_AS_DT_TITLE);
	$tpl->assign('lang_log',_AS_DT_LOG);
	$tpl->assign('lang_date',_AS_DT_DATE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_listsoft',_AS_DT_LSOFT);
	$tpl->assign('lang_selectitem',_AS_DT_SELECTITEM);
	$tpl->assign('item',$item);
	$tpl->assign('parent','logs');
	$tpl->assign('token',$util->getTokenHTML());


	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>".sprintf(_AS_DT_SW,$sw->name())."</a> &raquo; "._AS_DT_LOGS);
	$adminTemplate = 'admin/dtrans_logs.html';
	xoops_cp_header();
	xoops_cp_footer();

}


/**
* @desc Formulario de Logs
**/
function formLogs($edit=0){
	global $xoopsModule,$xoopsConfig;

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;

	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('./logs.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./logs.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	if ($edit){
		//Verificamos si log es válido
		if ($id<=0){
			redirectMsg('./logs.php?item='.$item,_AS_DT_ERRLOGVALID,1);
			die();
		}

		//Verificamos si log existe
		$log=new DTLog($id);
		if ($log->isNew()){
			redirectMsg('./logs.php?item='.$item,_AS_DT_ERRLOGEXIST,1);
			die();
		}

	}
	
	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>".sprintf(_AS_DT_SW,$sw->name())."</a> &raquo; ".($edit ? _AS_DT_EDITLOG : _AS_DT_NEWLOG));
	xoops_cp_header();

	$form =new RMForm($edit ? sprintf(_AS_DT_EDITLOGS,$sw->name()) : sprintf(_AS_DT_NEWLOGS,$sw->name()),'frmlog','logs.php');

	$form->addElement(new RMLabel(_AS_DT_SOFTWARE,$sw->name()));
	$form->addElement(new RMText(_AS_DT_TITLE,'title',50,100,$edit ? $log->title() : ''),true);
	$form->addElement(new RMEditor(_AS_DT_LOG,'log','90%','350px',$edit ? $log->getVar('log','e') : '',$xoopsConfig['editor_type']),true);
	if ($edit){
		$dohtml = $log->getVar('dohtml');
		$dobr = $log->getVar('dobr');
		$doimage = $log->getVar('doimage');
		$dosmiley = $log->getVar('dosmiley');
		$doxcode = $log->getVar('doxcode');
	} else {
		$dohtml = 1;
		$dobr = 0;
		$doimage = 0;
		$dosmiley = 0;
		$doxcode = 0;
	}
	$form->addElement(new RMTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));
	
	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('item',$item));
	$form->addElement(new RMHidden('id',$id));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'logs.php?item='.$item.'\';"');

	$form->addElement($buttons);

	$form->display();

	xoops_cp_footer();
}


/**
* @desc Almacena los datos del log en la base de datos
**/
function saveLogs($edit=0){

	global $util;

	foreach($_POST as $k=>$v){
		$$k=$v;
	}


	if (!$util->validateToken()){
		redirectMsg('./logs.php?item='.$item,_AS_DT_SESSINVALID, 1);
		die();
	}

	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('./logs.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./logs.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	if ($edit){
		//Verificamos si log es válido
		if ($id<=0){
			redirectMsg('./logs.php?item='.$item,_AS_DT_ERRLOGVALID,1);
			die();
		}

		//Verificamos si log existe
		$lg=new DTLog($id);
		if ($lg->isNew()){
			redirectMsg('./logs.php?item='.$item,_AS_DT_ERRLOGEXIST,1);
			die();
		}

	}else{
		$lg=new DTLog();
	}
	
	$lg->setSoftware($item);
	$lg->setTitle($title);
	$lg->setLog($log);
	$lg->setDate(time());
	$lg->setVar('dohtml', isset($dohtml) ? 1 : 0);
	$lg->setVar('doxcode', isset($doxcode) ? 1 : 0);
	$lg->setVar('dobr', isset($dobr) ? 1 : 0);
	$lg->setVar('dosmiley', isset($dosmiley) ? 1 : 0);
	$lg->setVar('doimage', isset($doimage) ? 1 : 0);

	if (!$lg->save()){
		if ($lg->isNew()){
			redirectMsg('./logs.php?op=new&id='.$id.'item='.$item,_AS_DT_DBERROR,1);
			die();
		}else{
			redirectMsg('./logs.php?op=edit&id='.$id.'item='.$item,_AS_DT_DBERROR,1);
			die();
		}
	}else{
		redirectMsg('./logs.php?item='.$item,_AS_DT_DBOK,0);
		die();
	}


}


/**
* @desc Elimina el Log especificado
**/
function deleteLogs(){
	global $xoopsModule,$util;

	$ids=isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$ok=isset($_POST['ok']) ? intval($_POST['ok']) : 0;

	
	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('./logs.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./logs.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	//Verificamos si nos proporcionaron algun log
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./logs.php?item='.$item,_AS_DT_NOTLOG,1);
		die();	
	}

	$num=0;
	if (!is_array($ids)){
		$log=new DTLog($ids);
		$ids=array($ids);
		$num=1;
	}

	
	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./logs.php?item='.$item,_AS_DT_SESSINVALID, 1);
			die();
		}
		
		$errors='';
		foreach ($ids as $k){

			
			//Verificamos si log es válido
			if ($k<=0){
				$errors.=sprintf(_AS_DT_ERRLOGVAL,$k);
				continue;
			}

			//Verificamos si log existe
			$lg=new DTLog($k);
			if ($lg->isNew()){
				$errors.=sprintf(_AS_DT_ERRLOGEX,$k);
				continue;
			}

			if (!$lg->delete()){
				$errors.=sprintf(_AS_DT_ERRLOGDEL,$k);
			}

		}

		
		if ($errors!=''){
			redirectMsg('./logs.php?item='.$item,_AS_DT_ERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./logs.php?item='.$item,_AS_DT_DBOK,0);
			die();
		}
		
	}else{


		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>".sprintf(_AS_DT_SW,$sw->name())."</a> &raquo; "._AS_DT_DELETELOG);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id[]'] = $ids;
		$hiddens['item'] = $item;
		$hiddens['op'] = 'delete';

		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'logs.php?item='.$item.'\';"';
		
		$util->msgBox($hiddens, 'logs.php', ($num ? sprintf(_AS_DT_DELETECONF,$log->title()) : _AS_DT_DELCONF). '<br /><br />' ._AS_DT_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();
	}



}


$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'new':
		formLogs();
	break;
	case 'edit':
		formLogs(1);
	break;
	case 'save':
		saveLogs();
	break;
	case 'saveedit':
		saveLogs(1);
	break;
	case 'delete':
		deleteLogs();
	break;
	default:
		showLogs();
}
?>
