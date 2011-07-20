<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','users');
include 'header.php';

/**
* @desc Visualiza todos los usuarios existentes
**/
function showUsers(){

	global $xoopsModule, $db, $tpl, $xoopsSecurity;

	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	$limit = 15;
	$search = rmc_server_var($_REQUEST, 'search', '');

	$db = Database::getInstance();
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_users');
	$sql1 = '';
	$search =trim($search);
	if ($search && strlen($search)>2){
			
		$sql1.= $sql1=='' ? " WHERE (uname LIKE '%$search%')" : " OR (uname LIKE '%$search%')";			

	}


	list($num)=$db->fetchRow($db->query($sql.$sql1));
	$start = $num<=0 ? 0 : ($page-1) * $limit;
    $tpages = ceil($num / $limit);
	$nav = new RMPageNav($num, $limit, $page, 5);
	$nav->target_url("users.php?page={PAGE_NUM}&amp;search=$search");

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	//Fin de barra de navegación

	$sql = "SELECT * FROM ".$db->prefix('gs_users');
	$sql2 = " LIMIT $start,$limit";
	$result = $db->query($sql.$sql1.$sql2);
    
	while($rows = $db->fetchArray($result)){
		$uname = eregi_replace("($search)","<span class='searchResalte'>\\1</span>", $rows['uname']);

		$user = new GSUser();
		$user->assignVars($rows);		

		$users[] = array(
			'id'=>$user->id(),
			'uid'=>$user->uid(),
			'uname'=>$uname,
			'quota'=>RMUtilities::formatBytesSize($user->quota()),
			'blocked'=>$user->blocked(),
			'used'=>GSFunctions::makeQuota($user),
			'pics'=>$user->pics(),
			'sets'=>$user->sets(),
			'date'=>formatTimeStamp($user->date(),'custom'),
			'url'=>$user->userUrl()
		);

	}
    
	GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Users management','admin_galleries'));
	RMTemplate::get()->assign('xoops_pagetitle', __('Users management','admin_galleries'));
	RMTemplate::get()->add_script('../include/js/gsscripts.php?file=sets&form=frm-users');
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	RMTemplate::get()->add_head("<script type='text/javascript'>\nvar delete_warning='".__('Do you really wish to delete selected users?','admin_galleries')."';\n</script>");
	xoops_cp_header();
	
	include RMTemplate::get()->get_template("admin/gs_users.php",'module','galleries');
	xoops_cp_footer();

}


/**
* @desc Formulario de creación/edición de usuarios
**/
function formUsers($edit = 0){

	global $xoopsModule, $xoopsModuleConfig;

	$mc =&$xoopsModuleConfig;

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&search=$search";

	if($edit){
		//Verificamos que el usuario sea válido
		if($id<=0){
			redirectMsg('./users.php?'.$ruta,__('User id is not valid!','admin_galleries'),1);
			die();
		}

		//Verificamos que el usuario exista
		$user = new GSUser($id);
		if($user->isNew()){
			redirectMsg('./users.php?'.$ruta,__('Specified user does not exists!','admin_galleries'),1);
			die();
		}

	}


	GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./users.php'>".__('Users management','admin_galleries')."</a> &raquo; ".($edit ? __('Edit User','admin_galleries') : __('New User','admin_galleries')));
	RMTemplate::get()->assign('xoops_pagetitle', $edit ? __('Edit User','admin_galleries') : __('New User','admin_galleries'));
	xoops_cp_header();

	$form = new RMForm($edit ? __('Edit User','admin_galleries') : __('New User','admin_galleries'), 'frmuser','users.php');
	
	$form->addElement(new RMFormUser(__('Xoops User','admin_galleries'),'uid',0,$edit ? array($user->uid()) : '',30));
	$ele = new RMFormText(__('Max Quota','admin_galleries'),'quota',10,10,$edit ? $user->quota()/1024/1024 : $mc['quota']);
	$ele->setDescription(__('This value determines the total disc quota to be used for user and must be specified in megabytes.','admin_galleries'));
	$form->addElement($ele,true);

	$form->addElement(new RMFormYesno(__('Blocked','admin_galleries'),'block',$edit ? $user->blocked() : 0));

	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('id',$id));	
	$form->addElement(new RMFormHidden('page',$page));		
	$form->addElement(new RMFormHidden('search',$search));

	$buttons = new RMFormButtonGroup();
	$buttons->addButton('sbt',$edit ? __('Save Changes','admin_galleries') : __('Create User','admin_galleries'),'submit');
	$buttons->addButton('cancel',__('Cancel','admin_galleries'),'button','onclick="window.location=\'users.php?'.$ruta.'\'"');

	$form->addElement($buttons);

	$form->display();

	xoops_cp_footer();
}


