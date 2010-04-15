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
	global $db, $xoopsModule, $tpl, $xoopsSecurity;


	$sql = "SELECT * FROM ".$db->prefix('pw_types');
	$result = $db->query($sql);
	while($row = $db->fetchArray($result)){
		$type = new PWType();
		$type->assignVars($row);

		$types[] = array('id'=>$type->id(),'type'=>$type->type());
	}

	PWFunctions::toolbar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Customer types','admin_works'));
	RMTemplate::get()->assign('xoops_pagetitle',__('Customer types','admin_works'));
	RMTemplate::get()->add_style('admin.css','works');
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	RMTemplate::get()->add_script('../include/js/admin_works.js');
	RMTemplate::get()->add_head("<script type='text/javascript'>\nvar pw_message='".__('Do you really want to delete selected types?','admin_works')."';\n
		var pw_select_message = '".__('You must select some type before to execute this action!','admin_works')."';</script>");
	xoops_cp_header();
    include RMTemplate::get()->get_template("admin/pw_types.php",'module','works');
	xoops_cp_footer();

}

/**
* @desc Formulario de creación/edición de Tipos de cliente
**/
function formTypes($edit = 0){

	global $tpl, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;

	if ($edit){
		//Verificamos si nos proporcionaron al menos un tipo para editar
		if (!is_array($ids)){
			redirectMsg('./types.php',__('You must provide a type ID at least','admin_works'),1);
			die();		
		}
	

		if (!is_array($ids)){
			$ids = array($ids);
		}
	}

	PWFunctions::toolbar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; <a href='./types.php'>".__('Customer types','admin_works')."</a> &raquo; ".($edit ? __('Edit type','admin_works') : __('New type','admin_works')));
	RMTemplate::get()->assign('xoops_pagetitle',__('Add Customers types','admin_works'));
	xoops_cp_header();

	$form = new RMForm($edit ? __('Edit Type','admin_works') : __('New Type','admin_works'),'frmtype','types.php');
	
	$num = 10;	
	if ($edit){
		foreach ($ids as $k){
			//Verificamos si el tipo es válido
			if ($k<=0) continue;	

			//Verificamos si el tipo existe
			$type = new PWType($k);
			if ($type->isNew()) continue;

			$form->addElement(new RMFormText(__('Type name','admin_works'),'type['.$type->id().']',50,100,$edit ? $type->type() : ''));


		}


	}else{
		for ($i=1; $i<=$num; $i++){
			$form->addElement(new RMFormText(__('Type name','admin_works'),'type['.$i.']',50,100,$edit ? '' : ''));
		}
	}

	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));

	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt', $edit ? __('Save Changes','admin_works') : __('Save Customer Types','admin_works'), 'submit');
	$ele->addButton('cancel', __('Cancel', 'admin_works'), 'button', 'onclick="window.location=\'types.php\';"');
	$form->addElement($ele);


	$form->display();

	xoops_cp_footer();

}

/**
* @desc Almacena la información de los tipos en la base de datos
**/
function saveTypes($edit = 0){

	global $xoopsSecurity, $db;


	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	
	if (!$xoopsSecurity->check()){
		redirectMsg('./types.php'.($edit ? '?op=edit&ids='.$ids : ''), __('Session token expired!','admin_works'), 1);
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
				$errors .= sprintf(__('Another type with same name already exists!','admin_works'),$v);
				continue;
			}

		}else{
			//Verificamos si ya existe el nombre del tipo
			$tp = new PWType($v);
			if (!$tp->isNew()){
				$errors .= sprintf(__('Another type with same name already exists!','admin_works'),$v);
				continue;
			}

		}
		
		$tp->setType($v);
		$tp->isNew() ? $tp->setCreated(time()) : '';
		if (!$tp->save()){
			$errors .= sprintf(__('Type "%s" could not be saved!','admin_works'), $v);
		}
	}

	if ($errors!=''){
		redirectMsg('./types.php',__('Errors ocurred while saving types','admin_works').$errors,1);
		die();
	}else{
		redirectMsg('./types.php',__('Types added successfully!','admin_works'),0);
		die();
	}
	
}

/**
* @desc Elimina los tipos de cliente porporcionados
**/
function deleteTypes(){

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
