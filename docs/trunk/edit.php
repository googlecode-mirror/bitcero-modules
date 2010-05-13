<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2


define('AH_LOCATION','edit');
include ('../../mainfile.php');
include_once 'include/functions.php';

if (!$xoopsUser){
	redirect_header($url_link,2,_MS_AH_NOTPERMEDIT);
	die();
}

/**
* @desc Obtiene las secciones hijas de una sección
* @param objeto $res Publicación a que pertenece
* @param int $parent Sección padre a qur pertenece
**/
function child(&$res,$parent,$indent){
	global $tpl,$db,$util, $xoopsModuleConfig;
	
	$retlink = XOOPS_URL.'/modules/ahelp/';
	$child= array();
	$sql="SELECT * FROM ".$db->prefix('pa_sections')." WHERE id_res='".$res->id()."' AND parent='$parent' ORDER BY `order`";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$sec= new AHSection();
		$sec->assignVars($rows);
	
		//Creamos link correcto para ir a contenido
		$url_cont = $res->nameId().'/'.$sec->nameId();	

		$tpl->append('sections',array('id'=>$sec->id(),'title'=>$sec->title(),'order'=>$sec->order(),
				'resource'=>$sec->resource(),'parent'=>$sec->parent(),'indent'=>$indent,'link'=>$url_cont));
		
		child($res,$sec->id(),$indent+1);	
	}
}

/**
* @desc Muestra todas las secciones existentes de la publicacion
**/
function showSection(){

	global $xoopsModule,$db,$tpl,$util,$xoopsOption,$xoopsUser,$xoopsModuleConfig;
	global $id_res;
	
	$xoopsOption['template_main']='ahelp_viewsec.html';
	$xoopsOption['module_subpage'] = 'edit';
	include ('header.php');
	
	$retlink = AHURL;

	//Verifica si se proporcionó una publicación para la sección
	if (trim($id_res)==''){
		redirect_header(AHURL,1,_MS_AH_ERRRESOURCE);
		die();
	}
	
	//Verifica si la publicación existe
	$res= new AHResource($id_res);
	if ($res->isNew()){
		redirect_header(AHURL,1,_MS_AH_ERRNOTEXIST);
		die();
	}
	
	//Verificamos si es una publicación aprobada
	if (!$res->approved()){
		redirect_header(AHURL,2,_MS_AH_NOTAPPROVED);
		die();
	}
	
	$url_link = ah_make_link($res->nameId());
	
	//Verificamos si el usuario tiene permisos de edicion
	if (!$xoopsUser->uid()==$res->owner() && 
		!$res->isEditor($xoopsUser->uid()) && 
		!$xoopsUser->isAdmin()){
		redirect_header($url_link,2,_MS_AH_NOTPERMEDIT);
		die();
	}
	
	$location = "<a href='".ah_make_link()."'>"._MS_AH_HOME."</a> &raquo; <a href='".ah_make_link($res->nameId())."'>".$res->title()."</a> &raquo; "._MS_AH_EXIST;
	$tpl->assign('location_bar', $location);
	
	$id = $res->id();

	//Secciones
	$sql="SELECT * FROM ".$db->prefix('pa_sections')." WHERE id_res='$id' AND parent=0 ORDER BY `order`";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$sec= new AHSection();
		$sec->assignVars($rows);
		
		//Creamos link correcto para ir a contenido
		$url_cont = $res->nameId().'/'.$sec->nameId();

		$tpl->append('sections',array('id'=>$sec->id(),'title'=>$sec->title(),'order'=>$sec->order(),
				'resource'=>$sec->resource(),'parent'=>$sec->parent(),'indent'=>0,'link'=>$url_cont));
		
		child($res,$sec->id(),1);
		
	}

	$tpl->assign('lang_secexist',_MS_AH_EXIST);
	$tpl->assign('lang_id',_MS_AH_ID);
	$tpl->assign('lang_title',_MS_AH_TITLE);
	$tpl->assign('lang_order',_MS_AH_ORDER);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_select',_SELECT);
	$tpl->assign('id',$id_res);
	$tpl->assign('token', $util->getTokenHTML());
	$tpl->assign('lang_save',_MS_AH_SAVE);
	$tpl->assign('lang_home',_MS_AH_HOME);
	$tpl->assign('title',$res->title());
	$tpl->assign('token',$util->getTokenHTML());
	$tpl->assign('url_link',$url_link);
	$tpl->assign('newpage_link',ah_make_link('newpage/'.$res->nameId()));
	$tpl->assign('lang_newpage', _AS_AH_NEWPAGE);
	
	makeHeader();
	$tpl->assign('lang_titleheader',$res->title());
	makeFooter();
	include ('footer.php');

}


