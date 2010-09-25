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
* @desc Visualiza la lista de amigos del usuario
**/
function showFriends(){

	global $xoopsOption, $tpl, $db, $exmUser, $xoopsModuleConfig,$pag;
	
	$xoopsOption['template_main'] = 'gs_panel_friends.html';
	include 'header.php';

	$mc =& $xoopsModuleConfig;

	GSFunctions::makeHeader();

	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_friends')." WHERE gsuser='".$exmUser->uid()."'";

	$page = isset($pag) ? $pag : '';
	$limit = 30;


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
			$urlnav = 'cpanel/friends';
		}else{
			$urlnav = 'cpanel.php?by=cpanel/friends';
		}
		 

    	    $nav = new GsPageNav($num, $limit, $start, 'pag',$urlnav,0);
    	    $tpl->assign('friendsNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_MS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);	
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación


	$sql = "SELECT * FROM ".$db->prefix('gs_friends')." WHERE gsuser='".$exmUser->uid()."'";
	$sql.=" LIMIT $start,$limit";
	$result = $db->query($sql);
	while($row = $db->fetchArray($result)){
		$xu = new ExmUser($row['uid']);
		
		$tpl->append('users',array('uid'=>$xu->uid(),'uname'=>$xu->uname(),
		'link'=>XOOPS_URL."/modules/galleries/".($mc['urlmode'] ? "usr/".$xu->uname()."/" : "user.php?id=usr/".$xu->uname()),
		'avatar'=>$xu->user_avatar()!='' ? XOOPS_URL.'/uploads/avatars/'.$xu->user_avatar() : GS_URL.'/images/avatar.png'));
	}

	$cols = 3;
	for ($i=1; $i<=$cols; $i++){
		$tpl->append('heads',array('i'=>$i));
	}

	$tpl->assign('lang_exist',_MS_GS_EXISTFRIEND);
	$tpl->assign('lang_uname',_MS_GS_UNAME);
	$tpl->assign('lang_user',_MS_GS_USER);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_newfriend',_MS_GS_NEWFRIEND);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_confirm',_MS_GS_CONFIRMFRIEND);
	$tpl->assign('lang_confirms',_MS_GS_CONFIRMFRIENDS);
	$tpl->assign('max_cols',$cols);

	$xmh.= '<link rel="stylesheet" type="text/css" media="screen" href="'.GS_URL.'/styles/panel.css" />';
	
	createLinks();
	include 'footer.php';
}


/**
* @desc Adiciona amigos
**/
function addFriends(){

	global $add, $exmUser, $xoopsModuleConfig, $db;
		
	$mc =& $xoopsModuleConfig;

	$id = isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
	$pag = isset($_REQUEST['pag']) ? intval($_REQUEST['pag']) : '';

		
	$link = XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? 'cpanel/friends/pag/'.$pag : 'cpanel.php?s=cpanel/friends/pag/'.$pag);

	//Verificamos que exista el usuario
	$add = $add ? $add : $id;
	$exu = new ExmUser($add);
	
	if($exu->isNew()){
		redirect_header($link, 1 , _MS_GS_ERRUSEREXIST);
		die();
	}

	//Verificamos que el usuario no se encuentre registrado
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_friends')." WHERE gsuser='".$exmUser->uid()."' AND uid='".$exu->uid()."'";
	list($num) = $db->fetchRow($db->query($sql));
	if ($num>0){
		redirect_header($link,1,_MS_GS_FRIENDEXIST);
		die();
	}

	$sql = "INSERT INTO ".$db->prefix('gs_friends')." (`gsuser`,`uid`) VALUES ('".$exmUser->uid()."','".$exu->uid()."')";
	$result = $db->queryF($sql);
	
	if (!$result){
		redirect_header($link,1,_MS_GS_DBERROR);
		die();
	}else{
		redirect_header($link,1,_MS_GS_DBOK);
		die();
	}
	
}


/**
* @desc Elimina de la base de datos los amigos especificados
**/
function deleteFriends(){

	global $xoopsModuleConfig, $db,$exmUser;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$pag = isset($_REQUEST['pag']) ? intval($_REQUEST['pag']) : '';

	$mc =& $xoopsModuleConfig;
	

	$link = XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? 'cpanel/friends/pag/'.$pag : 'cpanel.php?s=cpanel/friends/pag/'.$pag);

	//Verificamos si nos proporcionaron al menos un amigo para eliminar
	if (!is_array($ids) && $ids<=0){
		redirect_header($link,2,_MS_GS_ERRFRIENDDEL);
		die();
	}

	if (!is_array($ids)){
		$ids = array($ids);
	}
	
	
	$errors = '';
	foreach ($ids as $k){

		//Verificamos si el amigo es válido
		if($k<=0){
			$errors .= sprintf(_MS_GS_ERRNOTVALIDFRIEND, $k);
			continue;			
		}

		//Verificamos si el amigo existe
		$exu = new ExmUser($k);
		if ($exu->isNew()){
			$errors .= sprintf(_MS_GS_ERRNOTEXISTFRIEND, $k);
			continue;
		}	

		//Verificamos si se trata de un amigo
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_friends')." WHERE uid='".$k."' AND gsuser='".$exmUser->uid()."'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num<=0){
			continue;
		}

		$sql = "DELETE FROM ".$db->prefix('gs_friends')." WHERE uid='".$k."' AND gsuser='".$exmUser->uid()."'";
		$result = $db->queryF($sql);

		if(!$result){
			$errors .= sprintf(_MS_GS_ERRDELFRIEND, $k);
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
