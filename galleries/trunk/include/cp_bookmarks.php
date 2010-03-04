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
* @desc Visualiza todas la imágenes de favoritos
**/
function showBookMarks(){

	global $xoopsOption, $tpl, $db, $exmUser, $xoopsModuleConfig, $pag;
	

	$xoopsOption['template_main'] = 'gs_panel_bookmarks.html';
	include 'header.php';

	$mc =& $xoopsModuleConfig;

	GSFunctions::makeHeader();
	
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_favourites')." a INNER JOIN ".$db->prefix('gs_images')." b ON (";
	$sql.= " a.id_image=b.id_image AND a.uid='".$exmUser->uid()."')";

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
			$urlnav = 'cpanel/bookmarks';
		}else{
			$urlnav = 'cpanel.php?by=cpanel/bookmarks';
		}
		 

    	    $nav = new GsPageNav($num, $limit, $start, 'pag',$urlnav,0);
    	    $tpl->assign('bookmarksNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_MS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);	
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación


	$sql = "SELECT * FROM ".$db->prefix('gs_favourites')." a INNER JOIN ".$db->prefix('gs_images')." b ON (";
	$sql.= " a.id_image=b.id_image AND a.uid='".$exmUser->uid()."')";
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

	$tpl->assign('lang_exist',_MS_GS_EXISTBK);
	$tpl->assign('lang_id',_MS_GS_ID);
	$tpl->assign('lang_title',_MS_GS_TITLE);
	$tpl->assign('lang_date',_MS_GS_DATE);
	$tpl->assign('lang_owner',_MS_GS_USER);
	$tpl->assign('lang_image',_MS_GS_IMAGE);
	$tpl->assign('lang_public',_MS_GS_PUBLIC);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_confirm',_MS_GS_CONFIRMBK);
	$tpl->assign('lang_confirms',_MS_GS_CONFIRMBKS);
	
	
	$xmh.= '<link rel="stylesheet" type="text/css" media="screen" href="'.GS_URL.'/styles/panel.css" />';
	
	createLinks();

	include 'footer.php';
}


/**
* @desc Marca Imagen como favorita
**/
function addBookMarks(){

	global $exmUser, $db, $add, $xoopsModuleConfig,$referer;

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
	
		if(!$user->isFriend($exmUser->uid())){
			redirect_header($referer,1,_MS_GS_ERRNOTFRIEND);
			die();
		}
	}

	//Verificamos si la imagen se encuentra ya registrada en favoritos
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_favourites')." WHERE uid='".$exmUser->uid()."' AND id_image='".$img->id()."'";
	list($num) = $db->fetchRow($db->query($sql));
	if($num>0){
		redirect_header($referer,1,_MS_GS_ERRIMGADD);
		die();
	}

	//Agregamos la imagen a favoritos
	$sql = "INSERT INTO ".$db->prefix('gs_favourites')." (`uid`,`id_image`) VALUES ('".$exmUser->uid()."','".$img->id()."')";
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

	global $exmUser, $db, $xoopsModuleConfig;

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

		$sql = "DELETE FROM ".$db->prefix('gs_favourites')." WHERE id_image='".$k."' AND uid='".$exmUser->uid()."'";
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
