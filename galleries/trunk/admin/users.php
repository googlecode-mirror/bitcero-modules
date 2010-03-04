<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
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

define('GS_LOCATION','users');
include 'header.php';

function optionsBar(){
	global $tpl;

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "&pag=$page&limit=$limit&search=$search";

	$tpl->append('xoopsOptions', array('link' => './users.php', 'title' => _AS_GS_USERS, 'icon' => '../images/users16.png'));
	$tpl->append('xoopsOptions', array('link' => './users.php?op=new'.$ruta, 'title' => _AS_GS_NEW, 'icon' => '../images/add.png'));
	
}

/**
* @desc Visualiza todos los usuarios existentes
**/
function showUsers(){

	global $xoopsModule, $adminTemplate, $db, $tpl;

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$limit = $limit<=0 ? 15 : $limit;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';


	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_users');
	$sql1 = '';
	$search =trim($search);
	if ($search){
				
		if (strlen($search)<=2){
			continue;
		}
			
		$sql1.= $sql1=='' ? " WHERE (uname LIKE '%$search%')" : " OR (uname LIKE '%$search%')";			

	}


	list($num)=$db->fetchRow($db->query($sql.$sql1));
	
	if ($page > 0){ $page -= 1; }
    	$start = $page * $limit;
    	$tpages = (int)($num / $limit);
    	if($num % $limit > 0) $tpages++;
    	$pactual = $page + 1;
    	if ($pactual>$tpages){
    	    $rest = $pactual - $tpages;
    	    $pactual = $pactual - $rest + 1;
    	    $start = ($pactual - 1) * $limit;
    	}
	
    	
    	if ($tpages > 1) {
    	    $nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit.'&search='.$search, 0);
    	    $tpl->assign('usersNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_AS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	$tpl->assign('search',$search);
	//Fin de barra de navegación

	$sql = "SELECT * FROM ".$db->prefix('gs_users');
	$sql2 = " LIMIT $start,$limit";
	$result = $db->query($sql.$sql1.$sql2);
	while($rows = $db->fetchArray($result)){
		$uname = eregi_replace("($search)","<span class='searchResalte'>\\1</span>", $rows['uname']);

		$user = new GSUser();
		$user->assignVars($rows);		

		$tpl->append('users',array('id'=>$user->id(),'uid'=>$user->uid(),'uname'=>$uname,'quota'=>formatBytesSize($user->quota()),
		'blocked'=>$user->blocked(),'used'=>GSFunctions::makeQuota($user),'pics'=>$user->pics(),'sets'=>$user->sets(),
		'date'=>formatTimeStamp($user->date(),'string'),'url'=>$user->userUrl()));

	}
	
	$tpl->assign('lang_exist',_AS_GS_EXIST);
	$tpl->assign('lang_id',_AS_GS_ID);
	$tpl->assign('lang_name',_AS_GS_NAME);
	$tpl->assign('lang_quota',_AS_GS_QUOTA);
	$tpl->assign('lang_pics',_AS_GS_PICS);
	$tpl->assign('lang_sets',_AS_GS_SETS);
	$tpl->assign('lang_date',_AS_GS_DATE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_submit',_SUBMIT);
	$tpl->assign('lang_search',_AS_GS_SEARCH);
	$tpl->assign('lang_used',_AS_GS_USED);
	$tpl->assign('lang_blocked',_AS_GS_BLOCKED);
	$tpl->assign('lang_block',_AS_GS_BLOCK);

	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_GS_USERSLOC);
	$adminTemplate = "admin/gs_users.html";
	$cHead = '<link href="'.XOOPS_URL.'/modules/galleries/styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
	xoops_cp_header($cHead);
	
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

	$ruta = "pag=$page&limit=$limit&search=$search";

	if($edit){
		//Verificamos que el usuario sea válido
		if($id<=0){
			redirectMsg('./users.php?'.$ruta,_AS_GS_USERVALID,1);
			die();
		}

		//Verificamos que el usuario exista
		$user = new GSUser($id);
		if($user->isNew()){
			redirectMsg('./users.php?'.$ruta,_AS_GS_USEREXIST,1);
			die();
		}

	}


	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./users.php'>"._AS_GS_USERSLOC."</a> &raquo; ".($edit ? _AS_GS_EDITUSER : _AS_GS_NEWUSER));
	xoops_cp_header();

	$form = new RMForm($edit ? _AS_GS_EDITUSER : _AS_GS_NEWUSER, 'frmuser','users.php');
	
	$form->addElement(new RMFormUserEXM(_AS_GS_USER,'uid',0,$edit ? array($user->uid()) : '',30));
	$ele = new RMText(_AS_GS_QUOTA,'quota',10,10,$edit ? $user->quota()/1024/1024 : $mc['quota']);
	$ele->setDescription(_AS_GS_DESCQUOTA);
	$form->addElement($ele,true);

	$form->addElement(new RMYesno(_AS_GS_BLOCKED,'block',$edit ? $user->blocked() : 0));

	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));	
	$form->addElement(new RMHidden('page',$page));	
	$form->addElement(new RMHidden('limit',$limit));	
	$form->addElement(new RMHidden('search',$search));

	$buttons = new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button','onclick="window.location=\'users.php?'.$ruta.'\'"');

	$form->addElement($buttons);

	$form->display();

	xoops_cp_footer();
}