/**
* @desc Formulario para crear nueva sección
**/
function formSection($edit=0){
	global $tpl,$xoopsConfig,$xoopsUser,$xoopsOption,$xoopsModuleConfig, $id_res, $id_sec;
	
	$cHead='';
	//Verifica si se proporcionó una publicación para la sección
	if ($id_res==''){
		redirect_header(XOOPS_URL."/modules/ahelp/",1,_MS_AH_ERRRESOURCE);
		die();
	}
	
	//Verifica si la publicación existe
	$res= new AHResource($id_res);
	if ($res->isNew()){
		redirect_header(XOOPS_URL."/modules/ahelp/",1,_MS_AH_ERRNOTEXIST);
		die();
	}


	//Verificamos si es una publicación aprobada
	if (!$res->approved()){
		redirect_header(XOOPS_URL.'/modules/ahelp/',2,_MS_AH_NOTAPPROVED);
		die();
	}
	
	$url_link = ah_make_link($res->nameId());

	//Verificamos si el usuario tiene permisos de edicion
	if (!$xoopsUser->uid()==$res->owner() && !$res->isEditor($xoopsUser->uid()) && !$xoopsUser->isAdmin()){
		redirect_header(ah_make_link('list/'.$res->nameId()),2,_MS_AH_NOTPERMEDIT);
		die();
	}

	if ($edit){
		//Verifica si la sección es válida
		if ($id_sec==''){
			redirect_header(ah_make_link('list/'.$res->nameId()),1,_MS_AH_ERRSECTION);
			die();
		}
		
		//Comprueba si la sección es existente
		$sec=new AHSection($id_sec, $res->id());
		if ($sec->isNew()){
			redirect_header(ah_make_link('list/'.$res->nameId()),1,_MS_AH_ERRNOTSEC);
			die();
		}
	}

	$xoopsOption['template_main']='ahelp_sec.html';
	$xoopsOption['module_subpage'] = 'edit';
	include ('header.php');
	
	$location = "<a href='".ah_make_link()."'>"._MS_AH_HOME."</a> &raquo; <a href='".ah_make_link($res->nameId())."'>".$res->title()."</a> &raquo; "._MS_AH_EXIST;
	$tpl->assign('location_bar', $location);

	$form=new RMForm($edit ? _MS_AH_EDIT : _MS_AH_NEWSECTION,'frmsec',ah_make_link('edit/'.$res->nameId().'/'.($edit ? $sec->nameId() : '')));
	
	$form->addElement(new RMLabel(_MS_AH_PUBLISH,$res->title()));
	$form->addElement(new RMText(_MS_AH_TITLE,'title',50,200,$edit ? $sec->title() : ''),true);
	
	if ($edit){
		$ele = new RMEditorAddons(_OPTIONS,'options','content',$xoopsConfig['editor_type'],$res->id(),$sec->id(),$id_sec);
		$cHead = $ele->jsFunctions();
		$form->addElement($ele);
	} else {
		$form->addElement(new RMLabel(_OPTIONS, _MS_AH_REFFIG));
	}

	$form->addElement(new RMEditor(_MS_AH_CONTENT,'content','90%','300px',$edit ? $sec->getVar('content', 'e') : '', '', false));
	if ($edit){
		$dohtml = $sec->getVar('dohtml');
		$doxcode = $sec->getVar('doxcode');
		$dobr = $sec->getVar('dobr');
		$dosmiley = $sec->getVar('dosmiley');
		$doimage = $sec->getVar('doimage');
	} else {
		$dohtml = 1;
		$doxcode = 0;
		$dobr = 0;
		$dosmiley = 0;
		$doimage = 0;
	}
	$form->addElement(new RMTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));

	// Arbol de Secciones
	$ele= new RMSelect(_MS_AH_PARENT,'parent');
	$ele->addOption(0,_SELECT);
	$tree = array();
	getSectionTree($tree, 0, 0, $res->id(), 'id_sec, title', $edit ? $sec->id() : 0);
	foreach ($tree as $k){
		$ele->addOption($k['id_sec'], str_repeat('--', $k['saltos']).' '.$k['title'], $edit ? ($sec->parent()==$k['id_sec'] ? 1 : 0) : 0);
	}
	
	$form->addElement($ele);

	$form->addElement(new RMText(_MS_AH_ORDER,'order',5,5,$edit ? $sec->order() : ''),true);

	$ele=new RMCheck('');
	$ele->addOption(_MS_AH_GOTOSEC,'return',1,1);
		
	$form->addElement($ele);
	// Usuario
	$form->addElement(new RMLabel('',$res->approveEditors() ? _MS_AD_APPROVETIP : _MS_AD_NOAPPROVETIP));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_MS_AH_SAVE,'submit');
	$buttons->addButton('ret', _MS_AH_SAVERET, 'submit', 'onclick="document.forms[\'frmsec\'].op.value=\''.($edit ? 'saveretedit' : 'saveret').'\';"');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="history.go(-1);"');

	$form->addElement($buttons);
	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	
	$tpl->assign('lang_home',_MS_AH_HOME);
	$tpl->assign('lang_sec',$edit ? $sec->title() : _MS_AH_NEWSECTION);
	$tpl->assign('title',$res->title());
	$tpl->assign('id',$id_res);
	$tpl->assign('url_link',$url_link);
	$tpl->assign('chead',$cHead);
	$tpl->assign('content',$form->render());

	makeHeader();
	$tpl->assign('lang_titleheader', $res->title());
	makeFooter();
	include ('footer.php');

}

