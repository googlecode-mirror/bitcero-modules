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
* @desc Visualiza todas la imágenes de favoritos
**/
function showBookMarks(){

	global $xoopsOption, $tpl, $db, $xoopsUser, $xoopsModuleConfig, $pag;
	

	$xoopsOption['template_main'] = 'gs_panel_bookmarks.html';
	include 'header.php';

	$mc =& $xoopsModuleConfig;

	GSFunctions::makeHeader();
	
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_favourites')." a INNER JOIN ".$db->prefix('gs_images')." b ON (";
	$sql.= " a.id_image=b.id_image AND a.uid='".$xoopsUser->uid()."')";

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
			    $urlnav = 'cpanel/bookmarks/';
		    }else{
			    $urlnav = '?cpanel=bookmarks';
		    }
		    
            $nav = new RMPageNav($num, $limit, $pactual, 5);
            $nav->target_url(GSFunctions::get_url().$urlnav.($mc['urlmode'] ? 'pag/{PAGE_NUM}/' : '&amp;pag={PAGE_NUM}'));
            $tpl->assign('bookmarksNavPage', $nav->render(false));

    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	if ($num>0) $tpl->assign('lang_showing', sprintf(__('Showing pictures %u to %u from %u'), $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);	
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación


	$sql = "SELECT * FROM ".$db->prefix('gs_favourites')." a INNER JOIN ".$db->prefix('gs_images')." b ON (";
	$sql.= " a.id_image=b.id_image AND a.uid='".$xoopsUser->uid()."')";
	$sql.= " LIMIT $start,$limit";
	$result = $db->query($sql);
	$users = array();
	while ($rows = $db->fetchArray($result)){
		$img = new GSImage();
		$img->assignVars($rows);

		if (!isset($users[$img->owner()])) $users[$img->owner()] = new GSUser($img->owner(), 1);
		$urlimg = $users[$img->owner()]->filesURL().'/ths/'.$img->image();
		$link = $users[$img->owner()]->userURL().'img/'.$img->id().'/';

		$tpl->append('images',array('id'=>$img->id(),'title'=>$img->title(false),'desc'=>$img->desc(),
		'public'=>$img->isPublic(),'image'=>$urlimg,'created'=>formatTimeStamp($img->created(),'s'),
		'owner'=>$img->owner(),'uname'=>$users[$img->owner()]->uname(),'link'=>$link));
	}

	$tpl->assign('lang_exist',__('Existing Bookmarks','galleries'));
	$tpl->assign('lang_id',__('ID','galleries'));
	$tpl->assign('lang_title',__('Title','galleries'));
	$tpl->assign('lang_date',__('Created','galleries'));
	$tpl->assign('lang_owner',__('by user','galleries'));
	$tpl->assign('lang_image',__('Picture','galleries'));
	$tpl->assign('lang_public',__('Public','galleries'));
	$tpl->assign('lang_options',__('Options','galleries'));
	$tpl->assign('lang_edit',__('Edit','galleries'));
	$tpl->assign('lang_del', __('Delete','galleries'));
	$tpl->assign('lang_confirm',_MS_GS_CONFIRMBK);
	$tpl->assign('lang_confirms',_MS_GS_CONFIRMBKS);
	
	
	RMTemplate::get()->add_style('panel.css', 'galleries');
	
	createLinks();

	include 'footer.php';
}


/**
* @desc Marca Imagen como favorita
**/
function addBookMarks(){

	global $xoopsUser, $db, $add, $xoopsModuleConfig,$referer;

	$mc =& $xoopsModuleConfig;

	$referer = base64_decode($referer);	

	if (!$referer){
		$referer = XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? 'cpanel/bookmarks/' : 'cpanel.php?s=cpanel/bookmarks');
	}

	//Verificamos que la imagen sea válida
	if($add<=0){
		redirect_header($referer,1,_MS_GS_ERRIMGVALID);
		die();
	}

	//Verificamos que la imagen exista
	$img = new GSImage($add);
	if($img->isNew()){
		redirect_header($referer,1,_MS_GS_ERRIMGEXIST);
		die();
	}

	//Verificamos que la imagen sea pública o de amigos
	if ($img->isPublic()==0){
		redirect_header($referer,1,_MS_GS_ERRPRIVACY);
		die();
	}

	$user = new GSUser($img->owner(),1);
	//Verificamos si el usuario es amigo del dueño de la imagen
	if ($img->isPublic()==1){
	
		if(!$user->isFriend($xoopsUser->uid())){
			redirect_header($referer,1,_MS_GS_ERRNOTFRIEND);
			die();
		}
	}

	//Verificamos si la imagen se encuentra ya registrada en favoritos
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_favourites')." WHERE uid='".$xoopsUser->uid()."' AND id_image='".$img->id()."'";
	list($num) = $db->fetchRow($db->query($sql));
	if($num>0){
		redirect_header($referer,1,_MS_GS_ERRIMGADD);
		die();
	}

	//Agregamos la imagen a favoritos
	$sql = "INSERT INTO ".$db->prefix('gs_favourites')." (`uid`,`id_image`) VALUES ('".$xoopsUser->uid()."','".$img->id()."')";
	$result = $db->queryF($sql);
	
	if(!$result){
		redirect_header($referer,1,_MS_GS_DBERROR);
		die();
	}else{
		redirect_header($referer,1,_MS_GS_DBOK);
		die();
	}

}


/**
*  @desc Elimina una imagen de favoritos
**/
function deleteBookMarks(){

	global $xoopsUser, $db, $xoopsModuleConfig;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';

	$mc =& $xoopsModuleConfig;
	

	$link = XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? 'cpanel/bookmarks/pag/'.$page : 'cpanel.php?s=cpanel/bookmarks/pag/'.$page);

	//Verificamos si nos proporcionaron al menos un imagen para eliminar
	if (!is_array($ids) && $ids<=0){
		redirect_header($link,2,_MS_GS_ERRIMGDELETE);
		die();
	}

	if (!is_array($ids)){
		$ids = array($ids);
	}
	
	
	$errors = '';
	foreach ($ids as $k){

		//Verificamos si la imagen sea válida
		if($k<=0){
			$errors .= sprintf(_MS_GS_ERRNOTVALID, $k);
			continue;			
		}

		//Verificamos si la imagen existe
		$img = new GSImage($k);
		if ($img->isNew()){
			$errors .= sprintf(_MS_GS_ERRNOTEXIST, $k);
			continue;
		}	

		$sql = "DELETE FROM ".$db->prefix('gs_favourites')." WHERE id_image='".$k."' AND uid='".$xoopsUser->uid()."'";
		$result = $db->queryF($sql);

		if(!$result){
			$errors .= sprintf(_MS_GS_ERRDELETE, $k);
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
