<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','tags');
include 'header.php';

/**
* @desc Visualiza todas los etiquetas existentes
**/
function showTags(){
	
	global $xoopsModule, $xoopsSecurity, $xoopsModuleConfig;
	
	$db = Database::getInstance();	
	$mc =&$xoopsModuleConfig;

	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	$page = $page<1 ? 1 : $page;
	$limit = 15;
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
	$start = $num<=0 ? 0 : ($page - 1) * $limit;
    $tpages = ceil($num / $limit);	
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url("tags.php?search=".urlencode($search)."&amp;page={PAGE_NUM}");
    
	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	//Fin de barra de navegación
	
	$sql = "SELECT * FROM ".$db->prefix('gs_tags');
	$sql2 = " LIMIT $start,$limit";
	$result = $db->query($sql.$sql1.$sql2);
    
    $tags = array();
    
	while ($rows = $db->fetchArray($result)){

		foreach ($words as $k){
			$rows['tag'] = eregi_replace("($k)","<span class='searchResalte'>\\1</span>", $rows['tag']);
		}

		$tag = new GSTag();
		$tag->assignVars($rows);

		//Obtenemos todas las imágenes pertenecientes a la etiqueta
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_tagsimages')." WHERE id_tag=".$tag->id();
		list($pics) = $db->fetchRow($db->query($sql));
	
		$tags[] = array('id'=>$tag->id(),'name'=>$tag->tag(),'pics'=>$pics,'url'=>$tag->url());

	}

	GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Tags Management','galleries'));
	RMTemplate::get()->assign('xoops_pagetitle',__('Tags Management','galleries'));
	$cHead = '<link href="'.XOOPS_URL.'/modules/galleries/styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
	xoops_cp_header($cHead);
	
	include RMTemplate::get()->get_template("admin/gs_tags.php",'module','galleries');
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	RMTemplate::get()->add_script('../include/js/gsscripts.php?file=sets&form=frm-tags');
	RMTemplate::get()->add_script('../include/js/gsscripts.php?file=tags');
	RMTemplate::get()->add_head("<script type='text/javascript'>\nvar delete_warning='".__('Do you really wish to delete selected tags?','galleries')."';\n</script>");
	xoops_cp_footer();

}


/**
* @desc Formulario de creación/Edición de etiquetas
**/
function formTags($edit = 0){

	global $xoopsModule;

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "pag=$page&search=$search";


	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$num = 10;
	
	if($edit){

		//Verificamos si nos proporcionaron al menos una etiqueta para editar
		if (!is_array($ids) && $ids<=0){
			redirectMsg('./tags.php?'.$ruta,__('Specify some valid IDs','galleries'),1);
			die();
		}

		if (!is_array($ids)){
			$ids = array($ids);
		}

	}


	GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./tags.php'>".__('Tags Management','galleries')."</a> &raquo; ".($edit ? _AS_GS_EDITTAG : _AS_GS_NEWTAG));
	RMTemplate::get()->assign('xoops_pagetitle',$edit ? __('Edit Tags','galleries') : __('Add Tags','galleries'));
	xoops_cp_header();

	$form = new RMForm($edit ? __('Editing tags','galleries') : __('Add tags','galleries'),'frmtags','tags.php');
	
	if($edit){
		$errors = '';
		foreach ($ids as $k){
			
			//Verificamos si la etiqueta es válida
			if($k<=0){
				$errors .= sprintf(__('ID "%s" is not valid','galleries'), $k);
				continue;			
			}

			//Verificamos si la etiqueta existe
			$tag = new GSTag($k);
			if ($tag->isNew()){
				$errors .= sprintf(__('Tag "%s" does not exists','galleries'), $k);
				continue;
			}	

			$form->addElement(new RMFormText(__('Tag name','galleries'),'tags['.$tag->id().']',50,100,$edit ? $tag->tag() : ''));
		}
			

	}else{


		for ($i=0; $i<$num; $i++){
			$form->addElement(new RMFormText(__('Tag name','galleries'),'tags['.$i.']',50,100,$edit ? $tag->tag() : ''));
		}
	}

	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMFormHidden('page',$page));	
	$form->addElement(new RMFormHidden('search',$search));


	$buttons = new RMFormButtonGroup();
	$buttons->addButton('sbt',$edit ? __('Save Changes','galleries') : __('Create tags','galleries'),'submit');
	$buttons->addButton('cancel',__('Cancel','galleries'),'button','onclick="window.location=\'tags.php?'.$ruta.'\'"');

	$form->addElement($buttons);

	$form->display();
	
	xoops_cp_footer();


}