/**
* @desc Almacena toda la información referente a la sección
**/
function saveSection($edit=0,$ret=0){
	global $util,$db,$xoopsUser, $xoopsModuleConfig, $id_res, $id_sec;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	$util=& RMUtils::getInstance();

	//Verifica si se proporcionó una publicación para la sección
	if ($id_res==''){
		redirect_header(AHURL,1,_MS_AH_ERRRESOURCE);
		die();
	}
	
	//Verifica si la publicación existe
	$res= new AHResource($id_res);
	if ($res->isNew()){
		redirect_header(AHURL,1,_MS_AH_ERRNOTEXIST);
		die();
	}
	
	
	//Verificamos si es una publicación aprobada
	if (!$res->approved()){
		redirect_header(AHURL,2,_MS_AH_NOTAPPROVED);
		die();
	}

	// TODO: Crear el link correcto de retorno
	$retlink = ah_make_link('list/'.$res->nameId().'/');
		
	//Verificamos si el usuario tiene permisos de edicion
	if (!$xoopsUser->uid()==$res->owner() && 
		!$res->isEditor($xoopsUser->uid()) &&
		!$xoopsUser->isAdmin()){
		redirect_header($retlink,2,_MS_AH_NOTPERMEDIT);
		die();
	}

	if ($edit){
		//Verifica si la sección es válida
		if ($id_sec==''){
			redirect_header($retlink,1,_MS_AH_ERRSECTION);
			die();
		}
		
		//Comprueba si la sección es existente
		$sec=new AHSection($id_sec, $res->id());
		if ($sec->isNew()){
			redirect_header($retlink,1,_MS_AH_ERRNOTSEC);
			die();
		}

		//Comprueba que el título de la sección no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_sections')." WHERE title='$title' AND id_res='$id_res' AND id_sec<>".$sec->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirect_header(ah_make_link('edit/'.$res->nameId().'/'.$sec->nameId()),1,_MS_AH_ERRTITLE);	
			die();
		}
		
		/**
		* Comprobamos si debemos almacenar las ediciones en la
		* tabla temporal o directamente en la tabla de secciones
		*/
		if (!$res->approveEditors() && !$xoopsUser->isAdmin()){
			$sec = new AHEdit($id_sec);
		}
		
	}else{
	

		//Comprueba que el título de la sección no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_sections')." WHERE title='$title' AND id_res='".$res->id()."'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirect_header(ah_make_link('publish/'.$res->nameId().'/'),1,_MS_AH_ERRTITLE);	
			die();
		}
		$sec = new AHSection();
	}
	
	//Creamos link correcto
	$url_cont = ah_make_link($res->nameId().'/'.$sec->nameId());

	//Genera $nameid Nombre identificador
	if ($title<>$sec->title()){	
		$found=false; 
		$i = 0;
		do{
    			$nameid = $util->sweetstring($title).($found ? $i : '');
        		$sql = "SELECT COUNT(*) FROM ".$db->prefix('pa_sections'). " WHERE nameid = '$nameid'";
        		list ($num) =$db->fetchRow($db->queryF($sql));
        		if ($num>0){
        			$found =true;
        		    $i++;
        		}else{
        			$found=false;
        		}
        	
    		}while ($found==true);
	}
	
	if (!$res->approveEditors() && !$xoopsUser->isAdmin() && !($res->owner()==$xoopsUser->uid())) $sec->setSection($id_sec);
	$sec->setTitle($title);
	$sec->setContent($content);
	$sec->setOrder($order);
	$sec->setResource($res->id());
	isset($nameid) ? $sec->setNameId($nameid) : '' ;
	$sec->setParent($parent);
	$sec->setVar('dohtml', $dohtml);
	$sec->setVar('doxcode', isset($doxcode) ? $doxcode : 0);
	$sec->setVar('dobr', isset($dobr) ? $dobr : 0);
	$sec->setVar('dosmiley', isset($dosmiley) ? $dosmiley : 0);
	$sec->setVar('doimage', isset($doimage) ? $doimage : 0);
	$sec->setUid($xoopsUser->uid());
	$sec->setUname($xoopsUser->uname());
	
	if ($edit){
		$sec->setModified(time());
	}else{
		$sec->setCreated(time());
		$sec->setModified(time());
	}
	
	
	if (!$sec->save()){
        
		redirect_header(ah_make_link('edit/'.$res->nameId().'/'.$sec->nameId()),3,_MS_AH_DBERROR);
		die();
	}else{
		if ($ret){
			redirect_header(ah_make_link('edit/'.$res->nameId().'/'.$sec->nameId()),0,_MS_AH_DBOK);
			die();
		}else{	
			if (isset($return)){
				redirect_header($url_cont,1,_MS_AH_DBOK);
				die();	
			}else{
				redirect_header($retlink,1,_MS_AH_DBOK);
				die();	
			}
		}
	}

}

