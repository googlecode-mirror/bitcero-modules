<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION', 'postcards');
include 'header.php';


/**
* @desc Visualiza todas las postales existentes
**/
function showPostCards(){
	global $xoopsModule, $tpl, $xoopsModuleConfig;

	$mc =& $xoopsModuleConfig;

	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$limit = $limit<=0 ? 15 : $limit;
    
    $db = Database::getInstance();
	//Barra de Navegación
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('gs_postcards');
	
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
    	    $nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit, 0);
    	    $tpl->assign('postNavPage', $nav->renderNav(4, 1));
    	}

	$showmax = $start + $limit;
	$showmax = $showmax > $num ? $num : $showmax;
	$tpl->assign('lang_showing', sprintf(_AS_GS_SHOWING, $start + 1, $showmax, $num));
	$tpl->assign('limit',$limit);
	$tpl->assign('pag',$pactual);
	//Fin de barra de navegación


	
	$sql = "SELECT * FROM ".$db->prefix('gs_postcards');
	$sql.= " LIMIT $start,$limit";
	$result = $db->query($sql);
	while ($rows = $db->fetchArray($result)){
		$post = new GSPostcard();
		$post->assignVars($rows);
		
		$link = XOOPS_URL.'/modules/galleries/'.($mc['urlmode'] ? '' : 'postcard.php?id=').'postcard/view/id/'.$post->code();
	
		$tpl->append('posts',array('id'=>$post->id(),'title'=>$post->title(),'date'=>formatTimeStamp($post->date(),'string'),
		'toname'=>$post->toName(),'name'=>$post->email(),'view'=>$post->viewed(),'link'=>$link));	
	
	}
	
	$tpl->assign('lang_exist',_AS_GS_EXIST);
	$tpl->assign('lang_id',_AS_GS_ID);
	$tpl->assign('lang_title',_AS_GS_TITLE);
	$tpl->assign('lang_date',_AS_GS_DATE);
	$tpl->assign('lang_remit',_AS_GS_REMIT);
	$tpl->assign('lang_view',_AS_GS_VIEW);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_submit',_SUBMIT);
	
    GSFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_GS_POSTCARDS);
	xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/gs_postcards.php','module','galleries');
    
	xoops_cp_footer();

}


/**
* @desc Elimina de la base de datos las postales especificadas
**/
function deletePostCards(){

	global $util, $xoopsModule;

	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
  	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	
	//Verificamos si nos proporcionaron al menos una postal para eliminar
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./postcards.php?pag='.$page.'&limit='.$limit,_AS_GS_ERRPOSTDEL,1);
		die();
	}

	if (!is_array($ids)){
		$pst = new GSPostcard($ids);
		$ids = array($ids);
	}

	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./postcards.php?pag='.$page.'&limit='.$limit,_AS_GS_SESSINVALID,1);
			die();
		}

		$errors = '';
		foreach ($ids as $k){
			
			//Verificamos si la postal sea válida
			if($k<=0){
				$errors .= sprintf(_AS_GS_ERRNOTVALID, $k);
				continue;			
			}

			//Verificamos si la postal exista
			$post = new GSPostcard($k);
			if ($post->isNew()){
				$errors .= sprintf(_AS_GS_ERRNOTEXIST, $k);
				continue;
			}	

			if(!$post->delete()){
				$errors .= sprintf(_AS_GS_ERRDELETE, $k);
			}
		}

		if($errors!=''){
			redirectMsg('./postcards.php?pag='.$page.'&limit='.$limit,_AS_GS_DBERRORS.$errors,1);
			die();
		}else{
			redirectMsg('./postcards.php?pag='.$page.'&limit='.$limit,_AS_GS_DBOK,0);
			die();
		}
		
	}else{
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./postcards.php'>"._AS_GS_POSTCARDS."</a> &raquo; "._AS_GS_LOCDELETE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['ids[]'] = $ids;
		$hiddens['op'] = 'delete';
		$hiddens['limit'] = $limit;
		$hiddens['pag'] = $page;
		
		
		
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'postcards.php?pag='.$page.'&limit='.$limit.'\';"';
		
		$util->msgBox($hiddens, 'postcards.php',(isset($pst) ? sprintf(_AS_GS_DELETECONF, $pst->title()) : _AS_GS_DELETECONFS). '<br /><br />' ._AS_GS_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}
}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'delete':
		deletePostCards();
	break;
	default:
		showPostCards();
		break;
}

?>