/**
* @desc Almacena la información del usuario en la base de datos
**/
function saveUsers($edit = 0){

	global $xoopsSecurity, $mc;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	$ruta = "&pag=$page&search=$search";

	if (!$xoopsSecurity->check()){
		redirectMsg('users.php?'.($edit ? "op=edit&id=$id&" : '').$ruta, __('Session token expired!','admin_galleries'), 1);
		die();
	}
	
	$db = Database::getInstance();
	if ($edit){
		//Verificamos que el usuario sea válido
		if($id<=0){
			redirectMsg('./users.php?'.$ruta,__('User id is not valid!','admin_galleries'),1);
			die();
		}

		//Verificamos que el usuario exista
		$user = new GSUser($id);
		if($user->isNew()){
			redirectMsg('./users.php?'.$ruta,__('Specified user does not exists!','admin_galleries'),1);
			die();
		}

		//Verificamos que el usuario no se encuentre registrado
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_users')." WHERE uid=$uid AND id_user<>$id";
		list($num) = $db->fetchRow($db->query($sql));
		if($num>0){
			redirectMsg('./users.php?'.$ruta,__('This is user has been registered already!','admin_galleries'),1);
			die();
		}


	}else{
		//Verificamos que el usuario no se encuentre registrado
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_users')." WHERE uid=$uid";
		list($num) = $db->fetchRow($db->query($sql));
		if($num>0){
			redirectMsg('./users.php?'.$ruta,__('This is user has been registered already!','admin_galleries'),1);
			die();
		}
		
		$user = new GSUser();
	}

	$user->setUid($uid);
	$xu = new XoopsUser($uid);
	$user->setUname($xu->uname());
	$user->setQuota($quota*1024*1024);
	$user->isNew() ? $user->setDate(time()) : '';
	$user->setBlocked($block);

	if (!$user->save()){
		redirectMsg('./users.php?'.$ruta,__('Errors ocurred while trying to save this user.','admin_galleries').'<br />'.$user->errors(), 1);
		die();
	}else{
		if($edit){
			@mkdir($mc['storedir']."/".$user->uname(), 511);
			@mkdir($mc['storedir']."/".$user->uname()."/ths", 511);
			@mkdir($mc['storedir']."/".$user->uname()."/formats", 511);
		}else{
			mkdir($mc['storedir']."/".$user->uname(), 511);
			mkdir($mc['storedir']."/".$user->uname()."/ths", 511);
			mkdir($mc['storedir']."/".$user->uname()."/formats", 511);
		}
		
		redirectMsg('./users.php?'.$ruta,__('User saved successfully!','admin_galleries'),0);
		die();
	}

}


/**
* @desc Elimina de la base de datos los usuarios especificados
**/
function deleteUsers(){

	global $xoopsSecurity, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&search=$search";
	
	//Verificamos si nos proporcionaron al menos un usuario para eliminar
	if (!is_array($ids)){
		redirectMsg('./users.php?'.$ruta,__('You must provide a user id to delete'),1);
		die();
	}	

	if (!$xoopsSecurity->check()){
		redirectMsg('./users.php?'.$ruta,__('Session token expired!','admin_galleries'),1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){
		
		//Verificamos si el usuario es válido
		if($k<=0){
			$errors .= sprintf(__('ID "%s" is not valid','admin_galleries'), $k);
			continue;			
		}

		//Verificamos si el usuario existe
		$user = new GSUser($k);
		if ($user->isNew()){
			$errors .= sprintf(__('User with id "%s" does not exists!','admin_galleries'), $k);
			continue;
		}	

		if(!$user->delete()){
			$errors .= sprintf(__('User with id "%s" could not be deleted!','admin_galleries'), $k);
		}
		
	}

	if($erros!=''){
		redirectMsg('./users.php?'.$ruta,__('Errors ocurred while trying to delete users.','admin_galleries').'<br />'.$errors,1);
		die();
	}else{
		redirectMsg('./users.php?'.$ruta,__('Users deleted successfully!','admin_galleries'),0);
		die();
	}
		

}

/**
* @desc Bloquea/Desbloquea un usuario
**/
function blockUsers(){

	global $util, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&search=$search";
	
	//Verificamos si nos proporcionaron al menos un usuario para bloquear/desbloquear
	if (!is_array($ids)){
		redirectMsg('./users.php?'.$ruta,__('Select at least one user to update','admin_galleries'),1);
		die();
	}
	
	$errors = '';
	foreach ($ids as $k){
		
		//Verificamos si el usuario es válido
		if($k<=0){
			$errors .= sprintf(__('ID "%s" is not valid','admin_galleries'), $k);
			continue;			
		}

		//Verificamos si el usuario existe
		$user = new GSUser($k);
		if ($user->isNew()){
			$errors .= sprintf(__('User with id "%s" does not exists!','admin_galleries'), $k);
			continue;
		}	
		
		$user->setBlocked(!$user->blocked());

		if(!$user->save()){
			$errors .= sprintf(__('User with id "%s" could not be updated!'), $k);
		}
	}

	if($erros!=''){
		redirectMsg('./users.php?'.$ruta,__('Errors ocurred while trying to update users','admin_galleries').'<br />'.$errors,1);
		die();
	}else{
		redirectMsg('./users.php?'.$ruta,__('Users updated successfully!','admin_galleries'),0);
		die();
	}

}


$op = rmc_server_var($_REQUEST, 'op', '');

switch($op){
	case 'new':
		formUsers();
	break;
	case 'edit':
		formUsers(1);
	break;
	case 'save':
		saveUsers();
	break;
	case 'saveedit':
		saveUsers(1);
	break;
	case 'delete':
		deleteUsers();
	break;
	case 'block':
		blockUsers();
	break;
	default:
		showUsers();
		break;
}
