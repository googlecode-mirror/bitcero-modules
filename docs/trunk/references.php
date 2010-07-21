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
load_mod_locale('docs');

// Mensajes de Error
$rmc_messages = array();
if (isset($_SESSION['rmMsg'])){
    foreach ($_SESSION['rmMsg'] as $msg){
        $rmc_messages[] = $msg;
    }
    unset($_SESSION['rmMsg']);
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
	global $xoopsUser, $xoopsTpl, $rmc_messages, $xoopsSecurity;
	
	$id= rmc_server_var($_REQUEST, 'id', 0);
	$search = rmc_server_var($_REQUEST, 'search', '');
	$id_ref = rmc_server_var($_REQUEST, 'ref', 0);
	$id_editor = rmc_server_var($_REQUEST, 'editor', 0);
    $rmc_config = RMFunctions::configs();

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
    $limit = 15;

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url("?id=$id&amp;page={PAGE_NUM}");

	$ruta='?id='.$id.'&page='.$page.'&limit='.$limit.'&search='.$search;
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
		$references[] = array('id'=>$rows['id_ref'],'title'=>$rows['title']);
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

    $theme_css = xoops_getcss();
    $vars = $xoopsTpl->get_template_vars();
    extract($vars);
    
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.min.js');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery-ui.min.js');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script('include/js/scripts.php?file=ajax.js');
    if ($rmc_config['editor_type']=='tiny')
        RMTemplate::get()->add_script(XOOPS_URL.'/modules/rmcommon/api/editors/tinymce/tiny_mce_popup.js');
    RMTemplate::get()->add_style('refs.css','docs');
    RMTemplate::get()->add_style('jquery.css','rmcommon');
    RMTemplate::get()->add_head('<script type="text/javascript">$(document).ready(function(){$("frm-refs").validate();});</script>');
    
    // Options for table header
    $options[] = array(
        'title' => __('Select Resource','docs'),
        'href'  => 'javascript:;',
        'attrs' => 'id="option-resource" onclick="docsAjax.getSectionsList(1);"',
        'tip'   => __('Select another resource to show the notes that belong to this.','docs')
    );
    $options[] = array(
        'title' => __('Create Note','docs'),
        'href'  => 'javascript:;',
        'attrs' => 'id="option-new" onclick="docsAjax.displayForm();"',
        'tip'   => __('Create a new note.','docs')
    );
    // Get additional options from other modules or plugins
    $options = RMEvents::get()->run_event('docs.references.options',$options, $id, $edit, $edit ? $ref : null);
    
    // Insert adtional content in template
    $other_content = '';
    $other_content = RMEvents::get()->run_event('docs.additional.content', $other_content, $id, $edit, $edit ? $ref : null);
    
	include RMTemplate::get()->get_template('rd_references.php', 'module', 'docs');

}

/**
* @desc Almacena toda la información referente a la referencia
**/
function saveReferences($edit=0){
	global $xoopsSecurity;
    
    $db = Database::getInstance();
    
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}
	$ruta='?id='.$id.'&page='.$page.'&limit='.$limit.'&search='.$search;

	if (!$xoopsSecurity->check()){
		redirectMsg('./references.php'.$ruta, __('Session token expired!','docs'), 1);
		die();
	}

	//Comprobar publicacion valida
	if ($id<=0){
		redirectMsg('./references.php'.$ruta, __('Resource ID not provided!','docs'),1);
		die();
	}
	
	//Comprobar publicación existente existente
	$res=new RDResource($id);
	if ($res->isNew()){
		redirectMsg('./references.php'.$ruta, __('Specified resource does not exists!','docs'),1);
		die();

	}

	if ($edit){
		if ($id_ref<=0){
			redirectMsg('./references.php'.$ruta, __('Note id not provided!','docs'),1);
			die();
		}
		$ref=new RDReference($id_ref);
		if ($ref->isNew()){
			redirectMsg('./references.php'.$ruta, __('Specified note does not exists!','docs'),1);
			die();
		}

		//Comprobar si el título de la referencia en esa publicación existe
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_references')." WHERE title='$title' AND id_res='$id' AND id_ref<>'$id_ref'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./references.php'.$ruta, __('Another note with same title already exists','docs'),1);
			die();
		}

	}else{

		//Comprobar si el título de la referencia en esa publicación existe
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_references')." WHERE title='$title' AND id_res='$id'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./references.php'.$ruta, __('Another note with same title already exists','docs'),1);
			die();
		}
		$ref=new RDReference();
	}
	
	$ref->setVar('id_res',$id);
	$ref->setVar('title',$title);
	$ref->setVar('text',$reference);
	
	if ($ref->save()){
		redirectMsg('./references.php'.$ruta, __('Note saved successfully','docs'),0);
	}
	else{
		redirectMsg('./references.php'.$ruta, __('Errors ocurred while trying to save note','docs'),1);
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

$action= rmc_server_var($_REQUEST, 'action', '');

switch ($action){
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
