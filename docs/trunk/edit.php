<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include ('../../mainfile.php');

if (!$xoopsUser){
	redirect_header($url_link,2, __('Operation not allowed!','dcos'));
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

	global $xoopsModule,$xoopsUser,$xoopsModuleConfig, $xoopsTpl, $xoopsConfig;
	global $id, $xoopsSecurity;
	
	include ('header.php');
	
	//Verifica si se proporcionó una publicación para la sección
	if (trim($id)=='')
		RDFunctions::error_404();
	
	//Verifica si la publicación existe
	$section = new RDSection($id);
	if ($section->isNew())
        RDfunctions::error_404();
    
    $res = new RDResource($section->getVar('id_res'));
	
	//Verificamos si es una publicación aprobada
	if (!$res->getVar('approved')){
		redirect_header(RDURL, 2, __('Specified section does not exists!','docs'));
		die();
	}
	
	//Verificamos si el usuario tiene permisos de edicion
	if (!$xoopsUser->uid()==$res->getVar('owner') && 
		!$res->isEditor($xoopsUser->uid()) && 
		!$xoopsUser->isAdmin()){
		redirect_header($section->permalink(), 1, __('Operation not allowed','docs'));
		die();
	}
    
    $sections = array();
    RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $sections, false);
    
    array_walk($sections, 'rd_insert_edit');
	
	$xoopsTpl->assign('xoops_pagetitle', sprintf(__('Editing %s','docs'), $section->getVar('title')));
	
    include RMEvents::get()->run_event('docs.template.editsection', RMTemplate::get()->get_template('rd_viewsec.php', 'module', 'docs'));
    
	include ('footer.php');

}


/**
* @desc Formulario para crear nueva sección
**/
function formSection($edit=0){
	global $xoopsConfig,$xoopsUser,$xoopsModuleConfig, $id, $res;
    
    //Verifica si se proporcionó una publicación para la sección
    if ($res<=0)
        RDFunctions::error_404();
    
    
    $res = new RDResource($res);
    
    if($res->isNew())
        RDFunctions::error_404();
    
    //Verificamos si es una publicación aprobada
    if (!$res->getVar('approved')){
        redirect_header(RDURL, 2, __('Specified section does not exists!','docs'));
        die();
    }
    
    //Verificamos si el usuario tiene permisos de edicion
    if (!$xoopsUser->uid()==$res->getVar('owner') && 
        !$res->isEditor($xoopsUser->uid()) && 
        !$xoopsUser->isAdmin()){
        redirect_header($section->permalink(), 1, __('Operation not allowed','docs'));
        die();
    }

	if ($edit){
		//Verifica si la sección es válida
		if ($id=='')
			RDfunctions::error_404();
		
		//Comprueba si la sección es existente
		$section = new RDSection($id);
		if ($section->isNew())
			RDFunctions::error_404();
	}

	include ('header.php');

	include_once RMCPATH.'/class/form.class.php';
    define('RD_NO_FIGURES', 1);
	$rmc_config = RMFunctions::configs();
	$editor = new RMFormEditor('','content','100%','300px',$edit ? $section->getVar('content', $rmc_config['editor_type']=='tiny' ? 's' : 'e') : '', '', false);
    if ($rmc_config['editor_type']=='tiny'){
        $tiny = TinyEditor::getInstance();
        $tiny->configuration['content_css'] .= ','.XOOPS_URL.'/modules/docs/css/figures.css';
    }

	// Arbol de Secciones
	$sections = array();
	RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $sections, false);
    
    RMTemplate::get()->add_style('forms.css', 'docs');
    include RMEvents::get()->run_event('docs.template.formsections.front', RMTemplate::get()->get_template('rd_sec.php','module','docs'));

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

$action = rmc_server_var($_POST, 'action', isset($action) ? $action : '');

switch ($action){
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
