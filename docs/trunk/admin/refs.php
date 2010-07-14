<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('AH_LOCATION', 'refs');
include 'header.php';

/**
* @desc Muestra todas las referencias existentes
**/
function showReferences(){
	global $tpl,$db,$xoopsModule,$util,$adminTemplate;

	$id_res=isset($_REQUEST['res']) ? intval($_REQUEST['res']) : 0;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	
	//Separamos frase en palabras
	$words=explode(" ",$search);

	//Navegador de páginas
	$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_references').($id_res ? " WHERE id_res='$id_res'" : '');
	$sql1='';
	if ($search){
		foreach ($words as $k){
			//verifica que palabra sea mayor a 2 letras	
			if (strlen($k)<=2) continue;
			$sql1.=($sql1=='' ? ($id_res ? " AND " : " WHERE ") : " OR ")." (title LIKE '%$k%' OR text LIKE '%$k%')";	

		}	
	}
	
	list($num) = $db->fetchRow($db->query($sql.$sql1));
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
		$nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit.'&search='.$search.'&res='.$id_res, 0);
		$tpl->assign('refNavPage', $nav->renderNav(4, 1));
	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_AS_AH_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);

	//Fin de navegador de páginas	

	$sql = str_replace("COUNT(*)","*", $sql);
        $sql2=" LIMIT $start,$limit";
	$result=$db->queryF($sql.$sql1.$sql2);
	while ($rows=$db->fetchArray($result)){
		$ref= new AHReference();
		$ref->assignVars($rows);

		$res=new AHResource($ref->resource());
	
		$tpl->append('refs',array('id'=>$ref->id(),'title'=>$ref->title(),'text'=>substr($util->filterTags($ref->reference()),0,50)."...",
                'resource'=>$res->title()));
	
	}

	//Lista de publicaciones
	$sql="SELECT id_res,title FROM ".$db->prefix('pa_resources');
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$res=new AHResource();
		$res->assignVars($rows);

		$tpl->append('resources',array('id'=>$res->id(),'title'=>$res->title()));

	}

	$tpl->assign('lang_id',_AS_AH_ID);
	$tpl->assign('lang_title',_AS_AH_TITLE);
	$tpl->assign('lang_text',_AS_AH_TEXT);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_res',_AS_AH_RESOURCE);
	$tpl->assign('lang_exist',_AS_AH_EXIST);
	$tpl->assign('lang_select',_SELECT);
	$tpl->assign('lang_search',_AS_AH_SEARCH);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_result',_AS_AH_RESULT);
	$tpl->assign('lang_submit',_SUBMIT);
	$tpl->assign('res',$id_res);
	$tpl->assign('search',$search);
	$tpl->assign('token', $util->getTokenHTML());
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_REFS);
	$adminTemplate = 'admin/ahelp_refs.html';
	xoops_cp_header();
	 
	xoops_cp_footer();

}

