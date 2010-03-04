<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('GS_LOCATION','tags');
include 'header.php';

function optionsBar(){
	global $tpl;

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 45;
	$limit = $limit<=0 ? 45 : $limit;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "&pag=$page&limit=$limit&search=$search";

	
	$tpl->append('xoopsOptions', array('link' => './tags.php', 'title' => _AS_GS_TAGS, 'icon' => '../images/tags16.png'));
	$tpl->append('xoopsOptions', array('link' => './tags.php?op=new'.$ruta, 'title' => _AS_GS_NEW, 'icon' => '../images/add.png'));
	
}

/**
* @desc Visualiza todas los etiquetas existentes
**/
function showTags(){
	
	global $xoopsModule, $adminTemplate, $tpl, $db, $xoopsModuleConfig;
	
	$mc =&$xoopsModuleConfig;

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 45;
	$limit = $limit<=0 ? 45 : $limit;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_tags');
	$sql1 = '';
	$words = array();
	if ($search){
		
		//Separamos en palabras
		$words = explode(" ",$search);
		foreach ($words as $k){
			$k = trim($k);
			
			if (strlen($k)<$mc['min_tag'] || strlen($k)>$mc['max_tag']){
				continue;
			}
			
			$sql1.= $sql1=='' ? " WHERE (tag LIKE '%$k%')" : " OR (tag LIKE '%$k%')";			

		}
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
    	    $tpl->assign('tagsNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_AS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	$tpl->assign('search',$search);
	//Fin de barra de navegación



	
	$sql = "SELECT * FROM ".$db->prefix('gs_tags');
	$sql2 = " LIMIT $start,$limit";
	$result = $db->query($sql.$sql1.$sql2);
	while ($rows = $db->fetchArray($result)){

		foreach ($words as $k){
			$rows['tag'] = eregi_replace("($k)","<span class='searchResalte'>\\1</span>", $rows['tag']);
		}

		$tag = new GSTag();
		$tag->assignVars($rows);

		//Obtenemos todas las imágenes pertenecientes a la etiqueta
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_tagsimages')." WHERE id_tag=".$tag->id();
		list($pics) = $db->fetchRow($db->query($sql));
	
		$tpl->append('tags',array('id'=>$tag->id(),'name'=>$tag->tag(),'pics'=>$pics,'url'=>$tag->url()));

	}
	
	$tpl->assign('columns',3);
	for($i=0; $i<3; ++$i){
		$tpl->append('header',array());
	}

	$tpl->assign('lang_exist',_AS_GS_EXIST);
	$tpl->assign('lang_id',_AS_GS_ID);
	$tpl->assign('lang_tag',_AS_GS_TAG);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_pics',_AS_GS_PICS);
	$tpl->assign('lang_submit',_SUBMIT);
	$tpl->assign('lang_search',_AS_GS_SEARCH);

	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_GS_TAGSLOC);
	$adminTemplate = "admin/gs_tags.html";
	$cHead = '<link href="'.XOOPS_URL.'/modules/galleries/styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
	xoops_cp_header($cHead);
	
	xoops_cp_footer();

}


/**
* @desc Formulario de creación/Edición de etiquetas
**/
function formTags($edit = 0){

	global $xoopsModule, $adminTemplate, $tpl, $util;

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 45;
	$limit = $limit<=0 ? 45 : $limit;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&limit=$limit&search=$search";


	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$num = 10;
	
	if($edit){

		//Verificamos si nos proporcionaron al menos una etiqueta para editar
		if (!is_array($ids) && $ids<=0){
			redirectMsg('./tags.php?'.$ruta,_AS_GS_ERRTAGEDIT,1);
			die();
		}

		if (!is_array($ids)){
			$ids = array($ids);
		}

	}


	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./tags.php'>"._AS_GS_TAGSLOC."</a> &raquo; ".($edit ? _AS_GS_EDITTAG : _AS_GS_NEWTAG));
	xoops_cp_header();

	$form = new RMForm($edit ? _AS_GS_EDITTAG : _AS_GS_NEWTAG,'frmtags','tags.php');
	
	if($edit){
		$errors = '';
		foreach ($ids as $k){
			
			//Verificamos si la etiqueta es válida
			if($k<=0){
				$errors .= sprintf(_AS_GS_ERRNOTVALID, $k);
				continue;			
			}

			//Verificamos si la etiqueta existe
			$tag = new GSTag($k);
			if ($tag->isNew()){
				$errors .= sprintf(_AS_GS_ERRNOTEXIST, $k);
				continue;
			}	

			$form->addElement(new RMText(_AS_GS_NAME,'tags['.$tag->id().']',50,100,$edit ? $tag->tag() : ''));
		}
			

	}else{


		for ($i=0; $i<$num; $i++){
			$form->addElement(new RMText(_AS_GS_NAME,'tags['.$i.']',50,100,$edit ? $tag->tag() : ''));
		}
	}

	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('page',$page));	
	$form->addElement(new RMHidden('limit',$limit));	
	$form->addElement(new RMHidden('search',$search));


	$buttons = new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button','onclick="window.location=\'tags.php?'.$ruta.'\'"');

	$form->addElement($buttons);

	$form->display();
	
	xoops_cp_footer();


}


