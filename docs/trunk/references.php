<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('AH_LOCATION','ref');
include ('../../mainfile.php');
include ('header.php');

// Mensajes de Error
if (isset($_SESSION['exmMsg'])){
	$tpl->assign('showExmInfoMsg', 1);
	$tpl->assign('exmInfoMessage', array('text'=>html_entity_decode($_SESSION['exmMsg']['text']),'level'=>$_SESSION['exmMsg']['level']));
	unset($_SESSION['exmMsg']);
}

$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$res=new AHresource($id);
//Verificamos si el usuario tiene permisos de edicion
if (!$xoopsUser){
	redirect_header(XOOPS_URL.'/modules/ahelp',2,_MS_AH_NOTPERMEDIT);
	die();
}else{
	if (!($xoopsUser->uid()==$res->owner()) && 
	!$res->isEditor($xoopsUser->uid()) && 
	!$xoopsUser->isAdmin()){
		redirect_header(XOOPS_URL.'/modules/ahelp',2,_MS_AH_NOTPERMEDIT);
		die();
	}
}


/**
* @desc Visualiza todas las referencias existentes de la publicación
**/
function references($edit=0){
	global $db,$tpl,$util,$xoopsUser;
	$myts=&MyTextSanitizer::getInstance();
	
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$search=isset($_REQUEST['search']) ? $myts->addSlashes($_REQUEST['search']) : '';
	$id_ref=isset($_REQUEST['ref']) ? intval($_REQUEST['ref']) : 0;
	$id_editor=isset($_REQUEST['editor']) ? intval($_REQUEST['editor']) : 0;

	
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
	
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
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
	
    if ($tpages > 1) {
    	$nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit.'&id='.$id.'&text='.$id_text.'&search='.$search, 0);
    	$tpl->assign('refNavPage', $nav->renderNav(4, 1));
    }

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_MS_AH_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin navegador de páginas

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
	while ($rows=$db->fetchArray($result)){
		$tpl->append('references',array('id'=>$rows['id_ref'],'title'=>$rows['title']));
	}

	$tpl->assign('lang_id',_MS_AH_ID);
	$tpl->assign('lang_title',_MS_AH_TITLE);
	$tpl->assign('lang_insert',_MS_AH_INSERT);
	$tpl->assign('lang_new',_MS_AH_NEW);
	$tpl->assign('lang_submit',_SUBMIT);
	$tpl->assign('lang_close',_CLOSE);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_exist',_MS_AH_EXIST);
	$tpl->assign('id',$id);
	$tpl->assign('lang_confirm',_MS_AH_CONFIRM);
	$tpl->assign('lang_result',_MS_AH_RESULT);
	$tpl->assign('lang_search',_MS_AH_SEARCH);
	$tpl->assign('search',$search);
	$tpl->assign('token', $util->getTokenHTML());
	$tpl->assign('lang_references',_MS_AH_REFEREN);
	$tpl->assign('id_editor',$id_editor);
	
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

	}
	//Formulario de nueva referencia
	$form= new RMForm($edit ? _MS_AH_EDIT : _MS_AH_NEW,'frmref2','references.php');
	$form->addElement(new RMText(_MS_AH_TITLE,'title',40,150,$edit ? $ref->title() : ''),true);
	$form->addElement(new RMEditor(_MS_AH_REFERENCE,'reference','90%','200px',$edit ? $ref->reference() : '', 'textarea'),true);
	
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

	echo $tpl->fetch('db:ahelp_references.html');

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
