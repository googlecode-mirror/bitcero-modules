<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

include 'header.php';

// Mensajes de Error
/*
if (isset($_SESSION['exmMsg'])){
	$tpl->assign('showExmInfoMsg', 1);
	$tpl->assign('exmInfoMessage', array('text'=>html_entity_decode($_SESSION['exmMsg']['text']),'level'=>$_SESSION['exmMsg']['level']));
	unset($_SESSION['exmMsg']);
}
*/

/**
* @desc Visualiza todas las referencias existentes de la publicación
**/
function references($edit=0){
	
	$id = rmc_server_var($_GET, 'id', 0);
	$search = rmc_server_var($_GET, 'search', 0);
	$id_ref = rmc_server_var($_GET, 'ref', 0);
	$id_editor = rmc_server_var($_GET, 'editor', 0);
	
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
	
	$page = rmc_server_var($_GET, 'page', 0);
    $limit = rmc_server_var($_GET, 'limit', 0);
	$limit = $limit<=0 ? 15 : $limit;

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
			redirectMsg('./references.php'.$ruta,_AS_AH_NOTREF,1);
			die();
		}
		$ref=new AHReference($id_ref);
		if ($ref->isNew()){
			redirectMsg('./references.php'.$ruta,_AS_AH_NOTREFEXIST,1);
			die();
		}

	}
	//Formulario de nueva referencia
	$form= new RMForm($edit ? _AS_AH_EDIT : _AS_AH_NEW,'frmref2','references.php');
	$form->addElement(new RMText(_AS_AH_TITLE,'title',40,150,$edit ? $ref->title() : ''),true);
	$form->addElement(new RMEditor(_AS_AH_REFERENCE,'reference','90%','200px',$edit ? $ref->reference() : '', 'textarea'),true);
	
	if ($edit){
		$dohtml = $ref->getVar('dohtml');
		$doxcode = $ref->getVar('doxcode');
		$dobr = $ref->getVar('dobr');
		$doimage = $ref->getVar('doimage');
		$dosmiley = $ref->getVar('dosmiley');
	} else {
		$dohtml = 0;
		$doxcode = 1;
		$dobr = 1;
		$doimage = 1;
		$dosmiley = 1;
	}
	
	$form->addElement(new RMTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));
	
	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('id_ref',$id_ref));
	$form->addElement(new RMHidden('pag',$page));
	$form->addElement(new RMHidden('limit',$limit));
	$form->addElement(new RMHidden('search',$search));
	$buttons=new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$edit ? $buttons->addButton('cancel',_CANCEL,'button','onclick="window.location=\'references.php'.$ruta.'\';"') : '';
	$form->addElement($buttons);
	$tpl->assign('content_form',$form->render());

	echo $tpl->fetch('db:admin/ahelp_references.html');

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
		redirectMsg('./references.php'.$ruta,_AS_AH_SESSINVALID, 1);
		die();
	}

	//Comprobar publicacion valida
	if ($id<=0){
		redirectMsg('./references.php'.$ruta,_AS_AH_RESOURCE,1);
		die();
	}
	
	//Comprobar publicación existente existente
	$res=new AHResource($id);
	if ($res->isNew()){
		redirectMsg('./references.php'.$ruta,_AS_AH_RESNOEXIST,1);
		die();

	}

	if ($edit){
		if ($id_ref<=0){
			redirectMsg('./references.php'.$ruta,_AS_AH_NOTREF,1);
			die();
		}
		$ref=new AHReference($id_ref);
		if ($ref->isNew()){
			redirectMsg('./references.php'.$ruta,_AS_AH_NOTREFEXIST,1);
			die();
		}

		//Comprobar si el título de la referencia en esa publicación existe
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_references')." WHERE title='$title' AND id_res='$id' AND id_ref<>'$id_ref'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./references.php'.$ruta,_AS_AH_TITLEEXIST,1);
			die();
		}

	}else{

		//Comprobar si el título de la referencia en esa publicación existe
		$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_references')." WHERE title='$title' AND id_res='$id'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./references.php'.$ruta,_AS_AH_TITLEEXIST,1);
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
		redirectMsg('./references.php'.$ruta,_AS_AH_DBOK,0);
	}
	else{
		redirectMsg('./references.php'.$ruta,_AS_AH_DBERROR,1);
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
		redirectMsg('./references.php'.$ruta,_AS_AH_SESSINVALID, 1);
		die();
	}

	if (!is_array($references) || empty($references)){
		redirectMsg('./references.php'.$ruta,_AS_AH_REF,1);
		die();
	}


	$errors='';
	foreach ($references as $k){
		//Comprueba si la referencia es válida		
		if ($k<=0){
			$errors.=sprintf(_AS_AH_REFNOTVALID, $k);		
			continue;
		}		
		$ref=new AHReference($k);
		if ($ref->isNew()){
			$errors.=sprintf(_AS_AH_REFNOTEXIST, $k);			
			continue;
		}
		$ref->delete();
						
	}

	if ($errors==''){
		redirectMsg('./references.php'.$ruta,_AS_AH_DBOK,0);
	}
	else{
		redirectMsg('./references.php'.$ruta,_AS_AH_ERRORS.$errors,1);
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

?>
