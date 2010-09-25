<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------
// @copyright: 2008 Red México

/**
* @desc Visualiza todos los albumes existentes del usuario
**/
function showSets($edit = 0){
	global $xoopsOption, $tpl, $db, $exmUser, $xoopsModuleConfig, $pag;
	
	$xoopsOption['template_main'] = 'gs_panel_sets.html';
	include 'header.php';

	$mc =& $xoopsModuleConfig;

	GSFunctions::makeHeader();

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

	$link = GS_URL.($mc['urlmode'] ? '/cpanel/sets/pag/'.$pag : '/cpanel.php?s=cpanel/sets/'.$pag);


	if($edit){

		//Verificamos que el album sea válido
		if($id<=0){
			redirect_header($link,1,_MS_GS_ERRSETVALID);
			die();
		}

		//Verificamos que el album exista
		$set = new GSSet($id);
		if($set->isNew()){
			redirect_header($link,1,_MS_GS_ERRSETEXIST);
			die();
		}
	
		$tpl->assign('title',$set->title());
		$tpl->assign('public',$set->isPublic());
		$tpl->assign('edit',$edit);
		$tpl->assign('id',$id);		

	}


	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_sets')." WHERE owner='".$exmUser->uid()."'";
	$page = isset($pag) ? $pag : '';
	$limit = 10;


	list($num)=$db->fetchRow($db->query($sql));
	
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
		if($mc['urlmode']){
			$urlnav = 'cpanel/sets';
		}else{
			$urlnav = 'cpanel.php?by=cpanel/sets';
		}
		 

    	    $nav = new GsPageNav($num, $limit, $start, 'pag',$urlnav,0);
    	    $tpl->assign('setsNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_MS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);	
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación



	$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE owner='".$exmUser->uid()."'";
	$sql.=" LIMIT $start,$limit";
	$result = $db->query($sql);
	while($rows = $db->fetchArray($result)){
		$set = new GSSet();
		$set->assignVars($rows);

		$tpl->append('sets',array('id'=>$set->id(),'name'=>$set->title(),'owner'=>$set->owner(),'uname'=>$set->uname(),
		'public'=>$set->isPublic(),'date'=>formatTimeStamp($set->date(),'s'),'pics'=>$set->pics()));		

	}

	$tpl->assign('lang_setexists',_MS_GS_SETEXISTS);
	$tpl->assign('lang_id',_MS_GS_ID);
	$tpl->assign('lang_name',_MS_GS_NAME);
	$tpl->assign('lang_date',_MS_GS_DATE);
	$tpl->assign('lang_public',_MS_GS_SETPRIVACY);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_confirm',_MS_GS_CONFIRMSET);
	$tpl->assign('lang_confirms',_MS_GS_CONFIRMSETS);
	$tpl->assign('lang_newset',_MS_GS_NEWSET);
	$tpl->assign('lang_editset',_MS_GS_EDITSET);
	$tpl->assign('lang_yes',_YES);
	$tpl->assign('lang_no',_NO);
	$tpl->assign('lang_pics',_MS_GS_PICS);
	$tpl->assign('lang_privateme',_MS_GS_PRIVATEME);
	$tpl->assign('lang_privatef',_MS_GS_PRIVATEF);
	$tpl->assign('lang_publicset',_MS_GS_PUBLICSET);

	$tpl->assign('link_imgs',GS_URL.($mc['urlmode'] ? '/cpanel/imgs/set' : '/cpanel.php?s=cpanel/imgs/set'));
	

	$xmh.= '<link rel="stylesheet" type="text/css" media="screen" href="'.GS_URL.'/styles/panel.css" />';
	
	createLinks();

	include 'footer.php';

}


/**
* @desc Almacena la información del album en la base de datos
**/
function saveSets($edit = 0){

	global $exmUser, $xoopsModuleConfig;

	$mc =& $xoopsModuleConfig;

	foreach ($_POST as $k => $v){
		$$k = $v;
	}
		
	$link = XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? 'cpanel/sets/pag/'.$pag : 'cpanel.php?s=cpanel/sets/pag/'.$pag);

	if ($edit){

		//Verificamos que el album sea válido
		if($id<=0){
			redirect_header($link,1,_MS_GS_ERRSETVALID);
			die();
		}

		//Verificamos que el album exista
		$set = new GSSet($id);
		if($set->isNew()){
			redirect_header($link,1,_MS_GS_ERRSETEXIST);
			die();
		}

	}else{
		$set = new GSSet();
	}

	$set->setTitle($title);
	$set->setPublic($public);
	$set->setOwner($exmUser->uid());
	$set->setUname($exmUser->uname());
	$set->setDate(time());

	if (!$set->save()){
		redirect_header($link,1,_MS_GS_DBERROR);
		die();
	}else{
		redirect_header($link,1,_MS_GS_DBOK);
		die();
	}

}


/**
* @desc Elimina de la base de datos la información del album especificado
**/
function deleteSets(){

	global $util, $xoopsModule, $db,$xoopsModuleConfig;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$pag = isset($_REQUEST['pag']) ? intval($_REQUEST['pag']) : '';

	$mc =& $xoopsModuleConfig;
	

	$link = XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? 'cpanel/sets/pag/'.$pag : 'cpanel.php?s=cpanel/sets/pag/'.$pag);

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


?>
