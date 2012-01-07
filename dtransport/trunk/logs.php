<?php
// $Id$
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
include '../../mainfile.php';

$mc=& $xoopsModuleConfig;


/**
* @desc Visualiza los logs existentes y formulario de creacion/edicion
**/
function logs($edit=0){

	global $xoopsOption,$db,$tpl,$xoopsUser;
	
	$xoopsOption['template_main'] = 'dtrans_createlogs.html';
	$xoopsOption['module_subpage'] = 'logs';

	include('header.php');
	DTFunctionsHandler::makeHeader();

	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

	//Verificamos si el software es válido
	if ($item<=0){
		redirect_header('./logs.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./logs.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}

	//Verificamos si el usuario es el propietario de la descarga
	if ($xoopsUser->uid()!=$sw->uid()){
		redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERRUSER);
		die();
	}


	
	//Lista de logs
	$sql ="SELECT * FROM ".$db->prefix('dtrans_logs')." WHERE id_soft=$item";
	$result=$db->queryF($sql);
	while($rows=$db->fetchArray($result)){
		$log = new DTLog();
		$log->assignVars($rows);

		$tpl->append('logs',array('id'=>$log->id(),'title'=>$log->title(),'log'=>substr($util->filterTags($log->log()),0,80)."...",
		'date'=>date($xoopsConfig['datestring'],$log->date())));
	
	}

	$tpl->assign('lang_exist',sprintf(_MS_DT_EXIST,$sw->name()));
	$tpl->assign('lang_id',_MS_DT_ID);
	$tpl->assign('lang_title',_MS_DT_TITLE);
	$tpl->assign('lang_log',_MS_DT_LOG);
	$tpl->assign('lang_date',_MS_DT_DATE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('item',$item);
	$tpl->assign('parent','logs');
	$tpl->assign('lang_createlog',_MS_DT_NEWLOG);
	$tpl->assign('lang_deletelog',_MS_DT_DELETELOG);
	$tpl->assign('lang_deletelogs',_MS_DT_DELETELOGS);
	$tpl->assign('edit',$edit);


	/**
	* Formulario de logs
	**/
	if ($edit){
		//Verificamos si log es válido
		if ($id<=0){
			redirect_header('./logs.php?item='.$item,2,_MS_DT_ERRLOGVALID);
			die();
		}

		//Verificamos si log existe
		$log=new DTLog($id);
		if ($log->isNew()){
			redirect_header('./logs.php?item='.$item,2,_MS_DT_ERRLOGEXIST);
			die();
		}

	}


	$form =new RMForm($edit ? sprintf(_MS_DT_EDITLOGS,$sw->name()) : sprintf(_MS_DT_NEWLOGS,$sw->name()),'frmlog','logs.php');

	$form->addElement(new RMLabel(_MS_DT_SOFTWARE,$sw->name()));
	$form->addElement(new RMText(_MS_DT_TITLE,'title',50,100,$edit ? $log->title() : ''),true);
	$form->addElement(new RMEditor(_MS_DT_LOG,'log','50%','350px',$edit ? $log->log() : '',$xoopsConfig['editor_type']),true);
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

	$tpl->assign('form_logs',$form->render());



	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; <a href='".DT_URL."/mydownloads.php'>";
	$location .= _MS_DT_MYDOWNS."</a> &raquo; "._MS_DT_LOGS;
	$tpl->assign('dt_location', $location);

	$xmh.= "<script type='text/javascript'>\n
	function formLog(){
		if ($('dtFormLog').style.display=='block'){\n
			$('dtFormLog').style.display='none';\n
		}else{\n
				$('dtFormLog').style.display='block';\n
			}\n
		}\n
	</script>";

	include('footer.php');
}


/**
* @desc Almacena los datos del log en la base de datos
**/
function saveLogs($edit=0){

	
	foreach($_POST as $k=>$v){
		$$k=$v;
	}


	//Verificamos si el software es válido
	if ($item<=0){
		redirect_header('./logs.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./logs.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}

	if ($edit){
		//Verificamos si log es válido
		if ($id<=0){
			redirect_header('./logs.php?item='.$item,2,_MS_DT_ERRLOGVALID);
			die();
		}

		//Verificamos si log existe
		$lg=new DTLog($id);
		if ($lg->isNew()){
			redirect_header('./logs.php?item='.$item,2,MS_DT_ERRLOGEXIST);
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
			redirect_header('./logs.php?op=new&id='.$id.'item='.$item,2,_MS_DT_DBERROR);
			die();
		}else{
			redirect_header('./logs.php?op=edit&id='.$id.'item='.$item,2,_MS_DT_DBERROR);
			die();
		}
	}else{
		redirect_header('./logs.php?item='.$item,1,_MS_DT_DBOK);
		die();
	}

}



/**
* @desc Elimina el Log especificado
**/
function deleteLogs(){
	global $xoopsModule;

	$ids=isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	

	//Verificamos si el software es válido
	if ($item<=0){
		redirect_header('./logs.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./logs.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}

	//Verificamos si nos proporcionaron algun log
	if (!is_array($ids) && $ids<=0){
		redirect_header('./logs.php?item='.$item,2,_MS_DT_NOTLOG);
		die();	
	}

	
	if (!is_array($ids)){
		$ids=array($ids);
	}

	$errors='';
	foreach ($ids as $k){

			
		//Verificamos si log es válido
		if ($k<=0){
			$errors.=sprintf(_MS_DT_ERRLOGVAL,$k);
			continue;
		}

		//Verificamos si log existe
		$lg=new DTLog($k);
		if ($lg->isNew()){
			$errors.=sprintf(_MS_DT_ERRLOGEX,$k);
			continue;
		}

		if (!$lg->delete()){
			$errors.=sprintf(_MS_DT_ERRLOGDEL,$k);
		}
	}

		
	if ($errors!=''){
		redirect_header('./logs.php?item='.$item,2,_MS_DT_DBERROR."<br />".$errors);
		die();
	}else{
		redirect_header('./logs.php?item='.$item,1,_MS_DT_DBOK);
		die();
	}


}


$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'edit':
		logs(1);
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
		logs();
}
?>
