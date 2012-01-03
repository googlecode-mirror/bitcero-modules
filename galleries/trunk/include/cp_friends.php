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
* @desc Visualiza la lista de amigos del usuario
**/
function showFriends(){

	global $xoopsOption, $tpl, $db, $xoopsUser, $xoopsModuleConfig,$pag, $xoopsConfig;
	
	$xoopsOption['template_main'] = 'gs_panel_friends.html';
	include 'header.php';

	$mc =& $xoopsModuleConfig;

	GSFunctions::makeHeader();

	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_friends')." WHERE gsuser='".$xoopsUser->uid()."'";

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
	$tpl->assign('lang_showing', sprintf(__('Sowing friends %u to %u from %u.','galleries'), $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);	
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación


	$sql = "SELECT * FROM ".$db->prefix('gs_friends')." WHERE gsuser='".$xoopsUser->uid()."'";
	$sql.=" LIMIT $start,$limit";
	$result = $db->query($sql);
	while($row = $db->fetchArray($result)){
		$xu = new XoopsUser($row['uid']);
		
		$tpl->append('users',array(
            'uid'=>$xu->uid(),
            'uname'=>$xu->uname(),
		    'link'=>XOOPS_URL."/modules/galleries/".($mc['urlmode'] ? "usr/".$xu->uname()."/" : "user.php?id=usr/".$xu->uname()),
		    'avatar'=>RMEvents::get()->run_event('rmcommon.get.avatar', $xu->email(), 0, $xu->user_avatar()!='' ? XOOPS_URL.'/uploads/avatars/'.$xu->user_avatar() : GS_URL.'/images/avatar.png'))
        );
	}

	$tpl->assign('lang_uname', __('User name','galleries'));
	$tpl->assign('lang_newfriend',__('New Friend','galleries'));
	$tpl->assign('lang_del', __('Delete','galleries'));
	$tpl->assign('lang_confirm',__('Do you really wish to delete specified friend?','galleries'));
	$tpl->assign('lang_confirms',__('Do you really wish to delete selected friends?','galleries'));
    $tpl->assign('form_action_add', GSFunctions::get_url().($mc['urlmode'] ? 'cp/add/' : '?cp=add'));
    $tpl->assign('form_action_del', GSFunctions::get_url().($mc['urlmode'] ? 'cp/delete/' : '?cp=delete'));
    $tpl->assign('delete_link', GSFunctions::get_url().($mc['urlmode'] ? 'cp/deletefriend/pag/'.$pactual.'/id/' : '?cp=deletefriend&amp;page='.$pactual.'&amp;id='));

	RMTemplate::get()->add_style('panel.css','galleries');
	
	createLinks();
	include 'footer.php';
}


/**
* @desc Adiciona amigos
**/
function addFriends(){

	global $add, $xoopsUser, $xoopsModuleConfig, $db;
		
	$mc =& $xoopsModuleConfig;

	$id = isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
	$pag = isset($_REQUEST['pag']) ? intval($_REQUEST['pag']) : '';

		
	$link = GSFunctions::get_url().($mc['urlmode'] ? 'cp/friends/pag/'.$pag.'/' : '?cp=friends&amp;pag='.$pag);

	//Verificamos que exista el usuario
	$add = $add ? $add : $id;
	$exu = GSFunctions::get_xoops_user($add);
	
	if(!$exu){
		redirect_header($link, 1 , sprintf(__('User "%s" could not be found!','galleries'), $add));
		die();
	}
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();

	//Verificamos que el usuario no se encuentre registrado
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_friends')." WHERE gsuser='".$xoopsUser->uid()."' AND uid='".$exu->uid()."'";
	list($num) = $db->fetchRow($db->query($sql));
	if ($num>0){
		redirect_header($link,1, sprintf(__('%s is your friend already!','galleries'), $exu->uname()));
		die();
	}

	$sql = "INSERT INTO ".$db->prefix('gs_friends')." (`gsuser`,`uid`) VALUES ('".$xoopsUser->uid()."','".$exu->uid()."')";
	$result = $db->queryF($sql);
	
	if (!$result){
		redirect_header($link,1, sprintf(__('User %s could not be added as a friend!','galleries'), $exu->uname()));
		die();
	}else{
		redirect_header($link,1, sprintf(__('User %s has been added to your friends list!','galleries'), $exu->uname()));
		die();
	}
	
}


/**
* @desc Elimina de la base de datos los amigos especificados
**/
function deleteFriends(){

	global $xoopsModuleConfig, $db,$xoopsUser, $page, $id;

	$mc =& $xoopsModuleConfig;
	
	$link = GSFunctions::get_url().($mc['urlmode'] ? 'cp/friends/pag/'.$page.'/' : 'cp=friends&amp;pag='.$page);

	//Verificamos si nos proporcionaron al menos un amigo para eliminar
	if ($id<=0){
		redirect_header($link,2, __('Specified id for friend is not valid!','galleries'));
		die();
	}
	
	//Verificamos si el amigo existe
	$exu = new XoopsUser($id);
	if ($exu->isNew()){
	    redirect_header($link,2, __('Specified user does not exists!','galleries'));
        die();
	}
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();

	//Verificamos si se trata de un amigo
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_friends')." WHERE uid='".$id."' AND gsuser='".$xoopsUser->uid()."'";
	list($num) = $db->fetchRow($db->query($sql));
	if ($num<=0){
	    redirect_header($link,2, __('Specified user is not yoru friend!','galleries'));
        die();
	}

	$sql = "DELETE FROM ".$db->prefix('gs_friends')." WHERE uid='".$id."' AND gsuser='".$xoopsUser->uid()."'";
	$result = $db->queryF($sql);

	if(!$result){
	    redirect_header($link,2, __('Friend could not be deleted!','galleries'));
        die();
	} else {
        redirect_header($link,1,__('Friend deleted successfully!','galleries'));
        die();
    }
	
}