/**
* @desc Almacena la información del usuario en la base de datos
**/
function saveUsers($edit = 0){

	global $util, $mc, $db;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	$ruta = "&pag=$page&limit=$limit&search=$search";

	
	if ($edit){
		//Verificamos que el usuario sea válido
		if($id<=0){
			redirectMsg('./users.php?'.$ruta,_AS_GS_USERVALID,1);
			die();
		}

		//Verificamos que el usuario exista
		$user = new GSUser($id);
		if($user->isNew()){
			redirectMsg('./users.php?'.$ruta,_AS_GS_USEREXIST,1);
			die();
		}

		//Verificamos que el usuario no se encuentre registrado
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_users')." WHERE uid=$uid AND id_user<>$id";
		list($num) = $db->fetchRow($db->query($sql));
		if($num>0){
			redirectMsg('./users.php?'.$ruta,_AS_GS_ERRUSER,1);
			die();
		}


	}else{
		//Verificamos que el usuario no se encuentre registrado
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_users')." WHERE uid=$uid";
		list($num) = $db->fetchRow($db->query($sql));
		if($num>0){
			redirectMsg('./users.php?'.$ruta,_AS_GS_ERRUSER,1);
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
		redirectMsg('./users.php?'.$ruta,_AS_GS_DBERROR.$user->errors(), 1);
		die();
	}else{
		if($edit){
			@mkdir($mc['storedir']."/".$user->uname());
			@mkdir($mc['storedir']."/".$user->uname()."/ths");
			@mkdir($mc['storedir']."/".$user->uname()."/formats");
		}else{
			mkdir($mc['storedir']."/".$user->uname());
			mkdir($mc['storedir']."/".$user->uname()."/ths");
			mkdir($mc['storedir']."/".$user->uname()."/formats");
		}
		
		redirectMsg('./users.php?'.$ruta,_AS_GS_DBOK,0);
		die();
	}

}


/**
* @desc Elimina de la base de datos los usuarios especificados
**/
function deleteUsers(){

	global $util, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&limit=$limit&search=$search";
	
	//Verificamos si nos proporcionaron al menos un usuario para eliminar
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./users.php?'.$ruta,_AS_GS_ERRUSERDEL,1);
		die();
	}

	if (!is_array($ids)){
		$us = new GSUser($ids);
		$ids = array($ids);
	}
	

	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./users.php?'.$ruta,_AS_GS_SESSINVALID,1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){
			
			//Verificamos si el usuario es válido
			if($k<=0){
				$errors .= sprintf(_AS_GS_ERRNOTVALID, $k);
				continue;			
			}

			//Verificamos si el usuario existe
			$user = new GSUser($k);
			if ($user->isNew()){
				$errors .= sprintf(_AS_GS_ERRNOTEXIST, $k);
				continue;
			}	

			if(!$user->delete()){
				$errors .= sprintf(_AS_GS_ERRDELETE, $k);
			}
		}

		if($erros!=''){
			redirectMsg('./users.php?'.$ruta,_AS_GS_DBERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./users.php?'.$ruta,_AS_GS_DBOK,0);
			die();
		}
		


	}else{

		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./users.php'>"._AS_GS_USERSLOC."</a> &raquo; "._AS_GS_LOCDELETE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['ids[]'] = $ids;
		$hiddens['op'] = 'delete';
		$hiddens['limit'] = $limit;
		$hiddens['pag'] = $page;
		$hiddens['search'] = $search;
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'users.php?'.$ruta.'\';"';
		
		$util->msgBox($hiddens, 'users.php',(isset($us) ? sprintf(_AS_GS_DELETECONF, $us->uname()) : _AS_GS_DELETECONFS). '<br /><br />' ._AS_GS_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}

}

/**
* @desc Bloquea/Desbloquea un usuario
**/
function blockUsers(){

	global $util, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&limit=$limit&search=$search";
	
	//Verificamos si nos proporcionaron al menos un usuario para bloquear/desbloquear
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./users.php?'.$ruta,_AS_GS_ERRUSERBLOCK,1);
		die();
	}
	
	$errors = '';
	foreach ($ids as $k){
		
		//Verificamos si el usuario es válido
		if($k<=0){
			$errors .= sprintf(_AS_GS_ERRNOTVALID, $k);
			continue;			
		}

		//Verificamos si el usuario existe
		$user = new GSUser($k);
		if ($user->isNew()){
			$errors .= sprintf(_AS_GS_ERRNOTEXIST, $k);
			continue;
		}	
		
		$user->setBlocked(!$user->blocked());

		if(!$user->save()){
			$errors .= sprintf(_AS_GS_ERRSAVE, $k);
		}
	}

	if($erros!=''){
		redirectMsg('./users.php?'.$ruta,_AS_GS_DBERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./users.php?'.$ruta,_AS_GS_DBOK,0);
		die();
	}
			


}



$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

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
?>
