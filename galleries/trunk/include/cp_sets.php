<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* @desc Visualiza todos los albumes existentes del usuario
**/
function showSets($edit = 0){
	global $xoopsOption, $tpl, $db, $xoopsUser, $xoopsModuleConfig, $page, $xoopsConfig;
	
	$xoopsOption['template_main'] = 'gs_panel_sets.html';
	include 'header.php';

	$mc =& $xoopsModuleConfig;
    $limit = rmc_server_var($_REQUEST, 'limit', 15);

	GSFunctions::makeHeader();

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

	$link = GSFunctions::get_url().($mc['urlmode'] ? '/cpanel/sets/pag/'.$page : '/cpanel.php?s=cpanel/sets/'.$page);


	if($edit){

		//Verificamos que el album sea válido
		if($id<=0){
			redirect_header($link,1, __('Album ID is not valid!','galleries'));
			die();
		}

		//Verificamos que el album exista
		$set = new GSSet($id);
		if($set->isNew()){
			redirect_header($link,1, __('Specified album does not exists!','galleries'));
			die();
		}
	
		$tpl->assign('title',$set->title());
		$tpl->assign('public',$set->isPublic());
		$tpl->assign('edit',$edit);
		$tpl->assign('id',$id);
        $tpl->assign('action_editset', GSFunctions::get_url().($mc['urlmode'] ? 'cp/saveeditset/pag/'.$page.'/' : '?cp=saveeditset&amp;pag='.$page));

	}

	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_sets')." WHERE owner='".$xoopsUser->uid()."'";
	list($num)=$db->fetchRow($db->query($sql));
    
    $page = $page<=0 ? 1 : $page;
    list($num)=$db->fetchRow($db->query($sql));
    $start = $num<=0 ? 0 : ($page-1) * $limit;
    $tpages =ceil($num / $limit);
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url(GSFunctions::get_url().($mc['urlmode'] ? 'cp/sets/pag/{PAGE_NUM}/' : '?cp=sets&amp;pag={PAGE_NUM}'));
    //Fin de barra de navegación
    
	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE owner='".$xoopsUser->uid()."'";
	$sql.=" LIMIT $start,$limit";
	$result = $db->query($sql);
	while($rows = $db->fetchArray($result)){
		$set = new GSSet();
		$set->assignVars($rows);

		$tpl->append('sets',array(
            'id'=>$set->id(),
            'name'=>$set->title(),
            'owner'=>$set->owner(),
            'uname'=>$set->uname(),
		    'public'=>$set->isPublic(),
            'date'=>formatTimeStamp($set->date(),'s'),
            'pics'=>$set->pics(),
            'link'=>$set->url())
        );		

	}

	$tpl->assign('lang_setexists',__('My Albums','galleries'));
	$tpl->assign('lang_id', __('ID','galleries'));
	$tpl->assign('lang_name', __('Name','galleries'));
	$tpl->assign('lang_date', __('Date','galleries'));
	$tpl->assign('lang_public',__('Privacy:','galleries'));
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_confirm',__('Do you really wish to delete specified album?','galleries'));
	$tpl->assign('lang_confirms',__('Do you really wish to delete selected albums?','galleries'));
	$tpl->assign('lang_newset', __('Add Album','galleries'));
	$tpl->assign('lang_editset', __('Edit Album','galleries'));
	$tpl->assign('lang_yes', __('Yes','galleries'));
	$tpl->assign('lang_no', __('No','galleries'));
	$tpl->assign('lang_pics', __('Pictures','galleries'));
	$tpl->assign('lang_privateme', __('Private','galleries'));
	$tpl->assign('lang_privatef',__('Friends','galleries'));
	$tpl->assign('lang_publicset', __('Public','galleries'));
	
	RMTemplate::get()->add_style('panel.css', 'galleries');
    
    $tpl->assign('action_addset', GSFunctions::get_url().($mc['urlmode'] ? 'cp/saveset/pag/'.$page.'/' : '?cp=saveset&amp;pag='.$page));
	
	createLinks();

	include 'footer.php';

}


/**
* @desc Almacena la información del album en la base de datos
**/
function saveSets($edit = 0){

	global $xoopsUser, $xoopsModuleConfig, $page;

	$mc =& $xoopsModuleConfig;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}
		
	$link = GSFunctions::get_url().($mc['urlmode'] ? 'cp/sets/pag/'.$page : '?cp=sets&amp;pag='.$pag);

	if ($edit){

		//Verificamos que el album sea válido
		if($id<=0){
			redirect_header($link,1,__('Provided ID is not valid!','galleries'));
			die();
		}

		//Verificamos que el album exista
		$set = new GSSet($id);
		if($set->isNew()){
			redirect_header($link,1, __('Specified album does not exists!','galleries'));
			die();
		}

	}else{
		$set = new GSSet();
	}

	$set->setTitle($title);
	$set->setPublic($public);
	$set->setOwner($xoopsUser->uid());
	$set->setUname($xoopsUser->uname());
	$set->setDate(time());

	if (!$set->save()){
		redirect_header($link,1, __('Album could not be saved. Please try again.','galleries'));
		die();
	}else{
		redirect_header($link,1, __('Album saved successfully!','galleries'));
		die();
	}

}


/**
* @desc Elimina de la base de datos la información del album especificado
**/
function deleteSets(){

	global $util, $xoopsModule, $db,$xoopsModuleConfig, $page;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;

	$mc =& $xoopsModuleConfig;
	

	$link = GSFunctions::get_url().($mc['urlmode'] ? 'cp/sets/pag/'.$pag.'/' : '?cp=sets&amp;pag='.$pag);

	//Verificamos si nos proporcionaron al menos un imagen para eliminar
	if (!is_array($ids) && $ids<=0){
		redirect_header($link,2,_MS_GS_ERRSETDELETE);
		die();
	}

	if (!is_array($ids)){
		$ids = array($ids);
	}
	
	$errors = '';
	foreach ($ids as $k){

		//Verificamos si el album es válido
		if($k<=0){
			$errors .= sprintf(_MS_GS_ERRNOTVALIDSET, $k);
			continue;			
		}

		//Verificamos si el album existe
		$set = new GSSet($k);
		if ($set->isNew()){
			$errors .= sprintf(_MS_GS_ERRNOTEXISTSET, $k);
			continue;
		}	

		if(!$set->delete()){
			$errors .= sprintf(_MS_GS_ERRDELETESET, $k);
		}
	}

	if($errors!=''){
		redirect_header($link,2,_MS_GS_DBERRORS.$errors);
		die();
	}else{
		redirect_header($link,1,_MS_GS_DBOK);
		die();
	}
		
}