/**
* @desc Almacena la información de la etiqueta en la base de datos
**/
function saveTags($edit = 0){
	
	global $xoopsModuleConfig, $xoopsSecurity;

	$mc =&$xoopsModuleConfig;
	$db = Database::getInstance();

	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	$ruta = "&pag=$page&search=$search";

	if (!$xoopsSecurity->check()){
		redirectMsg('./tags.php?'.$ruta,__('Session token expired!','galleries'),1);
		die();
	}


	if ($edit){
		$errors = '';
		foreach ($tags as $k => $v){
			$v = trim($v);
			
			if (!$v) continue;

			//Verificamos el tamaño máximo y mínimo de la etiqueta
			if (strlen($v)<$mc['min_tag'] || strlen($v)>$mc['max_tag']){
				$errors .=sprintf(__('Tag "%s" does not have the correct length!','galleries'), $v);
				continue;
			}

			//Verificamos que la etiqueta no exista
			$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_tags')." WHERE tag='$v' AND id_tag<>$k";
			list($num) = $db->fetchRow($db->query($sql));
			if ($num>0){
				$errors .= sprintf(__('There are another tag with name "%s" already registered!','galleries'), $v);
				continue;
			}

			//Verificamos si la etiqueta es válida
			if($k<=0){
				$errors .= sprintf(__('ID "%s" is not valid','galleries'), $k);
				continue;			
			}

			//Verificamos si la etiqueta existe
			$tag = new GSTag($k);
			if ($tag->isNew()){
				$errors .= sprintf('Tag "%s" does not exists', $v);
				continue;
			}			

			$tag->setTag(strtolower($v));			
			
			if (!$tag->save()){
				$errors .= sprintf(__('Errors while trying to save tag "%s"','galleries'), $v);
			}
			
		}

	}else{
		$errors = '';
		foreach ($tags as $k => $v){
			$v = trim($v);
			
			if (!$v) continue;

			//Verificamos el tamaño máximo y mínimo de la etiqueta
			if (strlen($v)<$mc['min_tag'] || strlen($v)>$mc['max_tag']){
				$errors .=sprintf(__('Tag "%s" does not have the correct length!','galleries'), $v);
				continue;
			}


			//Verificamos que la etiqueta no exista
			$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_tags')." WHERE tag='$v'";
			list($num) = $db->fetchRow($db->query($sql));
			if ($num>0){
				$errors .= sprintf(__('There are another tag with name "%s" already registered!','galleries'), $v);
				continue;
			}

			$tag = new GSTag();
			$tag->setTag(strtolower($v));			
			
			if (!$tag->save()){
				$errors .= sprintf(__('Errors while trying to save tag "%s"','galleries'), $v);
			}
			
		}

		
	}

	if ($errors!=''){
		redirectMsg('./tags.php?'.$ruta,__('Errors ocurred while trying to save tags.','galleries').'<br />'.$errors,1);
		die();
	}else{
		redirectMsg('./tags.php?'.$ruta,__('Tags saved successfully!','galleries'),0);
		die();
	}

}

/**
* @desc Elimina las etiquetas especificadas de la base de datos
**/
function deleteTags(){

	global $xoopsSecurity, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

	$ruta = "&pag=$page&search=$search";

	
	//Verificamos si nos proporcionaron al menos una etiqueta para eliminar
	if (!is_array($ids)){
		redirectMsg('./tags.php?'.$ruta,__('Select at least one tag to delete','galleries'),1);
		die();
	}


	if (!$xoopsSecurity->check()){
		redirectMsg('./tags.php?'.$ruta,__('Session token expired!','galleries'),1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){
		//Verificamos si la etiqueta es válida
		if($k<=0){
			$errors .= sprintf(__('ID "%s" is not valid','galleries'), $k);
			continue;			
		}

		//Verificamos si la etiqueta existe
		$tag = new GSTag($k);
		if ($tag->isNew()){
			$errors .= sprintf(__('Tag "%s" does not exists','galleries'), $k);
			continue;
		}	

		if(!$tag->delete()){
			$errors .= sprintf(__('Tag "%s" could not be deleted!'), $k);
		}
	}

	if($erros!=''){
		redirectMsg('./tags.php?'.$ruta,__('Errors ocurred while trying to delete tags.','galleries').'<br />'.$errors,1);
		die();
	}else{
		redirectMsg('./tags.php?'.$ruta,__('Tags deleted successfully!','galleries'),0);
		die();
	}

}


$op = rmc_server_var($_REQUEST, 'op', '');

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
