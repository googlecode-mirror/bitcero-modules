<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include ('../../mainfile.php');
include ('header.php');

// Mensajes de Error
if (isset($_SESSION['exmMsg'])){
	$tpl->assign('showExmInfoMsg', 1);
	$tpl->assign('exmInfoMessage', array('text'=>html_entity_decode($_SESSION['exmMsg']['text']),'level'=>$_SESSION['exmMsg']['level']));
	unset($_SESSION['exmMsg']);
}

$id=rmc_server_var($_GET, 'id', 0);
$res=new RDResource($id);
//Verificamos si el usuario tiene permisos de edicion
if (!$xoopsUser){
	redirect_header(XOOPS_URL.'/modules/docs',2,__('You are not allowed to view this page','docs'));
	die();
}else{
	if (!($xoopsUser->uid()==$res->getVar('owner')) && 
	!$res->isEditor($xoopsUser->uid()) && 
	!$xoopsUser->isAdmin()){
		redirect_header(XOOPS_URL.'/modules/ahelp',2,__('You are not allowed to view this page','docs'));
		die();
	}
}

/**
* Visualiza todas las referencias existentes de la publicación
**/
function references($edit=0){
	global $xoopsUser, $xoopsTpl;
	
	$id= rmc_server_var($_REQUEST, 'id', 0);
	$search = rmc_server_var($_REQUEST, 'search', '');
	$id_ref = rmc_server_var($_REQUEST, 'ref', 0);
	$id_editor = rmc_server_var($_REQUEST, 'editor', 0);

	$db = Database::getInstance();
    
	//Navegador de páginas
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pa_references')." WHERE id_res='$id'";
	$sql1='';
	if ($search){
		
		//Separamos la frase en palabras para realizar la búsqueda
		$words = explode(" ",$search);
		
		foreach($words as $k){
			//Verificamos el tamaño de la palabra
			if (strlen($k) <= 2) continue;	
			$sql1.=($sql1=='' ? ' AND ' : " OR "). " title LIKE '%$k%' ";
		}	
	
	}
	list($num)=$db->fetchRow($db->queryF($sql.$sql1));
	
	$page = rmc_server_var($_REQUEST, 'page', 1);
    $limit = rmc_server_var($_REQUEST, 'limit', 15);
	$limit = $limit<=0 ? 15 : $limit;

	$ruta='?id='.$id.'&pag='.$page.'&limit='.$limit.'&search='.$search;
	//Lista de Referencias existentes
	$sql="SELECT id_ref,title FROM ".$db->prefix('pa_references')." WHERE id_res='$id'";
	$sql1='';
	if ($search){
		
		//Separamos la frase en palabras para realizar la búsqueda
		$words = explode(" ",$search);
		
		foreach($words as $k){
			//Verificamos el tamaño de la palabra
			if (strlen($k) <= 2) continue;	
			$sql1.=($sql1=='' ? ' AND ' : " OR "). " title LIKE '%$k%' ";
		}	
	
	}
	
	$sql2=" LIMIT $start,$limit";
	$result=$db->queryF($sql.$sql1.$sql2);
    $references = array();
	while ($rows=$db->fetchArray($result)){
		$references = array('id'=>$rows['id_ref'],'title'=>$rows['title']);
	}
	
	if ($edit){
		if ($id_ref<=0){
			redirectMsg('./references.php'.$ruta,__('A note has not been specified!','docs'),1);
			die();
		}
		$ref=new RDReference($id_ref);
		if ($ref->isNew()){
			redirectMsg('./references.php'.$ruta,__('Specified does not exists!','docs'),1);
			die();
		}

	}
	//Formulario de nueva referencia
	$form= new RMForm($edit ? __('Edit Note','docs') : __('New Note','docs'),'frmref2','references.php');
	$form->addElement(new RMFormText(__('Title','docs'),'title',40,150,$edit ? $ref->title() : ''),true);
	$form->addElement(new RMFormEditor(__('Note content','docs'),'reference','90%','200px',$edit ? $ref->getVar('text','e') : '', 'simple'),true);
	
	$form->addElement(new RMFormHidden('action',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('id_ref',$id_ref));
	$form->addElement(new RMFormHidden('page',$page));
	$form->addElement(new RMFormHidden('limit',$limit));
	$form->addElement(new RMFormHidden('search',$search));
	$buttons=new RMFormButtonGroup();
	$buttons->addButton('sbt',$edit ? __('Save Changes','docs') : __('Create Note','docs'),'submit');
	$edit ? $buttons->addButton('cancel',__('Cancel','docs'),'button','onclick="window.location=\'references.php'.$ruta.'\';"') : '';
	$form->addElement($buttons);

    $theme_css = xoops_getcss();
    $vars = $xoopsTpl->get_template_vars();
    extract($vars);
	include RMTemplate::get()->get_template('rd_references.php', 'module', 'docs');

}

/**
* @desc Almacena toda la información referente a la referencia
**/
function saveReferences($edit=0){
	global $db,$util;
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}
	$ruta='?id='.$id.'&pag='.$pag.'&limit='.$limit.'&search='.$search;

	if (!$util->validateToken()){
		redirectMsg('./references.php'.$ruta,_MS_AH_SESSINVALID, 1);
		die();
	}

	//Comprobar publicacion valida
	if ($id<=0){
		redirectMsg('./references.php'.$ruta,_MS_AH_ERRRESOURCE,1);
		die();
	}
	
	//Comprobar publicación existente existente
	$res=new AHResource($id);
	if ($res->isNew()){
		redirectMsg('./references.php'.$ruta,_MS_AH_ERRNOTEXIST,1);
		die();

	}

	if ($edit){
		if ($id_ref<=0){
			redirectMsg('./references.php'.$ruta,_MS_AH_NOTREF,1);
			die();
		}
		$ref=new AHReference($id_ref);
		if ($ref->isNew()){
			redirectMsg('./references.php'.$ruta,_MS_AH_NOTREFEXIST,1);
			die();
		}

		//Comprobar si el título de la referencia en esa publicación existe
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_references')." WHERE title='$title' AND id_res='$id' AND id_ref<>'$id_ref'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./references.php'.$ruta,_MS_AH_TITLEEXIST,1);
			die();
		}

	}else{

		//Comprobar si el título de la referencia en esa publicación existe
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_references')." WHERE title='$title' AND id_res='$id'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./references.php'.$ruta,_MS_AH_TITLEEXIST,1);
			die();
		}
		$ref=new AHReference();
	}
	
	$ref->setResource($id);
	$ref->setTitle($title);
	$ref->setReference($reference);
	
	if ($edit){
		$dohtml = isset($dohtml) ? 1 : 0;
		$doxcode = isset($doxcode) ? 1 : 0;
		$dobr = isset($dobr) ? 1 : 0;
		$doimage = isset($doimage) ? 1 : 0;
		$dosmiley = isset($dosmiley) ? 1 : 0;
	}
	
	$ref->setVar('dohtml', $dohtml);
	$ref->setVar('doxcode', $doxcode);
	$ref->setVar('dobr', $dobr);
	$ref->setVar('doimage', $doimage);
	$ref->setVar('dosmiley', $dosmiley);
	
	if ($ref->save()){
		redirectMsg('./references.php'.$ruta,_MS_AH_DBOK,0);
	}
	else{
		redirectMsg('./references.php'.$ruta,_MS_AH_DBERROR,1);
	}
	
}