/**
* @desc Modifica el orden de las secciones
**/
function changeOrderSections(){
	global $util;
	$orders=isset($_REQUEST['orders']) ? $_REQUEST['orders'] : array();
	$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : array();	
	
	$util=& RMUtils::getInstance();
	if (!$util->validateToken()){
		redirectMsg(XOOPS_URL.'/modules/ahelp/edit.php?id='.$id,_MS_AH_SESSINVALID, 1);
		die();
	}	

	if (!is_array($orders) || empty($orders)){
		redirectMsg(XOOPS_URL.'/modules/ahelp/edit.php?id='.$id,_MS_AH_NOTSECTION,1);
		die();
	}
	
	$errors='';
	foreach ($orders as $k=>$v){
	
		//Verifica si la sección es válida
		if ($k<=0){
			$errors.=sprintf(_MS_AH_NOTVALID, $k);
			continue;
		}	
		
		//Comprueba si la sección es existente
		$sec=new AHSection($k);
		if ($sec->isNew()){
			$errors.=sprintf(_MS_AH_NOTEXISTSECT,$k);
			continue;
		}	
		

		$sec->setOrder($v);		
		if (!$sec->save()){
			$errors.=sprintf(_MS_AH_NOTSAVEORDER, $k);		
		}
	}

	if ($errors!=''){
		redirect_header(XOOPS_URL.'/modules/ahelp/edit.php?id='.$id,1,_MS_AH_ERRORS.$errors);
		die();

	}else{
		redirect_header(XOOPS_URL.'/modules/ahelp/edit.php?id='.$id,0,_MS_AH_DBOK);
	}



}

$op = isset($_POST['op']) ? $_POST['op'] : $op;

switch ($op){
	case 'new':
		formSection();
		break;	
	case 'edit':
		formSection(1);
		break;
	case 'save':
		saveSection();
		break;
	case 'saveedit':
		saveSection(1,0);
		break;
	case 'saveret':
		saveSection(0,1);
		break;
	case 'saveretedit':
		saveSection(1, 1);
		break;
	case 'changeorder':
		changeOrderSections();		
		break;
	default:
		showSection();
		break;
}