/**
* @desc Almacena la información de la etiqueta en la base de datos
**/
function saveTags($edit = 0){
	
	global $util, $db, $xoopsModuleConfig;

	$mc =&$xoopsModuleConfig;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	$ruta = "&pag=$page&limit=$limit&search=$search";

	if (!$util->validateToken()){
		redirectMsg('./tags.php?'.$ruta,_AS_GS_SESSINVALID,1);
		die();
	}


	if ($edit){
		$errors = '';
		foreach ($tags as $k => $v){
			$v = trim($v);
			
			if (!$v) continue;

			//Verificamos el tamaño máximo y mínimo de la etiqueta
			if (strlen($v)<$mc['min_tag'] || strlen($v)>$mc['max_tag']){
				$errors .=sprintf(_AS_GS_ERRSIZETAG, $v);
				continue;
			}

			//Verificamos que la etiqueta no exista
			$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_tags')." WHERE tag='$v' AND id_tag<>$k";
			list($num) = $db->fetchRow($db->query($sql));
			if ($num>0){
				$errors .= sprintf(_AS_GS_ERRNAME, $v);
				continue;
			}

			//Verificamos si la etiqueta es válida
			if($k<=0){
				$errors .= sprintf(_AS_GS_ERRNOTVALID, $k);
				continue;			
			}

			//Verificamos si la etiqueta existe
			$tag = new GSTag($k);
			if ($tag->isNew()){
				$errors .= sprintf(_AS_GS_ERRNOTEXIST, $k);
				continue;
			}			

			$tag->setTag(strtolower($v));			
			
			if (!$tag->save()){
				$errors .= sprintf(_AS_GS_ERRSAVE, $v);
			}
			
		}

	}else{
		$errors = '';
		foreach ($tags as $k => $v){
			$v = trim($v);
			
			if (!$v) continue;

			//Verificamos el tamaño máximo y mínimo de la etiqueta
			if (strlen($v)<$mc['min_tag'] || strlen($v)>$mc['max_tag']){
				$errors .=sprintf(_AS_GS_ERRSIZETAG, $v);
				continue;
			}


			//Verificamos que la etiqueta no exista
			$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_tags')." WHERE tag='$v'";
			list($num) = $db->fetchRow($db->query($sql));
			if ($num>0){
				$errors .= sprintf(_AS_GS_ERRNAME, $v);
				continue;
			}

			$tag = new GSTag();
			$tag->setTag(strtolower($v));			
			
			if (!$tag->save()){
				$errors .= sprintf(_AS_GS_ERRSAVE, $v);
			}
			
		}

		
	}

	if ($errors!=''){
		redirectMsg('./tags.php?'.$ruta,_AS_GS_DBERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./tags.php?'.$ruta,_AS_GS_DBOK,0);
		die();
	}

}

/**
* @desc Elimina las etiquetas especificadas de la base de datos
**/
function deleteTags(){

	global $util, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 45;
	$limit = $limit<=0 ? 45 : $limit;
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "&pag=$page&limit=$limit&search=$search";

	
	//Verificamos si nos proporcionaron al menos una etiqueta para eliminar
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./tags.php?'.$ruta,_AS_GS_ERRTAG,1);
		die();
	}

	if (!is_array($ids)){
		$tg = new GSTag($ids);
		$ids = array($ids);
	}

	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./tags.php?'.$ruta,_AS_GS_SESSINVALID,1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){
			
			//Verificamos si la etiqueta es válida
			if($k<=0){
				$errors .= sprintf(_AS_GS_ERRNOTVALID, $k);
				continue;			
			}

			//Verificamos si la etiqueta existe
			$tag = new GSTag($k);
			if ($tag->isNew()){
				$errors .= sprintf(_AS_GS_ERRNOTEXIST, $k);
				continue;
			}	

			if(!$tag->delete()){
				$errors .= sprintf(_AS_GS_ERRDELETE, $k);
			}
		}

		if($erros!=''){
			redirectMsg('./tags.php?'.$ruta,_AS_GS_DBERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./tags.php?'.$ruta,_AS_GS_DBOK,0);
			die();
		}
		
	}else{

		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./sets.php'>"._AS_GS_TAGSLOC."</a> &raquo; "._AS_GS_LOCDELETE);
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
		$buttons['cancel']['extra'] = 'onclick="window.location=\'tags.php?'.$ruta.'\';"';
		
		$util->msgBox($hiddens, 'tags.php',(isset($tg) ? sprintf(_AS_GS_DELETECONF, $tg->tag()) : _AS_GS_DELETECONFS). '<br /><br />' ._AS_GS_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}
	



}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'new':
		formTags();
	break;
	case 'edit':
		formTags(1);
	break;
	case 'save':
		saveTags();
	break;
	case 'saveedit':
		saveTags(1);
	break;
	case 'delete':
		deleteTags();
	break;
	default:
		showTags();
		break;
}