/**
* @desc Elimina las referencias especificadas
**/
function deleteReferences(){
	global $util;
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
 	$references=isset($_REQUEST['refs']) ? $_REQUEST['refs'] : array();
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$limit = $limit<=0 ? 15 : $limit;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	
	$ruta='?id='.$id.'&pag='.$pag.'&limit='.$limit.'&search='.$search;
	if (!$util->validateToken()){
		redirectMsg('./references.php'.$ruta,_MS_AH_SESSINVALID, 1);
		die();
	}

	if (!is_array($references) || empty($references)){
		redirectMsg('./references.php'.$ruta,_MS_AH_REF,1);
		die();
	}

	$errors='';
	foreach ($references as $k){
		//Comprueba si la referencia es válida		
		if ($k<=0){
			$errors.=sprintf(_MS_AH_REFNOTVALID, $k);		
			continue;
		}		
		$ref=new AHReference($k);
		if ($ref->isNew()){
			$errors.=sprintf(_MS_AH_REFNOTEXIST, $k);			
			continue;
		}
		$ref->delete();
						
	}

	if ($errors==''){
		redirectMsg('./references.php'.$ruta,_MS_AH_DBOK,0);
	}
	else{
		redirectMsg('./references.php'.$ruta,_MS_AH_ERRORS.$errors,1);
	}
	

}

$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op){
	case 'edit':
		references(1);
	break;
	case 'save':
		saveReferences();
	break;
	case 'saveedit':
		saveReferences(1);
	break;
	case 'delete':
		deleteReferences();
	break;
	default:
		references();
}