/**
* desc Edita Referencias
**/
function editReferences(){
	global $xoopsModule;

	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_REFS);
	$adminTemplate = 'admin/ahelp_refs.html';
	xoops_cp_header();

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
        $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$id_res=isset($_REQUEST['res']) ? intval($_REQUEST['res']) : 0;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta="?res=$id_res&search=$search&limit=$limit&pag=$page";

	//Verifica que referencia sea válida
	if ($id<=0){
		redirectMsg('./refs.php'.$ruta,_AS_AH_REFNOTVALID,1);
		die();
	}

	//Verifica que referencia exista
	$ref= new AHReference($id);
	if ($ref->isNew()){
		redirectMsg('./refs.php'.$ruta,_AS_AH_REFNOTEXIST,1);
		die();
	}

	//Formulario
	$form=new RMForm(_AS_AH_EDIT,'frmref','refs.php');
	
	$form->addElement(new RMText(_AS_AH_TITLE,'title',50,150,$ref->title()),true);
	$form->addElement(new RMTextArea(_AS_AH_TEXT,'reference',5,50,$ref->reference()),true);

	$buttons=new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button','onclick="window.location=\'refs.php'.$ruta.'\';"');

	$form->addElement($buttons);


	$form->addElement(new RMHidden('op','save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('res',$id_res));
	$form->addElement(new RMHidden('search',$search));
	$form->addElement(new RMHidden('limit',$limit));
	$form->addElement(new RMHidden('pag',$page));
	
	$form->display();
	
	xoops_cp_footer();

}


/**
* @desc Almacena información perteneciente a referencia
**/
function saveReferences(){
	global $db,$util;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	$ruta="?res=$id_res&search=$search&limit=$limit&pag=$pag";

	if (!$util->validateToken()){
		redirectMsg('./refs.php'.$ruta,_AS_AH_SESSINVALID,1);
		die();
	}

	//Verifica que referencia sea válida
	if ($id<=0){
		redirectMsg('./refs.php'.$ruta,_AS_AH_REFNOTVALID,1);
		die();
	}

	//Verifica que referencia exista
	$ref= new AHReference($id);
	if ($ref->isNew()){
		redirectMsg('./refs.php'.$ruta,_AS_AH_REFNOTEXIST,1);
		die();
	}

	
	//Comprobar si el título de la referencia en esa publicación existe
	$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_references')." WHERE title='$title' AND id_res='$id' AND id_ref<>'$id'";
	list($num)=$db->fetchRow($db->queryF($sql));
	if ($num>0){
		redirectMsg('./refs.php'.$ruta,_AS_AH_TITLEEXIST,1);
		die();
	}


	$ref->setTitle($title);
	$ref->setReference($reference);

	if ($ref->save()){
		redirectMsg('./refs.php'.$ruta,_AS_AH_DBOK,0);
		die();
	}else{
		redirectMsg('./refs.php'.$ruta,_AS_AH_DBERROR,1);
		die();
	}
	

}


/**
* @desc Elimina referencias
**/
function deleteReferences(){
	global $xoopsModule,$util;

	$references=isset($_REQUEST['refs']) ? $_REQUEST['refs'] : null;
	$ok=isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
        $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$id_res=isset($_REQUEST['res']) ? intval($_REQUEST['res']) : 0;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta="?res=$id_res&search=$search&limit=$limit&pag=$page";

	//Comprueba si se proporciono una referencia 
	if (!is_array($references) && $references<=0){
		redirectMsg('./refs.php'.$ruta,_AS_AH_NOTREF,1);
		die();	
	}

	if (!is_array($references)) $references=array($references);
	
	if ($ok){	
		
		if (!$util->validateToken()){
			redirectMsg('./refs.php'.$ruta,_AS_AH_SESSINVALID,1);
			die();
		}

		$errors='';

		foreach ($references as $k){
			//Determina si la referencia es válida
			if ($k<=0){
				$errors.=sprintf(_AS_AH_NOTVALID,$k);
				continue;
			}

			//Determina si referencia existe
			$ref=new AHReference($k);
			if ($ref->isNew()){
				$errors.=sprintf(_AS_AH_NOTEXIST,$k);
				continue;
			}

			//Elimina Referencia
			if (!$ref->delete()){
				$errors.=sprintf(_AS_AH_NOTDELETE,$k);
			}


		}

		if ($errors!=''){
			redirectMsg('./refs.php'.$ruta,_AS_AH_ERRORS.$errors,1);
			die();

		}else{
			redirectMsg('./refs.php'.$ruta,_AS_AH_DBOK,0);
			die();

		}
		
	
	
	}else{
		
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_AH_REFS);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['refs[]'] = $references;
		$hiddens['op'] = 'delete';
		$hiddens['res'] = $id_res;
		$hiddens['search'] = $search;
		$hiddens['limit'] = $limit;
		$hiddens['pag'] = $page;
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'refs.php'.$ruta.'\';"';
		
		$util->msgBox($hiddens, 'refs.php', _AS_AH_DELETECONF. '<br /><br />' ._AS_AH_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
		
		xoops_cp_footer();

	}


}


$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op){
	case 'edit':
		editReferences();
	break;
	case 'save':
		saveReferences();
	break;
	case 'delete':
		deleteReferences();
	break;
	default:
		showReferences();

}


?>
