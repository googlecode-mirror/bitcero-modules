<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','customertypes');
include 'header.php';


/**
* @desc Visualiza todos los tipos de cliente
**/
function showTypes(){
	global $db, $xoopsModule, $tpl, $adminTemplate;


	$sql = "SELECT * FROM ".$db->prefix('pw_types');
	$result = $db->query($sql);
	while($row = $db->fetchArray($result)){
		$type = new PWType();
		$type->assignVars($row);

		$tpl->append('types',array('id'=>$type->id(),'type'=>$type->type()));
	}

	$tpl->assign('lang_exist',_AS_PW_EXIST);
	$tpl->assign('lang_id',_AS_PW_ID);
	$tpl->assign('lang_type',_AS_PW_TYPE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_delete',_DELETE);


	PWFunctions::toolbar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; "._AS_PW_TYPELOC);
	xoops_cp_header();
    include RMTemplate::get()->get_template("admin/pw_types.php",'module','works');
	xoops_cp_footer();

}

/**
* @desc Formulario de creación/edición de Tipos de cliente
**/
function formTypes($edit = 0){

	global $adminTemplate, $tpl, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;

	if ($edit){
		//Verificamos si nos proporcionaron al menos un tipo para editar
		if (!is_array($ids) && $ids<=0){
			redirectMsg('./types.php',_AS_PW_ERRTYPE,1);
			die();		
		}
	

		if (!is_array($ids)){
			$ids = array($ids);
		}
	}

	optionsBar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; <a href='./types.php'>"._AS_PW_TYPELOC."</a> &raquo; ".($edit ? _AS_PW_EDITTYPE : _AS_PW_NEWTYPE));
	xoops_cp_header();

	$form = new RMForm($edit ? _AS_PW_EDITTYPE : _AS_PW_NEWTYPE,'frmtype','types.php');
	
	$num = 10;	
	if ($edit){
		foreach ($ids as $k){
			//Verificamos si el tipo es válido
			if ($k<=0) continue;	

			//Verificamos si el tipo existe
			$type = new PWType($k);
			if ($type->isNew()) continue;

			$form->addElement(new RMText(_AS_PW_FNAME,'type['.$type->id().']',50,100,$edit ? $type->type() : ''));


		}


	}else{
		for ($i=1; $i<=$num; $i++){
			$form->addElement(new RMText(_AS_PW_FNAME,'type['.$i.']',50,100,$edit ? '' : ''));
		}
	}

	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));

	$ele = new RMButtonGroup();
	$ele->addButton('sbt', _AS_PW_SAVE, 'submit');
	$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'types.php\';"');
	$form->addElement($ele);


	$form->display();

	xoops_cp_footer();

}

/**
* @desc Almacena la información de los tipos en la base de datos
**/
function saveTypes($edit = 0){

	global $util, $db;


	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	
	if (!$util->validateToken()){
		redirectMsg('./types.php?op='.($edit ? 'edit&ids='.$ids : 'new'), _AS_PW_ERRSESSID, 1);
		die();
	}


	$errors = '';
	foreach ($type as $k => $v){
			
		if ($v=='') continue;

		if ($edit){
			$tp = new PWType($k);
			
			//Verificamos si ya existe el nombre del tipo
			$tpe = new PWType($v);
			if (!$tpe->isNew() && $tp->id()!=$tpe->id()){
				$errors .= sprintf(_AS_PW_ERRTYPENAME,$v);
				continue;
			}

		}else{
			//Verificamos si ya existe el nombre del tipo
			$tp = new PWType($v);
			if (!$tp->isNew()){
				$errors .= sprintf(_AS_PW_ERRTYPENAME,$v);
				continue;
			}

		}
		
		$tp->setType($v);
		$tp->isNew() ? $tp->setCreated(time()) : '';
		if (!$tp->save()){
			$errors .= sprintf(_AS_PW_ERRTYPESAVE, $v);
		}
	}

	if ($errors!=''){
		redirectMsg('./types.php',_AS_PW_DBERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./types.php',_AS_PW_DBOK,0);
		die();
	}
	
}

/**
* @desc Elimina los tipos de cliente porporcionados
**/
function deleteTypes(){

global $util, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;

	//Verificamos que nos hayan proporcionado un tipo para eliminar
	if (!is_array($ids) && ($ids<=0)){
		redirectMsg('./types.php',_AS_PW_ERRNOTTYPEDEL,1);
		die();
	}
	
	if (!is_array($ids)){
		$tp = new PWType($ids);
		$ids = array($ids);
	}


	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./types.php',_AS_PW_ERRSESSID, 1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){
			//Verificamos si el tipo sea válido
			if ($k<=0){
				$errors.=sprintf(_AS_PW_NOTVALID, $k);
				continue;
			}

			//Verificamos siel tipo existe
			$type = new PWType($k);
			if ($type->isNew()){
				$errors.=sprintf(_AS_PW_NOTEXIST, $k);
				continue;
			}
		
			if (!$type->delete()){
				$errors.=sprintf(_AS_PW_NOTDELETE,$k);
			}
		}
	
		if ($errors!=''){
			redirectMsg('./types.php',_AS_PW_DBERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./types.php',_AS_PW_DBOK,0);
			die();
		}


	}else{
		optionsBar();
		xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; 
		<a href='./types.php'>"._AS_PW_TYPELOC.'</a> &raquo; '._AS_PW_DELETE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['ids[]'] = $ids;
		$hiddens['op'] = 'delete';
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'types.php\';"';
		
		$util->msgBox($hiddens, 'types.php',($tp ? sprintf(_AS_PW_DELETECONF, $tp->type()) : _AS_PW_DELETECONFS). '<br /><br />' ._AS_PW_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}
	




}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch($op){
	case 'new':
		formTypes();
		break;
	case 'edit':
		formTypes(1);
		break;
	case 'save':
		saveTypes();
		break;
	case 'saveedit':
		saveTypes(1);
		break;
	case 'delete':
		deleteTypes();
		break;
	default:
		showTypes();

}
?>
